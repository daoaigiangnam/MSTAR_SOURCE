# Tài liệu phát triển FrontEnd — VideoDubing AI

Đây là bộ tài liệu phát triển chính của **FrontEnd VideoDubing AI**.

## Tài liệu chính

### `FRONTEND_DEVELOPMENT.md`

Tài liệu tổng thể, đầy đủ nhất về FrontEnd, bao gồm:

- Kiến trúc hệ thống FrontEnd.
- Cấu trúc thư mục và trách nhiệm từng file.
- Cài đặt PHP, Composer và MySQL.
- Cấu hình runtime, database và session.
- Authentication, Authorization và CSRF.
- Workspace và toàn bộ nghiệp vụ Download, Dubbing, Subtitle, TTS.
- AJAX API và hợp đồng dữ liệu.
- Cơ chế FrontEnd gọi VideoDubing Backend API.
- Timeout, cURL, binary streaming và xử lý lỗi.
- Cơ chế Failure-only Diagnostic Logging.
- Bảng `frontend_failure_logs` cho Workplace/Video Processing.
- Bảng `payment_error_logs` cho Payment.
- Payment Orders.
- Admin cấu hình Payment Provider.
- SePay và payOS.
- Webhook, kiểm tra chữ ký, kiểm tra số tiền và idempotency.
- Gia hạn `users.expires_at`.
- Database và migration.
- Bảo mật.
- Kiểm thử.
- Triển khai và cập nhật Production.
- Các điểm cần kiểm tra tương thích PHP/runtime.

### `FAILURE_LOG_DEPLOY.md`

Hướng dẫn triển khai hai bảng ghi nhận **chỉ khi thất bại**:

```text
frontend_failure_logs
payment_error_logs
```

## Nguyên tắc tài liệu

Tài liệu phải được đọc cùng source code trong `FrontEnd/` và các migration SQL trong `FrontEnd/database/`.

Khi source code thay đổi về:

- nghiệp vụ,
- API contract,
- database schema,
- payment behavior,
- bảo mật,
- deployment procedure,

thì tài liệu tương ứng phải được cập nhật trong cùng một Git change.

## Cài đặt nhanh

```bash
cd FrontEnd
composer install --no-dev --optimize-autoloader
```

Sau đó cấu hình database/runtime secrets theo `INSTALL.md` và môi trường Production.

## Quy tắc Production

Không commit các thông tin bí mật:

- Production passwords.
- Database passwords.
- SMTP passwords.
- payOS credentials.
- SePay secrets.
- API keys/tokens.

Luôn kiểm tra cú pháp bằng **đúng PHP binary đang chạy Production** trước khi deploy.

## Ngôn ngữ

Toàn bộ tài liệu phát triển FrontEnd trong thư mục `docs/` sử dụng **tiếng Việt**. Tên file, tên class/function, tên bảng, tên API endpoint và thuật ngữ kỹ thuật giữ nguyên theo source code để thuận tiện đối chiếu khi phát triển.
