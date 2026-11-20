# Audit Finding & CAPA Register — UOA / Mstar
**Document ID:** UOA-IT-CAPA-001  
**Version:** 0.1 — Working register template  
**Status:** Draft / live register once appointed owner validates entries  
**Owner (proposed):** Mstar IT Lead  
**Approval authority:** To be confirmed by UOA management

> This register deliberately records email allegations as reported concerns, not as verified facts. Populate verified evidence references only after controlled evidence collection. Do not put raw secrets or sensitive exports in Git.

## 1. Status definitions

- **Reported — validation pending:** described by Audit/email but not yet independently validated.
- **Under investigation:** evidence collection and scope analysis are in progress.
- **Confirmed:** supporting evidence establishes the condition.
- **Action approved:** management accepts action, owner and due date.
- **Implemented — effectiveness pending:** action is implemented but not yet tested.
- **Closed — effectiveness verified:** evidence and independent effectiveness check meet the approved closure criteria.
- **Risk accepted:** only where the authorized risk owner formally accepts the documented residual risk; this does not mean the control gap was corrected.

## 2. Register

| Finding ID | Reported concern / control objective | Validation and evidence required | Root cause | Immediate containment | Corrective / preventive action | Proposed priority | Owner | Approver / risk owner | Target date | Status | Evidence ref | Effectiveness test / closure criteria |
|---|---|---|---|---|---|---|---|---|---|---|---|---|
| CAPA-01 | Audit email reports VPN accounts/permissions without department-head and HR/Admin approval | Full VPN/account population; request and approval records; implementation logs; group/role export; timestamp comparison | To determine; do not assume | Preserve logs and inventory; identify unsupported access; risk-assess and obtain authorized decisions before changing access | Enforce prior approval, approved authorization matrix, traceable ticket, least privilege, MFA where supported, periodic review | P1 proposed | Mstar IT Lead + system owner | UOA delegated approver | To confirm | Reported — validation pending | Restricted evidence repository | Reconcile 100% of in-scope VPN accounts to approved basis; sample implementation logs; independently verify exceptions are resolved or formally accepted |
| CAPA-02 | Audit email reports IT could not provide evidence of authorized access approvals | Search ticket, email, workflow and audit records; record sources searched and result | To determine | Preserve original records; restrict evidence repository | Standardize access request/approval form and Evidence Register; train owners; prohibit retrospective approval fabrication | P1 proposed | Mstar IT Lead + HR/Admin + HODs | UOA sponsor | To confirm | Reported — validation pending | Restricted evidence repository | Risk-based sample of new/changed access has request, valid approval before grant, implementation log and register update |
| CAPA-03 | Audit email reports HR/Admin did not know company backup data was in an Mstar cloud account | Confirm source/destination, account/tenant owner, administrators, data owner, scope, approval, retention, logs, restore status | To determine | Preserve configuration and logs; do not delete/migrate data without authorized plan; review high-risk access | Obtain UOA decision on data ownership and approved destination; document architecture, access controls, retention and restore testing | P1 proposed | UOA data owner + Mstar IT | UOA sponsor / data owner | To confirm | Reported — validation pending | Restricted evidence repository | Signed ownership/hosting decision; access list reviewed; restore test passes agreed criteria; unresolved risks have owners and approvals |
| CAPA-04 | Audit email raises concern about evasive/inaccurate IT responses during Audit | Review question list, minutes, available correspondence and factual clarifications | To determine fairly | Appoint Audit liaison; avoid unsupported replies | Maintain Q&A log, evidence-linked answers, owner/reviewer, due dates and escalation; coach relevant staff | P1 proposed | Mstar IT Lead / Audit liaison | Mstar management + UOA sponsor | To confirm | Reported — validation pending | Audit evidence repository | Sample Audit questions have accurate, reviewed, traceable answers; unanswered items have owner and response date |
| CAPA-05 | QP-HRD-23 flowchart includes approval, verification, Access Register and periodic review, but actual operation is not yet evidenced | Review the procedure version, forms, matrix, tickets, access register and review records | To determine | Identify urgent unapproved high-risk access under CAPA-01 | Revise authorization matrix/workflow and implement; define approvers by system and permission level | P1 proposed | Mstar IT Lead + UOA HR/Admin + system owners | UOA delegated approvers | To confirm | Restricted evidence repository | Approved document plus post-effective-date samples demonstrate end-to-end control |
| CAPA-06 | QP-HRD-24 flowchart includes an email disable step 30 days after departure; relationship to sign-in disablement needs clarification | Clarify policy intent and test actual identity/VPN/system disable timestamps and retention/delegation decisions | To determine | Identify current ex-employee access based on risk; disable only under authorized rules and preserve required data | Revise offboarding procedure to distinguish sign-in blocking from mailbox/data retention; define urgent/high-risk exit path and evidence | P1 proposed | HR/Admin + Mstar IT + system owners | UOA HR/management | To confirm | Restricted evidence repository | Sampled leavers have access revoked at approved time; mailbox/data handling is separately authorized and evidenced |

## 3. Mandatory CAPA record fields

For every additional finding, record:
1. Finding ID and source.
2. Control objective and factual condition.
3. Evidence reviewed and evidence still missing.
4. Scope and population affected.
5. Root cause, supported by analysis rather than assumption.
6. Immediate containment and any associated change approval.
7. Corrective action and preventive action.
8. Action owner, approver, target date, dependencies and status.
9. Residual risk and authorized risk owner.
10. Evidence links and effectiveness test.
11. Independent verifier and closure decision/date.

## 4. Root-cause analysis guide

Use a documented method such as “5 Whys” or cause-and-effect analysis. Consider, without pre-judging:
- Policy/procedure ambiguity or absence.
- Unclear approval authority.
- Workflow/tool limitation.
- Knowledge/training gap.
- Inadequate segregation of duties.
- Monitoring or periodic review failure.
- Resource or workload constraints.
- Evidence retention or retrieval weakness.
- Management oversight and escalation.

Do not treat “human error” as the complete root cause without checking why the process allowed the error and why monitoring did not detect it.

## 5. Effectiveness verification

A CAPA is not complete when a document is merely drafted. The independent verifier should confirm:
- The approved control is communicated and implemented.
- Transactions performed after the effective date comply with the new control.
- Evidence is retrievable and has reliable timestamps.
- Exceptions follow an approved process.
- The action addresses the cause and reduces recurrence risk.
- Any residual risk is formally accepted by the correct authority.

## Revision history

| Version | Date | Description | Status |
|---|---|---|---|
| 0.1 | 2026-10-09 | Initial CAPA working register with reported issues from Audit feedback | Draft |
