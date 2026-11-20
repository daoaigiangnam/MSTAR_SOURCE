# Backup Control Testing Checklist

- **Document ID:** UOA-IT-AUD-TPL-004
- **Version:** 0.1 — Draft template
- **Status:** For review; not approved
- **Purpose:** Validate backup ownership, operation and recoverability using evidence.

Mark each item **Pass / Gap / N/A / To verify**. Record evidence references in the approved restricted repository.

| # | Test | Evidence / method | Status | Evidence ID / owner |
|---:|---|---|---|---|
| 1 | Backup scope is reconciled to critical systems/data | Compare asset/service inventory with backup configuration | To verify | |
| 2 | Each workload has a business/data owner | Owner register and owner confirmation | To verify | |
| 3 | Technical custodian is assigned | Operations roster / service register | To verify | |
| 4 | Backup destination is identified and approved | Config, account/tenant reference and approval | To verify | |
| 5 | Account owner and administrators are known | Provider/tenant records and role list | To verify | |
| 6 | Data classification and retention are approved | Classification/retention record | To verify | |
| 7 | Schedule and method match approved design | Job configuration and policy | To verify | |
| 8 | Recent job results are successful or failures tracked | Job reports and linked tickets | To verify | |
| 9 | Failed jobs were escalated and resolved | Incident/ticket timeline | To verify | |
| 10 | Capacity is sufficient for planned retention | Usage and growth evidence | To verify | |
| 11 | Encryption and key recovery arrangements are documented | Configuration and key custodian process (no key material) | To verify | |
| 12 | Backup administration is restricted | Role export, MFA/config evidence and review | To verify | |
| 13 | Copy is appropriately separated/protected | Architecture and access evidence | To verify | |
| 14 | Retention/versioning/deletion protection are enabled as approved | Configuration and change history | To verify | |
| 15 | Restore procedure exists and dependencies are known | Runbook, identity/network/application dependencies | To verify | |
| 16 | Restore test completed in safe target | Approved test plan and result | To verify | |
| 17 | Data integrity and business usability validated | Technical checks and owner sign-off | To verify | |
| 18 | Approved RPO/RTO evaluated where defined | Test timestamps and business-approved targets | To verify | |
| 19 | Cloud/vendor data ownership and exit rights are clear | Contract, written decision and export test/evidence | To verify | |
| 20 | Exceptions have owner, approval and expiry | Risk register and exception record | To verify | |

## Critical caution

Do not migrate or delete the only known-good backup copy as part of audit remediation until ownership is confirmed, the target is approved, transfer completeness is verified and a restore from the target has passed. Preserve evidence of the decision and rollback plan.

## Test summary

| Field | Entry |
|---|---|
| Test ID / date | |
| Systems and period | |
| Tester / reviewer | |
| Sample/population | |
| Key gaps and risk IDs | |
| CAPA owner / due date | |
| Overall conclusion / limitations | |
