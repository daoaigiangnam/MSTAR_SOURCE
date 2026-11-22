from unittest.mock import patch

from worker.translate_service import translate_transcript


def test_translation_preserves_timing_and_adds_target_text():
    transcript = {
        "language": "en",
        "segments": [
            {"start": 0.0, "end": 1.2, "text": "Hello world"},
            {"start": 1.2, "end": 2.5, "text": "This is a test"},
        ],
    }

    def fake_translate(text, source, target, endpoint):
        return {"Hello world": "Xin chao the gioi", "This is a test": "Day la bai kiem tra"}[text]

    with patch("worker.translation_edge.translate", side_effect=fake_translate):
        result = translate_transcript(transcript, "en", "vi", "http://test")

    assert len(result["segments"]) == 2
    for source, target in zip(transcript["segments"], result["segments"]):
        assert target["start"] == source["start"]
        assert target["end"] == source["end"]
        assert target["target_text"]
    assert result["source_language"] == "en"
    assert result["target_language"] == "vi"


def test_translation_normalizes_regional_language_codes():
    transcript = {
        "segments": [{"start": 0.0, "end": 1.0, "text": "Hello"}],
    }

    with patch("worker.translation_edge.translate", return_value="Xin chao") as translate:
        result = translate_transcript(transcript, "en-US", "vi-VN", "http://test")

    translate.assert_called_once_with("Hello", "en", "vi", "http://test")
    assert result["source_language"] == "en"
    assert result["target_language"] == "vi"
