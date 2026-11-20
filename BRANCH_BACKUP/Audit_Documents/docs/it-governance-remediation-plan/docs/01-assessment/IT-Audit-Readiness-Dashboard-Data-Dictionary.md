# IT Audit Readiness Dashboard — Data Dictionary

**Status:** Draft 0.1 — specification for review; not a live dashboard or source of actual audit status.  
**Purpose:** Define consistent fields and status rules for reporting evidence requests, control tests, findings and CAPA.

## 1. Data sources and authority

The dashboard should not become a new authoritative source for approvals or raw evidence. It should reference approved source systems and controlled registers:
- Evidence request tracker
- Evidence register
- Control test records
- Audit finding/CAPA register
- Decision and dependency log
- Approved risk acceptance records
- Controlled procedure/document register

Record source owner, last refresh, and known limitations. If a source is not available, report the gap instead of deriving an assumed status.

## 2. Core entities

### Finding / CAPA

| Field | Type / allowed values | Rule |
|---|---|---|
| finding_id | Unique text ID | Stable identifier; never reuse |
| title | Short text | Factual and neutral |
| status_of_assertion | Verified / Reported / Hypothesis / Unresolved | Distinguish confirmed facts from allegations and open questions |
| criterion_id | Controlled procedure/control reference | Include version/effective date where relevant |
| evidence_ids | List of IDs | Must point to approved evidence register |
| priority | Approved risk scale; otherwise interim P1–P4 | Label interim values clearly |
| owner_role | Role or approved owner reference | One accountable owner for each action |
| due_date | Date or unknown | Do not invent dates |
| capa_status | Intake / Validation / Risk triage / Action approved / Implementing / Retest pending / Retest failed / Effectiveness check / Closed | Use lifecycle gates |
| interim_control | Text/reference or none identified | Absence of interim control on high risk must be escalated |
| implementation_evidence | Evidence IDs or pending | Completion requires evidence |
| retest_result | Pass / Fail / Inconclusive / Not run | Not run is not a pass |
| effectiveness_result | Effective / Ineffective / Not required with rationale / Pending | Separate from action completion |
| risk_acceptance_id | Reference or none | Must have authority, scope and expiry/review |
| closure_approver | Approved role/reference or pending | Closure requires authorized approval |
| last_updated | Date/time and source | Display refresh timestamp |

### Evidence request

| Field | Type / allowed values | Rule |
|---|---|---|
| evidence_request_id | Unique text ID | Stable identifier |
| workstream | Controlled list | E.g. access, VPN, leaver, backup, procurement |
| requested_item | Text | Specific and bounded |
| period_scope | Text/date range | Explicitly identify period and population |
| evidence_owner | Role/reference | To be confirmed if unknown |
| requested_date / due_date | Date or pending | Track delay |
| status | Not requested / Requested / Partially received / Received—not reviewed / Under review / Validated / Insufficient / N/A with rationale | Only reviewer can mark validated |
| evidence_id | Evidence register ID or pending | Link received item |
| limitation | Text | Explain exclusions or insufficiency |
| reviewer / review_date | Role/date or pending | Preserve accountability |

### Control test

| Field | Type / allowed values | Rule |
|---|---|---|
| test_id | Unique text ID | Stable identifier |
| procedure_id/version | Controlled document reference | Exact criterion must be traceable |
| population_source | Source and extraction timestamp | Required before sample conclusions |
| population_count | Number or unknown | Do not invent count |
| sample_count | Number | Match actual records tested |
| selection_method | Text | Risk-based/random/other documented method |
| result | Pass / Fail / Inconclusive / Not run / N/A with rationale | Inconclusive is not a pass |
| finding_ids | List | Link failures/evidence gaps |
| tester / reviewer | Role/reference | Independence as required by policy |

## 3. Suggested KPI definitions

Use these formulas only after agreeing the reporting period, status mappings and denominator:

- **Evidence validation rate** = validated evidence items ÷ evidence items received for review × 100.
- **On-time evidence rate** = requests fulfilled by due date ÷ requests due in the period × 100.
- **Retest pass rate** = passed retests ÷ completed retests × 100.
- **CAPA overdue rate** = open actions past due date ÷ open actions with due dates × 100.
- **Closure quality rate** = closed CAPAs with required implementation and retest/effectiveness evidence ÷ all closed CAPAs × 100.
- **Access traceability coverage** = tested sampled grants with matching required approval evidence ÷ sampled grants requiring approval × 100.
- **Restore-test coverage** = approved critical datasets with a successful restore test in the approved period ÷ approved critical datasets in scope × 100.

Always display numerator, denominator, period, source, and last refresh. Do not report a percentage if the denominator is unknown or population coverage is incomplete without an explicit limitation.

## 4. Dashboard sections

1. Executive status and last refresh
2. Open findings by priority and CAPA gate
3. Overdue actions and unowned items
4. Evidence requests by status and owner
5. Control testing coverage, pass/fail/inconclusive
6. VPN/privileged access reconciliation exceptions
7. Joiner/mover/leaver revocation status
8. Backup ownership, restore-test status and approved recovery objectives
9. Decisions/dependencies awaiting management
10. Accepted risks and expiry/review dates

## 5. Data quality controls

- Use stable IDs and controlled status values.
- Do not overwrite source timestamps with dashboard refresh times.
- Record data extraction time and refresh failures.
- Prevent duplicate evidence and finding IDs.
- Restrict sensitive fields; show sanitized summaries to unauthorized viewers.
- Require reviewer approval before a test or evidence item becomes “validated.”
- Require closure approval before a CAPA becomes “Closed.”
- Flag missing owners, due dates, evidence IDs and expired risk acceptances.
- Retain an audit trail of status changes according to approved retention rules.

## 6. Acceptance criteria before implementation

- Source systems/registers and owners are confirmed.
- Status and risk definitions are approved.
- KPI formulas and denominators are agreed.
- Access permissions and sensitive-field masking are tested.
- Reconciliation against source registers is tested.
- Refresh failure and stale-data indicators are implemented.
- A sample report is reviewed by IT, Audit liaison and management.

**Approval note:** This document defines a proposed data model only. It contains no live counts and should not be presented as actual audit status until connected to validated source data.
