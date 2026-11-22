"""FFmpeg media extraction helpers."""

import subprocess
from pathlib import Path


class MediaError(RuntimeError):
    pass


def _run(command: list[str]) -> None:
    result = subprocess.run(command, capture_output=True, text=True)
    if result.returncode != 0:
        raise MediaError(result.stderr.strip() or "ffmpeg failed")


def extract_media(input_file: str, output_dir: str) -> dict:
    source = Path(input_file)
    target = Path(output_dir)
    target.mkdir(parents=True, exist_ok=True)

    video_only = target / "video-only.mp4"
    audio = target / "audio.wav"
    speech = target / "speech.wav"

    _run(["ffmpeg", "-y", "-i", str(source), "-an", "-c:v", "copy", str(video_only)])
    _run(["ffmpeg", "-y", "-i", str(source), "-vn", "-ac", "2", "-ar", "48000", str(audio)])
    # Initial speech track is the extracted source audio; vocal separation will be a later module.
    _run(["ffmpeg", "-y", "-i", str(audio), "-ac", "1", "-ar", "16000", str(speech)])

    return {
        "video_path": str(video_only),
        "audio_path": str(audio),
        "speech_path": str(speech),
    }
