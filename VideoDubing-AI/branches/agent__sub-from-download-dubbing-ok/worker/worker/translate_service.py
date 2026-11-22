"""Translate transcript segments while preserving timing."""

from .translation_edge import translate_segments


_LANGUAGE_ALIASES = {
    # Whisper and common clients may report simplified Chinese as ``zh`` or
    # ``zh-CN``. LibreTranslate v1.9.6 exposes the model as ``zh-Hans``.
    "zh": "zh-Hans",
    "zh-cn": "zh-Hans",
    "zh-hans": "zh-Hans",
    "zh-simplified": "zh-Hans",
}


def _normalize_language(language: str | None) -> str | None:
    if not language:
        return None

    normalized = language.strip().lower().replace("_", "-")
    if not normalized:
        return None

    if normalized in _LANGUAGE_ALIASES:
        return _LANGUAGE_ALIASES[normalized]

    # Preserve the canonical Chinese script code instead of reducing
    # ``zh-Hans`` to ``zh``. Other regional codes are reduced to their base
    # language as before (for example ``en-US`` -> ``en`` and ``vi-VN`` ->
    # ``vi``).
    if normalized == "zh-hans":
        return "zh-Hans"

    return normalized.split("-")[0]


def translate_transcript(
    transcript: dict,
    source: str | None = None,
    target: str | None = None,
    endpoint: str | None = None,
    *,
    source_language: str | None = None,
    target_language: str | None = None,
) -> dict:
    source = _normalize_language(source_language or source)
    target = _normalize_language(target_language or target)
    if not source or not target:
        raise ValueError("source and target languages are required")

    segments = transcript.get("segments", [])
    translations = translate_segments(segments, source, target, endpoint)
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
