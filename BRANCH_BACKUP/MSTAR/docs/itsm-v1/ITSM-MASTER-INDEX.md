# MSTAR ITSM — DANH MỤC TÀI LIỆU CHUẨN (MASTER INDEX)

**Mã tài liệu:** MSTAR-ITSM-MASTER-INDEX-001  
**Phiên bản:** 1.1-DRAFT  
**Chủ sở hữu đề xuất:** IT Lead / ITSM Process Owner  
**Phê duyệt:** Ban Giám đốc hoặc người được ủy quyền  
**Hiệu lực:** Chỉ sau khi phê duyệt chính thức

> **Đây là LINK DUY NHẤT để bắt đầu tra cứu bộ tài liệu ITSM MSTAR.** Thứ tự dưới đây được sắp xếp theo chu trình xây dựng, vận hành và kiểm soát hệ thống quản lý CNTT. Các tài liệu hiện có vẫn là dự thảo; sự hiện diện của tài liệu không tự chứng minh hệ thống đã triển khai hiệu lực hoặc đã được chứng nhận ISO.

## 0. Cách sử dụng và thứ tự áp dụng

1. Đọc quy chế và xác định phạm vi, trách nhiệm, thẩm quyền.
2. Duyệt chính sách, danh mục dịch vụ, SLA và các tiêu chí vận hành còn thiếu.
3. Thực hiện công việc theo đúng quy trình vận hành chi tiết tại `docs/procedures/`.
4. Sử dụng biểu mẫu/checklist và ghi hồ sơ bằng chứng; cập nhật sổ đăng ký liên quan.
5. Giám sát kết quả, đo SLA/KPI, đánh giá nội bộ, xử lý CAPA và cải tiến.
6. Khi có khác biệt giữa tài liệu, không tự lựa chọn phiên bản; tạm dừng hành động rủi ro, báo chủ sở hữu tài liệu để rà soát và phê duyệt.

**Nguồn quy trình vận hành chuẩn duy nhất:** `docs/procedures/`. Thư mục `docs/itsm/` là tập tài liệu quản trị, tham chiếu, biểu mẫu/nội dung tổng hợp và đối chiếu; không phải bộ quy trình vận hành song song.

---

## 1. Quy chế, phạm vi và quản trị ITSM

| Thứ tự | Tài liệu | Mục đích / tình trạng |
|---|---|---|
| 1.1 | [Quy chế quản lý dịch vụ CNTT](docs/itsm/01-QUY-CHẾ-QUẢN-LÝ-DỊCH-VỤ-CNTT.md) | Nguyên tắc, phạm vi, vai trò, thẩm quyền và kiểm soát chung. Phải chốt phạm vi dịch vụ nội bộ/khách hàng. |
| 1.2 | [Sổ tay ITSM tổng thể](docs/ITSM-HANDBOOK.md) | Hướng dẫn tổng quan, luồng dịch vụ, KPI đề xuất và kế hoạch triển khai. |
| 1.3 | [Lưu đồ tổng thể xuyên suốt](docs/itsm/00-LUU-DO-TONG-THE-ITSM.md) | Điểm vào vận hành từ tiếp nhận đến đóng hồ sơ, báo cáo và cải tiến. |
| 1.4 | [Đánh giá 10 hồ sơ gốc và khoảng trống](docs/itsm/00-DANH-GIA-10-HO-SO-GOC.md) | Nguồn hiện có, rủi ro, mâu thuẫn và các điểm chưa đủ căn cứ. |
| 1.5 | [Danh mục quyết định cần phê duyệt trước ban hành](docs/itsm/08-DANH-MUC-PHE-DUYET-TRUOC-BAN-HANH.md) | Danh sách điểm còn phải có quyết định của chủ dịch vụ/ban lãnh đạo. |

## 1.6 Hướng dẫn triển khai và vận hành thực tế

