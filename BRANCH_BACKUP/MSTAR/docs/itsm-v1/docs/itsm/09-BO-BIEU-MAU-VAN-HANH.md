# 09. Bộ biểu mẫu vận hành ITSM MSTAR

**Trạng thái:** DỰ THẢO — cần người phụ trách và cấp có thẩm quyền phê duyệt trước khi ban hành.  
**Căn cứ:** 10 hồ sơ nguồn được liệt kê tại `00-DANH-GIA-10-HO-SO-GOC.md`.  
**Nguyên tắc:** Biểu mẫu này chuẩn hóa cách ghi nhận thông tin, không thay thế dữ liệu thực tế tại MSTAR. Không điền mật khẩu, khóa bí mật hoặc thông tin xác thực vào biểu mẫu/ticket/Git.

---

## BM-01 — Tiếp nhận và xử lý yêu cầu/ticket

### A. Thông tin tiếp nhận
| Trường | Nội dung |
|---|---|
| Mã ticket | Hệ thống tự sinh hoặc ghi mã duy nhất |
| Ngày/giờ tiếp nhận | |
| Người yêu cầu | |
| Đơn vị/phòng ban | |
| Kênh tiếp nhận | Portal / Email công ty / Điện thoại / Trực tiếp / Khác |
| Nhóm dịch vụ | Tài khoản / Máy tính / Mạng / File Server / Email / Thiết bị / Phần mềm / Khác |
| Loại yêu cầu | Incident / Service Request / Access / Change / Other |
| Mô tả và ảnh hưởng | |
| Mức độ ảnh hưởng | Một người / Một bộ phận / Nhiều bộ phận / Toàn công ty |
| Mức ưu tiên | Theo ma trận ưu tiên được phê duyệt |
| Tài sản liên quan | Mã tài sản, không ghi mật khẩu |
| SLA áp dụng | Dẫn chiếu mục SLA tại tài liệu 02 |
| Người/nhóm được giao | |
| Thời điểm bắt đầu tính SLA | Theo quy tắc SLA được phê duyệt |

### B. Theo dõi xử lý
| Mốc | Thời điểm | Người thực hiện | Nội dung/kết quả |
|---|---|---|---|
| Tiếp nhận | | | |
| Phân loại/giao việc | | | |
| Phản hồi đầu tiên | | | |
| Chẩn đoán | | | |
| Cập nhật cho người yêu cầu | | | |
| Chuyển cấp/escalation (nếu có) | | | |
| Khôi phục/giải quyết | | | |
| Người yêu cầu xác nhận | | | |
| Đóng ticket | | | |

### C. Kiểm soát đóng ticket
- [ ] Đã ghi nguyên nhân hoặc kết quả xử lý phù hợp.
- [ ] Đã ghi giải pháp/biện pháp khắc phục.
- [ ] Đã cập nhật thời gian và người xử lý.
- [ ] Đã ghi nhận thiết bị/tài khoản bị tác động nếu có.
- [ ] Đã thông báo kết quả cho người yêu cầu.
- [ ] Đã ghi nhận việc vượt SLA và lý do nếu có.
- [ ] Đã xác nhận không còn công việc tiếp theo; nếu còn, tạo ticket/task theo dõi riêng.

---

## BM-02 — Cấp phát, thu hồi và bàn giao tài sản CNTT

### A. Thông tin biên bản
| Trường | Nội dung |
|---|---|
| Mã biên bản | |
| Loại nghiệp vụ | Cấp phát / Thu hồi / Điều chuyển / Mượn / Trả / Bàn giao |
| Ngày thực hiện | |
| Đơn vị | |
| Người giao | |
| Người nhận | |
| Người phê duyệt | Theo phân quyền đã ban hành |

### B. Danh sách tài sản
| STT | Mã tài sản | Loại thiết bị | Hãng/model | Serial | Tình trạng | Phụ kiện | Vị trí | Ghi chú |
|---|---|---|---|---|---|---|---|---|
| 1 | | | | | Tốt / Lỗi / Hư hỏng / Chưa kiểm tra | | | |

