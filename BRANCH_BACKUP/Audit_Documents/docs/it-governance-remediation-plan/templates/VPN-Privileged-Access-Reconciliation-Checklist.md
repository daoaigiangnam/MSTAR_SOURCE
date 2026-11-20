# VPN and Privileged Access Reconciliation Checklist

**Status:** Draft template. Use only after the system scope, approved access criteria, and data owners are confirmed.

## A. Inventory coverage

- [ ] Confirm current controlled VPN / privileged-access policy and procedure.
- [ ] Record inventory owner, source system, extraction date/time, covered period, and record count.
- [ ] List all in-scope VPN gateways, identity providers, firewalls, servers, databases, hypervisors, backup systems, cloud consoles and critical applications actually used.
- [ ] Record systems excluded from scope and the reason for exclusion.
- [ ] Confirm the authoritative HR/contractor status source and its data owner.
- [ ] Confirm system/application owners and the approved access request repository.
- [ ] Confirm audit-log sources, retention periods, time synchronization and access restrictions.

## B. Per-identity reconciliation

| Field | Expected record |
|---|---|
| Restricted identity reference | Avoid unnecessary personal data |
| Identity type | Human / contractor / service / shared or other |
| Employment/contract status reference | Restricted evidence ID |
| Manager / sponsor / system owner | Role or approved reference |
| System and access group/role | Actual inventory reference |
| Business justification | Request reference |
| Required approval(s) | Approval matrix reference |
| Approval timestamp | Original timestamp from source |
| Provisioning timestamp / executor | Evidence ID and role |
| MFA/authentication status | Configuration evidence reference; no secrets |
| Privilege and network scope | Sanitized entitlement summary |
| Expiry / next review date | Actual record or “not recorded” |
| Last activity evidence | Source and period, if available |
| Latest access review | Reviewer, date and decision reference |
| Leaver / revocation status | HR case and per-system evidence references |
| Reconciliation result | Match / excess / missing / orphan / inconclusive |
| Finding / CAPA reference | If applicable |

## C. Reconciliation checks

- [ ] Every in-scope active identity has an identifiable owner or sponsor.
- [ ] Every grant or change is traceable to required prior approval.
- [ ] Actual groups, roles, routes and reachable systems match approved scope.
- [ ] Privileged rights have a documented business need and separate approval where required.
- [ ] Temporary/third-party access has an owner, expiry and review decision.
- [ ] Required MFA and authentication controls are evidenced.
- [ ] Shared or service identities are documented, restricted, monitored and reviewed as required.
- [ ] Audit logs are available for the defined retention period and are reviewed by an assigned role.
- [ ] Leavers and expired contractors are reconciled to revocation evidence across all in-scope systems.
- [ ] Orphaned, duplicate, inactive, excessive or unexplained identities are investigated.
- [ ] Exceptions have documented risk acceptance, compensating controls, expiry and approval.
- [ ] Inventory differences are resolved or logged as findings with owner and due date.

## D. Exception triage

For each discrepancy, record: finding ID; affected system/access class; evidence ID; exact approved criterion; verified fact; risk and impact; whether access is still active; interim mitigation; accountable owner; target date; authorized change/incident reference if remediation is needed; retest evidence; effectiveness result; closure approver.

If an active identity appears unauthorized or excessively privileged, promptly escalate through the approved security/IT process. Do not make production changes solely from this checklist; obtain the required authorization and assess operational impact.

## E. Sign-off

- Inventory owner / date:
- IT/security reviewer / date:
- Relevant system owners / dates:
- Exceptions escalated to:
- Findings / CAPA register reference:
- Final reviewer / date:

**Evidence handling:** Keep source exports and restricted identity data in an approved access-controlled evidence store. This Git repository should contain sanitized summaries and evidence IDs only.
