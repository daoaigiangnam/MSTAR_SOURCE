# IT Audit Readiness — 30-Day Execution Plan

**Status:** Draft 0.1 — proposed plan for management approval  
**Purpose:** Move from drafting governance documents to validating controls, closing evidence gaps, and preparing a defensible audit handover.  
**Planning assumption:** Day 1 begins only after an authorized sponsor approves the plan and assigns owners. Dates, staffing and audit deadlines must be confirmed.

## 1. Operating rules

- Use the current controlled version of each procedure; drafts in this repository do not replace approved procedures.
- Establish the audit scope, period, systems and evidence rules with the authorized Audit contact.
- Do not claim a control is compliant or a finding is closed until supported by reviewed evidence and, where appropriate, retesting.
- Preserve source timestamps. Do not backdate approvals or reconstruct missing evidence as contemporaneous.
- Keep restricted production exports, personal data, credentials and keys in approved restricted storage; reference evidence IDs in this repository.
- Production remediation must follow authorized incident/change processes and consider business impact.
- Do not move or delete existing backup data until ownership, target, integrity, restore and deletion approvals are verified.

## 2. Work plan by week

| Period | Main activities | Required outputs | Exit gate |
|---|---|---|---|
| Days 1–5: Mobilize and scope | Confirm sponsor, audit liaison, control owners, scope, period, evidence channel and escalation path; obtain controlled QP-HRD-16/17/18/22/23/24 versions; confirm authoritative inventories and source owners | Approved scope and RACI; controlled-document register; evidence request tracker; prioritized risk/finding list | Owners and evidence sources assigned; scope limitations documented |
| Days 6–10: Collect and validate | Collect access approval, VPN/privileged, joiner/mover/leaver, backup ownership, backup job and restore evidence; record evidence IDs and limitations; establish populations before selecting samples | Evidence register; populated sample frames; access and backup validation records; unresolved-evidence list | High-risk evidence requests reviewed; no invented sample data |
| Days 11–15: Test controls | Execute risk-based tests against defined criteria; reconcile actual permissions to approvals; test leaver revocation; inspect purchase/software samples; run authorized restore tests where ready | Completed test records; evidence-linked exceptions; factual findings with criterion and impact | Each conclusion has a criterion, source evidence and reviewer |
| Days 16–20: Remediate and contain | Assign CAPA owners and dates; approve interim mitigations; implement authorized changes; document root causes and dependencies; obtain management decisions for risk acceptance | Approved CAPA plans; change/incident references; interim-control evidence; decision log | No critical exposure left without an assigned escalation/decision |
| Days 21–25: Retest and effectiveness | Retest completed actions; check actual operating evidence, not just documents; investigate failures and reopen/extend CAPA where needed; validate backup restore results | Retest records; effectiveness checks where warranted; updated CAPA register | Closure gates met or item remains open with reason and risk |
| Days 26–30: Audit handover | Reconcile management status to source evidence; prepare sanitized evidence index; record limitations, open findings and accepted risks; obtain review and authorization before sharing with Audit | Management status report; evidence package index; open-risk and decision log; handover record | Authorized reviewer signs off package; no unsupported closure claims |

## 3. Workstream ownership

| Workstream | Accountable role to assign | Supporting roles | Minimum output |
|---|---|---|---|
| Overall coordination and reporting | Executive sponsor / IT lead to confirm | Audit liaison, control owners | Approved scope, weekly status, decision log |
| Access approvals and VPN | IT/security owner to confirm | Requesting managers, system/data owners | Reconciled access inventory and test records |
| Joiner/mover/leaver | HR process owner to confirm | IT, line managers, system owners | Event-to-access traceability and revocation results |
| Backup ownership and recovery | Data owner and backup/service owner to confirm | Account owner, procurement/legal as needed, IT | Ownership/access decisions, restore-test evidence |
| Software and procurement | Process owners to confirm | IT asset owner, procurement/finance | Purchase/installation test results and asset reconciliation |
| Evidence governance | Audit coordinator to assign | Evidence owners, reviewer | Evidence register, custody/handover record |
| CAPA and risk acceptance | Authorized management owner to confirm | Finding owners, IT lead, risk/compliance | Approved actions, due dates, risk decisions, retest/closure evidence |

This table assigns roles by function only; it does not assume a named person or grant authority.

## 4. Suggested weekly reporting

Report at least:
- High-priority findings open, overdue, and without interim control
- Evidence requests not received or not sufficient
- Access samples tested, exceptions found, and leaver cases with unresolved revocation evidence
- Backup datasets with confirmed owner, approved storage/access, and successful restore test
- CAPAs implemented but not yet retested; retest failures; reopened items
- Decisions required, decision owner, due date and risk of delay
- Scope limitations and evidence not available

Do not use empty templates or planned actions as completed outputs. Show “not yet tested,” “inconclusive,” or “pending owner confirmation” where appropriate.

## 5. Stop/go gates

**Gate A — Scope:** Do not start formal sampling until the population source, period, criterion and sampling approach are documented.

**Gate B — Evidence:** Do not mark a control pass solely because a policy exists or an interview states that the control is performed.

**Gate C — Production changes:** Do not make access, network, backup or service changes without the required authorization, impact review and rollback/contingency plan.

**Gate D — Backup migration/deletion:** Do not remove the existing recovery path until data ownership, target authorization, completeness/integrity checks, successful restore and separate deletion approval are evidenced.

**Gate E — Audit handover:** Do not disclose restricted evidence until recipient, authorization, transfer channel and package scope are approved and recorded.

**Gate F — CAPA closure:** Do not close findings without implementation evidence and required retest/effectiveness approval.

## 6. Management decisions required before Day 1

1. Approve the 30-day plan or provide a revised timeline.
2. Name the executive sponsor, audit liaison and accountable owners for each workstream.
3. Confirm the next Audit date, scope, period, and evidence handover requirements.
4. Approve the evidence repository, access restrictions and disclosure authority.
5. Confirm the risk methodology, escalation threshold, and CAPA closure approver.
6. Confirm business-approved RPO/RTO and data owners for critical backup datasets.
7. Confirm the authorized procedure versions and approval matrix.
8. Decide how to handle unavailable historical approvals: document the gap transparently, assess risk, and approve a corrective path without backdating.

## 7. Plan approval

- Executive sponsor:
- Audit liaison:
- IT lead:
- HR owner:
- Data/backup owner:
- Risk/compliance reviewer:
- Approval date:
- Confirmed Audit date:
- Approved exceptions / dependencies:

**Approval note:** This is a proposed execution plan, not evidence that any activity has been completed or any audit finding has been closed.
