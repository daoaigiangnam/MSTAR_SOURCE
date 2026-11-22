"""yt-dlp download worker helpers with post-download validation."""

import json
import os
import shutil
import subprocess
from pathlib import Path
from typing import Callable

from .progress import parse_progress


class DownloadError(RuntimeError):
    pass


class DownloadValidationError(DownloadError):
    pass


_PARTIAL_SUFFIXES = (".part", ".ytdl", ".tmp", ".temp")
_VIDEO_EXTENSIONS = {
    ".mp4",
    ".mkv",
    ".webm",
    ".mov",
    ".avi",
    ".flv",
    ".m4v",
    ".ts",
    ".mts",
    ".m2ts",
}


def _run_download(
    url: str,
    output_dir: str,
    format_selector: str,
    progress_callback: Callable[[dict], None] | None,
    extractor_args: str | None = None,
) -> tuple[int, list[str]]:
    output_template = os.path.join(output_dir, "%(id)s.%(ext)s")
    command = [
        "yt-dlp",
        "--no-playlist",
        "--newline",
        "--print-json",
        "-f",
        format_selector,
        "-o",
        output_template,
    ]
    if extractor_args:
        command.extend(["--extractor-args", extractor_args])
    command.append(url)

    process = subprocess.Popen(
        command,
        stdout=subprocess.PIPE,
        stderr=subprocess.STDOUT,
        text=True,
        bufsize=1,
    )
    lines: list[str] = []
    assert process.stdout is not None
    for line in process.stdout:
        clean = line.strip()
        lines.append(clean)
        progress = parse_progress(line)
        if progress and progress_callback:
            progress_callback(progress)

    return process.wait(), lines


def _metadata(lines: list[str]) -> dict:
    json_lines = [line for line in lines if line.startswith("{") and line.endswith("}")]
    if not json_lines:
        raise DownloadError("yt-dlp returned no metadata")
    try:
        return json.loads(json_lines[-1])
    except json.JSONDecodeError as exc:
        raise DownloadError("Unable to parse yt-dlp output") from exc


def _partial_files(output_dir: str) -> list[Path]:
    root = Path(output_dir)
    if not root.exists():
        return []
    return sorted(
        path
        for path in root.rglob("*")
        if path.is_file() and path.suffix.lower() in _PARTIAL_SUFFIXES
    )


def _output_file(output_dir: str, info: dict) -> Path:
    """Resolve the actual post-processed media file.

    yt-dlp can change the final extension/name after merging formats, so do not
    assume the output template is the final filename. Prefer metadata paths and
    fall back to the largest video file in the job directory.
    """
    candidates: list[Path] = []

    for key in ("filepath", "_filename", "filename"):
        value = info.get(key)
        if value:
            candidates.append(Path(str(value)))

    for item in info.get("requested_downloads") or []:
        if not isinstance(item, dict):
            continue
        for key in ("filepath", "_filename", "filename"):
            value = item.get(key)
            if value:
                candidates.append(Path(str(value)))

    root = Path(output_dir).resolve()
    for candidate in candidates:
        if not candidate.is_absolute():
            candidate = root / candidate
        candidate = candidate.resolve()
        try:
            candidate.relative_to(root)
        except ValueError:
            continue
        if candidate.is_file() and candidate.suffix.lower() in _VIDEO_EXTENSIONS:
            return candidate

    files = [
        path
        for path in root.rglob("*")
        if path.is_file()
        and path.suffix.lower() in _VIDEO_EXTENSIONS
        and path.suffix.lower() not in _PARTIAL_SUFFIXES
    ]
    if not files:
        raise DownloadValidationError("Download completed but no video output file was found")
    return max(files, key=lambda path: path.stat().st_size)


def _probe_media(path: Path) -> dict:
    command = [
        "ffprobe",
        "-v",
        "error",
        "-show_entries",
        "format=duration:stream=codec_type",
        "-of",
        "json",
        str(path),
    ]
    result = subprocess.run(
        command,
        capture_output=True,
        text=True,
        check=False,
    )
    if result.returncode != 0:
        detail = (result.stderr or "").strip()
        raise DownloadValidationError(
            f"ffprobe could not read downloaded media{': ' + detail if detail else ''}"
        )

    try:
        data = json.loads(result.stdout or "{}")
    except json.JSONDecodeError as exc:
        raise DownloadValidationError("ffprobe returned invalid JSON") from exc

    duration_value = (data.get("format") or {}).get("duration")
    try:
        duration = float(duration_value) if duration_value is not None else None
    except (TypeError, ValueError):
        duration = None

    streams = data.get("streams") or []
    stream_types = {stream.get("codec_type") for stream in streams if stream.get("codec_type")}
    return {
        "duration": duration,
        "streams": sorted(stream_types),
    }


