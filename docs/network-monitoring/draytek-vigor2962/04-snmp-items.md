# 04. SNMP Items

## 1. Interface index đã xác nhận

- WAN1 ifIndex = `4`
- WAN2 ifIndex = `5`

## 2. OID interface counters đã xác nhận

| Chỉ số | OID WAN1 | OID WAN2 | Mô tả |
|---|---|---|---|
| ifInOctets | `1.3.6.1.2.1.2.2.1.10.4` | `1.3.6.1.2.1.2.2.1.10.5` | Octets nhận |
| ifOutOctets | `1.3.6.1.2.1.2.2.1.16.4` | `1.3.6.1.2.1.2.2.1.16.5` | Octets gửi |

IF-MIB ifInOctets/ifOutOctets là counter 32-bit. ifHCInOctets/ifHCOutOctets đã thử nhưng thiết bị không hỗ trợ trong môi trường hiện tại. Counter 32-bit có nguy cơ wrap khi interface tốc độ cao; cần đánh giá chu kỳ polling và lưu ý khi diễn giải lưu lượng.

## 3. Khuyến nghị tạo Item

| Name | Key gợi ý | Type | SNMP OID | Value type | Units |
|---|---|---|---|---|---|
| WAN1 In Octets | `draytek.wan1.in.octets` | SNMP agent | ifInOctets.4 | Numeric unsigned | B |
| WAN1 Out Octets | `draytek.wan1.out.octets` | SNMP agent | ifOutOctets.4 | Numeric unsigned | B |
| WAN2 In Octets | `draytek.wan2.in.octets` | SNMP agent | ifInOctets.5 | Numeric unsigned | B |
| WAN2 Out Octets | `draytek.wan2.out.octets` | SNMP agent | ifOutOctets.5 | Numeric unsigned | B |

Các key ở bảng này là key gợi ý cho triển khai mới, không khẳng định trùng với key đang có trên Host hiện hữu.

## 4. Tính tốc độ

Tốc độ có thể tính từ counter bằng preprocessing/Calculated Item theo delta bytes và thời gian thực tế:
- bps = delta(bytes) × 8 / delta(seconds)
- Kiểm tra counter reset/wrap và discard giá trị âm/bất thường.
- Với Zabbix, ưu tiên preprocessing Change per second và nhân 8 nếu muốn bit/s; đơn vị hiển thị phải thống nhất.

## 5. Inventory còn cần export

Hội thoại xác nhận Host hiện có 12 Items trước khi bổ sung, nhưng không có đầy đủ key/OID/name của các Item đó. Vì vậy không thể ghi chính xác toàn bộ SNMP Item hiện hữu vào tài liệu này mà không export cấu hình từ Zabbix.

Nghiệm thu tài liệu cuối cùng cần export Host/Template từ Zabbix và đối chiếu:
- Name, key, type, SNMP OID
- Value type, units, update interval
- Preprocessing, history/trends
- Status và interface binding
