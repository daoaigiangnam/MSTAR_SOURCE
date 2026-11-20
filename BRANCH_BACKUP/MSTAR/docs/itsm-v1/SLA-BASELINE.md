# SLA Baseline — hồ sơ Helpdesk hiện có

**Trạng thái:** Bản dự thảo, chưa phải SLA đã xác nhận. Không tự suy đoán giá trị thiếu/mâu thuẫn.

| SLA/Template | Response Time nguồn | Resolve Time nguồn | Escalation từ hồ sơ | Ghi chú |
|---|---:|---:|---|---|
| SLA Online / Support Online | 1 Hrs | 1 Hrs | >4 giờ báo VoTrongHung và NguyenHuuPhong; >8 giờ báo BOD | Cần xác nhận cách tính và mapping cột |
| SLA M Service / M-Care, M-Service, Khảo sát | [CẦN CHỐT — thiếu] | 3 Hrs | >8 giờ báo quản lý; >12 giờ báo BOD | Không tự điền Response Time |
| SLA Giao nhận / bàn giao NAS/thiết bị | 8 Hrs | 8 Hrs | >12 giờ báo quản lý; >16 giờ báo BOD | Xác nhận giờ SLA |
| SLA Sửa chữa | 3 Days | 7 Days | Response: >4 ngày quản lý, >5 ngày BOD; Resolve: >10 ngày quản lý, >14 ngày BOD | Chốt ngày lịch hay ngày làm việc |
| SLA Bảo hành | 3 Days | 20 Days | Response: >4 ngày quản lý, >5 ngày BOD; Resolve: >20 ngày quản lý, >25 ngày BOD | Đối chiếu bảng gốc |
| SLA Internal / nội bộ, mượn thiết bị | 1 Days | 5 Days | Response: >2 ngày quản lý, >3 ngày BOD; Resolve: >6 ngày quản lý, >7 ngày BOD | Chốt cách tính |

## Giờ làm việc ghi trong nguồn
- Thứ Hai đến thứ Bảy; một ngày làm việc là 8 giờ.
- Hồ sơ quy đổi 12 giờ = 1,5 ngày; 16 giờ = 2 ngày; 24 giờ = 3 ngày.
- [CẦN CHỐT] ngày nghỉ, timezone, ngoài giờ, chờ requester/vendor và quy tắc pause/resume.

Không sửa cấu hình SLA production trước khi IT Lead và Service Owner/BGĐ phê duyệt.