"""Compose timed TTS segments into a single dubbed speech track."""

import subprocess
from pathlib import Path


def _run(command: list[str]) -> None:
    result = subprocess.run(command, capture_output=True, text=True)
    if result.returncode != 0:
        raise RuntimeError(result.stderr.strip() or "FFmpeg audio mixing failed")


def mix_segments(segments: list[dict], output_path: str, duration: float | None = None) -> dict:
    valid = [s for s in segments if s.get("audio_path") and Path(s["audio_path"]).is_file()]
    if not valid:
        raise ValueError("No valid TTS segments")

    Path(output_path).parent.mkdir(parents=True, exist_ok=True)
    command = ["ffmpeg", "-y"]
    filters = []
    for index, segment in enumerate(valid):
        command += ["-i", segment["audio_path"]]
        delay = max(0, round(float(segment.get("start", 0)) * 1000))
        filters.append(f"[{index}:a]adelay={delay}:all=1[a{index}]")

    labels = "".join(f"[a{i}]" for i in range(len(valid)))
    filters.append(f"{labels}amix=inputs={len(valid)}:duration=longest:normalize=0[mix]")
    command += ["-filter_complex", ";".join(filters), "-map", "[mix]", "-c:a", "pcm_s16le"]
    if duration is not None:
        command += ["-t", f"{float(duration):.3f}"]
    command.append(output_path)
    _run(command)

    return {"output_path": output_path, "segments": len(valid)}
