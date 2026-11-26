# 08. Dashboard

## 1. Bố cục đề xuất

### Hàng 1 – Device health
- CPU utilization
- Memory utilization
- Uptime
- Latest webhook received / nodata status

### Hàng 2 – WAN status
- WAN1 status và IP
- WAN2 status và IP
- WAN1 sessions
- WAN2 sessions

### Hàng 3 – Traffic
- WAN1 RX/TX graph từ SNMP
- WAN2 RX/TX graph từ SNMP

### Hàng 4 – LAN/VPN
- DHCP client count
- ARP table count
- Site-to-site VPN count
- Host-to-LAN VPN count

### Hàng 5 – Problems
- Problems widget lọc theo Host
- Trigger severity
- Recent events

## 2. Quy tắc hiển thị

- WAN status/IP nên hiển thị dạng Item value hoặc value widget.
- Traffic phải dùng rate đã xác minh đơn vị.
- Không hiển thị raw WAN rate như Mbps nếu chưa xác minh.
- Dashboard nên lọc theo Host group/Customer tag để mở rộng nhiều khách hàng.

## 3. Tags khuyến nghị

- `vendor=draytek`
- `model=vigor2962`
- `customer=<customer-code>`
- `site=<site-code>`
- `service=network`

Giá trị customer/site phải lấy từ CMDB thực tế, không dùng placeholder trong production.
