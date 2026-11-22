# VideoDubing-AI — Hướng dẫn sử dụng API

## 1. Base URL

```text
http://SERVER:3000/api/v1
```

Ví dụ server local:

```text
http://127.0.0.1:3000/api/v1
```

## 2. Quy ước Pipeline

| Flag | Chức năng | Nhiều URL | TTS | Dịch |
|---:|---|---|---|---|
| `0` | Download only | Có | Không | Không |
| `1` | Full Dubbing | Có | Có | Có |
| `2` | Subtitle | Có | Không | Tùy `target_language` |

`flag=2` là pipeline phụ đề: video vẫn giữ **audio gốc**, không lồng tiếng.

## 3. Health

### Request

```bash
curl -s http://127.0.0.1:3000/health
```

### Mục đích

Kiểm tra API có hoạt động hay không.

---

# 4. API chính: POST /api/v1/download

Đây là endpoint tương thích chính cho cả Download, Dubbing và Subtitle.

## Request format

```json
{
  "flag": 0,
  "urls": [
    "https://example.com/video"
  ]
}
```

Có thể truyền một URL bằng `url`:

```json
{
  "flag": 0,
  "url": "https://example.com/video"
}
```

Hoặc nhiều URL bằng `urls`:

```json
{
  "flag": 0,
  "urls": [
    "https://example.com/video1",
    "https://example.com/video2",
    "https://example.com/video3"
  ]
}
```

## Response

Một URL trả một Job:

```json
{
  "success": true,
  "data": {
    "id": "JOB_UUID",
    "url": "https://example.com/video",
    "status": "accepted"
  }
}
```

Nhiều URL trả danh sách Job:

```json
{
  "success": true,
  "data": {
    "flag": 0,
    "total": 3,
    "jobs": []
  }
}
```

Sau khi nhận Job ID, poll:

```text
GET /api/v1/jobs/:id
```

---

# 5. Flag 0 — Download Only

## Mục đích

Chỉ tải video. Không Extract/Whisper/Translate/TTS/Dubbing.

Phù hợp cho Download Manager và download hàng loạt.

### Một URL

```bash
curl -s -X POST http://127.0.0.1:3000/api/v1/download \
  -H 'Content-Type: application/json' \
  -d '{
    "flag": 0,
    "urls": [
      "https://youtu.be/VIDEO_ID"
    ]
  }'
```

### Nhiều URL

```json
{
  "flag": 0,
  "urls": [
    "https://youtu.be/VIDEO_1",
    "https://youtu.be/VIDEO_2",
    "https://example.com/video_3"
  ]
}
```

Mỗi URL được tạo thành một Job riêng.

---

# 6. Flag 1 — Full Dubbing

## Mục đích

Tải video, tách audio, nhận diện lời nói, dịch, TTS, đồng bộ timing và render video lồng tiếng.

```text
Download
 -> Extract
 -> Separate
 -> Transcribe
 -> Detect Source Language
 -> Translate
 -> TTS
 -> Sentence Timing
 -> Mix
 -> Render
```

### Request tối thiểu

```json
{
  "flag": 1,
  "urls": [
    "https://youtu.be/VIDEO_ID"
  ],
  "target_language": "vi"
}
```

### Chỉ định ngôn ngữ nguồn

```json
{
  "flag": 1,
  "urls": [
    "https://youtu.be/VIDEO_ID"
  ],
  "source_language": "en",
  "target_language": "vi"
}
```

Nếu không truyền `source_language`, Worker có thể tự nhận diện ngôn ngữ bằng Whisper.

### Voice

Có thể truyền `tts_voice` nếu muốn chọn voice cụ thể:

```json
{
  "flag": 1,
  "urls": [
    "https://youtu.be/VIDEO_ID"
  ],
  "target_language": "vi",
  "tts_voice": "vi-VN-HoaiMyNeural"
}
```

Voice phải thuộc đúng ngôn ngữ đích. API/Worker không âm thầm fallback sang English.

---

# 7. Flag 2 — Subtitle

## Mục đích

Tạo phụ đề cho video và burn phụ đề vào video mới.

**Không TTS. Không lồng tiếng. Không thay audio. Audio gốc được giữ nguyên.**

Pipeline:

