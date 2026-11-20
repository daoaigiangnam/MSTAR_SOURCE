# IT Current State Assessment — UOA / Mstar
**Document ID:** UOA-IT-ASSESS-001  
**Version:** 0.1 — Draft for fieldwork  
**Status:** Draft; findings not yet validated  
**Owner (proposed):** Mstar IT Lead  
**Business sponsor (proposed):** UOA management  
**Related:** UOA-IT-GOV-001

---

## 1. Purpose

This document defines how Mstar and UOA will establish a fact-based view of the current IT environment and validate the issues reported in the Internal Audit feedback. It is a fieldwork template: it must be completed with evidence before findings are treated as verified.

## 2. Assessment rules

- Record the assessment period, systems included, source systems, evidence collection date, and assessor.
- Distinguish **reported concern**, **verified condition**, **potential control gap**, and **not tested**.
- Do not infer that a control is absent solely because it is not displayed in a one-page flowchart.
- Do not collect unnecessary personal data, passwords, tokens, private keys, or raw sensitive exports into this repository.
- Store sensitive evidence in UOA/Mstar's approved restricted evidence repository. Put only evidence IDs and sanitized summaries here.
- Preserve source logs and documents. Do not alter originals or create retrospective approvals.
- Any remediation in production must have an authorized change record, impact assessment, and rollback/recovery plan where applicable.

## 3. Assessment metadata — complete during kick-off

| Field | Value |
|---|---|
| Assessment period | To be confirmed |
| Next Internal Audit date / deadline | To be confirmed |
| UOA sponsor | To be appointed/confirmed |
| Mstar assessment lead | To be confirmed |
| Systems/locations in scope | To be inventoried |
| Evidence repository | To be approved |
| Interviewees and dates | To be scheduled |
| Assessment limitations | To be recorded |

## 4. Scope and evidence plan

| Domain | Assessment questions | Evidence to request | Suggested owner | Status |
|---|---|---|---|---|
| VPN / remote access | What gateways/tenants exist? Which users and groups have access? How is access requested and approved? Is MFA enabled? Are logs retained? | Account/group exports, configuration summary, approval records, MFA status, relevant logs | IT / system owner | Not assessed |
| Privileged access | Who has admin rights on identity, servers, firewalls, cloud, backup, and SaaS? Are admin accounts separated? Are service/shared accounts documented? | Role exports, admin roster, service-account register, approval and review evidence | IT / system owners | Not assessed |
| Joiner/mover/leaver | Are HR notifications timely? Do system permissions match role? When is sign-in disabled? Are devices and data handed over? | HR employee movement records, tickets, identity logs, checklists, asset return evidence | HR/Admin + IT | Not assessed |
| Approval evidence | Can each selected access change be traced from request to authorized approval and implementation? | Ticket/email/workflow reference, approval identity and timestamp, implementation log | HOD + IT | Not assessed |
| Backup and recovery | What datasets are protected? Which cloud tenant/account stores them? Who owns/administers it? Are retention, encryption, monitoring, and restore tests defined? | Data-flow diagram, backup inventory, redacted configuration summary, job/alert/restore reports, approval | UOA data owner + IT | Not assessed |
| Data ownership and cloud | Who is accountable for data and SaaS tenants? Who can export/share/delete data? Are external services approved? | Data owner confirmations, cloud/SaaS inventory, access list, contracts and approval | UOA management + IT | Not assessed |
| Software | Is software authorized/licensed? Are unsupported or risky packages present? Who can install software? | Software inventory, endpoint policy, license evidence, sample request/approval records | IT | Not assessed |
| IT assets and procurement | Do devices match asset records? Are purchase requests, approvals, receipts, serials, assignments, and returns traceable? | Asset register, sample purchase files, invoices, acceptance and handover records | IT Asset Owner + Finance/HR | Not assessed |
| Change management | Are material production changes requested, risk-assessed, approved, tested, logged, and reversible? | Change tickets, approvals, test evidence, implementation logs, rollback plans | IT Operations | Not assessed |
| Incident management | Are incidents classified, escalated, investigated, and closed with root cause and evidence? | Incident register, timelines, RCA, communications and action records | IT / Security | Not assessed |
| Vulnerability and patching | Are assets inventoried, vulnerabilities prioritized, patches tested, and exceptions approved? | Patch reports, vulnerability register, exception approvals | IT / Security | Not assessed |
| Audit collaboration | Are Audit questions assigned, answered accurately, and supported with traceable evidence? | Q&A log, minutes, response evidence and review | IT Lead / Audit liaison | Not assessed |
| Policy/document control | Are policies approved, versioned, communicated, reviewed, and linked to operating forms? | Policy register, versions, approvals, training/communication evidence | Document owners | Not assessed |

## 5. Fieldwork method

