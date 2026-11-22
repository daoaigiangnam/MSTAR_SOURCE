"""Translation service abstraction for subtitle dubbing."""

from dataclasses import dataclass


@dataclass
class TranslationResult:
    source_language: str | None
    target_language: str
    segments: list[dict]


def translate_segments(segments: list[dict], target_language: str,
                       source_language: str | None = None) -> TranslationResult:
    """Return translated segment structure.

    The provider is intentionally separated from the pipeline. Until an external
    translation provider is configured, text is preserved so timing data remains
    intact and downstream TTS can be tested safely.
    """
    if not target_language:
        raise ValueError("target_language is required")

    translated = []
    for segment in segments:
        item = dict(segment)
        item["source_text"] = segment.get("text", "")
        item["text"] = segment.get("text", "")
        item["target_language"] = target_language
        translated.append(item)

    return TranslationResult(source_language, target_language, translated)
