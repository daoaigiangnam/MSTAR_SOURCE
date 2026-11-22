# Tài liệu Phát triển Dự án Video Dubbing AI - Final

> **Tài liệu chuẩn Final về kiến trúc, phát triển, kiểm thử, triển khai và nguyên tắc bảo trì của dự án Video Dubbing AI.**
>
> **Repository nguồn:** `daoaigiangnam/Long_Tieng_Phu_De_Video`
>
> **Branch nguồn được kiểm tra:** `main`
>
> **Repository tài liệu:** `daoaigiangnam/System_Operation_and_Deployment_Documentation`
>
> **Ngày tổng hợp:** 16/08/2026
>
> **Trạng thái:** FINAL / Baseline phát triển

---

## 1. Mục đích và phạm vi

Tài liệu này là tài liệu phát triển chuẩn của dự án **Video Dubbing AI**, được tổng hợp từ source code và tài liệu đang tồn tại trên branch `main` của repository nguồn.

Mục tiêu của tài liệu là tạo một **baseline duy nhất** để đội phát triển, vận hành và bảo trì có thể dựa vào đó khi:

- Phát triển tính năng mới.
- Sửa lỗi.
- Kiểm thử.
- Build Docker image.
- Deploy lên server.
- Theo dõi Job và media pipeline.
- Bảo trì database và storage.
- Thay thế hoặc nâng cấp một AI/media provider mà không phá vỡ kiến trúc tổng thể.
- Đối chiếu code với thiết kế đã được chốt.

Phạm vi bao gồm:

1. Kiến trúc tổng thể.
2. Frontend/PHP integration.
3. Node.js REST API.
4. Python AI/media Worker.
5. Download và media inspection.
6. Speech separation.
7. Speech-to-text và language detection.
8. Translation.
9. Target-language TTS.
10. Sentence timing và audio fitting.
11. Audio mixing và final rendering.
12. Subtitle pipeline.
13. Job lifecycle và persistence.
14. Storage/cleanup.
15. Docker Compose.
16. CI/CD và GitHub Actions.
17. Kiểm thử và smoke/integration test.
18. Security boundaries.
19. Quy tắc phát triển và bảo trì.
20. Roadmap mở rộng sau baseline.

---

# 2. Nguyên tắc Final cần giữ nguyên

## 2.1. Kiến trúc cố định

Kiến trúc chuẩn là:

```text
Frontend / PHP / Client
        |
        | HTTP JSON
        v
Node.js API
        |
        +-------------------------+
        |                         |
        v                         v
   Job / DB / API          Python Worker
                                  |
              +-------------------+-------------------+
              |                   |                   |
              v                   v                   v
           yt-dlp              FFmpeg         AI / Media modules
                                                      |
                             +------------------------+-----------------------+
                             |                        |                       |
                             v                        v                       v
                         Whisper                 Translation               Edge TTS
                       / faster-whisper        / LibreTranslate          target voice
                             |                        |                       |
                             +------------------------+-----------------------+
                                                      |
                                                      v
                                             Timing / Audio / Render
                                                      |
                                                      v
                                                Final MP4 / SRT
```

**Không tự ý chuyển sang kiến trúc khác** nếu chưa có một architecture revision được phê duyệt.

Các thư mục logic cốt lõi được giữ nguyên:

```text
api/       Node.js REST API và orchestration
worker/    Python AI/media worker
storage/   runtime media storage, không commit vào Git
FrontEnd/  PHP/web integration và UI hiện hữu
docs/      tài liệu kỹ thuật
cleanup/   cleanup service
.github/   CI/CD automation
```

---

# 3. Trạng thái baseline nguồn

Repository `Long_Tieng_Phu_De_Video` hiện sử dụng branch mặc định `main`.

Source tree thể hiện đầy đủ các lớp:

- `api/`
- `worker/`
- `FrontEnd/`
- `database/`
- `cleanup/`
- `docs/`
- `docker-compose.yml`
- `.env.example`
- `.github/workflows/`

Ngoài phần code, repository hiện có các tài liệu về API, pipeline, architecture, installation, deployment update, cleanup và roadmap. Đây là cơ sở để tài liệu Final được xây dựng theo code thực tế thay vì chỉ dựa trên thiết kế lý thuyết.

---

# 4. Mô hình trách nhiệm của từng lớp

## 4.1. Frontend / PHP

Frontend có trách nhiệm:

