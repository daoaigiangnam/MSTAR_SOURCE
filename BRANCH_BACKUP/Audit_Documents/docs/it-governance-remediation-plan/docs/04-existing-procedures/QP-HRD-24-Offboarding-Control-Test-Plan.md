# QP-HRD-24 — Offboarding Control Test Plan

- Document ID: UOA-IT-TEST-024
- Version: 0.1
- Status: Draft; process-owner validation pending
- Date: 2026-10-09

## Purpose and source limitation

The Master Plan summarizes the QP-HRD-24 flowchart as including access termination, equipment return, verification and an email-disable step 30 days after departure. Confirm the exact wording and intended meaning against the current controlled source. This test plan does not establish that the source flowchart is defective or that the control failed in operation.

## Key distinction requiring owner decision

Blocking sign-in and revoking access are not the same as retaining, delegating, archiving or deleting a mailbox. The authorized business, HR and system owners must clarify what the 30-day email step means. Do not delay required sign-in blocking merely because mailbox data may need to be retained. Record the approved rule and its authority before changing the process.

## Preconditions

- [ ] Current controlled QP-HRD-24 and associated offboarding checklist obtained.
- [ ] HR/Admin source of departure dates and notification responsibilities confirmed.
- [ ] In-scope identity, VPN, application, privileged and vendor access systems identified.
- [ ] Approved timing requirement and escalation route confirmed.
- [ ] Test period and sampling approach approved.

## Sample testing record

| Sample ID | Departure effective time | HR notification evidence | Identity block timestamp | VPN/app/privileged revocation evidence | Asset return / exception | Mailbox decision approval | Result / exception ID |
|---|---|---|---|---|---|---|---|
| | | | | | | | |

## Test steps

1. Obtain an authorized list of departures for the agreed test period and document its source and extraction date.
2. Select a risk-based sample, including urgent departures and users with remote or privileged access where available.
3. Compare departure effective time with account sign-in blocking and revocation of VPN, privileged, application, session and token access where supported.
4. Verify equipment return or a documented exception and accountable follow-up.
5. Check whether mailbox retention, delegation, legal hold, archive, license removal or deletion has a separate documented decision where applicable.
6. Confirm the 30-day email step has a clear, approved meaning and does not unintentionally leave authentication enabled beyond the approved timeframe.
7. Record delays, incomplete evidence and exceptions. Preserve original logs and do not create retrospective approvals.

## Result criteria

- **Pass:** the required access is revoked within the approved timeframe, equipment disposition is recorded, mailbox handling follows a separate authorized decision where needed, and evidence is complete.
- **Fail:** a sampled account remains accessible beyond the approved requirement, required access is not revoked, or a required control demonstrably fails.
- **Inconclusive:** records do not establish the actual timing or outcome. Record the evidence gap and owner; do not report a pass.

## Test summary

- Population, period and source:
- Sampling rationale and sample size:
- Passed / failed / inconclusive:
- Delayed revocations and risk:
- Immediate containment, if authorized:
- CAPA IDs and owners:
- Independent reviewer and date:
- Conclusion and limitations:

## Decision and approval record

| Decision | Owner | Evidence / reference | Date |
|---|---|---|---|
| Meaning of the 30-day email step confirmed | UOA process owner / HR/Admin / system owner | | |
| Access revocation timing approved | UOA delegated authority | | |
| Procedure revision or no-change decision | Controlled procedure owner | | |

## Revision history

| Version | Date | Change | Status |
|---|---|---|---|
| 0.1 | 2026-10-09 | Initial test plan | Draft |
