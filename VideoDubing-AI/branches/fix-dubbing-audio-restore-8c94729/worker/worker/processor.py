"""End-to-end and stage-by-stage media/AI task processor."""

from pathlib import Path

from .audio_service import prepare_audio
from .config import STORAGE_PATH, TRANSLATION_ENDPOINT, TTS_VOICE, default_tts_voice
from .dubbing import generate_dubbed_speech
from .downloader import download_video
from .final_renderer import render_final
from .media import extract_media
from .subtitle_renderer import render_subtitled
from .transcription_service import create_transcript
from .translate_service import translate_transcript
from .subtitles import write_srt


def _normalize_language(language: str | None) -> str | None:
    """Normalize user language input for Whisper and the rest of the pipeline.

    Whisper/faster-whisper expects None for automatic language detection;
    the literal string 'auto' is NOT a valid language code.
    """
    if language is None:
        return None
    value = str(language).strip().lower().replace("_", "-")
    if not value or value in {"auto", "automatic", "detect", "detected"}:
        return None
    return value.split("-")[0]


def _report(callback, value, **extra):
    if callback:
        callback({"progress": max(0, min(100, value)), **extra})


def _completed_original(job_id: str, info: dict, main_file: Path, media: dict,
                        source_language: str | None, target_language: str | None,
                        progress_callback=None) -> dict:
    media["final_path"] = str(main_file)
    media["mode"] = "original"
    media["source_language"] = source_language
    media["target_language"] = target_language
    _report(progress_callback, 100, status="completed", mode="original")
    return {
        "job_id": job_id, "status": "completed", "mode": "original",
        "id": info.get("id"), "title": info.get("title"),
        "thumbnail": info.get("thumbnail"), "duration": info.get("duration"),
        "filename": main_file.name, "filepath": str(main_file),
        "filesize": main_file.stat().st_size, "format": info.get("ext"),
        "quality": None, "media": media,
        "source_language": source_language, "target_language": target_language,
    }


def _find_main_file(job_id: str) -> Path:
    storage = Path(STORAGE_PATH) / "downloads" / job_id
    files = [path for path in storage.iterdir() if path.is_file()]
    main = max(files, key=lambda p: p.stat().st_size, default=None)
    if not main:
        raise FileNotFoundError(f"No downloaded media found for job {job_id}")
    return main


def process_download_only(job_id: str, payload: dict, progress_callback=None) -> dict:
    """Download media only; do not enter the dubbing pipeline."""
    storage = Path(STORAGE_PATH) / "downloads" / job_id
    _report(progress_callback, 0, status="processing", mode="download")

    def download_progress(data):
        raw = float(data.get("progress", 0))
        _report(progress_callback, raw, status="downloading",
                speed=data.get("speed"), eta=data.get("eta"))

    info = download_video(payload["url"], str(storage), payload.get("format"), download_progress)
    files = [path for path in storage.iterdir() if path.is_file()]
    main_file = max(files, key=lambda p: p.stat().st_size, default=None)
    if not main_file:
        raise RuntimeError("Download completed without an output file")

    _report(progress_callback, 100, status="completed", mode="download")
    return {
        "job_id": job_id, "status": "completed", "mode": "download",
        "id": info.get("id"), "title": info.get("title"),
        "thumbnail": info.get("thumbnail"), "duration": info.get("duration"),
        "filename": main_file.name, "filepath": str(main_file),
        "filesize": main_file.stat().st_size, "format": info.get("ext"),
        "quality": payload.get("quality"), "media": {"download_path": str(main_file)},
    }


