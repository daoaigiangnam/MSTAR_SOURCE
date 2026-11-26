# 12. Lịch sử triển khai từ đầu đến hiện tại

Tài liệu ghi lại các bước đã thực hiện theo trình tự, phân biệt kết quả đã xác nhận và phần còn tồn tại. Không dùng tài liệu này để khẳng định các hạng mục chưa kiểm thử là đã hoàn thành.

## Giai đoạn 1 – Xác định topology và đường mạng

### Môi trường

| Thành phần | Giá trị |
|---|---|
| Ubuntu/Coolify host | 192.168.100.8 |
| NIC | ens32 |
| Gateway | Peplink 192.168.100.254 |
| DrayTek Vigor2962 | 192.168.105.1 |
| IPsec local selector | 172.21.0.2/32 |
| IPsec remote selector | 192.168.105.0/24 |
| Zabbix namespace IP | 172.21.0.2 |
| Zabbix server version | 7.0.30 |

Đã xác định host Ubuntu 192.168.100.8 không phải địa chỉ nằm trong selector IPsec; Zabbix container IP 172.21.0.2 mới là endpoint nằm trong selector.

### SNMP connectivity

- SNMP truy vấn từ Zabbix network namespace.
- DrayTek management/SNMP IP 192.168.105.1, UDP/161.
- Community phải được lưu bảo mật, không ghi trong repo.
- WAN1 ifIndex = 4; WAN2 ifIndex = 5.
- ifHCInOctets/ifHCOutOctets không được thiết bị hỗ trợ trong lần kiểm tra.
- Sử dụng IF-MIB 32-bit counters ifInOctets/ifOutOctets.

OID:
- WAN1 In: 1.3.6.1.2.1.2.2.1.10.4
- WAN1 Out: 1.3.6.1.2.1.2.2.1.16.4
- WAN2 In: 1.3.6.1.2.1.2.2.1.10.5
- WAN2 Out: 1.3.6.1.2.1.2.2.1.16.5

## Giai đoạn 2 – Khai báo Zabbix Host

Host đã được tạo/cấu hình:
- Technical name: `PEGA-HCM-151NGUYENDINHCHIEU`
- Visible name: `PEGA HCM - 151 Nguyễn Đình Chiểu - DrayTek Vigor2962`
- Host ID trong hệ thống hiện tại: 10687
- Host group: Network devices
- SNMP interface: 192.168.105.1:161
- Host enabled và monitored by Zabbix Server.

Host có 12 Items trước khi bổ sung webhook; danh sách chi tiết các Item cũ cần export từ Zabbix để lập inventory chuẩn, không suy đoán từ số lượng.

## Giai đoạn 3 – Tạo Trapper Items cho Webhook

CPU trapper đã tồn tại:
- Name: DrayTek CPU Utilization
- Key: `draytek.cpu.util`
- Type: Zabbix trapper
- Value type: Numeric unsigned
- Units: %
- Item ID: 51290

Đã tạo thêm 16 Trapper Items, IDs 51291–51306:
- Memory utilization
- Uptime
- WAN1 status/IP/sessions/RX raw/TX raw
- WAN2 status/IP/sessions/RX raw/TX raw
- DHCP count
- ARP count
- L2L VPN count
- Host2L VPN count

Tổng cộng 17 Trapper Items (CPU + 16). Đã xác minh các Item enabled và đúng value type/units trong Host. Không dùng Item ID khi triển khai sang môi trường khác.

## Giai đoạn 4 – Xây dựng Webhook Receiver

Receiver:
- Python 3.12.16.
- Container name: draytek-webhook.
- Chia sẻ network namespace với Zabbix Server.
- HTTP port 8080.
- Endpoint pattern: `/webhook/draytek/<TOKEN>`; token là secret, không lưu vào tài liệu.
- Source allow-list: 192.168.105.1/32.
- Nhận JSON và lưu raw payload vào SQLite.
- app.py không thay đổi trong đợt chuyển Worker Multi-Host.

UFW route rule đã thêm để cho phép source DrayTek tới 172.21.0.2:8080 qua ens32 và Docker bridge.

## Giai đoạn 5 – SQLite event queue và Worker đơn Host

Bảng `webhook_events` ban đầu lưu:
- id, received_at, source_ip, content_type, payload_json.

Worker cũ dùng cấu hình Host cố định qua biến `ZABBIX_HOST`. Worker parse 17 values và gửi vào một Zabbix Host. Đã kiểm tra Zabbix Sender protocol thành công.

Các trạng thái queue được bổ sung:
- pending
- processing
- retry
- processed
- failed

Cơ chế claim event, lock recovery, retry đã được triển khai. Có một Event thử nghiệm cũ bị failed do payload legacy không đúng định dạng; không xóa để giữ lịch sử.

