# VideoDubing AI FrontEnd — Tài liệu phát triển

## 1. Mục đích và phạm vi

Đây là tài liệu tham chiếu phát triển chính cho **FrontEnd PHP của VideoDubing AI**.

FrontEnd chịu trách nhiệm:

- Xác thực người dùng và quản lý session.
- Đăng ký tài khoản, kích hoạt tài khoản và các luồng đổi/đặt lại mật khẩu.
- Giao diện Workspace cho Download, Lồng tiếng (Dubbing), Phụ đề (Subtitle) và Chuyển văn bản thành giọng nói (Text-to-Speech).
- Làm proxy trung gian từ trình duyệt đến VideoDubing Backend API.
- Lưu lịch sử Job của người dùng.
- Kiểm soát gói dịch vụ và thời hạn sử dụng.
- Tạo đơn thanh toán và hiển thị trạng thái thanh toán.
- Quản trị người dùng và cấu hình Payment Provider.
- Tích hợp SePay và payOS.
- Ghi nhận lỗi phục vụ chẩn đoán, chỉ ghi khi thất bại đối với Workplace/xử lý video và thanh toán.

FrontEnd sử dụng **PHP + MySQL**. FrontEnd **không phải AI processing engine**. Việc xử lý video/AI dài thời gian được giao cho VideoDubing Backend API.

---

## 2. Kiến trúc hiện tại

```text
Trình duyệt
  |
  | HTTPS
  v
FrontEnd PHP
  |
  +-- Xác thực / Session
  |
  +-- Workspace
  |     +-- Download
  |     +-- Dubbing
  |     +-- Subtitle
  |     +-- TTS
  |     +-- Lịch sử Job
  |
  +-- Thanh toán
  |     +-- payment_orders
  |     +-- Admin cấu hình Provider
  |     +-- SePay
  |     +-- payOS
  |
  +-- Chẩn đoán lỗi
  |     +-- frontend_failure_logs
  |     +-- payment_error_logs
  |
  +-- MySQL
  |
  +-- HTTP API
         |
         v
   VideoDubing Backend API
         |
         +-- download
         +-- jobs
         +-- output
         +-- voices
         +-- xử lý AI/video
```

FrontEnd sở hữu các quy tắc nghiệp vụ về **người dùng, session và thanh toán**. Backend sở hữu việc **xử lý media/AI chạy lâu**.

---

## 3. Cấu trúc mã nguồn và trách nhiệm

Các file FrontEnd quan trọng:

```text
FrontEnd/
├── admin.php                 # Cổng quản trị / quản lý người dùng
├── admin-payment.php         # Cấu hình Payment Provider
├── workspace.php             # Workspace chính của người dùng
├── checkout.php              # Giao diện thanh toán
├── payment-create.php        # Tạo đơn thanh toán
├── payment-status.php        # Đọc trạng thái đơn thanh toán
├── payos-webhook.php         # Webhook server của payOS
├── sepay-webhook.php         # Webhook của SePay
├── activate.php              # Kích hoạt tài khoản
├── change-password.php       # Đổi mật khẩu
├── login.php                 # Đăng nhập
├── register.php              # Đăng ký
├── logout.php                # Đăng xuất
├── forgot-password.php       # Yêu cầu đặt lại mật khẩu
├── reset-password.php        # Hoàn tất đặt lại mật khẩu
├── ajax/api.php              # AJAX endpoint / proxy từ trình duyệt
├── config/config.php         # Cấu hình runtime ứng dụng
├── config/database.php       # Khởi tạo/cấu hình DB
├── lib/auth.php              # Hàm xác thực/session
├── lib/api.php               # Client gọi Backend API + trace lỗi
├── lib/payment.php           # Hàm nghiệp vụ thanh toán
├── lib/payment-provider.php  # Chọn/cấu hình Provider
├── lib/payos.php             # Triển khai payOS / kiểm tra chữ ký
├── lib/payment-error-log.php # Ghi lỗi thanh toán
├── lib/failure-log.php       # Ghi lỗi Workplace
├── lib/mailer.php            # SMTP mail helper
└── database/                 # Migration/schema SQL
```

Repository còn có CSS/JS và các cron worker. Ứng dụng trình duyệt chủ yếu được điều khiển bởi `workspace.php` + `assets/js/app.js`; các PHP endpoint đảm nhiệm xác thực, proxy API và nghiệp vụ thanh toán.

---

## 4. Runtime và cài đặt

### 4.1 PHP

