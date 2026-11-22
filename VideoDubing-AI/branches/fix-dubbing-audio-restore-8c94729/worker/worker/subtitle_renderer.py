"""Burn subtitles into video while preserving the original audio stream."""

from pathlib import Path

import ffmpeg


class SubtitleRenderError(RuntimeError):
    pass


def render_subtitled(
    video_path: str,
    subtitle_path: str,
    output_path: str,
    audio_path: str | None = None,
) -> dict:
    """Burn subtitles and explicitly preserve the original audio.

    extract_media() creates a video-only file for video_path and stores the
    original audio separately. Therefore audio must be supplied explicitly
    when rendering the subtitle output.
    """
    video = Path(video_path)
    subtitle = Path(subtitle_path)
    output = Path(output_path)

    if not video.is_file():
        raise SubtitleRenderError(f"Video file not found: {video_path}")
    if not subtitle.is_file():
        raise SubtitleRenderError(f"Subtitle file not found: {subtitle_path}")
    if audio_path is not None and not Path(audio_path).is_file():
        raise SubtitleRenderError(f"Audio file not found: {audio_path}")

    output.parent.mkdir(parents=True, exist_ok=True)
    subtitle_filter_path = str(subtitle).replace("\\", "/").replace(":", r"\:")

    try:
        video_input = ffmpeg.input(str(video))
        if audio_path:
            audio_input = ffmpeg.input(str(audio_path))
            stream = ffmpeg.output(
                video_input.video,
                audio_input.audio,
                str(output),
                vf=f"subtitles='{subtitle_filter_path}'",
                map_metadata=0,
                acodec="copy",
                vcodec="libx264",
                preset="medium",
                crf=18,
                movflags="+faststart",
                shortest=None,
            )
        else:
            stream = ffmpeg.output(
                video_input.video,
                video_input.audio,
                str(output),
                vf=f"subtitles='{subtitle_filter_path}'",
                map_metadata=0,
                acodec="copy",
                vcodec="libx264",
                preset="medium",
                crf=18,
                movflags="+faststart",
            )

        stream.overwrite_output().run(capture_stdout=True, capture_stderr=True)
    except ffmpeg.Error as exc:
        stderr = exc.stderr.decode("utf-8", errors="replace") if exc.stderr else str(exc)
        raise SubtitleRenderError(f"FFmpeg subtitle render failed: {stderr[-4000:]}") from exc

    if not output.is_file() or output.stat().st_size <= 0:
        raise SubtitleRenderError("Subtitle render completed without a valid output file")

    return {
        "output_path": str(output),
        "filesize": output.stat().st_size,
        "audio_preserved": bool(audio_path),
    }
