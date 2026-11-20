# POL-IT-02 — CHÍNH SÁCH SỬ DỤNG CNTT, CÀI ĐẶT PHẦN MỀM VÀ BẢN QUYỀN

**Mã tài liệu:** POL-IT-02 | **Phiên bản:** 1.0-DRAFT | **Chủ sở hữu:** IT Lead | **Phê duyệt:** Ban Giám đốc/người được ủy quyền

> **Trạng thái: DỰ THẢO — chưa có hiệu lực cho đến khi được phê duyệt.** Danh mục phần mềm được phép, quy trình ngoại lệ và thẩm quyền mua sắm cần được MSTAR xác nhận.

## 1. Mục đích và phạm vi
Quy định việc sử dụng thiết bị, tài khoản, mạng, email, dữ liệu, phần mềm và dịch vụ cloud của MSTAR nhằm giảm rủi ro an ninh, mất dữ liệu, vi phạm bản quyền và gián đoạn dịch vụ. Áp dụng cho nhân viên, cộng tác viên, nhà cung cấp và người được cấp quyền sử dụng tài nguyên CNTT.

## 2. Nguyên tắc sử dụng chấp nhận được
1. Tài nguyên CNTT được sử dụng chủ yếu cho công việc được giao và theo quy định công ty.
2. Người dùng phải bảo vệ tài khoản, bật MFA khi được hỗ trợ, khóa màn hình khi rời máy và không chia sẻ thông tin xác thực.
3. Không truy cập, sao chép, sửa đổi hoặc tiết lộ dữ liệu khi không được phép; không vượt quyền hoặc né biện pháp bảo mật.
4. Không cài, chạy hoặc kết nối thiết bị/dịch vụ có nguy cơ làm ảnh hưởng hệ thống nếu chưa được IT đánh giá/cho phép.
5. Không dùng tài nguyên công ty cho hành vi trái pháp luật, phát tán mã độc, spam, xâm nhập trái phép hoặc hoạt động làm tổn hại công ty/khách hàng.
6. Không tự ý tắt antivirus/EDR, firewall, mã hóa, cập nhật hoặc công cụ quản trị; không cắm USB/thiết bị lưu trữ không rõ nguồn gốc.
7. Không đưa dữ liệu công ty lên AI công cộng, dịch vụ chia sẻ, kho code hoặc tài khoản cá nhân nếu chưa được phê duyệt theo phân loại dữ liệu.
8. Người dùng phải báo Service Desk khi phát hiện mất thiết bị, email đáng ngờ, phần mềm lạ hoặc hành vi bất thường.

## 3. Cài đặt và thay đổi phần mềm
1. Phần mềm phải có nguồn tải chính thức/được tin cậy, phù hợp mục đích công việc và được IT kiểm tra tương thích, bảo mật, yêu cầu dữ liệu và giấy phép.
2. Người dùng không tự cài phần mềm trên thiết bị được quản lý nếu chính sách quản trị yêu cầu IT thực hiện; yêu cầu được gửi qua ticket.
3. IT kiểm tra nhu cầu, quyền quản trị, license, nguồn cài đặt, nhà phát triển, chữ ký/hash khi phù hợp, cảnh báo bảo mật, quyền truy cập và ảnh hưởng vận hành.
4. Phần mềm máy chủ/production, agent bảo mật, driver, tiện ích truy cập từ xa và thay đổi ảnh hưởng diện rộng phải đi theo [ITSM-CHG-001](../procedures/07-CHANGE-MANAGEMENT.md).
5. Phần mềm không còn hỗ trợ, có lỗ hổng nghiêm trọng hoặc không còn nhu cầu phải có kế hoạch cập nhật/gỡ bỏ được phê duyệt.
6. Không cài phần mềm bẻ khóa, keygen, crack, license không rõ nguồn, hoặc phần mềm vượt phạm vi quyền sử dụng.

