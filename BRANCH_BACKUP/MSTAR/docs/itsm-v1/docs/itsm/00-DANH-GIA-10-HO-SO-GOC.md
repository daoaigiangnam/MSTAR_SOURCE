# ĐÁNH GIÁ 10 HỒ SƠ GỐC MSTAR VÀ KẾ HOẠCH KHẮC PHỤC KHOẢNG TRỐNG ITSM/ATTT

**Mã tài liệu:** MSTAR-ITSM-ASSESS-001 | **Phiên bản:** 1.1-DRAFT  
**Ngày cập nhật:** 24/10/2026 | **Trạng thái:** DỰ THẢO — chưa thay thế hồ sơ gốc, chưa phải quyết định ban hành.

> **Cách đọc trạng thái:** “Đã có tài liệu” chỉ xác nhận file/tài liệu được tìm thấy trong nhánh. Không có nghĩa đã phê duyệt, cấu hình trên hệ thống, vận hành nhất quán hoặc có bằng chứng triển khai. Tình trạng thực tế chỉ được nâng lên sau khi đối chiếu hiện trường, hồ sơ vận hành và phê duyệt.

## 1. Mục đích và cách đánh giá
Đánh giá 10 hồ sơ nguồn đã cung cấp; đối chiếu với bộ tài liệu được tạo trong nhánh `docs/itsm-v1`; xác định phần đã có tài liệu, phần còn thiếu, nội dung chưa được triển khai/bằng chứng chưa có trong hồ sơ, mức ưu tiên và đầu ra cần nghiệm thu.

- **Có nguồn gốc:** được nêu trong hồ sơ nguồn.
- **Đã có dự thảo:** đã tạo được tài liệu trong repository nhưng chưa chứng minh hiệu lực.
- **Chưa xác minh triển khai:** không có bằng chứng hiện trường được đưa vào bộ hồ sơ để xác nhận.
- **Khoảng trống:** chưa thấy đủ tài liệu/bằng chứng trong nhánh để kết luận hoàn tất.
- **Ưu tiên P1:** rủi ro có thể gây mất dữ liệu, truy cập trái phép hoặc gián đoạn trọng yếu; cần xử lý/đánh giá sớm.
- **Ưu tiên P2:** điều kiện cần trước ban hành/quản trị thống nhất.
- **Ưu tiên P3:** hoàn thiện đo lường, tối ưu hóa và cải tiến sau khi kiểm soát trọng yếu được xử lý.

## 2. Đánh giá 10 hồ sơ nguồn

