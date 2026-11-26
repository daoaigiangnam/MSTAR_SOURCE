# Quy trình triển khai chi tiết DrayTek Vigor2962 → Zabbix 7.0

> **Phạm vi:** Tài liệu này mô tả trình tự triển khai thực tế đã được ghi nhận trong dự án, từ kiểm tra đường mạng đến SNMP, Zabbix, Webhook, Docker, SQLite và Worker Multi-Host. Các giá trị được chia thành:
> - **Đã xác nhận:** có kết quả/lệnh/log được ghi nhận.
> - **Cần đối chiếu ảnh:** thao tác giao diện từng được thực hiện nhưng ảnh gốc hiện không có trong bộ tư liệu có thể truy xuất.
> - **Chưa đạt:** đã kiểm thử nhưng kết quả không đúng mong đợi.
>
> Không sử dụng tài liệu này để suy diễn rằng hạng mục “cần đối chiếu ảnh” hoặc “chưa đạt” đã nghiệm thu.

## 0. Thông tin môi trường và quy ước

| Thành phần | Giá trị |
|---|---|
| Router cần giám sát | DrayTek Vigor2962 |
| Firmware | 4.4.3.5 – build Jun 2 2025 22:40:33 |
| IP LAN/SNMP DrayTek | 192.168.105.1 |
| Ubuntu/Coolify host | Ubuntu 24.04.4 LTS – 192.168.100.8 |
| NIC Ubuntu | ens32 |
| Gateway Ubuntu | Peplink 192.168.100.254 |
| Zabbix Server | 7.0.30, Docker container zabbix_6nta-zabbix-server-1 |
| IP trong network namespace Zabbix | 172.21.0.2 |
| IPsec selectors | 172.21.0.2/32 ↔ 192.168.105.0/24 |
| Zabbix API | https://monitoring.review360.id.vn/api_jsonrpc.php |
| Zabbix Host technical name | PEGA-HCM-151NGUYENDINHCHIEU |
| Zabbix visible name | PEGA HCM - 151 Nguyễn Đình Chiểu - DrayTek Vigor2962 |
| Host ID tại thời điểm triển khai | 10687 |
| Host group | Network devices |
| Webhook Receiver | draytek-webhook, Python 3.12.16, TCP/8080 |
| SQLite | /opt/draytek-webhook/data/events.sqlite3 |

**Nguyên tắc an toàn**
- Không đưa webhook token hoặc SNMP community vào Git, ảnh chụp công khai hay nội dung gửi hỗ trợ.
- Khi chụp ảnh cấu hình, che token, community, mật khẩu và thông tin nhạy cảm.
- Không thay đổi SNMP Items/Triggers/Graphs cũ khi chưa export và đối chiếu.
- SNMP là nguồn chính cho interface counters; Webhook là nguồn bổ sung.
- Chưa triển khai thêm thiết bị cho đến khi kiểm thử MAC không đăng ký đạt yêu cầu.

---

## 1. Khảo sát topology và xác định đường đi

### Mục tiêu
Xác định chính xác địa chỉ nguồn mà Zabbix dùng để truy cập DrayTek qua IPsec. Không dùng IP host Ubuntu để kết luận thay cho IP container.

### Thông tin đã xác nhận
- Ubuntu host: 192.168.100.8, NIC ens32, gateway 192.168.100.254.
- Zabbix Server container dùng network namespace có IP 172.21.0.2.
- IPsec selector local: 172.21.0.2/32.
- IPsec selector remote: 192.168.105.0/24.
- DrayTek: 192.168.105.1.

### Kết luận kỹ thuật
Địa chỉ 172.21.0.2 nằm trong selector IPsec; địa chỉ host 192.168.100.8 không nằm trong selector đã ghi nhận. Vì vậy các phép thử SNMP phải chạy từ network namespace Zabbix.

### Kiểm tra Docker và địa chỉ namespace
Các lệnh kiểm tra có thể dùng trên Ubuntu host:

```bash
docker ps --filter name=zabbix_6nta-zabbix-server-1
docker inspect zabbix_6nta-zabbix-server-1
docker exec zabbix_6nta-zabbix-server-1 sh -lc 'ip addr'
```

Nếu image không có lệnh `ip`, kiểm tra bằng công cụ mạng đã cài trong container hoặc dùng network namespace của container từ host.

