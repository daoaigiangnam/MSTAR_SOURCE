# POL-IS-01 — CHÍNH SÁCH PHÂN LOẠI THÔNG TIN, CHIA SẺ VÀ LƯU TRỮ DỮ LIỆU

**Mã tài liệu:** POL-IS-01 | **Phiên bản:** 1.0-DRAFT | **Chủ sở hữu:** IT Lead phối hợp chủ dữ liệu | **Phê duyệt:** Ban Giám đốc/người được ủy quyền

> **Trạng thái: DỰ THẢO — chưa có hiệu lực cho đến khi được phê duyệt.** Các thời hạn lưu cụ thể, yêu cầu pháp lý/ngành và danh sách kho dữ liệu phải được MSTAR xác nhận trước ban hành.

## 1. Mục đích
Đảm bảo thông tin được phân loại theo mức độ nhạy cảm, chỉ chia sẻ cho đúng đối tượng, được lưu ở nơi được phê duyệt, có thể truy xuất khi cần và được hủy an toàn khi hết thời hạn.

## 2. Phạm vi
Áp dụng cho nhân viên, cộng tác viên, nhà cung cấp và bên thứ ba được cấp quyền truy cập thông tin do MSTAR quản lý; bao gồm giấy tờ, email, file, cơ sở dữ liệu, thiết bị đầu cuối, máy chủ, NAS/File Server, dịch vụ cloud, bản sao lưu, ticket và hồ sơ giấy.

## 3. Phân loại thông tin
| Mức | Ví dụ tham khảo | Yêu cầu tối thiểu |
|---|---|---|
| Công khai | Nội dung đã được phê duyệt công bố | Chỉ công bố qua kênh/người được ủy quyền; kiểm tra nội dung trước khi phát hành |
| Nội bộ | Quy trình nội bộ, tài liệu vận hành không nhạy cảm | Chỉ chia sẻ cho người có nhu cầu công việc; lưu tại kho do công ty quản lý |
| Mật | Hợp đồng, dữ liệu khách hàng/nhân sự, báo cáo kinh doanh, sơ đồ hạ tầng chi tiết | Cần chủ dữ liệu phê duyệt quyền; hạn chế tải xuống/chuyển tiếp; ghi nhận việc chia sẻ bên ngoài |
| Hạn chế đặc biệt | Mật khẩu, token, private key, recovery code, dữ liệu xác thực, dữ liệu đặc biệt nhạy cảm | Không đưa vào Git, ticket, bảng tính thông thường hoặc email không bảo vệ; dùng kho bí mật/secret manager được duyệt; cấp quyền tối thiểu và ghi log |

Chủ dữ liệu phải xác nhận mức phân loại khi tạo hoặc tiếp nhận thông tin. Nếu chưa rõ, xử lý tạm theo mức cao hơn hợp lý và hỏi chủ dữ liệu; không tự hạ mức phân loại.

## 4. Quy tắc chia sẻ và truyền dữ liệu
1. Chỉ chia sẻ theo nguyên tắc need-to-know và least privilege; xác minh danh tính/người nhận trước khi gửi.
2. Chia sẻ dữ liệu Mật hoặc Hạn chế đặc biệt ra ngoài công ty phải được chủ dữ liệu và người có thẩm quyền phê duyệt; xác định mục đích, phạm vi, người nhận, kênh và thời hạn truy cập.
3. Dùng nền tảng/kênh do công ty phê duyệt; bật MFA khi có; dùng liên kết có hạn, giới hạn người nhận và thu hồi quyền khi hết nhu cầu.
4. Không dùng email cá nhân, kho cloud cá nhân, ứng dụng nhắn tin cá nhân hoặc thiết bị lưu trữ không được phép để chuyển dữ liệu công ty, trừ ngoại lệ được phê duyệt và có biện pháp bảo vệ.
5. Không gửi mật khẩu cùng kênh với tài khoản/đường dẫn. Không ghi secret vào ticket, source code, repo, log hoặc biểu mẫu công khai.
6. Chuyển dữ liệu cho nhà cung cấp phải có căn cứ công việc/hợp đồng, phê duyệt, giới hạn dữ liệu và yêu cầu bảo mật phù hợp.
7. Chia sẻ công khai phải được người được ủy quyền duyệt; kiểm tra metadata, dữ liệu cá nhân, thông tin nội bộ và thông tin bí mật trước phát hành.

