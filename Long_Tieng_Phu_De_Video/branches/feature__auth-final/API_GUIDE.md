# VideoDubing-AI — Hướng dẫn sử dụng API

Base URL mặc định trong server nội bộ:

```text
http://127.0.0.1:3000
```

Nếu public qua domain/reverse proxy, thay Base URL bằng domain API thực tế.

---

## 1. Health Check

### API

```http
GET /health
```

Test:

```bash
curl -s http://127.0.0.1:3000/health | python3 -m json.tool
```

### Worker

```http
GET /api/v1/worker/health
```

```bash
curl -s http://127.0.0.1:3000/api/v1/worker/health | python3 -m json.tool
```

---

## 2. API Root

```http
GET /api/v1
```

```bash
curl -s http://127.0.0.1:3000/api/v1 | python3 -m json.tool
```

---

# 3. Download / Full Dubbing / Subtitle

Endpoint hiện tại:

```http
POST /api/v1/download
```

`flag` xác định mục đích:

- `flag = 0`: Download only.
- `flag = 1`: Full dubbing pipeline.
- `flag = 2`: Subtitle pipeline; giữ audio gốc, không TTS.

## 3.1. Download only — 1 URL

```json
{
  "flag": 0,
  "urls": [
    "https://www.youtube.com/watch?v=VIDEO_ID"
  ]
}
```

## 3.2. Download only — nhiều URL

`flag=0` cho phép truyền nhiều URL trong cùng một request. Mỗi URL được xử lý thành một job riêng.

```json
{
  "flag": 0,
  "urls": [
    "https://www.youtube.com/watch?v=VIDEO_1",
    "https://www.youtube.com/watch?v=VIDEO_2",
    "https://www.tiktok.com/...",
    "https://www.facebook.com/..."
  ]
}
```

Luồng:

`URL → Download → File`

---

# 4. Auto Detect Source Language

`source_language` là **tùy chọn** đối với Full Dubbing và Subtitle.

Quy tắc cố định:

- Không truyền `source_language` → API gửi `null` xuống Worker → Whisper nhận `language=None` → **tự động nhận diện ngôn ngữ**.
- Truyền `source_language` như `en`, `vi`, `ja`... → Whisper dùng ngôn ngữ được chỉ định.
- Các giá trị `auto`, `automatic`, `detect`, `detected`, chuỗi rỗng và `null` đều được chuẩn hóa thành `null` trước khi Worker/Whisper xử lý.
- **Không được truyền chuỗi `"auto"` vào tham số `language` của Whisper**, vì Whisper/faster-whisper yêu cầu mã ngôn ngữ hợp lệ hoặc `None` để auto-detect.

### Dubbing Auto Detect

```json
{
  "flag": 1,
  "urls": [
    "https://youtu.be/VIDEO_ID"
  ],
  "target_language": "vi"
}
```

### Dubbing chỉ định source

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

### Subtitle Auto Detect

```json
{
  "flag": 2,
  "urls": [
    "https://youtu.be/VIDEO_ID"
  ],
  "target_language": "vi"
}
```

Không truyền `source_language` là chế độ khuyến nghị cho UI thông thường.

---

# 5. Full Dubbing — flag=1

Pipeline:

`Download → Extract → Separate → Transcribe → Detect Source Language → Translate → TTS → Timing → Mix → Render`

## 5.1. Request tối thiểu — Auto Detect

```json
{
  "flag": 1,
  "urls": [
    "https://www.youtube.com/watch?v=VIDEO_ID"
  ],
  "target_language": "vi"
}
```

## 5.2. Request — Source language chỉ định

```json
{
  "flag": 1,
  "urls": [
    "https://www.youtube.com/watch?v=VIDEO_ID"
  ],
  "source_language": "en",
  "target_language": "vi"
}
```

`target_language` là bắt buộc với `flag=1`.

### Các trường tùy chọn

