# POL-BCDR-03 — CHÍNH SÁCH LIÊN TỤC DỊCH VỤ CNTT VÀ KHÔI PHỤC THẢM HỌA (BCP/DR)

**Mã tài liệu:** POL-BCDR-03 | **Phiên bản:** 1.0-DRAFT | **Chủ sở hữu:** IT Lead / Service Owner | **Phê duyệt:** Ban Giám đốc/người được ủy quyền

> **Trạng thái: DỰ THẢO.** Đây là chính sách khung, không thay thế runbook khôi phục riêng cho từng dịch vụ. Không tự đặt RTO/RPO; các mục tiêu phải được chủ dịch vụ và Ban Giám đốc phê duyệt dựa trên tác động kinh doanh.

## 1. Mục đích
Đảm bảo dịch vụ CNTT trọng yếu có phương án duy trì ở mức chấp nhận được khi xảy ra sự cố lớn, và có kế hoạch phục hồi có kiểm thử để giảm thời gian gián đoạn, mất dữ liệu và tác động tới khách hàng/hoạt động kinh doanh.

## 2. Phạm vi
Áp dụng cho dịch vụ, hạ tầng, dữ liệu, mạng, máy chủ/VM, NAS/File Server, hệ thống định danh, email, ứng dụng nghiệp vụ, backup, nhà cung cấp cloud/Internet và các phụ thuộc được đưa vào phạm vi quản lý. Danh mục thực tế phải được xác nhận trong Service Catalogue và CMDB/asset register.

## 3. Nguyên tắc
1. Phân loại mức độ trọng yếu dựa trên tác động kinh doanh, khách hàng, pháp lý, an toàn và phụ thuộc kỹ thuật.
2. Mỗi dịch vụ trọng yếu phải có Service Owner, chủ kỹ thuật, phụ thuộc, kênh liên lạc và phương án khôi phục được ghi lại.
3. RTO (thời gian mục tiêu khôi phục) và RPO (mức mất dữ liệu tối đa theo thời gian) phải được đánh giá và phê duyệt; không suy ra từ khả năng backup hiện tại.
4. Bản sao lưu không được coi là có thể khôi phục cho đến khi có bằng chứng kiểm thử phù hợp.
5. Kế hoạch phải xem xét mất điện, hỏng thiết bị, lỗi cấu hình, lỗi cập nhật, mã độc/ransomware, mất kết nối, lỗi cloud/nhà cung cấp và mất địa điểm.
6. Bảo đảm độc lập hợp lý giữa môi trường production và bản backup; bảo vệ thông tin xác thực backup, quyền truy cập và tính toàn vẹn của bản sao.

## 4. Quản trị và kích hoạt
BCP/DR có thể được kích hoạt khi sự cố vượt khả năng xử lý thường lệ, dịch vụ trọng yếu gián đoạn kéo dài, có nghi ngờ mất dữ liệu/mã độc, mất địa điểm hoặc theo quyết định của người chỉ huy sự cố. Incident Manager/IT Lead ghi nhận thời điểm kích hoạt, phạm vi, quyết định, người tham gia và kênh liên lạc. Khi liên quan ATTT, phối hợp [ITSM-SEC-001](../procedures/10-SECURITY-INCIDENT.md).

## 5. Vòng đời lập kế hoạch
1. Xác định dịch vụ, chủ sở hữu, người dùng, dữ liệu và phụ thuộc.
2. Thực hiện Business Impact Analysis (BIA): tác động theo thời gian, mức ưu tiên phục hồi, nghĩa vụ khách hàng và yêu cầu pháp lý.
3. Đánh giá rủi ro và lựa chọn chiến lược: dự phòng, khôi phục từ backup, thiết bị thay thế, site/cloud thay thế hoặc phương án thủ công.
4. Chủ dịch vụ đề xuất RTO/RPO và mức dịch vụ tối thiểu; Ban Giám đốc/người được ủy quyền phê duyệt ngân sách và mục tiêu.
5. Viết runbook theo dịch vụ: điều kiện kích hoạt, liên hệ, thứ tự phục hồi, quyền cần thiết, bước kiểm tra, rollback và điều kiện thông báo khôi phục.
6. Bảo đảm bản backup, cấu hình, license, khóa mã hóa và tài khoản khẩn cấp được bảo vệ và có thể truy cập trong tình huống khẩn cấp.
7. Thử nghiệm theo kế hoạch, ghi kết quả, sai lệch, thời gian thực tế và hành động khắc phục.
8. Cập nhật sau thay đổi lớn, sự cố thực tế, thay đổi nhà cung cấp hoặc kết quả thử nghiệm không đạt.

