import subprocess
from pathlib import Path


def test_generate_deterministic_media_fixture(tmp_path):
    output = Path(tmp_path) / "fixture.mp4"
    command = [
        "ffmpeg", "-y",
        "-f", "lavfi", "-i", "color=c=black:s=320x240:r=25",
        "-f", "lavfi", "-i", "sine=frequency=440:sample_rate=48000",
        "-t", "2",
        "-c:v", "libx264", "-pix_fmt", "yuv420p",
        "-c:a", "aac", "-ac", "2",
        str(output),
    ]
    subprocess.run(command, check=True, capture_output=True)
    assert output.exists()
    assert output.stat().st_size > 0
