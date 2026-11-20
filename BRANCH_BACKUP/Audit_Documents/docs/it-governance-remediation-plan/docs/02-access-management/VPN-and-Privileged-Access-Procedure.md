# VPN and Privileged Access Management Procedure — UOA / Mstar
**Document ID:** UOA-IT-ACC-PRO-002  
**Version:** 0.1 — Draft for approval  
**Status:** Draft; not effective until approved by UOA  
**Owner (proposed):** Mstar IT Lead and designated UOA system/security owner  
**Related documents:** UOA-IT-ACC-POL-001, UOA-IT-ACC-MTX-001, UOA-IT-ACC-PRO-001

---

## 1. Purpose

Define the controlled process for requesting, approving, provisioning, monitoring, reviewing, and revoking VPN/remote access and privileged access. This procedure is intended to address the reported Audit concern that VPN access may have been granted without required approval. The concern must still be verified against actual records.

## 2. Scope

Applies to:
- Employee and contractor VPN/remote access.
- Vendor/third-party remote access.
- Administrative access to servers, identity systems, cloud tenants, network/security devices, storage, backup platforms, and business applications.
- Emergency/break-glass privileged access.
- Service and shared accounts where they can perform privileged operations.

The exact gateways, systems, account populations, MFA capabilities, and log retention settings must be identified during the current-state assessment.

## 3. Mandatory control rules proposed for approval

1. VPN and privileged access must have a documented business need and approval from the role(s) identified in the approved Authorization Matrix.
2. Approval must be obtained before routine access is granted.
3. IT must not self-approve its own request. Requester, approver and implementer must be separated where practicable.
4. Access must be limited to the required systems, destinations, roles and duration.
5. Use named individual identities wherever technically practicable. Shared administrative accounts require a documented exception, accountability controls and an expiry/review date.
6. MFA must be enabled for VPN and privileged access where supported. Unsupported MFA must be documented and risk-approved with compensating controls.
7. VPN and privileged activity must be logged to the extent supported by the platform; log source, retention and access must be documented.
8. Temporary and vendor access must have a defined expiry and internal sponsor.
9. Access must be reviewed on the approved cadence and upon role change, contract change, departure or suspected compromise.
10. Missing historical approval records must be recorded as evidence exceptions; never backdate or fabricate approval.

## 4. Roles

| Role | Responsibility |
|---|---|
| Requester / department head / internal sponsor | Defines business purpose, target systems, scope, duration and user/vendor identity. |
| UOA delegated approver | Approves according to the approved authorization matrix. |
| VPN/system owner | Confirms access scope, technical requirements and risk. |
| Data owner | Approves access to sensitive data where relevant. |
| Mstar IT operator | Validates approval, implements access, records evidence and tests the result. |
| Mstar IT Lead | Maintains inventory, oversight, exceptions, reporting and escalation. |
| Independent reviewer | Reviews privileged access, exceptions and evidence as assigned. |

## 5. Standard VPN access workflow

### Step 1 — Request
The requester submits the approved access request form or ticket. It must state:
- Named person/account and employment/vendor status.
- Business purpose and sponsor.
- Requested start and expiry date (expiry required for temporary/vendor access).
- Systems, network segments, destinations and services required.
- Whether access is standard remote access or elevated/administrative access.
- Data/system owner and approvals required.
- MFA requirement/capability, if known.
- Related project/change/incident reference where applicable.

### Step 2 — Approval validation
IT verifies the approval against the current approved Authorization Matrix, including department head, VPN/system owner, data owner, and any designated UOA approver required for the requested scope. An email or ticket is acceptable only if it is attributable, retained, and made under the approved workflow.

If approval is missing or the approver's authority is uncertain, do not grant routine access. Return the request for clarification and record the decision.

### Step 3 — Provisioning
The authorized operator:
1. Confirms the account maps to the intended individual.
2. Creates/enables the VPN identity or group membership using the approved administrative process.
3. Applies least-privilege routes, destinations, groups and restrictions supported by the platform.
4. Enables MFA where supported and records the status/evidence reference.
5. Sets expiry or schedules a controlled expiry mechanism for temporary access.
6. Records actual implementation timestamp, operator, permissions and relevant system log/ticket reference.
7. Updates the Access Register.

### Step 4 — Verification
A second person or suitable tester, where practicable, verifies that:
- The authorized user can reach the approved destination(s).
- Unapproved destinations are not exposed, to the extent testable.
- MFA and logging behave as expected.
- Expiry is configured for temporary access.
- The Access Register reflects the actual state.

Record the test result and any limitation. Notify the requester through an approved channel.