### C. Xác nhận
- [ ] Đối chiếu mã tài sản và serial với sổ tài sản.
- [ ] Kiểm tra thiết bị, phụ kiện và tình trạng hoạt động.
- [ ] Cập nhật người sử dụng, phòng ban, vị trí và trạng thái tài sản.
- [ ] Khi thu hồi: kiểm tra dữ liệu công ty, tài khoản và yêu cầu sao lưu/xóa dữ liệu theo chính sách được duyệt.
- [ ] Khi cấp phát: bàn giao hướng dẫn sử dụng và yêu cầu bảo mật liên quan.
- [ ] Hai bên xác nhận và lưu biên bản theo quy định lưu trữ.

**Chữ ký:** Người giao __________  Người nhận __________  Người phê duyệt __________

> Quy tắc mã tài sản phải đối chiếu tài liệu nguồn 2.b và danh mục định nghĩa 2.c. Không tự ý tạo mã mới nếu chưa xác định quy tắc chuẩn khi hai nguồn có khác biệt.

---

## BM-03 — Yêu cầu cấp, đổi hoặc thu hồi tài khoản/quyền truy cập

| Trường | Nội dung |
|---|---|
| Mã yêu cầu | |
| Loại yêu cầu | Tạo mới / Thay đổi quyền / Khóa / Thu hồi / Khôi phục |
| Nhân sự liên quan | |
| Phòng ban/chức danh | |
| Quản lý xác nhận | |
| Hệ thống | AD / File Server / Email / Ứng dụng / VPN / Khác |
| Nhóm/quyền cần cấp | Nêu đúng nhóm hoặc thư mục; tránh mô tả chung chung |
| Lý do nghiệp vụ | |
| Ngày hiệu lực | |
| Ngày hết hạn (nếu quyền tạm thời) | |
| Người phê duyệt | |
| Người thực hiện | |
| Thời điểm hoàn tất | |
| Kết quả kiểm tra | |
| Ticket/biên bản liên quan | |

### Kiểm soát bắt buộc
- [ ] Xác minh danh tính và thẩm quyền yêu cầu.
- [ ] Có phê duyệt của chủ quản/quản lý theo ma trận quyền đã duyệt.
- [ ] Cấp quyền tối thiểu cần thiết; không dùng chung tài khoản cá nhân.
- [ ] Kiểm tra quyền truy cập sau khi thay đổi.
- [ ] Khi nhân sự nghỉ việc/chuyển bộ phận, thu hồi hoặc điều chỉnh quyền đúng thời điểm được phê duyệt.
- [ ] Không ghi mật khẩu, mã OTP, recovery code hoặc private key vào ticket/biên bản.

---

## BM-04 — Phiếu thay đổi hệ thống (Change Request)

| Trường | Nội dung |
|---|---|
| Mã Change | |
| Người đề xuất | |
| Hệ thống/dịch vụ ảnh hưởng | |
| Mục tiêu và lý do | |
| Mô tả thay đổi | |
| Phạm vi/đối tượng bị ảnh hưởng | |
| Rủi ro và tác động dự kiến | |
| Thời gian triển khai dự kiến | |
| Thời gian gián đoạn dự kiến | |
| Kế hoạch sao lưu trước thay đổi | |
| Kế hoạch triển khai | |
| Kế hoạch kiểm thử sau thay đổi | |
| Kế hoạch rollback/khôi phục | |
| Người thực hiện | |
| Người phê duyệt | |
| Kết quả thực tế | |
| Sự cố phát sinh | |
| Thời điểm đóng Change | |

### Cổng kiểm soát
- [ ] Phân loại: thường / khẩn cấp theo tiêu chí đã phê duyệt.
- [ ] Đánh giá tác động và thông báo đơn vị liên quan.
- [ ] Có backup/rollback phù hợp với mức rủi ro.
- [ ] Có phê duyệt trước khi thực hiện, trừ quy trình khẩn cấp được ban hành riêng.
- [ ] Kiểm tra dịch vụ sau triển khai và cập nhật tài liệu cấu hình.
- [ ] Nếu thất bại, khôi phục theo kế hoạch và tạo/cập nhật incident liên quan.

---

## BM-05 — Nhật ký kiểm tra vận hành hằng ngày