1. **Kick-off:** agree scope, authority, assessment dates, evidence security and escalation path.
2. **Document review:** collect current policies, procedures, forms, registers, contracts and previous findings.
3. **Interviews:** interview UOA sponsor, HR/Admin, selected department heads, data/system owners, Mstar IT and other relevant roles.
4. **Population discovery:** document all relevant systems and obtain dated account/asset/back-up inventories.
5. **Walkthrough:** select real transactions and trace request → approval → implementation → verification → register update.
6. **Sampling:** select risk-based samples, including VPN, privileged access, joiners/movers/leavers, purchases, software installs and restore tests.
7. **Exception validation:** give the process owner a factual opportunity to clarify evidence before an issue is finalized.
8. **Risk assessment:** rate likelihood, impact, existing controls and residual risk with the accountable owner.
9. **Reporting:** document verified facts, criteria, cause, consequence/risk, recommendation, owner and target date.
10. **Follow-up:** transfer agreed actions into the CAPA tracker and verify effectiveness after implementation.

## 6. Sample testing worksheets

### 6.1 User access sample

| Field | Required entry |
|---|---|
| Sample ID | To assign |
| System/account type | To record |
| User/account identifier | Use controlled identifier; avoid unnecessary personal data |
| Business need and role | To verify |
| Request reference/date | To verify |
| Approver and authority basis | To verify |
| Approval timestamp vs. implementation timestamp | To compare |
| Permission granted | To record |
| MFA / other controls | To verify |
| Access register updated | Yes / No / N/A |
| Evidence reference | Restricted repository ID |
| Exception and risk | To record |
| Reviewer / date | To complete |

### 6.2 Leaver sample

| Field | Required entry |
|---|---|
| Sample ID and employee reference | Restricted identifier |
| HR notification received | Date/time and evidence |
| Last working time / risk instructions | Authorized source |
| VPN/SSO/system disable time | Per-system log |
| Privileged/token/session revocation | Evidence or N/A with reason |
| Mailbox/data handover and retention decision | Authorized owner decision |
| Device return / asset update | Evidence |
| Offboarding checklist and approver | Evidence |
| Exception / residual risk | To record |

### 6.3 Backup and restore sample

| Field | Required entry |
|---|---|
| Dataset and data owner | Confirmed by UOA |
| Source and destination | Verified diagram |
| Cloud tenant/account owner | Verified |
| Administrators and external access | Verified |
| Last successful backup / failures | Backup logs |
| Retention / encryption / monitoring | Configuration evidence |
| Restore test date and scope | Approved test plan |
| Integrity and recovery result | Business/data owner validation |
| RPO/RTO target and actual result | Targets approved first; actual measured |
| Exceptions and actions | CAPA references |

## 7. Initial source-based issues to validate

| ID | Reported issue / source observation | Required validation | Status |
|---|---|---|---|
| CSA-01 | Audit email reports VPN accounts/permissions without required approvals | Obtain full VPN inventory and reconcile every grant to request, authorized approver and implementation log | Open — reported, not yet validated |
| CSA-02 | Audit email reports that IT could not provide approval evidence | Search approved evidence sources and classify missing evidence without reconstructing it retrospectively | Open — reported, not yet validated |
| CSA-03 | Audit email reports company backup data stored in an Mstar cloud account without HR/Admin awareness | Confirm data scope, owner, account control, administrators, UOA approval, retention and restore capability | Open — reported, not yet validated |
| CSA-04 | Audit email raises concerns about IT responses during Audit | Review available meeting notes, question log and supporting responses fairly; define response protocol | Open — reported, not yet validated |
| CSA-05 | QP-HRD-23 flowchart contains approval, implementation, verification including MFA, Access Register update and periodic review steps | Request forms/registers and test actual operation; flowchart alone is not proof of execution | Open — requires operating-effectiveness test |
| CSA-06 | QP-HRD-24 flowchart shows email disablement 30 days after departure | Clarify whether this is distinct from disabling sign-in; test the approved offboarding rule and actual timestamps | Open — interpretation to be confirmed |

## 8. Rating guidance

Use the organization's approved risk method if one exists. If none is provided, use a provisional method and clearly label it pending management approval.

- **Critical:** credible exposure that may cause material data loss, unauthorized privileged access, or major service disruption requiring immediate escalation.
- **High:** important control failure with plausible significant confidentiality, integrity, availability, contractual, or audit impact.
- **Medium:** meaningful weakness with limited scope or compensating controls.
- **Low:** limited impact, procedural clarity or recordkeeping issue with other controls operating.

Final ratings must be assigned with the accountable UOA risk owner and documented rationale; these descriptions do not pre-assign a rating to an unverified finding.

## 9. Deliverables and completion criteria

The assessment is ready for sign-off when:
- Scope and population of in-scope systems are documented.
- Requested evidence has a status: received, unavailable, not applicable, or pending.
- Tests have a sample ID, result, evidence reference, reviewer, and date.
- Each finding distinguishes verified facts from unverified reports.
- Risk ratings have rationale and an accountable owner.
- Findings have agreed actions, deadlines, and owners in the CAPA register.
- Management has reviewed material risks, limitations, and decisions required.

## 10. Sign-off

| Role | Name | Decision / comments | Date |
|---|---|---|---|
| UOA sponsor | To confirm | Pending | |
| Mstar IT Lead | To confirm | Pending | |
| Relevant data/system owner(s) | To confirm | Pending | |
| Independent reviewer / Internal Audit liaison | To confirm | Pending | |

## Revision history

| Version | Date | Description | Status |
|---|---|---|---|
| 0.1 | 2026-10-09 | Initial fieldwork framework based on the Audit feedback and six supplied procedure flowcharts | Draft |