| # | Hồ sơ nguồn | Nội dung hữu ích cần giữ | Khoảng trống/rủi ro được xác định | Kết luận/việc cần làm |
|---|---|---|---|---|
| 1 | 1.a Biên bản bàn giao và tiếp nhận | Danh sách Server, NAS, Switch, Router, Internet, Email, Wi-Fi Controller, License; số lượng và tình trạng | Mẫu năm 2021; trường thông tin đăng nhập có thể làm lộ bí mật; thiếu trường tài sản/serial/vị trí/phụ kiện và xác nhận quản lý | Cập nhật mẫu; bỏ mật khẩu khỏi biên bản; đối chiếu mẫu mới với quy trình tài sản và kiểm thử biên bản thực tế. **P1/P2** |
| 2 | 1.b Thông tin thiết bị.xlsx | Danh mục thiết bị/dịch vụ, IP WAN/DDNS, nhà cung cấp, ghi chú | Cột username/password; thiếu owner, hợp đồng, ngày hết hạn, MFA và quản trị dự phòng | Chuyển thành danh mục phi bí mật; xác định kho bí mật được duyệt; kiểm tra lịch sử chia sẻ file cũ và thay secret nếu có nguy cơ lộ. **P1** |
| 3 | 2.a Hướng dẫn triển khai File Server | Tài khoản domain, nhóm phòng ban, PUBLIC, thư mục phòng/cá nhân, lưu dữ liệu tập trung | Domain để trống; quy tắc mật khẩu/khóa cũ cần xác minh; quyền PUBLIC root rộng có thể cho phép sửa/xóa; thiếu approval/ACL review/retention/restore evidence | Không triển khai nguyên xi tài liệu cũ; xuất ACL thực tế, xác định Data Owner, thử nghiệm quyền và lập kế hoạch thay đổi được duyệt. **P1** |
| 4 | 2.b Quy hoạch tài sản và thiết bị | Quy tắc mã tài sản, loại thiết bị, trạng thái và Check-In/Check-Out | Quy tắc IP thiếu subnet/VLAN/DHCP reservation; thiếu serial, owner, bảo hành, vòng đời, kiểm kê và xử lý mất/hỏng | Giữ làm nguồn tham chiếu; chuẩn hóa taxonomy/asset ID sau khi đối soát dữ liệu và cấu hình mạng đang chạy. **P2** |
| 5 | 2.c Định nghĩa trong TAISAN.MSTARCORP.VN.xlsx | Danh mục assets, licenses, accessories, status, kho và tài khoản | Phân loại không nhất quán với 2.b; chưa rõ định nghĩa role/status và mapping dữ liệu hiện hữu | Đối chiếu export dữ liệu thực tế; duyệt danh mục chuẩn, mapping và quy tắc dữ liệu bắt buộc. **P2** |
| 6 | 2.d Đề xuất phần mềm & phần cứng | Cơ sở xem xét cấu hình/ứng dụng | Chưa đủ quy trình thẩm định nhu cầu, tương thích, license, chi phí, vòng đời, nghiệm thu và ghi sổ | Áp dụng quy trình mua sắm/yêu cầu/thay đổi; bổ sung tiêu chí nghiệm thu và cập nhật asset/software register. **P2** |
| 7 | 2.e Network Diagram Template.pdf | Mẫu sơ đồ mạng | Template không thay thế as-built, IPAM/CMDB, version, owner và lịch cập nhật | Lập sơ đồ as-built theo site, ghi ngày/version/owner, ISP/WAN, firewall, switch, subnet/VLAN và phụ thuộc; đối chiếu hiện trường. **P1/P2** |
| 8 | 3.a Daily checklist.xlsx | Checklist server, backup, AD/DNS/DHCP, email/license, router, switch, Wi-Fi và phòng máy | Chưa chuẩn hóa timestamp, người thực hiện, giá trị/threshold, Pass/Fail, evidence, ticket và escalation | Giữ hạng mục nguồn; thêm bằng chứng và thử chạy trên hệ thống thật, có người rà soát và action owner. **P1/P2** |
| 9 | 3.b Helpdesk & SLA.docx | Ticket Bitrix, template, tiếp nhận–xử lý–kiểm tra–đóng; SLA và ngưỡng escalation | Response Time M-Service bị thiếu số; chưa rõ giờ/ngày làm việc, nghỉ lễ, pause/resume SLA, priority và xác nhận đóng | Không tự điền SLA thiếu; chủ dịch vụ/Ban Giám đốc chốt lịch, ma trận severity/escalation và test cấu hình Bitrix với dữ liệu thực tế. **P1/P2** |
| 10 | 4.a Monitoring With ZABBIX.docx | Hướng dẫn host/agent/SNMP và cấu hình giám sát | Chưa xác minh phiên bản, credential, trigger threshold, phân tuyến cảnh báo, trực ngoài giờ, ticket và test alert | Xác minh cấu hình Zabbix đang chạy; bảo vệ/đổi secret nếu cần; lập monitoring matrix và test cảnh báo tới đúng người/ticket. **P1** |

## 3. Khoảng trống hiện còn sau khi tạo bộ tài liệu ITSM

Bảng sau là kết quả đối chiếu **cây file hiện có** với nội dung cần để vận hành. Không có bằng chứng hiện trường trong repository đồng nghĩa trạng thái triển khai vẫn là “chưa xác minh”, không phải khẳng định chắc chắn rằng công ty chưa làm.