**Bằng chứng cần gắn:** ảnh topology/IPsec selector; ảnh địa chỉ IP container; kết quả xác nhận 172.21.0.2.

---

## 2. Kiểm tra SNMP từ đúng network namespace

### Mục tiêu
Xác nhận Zabbix Server có thể truy vấn SNMP tới DrayTek trên UDP/161.

### Thông số
- Target: 192.168.105.1
- Port: UDP/161
- Community: secret, không ghi giá trị vào tài liệu.
- Nguồn kiểm tra: network namespace Zabbix (172.21.0.2).

### Trình tự kiểm tra
1. Vào đúng Zabbix Server container hoặc chạy công cụ trong cùng network namespace.
2. Kiểm tra SNMP system information.
3. Kiểm tra bảng IF-MIB và xác định ifIndex WAN.
4. Không kết luận SNMP lỗi chỉ dựa trên phép thử từ host Ubuntu.

Ví dụ lệnh (thay COMMUNITY bằng biến/secret tại máy, không ghi community thật vào lịch sử shell hoặc ảnh):

```bash
snmpwalk -v2c -c "$SNMP_COMMUNITY" 192.168.105.1 1.3.6.1.2.1.1
snmpwalk -v2c -c "$SNMP_COMMUNITY" 192.168.105.1 1.3.6.1.2.1.2.2.1.2
```

### Kết quả đã xác nhận
- WAN1 ifIndex = 4.
- WAN2 ifIndex = 5.
- Thiết bị không hỗ trợ ifHCInOctets/ifHCOutOctets trong lần kiểm tra.
- Sử dụng IF-MIB 32-bit counters.

### OID interface counters

| Chỉ số | WAN1 (ifIndex 4) | WAN2 (ifIndex 5) |
|---|---|---|
| In Octets | 1.3.6.1.2.1.2.2.1.10.4 | 1.3.6.1.2.1.2.2.1.10.5 |
| Out Octets | 1.3.6.1.2.1.2.2.1.16.4 | 1.3.6.1.2.1.2.2.1.16.5 |

**Lưu ý:** IF-MIB 32-bit counter có thể wrap khi lưu lượng lớn. Cần theo dõi delta và chu kỳ polling; không tự đổi sang 64-bit OID khi thiết bị chưa hỗ trợ.

**Bằng chứng cần gắn:** ảnh SNMP walk system, ifTable, kết quả ifIndex 4/5 và phép thử ifHC thất bại.

---

## 3. Tạo và cấu hình Host trên Zabbix

### Mục tiêu
Tạo đối tượng giám sát DrayTek và gắn SNMP interface.

### Thông tin đã xác nhận
- Technical name: `PEGA-HCM-151NGUYENDINHCHIEU`
- Visible name: `PEGA HCM - 151 Nguyễn Đình Chiểu - DrayTek Vigor2962`
- Host ID: `10687` tại thời điểm ghi nhận.
- Group: `Network devices`
- SNMP interface: `192.168.105.1:161`
- Host đã enabled và monitored by Zabbix Server.
- Trước khi thêm webhook có 12 Items hiện hữu.

### Trình tự giao diện cần đối chiếu
1. Vào Data collection → Hosts (tên menu có thể khác nhẹ theo giao diện Zabbix 7.0).
2. Mở Host DrayTek.
3. Kiểm tra Host name và Visible name.
4. Kiểm tra Host groups.
5. Kiểm tra SNMP interface, IP 192.168.105.1, port 161.
6. Kiểm tra trạng thái Enabled và Monitored by Zabbix Server.
7. Không xóa hoặc sửa các Items hiện hữu.

### Inventory
Chưa có export XML/JSON đầy đủ của 12 Items ban đầu trong tài liệu. Cần export Host hoặc ghi lại toàn bộ Items trước khi chỉnh sửa tiếp.

**Bằng chứng cần gắn:** ảnh Host configuration (General), Host groups, Interfaces, Items ban đầu.

---

## 4. Xác định SNMP interface index và OID cho WAN

### Mục tiêu
Gắn đúng counters WAN1/WAN2 vào các interface thực tế.

