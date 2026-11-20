# CAPA Prioritization and Closure Gate

**Status:** Draft 0.1 — for review; does not approve, close, or reclassify any actual finding.  
**Purpose:** Provide a consistent way to prioritize audit corrective actions and prevent premature closure without evidence.

## 1. Classification principles

Classify findings using the organization's approved risk methodology where one exists. If no approved scoring method is available, use the interim categories below and obtain management approval before adopting them. Do not invent an official score or claim that the categories are part of an approved policy.

| Interim priority | Typical indicators | Expected management response |
|---|---|---|
| P1 — Critical / urgent | Credible evidence of active unauthorized privileged access; material risk of data loss or exposure; backup recoverability not demonstrated for critical data; a critical control failure with immediate business impact | Prompt escalation to designated management/security owner; assess containment; assign accountable owner and due date; document risk and decision |
| P2 — High | Required approval evidence unavailable for sensitive access; material gap in leaver revocation; critical system lacks an effective owner/review; repeated control failure; backup ownership or access ambiguity with material impact | Prioritized remediation plan, interim control, accountable owner, target date and scheduled retest |
| P3 — Moderate | Incomplete inventory, inconsistent forms, limited evidence retention, or isolated non-critical control weakness with bounded impact | Corrective action with owner/date and follow-up verification |
| P4 — Low / improvement | Documentation clarity or process efficiency issue with no identified material control exposure | Planned improvement and periodic review |

Priority depends on actual impact, likelihood, exposure, scope, compensating controls, and applicable obligations. Escalate uncertain high-impact conditions for assessment rather than downgrading them because evidence is incomplete.

## 2. Required CAPA record

Each finding must contain:
- Finding ID and related audit/control reference
- Status of the assertion: verified fact / reported statement / hypothesis / unresolved
- Exact criterion and controlled document version
- Evidence IDs and source limitations
- Condition observed, scope/population and sample
- Business impact, likelihood, exposure and existing controls
- Priority and rationale under the approved method
- Root cause, supported by evidence or clearly marked as pending analysis
- Immediate containment/interim control, if needed
- Corrective and preventive actions with measurable acceptance criteria
- One accountable owner and target date per action
- Dependencies and management decisions required
- Risk acceptance reference and expiry/review date, if applicable
- Implementation evidence, retest procedure and sample
- Effectiveness result, closure approver and closure date

## 3. CAPA lifecycle and gates

| Gate | Required condition | Evidence needed | If gate is not met |
|---|---|---|---|
| G0 — Intake | Issue is recorded without overstating what is known | Source, date, reporter/owner role, evidence reference, status label | Keep as reported/unverified and investigate |
| G1 — Validate | Criterion and factual condition are established or limitations are explicit | Controlled criterion, actual records/logs, sample/population, reviewer decision | Keep open; request evidence or record an inconclusive result |
| G2 — Risk triage | Risk and urgency are assessed by authorized role | Impact/likelihood rationale, affected scope, interim measures, escalation | Escalate uncertainty or potential material impact |
| G3 — Action approval | Corrective action, owner, due date and acceptance criteria are agreed | Approved action plan and any required change/risk approval | Do not treat a proposed action as authorized |
| G4 — Implementation | Action is completed and evidenced | Change/ticket/configuration/procedure/training or other relevant record | Status remains implementation in progress |
| G5 — Retest | Control operates on a suitable post-implementation sample | Test record, evidence IDs, pass/fail/inconclusive decision | Reopen or extend CAPA; correct the cause |
| G6 — Effectiveness | Control remains effective over an appropriate period or repeated sample where risk warrants | Follow-up evidence, repeat sample or monitoring results | Keep open or reopen if failure recurs |
| G7 — Closure | Authorized reviewer accepts evidence and residual risk | Closure rationale, approver, date, linked retest and effectiveness evidence | Do not close |

## 4. Minimum closure tests

Before closure, answer all applicable questions:
- [ ] Was the exact requirement and procedure version identified?
- [ ] Is the original finding based on verified evidence, or is uncertainty explicitly recorded?
- [ ] Has the root cause been addressed rather than only the visible symptom?
- [ ] Was the approved action implemented in the real operating environment?
- [ ] Is there evidence of implementation with source and timestamp?
- [ ] Was the control retested using a defined method and appropriate sample?
- [ ] Did the retest pass? If not, was the CAPA reopened or extended?
- [ ] Was effectiveness checked where risk or recurrence warrants it?
- [ ] Are remaining risks accepted by an authorized role, with expiry/review date and compensating controls where required?
- [ ] Has a reviewer independent of the action executor approved closure where required?
- [ ] Are evidence links, owner, dates and decision traceable?

A policy or form being published is not, by itself, evidence that a control operates. Training attendance is not, by itself, proof of correct execution.

## 5. Overdue and blocked actions

For each overdue item, record:
- Original due date and current forecast date
- Reason for delay and evidence of dependency
- Current risk and interim control
- Decision-maker and escalation date
- Revised date and approval
- Whether the audit response must disclose the open status

Do not silently extend deadlines or represent open items as closed. Repeated extensions require review of the action's adequacy and risk.

## 6. Reporting metrics

Report counts by priority and state, but do not use counts alone as proof of risk reduction:
- Open findings by priority
- Findings with overdue actions
- Actions awaiting approval or blocked by dependency
- Implementation complete but retest pending
- Retest failures and reopened CAPAs
- Closed CAPAs with effectiveness check completed
- Accepted risks approaching expiry
- Findings without evidence owner or controlled criterion

Define metric formulas and reporting period consistently. Distinguish “action completed” from “finding closed.”

## 7. Governance sign-off

- CAPA coordinator: To be assigned
- Risk methodology / policy reference: To be confirmed
- Authorized priority approver: To be confirmed
- Closure approver: To be confirmed
- Review date: Pending

**Approval note:** Draft governance aid. Reconcile with Mstar/UOA's approved risk and audit procedures before operational adoption.
