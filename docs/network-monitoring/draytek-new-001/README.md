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
| 01 | Khảo sát thiết bị, model, firmware, MAC, LAN/WAN | Đã xác nhận một phần – NEW-IMG-001 |
| 02 | Khảo sát topology mạng tại địa điểm | Đang chờ |
| 03 | Xác định phương án VPN LAN-to-LAN và NAT hai đầu (nếu cần) | Đang chờ khảo sát topology |
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


## 9. Nhật ký bằng chứng – NEW-IMG-001

<small>Ảnh người dùng gửi ngày 2026-11-26 · Trang Dashboard DrayTek</small>

### 9.1. Nhận diện thiết bị đọc từ ảnh

| Thuộc tính | Giá trị nhìn thấy trên ảnh | Trạng thái |
|---|---|---|
| Model Name | Vigor2962 | Đã xác nhận |
| Router Name | DrayTek | Đã xác nhận |
| Firmware/Loader Version | 4.4.5.1/v24 | Đã xác nhận |
| Build Date/Time | Aug 6 2025 16:07:54 | Đã xác nhận |
| LAN MAC Address | 14-49-BC-20-21-10 | Đã xác nhận |
| System Up Time | 2 days 22:04:00 | Ảnh chụp tại thời điểm hiển thị |
| Current Time trên router | Thu Nov 26 2026 15:36:06 | Ảnh chụp tại thời điểm hiển thị |

### 9.2. LAN hiện trạng

| Thuộc tính | Giá trị |
|---|---|
| LAN interface | LAN1 |
| IPv4 address | 192.168.1.1/24 |
| DHCP | Đang bật (dấu chọn hiển thị trên Dashboard) |
| Subnet | 192.168.1.0/24 |

### 9.3. WAN hiện trạng

| WAN | Line/Mode | IP hiển thị | MAC | Trạng thái |
|---|---|---|---|---|
| WAN1 | Ethernet / PPPoE | 14.161.37.40 | 14-49-BC-20-21-11 | Đang kết nối; uptime 2 days 22:03:25 |
| WAN2 | SFP / DHCP Client | Disconnected | 14-49-BC-20-21-12 | Đang ngắt kết nối |

### 9.4. Tài nguyên hệ thống tại thời điểm chụp

- CPU Usage: 10%.
- Co-Proc CPU: 1%.
- Memory Usage: 56%.
- Session current/max: 1%/2%.

### 9.5. Địa chỉ quản trị thể hiện trên trình duyệt

- URL hiển thị: `https://14.161.37.40:8443`.
- Trình duyệt hiển thị cảnh báo “Not secure”. Đây là cảnh báo bảo mật chứng chỉ/trình duyệt tại thời điểm chụp; chưa kết luận nguyên nhân.
- Không coi URL quản trị WAN là thông tin cần mở công khai. Cần giới hạn truy cập quản trị từ WAN theo chính sách bảo mật.

### 9.6. Kết luận bước khảo sát đầu tiên

**Đã xác nhận từ ảnh:** thiết bị là DrayTek Vigor2962, firmware 4.4.5.1/v24, LAN1 192.168.1.1/24, WAN1 PPPoE đang kết nối, WAN2 SFP/DHCP Client đang disconnected.

**Chưa xác nhận:** vị trí/lắp đặt, gateway/thiết bị NAT phía trước, public IP có cố định hay không, subnet tại trung tâm cần kết nối VPN, peer VPN, phương án VPN Dial-Out/Dial-In, cấu hình SNMP, MAC WAN có dùng để nhận diện thiết bị trong registry hay không.

### 9.7. Việc tiếp theo – khảo sát trước khi thay đổi cấu hình

1. Xác nhận đây có đúng là modem mới dự kiến kết nối VPN về trung tâm Zabbix hay không.
2. Xác nhận LAN 192.168.1.0/24 có đang sử dụng thực tế và có bị trùng subnet với mạng trung tâm không.
3. Xác định thiết bị này kết nối Internet trực tiếp bằng PPPoE hay còn modem/ONT/Peplink NAT phía trước.
4. Xác định WAN1 có public IP trực tiếp hay bị NAT/CGNAT; không suy luận chỉ từ địa chỉ đang hiển thị.
5. Kiểm tra WAN2 có được dự kiến dùng làm đường dự phòng không, hiện tại nó đang disconnected.
6. Sau khi khảo sát topology hai đầu, mới chốt thông số VPN LAN-to-LAN.

**Chưa thay đổi cấu hình LAN, WAN, VPN hoặc SNMP ở bước này.**


## 10. Nhật ký bằng chứng – NEW-IMG-002

<small>Ảnh người dùng gửi ngày 2026-11-26 · WAN → Internet Access → WAN1</small>

### 10.1. Cấu hình WAN1 đọc từ ảnh

| Thuộc tính | Giá trị hiển thị | Ghi chú |
|---|---|---|
| WAN1 | Enable | WAN1 đang bật |
| Access type | PPPoE | Tab PPPoE được chọn |
| PPP Authentication | PAP/CHAP/MS-CHAP/MS-CHAPv2 | Các phương thức được cho phép |
| Idle Timeout | -1 second(s) | Ghi đúng giá trị giao diện |
| IP Assignment (IPCP) | Dynamic | Không chọn Static |
| Fixed IP Address | Trống | Không khai báo |
| WAN IP Alias | Không xác định/không khai báo | Nút cấu hình riêng |
| WAN Connection Detection | PPP Detect | Chế độ phát hiện kết nối |
| MTU | 1438 | Giá trị đang cấu hình |
| Path MTU Discovery | Nút Detect | Ảnh không thể hiện kết quả Detect |
| Dial-Out Schedule | None | Các ô lịch đều None |
| TTL override | Không bật | Checkbox không chọn |
| MAC Address | Default MAC Address | Không chọn Specify a MAC Address |