```text
Download
 -> Extract
 -> Separate
 -> Whisper
 -> Detect Source Language
 -> [Translate nếu có target_language]
 -> Generate SRT
 -> Burn Subtitle
 -> Subtitled Video
```

## 7.1 Phụ đề nguyên bản

Không truyền `target_language`:

```json
{
  "flag": 2,
  "urls": [
    "https://youtu.be/VIDEO_ID"
  ]
}
```

Whisper tự nhận diện ngôn ngữ nguồn và tạo subtitle bằng ngôn ngữ gốc.

## 7.2 Phụ đề dịch

Chỉ cần truyền **một dòng `target_language`**:

```json
{
  "flag": 2,
  "urls": [
    "https://youtu.be/VIDEO_ID"
  ],
  "target_language": "vi"
}
```

Ví dụ:

```text
Video English
    ↓
Whisper detect: en
    ↓
Translate → vi
    ↓
SRT tiếng Việt
    ↓
Burn vào video
    ↓
Video tiếng gốc + phụ đề tiếng Việt
```

Không cần truyền `source_language` cho Subtitle. Nếu không truyền, hệ thống tự nhận diện.

Nếu cần ép ngôn ngữ nguồn, có thể truyền `source_language`, nhưng UI người dùng thông thường chỉ cần `target_language`.

## 7.3 Nhiều URL

```json
{
  "flag": 2,
  "urls": [
    "https://youtu.be/VIDEO_1",
    "https://youtu.be/VIDEO_2",
    "https://youtu.be/VIDEO_3"
  ],
  "target_language": "vi"
}
```

Mỗi URL tạo một Job.

## 7.4 Output Subtitle

Job Subtitle trả các thông tin quan trọng gồm:

- `filepath`: video đã burn subtitle;
- `output_path`: video đầu ra;
- `subtitle_path`: file `.srt`;
- `source_language`: ngôn ngữ nguồn đã detect;
- `target_language`: ngôn ngữ đích nếu có;
- `media.final_path`: video cuối;
- `media.subtitle_path`: file SRT.

---

# 8. Job Status

```text
GET /api/v1/jobs/:id
```

Ví dụ:

```bash
curl -s http://127.0.0.1:3000/api/v1/jobs/JOB_ID | python3 -m json.tool
```

Các trạng thái có thể gặp:

```text
accepted
queued
processing
downloading
extracting
separating
transcribing
translating
tts
rendering
subtitle_preparing
subtitle_rendering
completed
failed
```

Khi:

```text
status = completed
```

thì Job đã hoàn thành.

Khi:

```text
status = failed
```

xem trường `error` để biết nguyên nhân.

---

# 9. Output

```text
GET /api/v1/output/:id
```

Dùng Job ID để tải output theo route được API expose.

Đối với Subtitle, output gồm video đã burn subtitle; file `.srt` được worker lưu riêng theo `subtitle_path`.

---

# 10. Metadata API

## POST /api/v1/info

Dùng để lấy metadata video bằng yt-dlp trước khi tạo Job.

Ví dụ:

```bash
curl -s -X POST http://127.0.0.1:3000/api/v1/info \
  -H 'Content-Type: application/json' \
  -d '{
    "url": "https://youtu.be/VIDEO_ID"
  }'
```

Mục đích:

- kiểm tra URL;
- title;
- thumbnail;
- duration;
- extractor;
- format/quality nếu API trả về.

---

# 11. Language API

```text
GET /api/v1/languages
```

Dùng để lấy catalog ngôn ngữ runtime.

Không hard-code danh sách ngôn ngữ trong frontend nếu không cần thiết. Runtime provider là nguồn dữ liệu hiện hành.

---

# 12. Voice API

## Tất cả voice

```text
GET /api/v1/voices
```

## Voice theo ngôn ngữ

```text
GET /api/v1/voices?language=vi
```

Frontend nên gọi API này để hiển thị voice thay vì tự duy trì danh sách cứng.

Ví dụ voice tiếng Việt thường gặp:

```text
vi-VN-HoaiMyNeural
vi-VN-NamMinhNeural
```

Catalog Edge TTS là dynamic; luôn ưu tiên dữ liệu trả về từ `/api/v1/voices`.

---

# 13. Pipeline Stage APIs

