# Restore Test Procedure and Record

- **Document ID:** UOA-IT-RESTORE-PRO-001
- **Version:** 0.1 — Draft
- **Status:** For review; not approved
- **Procedure owner:** IT Operations (to confirm)
- **Business validation:** Relevant data/system owner

## 1. Purpose

Prove that selected backup copies can be restored and that recovered data is usable, complete enough for the test objective and within approved recovery targets.

## 2. Test planning

Define before the test:
- System/dataset, owner, classification and business purpose
- Test objective and scope (sample file, dataset, database, application or service)
- Backup point, source and target location
- Approved RPO/RTO and success criteria
- Test window, participants, dependencies and notification plan
- Isolation/security measures and protection of production
- Rollback/cleanup plan and change approval
- Evidence location and ticket/change reference

## 3. Safe test procedure

1. Obtain system owner approval and change authorization where required.
2. Select a representative recovery point and confirm its timestamp and expected contents.
3. Restore to an isolated or otherwise approved test location; do not overwrite production during a test.
4. Record start/end time and any manual intervention.
5. Verify technical integrity using appropriate checks (for example, file readability, checksums where available, database consistency checks or application startup).
6. Ask the data owner or delegate to validate business usability and expected content without exposing unnecessary sensitive data.
7. Compare achieved recovery point with approved RPO and elapsed recovery time with approved RTO, if targets have been approved.
8. Record gaps, failures, missing dependencies and remediation owners/due dates.
9. Clean up test data securely after approval and according to retention requirements.
10. Close only when evidence is stored and the owner has accepted the result or a remediation plan is approved.

## 4. Pass/fail criteria

**Pass** only when the intended data/service is restored to the agreed scope, integrity/usability checks pass, the result is documented and any applicable approved RPO/RTO targets are met.

**Fail / partial** if the copy is unavailable, incomplete, corrupt, inaccessible due to missing keys/permissions, dependent services are missing, business validation fails, or approved targets are missed. Open a tracked corrective action and reassess risk.

A successful backup job is not a substitute for this test.

## 5. Restore test record

| Field | Entry |
|---|---|
| Test ID / ticket | |
| Date, time and timezone | |
| System/dataset and owner | |
| Test lead / participants | |
| Backup copy and recovery-point timestamp | |
| Source / target references | |
| Scope and test objective | |
| Approved RPO / RTO | |
| Start / completion time | |
| Integrity checks performed and result | |
| Business owner validation | |
| Result: Pass / Fail / Partial | |
| Issues and risk accepted (if any) | |
| Remediation owner and due date | |
| Evidence reference | |
| Cleanup confirmation | |
| Approver / closure date | |

## 6. Suggested test cadence

Set cadence according to business criticality, change rate, threat model, service capability and contractual requirements. Critical workloads should receive a formally approved, risk-based schedule; no universal cadence is asserted in this draft. Also test after material backup architecture changes and after incidents that may affect recoverability.

## 7. Evidence handling

Store screenshots, sanitized job reports, validation results and relevant logs in the approved restricted evidence repository. Git should contain the procedure and a reference to evidence, not raw production data, credentials or sensitive exports.

## 8. Change history

| Version | Date | Change |
|---|---|---|
| 0.1 | 2026-10-09 | Initial draft |
