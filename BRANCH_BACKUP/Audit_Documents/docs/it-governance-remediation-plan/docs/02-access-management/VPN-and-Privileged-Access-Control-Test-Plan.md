# VPN and Privileged Access — Control Test Plan

**Status:** Draft 0.1 — for review; not an approved policy or production change instruction.  
**Objective:** Verify that VPN and privileged access are authorized, attributable to an individual, appropriately scoped, periodically reviewed, monitored, and revoked when no longer required.

## 1. Safety and scope

- Confirm scope with the IT/security owner before testing. Include VPN, firewall/admin consoles, server and database administration, hypervisors, backup platforms, cloud consoles, identity platforms, and other critical systems only where they are actually in use.
- This is an evidence review and controlled validation plan. Do not disable accounts, revoke sessions, change firewall rules, or test credentials in production without a separately approved change and rollback plan.
- Do not collect or commit passwords, private keys, recovery codes, session tokens, raw VPN profiles, or unrestricted access exports into this repository.
- Use a restricted evidence store and refer to records by evidence ID.

## 2. Required authoritative sources

Confirm the source and owner for each item before testing:
1. Current controlled VPN and privileged-access procedure and approval matrix.
2. Current HR employee/contractor status source and joiner/mover/leaver notifications.
3. Authoritative identity, VPN, MFA, group/role and privileged-account inventories.
4. System/application owner list and approved access request records.
5. VPN, identity provider, firewall and relevant system audit logs with retention periods.
6. Periodic access review records, exception/risk-acceptance records, and termination/offboarding cases.

If any authoritative inventory does not exist, record the scope limitation and create a CAPA; do not claim complete coverage.

## 3. Test population and sampling

Document the population owner, extraction time, date range, inclusion criteria, and count. Select a risk-based sample including, where available:
- All known privileged or administrator identities, or a documented risk-based subset if the population is large.
- VPN users with elevated access, third-party access, long inactivity, or exceptions.
- Recently granted, changed, and revoked access.
- A sample of current users and a sample of leavers.
- Service accounts or shared identities only where they exist; assess them separately from named human users.

Do not invent sample rows. Record each actual identity by a restricted reference rather than unnecessary personal details.

## 4. Sample test record

| Test ID | Restricted identity reference | Access type / system | Request and approval evidence | Actual entitlement evidence | MFA / logging evidence | Review / expiry | Result |
|---|---|---|---|---|---|---|---|
| VPN-01 | To be populated | To be confirmed | To be tested | To be tested | To be tested | To be tested | Not tested |
| VPN-02 | To be populated | To be confirmed | To be tested | To be tested | To be tested | To be tested | Not tested |
| PA-01 | To be populated | To be confirmed | To be tested | To be tested | To be tested | To be tested | Not tested |

## 5. Test steps

1. **Approval traceability:** For each sampled grant or change, trace to the request and dated approvals required by the controlled matrix. Record whether approval preceded enablement.
2. **Identity attribution:** Verify access is attributable to an authorized individual or approved service identity. Flag shared/admin accounts and determine whether use can be attributed through individual authentication, privileged access management, or logs.
3. **Least privilege:** Compare VPN group, routes, reachable networks, role, administrative permissions, and system scope with the approved request and job need. Do not infer necessity from current configuration alone.
4. **Authentication:** Verify the configured MFA/authentication requirement against approved security policy and capture configuration evidence without exposing secrets.
5. **Expiry and exceptions:** Verify third-party/temporary access has an owner, business justification, expiry/review date, and approved exception where required.
6. **Logging and monitoring:** Confirm successful/failed authentication and privileged actions are logged as applicable, timestamps are usable, access is restricted, retention is defined, and alerts/reviews have an assigned owner.
7. **Periodic review:** Inspect the latest required review evidence; verify the reviewer was authorized, decisions were recorded, and removals/corrections were completed and retested.
8. **Leaver revocation:** Trace selected leavers from the authoritative HR notification through VPN, identity provider, privileged groups, relevant applications and active sessions/tokens where applicable. Compare actual revocation timestamps to the approved timing requirement.
9. **Completeness reconciliation:** Reconcile VPN/privileged inventories to HR/contractor status and system-owner inventories. Record systems/log sources that were not available.
10. **Exception governance:** Verify exceptions have documented risk, compensating control, approving authority, owner, expiry/review date, and evidence of renewal or closure.
11. **Findings:** For each deviation, record criterion, fact, evidence ID, risk, owner, due date, interim mitigation, CAPA reference, retest method and closure approver.

## 6. Decision criteria

- **Pass:** The tested access is authorized, appropriately scoped, attributable, protected and monitored according to the applicable approved requirements; review/revocation evidence is complete.
- **Fail:** Evidence demonstrates unauthorized or excessive access, missing required approval, a failed required revocation, or a defined control was not performed.
- **Inconclusive:** The authoritative population, procedure, approval rule, or sufficient evidence cannot be confirmed.
- **Not applicable:** The control does not apply to the sample, with a documented rationale accepted by the reviewer.

Do not describe missing evidence as proof of intentional bypass without corroboration. Record the evidence gap and resulting assurance limitation.

## 7. Immediate risk triage

If testing reveals an apparently active unauthorized or orphaned privileged identity, notify the designated security/IT owner through the approved incident or escalation path. Preserve evidence and assess business impact. Any disabling, credential rotation, session revocation, or rule change must follow an authorized emergency/standard change process with a rollback and service-impact assessment.

## 8. Test sign-off

- Test lead:
- IT/security owner:
- Population and extraction date:
- Period and sample rationale:
- Evidence register references:
- Findings / CAPA IDs:
- Prepared by / date:
- Reviewed by / date:
- Authorized approver / date:

**Approval note:** Draft test aid only. Reconcile it with the approved VPN/privileged-access procedure and actual system scope before execution.