**Tài liệu điều phối triển khai:** [MSTAR-ITSM-IMP-001 — Hướng dẫn triển khai và vận hành](docs/itsm/13-HUONG-DAN-TRIEN-KHAI-VA-VAN-HANH.md).

Tài liệu này hướng dẫn chuyển chính sách/quy trình thành cấu hình, công việc thực tế, kiểm thử, bằng chứng, đào tạo và nghiệm thu. Dùng cùng [Báo cáo đánh giá 10 hồ sơ gốc và khoảng trống](docs/itsm/00-DANH-GIA-10-HO-SO-GOC.md) và [Checklist quyết định/phê duyệt trước ban hành](docs/itsm/08-DANH-MUC-PHE-DUYET-TRUOC-BAN-HANH.md). Trạng thái hiện tại là DỰ THẢO; chưa được xem là bằng chứng triển khai hoặc phê duyệt.

## 2. Chính sách và chuẩn kiểm soát cần ban hành

Các tài liệu ở nhóm này là **chính sách/tiêu chuẩn cần có**. Nội dung đã nêu trong quy trình hoặc hồ sơ nguồn có thể dùng làm đầu vào; những chính sách chưa có thành tài liệu độc lập phải được soạn, rà soát, phê duyệt trước khi áp dụng.

| Mã/tên | Trạng thái hiện tại | Đường dẫn |
|---|---|---|
| Chính sách quản lý tài khoản, xác thực và quyền truy cập | Nội dung kiểm soát nằm trong quy trình; cần phê duyệt thành chính sách riêng hoặc xác định rõ phần quy chế có hiệu lực | [Quy trình tài khoản và quyền](docs/procedures/05-ACCESS-MANAGEMENT.md) |
| **POL-IS-01 — Phân loại thông tin, chia sẻ và lưu trữ dữ liệu** | Đã có dự thảo v1.0; chờ phê duyệt. Cần xác nhận phân loại, kho được phép và thời hạn lưu. | [Mở chính sách POL-IS-01](docs/policies/POL-IS-01-CLASSIFICATION-SHARING-RETENTION.md) |
| Chính sách sao lưu, lưu giữ và khôi phục dữ liệu | Các bước vận hành đã có; RPO/RTO, retention và lịch restore phải được chủ dịch vụ phê duyệt | [Quy trình Backup/Restore](docs/procedures/09-BACKUP-RESTORE.md) |
| Chính sách quản lý thay đổi và thay đổi khẩn cấp | Quy trình đã có; tiêu chí phân loại, người phê duyệt/CAB và hậu kiểm cần chốt | [Quy trình Change Management](docs/procedures/07-CHANGE-MANAGEMENT.md) |
| Chính sách quản lý tài sản, sử dụng và thanh lý | Quy trình đã có; chu kỳ kiểm kê và thẩm quyền thanh lý cần chốt | [Quy trình Asset Management](docs/procedures/04-ASSET-MANAGEMENT.md) |
| **POL-IT-02 — Sử dụng CNTT, cài đặt phần mềm và bản quyền** | Đã có dự thảo v1.0; chờ phê duyệt. Cần duyệt danh mục phần mềm và thẩm quyền cài đặt. | [Mở chính sách POL-IT-02](docs/policies/POL-IT-02-ACCEPTABLE-USE-SOFTWARE-LICENSING.md) |
| Chính sách ứng phó sự cố ATTT và bảo toàn bằng chứng | Quy trình ứng phó đã có; phân loại mức độ, người nhận thông báo và nghĩa vụ báo cáo cần chốt | [Quy trình Security Incident](docs/procedures/10-SECURITY-INCIDENT.md) |
| Chính sách quản lý nhà cung cấp và truy cập bên thứ ba | Nội dung vận hành đã có; đánh giá rủi ro/điều kiện hợp đồng cần phê duyệt | [Quy trình Vendor/License](docs/procedures/11-VENDOR-LICENSE.md) |
| Chính sách lưu giữ và hủy hồ sơ | Quy trình kiểm soát hồ sơ có nguyên tắc; thời hạn theo loại hồ sơ phải được phê duyệt | [Kiểm soát tài liệu, hồ sơ và bằng chứng](docs/itsm/10-KIEM-SOAT-TAI-LIEU-HO-SO-VA-BANG-CHUNG.md) |
| **POL-BCDR-03 — Liên tục dịch vụ CNTT/khôi phục thảm họa (BCP/DR)** | Đã có chính sách khung dự thảo; cần BIA, RTO/RPO được duyệt và kiểm thử runbook từng dịch vụ. | [Mở chính sách POL-BCDR-03](docs/policies/POL-BCDR-03-IT-SERVICE-CONTINUITY-DISASTER-RECOVERY.md) |
| **POL-IS-04 — Phương pháp đánh giá và xử lý rủi ro ATTT** | Đã có phương pháp dự thảo; cần phê duyệt thang điểm, ngưỡng chấp nhận và thẩm quyền. | [Mở phương pháp POL-IS-04](docs/policies/POL-IS-04-INFOSEC-RISK-ASSESSMENT-TREATMENT.md) |

