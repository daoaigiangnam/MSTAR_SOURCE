# Vendor and Third-Party Access Management Policy

- **Document ID:** UOA-IT-VND-POL-001
- **Version:** 0.1 — Draft
- **Status:** For review; not approved
- **Policy owner:** IT owner / Procurement (to confirm)
- **Approver:** UOA management / delegated contract owner (to confirm)

## 1. Purpose

Control third-party IT services and vendor access to UOA systems, networks, cloud accounts and data throughout the relationship lifecycle.

## 2. Scope

IT service providers, consultants, contractors, cloud providers, maintenance vendors and other external parties who access UOA systems or process/store UOA data. Existing contracts and responsibilities must be verified.

## 3. Policy requirements

1. Each vendor relationship must have an accountable UOA business/contract owner and a technical service owner.
2. Before access or data transfer, document business purpose, systems/data in scope, risk, required access, duration, support window and approving authority.
3. Review relevant contract terms for confidentiality, data handling, sub-processors, data location, security controls, incident notification, evidence, service continuity, data return/deletion and exit rights, with Legal/Procurement as appropriate.
4. Provide named identities and least-privileged, scoped access. Shared accounts are prohibited unless specifically justified and controlled by an approved exception.
5. Require MFA for remote/privileged access wherever supported; use approved VPN or access gateway and restrict source/network scope where feasible.
6. Grant time-bound access where practical and record expiry/review date.
7. Log vendor activity where supported and review activity proportionate to risk.
8. Do not allow vendor personnel to approve their own UOA access or unilaterally authorize changes to customer data or UOA security settings.
9. Material production changes by vendors must follow the change-management process.
10. Review access when personnel change, contract scope changes, incidents occur or the contract ends.
11. Revoke vendor access promptly at contract/engagement end or when no longer required, and validate revocation.
12. Ensure service accounts, API keys, certificates and secrets have an internal owner, restricted scope, secure storage and rotation/revocation plan. Never store secret values in Git.
13. Keep evidence of approval, contract, access grant, review, change activity and revocation in the approved repository.

## 4. Vendor onboarding and access workflow

1. Business owner submits vendor access request and identifies contract/service reference.
2. Procurement/Legal reviews relevant contractual requirements.
3. IT assesses access route, systems, data classification, privilege level, logging, MFA, duration and recovery/rollback needs.
4. System/data owner approves the exact access scope.
5. Authorized approver records decision before access is enabled.
6. IT creates a named account or approved controlled identity, configures restrictions and sets expiry/review date.
7. Vendor and internal sponsor validate access and acknowledge applicable rules.
8. IT records evidence and schedules access review.

## 5. Periodic review and offboarding

Review vendor list, contract status, active accounts, privileged access, MFA/logging status, last-use data where available and business sponsor. At contract end or scope reduction, disable accounts, revoke sessions/tokens/keys as applicable, recover assets, transfer documentation/data ownership and obtain confirmation from the contract owner.

The exact review frequency and revocation target must be approved by UOA based on risk and contract requirements.

## 6. Vendor register fields

Vendor/service; contract reference and expiry; business owner; IT owner; data categories/classification; systems in scope; access route; named users/service identities; privilege; approval reference; MFA/logging; start/expiry/review dates; incident contacts; data location/return/deletion terms; last access review; offboarding evidence; open risk/action.

## 7. Exceptions

Exceptions must document scope, justification, risk, compensating controls, approving authority and expiry. No exception may silently override contract or legal obligations.

## 8. Approval record

| Role | Name | Decision | Date |
|---|---|---|---|
| UOA management / delegate | To confirm | Pending | — |
| IT owner | To confirm | Pending | — |
| Procurement/contract owner | To confirm | Pending | — |

## 9. Change history

| Version | Date | Change |
|---|---|---|
| 0.1 | 2026-10-09 | Initial draft |
