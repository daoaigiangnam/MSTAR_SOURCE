# 01. Kiến trúc tổng thể

## 1. Thành phần

- DrayTek Vigor2962: thiết bị mạng.
- Ubuntu 24.04.4 LTS / Coolify host: IP LAN `192.168.100.8`, NIC `ens32`, gateway Peplink `192.168.100.254`.
- strongSwan IPsec: selector đã xác nhận `172.21.0.2/32 === 192.168.105.0/24`.
- Zabbix Server container: Zabbix 7.0.30, network namespace IP `172.21.0.2`.
- Webhook Receiver container: Python 3.12.16, port 8080, dùng chung network namespace Zabbix Server.
- Worker container: Python Worker, dùng chung network namespace Zabbix Server.
- SQLite: lưu raw webhook events và device registry.
- Zabbix Server trapper listener: `127.0.0.1:10051` trong network namespace dùng chung.

## 2. Luồng mạng SNMP

Zabbix Server container truy vấn SNMP tới DrayTek:
- DrayTek IP: `192.168.105.1`
- UDP port: `161`
- Đường đi IPsec: từ Zabbix namespace IP `172.21.0.2` tới mạng `192.168.105.0/24`.
- WAN1 ifIndex 4, WAN2 ifIndex 5.

Lưu ý: IP host Ubuntu `192.168.100.8` không nằm trong selector IPsec nêu trên. Kiểm tra SNMP từ đúng network namespace Zabbix, không suy luận từ kết quả trên host.

SNMP community là secret; không lưu giá trị thật trong tài liệu.

## 3. Luồng Webhook

1. DrayTek gửi HTTP POST JSON tới receiver endpoint trên port 8080.
2. Receiver kiểm tra token và source allow-list.
3. Source allow-list hiện gồm `192.168.105.1/32`.
4. Receiver ghi payload nguyên bản vào `/data/events.sqlite3`.
5. Worker đọc event từ cùng file SQLite.
6. Worker lấy `device.MAC`, chuẩn hóa MAC rồi tra bảng `devices`.
7. Thiết bị đã đăng ký/enabled: parse 17 values và gửi Zabbix Sender tới Host technical name đã ánh xạ.
8. MAC không đăng ký phải thành `unmapped`; thiết bị disabled phải thành `disabled`; không được dùng Host fallback.

## 4. Docker deployment

Receiver và Worker dùng:
`network_mode: "container:zabbix_6nta-zabbix-server-1"`

Cả hai mount thư mục host:
`/opt/draytek-webhook/data:/data`

Các file chính:
- Host: `/opt/draytek-webhook/app.py`
- Host: `/opt/draytek-webhook/compose.yaml`
- Host: `/opt/draytek-webhook/Dockerfile`
- Host: `/opt/draytek-webhook/.env` (secret, không commit)
- SQLite: `/opt/draytek-webhook/data/events.sqlite3`
- Worker mới: `/opt/draytek-webhook/data/worker.py`
- Worker cũ backup: `/opt/draytek-webhook/data/worker_old.py`

Docker image dùng Python 3.12 slim, process receiver chạy UID 10001. Container được cấu hình read-only root filesystem, /tmp tmpfs, no-new-privileges và drop capabilities. Worker script nằm trên bind mount /data nên thay đổi file không cần build image; build đã thực hiện trong quá trình nâng cấp nhưng không phải cơ chế copy worker vào image.

## 5. Firewall

Đã thêm UFW route rule cho webhook:
```bash
sudo ufw route allow in on ens32 out on br-7ba30380a931 proto tcp from 192.168.105.1 to 172.21.0.2 port 8080
```

Kiểm tra lại bridge interface sau thay đổi Docker network; không coi tên bridge là bất biến. Chỉ mở đúng source/destination/port cần thiết.

## 6. SQLite data model

Bảng raw event:
```sql
CREATE TABLE webhook_events (
 id INTEGER PRIMARY KEY AUTOINCREMENT,
 received_at INTEGER NOT NULL,
 source_ip TEXT NOT NULL,
 content_type TEXT,
 payload_json TEXT NOT NULL
);
```

Worker bổ sung cột trạng thái queue:
- `processing_status`
- `attempts`
- `next_attempt_at`
- `locked_at`
- `processed_at`
- `last_error`

Bảng device registry:
```sql
CREATE TABLE devices (
 id INTEGER PRIMARY KEY AUTOINCREMENT,
 mac_address TEXT NOT NULL UNIQUE,
 device_name TEXT,
 model_name TEXT,
 zabbix_host TEXT NOT NULL,
 enabled INTEGER NOT NULL DEFAULT 1,
 created_at INTEGER NOT NULL,
 updated_at INTEGER NOT NULL
);
```

Registry hiện có thiết bị:
- MAC chuẩn: `1449bc9135d0`
- Device: DrayTek / Vigor2962
- Host: `PEGA-HCM-151NGUYENDINHCHIEU`
- enabled = 1

## 7. Delivery semantics

Worker dùng SQLite queue/retry. Nếu Zabbix nhận sender packet nhưng Worker chết trước khi mark processed, event có thể gửi lại. Đây là at-least-once, không phải exactly-once.

## 8. Sơ đồ logic

```text
DrayTek 192.168.105.1
  |-- SNMP UDP/161 via IPsec -----------------> Zabbix Server 172.21.0.2
  |
  |-- HTTP POST :8080 ------------------------> Receiver
                                                   |
                                                   v
                                          /data/events.sqlite3
                                                   |
                                                   v
                                              Worker
                                                   |
                                         normalize MAC
                                                   |
                                          devices registry
                                                   |
                                          Zabbix Sender
                                                   |
                                                   v
                                      Zabbix 127.0.0.1:10051
```

## 9. Security

- Webhook token và SNMP community không được commit vào Git.
- Source allow-list webhook giới hạn theo IP DrayTek.
- SNMP ACL chỉ cho phép Zabbix Server.
- Token/community từng xuất hiện trong trao đổi; rotate và cập nhật đồng bộ sau nghiệm thu.
- MAC chưa đăng ký tuyệt đối không được gửi vào Host bất kỳ.
