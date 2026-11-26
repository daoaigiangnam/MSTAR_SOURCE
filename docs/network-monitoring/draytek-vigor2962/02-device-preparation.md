# 02. Chuẩn bị DrayTek Vigor2962

## 1. Thông tin thiết bị đã xác nhận

| Thuộc tính | Giá trị |
|---|---|
| Model | Vigor2962 |
| Firmware | 4.4.3.5 |
| Build | Jun 2 2025 22:40:33 |
| Management IP | 192.168.105.1 |
| SNMP port | UDP/161 |
| Webhook source IP | 192.168.105.1 |

## 2. SNMP

Trên giao diện quản trị DrayTek:
1. Bật SNMP theo chính sách của đơn vị.
2. Cấu hình community tương ứng với secret đang lưu ở Zabbix.
3. Chỉ cho phép IP nguồn của Zabbix Server truy vấn SNMP.
4. Kiểm tra UDP/161 qua IPsec/firewall.
5. Không sử dụng community mặc định trong môi trường production.

Community từng dùng trong môi trường thử nghiệm là secret; không ghi giá trị community vào tài liệu. Nếu community cũ đã được chia sẻ ngoài phạm vi quản trị, hãy đổi community và cập nhật Zabbix đồng thời.

## 3. Webhook

Cấu hình webhook gửi HTTP POST JSON đến endpoint receiver theo URL/token do quản trị viên cấp. Không đưa token thật vào tài liệu hoặc ticket.

Các nhóm dữ liệu đã bật trong triển khai:
- WAN Rx/Tx
- WAN Rx/Tx Total
- WAN Current Session
- WAN ARP Table
- DHCP Table
- VPN Connections

Receiver đang allow-list source `192.168.105.1/32`. Đảm bảo route/firewall cho phép thiết bị đến endpoint receiver qua IPsec.

## 4. Payload mẫu đã quan sát

Các trường chính trong payload:
- `device.Model Name`
- `device.Device Name`
- `device.MAC`
- `Status.Uptime`
- `Status.Memory`
- `Status.CPU`
- `Status.WAN1_status`, `WAN1_IP`, `WAN1_rx_rate`, `WAN1_tx_rate`, `WAN1_sessions`
- Các trường WAN2 tương ứng
- `Status.DHCP_total`, `ARP_total`
- `Status.L2LVPN_counts`, `Host2LVPN_counts`

Lưu ý: đơn vị của `WAN*_rx_rate` và `WAN*_tx_rate` chưa được xác minh. Không tự gắn nhãn Mbps trước khi xác nhận tài liệu firmware hoặc đo đối chiếu.

## 5. Kiểm tra sau cấu hình

- SNMP walk/get từ đúng network namespace của Zabbix Server.
- Xác nhận receiver nhận HTTP 200.
- Kiểm tra event mới trong SQLite.
- Xác nhận Worker ánh xạ đúng MAC đến Zabbix Host.
- Kiểm tra Latest data trên Zabbix.
