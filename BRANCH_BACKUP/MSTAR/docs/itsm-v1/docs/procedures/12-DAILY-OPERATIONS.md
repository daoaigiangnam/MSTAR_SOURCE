# ITSM-OPS-001 — Kiểm tra vận hành hằng ngày

**Trạng thái:** DRAFT v1.0 | Owner: IT System/Network | Approver: IT Lead

## Phạm vi
Server/VM, NAS/volume/share, backup, AD/DNS/DHCP, email, router/firewall/WAN/VPN, switch/uplink, Wi-Fi/controller/AP, Zabbix/alert channel và dịch vụ trọng yếu.

## Thực hiện
1. Kiểm tra theo lịch được phân công.
2. Ghi timestamp, người kiểm tra, site, hệ thống, ngưỡng, giá trị quan sát, Pass/Fail/NA và evidence.
3. Dùng ngưỡng đã duyệt theo từng hệ thống; không đặt một ngưỡng chung cho tất cả.
4. Mọi Fail/out-of-threshold cần ticket/alert, severity, owner và bước xử lý.
5. Rà soát ticket tồn, SLA sắp/quá hạn, backup fail, alert trọng yếu chưa có owner và dung lượng gần ngưỡng.
6. IT Lead rà soát ngoại lệ, giao action và escalate sự cố nghiêm trọng.
7. Theo dõi tới khi có bằng chứng khắc phục và kiểm tra đạt.

Không đánh dấu Pass nếu chưa kiểm tra; ghi NA kèm lý do.


## Kiểm soát vận hành chung
- **Trạng thái:** DỰ THẢO — chỉ có hiệu lực sau phê duyệt theo thẩm quyền MSTAR.
- **Phân biệt nội dung:** thông tin trong hồ sơ nguồn là căn cứ hiện có; kiểm soát bổ sung là đề xuất; giá trị thiếu phải được chốt trước ban hành.
- **Vai trò:** requester/đơn vị yêu cầu cung cấp thông tin; Service Desk/IT tiếp nhận và điều phối; process owner chịu trách nhiệm chất lượng; approver quyết định theo ma trận thẩm quyền; IT Lead giám sát ngoại lệ và báo cáo.
- **Bằng chứng:** hồ sơ truy xuất được người yêu cầu, người xử lý, thời điểm, phê duyệt, kết quả và liên kết bằng chứng. Không lưu mật khẩu, token, private key hoặc recovery code trong ticket, CSV, email thông thường hay Git.
- **Chuyển quy trình:** gián đoạn dịch vụ liên kết ITSM-INC-001; dấu hiệu ATTT liên kết ITSM-SEC-001; thay đổi production liên kết ITSM-CHG-001; cấp quyền liên kết ITSM-IAM-001; restore liên kết ITSM-BKP-001.

## Lưu đồ
```mermaid
flowchart TD
 A([Phát sinh]) --> B[Đăng ký ticket/hồ sơ và bằng chứng]
 B --> C[Kiểm tra thông tin, phân loại, tác động và SLA]
 C --> D{Đủ thông tin?}
 D -- Không --> E[Yêu cầu bổ sung, ghi owner và trạng thái]
 E --> C
 D -- Có --> F{Cần phê duyệt?}
 F -- Có --> G[Trình đúng approver]
 G --> H{Được duyệt?}
 H -- Không --> I[Thông báo lý do, dừng hành động chưa được phép]
 H -- Có --> J[Phân công và thực hiện theo runbook]
 F -- Không --> J
 J --> K[Kiểm tra kết quả, cập nhật hồ sơ và bằng chứng]
 K --> L{Đạt tiêu chí?}
 L -- Không --> M[Khắc phục hoặc chuyển cấp, cập nhật ETA]
 M --> J
 L -- Có --> N[Bàn giao/thông báo và đóng hồ sơ]
 N --> O{Có vi phạm, rủi ro hoặc tái diễn?}
 O -- Có --> P[Escalation/RCA/CAPA theo quy trình liên quan]
 O -- Không --> Q([Kết thúc và báo cáo])
 P --> Q
```

## Luồng công việc đặc thù — ITSM-OPS-001 Vận hành hằng ngày
1. **Kích hoạt:** Kiểm tra hệ thống theo lịch được phân công.
2. **Tiếp nhận/đánh giá:** Ghi người kiểm tra, thời điểm, site, hệ thống, ngưỡng đã duyệt và kết quả.
3. **Kiểm soát:** Kiểm tra server/NAS/AD/DNS/DHCP/WAN/firewall/Wi-Fi/backup/Zabbix.
4. **Thực hiện:** Mọi Fail phải có ticket/alert, severity, owner và hành động.
5. **Xác minh/kết thúc:** Rà soát ticket tồn, SLA, backup fail, alert chưa owner và dung lượng.
6. **Ngoại lệ/chuyển cấp:** Bàn giao ca, theo dõi action tới khi đóng.
7. **Bằng chứng đặc thù:** Không có ngưỡng được duyệt, checklist thiếu, lỗi nghiêm trọng.
8. **KPI đề xuất:** Daily Checklist, ticket, alert, handover notes.

## Hồ sơ, chỉ số và checklist đóng
- **Hồ sơ tối thiểu:** mã tham chiếu, đầu vào, phân loại, SLA nếu áp dụng, phê duyệt nếu cần, người thực hiện, timeline, kết quả kiểm tra, thông báo/bàn giao và bằng chứng.
- **Nơi lưu/thời hạn:** theo kho lưu và chính sách MSTAR được phê duyệt; không tự đặt thời hạn.
- **KPI đề xuất:** chỉ dùng sau khi process owner xác nhận định nghĩa, nguồn dữ liệu, chu kỳ và mục tiêu.
- **Checklist đóng:** [ ] đúng requester/owner; [ ] phê duyệt đầy đủ; [ ] kết quả kiểm tra; [ ] ticket/register cập nhật; [ ] không chứa secret; [ ] bằng chứng truy xuất được; [ ] vi phạm/tái diễn đã escalation/RCA/CAPA.
- **Cần chốt trước ban hành:** owner/approver; ma trận SLA/escalation; ngưỡng kỹ thuật/retention nếu áp dụng; kho hồ sơ/quyền/thời hạn lưu; kết quả kiểm thử quy trình.