### Kết quả đã xác nhận
- WAN1 = ifIndex 4.
- WAN2 = ifIndex 5.
- ifHC counters không được hỗ trợ.
- OID sử dụng:
  - WAN1 RX: `1.3.6.1.2.1.2.2.1.10.4`
  - WAN1 TX: `1.3.6.1.2.1.2.2.1.16.4`
  - WAN2 RX: `1.3.6.1.2.1.2.2.1.10.5`
  - WAN2 TX: `1.3.6.1.2.1.2.2.1.16.5`

### Lưu ý cấu hình
Không tự tạo lại hoặc đổi key các SNMP Items cũ nếu chưa có export. Tài liệu hiện chỉ ghi nhận OID và ifIndex, chưa có đầy đủ key, polling interval, preprocessing và history/trends của từng Item cũ.

**Bằng chứng cần gắn:** ảnh Item SNMP WAN1/WAN2 và phần SNMP walk đối chiếu OID.

---

## 5. Tạo Trapper Item cho CPU

### Mục tiêu
Nhận CPU utilization từ webhook, độc lập với SNMP Items hiện hữu.

### Item đã xác nhận
| Thuộc tính | Giá trị |
|---|---|
| Name | DrayTek CPU Utilization |
| Key | `draytek.cpu.util` |
| Type | Zabbix trapper |
| Type of information | Numeric unsigned |
| Units | % |
| Item ID | 51290 |

### Kiểm tra
- Item tồn tại trên đúng Host.
- Key viết đúng chính tả.
- Type là Zabbix trapper.
- Value type Numeric unsigned.
- Units %.
- Enabled.

**Bằng chứng cần gắn:** ảnh cấu hình Item CPU và ảnh Latest data sau khi nhận dữ liệu.

---

## 6. Tạo 16 Trapper Items bổ sung

### Mục tiêu
Tạo đủ các key để Worker gửi dữ liệu Webhook vào Zabbix.

### Danh sách 16 Items đã xác nhận

| Key | Value type | Units |
|---|---|---|
| `draytek.webhook.memory.util` | Numeric unsigned | % |
| `draytek.webhook.uptime` | Numeric unsigned | s (sau đó người dùng đổi Units hiển thị thành uptime) |
| `draytek.webhook.wan1.status` | Character | — |
| `draytek.webhook.wan1.ip` | Character | — |
| `draytek.webhook.wan1.sessions` | Numeric unsigned | — |
| `draytek.webhook.wan1.rx.rate.raw` | Numeric unsigned | raw |
| `draytek.webhook.wan1.tx.rate.raw` | Numeric unsigned | raw |
| `draytek.webhook.wan2.status` | Character | — |
| `draytek.webhook.wan2.ip` | Character | — |
| `draytek.webhook.wan2.sessions` | Numeric unsigned | — |
| `draytek.webhook.wan2.rx.rate.raw` | Numeric unsigned | raw |
| `draytek.webhook.wan2.tx.rate.raw` | Numeric unsigned | raw |
| `draytek.webhook.lan.dhcp.count` | Numeric unsigned | — |
| `draytek.webhook.lan.arp.count` | Numeric unsigned | — |
| `draytek.webhook.vpn.l2l.count` | Numeric unsigned | — |
| `draytek.webhook.vpn.host2l.count` | Numeric unsigned | — |

IDs được ghi nhận trong Host hiện tại: 51291–51306.

### Quy tắc tạo Item
1. Chọn đúng Host DrayTek.
2. Tạo Item mới.
3. Nhập Name dễ hiểu, duy nhất.
4. Nhập Key đúng từng ký tự như bảng.
5. Chọn Type = Zabbix trapper.
6. Chọn Type of information theo bảng.
7. Nhập Units theo bảng.
8. Lưu và kiểm tra Enabled.
9. Lặp lại cho 16 key.
10. Đối chiếu tổng 17 Trapper Items bao gồm CPU.

### Kiểm chứng đã thực hiện
API kiểm tra xác nhận 17 Trapper Items, tất cả enabled, đúng value type/units theo danh sách. Tổng số Items trên Host là 28; các Items SNMP có trước được giữ nguyên.

**Bằng chứng cần gắn:** ảnh danh sách Items sau khi tạo; ảnh chi tiết các nhóm Item; kết quả API export/list.

---

## 7. Cấu hình DrayTek gửi Webhook

