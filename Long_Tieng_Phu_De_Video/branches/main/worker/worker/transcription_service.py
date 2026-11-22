"""End-to-end speech transcription and subtitle generation."""

from pathlib import Path

from .subtitles import write_srt
from .transcriber import transcribe


def create_transcript(audio_path: str, output_dir: str, model_size: str = "small",
                      language: str | None = None) -> dict:
    result = transcribe(audio_path, model_size=model_size, language=language)
    directory = Path(output_dir)
    directory.mkdir(parents=True, exist_ok=True)
    srt_path = write_srt(result["segments"], str(directory / "subtitles.srt"))
    return {
        "language": result["language"],
        "language_probability": result["language_probability"],
        "segments": result["segments"],
        "srt_path": srt_path,
    }
