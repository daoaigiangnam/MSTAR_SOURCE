# VideoDubing-AI — Cài đặt Server mới (Ubuntu)

Tài liệu này dành cho **server mới chỉ có Ubuntu OS**. Hệ thống cố định theo kiến trúc Node.js API + Python Worker + Docker Compose.

## 1. Kiến trúc

```text
Client / Web
    |
    v
Node.js API :3000
    |
    v
Python Worker
    |
    +-- yt-dlp
    +-- FFmpeg
    +-- Demucs
    +-- faster-whisper
    +-- LibreTranslate/API dịch
    +-- Edge TTS
    |
    v
Shared Storage
    |
    +-- Cleanup service (optional TTL)
```

Ba chức năng chính:

```text
flag=0  -> Download
flag=1  -> Full Dubbing
flag=2  -> Subtitle
```

`flag=0` và `flag=2` có thể nhận nhiều URL. `flag=1` giữ pipeline Dubbing hiện tại.

## 2. Yêu cầu server

- Ubuntu Server 22.04/24.04 LTS khuyến nghị.
- Docker Engine.
- Docker Compose Plugin (`docker compose`).
- Git.
- Internet để tải image/model/provider data.
- CPU/RAM/Disk tùy khối lượng. Dubbing với Whisper/Demucs cần tài nguyên cao hơn Download.
- GPU NVIDIA **không bắt buộc** cho pipeline hiện tại. Qwen3-TTS Voice Clone là module tương lai và không thuộc pipeline production hiện tại.

## 3. Cài đặt Server mới

```bash
sudo apt update
sudo apt upgrade -y
sudo apt install -y git curl wget ca-certificates unzip python3 python3-pip
```

Cài Docker nếu server chưa có:

```bash
curl -fsSL https://get.docker.com | sudo sh
```

Kiểm tra:

```bash
docker --version
docker compose version
```

Clone repository:

```bash
git clone https://github.com/daoaigiangnam/VideoDubing-AI.git
cd VideoDubing-AI
```

Build lần đầu:

```bash
docker compose build
docker compose up -d
docker compose ps
```

## 4. QUAN TRỌNG — Cập nhật code sau này

**Không phải mỗi lần cập nhật GitHub đều phải build lại toàn bộ hệ thống.** Trước tiên xác định phần nào thay đổi.

### 4.1. Chỉ thay code API

Ví dụ thay các file trong:

```text
api/src/...
```

Nếu source API được mount vào container hoặc image hiện tại đã có code mới theo cách deploy của project, có thể thử nhanh:

```bash
git pull --ff-only
docker compose restart api
```

Kiểm tra:

```bash
curl -s http://127.0.0.1:3000/health | python3 -m json.tool
docker compose logs --tail=100 api
```

Nếu container **không mount source** và vẫn chạy code cũ, build **riêng API**:

```bash
git pull --ff-only
docker compose build api
docker compose up -d api
```

Không cần build Worker nếu chỉ sửa API.

### 4.2. Chỉ thay code Worker

Ví dụ thay:

```text
worker/worker/...
```

Build riêng Worker:

```bash
git pull --ff-only
docker compose build worker
docker compose up -d worker
```

Kiểm tra:

```bash
docker compose logs --tail=100 worker
```

Không cần build API nếu chỉ sửa Worker.

### 4.3. Chỉ thay Cleanup

Ví dụ thay:

```text
cleanup/...
```

Build riêng Cleanup:

```bash
git pull --ff-only
docker compose build cleanup
docker compose up -d cleanup
```

Không cần restart API/Worker nếu chỉ thay Cleanup.

### 4.4. Chỉ thay tài liệu

Nếu chỉ thay:

```text
README.md
docs/...
```

thì **không cần restart, không cần build Docker**.

Chỉ cần:

```bash
git pull --ff-only
```

### 4.5. Thay `Dockerfile`, `package.json`, `requirements.txt` hoặc dependency

Phải build image tương ứng.

API dependency:

```bash
git pull --ff-only
docker compose build api
docker compose up -d api
```

Worker dependency:

```bash
git pull --ff-only
docker compose build worker
docker compose up -d worker
```

Cleanup dependency:

```bash
git pull --ff-only
docker compose build cleanup
docker compose up -d cleanup
```

Nếu thay dependency dùng chung hoặc thay đổi Compose/build configuration ảnh hưởng nhiều service thì mới build các service liên quan.

