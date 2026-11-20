# Test Case — Database Foundation

## Trạng thái
DRAFT — tạo trước khi migration implementation để bảo đảm yêu cầu có tiêu chí kiểm thử.

| ID | Mục tiêu | Kết quả mong đợi |
|---|---|---|
| DB-TC-001 | Chạy migration trên DB sạch | Tất cả migration hoàn tất không lỗi |
| DB-TC-002 | Kiểm tra PK/FK | Quan hệ bắt buộc được bảo vệ |
| DB-TC-003 | Kiểm tra tenant isolation | Không đọc/ghi được dữ liệu organization khác |
| DB-TC-004 | Tạo application hợp lệ | Thành công |
| DB-TC-005 | Tạo lease hợp lệ | Thành công |
| DB-TC-006 | Kích hoạt lease khi approval thiếu | Bị từ chối |
| DB-TC-007 | Kích hoạt lease gây occupancy overlap | Bị từ chối |
| DB-TC-008 | Kích hoạt lease đồng thời | Chỉ một transaction hợp lệ được commit theo policy |
| DB-TC-009 | Tạo amendment | Không làm mất lịch sử terms cũ |
| DB-TC-010 | Kiểm tra optimistic locking | Phát hiện cập nhật trên version cũ |
| DB-TC-011 | Kiểm tra audit | Lifecycle quan trọng tạo audit evidence |
| DB-TC-012 | Migration versioning | Không chạy sai thứ tự hoặc tạo schema không nhất quán |

## Nguyên tắc
Test Result sẽ được ghi riêng sau khi migration và test automation được triển khai. Không đánh dấu PASS khi chưa có bằng chứng thực thi.
