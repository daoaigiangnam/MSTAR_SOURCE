"""Build a timeline from fitted TTS segments."""

from pathlib import Path


def build_timeline(segments: list[dict], output_file: str) -> dict:
    timeline = []
    for segment in segments:
        path = segment.get("audio_path")
        if not path or not Path(path).is_file():
            continue
        timeline.append({
            "index": segment["index"],
            "start": float(segment["start"]),
            "end": float(segment["end"]),
            "audio_path": path,
        })

    Path(output_file).parent.mkdir(parents=True, exist_ok=True)
    Path(output_file).write_text(
        "\n".join(f"{x['index']}|{x['start']}|{x['end']}|{x['audio_path']}" for x in timeline),
        encoding="utf-8",
    )
    return {"output_file": output_file, "segments": timeline}
