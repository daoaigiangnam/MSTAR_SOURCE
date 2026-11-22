"""Subtitle segmentation and SRT output."""

import re
from pathlib import Path


def split_text(text: str, max_chars: int = 90) -> list[str]:
    parts = re.split(r"(?<=[.!?。！？])\s+|\s*[,;:]\s+", text.strip())
    result: list[str] = []
    for part in parts:
        part = part.strip()
        if not part:
            continue
        while len(part) > max_chars:
            cut = part.rfind(" ", 0, max_chars)
            if cut <= 0:
                cut = max_chars
            result.append(part[:cut].strip())
            part = part[cut:].strip()
        result.append(part)
    return result


def _timestamp(seconds: float) -> str:
    ms = round(seconds * 1000)
    hours, ms = divmod(ms, 3_600_000)
    minutes, ms = divmod(ms, 60_000)
    secs, ms = divmod(ms, 1000)
    return f"{hours:02d}:{minutes:02d}:{secs:02d},{ms:03d}"


def write_srt(segments: list[dict], output_file: str, text_key: str = "text") -> str:
    path = Path(output_file)
    path.parent.mkdir(parents=True, exist_ok=True)
    blocks = []
    for index, segment in enumerate(segments, 1):
        text = segment.get(text_key, "").strip()
        if not text:
            continue
        blocks.append(
            f"{index}\n{_timestamp(segment['start'])} --> {_timestamp(segment['end'])}\n{text}\n"
        )
    path.write_text("\n".join(blocks), encoding="utf-8")
    return str(path)