- Nhận URL hoặc danh sách URL từ người dùng.
- Cho người dùng chọn chế độ Download / Dubbing / Subtitle.
- Chọn source language hoặc `auto`.
- Chọn target language.
- Gọi `/languages` và `/voices` để lấy catalog runtime.
- Gửi Job tới Node API.
- Theo dõi Job.
- Hiển thị tiến trình và kết quả.
- Cho phép tải output.

Frontend **không tự thực hiện AI processing**.

## 4.2. Node.js API

Node.js là lớp control plane.

Trách nhiệm:

- HTTP/JSON API.
- Validation request.
- Authentication/authorization ở lớp public integration khi được cấu hình.
- Job lifecycle.
- Persistence.
- Dispatch Job tới Worker.
- Proxy/health check Worker.
- Output download.
- Language/voice catalog exposure.
- Orchestration của pipeline.
- Không trực tiếp thực hiện CPU-heavy AI/media processing.

## 4.3. Python Worker

Python là data plane / processing plane.

Trách nhiệm:

- Download media.
- Inspect media.
- Extract video/audio.
- Speech/music separation.
- Whisper/faster-whisper transcription.
- Language detection.
- Translation client.
- Edge TTS.
- Timing.
- Audio fitting.
- Audio mix.
- Subtitle generation/rendering.
- FFmpeg final rendering.

## 4.4. LibreTranslate

LibreTranslate là service translation nội bộ.

Worker gọi qua HTTP trong Docker network.

Không để frontend gọi LibreTranslate trực tiếp.

## 4.5. Cleanup

Cleanup service chịu trách nhiệm xoá media/job artifacts cũ theo TTL cấu hình.

Cleanup phải được xem là **operational safety mechanism**, không phải một bước trong AI pipeline.

---

# 5. Docker runtime chuẩn

Docker Compose hiện định nghĩa 4 service chính:

| Service | Vai trò | Public/Internal |
|---|---|---|
| `api` | Node.js REST API | Public `3000` |
| `worker` | Python AI/media worker | Internal `8000` |
| `libretranslate` | Translation provider | Internal `5000` |
| `cleanup` | TTL cleanup | Internal |

Runtime dependencies:

```text
api
  -> worker healthy

worker
  -> libretranslate healthy
```

Shared storage:

```text
./storage -> /app/storage
```

API data:

```text
./api/data -> /app/data
```

LibreTranslate persistent model volume:

```text
libretranslate_models
```

`INTERNAL_TOKEN` phải tồn tại trước khi Compose khởi động API/Worker.

---

# 6. Media storage model

Storage runtime không được commit vào Git.

Một Job cần có namespace riêng, ví dụ:

```text
storage/
└── downloads/
    └── <job_id>/
        ├── input/
        ├── work/
        └── output/
            └── final.mp4
```

Nguyên tắc:

1. Job không được ghi đè artifact của Job khác.
2. Path output phải được kiểm tra nằm trong storage root.
3. Không nhận path filesystem tuỳ ý từ client rồi phục vụ trực tiếp.
4. File cuối cùng phải được liên kết với Job metadata.
5. Cleanup chỉ xoá artifact đã quá TTL và không còn trạng thái đang chạy.

---

# 7. Job lifecycle

Job là đơn vị xử lý trung tâm.

Mô hình tổng quát:

```text
waiting
   |
   v
running
   |
   +-----------> failed
   |
   v
completed
```

Các field nghiệp vụ quan trọng:

```text
id
url
extractor
type
title
thumbnail
duration
format
quality
filename
filepath
filesize
progress
speed
eta
status
retry_count
worker
metadata
error
created_at
updated_at
finished_at
```

Nguyên tắc:

- Job được tạo trước khi xử lý.
- Worker nhận `job_id` và thực hiện xử lý bất đồng bộ.
- Client không chờ toàn bộ quá trình trong một HTTP request dài.
- Client polling `GET /api/v1/jobs/:id`.
- Khi completed, API cung cấp output URL.
- Khi failed, error phải được lưu vào Job.

---

# 8. Các workflow nghiệp vụ chính

## 8.1. Download Only

```text
URL(s)
  -> Node API
  -> Job
  -> Python downloader / yt-dlp
  -> completed file
```

Không chạy:

```text
Whisper
Translation
TTS
Timing
Audio mixing
```

Đây là luồng được expose độc lập để dùng như Download Manager.

## 8.2. Full Dubbing

```text
Source video
   |
   v
Download
   |
   v
Extract media
   |
   v
Separate speech/background
   |
   v
Whisper transcription + timestamps + source language
   |
   +------ source == target ------> ORIGINAL VIDEO
   |
   v
Translation source -> target
   |
   v
Target-language TTS
   |
   v
Sentence timing / audio fitting
   |
   v
Mix dubbed speech + background
   |
   v
FFmpeg final rendering
   |
   v
Final MP4
```