**Ngày:** __________ **Người kiểm tra:** __________ **Ca/thời gian:** __________

| Hạng mục theo checklist nguồn 3.a | Trạng thái (OK/Cảnh báo/Lỗi/N/A) | Bằng chứng/thông số | Ticket liên quan | Người xử lý | Ghi chú |
|---|---|---|---|---|---|
| DC-Primary | | | | | |
| File Server | | | | | |
| Backup Center | | | | | |
| DVA/Camera | | | | | |
| Host/VM | | | | | |
| Virtual DSM | | | | | |
| AD/DNS/DHCP | | | | | |
| Email Server/license | | | | | |
| Router chính | | | | | |
| Switch trung tâm | | | | | |
| Wi-Fi Controller | | | | | |
| Phòng máy: nhiệt độ/điện/điều kiện | | | | | |
| Backup Domain Controller | | | | | |
| Backup Wi-Fi Controller | | | | | |
| Backup Accounting Server | | | | | |
| Backup Accounting DB | | | | | |
| Zabbix: host/problem/trigger | | | | | |

**Quy tắc:** Nếu trạng thái Cảnh báo/Lỗi, ghi bằng chứng, mở ticket hoặc liên kết ticket có sẵn, xác định người chịu trách nhiệm và thời điểm kiểm tra lại. Không đánh dấu “OK” nếu chưa có bằng chứng kiểm tra. Danh sách trên được lấy từ checklist nguồn và cần đối chiếu với hệ thống đang thực tế vận hành.

---

## BM-06 — Nhật ký sự kiện Zabbix/giám sát

| Trường | Nội dung |
|---|---|
| Mã cảnh báo/ticket | |
| Thời điểm phát hiện | |
| Host/service | |
| Trigger/problem | |
| Mức độ | Theo severity đang cấu hình; không tự đặt ngưỡng mới |
| Nguồn phát hiện | Zabbix / Người dùng / Khác |
| Dịch vụ/người dùng bị ảnh hưởng | |
| Người nhận cảnh báo | |
| Thời điểm xác nhận cảnh báo | |
| Hành động ban đầu | |
| Nguyên nhân | |
| Hành động khắc phục | |
| Thời điểm hết sự cố | |
| Thời gian gián đoạn | |
| Bài học/đề xuất phòng ngừa | |

Ngưỡng CPU, RAM, disk, network, thời gian mất kết nối, người nhận cảnh báo và lịch trực phải được phê duyệt/cấu hình dựa trên từng hệ thống; tài liệu nguồn hiện chưa đủ căn cứ để coi một bộ ngưỡng chung là chuẩn chính thức.

---

## BM-07 — Phiếu kiểm tra backup và thử khôi phục

| Trường | Nội dung |
|---|---|
| Ngày kiểm tra | |
| Hệ thống/nguồn dữ liệu | |
| Loại backup | Full / Incremental / DB / File / Config / Khác |
| Thời điểm backup gần nhất | |
| Kết quả job | Success / Warning / Failed / Chưa kiểm tra |
| Dung lượng/đích lưu | |
| Bằng chứng log | |
| Người kiểm tra | |
| Ticket nếu thất bại | |
| Ngày thử restore gần nhất | |
| Phạm vi restore thử | |
| Kết quả kiểm tra dữ liệu/ứng dụng | |
| Thời gian khôi phục thực tế | |
| RPO/RTO mục tiêu được phê duyệt | |
| Hành động khắc phục | |

**Lưu ý:** Không tự đặt RPO/RTO chỉ từ checklist hiện có. Chủ hệ thống và cấp có thẩm quyền phải xác định mục tiêu, tần suất backup, thời gian lưu, vị trí bản sao và lịch thử khôi phục.

---

## BM-08 — Theo dõi SLA và escalation

| Mã ticket | Nhóm dịch vụ | SLA nguồn áp dụng | Mốc bắt đầu | Phản hồi đầu tiên | Hoàn tất | Có vượt SLA? | Lý do | Cấp escalation | Thời điểm escalation | Kết quả |
|---|---|---|---|---|---|---|---|---|---|---|

