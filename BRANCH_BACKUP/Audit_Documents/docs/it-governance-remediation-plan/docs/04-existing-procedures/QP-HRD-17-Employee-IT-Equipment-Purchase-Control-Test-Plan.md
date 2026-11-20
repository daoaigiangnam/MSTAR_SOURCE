# QP-HRD-17 — Employee IT Equipment Purchase Control Test Plan

**Document status:** Draft 0.1 — for review; not an approved policy or procedure  
**Purpose:** Test whether employee IT equipment purchases have a documented business need, proper authorization, compliant procurement, and traceable receipt and assignment.  
**Source dependency:** Confirm the controlled version, effective date, process owner, approval matrix, and required records for QP-HRD-17 before testing.

## 1. Scope and safeguards

Test a risk-based sample of employee IT equipment purchases across available request, approval, quotation/selection, purchase, receipt, asset registration, and handover records. Include exceptions or urgent purchases if present in the actual population. Do not infer the approval matrix from this draft; use the current approved source process and delegation of authority.

Keep invoices, employee identifiers, supplier-sensitive documents, and other restricted evidence in approved storage. This repository should contain only evidence IDs and sanitized summaries.

## 2. Sample register

| Sample ID | Purchase/request reference | Equipment type | Requesting unit | Approval evidence | Procurement evidence | Receipt/asset/handover evidence | Result |
|---|---|---|---|---|---|---|---|
| EP-01 | To be populated | To be populated | Restricted evidence reference | To be tested | To be tested | To be tested | Not tested |
| EP-02 | To be populated | To be populated | Restricted evidence reference | To be tested | To be tested | To be tested | Not tested |
| EP-03 | To be populated | To be populated | Restricted evidence reference | To be tested | To be tested | To be tested | Not tested |

Record the real population, period, sample selection method, and reason for including each sample.

## 3. Test steps

1. Verify QP-HRD-17 is current and identify required request fields, approval roles, spending thresholds, procurement controls, exception path, and records retention.
2. Confirm each sampled request states the employee/business need, equipment specification, quantity, destination, and cost estimate.
3. Match dated approvals to the authorized approver and applicable delegation/threshold. Verify approval preceded commitment or purchase unless a documented exception applies.
4. Inspect quotations, supplier selection, purchase order, and procurement/finance evidence required by the approved process. Record missing records; do not assume procurement steps occurred.
5. Match ordered items and quantities to delivery/receipt evidence; investigate substitutions, price differences, missing items, or damaged equipment.
6. Verify each received item has an asset identifier and required inventory fields, including custodian/assigned user, location, status, and warranty details where applicable.
7. Match handover evidence to the actual assignee and confirm the user acknowledged receipt if required by the procedure.
8. Check whether security baseline, endpoint protection, encryption, and standard configuration requirements were addressed before the equipment entered production, where applicable.
9. Reconcile a sample of purchases to the asset register and a sample of recently added assets back to purchase/receipt evidence to test completeness in both directions.
10. Record factual exceptions with evidence IDs, applicable criteria, risk, owner, due date, and CAPA reference.

## 4. Result criteria

- **Pass:** Need, authorization, procurement, receipt, inventory registration, and handover are evidenced and consistent with the approved process.
- **Fail:** A required approval was bypassed, purchase exceeded delegated authority, goods cannot be reconciled, or required asset/handover controls were not performed.
- **Inconclusive:** The source process, approval threshold, population, or supporting records cannot be confirmed.
- **Not applicable:** Supported by a documented rationale approved by the test lead.

A missing record is an evidence/control gap; do not overstate it as proof of fraud or intentional circumvention without corroborating evidence.

## 5. Finding and CAPA fields

Finding ID; sample ID; criterion; verified condition; evidence ID; risk/impact; root cause; corrective action; interim control; accountable owner; target date; retest evidence; effectiveness result; closure approver.

CAPA closure requires evidence that the control operates, not merely that a form or policy has been drafted.

## 6. Test record and approval

- Test lead / process owner: To be assigned or confirmed
- Test period / population / sample size: To be completed from actual records
- Evidence register and CAPA references: To be completed
- Prepared by / date; reviewed by / date; approved by / date: To be completed under the organization's approval process

**Approval note:** This is a draft test aid. Validate it against the current controlled QP-HRD-17 and obtain appropriate approval before operational use.