## 8.3. Subtitle workflow

Subtitle là một pipeline riêng về logic nghiệp vụ:

```text
Download
 -> Extract audio
 -> Speech separation nếu cần
 -> Whisper
 -> Source language detection
 -> Optional translation
 -> SRT generation
 -> Optional burn-in
 -> giữ audio gốc
```

Subtitle không tự động chuyển thành dubbing nếu người dùng không yêu cầu TTS.

---

# 9. Download subsystem

Download module là subsystem dùng chung cho Download-only và Full Dubbing.

Nguyên tắc:

- Centralize download behavior.
- Không duplicate logic yt-dlp giữa nhiều workflow.
- Có inspect/info trước khi cần.
- Giữ fallback strategy của project.

Download strategy hiện tại ưu tiên yt-dlp thông thường; Android client strategy là fallback khi strategy thông thường thất bại.

Không được để mỗi route tự có một implementation downloader riêng.

---

# 10. Language model

## 10.1. Source language

`source_language`:

- Có thể là mã ISO/ứng dụng tương ứng.
- Hoặc `auto`.

Khi `auto`, Worker phải truyền giá trị phù hợp với Whisper/faster-whisper để model thực sự tự phát hiện.

Không được giả định source là English.

## 10.2. Target language

`target_language` là language của output.

Ví dụ:

```text
source = ko
target = vi
```

→ Korean speech được translate và TTS sang Vietnamese.

## 10.3. Runtime language catalog

Frontend không nên hard-code toàn bộ provider catalog.

Sử dụng:

```http
GET /api/v1/languages
```

Catalog runtime là nguồn tham chiếu authoritative cho language capability hiện tại của TTS provider cấu hình.

---

# 11. Voice selection

Voice catalog được lấy runtime:

```http
GET /api/v1/voices
GET /api/v1/voices?language=vi
```

Khi target language thay đổi:

```text
target_language changed
        |
        v
GET /voices?language=<target>
        |
        v
refresh voice dropdown
```

Quy tắc bắt buộc:

1. Voice phải cùng target language.
2. Không tự động fallback sang English khi target không hỗ trợ.
3. Nếu `voice_id` bỏ trống, backend có thể chọn verified default voice của target language.
4. Provider catalog có thể thay đổi theo thời gian; không coi bảng hard-code là authoritative.

Các default voice đã được tài liệu dự án xác nhận gồm:

```text
English:    en-US-AriaNeural
Vietnamese: vi-VN-HoaiMyNeural
```

Các voice khác phải được xác minh trước khi coi là project default.

---

# 12. Translation subsystem

Translation flow:

```text
Whisper transcript
      |
      v
Normalize language code
      |
      v
LibreTranslate /translate
      |
      v
Translated transcript
```

Payload chuẩn:

```json
{
  "q": "text",
  "source": "en",
  "target": "vi",
  "format": "text"
}
```

Response yêu cầu:

```json
{
  "translatedText": "..."
}
```

Phải kiểm tra:

- HTTP error.
- Connection error.
- Provider error object.
- Empty translation.
- Unsupported language.

## 12.1. Language alias normalization

Các mã ngôn ngữ không hoàn toàn tương thích giữa Whisper và LibreTranslate phải được normalize trước khi gọi provider.

Ví dụ tiếng Trung:

```text
zh
zh-CN
zh-Hans
```

có thể cần map về mã provider hợp lệ như:

```text
zh-Hans
```

Không được giả định mã của một provider có thể gửi nguyên trạng cho provider khác.

---

# 13. Speech-to-text

Whisper/faster-whisper là nguồn tham chiếu timestamp cho dubbing.

Worker cần giữ ít nhất:

```text
segment.start
segment.end
segment.text
source_language
```

Khi cần timing chính xác hơn, word timestamps được sử dụng theo khả năng của cấu hình model.

Nguyên tắc:

- Whisper chịu trách nhiệm transcription và detection.
- Timestamp là dữ liệu đầu vào cho timing engine.
- Không hard-code source language nếu người dùng chọn auto.
- Model phải được cấu hình nhất quán giữa local, CI và production.

---

# 14. Speech / background separation

Pipeline dùng separation để tách speech khỏi background audio trước khi dubbing.

Logic tổng quát:

```text
Original audio
      |
      v
Separation model
      |
      +---- vocals / speech
      |
      +---- background / music
```

Mục tiêu:

- Giữ lại background/music khi cần.
- Loại speech nguồn để thay bằng speech target.
- Không làm mất video stream.

