# Sổ tay Quản lý dịch vụ CNTT (ITSM) — MSTAR Corp

> **Nguồn quy trình vận hành chuẩn duy nhất:** các quy trình chi tiết có mã ITSM-INC-001 đến ITSM-DOC-001 nằm trong [`docs/procedures/`](procedures/01-INCIDENT.md). Thư mục [`docs/itsm/`](itsm/00-LUU-DO-TONG-THE-ITSM.md) chứa tài liệu quản trị, lưu đồ tổng thể, SLA nguồn, biểu mẫu, đối chiếu ISO và CAPA; không phải bộ quy trình vận hành song song. Nếu có khác biệt, phải rà soát, phê duyệt và cập nhật quy trình chuẩn cùng các tài liệu tham chiếu liên quan.


**Mã:** ITSM-HBK-001 | **Phiên bản:** DRAFT v1.0 | **Ngày tổng hợp:** 23/10/2026  
**Chủ sở hữu dự kiến:** IT Lead | **Phê duyệt:** [CẦN CHỐT — Ban Giám đốc/Service Owner]

> Dự thảo được tổng hợp từ hồ sơ cung cấp. Chỉ ban hành sau khi điểm [CẦN CHỐT] được xác nhận và phê duyệt.

## 1. Mục tiêu và phạm vi
Chuẩn hóa quản lý sự cố/yêu cầu, SLA, tài sản, tài khoản/quyền, File Server, thay đổi, giám sát, backup/restore, an ninh, nhà cung cấp và tài liệu kỹ thuật. Phạm vi nội bộ MSTAR hay gồm dịch vụ khách hàng M-Care/M-Service cần được chủ dịch vụ xác nhận.

## 2. Quy ước nguồn
- **[HIỆN CÓ]**: phản ánh hồ sơ hiện hữu.
- **[ĐỀ XUẤT]**: kiểm soát bổ sung để hoàn thiện ITSM.
- **[CẦN CHỐT]**: cần owner/Ban Giám đốc phê duyệt, không tự suy đoán.

## 3. Vai trò
| Vai trò | Trách nhiệm |
|---|---|
| Ban Giám đốc/Service Owner | Phê duyệt chính sách, SLA, ngoại lệ/rủi ro cao và major incident theo thẩm quyền. |
| IT Lead/ITSM Process Owner | Hiệu lực quy trình, phân công, SLA/KPI, thay đổi theo thẩm quyền và cải tiến. |
| Service Desk/Helpdesk | Tạo ticket, phân loại, xác nhận tiếp nhận, giao việc, cập nhật requester, kiểm tra kết quả và đóng ticket. |
| IT System/Network/Application | Chẩn đoán, thực hiện, lưu bằng chứng, cập nhật cấu hình/runbook. |
| HR/Manager | Xác nhận nhu cầu, nhân sự, ngày hiệu lực, vị trí, bộ quyền và thiết bị. |
| System/Data Owner | Phê duyệt quyền, restore dữ liệu và thay đổi liên quan dịch vụ/dữ liệu. |
| Requester/Customer | Cung cấp thông tin, phối hợp kiểm thử và xác nhận kết quả. |
| Vendor | Thực hiện theo hợp đồng dưới kiểm soát và giám sát nội bộ. |

## 4. Nguyên tắc kiểm soát
1. Mọi công việc CNTT phải được ghi nhận bằng ticket trên Bitrix hoặc hệ thống được chỉ định. Yêu cầu qua Zalo/điện thoại phải được tạo/cập nhật ticket.
2. Ticket tối thiểu ghi requester, công ty/site, dịch vụ/tài sản, mô tả, tác động, ưu tiên, người xử lý, mốc thời gian, trạng thái/cập nhật, kết quả, bằng chứng và xác nhận.
3. Không ghi mật khẩu, token, private key hoặc recovery code trong GitHub, Excel, ticket hay biên bản. Chỉ lưu tham chiếu tới password vault.
4. Cấp quyền theo nguyên tắc least privilege và có phê duyệt phù hợp.
5. Thay đổi rủi ro cần đánh giá tác động, phê duyệt, kiểm thử, kế hoạch rollback.
6. Lỗi từ checklist/monitoring phải có owner và ticket khi cần hành động.
7. Không đóng ticket khi chưa xác minh kết quả hoặc chưa ghi lý do không thể xác minh.
8. Không tự ý đổi SLA, retention, RPO/RTO hoặc cấu hình bảo mật chưa được duyệt.
9. Tài liệu phải có mã, version, owner, người kiểm tra/duyệt, ngày hiệu lực và lịch review.

