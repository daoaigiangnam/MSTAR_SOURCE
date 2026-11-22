# VideoDubing-AI V1 Pipeline APIs

Base URL: `/api/v1`

## Language and voice selection

The frontend must treat `target_language` as the user's selected destination language. When the destination language changes, it should automatically call the voice API and reload the voice dropdown.

There are **two different concepts**:

1. **Translation language** — the language used by the translation engine.
2. **TTS language / voice** — the language and voice actually used to generate the dubbed speech.

For Full Dubbing, the target language must have a compatible TTS voice. The backend must not silently fall back to English when a requested language has no compatible voice.

### Supported target languages

`GET /languages`

This endpoint reads the current Edge TTS voice catalog at runtime and returns the language IDs that currently have available provider voices. Therefore the list should be considered the **authoritative runtime catalog**, rather than a hard-coded list in the frontend.

Example response shape:

```json
{
  "success": true,
  "data": [
    {
      "code": "vi",
      "name": "Vietnamese",
      "locale": "vi-VN"
    },
    {
      "code": "en",
      "name": "English",
      "locale": "en-US"
    }
  ]
}
```

The exact catalog can change when Microsoft/Edge TTS adds, removes, or changes voices. The frontend should therefore populate the language dropdown from this endpoint.

### Voice catalog

`GET /voices`

Returns the complete voice catalog currently available from Edge TTS.

`GET /voices?language=vi`

Returns only voices for the selected target language.

Each voice contains:

| Field | Meaning |
|---|---|
| `id` | Exact voice ID / `ShortName` to send as `voice_id` |
| `name` | Provider-friendly voice name |
| `locale` | Full locale, for example `vi-VN` or `en-US` |
| `language` | Language ID, for example `vi` or `en` |
| `gender` | Provider-reported gender |
| `styles` | Provider voice styles, when available |

### Currently verified default voices in this project

These are the voices explicitly configured as project defaults and tested by the project team:

| Language | Language ID | Locale | Voice ID (`voice_id`) | Gender | Default | Status |
|---|---|---|---|---|---|---|
| English | `en` | `en-US` | `en-US-AriaNeural` | Female | Yes | Verified |
| Vietnamese | `vi` | `vi-VN` | `vi-VN-HoaiMyNeural` | Female | Yes | Verified |

The current worker configuration explicitly defines these two default voices. Other Edge TTS voices may be available through `/languages` and `/voices`, but they are **not automatically considered project defaults** until they are verified for the dubbing pipeline.

### Recommended frontend flow

```text
GET /languages
       ↓
User selects target_language = vi
       ↓
GET /voices?language=vi
       ↓
Display voice list
       ↓
User selects voice_id or "Auto"
       ↓
POST /jobs or POST /download
```

For a production UI, do not hard-code the full Edge TTS voice table. Fetch it from the API so the UI always reflects the provider's current catalog.

### Example: Vietnamese

```http
GET /languages
GET /voices?language=vi
```

Typical verified project voice:

```json
{
  "id": "vi-VN-HoaiMyNeural",
  "locale": "vi-VN",
  "language": "vi"
}
```

Then submit:

```json
{
  "url": "https://www.youtube.com/watch?v=...",
  "source_language": "auto",
  "target_language": "vi",
  "voice_id": "vi-VN-HoaiMyNeural"
}
```

### Example: English

```http
GET /voices?language=en
```

Verified project default:

```json
{
  "id": "en-US-AriaNeural",
  "locale": "en-US",
  "language": "en"
}
```

## Full workflow

`POST /jobs` or `POST /download`

```json
{
  "url": "https://www.youtube.com/watch?v=...",
  "source_language": "auto",
  "target_language": "vi",
  "voice_id": "vi-VN-HoaiMyNeural",
  "whisper_model": "small",
  "keep_background": true,
  "sync": true
}
```

`source_language` may be omitted or set to `auto`; the worker detects the source language. `target_language` is required for dubbing. `voice_id` is optional.

