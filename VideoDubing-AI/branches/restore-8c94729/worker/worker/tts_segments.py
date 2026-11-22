"""Create a TTS execution plan without coupling to a provider."""

from .timing import TimedSegment, fit_duration


def build_tts_plan(segments: list[TimedSegment]) -> list[dict]:
    plan = []
    for segment in segments:
        text = segment.target_text or segment.text
        if not text:
            continue
        plan.append({
            "index": segment.index,
            "start": segment.start,
            "end": segment.end,
            "available_duration": segment.duration,
            "estimated_duration": fit_duration(text, segment.duration),
            "text": text,
        })
    return plan
