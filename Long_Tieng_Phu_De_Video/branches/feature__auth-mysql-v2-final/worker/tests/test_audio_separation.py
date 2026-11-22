import subprocess
from pathlib import Path

import pytest

from worker.audio_separation import separate_audio


@pytest.mark.integration
def test_demucs_separates_vocals_and_background(tmp_path):
    source = Path(tmp_path) / "mix.wav"
    output_dir = Path(tmp_path) / "separated"

    # Two deterministic tones provide a small synthetic mix for the integration test.
    subprocess.run([
        "ffmpeg", "-y",
        "-f", "lavfi", "-i", "sine=frequency=220:sample_rate=44100",
        "-f", "lavfi", "-i", "sine=frequency=660:sample_rate=44100",
        "-filter_complex", "amix=inputs=2:duration=shortest",
        "-t", "3", "-ac", "2", str(source),
    ], check=True, capture_output=True)

    result = separate_audio(str(source), str(output_dir), model="htdemucs")

    vocals = Path(result["vocals_path"])
    background = Path(result["background_path"])
    assert vocals.is_file()
    assert background.is_file()
    assert vocals.stat().st_size > 0
    assert background.stat().st_size > 0
