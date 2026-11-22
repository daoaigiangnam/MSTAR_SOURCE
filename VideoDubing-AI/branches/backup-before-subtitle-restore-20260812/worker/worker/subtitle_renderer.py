"""Burn subtitles into video and restore the extracted original audio."""

from __future__ import annotations

import json
import os
import selectors
import subprocess
import time
from pathlib import Path
from typing import Callable


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
    if abs(duration - expected_duration) > max(5.0, expected_duration * 0.03):
        raise SubtitleRenderError(
            f"Rendered duration mismatch: expected {expected_duration:.3f}s, got {duration:.3f}s"
        )

    video_streams = [item for item in streams if item.get("codec_type") == "video"]
    audio_streams = [item for item in streams if item.get("codec_type") == "audio"]

    if not video_streams:
        raise SubtitleRenderError("Rendered MP4 has no video stream")
    if not audio_streams:
        raise SubtitleRenderError("Rendered MP4 has no audio stream")

    video_codec = video_streams[0].get("codec_name")
    audio_codec = audio_streams[0].get("codec_name")

    if video_codec != "h264":
        raise SubtitleRenderError(
            f"Rendered MP4 has unexpected video codec: {video_codec}"
        )
    if audio_codec != "aac":
        raise SubtitleRenderError(
            f"Rendered MP4 has unexpected audio codec: {audio_codec}"
        )


def _report_render_progress(
    callback: Callable[[dict], None] | None,
    duration: float,
    out_time_ms: int | None,
    speed: str | None = None,
) -> None:
    """Map FFmpeg's 0-100% progress into the subtitle-render range 85-99%."""
    if not callback or out_time_ms is None or duration <= 0:
        return

    rendered_seconds = max(0.0, out_time_ms / 1_000_000.0)
    fraction = min(1.0, rendered_seconds / duration)
    progress = 85.0 + (fraction * 14.0)
    payload = {
        "progress": round(min(99.0, progress), 2),
        "status": "subtitle_rendering",
        "mode": "subtitle",
    }
    if speed:
        payload["speed"] = speed
    callback(payload)


def render_subtitled(
    video_path: str,
    subtitle_path: str,
    output_path: str,
    audio_path: str,
    progress_callback: Callable[[dict], None] | None = None,
) -> dict:
    """Burn subtitles into video and mux the extracted original audio.

    ``media.extract_media()`` deliberately creates a video-only file. Therefore
    the renderer must not look for an audio stream inside ``video_path``.
    Instead, the extracted WAV is supplied separately and encoded as AAC for
    MP4 compatibility.

    FFmpeg progress is streamed through ``-progress pipe:1`` so callers do not
    remain stuck at 85% while a long video is being encoded. The final MP4 is
    written to a temporary file and atomically promoted only after validation.
    """
    video = Path(video_path)
    subtitle = Path(subtitle_path)
    output = Path(output_path)
    audio = Path(audio_path)

    if not video.is_file():
        raise SubtitleRenderError(f"Video file not found: {video_path}")
    if not subtitle.is_file():
        raise SubtitleRenderError(f"Subtitle file not found: {subtitle_path}")
    if not audio.is_file():
        raise SubtitleRenderError(f"Audio file not found: {audio_path}")

    output.parent.mkdir(parents=True, exist_ok=True)
    temporary = output.with_name(f".{output.stem}.rendering{output.suffix}")

    duration = _probe_duration(video)
    crf = _env_int("SUBTITLE_RENDER_CRF", 24, minimum=0)
    preset = os.getenv("SUBTITLE_RENDER_PRESET", "veryfast").strip() or "veryfast"
    timeout_seconds = _env_int("SUBTITLE_RENDER_TIMEOUT_SECONDS", 7200)

    # The SRT is generated internally by this worker and is trusted local data.
    subtitle_filter_path = str(subtitle).replace("\\", "/").replace(":", r"\:")

    command = [
        "ffmpeg",
        "-hide_banner",
        "-loglevel", "error",
        "-nostats",
        "-y",
        "-i", str(video),
        "-i", str(audio),
        "-vf", f"subtitles='{subtitle_filter_path}'",
        "-map", "0:v:0",
        "-map", "1:a:0",
        "-c:v", "libx264",
        "-preset", preset,
        "-crf", str(crf),
        "-pix_fmt", "yuv420p",
        "-threads", "0",
        "-c:a", "aac",
        "-b:a", "128k",
        "-ar", "48000",
        "-shortest",
        "-movflags", "+faststart",
        "-progress", "pipe:1",
        str(temporary),
    ]

    if temporary.exists():
        temporary.unlink()
    if output.exists():
        output.unlink()

    process: subprocess.Popen[str] | None = None
    selector: selectors.BaseSelector | None = None
    stderr_tail: list[str] = []

    try:
        process = subprocess.Popen(
            command,
            stdout=subprocess.PIPE,
            stderr=subprocess.PIPE,
            text=True,
            bufsize=1,
        )
        if process.stdout is None or process.stderr is None:
            raise SubtitleRenderError("Unable to capture FFmpeg progress output")

        selector = selectors.DefaultSelector()
        selector.register(process.stdout, selectors.EVENT_READ, "progress")
        selector.register(process.stderr, selectors.EVENT_READ, "stderr")

        progress_data: dict[str, str] = {}
        start_time = time.monotonic()

        while selector.get_map():
            if time.monotonic() - start_time > timeout_seconds:
                process.kill()
                process.wait(timeout=10)
                raise SubtitleRenderError(
                    f"Subtitle render timed out after {timeout_seconds}s"
                )

            events = selector.select(timeout=0.5)
            for key, _ in events:
                stream = key.fileobj
                line = stream.readline()
                if line == "":
                    try:
                        selector.unregister(stream)
                    except Exception:
                        pass
                    continue

                line = line.strip()
                if not line:
                    continue

                if key.data == "progress":
                    if "=" in line:
                        name, value = line.split("=", 1)
                        progress_data[name] = value
                        if name == "out_time_ms":
                            try:
                                out_time_ms = int(value)
                            except ValueError:
                                out_time_ms = None
                            _report_render_progress(
                                progress_callback,
                                duration,
                                out_time_ms,
                                progress_data.get("speed"),
                            )
                else:
                    stderr_tail.append(line)
                    if len(stderr_tail) > 40:
                        stderr_tail.pop(0)

        return_code = process.wait(timeout=10)
        if return_code != 0:
            detail = "\n".join(stderr_tail[-20:]) or "unknown FFmpeg error"
            raise SubtitleRenderError(
                f"FFmpeg subtitle render failed (exit {return_code}): {detail}"
            )

        _validate_output(temporary, duration)
        temporary.replace(output)
        _validate_output(output, duration)

        if progress_callback:
            progress_callback({
                "progress": 100,
                "status": "completed",
                "mode": "subtitle",
                "filesize": output.stat().st_size,
            })

        return {
            "output_path": str(output),
            "filesize": output.stat().st_size,
        }
    except SubtitleRenderError:
        raise
    except (OSError, subprocess.SubprocessError) as exc:
        raise SubtitleRenderError(f"Subtitle render process failed: {exc}") from exc
    finally:
        if selector is not None:
            try:
                selector.close()
            except Exception:
                pass
        if process is not None and process.poll() is None:
            process.kill()
            try:
                process.wait(timeout=10)
            except Exception:
                pass
        if temporary.exists():
            try:
                temporary.unlink()
            except OSError:
                pass
