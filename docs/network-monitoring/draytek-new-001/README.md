# Hồ sơ triển khai mới – DrayTek thứ hai

<small>Trạng thái hồ sơ: **ĐÃ BẮT ĐẦU** · Thiết bị mới · Không ghi đè hồ sơ Vigor2962 trước đó</small>

## 1. Mục đích và nguyên tắc

Hồ sơ này dùng để ghi lại từ đầu quá trình cấu hình một modem/router DrayTek khác để giám sát bằng Zabbix. Mọi thao tác, thông số, ảnh chụp, lệnh kiểm tra, lỗi và kết quả sẽ được ghi theo đúng trình tự thực tế.

**Đây là một thiết bị mới, không mặc định dùng lại cấu hình của DrayTek Vigor2962 đã triển khai trước đây.** Hồ sơ cũ chỉ là tài liệu tham khảo về kiến trúc và kinh nghiệm. Mọi IP, WAN, VPN, firmware, SNMP, webhook, MAC và Zabbix Host của thiết bị mới phải được xác nhận lại.

## 2. Thông tin nhận diện thiết bị mới

| Thuộc tính | Giá trị ghi nhận |
|---|---|
| Mã hồ sơ | DRAYTEK-NEW-001 |
| Tên thiết bị | Chưa xác nhận |
| Model | Chưa xác nhận |
| Firmware/build | Chưa xác nhận |
| Địa điểm/lắp đặt | Chưa xác nhận |
| MAC address | Chưa xác nhận |
| LAN IP | Chưa xác nhận |
| LAN subnet | Chưa xác nhận |
| WAN1 | Chưa xác nhận |
| WAN2 | Chưa xác nhận |
| Người thực hiện | Người dùng và trợ lý phối hợp |
| Ngày bắt đầu | 2026-11-26 |
| Trạng thái | **Đang triển khai – Chờ khảo sát thiết bị** |

## 3. Quy tắc ghi nhận bằng chứng

Mỗi lần người dùng gửi ảnh hoặc kết quả lệnh:

1. Gán mã bằng chứng theo thứ tự: `NEW-IMG-001`, `NEW-IMG-002`… hoặc `NEW-LOG-001`, `NEW-LOG-002`…
2. Ghi ngày/giờ nếu có.
3. Mô tả chính xác màn hình, menu, trường cấu hình hoặc kết quả lệnh.
4. Ghi lại thông số đọc được; không suy đoán phần bị khuất/mờ.
5. Ghi thao tác đã thực hiện và kết quả sau thao tác.
6. Đánh dấu trạng thái: **Đã xác nhận / Chưa xác nhận / Không đạt / Chưa thực hiện**.
7. Ảnh có mật khẩu, PSK, SNMP community, webhook token hoặc thông tin nhạy cảm phải được che trước khi đưa vào tài liệu chia sẻ.

Ảnh phải được đặt ngay tại bước tương ứng, kèm chú thích; không chỉ gom thành danh sách ảnh ở cuối tài liệu.

## 4. Trình tự triển khai dự kiến

Các giai đoạn dưới đây là khung quản lý công việc. Chỉ chuyển sang “Đã xác nhận” sau khi có thao tác và bằng chứng thực tế.

| STT | Giai đoạn | Trạng thái ban đầu |
|---:|---|---|
| 01 | Khảo sát thiết bị, model, firmware, MAC, LAN/WAN | Đang chờ |
| 02 | Khảo sát topology mạng tại địa điểm | Đang chờ |
| 03 | Xác định phương án VPN LAN-to-LAN và NAT hai đầu (nếu cần) | Đang chờ |
| 04 | Cấu hình VPN và kiểm tra tunnel/selectors | Đang chờ |
| 05 | Kiểm tra định tuyến và truy cập mạng quản trị | Đang chờ |
| 06 | Bật/cấu hình SNMP và ACL | Đang chờ |
| 07 | Kiểm tra SNMP walk, OID, interface index | Đang chờ |
| 08 | Tạo Zabbix Host và SNMP interface | Đang chờ |
| 09 | Khai báo Items, Triggers, Graphs/Template | Đang chờ |
| 10 | Cấu hình Webhook trên DrayTek (nếu dùng) | Đang chờ |
| 11 | Receiver, firewall, Docker và kiểm tra HTTP | Đang chờ |
| 12 | SQLite event queue và đăng ký thiết bị | Đang chờ |
| 13 | Worker mapping và gửi dữ liệu Zabbix | Đang chờ |
| 14 | Kiểm thử âm/dương, bảo mật và nghiệm thu | Đang chờ |
| 15 | Hoàn thiện sơ đồ, bàn giao và cập nhật tài liệu tổng | Đang chờ |

## 5. Nhật ký triển khai

| Lần | Ngày/giờ | Bước | Thao tác / thông số | Bằng chứng | Kết quả | Trạng thái |
|---:|---|---|---|---|---|---|
| 001 | 2026-11-26 | Khởi tạo hồ sơ | Tạo hồ sơ riêng cho DrayTek mới; chưa áp dụng cấu hình thiết bị cũ | Hồ sơ Git | Bắt đầu theo dõi | Đã xác nhận |

## 6. Ranh giới với hồ sơ DrayTek Vigor2962 trước đây

- Hồ sơ cũ: `12-implementation-history.md`, `13-detailed-implementation-runbook.md`.
- Thiết bị cũ: Vigor2962, MAC đã ghi nhận `1449bc9135d0`, LAN IP `192.168.105.1`.
- Các thông số trên **không phải** thông số của thiết bị mới.
- Chỉ tái sử dụng kiến trúc/phương pháp sau khi xác nhận tương thích; không sao chép IP, selector, PSK, community, token, Host name hoặc Item mapping.
- Nếu thiết bị mới dùng chung Receiver/Worker, phải đăng ký MAC và Zabbix Host mapping riêng, đồng thời kiểm thử cách ly MAC trước khi đưa vào vận hành.

## 7. Bước đầu tiên – Khảo sát thiết bị

**Trạng thái: ĐANG CHỜ ẢNH / THÔNG TIN THỰC TẾ.**

Bắt đầu bằng ảnh trang Dashboard/System Status của DrayTek mới, trong đó nhìn rõ:
- Model Name.
- Firmware Version/Build Date.
- Device Name.
- LAN IP (nếu hiển thị).
- MAC address (nếu hiển thị).

Sau khi xác nhận thiết bị, mới ghi nhận sơ đồ LAN/WAN và lập phương án VPN. Không thay đổi cấu hình VPN hoặc SNMP trước khi hoàn tất khảo sát hiện trạng và xác định đường truy cập quản trị.

---

## 8. Lịch sử chỉnh sửa hồ sơ

- 2026-11-26: Khởi tạo hồ sơ DRAYTEK-NEW-001, đánh dấu bắt đầu triển khai thiết bị DrayTek mới.
