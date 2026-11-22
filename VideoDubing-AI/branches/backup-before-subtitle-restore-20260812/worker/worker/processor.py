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
    """Normalize user language input for Whisper and the rest of the pipeline."""
    if language is None:
        return None
    value = str(language).strip().lower().replace("_", "-")
    if not value or value in {"auto", "automatic", "detect", "detected"}:
        return None
    return value.split("-")[0]


def _report(callback, value, **extra):
    if callback:
        callback({"progress": max(0, min(100, value)), **extra})


def _completed_original(
    job_id: str,
    info: dict,
    main_file: Path,
    media: dict,
    source_language: str | None,
    target_language: str | None,
    progress_callback=None,
) -> dict:
    media["final_path"] = str(main_file)
    media["mode"] = "original"
    media["source_language"] = source_language
    media["target_language"] = target_language
    _report(progress_callback, 100, status="completed", mode="original")
    return {
        "job_id": job_id,
        "status": "completed",
        "mode": "original",
        "id": info.get("id"),
        "title": info.get("title"),
        "thumbnail": info.get("thumbnail"),
        "duration": info.get("duration"),
        "filename": main_file.name,
        "filepath": str(main_file),
        "filesize": main_file.stat().st_size,
        "format": info.get("ext"),
        "quality": None,
        "media": media,
        "source_language": source_language,
        "target_language": target_language,
    }


def _find_main_file(job_id: str) -> Path:
    storage = Path(STORAGE_PATH) / "downloads" / job_id
    files = [path for path in storage.iterdir() if path.is_file()]
    main = max(files, key=lambda p: p.stat().st_size, default=None)
    if not main:
        raise FileNotFoundError(f"No downloaded media found for job {job_id}")
    return main


def process_download_only(job_id: str, payload: dict, progress_callback=None) -> dict:
    """Download media only; do not enter the AI pipeline."""
    storage = Path(STORAGE_PATH) / "downloads" / job_id
    _report(progress_callback, 0, status="processing", mode="download")

    def download_progress(data):
        raw = float(data.get("progress", 0))
        _report(
            progress_callback,
            raw,
            status="downloading",
            speed=data.get("speed"),
            eta=data.get("eta"),
        )

    info = download_video(
        payload["url"],
        str(storage),
        payload.get("format"),
        download_progress,
    )
    main_file = _find_main_file(job_id)

    _report(progress_callback, 100, status="completed", mode="download")
    return {
        "job_id": job_id,
        "status": "completed",
        "mode": "download",
        "id": info.get("id"),
        "title": info.get("title"),
        "thumbnail": info.get("thumbnail"),
        "duration": info.get("duration"),
        "filename": main_file.name,
        "filepath": str(main_file),
        "filesize": main_file.stat().st_size,
        "format": info.get("ext"),
        "quality": payload.get("quality"),
        "media": {"download_path": str(main_file)},
    }


