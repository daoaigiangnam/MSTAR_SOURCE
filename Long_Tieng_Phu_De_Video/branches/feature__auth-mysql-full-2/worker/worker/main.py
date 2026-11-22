"""VideoDubing-AI media/AI worker API."""

import asyncio
import json
import os
import tempfile
from concurrent.futures import ThreadPoolExecutor
from urllib.request import Request, urlopen

import edge_tts
from fastapi import FastAPI, HTTPException, Query
from fastapi.responses import Response
from pydantic import BaseModel, Field

from .language_utils import normalize_language, same_language
from .processor import process

app = FastAPI(title="VideoDubing-AI Worker", version="0.2.0")
executor = ThreadPoolExecutor(max_workers=2)
API_URL = os.getenv("API_URL", "http://api:3000")
INTERNAL_TOKEN = os.getenv("INTERNAL_TOKEN", "")
SUPPORTED_TASKS = {"download", "download_only", "subtitle", "extract", "separate", "transcribe", "translate", "tts", "render"}


class JobRequest(BaseModel):
    job_id: str
    task: str
    payload: dict = Field(default_factory=dict)


class VoicePreviewRequest(BaseModel):
    text: str = Field(default="Xin chào, đây là giọng đọc mẫu cho video của bạn.", min_length=1, max_length=500)
    voice: str = Field(min_length=1, max_length=200)
    rate: str = Field(default="+0%", max_length=20)


@app.get("/health")
def health():
    return {"success": True, "service": "videodubing-worker", "status": "ok", "version": "0.2.0"}


async def _voice_catalog():
    return await edge_tts.list_voices()


@app.get("/languages")
async def languages():
    """Return target languages supported by the configured TTS voice provider."""
    items = await _voice_catalog()
    seen = {}
    for voice in items:
        locale = str(voice.get("Locale", "")).strip()
        code = locale.split("-")[0].lower() if locale else ""
        if not code:
            continue
        if code not in seen:
            friendly = str(voice.get("FriendlyName") or voice.get("ShortName") or code)
            seen[code] = {"code": code, "name": friendly.split(" - ")[-1] if " - " in friendly else code, "locale": locale}
    return {"success": True, "data": sorted(seen.values(), key=lambda item: item["code"])}


@app.get("/voices")
async def voices(language: str | None = Query(default=None)):
    """Return provider voices, optionally filtered by target language."""
    items = await _voice_catalog()
    normalized = (language or "").lower().replace("_", "-")
    if normalized and normalized not in {"auto", "all"}:
        normalized = normalized.split("-")[0]
        items = [v for v in items if str(v.get("Locale", "")).lower().split("-")[0] == normalized]
    return {"success": True, "data": [{
        "id": v.get("ShortName"), "name": v.get("FriendlyName") or v.get("ShortName"),
        "locale": v.get("Locale"), "language": str(v.get("Locale", "")).split("-")[0].lower(),
        "gender": str(v.get("Gender", "")).lower(), "styles": v.get("StyleList", []),
    } for v in items]}


@app.post("/voices/preview")
async def voice_preview(request: VoicePreviewRequest):
    """Generate a short MP3 preview for a selected Edge TTS voice."""
    voices_catalog = await _voice_catalog()
    selected = next((v for v in voices_catalog if v.get("ShortName") == request.voice), None)
    if not selected:
        raise HTTPException(status_code=400, detail=f"Unsupported voice: {request.voice}")

    fd, output_file = tempfile.mkstemp(prefix="voice-preview-", suffix=".mp3")
    os.close(fd)
    try:
        communicate = edge_tts.Communicate(request.text.strip(), request.voice, rate=request.rate)
        await communicate.save(output_file)
        if not os.path.isfile(output_file) or os.path.getsize(output_file) == 0:
            raise HTTPException(status_code=502, detail="TTS provider produced no audio")
        with open(output_file, "rb") as audio:
            content = audio.read()
        return Response(
            content=content,
            media_type="audio/mpeg",
            headers={"Content-Disposition": 'inline; filename="voice-preview.mp3"'},
        )
    except HTTPException:
        raise
    except Exception as exc:
        raise HTTPException(status_code=502, detail=f"TTS preview failed: {exc}") from exc
    finally:
        try:
            os.unlink(output_file)
        except OSError:
            pass


def callback(path: str, payload: dict):
    data = json.dumps(payload).encode()
    headers = {"Content-Type": "application/json"}
    if INTERNAL_TOKEN:
        headers["Authorization"] = f"Bearer {INTERNAL_TOKEN}"
    request = Request(f"{API_URL}{path}", data=data, headers=headers, method="POST")
    with urlopen(request, timeout=15):
        pass


def _effective_task(request: JobRequest) -> str:
    """Resolve the FLAG=1 source/target routing exception.

    The established Chinese case is:
      FLAG=1 + source=zh/Chinese + target=zh-Hans -> download only.

    The monitor command used for this case supplies URL + target + flag and
    therefore does not include source_language. When source is omitted and
    target is explicitly zh-Hans, keep that exact monitor case download-only
    as well. Explicit non-Chinese source languages continue through the normal
    FLAG=1 dubbing path.
    """
    if request.task != "download":
        return request.task

    source = normalize_language(request.payload.get("source_language"))
    target = normalize_language(request.payload.get("target_language"))

    if target == "zh-Hans" and (source == "zh" or source is None):
        return "download_only"

    if source and target and same_language(source, target):
        return "download_only"

    return request.task


def run_job(request: JobRequest):
    try:
        callback(f"/internal/jobs/{request.job_id}/progress", {"progress": 0, "status": "processing", "task": request.task})

        def on_progress(progress):
            callback(f"/internal/jobs/{request.job_id}/progress", progress)

        effective_task = _effective_task(request)
        result = process(request.job_id, effective_task, request.payload, on_progress)
        callback(f"/internal/jobs/{request.job_id}/complete", result)
    except Exception as exc:
        print(f"Job {request.job_id} failed: {exc}", flush=True)
        try:
            callback(f"/internal/jobs/{request.job_id}/fail", {"error": str(exc)})
        except Exception as callback_error:
            print(f"Job {request.job_id} failure callback failed: {callback_error}", flush=True)


@app.post("/jobs", status_code=202)
def create_job(request: JobRequest):
    if request.task not in SUPPORTED_TASKS:
        raise HTTPException(status_code=400, detail=f"Unsupported task: {request.task}")
    executor.submit(run_job, request)
    return {"success": True, "job_id": request.job_id, "task": request.task, "status": "accepted"}