## 3. Quy trình vận hành — nguồn chuẩn duy nhất

**Toàn bộ quy trình vận hành chi tiết được quản lý tại:** [Thư mục `docs/procedures/`](docs/procedures/01-INCIDENT.md).

| Thứ tự | Mã quy trình | Tên và link | Khi nào áp dụng |
|---|---|---|---|
| 3.1 | ITSM-INC-001 | [Quản lý sự cố (Incident)](docs/procedures/01-INCIDENT.md) | Dịch vụ gián đoạn/suy giảm ngoài dự kiến, khôi phục và theo dõi tái diễn |
| 3.2 | ITSM-REQ-001 | [Quản lý yêu cầu dịch vụ](docs/procedures/02-SERVICE-REQUEST.md) | Yêu cầu dịch vụ chuẩn, cấp phát và hỗ trợ |
| 3.3 | ITSM-SLA-001 | [SLA, ưu tiên và leo thang](docs/procedures/03-SLA-ESCALATION.md) | Áp SLA đã duyệt, cảnh báo hạn, escalation và báo cáo |
| 3.4 | ITSM-AST-001 | [Quản lý tài sản CNTT](docs/procedures/04-ASSET-MANAGEMENT.md) | Tiếp nhận, định danh, cấp phát, thu hồi, kiểm kê, sửa chữa |
| 3.5 | ITSM-IAM-001 | [Quản lý tài khoản và quyền truy cập](docs/procedures/05-ACCESS-MANAGEMENT.md) | Cấp/đổi/thu hồi tài khoản, quyền và quyền đặc biệt |
| 3.6 | ITSM-FS-001 | [File Server và quản lý dữ liệu](docs/procedures/06-FILE-SERVER.md) | Share, thư mục, ACL, rà soát quyền và phục hồi dữ liệu |
| 3.7 | ITSM-CHG-001 | [Quản lý thay đổi](docs/procedures/07-CHANGE-MANAGEMENT.md) | Thay đổi cấu hình, hạ tầng, ứng dụng hoặc production |
| 3.8 | ITSM-MON-001 | [Giám sát và xử lý cảnh báo Zabbix](docs/procedures/08-MONITORING-ZABBIX.md) | Đăng ký host, cảnh báo, theo dõi và xử lý sự kiện |
| 3.9 | ITSM-BKP-001 | [Sao lưu và khôi phục](docs/procedures/09-BACKUP-RESTORE.md) | Theo dõi backup, yêu cầu restore và kiểm thử phục hồi |
| 3.10 | ITSM-SEC-001 | [Sự kiện an toàn thông tin](docs/procedures/10-SECURITY-INCIDENT.md) | Phishing, malware, chiếm tài khoản, rò rỉ dữ liệu hoặc sự kiện ATTT |
| 3.11 | ITSM-VND-001 | [Nhà cung cấp, hợp đồng, domain/SSL và license](docs/procedures/11-VENDOR-LICENSE.md) | Hợp đồng, gia hạn, license và truy cập nhà cung cấp |
| 3.12 | ITSM-OPS-001 | [Kiểm tra vận hành hằng ngày](docs/procedures/12-DAILY-OPERATIONS.md) | Checklist hệ thống, phát hiện bất thường và theo dõi action |
| 3.13 | ITSM-HR-001 | [Nhân viên mới, chuyển vị trí và nghỉ việc](docs/procedures/13-USER-LIFECYCLE.md) | Joiner/Mover/Leaver, account, quyền, thiết bị và license |
| 3.14 | ITSM-DOC-001 | [Kiểm soát tài liệu, sơ đồ và tri thức](docs/procedures/14-DOCUMENT-CONTROL.md) | Tạo/sửa/duyệt tài liệu, cập nhật runbook/sơ đồ và thu hồi bản cũ |