**Thông tin xác thực:** ảnh có hiển thị PPPoE username và password bị che. Không sao chép username/password vào tài liệu triển khai; không cần gửi mật khẩu cho quá trình khảo sát.

### 10.2. Nhận định kỹ thuật – chỉ dựa trên bằng chứng hiện có

- WAN1 đang được cấu hình PPPoE và IPCP Dynamic.
- Dashboard trước đó hiển thị WAN1 có IP `14.161.37.40`; ảnh cấu hình này không hiển thị gateway, DNS hoặc trạng thái IP cố định.
- Chưa đủ bằng chứng để kết luận IP WAN1 là static/dynamic từ ISP, có bị NAT/CGNAT hay không, hoặc có thể nhận kết nối VPN từ Internet.
- MTU hiện tại là 1438. Chưa thay đổi MTU; khi triển khai IPsec cần kiểm tra MTU/MSS end-to-end trước khi quyết định điều chỉnh.

### 10.3. Trạng thái

**Đã ghi nhận:** WAN1 PPPoE Enable, IPCP Dynamic, MTU 1438, PPP Detect, Default MAC.

**Chưa ghi nhận:** Default Gateway, DNS, PPPoE public IP ownership/static lease, ISP NAT/CGNAT, IPsec reachability từ Internet, thông tin WAN2 và thiết bị upstream.

**Không thực hiện thay đổi cấu hình.** Không lưu/nhấn OK trong trang cấu hình ở bước khảo sát này.

### 10.4. Bước tiếp theo

Mở **WAN → Internet Access → Static or Dynamic IP** của WAN1 và gửi ảnh trang đó để kiểm tra các thông tin IP/gateway/DNS nếu giao diện có cung cấp. Nếu trang này không có thông tin WAN1 đang dùng, ta sẽ lấy trạng thái thực tế tại **Online Status → Physical Connection** hoặc **Online Status → WAN** (tùy menu firmware).

Sau đó khảo sát WAN2 và topology phía ISP trước khi thiết kế VPN.


## 11. Nhật ký bằng chứng – NEW-IMG-003

<small>Ảnh người dùng gửi ngày 2026-11-26 · Online Status → Physical Connection → IPv4</small>

### 11.1. LAN Status

| Thuộc tính | Giá trị |
|---|---|
| LAN IP | 192.168.1.1 |
| TX Packets | 136,245,736 |
| RX Packets | 129,040,915 |
| Router Primary DNS | 123.23.23.23 |
| Router Secondary DNS | 123.26.26.26 |

### 11.2. WAN1 Status

| Thuộc tính | Giá trị |
|---|---|
| Enable | Yes |
| Line | Ethernet |
| Name | VNPT |
| Mode | PPPoE |
| Up Time | 70:07:10 |
| IP | 14.161.37.40 |
| GW IP | 203.210.144.229 |
| TX Bytes | 141 GB |
| TX Rate | 29.52 Mbps |
| RX Bytes | 127 GB |
| RX Rate | 641.02 Kbps |

### 11.3. WAN2 Status

| Thuộc tính | Giá trị |
|---|---|
| Enable | No |
| Line | Fiber |
| Mode | DHCP Client |
| Up Time | 00:00:00 |
| IP | --- |
| GW IP | --- |
| TX/RX Bytes | 0 B / 0 B |
| TX/RX Rate | 0 |

### 11.4. Nhận định

- WAN1 đang hoạt động với PPPoE, tên đường truyền VNPT, IP 14.161.37.40 và gateway hiển thị 203.210.144.229.
- WAN2 hiện không được Enable và chưa có địa chỉ IP; không chỉ là trạng thái mất link. Cần xác nhận có chủ đích để tắt WAN2 hay không.
- Router DNS hiển thị 123.23.23.23 và 123.26.26.26.
- WAN1 Up Time trên trang trạng thái là 70:07:10, khác với thời điểm uptime ở Dashboard trước đó; ghi nhận đúng theo từng ảnh, chưa suy luận nguyên nhân.
- Trang này cho biết gateway nhưng chưa xác định WAN1 có public IP trực tiếp hay có NAT/CGNAT. Cần đối chiếu với IP public quan sát từ một dịch vụ Internet độc lập hoặc xác nhận từ ISP; không suy luận chỉ dựa trên dải IP.

### 11.5. Trạng thái bước khảo sát

**Đã xác nhận:** LAN IP, router DNS, WAN1 enable/PPPoE/VNPT/IP/Gateway, WAN2 disable/DHCP Client chưa có IP.

**Chưa xác nhận:** public IP trực tiếp hay NAT/CGNAT; WAN2 có được dự kiến sử dụng dự phòng hay không; thiết bị upstream và sơ đồ kết nối vật lý; subnet và IP endpoint phía trung tâm.

**Không thay đổi cấu hình.**

### 11.6. Bước tiếp theo

Cần xác nhận hai thông tin topology trước khi thiết kế VPN:
1. Đường WAN1 VNPT có cắm trực tiếp vào cổng WAN1 DrayTek từ ONT/modem nhà mạng ở chế độ bridge, hay đi qua một router/NAT khác?
2. WAN2 đang Disable là do không sử dụng, hay sẽ triển khai làm đường Internet dự phòng?

Sau khi có câu trả lời, tiếp tục khảo sát VPN target và subnet phía trung tâm; chưa bật WAN2 hoặc thay đổi cấu hình.