## 5. Lưu trữ, sao lưu và thời hạn lưu
1. Lưu dữ liệu tại hệ thống/kho được MSTAR phê duyệt, có chủ sở hữu, phân quyền và phương án sao lưu phù hợp mức phân loại.
2. Chủ dữ liệu xác định mục đích, thời hạn lưu và căn cứ nghiệp vụ/pháp lý; IT triển khai kỹ thuật và ghi nhận vị trí lưu.
3. Lập lịch lưu giữ theo từng nhóm hồ sơ: ticket, tài sản, hợp đồng, nhân sự, log, backup, hồ sơ bảo mật và hồ sơ phê duyệt. Không tự đặt thời hạn nếu chưa có căn cứ/đồng ý của chủ sở hữu.
4. Khi hết thời hạn, chủ dữ liệu phê duyệt xóa/hủy; IT thực hiện phương pháp phù hợp phương tiện lưu trữ, lưu bằng chứng. Nếu có tranh chấp, điều tra hoặc yêu cầu bảo toàn, phải tạm ngừng hủy theo chỉ đạo hợp lệ.
5. Bản sao lưu được bảo vệ tương đương dữ liệu gốc; quyền truy cập và việc khôi phục phải được kiểm soát. Xóa bản gốc không đồng nghĩa bản backup đã bị xóa ngay; chu kỳ hết hạn backup phải được công bố.

## 6. Quyền truy cập và rà soát
- Quyền được cấp theo vai trò và nhu cầu công việc, có yêu cầu/phê duyệt truy vết được.
- Chủ dữ liệu rà soát quyền định kỳ và khi nhân sự chuyển vị trí/nghỉ việc hoặc thay đổi hợp đồng; IT thu hồi quyền không còn cần thiết.
- Truy cập đặc quyền phải giới hạn, có xác thực mạnh và log phù hợp; tài khoản dùng chung phải tránh, nếu buộc dùng thì phải có kiểm soát bù và truy vết.

## 7. Sự cố và ngoại lệ
Nếu gửi nhầm, mất thiết bị, lộ dữ liệu hoặc truy cập trái phép: báo ngay Service Desk/đầu mối ATTT, không tự xóa log/bằng chứng; áp dụng [ITSM-SEC-001](../procedures/10-SECURITY-INCIDENT.md). Ngoại lệ phải có lý do, chủ sở hữu, đánh giá rủi ro, biện pháp bù, thời hạn và người phê duyệt; ngoại lệ hết hạn phải được đóng hoặc gia hạn có phê duyệt.

## 8. Vai trò
| Vai trò | Trách nhiệm |
|---|---|
| Ban Giám đốc/người được ủy quyền | Phê duyệt chính sách và ngoại lệ rủi ro cao |
| Chủ dữ liệu/Service Owner | Phân loại, xác định người được truy cập, mục đích và thời hạn lưu |
| IT Lead/ATTT | Triển khai kiểm soát kỹ thuật, ghi log, sao lưu, rà soát và báo cáo vi phạm |
| Người sử dụng | Tuân thủ phân loại, chia sẻ đúng đối tượng, báo cáo sự cố |
| Nhà cung cấp | Chỉ xử lý dữ liệu theo phạm vi hợp đồng và hướng dẫn được duyệt |

## 9. Bằng chứng và biểu mẫu
- Yêu cầu cấp/đổi quyền: [FRM-ITSM-04 Access Request](../../forms/FRM-ITSM-04-Access-Request.csv).
- Sự cố dữ liệu/ATTT: [FRM-ITSM-06 Security Incident](../../forms/FRM-ITSM-06-Security-Incident.csv).
- Quy trình liên quan: [File Server](../procedures/06-FILE-SERVER.md), [Access Management](../procedures/05-ACCESS-MANAGEMENT.md), [Backup/Restore](../procedures/09-BACKUP-RESTORE.md), [Document Control](../procedures/14-DOCUMENT-CONTROL.md).
- Lưu bằng chứng phê duyệt chia sẻ, danh mục kho dữ liệu, rà soát quyền, xóa/hủy và ngoại lệ tại kho hồ sơ được duyệt; không commit dữ liệu nhạy cảm lên Git.

## 10. Tuân thủ và xem xét
IT Lead phối hợp chủ dữ liệu xem xét tối thiểu hằng năm và sau sự cố/thay đổi lớn. Vi phạm được ghi nhận, đánh giá tác động, xử lý theo quy trình sự cố và CAPA. Trước ban hành cần chốt: phân loại chính thức, nhóm dữ liệu cá nhân/khách hàng, kho được phép, thời hạn lưu theo hồ sơ, người phê duyệt và yêu cầu pháp lý áp dụng.