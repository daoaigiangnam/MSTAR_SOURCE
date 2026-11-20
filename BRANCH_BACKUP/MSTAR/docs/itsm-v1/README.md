# MSTAR Corp — Hệ thống quản lý dịch vụ CNTT (ITSM)

**Trạng thái:** DỰ THẢO CÓ KIỂM SOÁT — CHƯA BAN HÀNH. Không dùng tài liệu này làm căn cứ thay đổi production cho đến khi hoàn thành phê duyệt.

## Điểm vào duy nhất

**[MỞ DANH MỤC TÀI LIỆU ITSM CHUẨN — MASTER INDEX](ITSM-MASTER-INDEX.md)**

Master Index sắp xếp toàn bộ bộ tài liệu theo thứ tự: quy chế và quản trị → chính sách/chuẩn kiểm soát → quy trình vận hành → SLA/KPI → biểu mẫu/checklist → sổ đăng ký/bằng chứng → đánh giá nội bộ/CAPA → đối chiếu ISO và danh mục còn thiếu.

## Thư mục cấu trúc tài liệu

- [Quy trình vận hành — nguồn chuẩn duy nhất](docs/procedures/01-INCIDENT.md)
- [Tài liệu quản trị, tổng hợp và đối chiếu](docs/itsm/00-LUU-DO-TONG-THE-ITSM.md)
- [Sổ tay ITSM](docs/ITSM-HANDBOOK.md)
- [Biểu mẫu CSV](forms/FRM-ITSM-01-Ticket-Fields.csv)
- [Sổ đăng ký tài sản](registers/ASSET-REGISTER.csv)
- [SLA baseline](SLA-BASELINE.md)

## Quy tắc quan trọng

`docs/procedures/` là nguồn chuẩn duy nhất cho quy trình vận hành chi tiết. `docs/itsm/` là tài liệu quản trị, lưu đồ tổng thể, SLA nguồn, biểu mẫu/nội dung tổng hợp, kiểm soát hồ sơ và đối chiếu; không phải bộ quy trình vận hành song song.

Không commit mật khẩu, token, private key, recovery code hoặc bí mật xác thực vào GitHub, CSV, ticket hay biên bản. Các mục SLA, thẩm quyền, ngưỡng kỹ thuật, RPO/RTO và thời hạn lưu còn thiếu phải được người có thẩm quyền xác nhận; không tự suy đoán.

**Lưu ý:** Có tài liệu không đồng nghĩa đã triển khai hiệu lực hoặc đã được chứng nhận ISO.