Các API stage phục vụ diagnostics/integration nâng cao:

```text
POST /api/v1/pipeline/extract
POST /api/v1/pipeline/separate
POST /api/v1/pipeline/transcribe
POST /api/v1/pipeline/translate
POST /api/v1/pipeline/tts
POST /api/v1/pipeline/render
```

Thông thường frontend không cần gọi từng stage. Web người dùng nên gọi pipeline chính (`flag=0/1/2`).

---

# 14. Các tham số tùy chọn

| Field | Ý nghĩa |
|---|---|
| `flag` | `0` Download, `1` Dubbing, `2` Subtitle |
| `url` | Một URL |
| `urls` | Một hoặc nhiều URL |
| `source_language` | Ngôn ngữ nguồn; có thể để auto trong Dubbing/Subtitle |
| `target_language` | Ngôn ngữ đích; bắt buộc với Dubbing, tùy chọn với Subtitle |
| `tts_voice` | Edge TTS voice cho Dubbing |
| `format` | Format download nếu được hỗ trợ |
| `quality` | Quality yêu cầu |
| `whisper_model` | Whisper model, mặc định `small` |
| `translation_endpoint` | Endpoint dịch tùy cấu hình |
| `keep_background` | Giữ background audio trong Dubbing |
| `sync` | Bật timing synchronization |

---

# 15. Android fallback / YouTube

Download engine dùng yt-dlp strategy chính trước.

```text
Normal yt-dlp
      |
      +-- OK -> tiếp tục
      |
      +-- FAIL -> Android client fallback
```

Không dùng Android client làm strategy mặc định khi strategy thường vẫn thành công.

Ví dụ kiểm tra yt-dlp bên trong Worker:

```bash
docker compose exec worker yt-dlp --version
```

Không cần truyền `ANDROID_AGENT_URL` cho API người dùng nếu fallback đã được cấu hình nội bộ trong Worker.

---

# 16. Download validation

Download không được coi là thành công chỉ vì yt-dlp process exit code thành công.

Ứng dụng cần kiểm tra tối thiểu:

```text
file tồn tại
AND
filesize > 0
AND
media/container đọc được
AND
job không có lỗi
```

Không dùng một ngưỡng cứng như `20 MB` vì video có thể rất ngắn hoặc bitrate thấp.

---

# 17. Ví dụ Shell test nhanh

### Download

```bash
./monitor-job.sh 'https://youtu.be/VIDEO_ID' auto vi 0
```

### Dubbing

```bash
./monitor-job.sh 'https://youtu.be/VIDEO_ID' en vi 1
```

### Subtitle nguyên bản

```bash
./monitor-job.sh 'https://youtu.be/VIDEO_ID' auto auto 2
```

### Subtitle dịch

```bash
./monitor-job.sh 'https://youtu.be/VIDEO_ID' auto vi 2
```

Trong test Subtitle, `target_language` mới là tham số quan trọng; API thực tế có thể bỏ `source_language` để auto-detect.

---

# 18. Contract cho Web UI

Web UI chỉ cần ba chức năng chính:

```text
┌───────────────────────────────┐
│ VideoDubing-AI                │
├───────────────────────────────┤
│ 📥 Download                   │
│ 🎙 Full Dubbing               │
│ 📝 Subtitle                   │
└───────────────────────────────┘
```

### Download

```text
URLs: 1..N
flag=0
```

### Dubbing

```text
URL(s)
source_language: optional/auto
target_language: required
flag=1
```

### Subtitle

```text
URL(s)
target_language: optional
flag=2
```

Nếu `target_language` bỏ trống → subtitle ngôn ngữ gốc.

Nếu `target_language` có giá trị → auto-detect nguồn + dịch sang đúng một ngôn ngữ đích.

Audio luôn giữ nguyên đối với Subtitle.

---

# 19. Không thuộc API production hiện tại

Qwen3-TTS / Voice Clone là hướng phát triển riêng. Server hiện tại chưa có GPU nên **không đưa Qwen3-TTS vào pipeline production hiện tại**.

Kiến trúc tương lai có thể tách thành `VoiceClone-AI` rồi kết nối vào TTS Provider của VideoDubing-AI. Việc này không thay đổi contract `flag=0/1/2` hiện tại.
