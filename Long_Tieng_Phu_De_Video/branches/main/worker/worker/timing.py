"""Timeline planning for multilingual dubbing."""

from dataclasses import dataclass


@dataclass
class TimedSegment:
    index: int
    start: float
    end: float
    text: str
    target_text: str = ""

    @property
    def duration(self) -> float:
        return max(0.0, self.end - self.start)


def _clean_bounds(start: float, end: float) -> tuple[float, float]:
    start = max(0.0, start)
    end = max(start + 0.05, end)
    return start, end


def build_segments(
    segments: list[dict],
    translations: list[str] | None = None,
    *,
    min_gap: float = 0.03,
) -> list[TimedSegment]:
    """Build a non-overlapping speech timeline while preserving source anchors.

    Whisper can occasionally return touching/overlapping segments.  We keep the
    original start anchors where possible and clamp only pathological overlaps.
    """
    translations = translations or []
    raw: list[TimedSegment] = []
    for index, segment in enumerate(segments):
        start, end = _clean_bounds(float(segment.get("start", 0)), float(segment.get("end", 0)))
        text = str(segment.get("text", "")).strip()
        segment_target = str(segment.get("target_text", "")).strip()
        target = segment_target or (str(translations[index]).strip() if index < len(translations) else "")
        raw.append(TimedSegment(index + 1, start, end, text, target))

    raw.sort(key=lambda item: (item.start, item.end, item.index))
    result: list[TimedSegment] = []
    cursor = 0.0
    for item in raw:
        start = max(item.start, cursor)
        end = max(start + 0.05, item.end)
        result.append(TimedSegment(item.index, start, end, item.text, item.target_text))
        cursor = end + min_gap
    return result


def available_slot(segment: TimedSegment, next_segment: TimedSegment | None, max_extension: float = 0.75) -> float:
    """Return usable speech duration without entering the next speech anchor."""
    source_duration = segment.duration
    if next_segment is None:
        return source_duration + max_extension
    until_next = max(0.05, next_segment.start - segment.start - 0.03)
    return max(0.05, min(source_duration + max_extension, until_next))


def fit_duration(text: str, available_seconds: float, chars_per_second: float = 14.0) -> float:
    minimum = 0.25
    estimated = len(text.strip()) / max(chars_per_second, 1.0)
    return max(minimum, min(estimated, max(available_seconds, minimum)))
