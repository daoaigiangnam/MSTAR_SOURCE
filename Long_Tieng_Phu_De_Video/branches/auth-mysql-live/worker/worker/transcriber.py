"""Speech transcription with faster-whisper."""

import re
from pathlib import Path

from faster_whisper import WhisperModel


class TranscriptionError(RuntimeError):
    pass


def _split_word_timed_segment(segment) -> list[dict]:
    """Split a Whisper segment into sentence-sized items using word timing.

    Whisper's segment end can cover a long silence. Word timestamps are much
    better anchors for dubbing because they describe where the spoken words
    actually occur. We split on sentence punctuation and keep the first/last
    word timestamps for each sentence.
    """
    words = getattr(segment, "words", None) or []
    if not words:
        return [{
            "start": round(segment.start, 3),
            "end": round(segment.end, 3),
            "text": segment.text.strip(),
        }]

    items: list[dict] = []
    current_words = []
    sentence_re = re.compile(r"[.!?。！？]+(?:[\"'”’)]*)$")

    for word in words:
        text = str(getattr(word, "word", "") or "")
        if not text.strip():
            continue
        current_words.append(word)
        if sentence_re.search(text.strip()):
            items.append(_make_word_item(current_words))
            current_words = []

    if current_words:
        items.append(_make_word_item(current_words))

    if not items:
        return [{
            "start": round(segment.start, 3),
            "end": round(segment.end, 3),
            "text": segment.text.strip(),
        }]
    return items


def _make_word_item(words) -> dict:
    start = float(getattr(words[0], "start", 0.0) or 0.0)
    end = float(getattr(words[-1], "end", start) or start)
    text = "".join(str(getattr(word, "word", "") or "") for word in words).strip()
    return {
        "start": round(max(0.0, start), 3),
        "end": round(max(start + 0.05, end), 3),
        "text": text,
    }


def transcribe(audio_path: str, model_size: str = "small", language: str | None = None) -> dict:
    if not Path(audio_path).is_file():
        raise TranscriptionError(f"Audio file not found: {audio_path}")

    model = WhisperModel(model_size, device="cpu", compute_type="int8")
    segments, info = model.transcribe(
        audio_path,
        language=language,
        vad_filter=True,
        word_timestamps=True,
    )

    items = []
    for segment in segments:
        items.extend(_split_word_timed_segment(segment))

    return {
        "language": info.language,
        "language_probability": info.language_probability,
        "segments": items,
    }