### Mục tiêu
Đẩy các trạng thái thiết bị từ Vigor2962 sang Receiver.

### Endpoint
Mẫu endpoint:
`http://172.21.0.2:8080/webhook/draytek/<TOKEN>`

Token là secret; không đưa token thật vào ảnh hoặc tài liệu.

### Source allow-list
`192.168.105.1/32`

### Các nhóm webhook đã bật
1. WAN Rx/Tx
2. WAN Rx/Tx Total
3. WAN Current Session
4. WAN ARP Table
5. DHCP Table
6. VPN Connections

### Payload thực tế đã nhận
Payload có các nhóm:
- device: Model Name, Device Name, MAC.
- Status: Uptime, Memory, CPU.
- WAN1/WAN2: status, IP, tx/rx, packet counters, rate, sessions.
- DHCP_total, DHCP_LAN1.
- ARP_total, ARP_LAN1.
- L2LVPN_counts, Host2LVPN_counts.

Ví dụ cấu trúc rút gọn (đã bỏ dữ liệu nhạy cảm):

```json
{
  "device": {
    "Model Name": "Vigor2962",
    "Device Name": "DrayTek",
    "MAC": "1449bc9135d0"
  },
  "Status": {
    "Uptime": "2994767",
    "Memory": "77",
    "CPU": "56",
    "WAN1_status": "UP",
    "WAN1_IP": "113.161.92.1",
    "WAN1_sessions": "1461",
    "WAN2_status": "UP",
    "WAN2_IP": "14.161.4.84",
    "WAN2_sessions": "2224",
    "DHCP_total": "162",
    "ARP_total": "165",
    "L2LVPN_counts": "1",
    "Host2LVPN_counts": "0"
  }
}
```

### Lưu ý dữ liệu
- WAN rate hiện giữ dạng raw; đơn vị chưa được xác minh, không tự ghi Mbps.
- Packet counter có giá trị âm trong payload thực tế; không dùng các trường này cho Item Numeric unsigned.
- DHCP/ARP/VPN hiện mới có count; chưa có danh sách chi tiết thiết bị trong Status payload đã ghi nhận.

**Bằng chứng cần gắn:** ảnh từng nhóm Webhook DrayTek, URL đã che token, danh sách event bật, payload JSON đã che IP/MAC nếu cần.

---

## 8. Tạo Ubuntu Webhook Receiver

### Mục tiêu
Nhận HTTP POST từ DrayTek, xác thực request và lưu raw JSON vào SQLite.

### Thư mục triển khai
`/opt/draytek-webhook/`

Các file:
- `app.py`
- `compose.yaml`
- `Dockerfile`
- `.env` (secret, không commit)
- `data/events.sqlite3`

### Dockerfile đã ghi nhận

```dockerfile
FROM python:3.12-slim
ENV PYTHONDONTWRITEBYTECODE=1 PYTHONUNBUFFERED=1
WORKDIR /app
COPY app.py /app/app.py
RUN useradd --system --uid 10001 --home-dir /nonexistent --shell /usr/sbin/nologin receiver \
    && mkdir -p /data && chown -R receiver:receiver /app /data
USER receiver
EXPOSE 8080
CMD ["python", "/app/app.py"]
```

### Cấu hình container đã ghi nhận
- Container: `draytek-webhook`.
- Python 3.12.16.
- Chia sẻ network namespace với Zabbix Server:
  `network_mode: "container:zabbix_6nta-zabbix-server-1"`.
- HTTP listen port 8080.
- Source allow-list 192.168.105.1/32.
- Root filesystem read-only.
- `/tmp` dùng tmpfs.
- `no-new-privileges:true`.
- Drop toàn bộ Linux capabilities.
- Data directory mount vào `/data`.

### Firewall rule đã thêm

```bash
sudo ufw route allow in on ens32 out on br-7ba30380a931 proto tcp from 192.168.105.1 to 172.21.0.2 port 8080
```

Tên Docker bridge có thể thay đổi theo môi trường; kiểm tra bridge thực tế trước khi áp dụng lại trên máy khác.

### Xác minh
- DrayTek gửi HTTP POST.
- Receiver trả HTTP 200.
- Payload được lưu vào SQLite.
- Không công khai token trong log hoặc ảnh.

