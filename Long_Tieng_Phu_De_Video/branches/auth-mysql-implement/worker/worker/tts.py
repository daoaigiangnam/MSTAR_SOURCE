"""Text-to-speech provider abstraction."""

from pathlib import Path


class TTSNotConfigured(RuntimeError):
    pass


def synthesize(text: str, output_file: str, language: str, voice: str | None = None) -> dict:
    """Create a TTS request descriptor until a concrete provider is configured."""
    if not text.strip():
        raise ValueError("text is required")
    if not language:
        raise ValueError("language is required")

    path = Path(output_file)
    path.parent.mkdir(parents=True, exist_ok=True)
    return {
        "status": "pending_provider",
        "audio_path": str(path),
        "language": language,
        "voice": voice,
        "text": text,
    }
