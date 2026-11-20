# Thiết kế đã chốt — Tenant / Application / Lease

## Trạng thái
APPROVED — Product Owner chốt ngày 2026-08-10.

## Phạm vi
Thiết kế domain, dữ liệu và API cho Tenant, Rental Application, Screening, Lease, Lease Terms, Amendment và Occupancy.

## Các nguyên tắc đã chốt
- Multi-tenant theo `organization_id`.
- UUID cho định danh nghiệp vụ/public API.
- Lease và Lease Terms được tách để bảo toàn lịch sử hiệu lực.
- Amendment không ghi đè lịch sử.
- Occupancy conflict phải được kiểm tra ở server/domain transaction và chống race condition.
- Lifecycle state không được thay đổi tùy ý từ client.
- Audit trail độc lập với entity.
- Entity workflow có optimistic locking/version khi cần.
- API dùng `/api/v1` và có authorization/resource scope.
- Command quan trọng có idempotency strategy.
- Integration event ưu tiên outbox khi yêu cầu đảm bảo giao nhận.
- Schema production thay đổi qua migration có version.

## Trạng thái chuyển tiếp
Design này là baseline cho Database Design và Implementation. Mọi thay đổi sau khi chốt phải tạo decision/change record và đánh giá ảnh hưởng.
