# VideoDubing-AI — Cleanup TTL Guide

## 1. Mục đích

Cleanup Service tự động dọn dữ liệu Job cũ để tránh thư mục storage đầy theo thời gian.

Cleanup không theo dõi người dùng có tải file hay chưa. Khi Job đã kết thúc, hệ thống giữ toàn bộ folder `JOB_ID` trong thời gian TTL rồi xóa một lần.

Áp dụng cho:

- `flag=0` — Download
- `flag=1` — Full Dubbing
- `flag=2` — Subtitle

## 2. Job nào được xóa?

Chỉ các Job ở trạng thái terminal:

```text
completed
failed
cancelled
```

Các Job đang chạy không bị Cleanup đụng tới:

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

TTL được tính từ `jobs.finished_at`, **không tính từ `created_at` và không dựa vào mtime của folder**.

## 3. Cấu hình

Copy `.env.example` thành `.env` nếu chưa có, sau đó cấu hình:

```env
CLEANUP_ENABLED=false
CLEANUP_TTL_HOURS=2
CLEANUP_INTERVAL_MINUTES=5
```

### CLEANUP_ENABLED

```env
CLEANUP_ENABLED=false
```

Tắt Cleanup. Khuyến nghị giữ `false` trong toàn bộ giai đoạn test/debug.

Production:

```env
CLEANUP_ENABLED=true
```

### CLEANUP_TTL_HOURS

Số giờ giữ Job sau khi Job kết thúc.

Ví dụ:

```env
CLEANUP_TTL_HOURS=2
```

Giữ 2 giờ sau `finished_at`.

Giữ 6 giờ:

```env
CLEANUP_TTL_HOURS=6
```

Giữ 24 giờ:

```env
CLEANUP_TTL_HOURS=24
```

Giá trị phải lớn hơn 0.

### CLEANUP_INTERVAL_MINUTES

Khoảng thời gian giữa các lần quét.

```env
CLEANUP_INTERVAL_MINUTES=5
```

Nghĩa là Cleanup kiểm tra khoảng mỗi 5 phút. Giá trị phải lớn hơn 0.

## 4. Cơ chế hoạt động

Ví dụ:

```text
CLEANUP_TTL_HOURS=2
CLEANUP_INTERVAL_MINUTES=5
```

Job:

```text
10:00  created
10:45  completed
```

Job được giữ lại đến:

```text
12:45  eligible for cleanup
```

Cleanup có thể phát hiện và xóa trong lần quét kế tiếp, ví dụ 12:45–12:50.

Nếu Job failed:

```text
10:45  failed
12:45  eligible for cleanup
```

`failed` cũng được xóa vì người dùng có thể tạo Job mới để chạy lại.

## 5. Xóa những gì?

Khi Job hết TTL, Cleanup xóa toàn bộ:

```text
storage/downloads/JOB_ID/
```

Bao gồm:

- Video nguồn
- Audio trung gian
- Vocals/background
- Transcript
- SRT
- Translation
- TTS audio
- Render trung gian
- File thành phẩm

Sau khi xóa folder thành công, Cleanup cũng xóa record Job tương ứng khỏi SQLite database.

## 6. Orphan folder

Cleanup **không tự ý xóa folder không có Job record trong database**.

Điều này là lớp bảo vệ để tránh trường hợp database gặp sự cố nhưng Cleanup lại xóa dữ liệu chưa được xác minh.

## 7. Bật Cleanup sau khi test xong

Sau khi toàn bộ pipeline `flag=0/1/2` đã test ổn định:

```bash
nano .env
```

Đặt:

```env
CLEANUP_ENABLED=true
CLEANUP_TTL_HOURS=2
CLEANUP_INTERVAL_MINUTES=5
```

Sau đó recreate Cleanup service:

```bash
docker compose up -d --build cleanup
```

Kiểm tra:

```bash
docker compose ps cleanup
docker compose logs --tail=100 cleanup
```

Kỳ vọng log:

```text
Cleanup service started
enabled=true ttl_hours=2 interval_minutes=5
Scanning completed/failed jobs older than 2h
```

## 8. Thay đổi TTL sau này

Nếu chỉ thay biến môi trường, không cần build image:

```bash
nano .env
```

Ví dụ đổi thành 12 giờ:

```env
CLEANUP_TTL_HOURS=12
```

Sau đó:

```bash
docker compose up -d cleanup
```

## 9. Tắt Cleanup tạm thời

```env
CLEANUP_ENABLED=false
```

Sau đó:

```bash
docker compose up -d cleanup
```

Cleanup sẽ không xóa Job nào.

## 10. Kiểm tra database trước khi bật Production

Có thể xem các Job terminal:

```bash
sqlite3 api/data/videodubing.db \
  "SELECT id,status,finished_at FROM jobs WHERE status IN ('completed','failed','cancelled') ORDER BY finished_at DESC;"
```

Không cần xóa thủ công. Cleanup Service sẽ xử lý theo TTL.

## 11. Nguyên tắc Production đã chốt

```text
User tạo Job
     ↓
Pipeline chạy
     ↓
┌─────────────────────────────┐
│ đang chạy → GIỮ             │
│ completed → chờ TTL         │
│ failed → chờ TTL            │
│ cancelled → chờ TTL         │
└─────────────────────────────┘
     ↓
finished_at + TTL
     ↓
Xóa toàn bộ JOB_ID folder
     ↓
Xóa Job record khỏi DB
```

Không cần:

- Detect browser download thành công
- Callback sau khi user tải file
- Xóa file tạm từng stage
- Xóa thành phẩm ngay sau khi Job completed

Mục tiêu là giữ toàn bộ artifact trong TTL để người dùng có thời gian tải và để hệ thống dễ kiểm tra/debug, sau đó dọn sạch một lần.
