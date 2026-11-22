"""Translate transcript segments while preserving timing."""

from .language_utils import normalize_language, same_language, translation_language
from .translation_edge import translate_segments


def translate_transcript(
    transcript: dict,
    source: str | None = None,
    target: str | None = None,
    endpoint: str | None = None,
    *,
    source_language: str | None = None,
    target_language: str | None = None,
) -> dict:
    source = normalize_language(source_language or source)
    target = normalize_language(target_language or target)
    if not source or not target:
        raise ValueError("source and target languages are required")

    if same_language(source, target):
        # Flag=2 intentionally keeps a subtitle output even when source and
        # target are the same language. Do not call LibreTranslate in that case.
        translated = []
        for segment in transcript.get("segments", []):
            item = dict(segment)
            item["target_text"] = str(segment.get("text", ""))
            translated.append(item)
        return {
            "source_language": source,
            "target_language": target,
            "language": target,
            "segments": translated,
        }

    provider_source = translation_language(source)
    provider_target = translation_language(target)
    segments = transcript.get("segments", [])
    translations = translate_segments(segments, provider_source, provider_target, endpoint)
    translated = []
    for index, segment in enumerate(segments):
        item = dict(segment)
        item["target_text"] = translations[index] if index < len(translations) else ""
        translated.append(item)

    return {
        "source_language": source,
        "target_language": target,
        "language": target,
        "segments": translated,
    }