| ID | Hạng mục | Tài liệu hiện có | Phần còn thiếu/chưa chứng minh triển khai | Ưu tiên | Bằng chứng để đóng khoảng trống |
|---|---|---|---|---|---|
| G01 | Phạm vi hệ thống quản lý dịch vụ (SMS) và danh mục dịch vụ | Quy chế và sổ tay ITSM dự thảo | Chưa thấy danh mục dịch vụ đã duyệt, phạm vi nội bộ/khách hàng, site, Service Owner, phụ thuộc và tiêu chí loại trừ | P1 | Scope statement được duyệt; Service Catalogue; danh sách owner và phụ thuộc |
| G02 | Chính sách phân loại/chia sẻ/lưu trữ dữ liệu | Đã có dự thảo `POL-IS-01` | Chưa phê duyệt taxonomy; chưa có data inventory, approved repositories, retention schedule và bằng chứng rà soát/hủy | P1 | Chính sách ký duyệt; danh mục dữ liệu/kho; lịch retention; hồ sơ kiểm tra quyền và hủy |
| G03 | Sử dụng CNTT, phần mềm và license | Đã có dự thảo `POL-IT-02`; có quy trình Vendor/License | Chưa có danh sách phần mềm được phép, quy trình duyệt cài đặt, kiểm kê software/license có số liệu thực tế và bằng chứng đối soát | P1/P2 | Policy ký duyệt; software catalog; license reconciliation; ticket mua/cài/gỡ mẫu |
| G04 | BCP/DR và liên tục dịch vụ | Đã có chính sách khung `POL-BCDR-03`; quy trình Backup/Restore | Chưa có BIA được duyệt, BCP/DR theo dịch vụ, RTO/RPO chính thức, kịch bản liên lạc, runbook và biên bản diễn tập/restore test đủ phạm vi | P1 | BIA; RTO/RPO approval; service runbook; lịch kiểm thử; kết quả và CAPA |
| G05 | Phương pháp và hồ sơ rủi ro ATTT | Đã có dự thảo `POL-IS-04` | Chưa thấy Risk Register thực tế, phương án xử lý, owner/hạn, phê duyệt risk acceptance và xác minh hiệu lực kiểm soát | P1 | Methodology được duyệt; risk register; treatment plan; risk acceptance; evidence closure |
| G06 | Chính sách truy cập/định danh | Có quy trình Access Management và User Lifecycle | Chưa có policy/standard được duyệt, ma trận quyền theo vai trò, danh mục tài khoản đặc quyền, bằng chứng rà soát/thu hồi định kỳ | P1 | Access policy hoặc xác nhận tài liệu có hiệu lực; role matrix; privileged account review; leaver samples |
| G07 | SLA và quản trị ticket | Có SLA baseline, quy trình SLA và quy trình ticket | Response Time M-Service thiếu; chưa có phê duyệt lịch/định nghĩa pause, ma trận P1–P4, xác nhận cấu hình Bitrix và báo cáo vận hành thực | P1 | SLA signed-off; export cấu hình; test cases; báo cáo SLA và backlog ít nhất theo chu kỳ được duyệt |
| G08 | File Server/ACL | Có runbook File Server và policy dữ liệu dự thảo | Chưa có ACL export, Data Owner, đối chiếu nhóm quyền, test access denied/allowed và kế hoạch xử lý PUBLIC root được duyệt | P1 | ACL inventory; approval; test evidence; change record; biên bản rà soát quyền |
| G09 | Asset Register và vòng đời | Có quy trình tài sản, CSV register và form bàn giao | Chưa có đối soát vật lý với register, tỷ lệ coverage, serial/owner/location, chu kỳ kiểm kê và kết quả xử lý sai lệch | P2 | Asset export; biên bản inventory; reconciliation report; phê duyệt xử lý chênh lệch |
| G10 | As-built network/CMDB/configuration | Có template sơ đồ và quy trình thay đổi/giám sát | Chưa có sơ đồ as-built được duyệt theo site, CMDB/CI dependency map và bằng chứng cập nhật sau change | P1/P2 | Sơ đồ hiện trạng; CI register; mẫu change cập nhật sơ đồ; xác nhận IT Network/Owner |
| G11 | Zabbix monitoring/alerting | Có hướng dẫn và quy trình giám sát | Chưa có monitoring matrix được duyệt, ngưỡng/recipient/on-call, coverage thực tế và test alert tới ticket/escalation | P1 | Host register đầy đủ; trigger matrix; test alert; acknowledgement/incident record |
| G12 | Backup/Restore | Có quy trình, form test và chính sách khung BCP/DR | Chưa có inventory job/retention theo hệ thống, báo cáo thành công/thất bại, bản sao độc lập/immutable nếu yêu cầu, restore test đại diện và RPO/RTO approval | P1 | Backup report; config/retention approval; restore test evidence; measured recovery time/data loss; CAPA |
| G13 | Incident/security incident/RCA | Có quy trình Incident và Security Incident | Chưa có phân cấp/major incident criteria được duyệt, danh sách liên lạc, test tabletop, hồ sơ RCA/CAPA thực tế và bằng chứng lưu log/chain-of-custody | P1 | Major incident matrix; contact tree; incident samples; RCA; exercise results; evidence handling log |
| G14 | Vendor, domain, SSL và contract/license | Có quy trình Vendor/License và register CSV | Chưa có register đối soát thực tế, hợp đồng, owner, renewal alert test, đánh giá rủi ro nhà cung cấp và review quyền truy cập bên thứ ba | P2 | Register hoàn chỉnh; hợp đồng/renewal evidence; vendor review; vendor access expiry evidence |
| G15 | Sử dụng CNTT và software inventory | Có policy dự thảo; quy trình Asset/Request/Vendor | Chưa thấy kiểm kê phần mềm cài đặt thực tế, tiêu chuẩn hỗ trợ/patching, approval mẫu và xử lý phần mềm không phép | P1/P2 | Inventory từ endpoint/server; software baseline; ticket/approval; remediation report |
| G16 | Daily checklist và vận hành hằng ngày | Có checklist nguồn và quy trình daily ops | Chưa có checklist đã chạy qua nhiều kỳ, ngưỡng được duyệt, ticket cho Fail, bàn giao ca và rà soát action | P1/P2 | Biểu mẫu có timestamp/owner/evidence; sample records; ticket link; weekly review |
| G17 | Tài liệu, hồ sơ và retention | Có quy trình document control, policy data dự thảo và register | Chưa có retention schedule được duyệt, nơi lưu chính thức, access matrix, kiểm tra link/version và bằng chứng thu hồi bản lỗi thời | P2 | Approved document/record policy; retention schedule; register; sample retrieval and obsolete-document check |
| G18 | Đào tạo, năng lực và truyền thông | Có các tài liệu quy trình dự thảo | Chưa có kế hoạch đào tạo, phân nhóm đối tượng, xác nhận hiểu biết, hồ sơ tham gia và hướng dẫn ngày hiệu lực | P2 | Training plan/material; attendance; acknowledgement; assessment results |
| G19 | Đánh giá nội bộ và xem xét của lãnh đạo | Có quy trình audit/CAPA dự thảo | Chưa thấy chương trình audit được phê duyệt, auditor competency/independence, kết quả đánh giá thực tế, management review minutes | P2 | Audit programme/report; CAPA register; review minutes; assigned decisions/resources |
| G20 | KPI và cải tiến | Có KPI đề xuất trong handbook/quy trình | Chưa duyệt định nghĩa, nguồn dữ liệu, target, owner, chu kỳ và báo cáo baseline; chưa có số đo liên tục chứng minh hiệu lực | P2 | KPI dictionary; dashboard/report; data validation; meeting minutes/action |
| G21 | Biểu mẫu và sổ đăng ký | Có 8 CSV forms và 5 CSV registers trong repository | Chưa xác minh mẫu được chấp nhận trong công cụ nghiệp vụ, có owner/approval/version và đã thực sự được người dùng áp dụng | P2 | Approved templates; test completed forms; register sample data; controlled storage and access |
| G22 | An toàn vật lý/phòng máy | Có các mục trong Daily Checklist nguồn | Chưa thấy biên bản kiểm tra thực địa, kiểm soát ra vào, môi trường, điện/UPS và phương án ứng phó theo site | P2 | Site inspection; access/visitor logs; environmental/UPS checks; corrective actions |
| G23 | Pháp lý/khách hàng/nhà cung cấp | Có hợp đồng/vendor content cần tổng hợp | Chưa có register nghĩa vụ pháp lý/contractual, owner, thời hạn, điều khoản dữ liệu/notify và bằng chứng review | P2 | Obligations register; contract review; owner/renewal; compliance evidence |

