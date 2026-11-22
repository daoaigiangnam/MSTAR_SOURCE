"""Language normalization helpers shared by transcription and translation stages."""

from __future__ import annotations


_AUTO_VALUES = {"", "auto", "automatic", "detect", "detected"}


def normalize_language(value: str | None) -> str | None:
    """Normalize a language tag without throwing away its script/region.

    Examples: zh_Hans -> zh-Hans, pt_BR -> pt-BR, en -> en.
    """
    if value is None:
        return None
    raw = str(value).strip().replace("_", "-")
    if not raw or raw.lower() in _AUTO_VALUES:
        return None

    parts = [part for part in raw.split("-") if part]
    if not parts:
        return None

    language = parts[0].lower()
    rest = []
    for part in parts[1:]:
        if len(part) == 4 and part.isalpha():
            rest.append(part.title())
        elif len(part) in (2, 3) and part.isalnum():
            rest.append(part.upper() if len(part) == 2 and part.isalpha() else part)
        else:
            rest.append(part)
    return "-".join([language, *rest])


def language_family(value: str | None) -> str | None:
    """Return the base ISO language used for Whisper and generic comparisons."""
    normalized = normalize_language(value)
    return normalized.split("-", 1)[0] if normalized else None


def _language_identity(value: str | None) -> str | None:
    """Return a comparison identity while preserving meaningful Chinese scripts."""
    normalized = normalize_language(value)
    if not normalized:
        return None
    if language_family(normalized) != "zh":
        return language_family(normalized)

    lowered = normalized.lower()
    if lowered in {"zh-hant", "zh-tw", "zh-hk", "zh-mo"}:
        return "zh-Hant"
    return "zh-Hans"


def same_language(left: str | None, right: str | None) -> bool:
    """Compare languages for pipeline routing.

    Base codes such as ``zh`` are treated as Simplified Chinese because that
    is what Whisper returns. Explicit ``zh-Hant`` remains distinct from
    ``zh-Hans`` so a Traditional-Chinese target still triggers translation.
    """
    left_identity = _language_identity(left)
    right_identity = _language_identity(right)
    return bool(left_identity and right_identity and left_identity == right_identity)


def whisper_language(value: str | None) -> str | None:
    """faster-whisper expects the base language code, not a BCP-47 tag."""
    return language_family(value)


def translation_language(value: str | None) -> str | None:
    """Return the Argos/LibreTranslate model code for a language tag.

    LibreTranslate accepts model/ISO codes, not the UI/BCP-47 aliases returned
    by ``/languages``. In particular:

    - Simplified Chinese: ``zh-Hans`` / ``zh`` -> ``zh``
    - Traditional Chinese: ``zh-Hant`` / ``zh-TW`` -> ``zt``
    - Brazilian Portuguese: ``pt-BR`` -> ``pb``

    All other tags use their base language code (for example ``en-US`` ->
    ``en`` and ``vi-VN`` -> ``vi``).
    """
    normalized = normalize_language(value)
    if not normalized:
        return None

    family = language_family(normalized)
    lowered = normalized.lower()

    if family == "zh":
        if lowered in {"zh-hant", "zh-tw", "zh-hk", "zh-mo"}:
            return "zt"
        return "zh"

    if lowered in {"pt-br", "pb"}:
        return "pb"

    return family
