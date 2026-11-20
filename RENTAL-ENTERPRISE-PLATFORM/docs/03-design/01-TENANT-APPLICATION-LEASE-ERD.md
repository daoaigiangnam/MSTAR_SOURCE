# Thiết kế dữ liệu — Tenant / Application / Lease

## Trạng thái
DRAFT — thiết kế cơ sở để review trước khi chốt ERD chính thức.

## 1. Mục tiêu
Thiết kế mô hình dữ liệu có thể:
- quản lý người thuê và các bên tham gia;
- quản lý hồ sơ đăng ký thuê;
- quản lý screening và quyết định;
- quản lý hợp đồng theo thời gian hiệu lực;
- lưu lịch sử sửa đổi/gia hạn;
- kiểm soát xung đột chiếm dụng phòng;
- bảo đảm audit và truy vết tài chính/pháp lý.

## 2. Nhóm bảng đề xuất

### Tenant
- `tenants`
- `tenant_contacts`
- `tenant_emergency_contacts`
- `tenant_documents`

### Application
- `rental_applications`
- `application_participants`
- `screening_cases`
- `screening_results`
- `application_decisions`

### Lease
- `leases`
- `lease_participants`
- `lease_terms`
- `lease_amendments`
- `lease_events`

### Occupancy
- `unit_occupancies`

## 3. Quan hệ chính

```text
organizations
    │
    ├── tenants
    │     └── tenant_contacts
    │
    ├── rental_applications
    │     ├── application_participants ──> tenants / applicant parties
    │     ├── screening_cases
    │     │      └── screening_results
    │     └── application_decisions
    │
    └── leases
          ├── lease_participants ──> tenants / parties
          ├── lease_terms
          ├── lease_amendments
          └── lease_events

units
  ├── rental_applications
  ├── leases
  └── unit_occupancies
```

## 4. Lease và Lease Terms

`leases` giữ danh tính và vòng đời của hợp đồng.

`lease_terms` giữ các điều khoản có hiệu lực theo thời gian, ví dụ:
- ngày bắt đầu/kết thúc;
- giá thuê;
- tiền cọc;
- chu kỳ thanh toán;
- các điều khoản tài chính khác.

Không cập nhật đè dữ liệu lịch sử khi một amendment có hiệu lực.

## 5. Amendment

`lease_amendments` ghi nhận:
- hợp đồng gốc;
- loại sửa đổi;
- nội dung/metadata thay đổi;
- ngày hiệu lực;
- trạng thái phê duyệt;
- người tạo/phê duyệt;
- thời điểm áp dụng.

Amendment có thể tạo hoặc cập nhật một bản ghi `lease_terms` hiệu lực mới theo quy tắc nghiệp vụ.

## 6. Occupancy Conflict

`unit_occupancies` là lớp dữ liệu phục vụ truy vấn và kiểm soát chiếm dụng.

Mỗi bản ghi tham chiếu:
- `unit_id`;
- `lease_id`;
- `start_at`;
- `end_at`;
- trạng thái chiếm dụng.

Việc kích hoạt lease phải kiểm tra xung đột trong cùng transaction. Không dựa vào kiểm tra UI.

## 7. Multi-Tenant

Các bảng thuộc domain phải có `organization_id` hoặc được truy xuất thông qua quan hệ bảo đảm xác định được organization. API/service layer phải áp dụng tenant scope cho mọi thao tác đọc/ghi.

## 8. Audit

Các thao tác có ý nghĩa pháp lý/tài chính/lifecycle phải tạo audit evidence. Audit không được phụ thuộc vào dữ liệu hiện tại của entity để tái dựng lịch sử.

## 9. Chưa chốt ở mức thiết kế vật lý

- kiểu khóa chính và chiến lược UUID/ULID;
- chính sách soft delete;
- partition/index chiến lược cho dữ liệu lớn;
- cấu trúc JSON cho điều khoản mở rộng;
- retention vật lý của tài liệu và screening;
- cơ chế database-level enforcement cho occupancy overlap.

Các điểm này sẽ được quyết định trong Database Design trước khi migration production được tạo.
