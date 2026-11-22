import subprocess
from pathlib import Path

from worker.final_renderer import render_final


def make_fixture(path: Path):
    subprocess.run([
        "ffmpeg", "-y", "-f", "lavfi", "-i", "color=c=black:s=320x240:r=25",
        "-f", "lavfi", "-i", "sine=frequency=440:sample_rate=48000", "-t", "2",
        "-c:v", "libx264", "-pix_fmt", "yuv420p", "-c:a", "aac", "-ac", "2", str(path)
    ], check=True, capture_output=True)


def test_final_renderer_creates_valid_mp4(tmp_path):
    source = tmp_path / "source.mp4"
    background = tmp_path / "background.wav"
    speech = tmp_path / "speech.wav"
    output = tmp_path / "final.mp4"
    make_fixture(source)

    subprocess.run([
        "ffmpeg", "-y", "-f", "lavfi", "-i", "sine=frequency=220:sample_rate=48000",
        "-t", "2", "-ac", "2", str(background)
    ], check=True, capture_output=True)
    subprocess.run([
        "ffmpeg", "-y", "-f", "lavfi", "-i", "sine=frequency=660:sample_rate=48000",
        "-t", "2", "-ac", "2", str(speech)
    ], check=True, capture_output=True)

    result = render_final(str(source), str(background), str(speech), str(output))
    assert output.exists()
    assert output.stat().st_size > 0
    assert result["output_path"] == str(output)

    probe = subprocess.run([
        "ffprobe", "-v", "error", "-show_entries", "format=format_name",
        "-of", "default=noprint_wrappers=1:nokey=1", str(output)
    ], check=True, capture_output=True, text=True)
    assert "mp4" in probe.stdout