Trước nâng cấp, thống kê đã ghi nhận 153 processed và 1 failed. Backup Worker và SQLite được tạo tại:
`/opt/draytek-webhook/backup/20261126_130801/`

Worker cũ được giữ lại:
`/opt/draytek-webhook/data/worker_old.py`

## Giai đoạn 6 – Nâng cấp Multi-Host

Yêu cầu: Worker hỗ trợ nhiều Zabbix Host trước khi phát triển PHP Dashboard Backend.

Bảng `devices` được tạo để ánh xạ:
MAC → device/model → Zabbix technical host → enabled.

Thiết bị đã đăng ký:
`1449bc9135d0 | DrayTek | Vigor2962 | PEGA-HCM-151NGUYENDINHCHIEU | enabled=1`

Worker mới:
- Chuẩn hóa MAC bằng cách loại ký tự phân cách, chuyển lowercase, yêu cầu 12 ký tự hex.
- Tra registry theo MAC.
- Đã có nhánh dự kiến `unmapped` cho MAC không đăng ký.
- Đã có nhánh `disabled` cho thiết bị bị disable.
- Gắn Zabbix host đích theo registry.
- Giữ parse/send 17 values.
- Giữ SQLite retry/claim/send protocol.

Worker mới được compile bằng Python trên host; Docker worker build thành công; Worker container đã khởi động. Worker log xác nhận Event 183 cho MAC 1449bc9135d0 được gửi tới Host PEGA-HCM-151NGUYENDINHCHIEU với 17 processed, 0 failed. SQLite sau đó ghi nhận 182 processed và 1 failed tại thời điểm kiểm tra. Các webhook thật tiếp tục được xử lý.

## Giai đoạn 7 – Kiểm thử MAC chưa đăng ký: CHƯA ĐẠT

### Event 203

Đã chèn Event test với MAC `aabbccddeeff`, nhưng payload thiếu `Status.Uptime`. Kết quả:
`203|failed|1|Missing Status.Uptime`

Đây là lỗi định dạng test payload; chưa kiểm tra được nhánh unmapped.

### Event 226

Đã chèn payload đủ các trường parser yêu cầu, MAC dạng colon:
`aa:bb:cc:dd:ee:ff`

Kết quả truy vấn:
`226|processed|1|`

Trong khi MAC này không có trong bảng devices. Đây là kết quả sai nghiêm trọng: theo code đang xem, resolve_device() không tìm thấy row phải raise LookupError, process_one() phải gọi mark_unmapped() và return trước send_to_zabbix().

Đã kiểm tra source file trên host:
- normalize_mac() bỏ dấu phân cách và chuẩn hóa lowercase.
- resolve_device() SELECT bảng devices WHERE mac_address = ?; row None thì LookupError.
- process_one() bắt LookupError, gọi mark_unmapped(), log và return.
- parse_payload() lấy MAC và tạo mapping 17 keys.

Do kết quả runtime trái với logic source được xem, nguyên nhân chưa được xác định. Các khả năng cần điều tra, chưa kết luận:
1. Worker container đang chạy code khác với file host.
2. Worker runtime mở database khác với SQLite đang truy vấn trên host.
3. Có process/worker khác cập nhật event.
4. Có đường code khác đánh dấu processed hoặc sender fallback.

**Không onboard thêm thiết bị và không công nhận Multi-Host an toàn cho đến khi xử lý xong.**

## Giai đoạn 8 – Công việc tiếp theo

1. Xác định chính xác Worker container/process đang chạy và command.
2. In giá trị DB_PATH runtime, kiểm tra mount/inode/path DB của receiver và Worker.
3. Đọc `/data/worker.py` trực tiếp bên trong container, đối chiếu checksum với host file.
4. Xác minh query `devices` từ đúng DB runtime không có MAC giả.
5. Rà soát tất cả vị trí gọi send_to_zabbix() và mark_processed().
6. Bổ sung log an toàn: event_id, normalized MAC, DB path, resolved host; không log token/community.
7. Chạy test bằng MAC giả mới; xác minh trạng thái unmapped và không phát sender packet.
8. Kiểm thử MAC có dấu colon/dash/uppercase đều map về cùng thiết bị.
9. Kiểm thử disabled device và host mapping sai.
10. Bổ sung requeue an toàn cho unmapped sau khi đăng ký thiết bị.
11. Chỉ sau đó mới tạo Template dùng chung và onboard thêm thiết bị.

## Bằng chứng còn cần thu thập

- Export Host Zabbix (XML) gồm Items, Triggers, Graphs.
- Full SNMP Item inventory hiện hữu.
- Screenshot/export các Trigger và Graph hiện tại.
- Worker container runtime source/checksum và DB path.
- Kết quả kiểm thử unmapped đạt.
- Xác minh đơn vị WAN webhook rate trước khi đổi từ raw sang bps/Mbps.