If the detected source language equals the requested target language, the worker returns the downloaded source unchanged (`mode=original`). No translation, TTS, remix or final re-render is performed.

## Independent pipeline stages

Every stage is asynchronous and returns a `job_id`. Poll `GET /jobs/:id` for progress/result.

### Extract media

`POST /pipeline/extract`

```json
{
  "input_path": "/storage/input/video.mp4",
  "output_dir": "/storage/work/media"
}
```

### Separate speech/music

`POST /pipeline/separate`

```json
{
  "audio_path": "/storage/work/media/audio.wav",
  "output_dir": "/storage/work/media"
}
```

### Transcribe

`POST /pipeline/transcribe`

```json
{
  "audio_path": "/storage/work/media/separated/vocals.wav",
  "output_dir": "/storage/work/transcript",
  "source_language": "auto",
  "whisper_model": "small"
}
```

### Translate

`POST /pipeline/translate`

```json
{
  "source_language": "en",
  "target_language": "vi",
  "translation_endpoint": "http://libretranslate:5000",
  "transcript": {
    "segments": [
      {"start": 0.0, "end": 2.1, "text": "Hello everyone."}
    ]
  }
}
```

### Target-language TTS + timing

`POST /pipeline/tts`

```json
{
  "target_language": "vi",
  "voice_id": "vi-VN-HoaiMyNeural",
  "duration": 120.0,
  "output_dir": "/storage/work/dubbing",
  "transcript": {
    "segments": [
      {
        "start": 0.0,
        "end": 2.1,
        "text": "Hello everyone.",
        "target_text": "Xin chào mọi người."
      }
    ]
  }
}
```

The worker validates that the voice language matches `target_language`, fits speech duration without destructive clipping, preserves pauses where possible, and shifts a later segment instead of overlapping voices when a translated sentence needs extra time.

### Final render

`POST /pipeline/render`

```json
{
  "video_path": "/storage/work/media/video-only.mp4",
  "background_path": "/storage/work/media/separated/background.wav",
  "dubbed_speech_path": "/storage/work/dubbing/dubbed_speech.wav",
  "output_path": "/storage/work/output/final.mp4"
}
```

## Download API

### Download only — `flag=0`

One URL:

```json
{
  "flag": 0,
  "urls": [
    "https://youtu.be/example"
  ]
}
```

Multiple URLs:

```json
{
  "flag": 0,
  "urls": [
    "https://youtu.be/example-1",
    "https://www.example.com/video/example-2",
    "https://www.example.com/video/example-3"
  ]
}
```

`flag=0` is download-only and does not enter the dubbing pipeline.

### Full Dubbing — `flag=1`

```json
{
  "flag": 1,
  "urls": [
    "https://youtu.be/example"
  ],
  "source_language": "en",
  "target_language": "vi",
  "voice_id": "vi-VN-HoaiMyNeural"
}
```

The established Full Dubbing pipeline remains unchanged: download → extract → separate → transcribe → translate → TTS → sentence timing → mix → render.

## Design rules

1. Source language may be `auto`.
2. Target language is required for dubbing.
3. `/languages` is the authoritative runtime language catalog for the configured TTS provider.
4. `/voices` is the authoritative runtime voice catalog.
5. When the target language changes in PHP/frontend, reload `/voices?language=<target_language>`.
6. Source == target means original passthrough.
7. Voice must belong to the target language.
8. If `voice_id` is omitted, the backend selects the default voice for the target language when a verified default exists.
9. Translation is performed before TTS.
10. TTS is scheduled from source speech timestamps.
11. Speech segments must not overlap.
12. Generated speech is never silently hard-trimmed to a slot.
13. Unsupported translation or voice languages fail explicitly instead of silently falling back to English.
14. The frontend should normally call `/jobs` for one-click Full Workflow; stage APIs are for testing, diagnostics and advanced workflows.
15. Do not hard-code the provider's complete voice catalog in the frontend; query `/languages` and `/voices` at runtime.
