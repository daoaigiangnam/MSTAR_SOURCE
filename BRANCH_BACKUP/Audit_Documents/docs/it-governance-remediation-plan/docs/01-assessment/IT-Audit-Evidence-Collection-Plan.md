# IT Audit Evidence Collection Plan — UOA / Mstar

**Status:** Draft 0.1 — working plan for review  
**Purpose:** Organize the collection, validation, and controlled handover of evidence for the next IT audit. This plan does not state that a finding is proven or closed.

## 1. Operating principles

1. Use the latest controlled procedures and actual source records.
2. Preserve original timestamps and metadata; never backdate approvals or reconstruct missing records as if contemporaneous.
3. Distinguish verified facts, stakeholder statements, hypotheses, and unresolved questions.
4. Store restricted evidence only in an approved access-controlled repository. This Git repository should contain sanitized summaries and evidence IDs, not credentials, private keys, full HR files, or unredacted production exports.
5. Collect the minimum evidence needed for the stated control objective, using least-privilege access and a documented chain of custody where appropriate.
6. A missing record is an evidence/control gap; do not infer intentional misconduct without corroboration.
7. Do not close CAPA until implementation is evidenced and the control has been retested.

## 2. Priority evidence workstreams

| Priority | Workstream | Evidence to request | Evidence owner to confirm | Validation method | Output |
|---|---|---|---|---|---|
| P1 | Access approvals | Current access request register; approvals for VPN, privileged and application access; current approval matrix | IT access owner, requesting managers, system/data owners | Trace a risk-based sample from request to approval and actual entitlement | Access test records; exceptions and CAPA |
| P1 | VPN and privileged access | Current VPN identity/group inventory; privileged account inventory; MFA/configuration evidence; authentication/admin logs; review records | IT/security owner and relevant system owners | Reconcile inventory to approved requests, HR status, review and log evidence | Reconciliation checklist; orphan/excess/unknown access exceptions |
| P1 | Leaver revocation | Authoritative HR termination/change cases; system scope list; disable/revoke timestamps; VPN/SSO/application/admin evidence; asset-return records where applicable | HR process owner, IT, system owners | Trace selected leavers across every in-scope system and compare timing to approved criteria | Offboarding test records; unresolved revocation gaps |
| P1 | Backup ownership and cloud storage | Backup inventory; storage account owner/tenant; contract or service terms; data owner approvals; access list; encryption/configuration evidence; retention and deletion settings | Data owner, IT backup owner, cloud/service owner to confirm | Reconcile data sets, storage location, ownership, access, approved purpose and recovery requirements | Backup ownership register; risk decisions and remediation |
| P1 | Backup recoverability | Backup job status/history; scope and exclusions; retention; immutability/offline protection where applicable; restore test records; recovery objectives approved by business | IT backup owner and service/data owners | Select critical datasets and verify restore evidence, integrity and elapsed time against approved objectives | Restore-test record; recovery gaps and CAPA |
| P2 | Software and equipment procurement | QP-HRD-16/17/18 controlled copies; request/approval; procurement and license records; receipt; asset/software inventory; handover | Process owners, procurement/finance, IT asset owner | Trace purchase/installation to approval and inventory in both directions | Procedure test results and asset/software exceptions |
| P2 | Joiner/mover/leaver lifecycle | QP-HRD-22/23/24 controlled copies; HR notifications; approved access specifications; provisioning/change/revocation records | HR, IT, managers and system owners | Trace HR events to access and asset changes; trace access changes back to approvals | Lifecycle test results and handoff gaps |
| P2 | Incident/change controls | Incident register; change requests; emergency change approvals; implementation/rollback evidence; post-change review | IT operations and service owners | Sample significant incidents and changes; trace authorization, execution and review | Incident/change test records |
| P2 | Logging and vulnerability controls | System/log source inventory; retention and review evidence; vulnerability scans; patch records; exceptions and risk acceptance | Security/IT operations and system owners | Verify coverage, timeliness, ownership and documented exceptions | Monitoring/patch test records |
| P3 | Governance and CAPA reporting | Finding register; owner/due date; risk acceptance; management decisions; retest and closure evidence | IT lead, control owners, management | Reconcile reported status to evidence and independently retest closed items | Evidence-based CAPA dashboard |

## 3. Evidence register minimum fields

Every item should have:
- Evidence ID and short title
- Audit objective / control / finding ID
- Source system or document ID, version and owner
- Period covered and extraction/collection date
- Collector and date received
- Original timestamp/metadata preserved: yes/no/not applicable
- Restricted storage location and access classification
- Integrity/checksum or custody record where required by internal rules
- Redaction/sanitization status
- Validation performed and result
- Related sample/test/finding/CAPA IDs
- Known limitation or missing context
- Reviewer and review date

## 4. Evidence validation workflow

1. **Request:** Send a specific request stating control objective, period, expected format, owner and due date.
2. **Receive:** Record receipt date, source, version, collector and restricted storage location.
3. **Preserve:** Keep the original unchanged where permitted; create a working copy for redaction or analysis.
4. **Validate:** Check scope, completeness, timestamp, provenance, consistency and whether the item actually supports the criterion.
5. **Link:** Assign an evidence ID and link it to the test record and finding/CAPA.
6. **Review:** Have a reviewer independent of the control executor check sufficiency for high-risk evidence.
7. **Resolve:** Request clarification or record the limitation; never silently fill a missing field.
8. **Handover:** Share only the approved subset with authorized auditors through the approved channel and record what was shared, when, by whom, and under whose authorization.

## 5. Evidence request tracker

| Request ID | Workstream | Specific evidence requested | Owner | Due date | Status | Evidence ID | Limitation / follow-up |
|---|---|---|---|---|---|---|---|
| EV-01 | Access approvals | To be specified with actual system and period | To assign | To agree | Not requested | Pending | Pending |
| EV-02 | VPN/privileged | To be specified with actual system and period | To assign | To agree | Not requested | Pending | Pending |
| EV-03 | Leaver revocation | To be specified with actual period and system scope | To assign | To agree | Not requested | Pending | Pending |
| EV-04 | Backup ownership | To be specified with actual datasets and storage account | To assign | To agree | Not requested | Pending | Pending |
| EV-05 | Restore/recovery | To be specified with approved critical datasets and objectives | To assign | To agree | Not requested | Pending | Pending |

Replace the illustrative tracker rows with real requests before use. Do not mark an item received or tested until evidence is actually available and reviewed.

## 6. Readiness exit criteria

Evidence collection is ready for Audit handover only when:
- Scope, period, and population are defined for each test.
- Each conclusion has an explicit criterion and traceable evidence.
- Missing records and exclusions are transparently recorded.
- Findings have an accountable owner, due date, risk, interim mitigation and CAPA reference.
- Claimed closures have implementation and effectiveness-test evidence.
- Restricted evidence access and auditor handover are authorized and logged.
- Management understands which items are verified, unresolved, or accepted as residual risk.

## 7. Approval and ownership

- Audit coordinator: To be assigned
- IT evidence coordinator: To be assigned
- HR evidence owner: To be confirmed
- Data/backup owner: To be confirmed
- System owners: To be confirmed
- Authorized approver for external evidence handover: To be confirmed
- Review date and decision: Pending

**Important:** This plan is a proposed working framework. Confirm audit scope, data handling rules, owners, deadlines and evidence channels with the authorized stakeholders before execution.
