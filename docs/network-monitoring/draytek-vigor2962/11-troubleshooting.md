# 11. Troubleshooting

## SNMP không có dữ liệu

1. Kiểm tra SNMP enabled trên DrayTek.
2. Kiểm tra community/version.
3. Kiểm tra UDP/161 và route IPsec.
4. Thử truy vấn từ đúng network namespace của Zabbix Server.
5. Xác nhận interface IP trên Host là `192.168.105.1`.
6. Kiểm tra Latest data và error message của Item.

## Trapper Item không nhận dữ liệu

1. Kiểm tra Zabbix Sender/Worker log.
2. Kiểm tra Zabbix Server port 10051.
3. Kiểm tra technical host name; không dùng Visible name.
4. Kiểm tra key tồn tại và Item enabled.
5. Kiểm tra Value type tương thích với dữ liệu gửi.
6. Kiểm tra sender response và thời gian timestamp.

## Webhook không tới receiver

1. Kiểm tra URL/token mà không đưa token vào log chia sẻ.
2. Kiểm tra source IP allow-list.
3. Kiểm tra route/firewall qua IPsec.
4. Kiểm tra receiver log và HTTP status.
5. Kiểm tra SQLite có event mới hay không.

## Event MAC không đăng ký nhưng processed

Đây là lỗi nghiêm trọng về tenant isolation. Không tiếp tục onboard thiết bị mới.

1. Xác định PID/container đang chạy Worker.
2. Xác nhận command line và file `/data/worker.py` thực tế trong container.
3. Xác nhận `DB_PATH` runtime và inode/file database dùng bởi receiver.
4. Truy vấn bảng `devices` trực tiếp từ đúng DB mà Worker đang mở.
5. Kiểm tra mọi đường gọi `send_to_zabbix` và mọi cập nhật `processing_status='processed'`.
6. Thêm log có event_id, normalized MAC, database path và resolved host (không log secret).
7. Test với MAC giả mới, xác minh event chuyển `unmapped` và không phát sinh sender payload.

Không chỉ dựa vào source file trên host; cần xác minh code đang chạy trong container và database thực tế.

## WAN rate sai đơn vị

Các giá trị webhook rate đang lưu raw. Không quy đổi sang Mbps cho đến khi xác nhận đơn vị nguồn và đối chiếu phép đo SNMP/traffic thực tế.