Separation là một processing stage độc lập và phải có test riêng trước khi ghép vào full pipeline.

---

# 15. TTS

TTS phải tạo speech bằng voice thuộc target language.

Workflow:

```text
Translated transcript
        |
        v
Target language validation
        |
        v
Voice selection
        |
        v
Edge TTS
        |
        v
Speech segments
```

Không được:

- Dùng English voice cho target không phải English.
- Âm thầm đổi target language.
- Cắt cụt sentence chỉ để ép vừa slot.

Nếu provider không có voice tương thích, Job phải fail rõ ràng hoặc route về policy đã được xác định; không được silent fallback.

---

# 16. Timing và audio fitting

Timing engine sử dụng timestamp từ Whisper làm scheduling reference.

Mỗi segment có:

```text
start
end
text
translated_text
tts_audio
```

Nguyên tắc:

1. Giữ start anchor của source khi có thể.
2. Giữ pause tự nhiên khi có thể.
3. Điều chỉnh speaking rate để TTS phù hợp duration.
4. Không cho speech segment bị overlap.
5. Không silently clip câu dịch.
6. Nếu câu dịch dài hơn slot, scheduler phải xử lý bằng timing policy thay vì cắt mất nội dung.
7. Final render vẫn phải nằm trong duration video nguồn theo policy renderer.

Pipeline timing:

```text
Whisper timestamps
       |
       v
Target speech duration
       |
       v
Fit / speed adjustment
       |
       v
Overlap protection
       |
       v
Timeline
```

Đây là module nhạy cảm. Không thay đổi timing algorithm chỉ để “làm cho chạy được”; mọi thay đổi phải có regression test.

---

# 17. Audio mixing và final render

Sau khi tạo target speech:

```text
Background music
       +
Dubbed speech
       |
       v
Audio mixer
       |
       v
Mixed audio
```

Sau đó:

```text
Source video stream
       +
Mixed target audio
       |
       v
FFmpeg
       |
       v
Final MP4
```

Renderer phải bảo đảm:

- Video duration ổn định.
- Audio không bị thiếu cuối câu ngoài policy đã chốt.
- Codec/container tương thích output.
- File có thể download và play được.

---

# 18. Same-language bypass

Đây là business rule bắt buộc.

Nếu:

```text
actual_source_language == target_language
```

thì:

```text
Return original source video
```

Không thực hiện:

```text
Translation
TTS
Audio remix
Final re-render
```

Lý do:

- Không cần xử lý AI.
- Giảm CPU/GPU.
- Giảm thời gian.
- Tránh làm giảm chất lượng audio/video khi không cần thiết.

Nếu source là `auto`, worker phải detect trước khi quyết định bypass.

---

# 19. Public API baseline

Base path:

```text
/api/v1
```

Các endpoint chính:

```text
GET  /health
GET  /api/v1
POST /api/v1/info
POST /api/v1/jobs
GET  /api/v1/jobs/:id
GET  /api/v1/output/:id
GET  /api/v1/languages
GET  /api/v1/voices
GET  /api/v1/voices?language=<target>
GET  /api/v1/worker/health
POST /api/v1/download
```

Stage APIs:

```text
POST /api/v1/pipeline/extract
POST /api/v1/pipeline/separate
POST /api/v1/pipeline/transcribe
POST /api/v1/pipeline/translate
POST /api/v1/pipeline/tts
POST /api/v1/pipeline/render
```

Stage APIs là asynchronous API để:

- Diagnostic.
- Integration.
- Automated workflow.
- Test từng stage.
- Thay thế từng provider/module.

Frontend production thông thường nên dùng full workflow thay vì tự orchestration từng stage.

---

# 20. Download API contract

## 20.1. Download Only — `flag=0`

```json
{
  "flag": 0,
  "urls": [
    "https://example.com/video-1",
    "https://example.com/video-2"
  ]
}
```

Kết quả:

```text
download jobs
    -> video files
```

Không đi qua dubbing pipeline.

## 20.2. Full Dubbing — `flag=1`

Ví dụ:

```json
{
  "flag": 1,
  "urls": [
    "https://example.com/video"
  ],
  "source_language": "auto",
  "target_language": "vi",
  "voice_id": "vi-VN-HoaiMyNeural"
}
```

Pipeline:

```text
Download
-> Extract
-> Separate
-> Transcribe
-> Detect
-> Translate
-> TTS
-> Timing
-> Mix
-> Render
```

---

# 21. Stage API rules

Mỗi stage API phải:

