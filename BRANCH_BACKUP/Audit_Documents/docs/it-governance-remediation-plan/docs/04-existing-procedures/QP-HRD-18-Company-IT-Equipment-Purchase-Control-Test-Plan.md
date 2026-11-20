# QP-HRD-18 — Company IT Equipment Purchase Control Test Plan

**Document status:** Draft 0.1 — for review; not an approved policy or procedure  
**Purpose:** Test authorization, procurement, receipt, registration, custody, and deployment controls for company-level IT equipment purchases.  
**Source dependency:** Verify the current controlled QP-HRD-18, applicable budget/delegation rules, procurement requirements, and required evidence before testing.

## 1. Scope and safeguards

Include a risk-based sample of company IT equipment purchases such as network equipment, servers, storage, endpoint fleets, and other infrastructure where present in the real population. Select samples based on value, criticality, recent purchase, exceptions, and audit risk. Do not assume that all listed equipment types exist in the organization.

Store contracts, invoices, serial numbers, configuration exports, and supplier-sensitive evidence in approved restricted storage. Put evidence IDs—not sensitive attachments—into this repository.

## 2. Sample register

| Sample ID | Request / purchase reference | Equipment / project | Business owner | Approval and budget evidence | Procurement / receipt evidence | Asset / deployment evidence | Result |
|---|---|---|---|---|---|---|---|
| CP-01 | To be populated | To be populated | To be confirmed | To be tested | To be tested | To be tested | Not tested |
| CP-02 | To be populated | To be populated | To be confirmed | To be tested | To be tested | To be tested | Not tested |
| CP-03 | To be populated | To be populated | To be confirmed | To be tested | To be tested | To be tested | Not tested |

Record population, period, selection method, and sample rationale from actual records.

## 3. Test steps

1. Confirm the controlled procedure's version, owner, approval sequence, budget requirements, technical review, sourcing rules, exception process, and retention requirements.
2. Trace the business requirement to a documented request or project justification, including capacity, compatibility, supportability, security, and lifecycle considerations where relevant.
3. Verify budget availability and dated approval by authorized roles before purchase commitment; check that the approval matches the final scope and amount.
4. Inspect quotations/tender or sourcing evidence, supplier due diligence, comparison/selection rationale, conflict-of-interest controls where required, and purchase order/contract evidence.
5. Compare the ordered specification, quantity, price, and delivery terms with approvals and received items; investigate material differences.
6. Verify receipt/acceptance evidence and confirm discrepancies or defects were resolved before acceptance/payment as required.
7. Verify asset registration includes a unique asset ID, type/model, serial number where applicable, acquisition date/cost, supplier/warranty, location, custodian/owner, lifecycle status, and support information according to the approved register standard.
8. For infrastructure deployed into production, verify an accountable service/system owner, change authorization, secure configuration, access restrictions, monitoring, backup/recovery considerations, and documentation updates as applicable.
9. Confirm custody, location, and operational status for a sample of registered assets. Reconcile sampled purchases to the asset register and selected register entries back to purchase/receipt evidence.
10. Record deviations as evidence-based findings with criterion, evidence ID, impact, owner, target date, and CAPA reference.

## 4. Result criteria

- **Pass:** Business need, budget/authorization, procurement, receipt, asset registration, and deployment/change controls are traceable and comply with the controlled requirements.
- **Fail:** A required authorization or procurement control was bypassed, received equipment materially differs without approved change, asset custody is untraceable, or production deployment bypassed required change/security controls.
- **Inconclusive:** The controlled criteria, approval authority, source population, or evidence is unavailable or ambiguous.
- **Not applicable:** A reasoned explanation is recorded and accepted by the test lead.

Do not treat a missing document alone as proof of misconduct. Record the specific evidence limitation and its control risk.

## 5. Finding and CAPA record

Finding ID; sample ID; requirement; verified condition; evidence reference; risk/impact; root cause; corrective action; interim mitigation; owner; due date; retest procedure; effectiveness result; closure approver.

## 6. Test record and approval

- Test lead / process owner: To be assigned or confirmed
- Population, period, sample size, and selection rationale: To be populated
- Evidence IDs and CAPA references: To be populated
- Prepared by / date; reviewed by / date; approved by / date: To be completed under the organization's approval process

**Approval note:** Draft testing aid only. Reconcile it with the current controlled QP-HRD-18 and obtain appropriate approval before operational use.