`FrontEnd/INSTALL.md` hiện ghi PHP 7.4. Tuy nhiên source hiện tại có các hàm sử dụng kiểu trả về `never` (ví dụ helper trả JSON), và `never` không được PHP 7.4 hỗ trợ. Vì vậy phải kiểm tra **PHP runtime thực tế của Production** với chính source đang triển khai trước khi nâng cấp hoặc deploy.

**Quy tắc triển khai:** luôn kiểm tra cú pháp bằng đúng PHP-FPM/CLI đang chạy Production. Không dùng một PHP binary khác để kết luận source hợp lệ.

Ví dụ trên server hiện tại:

```bash
/www/server/php/74/bin/php -l payment-create.php
/www/server/php/74/bin/php -l payment-status.php
/www/server/php/74/bin/php -l lib/payment.php
/www/server/php/74/bin/php -l lib/payment-provider.php
/www/server/php/74/bin/php -l lib/payos.php
/www/server/php/74/bin/php -l payos-webhook.php
/www/server/php/74/bin/php -l sepay-webhook.php
```

Nếu source triển khai có `never`, phải dùng PHP có hỗ trợ `never` hoặc refactor chữ ký hàm trước khi Production. Đây là **điểm kiểm tra tương thích bắt buộc**.

### 4.2 Composer

Từ thư mục `FrontEnd/`:

```bash
composer install --no-dev --optimize-autoloader
```

Composer được sử dụng cho PHPMailer và chức năng gửi mail của FrontEnd.

### 4.3 MySQL

Tạo/import schema cơ sở dữ liệu, sau đó chạy các migration theo thứ tự thời gian. Các migration thanh toán và chẩn đoán nằm trong `database/`.

Migration ghi nhận lỗi:

```text
FrontEnd/database/20260819_create_failure_logs.sql
```

Migration này tạo đúng hai bảng chẩn đoán:

```text
frontend_failure_logs
payment_error_logs
```

Chạy một lần trên database của FrontEnd.

### 4.4 Environment và cấu hình

Repository có các file `.env.example`, nhưng trang quản trị thanh toán hiện tại yêu cầu credential thanh toán trong `config/config.php`. **Không commit secret Production lên Git.**

Các cấu hình runtime chính gồm:

```text
DB_HOST
DB_PORT
DB_NAME
DB_USER
DB_PASSWORD

MAIL_HOST
MAIL_PORT
MAIL_USERNAME
MAIL_PASSWORD
MAIL_ENCRYPTION
MAIL_FROM
MAIL_FROM_NAME

VIDEODUBING_API_URL
VIDEODUBING_API_TIMEOUT
VIDEODUBING_WEB_URL
TRIAL_DAYS
SESSION_NAME
SESSION_SAVE_PATH
```

Cấu hình thanh toán gồm credential riêng của từng Provider và thông tin ngân hàng. Chỉ lưu các thông tin này trong cấu hình Production.

### 4.5 Session

Ứng dụng sử dụng một thư mục PHP session dùng chung để `workspace.php` và `ajax/api.php` có thể sử dụng cùng session đăng nhập khi chạy qua các PHP-FPM pool/version khác nhau.

Mặc định:

```text
/tmp/videodubing-ai-sessions
```

Có thể thay đổi bằng:

```text
SESSION_SAVE_PATH=/path/to/shared/session-directory
```

User chạy PHP-FPM phải có quyền đọc/ghi thư mục này.

---

## 5. Xác thực và phân quyền

Xác thực được tập trung trong `lib/auth.php`.

Các quy tắc chính:

- Các trang công khai có thể truy cập không cần đăng nhập khi phù hợp.
- Trang người dùng cần bảo vệ gọi `require_login()`.
- AJAX endpoint gọi `require_ajax_login()`.
- Trang chỉ dành cho Admin gọi `require_admin()`.
- Các request làm thay đổi dữ liệu phải kiểm tra CSRF.

Quyền Admin được kiểm soát theo role. Người dùng thông thường **không được chọn Payment Provider và không được thay đổi cấu hình Provider**.

Có thể tạo Administrator đầu tiên bằng:

```bash
php bin/create_admin.php "System Administrator" admin@example.com 'ChangeThisPassword!'
```

Production phải sử dụng mật khẩu mạnh.

---

## 6. Luồng nghiệp vụ Workspace

`workspace.php` là giao diện ứng dụng chính.

Các chế độ hiện có:

