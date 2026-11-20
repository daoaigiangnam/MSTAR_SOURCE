# UOA IT Governance Remediation Master Plan
**Document ID:** UOA-IT-GOV-001  
**Version:** 0.1 — Draft for review  
**Status:** Draft; not yet approved  
**Document owner (proposed):** Mstar IT Lead  
**Business owner (proposed):** UOA management / designated sponsor  
**Prepared date:** 2026-10-09  
**Review trigger:** Internal Audit findings, material changes to IT services, or at least annual review after approval

---

## 1. Purpose

This plan establishes a controlled, evidence-led programme to assess and remediate IT governance gaps raised in the Internal Audit feedback provided to Mstar and to prepare UOA for its next audit.

The plan coordinates assessment, corrective and preventive actions (CAPA), policy and procedure development, evidence collection, testing, and management reporting. It is not itself evidence that a control is operating effectively.

## 2. Scope

The initial scope covers:

1. User account and access management, including VPN and privileged access.
2. Backup, recovery, data ownership, and cloud storage account governance.
3. IT audit cooperation, evidence management, and CAPA tracking.
4. The six HR/Admin IT procedures supplied for review:
   - QP-HRD-16 — Procedure for Software Installation Request.
   - QP-HRD-17 — Procedure for Employee's IT Equipment Purchase.
   - QP-HRD-18 — Procedure for Company's IT Equipment Purchase.
   - QP-HRD-22 — IT Account and Equipment Provisioning for New Employee.
   - QP-HRD-23 — Procedure for User Access Request and Authorization.
   - QP-HRD-24 — Procedure for User Account and Access Termination.
5. Related IT controls to be scoped after initial discovery: IT assets, software licensing, change management, incident management, vulnerability and patch management, cloud/vendor management, information security, and business continuity/disaster recovery.

## 3. Source basis and assessment limitations

### 3.1 Information supplied in the Internal Audit feedback

The email states the following concerns:
- VPN accounts and permissions were reportedly created/granted without approval from the employee's department head and HR/Admin.
- The Audit team reported concerns about IT responses during audit meetings.
- HR/Admin reportedly did not know that company backup data was stored in an Mstar cloud account.
- IT reportedly could not provide evidence of approvals by authorized persons for granting IT access.
- UOA requested Mstar to review the supplied procedures, propose amendments or new procedures, and recommend IT policies.

These are recorded as **reported findings requiring verification**. Root causes and detailed facts must be established from source records, logs, interviews, and authorized system exports.

### 3.2 Documents reviewed

The available source set consists of six one-page process flowcharts listed in Section 2. These diagrams show process steps but do not by themselves prove how controls operated in practice. Supporting forms, policies, ticket records, system logs, asset registers, cloud configurations, and approval evidence have not yet been validated as part of this plan.

### 3.3 Assessment language

- **Observed in supplied source:** directly stated in the email or shown in a flowchart.
- **Gap to verify:** not evident in the supplied material; must be checked against supporting documents and actual operation.
- **Proposal:** a recommended control or document requiring agreement and approval.
- No missing approval may be fabricated or backdated. Where historical evidence cannot be found, record the evidence gap accurately.

## 4. Programme objectives

1. Identify the complete population of accounts, VPN access, privileged access, and sensitive systems in scope.
2. Establish whether access was requested, approved by the correct authority, implemented, and periodically reviewed.
3. Confirm who owns and administers backup data and its cloud destination, and document UOA's approval and recovery requirements.
4. Make evidence retrieval repeatable and reliable.
5. Review all six supplied procedures against practical operating needs and related approval/evidence controls.
6. Approve missing policies and procedures through UOA's authorized governance process.
7. Verify that remediation actions were implemented and operate effectively before declaring an audit finding closed.

## 5. Governance and responsibilities

The following is a proposed governance model and must be confirmed by UOA and Mstar.

| Role | Proposed responsibility |
|---|---|
| UOA BOD / management sponsor | Approve programme priorities, risk acceptance, policy authority, and material changes to data ownership or service arrangements. |
| UOA department head / requester | Confirm business need and approve access within delegated authority. |
| UOA data owner / system owner | Decide access to owned data and systems; confirm backup, retention, recovery, and service requirements. |
| HR/Admin | Confirm joiner/mover/leaver information; maintain authorized employee status and required workflow records. |
| Mstar IT Lead / programme owner | Coordinate assessment, policy drafting, CAPA, status reporting, evidence completeness, and escalation. |
| Mstar IT operator / administrator | Perform authorized changes, record implementation evidence, and avoid self-approval. |
| Internal Audit / designated independent reviewer | Assess evidence and independently verify whether corrective actions address findings. |