## 5. Luồng dịch vụ
**Yêu cầu/sự cố → Ticket → Phân loại/ưu tiên → Phê duyệt (nếu cần) → Phân công → Thực hiện/cập nhật → Kiểm chứng → Đóng → Báo cáo/RCA/tri thức khi cần.**

## 6. Bộ quy trình ITSM — tài liệu điều phối và quy trình chi tiết

> **Cách sử dụng:** Dùng [Lưu đồ tổng thể ITSM — một trang](itsm/00-LUU-DO-TONG-THE-ITSM.md) để xác định nhánh xử lý. Tại từng nhánh, mở quy trình chi tiết ở bảng dưới. Các tài liệu là bản dự thảo; chỉ áp dụng chính thức sau khi được phê duyệt. Tên/mã quy trình chi tiết hiện hữu được giữ nguyên để tránh làm sai lệch tài liệu; quy trình bổ sung theo bộ ITSM được dẫn riêng.

### 6.1. Lưu đồ tổng thể và các quy trình quản trị

| Mã/nhóm | Quy trình/tài liệu | Dùng khi nào | Hồ sơ/biểu mẫu liên quan |
|---|---|---|---|
| ITSM-MASTER | [Lưu đồ tổng thể quản lý dịch vụ CNTT](itsm/00-LUU-DO-TONG-THE-ITSM.md) | Điều phối từ tiếp nhận tới đóng hồ sơ và cải tiến | Ticket, SLA, asset/change/access records, evidence |
| ITSM-GOV | [Quy chế quản lý dịch vụ CNTT](itsm/01-QUY-CHẾ-QUẢN-LÝ-DỊCH-VỤ-CNTT.md) | Phạm vi, nguyên tắc, vai trò, phê duyệt và quản trị ITSM | RACI, danh mục dịch vụ, KPI |
| ITSM-SLA | [SLA nguồn chuẩn và quy tắc tính](itsm/02-SLA-NGUON-CHUAN-VA-QUY-TAC-TINH.md) | Xác định thời hạn phản hồi/giải quyết, ghi nhận vi phạm và escalation | BM-08 theo [Bộ biểu mẫu](itsm/09-BO-BIEU-MAU-VAN-HANH.md) |
| ITSM-APPROVAL | [Checklist quyết định trước ban hành](itsm/08-DANH-MUC-PHE-DUYET-TRUOC-BAN-HANH.md) | Rà soát các điểm phải chốt trước khi công bố quy trình | Danh sách quyết định/owner/hạn |
| ITSM-FORMS | [Bộ biểu mẫu vận hành ITSM](itsm/09-BO-BIEU-MAU-VAN-HANH.md) | Chọn biểu mẫu phù hợp để ghi nhận công việc và bằng chứng | BM-01 đến BM-10 trong tài liệu |
| ITSM-DOC | [Kiểm soát tài liệu, hồ sơ và bằng chứng](itsm/10-KIEM-SOAT-TAI-LIEU-HO-SO-VA-BANG-CHUNG.md) | Tạo/sửa/duyệt/phát hành tài liệu; lưu và truy xuất hồ sơ | Document Register, lịch sử sửa đổi |
| ITSM-AUD/CAPA | [Đánh giá nội bộ và hành động khắc phục](itsm/12-QUY-TRINH-DOANH-GIA-NOI-BO-CAPA.md) | Đánh giá bằng chứng, ghi phát hiện, phân tích nguyên nhân và xác minh CAPA | FRM-AUD-01/02/03, FRM-CAPA-01 |
| ITSM-ISO-MAP | [Bảng đối chiếu ISO/IEC 20000-1 và ISO/IEC 27001](itsm/11-DOI-CHIEU-ISO-20000-1-ISO-27001.md) | Lập kế hoạch rà soát chủ đề, khoảng trống và bằng chứng cần có | Risk register, audit evidence, KPI |

### 6.2. Quy trình vận hành chi tiết

