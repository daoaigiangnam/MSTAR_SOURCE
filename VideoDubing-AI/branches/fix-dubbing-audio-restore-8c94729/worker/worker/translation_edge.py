"""Translation provider using LibreTranslate-compatible HTTP API."""

import json
from urllib.error import HTTPError, URLError
from urllib.request import Request, urlopen


def translate(text: str, source: str, target: str, endpoint: str) -> str:
    if not text.strip():
        return ""

    payload = json.dumps({
        "q": text,
        "source": source,
        "target": target,
        "format": "text",
    }).encode("utf-8")

    url = endpoint.rstrip("/") + "/translate"

    request = Request(
        url,
        data=payload,
        headers={
            "Content-Type": "application/json",
            "Accept": "application/json",
        },
        method="POST",
    )

    try:
        with urlopen(request, timeout=60) as response:
            raw = response.read().decode("utf-8", errors="replace")

        try:
            data = json.loads(raw)
        except json.JSONDecodeError as exc:
            raise RuntimeError(
                f"Translation returned invalid JSON: {raw[:1000]}"
            ) from exc

    except HTTPError as exc:
        try:
            body = exc.read().decode("utf-8", errors="replace")
        except Exception:
            body = ""

        raise RuntimeError(
            f"Translation HTTP {exc.code}: "
            f"url={url}; source={source}; target={target}; "
            f"response={body[:2000]}"
        ) from exc

    except URLError as exc:
        raise RuntimeError(
            f"Translation connection error: "
            f"url={url}; source={source}; target={target}; error={exc}"
        ) from exc

    translated = str(data.get("translatedText", "")).strip()

    if not translated:
        raise RuntimeError(
            f"Translation returned empty result: {data}"
        )

    return translated


def translate_segments(
    segments: list[dict],
    source: str,
    target: str,
    endpoint: str,
) -> list[str]:
    results = []
    total = len(segments)

    for index, segment in enumerate(segments, start=1):
        text = str(segment.get("text", "")).strip()

        if not text:
            results.append("")
            continue

        print(
            f"[TRANSLATE] {index}/{total} "
            f"source={source} target={target} "
            f"text={text[:120]}",
            flush=True,
        )

        translated = translate(
            text=text,
            source=source,
            target=target,
            endpoint=endpoint,
        )

        results.append(translated)

        print(
            f"[TRANSLATE] OK {index}/{total}: {translated[:120]}",
            flush=True,
        )

    return results