1. Nhận input contract rõ ràng.
2. Tạo hoặc liên kết Job.
3. Xử lý asynchronous.
4. Trả `job_id`.
5. Cập nhật progress/status.
6. Ghi error nếu thất bại.
7. Có output artifact xác định.

Stage API không được làm thay đổi contract của full workflow một cách âm thầm.

---

# 22. PHP / Frontend integration chuẩn

Frontend hiện hữu có thể dùng PHP để gọi Node API.

Mô hình:

```text
Browser
   |
   v
PHP / FrontEnd
   |
   | HTTP JSON
   v
Node API :3000
   |
   v
Python Worker
```

PHP không cần chứa các dependency AI nặng.

### Request mẫu từ PHP

```php
$payload = [
    'flag' => 1,
    'urls' => [$videoUrl],
    'source_language' => 'auto',
    'target_language' => 'vi',
    'voice_id' => 'vi-VN-HoaiMyNeural'
];
```

PHP gửi JSON tới Node API, nhận `job_id`, sau đó polling trạng thái.

### Polling model

```text
POST /download
       |
       v
202 + job ids
       |
       v
GET /jobs/:id
       |
       +-- processing -> poll
       |
       +-- completed -> output_url
       |
       +-- failed -> error
```

---

# 23. Database và persistence

Node API sử dụng database runtime cho Job metadata.

Baseline hiện tại có:

```text
api/data/
```

và database schema/migration theo repository.

Nguyên tắc:

- Schema thay đổi phải có migration.
- Không sửa production database thủ công rồi bỏ migration.
- Migration phải idempotent hoặc có cơ chế quản lý trạng thái rõ ràng.
- Job record phải tồn tại đủ lâu để monitor, audit và cleanup logic hoạt động.
- Metadata provider không nên phá vỡ schema core.

Nếu thay SQLite bằng DB khác trong tương lai, đó là architecture/data-layer revision, không phải refactor nhỏ.

---

# 24. Cleanup / TTL

Cleanup là service độc lập.

Các biến cấu hình:

```text
CLEANUP_ENABLED
CLEANUP_TTL_HOURS
CLEANUP_INTERVAL_MINUTES
JOBS_DIR
DATABASE_PATH
```

Nguyên tắc:

```text
completed/failed + quá TTL
        |
        v
xoá artifact
        |
        v
giữ metadata cần thiết theo retention policy
```

Không được xoá:

- Job đang `running`.
- Artifact đang được dùng.
- File chưa hết TTL.

TTL phải được cấu hình rõ trên production.

---

# 25. Environment configuration

Không commit secret thật vào Git.

`.env.example` chỉ chứa placeholder/documented variables.

Các biến quan trọng gồm:

```text
INTERNAL_TOKEN
PORT
DATABASE_PATH
STORAGE_PATH
WORKER_URL
API_URL
TRANSLATION_ENDPOINT
TTS_VOICE
CLEANUP_ENABLED
CLEANUP_TTL_HOURS
CLEANUP_INTERVAL_MINUTES
```

Production `.env` phải được quản lý ngoài source control.

---

# 26. Security baseline

## 26.1. Internal API

Worker không phải public API.

Trao đổi API ↔ Worker phải sử dụng internal network và `INTERNAL_TOKEN`.

## 26.2. Output path security

Không cho client truyền filesystem path tuỳ ý.

Output serving phải:

- Resolve path.
- Kiểm tra nằm trong storage root.
- Không cho path traversal.

## 26.3. Upload/media safety

Media input có thể lớn và không đáng tin cậy.

Phải giới hạn:

- URL input size.
- Job count/batch size theo policy.
- Storage quota.
- Execution timeout.
- Cleanup TTL.

## 26.4. Secrets

Không commit:

```text
.env
API keys
password
tokens
private credentials
```

## 26.5. Public exposure

Production nên đặt Node API sau reverse proxy/TLS thay vì expose thẳng port `3000` ra Internet nếu kiến trúc deployment không yêu cầu.

---

# 27. CI baseline

Repository hiện có GitHub Actions cho CI.

CI thực hiện các nhóm kiểm tra:

### Worker

- Python 3.11.
- Install dependencies.
- Install FFmpeg.
- `compileall`.
- Pytest unit/contract tests.
- Bỏ qua test integration trong job unit baseline khi cần.

### API

- Node.js 20.
- `npm install`.
- `npm test`.

### Compose

```bash
docker compose --env-file .env.example config -q
```

### Docker build

Build:

```text
videodubing-worker:ci
videodubing-api:ci
```

Do đó một thay đổi chỉ được xem là baseline hợp lệ khi vượt qua cả application test và container validation. fileciteturn14file0L2-L10

