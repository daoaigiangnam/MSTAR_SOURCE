import subprocess
from pathlib import Path

import pytest

from worker.audio_duration import get_duration
from worker.audio_separation import separate_audio
from worker.final_renderer import render_final


@pytest.mark.integration
def test_media_e2e_fixture_pipeline(tmp_path):
    source = Path(tmp_path) / "input.mp4"
    separated = Path(tmp_path) / "separated"
    background = Path(tmp_path) / "background.wav"
    speech = Path(tmp_path) / "speech.wav"
    final = Path(tmp_path) / "final.mp4"

    subprocess.run([
        "ffmpeg", "-y", "-f", "lavfi", "-i", "color=c=black:s=320x240:r=25",
        "-f", "lavfi", "-i", "sine=frequency=440:sample_rate=44100",
        "-t", "2", "-c:v", "libx264", "-pix_fmt", "yuv420p",
        "-c:a", "aac", "-ac", "2", str(source)
    ], check=True, capture_output=True)

    pytest.importorskip("demucs")
    result = separate_audio(str(source), str(separated), model="htdemucs")
    background.write_bytes(Path(result["background_path"]).read_bytes())
    speech.write_bytes(Path(result["vocals_path"]).read_bytes())

    render_result = render_final(str(source), str(background), str(speech), str(final))
    assert final.is_file()
    assert final.stat().st_size > 0
    assert render_result["output_path"] == str(final)

    source_duration = get_duration(str(source))
    final_duration = get_duration(str(final))
    assert abs(final_duration - source_duration) < 0.15

    probe = subprocess.run([
        "ffprobe", "-v", "error", "-show_entries", "format=format_name",
        "-of", "default=noprint_wrappers=1:nokey=1", str(final)
    ], check=True, capture_output=True, text=True)
    assert "mp4" in probe.stdout
