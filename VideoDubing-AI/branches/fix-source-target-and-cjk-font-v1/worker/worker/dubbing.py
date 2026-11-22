"""Generate, fit, schedule, retry, and compose translated TTS segments."""

from pathlib import Path
import subprocess
import time

from .audio_fit import fit_audio
from .audio_mixer import mix_segments
from .audio_duration import get_duration
from .tts_edge import synthesize
from .timing import build_segments


# Dubbing quality policy
# 1. Never clip generated speech.
# 2. Prefer source timing when it is naturally possible.
# 3. For short videos, use controlled global atempo compression rather than
#    failing the job when translated speech is longer than the source.
# 4. Keep a hard safety ceiling at 2x. This matches the maximum atempo ratio
#    supported by the per-segment fitter and avoids unnecessary failures for
#    languages whose translations are naturally longer than the source.
DUB_NORMAL_MAX_SPEED = 1.15
DUB_GLOBAL_MAX_SPEED = 2.00
DUB_MIN_SPEED = 1.00
DUB_GAP = 0.03
DUB_EPSILON = 0.05
DUB_WARNING_SPEED = 1.60


def _synthesize_with_retry(text: str, output_path: str, voice: str, retries: int = 3) -> dict:
    """Synthesize one segment and retry transient provider failures."""
    last_error = None
    for attempt in range(1, retries + 1):
        try:
            result = synthesize(text, output_path, voice=voice)
            path = Path(output_path)
            if not path.is_file() or path.stat().st_size == 0:
                raise RuntimeError("TTS provider produced no audio")
            result["attempt"] = attempt
            return result
        except Exception as exc:
            last_error = exc
            if attempt < retries:
                time.sleep(0.5 * attempt)
    raise RuntimeError(f"TTS failed after {retries} attempts: {last_error}") from last_error


def _apply_global_atempo(input_path: str, output_path: str, speed: float) -> float:
    """Compress a complete mixed speech track without hard clipping."""
    speed = max(0.01, float(speed))
    parts = []
    remaining = speed
    while remaining > 2.0:
        parts.append("atempo=2.0")
        remaining /= 2.0
    while remaining < 0.5:
        parts.append("atempo=0.5")
        remaining /= 0.5
    parts.append(f"atempo={remaining:.6f}")

    Path(output_path).parent.mkdir(parents=True, exist_ok=True)
    result = subprocess.run(
        [
            "ffmpeg", "-y", "-i", input_path,
            "-filter:a", ",".join(parts),
            "-c:a", "pcm_s16le", output_path,
        ],
        capture_output=True,
        text=True,
    )
    if result.returncode != 0:
        raise RuntimeError(result.stderr.strip() or "ffmpeg global speech compression failed")
    return get_duration(output_path)