---

# 28. AI / media integration test

Ngoài CI cơ bản, project có workflow AI Integration để chạy deterministic media/audio tests.

Workflow cài:

```text
Python 3.11
FFmpeg
worker requirements
pytest
```

Sau đó kiểm tra:

```text
ffmpeg
ffprobe
media fixture
audio pipeline
media pipeline
```

Workflow được trigger theo schedule và khi push vào `main`. fileciteturn15file0L2-L10

Nguyên tắc:

- Unit test không thay thế media integration test.
- Media pipeline phải có fixture deterministic.
- Regression của audio/video phải được phát hiện tự động.

---

# 29. CD baseline

CD chỉ publish Docker images sau khi CI `main` thành công.

Workflow publish lên GitHub Container Registry:

```text
ghcr.io/<owner>/videodubing-api
 ghcr.io/<owner>/videodubing-worker
```

Mỗi image có:

```text
:<commit-sha>
:latest
```

CI/CD sử dụng Docker Buildx và GitHub Actions cache. fileciteturn16file0L2-L10

Đây là baseline release flow:

```text
Developer push main
        |
        v
CI
 |-- worker tests
 |-- API tests
 |-- compose validation
 |-- Docker build
        |
        v
AI/media integration checks
        |
        v
CD
 |-- build API image
 |-- build Worker image
 |-- push GHCR
```

---

# 30. Deployment chuẩn

## 30.1. Chuẩn bị

Production server cần:

- Linux server phù hợp.
- Docker Engine.
- Docker Compose plugin.
- Git.
- Đủ CPU/RAM/storage cho media jobs.
- Network tới các provider cần thiết.

## 30.2. Deploy

```bash
git clone <repo>
cd Long_Tieng_Phu_De_Video
cp .env.example .env
nano .env

docker compose pull
docker compose build
docker compose up -d
```

Kiểm tra:

```bash
docker compose ps
docker compose logs -f api
docker compose logs -f worker
```

Health:

```bash
curl http://127.0.0.1:3000/health
curl http://127.0.0.1:3000/api/v1/worker/health
```

---

# 31. Quy trình release an toàn

Mỗi thay đổi production phải đi theo chuỗi:

```text
Code change
   |
   v
Local test
   |
   v
CI
   |
   v
AI/media regression nếu ảnh hưởng pipeline
   |
   v
Build image
   |
   v
Deploy staging/test
   |
   v
Smoke test
   |
   v
Production
```

Không merge một thay đổi media-critical chỉ dựa vào việc API trả HTTP 200.

---

# 32. Smoke test sau deploy

Tối thiểu kiểm tra:

### API

```bash
curl -fsS http://127.0.0.1:3000/health
```

### Worker

```bash
curl -fsS http://127.0.0.1:3000/api/v1/worker/health
```

### Languages

```bash
curl -fsS http://127.0.0.1:3000/api/v1/languages
```

### Voices

```bash
curl -fsS 'http://127.0.0.1:3000/api/v1/voices?language=vi'
```

### Info

```bash
curl -fsS -X POST http://127.0.0.1:3000/api/v1/info \
  -H 'Content-Type: application/json' \
  -d '{"url":"<TEST_URL>"}'
```

### Job

Tạo một test job và theo dõi:

```bash
curl -fsS http://127.0.0.1:3000/api/v1/jobs/<JOB_ID>
```

### Output

```bash
curl -I http://127.0.0.1:3000/api/v1/output/<JOB_ID>
```

---

# 33. Regression checklist cho Full Dubbing

Trước release của các thay đổi dubbing:

- Download thành công.
- Extract media thành công.
- Separation có vocals/background.
- Whisper trả transcript.
- Source language detection đúng.
- Translation trả text không rỗng.
- Target voice match target language.
- TTS tạo đủ segments.
- Không overlap segment.
- Không silent clipping cuối câu.
- Background được giữ theo option.
- Final MP4 play được.
- Audio/video duration hợp lệ.
- Job tiến qua completed.
- Output URL hoạt động.

---

# 34. Regression checklist cho Subtitle

- Transcript đúng.
- Language source đúng.
- Translation đúng khi có target.
- Timestamp hợp lệ.
- SRT UTF-8.
- Font Unicode render được khi burn-in.
- Audio gốc không bị thay đổi ngoài policy.
- Final video play được.

---

# 35. Xử lý lỗi

Mọi lỗi cần phân loại:

```text
Input validation error
Provider error
Download error
Media processing error
AI model error
Translation error
TTS error
Timing error
Render error
Storage error
Internal API error
```

