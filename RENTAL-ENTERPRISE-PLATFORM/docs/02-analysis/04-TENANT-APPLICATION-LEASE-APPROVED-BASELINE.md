# Phân tích cơ sở đã chốt — Tenant / Application / Lease

## Trạng thái
APPROVED BASELINE — 2026-08-10

Tài liệu này chuyển các quyết định nghiệp vụ đã được Product Owner chốt thành cơ sở để thiết kế.

## 1. Mô hình chủ thể thuê
Hệ thống phải hỗ trợ Tenant cá nhân và tổ chức. Quan hệ hộ gia đình, đồng thuê và bảo lãnh được biểu diễn bằng các quan hệ/participant riêng.

## 2. Mô hình Lease
Lease có lịch sử và các kỳ hạn/phiên bản hiệu lực. Gia hạn không làm mất lịch sử cũ. Amendment phải có effective date và audit.

## 3. Kiểm soát xung đột Unit
Kiểm tra xung đột phải thực hiện phía server trong transaction. Không chỉ dựa vào UI. Ngoại lệ nhiều Lease đồng thời chỉ được phép khi policy của tài sản cho phép.

## 4. Approval
Lease và các thay đổi vật chất phải đi qua workflow phê duyệt phù hợp. Approval policy sẽ là cấu hình theo Organization/Property và loại/giá trị giao dịch.

## 5. Screening
Screening là một domain độc lập với Lease. Có thể sử dụng screening nội bộ hoặc provider bên ngoài. Provider không có quyền trực tiếp kích hoạt Lease.

## 6. Retention
Document và dữ liệu lịch sử được phân loại theo retention policy. Dữ liệu pháp lý/tài chính/audit phải có khả năng tái dựng lịch sử.

## 7. Thiết kế tiếp theo
Các quyết định này là đầu vào bắt buộc cho:
1. Domain ERD chi tiết.
2. State Machine chi tiết.
3. Authorization Matrix.
4. Approval Workflow.
5. Document/Retention model.
6. Test Scenario và Test Case.
