"""Audio duration probing with ffprobe."""

import json
import subprocess
from pathlib import Path


class AudioProbeError(RuntimeError):
    pass


def get_duration(audio_path: str) -> float:
    if not Path(audio_path).is_file():
        raise AudioProbeError(f"Audio file not found: {audio_path}")
    result = subprocess.run(
        ["ffprobe", "-v", "error", "-show_entries", "format=duration",
         "-of", "json", audio_path],
        capture_output=True, text=True,
    )
    if result.returncode != 0:
        raise AudioProbeError(result.stderr.strip() or "ffprobe failed")
    try:
        return float(json.loads(result.stdout)["format"]["duration"])
    except (KeyError, TypeError, ValueError, json.JSONDecodeError) as exc:
        raise AudioProbeError("Unable to read audio duration") from exc
