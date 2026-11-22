"""Generate, fit, schedule, retry, and compose translated TTS segments."""

from pathlib import Path
import time

from .audio_fit import fit_audio
from .audio_mixer import mix_segments
from .audio_duration import get_duration
from .tts_edge import synthesize
from .timing import build_segments


# Dubbing quality policy:
# 1. Never clip generated speech.
# 2. Never use extreme time-stretching to force speech into the source slot.
# 3. Prefer natural speech and continuity over frame-accurate sentence starts.
# 4. Keep source timestamps as soft anchors; when a sentence is longer than
#    the available window, move the following sentence instead of making the
#    current voice unnaturally fast.
DUB_MAX_SPEED = 1.15
DUB_MIN_SPEED = 1.00
DUB_MAX_EXTENSION = 1.50
DUB_GAP = 0.03


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
    fitted = []
    warnings = []
    timeline_cursor = 0.0

    for pos, segment in enumerate(segments):
        text = segment.target_text.strip()
        if not text:
            continue

        raw = raw_dir / f"segment_{segment.index:04d}.mp3"
        out = fitted_dir / f"segment_{segment.index:04d}.wav"

        try:
            tts_result = _synthesize_with_retry(text, str(raw), voice=voice, retries=3)
        except Exception as exc:
            warnings.append({
                "segment": segment.index,
                "message": str(exc),
                "target_text": text,
            })
            continue

        next_segment = segments[pos + 1] if pos + 1 < len(segments) else None

        # The original timestamp is a soft anchor. Never create overlapping
        # voices just to hit the next source timestamp.
        start = max(segment.start, timeline_cursor)

        if next_segment is None:
            slot = segment.duration + DUB_MAX_EXTENSION
        else:
            until_next = max(0.05, next_segment.start - start - DUB_GAP)
            slot = max(0.05, min(segment.duration + DUB_MAX_EXTENSION, until_next))

        fit = fit_audio(
            str(raw),
            str(out),
            slot,
            max_speed=DUB_MAX_SPEED,
            min_speed=DUB_MIN_SPEED,
            allow_trim=False,
        )
        actual = get_duration(str(out))
        end = start + actual

        # If 1.15x still cannot fit, keep the complete sentence and let the
        # next sentence move. This is preferable to clipping words or using
        # extreme atempo values that sound unnaturally fast/slow.
        if next_segment is not None and end > next_segment.start + DUB_GAP:
            warnings.append({
                "segment": segment.index,
                "message": "TTS exceeded source timing window; speech preserved and following segment shifted",
                "source_start": segment.start,
                "scheduled_start": start,
                "source_next_start": next_segment.start,
                "scheduled_end": end,
                "speed_ratio": fit.get("speed_ratio"),
            })

        fitted.append({
            "index": segment.index,
            "start": start,
            "source_start": segment.start,
            "source_end": segment.end,
            "end": end,
            "duration": actual,
            "audio_path": str(out),
            "target_text": text,
            "fit": fit,
            "tts_attempt": tts_result.get("attempt"),
        })

        timeline_cursor = end + DUB_GAP

    if not fitted:
        raise ValueError("translated transcript contains no successfully synthesized segments")

    mix_duration = float(duration) if duration is not None else max(item["end"] for item in fitted)
    if fitted[-1]["end"] > mix_duration + 0.05:
        raise RuntimeError(
            f"Dubbed speech exceeds video duration ({fitted[-1]['end']:.2f}s > {mix_duration:.2f}s)"
        )

    dubbed = root / "dubbed_speech.wav"
    mix_segments(fitted, str(dubbed), duration=mix_duration)
    return {
        "dubbed_speech_path": str(dubbed),
        "segments": fitted,
        "warnings": warnings,
        "language": language,
        "voice": voice,
        "duration": mix_duration,
    }