1. Download
2. Dubbing — Lồng tiếng
3. Subtitle — Phụ đề
4. Text → Speech — Chuyển văn bản thành giọng nói

Trang cũng hiển thị trạng thái gói dịch vụ và gói đã thanh toán gần nhất.

Nếu `expires_at` đã quá hạn, người dùng vẫn có thể đăng nhập nhưng các chức năng xử lý bị khóa và giao diện cung cấp đường dẫn gia hạn.

Số ngày còn lại được tính từ thời gian hiện tại của server và timestamp hết hạn thực tế; giao diện không dùng cách tính đơn giản kiểu `ceil(seconds / 86400)`.

### Luồng xử lý

```text
Người dùng
  |
  v
workspace.php / app.js
  |
  v
ajax/api.php
  |
  v
lib/api.php
  |
  | HTTP
  v
VideoDubing Backend API
  |
  +--> tạo/download Job
  +--> kiểm tra trạng thái Job
  +--> liệt kê file output
  +--> tải file output
```

Request thành công **không được ghi** vào database failure log.

---

## 7. Hợp đồng AJAX API

`FrontEnd/ajax/api.php` là cổng JSON/binary hướng tới trình duyệt. Endpoint yêu cầu người dùng đã đăng nhập.

### `action=heartbeat`

Trả về phản hồi kiểm tra hoạt động cùng thời gian server.

### `action=job_history_list`

Trả về lịch sử Job mà người dùng hiện tại được xem, mới nhất trước, tối đa 50 dòng.

### `action=job_history_save`

Body JSON POST:

```json
{
  "job_id": "...",
  "title": "...",
  "mode": "dubbing"
}
```

Bản ghi lịch sử được gắn với user đã xác thực.

### `action=job_history_hide`

Body JSON POST:

```json
{
  "job_id": "..."
}
```

Ẩn bản ghi lịch sử đối với user hiện tại. Không xóa Job thực tế ở Backend.

### `action=languages`

Proxy `GET /languages` đến VideoDubing Backend.

### `action=voices`

Proxy `GET /voices`, có thể truyền `language`.

### `action=preview`

POST đến `voices/preview` và stream kết quả audio dạng binary về trình duyệt.

### `action=tts`

POST đến `voices/synthesize` và stream kết quả MP3 về trình duyệt.

### `action=create`

POST đến Backend `download` và trả JSON response.

### `action=job`

GET `jobs/{job_id}`.

### `action=files`

GET `output/{job_id}/files`.

### `action=output_file`

Stream một trong các loại:

```text
original
subtitle
subtitled
dubbed
```

### `action=output`

Stream video output chính.

---

## 8. Client gọi Backend API

`lib/api.php` là HTTP client trung tâm của FrontEnd.

Client sử dụng cURL với:

- Request/response JSON cho API thông thường.
- Binary streaming cho audio/video.
- Connect timeout 10 giây.
- Request timeout ứng dụng lấy từ `VIDEODUBING_API_TIMEOUT`.

Client xác định Job ID từ URL dạng `jobs/{id}` hoặc từ các key phổ biến trong request body: `job_id`, `jobId`, `id`.

### Xử lý lỗi

**Chỉ khi thất bại**, client ghi nhận:

- Lỗi HTTP.
- JSON không hợp lệ.
- Lỗi cURL.
- Timeout.
- Connection reset.
- Response body từ API.
- Thời gian request/response.
- Endpoint và operation.
- Job ID nếu xác định được.
- URL video nếu có.
- CPU load trung bình 1/5/15 phút.
- RAM host: tổng/đã dùng/còn khả dụng.
- Disk: tổng/còn trống/% còn trống.
- PHP memory usage và peak.
- Hostname/PHP/OS.
- Thông tin chẩn đoán cURL.
- `context_json`.

Snapshot tài nguyên được thu thập **ngay tại thời điểm phát hiện lỗi**.

### Nguyên tắc quan trọng

Failure logger không được phép làm thay đổi kết quả nghiệp vụ gốc. Nếu chính việc ghi log thất bại thì lỗi API ban đầu vẫn phải được trả về cho người dùng.

---

## 9. Cơ chế ghi log chỉ khi thất bại

Thiết kế sử dụng đúng **hai bảng** và chỉ ghi các trường hợp lỗi/bất thường.

### Bảng Workplace/video

```text
frontend_failure_logs
```

Mục đích: trace một request FrontEnd → Backend/xử lý video bị thất bại.

