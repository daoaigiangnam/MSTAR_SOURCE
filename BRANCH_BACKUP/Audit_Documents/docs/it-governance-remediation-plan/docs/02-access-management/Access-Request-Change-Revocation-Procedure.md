# Access Request, Change and Revocation Procedure — UOA / Mstar
**Document ID:** UOA-IT-ACC-PRO-001  
**Version:** 0.1 — Draft for approval  
**Status:** Draft; do not apply until authorized and reconciled with QP-HRD-23 / QP-HRD-24  
**Owner (proposed):** Mstar IT Lead + UOA HR/Admin and system owners

## 1. Purpose

Define operational steps and minimum evidence for granting, changing, reviewing, and revoking access. This draft supplements the supplied QP-HRD-23 (User Access Request and Authorization) and QP-HRD-24 (User Account and Access Termination); it does not silently replace those signed documents.

## 2. Triggering events

- New employee, contractor or vendor.
- New application/system role or remote access requirement.
- Change of department, job, duties or contract scope.
- Temporary project/third-party access.
- Privilege elevation or access to sensitive data.
- Emergency access during an incident.
- Termination, contract end, access no longer required, or suspected compromise.
- Periodic access review identifies unnecessary or unexplained access.

## 3. Roles

- Requester / department head: states business purpose, system, role, duration and sponsor.
- HR/Admin: validates employment/contract status and effective dates using the approved source.
- Data/system owner: approves access to the owned system/data and sensitive entitlements.
- Authorized approver: approves according to the approved authorization matrix.
- IT operator: validates completeness, implements, tests and records the change.
- Independent reviewer: reviews exceptions and access control operation as assigned.

## 4. Standard access request workflow

### Step 1 — Submit request
Request must be entered into the approved ticket/workflow system. A controlled email form may be used only if management approves it and it provides equivalent traceability.

Required fields:
- Request ID and submission timestamp.
- Subject user/account identifier and employment/vendor status.
- Department, role and manager/sponsor.
- System/tenant, requested role/permissions and data scope.
- Business purpose and effective date.
- End date if temporary.
- MFA/remote/privileged-access need.
- Data/system owner approval requirement.
- Requester and approver identities.
- Related change/incident record where applicable.

### Step 2 — HR / business validation
HR/Admin confirms relevant joiner/mover/leaver facts. The department head confirms the business need and correct role. Missing or inconsistent fields are returned to requester; IT must not guess a role from a similar employee without verification.

### Step 3 — Approval
IT checks that approvals required by the current approved Authorization Matrix are present and attributable. Approval must precede implementation except the approved emergency process. A requestor must not approve their own request. Where duties overlap, obtain a documented compensating approval/review.

### Step 4 — IT implementation
IT:
1. Confirms account identity and target system.
2. Applies only approved permissions and duration.
3. Uses named accounts and approved administrative methods.
4. Enables MFA or other required control where supported.
5. Records actual role/permissions and implementation timestamp.
6. Updates the Access Register and links the request, approvals and implementation record.
7. Keeps secrets out of tickets and Git; document a secure secret-handling reference if needed.

### Step 5 — Verification and notification
A suitable tester confirms the user can perform approved tasks and cannot access prohibited scope where testable. Record test date, tester, result and any exception. Notify requester/manager through an approved channel and do not disclose credentials insecurely.

### Step 6 — Closure
Close only when approval, implementation, verification and register-update evidence are available. If not completed, retain a clear pending/failed status.

## 5. Change-of-role workflow

1. Receive HR/business notification with effective date.
2. Identify current access before changing it.
3. Obtain approvals for new permissions.
4. Remove permissions no longer needed, preferably as part of the same controlled change window.
5. Test new access and verify old access removal.
6. Update Access Register and preserve evidence.
7. Escalate any conflicting access or business dependency.

## 6. Temporary / vendor access

- Named sponsor and business purpose required.
- Specify systems, destinations, roles and allowed time window.
- Obtain data/system owner approval and required contract/vendor approval.
- Use MFA and logging where supported.
- Set expiry at creation; do not rely only on a calendar reminder.
- Confirm account disablement and access revocation after expiry.
- Review vendor access at renewal and project/scope changes.

## 7. Emergency access

This workflow must be approved by UOA before use:
1. Record incident/reference, reason, requested scope and duration.
2. Obtain approval from the designated emergency authority; if unavailable, follow formally approved delegation.
3. Use the narrowest scope and shortest duration.
4. Record all actions and system logs.
5. Revoke access when the emergency window ends.
6. Have an independent owner review the access and activity promptly after the event.
7. Record any policy exception and corrective action.

Emergency access is not a substitute for normal approvals.

## 8. Termination / access revocation workflow

1. HR/Admin sends termination or contract-end notice through approved secure workflow.
2. Record effective disablement time and whether an earlier security action is required.
3. Obtain data/system-owner instructions for business data transfer, mailbox delegation, retention or legal hold; these are separate from the sign-in decision.
4. At the authorized effective time, block sign-in and revoke VPN, SSO/application access, privileged rights, active sessions, refresh tokens, API keys and third-party access as technically applicable.
5. Revoke credentials or certificates and rotate shared/service secrets if the departing person could know them and rotation is required.
6. Recover devices, badges and company equipment; update asset register.
7. Verify each in-scope system is addressed; record not-applicable systems with rationale.
8. Update Access Register and complete offboarding checklist.
9. For high-risk or unexpected departure, follow authorized incident/security procedure.
10. Retain source records and evidence in the controlled repository.

Do not interpret a mailbox retention or disablement date as permission to leave account sign-in active. UOA must formally clarify the 30-day email step shown in QP-HRD-24.

## 9. Failed or unsupported requests

Return a request if approvals are missing, approver authority is uncertain, business scope is vague, or the requested permission is excessive. Record the reason, requester notification and resubmission/cancellation. Never fabricate approval or backdate records to close a request.

## 10. Exception management

Exception record must include ID, control requirement, reason, impacted account/system, scope, risk, compensating controls, approving risk owner, start/expiry date, review date, and closure evidence. Expiry notifications and review must be assigned to a named owner.

## 11. Evidence and operational metrics

Store:
- Request and approval reference.
- HR/business validation.
- Implementation log with actual timestamp and permissions.
- Verification/test result.
- Access Register update.
- Exception and review record, if applicable.
- Revocation logs and handover record for offboarding.

Recommended monitoring:
- Requests missing required approval (target: zero before routine grant).
- Time from authorized effective termination to access blocking.
- Percentage of temporary accounts disabled by expiry.
- Percentage of grants with full evidence.
- Number and age of unresolved access exceptions.
- Periodic review completion rate.

Targets and SLA must be approved by UOA.

## 12. Procedure approval

| Role | Name | Approval record | Date |
|---|---|---|---|
| UOA authorized approver | To confirm | Pending | |
| HR/Admin owner | To confirm | Pending | |
| Mstar IT Lead | To confirm | Pending | |

## Revision history

| Version | Date | Description | Status |
|---|---|---|---|
| 0.1 | 2026-10-09 | Initial draft aligned to supplied QP-HRD-23 / QP-HRD-24 and Audit concerns | Draft |