**RACI key:** R = performs the task; A = accountable/approves; C = consulted; I = informed. A detailed RACI must be approved before operational use.

## 6. Priority model

- **P1 — Immediate risk containment:** access without verified approval, privileged/VPN account inventory, unclear backup ownership or control, evidence preservation.
- **P2 — Control design and implementation:** procedure updates, approval matrices, registers, evidence workflow, and additional core IT policies.
- **P3 — Maturity improvement:** periodic testing, broader operational governance, metrics, and ongoing assurance.

The rating is a planning proposal. UOA's risk owner should confirm severity and any required due dates.

## 7. Workstreams and deliverables

| ID | Workstream / deliverable | Priority | Proposed owner | Target from programme start | Exit evidence |
|---|---|---|---|---|---|
| WS-01 | Establish sponsor, owners, scope, document control, and evidence repository | P1 | UOA sponsor + Mstar IT Lead | Days 0–2 | Confirmed ownership, scope, repository access, workplan |
| WS-02 | Export VPN, privileged, and sensitive-system account populations; preserve logs | P1 | Mstar IT + system owners | Days 1–3 | Dated exports, source systems, extraction scope, integrity/provenance notes |
| WS-03 | Match each sampled/in-scope access grant to request and authorized approval | P1 | Mstar IT + HR/Admin + department heads | Initial results by Day 7 | Access-to-approval reconciliation; exceptions register |
| WS-04 | Assess current backup/cloud ownership, access, data scope, retention, and restoration | P1 | UOA data owner + Mstar IT | Initial assessment by Day 7 | Data-flow inventory, ownership confirmation, access list, risks and approved next actions |
| WS-05 | Establish Audit Q&A, evidence index, and CAPA tracker | P1 | Mstar IT Lead / Audit liaison | Days 0–5 | Controlled registers with owner, due date, status, evidence link |
| WS-06 | Revise QP-HRD-23 and QP-HRD-24 and approve access authorization matrix | P1 | Mstar IT + UOA HR/Admin + system owners | Draft by Day 15; approval target Day 30 | Approved versions, forms, authority matrix, implementation communication |
| WS-07 | Backup & Recovery policy and operating procedure | P1 | UOA data owner + Mstar IT | Draft by Day 30–45 | Approved policy, restore test plan, recovery evidence |
| WS-08 | Gap analysis and revisions for QP-HRD-16/17/18/22 | P2 | Mstar IT + HR/Admin + Finance/Procurement | Days 15–45 | Approved revisions or documented no-change decisions |
| WS-09 | Draft data/cloud, asset, change, incident, patch, vendor, and BCP/DR controls | P2 | Relevant UOA/Mstar owners | Days 30–60+ | Approved document register and implementation plans |
| WS-10 | Independent audit-readiness sampling and CAPA effectiveness verification | P1/P2 | Designated independent reviewer | Days 46–60, adjust to audit date | Test scripts, samples, results, open issues, readiness report |

Timeframes are proposed targets, not dates already approved by UOA. Set actual dates after sponsor appointment and confirmation of the next Audit date.

## 8. Immediate action plan

### First 48 hours
1. Name one programme owner and one UOA decision-maker for access and data.
2. Confirm a controlled repository and appropriate access restrictions.
3. Preserve relevant emails, approval records, tickets, system logs, VPN exports, cloud backup logs, and existing procedures.
4. Do not alter or delete source evidence. Record collection date, source system, collector, export scope, and storage location.
5. Start an Audit question log and CAPA register.

### Days 1–7: access and backup discovery
1. Export VPN account inventory and, where available, creation/change/last-use information.
2. Identify privileged, service, shared, dormant, and emergency accounts across in-scope systems.
3. Match access grants to request, approval, implementation timestamp, assigned role, MFA status, and owner.
4. Record all missing evidence as exceptions; do not create retrospective approvals.
5. Confirm backup datasets, source systems, cloud destination/tenant/account owner, administrators, data owner, retention, encryption, access logs, failure alerts, and last successful restoration test if available.
6. Do not migrate or delete backup data until ownership, business impact, change approval, and rollback/recovery arrangements are agreed.

### Days 8–30: control design
1. Approve a system-by-system access authorization matrix.
2. Define standard and emergency access workflows, with time limits and retrospective review for approved emergencies.
3. Update onboarding, access change, access review, and offboarding procedures.
4. Agree cloud/backup ownership, recovery objectives, data access, and provider-exit requirements.
5. Approve controlled templates and mandatory evidence fields.

### Days 31–60: implementation and validation
1. Implement approved procedures and communicate them to stakeholders.
2. Test a risk-based sample of access grants and removals.
3. Perform a planned backup restore test with a business/data owner witnessing the result.
4. Review sample software, purchase, and asset records.
5. Have an independent reviewer test completed CAPAs and report remaining exceptions.

