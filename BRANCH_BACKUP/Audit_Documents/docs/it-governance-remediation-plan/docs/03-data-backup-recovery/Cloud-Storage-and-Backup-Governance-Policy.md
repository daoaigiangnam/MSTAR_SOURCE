# Cloud Storage and Backup Governance Policy

- **Document ID:** UOA-IT-CLOUD-POL-001
- **Version:** 0.1 — Draft
- **Status:** For review; not approved
- **Policy owner:** UOA management / designated data owner (to confirm)
- **Technical owner:** IT (to confirm)
- **Approvers:** UOA management, data owners, Procurement/Legal/Compliance as applicable
- **Effective date:** To be assigned after formal approval

## 1. Purpose

Define how UOA approves, owns, secures and exits cloud storage used for company data and backup copies.

## 2. Scope

All cloud storage and backup services used for UOA data, whether subscribed to by UOA, Mstar or another service provider. The actual provider, tenant/account, region, contract and stored data must be confirmed through inventory and evidence.

## 3. Governance requirements

1. Use only cloud services and accounts approved by UOA's authorized decision-maker.
2. Record the legal/customer account owner, tenant/subscription owner, billing party, data owner, technical administrators and recovery/export rights. These roles may differ and must not be assumed to be the same.
3. Before storing company or customer data, document purpose, data classification, owner approval, access model, retention, encryption capabilities, audit logs, recovery needs and exit method.
4. Third-party access must be named, justified, time-bound where feasible, least-privileged and periodically reviewed.
5. Require MFA for cloud administrators and privileged users wherever supported; use separate administrative identities and avoid shared accounts.
6. Restrict public links and anonymous access. External sharing must be approved by the data owner and periodically reviewed.
7. Enable available audit/activity logs and retain them according to an approved schedule.
8. Confirm encryption in transit and at rest, key-management responsibilities, data location, incident notification and service continuity in provider documentation/contract.
9. Ensure backup data is recoverable independently of a single user's account where technically and contractually feasible.
10. Document how UOA can retrieve data, restore service, terminate access and export data if the provider relationship ends.
11. Do not treat synchronization or file replication as a complete backup unless versioning, deletion recovery, retention and independent restore capability are confirmed.
12. No one may delete or migrate existing backup copies until a change plan, owner approval, verified replacement and successful restore test are documented.

## 4. Approval workflow

1. Requester identifies the business purpose, dataset, classification, expected volume, users and retention.
2. Data owner confirms ownership, business need and permitted data.
3. IT assesses identity/access, security, backup, monitoring, recovery and technical dependencies.
4. Procurement/Legal/Compliance reviews contract, data location, customer commitments and exit rights where required.
5. Authorized UOA approver records the decision before production data is transferred.
6. IT implements the approved configuration and captures evidence.
7. Owner and IT verify access, logging, retention and a sample restore; record exceptions and remediation.

## 5. Specific review: Mstar-managed cloud account

The source audit recap reports that HR/Admin may not have known that some company backup data was stored in an Mstar cloud account, and that the customer did not want its backup data to remain in an Mstar Google Drive account. These are reported concerns requiring validation, not proof of the complete current state.

Until UOA issues a documented decision:
- Inventory the affected backup sets, dates, data owners, storage account/tenant, administrators, sharing settings, retention and restore dependencies.
- Preserve current copies and access logs; do not perform a destructive migration or deletion.
- Restrict access to authorized personnel and review external sharing.
- Agree the target account owner, billing owner, administrator model, customer-held ownership requirements and emergency recovery access.
- If migration is approved, test a representative restore from the target location and verify completeness before changing or deleting the source copy.
- Document the cutover, rollback plan, evidence and final deletion approval separately.

## 6. Minimum cloud register fields

Service/provider; tenant/account identifier (non-secret); subscription/billing owner; business data owner; IT custodian; administrators; data categories/classification; region/location (verified); purpose; contract/reference; MFA/logging/encryption status; backup/versioning/retention; restore test date; export/exit method; approval reference; next review date; risks and actions.

Do not commit credentials, access tokens, private keys, customer files or raw sensitive logs to this repository.

## 7. Monitoring and review

IT reviews cloud administrators, external sharing, inactive accounts, capacity, backup/retention status and material security alerts at a cadence approved by UOA. High-risk changes are reviewed immediately. The exact review cadence and log-retention period must be approved based on risk and provider capability.

## 8. Exceptions

Document scope, reason, risk, compensating controls, accountable owner, approver and expiry. An exception does not override contractual or legal requirements.

## 9. Approval record

| Role | Name | Status | Date |
|---|---|---|---|
| UOA management | To confirm | Pending | — |
| Data owner | To confirm | Pending | — |
| IT owner | To confirm | Pending | — |
| Procurement/Legal/Compliance, if applicable | To confirm | Pending | — |

## 10. Change history

| Version | Date | Change |
|---|---|---|
| 0.1 | 2026-10-09 | Initial draft |
