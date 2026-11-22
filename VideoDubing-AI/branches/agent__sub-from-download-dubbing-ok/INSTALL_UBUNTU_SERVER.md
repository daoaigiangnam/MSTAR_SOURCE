# VideoDubing-AI — Hướng dẫn cài đặt Server Ubuntu mới

Tài liệu này dành cho **server mới chỉ có Ubuntu**. Thực hiện theo đúng thứ tự.

## 1. Chuẩn bị

Khuyến nghị Ubuntu Server 22.04 LTS hoặc 24.04 LTS.

Cập nhật hệ thống:

```bash
sudo apt update && sudo apt upgrade -y
```

Cài các gói cơ bản:

```bash
sudo apt install -y \
  git \
  curl \
  ca-certificates \
  gnupg \
  lsb-release \
  unzip \
  ffmpeg
```

Kiểm tra:

```bash
 git --version
 ffmpeg -version
```

## 2. Cài Docker Engine + Docker Compose

Gỡ các gói Docker cũ nếu có:

```bash
sudo apt remove -y docker docker-engine docker.io containerd runc 2>/dev/null || true
```

Thiết lập Docker repository:

```bash
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg

. /etc/os-release
printf 'deb [arch=%s signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu %s stable\n' "$(dpkg --print-architecture)" "$VERSION_CODENAME" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
```

Cài Docker:

```bash
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
```

Khởi động và cho Docker tự chạy cùng hệ thống:

```bash
sudo systemctl enable --now docker
```

Kiểm tra:

```bash
sudo docker --version
sudo docker compose version
sudo docker run --rm hello-world
```

## 3. Cho user hiện tại chạy Docker không cần sudo

```bash
sudo usermod -aG docker "$USER"
```

Đăng xuất/đăng nhập lại SSH, sau đó kiểm tra:

```bash
docker ps
```

Nếu đang chạy bằng `root` thì có thể bỏ qua bước `usermod`.

## 4. Clone project

```bash
sudo mkdir -p /www/wwwroot
cd /www/wwwroot
git clone https://github.com/daoaigiangnam/VideoDubing-AI.git
cd /www/wwwroot/VideoDubing-AI
```

Kiểm tra:

```bash
git log -1 --oneline
```

## 5. Cấu hình môi trường

Kiểm tra các file mẫu trong repository:

```bash
ls -la
find . -maxdepth 2 -type f \( -name '.env*' -o -name '*example*' \) -print
```

Nếu repository có `.env.example`, tạo `.env` theo file mẫu trước khi build.

Không commit secret/API key vào Git.

## 6. Build và chạy

Build API + Worker:

```bash
docker compose build --no-cache api worker
```

Khởi động:

```bash
docker compose up -d
```

Kiểm tra:

```bash
docker compose ps
```

Worker phải ở trạng thái `healthy`; API phải `Up`.

## 7. Kiểm tra Health API

```bash
curl -s http://127.0.0.1:3000/health | python3 -m json.tool
```

Kiểm tra Worker:

```bash
curl -s http://127.0.0.1:3000/api/v1/worker/health | python3 -m json.tool
```

## 8. Kiểm tra yt-dlp

`yt-dlp` được cài trong worker container, không cần cài riêng trên Ubuntu host.

```bash
docker compose exec worker yt-dlp --version
```

Test YouTube Android client khi cần kiểm tra downloader:

```bash
docker compose exec worker yt-dlp \
  --extractor-args "youtube:player_client=android" \
  --dump-single-json \
  --no-download \
  "https://www.youtube.com/watch?v=ntlFB_blHBo"
```

Trong pipeline chính, **yt-dlp bình thường được thử trước; Android client chỉ được dùng khi lần tải bình thường thất bại**.

## 9. Kiểm tra TTS

```bash
docker compose exec worker python -c "import edge_tts; print('edge-tts OK')"
```

Test tiếng Việt:

```bash
docker compose exec worker python -c "import asyncio,edge_tts; asyncio.run(edge_tts.Communicate('Xin chào, đây là bài kiểm tra.', 'vi-VN-HoaiMyNeural').save('/tmp/test-vi.mp3')); print('TTS OK')"
```

## 10. Test Download-only

Một URL:

```bash
curl -s -X POST http://127.0.0.1:3000/api/v1/download \
  -H 'Content-Type: application/json' \
  -d '{"flag":0,"urls":["https://www.youtube.com/watch?v=ntlFB_blHBo"]}' \
  | python3 -m json.tool
```

Nhiều URL:

```bash
curl -s -X POST http://127.0.0.1:3000/api/v1/download \
  -H 'Content-Type: application/json' \
  -d '{"flag":0,"urls":["https://www.youtube.com/watch?v=ntlFB_blHBo","https://www.youtube.com/watch?v=dQw4w9WgXcQ"]}' \
  | python3 -m json.tool
```

Lấy `id` trả về và kiểm tra:

```bash
curl -s http://127.0.0.1:3000/api/v1/jobs/JOB_ID | python3 -m json.tool
```

## 11. Test Full Dubbing

```bash
curl -s -X POST http://127.0.0.1:3000/api/v1/download \
  -H 'Content-Type: application/json' \
  -d '{
    "flag":1,
    "urls":["https://www.youtube.com/watch?v=ntlFB_blHBo"],
    "source_language":"en",
    "target_language":"vi"
  }' | python3 -m json.tool
```

Theo dõi job:

```bash
curl -s http://127.0.0.1:3000/api/v1/jobs/JOB_ID | python3 -m json.tool
```

Pipeline hoàn chỉnh:

`Download → Extract → Separate → Transcribe → Translate → TTS → Timing → Mix → Render`.

## 12. Script cập nhật server

Sau khi project đã được cài lần đầu, cập nhật code:

```bash
cd /www/wwwroot/VideoDubing-AI
git pull origin main
docker compose build --no-cache api worker
docker compose up -d api worker
docker compose ps
```

Có thể gom các lệnh trên vào `update.sh` của server.

## 13. Khởi tạo lại từ đầu

Nếu cần triển khai trên server mới:

```bash
sudo mkdir -p /www/wwwroot
cd /www/wwwroot
git clone https://github.com/daoaigiangnam/VideoDubing-AI.git
cd VideoDubing-AI
docker compose build --no-cache api worker
docker compose up -d
docker compose ps
```

## 14. Kiểm tra log

API:

```bash
docker compose logs -f api
```

Worker:

```bash
docker compose logs -f worker
```

Tất cả service:

```bash
docker compose logs -f
```

## 15. Lưu ý production

- Không commit `.env`, token, SSH key hoặc credential vào Git.
- Nên đặt reverse proxy/HTTPS phía trước API khi public ra Internet.
- Kiểm tra dung lượng storage vì video, WAV tách tiếng và file render có thể rất lớn.
- Không xóa Docker volume/storage đang chứa job hoặc output nếu chưa cần.
- Khi cập nhật code, ưu tiên `git pull` → `docker compose build` → `docker compose up -d`.
