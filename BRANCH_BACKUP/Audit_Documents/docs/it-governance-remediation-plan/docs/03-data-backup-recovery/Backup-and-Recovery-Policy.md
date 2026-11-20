# Backup and Recovery Policy

- **Document ID:** UOA-IT-BACKUP-POL-001
- **Version:** 0.1 — Draft
- **Status:** For review; not approved
- **Policy owner:** UOA IT owner (to confirm)
- **Business approvers:** Relevant system/data owners and UOA management
- **Effective date:** To be assigned after formal approval

## 1. Purpose

Set minimum requirements for protecting business data, detecting backup failures and restoring data or services after accidental deletion, corruption, cyberattack, infrastructure failure or other disruption.

## 2. Scope

Servers, NAS/file services, endpoints where applicable, databases, SaaS data and cloud repositories in the approved UOA IT inventory. Scope must be reconciled against actual assets and service contracts.

## 3. Policy requirements

1. Each in-scope system/dataset must have a named business owner, technical custodian, classification, recovery priority and documented backup design.
2. The business owner must approve **Recovery Point Objective (RPO)**—maximum acceptable data loss measured in time—and **Recovery Time Objective (RTO)**—target time to restore service. Do not assign targets without business validation.
3. Backup frequency, retention, copy count and storage locations must be designed to meet approved RPO/RTO and contractual requirements.
4. At least one recovery copy should be protected against compromise or deletion of the primary environment. Where feasible, use an isolated, immutable or offline copy appropriate to the threat model.
5. Backup credentials and administrative roles must be separated from ordinary user access where feasible. Protect backup consoles with MFA and least privilege.
6. Backups must be encrypted in transit and at rest where supported and required by classification. Key access and recovery procedures must be documented.
7. Replication, synchronization and RAID are not substitutes for recoverable backup copies.
8. Monitoring must identify job failures, missed schedules, capacity problems, retention gaps and unauthorized configuration changes.
9. A successful job status alone does not prove that data can be restored. Restore tests must be performed and evidenced on a risk-based schedule.
10. Retention and deletion must be approved by the data owner and aligned with business, contractual and applicable legal requirements. Avoid indefinite retention by default.
11. Backup copies must be inventoried with source, destination, owner, schedule, retention, encryption, access, last successful job and last restore test.
12. Changes to backup target, credentials, schedules, retention, encryption, replication or deletion require change control and rollback planning.
13. Restore of production data must be authorized, logged, validated by the data owner and checked for malware/integrity where appropriate.
14. Any suspected backup compromise, mass deletion or inability to recover must be handled under the incident-management and business-continuity processes.

## 4. Backup design principles

The detailed design is to be confirmed per workload. Consider:
- Operational recovery from accidental deletion or corruption
- Independent recovery if the primary site or credentials are compromised
- Offsite or geographically separated copy where required
- Retention/versioning and protection against ransomware
- Bandwidth, data growth, capacity and restore throughput
- SaaS export/API limitations and provider responsibility boundaries
- Dependencies such as identity, DNS, network, encryption keys and application configuration

Do not prescribe a fixed number of copies or a universal retention period without assessing workload criticality, risk, cost and recovery requirements.

## 5. Required backup plan per workload

| Field | Required value |
|---|---|
| System/dataset and environment | To be inventoried |
| Business owner / IT custodian | Named roles |
| Classification | Approved class |
| Data volume and growth | Measured estimate |
| Source and destination | Verified locations/account IDs |
| Backup method and schedule | Approved design |
| Retention and versioning | Owner-approved |
| RPO / RTO | Business-approved |
| Encryption and key custodian | Documented |
| Administrative access and MFA | Verified |
| Monitoring and escalation | Named owner/contact |
| Restore procedure and dependencies | Tested instructions |
| Last successful backup / restore | Evidence reference |
| Exception and remediation | If applicable |

## 6. Recovery priorities

The business owner and management must prioritize workloads and approve RPO/RTO. IT should not infer recovery priorities solely from system size or technical convenience. Record dependencies and recovery order, and validate the order in a tabletop or restore exercise.

## 7. Review and assurance

Review the policy at least annually and after a material incident, system change or audit finding. IT provides operational evidence; the designated control owner reviews exceptions; Internal Audit independently assesses effectiveness.

## 8. Approval record

| Approver | Name | Decision | Date |
|---|---|---|---|
| UOA management | To confirm | Pending | — |
| Business/data owner(s) | To confirm | Pending | — |
| IT owner | To confirm | Pending | — |

## 9. Change history

| Version | Date | Change |
|---|---|---|
| 0.1 | 2026-10-09 | Initial draft |
