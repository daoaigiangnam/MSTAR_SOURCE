# 03. Khai báo Host trên Zabbix 7.0

## 1. Host hiện hữu đã xác nhận

| Trường | Giá trị |
|---|---|
| Host technical name | `PEGA-HCM-151NGUYENDINHCHIEU` |
| Visible name | `PEGA HCM - 151 Nguyễn Đình Chiểu - DrayTek Vigor2962` |
| Host ID tại môi trường hiện tại | `10687` |
| Host group | `Network devices` |
| SNMP interface | `192.168.105.1:161` |
| Monitored by | Zabbix Server |

Host ID chỉ có ý nghĩa trong môi trường đã triển khai; không hard-code ID này khi import sang Zabbix khác.

## 2. Khai báo Host mới

Trong Data collection → Hosts → Create host:
1. Host name: dùng technical name ổn định, duy nhất, không dấu cách.
2. Visible name: tên dễ đọc, gồm khách hàng/địa điểm/model.
3. Host groups: chọn `Network devices` hoặc group theo chuẩn vận hành.
4. Interfaces: thêm SNMP interface, IP `192.168.105.1`, port `161`.
5. SNMP version/community: khớp cấu hình thiết bị; lưu community an toàn.
6. Monitored by: chọn Zabbix Server/Proxy đúng topology.
7. Add.

## 3. Webhook Trapper Items

Các Trapper Items nhận giá trị qua Zabbix Sender không yêu cầu SNMP interface để nhận dữ liệu, nhưng có thể cùng tồn tại trên Host có SNMP interface.

Đảm bảo technical host name gửi từ Worker khớp tuyệt đối với Host name trong Zabbix, không phải Visible name.

## 4. Không phá cấu hình hiện hữu

- Không xóa SNMP Items đang chạy.
- Không đổi key của Item hiện hữu nếu chưa rà soát Trigger/Graph phụ thuộc.
- Không tạo Item trùng key.
- Export cấu hình Host trước khi thay đổi lớn.
