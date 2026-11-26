# 01. Kiến trúc giám sát

## 1. Thành phần

- DrayTek Vigor2962: thiết bị mạng cần giám sát.
- Zabbix Server 7.0.30: nhận SNMP polling và Zabbix trapper values.
- Webhook Receiver: nhận HTTP POST từ DrayTek, lưu payload nguyên bản vào SQLite.
- Multi-Host Worker: đọc event từ SQLite, xác định thiết bị theo MAC, chuyển các giá trị thành Zabbix Sender protocol.
- Zabbix Host: đích nhận dữ liệu theo technical host name.

## 2. Luồng SNMP

Zabbix Server polling SNMP đến địa chỉ quản trị DrayTek:
- Device IP: `192.168.105.1`
- UDP port: `161`
- SNMP community hiện được cấu hình: `publicmstar`

SNMP là nguồn chính cho interface counters. Kết nối SNMP được thực hiện từ network namespace của Zabbix Server qua IPsec; không giả định rằng IP host Ubuntu có cùng đường định tuyến.

## 3. Luồng Webhook

1. DrayTek gửi JSON HTTP POST đến receiver.
2. Receiver kiểm tra source allow-list và token.
3. Receiver ghi payload nguyên bản vào SQLite `webhook_events`.
4. Worker đọc event, chuẩn hóa MAC và tra bảng `devices`.
5. MAC đã đăng ký và enabled: parse dữ liệu, gửi đến đúng Zabbix Host.
6. MAC chưa đăng ký: phải chuyển trạng thái `unmapped`, không gửi sang Host mặc định.
7. Thiết bị disabled: chuyển `disabled`, không gửi dữ liệu.

## 4. Sơ đồ logic

```text
DrayTek Vigor2962
  |-- SNMP UDP/161 ----------------------> Zabbix Server
  |
  |-- HTTP Webhook ----------------------> Receiver
                                             |
                                             v
                                         SQLite events
                                             |
                                             v
                                         Multi-Host Worker
                                             |
                                  MAC -> devices registry
                                             |
                                             v
                                      Zabbix Sender
                                             |
                                             v
                                      Zabbix Server
```

## 5. Nguyên tắc bảo mật

- Không đưa webhook token hoặc SNMP community vào tài liệu công khai.
- Thông tin xác thực chỉ lưu trong secret/environment hoặc cấu hình bảo mật.
- Giới hạn source IP của webhook theo địa chỉ thiết bị.
- Giới hạn SNMP bằng ACL/firewall chỉ cho phép Zabbix Server.
- Token và community từng xuất hiện trong quá trình trao đổi; cần rotate sau khi nghiệm thu và cập nhật đồng bộ ở thiết bị, receiver và secret store.
- Không dùng MAC không đăng ký làm fallback về một Zabbix Host bất kỳ.

## 6. Độ tin cậy dữ liệu

Webhook worker dùng cơ chế hàng đợi SQLite và retry. Có thể xảy ra at-least-once delivery nếu tiến trình dừng sau khi Zabbix nhận dữ liệu nhưng trước khi SQLite được đánh dấu processed. Không mô tả cơ chế này là exactly-once.
