# Joiner–Mover–Leaver (JML) Procedure

- **Document ID:** UOA-IT-IAM-PRO-001
- **Version:** 0.1 — Draft
- **Status:** For review; not approved
- **Process owner:** HR/Admin with IT (to confirm)

## 1. Purpose

Ensure account creation, access changes and access removal are initiated by an authoritative personnel event, approved by the correct business owner and completed with traceable evidence.

## 2. Source process references

This draft is intended to align with:
- **QP-HRD-22 — IT Account and Equipment Provisioning for New Employee**
- **QP-HRD-23 — User Access Request and Authorization**
- **QP-HRD-24 — User Account and Access Termination**

The detailed source flowcharts must be compared with this procedure before approval. Where the existing approved flowcharts assign different roles or steps, use formal change control to reconcile them; do not silently overwrite the source process.

## 3. Core controls

1. IT must not create or grant access solely on an informal verbal request when the approved process requires documented authorization.
2. HR/Admin or another formally designated personnel owner provides the authoritative joiner, mover and leaver notice.
3. The line manager/business owner approves the business need and role-specific access.
4. System/data owners approve access to their systems or data where required by the access matrix.
5. IT implements only the approved scope and records the operator, date/time, systems and result.
6. Access must be least-privileged, role-based where feasible and separated for incompatible duties.
7. VPN, privileged, remote administration and sensitive-data access require the additional approvals specified in the approved access policy.
8. Use named accounts; shared or service accounts require documented ownership and special handling.
9. Leaver revocation must cover interactive access, remote access, sessions/tokens, privileged roles, shared secrets where applicable, physical access interfaces and company equipment, according to system capability and approved timing.
10. Preserve business data and transfer ownership before disabling/deleting accounts when needed. Do not delete records before retention and legal/contractual needs are checked.
11. Never fabricate or backdate approvals. If evidence is missing, record the exception and investigate.
12. Urgent termination or suspected compromise must be escalated immediately through the authorized route, with actions recorded as soon as practicable.

## 4. Joiner — new employee/contractor

1. HR/Admin submits the approved personnel notification with identity, department, manager, start date, employment type and required systems.
2. Manager requests role-specific access and equipment; system/data owners approve sensitive or non-standard access.
3. IT validates approval completeness, unique identity and requested role.
4. IT provisions named accounts, groups, licenses and equipment using approved baselines.
5. Require secure initial credential setup and MFA where supported and required.
6. Test access and ensure it matches the approved scope; do not grant broad administrative access as a convenience.
7. Record account identifiers, assigned roles, equipment, completion timestamps and evidence references.
8. Manager/user confirms readiness and receives applicable security guidance.

## 5. Mover — department/role/status change

1. HR/Admin or manager records the effective date and new role/department.
2. Manager requests new access and identifies access that is no longer needed.
3. Relevant system/data owners approve changed access.
4. IT adds approved access and removes obsolete access; avoid accumulating roles from previous positions.
5. Validate separation-of-duties and privileged-access implications.
6. Record changes, approvers, effective time, verification and exceptions.

## 6. Leaver — termination/end of engagement

1. HR/Admin sends the authoritative termination notice with effective date/time and handling priority through an approved restricted channel.
2. IT identifies accounts, systems, VPN/remote access, privileged roles, sessions/tokens and assigned devices from the current inventory.
3. At the authorized effective time, disable/revoke access according to the approved leaver plan; suspected compromise or urgent cases follow incident escalation.
4. Transfer business-owned data and service/account ownership to an approved successor where required.
5. Recover company equipment and record condition/accessories; apply approved data preservation, wipe or reimage steps.
6. Revoke licenses or reassign them according to license terms.
7. Validate high-risk access removal and record any system that could not be disabled, reason, compensating control, owner and deadline.
8. Obtain closure confirmation from IT and the designated HR/Admin/manager owner.
9. Retain evidence and logs according to the approved retention policy.

## 7. Required timing

UOA must approve specific provisioning and revocation service targets, including after-hours and urgent termination cases. Until approved, follow the existing authorized process and record actual notification/effective/completion times. Do not invent a universal deadline.

## 8. Minimum JML record

| Field | Entry |
|---|---|
| JML ticket / personnel reference | |
| Event type | Joiner / Mover / Leaver |
| Effective date/time and timezone | |
| HR/Admin notification reference | |
| Manager / business owner approval | |
| Systems / roles / equipment in scope | |
| System/data owner approvals | |
| VPN / privileged access decision | |
| IT operator and completion timestamps | |
| Access/equipment changes completed | |
| Validation / exception | |
| Data ownership transfer | |
| Evidence reference | |
| HR/Admin / manager closure | |

## 9. Change history

| Version | Date | Change |
|---|---|---|
| 0.1 | 2026-10-09 | Initial draft; reconcile with QP-HRD-22/23/24 before approval |