## 4. Quản lý bản quyền và đăng ký
- IT duy trì danh mục phần mềm/license, phiên bản, số lượng/seat, chủ sở hữu, đơn vị sử dụng, nhà cung cấp, thời hạn, bằng chứng mua/đăng ký và điều kiện gia hạn.
- Trước khi cấp license mới, kiểm tra license còn khả dụng, điều khoản sử dụng, người dùng/thiết bị được phép và chi phí.
- Không chia sẻ key/license trái điều khoản; không dùng tài khoản cá nhân để đứng tên tài sản công ty nếu không được phê duyệt.
- Theo dõi ngày hết hạn, số lượng sử dụng và điều kiện gia hạn; thu hồi license khi người dùng nghỉ việc hoặc không còn nhu cầu.
- Phần mềm miễn phí/open-source vẫn phải rà soát điều khoản license, nghĩa vụ thông báo/ghi nhận và hạn chế sử dụng thương mại nếu có.

## 5. Tài khoản, thiết bị và giám sát
Thiết bị và phần mềm được quản lý phải được ghi nhận trong danh mục tài sản phù hợp. IT có thể thu thập log bảo mật/vận hành theo chính sách và pháp luật áp dụng, với quyền truy cập giới hạn và mục đích rõ ràng. Chính sách này không cho phép theo dõi tùy tiện hoặc truy cập dữ liệu cá nhân ngoài phạm vi được phép.

## 6. Ngoại lệ
Yêu cầu ngoại lệ phải nêu lý do, hệ thống/thiết bị, phần mềm, thời hạn, đánh giá rủi ro, biện pháp giảm thiểu và người chịu trách nhiệm. IT Lead đánh giá; ngoại lệ rủi ro cao hoặc liên quan chi phí/dữ liệu nhạy cảm cần người có thẩm quyền phê duyệt. Không được ngoại lệ đối với hành vi vi phạm pháp luật hoặc license.

## 7. Vi phạm và phản ứng
IT có thể cách ly thiết bị/tài khoản khi có căn cứ rủi ro tức thời, đồng thời thông báo chủ quản và lưu bằng chứng. Sự cố bảo mật thực hiện theo [ITSM-SEC-001](../procedures/10-SECURITY-INCIDENT.md); thay đổi kỹ thuật theo [ITSM-CHG-001](../procedures/07-CHANGE-MANAGEMENT.md); tài sản theo [ITSM-AST-001](../procedures/04-ASSET-MANAGEMENT.md). Xử lý nhân sự theo quy định công ty và pháp luật áp dụng.

## 8. Trách nhiệm
| Vai trò | Trách nhiệm |
|---|---|
| Ban Giám đốc/người được ủy quyền | Phê duyệt chính sách, ngân sách và ngoại lệ rủi ro cao |
| IT Lead | Duy trì danh mục phần mềm, tiêu chuẩn kỹ thuật, rà soát license và báo cáo |
| Service Desk/IT System | Xử lý ticket, kiểm tra nguồn, cài đặt theo quyền hạn và cập nhật hồ sơ |
| Quản lý bộ phận | Xác nhận nhu cầu nghiệp vụ và người sử dụng |
| Người dùng | Tuân thủ chính sách, không tự ý cài phần mềm trái phép, báo cáo bất thường |
| Nhà cung cấp | Tuân thủ điều khoản hợp đồng, phạm vi truy cập và yêu cầu bảo mật |

## 9. Hồ sơ liên quan
- [ITSM-REQ-001 — Yêu cầu dịch vụ](../procedures/02-SERVICE-REQUEST.md)
- [ITSM-CHG-001 — Quản lý thay đổi](../procedures/07-CHANGE-MANAGEMENT.md)
- [ITSM-AST-001 — Quản lý tài sản](../procedures/04-ASSET-MANAGEMENT.md)
- [ITSM-VND-001 — Nhà cung cấp/license](../procedures/11-VENDOR-LICENSE.md)
- [FRM-ITSM-04 — Yêu cầu cấp quyền](../../forms/FRM-ITSM-04-Access-Request.csv)

## 10. Xem xét trước ban hành
Chốt danh mục phần mềm được phép, ai có quyền cài trên máy trạm/server, quy trình mua sắm, bằng chứng license, thời hạn lưu hồ sơ, cơ chế kiểm kê và phạm vi log/giám sát. Xem xét tối thiểu hằng năm và khi có sự cố hoặc thay đổi công nghệ lớn.