## 4. SLA, ưu tiên, KPI và báo cáo

| Tài liệu | Mục đích / lưu ý |
|---|---|
| [SLA baseline](SLA-BASELINE.md) | Bảng tham chiếu SLA hiện có. Chỉ giá trị đã xác nhận và phê duyệt mới được cấu hình chính thức. |
| [SLA nguồn chuẩn và quy tắc tính](docs/itsm/02-SLA-NGUON-CHUAN-VA-QUY-TAC-TINH.md) | Diễn giải nguồn SLA, cách tính và các điểm còn phải chốt. |
| [SLA và leo thang — quy trình](docs/procedures/03-SLA-ESCALATION.md) | Đo, cảnh báo, chuyển cấp và báo cáo. |
| [Bộ biểu mẫu vận hành ITSM](docs/itsm/09-BO-BIEU-MAU-VAN-HANH.md) | Có mẫu theo dõi SLA và báo cáo tháng. |
| [Sổ tay ITSM — KPI đề xuất](docs/ITSM-HANDBOOK.md) | KPI chưa là mục tiêu chính thức cho tới khi có chủ sở hữu, định nghĩa, nguồn dữ liệu, chu kỳ và mục tiêu được duyệt. |

**Điểm bắt buộc cần chốt:** Response Time M-Service bị thiếu trong hồ sơ gốc; cách tính giờ/ngày làm việc, ngày nghỉ, timezone, trạng thái tạm dừng và người nhận escalation. Không tự điền các giá trị này.

## 5. Biểu mẫu, checklist và hồ sơ tác nghiệp

### 5.1. Biểu mẫu tổng hợp

- [Bộ biểu mẫu vận hành ITSM (BM-01 đến BM-10)](docs/itsm/09-BO-BIEU-MAU-VAN-HANH.md)

### 5.2. Biểu mẫu CSV có thể mở trực tiếp

| Mã | Biểu mẫu | Link |
|---|---|---|
| FRM-ITSM-01 | Ticket Fields | [Mở CSV](forms/FRM-ITSM-01-Ticket-Fields.csv) |
| FRM-ITSM-02 | Asset Handover | [Mở CSV](forms/FRM-ITSM-02-Asset-Handover.csv) |
| FRM-ITSM-03 | Change Request | [Mở CSV](forms/FRM-ITSM-03-Change-Request.csv) |
| FRM-ITSM-04 | Access Request | [Mở CSV](forms/FRM-ITSM-04-Access-Request.csv) |
| FRM-ITSM-05 | Backup/Restore Test | [Mở CSV](forms/FRM-ITSM-05-Backup-Restore-Test.csv) |
| FRM-ITSM-06 | Security Incident | [Mở CSV](forms/FRM-ITSM-06-Security-Incident.csv) |
| FRM-ITSM-07 | Vendor Service | [Mở CSV](forms/FRM-ITSM-07-Vendor-Service.csv) |
| FRM-ITSM-08 | Daily Checklist | [Mở CSV](forms/FRM-ITSM-08-Daily-Checklist.csv) |

