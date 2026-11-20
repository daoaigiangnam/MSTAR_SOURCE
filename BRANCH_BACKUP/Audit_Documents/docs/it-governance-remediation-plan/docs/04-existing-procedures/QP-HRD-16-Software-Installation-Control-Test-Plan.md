# QP-HRD-16 — Software Installation Control Test Plan

**Document status:** Draft 0.1 — for review; not an approved policy or procedure  
**Purpose:** Test whether software installation requests are authorized, traceable, licensed, and performed within approved IT controls.  
**Source dependency:** Obtain the current controlled copy of QP-HRD-16 and confirm its version, effective date, process owner, and approval before testing. Do not assume this test plan replaces the source procedure.

## 1. Scope and safeguards

- Include user-requested software, standard software, paid/licensed software, and exceptions where applicable.
- Use a risk-based sample covering approved, rejected, incomplete, urgent/exception, and recently completed requests where records exist.
- Do not install or uninstall software merely to demonstrate a control. Inspect records first; perform technical verification only in an approved test or maintenance window.
- Do not store passwords, license keys, software binaries, or sensitive endpoint exports in this repository. Reference evidence IDs in the approved evidence register.

## 2. Sample register

| Sample ID | Request/ticket reference | Software and endpoint class | Requester / department | Required approval(s) | License/security review | Installation record | Result |
|---|---|---|---|---|---|---|---|
| SI-01 | To be populated | To be populated | Restricted evidence reference | To be verified against controlled QP-HRD-16 | To be tested | To be tested | Not tested |
| SI-02 | To be populated | To be populated | Restricted evidence reference | To be verified against controlled QP-HRD-16 | To be tested | To be tested | Not tested |
| SI-03 | To be populated | To be populated | Restricted evidence reference | To be verified against controlled QP-HRD-16 | To be tested | To be tested | Not tested |

Select samples from the actual population and record the population size, period, selection method, and rationale. Do not invent sample records.

## 3. Test steps

1. **Procedure currency:** Verify the controlled QP-HRD-16 version and compare its stated roles, approval sequence, exception handling, and evidence requirements with actual practice.
2. **Request traceability:** For each sample, verify the requestor, business purpose, software name/version, target device or user, date, and unique request/ticket reference.
3. **Authorization:** Inspect dated approval evidence from the role(s) required by the controlled procedure. An email assertion or verbal recollection alone is not sufficient where formal approval evidence is required.
4. **License and procurement:** For paid, subscription, or restricted software, verify entitlement/procurement approval and that the installation does not exceed known license rights.
5. **Security review:** Determine whether the software is permitted under the organization's security baseline; verify documented review or exception approval where required. Record unknowns rather than assuming a review occurred.
6. **Execution:** Compare the approved request with the installed software, endpoint, version, and installation date. Verify who performed the installation and whether administrator privileges were controlled.
7. **Post-install evidence:** Check completion notice, inventory/asset record update, and any required validation or user acceptance.
8. **Exceptions and rejected requests:** Confirm that rejected, emergency, or out-of-process requests were not installed without an authorized exception and subsequent review.
9. **Population completeness:** Reconcile the sampled tickets with available endpoint/software inventory or installation logs, noting coverage gaps and retention limits.
10. **Record conclusion:** Link each observation to an evidence ID, criterion, factual condition, risk, responsible owner, due date, and CAPA reference.

## 4. Result criteria

- **Pass:** Required approvals and supporting records are present, consistent, timely, and traceable; the installation matches the authorized scope.
- **Fail:** Evidence shows installation without required approval, unauthorized software, an invalid license, a material mismatch, or a required control was not performed.
- **Inconclusive:** Records are unavailable, the population cannot be established, or the controlled procedure/approval requirement is unclear. Missing evidence must not automatically be described as proof that an event did not occur; record it as an evidence/control gap.
- **Not applicable:** Only with a documented rationale approved by the test lead.

## 5. Findings and remediation

For each failure or evidence gap, record: finding ID; sample ID; exact criterion; evidence reference; verified fact; risk/impact; root-cause analysis; corrective action; accountable owner; target date; interim control; retest method; effectiveness check; closure approver.

Do not close a finding solely because a procedure was written. Verify implementation through operating evidence and a follow-up sample.

## 6. Test record and approval

- Test lead: To be assigned
- Process owner: To be confirmed
- Test period / population / sample size: To be populated from actual records
- Evidence register references: To be populated
- Findings / CAPA IDs: To be populated
- Prepared by / date: To be completed
- Reviewed by / date: To be completed
- Approved by / date: To be completed under the organization's approval process

**Approval note:** This document is a draft testing aid. Confirm it against the current controlled QP-HRD-16 and obtain appropriate approval before operational use.
