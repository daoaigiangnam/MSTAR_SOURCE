"""Final video renderer: original video + background + dubbed speech."""

import subprocess
from pathlib import Path

from .audio_duration import get_duration


def render_final(video_path: str, background_path: str, dubbed_path: str,
                 output_path: str) -> dict:
    """Mix background/dubbed audio and keep exactly the source video duration."""
    Path(output_path).parent.mkdir(parents=True, exist_ok=True)
    duration = get_duration(video_path)
    command = [
        "ffmpeg", "-y",
        "-i", video_path,
        "-i", background_path,
        "-i", dubbed_path,
        "-filter_complex",
        (
            f"[1:a]volume=0.35,apad,atrim=duration={duration}[bg];"
            f"[2:a]apad,atrim=duration={duration}[dub];"
            "[bg][dub]amix=inputs=2:duration=longest:normalize=0,"
            "aresample=async=1:first_pts=0[a]"
        ),
        "-map", "0:v:0", "-map", "[a]",
        "-c:v", "copy", "-c:a", "aac", "-b:a", "192k",
        "-t", str(duration),
        output_path,
    ]
    result = subprocess.run(command, capture_output=True, text=True)
    if result.returncode != 0:
        raise RuntimeError(result.stderr.strip() or "Final FFmpeg render failed")
    return {"output_path": output_path, "duration": duration}