Bảng chứa thông tin định danh trace, Job/User, request/response, thời gian, thông tin video, tài nguyên host, chẩn đoán cURL và `context_json`.

### Bảng thanh toán

```text
payment_error_logs
```

Mục đích: trace lỗi Provider và lỗi trong luồng thanh toán.

**Thanh toán thành công không ghi log.** Nguồn sự thật về trạng thái thanh toán vẫn là `payment_orders`.

Không được ghi vào diagnostic database:

- API key.
- Checksum key.
- Secret.
- Authorization token.

---

## 10. Thiết kế cơ sở dữ liệu

FrontEnd sử dụng MySQL với `utf8mb4`.

Các bảng nghiệp vụ quan trọng gồm:

```text
users
payment_orders
payment_provider_settings
job_history
job_email_notifications
```

Các bảng chẩn đoán:

```text
frontend_failure_logs
payment_error_logs
```

`payment_orders` là bản ghi nghiệp vụ có thẩm quyền về thanh toán. Một record trong `payment_error_logs` tự nó **không có nghĩa** đơn hàng đã thanh toán hoặc chưa thanh toán.

---

## 11. Kiến trúc thanh toán

Việc chọn Payment Provider là **cấu hình của Admin**, không phải lựa chọn của User.

Provider hiện tại:

```text
sepay
payos
```

`lib/payment-provider.php` đọc Provider được bật/mặc định từ `payment_provider_settings`.

Provider mặc định được lấy bằng:

```sql
SELECT provider_code
FROM payment_provider_settings
WHERE is_enabled=1 AND is_default=1
ORDER BY id
LIMIT 1
```

Nếu không có dòng mặc định, hệ thống có fallback an toàn là `sepay`.

Admin có thể:

- Bật Provider.
- Tắt Provider không phải mặc định.
- Đặt Provider làm mặc định.

Không được tắt Provider đang là mặc định trước khi chọn Provider khác làm mặc định.

Các đơn hàng cũ giữ nguyên `payment_provider` ban đầu; thay đổi Provider mặc định **không sửa lại các đơn lịch sử**.

---

## 12. Luồng tạo thanh toán

`payment-create.php` chỉ nhận POST và yêu cầu:

- User đã đăng nhập.
- CSRF hợp lệ.
- Plan hợp lệ.

Luồng:

```text
User chọn gói
      |
      v
payment-create.php
      |
      +-- kiểm tra Plan
      |
      +-- đọc Provider mặc định của Admin
      |
      +-- tạo order code
      |
      +-- INSERT payment_orders với trạng thái pending
      |
      +-- tạo thanh toán theo Provider
      |
      +-- trả dữ liệu checkout/QR
```

User **không gửi `provider` để lựa chọn phương thức thanh toán**.

### SePay

FrontEnd tạo đơn `pending` và trả về QR ngân hàng, ngân hàng, nội dung chuyển khoản/order code. Xác nhận thanh toán được thực hiện qua SePay webhook.

### payOS

FrontEnd tạo đơn `pending`, gọi payOS tạo payment link, lưu `provider_order_id` và `payment_link_id`, sau đó trả thông tin checkout/QR cho trình duyệt.

---

## 13. Luồng kiểm tra trạng thái thanh toán

`payment-status.php` chỉ nhận GET và yêu cầu đăng nhập.

Client cung cấp order code của FrontEnd. Endpoint kiểm tra đơn đó thuộc user đang đăng nhập.

Nếu đơn `pending` đã quá `payment_expires_at`, đơn được chuyển sang `expired`.

Response chỉ chứa thông tin đơn hàng và dữ liệu hiển thị thanh toán được phép công khai; secret của Provider không bao giờ được trả về.

---

## 14. Luồng payOS Webhook

`payos-webhook.php` chỉ nhận POST.

Luồng tổng quát:

```text
payOS
  |
  v
payos-webhook.php
  |
  +-- kiểm tra POST
  +-- nạp cấu hình
  +-- đọc JSON body
  +-- kiểm tra chữ ký
  +-- yêu cầu mã thanh toán thành công
  +-- tìm đơn bằng provider_order_id
  +-- kiểm tra payment link nếu đã lưu
  +-- kiểm tra description ngắn
  +-- kiểm tra amount
  +-- khóa payment order
  +-- khóa user
  +-- tính expiry mới
  +-- cập nhật users.expires_at
  +-- đánh dấu payment_orders paid
  +-- commit transaction
  |
  v
HTTP 200
```

