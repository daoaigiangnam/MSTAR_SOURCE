from unittest.mock import patch

from worker.transcription_service import create_transcript


def test_transcription_contract_writes_srt(tmp_path):
    fake_result = {
        "language": "en",
        "language_probability": 0.99,
        "segments": [
            {"start": 0.0, "end": 1.0, "text": "Hello world"},
            {"start": 1.0, "end": 2.0, "text": "VideoDubing AI"},
        ],
    }

    with patch("worker.transcription_service.transcribe", return_value=fake_result):
        result = create_transcript("unused.wav", str(tmp_path), model_size="tiny")

    assert result["language"] == "en"
    assert result["language_probability"] == 0.99
    assert len(result["segments"]) == 2
    assert (tmp_path / "subtitles.srt").is_file()
