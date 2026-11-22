"""Fit generated speech into a target timeline without destructive clipping."""

import subprocess
from pathlib import Path

from .audio_duration import get_duration


def _run(command: list[str]) -> None:
    result = subprocess.run(command, capture_output=True, text=True)
    if result.returncode != 0:
        raise RuntimeError(result.stderr.strip() or "ffmpeg audio fit failed")


def _atempo_chain(speed: float) -> str:
    """Build an FFmpeg atempo chain for ratios outside the single-filter range."""
    speed = max(0.01, float(speed))
    parts = []
    while speed > 2.0:
        parts.append("atempo=2.0")
        speed /= 2.0
    while speed < 0.5:
        parts.append("atempo=0.5")
        speed /= 0.5
    parts.append(f"atempo={speed:.6f}")
    return ",".join(parts)


def fit_audio(
    input_path: str,
    output_path: str,
    target_duration: float,
    max_speed: float = 2.0,
    min_speed: float = 0.85,
    allow_trim: bool = False,
) -> dict:
    """Fit TTS to a slot.

    Speed adjustment is preferred. Hard clipping is disabled by default so a
    translated sentence cannot silently lose its final words.

    The default maximum speed is 2x. This allows a normal two-second TTS
    sentence to fit a one-second slot while preserving the complete utterance.
    If a sentence still cannot fit within the configured speed range, the
    caller can keep the longer duration and the scheduler can move following
    segments instead of clipping speech.
    """
    actual = get_duration(input_path)
    target = max(float(target_duration), 0.05)
    ratio = actual / target

    # If the generated audio is longer, atempo > 1 speeds it up. If shorter,
    # atempo < 1 slows it down. Hard trimming remains disabled by default.
    speed = max(min_speed, min(max_speed, ratio))
    needs_fit = abs(actual - target) >= 0.03

    Path(output_path).parent.mkdir(parents=True, exist_ok=True)
    if not needs_fit:
        _run(["ffmpeg", "-y", "-i", input_path, "-c:a", "pcm_s16le", output_path])
    else:
        command = ["ffmpeg", "-y", "-i", input_path, "-filter:a", _atempo_chain(speed)]
        if allow_trim:
            command += ["-t", f"{target:.3f}"]
        command.append(output_path)
        _run(command)

    output_duration = get_duration(output_path)
    return {
        "input_duration": actual,
        "target_duration": target,
        "output_duration": output_duration,
        "speed_ratio": speed,
        "output_path": output_path,
        "trimmed": bool(allow_trim),
    }
