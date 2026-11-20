# Backup Restore Test — Execution Checklist

**Status:** Draft 0.1 — test plan template; execute only with data/service-owner authorization.  
**Objective:** Demonstrate that selected backup data can be restored and validated, rather than relying solely on successful backup-job status.

## 1. Test authorization and scope

- Test ID:
- Dataset/application:
- Business/data owner:
- Technical owner/operator:
- Criticality and business purpose:
- Approved test/change reference:
- Source backup location and backup point:
- Target restore location:
- Data classification and access restrictions:
- Test date/window:
- Approved RPO:
- Approved RTO:
- Approver and date:
- Production impact assessment and rollback plan:
- Evidence repository reference:

Do not invent RPO/RTO. Obtain approval from the business/service owner. If targets are not defined, record this as a governance gap and do not claim recovery compliance.

## 2. Pre-test checklist

- [ ] Data owner has approved the dataset and restore test.
- [ ] Test scope, backup point, target, and validation criteria are documented.
- [ ] Restore target is isolated or explicitly approved and will not overwrite production.
- [ ] Required access is authorized and limited to named roles.
- [ ] Backup integrity/availability and required decryption mechanism are confirmed without exposing keys.
- [ ] Sufficient storage and network capacity are available.
- [ ] Application consistency requirements are understood.
- [ ] Security, privacy and data handling requirements are addressed.
- [ ] Rollback, stop criteria and escalation contacts are documented.
- [ ] Evidence collection and timestamps are prepared.

## 3. Execution record

| Step | Expected action | Actual result / timestamp | Evidence ID | Status |
|---|---|---|---|---|
| 1 | Identify approved recovery point and scope | To record during test | Pending | Not run |
| 2 | Start restore to approved target | To record during test | Pending | Not run |
| 3 | Record completion and elapsed time | To record during test | Pending | Not run |
| 4 | Validate expected files/objects/records and counts | To record during test | Pending | Not run |
| 5 | Validate checksums or application-level integrity where suitable | To record during test | Pending | Not run |
| 6 | Validate permissions, ownership, metadata and usability as applicable | To record during test | Pending | Not run |
| 7 | Compare recovery point to approved RPO | To calculate from actual timestamps | Pending | Not run |
| 8 | Compare elapsed recovery time to approved RTO | To calculate from actual timestamps | Pending | Not run |
| 9 | Obtain data/service-owner acceptance | To record | Pending | Not run |
| 10 | Clean up test data under approved process | To record | Pending | Not run |

## 4. Acceptance criteria

Mark the test **Pass** only when:
- The selected backup point is identifiable and authorized.
- The required scope is restored to the approved target.
- Data integrity and usability checks meet the pre-defined criteria.
- Permissions and metadata are acceptable where required.
- Measured recovery point and elapsed recovery time meet the business-approved RPO/RTO, if targets are defined.
- The data/service owner accepts the result.
- Evidence and any limitations are recorded.

Mark **Fail** when an explicit acceptance criterion is not met. Mark **Inconclusive** when the test cannot establish the result due to missing targets, evidence, scope or validation criteria. Do not relabel an inconclusive result as pass.

## 5. Failure handling

- Stop condition triggered:
- Data/service owner notified:
- Incident/change reference:
- Data integrity or exposure concern:
- Interim risk treatment:
- Root-cause analysis owner:
- CAPA ID and target date:
- Retest requirements:
- Authorized decision to retry / change method:

Do not delete or replace the existing backup based on a failed or inconclusive restore test.

## 6. Post-test sign-off

- Operator / date:
- Independent reviewer / date:
- Data/service owner acceptance / date:
- RPO result:
- RTO result:
- Overall result: Pass / Fail / Inconclusive
- Findings / CAPA references:
- Next test date / trigger:
- Evidence package ID:

**Critical rule:** A green backup job is not proof of recoverability. Retain the existing recovery path until a target copy and restore have been verified and any cutover/deletion is separately authorized.
