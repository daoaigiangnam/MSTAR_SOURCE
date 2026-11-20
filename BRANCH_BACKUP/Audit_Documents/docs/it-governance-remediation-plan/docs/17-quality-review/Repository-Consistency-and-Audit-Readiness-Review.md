# Repository Consistency & Audit Readiness Review

- **Document ID:** UOA-IT-GOV-REV-001
- **Version:** 0.1
- **Status:** Draft review record — not an approval of policies or operating controls
- **Review date:** 2026-10-09
- **Review scope:** File inventory, README structure, selected governance/access/backup documents and template consistency on branch `docs/it-governance-remediation-plan`
- **Reviewer role:** Documentation consistency review
- **Limitation:** This review is not an independent audit, does not validate live systems, and does not replace confirmation against the six original controlled QP-HRD flowcharts and operating evidence.

## 1. Executive summary

The repository contains a substantial draft framework covering access management, backup/recovery, change and incident management, asset management, identity lifecycle, vendors, security monitoring, control testing, governance reporting and reusable evidence templates.

The most important consistency issue identified in this review was that the README's directory map did not match the actual directory layout. The README has been updated in this branch to reflect the current structure. The programme remains a **draft**: proposed role assignments, control frequencies, approval authority, RPO/RTO and target dates require confirmation by UOA/Mstar before operational use.

## 2. Findings and actions

| ID | Observation | Risk / consequence | Required action | Suggested owner | Status |
|---|---|---|---|---|---|
| REV-01 | README directory descriptions did not match the actual folders and document names. | Users may look in incorrect locations or misunderstand the document set. | README structure updated to match the current repository inventory; recheck when files move. | Documentation owner | Updated in this branch |
| REV-02 | The RACI contains proposed/asterisked accountabilities, and some activities show multiple candidate A* roles. | Approval authority could remain ambiguous, especially for VPN/privileged access, cloud/backup, production change and risk acceptance. | UOA management must choose one accountable role per activity and record delegated authority before approval. | UOA sponsor / process owners | Open |
| REV-03 | The master plan and policy documents explicitly state that the initial Audit concerns are reported and require verification. | Treating a reported concern as a proven root cause could lead to inaccurate audit responses or unsuitable remediation. | Preserve this evidence status; reconcile to source tickets, approval records, account exports, logs and interviews. | CAPA owner / Audit liaison | Open |
| REV-04 | The six QP-HRD flowcharts are named in the master plan, but the current repository inventory does not contain a completed controlled gap-analysis/revision package for all six procedures. | Proposed controls may not be reflected in the actual HR/Admin-controlled procedures. | Obtain current controlled copies; document per-flowchart gap, decision, proposed redline, process owner and approval. Do not claim existing procedures have been changed. | HR/Admin + Mstar IT + process owners | Open |
| REV-05 | Backup policy and owner-confirmation templates require actual verification of tenant/account ownership, contractual responsibility, data location, backup access and restore capability. | Ownership and recovery risk cannot be resolved by policy wording alone. | Complete owner confirmation and backup inventory using authoritative provider/contract/configuration evidence; approve recovery targets with business owners. | UOA data/system owner + Mstar IT | Open |
| REV-06 | Backup policy says RPO/RTO must be business-approved; templates correctly allow “pending approval.” | Unapproved recovery objectives could be mistaken for committed service levels. | Keep RPO/RTO marked pending until the authorized business owner approves them per workload. | Business/data owners | Open |
| REV-07 | Several procedures specify proposed review frequencies or rely on technical capabilities “where supported.” | The draft may promise controls that are not configured or feasible in the current environment. | Confirm system capabilities, log retention, MFA, monitoring and operational capacity; record exceptions and compensating controls where needed. | IT control owners + Security role, if appointed | Open |
| REV-08 | Document metadata uses document-specific IDs and versions, but a single authoritative document register is not yet identified in the reviewed subset. | Future changes may create inconsistent version/status/approval tracking. | Maintain one controlled document register with ID, title, owner, approver, version, status, effective date, next review and related forms. | Document control owner | Open |
| REV-09 | Draft templates are ready to capture evidence, but the actual repository/access model for restricted evidence has not been verified in this review. | Sensitive production data or audit evidence could be exposed if placed in the wrong location. | Confirm approved evidence repository, permissions, retention and access review. Store evidence IDs/links in Git; do not commit secrets or raw sensitive exports. | UOA sponsor + IT | Open |
| REV-10 | CAPA effectiveness and audit handover documents require independent validation and authorized closure. | Drafting or implementing a procedure could be mistaken for effective remediation. | Define test populations, sampling, acceptance criteria, independent validator and closure authority for each CAPA. | CAPA owner + independent reviewer | Open |

## 3. Consistency controls to apply to every document

Before approval, check each controlled document for:

- Unique document ID and clear title.
- Version, status, owner, approver, effective date and review date.
- Terms and role names consistent with the approved RACI and authorization matrix.
- Explicit distinction between mandatory requirements and proposed practices.
- Cross-references to current forms, registers and related procedures.
- Evidence requirements that can be demonstrated in actual systems.
- Escalation path, exception handling and record-retention requirements.
- No implied approval, implementation or effectiveness that has not been evidenced.
- Change history updated for each approved revision.

## 4. Decisions required from UOA/Mstar

1. Confirm the executive sponsor, document-control owner, policy approvers and delegated approval authority.
2. Confirm the approved access authorization matrix by system, permission level, VPN and privileged access.
3. Confirm the business/data owner and technical custodian for each important dataset and service.
4. Confirm the cloud tenant/account holder, contract party, backup destination, authorized administrators and data-sharing basis from authoritative records.
5. Approve RPO/RTO and retention requirements per workload; do not infer values.
6. Confirm required MFA/logging/monitoring capabilities and any approved exceptions.
7. Confirm the next Audit date and approve realistic CAPA deadlines.
8. Obtain the current controlled versions of QP-HRD-16/17/18/22/23/24 and approve either revisions or documented no-change decisions.
9. Confirm the approved restricted evidence repository and evidence access model.
10. Assign independent CAPA effectiveness validators and authorized closure roles.

## 5. Recommended sequence

1. **Immediate:** confirm owners, preserve evidence, inventory access/backup and establish the evidence repository.
2. **Before policy approval:** resolve RACI and approval-authority ambiguities; confirm scope, system capabilities and business requirements.
3. **Process alignment:** compare each of the six QP-HRD flowcharts with proposed access, onboarding/offboarding, software and procurement controls; document decisions with process owners.
4. **Implementation:** issue approved versions, communicate changes, train affected roles and retain acknowledgements where required.
5. **Effectiveness:** sample real transactions and test restore capability; record failures and follow-up CAPAs.
6. **Audit handover:** provide a traceable evidence index, status of each finding, open gaps, accountable owners and approved commitments.

## 6. Review conclusion

The repository is a useful **draft governance and evidence toolkit**, but it should not yet be described as an approved control environment or proof that UOA is audit-ready. Readiness must be based on owner-confirmed processes, implemented controls, authentic evidence and successful effectiveness testing.

## 7. Revision history

| Version | Date | Change | Status |
|---|---|---|---|
| 0.1 | 2026-10-09 | Initial repository consistency review | Draft |
