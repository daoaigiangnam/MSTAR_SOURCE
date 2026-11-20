# Change Request and Approval Procedure

- **Document ID:** UOA-IT-CHG-PRO-001
- **Version:** 0.1 — Draft
- **Status:** For review; not approved
- **Process owner:** IT owner (to confirm)

## 1. Purpose

Define how a proposed IT change is requested, assessed, authorized, scheduled, implemented and closed.

## 2. Procedure

### Step 1 — Submit request

Requester opens a ticket/change record before work begins and records:
- Business reason and expected outcome
- Affected systems, assets, users and customers
- Proposed change, scope and exclusions
- Requested window and expected downtime
- Dependencies, vendor involvement and communications
- Urgency and consequences of delay

### Step 2 — Triage and classify

IT checks completeness, duplicates, affected service owners and whether the request qualifies as an already approved standard change. Otherwise classify as normal or emergency under the policy. The classification decision must be recorded.

### Step 3 — Assess risk and impact

Document security, access, privacy/data, availability, backup/recovery, network, compatibility, capacity, customer and rollback impacts. Identify dependencies and required stakeholder approvals. If material information is unknown, record it as an open risk rather than assuming it is safe.

### Step 4 — Prepare implementation and rollback plans

Include prerequisites, backup/configuration capture, ordered commands/actions at an appropriate operational level, validation tests, monitoring, communication, rollback trigger, rollback steps and decision owner. Avoid placing secrets in the ticket or repository.

### Step 5 — Test

Test in a representative non-production environment where feasible. Record the environment, test cases, expected results, actual results and defects. If testing cannot be performed, document why, compensating controls and explicit approval.

### Step 6 — Obtain approval

Obtain approval from the relevant business/service owner and technical/change authority in accordance with the approved matrix. Changes involving customer data, security controls, access rights, backup destinations or material downtime require explicit approval by the designated owner. Approval must be timestamped and traceable to a person or authorized role.

### Step 7 — Schedule and notify

Confirm maintenance window, outage expectations, support coverage, monitoring, contact/escalation route and affected-user/customer communications. Check for conflicts with other changes or business-critical periods.

### Step 8 — Implement

Authorized implementer follows the approved plan. Record actual start/end time, actions, deviations, results and unexpected events. Material scope changes require re-assessment and approval before proceeding, unless immediate action is required under the emergency procedure.

### Step 9 — Verify or rollback

Run the predefined post-change checks. If success criteria fail or risk exceeds the agreed threshold, pause, escalate and execute the approved rollback/recovery plan where safe. Record decision and result.

### Step 10 — Close

Record final status, evidence references, stakeholder notification, incident links, residual risks, follow-up actions and closure approval. Do not close merely because implementation commands completed.

## 3. Minimum change record template

| Field | Required entry |
|---|---|
| Change ID / linked ticket | |
| Requester / date | |
| Business reason / expected outcome | |
| Change category and risk | |
| Affected service/assets | |
| Business owner / technical owner | |
| Impact, dependencies and outage | |
| Security/data/backup assessment | |
| Test plan and results | |
| Implementation plan | |
| Rollback plan and trigger | |
| Approver(s) and timestamps | |
| Planned / actual window | |
| Implementer | |
| Post-change verification | |
| Incident/rollback/deviation | |
| Communications | |
| Evidence reference(s) | |
| Closure decision / owner | |
| Lessons learned / actions | |

## 4. Approval integrity

- Do not fabricate, backdate or infer approval from a meeting, chat reaction or lack of response.
- If work was performed without an appropriate record, record it as an exception/unauthorized change and assess risk; do not create a false prior approval.
- Preserve original records and link corrective documentation rather than overwriting historical evidence.

## 5. Change history

| Version | Date | Change |
|---|---|---|
| 0.1 | 2026-10-09 | Initial draft |
