from pathlib import Path

import pytest

from worker.tts_edge import synthesize
from worker.dubbing import _synthesize_with_retry


@pytest.mark.integration
def test_edge_tts_creates_audio(tmp_path):
    output = Path(tmp_path) / "speech.mp3"
    result = synthesize(
        "Hello, this is a VideoDubing AI test.",
        str(output),
        voice="en-US-AriaNeural",
    )
    assert result["status"] == "completed"
    assert output.is_file()
    assert output.stat().st_size > 0


def test_synthesize_with_retry_accepts_valid_audio(tmp_path):
    output = Path(tmp_path) / "speech.mp3"
    result = _synthesize_with_retry(
        "Test sentence.",
        str(output),
        voice="en-US-AriaNeural",
        retries=3,
    )
    assert result["status"] == "completed"
    assert result["attempt"] == 1
    assert output.is_file()
    assert output.stat().st_size > 0
