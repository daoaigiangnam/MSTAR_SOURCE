# QP-HRD-23 — Access Authorization Test Plan

- Document ID: UOA-IT-TEST-023
- Version: 0.1
- Status: Draft; approval and source validation pending
- Date: 2026-10-09

## Purpose and source limitation

The Master Plan summarizes the QP-HRD-23 flowchart as including approval, access implementation, verification including MFA, Access Register update and periodic review. This test plan does not prove those steps operate effectively. Confirm the exact source wording and sequence against the current controlled flowchart before finalizing this plan.

## Test objective

Determine whether a sample of access grants is supported by valid authorization, matches the actual entitlement, and is recorded and reviewed as required by the approved process.

## Preconditions

- [ ] Current controlled QP-HRD-23 and associated form obtained.
- [ ] Approved access authorization matrix and delegated authority obtained.
- [ ] System population and extraction scope recorded with timestamp and owner.
- [ ] Test period, sampling method and sample size approved by the control owner.
- [ ] Evidence stored in the approved restricted repository; this document contains references only.

## Sample testing record

| Sample ID | System/account type | Request ID | Required approver | Approval evidence ID | Actual access evidence ID | MFA/verification result | Register reconciled? | Exception ID / result |
|---|---|---|---|---|---|---|---|---|
| | | | | | | | | |

## Test steps

1. Select a risk-based sample from a dated system export. Include VPN, privileged or sensitive access where these are in scope.
2. Locate the request and confirm the business purpose, person/account, system, permissions and duration are clear.
3. Verify that the approver had the required authority at the time of approval.
4. Compare the approved scope with actual group membership, application roles or other effective permissions.
5. Verify MFA or other required authentication control from reliable configuration or test evidence, where applicable.
6. Confirm the Access Register reflects the grant and relevant timestamps.
7. Inspect the most recent periodic review and trace identified exceptions to closure evidence.
8. Record failures and missing evidence as exceptions; do not reconstruct or backdate approval records.

## Result criteria

- **Pass:** all required approval and implementation evidence exists, actual access matches approved scope, required verification is evidenced, and the register is accurate.
- **Fail:** unauthorized access, excess permissions, missing mandatory approval, or a required control demonstrably did not operate.
- **Inconclusive:** evidence cannot establish whether the control operated. Record the missing evidence and owner; do not report this as a pass.

## Test summary

- Population and extraction date:
- Sample selection rationale and sample size:
- Samples passed / failed / inconclusive:
- Exceptions and risk rating:
- Immediate containment, if authorized:
- CAPA IDs and owners:
- Independent reviewer and date:
- Conclusion and limitations:

## Approval and revision history

| Role | Name/title | Decision | Date/reference |
|---|---|---|---|
| Access control owner | To confirm | Pending | |
| UOA delegated approver | To confirm | Pending | |
| Independent reviewer | To confirm | Pending | |

| Version | Date | Change | Status |
|---|---|---|---|
| 0.1 | 2026-10-09 | Initial test plan | Draft |
