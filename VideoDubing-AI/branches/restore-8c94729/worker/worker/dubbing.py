"""Generate, fit, schedule, retry, and compose translated TTS segments."""

from pathlib import Path
import time

from .audio_fit import fit_audio
from .audio_mixer import mix_segments
from .audio_duration import get_duration
from .timing import available_slot, build_segments
from .tts_edge import synthesize



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
        slot = available_slot(segment, next_segment)
        fit = fit_audio(
            str(raw), str(out), slot,
            max_speed=2.0, min_speed=0.85, allow_trim=False,
        )
        actual = get_duration(str(out))

        # Keep every translated sentence anchored to the original source
        # start. Never move a sentence forward because an earlier TTS sentence
        # was long; that causes cumulative drift over a movie.
        start = segment.start
        end = start + actual

        # With word-timed transcription, the next sentence start is a hard
        # synchronization boundary. Do not allow two dubbed voices to overlap.
        if next_segment is not None and end > next_segment.start + 0.05:
            raise RuntimeError(
                f"TTS timing overflow at segment {segment.index}: "
                f"ends at {end:.3f}s but next sentence starts at "
                f"{next_segment.start:.3f}s"
            )

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