### 5.3. Checklist và runbook

- [Checklist vận hành hằng ngày](docs/procedures/12-DAILY-OPERATIONS.md)
- [Checklist backup/restore](docs/procedures/09-BACKUP-RESTORE.md)
- [Checklist Zabbix và xử lý cảnh báo](docs/procedures/08-MONITORING-ZABBIX.md)
- [Checklist quyết định trước ban hành](docs/itsm/08-DANH-MUC-PHE-DUYET-TRUOC-BAN-HANH.md)

## 6. Sổ đăng ký, hồ sơ và bằng chứng

| Sổ đăng ký | Công dụng | Link |
|---|---|---|
| ACTION-ITEMS | Theo dõi công việc tồn, quyết định cần chốt và hành động khắc phục | [Mở CSV](registers/ACTION-ITEMS.csv) |
| ASSET-REGISTER | Danh mục tài sản và vòng đời | [Mở CSV](registers/ASSET-REGISTER.csv) |
| DOCUMENT-REGISTER | Danh mục tài liệu, phiên bản và trạng thái | [Mở CSV](registers/DOCUMENT-REGISTER.csv) |
| MONITORING-HOST-REGISTER | Danh mục host/hệ thống theo dõi | [Mở CSV](registers/MONITORING-HOST-REGISTER.csv) |
| SERVICE-VENDOR-REGISTER | Danh mục dịch vụ/nhà cung cấp | [Mở CSV](registers/SERVICE-VENDOR-REGISTER.csv) |

Quy tắc kiểm soát: hồ sơ cần có mã tham chiếu, chủ sở hữu, nguồn tạo, ngày, quyền truy cập, nơi lưu và thời hạn lưu theo chính sách đã duyệt. Không lưu mật khẩu/token/private key/recovery code trong ticket, CSV hoặc repository.

## 7. Kiểm soát tài liệu, đánh giá nội bộ và cải tiến

- [Kiểm soát tài liệu, hồ sơ và bằng chứng](docs/itsm/10-KIEM-SOAT-TAI-LIEU-HO-SO-VA-BANG-CHUNG.md)
- [Kiểm soát tài liệu — quy trình vận hành ITSM-DOC-001](docs/procedures/14-DOCUMENT-CONTROL.md)
- [Đánh giá nội bộ và CAPA](docs/itsm/12-QUY-TRINH-DOANH-GIA-NOI-BO-CAPA.md)
- [Danh mục quyết định/hành động](registers/ACTION-ITEMS.csv)

## 8. Đối chiếu tiêu chuẩn và khoảng trống

- [Bảng đối chiếu ISO/IEC 20000-1 và ISO/IEC 27001](docs/itsm/11-DOI-CHIEU-ISO-20000-1-ISO-27001.md)
- [Đánh giá 10 hồ sơ nguồn và khoảng trống](docs/itsm/00-DANH-GIA-10-HO-SO-GOC.md)
- [Các quyết định cần chốt trước ban hành](docs/itsm/08-DANH-MUC-PHE-DUYET-TRUOC-BAN-HANH.md)

**Lưu ý:** Đây là bản đồ tham chiếu để lập kế hoạch và chuẩn bị bằng chứng; không thay thế bản tiêu chuẩn chính thức, không phải tuyên bố phù hợp và không đồng nghĩa MSTAR đã được chứng nhận.

## 9. Danh mục cần bổ sung/triển khai và xác minh trước khi tuyên bố hệ thống hoàn chỉnh

