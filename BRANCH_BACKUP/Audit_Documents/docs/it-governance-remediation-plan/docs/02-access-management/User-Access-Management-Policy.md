# User Access Management Policy — UOA / Mstar
**Document ID:** UOA-IT-ACC-POL-001  
**Version:** 0.1 — Draft for approval  
**Status:** Draft; not effective until approved  
**Policy owner (proposed):** UOA IT service owner / Mstar IT Lead  
**Approving authority:** UOA management, to be confirmed  
**Review cycle (proposed):** At least annually and after material incidents or organization/system changes

---

## 1. Purpose

Establish minimum governance requirements for requesting, approving, granting, changing, reviewing, and revoking access to UOA information, systems, networks, VPN, cloud services, and IT assets managed by UOA or Mstar.

## 2. Scope

Applies to employees, contractors, temporary staff, service providers, and other human or non-human accounts used to access UOA systems or data. It covers standard user access, remote/VPN access, privileged access, service accounts, emergency access, and third-party access.

Platform-specific requirements must be adapted to actual technical capabilities and approved contractual arrangements.

## 3. Principles

1. **Business need:** access must serve a documented, legitimate business purpose.
2. **Prior authorization:** access must be approved by the authorized decision-maker before it is granted, except through a formally approved emergency process.
3. **Least privilege:** grant only the permissions required for the assigned duties.
4. **Role appropriateness:** access must align with job function and approved role/entitlement definitions.
5. **Separation of duties:** requester, approver, and implementer must be separated where practicable.
6. **Individual accountability:** unique user accounts are the default; shared interactive accounts require a documented exception and compensating controls.
7. **Strong authentication:** MFA must be used for VPN/remote access and privileged access where supported, subject to documented exceptions approved by the risk owner.
8. **Traceability:** each grant, change, exception, review, and revocation must be recorded and retrievable.
9. **Timely revocation:** access must be removed or restricted when no longer required, including personnel departure and contract termination.
10. **Periodic review:** system/data owners must review access on the approved cadence.
11. **Evidence integrity:** missing historical approval evidence must be recorded honestly; it must not be fabricated or backdated.

## 4. Roles and responsibilities

| Role | Responsibility |
|---|---|
| UOA management / delegated authority | Approves this policy, sets risk tolerance and delegates access approval authority. |
| Department head / line manager | Confirms business need, role, duration, and requested permissions. |
| Data owner / system owner | Approves access to owned data/system and sensitive entitlements; defines reviewer and access criteria. |
| HR/Admin | Confirms hire, transfer, leave, termination, and effective dates through the approved source. |
| Mstar IT Lead | Maintains procedure, access matrix, oversight, exception escalation, and periodic reporting. |
| IT administrator/operator | Implements only authorized access changes; records actual actions and timestamps; does not approve own request. |
| User / contractor | Protects credentials, uses access only for approved business purposes, and reports suspected compromise. |
| Independent reviewer / Internal Audit liaison | Tests access controls and CAPA effectiveness as assigned. |

## 5. Policy requirements

### 5.1 Request and approval
Every request must identify the person/account, system, requested role/permissions, business purpose, start date, end date if temporary, data sensitivity, approver, and supporting ticket/reference. Approver authority must be checked against an approved authorization matrix.

### 5.2 Provisioning
IT verifies request completeness and approval authority before implementation. After granting access, IT records the actual permissions and timestamp, tests the expected access, confirms MFA where required, and updates the Access Register.

### 5.3 Change of role or duties
When a person changes department, duties, location, or contract, the owner reviews existing entitlements; unnecessary access is removed and new access is requested and approved. Do not simply add new permissions without evaluating existing permissions.

### 5.4 Remote/VPN access
VPN requires a documented business need and named approval from the appropriate manager/system owner as defined in the authorization matrix. Use MFA where technically supported. Access must be linked to an individual where practicable, limited by role and duration, and logged. Third-party VPN access must include sponsor, purpose, allowed destinations, expiry, and review.

### 5.5 Privileged access
Administrative access requires explicit approval from the relevant system owner and designated authority. Separate daily-use and admin accounts where supported; use MFA; restrict and log privileged actions; prohibit shared admin credentials except approved break-glass arrangements. Service accounts must have a named owner, documented purpose, restricted permissions, credential/key rotation or equivalent lifecycle controls, and periodic review.

### 5.6 Emergency access
Emergency access is permitted only to address a documented urgent operational or security need. Record the requester, reason, approver under the emergency delegation, scope, start/end, changes made, and logs. Access must be time-bound. A designated independent reviewer must review the use promptly after the event. An emergency workflow must not become the routine route for access.

### 5.7 Periodic review
System/data owners review access at a frequency set by the approved risk-based schedule. As a starting proposal, review privileged and remote access at least quarterly, and other access at least semi-annually; management must approve final cadence. Reviews must identify stale, excessive, orphaned, external, and conflicting access, with actions and closure evidence.

### 5.8 Termination
HR/Admin notifies IT and relevant system owners through the approved workflow. At the effective termination time or earlier risk-based time authorized by management, IT blocks sign-in and revokes VPN, tokens, sessions, privileged access, and relevant third-party access. Mailbox retention, delegation, legal holds, and business data transfer are separate authorized decisions; do not delay sign-in blocking solely because email data will be retained.

### 5.9 Monitoring and exceptions
Access logs must be retained according to the approved logging/retention rules. Exceptions state the reason, affected account/system, risk, compensating controls, expiry, approving risk owner, and review date. Expired exceptions must be removed or reapproved before continuation.

## 6. Minimum record set

- Access request and approval.
- Authorization matrix version used.
- Implementation record and timestamp.
- MFA/status evidence where relevant.
- Current Access Register.
- Periodic access review and remediation record.
- Emergency access log and post-use review.
- Revocation/offboarding evidence.
- Exception register and approved expiry.
- Evidence references retained under the Evidence Register.

## 7. Non-compliance and escalation

Suspected access without authorization, a compromised account, or unexplained privileged activity must be promptly reported through the incident process to the designated IT/security owner and UOA sponsor. Containment must be authorized and proportionate, with evidence preserved.

## 8. Implementation and approval

This policy must not be treated as effective until the authorized UOA approver signs off. Before effective date, the organization must confirm:
- Actual identity, VPN, cloud, and line-of-business systems in scope.
- Access approvers and emergency delegation.
- MFA and logging capabilities.
- Access review frequency and evidence repository.
- Related procedures and forms.
- Communication/training arrangements.

| Approval role | Name | Signature / record | Date |
|---|---|---|---|
| UOA management / delegated authority | To confirm | Pending | |
| Policy owner | To confirm | Pending | |
| Relevant system/data owner representative | To confirm | Pending | |

## Revision history

| Version | Date | Description | Status |
|---|---|---|---|
| 0.1 | 2026-10-09 | Initial policy draft from Audit remediation requirements | Draft |
