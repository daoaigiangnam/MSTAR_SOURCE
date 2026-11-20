# DANH MỤC QUYẾT ĐỊNH, BẰNG CHỨNG VÀ CHECKLIST BAN HÀNH ITSM/ATTT

**Mã:** MSTAR-ITSM-GATE-001 | **Phiên bản:** 1.1-DRAFT | **Trạng thái:** Chưa ban hành

> Dùng cùng [Đánh giá 10 hồ sơ nguồn và khoảng trống triển khai](00-DANH-GIA-10-HO-SO-GOC.md) và [Master Index](../../ITSM-MASTER-INDEX.md). Trạng thái “chưa xác minh” nghĩa là chưa có bằng chứng được cung cấp để xác nhận việc triển khai; không mặc định rằng kiểm soát không tồn tại.

## 1. Quyết định quản trị bắt buộc

| ID | Nội dung cần chốt | Chủ trì đề xuất | Người phê duyệt | Đầu ra/bằng chứng | Trạng thái |
|---|---|---|---|---|---|
| D01 | Response Time M-Service còn thiếu trong hồ sơ gốc | IT Lead/Service Owner | Ban Giám đốc/ủy quyền | SLA có phê duyệt | CHƯA CHỐT |
| D02 | Days là ngày lịch hay ngày làm việc cho SLA sửa chữa/bảo hành/nội bộ | IT Lead | Service Owner/Ban Giám đốc | Quy tắc tính SLA | CHƯA CHỐT |
| D03 | Lịch làm việc, ngày nghỉ, giờ làm việc, timezone, pause/resume | IT Lead/HR | Ban Giám đốc | Lịch dịch vụ và quy tắc SLA | CHƯA CHỐT |
| D04 | Người nhận escalation, contact tree và người thay thế | IT Lead | Ban Giám đốc/Service Owner | Ma trận liên hệ có xác nhận | CHƯA CHỐT |
| D05 | Ma trận tác động/khẩn cấp, P1–P4 và Major Incident | IT Lead | Ban Giám đốc | Ma trận ưu tiên/major incident | CHƯA CHỐT |
| D06 | Thẩm quyền cấp tài khoản, quyền đặc quyền và File Server | IT Lead/Data Owner | Quản lý/BGĐ theo thẩm quyền | RACI/access approval matrix | CHƯA CHỐT |
| D07 | Chính sách xác thực hiện hành; rà soát quy tắc cũ 8 ký tự/60 ngày/khóa vĩnh viễn | System Owner | IT Lead/BGĐ | Chính sách định danh đã duyệt | CHƯA CHỐT |
| D08 | ACL PUBLIC root và nhóm quyền File Server thực tế | System Owner/Data Owner | IT Lead/Service Owner | ACL export, đánh giá rủi ro, change record, test | CHƯA CHỐT |
| D09 | Taxonomy tài sản và mapping dữ liệu `TAISAN.MSTARCORP.VN` | Asset Owner/IT/Finance | IT Lead/Finance | Danh mục chuẩn và reconciliation | CHƯA CHỐT |
| D10 | Chu kỳ kiểm kê, xử lý mất/hỏng/thanh lý tài sản | Asset Owner | Ban Giám đốc | Quy trình và lịch kiểm kê duyệt | CHƯA CHỐT |
| D11 | Ngưỡng Zabbix, dịch vụ trọng yếu, tuyến nhận cảnh báo và trực | Monitoring Owner | IT Lead/Service Owner | Monitoring matrix và test alert | CHƯA CHỐT |
| D12 | BIA và RTO/RPO theo từng dịch vụ; lịch restore/DR test | Service Owner/IT | Ban Giám đốc | BIA, RTO/RPO được duyệt, test results | CHƯA CHỐT |
| D13 | Retention ticket/log/backup/hồ sơ và quy trình hủy | IT/Data Owner | Ban Giám đốc/đơn vị liên quan | Retention schedule | CHƯA CHỐT |
| D14 | Chu kỳ rà soát quyền, license, vendor và tài liệu | IT Lead/Owners | Ban Giám đốc | Lịch kiểm soát | CHƯA CHỐT |
| D15 | Owner/approver/backup owner, phiên bản và ngày hiệu lực mỗi tài liệu | IT Lead | Ban Giám đốc | Document Register/phê duyệt | CHƯA CHỐT |
| D16 | Phân loại thông tin, kho được phép, chia sẻ bên ngoài và hủy | Data Owner/IT | Ban Giám đốc | POL-IS-01 và data/retention inventory | CHƯA CHỐT |
| D17 | Danh mục phần mềm được phép, quyền cài đặt, mua license và ngoại lệ | IT Lead/Procurement | Ban Giám đốc | POL-IT-02, software/license inventory | CHƯA CHỐT |
| D18 | Phương pháp đánh giá rủi ro, ngưỡng chấp nhận và thẩm quyền risk acceptance | IT Lead/Risk Owner | Ban Giám đốc | POL-IS-04, Risk Register/Treatment Plan | CHƯA CHỐT |
| D19 | BCP/DR: phạm vi dịch vụ, chiến lược, người chỉ huy và diễn tập | Service Owner/IT Lead | Ban Giám đốc | POL-BCDR-03, BIA, runbook và biên bản diễn tập | CHƯA CHỐT |
| D20 | Phạm vi SMS/ISMS, dịch vụ/site/ngoại lệ và chủ hệ thống quản lý | IT Lead | Ban Giám đốc | Scope statement đã duyệt | CHƯA CHỐT |

