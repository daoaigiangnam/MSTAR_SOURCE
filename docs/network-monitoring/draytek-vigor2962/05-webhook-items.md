# 05. Webhook Trapper Items

## 1. Quy tắc

- Type: Zabbix trapper.
- Sender host: technical host name.
- Key phải khớp tuyệt đối giữa Worker và Zabbix.
- Dữ liệu gửi theo từng Event; worker hiện gửi 17 giá trị.
- Không map MAC chưa đăng ký vào Host mặc định.

## 2. Danh mục 17 Items đã xác nhận

| # | Name | Key | Value type | Units |
|---:|---|---|---|---|
| 1 | DrayTek CPU Utilization | `draytek.cpu.util` | Numeric unsigned | % |
| 2 | Memory Utilization | `draytek.webhook.memory.util` | Numeric unsigned | % |
| 3 | Uptime | `draytek.webhook.uptime` | Numeric unsigned | uptime (đang dùng hiển thị) |
| 4 | WAN1 Status | `draytek.webhook.wan1.status` | Character | — |
| 5 | WAN1 IP | `draytek.webhook.wan1.ip` | Character | — |
| 6 | WAN1 Sessions | `draytek.webhook.wan1.sessions` | Numeric unsigned | — |
| 7 | WAN1 RX Rate Raw | `draytek.webhook.wan1.rx.rate.raw` | Numeric unsigned | raw |
| 8 | WAN1 TX Rate Raw | `draytek.webhook.wan1.tx.rate.raw` | Numeric unsigned | raw |
| 9 | WAN2 Status | `draytek.webhook.wan2.status` | Character | — |
| 10 | WAN2 IP | `draytek.webhook.wan2.ip` | Character | — |
| 11 | WAN2 Sessions | `draytek.webhook.wan2.sessions` | Numeric unsigned | — |
| 12 | WAN2 RX Rate Raw | `draytek.webhook.wan2.rx.rate.raw` | Numeric unsigned | raw |
| 13 | WAN2 TX Rate Raw | `draytek.webhook.wan2.tx.rate.raw` | Numeric unsigned | raw |
| 14 | DHCP Client Count | `draytek.webhook.lan.dhcp.count` | Numeric unsigned | — |
| 15 | ARP Table Count | `draytek.webhook.lan.arp.count` | Numeric unsigned | — |
| 16 | Site-to-Site VPN Count | `draytek.webhook.vpn.l2l.count` | Numeric unsigned | — |
| 17 | Host-to-LAN VPN Count | `draytek.webhook.vpn.host2l.count` | Numeric unsigned | — |

CPU Item ID hiện hữu: `51290`. Các Item ID bổ sung từng được ghi nhận trong môi trường hiện tại là `51291–51306`; không dùng các ID này làm định danh khi triển khai môi trường khác.

## 3. Cấu hình tạo Item

Trong Data collection → Hosts → Items → Create item:
- Type: Zabbix trapper.
- Name: theo danh mục.
- Key: chính xác theo bảng.
- Type of information: Numeric unsigned cho số; Character cho status/IP.
- Units: theo bảng.
- Enabled: bật.
- History/trends: theo retention policy của hệ thống.

## 4. Payload mapping

| JSON path | Zabbix key |
|---|---|
| `Status.CPU` | `draytek.cpu.util` |
| `Status.Memory` | `draytek.webhook.memory.util` |
| `Status.Uptime` | `draytek.webhook.uptime` |
| `Status.WAN1_status` | `draytek.webhook.wan1.status` |
| `Status.WAN1_IP` | `draytek.webhook.wan1.ip` |
| `Status.WAN1_sessions` | `draytek.webhook.wan1.sessions` |
| `Status.WAN1_rx_rate` | `draytek.webhook.wan1.rx.rate.raw` |
| `Status.WAN1_tx_rate` | `draytek.webhook.wan1.tx.rate.raw` |
| WAN2 fields | WAN2 keys tương ứng |
| `Status.DHCP_total` | `draytek.webhook.lan.dhcp.count` |
| `Status.ARP_total` | `draytek.webhook.lan.arp.count` |
| `Status.L2LVPN_counts` | `draytek.webhook.vpn.l2l.count` |
| `Status.Host2LVPN_counts` | `draytek.webhook.vpn.host2l.count` |

## 5. Đơn vị rate

Các trường `WAN1_rx_rate`, `WAN1_tx_rate`, `WAN2_rx_rate`, `WAN2_tx_rate` hiện được lưu với units `raw`. Đơn vị gốc chưa xác minh. Không đổi sang bps/Mbps hoặc áp dụng phép nhân/chia nếu chưa đối chiếu tài liệu DrayTek và đo thực tế.

## 6. MAC registry

Worker chuẩn hóa MAC bằng cách loại bỏ dấu phân cách và chuyển chữ thường. Ví dụ:
- `1449bc9135d0`
- `14:49:BC:91:35:D0`
- `14-49-BC-91-35-D0`

Đều chuẩn hóa thành `1449bc9135d0`.

Bảng `devices` ánh xạ MAC → Zabbix technical host. Thiết bị chưa đăng ký phải thành `unmapped`; disabled phải thành `disabled`. Trước khi đưa vào production nhiều khách hàng, bắt buộc kiểm thử hai trường hợp này và xác minh Worker thực tế đang dùng đúng SQLite database.
