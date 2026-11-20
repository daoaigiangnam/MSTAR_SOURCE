# BẢNG ĐỐI CHIẾU ITSM MSTAR VỚI ISO/IEC 20000-1 VÀ ISO/IEC 27001

**Mã:** MSTAR-ITSM-MAP-001 | **Phiên bản:** 0.1-DRAFT  
**Mục đích:** Bản đồ tham chiếu để lập kế hoạch triển khai và đánh giá nội bộ; không phải tuyên bố phù hợp hoặc chứng nhận ISO.

## 1. Nguyên tắc sử dụng
- Có tài liệu không đồng nghĩa đã triển khai hiệu lực. Cần kiểm tra hồ sơ vận hành, phỏng vấn và quan sát thực tế.
- Cần rà soát với bản tiêu chuẩn chính thức mà MSTAR sử dụng; tài liệu này không thay thế tiêu chuẩn được cấp phép.

## 2. Bản đồ chủ đề ISO/IEC 20000-1
| Chủ đề | Tài liệu MSTAR | Bằng chứng cần có | Trạng thái sơ bộ |
|---|---|---|---|
| Bối cảnh, phạm vi hệ thống quản lý dịch vụ | 01; cần bổ sung danh mục dịch vụ | Phạm vi SMS, bên liên quan, dịch vụ, phụ thuộc | Chưa đủ dữ liệu |
| Lãnh đạo, chính sách, trách nhiệm | 01; 08; 10 | Chính sách duyệt, vai trò, ma trận phê duyệt | Dự thảo |
| Hoạch định, rủi ro, mục tiêu, cải tiến | 01; quy trình audit/CAPA | Risk register, mục tiêu, hành động | Cần xây dựng bằng chứng |
| Năng lực, nhận thức, truyền thông, tài liệu | 10 | Đào tạo, năng lực, truyền thông, kiểm soát tài liệu | Dự thảo |
| Kiểm soát vận hành dịch vụ | 03–07; 09 | Ticket, tài sản, quyền, change, monitoring, backup, vendor | Dự thảo |
| Danh mục dịch vụ và SLA | 02; 03 | Danh mục dịch vụ, SLA duyệt, báo cáo hiệu suất | Chưa đủ |
| Thiết kế/chuyển tiếp/thay đổi dịch vụ | 06 | Change, đánh giá rủi ro, test, rollback, nghiệm thu | Dự thảo |
| Sự cố, yêu cầu, vấn đề, liên tục dịch vụ | 03; 06 | Ticket, RCA/problem record, kế hoạch liên tục, thử khôi phục | Cần bổ sung |
| Tài sản/cấu hình và nhà cung cấp | 04; 07; sổ đăng ký | Asset register/CMDB, đối soát, hồ sơ vendor | Cần đối chiếu |
| Đo lường và đánh giá | 02; 06; 07; 09 | KPI, nguồn dữ liệu, báo cáo định kỳ | Chưa có mục tiêu duyệt |
| Đánh giá nội bộ và xem xét lãnh đạo | Quy trình audit/CAPA | Chương trình audit, báo cáo, biên bản xem xét | Chưa có bằng chứng |
| Không phù hợp và hành động khắc phục | Quy trình audit/CAPA | NCR/CAPA register, xác minh hiệu lực | Dự thảo |

## 3. Bản đồ chủ đề ISO/IEC 27001
| Chủ đề kiểm soát | Tài liệu liên quan | Bằng chứng thực tế cần thu thập | Trạng thái |
|---|---|---|---|
| Chính sách và trách nhiệm ATTT | 01; 07 | Chính sách, vai trò, phân quyền | Cần phê duyệt |
| Quản lý tài sản và thông tin | 04; 05 | Danh mục, chủ sở hữu, phân loại | Cần đối soát |
| Danh tính, xác thực, quyền truy cập | 05 | Cấp/thu hồi, rà soát quyền, log | Dự thảo |
| Nhà cung cấp và bên thứ ba | 07 | Đánh giá rủi ro, hợp đồng, quyền truy cập, dữ liệu | Cần thu thập |
| Sự cố ATTT | 07 | Timeline, bằng chứng, phân loại, bài học | Dự thảo |
| Backup, dự phòng, sẵn sàng | 06 | Log backup, restore test, BCP/DR, RPO/RTO | Chưa đủ mục tiêu duyệt |
| Logging, monitoring | 06; 07 | Zabbix, quyền log, thời gian lưu, cảnh báo | Cần kiểm tra cấu hình |
| Thay đổi và cấu hình | 06 | Change records, baseline, test, rollback | Dự thảo |
| An ninh vật lý/môi trường | 04; checklist 3.a | Kiểm soát phòng máy, ra vào, điện, nhiệt độ | Cần kiểm tra thực địa |
| Tuân thủ, đánh giá, cải tiến | 08; quy trình audit/CAPA | Đánh giá rủi ro, audit/CAPA, SoA nếu ISMS áp dụng | Chưa đủ bằng chứng |

## 4. Hồ sơ cần hoàn thiện trước khi tự đánh giá
1. Phạm vi SMS/ISMS, địa điểm, dịch vụ, hệ thống và ngoại lệ.
2. Danh mục dịch vụ, chủ sở hữu, người dùng, phụ thuộc và nhà cung cấp.
3. Ma trận trách nhiệm, phân quyền và người thay thế.
4. Risk register; với ISMS, phương pháp đánh giá/xử lý rủi ro và Statement of Applicability khi áp dụng.
5. KPI, phương pháp đo, chủ sở hữu, kỳ báo cáo và kết quả.
6. Bằng chứng vận hành qua nhiều kỳ: ticket, SLA, change, access review, asset inventory, backup/restore, monitoring, vendor review.
7. Chương trình đánh giá nội bộ, năng lực/độc lập người đánh giá, CAPA và xác minh hiệu lực.
8. Biên bản xem xét lãnh đạo và quyết định về nguồn lực/cải tiến.
9. Kế hoạch liên tục dịch vụ/khôi phục thảm họa đã duyệt và kết quả thử nghiệm.
10. Hồ sơ đào tạo/nhận thức và năng lực nhân sự.

## 5. Kết luận
MSTAR hiện có một số hồ sơ vận hành nền tảng và bộ quy trình dự thảo. Chưa đủ bằng chứng để kết luận phù hợp ISO/IEC 20000-1 hoặc ISO/IEC 27001. Chỉ đánh giá phù hợp sau khi xác định phạm vi, hoàn thiện tài liệu, triển khai thực tế và kiểm tra bằng chứng.
