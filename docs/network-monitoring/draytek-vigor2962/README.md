# DrayTek Vigor2962 – Zabbix 7.0

Tài liệu triển khai giám sát DrayTek Vigor2962 bằng Zabbix 7.0, kết hợp SNMP và Webhook.

## Mục tiêu

- SNMP là nguồn giám sát chính cho các chỉ số interface/counter mà thiết bị hỗ trợ.
- Webhook là nguồn bổ sung cho CPU, Memory, Uptime, trạng thái WAN, IP WAN, sessions, DHCP/ARP và số lượng VPN.
- Chuẩn hóa cấu hình để có thể tái sử dụng cho nhiều thiết bị/khách hàng.
- Không ghi đè hoặc thay đổi các Item, Trigger, Graph SNMP hiện hữu khi triển khai bổ sung.

## Tài liệu

1. [Kiến trúc](01-architecture.md)
2. [Chuẩn bị DrayTek](02-device-preparation.md)
3. [Khai báo Host trên Zabbix](03-zabbix-host-configuration.md)
4. [SNMP Items](04-snmp-items.md)
5. [Webhook Items](05-webhook-items.md)
6. [Triggers](06-triggers.md)
7. [Graphs](07-graphs.md)
8. [Dashboard](08-dashboard.md)
9. [Template và triển khai nhiều thiết bị](09-template-deployment.md)
10. [Kiểm thử và nghiệm thu](10-testing.md)
11. [Troubleshooting](11-troubleshooting.md)

## Trạng thái thông tin

### Đã xác nhận từ hệ thống triển khai

- Zabbix Server: 7.0.30.
- DrayTek: Vigor2962, firmware 4.4.3.5, build Jun 2 2025 22:40:33.
- Host technical name: `PEGA-HCM-151NGUYENDINHCHIEU`.
- Visible name: `PEGA HCM - 151 Nguyễn Đình Chiểu - DrayTek Vigor2962`.
- Host ID tại môi trường hiện tại: `10687`.
- SNMP interface: `192.168.105.1:161`.
- WAN1 ifIndex = 4; WAN2 ifIndex = 5.
- SNMP ifInOctets/ifOutOctets dùng OID IF-MIB chuẩn.
- 17 Trapper Items đã được tạo và xác minh trên Host.

### Cần export từ Zabbix trước khi chốt inventory hiện hữu

Thông tin hội thoại chưa chứa đầy đủ danh sách SNMP Items, Trigger, Graph và Dashboard đang có trên Host. Vì vậy tài liệu phân biệt rõ:
- **Đã xác nhận**: thông số có bằng chứng triển khai.
- **Đề xuất**: cấu hình mục tiêu/threshold cần hiệu chỉnh.
- **Cần kiểm kê**: cấu hình hiện hữu phải export từ Zabbix để không bỏ sót hoặc tạo trùng.

Không coi các Trigger/Graph đề xuất trong tài liệu là cấu hình đã được tạo trên Zabbix.