Job phải lưu error context đủ để debug nhưng không leak secret.

Ví dụ:

```json
{
  "status": "failed",
  "error": {
    "stage": "translate",
    "code": "UNSUPPORTED_LANGUAGE",
    "message": "Translation target is not supported"
  }
}
```

---

# 36. Retry policy

Không retry mù mọi lỗi.

Có thể retry:

- Temporary network error.
- Provider timeout có khả năng transient.
- Internal worker transient failure.

Không retry vô hạn:

- Invalid URL.
- Unsupported language.
- Invalid voice.
- Corrupt input.
- Deterministic render error chưa sửa.

`retry_count` phải được giới hạn.

---

# 37. Observability

Tối thiểu phải theo dõi:

```text
API health
Worker health
Job status
Job progress
Job failure rate
Translation failure
TTS failure
Storage usage
Cleanup execution
Docker container health
```

Log cần có:

```text
timestamp
job_id
stage
status
error code
elapsed time
```

Không log secret hoặc token.

---

# 38. Quy tắc thay đổi code

## Không tự ý thay đổi

Các module sau được xem là pipeline-critical:

```text
dubbing.py
audio_fit.py
audio_mixer.py
timing.py
final_renderer.py
subtitle_renderer.py
transcriber.py
translate_service.py
tts_edge.py
```

Nếu thay đổi:

1. Xác định regression.
2. Tạo test reproducer.
3. Sửa tối thiểu.
4. Chạy unit test.
5. Chạy media integration test.
6. Chạy full CI.
7. Kiểm tra sample output bằng mắt/tai.

---

# 39. Quy tắc không phá kiến trúc

Không được tự ý:

- Gộp Node và Python thành một runtime chỉ để giảm số container.
- Chuyển AI processing vào PHP.
- Cho frontend gọi thẳng worker.
- Cho frontend gọi trực tiếp LibreTranslate.
- Duplicate downloader trong từng route.
- Hard-code danh sách voice provider thay cho runtime catalog.
- Silent fallback language/voice.
- Đổi schema Job mà không migration.
- Đổi storage path contract mà không update API.

Mọi thay đổi kiến trúc phải được ghi thành architecture revision trước khi code.

---

# 40. Versioning và compatibility

API giữ namespace:

```text
/api/v1
```

Không breaking change trực tiếp trong V1 nếu không có kế hoạch migration.

Khi cần breaking change lớn:

```text
/api/v2
```

Song song migration:

```text
v1 -> v2
```

phải được tài liệu hóa.

---

# 41. Provider abstraction

Các provider cần được coi là replaceable components:

```text
Downloader provider
Translation provider
TTS provider
Speech-to-text provider
Separation model
```

Business logic không nên phụ thuộc quá sâu vào provider-specific response.

Ví dụ:

```text
Provider voice catalog
       |
       v
Voice normalized model
       |
       v
Application
```

Tương tự với translation và transcription.

---

# 42. Performance principles

Media AI là workload CPU/GPU intensive.

Nguyên tắc:

- Không block Node event loop bằng AI processing.
- Worker xử lý bất đồng bộ.
- Giới hạn concurrency theo tài nguyên.
- Theo dõi CPU/RAM/storage.
- Không giữ media lớn trong memory nếu không cần.
- Dùng streaming/file-based processing khi phù hợp.
- Cleanup thường xuyên.
- Cache model/provider data ở mức có lợi mà không gây stale behavior nghiêm trọng.

---

# 43. Scale-out direction

Kiến trúc hiện tại cho phép mở rộng Worker mà không cần đổi frontend/API contract:

```text
                +--> Worker 1
Node API ------+--> Worker 2
                +--> Worker 3
```

Điều kiện tương lai:

- Job queue thực sự.
- Worker registry.
- Shared object storage.
- Concurrency scheduler.
- GPU-aware scheduling.

Đây là hướng mở rộng sau baseline, không phải thay đổi V1 ngay lập tức.

---

# 44. GPU direction

Current architecture không khóa vào CPU-only.

Về sau có thể có:

```text
CPU Worker
GPU Worker
```

với job routing theo model/workload.

Nhưng contract API và Job phải giữ nguyên.

---

# 45. Backup và disaster recovery

Cần backup tối thiểu:

```text
Application source -> Git
Database -> backup riêng
Production .env / secret inventory -> secure backup
Persistent LibreTranslate models -> theo nhu cầu
User/output media -> theo retention/business policy
```

Không coi Git là backup duy nhất của database hoặc user-generated media.

---

# 46. Migration / restore

