# Backup Operation and Monitoring Procedure

- **Document ID:** UOA-IT-BACKUP-PRO-001
- **Version:** 0.1 — Draft
- **Status:** For review; not approved
- **Procedure owner:** IT Operations (to confirm)

## 1. Purpose

Provide repeatable operating steps for running, monitoring, escalating and evidencing backup jobs.

## 2. Preconditions

Before operating a backup job, verify:
- System and dataset are in the approved inventory.
- Business owner, IT custodian, approved target, schedule, retention and RPO/RTO are recorded.
- Required access is authorized and uses approved named identities.
- Destination capacity, connectivity, encryption and retention settings are adequate.
- A documented procedure and escalation contact exist.
- Changes to production configuration have approved change records.

## 3. Routine operating steps

1. Review the backup dashboard or job logs for the previous run.
2. Check status, start/end time, transferred/processed volume, warnings, duration and destination capacity.
3. Confirm the job covers the expected source and destination; investigate unexpected scope changes.
4. Record failed, partial, skipped or unusually long jobs as incidents/operational tickets.
5. For a failure, capture non-sensitive error details, affected workload, last known good recovery point and potential RPO exposure.
6. Retry only where safe and consistent with the runbook. Do not repeatedly retry a destructive or potentially corrupting job without diagnosis.
7. Escalate based on workload criticality and time remaining before the approved RPO/RTO is breached.
8. Verify the next successful run and record closure evidence.
9. Review storage capacity, retention execution and alert delivery on the cadence approved for the service.
10. Protect logs and reports according to their sensitivity; never place secrets or raw customer data in the Git repository.

## 4. Failure and escalation record

Capture:
- Ticket/incident ID and detection time
- System, backup job and owner
- Failure type and non-sensitive error summary
- Last successful backup and known recovery point
- Expected RPO/RTO and estimated exposure
- Actions, retry/change references and results
- Escalation/notification timestamps
- Next action, accountable person and due date
- Evidence reference in the approved restricted location

Escalate immediately if there is suspected malicious deletion, compromised backup credentials, loss of the only known-good copy, unexplained retention changes, or a critical workload likely to exceed its approved recovery objective.

## 5. Daily/periodic checklist

- [ ] Review failed, warning, skipped and overdue jobs.
- [ ] Verify the most recent successful recovery point for critical workloads.
- [ ] Check destination capacity and forecast exhaustion.
- [ ] Review alerts and confirm notification routing works.
- [ ] Confirm retention/versioning has not been disabled or reduced without approval.
- [ ] Review privileged access or configuration changes affecting backups.
- [ ] Open and track incidents for unresolved failures.
- [ ] Record review date, operator and evidence reference.

The precise frequency must be defined per workload and approved service capability; this draft does not assume every platform supports continuous or daily checks.

## 6. Change control

Changes to source scope, destination, schedule, retention, encryption, credentials, replication or deletion require:
- Change/ticket reference and reason
- Business owner approval when data protection or recovery outcomes change
- Risk and dependency assessment
- Backup of current configuration where feasible
- Test and rollback plan
- Post-change verification and evidence

## 7. Evidence and records

Store job reports, failure tickets, configuration approvals and restore evidence in the approved restricted evidence repository. In Git, record only document references, sanitized summaries and non-sensitive metadata.

## 8. Procedure record template

| Field | Entry |
|---|---|
| Date/time and timezone | |
| Operator | |
| Workload / job ID | |
| Source / destination reference | |
| Result and last successful recovery point | |
| Warnings / errors (sanitized) | |
| Capacity / retention status | |
| Ticket / change reference | |
| Escalation / action owner / due date | |
| Evidence reference | |
| Reviewer / closure date | |

## 9. Change history

| Version | Date | Change |
|---|---|---|
| 0.1 | 2026-10-09 | Initial draft |
