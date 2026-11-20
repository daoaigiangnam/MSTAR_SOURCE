# IT Governance and CAPA Dashboard Specification

- **Document ID:** UOA-IT-GOV-SPEC-001
- **Version:** 0.1 — Draft
- **Status:** For review; not approved
- **Business owner:** UOA management / designated governance sponsor (to confirm)
- **Data owner:** CAPA register owner (to confirm)

## 1. Purpose

Define a factual management view of IT audit remediation, risks, actions, evidence and control effectiveness. This is a specification, not a claim that a dashboard has been built or connected to live systems.

## 2. Dashboard principles

- Use the approved CAPA/risk registers as the source of truth.
- Every count must have a defined source, reporting date, filter and status definition.
- Separate unvalidated reported concerns from verified findings.
- Separate action implementation from effectiveness verification.
- Do not count a policy draft as a completed operational control.
- Display overdue items and residual risk without hiding blocked dependencies.
- Restrict sensitive details; management summaries should use IDs and concise descriptions.

## 3. Recommended views

### Executive summary
- Total findings by status and priority
- Verified findings vs pending validation
- Open high-priority risks
- CAPA actions overdue / due within the next approved reporting window
- Implemented actions awaiting effectiveness test
- Controls verified effective
- Blocked actions and decisions required from management

### CAPA tracker
- Finding/CAPA ID and short title
- Source and validation status
- Risk/priority and rationale
- Corrective action and control objective
- Accountable owner and due date
- Current status and last update
- Implementation evidence ID
- Effectiveness test plan/result
- Residual risk / acceptance reference
- Blocker and escalation status

### Control effectiveness
- Control ID and owner
- Test period, population and sample size
- Pass/fail/partial/unable-to-test counts
- Exceptions and associated CAPA
- Reviewer and conclusion
- Retest due date and result

### Evidence readiness
- Required evidence items by control
- Evidence received / missing / expired / access restricted
- Source system and evidence ID
- Evidence reviewer and review date
- Limitations and outstanding request

## 4. Status definitions

| Status | Definition |
|---|---|
| Reported — pending validation | Concern has been raised but evidence review is incomplete |
| Verified — action not started | Finding is supported by evidence and no corrective action has begun |
| In progress | Approved corrective action is being implemented |
| Implemented — effectiveness pending | Change is in place but operating effectiveness has not yet been demonstrated |
| Effective — verified | Defined test passed and authorized reviewer accepted the evidence |
| Blocked | Action cannot proceed due to documented dependency/decision |
| Overdue | Approved due date passed without accepted closure |
| Closed with residual risk | Authorized risk acceptance and closure evidence exist |
| Reopened | Retest failed or new evidence invalidated closure |

## 5. Data model

Recommended entities:
- Finding: ID, source, statement, validation status, evidence IDs, risk ID
- Risk: ID, scenario, impact, likelihood, rating method, owner, treatment
- CAPA action: ID, finding ID, action, owner, due date, status, dependencies
- Control test: ID, control ID, period, population, sample, result, reviewer
- Evidence: ID, type, source, timestamp, storage reference, sensitivity, review status
- Decision/exception: ID, decision, approver, date, scope, expiry
- Reporting snapshot: reporting date, filters, totals and source version

Use stable IDs and explicit relationships. Avoid storing raw personal/customer data in the dashboard dataset unless approved and necessary.

## 6. Data quality controls

- Require owner, status, due date and evidence reference for applicable actions.
- Validate that closed CAPA items have implementation evidence and closure reviewer.
- Flag “Effective — verified” if effectiveness test fields are missing.
- Flag overdue due dates and exceptions past expiry.
- Preserve status history rather than overwriting prior states.
- Reconcile dashboard totals with the source register before management publication.
- Record the reporting cutoff date and data freshness.

## 7. Reporting cadence and governance

UOA must approve the reporting cadence, escalation thresholds and audience. A suggested starting point is a periodic operational review with management escalation for material overdue/high-risk items; cadence should be agreed rather than assumed. Each report should identify data owner, preparation date and unresolved data-quality limitations.

## 8. Acceptance criteria

A future dashboard implementation is acceptable only when:
- Source registers and owners are approved.
- Status definitions are agreed.
- Totals reconcile to source records.
- Permissions restrict sensitive details appropriately.
- Data freshness and failed imports are visible.
- Sample records can be traced from dashboard to source evidence.
- Management validates a sample of findings and actions.
- The dashboard does not label implementation as effective without test evidence.

## 9. Approval

| Role | Name | Decision | Date |
|---|---|---|---|
| Management sponsor | To confirm | Pending | — |
| CAPA register owner | To confirm | Pending | — |
| IT/governance owner | To confirm | Pending | — |

## 10. Change history

| Version | Date | Change |
|---|---|---|
| 0.1 | 2026-10-09 | Initial specification |
