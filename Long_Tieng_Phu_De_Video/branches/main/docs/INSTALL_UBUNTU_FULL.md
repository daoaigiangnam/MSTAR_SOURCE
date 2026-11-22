# VideoDubing-AI — Cài đặt Server mới Ubuntu — Full Guide

> Tài liệu này là bản đầy đủ để dựng một Server Ubuntu mới và đưa VideoDubing-AI lên chạy. Bản này bao gồm cả Cleanup TTL.

## 1. Kiến trúc

```text
Client / Web
    |
    v
Node.js API :3000
    |
    v
Python Worker :8000
    |
    +-- yt-dlp
    +-- FFmpeg
    +-- Demucs
    +-- faster-whisper
    +-- LibreTranslate
    +-- Edge TTS
    |
    v
Shared Storage
    |
    +-- Cleanup Service (optional TTL)
```

Pipeline:

```text
flag=0  -> Download
flag=1  -> Full Dubbing + Translation + TTS
flag=2  -> Subtitle + Translation, giữ audio gốc
```

## 2. Yêu cầu Server

- Ubuntu Server 22.04/24.04 LTS.
- Docker Engine.
- Docker Compose Plugin.
- Git.
- Internet.
- CPU/RAM/Disk phù hợp với khối lượng xử lý.
- GPU NVIDIA không bắt buộc cho pipeline hiện tại. Qwen3-TTS Voice Clone là module tương lai.

## 3. Cài đặt Ubuntu

```bash
sudo apt update
sudo apt upgrade -y
sudo apt install -y git curl wget ca-certificates unzip python3 python3-pip
```

Nếu chưa có Docker:

```bash
curl -fsSL https://get.docker.com | sudo sh
```

Kiểm tra:

```bash
docker --version
docker compose version
git --version
```

## 4. Clone Project

```bash
git clone git@github.com:daoaigiangnam/VideoDubing-AI.git
cd VideoDubing-AI
```

Nếu server đã có project:

```bash
git pull --ff-only
```

## 5. Cấu hình Environment

```bash
cp .env.example .env
nano .env
```

Các biến Cleanup:

```env
CLEANUP_ENABLED=false
CLEANUP_TTL_HOURS=2
CLEANUP_INTERVAL_MINUTES=5
```

### Trong giai đoạn TEST

Giữ:

```env
CLEANUP_ENABLED=false
```

để Job, file trung gian và thành phẩm không bị xóa trong lúc debug.

### Khi chuyển PRODUCTION

```env
CLEANUP_ENABLED=true
CLEANUP_TTL_HOURS=2
CLEANUP_INTERVAL_MINUTES=5
```

`CLEANUP_TTL_HOURS` là số giờ giữ Job sau khi Job kết thúc. Có thể đổi thành 1, 2, 6, 12, 24...

`CLEANUP_INTERVAL_MINUTES` là khoảng thời gian Cleanup quét lại storage.

## 6. Build lần đầu

```bash
docker compose build
docker compose up -d
docker compose ps
```

Kiểm tra API:

```bash
curl -s http://127.0.0.1:3000/health | python3 -m json.tool
```

## 7. Cài/Khởi động Cleanup

Cleanup là service độc lập, không can thiệp vào pipeline Download/Dubbing/Subtitle.

Sau khi đã cấu hình `.env`:

```bash
docker compose build cleanup
docker compose up -d cleanup
```

Kiểm tra:

```bash
docker compose ps cleanup
docker compose logs --tail=100 cleanup
```

Nếu đang test thì có thể chạy service với `CLEANUP_ENABLED=false`; service vẫn tồn tại nhưng không xóa dữ liệu.

## 8. Cleanup hoạt động thế nào?

Cleanup quét các Job trong:

```text
storage/downloads/
    JOB_ID_1/
    JOB_ID_2/
    JOB_ID_3/
```

Chỉ Job đã kết thúc mới đủ điều kiện:

```text
completed
failed
cancelled
```

