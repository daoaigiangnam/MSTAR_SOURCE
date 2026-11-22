# VideoDubing-AI — Cài mới và cập nhật Server

Tài liệu này là quy trình chuẩn để cài mới, cập nhật code từ GitHub và rebuild Docker trên server.

## 1. Kiến trúc cố định

- `api/`: Node.js REST API
- `worker/`: Python AI/media worker
- `storage/`: dữ liệu runtime, không commit lên Git
- `docs/`: tài liệu
- Docker Compose: API + Worker + LibreTranslate

Không tự ý thay đổi cấu trúc thư mục nếu chưa có quyết định thay đổi kiến trúc.

---

## 2. Cài mới trên Server

Ví dụ thư mục production:

```bash
cd /www/wwwroot

git clone git@github.com:daoaigiangnam/VideoDubing-AI.git
cd VideoDubing-AI
```

Nếu server dùng SSH key riêng:

```bash
git clone git@github.com:daoaigiangnam/VideoDubing-AI.git
```

Kiểm tra Git:

```bash
git remote -v
git branch --show-current
git log -1 --oneline
```

Tạo file môi trường:

```bash
cp .env.example .env
nano .env
```

Điền các biến môi trường production theo cấu hình server. Không commit `.env` chứa secret lên Git.

---

## 3. Build và chạy lần đầu

```bash
docker compose up -d --build
```

Kiểm tra container:

```bash
docker compose ps
```

Hoặc:

```bash
docker ps --format "table {{.Names}}\t{{.Image}}\t{{.Status}}"
```

Kiểm tra log:

```bash
docker compose logs --tail=100 api
docker compose logs --tail=100 worker
```

Health check:

```bash
curl -i http://127.0.0.1:3000/health
curl -i http://127.0.0.1:3000/api/v1/worker/health
```

---

## 4. Cập nhật code sau khi GitHub có commit mới

### Bước 1 — Vào project

```bash
cd /www/wwwroot/VideoDubing-AI
```

### Bước 2 — Kiểm tra phiên bản hiện tại

```bash
git status
git log -1 --oneline
```

Nếu server không có thay đổi local cần giữ lại, cập nhật:

```bash
git pull origin main
```

Kiểm tra commit mới:

```bash
git log -1 --oneline
```

### Bước 3 — Rebuild Docker

Nếu code API/Worker/Dockerfile/requirements/package thay đổi, luôn rebuild image:

```bash
docker compose up -d --build
```

Đây là cách khuyến nghị vì Docker Compose sẽ build lại image cần thiết và khởi động container mới.

Có thể dùng quy trình rõ ràng hơn trong production:

```bash
docker compose build --no-cache api worker
docker compose up -d
```

Chỉ dùng `--no-cache` khi cần build sạch hoặc nghi ngờ Docker cache. Không cần dùng cho mọi lần cập nhật.

### Bước 4 — Kiểm tra sau deploy

```bash
docker compose ps
curl -i http://127.0.0.1:3000/health
curl -i http://127.0.0.1:3000/api/v1/worker/health
```

Kiểm tra voice API:

```bash
curl -s "http://127.0.0.1:3000/api/v1/voices?language=vi" | python3 -m json.tool
```

Kiểm tra image/runtime của worker:

```bash
docker compose exec worker python -c "import torch, torchaudio; print('torch=', torch.__version__); print('torchaudio=', torchaudio.__version__)"
```

Nếu project yêu cầu TorchCodec:

```bash
docker compose exec worker python -c "import torchcodec; print('torchcodec=OK')"
```

---

## 5. Khi nào chỉ cần restart và khi nào phải rebuild?

### Chỉ restart

Chỉ restart khi thay đổi runtime/config mà image không cần thay đổi:

```bash
docker compose restart api worker
```

### Rebuild

Phải rebuild khi có một trong các thay đổi:

- `api/src/**`
- `worker/**`
- `Dockerfile`
- `requirements.txt`
- `package.json`
- `package-lock.json`
- Python/Node dependencies
- system packages trong Docker image
- entrypoint/CMD

Lệnh chuẩn:

```bash
docker compose up -d --build
```

---

## 6. Quy trình cập nhật production chuẩn

Luôn thực hiện theo thứ tự:

```text
GitHub có commit mới
        ↓
Server: git pull origin main
        ↓
Kiểm tra git log -1
        ↓
Docker compose up -d --build
        ↓
Kiểm tra container health
        ↓
Health API
        ↓
Health Worker
        ↓
Test API thay đổi
        ↓
Test job thực tế
```

Không nên chạy `docker compose down` trước mỗi lần update nếu không cần thiết. `docker compose up -d --build` thường đủ và giảm downtime.

---

## 7. Rollback khi bản mới có lỗi

Xem commit hiện tại:

```bash
git log --oneline -10
```

Chuyển về commit ổn định:

```bash
git checkout <COMMIT_ID>
```

Sau đó rebuild:

```bash
docker compose up -d --build
```

Sau khi xác nhận bản ổn định, đưa server về branch main:

```bash
git checkout main
git pull origin main
```

Nếu cần rollback production lâu dài, nên tạo một commit revert trên `main` thay vì để server ở detached HEAD.

---

## 8. Không được ghi đè dữ liệu runtime

Không xóa tùy tiện:

```text
storage/
data/
```

Các thư mục này có thể chứa database, job data hoặc media output tùy cấu hình Docker Compose.

Trước khi thực hiện thao tác destructive như `docker compose down -v`, phải kiểm tra volume/database vì `-v` có thể xóa dữ liệu persistent.

---

## 9. Quy trình nhanh hằng ngày

Khi chỉ có code mới trên GitHub:

```bash
cd /www/wwwroot/VideoDubing-AI

git pull origin main

git log -1 --oneline

docker compose up -d --build

docker compose ps

curl -i http://127.0.0.1:3000/health
curl -i http://127.0.0.1:3000/api/v1/worker/health
```

Sau đó test API/module vừa thay đổi.

---

## 10. Lưu ý với Coolify

Nếu Coolify đang quản lý deployment từ Git repository, không nên đồng thời dùng một quy trình deploy khác cho cùng service nếu hai bên có thể ghi đè trạng thái của nhau.

Trong trường hợp deploy trực tiếp từ thư mục `/www/wwwroot/VideoDubing-AI` bằng Docker Compose, sử dụng quy trình trong tài liệu này.

Nếu chuyển hoàn toàn sang Coolify auto-deploy từ GitHub, quy trình sẽ là:

```text
Git push main
   ↓
Coolify phát hiện commit
   ↓
Build image
   ↓
Deploy
   ↓
Health check
```

Khi đó không cần SSH vào server để `git pull` thủ công cho deployment đó.

---

## 11. Nguyên tắc quan trọng

1. GitHub là source of truth của source code.
2. Server production phải xác nhận đúng commit trước khi test.
3. Code/dependency/Dockerfile thay đổi thì rebuild image.
4. Không commit `.env`, secret, SSH key hoặc dữ liệu runtime.
5. Không dùng `docker compose down -v` nếu chưa xác nhận dữ liệu persistent.
6. Sau mỗi deploy phải test health API và worker.
7. Sau thay đổi AI/media pipeline phải chạy ít nhất một job thực tế trước khi coi deployment là thành công.
