# Từ điển dữ liệu — Tenant / Application / Lease

## Trạng thái
DRAFT — chuẩn bị review trước khi tạo migration.

## 1. Quy ước chung
- Primary key: `id` dạng UUID.
- Foreign key dùng hậu tố `_id`.
- Thời gian hệ thống dùng UTC; hiển thị theo timezone cấu hình của Organization/Property.
- Các bảng nghiệp vụ có `organization_id` khi cần để tenant scope rõ ràng.
- Có `created_at`, `updated_at` cho entity có lifecycle; audit dùng bảng riêng.
- Không lưu dữ liệu bí mật nhạy cảm dạng plaintext nếu không cần thiết.

## 2. organizations
Định danh tenant cấp nền tảng.

| Cột | Kiểu đề xuất | Ghi chú |
|---|---|---|
| id | CHAR(36) | PK |
| name | VARCHAR(200) | Tên tổ chức |
| code | VARCHAR(50) | Unique trong nền tảng |
| status | VARCHAR(20) | ACTIVE/INACTIVE |
| timezone | VARCHAR(64) | Múi giờ |
| created_at | DATETIME(6) | UTC |
| updated_at | DATETIME(6) | UTC |

## 3. tenants
Hồ sơ người thuê/khách thuê.

| Cột | Kiểu đề xuất | Ghi chú |
|---|---|---|
| id | CHAR(36) | PK |
| organization_id | CHAR(36) | FK |
| tenant_type | VARCHAR(30) | INDIVIDUAL/HOUSEHOLD/BUSINESS |
| display_name | VARCHAR(255) | Tên hiển thị |
| status | VARCHAR(30) | ACTIVE/INACTIVE/ARCHIVED |
| created_at | DATETIME(6) | |
| updated_at | DATETIME(6) | |
| version | BIGINT | Optimistic locking |

Thông tin định danh chi tiết sẽ được tách theo privacy policy, không nhồi toàn bộ vào một cột JSON.

## 4. rental_applications
Hồ sơ đăng ký thuê.

Các trường chính:
- `id`
- `organization_id`
- `unit_id`
- `status`
- `submitted_at`
- `decision_at`
- `expires_at`
- `created_at`
- `updated_at`
- `version`

## 5. application_participants
Liên kết người/các bên tham gia với application.

Các trường chính:
- `id`
- `application_id`
- `party_type`
- `tenant_id` nullable khi applicant chưa được tạo thành tenant
- `role`
- `is_primary`
- timestamps

## 6. screening_cases / screening_results
Tách case khỏi result để hỗ trợ nhiều lần kiểm tra và provider khác nhau.

`screening_cases`:
- application_id
- provider_type
- status
- requested_at
- completed_at
- external_reference

`screening_results`:
- screening_case_id
- result_type
- outcome
- score nullable
- result_reference
- completed_at

Không lưu dữ liệu nhạy cảm của provider nếu không cần thiết; dữ liệu cần lưu phải theo retention policy.

## 7. application_decisions
Lưu quyết định nghiệp vụ độc lập với trạng thái application hiện tại.

- `id`
- `application_id`
- `decision`
- `reason_code`
- `notes`
- `decided_by`
- `decided_at`

## 8. leases
Định danh và lifecycle của hợp đồng.

Các trường chính:
- `id`
- `organization_id`
- `unit_id`
- `status`
- `lease_number`
- `application_id` nullable
- `signed_at`
- `activated_at`
- `terminated_at`
- `version`
- timestamps

## 9. lease_participants
- `id`
- `lease_id`
- `tenant_id` hoặc party reference
- `role`
- `is_primary`
- timestamps

## 10. lease_terms
Điều khoản có hiệu lực theo thời gian.

- `id`
- `lease_id`
- `version_no`
- `effective_from`
- `effective_to`
- `rent_amount`
- `deposit_amount`
- `billing_cycle`
- `currency`
- `terms_status`
- timestamps

Không cho phép hai bản terms hiệu lực chồng lấn trong cùng lease nếu nghiệp vụ không cho phép.

## 11. lease_amendments
- `id`
- `lease_id`
- `amendment_number`
- `status`
- `effective_from`
- `reason_code`
- `description`
- `approved_by`
- `approved_at`
- timestamps

## 12. lease_events
Append-oriented lifecycle history.

- `id`
- `lease_id`
- `event_type`
- `from_status`
- `to_status`
- `occurred_at`
- `actor_id`
- `metadata_json`

## 13. unit_occupancies
Lớp dữ liệu phục vụ kiểm soát chiếm dụng.

- `id`
- `organization_id`
- `unit_id`
- `lease_id`
- `start_at`
- `end_at` nullable
- `status`
- timestamps

Index bắt buộc phục vụ `(organization_id, unit_id, start_at, end_at, status)`.

## 14. Audit
Audit log không nằm trong các bảng entity trên. Nó phải chứa ít nhất:
- actor
- organization
- action
- entity type/id
- timestamp
- request/correlation id nếu có
- before/after hoặc diff phù hợp với policy

## 15. Migration Gate
Chưa tạo migration production cho đến khi:
1. ERD được Product Owner chốt;
2. Data Dictionary được review;
3. Constraint/index strategy được chốt;
4. Test cases cho constraint quan trọng được xác định.