**Bằng chứng cần gắn:** ảnh thư mục triển khai, Dockerfile, Compose (che secret), UFW status/rule, container status, log nhận webhook.

---

## 9. Thiết kế SQLite lưu sự kiện

### Bảng webhook_events ban đầu

```sql
CREATE TABLE webhook_events (
 id INTEGER PRIMARY KEY AUTOINCREMENT,
 received_at INTEGER NOT NULL,
 source_ip TEXT NOT NULL,
 content_type TEXT,
 payload_json TEXT NOT NULL
);
```

### Các cột queue được bổ sung
- `processing_status`
- `attempts`
- `next_attempt_at`
- `locked_at`
- `processed_at`
- `last_error`

### Trạng thái xử lý
- pending
- processing
- retry
- processed
- failed

Trong Worker Multi-Host có thêm trạng thái dự kiến/được xử lý:
- unmapped: MAC chưa đăng ký.
- disabled: thiết bị bị vô hiệu hóa.

### Kiểm tra dữ liệu
Lệnh kiểm tra chỉ dùng khi đã xác định đúng DB path:

```bash
sqlite3 /opt/draytek-webhook/data/events.sqlite3 \
  "SELECT processing_status, COUNT(*) FROM webhook_events GROUP BY processing_status;"
```

**Bằng chứng cần gắn:** schema SQLite, kết quả đếm trạng thái, một payload mẫu đã ẩn thông tin nhạy cảm.

---

## 10. Worker đơn Host – phiên bản đầu

### Mục tiêu
Đọc các webhook event từ SQLite, parse dữ liệu và gửi 17 values vào Zabbix.

### Thiết kế đã ghi nhận
- Worker dùng Host cố định qua `ZABBIX_HOST`.
- Zabbix trapper listener: 127.0.0.1:10051 trong network namespace dùng chung.
- Gửi values theo Zabbix Sender protocol.
- Có claim event, retry và lock recovery.
- Worker cũ được giữ tại `/opt/draytek-webhook/data/worker_old.py`.

### Kết quả trước khi nâng cấp
- 153 events processed.
- 1 event failed.
- Event failed là test payload legacy không đúng định dạng; được giữ để lưu lịch sử.
- Zabbix Sender CLI test thành công.

### Backup trước khi nâng cấp
`/opt/draytek-webhook/backup/20261126_130801/`
- worker.py
- events.sqlite3

**Bằng chứng cần gắn:** ảnh backup, log Worker, kết quả SQLite counts, Latest data Zabbix.

---

## 11. Chuyển Worker sang Multi-Host

### Lý do
Không muốn Worker bị khóa vào một Zabbix Host cố định; cần hỗ trợ nhiều thiết bị trước khi phát triển PHP Dashboard Backend.

### Bảng devices

