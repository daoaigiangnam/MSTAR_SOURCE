"""Worker runtime configuration."""

import os


API_URL = os.getenv("API_URL", "http://api:3000")
STORAGE_PATH = os.getenv("STORAGE_PATH", "./storage")
TRANSLATION_ENDPOINT = os.getenv("TRANSLATION_ENDPOINT", "http://libretranslate:5000")
TTS_VOICE = os.getenv("TTS_VOICE", "")

# Default Edge TTS voices used when the caller does not explicitly select a voice.
# Add new languages only when the selected provider has a verified voice for them.
DEFAULT_TTS_VOICES = {
    "en": "en-US-AriaNeural",
    "vi": "vi-VN-HoaiMyNeural",
}


def default_tts_voice(language: str | None) -> str:
    """Return a voice matching the requested target language.

    Never silently fall back to an English voice for another target language.
    Unsupported target languages must provide an explicit compatible voice.
    """
    normalized = (language or "").lower().replace("_", "-").split("-")[0]
    if not normalized:
        raise ValueError("target language is required before selecting a TTS voice")

    voice = DEFAULT_TTS_VOICES.get(normalized)
    if not voice:
        raise ValueError(
            f"No default TTS voice configured for target language: {normalized}. "
            "Select a voice explicitly from the target-language voice list."
        )
    return voice
