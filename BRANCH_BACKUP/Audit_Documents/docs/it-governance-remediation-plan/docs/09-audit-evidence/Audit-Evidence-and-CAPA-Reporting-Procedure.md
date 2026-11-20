# Audit Evidence and CAPA Reporting Procedure

- **Document ID:** UOA-IT-AUD-PRO-001
- **Version:** 0.1 — Draft
- **Status:** For review; not approved
- **Process owner:** IT governance / designated CAPA owner (to confirm)

## 1. Purpose

Standardize how audit issues are assessed, evidence is collected, corrective actions are tracked and management receives an accurate status report.

## 2. Fundamental evidence rules

1. Separate reported allegations, verified facts, hypotheses, proposed controls and unresolved questions.
2. Every finding must link to source evidence, affected control/process, risk, accountable owner and corrective action.
3. Do not fabricate, backdate, selectively alter or misrepresent approvals, records, logs or test results.
4. Preserve original evidence and its context. Use a controlled evidence repository with access restrictions and appropriate retention.
5. Record evidence references in Git; do not commit raw sensitive production data, credentials, private keys or unnecessary personal data.
6. Evidence should demonstrate both design and operation of a control. A policy draft alone does not prove that the control is implemented or effective.
7. Use sampling methods and periods approved by the audit/control owner; document sample population, selection, exceptions and limitations.
8. A finding may be closed only after required remediation evidence and effectiveness testing are reviewed by an authorized independent or designated reviewer.

## 3. Evidence record fields

| Field | Entry |
|---|---|
| Evidence ID | Unique stable reference |
| Related finding / risk / CAPA ID | Linked identifiers |
| Control/process | Name and version |
| Evidence description | What the artifact demonstrates |
| Source system / record owner | Verified source |
| Period covered / timestamp | Include timezone where relevant |
| Collection method and collector | Read-only method where feasible |
| Integrity / completeness notes | Limitations and gaps |
| Sensitivity / access restriction | Classification and permitted audience |
| Storage location | Approved restricted repository reference |
| Hash or integrity check | If appropriate and supported |
| Reviewer / review date | Independent or designated review |
| Conclusion and limitations | What evidence supports and does not support |

## 4. CAPA workflow

### Step 1 — Register and validate
Assign finding/CAPA ID, source, date, issue statement, affected process and evidence. Label unverified reports as “Pending validation”.

### Step 2 — Assess risk
Document impact, likelihood, existing controls, evidence gaps, risk owner and priority. Use approved risk criteria; do not invent an official rating.

### Step 3 — Analyze cause
Distinguish immediate cause, contributing factors and systemic root cause. Use evidence-based analysis; do not assign blame without support.

### Step 4 — Define corrective/preventive action
Each action must include expected control outcome, owner, due date, dependencies, deliverable/evidence and interim mitigation for high risks.

### Step 5 — Implement and verify
Collect implementation evidence and confirm the control operates on a representative sample or test. Record failed tests and remediation.

### Step 6 — Review effectiveness
After a suitable operating period or sample, assess whether the control is consistently followed and reduces the risk. Define sample size and period with the reviewer/auditor; do not claim effectiveness from a single policy publication.

### Step 7 — Close or reopen
Record reviewer decision, evidence references, residual risk and management acceptance where permitted. Reopen if testing fails or new evidence contradicts closure.

## 5. Management reporting

The report should show:
- Reporting date and scope
- Findings by status and risk/priority
- Verified versus pending-validation status
- CAPA actions completed, overdue and due soon
- High-risk interim controls and residual risks
- Evidence gaps and decisions needed from management
- Effectiveness test results and failed/reopened items
- Owners and target dates
- Constraints or dependencies that could affect completion

Use factual status labels such as **Not started**, **In progress**, **Implemented — effectiveness pending**, **Effective — verified**, **Overdue**, **Blocked**, and **Closed with approved residual risk**. “Implemented” must not be presented as “effective” without test evidence.

## 6. Closure checklist

- [ ] Original issue and evidence are preserved.
- [ ] Finding status distinguishes reported concern from verified fact.
- [ ] Root cause or reason for unresolved cause is documented.
- [ ] Corrective action is linked to an accountable owner and due date.
- [ ] Required policy/procedure/system changes are approved.
- [ ] Implementation evidence is stored and accessible to authorized reviewers.
- [ ] Operating effectiveness test has defined scope, period and result.
- [ ] Exceptions and residual risks are explicitly approved by authorized roles.
- [ ] Independent/designated reviewer recorded closure decision.
- [ ] Management report reflects the correct status.

## 7. Change history

| Version | Date | Change |
|---|---|---|
| 0.1 | 2026-10-09 | Initial draft |
