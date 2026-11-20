# ERD — Tenant / Application / Lease

## Trạng thái
DRAFT — chờ Product Owner chốt.

```text
organizations
   │
   ├────────────── tenants
   │                 │
   │                 └── tenant_contacts
   │
   ├────────────── rental_applications ─────── units
   │                    │
   │                    ├── application_participants ── tenants
   │                    ├── screening_cases
   │                    │      └── screening_results
   │                    └── application_decisions
   │
   └────────────── leases ─────────────────── units
                        │
                        ├── lease_participants ── tenants
                        ├── lease_terms
                        ├── lease_amendments
                        ├── lease_events
                        └── unit_occupancies ─── units
```

## Quan hệ chính

- `organizations 1:N tenants`
- `organizations 1:N rental_applications`
- `organizations 1:N leases`
- `units 1:N rental_applications`
- `units 1:N leases`
- `rental_applications 1:N application_participants`
- `rental_applications 1:N screening_cases`
- `screening_cases 1:N screening_results`
- `rental_applications 1:N application_decisions`
- `leases 1:N lease_participants`
- `leases 1:N lease_terms`
- `leases 1:N lease_amendments`
- `leases 1:N lease_events`
- `leases 1:N unit_occupancies`
- `units 1:N unit_occupancies`

## Quy tắc toàn vẹn quan trọng

1. Không được tạo Lease cho Unit không thuộc Organization của Lease.
2. Không được kích hoạt Lease nếu participant bắt buộc chưa hợp lệ.
3. Không được tạo occupancy gây overlap theo policy.
4. `lease_terms` phải có khoảng hiệu lực hợp lệ.
5. Không được xóa vật lý dữ liệu cần cho pháp lý/tài chính/audit.
6. Foreign key phải ngăn record mồ côi ở các quan hệ bắt buộc.

## Ghi chú
ERD này chỉ bao phủ domain đang triển khai. Các domain Billing, Payment, Maintenance, Document, Notification và Reporting sẽ nối vào các khóa domain ổn định sau khi được phân tích/chốt riêng.
