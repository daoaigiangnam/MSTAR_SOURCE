# QP-HRD-22 — New Employee IT Provisioning Control Test Plan

**Document status:** Draft 0.1 — for review; not an approved policy or procedure  
**Purpose:** Test whether new employee accounts, access, equipment, and onboarding IT tasks are provisioned from authorized, traceable instructions and aligned with the employee's role.  
**Source dependency:** Obtain the controlled QP-HRD-22 and confirm version, required HR source, approval roles, timing, and evidence requirements before testing.

## 1. Scope and safeguards

Test a risk-based sample of recent joiners across departments and role types, including cases with remote access or privileged access only where they exist. Coordinate with HR and relevant system owners. Do not create, disable, or change production accounts solely for testing. Use restricted evidence references rather than employee-sensitive records in this repository.

## 2. Sample register

| Sample ID | HR/onboarding reference | Start date | Role/unit reference | Approved access specification | Account/equipment evidence | Result |
|---|---|---|---|---|---|---|
| NE-01 | Restricted evidence reference | To be populated | Restricted evidence reference | To be tested | To be tested | Not tested |
| NE-02 | Restricted evidence reference | To be populated | Restricted evidence reference | To be tested | To be tested | Not tested |
| NE-03 | Restricted evidence reference | To be populated | Restricted evidence reference | To be tested | To be tested | Not tested |

Select from an actual HR joiner population and document population size, time period, selection method, and sample rationale. Avoid including unnecessary personal data in Git.

## 3. Test steps

1. Confirm the controlled procedure identifies the authorized HR notification/source, minimum required fields, approval steps, provisioning responsibilities, timing, and retained evidence.
2. For each sample, verify that the onboarding instruction came through the approved channel and includes the employee's role, unit, start date, manager, required systems, and equipment as applicable.
3. Compare the requested access with a role-based baseline or an explicitly approved access specification. Investigate broad/default access and any privileges not justified by job duties.
4. Verify approvals for non-standard, sensitive, remote, administrative, or other controlled access according to the approved access matrix. Do not infer approval from the fact that access exists.
5. Compare account creation dates with the authorized start date and approved onboarding schedule. Identify premature account activation or access granted before authorization.
6. Verify unique user identity, account naming, authentication/MFA requirements, initial credential delivery controls, and privileged-account separation where required by policy.
7. Confirm equipment issued is recorded in the asset register and handover acknowledgement is retained where required.
8. Verify the user was assigned to the correct groups, licenses, email/collaboration services, and business applications; compare actual access with approved scope.
9. Check whether completion was recorded, the manager/user was notified, and exceptions were approved and time-bounded.
10. For a sample of provisioned access, trace backward to the HR/onboarding instruction and approvals; for a sample of onboarding instructions, trace forward to completed account and asset records to test completeness.

## 4. Result criteria

- **Pass:** Each tested account/equipment action is supported by an authorized instruction, access matches the approved role/scope, and completion evidence is traceable.
- **Fail:** An account or permission was provisioned without required authorization, before an allowed activation point, or beyond approved scope; required equipment/custody records are missing.
- **Inconclusive:** Source records, the role baseline, procedure version, or approval rules cannot be confirmed.
- **Not applicable:** Documented and accepted by the test lead.

If a system owner or manager confirms a request after the event, record it as retrospective confirmation, not as evidence that prior approval existed.

## 5. Finding and CAPA record

Finding ID; sample ID; criterion; verified condition; evidence ID; affected system/access class; risk; root cause; corrective action; interim mitigation; accountable owner; due date; retest sample; effectiveness result; closure approver.

## 6. Test record and approval

- Test lead / process owner: To be assigned or confirmed
- Population / period / sample size / selection method: To be completed from actual records
- Evidence register and CAPA references: To be populated
- Prepared by / date; reviewed by / date; approved by / date: To be completed under the organization's approval process

**Approval note:** Draft test aid only. Validate against the current controlled QP-HRD-22 and obtain appropriate approval before operational use.
