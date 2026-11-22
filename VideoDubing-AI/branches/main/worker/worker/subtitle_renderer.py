"""Burn subtitles into a video using the extracted original audio track."""

from __future__ import annotations

import json
import os
import subprocess
from pathlib import Path


class SubtitleRenderError(RuntimeError):
    """Raised when subtitle rendering cannot produce a valid MP4."""


def _env_int(name: str, default: int, minimum: int = 1) -> int:
    raw = os.getenv(name)
    if raw is None or not raw.strip():
        return default
    try:
        value = int(raw)
    except ValueError as exc:
        raise SubtitleRenderError(f"Invalid integer value for {name}: {raw}") from exc
    if value < minimum:
        raise SubtitleRenderError(f"{name} must be >= {minimum}")
    return value


def _probe_duration(video_path: Path) -> float:
    command = [
        "ffprobe",
        "-v", "error",
        "-show_entries", "format=duration",
        "-of", "json",
        str(video_path),
    ]
    try:
        completed = subprocess.run(
            command,
            check=True,
            capture_output=True,
            text=True,
            timeout=_env_int("SUBTITLE_RENDER_PROBE_TIMEOUT_SECONDS", 60),
        )
        data = json.loads(completed.stdout or "{}")
        duration = float(data.get("format", {}).get("duration") or 0)
    except (subprocess.SubprocessError, ValueError, json.JSONDecodeError) as exc:
        raise SubtitleRenderError(f"Unable to probe video duration: {exc}") from exc

    if duration <= 0:
        raise SubtitleRenderError("Video duration is invalid")
    return duration


def _validate_output(output: Path, expected_duration: float) -> None:
    if not output.is_file() or output.stat().st_size <= 0:
        raise SubtitleRenderError("Subtitle render completed without a valid output file")

    command = [
        "ffprobe",
        "-v", "error",
        "-show_entries", "format=duration,size",
        "-show_entries", "stream=codec_type,codec_name",
        "-of", "json",
        str(output),
    ]
    try:
        completed = subprocess.run(
            command,
            check=True,
            capture_output=True,
            text=True,
            timeout=_env_int("SUBTITLE_RENDER_PROBE_TIMEOUT_SECONDS", 60),
        )
        data = json.loads(completed.stdout or "{}")
        format_data = data.get("format") or {}
        duration = float(format_data.get("duration") or 0)
        size = int(float(format_data.get("size") or 0))
        streams = data.get("streams") or []
    except (subprocess.SubprocessError, ValueError, json.JSONDecodeError) as exc:
        raise SubtitleRenderError(f"Rendered MP4 validation failed: {exc}") from exc

    if duration <= 0:
        raise SubtitleRenderError("Rendered MP4 has no valid duration; output may be incomplete")
    if size <= 0:
        raise SubtitleRenderError("Rendered MP4 has invalid size")

    tolerance = max(5.0, expected_duration * 0.03)
    if abs(duration - expected_duration) > tolerance:
        raise SubtitleRenderError(
            f"Rendered duration mismatch: expected {expected_duration:.3f}s, got {duration:.3f}s"
        )

    codec_types = {str(item.get("codec_type")) for item in streams}
    if "video" not in codec_types:
        raise SubtitleRenderError("Rendered MP4 has no video stream")
    if "audio" not in codec_types:
        raise SubtitleRenderError("Rendered MP4 has no audio stream")


def render_subtitled(
    video_path: str,
    audio_path: str,
    subtitle_path: str,
    output_path: str,
) -> dict:
    """Burn subtitles into video and mux the separately extracted original audio.

    ``extract_media()`` intentionally creates ``video-only.mp4`` without an audio
    stream. Therefore the renderer MUST use ``audio_path`` as a second FFmpeg
    input instead of attempting to map audio from the video-only input.
    """
    video = Path(video_path)
    audio = Path(audio_path)
    subtitle = Path(subtitle_path)
    output = Path(output_path)

    if not video.is_file():
        raise SubtitleRenderError(f"Video file not found: {video_path}")
    if not audio.is_file():
        raise SubtitleRenderError(f"Audio file not found: {audio_path}")
    if not subtitle.is_file():
        raise SubtitleRenderError(f"Subtitle file not found: {subtitle_path}")

    output.parent.mkdir(parents=True, exist_ok=True)
    temporary = output.with_name(f".{output.stem}.rendering{output.suffix}")

    expected_duration = _probe_duration(video)
    preset = os.getenv("SUBTITLE_RENDER_PRESET", "veryfast").strip() or "veryfast"
    crf = _env_int("SUBTITLE_RENDER_CRF", 22, minimum=0)
    timeout_seconds = _env_int("SUBTITLE_RENDER_TIMEOUT_SECONDS", 7200)

    # The SRT is generated internally by this worker and is trusted local data.
    subtitle_filter_path = str(subtitle).replace("\\", "/").replace(":", r"\:")

    command = [
        "ffmpeg",
        "-hide_banner",
        "-loglevel", "error",
        "-y",
        "-i", str(video),
        "-i", str(audio),
        "-map", "0:v:0",
        "-map", "1:a:0",
        "-vf", f"subtitles='{subtitle_filter_path}'",
        "-c:v", "libx264",
        "-preset", preset,
        "-crf", str(crf),
        "-c:a", "aac",
        "-b:a", "192k",
        "-shortest",
        "-movflags", "+faststart",
        str(temporary),
    ]

    if temporary.exists():
        temporary.unlink()

    stderr_tail: list[str] = []

    try:
        process = subprocess.Popen(
            command,
            stdout=subprocess.DEVNULL,
            stderr=subprocess.PIPE,
            text=True,
            bufsize=1,
        )

        if process.stderr is not None:
            for line in process.stderr:
                line = line.rstrip()
                if line:
                    stderr_tail.append(line)
                    if len(stderr_tail) > 40:
                        stderr_tail.pop(0)

        try:
            return_code = process.wait(timeout=timeout_seconds)
        except subprocess.TimeoutExpired as exc:
            process.kill()
            process.wait(timeout=10)
            raise SubtitleRenderError(
                f"Subtitle render timed out after {timeout_seconds}s"
            ) from exc

        if return_code != 0:
            detail = "\n".join(stderr_tail[-20:]) or "unknown FFmpeg error"
            raise SubtitleRenderError(
                f"FFmpeg subtitle render failed (exit {return_code}): {detail}"
            )

        _validate_output(temporary, expected_duration)
        temporary.replace(output)
        _validate_output(output, expected_duration)

        return {
            "output_path": str(output),
            "filesize": output.stat().st_size,
        }
    except SubtitleRenderError:
        raise
    except (OSError, subprocess.SubprocessError) as exc:
        raise SubtitleRenderError(f"Subtitle render process failed: {exc}") from exc
    finally:
        if temporary.exists():
            try:
                temporary.unlink()
            except OSError:
                pass
