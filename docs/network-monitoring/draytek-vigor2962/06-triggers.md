# 06. Trigger catalogue

## 1. Nguyên tắc

Các Trigger dưới đây là **đề xuất**, không khẳng định đã được tạo trên Host. Cần kiểm tra key thực tế, chu kỳ nhận webhook và ngưỡng vận hành trước khi import.

Zabbix 7.0 expression examples assume item keys in section 05.

## 2. Trigger đề xuất

| Tên | Severity | Điều kiện gợi ý | Ghi chú |
|---|---|---|---|
| WAN1 Down | High | `last(/HOST/draytek.webhook.wan1.status)<>"UP"` | Dùng sau khi xác minh chuỗi status |
| WAN2 Down | Warning | `last(/HOST/draytek.webhook.wan2.status)<>"UP"` | Điều chỉnh theo WAN dự phòng |
| CPU high | Warning | `min(/HOST/draytek.cpu.util,5m)>85` | Ngưỡng khởi điểm, cần hiệu chỉnh |
| CPU critical | High | `min(/HOST/draytek.cpu.util,5m)>95` | Ngưỡng khởi điểm |
| Memory high | Warning | `min(/HOST/draytek.webhook.memory.util,5m)>90` | Ngưỡng khởi điểm |
| No webhook data | Average | `nodata(/HOST/draytek.cpu.util,5m)=1` | Chỉ dùng nếu webhook dự kiến mỗi phút và có giám sát receiver |
| WAN1 sessions high | Warning | `last(/HOST/draytek.webhook.wan1.sessions)>N` | Thay N theo capacity thực tế |
| WAN2 sessions high | Warning | `last(/HOST/draytek.webhook.wan2.sessions)>N` | Thay N theo capacity thực tế |

Thay `HOST` bằng technical host name trong expression khi tạo Trigger trên Host. Với Trigger prototype/template, dùng cú pháp template tương ứng.

## 3. Recovery và chống flapping

- Dùng recovery expression/hysteresis khi giá trị dao động gần ngưỡng.
- Với WAN status, cân nhắc yêu cầu nhiều lần liên tiếp hoặc thời gian duy trì trước khi cảnh báo.
- Không cảnh báo WAN2 Down như sự cố nghiêm trọng nếu WAN2 là đường dự phòng không được yêu cầu luôn UP.
- Trigger nodata cần tính đến thời gian polling/webhook, thời gian queue và bảo trì.

## 4. SNMP availability

Nếu dùng Trigger SNMP unavailable, cần xác định Item/host availability key hoặc cơ chế internal event đúng theo Zabbix version. Không tự tạo expression dựa trên key chưa tồn tại.

## 5. Chưa xác minh

Ngưỡng CPU/Memory/session ở trên là baseline tham khảo, không phải ngưỡng vendor hoặc SLA. Xác nhận với dữ liệu vận hành trước khi bật notification.
