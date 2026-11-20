# Periodic User Access Review Procedure — UOA / Mstar
**Document ID:** UOA-IT-ACC-PRO-003  
**Version:** 0.1 — Draft for approval  
**Status:** Draft; cadence and approvers require UOA approval  
**Owner (proposed):** Mstar IT Lead with UOA system/data owners  
**Related:** UOA-IT-ACC-POL-001, UOA-IT-ACC-MTX-001, UOA-IT-ACC-PRO-001

## 1. Purpose

Ensure active user, vendor, service, remote and privileged access remains appropriate, approved, attributable and aligned with current business need.

## 2. Scope

Covers all in-scope systems identified in the IT Current State Assessment, including identity, VPN, business applications, file storage, cloud/SaaS, backup, servers, network/security devices and third-party connections.

## 3. Review frequency

The following is a proposed starting cadence and must be approved by UOA:
- Privileged/admin and VPN/remote access: at least quarterly.
- Sensitive data and critical business systems: at least quarterly or at a stricter frequency defined by the data/system owner.
- Standard application access: at least semi-annually.
- Vendor/temporary access: at project milestones and before expiry/renewal; temporary access must also be checked at expiry.
- Service/shared/emergency accounts: at least quarterly, including ownership, purpose, privilege and lifecycle controls.
- Event-driven review: immediately after role/department change, contract scope change, suspected compromise, material incident, or major system change.

If an approved corporate policy sets a stricter frequency, that policy prevails.

## 4. Roles

- IT/system administrator: generates a dated current access population and supplies source references.
- Department head/manager: confirms whether each person's access remains necessary for current duties.
- System/data owner: confirms roles, data scope, privilege and exceptions.
- HR/Admin: provides authoritative joiner/mover/leaver status where appropriate.
- Mstar IT Lead: coordinates the campaign, tracks completion, resolves discrepancies and reports overdue reviews.
- Independent reviewer: checks evidence and a sample of resulting changes.

A reviewer must not simply approve their own access. Where role overlap cannot be avoided, record the conflict and obtain an independent compensating review.

## 5. Review workflow

### Step 1 — Define the review population
1. List systems and owners from the approved inventory.
2. Set review period, scope, due date, reviewer and approver.
3. Export current accounts, groups, roles, privileges, vendor identities and service accounts from each authoritative system.
4. Record export timestamp, source, query/filter scope and collector.
5. Reconcile the export to the Access Register and note population limitations.

### Step 2 — Send review package
For each reviewer, provide a controlled list showing account identifier, owner, department/sponsor, role/permission, business purpose, last activity if available, approval reference, account type, expiry, and prior exceptions. Share only data necessary for the review through an approved restricted channel.

### Step 3 — Reviewer decision
For each entitlement, the authorized reviewer records one of:
- **Retain:** current business need and scope confirmed.
- **Modify:** retain only specified reduced/changed permissions.
- **Revoke:** no current business need or account is no longer authorized.
- **Investigate:** owner, purpose, approval or activity is unclear; assign owner and due date.
- **Exception:** access is required despite a control deviation; document risk and obtain authorized risk acceptance with expiry.

No response is not approval. Unreviewed accounts remain unresolved exceptions and must be escalated.

### Step 4 — Validate decisions
IT checks reviewer authority and that each decision is attributable and complete. Decisions involving sensitive data or elevated privileges must include the required system/data-owner approval.

### Step 5 — Implement changes
Implement modifications and revocations through the approved change/access process. Record actual implementation timestamps and source-system evidence. Do not delete accounts or data where retention/operational dependencies require a separate decision.

### Step 6 — Verify and reconcile
Re-export or inspect the authoritative system to verify that approved changes occurred. Update the Access Register, CAPA/exception records and evidence index. Record failures or partial changes and escalate them.

### Step 7 — Close the campaign
Close the review only when:
- The population and limitations are documented.
- Every account/entitlement has a recorded decision or an assigned exception.
- Changes have implementation evidence.
- Outstanding high-risk exceptions are escalated to the risk owner.
- An independent reviewer checks completion and samples the results.

## 6. Mandatory review questions

For every account/entitlement:
1. Is the identity still active and attributable to a person, service owner or approved exception?
2. Is there a current business purpose?
3. Does the permission match the person's current role?
4. Is the privilege level proportionate?
5. Is access to sensitive data approved by the data owner?
6. Is VPN/remote access still required and limited to the correct scope?
7. Is MFA enabled where supported, or is there an approved exception?
8. Is the account temporary/vendor and does it have a valid expiry?
9. Is the account dormant, orphaned, shared or unsupported?
10. Is the approval evidence available and traceable?
11. Are there conflicts with separation-of-duties requirements?
12. Are service-account ownership, dependency and credential lifecycle controls documented?

## 7. Evidence to retain

- Review plan and population export references.
- Source system, export date/time, scope and collector.
- Reviewer list and authority basis.
- Decisions for each account/entitlement.
- Clarifications and exception approvals.
- Change tickets and implementation logs.
- Post-change reconciliation/export.
- Independent review and campaign closure sign-off.

Keep sensitive exports in the approved restricted repository, not in Git. Link by Evidence ID.

## 8. Monitoring metrics

- Review completion rate by due date.
- Percentage of accounts with confirmed owner and business purpose.
- Number of retain/modify/revoke/investigate decisions.
- Number of unresolved approval-evidence gaps.
- Number and age of overdue remediation actions.
- Number of expired temporary/vendor accounts still enabled.
- Percentage of sampled changes verified in the authoritative system.

Set target values after the first baseline and management approval.

## 9. Escalation

Escalate promptly where the review finds unknown privileged accounts, access after termination, unexpected external access, unexplained sensitive-data access, expired vendor accounts, or inability to establish ownership. Follow incident and change-control requirements; preserve evidence before remediation where feasible.

## 10. Approval

| Role | Name | Approval reference | Date |
|---|---|---|---|
| UOA management / delegated authority | To confirm | Pending | |
| System/data owner representative | To confirm | Pending | |
| Mstar IT Lead | To confirm | Pending | |

## Revision history

| Version | Date | Description | Status |
|---|---|---|---|
| 0.1 | 2026-10-09 | Initial periodic access review procedure draft | Draft |
