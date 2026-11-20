# Database Design v1.0 — Tenant / Application / Lease

## Trạng thái
APPROVED / FROZEN — Product Owner chốt ngày 2026-08-10.

## Phạm vi
Tenant, Rental Application, Screening, Lease, Lease Terms, Lease Amendment, Lease Event và Unit Occupancy.

## Chuẩn dữ liệu
- MySQL 8.
- UUID cho định danh nghiệp vụ/public API.
- UTC cho thời gian lưu trữ; chuyển đổi theo timezone cấu hình khi hiển thị.
- Foreign key cho quan hệ bắt buộc.
- Index theo organization scope, foreign key, lifecycle và effective date.
- Optimistic locking cho entity có cập nhật cạnh tranh.

## Toàn vẹn dữ liệu
- Không cho phép record mồ côi ở quan hệ bắt buộc.
- Không cho phép lease activation nếu unit/participant/approval không hợp lệ.
- Occupancy overlap phải được kiểm tra trong transaction và có cơ chế chống race condition.
- Lease terms không được chồng lấn hiệu lực trong cùng lease nếu policy không cho phép.
- Dữ liệu pháp lý, tài chính và audit không được xóa vật lý tùy tiện.

## Lịch sử
- `lease_amendments` ghi nhận thay đổi.
- `lease_terms` ghi nhận phiên bản điều khoản có hiệu lực.
- `lease_events` ghi nhận lifecycle.
- Audit log độc lập với entity.

## Migration Gate
Migration production được phép bắt đầu dựa trên baseline này. Mọi thay đổi sau Freeze phải có Change/Decision và Impact Analysis.

## Definition of Done cho Database Foundation
- Migration chạy được trên môi trường sạch.
- Migration chạy lại/được kiểm soát version đúng quy định.
- FK, unique constraint, check/business constraints và index được kiểm thử.
- Tenant isolation được kiểm thử.
- Occupancy conflict và concurrent activation được kiểm thử.
- Seed/reference data có version nếu cần.
- Backup/restore procedure được tài liệu hóa trước production.