def process_subtitle(job_id: str, payload: dict, progress_callback=None) -> dict:
    """Create original/translated subtitles and burn them into the downloaded video."""
    storage = Path(STORAGE_PATH) / "downloads" / job_id
    _report(progress_callback, 0, status="processing", mode="subtitle")

    def download_progress(data):
        raw = float(data.get("progress", 0))
        _report(
            progress_callback,
            raw * 0.25,
            status="downloading",
            speed=data.get("speed"),
            eta=data.get("eta"),
        )

    info = download_video(
        payload["url"],
        str(storage),
        payload.get("format"),
        download_progress,
    )
    main_file = _find_main_file(job_id)

    _report(progress_callback, 30, status="extracting")
    media_dir = storage / "media"
    media = extract_media(str(main_file), str(media_dir))

    _report(progress_callback, 45, status="separating")
    separated = prepare_audio(media["audio_path"], str(media_dir))
    media.update(separated)

    source_language = _normalize_language(payload.get("source_language"))
    _report(
        progress_callback,
        65,
        status="transcribing",
        source_language=source_language,
    )
    transcript = create_transcript(
        separated["speech_path"],
        str(media_dir / "transcript"),
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
            transcript,
            source_language,
            target_language,
            payload.get("translation_endpoint") or TRANSLATION_ENDPOINT,
        )
        subtitle_transcript = translated
        write_srt(
            translated["segments"],
            str(subtitle_srt),
            text_key="target_text",
        )
    else:
        _report(progress_callback, 75, status="subtitle_preparing")
        write_srt(transcript["segments"], str(subtitle_srt), text_key="text")

    _report(progress_callback, 85, status="subtitle_rendering")
    output_path = storage / "output" / "subtitled.mp4"
    rendered = render_subtitled(
        media["video_path"],
        str(subtitle_srt),
        str(output_path),
        # video-only.mp4 intentionally has no audio stream. Use the validated
        # original downloaded A/V file as the audio source for the final MP4.
        str(main_file),
        progress_callback=progress_callback,
    )

    media["subtitle_path"] = str(subtitle_srt)
    media["final_path"] = rendered["output_path"]
    media["mode"] = "subtitle"
    media["source_language"] = source_language
    media["target_language"] = target_language

    _report(progress_callback, 100, status="completed", mode="subtitle")
    return {
        "job_id": job_id,
        "status": "completed",
        "mode": "subtitle",
        "id": info.get("id"),
        "title": info.get("title"),
        "thumbnail": info.get("thumbnail"),
        "duration": info.get("duration"),
        "filename": Path(rendered["output_path"]).name,
        "filepath": rendered["output_path"],
        "filesize": rendered["filesize"],
        "format": "mp4",
        "quality": payload.get("quality"),
        "media": media,
        "transcript": subtitle_transcript,
        "source_language": source_language,
        "target_language": target_language,
        "subtitle_path": str(subtitle_srt),
        "output_path": rendered["output_path"],
    }


def process_download(job_id: str, payload: dict, progress_callback=None) -> dict:
    """Run the complete dubbing pipeline."""
    storage = Path(STORAGE_PATH) / "downloads" / job_id
    _report(progress_callback, 0, status="processing")

    def download_progress(data):
        raw = float(data.get("progress", 0))
        _report(
            progress_callback,
            raw * 0.25,
            status="downloading",
            speed=data.get("speed"),
            eta=data.get("eta"),
        )

    info = download_video(
        payload["url"],
        str(storage),
        payload.get("format"),
        download_progress,
    )
    main_file = _find_main_file(job_id)

    requested_source = _normalize_language(payload.get("source_language"))
    target_language = _normalize_language(payload.get("target_language"))

    if requested_source and target_language and requested_source == target_language:
        return _completed_original(
            job_id,
            info,
            main_file,
            {},
            requested_source,
            target_language,
            progress_callback,
        )

    _report(progress_callback, 30, status="extracting")
    media_dir = storage / "media"
    media = extract_media(str(main_file), str(media_dir))

    _report(progress_callback, 45, status="separating")
    separated = prepare_audio(media["audio_path"], str(media_dir))
    media.update(separated)

    _report(
        progress_callback,
        60,
        status="transcribing",
        source_language=requested_source,
    )
    transcript = create_transcript(
        separated["speech_path"],
        str(media_dir / "transcript"),
        model_size=payload.get("whisper_model", "small"),
        language=requested_source,
    )
    media["subtitle_path"] = transcript["srt_path"]

    source_language = _normalize_language(transcript.get("language")) or requested_source
    if not source_language:
        raise RuntimeError("Unable to determine source language")

    if target_language and target_language == source_language:
        return _completed_original(
            job_id,
            info,
            main_file,
            media,
            source_language,
            target_language,
            progress_callback,
        )
    if not target_language:
        raise RuntimeError("target_language is required for dubbing")

    _report(progress_callback, 70, status="translating")
    translated = translate_transcript(
        transcript,
        source_language,
        target_language,
        payload.get("translation_endpoint") or TRANSLATION_ENDPOINT,
    )
    media["translation"] = translated

    _report(progress_callback, 78, status="tts")
    voice = payload.get("tts_voice") or TTS_VOICE or default_tts_voice(target_language)
    voice_language = _normalize_language(voice)
    if voice_language != target_language:
        raise ValueError(
            f"TTS voice language '{voice_language}' does not match target language '{target_language}'"
        )

    dubbed = generate_dubbed_speech(
        translated,
        str(media_dir / "dubbing"),
        target_language,
        voice=voice,
        duration=float(info.get("duration") or 0) or None,
    )
    media["dubbed_speech_path"] = dubbed["dubbed_speech_path"]

    _report(progress_callback, 92, status="rendering")
    output_path = storage / "output" / "final.mp4"
    rendered = render_final(
        media["video_path"],
        media["background_path"],
        dubbed["dubbed_speech_path"],
        str(output_path),
    )
    media["final_path"] = rendered["output_path"]
    media["mode"] = "dubbed"
    media["source_language"] = source_language
    media["target_language"] = target_language
    _report(progress_callback, 100, status="completed", mode="dubbed")

    return {
        "job_id": job_id,
        "status": "completed",
        "mode": "dubbed",
        "id": info.get("id"),
        "title": info.get("title"),
        "thumbnail": info.get("thumbnail"),
        "duration": info.get("duration"),
        "filename": Path(rendered["output_path"]).name,
        "filepath": rendered["output_path"],
        "filesize": rendered["filesize"],
        "format": "mp4",
        "quality": payload.get("quality"),
        "media": media,
        "transcript": transcript,
        "source_language": source_language,
        "target_language": target_language,
        "voice": voice,
    }