## 9. Required registers and controlled records

The programme should maintain these records at minimum:

- **IT Risk Register:** risk ID, scenario, cause, impact, likelihood, inherent rating, existing controls, residual rating, owner, treatment, due date, acceptance authority, review date.
- **Audit Finding & CAPA Register:** finding ID, source, factual statement, evidence, root cause, correction, corrective action, preventive action, owner, approver, due date, status, evidence link, independent effectiveness result.
- **Access Register:** person/account ID, account type, system, role/permission, business owner, requester, approver, approval reference, grant/change/revocation timestamps, MFA, review date, status.
- **Backup & Cloud Register:** dataset, business/data owner, source, destination/tenant, account owner, administrators, data location if known, encryption, retention, RPO/RTO (when agreed), job monitoring, restore test, external access, approved basis.
- **Policy & Procedure Register:** ID, title, owner, approver, version, status, effective date, next review, related forms, training/communication record.
- **Audit Evidence Register:** evidence ID, control/finding, description, source, collection timestamp, collector, storage path, sensitivity/access restriction, review status.
- **Decision Log:** decision, options considered, decision authority, date, rationale, dependencies, and communication record.

Use the companion worksheets in the repository where available; avoid placing passwords, private keys, tokens, personal data, or raw sensitive production exports in an unrestricted repository.

## 10. Minimum access-control requirements proposed for approval

1. Access is granted only for a documented business purpose and by an authorized approver before implementation, except for a formally defined emergency process.
2. Approval authority is explicit by system, data owner, permission level, VPN access, and privileged role.
3. Requester, approver, and implementer are separated where practicable. Any unavoidable exception is recorded and independently reviewed.
4. Use least privilege and role-based access where practical; avoid shared interactive accounts unless a documented exception is approved.
5. MFA is required for remote access and privileged access where supported; confirm actual platform capability during assessment.
6. Every grant, change, and revocation has a traceable record and timestamp.
7. Access is reviewed at a defined frequency and when personnel change role or leave.
8. Emergency access is time-bound, justified, logged, and reviewed after use.
9. Unapproved or unexplained access is risk-assessed and escalated; changes are made under authorized change control.
10. A missing historical record is recorded as an evidence/control exception, not reconstructed as though approval had occurred.

## 11. Backup and data ownership requirements proposed for approval

1. Each protected dataset has a named UOA business/data owner.
2. UOA confirms where data may be stored and who may administer, access, restore, export, or delete it.
3. The approved design documents backup scope, schedule, retention, encryption, monitoring, recovery objectives, and restore testing.
4. Access to the backup control plane is restricted, MFA-protected where available, logged, and periodically reviewed.
5. Restore tests verify data integrity and practical recovery; a successful backup job alone is not proof of recoverability.
6. Backup design considers deletion/ransomware resistance and an appropriately isolated or immutable copy where feasible and approved.
7. Provider changes or termination include data export, access revocation, secure deletion where appropriate, and evidence.
8. Migration or redesign requires owner approval, impact assessment, tested restore/rollback plan, and preservation of existing recoverable copies until acceptance.

These are proposed controls; they do not assert that the current environment already supports every capability.

## 12. Audit response and evidence rules

- Answer questions truthfully and within the responder's knowledge.
- If facts are not yet verified, state what is known, what remains under investigation, the owner, and the expected response date.
- Cite evidence by evidence ID or controlled path; avoid unsupported verbal assertions.
- Preserve original records and maintain appropriate access restrictions.
- Do not backdate approvals or rewrite original logs.
- Record each Audit request, assigned owner, due date, response, evidence, reviewer, and follow-up.
- Escalate missed commitments or newly identified high risks to the programme sponsor.

## 13. CAPA lifecycle and closure criteria

A finding must not be closed only because a policy or procedure has been drafted.

1. **Record:** state the reported issue and supporting evidence without overstating unverified facts.
2. **Contain:** reduce immediate exposure, with approval and change safeguards.
3. **Investigate:** determine scope and root cause using records and interviews.
4. **Correct:** fix identified instances.
5. **Prevent:** change the design/process to reduce recurrence.
6. **Implement:** train relevant people and put controls into operation.
7. **Evidence:** attach records demonstrating implementation.
8. **Test effectiveness:** sample actual transactions after implementation.
9. **Approve closure:** an assigned independent reviewer confirms the defined acceptance criteria were met.
10. **Reopen if needed:** failed testing or recurrence reopens the CAPA.

## 14. Status reporting

