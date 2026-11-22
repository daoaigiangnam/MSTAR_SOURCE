from worker.language_utils import (
    normalize_language,
    same_language,
    translation_language,
    whisper_language,
)
from worker.subtitle_renderer import _font_for_language


def test_language_tags_keep_script_and_region():
    assert normalize_language("zh_hans") == "zh-Hans"
    assert normalize_language("pt_BR") == "pt-BR"
    assert normalize_language("en") == "en"


def test_same_language_compares_language_identity():
    assert same_language("zh", "zh-Hans")
    assert same_language("zh-Hant", "zh-TW")
    assert same_language("en", "en-US")
    assert same_language("vi", "vi-VN")
    assert same_language("pt-BR", "pt-br")
    assert not same_language("zh-Hans", "zh-Hant")
    assert not same_language("zh-Hans", "vi")
    assert not same_language("en", "vi")


def test_whisper_uses_base_language_code():
    assert whisper_language("zh-Hans") == "zh"
    assert whisper_language("en-US") == "en"
    assert whisper_language("vi-VN") == "vi"


def test_translation_uses_argos_model_codes():
    # LibreTranslate/Argos uses zh for Simplified Chinese and zt for
    # Traditional Chinese. The public BCP-47 aliases must not be sent to
    # /translate because they can produce HTTP 400.
    assert translation_language("zh") == "zh"
    assert translation_language("zh-Hans") == "zh"
    assert translation_language("zh-CN") == "zh"
    assert translation_language("zh-Hant") == "zt"
    assert translation_language("zh-TW") == "zt"
    assert translation_language("zh-HK") == "zt"

    # Argos uses pb for Brazilian Portuguese.
    assert translation_language("pt-BR") == "pb"
    assert translation_language("pt-br") == "pb"

    assert translation_language("vi") == "vi"
    assert translation_language("vi-VN") == "vi"
    assert translation_language("en-US") == "en"
    assert translation_language("ja-JP") == "ja"
    assert translation_language("ko-KR") == "ko"
    assert translation_language("fr-FR") == "fr"
    assert translation_language("de-DE") == "de"
    assert translation_language("es-ES") == "es"
    assert translation_language("id-ID") == "id"
    assert translation_language("th-TH") == "th"
    assert translation_language("ru-RU") == "ru"


def test_script_aware_subtitle_fonts():
    assert _font_for_language("zh-Hans") == "Noto Sans CJK SC"
    assert _font_for_language("zh-Hant") == "Noto Sans CJK TC"
    assert _font_for_language("ja") == "Noto Sans CJK JP"
    assert _font_for_language("ko") == "Noto Sans CJK KR"
    assert _font_for_language("ar") == "Noto Sans Arabic"
    assert _font_for_language("hi") == "Noto Sans Devanagari"
    assert _font_for_language("vi") == "Noto Sans"