Bản đánh giá chi tiết theo 10 hồ sơ gốc và 23 nhóm khoảng trống nằm tại [Đánh giá 10 hồ sơ nguồn và kế hoạch khắc phục](docs/itsm/00-DANH-GIA-10-HO-SO-GOC.md). Checklist phê duyệt và bằng chứng nghiệm thu nằm tại [Danh mục quyết định và checklist ban hành](docs/itsm/08-DANH-MUC-PHE-DUYET-TRUOC-BAN-HANH.md).

| Nhóm ưu tiên | Khoảng trống chính | Tài liệu / đầu ra cần có |
|---|---|---|
| **P1 — Rủi ro tức thời** | Bí mật xác thực trong file bàn giao/danh mục; quyền PUBLIC/File Server; tình trạng backup và cảnh báo trọng yếu | Kiểm tra/rotation secret nếu cần; ACL export và change được duyệt; backup status, owner, ticket và bằng chứng xử lý | 
| **P1 — Quản trị** | Phạm vi SMS/danh mục dịch vụ, SLA M-Service còn thiếu, ma trận P1–P4/Major Incident, escalation và thẩm quyền | Scope được duyệt; Service Catalogue; SLA signed-off; ma trận escalation/RACI; kiểm thử cấu hình Bitrix | 
| **P1 — ATTT** | Phân loại dữ liệu, risk register, quyền truy cập thực tế, chính sách sử dụng CNTT và software inventory | POL-IS-01, POL-IS-04, POL-IT-02 được duyệt; data/software inventory; access review; risk treatment evidence | 
| **P1 — Liên tục** | BIA/RTO/RPO, BCP/DR theo dịch vụ và kiểm thử khôi phục | POL-BCDR-03 được duyệt; BIA; RTO/RPO; runbook từng dịch vụ; backup/restore/DR test và CAPA | 
| **P2 — Kiểm kê và cấu hình** | Asset register đối soát thực tế, taxonomy thống nhất, as-built network/CI, monitoring matrix | Biên bản kiểm kê; danh mục chuẩn; sơ đồ site có version/owner; host/trigger/recipient matrix; test alert | 
| **P2 — Nhà cung cấp và bản quyền** | Register vendor/license/domain/SSL có dữ liệu thật, gia hạn và quyền vendor | Vendor/license reconciliation; hợp đồng/renewal evidence; software approved catalog; vendor access review | 
| **P2 — Năng lực và đảm bảo** | Đào tạo, hồ sơ vận hành qua nhiều kỳ, audit độc lập, management review và KPI baseline | Training records; ticket/SLA/change/access/backup samples; audit report; CAPA verification; management review minutes; KPI dictionary/report | 
| **P2 — Hồ sơ** | Retention schedule, kho lưu chính thức, quyền truy cập, hủy an toàn, kiểm soát bản lỗi thời | Chính sách/lịch lưu được duyệt; Document Register; bằng chứng truy xuất/hủy và thu hồi tài liệu cũ | 

Không nâng trạng thái mục nào lên “Đã triển khai/Đã kiểm chứng” chỉ dựa trên file dự thảo. Điều kiện đóng từng khoảng trống: tài liệu phê duyệt (nếu yêu cầu), owner được giao, cấu hình/thao tác thực tế, bằng chứng có ngày và người thực hiện, kết quả được rà soát, hành động được đóng hoặc residual risk được chấp nhận đúng thẩm quyền.

## 10. Điều kiện ban hành

Chỉ chuyển từ DRAFT sang APPROVED khi phạm vi/chủ sở hữu được chốt; SLA và thẩm quyền còn thiếu được phê duyệt; chính sách liên quan được ban hành hoặc xác định rõ tài liệu thay thế có hiệu lực; biểu mẫu/nơi lưu hồ sơ được xác định; quy trình trọng yếu được chạy thử; người có thẩm quyền ký duyệt; và master index được cập nhật theo phiên bản ban hành.

**Trạng thái hiện tại: DỰ THẢO CÓ KIỂM SOÁT — CHƯA BAN HÀNH.**
