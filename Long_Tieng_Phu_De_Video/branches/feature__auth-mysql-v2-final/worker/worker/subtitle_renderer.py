"""Burn subtitles into video while preserving the original audio stream."""

from pathlib import Path
import subprocess

import ffmpeg

from .language_utils import language_family, normalize_language


class SubtitleRenderError(RuntimeError):
    pass


# Use script-aware Noto families instead of relying on libass/fontconfig's
# default DejaVu fallback. This prevents CJK and other non-Latin scripts from
# becoming square boxes while keeping one generic implementation for all
# supported languages.
_FONT_BY_LANGUAGE = {
    "zh": "Noto Sans CJK SC",
    "ja": "Noto Sans CJK JP",
    "ko": "Noto Sans CJK KR",
    "ar": "Noto Sans Arabic",
    "fa": "Noto Sans Arabic",
    "ur": "Noto Sans Arabic",
    "he": "Noto Sans Hebrew",
    "hi": "Noto Sans Devanagari",
    "mr": "Noto Sans Devanagari",
    "ne": "Noto Sans Devanagari",
    "bn": "Noto Sans Bengali",
    "gu": "Noto Sans Gujarati",
    "pa": "Noto Sans Gurmukhi",
    "kn": "Noto Sans Kannada",
    "ml": "Noto Sans Malayalam",
    "or": "Noto Sans Oriya",
    "si": "Noto Sans Sinhala",
    "ta": "Noto Sans Tamil",
    "te": "Noto Sans Telugu",
    "th": "Noto Sans Thai",
    "km": "Noto Sans Khmer",
    "lo": "Noto Sans Lao",
    "my": "Noto Sans Myanmar",
    "am": "Noto Sans Ethiopic",
    "hy": "Noto Sans Armenian",
    "ka": "Noto Sans Georgian",
    "el": "Noto Sans Greek",
}


def _font_for_language(language: str | None) -> str:
    normalized = normalize_language(language)
    family = language_family(normalized)
    if not family:
        return "Noto Sans"
    if family == "zh":
        lowered = (normalized or "").lower()
        if lowered in {"zh-hant", "zh-tw", "zh-hk", "zh-mo"}:
            return "Noto Sans CJK TC"
    return _FONT_BY_LANGUAGE.get(family, "Noto Sans")


def _font_exists(font_name: str) -> bool:
    try:
        result = subprocess.run(
            ["fc-match", "-f", "%{family}", font_name],
            capture_output=True,
            text=True,
            timeout=5,
            check=False,
        )
        family = result.stdout.strip().lower()
        return bool(family and font_name.lower() in family)
    except (OSError, subprocess.SubprocessError):
        return False


def render_subtitled(
    video_path: str,
    subtitle_path: str,
    output_path: str,
    audio_path: str | None = None,
    language: str | None = None,
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
    font_name = _font_for_language(language)
    if not _font_exists(font_name):
        font_name = "sans-serif"

    # libass accepts ASS-style force_style options on the subtitles filter.
    # FontName is intentionally the only style override so existing subtitle
    # sizing/positioning remains unchanged.
    subtitle_filter = (
        f"subtitles='{subtitle_filter_path}'"
        f":force_style='FontName={font_name}'"
    )

    try:
        video_input = ffmpeg.input(str(video))
        if audio_path:
            audio_input = ffmpeg.input(str(audio_path))
            stream = ffmpeg.output(
                video_input.video,
                audio_input.audio,
                str(output),
                vf=subtitle_filter,
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
                vf=subtitle_filter,
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
        "subtitle_language": normalize_language(language),
        "font_name": font_name,
    }
