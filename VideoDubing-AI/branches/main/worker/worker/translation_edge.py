"""Translation provider using LibreTranslate-compatible HTTP API."""

import json
from urllib.request import Request, urlopen


def translate(text: str, source: str, target: str, endpoint: str) -> str:
    if not text.strip():
        return ""
    payload = json.dumps({"q": text, "source": source, "target": target, "format": "text"}).encode()
    request = Request(endpoint.rstrip("/") + "/translate", data=payload,
                      headers={"Content-Type": "application/json"}, method="POST")
    with urlopen(request, timeout=60) as response:
        data = json.loads(response.read().decode("utf-8"))
    return str(data.get("translatedText", "")).strip()


def translate_segments(segments: list[dict], source: str, target: str, endpoint: str) -> list[str]:
    return [translate(str(segment.get("text", "")), source, target, endpoint) for segment in segments]
