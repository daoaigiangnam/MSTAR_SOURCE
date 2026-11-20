# POL-IS-04 — PHƯƠNG PHÁP ĐÁNH GIÁ VÀ XỬ LÝ RỦI RO AN TOÀN THÔNG TIN

**Mã tài liệu:** POL-IS-04 | **Phiên bản:** 1.0-DRAFT | **Chủ sở hữu:** IT Lead/đầu mối ATTT | **Phê duyệt:** Ban Giám đốc/người được ủy quyền

> DỰ THẢO. Thang điểm, ngưỡng chấp nhận, chu kỳ đánh giá và thẩm quyền chấp nhận rủi ro phải được MSTAR phê duyệt trước khi áp dụng chính thức.

## 1. Mục đích và phạm vi
Thiết lập cách thống nhất để nhận diện, phân tích, đánh giá, xử lý và theo dõi rủi ro ảnh hưởng đến tính bí mật, toàn vẹn và sẵn sàng của thông tin/dịch vụ CNTT. Áp dụng cho hệ thống, dữ liệu, thiết bị, mạng, cloud, nhà cung cấp, dự án và thay đổi thuộc phạm vi được phê duyệt.

## 2. Nguyên tắc
- Ghi nhận kịch bản rủi ro cụ thể: tài sản/dịch vụ, mối đe dọa, lỗ hổng/nguyên nhân, hậu quả và kiểm soát hiện có.
- Phân biệt rủi ro cố hữu trước kiểm soát và rủi ro còn lại sau kiểm soát.
- Không dùng điểm số thay cho mô tả, bằng chứng và đánh giá chuyên môn.
- Không để IT tự chấp nhận rủi ro kinh doanh vượt thẩm quyền; rủi ro còn lại phải được đúng người có thẩm quyền phê duyệt.

## 3. Khi nào đánh giá
Thực hiện khi xác định phạm vi; trước khi đưa hệ thống/dịch vụ mới vào hoạt động; trước thay đổi lớn; khi có sự cố/lỗ hổng; khi đổi nhà cung cấp hoặc cách xử lý dữ liệu; định kỳ theo mức rủi ro; và khi lãnh đạo/khách hàng/yêu cầu pháp lý yêu cầu.

## 4. Các bước đánh giá
1. Xác định phạm vi, mục tiêu, chủ sở hữu, bên liên quan và yêu cầu pháp lý/hợp đồng.
2. Liệt kê tài sản, dữ liệu, dịch vụ và phụ thuộc; xác định mối đe dọa, điểm yếu, nguyên nhân và hậu quả.
3. Ước lượng khả năng xảy ra dựa trên phơi nhiễm, khả năng khai thác, lịch sử sự cố và hiệu lực kiểm soát.
4. Ước lượng tác động tới dịch vụ, khách hàng, dữ liệu, pháp lý, tài chính, an toàn và uy tín.
5. Chấm điểm theo thang đã duyệt; ghi lý do, nguồn bằng chứng và độ tin cậy.
6. So sánh với tiêu chí chấp nhận; chọn xử lý, tránh, chuyển giao/chia sẻ hoặc chấp nhận có phê duyệt.
7. Lập kế hoạch xử lý gồm kiểm soát, owner, hạn, nguồn lực, tiêu chí nghiệm thu và mức rủi ro còn lại.
8. Xin phê duyệt rủi ro còn lại đúng thẩm quyền; theo dõi, xác minh hiệu lực và đánh giá lại khi có thay đổi.

