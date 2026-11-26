# 10. Kiểm thử và nghiệm thu

## 1. SNMP

- [ ] Zabbix Server/Proxy truy vấn được `192.168.105.1:161`.
- [ ] SNMP credentials đúng và được lưu an toàn.
- [ ] WAN1 ifIndex xác nhận là 4.
- [ ] WAN2 ifIndex xác nhận là 5.
- [ ] ifInOctets/ifOutOctets trả dữ liệu.
- [ ] ifHC counters không hỗ trợ trong lần kiểm tra hiện tại; không cấu hình phụ thuộc vào chúng.

## 2. Webhook receiver

- [ ] DrayTek gửi đúng endpoint.
- [ ] Receiver trả HTTP 200.
- [ ] Source allow-list hoạt động.
- [ ] Payload được ghi vào đúng SQLite DB.
- [ ] Không ghi token/community vào log hoặc tài liệu.

## 3. Worker

- [ ] Event MAC đăng ký được map đúng Zabbix Host.
- [ ] 17 giá trị gửi thành công.
- [ ] MAC không đăng ký chuyển `unmapped`.
- [ ] Device disabled chuyển `disabled`.
- [ ] MAC dạng `14:49:BC:91:35:D0` map cùng thiết bị.
- [ ] Worker và receiver dùng cùng database file.
- [ ] Không có fallback host khi MAC không tồn tại.

**Điều kiện chặn nghiệm thu:** test MAC chưa đăng ký trước đây cho kết quả Event `processed` thay vì `unmapped`. Phải sửa và kiểm thử lại thành công trước khi công nhận Multi-Host an toàn.

## 4. Zabbix

- [ ] 17 Trapper Items enabled, key/type đúng.
- [ ] Latest data cập nhật.
- [ ] Giá trị CPU/Memory nằm trong miền hợp lý.
- [ ] WAN status/IP khớp trạng thái thiết bị.
- [ ] WAN rate raw chưa được gán đơn vị bps/Mbps khi chưa xác minh.
- [ ] Trigger test được kiểm tra bằng phương pháp an toàn.
- [ ] Graph không trùng và dùng đúng đơn vị.
- [ ] Dashboard hiển thị đúng Host.

## 5. Hồ sơ cần lưu

- Export Host/Template XML từ Zabbix.
- Export danh sách Items, Triggers, Graphs.
- Ảnh cấu hình SNMP/Webhook đã che thông tin bí mật.
- Kết quả SNMP walk.
- Event payload mẫu đã loại bỏ thông tin nhạy cảm.
- Log Worker và kết quả kiểm thử.
