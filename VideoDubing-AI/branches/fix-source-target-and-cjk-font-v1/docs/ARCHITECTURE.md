# Architecture

VideoDubing-AI is organized as a service-oriented, multilingual media pipeline.

```text
Client / Frontend
  |
  v
Node.js API (api/)
  |
  +--> Job / Download services
  +--> Pipeline stage APIs
  +--> Voice discovery API
  |
  +--> Python Worker (worker/)
          |
          +--> yt-dlp / FFmpeg
          +--> Speech-to-text + language detection
          +--> Speech/music separation
          +--> Translation (source -> target)
          +--> Target-language TTS / voice selection
          +--> Timing / audio fitting / overlap protection
          +--> Audio mixing
          +--> Final video rendering
  |
  v
Storage / Job metadata
```

## V1 processing rules

### Language model

- `source_language`: `auto` by default, or an explicit ISO language code.
- `target_language`: the language requested for the dubbed output.
- `tts_voice`: must belong to the target language.
- Voices are discovered from the TTS provider and filtered by target language.
- The backend must never silently use an English voice for another target language.
- Translation and TTS fail explicitly when a requested language is unsupported.

### Same-language bypass

If the actual source language and target language are the same, the downloaded source video is returned unchanged.

```text
source == target
       |
       v
Return original video
       |
       X no translation
       X no TTS
       X no audio re-mix
       X no final re-render
```

When `source_language=auto`, the worker uses transcription language detection to determine the actual source language. If the detected language equals `target_language`, it returns the original source video unchanged after detection.

### Multilingual dubbing pipeline

```text
Source media
    |
    v
Download / inspect
    |
    v
Extract audio
    |
    v
Demucs speech/background separation
    |
    v
Whisper transcription + timestamps + source language
    |
    +---- source == target ---> ORIGINAL VIDEO
    |
    v
Translation source -> target
    |
    v
Target-language TTS voice
    |
    v
Timing engine
    +-- preserve source start anchors
    +-- preserve pauses where possible
    +-- prevent segment overlap
    +-- adapt speaking rate
    +-- never silently clip translated text
    |
    v
Dubbed voice + background music
    |
    v
FFmpeg final render
    |
    v
Final MP4
```

## Timing requirements

The dubbing engine treats Whisper timestamps as the scheduling reference. TTS audio is fitted to the available segment window with adaptive speed. If a translated segment still needs extra time, the scheduler moves the next generated segment instead of overlapping voices or silently clipping words. The final renderer still enforces the source video duration.

## Stage APIs

The public API exposes independent asynchronous stages for diagnostics and advanced integrations:

- `POST /api/v1/pipeline/extract`
- `POST /api/v1/pipeline/separate`
- `POST /api/v1/pipeline/transcribe`
- `POST /api/v1/pipeline/translate`
- `POST /api/v1/pipeline/tts`
- `POST /api/v1/pipeline/render`
- `GET /api/v1/voices?language=<target>`

The normal Frontend workflow should use `POST /api/v1/jobs`, which orchestrates the complete pipeline. Stage APIs are intentionally separate so each module can be tested, monitored and replaced independently.

## Responsibilities

The API remains responsible for HTTP, authentication, job lifecycle, persistence, validation and orchestration. Python handles AI/media processing. The frontend selects source language, target language, target-language voice and workflow options, then displays job progress and the final output.
