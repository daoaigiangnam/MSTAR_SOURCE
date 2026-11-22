"""Parse yt-dlp progress lines."""

import re

_PROGRESS = re.compile(r"(?P<pct>\d+(?:\.\d+)?)%.*?(?P<speed>\S+/s).*?(?:ETA\s+(?P<eta>\S+))?")


def parse_progress(line: str) -> dict | None:
    match = _PROGRESS.search(line)
    if not match:
        return None
    return {
        "progress": float(match.group("pct")),
        "speed": match.group("speed"),
        "eta": match.group("eta"),
    }