```json
{
  "format": "bv*+ba/b",
  "quality": null,
  "source_language": null,
  "target_language": "vi",
  "tts_voice": "vi-VN-HoaiMyNeural",
  "translation_endpoint": null,
  "whisper_model": "small",
  "keep_background": true,
  "sync": true
}
```

`tts_voice` có thể bỏ qua để hệ thống tự chọn voice mặc định tương ứng với target language.

---

# 6. Subtitle — flag=2

`flag=2` tạo phụ đề và burn vào video mới.

**Không TTS. Không lồng tiếng. Không thay audio. Audio gốc được giữ nguyên.**

Pipeline:

`Download → Extract → Separate → Whisper → Detect Source Language → Translate nếu có target_language → Generate SRT → Burn Subtitle`

## 6.1. Phụ đề nguyên bản

Không truyền `target_language`:

```json
{
  "flag": 2,
  "urls": [
    "https://youtu.be/VIDEO_ID"
  ]
}
```

Whisper tự nhận diện ngôn ngữ nguồn và tạo phụ đề theo ngôn ngữ gốc.

## 6.2. Phụ đề dịch

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
English video
   ↓
Whisper Auto Detect: en
   ↓
Translate → vi
   ↓
SRT tiếng Việt
   ↓
Burn subtitle
   ↓
Video vẫn giữ audio English
```

---

# 7. Theo dõi Job

```http
GET /api/v1/jobs/:id
```

Ví dụ:

```bash
curl -s http://127.0.0.1:3000/api/v1/jobs/JOB_ID | python3 -m json.tool
```

Các trường quan trọng:

```text
id
status
progress
speed
eta
title
duration
filename
filepath
filesize
error
output_url
```

Các trạng thái thường gặp:

```text
waiting
accepted
queued
processing
downloading
extracting
separating
transcribing
translating
tts
subtitle_preparing
subtitle_rendering
rendering
completed
failed
cancelled
```

---

# 8. Output

```http
GET /api/v1/output/:id
```

Ví dụ:

```bash
curl -L -o output.mp4 \
  http://127.0.0.1:3000/api/v1/output/JOB_ID
```

Subtitle job còn có `subtitle_path` trỏ tới file `.srt` được worker tạo trong storage của job.

---

# 9. Languages

```http
GET /api/v1/languages
```

Dùng endpoint này để frontend/PHP lấy danh sách ngôn ngữ runtime.

---

# 10. Voices

```http
GET /api/v1/voices
GET /api/v1/voices?language=vi
```

Frontend/PHP nên lấy runtime catalog từ API thay vì hard-code danh sách voice.

Ví dụ voice tiếng Việt thường gặp:

```text
vi-VN-HoaiMyNeural
vi-VN-NamMinhNeural
```

---

# 11. Video Info

```http
GET /api/v1/info?url=...
```

Dùng để kiểm tra metadata video trước khi chạy pipeline.

---

# 12. Stage APIs

Các stage có thể được gọi độc lập để kiểm thử hoặc integration nâng cao:

```text
POST /api/v1/pipeline/extract
POST /api/v1/pipeline/separate
POST /api/v1/pipeline/transcribe
POST /api/v1/pipeline/translate
POST /api/v1/pipeline/tts
POST /api/v1/pipeline/render
```

Frontend thông thường nên dùng pipeline chính `flag=0/1/2` thay vì tự ghép từng stage.

---

# 13. Download fallback cho YouTube

Downloader dùng `yt-dlp` bình thường trước.

Chỉ khi lần tải bình thường thất bại hoặc validation không đạt mới thử Android client fallback.

```text
Normal yt-dlp
      |
      +-- OK -> tiếp tục pipeline
      |
      +-- FAIL -> Android client fallback