## 4. Những vấn đề bắt buộc phải chốt trước khi ban hành

1. SLA Response Time của M-Service còn thiếu số trong hồ sơ gốc — phải do Service Owner/Ban Giám đốc quyết định.
2. Xác định “Days” là ngày lịch hay ngày làm việc đối với SLA sửa chữa/bảo hành/nội bộ; xác định múi giờ, lịch làm việc, ngày nghỉ và pause/resume.
3. Phê duyệt người nhận escalation, ma trận P1–P4 và tiêu chí Major Incident.
4. Xác minh quyền PUBLIC root/File Server, chính sách xác thực hiện hành và tình trạng secret trong các file bàn giao cũ.
5. Chốt taxonomy tài sản và đối soát `TAISAN.MSTARCORP.VN` với asset register chuẩn.
6. Chốt ngưỡng Zabbix, dịch vụ trọng yếu, tuyến nhận cảnh báo và thời gian trực.
7. Chốt BIA, RTO/RPO, retention backup và lịch kiểm thử restore theo từng dịch vụ.
8. Phê duyệt phân loại thông tin, kho lưu được phép, retention schedule và phương pháp hủy.
9. Phê duyệt phương pháp rủi ro, ngưỡng chấp nhận và thẩm quyền ký nhận rủi ro còn lại.
10. Xác định phạm vi SMS/ISMS, chủ quy trình, người thay thế, ngày hiệu lực và nguồn lưu bằng chứng chính thức.

