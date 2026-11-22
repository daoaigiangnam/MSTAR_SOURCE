import subprocess
from pathlib import Path

import pytest

from worker.transcription_service import create_transcript


@pytest.mark.integration
def test_whisper_transcription_creates_srt(tmp_path):
    audio = Path(tmp_path) / "speech.wav"
    output_dir = Path(tmp_path) / "transcript"
    subprocess.run([
        "ffmpeg", "-y", "-f", "lavfi", "-i",
        "sine=frequency=440:sample_rate=16000", "-t", "2", "-ac", "1", str(audio)
    ], check=True, capture_output=True)

    result = create_transcript(str(audio), str(output_dir), model_size="tiny")
    assert result["language"]
    assert isinstance(result["segments"], list)
    assert Path(result["srt_path"]).is_file()