### Quy tắc ghi nhận
1. Chỉ áp dụng thời gian SLA đã được phê duyệt trong tài liệu 02.
2. Không tự suy diễn thời gian phản hồi M-Service: nguồn hiện thiếu con số.
3. Ghi rõ đơn vị thời gian là giờ/ngày và quy tắc tính ngày làm việc/ngày lịch sau khi được phê duyệt.
4. Khi vượt SLA, lưu nguyên nhân, tác động, người đã được thông báo và hành động khắc phục.
5. Danh sách người nhận escalation phải lấy từ danh bạ/phân quyền hiện hành đã được xác nhận, không chỉ dựa vào tên trong tài liệu cũ.

---

## BM-09 — Quản lý nhà cung cấp, hợp đồng và license

| Trường | Nội dung |
|---|---|
| Nhà cung cấp | |
| Dịch vụ/thiết bị/license | |
| Chủ sở hữu nội bộ | |
| Đơn vị sử dụng | |
| Mã hợp đồng/đơn hàng | |
| Ngày bắt đầu/kết thúc | |
| Số lượng license/quyền sử dụng | |
| Phạm vi hỗ trợ và SLA nhà cung cấp | |
| Kênh hỗ trợ chính thức | |
| Thông tin gia hạn | |
| Ngày nhắc gia hạn | |
| Rủi ro nếu hết hạn | |
| Hồ sơ/chứng từ liên quan | |

Không lưu mật khẩu, license key đầy đủ, token API hoặc thông tin thanh toán nhạy cảm trong sổ theo dõi chia sẻ. Chỉ lưu tham chiếu đến kho bí mật có kiểm soát truy cập.

---

## BM-10 — Báo cáo vận hành CNTT tháng

| Chỉ số | Kết quả kỳ này | Kỳ trước | Mục tiêu đã duyệt | Ghi chú/bằng chứng |
|---|---:|---:|---:|---|
| Ticket tiếp nhận | | | | |
| Ticket đã đóng | | | | |
| Ticket còn mở cuối kỳ | | | | |
| Ticket vượt SLA | | | | |
| Sự cố nghiêm trọng | | | | |
| Thời gian phản hồi/giải quyết | | | | |
| Số sự kiện giám sát đáng chú ý | | | | |
| Backup lỗi/chưa xác minh | | | | |
| Thử restore thành công | | | | |
| Tài sản mới cấp phát/thu hồi | | | | |
| Tài khoản cấp mới/thu hồi | | | | |
| Thay đổi hệ thống | | | | |
| License/hợp đồng sắp hết hạn | | | | |
| Rủi ro và hành động cần quyết định | | | | |

**Nguyên tắc báo cáo:** Số liệu phải trích từ ticket, sổ tài sản, nhật ký vận hành, hệ thống backup và Zabbix; ghi rõ khoảng thời gian, nguồn dữ liệu và hạn chế nếu số liệu chưa đầy đủ. Không tự đặt KPI mục tiêu khi chưa có phê duyệt.

---

## Lịch sử và phê duyệt biểu mẫu

| Phiên bản | Ngày | Nội dung thay đổi | Người soạn | Người rà soát | Người phê duyệt |
|---|---|---|---|---|---|
| 0.1-DRAFT | 2026-10-23 | Chuẩn hóa biểu mẫu dựa trên 10 hồ sơ nguồn; các trường chưa có căn cứ được để trống | | | |

## Điều kiện trước khi áp dụng
- Xác nhận SLA còn thiếu và quy tắc giờ/ngày làm việc.
- Xác nhận người phê duyệt, người nhận escalation và kênh tiếp nhận ticket.
- Đối chiếu tên hệ thống/thiết bị với hạ tầng thực tế.
- Phê duyệt quy tắc mã tài sản và xử lý khác biệt giữa tài liệu 2.b và 2.c.
- Phê duyệt phân quyền File Server, chính sách tài khoản và quy tắc offboarding.
- Phê duyệt ngưỡng Zabbix, người nhận cảnh báo, backup retention, RPO/RTO.
- Xác định nơi lưu hồ sơ, quyền truy cập, thời hạn lưu và người chịu trách nhiệm cập nhật.