The programme owner should provide a weekly summary covering:
- Work completed and evidence produced.
- P1 items and residual exposure.
- Overdue actions and reasons.
- Decisions required from UOA or Mstar management.
- CAPAs awaiting independent verification.
- Next week's planned activities.

Recommended metrics:
- Percentage of in-scope accounts reconciled to valid approval.
- Number of access exceptions by risk level and age.
- Percentage of sampled leaver accounts disabled within the approved timeframe.
- Percentage of critical datasets with confirmed owner and approved backup design.
- Restore-test pass/fail and unresolved recovery issues.
- Percentage of CAPAs closed after effectiveness testing, not documentation alone.

Targets must be set after the baseline is established.

## 15. Programme exit criteria

The programme may be reported as audit-ready only when:
1. Scope and system population are documented and approved.
2. P1 access and backup findings have documented disposition, accountable owner, and evidence.
3. Required policies/procedures are approved, version-controlled, communicated, and implemented.
4. Registers are current and samples can be retrieved within an agreed time.
5. Backup recovery has been tested against agreed acceptance criteria.
6. CAPA effectiveness has been independently assessed.
7. Remaining risks are explicitly documented and accepted by the authorized UOA risk owner, or have approved remediation plans.
8. Internal readiness results and unresolved issues are presented to management.

“Audit-ready” does not guarantee that an auditor will raise no additional findings.

## 16. Decisions required from UOA/Mstar management

1. Confirm the sponsor and programme owner.
2. Confirm the authoritative approvers for VPN, privileged access, business systems, and sensitive data.
3. Confirm data owners and decision authority over backup/cloud storage.
4. Confirm the next Audit date and mandatory remediation deadlines.
5. Confirm the repository and approved location for sensitive audit evidence.
6. Approve the proposed priority model and reporting cadence.
7. Approve or revise proposed target timeframes and closure authority.

## 17. Revision history

| Version | Date | Description | Status |
|---|---|---|---|
| 0.1 | 2026-10-09 | Initial draft based on the Audit feedback email and six supplied procedure flowcharts | Draft for review |

---

## Appendix A — Initial source-based issues to verify

| Ref | Reported concern / source observation | Verification required | Initial priority |
|---|---|---|---|
| A-01 | Audit email reports VPN accounts/permissions without required approval | Export full VPN population and match each grant to valid request/approval | P1 |
| A-02 | Audit email reports that IT could not provide approval evidence | Search ticketing/email/workflow records; record unlocated records as evidence exceptions | P1 |
| A-03 | Audit email reports backup data stored in an Mstar cloud account without HR/Admin awareness | Confirm data scope, account owner, admin access, UOA approval, retention, and restore capability | P1 |
| A-04 | Audit email describes unsatisfactory answers/cooperation during Audit | Confirm meeting facts fairly; establish owner, response protocol, Q&A register, and evidence references | P1 |
| A-05 | QP-HRD-23 flowchart includes approval, access implementation, verification including MFA, Access Register update, and periodic review | Test whether the supporting artifacts and actual implementation demonstrate those steps | P1 |
| A-06 | QP-HRD-24 flowchart includes access termination, equipment return, verification and an email-disable step 30 days after departure | Clarify the intended distinction between blocking sign-in and later email disablement; test actual offboarding timing | P1 |

## Appendix B — Initial document backlog

| ID | Document / record | Action |
|---|---|---|
| DOC-01 | IT Governance Remediation Master Plan | This document; review and approve |
| DOC-02 | IT Current State Assessment | Create after interviews, inventories, and evidence review |
| DOC-03 | Audit Finding & CAPA Register | Maintain as a live record |
| DOC-04 | IT Risk Register | Create and rate with accountable risk owners |
| DOC-05 | Audit Evidence Register | Create and restrict access appropriately |
| DOC-06 | User Access Management Policy | Draft and seek approval |
| DOC-07 | User Access Authorization Matrix | Build by system and permission level |
| DOC-08 | Access request/change/revocation forms and procedures | Align to approved matrix |
| DOC-09 | VPN and privileged access procedure | Define authorization, MFA, logging, review, emergency access |
| DOC-10 | Backup & Recovery Policy and procedures | Confirm current architecture before drafting final design |
| DOC-11 | Data classification/ownership and Cloud/SaaS register | Confirm data owners and approved service boundaries |
| DOC-12 | Revisions to QP-HRD-16/17/18/22/23/24 | Gap analysis, approve revisions or document no-change rationale |
| DOC-13 | IT Asset, Software, Change, Incident, Patch, Vendor, Information Security and BCP/DR documents | Prioritize after current-state assessment |
| DOC-14 | Internal Audit Readiness Report | Prepare after independent sample testing |
