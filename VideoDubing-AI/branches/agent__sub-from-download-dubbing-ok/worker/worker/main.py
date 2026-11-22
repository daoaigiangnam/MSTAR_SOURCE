"""VideoDubing-AI media/AI worker API."""

import asyncio
import json
import os
from concurrent.futures import ThreadPoolExecutor
from urllib.request import Request, urlopen

import edge_tts
from fastapi import FastAPI, HTTPException, Query
from pydantic import BaseModel, Field

from .processor import process

app = FastAPI(title="VideoDubing-AI Worker", version="0.2.0")
executor = ThreadPoolExecutor(max_workers=2)
API_URL = os.getenv("API_URL", "http://api:3000")
INTERNAL_TOKEN = os.getenv("INTERNAL_TOKEN", "")
SUPPORTED_TASKS = {
    "download",
    "download_only",
    "extract",
    "separate",
    "transcribe",
    "translate",
    "tts",
    "render",
    "subtitle",
}


class JobRequest(BaseModel):
    job_id: str
    task: str
    payload: dict = Field(default_factory=dict)


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


def callback(path: str, payload: dict):
    data = json.dumps(payload).encode()
    headers = {"Content-Type": "application/json"}
    if INTERNAL_TOKEN:
        headers["Authorization"] = f"Bearer {INTERNAL_TOKEN}"
    request = Request(f"{API_URL}{path}", data=data, headers=headers, method="POST")
    with urlopen(request, timeout=15):
        pass


def run_job(request: JobRequest):
    try:
        callback(f"/internal/jobs/{request.job_id}/progress", {"progress": 0, "status": "processing", "task": request.task})

        def on_progress(progress):
            callback(f"/internal/jobs/{request.job_id}/progress", progress)

        result = process(request.job_id, request.task, request.payload, on_progress)
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