## 5. Kế hoạch khắc phục theo giai đoạn

| Giai đoạn | Công việc | Chủ trì đề xuất | Đầu ra nghiệm thu |
|---|---|---|---|
| 0 — Ngăn rủi ro tức thời | Kiểm tra secret trong file bàn giao; xác minh ACL rủi ro; kiểm tra backup job và cảnh báo trọng yếu; ghi lại ticket/rủi ro | IT Lead + System/Network Owner + Data Owner | Ticket/biên bản kiểm tra, secret rotation nếu cần, ACL/change approval, backup status |
| 1 — Chốt quản trị | Chốt phạm vi, danh mục dịch vụ, owner/RACI, SLA/lịch/escalation, phân loại dữ liệu, thẩm quyền phê duyệt | IT Lead + Service Owner + BGĐ | Các chính sách/ma trận ký duyệt và Service Catalogue |
| 2 — Hoàn tất nền tảng | Đối soát tài sản, File Server ACL, sơ đồ as-built, Zabbix monitoring matrix, software/license inventory, record retention | Asset Owner + System/Network + Data Owner + Procurement | Register thực tế, sơ đồ xác nhận, các báo cáo đối soát và test evidence |
| 3 — Liên tục và rủi ro | BIA, RTO/RPO, runbook BCP/DR; Risk Register/Treatment Plan; tabletop/restore test | Service Owner + IT Lead + Risk Owner | BIA/RTO/RPO được duyệt, Risk Register, kế hoạch xử lý, biên bản kiểm thử và CAPA |
| 4 — Thử vận hành và đánh giá | Đào tạo; chạy thử ticket/SLA/access/change/backup/incident; đo KPI; audit nội bộ và management review | Process Owner + người đánh giá độc lập + BGĐ | Hồ sơ vận hành thực, báo cáo KPI, audit report, CAPA đã xác minh, biên bản xem xét |

Thời hạn và owner cá nhân phải được MSTAR phân công chính thức; bảng trên không tự gán ngày hoàn thành hoặc thay thế quyết định quản lý.

## 6. Tiêu chí đóng một khoảng trống
Mỗi mục chỉ được chuyển sang “Đã triển khai/Đã kiểm chứng” khi đủ cả: (1) tài liệu/chính sách được phê duyệt nếu cần; (2) owner được giao; (3) cấu hình/thao tác áp dụng thực tế; (4) bằng chứng thực hiện có ngày và người thực hiện; (5) kết quả được người có trách nhiệm rà soát; (6) hành động khắc phục được đóng hoặc rủi ro còn lại được chấp nhận đúng thẩm quyền.

