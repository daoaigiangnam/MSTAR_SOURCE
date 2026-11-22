"""Translation provider using LibreTranslate-compatible HTTP API."""

from __future__ import annotations

import json
from urllib.error import HTTPError, URLError
from urllib.request import Request, urlopen


class TranslationProviderError(RuntimeError):
    """Raised when the translation provider cannot complete a request."""


def translate(text: str, source: str, target: str, endpoint: str) -> str:
    if not text.strip():
        return ""
    if not source or not target:
        raise TranslationProviderError("translation source and target are required")
    if source == target:
        return text.strip()

    payload = json.dumps({
        "q": text,
        "source": source,
        "target": target,
        "format": "text",
    }).encode("utf-8")
    request = Request(
        endpoint.rstrip("/") + "/translate",
        data=payload,
        headers={"Content-Type": "application/json"},
        method="POST",
    )

    try:
        with urlopen(request, timeout=60) as response:
            data = json.loads(response.read().decode("utf-8"))
    except HTTPError as exc:
        try:
            body = exc.read().decode("utf-8", errors="replace").strip()
        except Exception:
            body = ""
        detail = f": {body[:1000]}" if body else ""
        raise TranslationProviderError(
            f"LibreTranslate HTTP {exc.code} for {source}->{target}{detail}"
        ) from exc
    except URLError as exc:
        raise TranslationProviderError(
            f"LibreTranslate connection failed for {source}->{target}: {exc.reason}"
        ) from exc

    translated = str(data.get("translatedText", "")).strip()
    if not translated:
        raise TranslationProviderError(
            f"LibreTranslate returned an empty translation for {source}->{target}"
        )
    return translated


def translate_segments(
    segments: list[dict], source: str, target: str, endpoint: str
) -> list[str]:
    return [
        translate(str(segment.get("text", "")), source, target, endpoint)
        for segment in segments
    ]