```

Không ép mọi YouTube request chạy Android client từ đầu.

URL YouTube có thể dùng URL gốc, không bắt buộc link Share có `?si=...`.

---

# 14. Download Validation

Không dùng một ngưỡng kích thước cố định như `20 MB`.

Download phải được kiểm tra bằng artifact thực tế, partial-file state, container/stream và duration trước khi đưa file vào các pipeline tiếp theo.

---

# 15. PHP Integration

### Lấy languages

```php
$response = file_get_contents('http://127.0.0.1:3000/api/v1/languages');
$data = json_decode($response, true);
```

### Lấy voices

```php
$language = 'vi';
$response = file_get_contents(
    'http://127.0.0.1:3000/api/v1/voices?language=' . urlencode($language)
);
$voices = json_decode($response, true);
```

### Full Dubbing

```php
$payload = [
    'flag' => 1,
    'urls' => ['https://www.youtube.com/watch?v=VIDEO_ID'],
    'target_language' => 'vi',
    // source_language intentionally omitted => Auto Detect
];

$ch = curl_init('http://127.0.0.1:3000/api/v1/download');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_RETURNTRANSFER => true,
]);
$response = curl_exec($ch);
curl_close($ch);
```

### Download hàng loạt

```php
$payload = [
    'flag' => 0,
    'urls' => [
        'https://www.youtube.com/watch?v=video1',
        'https://www.youtube.com/watch?v=video2',
        'https://www.tiktok.com/...',
    ],
];
```

### Subtitle dịch

```php
$payload = [
    'flag' => 2,
    'urls' => ['https://www.youtube.com/watch?v=VIDEO_ID'],
    'target_language' => 'vi',
    // source_language omitted => Auto Detect
];
```

---

# 16. Shell Test

### Download

```bash
./monitor-job.sh 'https://youtu.be/VIDEO_ID' 0
```

### Dubbing — Auto Detect source → Vietnamese

```bash
./monitor-job.sh 'https://youtu.be/VIDEO_ID' vi 1
```

### Dubbing — English → Vietnamese

```bash
./monitor-job.sh 'https://youtu.be/VIDEO_ID' en vi 1
```

### Subtitle — Auto Detect source, no translation

```bash
./monitor-job.sh 'https://youtu.be/VIDEO_ID' auto auto 2
```

### Subtitle — Auto Detect source → Vietnamese

```bash
./monitor-job.sh 'https://youtu.be/VIDEO_ID' auto vi 2
```

---

# 17. Lỗi thường gặp

## `'auto' is not a valid language code`

Không được truyền chuỗi `"auto"` trực tiếp vào Whisper. API hiện tại đã chuẩn hóa các giá trị `auto`, `automatic`, `detect`, `detected`, rỗng và `null` thành `null` trước khi dispatch sang Worker.

Nếu vẫn gặp lỗi này sau khi cập nhật server, kiểm tra API container đã dùng commit mới và restart/rebuild đúng service.

## `target_language is required`

Khi `flag=1`, phải truyền `target_language`.

## Voice không đúng ngôn ngữ

`tts_voice` phải thuộc target language.

## Job failed

Lấy chi tiết:

```bash
curl -s http://127.0.0.1:3000/api/v1/jobs/JOB_ID | python3 -m json.tool
```

Tập trung vào `status`, `progress` và `error`.

---

# 18. Web UI Contract

```text
📥 Download  -> flag=0
🎙 Dubbing   -> flag=1
📝 Subtitle  -> flag=2
```

### Download

```text
1..N URLs
flag=0
```

### Dubbing

```text
1..N URLs
source_language: optional / auto
target_language: required
flag=1
```

### Subtitle

```text
1..N URLs
source_language: optional / auto
target_language: optional
flag=2
```

Audio luôn giữ nguyên đối với Subtitle.

---

# 19. Nguyên tắc chốt

- `flag=0` dành cho download-only và hỗ trợ 1 hoặc nhiều URL.
- `flag=1` dành cho Full Dubbing.
- `flag=2` dành cho Subtitle.
- Download Engine dùng chung cho các luồng.
- Auto Detect source language dùng `None` ở Worker/Whisper, không dùng chuỗi `"auto"`.
- YouTube Android client chỉ là fallback khi yt-dlp bình thường thất bại hoặc validation không đạt.
- Full Dubbing pipeline đã chạy ổn thì không tự ý thay đổi thứ tự các stage.