| Mã quy trình | Quy trình | Khi nào áp dụng | Biểu mẫu/sổ đăng ký liên quan |
|---|---|---|---|
| ITSM-INC-001 | [Quản lý sự cố (Incident)](procedures/01-INCIDENT.md) | Dịch vụ gián đoạn hoặc suy giảm ngoài dự kiến | [FRM Ticket](../forms/FRM-ITSM-01-Ticket-Fields.csv), security form nếu liên quan |
| ITSM-REQ-001 | [Yêu cầu dịch vụ (Service Request)](procedures/02-SERVICE-REQUEST.md) | Yêu cầu cung cấp dịch vụ chuẩn | FRM Ticket; [Access Request](../forms/FRM-ITSM-04-Access-Request.csv) khi có cấp quyền |
| ITSM-SLA-001 | [SLA và leo thang](procedures/03-SLA-ESCALATION.md) | Theo dõi hạn phản hồi/giải quyết và chuyển cấp | [SLA baseline](../SLA-BASELINE.md); BM-08 |
| ITSM-AST-001 | [Quản lý tài sản](procedures/04-ASSET-MANAGEMENT.md) | Tiếp nhận, gán mã, cấp phát, thu hồi, kiểm kê hoặc xử lý tài sản | [Biên bản bàn giao](../forms/FRM-ITSM-02-Asset-Handover.csv); [Asset Register](../registers/ASSET-REGISTER.csv) |
| ITSM-IAM-001 | [Tài khoản và quyền truy cập](procedures/05-ACCESS-MANAGEMENT.md) | Cấp/đổi/thu hồi tài khoản hoặc quyền | [Access Request](../forms/FRM-ITSM-04-Access-Request.csv); FRM Ticket |
| ITSM-FS-001 | [File Server và dữ liệu](procedures/06-FILE-SERVER.md) | Thư mục phòng ban/cá nhân, phân quyền chia sẻ và phục hồi dữ liệu | Access Request; ticket; bằng chứng duyệt quyền |
| ITSM-CHG-001 | [Quản lý thay đổi](procedures/07-CHANGE-MANAGEMENT.md) | Thay đổi cấu hình, dịch vụ, hạ tầng hoặc production | [Change Request](../forms/FRM-ITSM-03-Change-Request.csv) |
| ITSM-MON-001 | [Giám sát Zabbix](procedures/08-MONITORING-ZABBIX.md) | Theo dõi host, trigger, cảnh báo và sự kiện | [Monitoring Host Register](../registers/MONITORING-HOST-REGISTER.csv); Daily Checklist |
| ITSM-BKP-001 | [Backup và Restore](procedures/09-BACKUP-RESTORE.md) | Kiểm tra job backup, thử khôi phục hoặc phục hồi theo phê duyệt | [Backup/Restore Test](../forms/FRM-ITSM-05-Backup-Restore-Test.csv) |
| ITSM-SEC-001 | [Sự kiện an toàn thông tin](procedures/10-SECURITY-INCIDENT.md) | Nghi ngờ truy cập trái phép, lộ lọt dữ liệu, malware hoặc sự kiện ATTT | [Security Incident](../forms/FRM-ITSM-06-Security-Incident.csv) |
| ITSM-VND-001 | [Nhà cung cấp và license](procedures/11-VENDOR-LICENSE.md) | Hợp đồng, gia hạn, hỗ trợ bên thứ ba, license và quyền truy cập vendor | [Vendor Service Form](../forms/FRM-ITSM-07-Vendor-Service.csv); [Service/Vendor Register](../registers/SERVICE-VENDOR-REGISTER.csv) |
| ITSM-OPS-001 | [Vận hành hằng ngày](procedures/12-DAILY-OPERATIONS.md) | Checklist vận hành, ghi nhận bất thường và chuyển thành ticket khi cần | [Daily Checklist](../forms/FRM-ITSM-08-Daily-Checklist.csv) |
| ITSM-HR-001 | [Vòng đời nhân sự — Joiner/Mover/Leaver](procedures/13-USER-LIFECYCLE.md) | Nhân sự mới, đổi vai trò/bộ phận hoặc nghỉ việc | Ticket, Access Request, biên bản tài sản |
| ITSM-DOC-001 | [Kiểm soát tài liệu — quy trình hiện hữu](procedures/14-DOCUMENT-CONTROL.md) | Quản lý phiên bản và tài liệu vận hành hiện có | [Document Register](../registers/DOCUMENT-REGISTER.csv); xem thêm quy trình chi tiết tại mục 6.1 |

### 6.3. Biểu mẫu và sổ đăng ký có thể dùng trực tiếp