```sql
CREATE TABLE IF NOT EXISTS devices (
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

### Registry hiện có
`1449bc9135d0 | DrayTek | Vigor2962 | PEGA-HCM-151NGUYENDINHCHIEU | enabled=1`

### Quy tắc Worker mới
1. Đọc payload.
2. Lấy `device.MAC`.
3. Chuẩn hóa MAC: bỏ dấu phân cách, chuyển lowercase, yêu cầu đúng 12 ký tự hex.
4. Tra bảng `devices`.
5. Nếu không có MAC: phải đánh dấu unmapped và dừng, tuyệt đối không gửi Zabbix.
6. Nếu thiết bị disabled: đánh dấu disabled và dừng.
7. Nếu đã đăng ký/enabled: lấy `zabbix_host` tương ứng.
8. Parse 17 values.
9. Gửi Zabbix Sender.
10. Chỉ đánh dấu processed khi gửi thành công.

### Cấu hình Worker
- File: `/opt/draytek-webhook/data/worker.py`
- Container: `draytek-worker`
- DB path: `/data/events.sqlite3`
- Zabbix: `127.0.0.1:10051`
- Worker script nằm trong bind mount `./data:/data`; sửa script không cần build lại image để copy file, dù quá trình nâng cấp đã thực hiện build worker image.
- Worker dùng chung network namespace với Zabbix Server.

### Lệnh đã thực hiện
Compile:

```bash
python3 -m py_compile /opt/draytek-webhook/data/worker.py
```

Build:

```bash
cd /opt/draytek-webhook
docker compose build draytek-worker
```

Khởi động:

```bash
docker compose up -d draytek-worker
```

Xem log:

```bash
docker logs --tail 100 draytek-worker
```

### Kết quả đã xác nhận
Worker log ghi nhận Event 183:
- MAC 1449bc9135d0.
- Host PEGA-HCM-151NGUYENDINHCHIEU.
- processed: 17.
- failed: 0.
- total: 17.

Các event thật sau đó tiếp tục được xử lý thành công.

**Bằng chứng cần gắn:** ảnh source Worker, py_compile, build output, container status, log Event 183 và các event thật tiếp theo.

---

## 12. Kiểm thử MAC chưa đăng ký – lỗi chưa xử lý

### Mục tiêu
Chứng minh một MAC không có trong bảng devices không thể gửi dữ liệu vào bất kỳ Zabbix Host nào.

### Test Event 203
- Payload dùng MAC giả nhưng thiếu `Status.Uptime`.
- Kết quả: `203|failed|1|Missing Status.Uptime`.
- Kết luận: payload test sai định dạng; chưa đi tới kiểm tra mapping MAC.

### Test Event 226
- Payload đủ trường parser yêu cầu.
- MAC: `aa:bb:cc:dd:ee:ff`.
- MAC không có trong registry.
- Kết quả thực tế: `226|processed|1|`.
- Kết quả mong đợi: `unmapped`.

### Vì sao đây là lỗi nghiêm trọng
Code trên host được kiểm tra có logic:
- resolve_device() không tìm thấy row thì raise LookupError.
- process_one() bắt LookupError, gọi mark_unmapped() rồi return.
- Vì vậy Event 226 không được phép thành processed theo logic này.

### Chưa được kết luận nguyên nhân
Các giả thuyết cần kiểm tra:
1. Worker container đang chạy file source khác file host.
2. Worker đang mở SQLite database khác.
3. Có Worker/process khác cùng xử lý event.
4. Có đường code khác gọi mark_processed() hoặc send_to_zabbix().

### Các lệnh chẩn đoán tiếp theo

Kiểm tra container và command:

```bash
docker ps --filter name=draytek-worker
docker inspect draytek-worker --format '{{.Config.Cmd}}'
```

So sánh checksum file host và file trong container:

```bash
sha256sum /opt/draytek-webhook/data/worker.py
docker exec draytek-worker sha256sum /data/worker.py
```

Kiểm tra database path và file trong container:

```bash
docker exec draytek-worker sh -lc 'echo "DB_PATH=$DB_PATH"; ls -l /data/worker.py /data/events.sqlite3'
```

Kiểm tra registry và Event 226 ngay trong cùng container:

```bash
docker exec draytek-worker python - <<'PY'
import os, sqlite3
p = os.environ.get("DB_PATH", "/data/events.sqlite3")
db = sqlite3.connect(p)
print("DB_PATH:", p)
print("devices:", db.execute("SELECT mac_address, zabbix_host, enabled FROM devices").fetchall())
print("event226:", db.execute("SELECT id, processing_status, attempts, last_error FROM webhook_events WHERE id=226").fetchall())
PY
```

Các lệnh trên là bước điều tra đề xuất tiếp theo; chưa có kết quả thực thi được ghi nhận. Trước khi sửa code hoặc khởi động lại Worker, lưu lại kết quả hiện tại.

**Trạng thái:** CHƯA ĐẠT. Không onboarding thiết bị thứ hai, không công nhận Multi-Host an toàn.

---

## 13. Những phần chưa triển khai hoặc chưa đủ bằng chứng

| Hạng mục | Trạng thái |
|---|---|
| SNMP connectivity qua IPsec | Đã xác nhận |
| WAN ifIndex/OID | Đã xác nhận |
| Zabbix Host | Đã xác nhận |
| 17 Trapper Items | Đã xác nhận |
| Receiver nhận webhook và lưu SQLite | Đã xác nhận |
| Worker đơn Host | Đã chạy |
| Worker Multi-Host xử lý MAC đã đăng ký | Đã chạy với thiết bị hiện tại |
| MAC không đăng ký phải unmapped | Chưa đạt |
| Thiết bị disabled phải disabled | Chưa nghiệm thu |
| Requeue unmapped sau khi đăng ký | Chưa triển khai/xác nhận |
| Đơn vị WAN rate | Chưa xác minh |
| Export đầy đủ SNMP Items cũ | Chưa có |
| Trigger/Graph hiện hữu | Chưa có inventory đầy đủ |
| Dashboard PHP Backend | Chưa triển khai trong phạm vi tài liệu này |
| Template triển khai hàng loạt | Chưa nghiệm thu |

---

## 14. Danh mục ảnh chụp màn hình cần đưa vào hồ sơ

Ảnh gốc từ các lượt trao đổi trước chưa có đầy đủ trong nguồn tư liệu hiện truy xuất được. Danh mục dưới đây là vị trí cần bổ sung ảnh thật, không phải ảnh được tái tạo.

| Mã ảnh | Nội dung ảnh cần có | Gắn tại bước |
|---|---|---|
| IMG-01 | Topology mạng, IPsec selectors | 1 |
| IMG-02 | Zabbix container/network namespace IP | 1 |
| IMG-03 | SNMP system walk thành công | 2 |
| IMG-04 | IF-MIB xác định WAN1 ifIndex 4, WAN2 ifIndex 5 | 2–4 |
| IMG-05 | Kết quả ifHC OID không hỗ trợ | 2 |
| IMG-06 | Zabbix Host General/Host groups | 3 |
| IMG-07 | Zabbix SNMP interface 192.168.105.1:161 | 3 |
| IMG-08 | Danh sách 12 Items có sẵn trước khi bổ sung | 3 |
| IMG-09 | Item CPU trapper | 5 |
| IMG-10 | Danh sách 16 Trapper Items bổ sung | 6 |
| IMG-11 | DrayTek Webhook event configuration | 7 |
| IMG-12 | Webhook URL đã che token | 7 |
| IMG-13 | Payload JSON mẫu đã ẩn thông tin nhạy cảm | 7 |
| IMG-14 | Dockerfile và compose.yaml đã che secret | 8 |
| IMG-15 | UFW route rule | 8 |
| IMG-16 | Receiver container và log nhận request | 8 |
| IMG-17 | SQLite schema và event counts | 9 |
| IMG-18 | Backup Worker/SQLite | 10 |
| IMG-19 | Worker đơn Host log | 10 |
| IMG-20 | devices registry | 11 |
| IMG-21 | Worker Multi-Host source/compile/build | 11 |
| IMG-22 | Event 183 gửi 17 values thành công | 11 |
| IMG-23 | Event 203 failed do thiếu Uptime | 12 |
| IMG-24 | Event 226 processed sai kỳ vọng | 12 |
| IMG-25 | Kết quả checksum/runtime DB sau điều tra | 12, bổ sung sau |

## 15. Tiêu chí nghiệm thu trước khi phát triển Dashboard

1. Xác nhận Worker container chạy đúng source code và đúng SQLite database.
2. MAC chưa đăng ký phải thành unmapped.
3. Xác minh event unmapped không tạo bất kỳ sender packet nào tới Zabbix.
4. MAC có dấu colon, dash, chữ hoa phải chuẩn hóa đúng.
5. Thiết bị disabled phải thành disabled và không gửi dữ liệu.
6. MAC đã đăng ký phải gửi đủ 17 values đúng Zabbix Host mapping.
7. Kiểm tra retry/lock recovery khi Zabbix Sender tạm thời lỗi.
8. Xác minh đơn vị WAN rate trước khi chuyển đổi hoặc đặt đơn vị hiển thị.
9. Export đầy đủ Host, Items, Triggers, Graphs trước khi thiết kế Template.
10. Chỉ sau khi các kiểm thử trên đạt mới onboarding thêm thiết bị và phát triển PHP Dashboard Backend.

---

## 16. Nhật ký cập nhật tài liệu

Khi tiếp tục triển khai, mỗi lần cập nhật cần ghi:
- Ngày giờ thực hiện.
- Người thực hiện.
- Bước đang làm.
- Thay đổi cấu hình/lệnh đã chạy.
- Kết quả trước và sau.
- Mã ảnh minh chứng.
- Trạng thái: đạt / không đạt / chờ xác minh.

Không ghi đè kết quả lỗi cũ; bổ sung lần kiểm thử mới để giữ nguyên lịch sử kỹ thuật.
