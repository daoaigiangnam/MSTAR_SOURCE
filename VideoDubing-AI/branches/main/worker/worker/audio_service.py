"""Audio preparation pipeline for dubbing."""

from pathlib import Path

from .audio_separation import separate_audio


def prepare_audio(speech_path: str, output_dir: str) -> dict:
    result = separate_audio(speech_path, str(Path(output_dir) / "separated"))
    return {
        "speech_path": result["vocals_path"],
        "background_path": result["background_path"],
    }
