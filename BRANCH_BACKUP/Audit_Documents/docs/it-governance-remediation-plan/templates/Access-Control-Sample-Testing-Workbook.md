# Access Control Sample Testing Workbook

- **Document ID:** UOA-IT-AUD-TPL-003
- **Version:** 0.1 — Draft template
- **Status:** For review; not approved
- **Purpose:** Test evidence for user access grants, changes and revocations.

## A. Test scope

| Field | Entry |
|---|---|
| Test ID | |
| Period under review | |
| Systems / identity platforms | |
| Population source and extraction date | |
| Population count | |
| Completeness/reconciliation checks | |
| Sampling method and rationale | |
| Sample size approved by reviewer | |
| Tester / independent reviewer | |

## B. Access grant sample

Use one row per sample. Reference evidence in the restricted evidence repository; do not paste sensitive exports or personal data into Git.

| Sample ID | User/account reference | System/role | Request ID | Manager approval verified? | System/data owner approval verified? | Approval precedes grant? | IT implementation evidence? | Least privilege checked? | Result / exception ID |
|---|---|---|---|---|---|---|---|---|---|
| AG-01 | | | | To verify | To verify | To verify | To verify | To verify | |
| AG-02 | | | | To verify | To verify | To verify | To verify | To verify | |
| AG-03 | | | | To verify | To verify | To verify | To verify | To verify | |

## C. VPN / privileged access sample

| Sample ID | Account reference | Business need | Required approvals | MFA/config evidence | Scope/expiry | Last review | Result / exception ID |
|---|---|---|---|---|---|---|---|
| PV-01 | | | To verify | To verify | To verify | To verify | |
| PV-02 | | | To verify | To verify | To verify | To verify | |

## D. Access change / mover sample

| Sample ID | Account reference | Personnel/role change reference | Old access removed? | New access approved? | Effective timestamps checked? | Result / exception ID |
|---|---|---|---|---|---|---|
| MV-01 | | | To verify | To verify | To verify | |
| MV-02 | | | To verify | To verify | To verify | |

## E. Leaver revocation sample

| Sample ID | Personnel reference | Effective termination time | HR/Admin notice reference | Systems in scope | Revocation timestamp(s) | Delayed/missing access | Exception/CAPA ID |
|---|---|---|---|---|---|---|---|
| LV-01 | | | | | | | |
| LV-02 | | | | | | | |

## F. Evaluation rules

For each sample, mark **Pass**, **Fail**, **Partial** or **Unable to test** against criteria approved before testing.

- Missing approval evidence is an evidence/control gap; do not assume approval occurred.
- If access was granted before approval, record the exception and assess impact.
- If the population is incomplete, the conclusion must state that limitation.
- If a compensating control exists, inspect its evidence rather than assuming it mitigates the gap.
- Retest remediation using newly operated control evidence over a defined period.
- Do not include credentials, access tokens, private keys or unnecessary personal data in this workbook.

## G. Summary and sign-off

| Metric | Result |
|---|---|
| Population count | |
| Sample count | |
| Pass | |
| Fail | |
| Partial | |
| Unable to test | |
| Key exceptions / risk IDs | |
| CAPA owners / due dates | |
| Overall conclusion and limitations | |
| Tester / date | |
| Reviewer / date | |
