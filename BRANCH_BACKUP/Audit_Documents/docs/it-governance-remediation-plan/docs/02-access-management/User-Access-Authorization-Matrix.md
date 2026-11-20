# User Access Authorization Matrix — UOA / Mstar
**Document ID:** UOA-IT-ACC-MTX-001  
**Version:** 0.1 — Template for UOA approval  
**Status:** Draft; entries and delegated authorities must be confirmed before use  
**Owner (proposed):** UOA system owners + Mstar IT Lead

## 1. Purpose

Define who may approve different kinds of access before IT implements them. This matrix is a blank control design template, not a statement of current UOA delegation.

## 2. Matrix design rules

- Approvers must be named by role, not merely “management” or “IT”.
- Data/system owners must approve permissions to data and systems under their accountability.
- HR confirms employment status and effective dates but does not automatically replace the business/system owner's permission approval.
- IT validates completeness and implements approved changes; IT should not approve its own access request.
- Where one person occupies multiple roles, define a compensating review.
- Add actual platforms and role names only after inventory/owner confirmation.
- Temporary access must have expiry and sponsor.
- Record actual approval reference and date in each access request.

## 3. Proposed authorization matrix (to be completed and approved)

| Access type | Business requester | Required business approval | Additional approval | IT verification | Implementer | Expiry/review |
|---|---|---|---|---|---|---|
| Standard account creation | Department head / authorized delegate | HR confirms valid joiner and department head confirms role | System owner if non-standard or sensitive access | Identity and request completeness | Authorized IT operator | Review on role change / periodic review |
| Standard application role | Department head | Application/system owner or delegated role owner | Data owner if sensitive dataset included | Correct entitlement and approval | Authorized IT operator | Risk-based review |
| VPN / remote access | Department head / named sponsor | Department head plus designated VPN/service owner | UOA approver for external or elevated remote access, as formally delegated | Business need, scope, MFA capability, duration | Authorized IT operator | Expiry for temporary/vendor access; periodic review |
| Privileged/admin access | System owner / manager | System owner and delegated security/IT authority | UOA risk owner for critical systems, per delegation | Least privilege, MFA, separate admin identity where supported | Separate authorized operator where practicable | Quarterly review proposed; immediate review at role change |
| Access to sensitive/regulated/customer data | Department head / data user sponsor | Named data owner | Compliance/legal approval where required by UOA policy | Scope, purpose, sharing/export capability | Authorized IT operator | Defined duration and periodic review |
| Third-party/vendor access | Internal sponsor | Service/system owner | UOA vendor/data owner and contract owner as applicable | Named identity, limited destinations, MFA, logging | Authorized IT operator | Must have expiry and sponsor review |
| Shared account exception | Service owner | System owner | Designated risk owner | Justification, compensating controls, accountability/logging | Authorized IT operator | Explicit expiry and review |
| Service account/API identity | Technical service owner | System/data owner | Security review for sensitive scope | Non-interactive purpose, secret storage, permissions, rotation plan | Authorized IT operator | Owner review and scheduled credential lifecycle |
| Emergency/break-glass access | Incident commander / authorized requester | Named emergency delegate according to approved delegation | Post-use independent review | Time limit, scope, logging and containment | Authorized operator | Expire/revoke immediately after approved window |
| Role/department change | Department head / HR workflow | New department head and relevant system owners | Data owner for retained sensitive access | Remove stale access before/additional to grant as applicable | Authorized IT operator | Review at every transfer |
| Termination/offboarding | HR/Admin notification | HR confirms effective time; business/system owners decide data handover | UOA security/management for high-risk exit where required | Verify systems, tokens, sessions, VPN, privileged and third-party access | Authorized IT operator | Disable at approved effective time; separate mail/data retention decision |

## 4. Platform-specific mapping — required fields

Complete one row per system or tenant:

| Platform/system | System owner | Data owner (if different) | Approver role(s) | Privilege levels / role names | MFA capability/status | Provisioning source/ticket | Log source | Review frequency | Notes / exceptions |
|---|---|---|---|---|---|---|---|---|---|
| VPN gateway(s) | To confirm | To confirm | To confirm | To inventory | To assess | To confirm | To confirm | Proposed quarterly for remote access | |
| Identity directory / SSO | To confirm | To confirm | To confirm | To inventory | To assess | To confirm | To confirm | To set | |
| File storage / NAS | To confirm | To confirm | To confirm | To inventory | To assess | To confirm | To confirm | To set | |
| Backup/cloud platform | To confirm | To confirm | To confirm | To inventory | To assess | To confirm | To confirm | To set | |
| Email/collaboration | To confirm | To confirm | To confirm | To inventory | To assess | To confirm | To confirm | To set | |
| Business applications | To confirm | To confirm | To confirm | To inventory | To assess | To confirm | To confirm | To set | |
| Servers/network/security devices | To confirm | To confirm | To confirm | To inventory | To assess | To confirm | To confirm | To set | |

## 5. Approval validation checklist

Before implementation, IT verifies:
- The requestor is entitled to request access.
- Business purpose, system, role, duration and user identity are clear.
- Required approval exists, is attributable, and was given by the current authorized role.
- Required data/system-owner approval is present.
- Required start/end date and conditions are recorded.
- No conflict or segregation-of-duties issue is evident, or the exception is approved.
- MFA and logging requirements have been checked.
- The request and approvals are preserved in the controlled ticket/evidence system.

## 6. Approval and change control

This matrix is not operational until the named UOA authority approves it. Changes to approvers, platform owners, or privilege definitions require versioning, communication, and update to related forms and workflows.

| Approver | Name | Approval record | Date |
|---|---|---|---|
| UOA management / delegate | To confirm | Pending | |
| Relevant system owners | To confirm | Pending | |
| Mstar IT Lead | To confirm | Pending | |

## Revision history

| Version | Date | Description | Status |
|---|---|---|---|
| 0.1 | 2026-10-09 | Initial authorization matrix template based on the reported VPN/access issue | Draft |
