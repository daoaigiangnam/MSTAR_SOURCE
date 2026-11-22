"""Speech/background separation using Demucs."""

import subprocess
from pathlib import Path


class SeparationError(RuntimeError):
    pass


def separate_audio(audio_path: str, output_dir: str, model: str = "htdemucs") -> dict:
    source = Path(audio_path)
    target = Path(output_dir)
    target.mkdir(parents=True, exist_ok=True)

    result = subprocess.run(
        ["python", "-m", "demucs", "--two-stems=vocals", "-n", model, "-o", str(target), str(source)],
        capture_output=True, text=True,
    )
    if result.returncode != 0:
        raise SeparationError(result.stderr.strip() or "Demucs separation failed")

    stem_dir = target / model / source.stem
    vocals = stem_dir / "vocals.wav"
    no_vocals = stem_dir / "no_vocals.wav"
    if not vocals.is_file() or not no_vocals.is_file():
        raise SeparationError("Demucs did not produce expected stems")

    return {"vocals_path": str(vocals), "background_path": str(no_vocals)}
