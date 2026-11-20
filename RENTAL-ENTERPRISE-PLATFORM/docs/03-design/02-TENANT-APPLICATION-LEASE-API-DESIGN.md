# Thiết kế API — Tenant / Application / Lease

## Trạng thái
DRAFT — thiết kế review.

## Nguyên tắc
- API version: `/api/v1`.
- Tất cả request phải có tenant/organization context hợp lệ.
- Authorization thực hiện ở server.
- Các thao tác lifecycle quan trọng phải idempotent hoặc có cơ chế chống xử lý trùng.
- Không cho client tự ý chuyển status bằng field tùy ý.

## Tenant

```text
GET    /api/v1/tenants
POST   /api/v1/tenants
GET    /api/v1/tenants/{tenantId}
PATCH  /api/v1/tenants/{tenantId}
GET    /api/v1/tenants/{tenantId}/leases
GET    /api/v1/tenants/{tenantId}/documents
```

## Application

```text
GET    /api/v1/applications
POST   /api/v1/applications
GET    /api/v1/applications/{applicationId}
POST   /api/v1/applications/{applicationId}/submit
POST   /api/v1/applications/{applicationId}/screening
POST   /api/v1/applications/{applicationId}/approve
POST   /api/v1/applications/{applicationId}/reject
POST   /api/v1/applications/{applicationId}/withdraw
```

## Lease

```text
GET    /api/v1/leases
POST   /api/v1/leases
GET    /api/v1/leases/{leaseId}
POST   /api/v1/leases/{leaseId}/submit-approval
POST   /api/v1/leases/{leaseId}/approve
POST   /api/v1/leases/{leaseId}/activate
POST   /api/v1/leases/{leaseId}/amendments
POST   /api/v1/leases/{leaseId}/renew
POST   /api/v1/leases/{leaseId}/notice
POST   /api/v1/leases/{leaseId}/terminate
```

## Nguyên tắc lỗi
API phải trả lỗi có mã ổn định, ví dụ:
- `TENANT_NOT_FOUND`
- `APPLICATION_INVALID_STATE`
- `LEASE_INVALID_STATE`
- `LEASE_OCCUPANCY_CONFLICT`
- `LEASE_APPROVAL_REQUIRED`
- `FORBIDDEN_RESOURCE_SCOPE`
- `VALIDATION_ERROR`

## Chưa chốt
Schema request/response chi tiết, pagination chuẩn, filter syntax, idempotency-key policy, optimistic locking và event/webhook contract sẽ được thiết kế trong API Specification.