Khi chuyển server:

```text
Provision server
   |
   v
Install Docker
   |
   v
Clone Git repo
   |
   v
Restore .env securely
   |
   v
Restore DB
   |
   v
Restore required storage
   |
   v
docker compose up -d
   |
   v
Health checks
   |
   v
Smoke test
```

Không restore file runtime trực tiếp nếu chưa xác định chúng thuộc Job nào và có còn hợp lệ hay không.

---

# 47. Data retention

Retention phải tách:

```text
Job metadata retention
Media artifact retention
Logs retention
Backup retention
```

Ví dụ:

```text
Media: ngắn
Job metadata: dài hơn
Audit: theo policy
Backup: dài nhất
```

Giá trị TTL production phải được xác định theo business policy và capacity của server.

---

# 48. Quy trình phát triển tính năng mới

Mọi tính năng mới đi theo trình tự:

```text
1. Xác định business rule
        |
2. Xác định API contract
        |
3. Xác định stage/service bị ảnh hưởng
        |
4. Thiết kế test
        |
5. Implement
        |
6. Unit test
        |
7. Integration/media test
        |
8. CI
        |
9. Deploy staging
        |
10. Smoke test
        |
11. Production release
        |
12. Cập nhật documentation
```

Không code trước rồi mới suy nghĩ contract sau đối với các module public.

---

# 49. Definition of Done

Một thay đổi chỉ được xem là hoàn thành khi:

- Code chạy.
- Unit tests pass.
- Contract tests pass.
- Media integration test pass nếu có ảnh hưởng.
- Docker build pass.
- Compose validation pass.
- Không phá API contract.
- Documentation được cập nhật.
- Production smoke test pass sau deploy.

---

# 50. Baseline tài liệu liên quan

Trong repository nguồn, các tài liệu kỹ thuật quan trọng gồm:

```text
docs/API_GUIDE.md
docs/API_PIPELINE.md
docs/ARCHITECTURE.md
docs/CLEANUP_TTL.md
docs/DEPLOY_UPDATE.md
docs/INSTALL_UBUNTU.md
docs/INSTALL_UBUNTU_FULL.md
docs/ROADMAP.md
README.md
INSTALL_UBUNTU_SERVER.md
```

Tài liệu Final này đóng vai trò **tổng hợp/điều phối** giữa các tài liệu chi tiết đó.

Các tài liệu chuyên sâu vẫn có thể được giữ lại để tham khảo implementation-level.

---

# 51. Roadmap sau Final Baseline

Các hướng phát triển tiếp theo có thể gồm:

## Phase A — Stability

- Hoàn thiện contract tests.
- Hoàn thiện regression fixtures.
- Monitoring chuẩn hóa.
- Rate limit.
- Storage quota.
- Better retry classification.

## Phase B — Scale

- Queue-based orchestration.
- Multiple workers.
- GPU worker pool.
- Object storage.
- Distributed job locking.

## Phase C — Product

- User accounts.
- Project/library management.
- Dubbing profiles.
- Voice presets.
- Translation quality options.
- Subtitle editor.
- Batch processing.
- Usage quota/billing.

## Phase D — Enterprise

- Team/workspace.
- RBAC.
- Audit logs.
- SSO.
- API keys.
- SLA monitoring.
- Dedicated GPU workers.

---

# 52. Nguyên tắc cuối cùng

**Video Dubbing AI được duy trì theo nguyên tắc: API ổn định, Worker chuyên trách AI/media, pipeline tách stage, Job bất đồng bộ, provider runtime-aware, test trước khi thay đổi, và documentation phải đi cùng code.**

Khi có mâu thuẫn giữa tài liệu cũ và source code đã được freeze/review, phải xác định rõ revision thay vì âm thầm thay đổi behavior.

Khi một module pipeline-critical đã được kiểm thử và xác nhận, không tự ý refactor chỉ vì muốn “đẹp code”.

Khi thay đổi kiến trúc, phải tạo architecture revision riêng.

Khi thay đổi API, phải đánh giá backward compatibility.

Khi thay đổi AI/media pipeline, phải chạy regression media thực tế.

---

# 53. Trạng thái Final

```text
Project: Video Dubbing AI
Repository: daoaigiangnam/Long_Tieng_Phu_De_Video
Branch: main
Documentation: FINAL
Baseline Date: 2026-08-16
Architecture: Node.js API + Python Worker
Deployment: Docker Compose / GHCR CI-CD
Status: Development Baseline
```

Tài liệu này là điểm tham chiếu chính cho các trao đổi phát triển tiếp theo của dự án.
