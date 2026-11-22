import subprocess
from pathlib import Path

from worker.audio_fit import fit_audio
from worker.audio_mixer import mix_segments
from worker.audio_duration import get_duration


def make_tone(path: Path, frequency: int, duration: float = 2.0):
    subprocess.run([
        "ffmpeg", "-y", "-f", "lavfi", "-i",
        f"sine=frequency={frequency}:sample_rate=48000",
        "-t", str(duration), "-ac", "2", str(path)
    ], check=True, capture_output=True)


def test_audio_fit_and_mixer(tmp_path):
    raw = tmp_path / "raw.wav"
    fitted = tmp_path / "fitted.wav"
    second = tmp_path / "second.wav"
    mixed = tmp_path / "mixed.wav"
    make_tone(raw, 440, 2.0)
    make_tone(second, 660, 1.0)

    fit_audio(str(raw), str(fitted), 1.0)
    assert fitted.exists()
    assert abs(get_duration(str(fitted)) - 1.0) < 0.15

    mix_segments([
        {"index": 1, "start": 0.0, "end": 1.0, "audio_path": str(fitted)},
        {"index": 2, "start": 1.0, "end": 2.0, "audio_path": str(second)},
    ], str(mixed), duration=2.0)

    assert mixed.exists()
    assert get_duration(str(mixed)) >= 1.8
