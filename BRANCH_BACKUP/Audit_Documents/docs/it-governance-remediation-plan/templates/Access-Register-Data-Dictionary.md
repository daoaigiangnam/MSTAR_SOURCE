# Access Register — Data Dictionary and Blank Template
**Template ID:** UOA-IT-REG-ACC-001  
**Version:** 0.1 — Draft  
**Owner (proposed):** Mstar IT Lead with UOA system owners

## 1. Use

Maintain one controlled register or connected records covering each human, service, vendor and privileged account in scope. The authoritative account population should come from system exports, not only from manually maintained rows. Store actual exports in a restricted evidence repository; do not commit raw exports containing personal or sensitive data to Git.

## 2. Required fields

| Field | Meaning / rule |
|---|---|
| Account Record ID | Unique row/reference that does not reveal secrets |
| System / tenant | Actual system name and tenant/environment |
| Account ID | Stable identifier; minimize personal information |
| Account type | Standard, privileged/admin, service, shared exception, vendor, emergency |
| Account owner / responsible person | Named accountable owner; service account has technical/business owner |
| Department / sponsor | Current business owner or internal vendor sponsor |
| Employment / vendor status | Active, leave, transferred, departed, contract expired, unknown |
| Role / permissions | Actual current entitlement, group or privilege |
| Data/system owner | Owner accountable for access decision |
| Business purpose | Specific approved reason |
| Request ID | Link to request record |
| Approver / authority | Approver and delegation/matrix version |
| Approval timestamp | Timestamp of approval, with timezone if available |
| Granted/changed timestamp | Actual system implementation time |
| MFA status | Enabled, disabled, not supported, unknown; include evidence reference |
| Last sign-in / activity | If available and appropriate; record source and collection date |
| Expiry | Mandatory for temporary access |
| Last access review | Date, reviewer, result and action reference |
| Status | Active, pending, disabled, expired, exception |
| Evidence references | Controlled evidence IDs, not raw secrets |
| Exception / remediation ID | CAPA or exception reference |
| Next review date | Calculated only after approved cadence is established |

## 3. Blank register

| Record ID | System / tenant | Account ID | Type | Owner / sponsor | Role / permissions | Business purpose | Request ID | Approver / matrix version | Approval timestamp | Implemented timestamp | MFA status/evidence | Last activity source/date | Expiry | Last review / reviewer | Status | Evidence IDs | CAPA/exception | Next review |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| ACC-0001 | To inventory | To record | To classify | To assign | To export/verify | To confirm | To link | To verify | To verify | To export | To verify | To verify | If temporary | To record | Pending inventory | To assign | If needed | To set |

## 4. Reconciliation procedure

1. Export all accounts from every in-scope system.
2. Record export source, timestamp, scope, and collector.
3. Normalize identifiers and deduplicate carefully without losing source references.
4. Assign owner and account type, including service/shared/vendor/emergency identities.
5. Link each active entitlement to a valid approval and business purpose.
6. Record unmatched accounts, missing evidence, excessive privilege, stale accounts, or unknown owners as exceptions.
7. Get system/data owners to decide on corrective actions.
8. Apply approved changes through the access procedure and preserve implementation logs.
9. Have an independent reviewer validate a sample and document reconciliation completeness.
10. Re-run the reconciliation at the approved review cadence and after material changes.

## 5. Data handling

The complete access inventory is sensitive. Restrict access to designated IT/security, system owners, HR where necessary, and auditors according to UOA's approved evidence-access rules. Do not store passwords, hashes intended for authentication, private keys, recovery codes, or tokens in this register.