def process_stage(job_id: str, task: str, payload: dict, progress_callback=None) -> dict:
    """Execute one independently testable pipeline stage."""
    if task == "extract":
        result = extract_media(payload["input_path"], payload["output_dir"])
    elif task == "separate":
        result = prepare_audio(payload["audio_path"], payload["output_dir"])
    elif task == "transcribe":
        source_language = _normalize_language(payload.get("source_language"))
        result = create_transcript(
            payload["audio_path"],
            payload["output_dir"],
            model_size=payload.get("whisper_model", "small"),
            language=source_language,
        )
    elif task == "translate":
        result = translate_transcript(
            payload["transcript"],
            payload.get("source_language"),
            payload.get("target_language"),
            payload.get("translation_endpoint") or TRANSLATION_ENDPOINT,
        )
    elif task == "tts":
        target = _normalize_language(payload.get("target_language"))
        if not target:
            raise ValueError("target_language is required")
        voice = payload.get("tts_voice") or default_tts_voice(target)
        if _normalize_language(voice) != target:
            raise ValueError(
                f"TTS voice language does not match target language '{target}'"
            )
        result = generate_dubbed_speech(
            payload["transcript"],
            payload["output_dir"],
            target,
            voice=voice,
            duration=payload.get("duration"),
        )
    elif task == "render":
        result = render_final(
            payload["video_path"],
            payload["background_path"],
            payload["dubbed_speech_path"],
            payload["output_path"],
        )
    else:
        raise ValueError(f"Unsupported stage task: {task}")
    _report(progress_callback, 100, status="completed", task=task)
    return {"job_id": job_id, "status": "completed", "task": task, **result}


def process(job_id: str, task: str, payload: dict, progress_callback=None) -> dict:
    if task == "download":
        return process_download(job_id, payload, progress_callback)
    if task == "download_only":
        return process_download_only(job_id, payload, progress_callback)
    if task == "subtitle":
        return process_subtitle(job_id, payload, progress_callback)
    return process_stage(job_id, task, payload, progress_callback)
