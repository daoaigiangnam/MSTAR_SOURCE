# Backup Inventory and Ownership Register

- **Document ID:** UOA-IT-REG-BKP-001
- **Version:** 0.1 — Draft template
- **Status:** Populate from verified inventory; not an assertion of current state
- **Register owner:** IT Operations (to confirm)
- **Business validation:** Each data/system owner

## 1. Purpose and rules

Use this register to identify what is protected, who owns it, where copies are stored, whether jobs are healthy and whether recovery has been tested.

- Populate from verified configurations, contracts, job reports and owner confirmation.
- Use stable asset IDs and non-secret account/tenant identifiers.
- Mark unknown fields **To verify**; do not guess.
- Record restricted evidence by reference ID in the approved evidence repository.
- Do not include passwords, access tokens, private keys, recovery codes or raw sensitive data.
- A listed backup destination is not considered approved until the approval reference is recorded.

## 2. Register template

| Field | Value / instructions |
|---|---|
| Backup ID | Unique ID, e.g. BKP-001 |
| System / dataset | Official system or dataset name |
| Business purpose | Short description |
| Business/data owner | Named accountable role/person |
| IT custodian | Operational owner |
| Provider / service | Verified provider/service |
| Source asset/location | Asset ID or controlled reference |
| Destination / account reference | Non-secret account/tenant identifier; do not record credentials |
| Account owner | Legal/customer account owner |
| Billing/subscription owner | Party responsible for subscription |
| Administrator group | Approved group/role reference |
| Data classification | Approved classification |
| Approximate protected volume | Measured value and measurement date |
| Data growth estimate | Basis and period |
| Backup method | Full/incremental/snapshot/export/other, as verified |
| Schedule / last successful job | Verified schedule and timestamp |
| Retention / versioning | Verified configuration |
| RPO / RTO | Business-approved values or “Pending approval” |
| Encryption / key custodian | Verified status and accountable role; never include key material |
| Isolation / immutability | Verified controls, if available |
| Monitoring / alert destination | Approved operational reference |
| Last restore test | Date, scope and evidence ID |
| Restore test result | Pass / Fail / Partial / Not tested |
| Data export / exit method | Verified method and owner |
| Approval reference | Ticket, decision or change ID |
| Risk / open action | Risk ID, action owner and due date |
| Last reviewed / next review | Dates |
| Evidence reference | Restricted repository ID, not a raw file in Git |

## 3. Initial inventory queue

The following are inventory prompts, not confirmation that every item exists or is configured:

| Candidate area | What to verify |
|---|---|
| NAS / file server | Shares, owners, protected data, capacity, schedule, destinations, retention and restore evidence |
| Google Drive / cloud storage | Tenant/account owner, shared drives/folders, stored backup sets, external sharing, retention and export rights |
| Mstar-managed cloud account | Whether UOA data is present; exact scope, account ownership, administrators, approvals and recovery dependencies |
| Servers and databases | Workloads, application-consistent backup method, configuration/system-state dependencies |
| SaaS services | Provider backup/export limitations, retention, recovery process and contract responsibility |
| Offsite repository | Physical/logical separation, access, encryption, connectivity and recovery path |

## 4. Reconciliation workflow

1. Export or inspect the authoritative asset and backup configuration sources using approved read-only access.
2. Reconcile each workload against business owner and service provider records.
3. Validate actual job status and destination from recent logs.
4. Confirm ownership and authorization for every cloud or third-party destination.
5. Identify missing coverage, stale copies, unapproved destinations, failed jobs and untested recovery.
6. Assign risk/action IDs and owners; prioritize according to business impact and exposure.
7. Obtain owner approval for the target state before any migration or deletion.
8. Review and sign off the register; schedule recurring reviews.

## 5. Approval and review

| Role | Name | Decision/date |
|---|---|---|
| IT register owner | To confirm | Pending |
| Business/data owners | To confirm | Pending |
| UOA management / delegate | To confirm | Pending |

## 6. Change history

| Version | Date | Change |
|---|---|---|
| 0.1 | 2026-10-09 | Initial register template |
