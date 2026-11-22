# VideoDubing-AI

AI video dubbing and video download platform: download/import video, separate speech and background audio, transcribe speech, translate to a selected target language, synthesize target-language speech, synchronize sentence timing, and render the final video.

## Fixed architecture

- `api/` — Node.js REST API and job orchestration
- `worker/` — Python AI/media processing worker
- `storage/` — shared runtime media storage (ignored by Git)
- `docs/` — installation, architecture, roadmap and API contracts
- `.github/workflows/` — CI, deterministic media integration, and AI model smoke checks

The architecture remains the fixed Node API + Python worker design. Do not change the directory structure without an explicit architecture revision.

## V1 business rules

1. Source language can be `auto` or explicitly selected.
2. Target language is selected by the user for Full Dubbing.
3. TTS voices are loaded dynamically for the target language.
4. A voice must match the target language; there is no silent English fallback.
5. If detected source language equals target language, the original video is returned unchanged. No translation, TTS, remix or final re-render is performed.
6. If source and target differ, the full dubbing pipeline runs.
7. TTS timing preserves source sentence anchors and pauses where possible, prevents overlapping generated speech, and does not silently clip the end of translated sentences.
8. Download is also available as an independent operation with `flag=0`.
9. `flag=0` accepts one or many URLs and performs download-only processing without entering the dubbing pipeline.
10. `flag=1` runs the established Full Dubbing pipeline.
11. YouTube download uses the normal yt-dlp strategy first; the Android client strategy is used as a fallback when the normal strategy fails.
12. The Download module is shared by Download-only and Full Dubbing flows so download behavior remains centralized without changing the established dubbing stages.

## Runtime flows

### Download only — `flag=0`

```text
One or many video URLs
        -> Node API creates download jobs
        -> Python Worker downloads with yt-dlp
        -> completed video files
```

### Full Dubbing — `flag=1`

```text
Video URL
  -> Node API creates Job
  -> Download module / yt-dlp
  -> FFmpeg extracts video/audio
  -> Demucs separates vocals/background
  -> faster-whisper transcribes speech + word timestamps
  -> detect source language
  -> source == target ? return original : continue
  -> LibreTranslate translates transcript
  -> Edge TTS generates target-language speech
  -> timing engine fits/schedules speech sentence by sentence
  -> background + dubbed speech are mixed
  -> FFmpeg renders final.mp4
  -> API exposes the completed file
```

The Full Dubbing pipeline is kept unchanged while the Download operation is exposed separately for batch downloading.

## API

Base URL:

```text
http://SERVER:3000/api/v1
```

### Core endpoints

- `GET /health` — API health
- `GET /api/v1` — API capabilities/version
- `POST /api/v1/info` — inspect video metadata with yt-dlp
- `POST /api/v1/jobs` — one-click Full Dubbing workflow
- `POST /api/v1/download` — Download-only or Full Dubbing compatibility endpoint using `flag=0/1`
- `GET /api/v1/jobs/:id` — job status and output information
- `GET /api/v1/output/:id` — download final output
- `GET /api/v1/languages` — current runtime language catalog
- `GET /api/v1/voices` — current Edge TTS voice catalog
- `GET /api/v1/voices?language=vi` — voices for a target language
- `GET /api/v1/worker/health` — worker health

### Pipeline endpoints

- `POST /api/v1/pipeline/extract` — media extraction stage
- `POST /api/v1/pipeline/separate` — speech/background separation stage
- `POST /api/v1/pipeline/transcribe` — Whisper transcription stage
- `POST /api/v1/pipeline/translate` — translation stage
- `POST /api/v1/pipeline/tts` — target-language TTS + sentence timing stage
- `POST /api/v1/pipeline/render` — final FFmpeg render stage

