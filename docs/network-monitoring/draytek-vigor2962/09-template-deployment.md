# 09. Template và triển khai nhiều thiết bị

## 1. Mục tiêu

Chuẩn hóa cấu hình để nhiều Vigor2962 có thể được onboard nhất quán, không phụ thuộc Host ID cụ thể.

## 2. Template đề xuất

Tên gợi ý: `Template Net DrayTek Vigor2962 SNMP + Webhook`

Nhóm cấu hình:
- SNMP interface counters và các SNMP Items đã kiểm kê.
- Webhook Trapper Items.
- Triggers đã được phê duyệt.
- Graphs.
- Tags và macros.

## 3. Host-specific macros

Ví dụ:
- `{$SNMP_COMMUNITY}`: lưu secret theo chính sách Zabbix; không commit giá trị thật.
- `{$WAN1_IFINDEX}`: 4 cho thiết bị hiện tại, phải xác minh từng model/cấu hình.
- `{$WAN2_IFINDEX}`: 5 cho thiết bị hiện tại.
- `{$CPU_WARN}`, `{$CPU_HIGH}`: ngưỡng được vận hành phê duyệt.

## 4. Worker registry

Bảng `devices` hiện dùng các trường:
- `mac_address` unique
- `device_name`
- `model_name`
- `zabbix_host`
- `enabled`
- `created_at`, `updated_at`

Worker xác định Host đích theo MAC đã chuẩn hóa. Không dùng biến ZABBIX_HOST cố định làm fallback cho MAC không đăng ký.

## 5. Điều kiện bắt buộc trước production

- Xác minh Worker container mount đúng SQLite file mà receiver ghi.
- Test MAC đăng ký: gửi đúng Host, đủ key.
- Test MAC không đăng ký: trạng thái `unmapped`, không gửi Zabbix Sender.
- Test device disabled: trạng thái `disabled`, không gửi.
- Test MAC dạng colon/dash/case khác nhau.
- Test Host mapping sai hoặc Host không tồn tại.
- Xác minh toàn bộ Trapper keys tồn tại trên từng Host/Template.
- Có quy trình requeue event `unmapped` sau khi đăng ký thiết bị.
- Có backup DB và quy trình rollback.

## 6. Lưu ý về kiểm thử hiện tại

Trong đợt kiểm thử, Event giả MAC `aa:bb:cc:dd:ee:ff` đã được đánh dấu `processed` dù MAC chưa có trong registry. Đây là kết quả không đạt yêu cầu; chưa được triển khai onboarding nhiều thiết bị cho đến khi xác định và sửa nguyên nhân. Cần kiểm tra Worker runtime/database/code đang thực sự chạy, sau đó lặp lại test và chứng minh không có giá trị nào được gửi sang Host thật.
