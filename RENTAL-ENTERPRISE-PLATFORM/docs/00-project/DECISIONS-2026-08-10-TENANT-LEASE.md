# Quyết định nghiệp vụ — Tenant / Application / Lease

## Trạng thái
APPROVED — Product Owner chốt ngày 2026-08-10.

## DEC-001 — Đối tượng thuê
Hệ thống hỗ trợ:
- Cá nhân
- Hộ gia đình
- Người đồng thuê
- Người bảo lãnh
- Doanh nghiệp / tổ chức thuê

Một hồ sơ Tenant có thể đại diện cho cá nhân hoặc tổ chức. Quan hệ người trong hộ gia đình, đồng thuê và bảo lãnh được mô hình hóa riêng, không gộp cứng vào một loại Tenant duy nhất.

## DEC-002 — Nhiều hợp đồng trên một phòng
Mặc định không cho phép nhiều Lease ACTIVE chồng lấn thời gian trên cùng Unit.

Ngoại lệ phải được cấu hình rõ theo chính sách kinh doanh của Property/Unit và phải có kiểm soát nghiệp vụ, quyền hạn và audit. Hệ thống không cho phép client tự bỏ qua kiểm tra xung đột.

## DEC-003 — Gia hạn hợp đồng
Gia hạn được mô hình hóa là một nghiệp vụ tạo kỳ hạn/phiên bản hiệu lực tiếp theo của quan hệ thuê, đồng thời giữ nguyên lịch sử Lease và các điều khoản trước đó.

Không ghi đè dữ liệu lịch sử đã có hiệu lực.

## DEC-004 — Sửa đổi hợp đồng
Sửa đổi vật chất đối với điều khoản Lease phải tạo bản ghi amendment/version có ngày hiệu lực, lý do, người tạo, người phê duyệt và audit trail.

Các thay đổi phi vật chất có thể được cấu hình để không yêu cầu cấp phê duyệt cao, nhưng vẫn phải audit.

## DEC-005 — Phê duyệt
Hệ thống hỗ trợ approval workflow theo Organization, Property và giá trị/loại giao dịch. Cấu hình cụ thể sẽ được thiết kế ở module Workflow/IAM.

Mặc định các nghiệp vụ tạo hoặc thay đổi Lease có ảnh hưởng pháp lý/tài chính phải có bước phê duyệt phù hợp trước khi ACTIVE.

## DEC-006 — Screening
Hệ thống hỗ trợ cả:
- Screening nội bộ
- Tích hợp nhà cung cấp screening bên ngoài

Kết quả bên ngoài phải được lưu dưới dạng dữ liệu/evidence được kiểm soát, không cho phép nhà cung cấp bên ngoài tự quyết định trạng thái Lease.

## DEC-007 — Hồ sơ và lưu trữ
Hồ sơ Tenant, Application, Screening và Lease phải có chính sách lưu trữ theo loại dữ liệu và yêu cầu pháp lý/kinh doanh.

Dữ liệu cần cho tài chính, pháp lý, audit và lịch sử thuê không được xóa vật lý tùy tiện. Khi cần đáp ứng chính sách quyền riêng tư, hệ thống phải hỗ trợ hạn chế truy cập, anonymize hoặc retention workflow theo quy định được cấu hình.

## Ảnh hưởng
Các quyết định trên là baseline cho:
- Domain Model
- ERD
- Lease State Machine
- Occupancy Conflict
- IAM / Resource Scope
- Workflow / Approval
- Document / Retention
- Test Case và Traceability

## Quy tắc thay đổi
Mọi thay đổi sau khi APPROVED phải có decision mới, nêu rõ lý do và tác động đến Requirement, Analysis, Design, Database, API, Code và Test.