def _validate_download(output_dir: str, info: dict) -> tuple[Path, dict]:
    """Validate the completed download without using a fixed filesize threshold."""
    partials = _partial_files(output_dir)
    if partials:
        names = [str(path.relative_to(Path(output_dir))) for path in partials]
        raise DownloadValidationError(
            "Incomplete download detected; temporary files remain: " + ", ".join(names)
        )

    output_file = _output_file(output_dir, info)
    if not output_file.is_file():
        raise DownloadValidationError("Downloaded media file does not exist")

    filesize = output_file.stat().st_size
    if filesize <= 0:
        raise DownloadValidationError("Downloaded media file is empty")

    probe = _probe_media(output_file)
    streams = set(probe["streams"])
    if "video" not in streams:
        raise DownloadValidationError("Downloaded file contains no video stream")
    if "audio" not in streams:
        raise DownloadValidationError("Downloaded file contains no audio stream")

    expected_duration = info.get("duration")
    try:
        expected_duration = float(expected_duration) if expected_duration is not None else None
    except (TypeError, ValueError):
        expected_duration = None

    actual_duration = probe.get("duration")
    duration_delta = None
    if expected_duration and actual_duration:
        duration_delta = abs(actual_duration - expected_duration)
        # Allow small container/encoder rounding differences, but catch a
        # truncated file such as the previous ~629s vs ~802s failure.
        tolerance = max(2.0, min(5.0, expected_duration * 0.01))
        if duration_delta > tolerance:
            raise DownloadValidationError(
                "Downloaded media duration mismatch: "
                f"expected {expected_duration:.3f}s, got {actual_duration:.3f}s "
                f"(delta {duration_delta:.3f}s, tolerance {tolerance:.3f}s)"
            )

    validation = {
        "valid": True,
        "filepath": str(output_file),
        "filesize": filesize,
        "duration": actual_duration,
        "expected_duration": expected_duration,
        "duration_delta": duration_delta,
        "streams": sorted(streams),
        "partial_files": [],
    }
    return output_file, validation


def _cleanup_failed_download(output_dir: str) -> None:
    """Remove failed-attempt artifacts before trying the fallback client."""
    root = Path(output_dir)
    if not root.exists():
        return
    for child in root.iterdir():
        if child.is_dir():
            shutil.rmtree(child, ignore_errors=True)
        else:
            try:
                child.unlink()
            except FileNotFoundError:
                pass


def _attempt(
    url: str,
    output_dir: str,
    selector: str,
    progress_callback: Callable[[dict], None] | None,
    extractor_args: str | None = None,
) -> dict:
    return_code, lines = _run_download(
        url,
        output_dir,
        selector,
        progress_callback,
        extractor_args=extractor_args,
    )
    if return_code != 0:
        raise DownloadError(
            "yt-dlp download failed"
            + (f" (client={extractor_args})" if extractor_args else "")
        )

    info = _metadata(lines)
    output_file, validation = _validate_download(output_dir, info)
    info["filepath"] = str(output_file)
    info["download_validation"] = validation
    return info


def download_video(
    url: str,
    output_dir: str,
    format_selector: str | None = None,
    progress_callback: Callable[[dict], None] | None = None,
) -> dict:
    """Download and validate media with Android fallback.

    Normal yt-dlp remains first priority. The YouTube Android player client is
    attempted only when the normal attempt fails at the process or validation
    stage. A download is not handed to the dubbing pipeline until validation
    confirms a readable video+audio file with a sane duration when metadata is
    available.
    """
    Path(output_dir).mkdir(parents=True, exist_ok=True)
    selector = format_selector or "bv*+ba/b"

    try:
        info = _attempt(url, output_dir, selector, progress_callback)
        info["download_client"] = "default"
        return info
    except DownloadError as normal_error:
        normal_message = str(normal_error)

    # Normal yt-dlp failed or produced an invalid/incomplete media file.
    _cleanup_failed_download(output_dir)

    try:
        info = _attempt(
            url,
            output_dir,
            selector,
            progress_callback,
            extractor_args="youtube:player_client=android",
        )
        info["download_client"] = "android"
        info["fallback_reason"] = normal_message
        return info
    except DownloadError as android_error:
        raise DownloadError(
            "yt-dlp download failed after Android client fallback. "
            f"Normal attempt: {normal_message}. Android attempt: {android_error}"
        ) from android_error
