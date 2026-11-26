# 07. Graphs

Các Graph dưới đây là thiết kế khuyến nghị. Cần đối chiếu key hiện hữu trước khi tạo để tránh trùng Graph.

## 1. Graph catalogue

| Graph | Items | Kiểu hiển thị | Mục đích |
|---|---|---|---|
| Device CPU & Memory | CPU, Memory | Line | Theo dõi tải thiết bị |
| WAN1 Traffic | WAN1 In/Out rate từ SNMP | Line | Lưu lượng WAN1 |
| WAN2 Traffic | WAN2 In/Out rate từ SNMP | Line | Lưu lượng WAN2 |
| WAN Sessions | WAN1 sessions, WAN2 sessions | Line | Số phiên hiện tại |
| LAN Table Counts | DHCP count, ARP count | Line | Theo dõi số lượng entry |
| VPN Connections | L2L count, Host2L count | Line | Theo dõi VPN connection count |

## 2. Traffic graph

Ưu tiên Graph từ SNMP-derived rate, không dùng Webhook rate raw cho đến khi xác minh đơn vị. Nếu vẽ counter bytes trực tiếp, biểu đồ phản ánh tổng tích lũy chứ không phải tốc độ.

## 3. CPU/Memory

- CPU key: `draytek.cpu.util`
- Memory key: `draytek.webhook.memory.util`
- Units: %
- Có thể dùng Simple graph trước; sau đó tạo custom graph với hai series.

## 4. Graph WAN

Mỗi WAN nên có RX và TX riêng, thống nhất đơn vị bps hoặc bit/s. Chuyển đổi đơn vị phải được xử lý trong Item/preprocessing, không chỉ đổi nhãn Graph.

## 5. Inventory hiện hữu

Chưa có export Graph từ Host hiện tại trong nguồn thông tin được cung cấp. Các Graph nêu trên là đề xuất thiết kế, chưa phải danh sách Graph đã được tạo.