Tính **idempotency** được đảm bảo bằng cách chỉ cho phép đơn `pending` thực hiện thay đổi quota/subscription. Đơn đã ở trạng thái cuối được acknowledge mà không cấp thêm thời hạn lần nữa.

`payment_expires_at` cũng được kiểm tra trước khi cấp dịch vụ.

Nếu không tìm thấy đơn hoặc một kiểm tra integrity không khớp, webhook được acknowledge nhưng **không thay đổi tài khoản**.

---

## 15. Luồng SePay Webhook

`sepay-webhook.php` tiếp nhận thông báo thanh toán từ SePay.

Nguyên tắc nghiệp vụ tương tự:

- Xác định payment order của FrontEnd.
- Kiểm tra thông tin Provider/chữ ký/secret theo cơ chế SePay đang cấu hình.
- Kiểm tra amount và order matching.
- Ngăn cấp subscription nhiều lần.
- Chỉ cập nhật `users.expires_at` và payment order sau khi mọi kiểm tra hợp lệ.
- Ghi lỗi kỹ thuật vào `payment_error_logs`.

Hợp đồng payload cụ thể của Provider được triển khai trong source file và phải được giữ nguyên khi thay đổi integration.

---

## 16. Quy tắc gia hạn và thời hạn

Khi một khoản thanh toán hợp lệ được xác nhận:

```text
Thời hạn hiện tại của User
       |
       +-- còn hiệu lực --> cộng tiếp từ thời hạn hiện tại
       |
       +-- hết hạn/rỗng --> cộng từ thời gian hiện tại
```

Giá trị `days` của đơn đã thanh toán được cộng vào ngày cơ sở.

Kết quả được ghi vào:

```text
users.expires_at
payment_orders.expires_at
```

Đơn thanh toán được đánh dấu:

```text
status = paid
paid_at = NOW()
```

Cơ chế này bảo đảm khi User mua tiếp trong lúc gói cũ còn hạn thì thời hạn được **nối tiếp**, không bị reset về ngày mua mới.

---

## 17. Mô hình bảo mật

### Xác thực

Sử dụng các hàm xác thực tập trung. Không tự viết logic login/session riêng trong từng trang.

### CSRF

Mọi form/endpoint đã xác thực có thay đổi dữ liệu phải sử dụng cơ chế CSRF hiện có.

### SQL

Sử dụng PDO prepared statements. Không nối trực tiếp dữ liệu từ User vào SQL.

### Encode output

HTML output phải sử dụng `htmlspecialchars` với `ENT_QUOTES` và UTF-8.

### Secret của Provider

Không bao giờ đưa credential thanh toán vào JavaScript, response trình duyệt hoặc diagnostic log.

### Webhook

Không được thay đổi trạng thái thanh toán trước khi xác thực nguồn Provider và kiểm tra integrity của order/amount thành công.

### Idempotency

Webhook có thể được Provider gửi lại nhiều lần. Luồng cập nhật thanh toán phải an toàn khi nhận cùng một webhook nhiều lần.

---

## 18. Phân loại lỗi

Lỗi Workplace/API nên sử dụng `error_type` có ý nghĩa, ví dụ:

```text
HTTP_ERROR
INVALID_JSON
TIMEOUT
CONNECTION_RESET
CURL_ERROR
EXCEPTION
```

Lỗi thanh toán có thể phân loại:

```text
INVALID_PLAN
EXCEPTION
HTTP_ERROR
TIMEOUT
CONNECTION_RESET
SIGNATURE_ERROR
AMOUNT_MISMATCH
UNKNOWN_ORDER
```

Có thể mở rộng danh mục sau này, nhưng giá trị phải mô tả **cơ chế lỗi**, không chỉ lặp lại thông báo cho người dùng.

---

## 19. Checklist kiểm thử

### Kiểm tra cú pháp PHP

Chạy tất cả PHP file đã thay đổi bằng PHP binary của Production:

```bash
for f in \
config/config.php \
payment-create.php \
payment-status.php \
lib/payment.php \
lib/payment-provider.php \
lib/payos.php \
lib/failure-log.php \
lib/payment-error-log.php \
payos-webhook.php \
sepay-webhook.php \
ajax/api.php
 do
    echo "========================================"
    echo "CHECK: $f"
    /path/to/production/php -l "$f"
done
```

### Database

```sql
SHOW TABLES LIKE 'frontend_failure_logs';
SHOW TABLES LIKE 'payment_error_logs';
```

### Kiểm tra webhook có thể truy cập