**Biểu mẫu CSV**
- [FRM-ITSM-01 — Ticket Fields](../forms/FRM-ITSM-01-Ticket-Fields.csv)
- [FRM-ITSM-02 — Asset Handover](../forms/FRM-ITSM-02-Asset-Handover.csv)
- [FRM-ITSM-03 — Change Request](../forms/FRM-ITSM-03-Change-Request.csv)
- [FRM-ITSM-04 — Access Request](../forms/FRM-ITSM-04-Access-Request.csv)
- [FRM-ITSM-05 — Backup/Restore Test](../forms/FRM-ITSM-05-Backup-Restore-Test.csv)
- [FRM-ITSM-06 — Security Incident](../forms/FRM-ITSM-06-Security-Incident.csv)
- [FRM-ITSM-07 — Vendor Service](../forms/FRM-ITSM-07-Vendor-Service.csv)
- [FRM-ITSM-08 — Daily Checklist](../forms/FRM-ITSM-08-Daily-Checklist.csv)

**Sổ đăng ký**
- [Danh mục công việc cần hành động](../registers/ACTION-ITEMS.csv)
- [Sổ tài sản](../registers/ASSET-REGISTER.csv)
- [Sổ kiểm soát tài liệu](../registers/DOCUMENT-REGISTER.csv)
- [Sổ host giám sát](../registers/MONITORING-HOST-REGISTER.csv)
- [Sổ nhà cung cấp/dịch vụ](../registers/SERVICE-VENDOR-REGISTER.csv)

**Tài liệu tổng hợp và đối chiếu**
- [Đánh giá 10 hồ sơ nguồn và khoảng trống](itsm/00-DANH-GIA-10-HO-SO-GOC.md)
- [Lưu đồ tổng thể ITSM](itsm/00-LUU-DO-TONG-THE-ITSM.md)
- [Danh mục cần phê duyệt trước ban hành](itsm/08-DANH-MUC-PHE-DUYET-TRUOC-BAN-HANH.md)

## 7. KPI đề xuất
| KPI | Cách tính | Chu kỳ |
|---|---|---|
| SLA Response Compliance | Ticket phản hồi trong SLA / ticket có SLA | Tuần/tháng |
| SLA Resolution Compliance | Ticket giải quyết trong SLA / ticket có SLA | Tuần/tháng |
| Overdue Backlog | Ticket quá hạn còn mở | Hằng ngày/tuần |
| Reopen Rate | Ticket mở lại / ticket đã đóng | Tháng |
| Backup Success | Job backup thành công / job đến hạn | Ngày/tháng |
| Restore Test Pass | Restore test đạt / tổng restore test | Tháng/quý |
| Asset Accuracy | Tài sản kiểm kê khớp / tổng tài sản kiểm kê | Quý |
| Monitoring Coverage | Tài sản trọng yếu có monitor / tổng tài sản trọng yếu | Tháng |
| Change Success Rate | Change không rollback / tổng change | Tháng |

Target, owner KPI và quy tắc loại trừ cần được chốt.

## 8. Quyết định cần chốt trước phát hành
1. Phạm vi nội bộ hay gồm khách hàng M-Care/M-Service và SLA theo hợp đồng.
2. Xác nhận SLA thiếu/mâu thuẫn, lịch làm việc, ngày lễ, điều kiện tạm dừng và escalation.
3. Ma trận P1–P4, định nghĩa major incident và thông báo ngoài giờ.
4. Chính sách mật khẩu/MFA/khóa tài khoản.
5. RPO/RTO, retention và lịch restore test từng dịch vụ.
6. Ngưỡng daily checklist/monitoring và lịch trực.
7. Cấu hình endpoint/software, hệ điều hành hỗ trợ, license và đơn vị ổ đĩa trong tài liệu cũ.
8. Sơ đồ as-built, IP/VLAN theo từng site.
9. Chu kỳ rà soát quyền, kiểm kê, vendor/license và tài liệu.
10. Thời hạn lưu hồ sơ, kho tài liệu chính và phân quyền phê duyệt.

## 9. Kế hoạch triển khai đề xuất
- Tuần 1: chốt owner/RACI, phạm vi, SLA và vấn đề an ninh khẩn cấp.
- Tuần 2: chuẩn hóa ticket template, asset register, access request và change request.
- Tuần 3: đối soát monitoring, daily checklist, vendor/license và backup/restore.
- Tuần 4: chạy thử, đào tạo, báo cáo baseline, khắc phục và trình phê duyệt.
