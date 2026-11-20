# IT Governance RACI Matrix

- **Document ID:** UOA-IT-GOV-RACI-001
- **Version:** 0.1 — Draft
- **Status:** For review; not approved
- **Purpose:** Propose accountable roles for UOA governance processes; confirm actual role names and delegated authorities before approval.

## 1. RACI definitions

- **R — Responsible:** Performs the work.
- **A — Accountable:** Owns the outcome and final decision. Aim for one A per activity.
- **C — Consulted:** Provides input before decision/action.
- **I — Informed:** Receives relevant updates.

Role abbreviations:
- **MGMT:** UOA management / delegated authority
- **BO:** Business or data owner / line manager
- **HR:** HR/Admin or authoritative personnel owner
- **IT:** IT operations / IT owner
- **PROC:** Procurement/Finance/contract owner
- **SEC:** Designated security/compliance role, if appointed
- **AUD:** Internal Audit (independent assurance, not operational control owner)
- **VEND:** Approved service provider/vendor

## 2. Proposed responsibility matrix

| Activity | MGMT | BO | HR | IT | PROC | SEC | AUD | VEND |
|---|---|---|---|---|---|---|---|---|
| Approve IT/security policies | A | C | C | R | C | C | I | I |
| Own business data classification | I | A/R | C | C | I | C | I | I |
| Approve user business need/access | I | A/R | C | C | I | C | I | I |
| Confirm joiner/mover/leaver event | I | C | A/R | I | I | I | I | I |
| Implement approved account changes | I | C | C | A/R | I | C | I | C |
| Approve privileged/VPN access | A* | A* | I | R | I | C | I | C |
| Maintain asset register | I | C | C | A/R | C | C | I | C |
| Approve IT purchase/budget | A* | A/R* | I | C | R | C | I | C |
| Approve cloud storage/data location | A* | A/R* | I | R | C | C | I | C |
| Operate backup jobs | I | C | I | A/R | I | C | I | C |
| Approve RPO/RTO and recovery priority | I | A/R | I | C | I | C | I | C |
| Authorize production changes | A* | A* | I | R | I | C | I | C |
| Respond to IT/security incidents | A* | C | C | R | I | R* | I | C |
| Manage vendor contract/access | I | A* | I | R | A/R* | C | I | R |
| Maintain CAPA register | I | C | C | R | C | C | I | I |
| Accept residual business risk | A/R* | C | I | C | C | C | I | I |
| Independently test controls | I | C | C | C | C | C | A/R* | I |

* **Important:** Asterisked assignments are proposals requiring UOA confirmation. The matrix is not a grant of authority. Where two roles appear with A*, management must select the actual accountable role for each activity before approval.

## 3. Governance rules

1. Confirm the actual job titles and delegated authority of every role.
2. Where practicable, the requester should not be the sole approver and implementer of a material access grant or production change.
3. HR/Admin confirms personnel events; it does not automatically approve every technical permission.
4. Business/data owners approve business need and data access; IT implements approved scope.
5. IT should not approve its own exceptions to a control without the designated independent authority.
6. Internal Audit remains independent and should not operate controls it later audits.
7. If no designated security/compliance role exists, management must formally assign responsibility rather than leave the role ambiguous.
8. Record substitutions/delegations and effective dates.

## 4. Approval worksheet

| Activity needing decision | Proposed accountable role | Confirmed role/person | Delegation reference | Approval date |
|---|---|---|---|---|
| Access approvals | To confirm | | | |
| VPN/privileged access | To confirm | | | |
| Cloud/backup ownership | To confirm | | | |
| Production change approval | To confirm | | | |
| Emergency incident authority | To confirm | | | |
| Risk acceptance | To confirm | | | |
| CAPA closure | To confirm | | | |

## 5. Change history

| Version | Date | Change |
|---|---|---|
| 0.1 | 2026-10-09 | Initial proposed matrix; authority assignments require validation |