Gọi GET vào webhook chỉ nhận POST phải trả `405`. Đây là hành vi đúng.

Ví dụ:

```bash
curl -i https://YOUR_DOMAIN/payos-webhook.php
```

Kết quả mong đợi về method:

```text
HTTP 405
{"success":false,"message":"Method Not Allowed"}
```

### Kiểm thử lỗi Workplace

Tạo một lỗi Backend có kiểm soát và xác nhận đúng một record được ghi vào `frontend_failure_logs`.

### Kiểm thử lỗi thanh toán

Kiểm thử Plan không hợp lệ, Provider không khả dụng, Provider trả response sai và webhook integrity không hợp lệ. Xác nhận lỗi được ghi vào `payment_error_logs` nhưng không làm sai trạng thái một payment order hợp lệ.

### Kiểm thử thanh toán thành công

Kiểm thử riêng từng Provider và xác nhận:

```text
payment_orders.status = paid
payment_orders.paid_at được cập nhật
users.expires_at được gia hạn đúng
```

Gửi cùng một webhook thành công hai lần và xác nhận subscription **không được cộng hai lần**.

---

## 20. Câu lệnh chẩn đoán

### Các lỗi Workplace mới nhất

```sql
SELECT id,trace_id,job_id,user_id,module,action,http_status,
       error_type,error_message,failed_at,load_1,load_5,load_15,
       memory_used_bytes,disk_free_percent
FROM frontend_failure_logs
ORDER BY failed_at DESC
LIMIT 100;
```

### Các lỗi thanh toán mới nhất

```sql
SELECT id,trace_id,provider,action,order_code,payment_order_id,
       http_status,error_type,error_message,failed_at
FROM payment_error_logs
ORDER BY failed_at DESC
LIMIT 100;
```

### Lỗi của một Job

```sql
SELECT *
FROM frontend_failure_logs
WHERE job_id='JOB_ID'
ORDER BY failed_at DESC;
```

### Các lỗi xảy ra khi server có tải cao

```sql
SELECT failed_at,job_id,error_type,http_status,
       load_1,load_5,load_15,
       memory_used_bytes,memory_available_bytes,
       disk_free_percent
FROM frontend_failure_logs
WHERE load_1 >= 8
ORDER BY failed_at DESC;
```

---

## 21. Quy trình cập nhật Production

```bash
cd /www/wwwroot/videotool.review360.id.vn

git status
git pull origin main
```

Trước khi chuyển traffic hoặc restart PHP-FPM:

1. Kiểm tra Git diff.
2. Bảo đảm cấu hình Production không bị ghi đè.
3. Chạy PHP lint bằng đúng PHP binary Production.
4. Chỉ chạy các SQL migration mới.
5. Kiểm tra trang landing/login.
6. Kiểm tra `/workspace.php`.
7. Kiểm tra một API request nhỏ.
8. Kiểm tra trạng thái thanh toán.
9. Không thay đổi Provider mặc định nếu không có chủ đích.

**Không commit** `config/config.php` Production chứa secret hoặc `.env` chứa credential.

---

## 22. Nguyên tắc phát triển FrontEnd

Các thay đổi FrontEnd phải tuân theo các nguyên tắc sau:

1. **FrontEnd quản lý nghiệp vụ người dùng và thanh toán; Backend quản lý xử lý AI/video dài thời gian.**
2. **Không để User tự chọn Payment Provider.** Provider được Admin cấu hình.
3. **`payment_orders` là nguồn sự thật về thanh toán.** `payment_error_logs` chỉ phục vụ trace lỗi.
4. **Chỉ ghi diagnostic log khi thất bại.** Không ghi thành công vào hai bảng failure log.
5. **Không lưu secret trong Git hoặc diagnostic database.**
6. **Không để failure logger làm thay đổi nghiệp vụ gốc.**
7. **Webhook phải kiểm tra authenticity, amount, order và idempotency trước khi cập nhật dữ liệu.**
8. **Mọi thay đổi schema phải có migration SQL.**
9. **Mọi thay đổi API/nghiệp vụ phải cập nhật tài liệu trong cùng Git change.**
10. **Luôn kiểm tra bằng đúng PHP runtime của Production trước khi deploy.**

Đây là tài liệu phát triển chuẩn của FrontEnd tại thời điểm hiện tại. Khi source code thay đổi, tài liệu này phải được cập nhật đồng thời để giữ tài liệu và mã nguồn đồng bộ.