## 2. Checklist kiểm tra triển khai và bằng chứng trước ban hành

- [ ] Có phạm vi SMS/ISMS được phê duyệt, danh mục dịch vụ, Service Owner, site, phụ thuộc và bên cung cấp.
- [ ] Từng quy chế/chính sách/quy trình có mã, phiên bản, owner, reviewer, approver, ngày hiệu lực và trạng thái.
- [ ] SLA đã so với tài liệu nguồn và được duyệt; cấu hình Bitrix được test; cảnh báo/đẩy cấp đi đúng người.
- [ ] Ma trận P1–P4 và Major Incident được duyệt; diễn tập hoặc test luồng liên hệ đã có bằng chứng.
- [ ] Mẫu bàn giao không chứa trường lưu mật khẩu; tài sản/serial/owner/location đã được đối soát.
- [ ] File Server ACL thực tế đã kiểm tra; rủi ro PUBLIC root được xử lý theo change có duyệt và test.
- [ ] Chính sách phân loại dữ liệu, software use, risk methodology và BCP/DR đã được người có thẩm quyền duyệt.
- [ ] Sổ phần mềm/license, vendor/domain/SSL có số liệu thật, owner, expiry và kiểm thử nhắc hạn.
- [ ] Sơ đồ as-built theo site đã được xác minh, có phiên bản/owner và cập nhật cùng hồ sơ change.
- [ ] Zabbix host/trigger/recipient/threshold đã xác minh; test alert tạo được hành động/ticket đúng quy trình.
- [ ] Backup theo từng hệ thống có job status, retention được duyệt, bản sao được bảo vệ và restore test có bằng chứng.
- [ ] BIA/RTO/RPO theo từng dịch vụ được duyệt; BCP/DR runbook và liên lạc khẩn cấp đã chạy thử.
- [ ] Risk Register/Treatment Plan có owner, hạn, residual risk, approver và bằng chứng theo dõi.
- [ ] Daily checklist có timestamp, owner, kết quả, evidence; Fail có ticket/action và review.
- [ ] Access review/Mover/Leaver và quyền đặc quyền có mẫu bằng chứng thực tế.
- [ ] Có kế hoạch đào tạo/nhận thức, người tham gia, xác nhận hiểu biết và ngày áp dụng.
- [ ] Có chương trình audit nội bộ, auditor đủ năng lực/độc lập, report, CAPA và xác minh hiệu lực.
- [ ] Có KPI dictionary, nguồn dữ liệu, owner, kỳ báo cáo, mục tiêu được duyệt và baseline thực tế.
- [ ] Có retention schedule, kho lưu chính thức, quyền truy cập, cách hủy và bằng chứng truy xuất.
- [ ] Chạy thử quy trình trọng yếu; khuyết điểm phải đóng hoặc được chấp nhận đúng thẩm quyền trước ban hành.
- [ ] Thu hồi/đánh dấu tài liệu lỗi thời; chỉ mục master index link đúng phiên bản hiện hành.

## 3. Mẫu phê duyệt ban hành

- Tên bộ tài liệu: Bộ quản lý dịch vụ CNTT và kiểm soát liên quan MSTAR.
- Phiên bản: ____________________
- Chủ tài liệu: ____________________
- Rà soát kỹ thuật: ____________________
- Rà soát nghiệp vụ/Service Owner: ____________________
- Người phê duyệt: ____________________
- Ngày phê duyệt/hiệu lực: ____________________
- Ngoại lệ được chấp thuận và thời hạn: ____________________
- Link hồ sơ nghiệm thu triển khai: ____________________
- Chữ ký/xác nhận điện tử: ____________________

**Không đánh dấu APPROVED/Đã ban hành cho đến khi điều kiện chặn được giải quyết hoặc ngoại lệ được chấp nhận bằng văn bản đúng thẩm quyền.**