## 6. Quy tắc khi khôi phục
- Ưu tiên phục hồi theo thứ tự đã được phê duyệt và quan hệ phụ thuộc; không khởi động lại mù quáng gây hư hỏng dữ liệu.
- Với nghi ngờ ransomware/compromise, cô lập và bảo toàn bằng chứng; không phục hồi vào môi trường chưa được xác nhận an toàn.
- Xác minh tính toàn vẹn, dữ liệu, quyền truy cập, kết nối, giám sát và chức năng nghiệp vụ trước khi tuyên bố dịch vụ sẵn sàng.
- Service Owner/đại diện nghiệp vụ xác nhận chấp nhận dịch vụ; ghi rõ dữ liệu thiếu, giới hạn còn lại và thông báo liên quan.
- Mọi thay đổi khẩn cấp phải được ghi lại và hậu kiểm theo quy trình quản lý thay đổi.

## 7. Kiểm thử và đo lường
Loại kiểm thử có thể gồm walkthrough/tabletop, kiểm tra tài liệu/liên hệ, restore file/DB, khôi phục máy ảo, chuyển đổi dự phòng hoặc diễn tập toàn dịch vụ. Chọn loại phù hợp mức rủi ro; không thực hiện thử nghiệm gây gián đoạn production nếu chưa có phê duyệt và kế hoạch an toàn.

Chỉ số đề xuất: tỷ lệ dịch vụ trọng yếu có runbook được duyệt; tỷ lệ backup thành công; tỷ lệ kiểm thử restore đạt; thời gian phục hồi thực tế so với RTO; mức mất dữ liệu thực tế so với RPO; số hành động khắc phục quá hạn. Mục tiêu và chu kỳ báo cáo cần được phê duyệt.

## 8. Vai trò
| Vai trò | Trách nhiệm |
|---|---|
| Ban Giám đốc/người được ủy quyền | Phê duyệt ưu tiên kinh doanh, RTO/RPO, ngân sách và kích hoạt ở cấp nghiêm trọng |
| Service Owner | BIA, xác nhận mục tiêu và nghiệm thu dịch vụ sau phục hồi |
| IT Lead/Incident Manager | Điều phối kế hoạch, quyết định kỹ thuật trong thẩm quyền, liên lạc và báo cáo |
| IT System/Network/Application Owner | Duy trì runbook, backup, cấu hình và thực hiện phục hồi |
| Nhà cung cấp | Thực hiện nghĩa vụ phục hồi và cung cấp bằng chứng theo hợp đồng |
| Người dùng/đơn vị nghiệp vụ | Thực hiện phương án làm việc tạm thời và xác nhận chức năng nghiệp vụ |

## 9. Hồ sơ bắt buộc
- Danh mục dịch vụ trọng yếu và phụ thuộc.
- BIA, đánh giá rủi ro, phê duyệt RTO/RPO và chiến lược phục hồi.
- Runbook/communication tree, danh sách liên hệ và phương án thủ công.
- Báo cáo backup, restore test, diễn tập, thời gian thực tế, lỗi và CAPA.
- Biên bản kích hoạt, nhật ký quyết định, thông báo và xác nhận nghiệp vụ.
- Liên kết: [ITSM-BKP-001](../procedures/09-BACKUP-RESTORE.md), [ITSM-INC-001](../procedures/01-INCIDENT.md), [ITSM-CHG-001](../procedures/07-CHANGE-MANAGEMENT.md), [FRM-ITSM-05 Backup/Restore Test](../../forms/FRM-ITSM-05-Backup-Restore-Test.csv).

## 10. Kế hoạch triển khai trước ban hành
1. Lập danh mục dịch vụ và xếp hạng trọng yếu cùng chủ dịch vụ.
2. Thực hiện BIA và xác nhận phụ thuộc.
3. Trình duyệt RTO/RPO; ghi rõ các dịch vụ chưa có khả năng đạt mục tiêu.
4. Hoàn thiện runbook và liên lạc khẩn cấp.
5. Lập lịch kiểm thử, tiêu chí đạt/không đạt và quy trình CAPA.
6. Xác nhận ngân sách, người chịu trách nhiệm, phương án nhà cung cấp và phê duyệt chính thức.