Stage APIs are asynchronous and return a `job_id`; poll `/api/v1/jobs/:id`. They are intended for diagnostics, automated workflows and advanced integrations. The frontend should normally use the Full Workflow endpoint for ordinary dubbing jobs.

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
    "https://example.com/video-2",
    "https://example.com/video-3"
  ]
}
```

`flag=0` is intended for the standalone Download Manager/UI and does not enter the dubbing pipeline.

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

The established pipeline remains:

```text
Download -> Extract -> Separate -> Transcribe -> Translate
-> TTS -> Sentence Timing -> Mix -> Render
```

## Language and voice catalog

The project supports the Microsoft Edge TTS language/voice catalog exposed by the runtime `edge_tts` provider. **Do not assume that only English and Vietnamese are supported.** The table below gives the language ID used by the application, the locale, and representative female/male voice IDs. A locale may have additional voices; query `/api/v1/voices` for the complete runtime list.

> **Important:** the provider catalog is dynamic. The runtime API is authoritative. A voice shown in this reference table may be added, renamed, retired, or replaced by the provider. Always validate the selected `voice_id` through the current `/api/v1/voices` response before hard-coding it in a frontend.

### Supported languages / locales / representative voices

| Language ID | Language | Locale | Female voice | Male voice |
|---|---|---|---|---|
| `af` | Afrikaans | `af-ZA` | `af-ZA-AdriNeural` | `af-ZA-WillemNeural` |
| `am` | Amharic | `am-ET` | `am-ET-MekdesNeural` | `am-ET-AmehaNeural` |
| `ar` | Arabic | `ar-AE` | `ar-AE-FatimaNeural` | `ar-AE-HamdanNeural` |
| `ar` | Arabic | `ar-BH` | `ar-BH-LailaNeural` | `ar-BH-AliNeural` |
| `ar` | Arabic | `ar-DZ` | `ar-DZ-AminaNeural` | `ar-DZ-IsmaelNeural` |
| `ar` | Arabic | `ar-EG` | `ar-EG-SalmaNeural` | `ar-EG-ShakirNeural` |
| `ar` | Arabic | `ar-IQ` | `ar-IQ-RanaNeural` | `ar-IQ-BasselNeural` |
| `ar` | Arabic | `ar-JO` | `ar-JO-SanaNeural` | `ar-JO-TaimNeural` |
| `ar` | Arabic | `ar-KW` | `ar-KW-NouraNeural` | `ar-KW-FahedNeural` |
| `ar` | Arabic | `ar-LB` | `ar-LB-LaylaNeural` | `ar-LB-RamiNeural` |
| `ar` | Arabic | `ar-LY` | `ar-LY-ImanNeural` | `ar-LY-OmarNeural` |
| `ar` | Arabic | `ar-MA` | `ar-MA-MounaNeural` | `ar-MA-JamalNeural` |
| `ar` | Arabic | `ar-OM` | `ar-OM-AyshaNeural` | `ar-OM-AbdullahNeural` |
| `ar` | Arabic | `ar-QA` | `ar-QA-AmalNeural` | `ar-QA-MoazNeural` |
| `ar` | Arabic | `ar-SA` | `ar-SA-ZariyahNeural` | `ar-SA-HamedNeural` |
| `ar` | Arabic | `ar-SY` | `ar-SY-AmanyNeural` | `ar-SY-LaithNeural` |
| `ar` | Arabic | `ar-TN` | `ar-TN-ReemNeural` | `ar-TN-HediNeural` |
| `ar` | Arabic | `ar-YE` | `ar-YE-MaryamNeural` | `ar-YE-SalehNeural` |
| `as` | Assamese | `as-IN` | `as-IN-YashicaNeural` | `as-IN-PriyomNeural` |
| `az` | Azerbaijani | `az-AZ` | `az-AZ-BanuNeural` | `az-AZ-BabekNeural` |
| `bg` | Bulgarian | `bg-BG` | `bg-BG-KalinaNeural` | `bg-BG-BorislavNeural` |
| `bn` | Bengali / Bangla | `bn-BD` | `bn-BD-NabanitaNeural` | `bn-BD-PradeepNeural` |
| `bn` | Bengali | `bn-IN` | `bn-IN-TanishaaNeural` | `bn-IN-BashkarNeural` |
| `bs` | Bosnian | `bs-BA` | `bs-BA-VesnaNeural` | `bs-BA-GoranNeural` |
| `ca` | Catalan | `ca-ES` | `ca-ES-JoanaNeural` | `ca-ES-EnricNeural` |
| `cs` | Czech | `cs-CZ` | `cs-CZ-VlastaNeural` | `cs-CZ-AntoninNeural` |
| `cy` | Welsh | `cy-GB` | `cy-GB-NiaNeural` | `cy-GB-AledNeural` |
| `da` | Danish | `da-DK` | `da-DK-ChristelNeural` | `da-DK-JeppeNeural` |
| `de` | German | `de-AT` | `de-AT-IngridNeural` | `de-AT-JonasNeural` |
| `de` | German | `de-CH` | `de-CH-LeniNeural` | `de-CH-JanNeural` |
| `de` | German | `de-DE` | `de-DE-KatjaNeural` | `de-DE-ConradNeural` |
| `el` | Greek | `el-GR` | `el-GR-AthinaNeural` | `el-GR-NestorasNeural` |
| `en` | English | `en-AU` | `en-AU-NatashaNeural` | `en-AU-WilliamNeural` |
| `en` | English | `en-CA` | `en-CA-ClaraNeural` | `en-CA-LiamNeural` |
| `en` | English | `en-GB` | `en-GB-SoniaNeural` | `en-GB-RyanNeural` |
| `en` | English | `en-HK` | `en-HK-YanNeural` | `en-HK-SamNeural` |
| `en` | English | `en-IE` | `en-IE-EmilyNeural` | `en-IE-ConnorNeural` |
| `en` | English | `en-IN` | `en-IN-NeerjaNeural` | `en-IN-PrabhatNeural` |
| `en` | English | `en-KE` | `en-KE-AsiliaNeural` | `en-KE-ChilembaNeural` |
| `en` | English | `en-NG` | `en-NG-EzinneNeural` | `en-NG-AbeoNeural` |
| `en` | English | `en-NZ` | `en-NZ-MollyNeural` | `en-NZ-MitchellNeural` |
| `en` | English | `en-PH` | `en-PH-RosaNeural` | `en-PH-JamesNeural` |
| `en` | English | `en-SG` | `en-SG-LunaNeural` | `en-SG-WayneNeural` |
| `en` | English | `en-TZ` | `en-TZ-ImaniNeural` | `en-TZ-ElimuNeural` |
| `en` | English | `en-US` | `en-US-AriaNeural` | `en-US-GuyNeural` |
| `en` | English | `en-ZA` | `en-ZA-LeahNeural` | `en-ZA-LukeNeural` |
| `es` | Spanish | `es-AR` | `es-AR-ElenaNeural` | `es-AR-TomasNeural` |
| `es` | Spanish | `es-BO` | `es-BO-SofiaNeural` | `es-BO-MarceloNeural` |
| `es` | Spanish | `es-CL` | `es-CL-CatalinaNeural` | `es-CL-LorenzoNeural` |
| `es` | Spanish | `es-CO` | `es-CO-SalomeNeural` | `es-CO-GonzaloNeural` |
| `es` | Spanish | `es-CR` | `es-CR-MariaNeural` | `es-CR-JuanNeural` |
| `es` | Spanish | `es-CU` | `es-CU-BelkysNeural` | `es-CU-ManuelNeural` |
| `es` | Spanish | `es-DO` | `es-DO-RamonaNeural` | `es-DO-EmilioNeural` |
| `es` | Spanish | `es-EC` | `es-EC-AndreaNeural` | `es-EC-LuisNeural` |
| `es` | Spanish | `es-ES` | `es-ES-ElviraNeural` | `es-ES-AlvaroNeural` |
| `es` | Spanish | `es-GQ` | `es-GQ-TeresaNeural` | `es-GQ-JavierNeural` |
| `es` | Spanish | `es-GT` | `es-GT-MartaNeural` | `es-GT-AndresNeural` |
| `es` | Spanish | `es-HN` | `es-HN-KarlaNeural` | `es-HN-CarlosNeural` |
| `es` | Spanish | `es-MX` | `es-MX-DaliaNeural` | `es-MX-JorgeNeural` |
| `es` | Spanish | `es-NI` | `es-NI-YolandaNeural` | `es-NI-FedericoNeural` |
| `es` | Spanish | `es-PA` | `es-PA-MargaritaNeural` | `es-PA-RobertoNeural` |
| `es` | Spanish | `es-PE` | `es-PE-CamilaNeural` | `es-PE-AlexNeural` |
| `es` | Spanish | `es-PR` | `es-PR-KarinaNeural` | `es-PR-VictorNeural` |
| `es` | Spanish | `es-PY` | `es-PY-TaniaNeural` | `es-PY-MarioNeural` |
| `es` | Spanish | `es-SV` | `es-SV-LorenaNeural` | `es-SV-RodrigoNeural` |
| `es` | Spanish | `es-US` | `es-US-PalomaNeural` | `es-US-AlonsoNeural` |
| `es` | Spanish | `es-UY` | `es-UY-ValentinaNeural` | `es-UY-MateoNeural` |
| `es` | Spanish | `es-VE` | `es-VE-PaolaNeural` | `es-VE-SebastianNeural` |
| `et` | Estonian | `et-EE` | `et-EE-AnuNeural` | `et-EE-KertNeural` |
| `eu` | Basque | `eu-ES` | `eu-ES-AinhoaNeural` | `eu-ES-AnderNeural` |
| `fa` | Persian | `fa-IR` | `fa-IR-DilaraNeural` | `fa-IR-FaridNeural` |
| `fi` | Finnish | `fi-FI` | `fi-FI-SelmaNeural` | `fi-FI-HarriNeural` |
| `fil` | Filipino | `fil-PH` | `fil-PH-BlessicaNeural` | `fil-PH-AngeloNeural` |
| `fr` | French | `fr-BE` | `fr-BE-CharlineNeural` | `fr-BE-GerardNeural` |
| `fr` | French | `fr-CA` | `fr-CA-SylvieNeural` | `fr-CA-JeanNeural` |
| `fr` | French | `fr-CH` | `fr-CH-ArianeNeural` | `fr-CH-FabriceNeural` |
| `fr` | French | `fr-FR` | `fr-FR-DeniseNeural` | `fr-FR-HenriNeural` |
| `ga` | Irish | `ga-IE` | `ga-IE-OrlaNeural` | `ga-IE-ColmNeural` |
| `gl` | Galician | `gl-ES` | `gl-ES-SabelaNeural` | `gl-ES-RoiNeural` |
| `gu` | Gujarati | `gu-IN` | `gu-IN-DhwaniNeural` | `gu-IN-NiranjanNeural` |
| `he` | Hebrew | `he-IL` | `he-IL-HilaNeural` | `he-IL-AvriNeural` |
| `hi` | Hindi | `hi-IN` | `hi-IN-AnanyaNeural` | `hi-IN-AaravNeural` |
| `hr` | Croatian | `hr-HR` | `hr-HR-GabrijelaNeural` | `hr-HR-SreckoNeural` |
| `hu` | Hungarian | `hu-HU` | `hu-HU-NoemiNeural` | `hu-HU-TamasNeural` |
| `hy` | Armenian | `hy-AM` | `hy-AM-AnahitNeural` | `hy-AM-HaykNeural` |
| `id` | Indonesian | `id-ID` | `id-ID-GadisNeural` | `id-ID-ArdiNeural` |
| `is` | Icelandic | `is-IS` | `is-IS-GudrunNeural` | `is-IS-GunnarNeural` |
| `it` | Italian | `it-IT` | `it-IT-IsabellaNeural` | `it-IT-DiegoNeural` |
| `iu` | Inuktitut | `iu-Cans-CA` | `iu-Cans-CA-SiqiniqNeural` | `iu-Cans-CA-TaqqiqNeural` |
| `iu` | Inuktitut | `iu-Latn-CA` | `iu-Latn-CA-SiqiniqNeural` | `iu-Latn-CA-TaqqiqNeural` |
| `ja` | Japanese | `ja-JP` | `ja-JP-NanamiNeural` | `ja-JP-KeitaNeural` |
| `jv` | Javanese | `jv-ID` | `jv-ID-SitiNeural` | `jv-ID-DimasNeural` |
| `ka` | Georgian | `ka-GE` | `ka-GE-EkaNeural` | `ka-GE-GiorgiNeural` |
| `kk` | Kazakh | `kk-KZ` | `kk-KZ-AigulNeural` | `kk-KZ-DauletNeural` |
| `km` | Khmer | `km-KH` | `km-KH-SreymomNeural` | `km-KH-PisethNeural` |
| `kn` | Kannada | `kn-IN` | `kn-IN-SapnaNeural` | `kn-IN-GaganNeural` |
| `ko` | Korean | `ko-KR` | `ko-KR-SunHiNeural` | `ko-KR-InJoonNeural` |
| `lo` | Lao | `lo-LA` | `lo-LA-KeomanyNeural` | `lo-LA-ChanthavongNeural` |
| `lt` | Lithuanian | `lt-LT` | `lt-LT-OnaNeural` | `lt-LT-LeonasNeural` |
| `lv` | Latvian | `lv-LV` | `lv-LV-EveritaNeural` | `lv-LV-NilsNeural` |
| `mk` | Macedonian | `mk-MK` | `mk-MK-MarijaNeural` | `mk-MK-AleksandarNeural` |
| `ml` | Malayalam | `ml-IN` | `ml-IN-SobhanaNeural` | `ml-IN-MidhunNeural` |
| `mn` | Mongolian | `mn-MN` | `mn-MN-YesuiNeural` | `mn-MN-BataaNeural` |
| `mr` | Marathi | `mr-IN` | `mr-IN-AarohiNeural` | `mr-IN-ManoharNeural` |
| `ms` | Malay | `ms-MY` | `ms-MY-YasminNeural` | `ms-MY-OsmanNeural` |
| `mt` | Maltese | `mt-MT` | `mt-MT-GraceNeural` | `mt-MT-JosephNeural` |
| `my` | Burmese | `my-MM` | `my-MM-NilarNeural` | `my-MM-ThihaNeural` |
| `nb` | Norwegian Bokmål | `nb-NO` | `nb-NO-PernilleNeural` | `nb-NO-FinnNeural` |
| `ne` | Nepali | `ne-NP` | `ne-NP-HemkalaNeural` | `ne-NP-SagarNeural` |
| `nl` | Dutch | `nl-BE` | `nl-BE-DenaNeural` | `nl-BE-ArnaudNeural` |
| `nl` | Dutch | `nl-NL` | `nl-NL-FennaNeural` | `nl-NL-MaartenNeural` |
| `or` | Odia | `or-IN` | `or-IN-SubhasiniNeural` | `or-IN-SukantNeural` |
| `pa` | Punjabi | `pa-IN` | `pa-IN-VaaniNeural` | `pa-IN-OjasNeural` |
| `pl` | Polish | `pl-PL` | `pl-PL-AgnieszkaNeural` | `pl-PL-MarekNeural` |
| `ps` | Pashto | `ps-AF` | `ps-AF-LatifaNeural` | `ps-AF-GulNawazNeural` |
| `pt` | Portuguese | `pt-BR` | `pt-BR-FranciscaNeural` | `pt-BR-AntonioNeural` |
| `pt` | Portuguese | `pt-PT` | `pt-PT-RaquelNeural` | `pt-PT-DuarteNeural` |
| `ro` | Romanian | `ro-RO` | `ro-RO-AlinaNeural` | `ro-RO-EmilNeural` |
| `ru` | Russian | `ru-RU` | `ru-RU-SvetlanaNeural` | `ru-RU-DmitryNeural` |
| `si` | Sinhala | `si-LK` | `si-LK-ThiliniNeural` | `si-LK-SameeraNeural` |
| `sk` | Slovak | `sk-SK` | `sk-SK-ViktoriaNeural` | `sk-SK-LukasNeural` |
| `sl` | Slovenian | `sl-SI` | `sl-SI-PetraNeural` | `sl-SI-RokNeural` |
| `so` | Somali | `so-SO` | `so-SO-UbaxNeural` | `so-SO-MuuseNeural` |
| `sq` | Albanian | `sq-AL` | `sq-AL-AnilaNeural` | `sq-AL-IlirNeural` |
| `sr` | Serbian | `sr-Latn-RS` | `sr-Latn-RS-SophieNeural` | `sr-Latn-RS-NicholasNeural` |
| `sr` | Serbian | `sr-RS` | `sr-RS-SophieNeural` | `sr-RS-NicholasNeural` |
| `su` | Sundanese | `su-ID` | `su-ID-TutiNeural` | `su-ID-JajangNeural` |
| `sv` | Swedish | `sv-SE` | `sv-SE-SofieNeural` | `sv-SE-MattiasNeural` |
| `sw` | Kiswahili | `sw-KE` | `sw-KE-ZuriNeural` | `sw-KE-RafikiNeural` |
| `sw` | Kiswahili | `sw-TZ` | `sw-TZ-RehemaNeural` | `sw-TZ-DaudiNeural` |
| `ta` | Tamil | `ta-IN` | `ta-IN-PallaviNeural` | `ta-IN-ValluvarNeural` |
| `ta` | Tamil | `ta-LK` | `ta-LK-SaranyaNeural` | `ta-LK-KumarNeural` |
| `ta` | Tamil | `ta-MY` | `ta-MY-KaniNeural` | `ta-MY-SuryaNeural` |
| `ta` | Tamil | `ta-SG` | `ta-SG-VenbaNeural` | `ta-SG-AnbuNeural` |
| `te` | Telugu | `te-IN` | `te-IN-ShrutiNeural` | `te-IN-MohanNeural` |
| `th` | Thai | `th-TH` | `th-TH-PremwadeeNeural` | `th-TH-NiwatNeural` |
| `tr` | Turkish | `tr-TR` | `tr-TR-EmelNeural` | `tr-TR-AhmetNeural` |
| `uk` | Ukrainian | `uk-UA` | `uk-UA-PolinaNeural` | `uk-UA-OstapNeural` |
| `ur` | Urdu | `ur-IN` | `ur-IN-GulNeural` | `ur-IN-SalmanNeural` |
| `ur` | Urdu | `ur-PK` | `ur-PK-UzmaNeural` | `ur-PK-AsadNeural` |
| `uz` | Uzbek | `uz-UZ` | `uz-UZ-MadinaNeural` | `uz-UZ-SardorNeural` |
| `vi` | Vietnamese | `vi-VN` | `vi-VN-HoaiMyNeural` | `vi-VN-NamMinhNeural` |
| `wuu` | Chinese (Wu) | `wuu-CN` | `wuu-CN-XiaotongNeural` | `wuu-CN-YunzheNeural` |
| `yue` | Chinese Cantonese | `yue-CN` | `yue-CN-XiaoMinNeural` | `yue-CN-YunSongNeural` |
| `zh` | Chinese Mandarin | `zh-CN` | `zh-CN-XiaoxiaoNeural` | `zh-CN-YunxiNeural` |
| `zh` | Chinese Cantonese | `zh-HK` | `zh-HK-HiuMaanNeural` | `zh-HK-WanLungNeural` |
| `zh` | Chinese Mandarin (Taiwan) | `zh-TW` | `zh-TW-HsiaoChenNeural` | `zh-TW-YunJheNeural` |
| `zu` | isiZulu | `zu-ZA` | `zu-ZA-ThandoNeural` | `zu-ZA-ThembaNeural` |

### How to use the language IDs

Use the short `Language ID` in API requests when the API accepts a language code:

```json
{
  "source_language": "en",
  "target_language": "vi",
  "voice_id": "vi-VN-HoaiMyNeural"
}
```

For locale-specific voice selection, use the exact `voice_id` returned by the runtime voice catalog.

### Get the complete runtime catalog

Because Edge TTS is a provider-backed service and its voice list changes independently of this repository, applications should load the catalog dynamically:

```bash
curl http://SERVER:3000/api/v1/languages
curl http://SERVER:3000/api/v1/voices
curl "http://SERVER:3000/api/v1/voices?language=vi"
```

The response from `/api/v1/voices` contains the exact runtime voice IDs, locale, language, gender and other provider metadata available to the current worker.

For the API contract and request/response examples, see `docs/API_PIPELINE.md`.

## YouTube download fallback

The download layer uses yt-dlp. The normal extraction strategy is attempted first. If YouTube rejects that strategy or no usable media is returned, the worker can retry using the configured Android client strategy.

This keeps the public API simple: users can submit the normal/original YouTube URL without having to convert it to a share link.

## Installation on a new Ubuntu Server

For a new server containing only Ubuntu OS, follow:

```text
INSTALL_UBUNTU_SERVER.md
```

The installation guide covers Docker, project deployment, environment configuration, build/start, health checks, yt-dlp, TTS, Download-only testing and Full Dubbing testing.

## API documentation

Detailed API contracts, request examples, language/voice selection and Download/Dubbing workflows:

```text
docs/API_PIPELINE.md
```

## Run with Docker Compose

```bash
cp .env.example .env
# replace INTERNAL_TOKEN with a long random value

docker compose up -d --build
```

Check:

```bash
curl http://localhost:3000/health
curl http://localhost:3000/api/v1/worker/health
curl http://localhost:3000/api/v1/languages
curl http://localhost:3000/api/v1/voices?language=vi
```

## Project documentation

- `INSTALL_UBUNTU_SERVER.md` — step-by-step installation for a new Ubuntu Server
- `docs/API_PIPELINE.md` — complete API usage and contracts
- `docs/ARCHITECTURE.md` — fixed system architecture
- `docs/ROADMAP.md` — implementation roadmap

## Current V1 test scope

The following real-world flows are the primary acceptance tests:

- Download one URL with `flag=0`
- Download multiple URLs with `flag=0`
- Download videos from multiple supported platforms
- YouTube original URL with normal yt-dlp and Android fallback when required
- Full Dubbing with `flag=1`
- Sentence-level timing without overlapping speech
- Vietnamese Edge TTS output
- Final rendered video output

Individual pipeline stages remain available for diagnostics and advanced integrations, but end-to-end workflows are the primary product acceptance tests.