## 7. Kết luận
10 hồ sơ gốc là nền tảng nghiệp vụ hữu ích nhưng **chưa đủ để kết luận hệ thống ITSM/ISMS đã triển khai đầy đủ**. Trong repository hiện có nhiều bản dự thảo quy trình/chính sách và biểu mẫu, nhưng bằng chứng vận hành thực tế, phê duyệt, cấu hình hệ thống và kết quả kiểm thử còn thiếu hoặc chưa được cung cấp. Ưu tiên trước là xử lý rủi ro bí mật xác thực/File Server/backup/monitoring; chốt SLA và thẩm quyền; sau đó hoàn thiện BIA/BCP/DR, risk register, kiểm kê và các bằng chứng vận hành. Không tuyên bố phù hợp/chứng nhận ISO chỉ dựa trên sự tồn tại của tài liệu.

## 8. Chính sách mới được tạo và tình trạng triển khai

| Tài liệu | Tình trạng repository | Điều chưa thể kết luận / cần xác minh | Bằng chứng cần có để xác nhận triển khai |
|---|---|---|---|
| [POL-IS-01 — Phân loại thông tin, chia sẻ và lưu trữ dữ liệu](../policies/POL-IS-01-CLASSIFICATION-SHARING-RETENTION.md) | Đã có bản dự thảo | Chưa có phê duyệt phân loại, data inventory, approved repository list, retention schedule và mẫu kiểm tra chia sẻ/hủy | Bản ký duyệt; danh mục dữ liệu/kho; quyền chia sẻ; lịch lưu; log rà soát và biên bản hủy |
| [POL-IT-02 — Sử dụng CNTT, cài phần mềm và bản quyền](../policies/POL-IT-02-ACCEPTABLE-USE-SOFTWARE-LICENSING.md) | Đã có bản dự thảo | Chưa có danh mục phần mềm được phép, inventory phần mềm cài thực tế, đối soát license và hồ sơ yêu cầu/cài đặt đã nghiệm thu | Policy được duyệt; software inventory; license reconciliation; ticket/approval; báo cáo xử lý phần mềm không phép |
| [POL-BCDR-03 — Chính sách BCP/DR](../policies/POL-BCDR-03-IT-SERVICE-CONTINUITY-DISASTER-RECOVERY.md) | Đã có chính sách khung dự thảo | Chưa thấy BIA được duyệt, RTO/RPO đã duyệt theo từng dịch vụ, runbook khôi phục và biên bản diễn tập đủ phạm vi | BIA; approval RTO/RPO; recovery runbooks; contact tree; kết quả tabletop/restore/DR test; CAPA |
| [POL-IS-04 — Phương pháp đánh giá và xử lý rủi ro ATTT](../policies/POL-IS-04-INFOSEC-RISK-ASSESSMENT-TREATMENT.md) | Đã có phương pháp dự thảo | Chưa thấy Risk Register thực tế, kế hoạch xử lý có owner/hạn, quyết định chấp nhận rủi ro còn lại và xác minh hiệu lực | Methodology phê duyệt; Risk Register; Treatment Plan; residual risk approval; bằng chứng đóng kiểm soát |

## 9. Link báo cáo và quản trị khắc phục
- [Master Index — danh mục tài liệu chuẩn](../../ITSM-MASTER-INDEX.md).
- [Danh mục quyết định và checklist ban hành](08-DANH-MUC-PHE-DUYET-TRUOC-BAN-HANH.md).
- [Bảng đối chiếu ISO/IEC 20000-1 và ISO/IEC 27001](11-DOI-CHIEU-ISO-20000-1-ISO-27001.md).

**Kết luận quản trị:** tài liệu chính sách/phương pháp đã được tạo trong repository chỉ là “đã có dự thảo”. Chưa xác nhận trạng thái APPROVED, IMPLEMENTED hoặc EFFECTIVE nếu chưa có chữ ký/phê duyệt, cấu hình hoặc thao tác thực tế và hồ sơ bằng chứng được người có trách nhiệm rà soát.