### 4.6. Thay `docker-compose.yml` / `compose.yml`

Sau khi pull:

```bash
git pull --ff-only
docker compose config
docker compose up -d
```

Chỉ build lại image nếu Compose configuration hoặc Dockerfile/dependency thực sự yêu cầu build.

### 4.7. Khi nào mới build toàn bộ

Chỉ dùng:

```bash
docker compose build
docker compose up -d
```

khi thay đổi ảnh hưởng đồng thời nhiều service, hoặc khi muốn triển khai lại toàn bộ hệ thống.

Không dùng `--no-cache` thường xuyên. Chỉ dùng khi cần loại bỏ cache để xử lý lỗi build/cache:

```bash
docker compose build --no-cache
```

## 5. Cleanup TTL

Cleanup là service độc lập, **không tham gia vào pipeline Download/Dubbing/Subtitle**. Vì vậy có thể bật/tắt riêng và không ảnh hưởng quá trình xử lý media.

### Biến cấu hình

Trong `.env`:

```env
CLEANUP_ENABLED=false
CLEANUP_TTL_HOURS=2
CLEANUP_INTERVAL_MINUTES=5
```

- `CLEANUP_ENABLED=false`: tắt cleanup trong giai đoạn test.
- `CLEANUP_ENABLED=true`: bật cleanup.
- `CLEANUP_TTL_HOURS`: số giờ giữ Job sau khi hoàn thành; có thể đổi thành `1`, `2`, `6`, `24`, ...
- `CLEANUP_INTERVAL_MINUTES`: chu kỳ quét; mặc định 5 phút.

### Quy tắc cleanup

Cleanup quét tất cả thư mục Job trực tiếp bên dưới:

```text
/storage/downloads/
    JOB_ID_1/
    JOB_ID_2/
    JOB_ID_3/
    ...
```

Service chỉ xóa thư mục đã quá TTL theo **thời gian sửa cuối của folder**. Job đang chạy không bị service đặc biệt nhắm tới, vì vậy trong production nên để worker hoàn tất ghi file trước khi TTL bắt đầu có hiệu lực.

Cleanup xóa **toàn bộ JOB_ID folder một lần**, bao gồm thành phẩm và tất cả file trung gian.

Không cần cơ chế phát hiện Browser đã tải file thành công. Người dùng tải hay không tải đều không ảnh hưởng cleanup; sau TTL folder sẽ được dọn.

Trong giai đoạn test nên để:

```env
CLEANUP_ENABLED=false
```

Sau khi tất cả pipeline 0/1/2 đã ổn định mới bật:

```env
CLEANUP_ENABLED=true
```

### Khởi động Cleanup

Sau khi cập nhật repo và cấu hình `.env`:

```bash
docker compose build cleanup
docker compose up -d cleanup
```

Kiểm tra:

```bash
docker compose ps cleanup
docker compose logs --tail=100 cleanup
```

Khi chạy, log sẽ hiển thị cấu hình TTL/interval và các folder được xóa.

### Thay đổi TTL không cần build

Nếu chỉ đổi:

```env
CLEANUP_TTL_HOURS=6
```

hoặc:

```env
CLEANUP_INTERVAL_MINUTES=10
```

thì không cần build image. Chỉ recreate/restart Cleanup:

```bash
docker compose up -d --force-recreate cleanup
```

### Tắt Cleanup

```env
CLEANUP_ENABLED=false
```

rồi:

```bash
docker compose up -d --force-recreate cleanup
```

## 6. Quy trình update chuẩn

Mỗi lần chuẩn bị update:

```bash
git pull --ff-only
git log -1 --oneline
```

`git log -1 --oneline` dùng để xác nhận server đang đứng ở commit nào sau khi pull.

Sau đó chọn đúng trường hợp ở mục 4. Không cần build tất cả chỉ vì một file thay đổi.

## 7. Health Check

```bash
curl -s http://127.0.0.1:3000/health | python3 -m json.tool
```

Kỳ vọng API trả `success=true` và service `videodubing-api`.

## 8. Test Download

Một URL:

```bash
curl -s -X POST http://127.0.0.1:3000/api/v1/download \
  -H 'Content-Type: application/json' \
  -d '{
    "flag": 0,
    "urls": ["https://youtu.be/VIDEO_ID"]
  }' | python3 -m json.tool
```

Nhiều URL:

