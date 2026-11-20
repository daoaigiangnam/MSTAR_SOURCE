# Backup Ownership and Cloud Storage Validation Plan

**Status:** Draft 0.1 — proposed validation plan; not a finding closure or approval to move/delete data.  
**Objective:** Establish what data is backed up, where it resides, who owns the data and storage account, which access is authorized, and whether backup and recovery arrangements are approved and testable.

## 1. Known issue framing

Prior communications reported that some backup data was stored in a cloud account associated with Mstar and that the customer did not accept the existing ownership arrangement. Treat this as a reported issue until validated with current contracts, account records, data-owner decisions and technical evidence. Do not assume the cloud provider, legal owner, contractual terms, data location, or completeness of backup without evidence.

## 2. Safety gates

Do not migrate, delete, overwrite, reconfigure, or materially change backup data until all relevant gates below are satisfied:
1. Authorized data owner confirms the data sets and business purpose.
2. Account/tenant owner and authorized administrators are identified.
3. Target storage, ownership, access, contractual terms and data-handling requirements are approved.
4. Capacity and retention are verified for the actual data volume and growth.
5. Copy/replication completes and integrity/completeness are verified.
6. A restore test succeeds for representative critical data.
7. Cutover and rollback plans are approved, including responsibility and timing.
8. Any source deletion is separately authorized after the retention and recovery requirements are satisfied.

## 3. Validation workstreams

| Workstream | Questions to resolve | Evidence required | Owner to confirm | Exit condition |
|---|---|---|---|---|
| Data scope | Which shares, folders, applications, datasets and versions are included? What is excluded? | Backup job scope, source inventory, exclusions, owner confirmation | Data/system owners and backup operator | Approved scope and exclusions |
| Storage identity | Which provider, tenant/account, region/location and subscription are used? Who is the legal/account owner? | Console metadata, contract/order records, account ownership confirmation | Account owner/procurement/management | Owner and service arrangement verified |
| Access control | Who can read, change, delete, share, or administer the data? Is access attributable and reviewed? | Sanitized role/access export, audit logs, access review and approval records | Account owner and security/IT | Authorized least-privilege access evidenced |
| Data governance | Who owns the data? What classification, retention, privacy and contractual rules apply? | Data-owner decision, classification, retention and contract requirements | Data owner/legal/compliance where applicable | Written decisions recorded |
| Backup operation | Are jobs successful, complete, encrypted and monitored? Are failures escalated? | Job history, monitoring/alert evidence, encryption/configuration evidence | Backup operator | Coverage and monitoring verified |
| Resilience | Are copies separated appropriately from production and protected from accidental/malicious deletion? | Architecture/configuration evidence, immutability/offline/independent copy settings where applicable | IT/security and data owner | Risk treatment accepted and controls tested |
| Recovery | Can critical data be restored within approved recovery objectives? | Restore-test records, integrity validation, measured recovery times | Service/data owners and IT | Restore test passes against approved objectives |
| Capacity | Is target capacity sufficient for current data, retention, versions and growth? | Measured used capacity, retention calculations, growth assumptions, headroom decision | IT/storage owner | Capacity and expansion trigger approved |
| Migration/cutover | How will data be copied, reconciled, tested, cut over and rolled back? | Approved change plan, checksums/manifests where suitable, logs, restore evidence | Change owner and data owner | All cutover gates met |
| Decommission | Can the old copy be removed, and who approves? | Retention decision, legal/contract checks, validated new copy, written deletion authorization | Data owner and authorized approver | Separate deletion approval recorded |

## 4. Inventory template

| Field | Value / evidence reference |
|---|---|
| Dataset / share / application | To be confirmed |
| Business and data owner | To be confirmed |
| Data classification | To be confirmed |
| Source system / location | To be confirmed |
| Backup method / schedule | To be verified |
| Current stored size / measurement date | To be measured |
| Growth assumption / retention window | To be approved |
| Backup destination provider / tenant / account | To be verified |
| Account owner / contract reference | To be verified |
| Administrators and data readers | Restricted evidence reference |
| Encryption and key ownership | To be verified; never include keys |
| Retention / versioning / deletion protection | To be verified |
| Job status and alert owner | To be verified |
| Last successful restore test | Evidence reference or not recorded |
| Approved RPO / RTO | To be confirmed by business/service owner |
| Risk / exception / CAPA ID | To be populated |

## 5. Capacity decision method

Calculate target capacity from measured data, not an unverified headline number:

**Required usable capacity = current protected data + retained versions/changed blocks + metadata/overhead + expected growth over the planning horizon + operational headroom.**

The actual method depends on the backup product and whether it uses full copies, incremental copies, deduplication, compression, snapshots, or object versioning. Confirm whether advertised capacity is raw, formatted, usable after RAID, or quota-limited. Do not apply a universal percentage without validating the workload and retention model.

## 6. Recovery test requirements

For each selected critical dataset:
1. Record owner, dataset, backup point and approved change/test authorization.
2. Restore to an isolated or approved destination that will not overwrite production.
3. Verify file/object counts and sizes where suitable; use checksums or application-level validation where appropriate.
4. Confirm permissions, ownership, metadata and application usability where required.
5. Measure elapsed time and compare with business-approved RTO; identify the recovery point and compare with approved RPO.
6. Record failures, omissions, warnings, evidence IDs and corrective actions.
7. Obtain service/data-owner acceptance and schedule retest if unsuccessful.

RPO/RTO are not assumed by this draft; the business/service owner must approve the targets.

## 7. Decision record

- Confirmed data scope:
- Confirmed provider/tenant/account and legal/account owner:
- Approved data owner:
- Approved access list:
- Approved retention and data location:
- Approved target and capacity calculation:
- Restore test results:
- Open risks and compensating controls:
- Migration/cutover decision:
- Rollback owner and criteria:
- Separate source-deletion decision:
- Approver(s), date and evidence references:

**Critical rule:** Do not treat a completed copy job as proof of recoverability. Do not remove the existing backup until the target copy is verified, a restore test succeeds, ownership and retention are approved, and deletion is separately authorized.