### Step 5 — Monitoring and review
Monitor available connection and authentication logs for anomalous activity under the approved monitoring process. Review active VPN access at the management-approved frequency; quarterly is a proposed starting point for remote access, subject to UOA approval. Immediately reassess access after role changes, contract scope changes, security incidents, or suspected compromise.

## 6. Privileged access workflow

1. Requester documents the administrative task, target system, privilege level, business justification and duration.
2. The system owner and designated UOA approver approve the request according to the Authorization Matrix.
3. IT verifies whether a standard non-privileged method can meet the need.
4. Where supported, assign a separate named admin identity rather than using a daily-use account.
5. Grant only the required role/permission, for the shortest practical period.
6. Enable MFA and privileged activity logging where supported.
7. Record account, role, approvers, timestamps, operator, expiry, verification and evidence reference.
8. Confirm the requester can perform the approved task and does not receive unrelated privilege.
9. Remove temporary elevation at expiry or task completion.
10. Review privileged account membership and activity at the approved cadence.

## 7. Service, shared and emergency accounts

### Service accounts
Each account must have a named technical owner and business purpose, documented dependent service, restricted permissions, approved credential/secret storage, and a defined lifecycle/rotation method appropriate to the platform. Interactive login should be disabled where technically appropriate. Record review and expiry/replacement plans.

### Shared accounts
Avoid shared interactive accounts. If technically unavoidable, document why individual accounts are not feasible, the approving risk owner, users authorized to access the secret, compensating logging/accountability controls, rotation events, expiry and review date. Do not store shared credentials in tickets or Git.

### Emergency/break-glass accounts
- Keep the number of emergency identities limited and their use controlled.
- Protect credentials in an approved secure mechanism; do not place them in the repository.
- Log every use, the incident/ticket, approver, reason, start/end, actions and resulting changes.
- Revoke temporary access or rotate credentials after use as required.
- Independently review use promptly after the incident.
- Test availability of the emergency mechanism periodically without exposing secrets.

## 8. Revocation and change events

Revoke or modify VPN/privileged access when:
- Employment or contract ends.
- User changes role or no longer requires the permission.
- Temporary access expires.
- Vendor sponsorship or contract ends.
- Security incident or account compromise requires containment.
- Periodic review identifies access without continuing need.

Follow the approved Access Request, Change and Revocation Procedure. Verify revocation from the source system and record the actual timestamp. Address active sessions, tokens, certificates and alternative remote paths where technically applicable.

## 9. Immediate handling of existing accounts during remediation

For the initial Audit remediation:
1. Preserve current account/group exports and relevant logs before making changes.
2. Build a complete VPN and privileged-account inventory, including dormant, service, shared, vendor and emergency identities.
3. Match each account and permission to an owner, business purpose, valid approval and current need.
4. Record unmatched or unsupported accounts as exceptions and risk-assess them with the system owner.
5. Obtain an authorized decision to retain, reduce, suspend or revoke each exception.
6. Implement approved changes under change control and retain before/after evidence.
7. Have a reviewer independent of the implementation check the resulting inventory and sample logs.

Do not disable accounts solely because a row is incomplete without assessing operational dependency and obtaining the required authorization, except where the approved incident response process authorizes urgent containment.

## 10. Evidence checklist

For each VPN/privileged grant or review, retain:
- Request ID and business purpose.
- Approval references and Authorization Matrix version.
- Account identifier, role/group, routes/destinations or privilege scope.
- MFA status and supporting evidence where available.
- Provisioning operator and actual timestamp.
- Relevant logs and verification result.
- Expiry/revocation and periodic review records.
- Exception/risk acceptance and compensating controls where applicable.

Store raw exports and logs in the restricted evidence repository and reference their evidence IDs in the Audit Evidence Register. Do not commit sensitive production exports to Git.

## 11. Exceptions and reporting

Exceptions must identify the account/system, requirement not met, business reason, risk, compensating controls, risk owner, approver, expiry, review date and closure plan. Report overdue or high-risk exceptions to the designated UOA sponsor. Expired exceptions must not remain active without reapproval.

## 12. Procedure approval checklist

Before this procedure becomes effective, confirm:
- Actual VPN gateways, identity providers, and privileged systems.
- Approved approvers and delegated emergency authority.
- Supported MFA/logging features and retention.
- Access review frequency and evidence location.
- Temporary access expiry method.
- Incident/change process integration.
- Staff communication and training.

| Role | Name | Approval reference | Date |
|---|---|---|---|
| UOA management / delegated authority | To confirm | Pending | |
| VPN/system owner | To confirm | Pending | |
| Mstar IT Lead | To confirm | Pending | |

## Revision history

| Version | Date | Description | Status |
|---|---|---|---|
| 0.1 | 2026-10-09 | Initial procedure draft for VPN and privileged access remediation | Draft |
