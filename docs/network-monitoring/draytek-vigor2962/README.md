# DrayTek Vigor2962 – Zabbix 7.0

Tài liệu này tổng hợp **toàn bộ quá trình triển khai từ đầu đến hiện tại**, không chỉ là hướng dẫn cấu hình cuối cùng. Nội dung bao gồm mạng/IPsec, SNMP, Zabbix Host/Items, Webhook Receiver, SQLite queue, Docker, Worker, Multi-Host, các lần kiểm thử và các vấn đề còn tồn tại.

## Lịch sử triển khai và tài liệu

1. [Lịch sử triển khai từ đầu đến hiện tại](12-implementation-history.md)
2. [Kiến trúc tổng thể](01-architecture.md)
3. [Chuẩn bị DrayTek](02-device-preparation.md)
4. [Khai báo Host trên Zabbix](03-zabbix-host-configuration.md)
5. [SNMP Items](04-snmp-items.md)
6. [Webhook Items](05-webhook-items.md)
7. [Triggers](06-triggers.md)
8. [Graphs](07-graphs.md)
9. [Dashboard](08-dashboard.md)
10. [Template và triển khai nhiều thiết bị](09-template-deployment.md)
11. [Kiểm thử và nghiệm thu](10-testing.md)
12. [Troubleshooting](11-troubleshooting.md)

## Môi trường đã triển khai

| Thành phần | Thông tin |
|---|---|
| DrayTek | Vigor2962 |
| Firmware | 4.4.3.5, build Jun 2 2025 22:40:33 |
| DrayTek LAN/SNMP IP | 192.168.105.1 |
| Zabbix | 7.0.30 |
| Zabbix Host technical name | `PEGA-HCM-151NGUYENDINHCHIEU` |
| Visible name | `PEGA HCM - 151 Nguyễn Đình Chiểu - DrayTek Vigor2962` |
| Host ID hiện tại | 10687 |
| Host group | Network devices |
| SNMP interface | 192.168.105.1:161 |
| WAN1 / WAN2 ifIndex | 4 / 5 |
| Webhook Receiver | HTTP port 8080 trong network namespace Zabbix Server |
| Zabbix Sender | 127.0.0.1:10051 trong cùng network namespace |

## Trạng thái hiện tại

- Receiver đã nhận webhook và lưu raw payload vào SQLite.
- Worker Multi-Host mới đã khởi động và xử lý Event thật của MAC `1449bc9135d0`, gửi đủ 17 values đến Host hiện tại.
- 17 Trapper Items đã được xác minh enabled trên Host.
- SNMP là nguồn chính cho interface counters; Webhook là nguồn bổ sung.
- Chức năng chặn MAC chưa đăng ký **chưa đạt nghiệm thu**: Event kiểm thử MAC `aa:bb:cc:dd:ee:ff` đã bị đánh dấu `processed` thay vì `unmapped`. Không onboard thêm thiết bị cho đến khi xác định nguyên nhân và kiểm thử lại.
- Chưa có export đầy đủ các SNMP Items, Trigger và Graph hiện hữu. Các Trigger/Graph trong tài liệu là thiết kế đề xuất, không được hiểu là đã tạo.

## Bảo mật

Không ghi token webhook hoặc SNMP community thật vào repository. Credentials phải được rotate nếu đã lộ trong trao đổi/log, và chỉ lưu trong secret store hoặc cấu hình bảo mật.