```bash
curl -s -X POST http://127.0.0.1:3000/api/v1/download \
  -H 'Content-Type: application/json' \
  -d '{
    "flag": 0,
    "urls": [
      "https://youtu.be/VIDEO_ID_1",
      "https://example.com/video2"
    ]
  }' | python3 -m json.tool
```

## 9. Test Full Dubbing

```bash
curl -s -X POST http://127.0.0.1:3000/api/v1/download \
  -H 'Content-Type: application/json' \
  -d '{
    "flag": 1,
    "urls": ["https://youtu.be/VIDEO_ID"],
    "source_language": "en",
    "target_language": "vi"
  }' | python3 -m json.tool
```

Có thể bỏ `source_language` để Worker tự nhận diện. `target_language` bắt buộc với `flag=1`.

## 10. Test Subtitle

Tự nhận diện và giữ nguyên ngôn ngữ gốc:

```bash
curl -s -X POST http://127.0.0.1:3000/api/v1/download \
  -H 'Content-Type: application/json' \
  -d '{
    "flag": 2,
    "urls": ["https://youtu.be/VIDEO_ID"]
  }' | python3 -m json.tool
```

Tự nhận diện nguồn và dịch sang tiếng Việt:

```bash
curl -s -X POST http://127.0.0.1:3000/api/v1/download \
  -H 'Content-Type: application/json' \
  -d '{
    "flag": 2,
    "urls": ["https://youtu.be/VIDEO_ID"],
    "target_language": "vi"
  }' | python3 -m json.tool
```

Subtitle không TTS và không thay audio. Video đầu ra giữ tiếng gốc và burn subtitle vào hình.

## 11. Theo dõi Job

```bash
curl -s http://127.0.0.1:3000/api/v1/jobs/JOB_ID | python3 -m json.tool
```

Trạng thái thường gặp:

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

## 12. YouTube Android fallback

Download engine dùng yt-dlp thông thường trước. Khi strategy thông thường thất bại, hệ thống mới chuyển sang Android client fallback đã được cấu hình trong downloader.

Không cài một `yt-dlp` riêng trên host nếu pipeline đang chạy yt-dlp trong Worker container. Kiểm tra phiên bản trong container:

```bash
docker compose exec worker yt-dlp --version
```

## 13. Kiểm tra file đầu ra

Sau khi Job `completed`, kiểm tra `filepath`, `output_path` hoặc `media.final_path` trong job result.

Không dùng kiểm tra kiểu cố định `20 MB` để quyết định file hợp lệ. File phải được kiểm tra bằng:

- tồn tại;
- kích thước > 0;
- FFmpeg có thể đọc container/media;
- Job báo `completed`;
- với download có thể kiểm tra metadata/duration/format nếu cần.

## 14. Troubleshooting nhanh

API không lên:

```bash
docker compose ps
docker compose logs --tail=200 api
```

Worker lỗi:

```bash
docker compose logs --tail=200 worker
```

Cleanup lỗi:

```bash
docker compose logs --tail=200 cleanup
```

Container chưa có curl: không giả định `curl` tồn tại trong Worker. Có thể kiểm tra từ host hoặc dùng Python/endpoint nội bộ phù hợp.

Nếu code mới đã pull nhưng container vẫn chạy code cũ:

```bash
docker compose build <service>
docker compose up -d <service>
```

Ví dụ chỉ sửa API:

```bash
docker compose build api
docker compose up -d api
```

Ví dụ chỉ sửa Worker:

```bash
docker compose build worker
docker compose up -d worker
```

Ví dụ chỉ sửa Cleanup:

```bash
docker compose build cleanup
docker compose up -d cleanup
```

Chỉ dùng full rebuild khi thực sự cần:

```bash
docker compose build --no-cache
docker compose up -d
```

## 15. Nguyên tắc vận hành

- Không thay đổi kiến trúc thư mục nếu chưa có quyết định kiến trúc mới.
- Không sửa pipeline Full Dubbing đang ổn chỉ để phục vụ Download/Subtitle.
- Download engine là module dùng chung.
- Android fallback là fallback, không thay thế strategy chính.
- `flag=0/1/2` là contract hiện tại.
- Cleanup TTL là service độc lập và có thể bật/tắt bằng biến môi trường.
- Qwen3-TTS/Voice Clone chưa thuộc production pipeline này vì server hiện tại chưa có GPU.