def process_subtitle(job_id: str, payload: dict, progress_callback=None) -> dict:
    """Create original or translated subtitles and burn them into the original video.

    target_language is optional. When omitted, the detected/original language is used.
    When provided, the transcript is translated to that single target language.
    Original audio is always preserved and no TTS is performed.
    """
    storage = Path(STORAGE_PATH) / "downloads" / job_id
    _report(progress_callback, 0, status="processing", mode="subtitle")

    def download_progress(data):
        raw = float(data.get("progress", 0))
        _report(progress_callback, raw * 0.25, status="downloading",
                speed=data.get("speed"), eta=data.get("eta"))

    info = download_video(payload["url"], str(storage), payload.get("format"), download_progress)
    files = [path for path in storage.iterdir() if path.is_file()]
    main_file = max(files, key=lambda p: p.stat().st_size, default=None)
    if not main_file:
        raise RuntimeError("Download completed without an output file")

    _report(progress_callback, 30, status="extracting")
    media_dir = storage / "media"
    media = extract_media(str(main_file), str(media_dir))

    _report(progress_callback, 45, status="separating")
    separated = prepare_audio(media["audio_path"], str(media_dir))
    media.update(separated)

    source_language = _normalize_language(payload.get("source_language"))
    _report(progress_callback, 65, status="transcribing", source_language=source_language)
    transcript = create_transcript(
        separated["speech_path"], str(media_dir / "transcript"),
        model_size=payload.get("whisper_model", "small"),
        language=source_language,
    )

    source_language = _normalize_language(transcript.get("language")) or source_language
    if not source_language:
        raise RuntimeError("Unable to determine source language")

    target_language = _normalize_language(payload.get("target_language"))
    subtitle_transcript = transcript
    subtitle_srt = media_dir / "transcript" / "subtitles.srt"

    if target_language and target_language != source_language:
        _report(progress_callback, 75, status="translating")
        translated = translate_transcript(
            transcript, source_language, target_language,
            payload.get("translation_endpoint") or TRANSLATION_ENDPOINT,
        )
        subtitle_transcript = translated
        write_srt(translated["segments"], str(media_dir / "transcript" / "subtitles.srt"), text_key="target_text")
        subtitle_srt = media_dir / "transcript" / "subtitles.srt"
    else:
        _report(progress_callback, 75, status="subtitle_preparing")

    _report(progress_callback, 85, status="subtitle_rendering")
    output_path = storage / "output" / "subtitled.mp4"
    rendered = render_subtitled(
        media["video_path"],
        str(subtitle_srt),
        str(output_path),
        audio_path=media["audio_path"],
    )

    media["subtitle_path"] = str(subtitle_srt)
    media["final_path"] = rendered["output_path"]
    media["mode"] = "subtitle"
    media["source_language"] = source_language
    media["target_language"] = target_language

    _report(progress_callback, 100, status="completed", mode="subtitle")
    return {
        "job_id": job_id, "status": "completed", "mode": "subtitle",
        "id": info.get("id"), "title": info.get("title"),
        "thumbnail": info.get("thumbnail"), "duration": info.get("duration"),
        "filename": Path(rendered["output_path"]).name,
        "filepath": rendered["output_path"], "filesize": rendered["filesize"],
        "format": "mp4", "quality": payload.get("quality"), "media": media,
        "transcript": subtitle_transcript,
        "source_language": source_language, "target_language": target_language,
        "subtitle_path": str(subtitle_srt), "output_path": rendered["output_path"],
    }


def process_download(job_id: str, payload: dict, progress_callback=None) -> dict:
    storage = Path(STORAGE_PATH) / "downloads" / job_id
    _report(progress_callback, 0, status="processing")

    def download_progress(data):
        raw = float(data.get("progress", 0))
        _report(progress_callback, raw * 0.25, status="downloading",
                speed=data.get("speed"), eta=data.get("eta"))

    info = download_video(payload["url"], str(storage), payload.get("format"), download_progress)
    files = [path for path in storage.iterdir() if path.is_file()]
    main_file = max(files, key=lambda p: p.stat().st_size, default=None)
    if not main_file:
        raise RuntimeError("Download completed without an output file")

    _report(progress_callback, 100, status="completed")
    return {
        "job_id": job_id, "status": "completed", "mode": "download",
        "id": info.get("id"), "title": info.get("title"),
        "thumbnail": info.get("thumbnail"), "duration": info.get("duration"),
        "filename": main_file.name, "filepath": str(main_file),
        "filesize": main_file.stat().st_size, "format": info.get("ext"),
        "quality": payload.get("quality"), "media": {"download_path": str(main_file)},
    }


def process(job_id: str, task: str, payload: dict, progress_callback=None) -> dict:
    if task == "download":
        return process_download(job_id, payload, progress_callback)
    if task == "download_only":
        return process_download_only(job_id, payload, progress_callback)
    if task == "subtitle":
        return process_subtitle(job_id, payload, progress_callback)
    return process_stage(job_id, task, payload, progress_callback)