## 5. Thang điểm đề xuất — chưa có hiệu lực
| Điểm | Khả năng xảy ra (L) | Tác động (I) |
|---|---|---|
| 1 | Hiếm, cần nhiều điều kiện | Không đáng kể, ảnh hưởng nhỏ |
| 2 | Khó xảy ra nhưng có thể | Nhỏ, có workaround |
| 3 | Có thể xảy ra hoặc có tiền lệ | Trung bình, ảnh hưởng một nhóm/dịch vụ |
| 4 | Dễ xảy ra, kiểm soát yếu hoặc có dấu hiệu thường xuyên | Lớn, ảnh hưởng nhiều đơn vị/khách hàng hoặc dữ liệu đáng kể |
| 5 | Gần như chắc chắn/đang diễn ra | Nghiêm trọng, ảnh hưởng diện rộng, dữ liệu nhạy cảm hoặc dịch vụ trọng yếu |

Điểm tham khảo = L × I (1–25). Ngưỡng gợi ý để Ban Giám đốc xem xét: 1–4 thấp; 5–9 trung bình; 10–16 cao; 17–25 nghiêm trọng. Các ngưỡng này **không được dùng chính thức** cho đến khi phê duyệt. Rủi ro pháp lý, an toàn hoặc rò rỉ dữ liệu nhạy cảm có thể cần nâng mức dù điểm số thấp.

## 6. Phương án xử lý
- **Giảm thiểu:** triển khai hoặc tăng cường kiểm soát.
- **Tránh:** dừng/không thực hiện hoạt động tạo ra rủi ro không thể chấp nhận.
- **Chuyển giao/chia sẻ:** dùng hợp đồng, bảo hiểm hoặc nhà cung cấp; vẫn phải quản lý trách nhiệm còn lại.
- **Chấp nhận:** chỉ khi trong ngưỡng được duyệt, có lý do, owner, thời hạn (nếu có) và chữ ký/phê duyệt đúng thẩm quyền.

## 7. Kế hoạch xử lý và theo dõi
Mỗi rủi ro cần có ID, ngày, phạm vi/tài sản, kịch bản, nguyên nhân, hậu quả, kiểm soát hiện tại, L, I, điểm, mức, phương án, owner, hạn, rủi ro còn lại, người phê duyệt, bằng chứng và ngày rà soát. Rủi ro cao/nghiêm trọng hoặc hành động quá hạn phải được leo thang theo ma trận đã duyệt.

## 8. Vai trò
| Vai trò | Trách nhiệm |
|---|---|
| Ban Giám đốc/người được ủy quyền | Phê duyệt tiêu chí chấp nhận, nguồn lực và rủi ro vượt ngưỡng |
| Risk Owner/Service Owner | Chịu trách nhiệm kịch bản, phương án xử lý và rủi ro còn lại |
| IT Lead/đầu mối ATTT | Điều phối phương pháp, duy trì sổ rủi ro, tư vấn và báo cáo |
| IT System/Network/Application Owner | Cung cấp dữ liệu kỹ thuật, thực hiện kiểm soát, lưu bằng chứng |
| Người đánh giá độc lập | Kiểm tra tính đầy đủ/hiệu lực; tránh tự đánh giá công việc mình trực tiếp thực hiện khi cần độc lập |

## 9. Hồ sơ và liên kết
- Duy trì Risk Register và Risk Treatment Plan tại kho hồ sơ được duyệt; không lưu bí mật xác thực trong sổ rủi ro.
- Liên quan: [Quản lý thay đổi](../procedures/07-CHANGE-MANAGEMENT.md), [Sự kiện ATTT](../procedures/10-SECURITY-INCIDENT.md), [Nhà cung cấp/license](../procedures/11-VENDOR-LICENSE.md), [Đánh giá nội bộ và CAPA](../itsm/12-QUY-TRINH-DOANH-GIA-NOI-BO-CAPA.md).

## 10. Rà soát và ban hành
Trước ban hành phải chốt phạm vi đánh giá, thang điểm/ngưỡng, tiêu chí chấp nhận, thẩm quyền, chu kỳ, mẫu sổ rủi ro và phương pháp báo cáo. Rà soát sau sự cố nghiêm trọng, thay đổi lớn hoặc theo chu kỳ do Ban Giám đốc phê duyệt.