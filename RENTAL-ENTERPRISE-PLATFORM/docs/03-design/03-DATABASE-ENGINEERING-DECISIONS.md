# Quyết định kỹ thuật Database — Tenant / Application / Lease

## Trạng thái
APPROVED — Product Owner chốt Design ngày 2026-08-10.

## 1. Khóa chính
Sử dụng UUID cho các entity nghiệp vụ có yêu cầu phân tán/integration. Không sử dụng ID tăng dần làm định danh public API.

## 2. Soft Delete
Không dùng soft delete một cách máy móc cho mọi bảng. Dữ liệu có ý nghĩa pháp lý, tài chính, audit hoặc lịch sử phải được bảo toàn. Các bảng master có thể có trạng thái `active/inactive` thay vì xóa.

## 3. Audit
Các thay đổi quan trọng phải có audit record độc lập. Không dựa vào `updated_at` để thay thế audit trail.

## 4. Optimistic Locking
Các entity có cập nhật cạnh tranh, đặc biệt Lease và các hồ sơ workflow, phải có cơ chế version/revision để phát hiện cập nhật trên dữ liệu cũ.

## 5. Index
Index phải phục vụ tenant scope, foreign key, trạng thái lifecycle, thời gian hiệu lực và các truy vấn nghiệp vụ chính. Index được kiểm chứng bằng execution plan khi dữ liệu tăng trưởng.

## 6. Occupancy Conflict
Kiểm tra overlap phải nằm ở service/domain transaction và phải có cơ chế chống race condition. Không dựa vào client/UI. Thiết kế database phải hỗ trợ khóa/serialization phù hợp.

## 7. Idempotency
Các command có khả năng gửi lại, đặc biệt approve/activate/payment-like operations, phải có idempotency strategy phù hợp.

## 8. API Pagination
API collection sử dụng pagination ổn định và filter/sort có whitelist. Không cho client tự truyền tên cột SQL tùy ý.

## 9. Event / Integration
Domain event và webhook được thiết kế tách khỏi transaction chính; việc phát sự kiện phải có cơ chế đảm bảo không mất event, ưu tiên outbox pattern khi cần.

## 10. Migration
Mọi thay đổi schema production phải đi qua migration có version, có rollback/forward strategy phù hợp và có automated migration test.

## 11. Database Engine
MySQL 8 là target transactional database theo quyết định kiến trúc đã chốt.