def generate_dubbed_speech(
    transcript: dict,
    output_dir: str,
    language: str,
    voice: str,
    duration: float | None = None,
) -> dict:
    root = Path(output_dir)
    raw_dir = root / "tts_raw"
    fitted_dir = root / "tts_fitted"
    raw_dir.mkdir(parents=True, exist_ok=True)
    fitted_dir.mkdir(parents=True, exist_ok=True)

    segments = build_segments(transcript.get("segments", []))
    if not segments:
        raise ValueError("translated transcript contains no segments")

    generated = []
    warnings = []

    # Pass 1: synthesize every sentence before deciding the final timeline.
    # This gives the scheduler the real TTS durations instead of estimates.
    for segment in segments:
        text = segment.target_text.strip()
        if not text:
            continue

        raw = raw_dir / f"segment_{segment.index:04d}.mp3"
        try:
            tts_result = _synthesize_with_retry(text, str(raw), voice=voice, retries=3)
            generated.append({
                "index": segment.index,
                "source_start": segment.start,
                "source_end": segment.end,
                "source_duration": segment.duration,
                "text": text,
                "raw_path": str(raw),
                "raw_duration": get_duration(str(raw)),
                "tts_attempt": tts_result.get("attempt"),
            })
        except Exception as exc:
            warnings.append({
                "segment": segment.index,
                "message": str(exc),
                "target_text": text,
            })

    if not generated:
        raise ValueError("translated transcript contains no successfully synthesized segments")

    video_duration = float(duration) if duration is not None and float(duration) > 0 else None
    total_raw = sum(item["raw_duration"] for item in generated)
    gap_total = max(0, len(generated) - 1) * DUB_GAP

    compressed = False
    compression_speed = 1.0

    if video_duration is not None:
        available = max(0.05, video_duration - gap_total)
        required_speed = total_raw / available

        if required_speed > DUB_GLOBAL_MAX_SPEED + 0.001:
            raise RuntimeError(
                "Translated speech is too long to fit the source video within "
                f"the safe compression limit of {DUB_GLOBAL_MAX_SPEED:.2f}x "
                f"(required {required_speed:.2f}x; speech {total_raw:.2f}s; "
                f"video {video_duration:.2f}s)"
            )

        compressed = required_speed > DUB_NORMAL_MAX_SPEED + 0.001
        compression_speed = max(1.0, required_speed)

        if required_speed > DUB_WARNING_SPEED + 0.001:
            warnings.append({
                "message": "Strong global speech compression applied",
                "speed_ratio": required_speed,
                "warning_threshold": DUB_WARNING_SPEED,
                "source_duration": video_duration,
                "speech_duration": total_raw,
            })

    # Pass 2: create the initial schedule. In compression mode we deliberately
    # use a continuous timeline; source timestamps are no longer feasible once
    # the translated speech needs global compression.
    fitted = []
    cursor = 0.0

    for item in generated:
        if video_duration is None:
            start = max(float(item["source_start"]), cursor)
            target_slot = max(0.05, float(item["source_duration"]))
            max_speed = DUB_NORMAL_MAX_SPEED
        elif compressed:
            start = cursor
            target_slot = item["raw_duration"] / compression_speed
            max_speed = DUB_GLOBAL_MAX_SPEED
        else:
            start = max(float(item["source_start"]), cursor)
            target_slot = max(0.05, float(item["source_duration"]))
            max_speed = DUB_NORMAL_MAX_SPEED

        out = fitted_dir / f"segment_{item['index']:04d}.wav"
        fit = fit_audio(
            item["raw_path"],
            str(out),
            target_slot,
            max_speed=max_speed,
            min_speed=DUB_MIN_SPEED,
            allow_trim=False,
        )
        actual = get_duration(str(out))
        end = start + actual

        fitted.append({
            "index": item["index"],
            "start": start,
            "source_start": item["source_start"],
            "source_end": item["source_end"],
            "end": end,
            "duration": actual,
            "audio_path": str(out),
            "target_text": item["text"],
            "fit": fit,
            "tts_attempt": item["tts_attempt"],
        })
        cursor = end + DUB_GAP

    if not fitted:
        raise ValueError("No fitted TTS segments")

    dubbed = root / "dubbed_speech.wav"
    mix_duration = video_duration if video_duration is not None else max(item["end"] for item in fitted)

    # Always mix first. This makes the final duration check authoritative and
    # also catches tiny duration differences introduced by individual TTS fits.
    temp_mix = root / "dubbed_speech_precompression.wav"
    mix_segments(fitted, str(temp_mix), duration=None)
    mixed_duration = get_duration(str(temp_mix))
    before_compression = mixed_duration

    if video_duration is not None and mixed_duration > video_duration + DUB_EPSILON:
        final_speed = mixed_duration / video_duration
        if final_speed > DUB_GLOBAL_MAX_SPEED + 0.001:
            raise RuntimeError(
                "Final dubbed speech is too long to fit the source video within "
                f"the safe compression limit of {DUB_GLOBAL_MAX_SPEED:.2f}x "
                f"({mixed_duration:.2f}s > {video_duration:.2f}s; "
                f"required {final_speed:.2f}x)"
            )

        output_after_compression = _apply_global_atempo(
            str(temp_mix), str(dubbed), final_speed
        )
        compressed = True
        compression_speed = max(compression_speed, final_speed)
        warnings.append({
            "message": "Global speech compression applied",
            "speed_ratio": final_speed,
            "source_duration": video_duration,
            "before_compression": before_compression,
            "after_compression": output_after_compression,
        })
    else:
        Path(dubbed).write_bytes(Path(temp_mix).read_bytes())

    output_duration = get_duration(str(dubbed))
    if video_duration is not None and output_duration > video_duration + DUB_EPSILON:
        raise RuntimeError(
            f"Dubbed speech exceeds video duration ({output_duration:.2f}s > {video_duration:.2f}s)"
        )

    return {
        "dubbed_speech_path": str(dubbed),
        "segments": fitted,
        "warnings": warnings,
        "language": language,
        "voice": voice,
        "duration": mix_duration,
        "compression_mode": compressed,
        "compression_speed": compression_speed,
        "output_duration": output_duration,
    }
