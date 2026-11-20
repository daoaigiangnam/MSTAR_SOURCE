# IT Control Testing Plan

- **Document ID:** UOA-IT-AUD-PLAN-001
- **Version:** 0.1 — Draft
- **Status:** For review; not approved
- **Plan owner:** IT governance / designated control owners (to confirm)
- **Independent reviewer:** To be designated by UOA

## 1. Purpose

Define a repeatable plan for testing whether selected IT controls are designed appropriately and operate as intended. This plan is a template; it does not assert that any control has passed testing.

## 2. Testing principles

1. Define the control objective and criteria before selecting samples.
2. Distinguish **design effectiveness** (whether the control could address the risk) from **operating effectiveness** (whether it operated consistently during the period).
3. Use a defined population, period, selection method and sample size. Record limitations.
4. Prefer evidence from authoritative systems and original records. Preserve source and timestamps.
5. Record exceptions honestly; do not infer approval from successful implementation.
6. The control operator should not be the sole independent reviewer of their own control where segregation is practicable.
7. Protect personal, customer and security-sensitive evidence in the approved restricted repository.
8. Do not backdate or fabricate evidence. Remediation after a gap is found must be recorded with its actual date.
9. A new policy or procedure is evidence of design intent, not proof of operating effectiveness.
10. Internal Audit retains its independent role and should not become the operational owner of controls it tests.

## 3. Control test lifecycle

1. **Scope:** Select control, system, period, risk and owner.
2. **Define criterion:** State expected control behavior and approved policy/procedure reference.
3. **Identify population:** Obtain a complete or appropriately bounded population from an authoritative source.
4. **Validate population:** Reconcile totals, period, filters and exclusions; document gaps.
5. **Select sample:** Use risk-based or approved sampling; document rationale and selection method.
6. **Collect evidence:** Obtain request, approval, implementation, logs, review or test records as relevant.
7. **Execute test:** Compare each sample against each criterion; record pass, fail, partial or unable to test.
8. **Evaluate exception:** Determine whether evidence is missing, control was not followed, or the control design is inadequate.
9. **Report and remediate:** Link exception to finding/risk/CAPA, owner and due date.
10. **Retest:** Confirm corrective action and test operating effectiveness over a suitable subsequent period.
11. **Conclude:** Reviewer records conclusion, limitations, residual risk and date.

## 4. Initial control testing matrix

| Control ID | Control objective | Suggested test | Evidence examples | Owner / status |
|---|---|---|---|---|
| CT-01 | Access granted only after authorization | Sample account grants and compare request, manager/system owner approval and IT completion | Ticket/form, approval timestamps, directory/cloud audit logs | To confirm |
| CT-02 | VPN/privileged access is justified and restricted | Reconcile active account population to approved requests; inspect MFA and review evidence where supported | VPN export, approval, configuration evidence, review record | To confirm |
| CT-03 | Leaver access is revoked | Sample leavers and compare effective time to disable/revoke timestamps across in-scope systems | HR/Admin notice, IAM logs, checklist | To confirm |
| CT-04 | Cloud backup location has accountable ownership | Inspect inventory, account ownership, data owner decision and access/contract controls | Tenant/account details, written decision, sharing/admin records | To confirm |
| CT-05 | Backup jobs operate as approved | Compare scheduled jobs with recent job results and failure follow-up | Job reports, configuration, incident tickets | To confirm |
| CT-06 | Backup can be restored | Inspect restore test, integrity checks, business validation and RPO/RTO comparison if approved | Test record, logs, owner sign-off | To confirm |
| CT-07 | Production changes are authorized | Sample changes and compare risk, approval, test, implementation and post-check evidence | Change records, approval trail, logs | To confirm |
| CT-08 | Assets are traceable | Sample assets from physical/endpoint source to register and from register to physical/source evidence | Asset register, purchase/handover records | To confirm |
| CT-09 | Vulnerabilities are managed | Sample findings from discovery through remediation and verification | Scanner/advisory, ticket, change, rescan | To confirm |
| CT-10 | Incidents are recorded and reviewed | Sample incidents and verify timeline, owner, evidence, recovery and CAPA where required | Incident ticket, logs, post-incident review | To confirm |
| CT-11 | Vendor access is approved and removed | Sample active and ended vendor engagements | Contract, sponsor approval, access logs, revocation evidence | To confirm |
| CT-12 | CAPA closure is evidence-based | Sample closed actions and inspect implementation plus effectiveness evidence | CAPA register, test result, reviewer decision | To confirm |

## 5. Test worksheet template

| Field | Entry |
|---|---|
| Test ID / control ID | |
| Control objective and risk | |
| Policy/procedure reference | |
| Control owner / tester / reviewer | |
| Period and scope | |
| Population source and count | |
| Completeness/reconciliation performed | |
| Sampling method and rationale | |
| Sample identifiers (non-sensitive) | |
| Criteria | |
| Evidence IDs | |
| Results per sample | |
| Exceptions / root cause hypothesis | |
| Conclusion: effective / gap / unable to conclude | |
| Limitations and residual risk | |
| CAPA / owner / due date | |
| Retest period and result | |
| Reviewer / date | |

## 6. Initial priorities

Based on the reported audit concerns, consider prioritizing tests of access authorization, VPN/privileged access, leaver revocation, cloud backup ownership and backup recoverability. These are suggested priorities, not verified findings. Final priority must be confirmed against evidence and UOA's approved risk criteria.

## 7. Approval

| Role | Name | Decision | Date |
|---|---|---|---|
| UOA management / sponsor | To confirm | Pending | — |
| Control owners | To confirm | Pending | — |
| Independent reviewer | To confirm | Pending | — |

## 8. Change history

| Version | Date | Change |
|---|---|---|
| 0.1 | 2026-10-09 | Initial draft |