Job đang chạy không bị xóa:

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
subtitle_rendering
```

TTL tính từ `finished_at`, không tính từ `created_at`.

Ví dụ:

```text
10:00 created
10:45 completed
```

với:

```env
CLEANUP_TTL_HOURS=2
```

thì Job đủ điều kiện Cleanup từ 12:45 trở đi và sẽ được xóa ở lần quét tiếp theo.

Job `failed` cũng được Cleanup sau TTL vì User có thể tạo Job mới để chạy lại.

## 9. Cleanup xóa gì?

Khi hết TTL, toàn bộ folder:

```text
storage/downloads/JOB_ID/
```

được xóa, gồm:

- Video nguồn.
- Audio trung gian.
- Vocals/background.
- Transcript.
- SRT/VTT.
- Translation.
- TTS audio.
- Render trung gian.
- File thành phẩm.

Sau khi folder được xóa thành công, record Job tương ứng trong SQLite cũng được xóa.

Cleanup không cần biết User đã bấm Download hay tải hoàn tất. User tải hoặc không tải đều được giữ dữ liệu đến hết TTL.

Cleanup không tự ý xóa folder orphan không có Job record trong DB.

## 10. Đổi TTL sau này

Nếu chỉ thay đổi `.env`, không cần build lại image.

Ví dụ 6 giờ:

```env
CLEANUP_ENABLED=true
CLEANUP_TTL_HOURS=6
CLEANUP_INTERVAL_MINUTES=5
```

Sau đó:

```bash
docker compose up -d --force-recreate cleanup
```

Tắt Cleanup:

```env
CLEANUP_ENABLED=false
```

```bash
docker compose up -d --force-recreate cleanup
```

## 11. Test Download — flag=0

Một URL:

```bash
./monitor-job.sh "https://youtu.be/VIDEO_ID" 0
```

API tương đương:

```json
{
  "flag": 0,
  "urls": ["https://youtu.be/VIDEO_ID"]
}
```

Nhiều URL:

```json
{
  "flag": 0,
  "urls": [
    "https://youtu.be/VIDEO_ID_1",
    "https://youtu.be/VIDEO_ID_2"
  ]
}
```

## 12. Test Full Dubbing — flag=1

Auto Detect source:

```bash
./monitor-job.sh "https://youtu.be/VIDEO_ID" vi 1
```

Source chỉ định:

```bash
./monitor-job.sh "https://youtu.be/VIDEO_ID" en vi 1
```

API auto detect:

```json
{
  "flag": 1,
  "urls": ["https://youtu.be/VIDEO_ID"],
  "target_language": "vi"
}
```

API chỉ định source:

```json
{
  "flag": 1,
  "urls": ["https://youtu.be/VIDEO_ID"],
  "source_language": "en",
  "target_language": "vi"
}
```

Khi không truyền source language, hệ thống dùng Auto Detect. Giá trị nội bộ `auto` được chuẩn hóa thành `None` trước khi đưa vào faster-whisper.

## 13. Test Subtitle — flag=2

Auto Detect source, dịch sang tiếng Việt:

```bash
./monitor-job.sh "https://youtu.be/VIDEO_ID" vi 2
```

Chỉ định source:

```bash
./monitor-job.sh "https://youtu.be/VIDEO_ID" en vi 2
```

API:

```json
{
  "flag": 2,
  "urls": ["https://youtu.be/VIDEO_ID"],
  "target_language": "vi"
}
```

Subtitle không TTS và không thay audio. Video giữ tiếng gốc, subtitle được tạo/burn theo pipeline.

## 14. Theo dõi Job

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
subtitle_rendering
completed
failed
```

## 15. Update code — Không build toàn bộ nếu không cần

Luôn bắt đầu:

```bash
git pull --ff-only
git log -1 --oneline
```

Chỉ sửa API:

```bash
docker compose build api
docker compose up -d api
```

Chỉ sửa Worker:

```bash
docker compose build worker
docker compose up -d worker
```

Chỉ sửa Cleanup:

```bash
docker compose build cleanup
docker compose up -d cleanup
```

Chỉ sửa Docs:

```bash
git pull --ff-only
```

Không cần restart/build Docker.

Chỉ build toàn bộ khi thay đổi ảnh hưởng nhiều service hoặc chủ động redeploy toàn bộ:

```bash
docker compose build
docker compose up -d
```

## 16. Docker Troubleshooting

Xem trạng thái:

```bash
docker compose ps
```

API:

```bash
docker compose logs --tail=200 api
```

Worker:

```bash
docker compose logs --tail=200 worker
```

Cleanup:

```bash
docker compose logs --tail=200 cleanup
```

Kiểm tra yt-dlp trong Worker:

```bash
docker compose exec worker yt-dlp --version
```

Không giả định `curl` tồn tại bên trong Worker. Có thể kiểm tra từ host hoặc dùng Python/HTTP endpoint thích hợp.

## 17. Download Validation

Không dùng ngưỡng cố định như 20 MB để xác định file hợp lệ.

Nên kiểm tra:

- File tồn tại.
- Filesize > 0.
- FFmpeg đọc được media.
- Job báo `completed`.
- Metadata/duration/format hợp lệ khi cần.

Downloader dùng strategy yt-dlp chính trước; Android client là fallback khi strategy chính thất bại.

## 18. Cleanup Production Model

```text
User tạo Job
      ↓
API
      ↓
Worker
      ↓
flag 0 / 1 / 2
      ↓
completed / failed / cancelled
      ↓
GIỮ toàn bộ JOB_ID folder
      ↓
finished_at + CLEANUP_TTL_HOURS
      ↓
Cleanup Service
      ↓
Xóa toàn bộ JOB_ID folder
      ↓
Xóa Job record DB
```

Không cần:

- Detect Browser/User download thành công.
- Download callback.
- Xóa file tạm ngay khi completed.
- Xóa thành phẩm ngay khi completed.

Mục tiêu là giữ hệ thống đơn giản, dễ debug trong TTL và tránh Storage tăng vô hạn.

## 19. Tài liệu chi tiết

API:

```text
docs/API_GUIDE.md
```

Cleanup:

```text
docs/CLEANUP_TTL.md
```

Roadmap:

```text
docs/ROADMAP.md
```
