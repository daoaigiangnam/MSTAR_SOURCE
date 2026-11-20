# IT Risk Register — UOA / Mstar
**Document ID:** UOA-IT-RISK-001  
**Version:** 0.1 — Draft  
**Status:** Initial risk scenarios for validation; not a final risk assessment  
**Risk owner:** Must be assigned by UOA for each risk

## 1. Use and scoring

This is a starting register derived from the Audit feedback and the scope of the six supplied procedures. It does not claim that each scenario is confirmed.

Use the organization's approved risk matrix if one exists. If not, management must approve likelihood/impact scales before this register is used for formal acceptance.

### Provisional likelihood scale
1. Rare — exceptional circumstances.
2. Unlikely — not expected in normal circumstances.
3. Possible — could occur.
4. Likely — expected to occur in relevant conditions.
5. Almost certain — repeated or highly probable.

### Provisional impact scale
1. Insignificant — minimal operational/compliance effect.
2. Minor — limited impact with straightforward recovery.
3. Moderate — noticeable disruption, limited data exposure or material rework.
4. Major — significant data, service, contractual, financial or audit impact.
5. Severe — major data loss/exposure, critical service disruption or significant legal/regulatory consequence.

**Provisional score = likelihood × impact.** These scales and thresholds require management approval before formal use. Do not assign likelihood/impact based solely on the Audit email.

## 2. Initial risk scenarios

| Risk ID | Scenario (not a verified finding) | Potential consequence | Initial priority for assessment | Existing controls / evidence | Proposed treatment | Proposed risk owner | Status |
|---|---|---|---|---|---|---|---|
| R-01 | VPN or sensitive access may exist without verified authorized approval | Unauthorized access, data exposure, inability to demonstrate control | P1 | QP-HRD-23 flowchart includes approval/access verification steps; actual operation unverified | Inventory and reconcile access; formalize approval matrix; document exceptions and remediate under authorized change control | UOA system owner / management delegate | Open — assess |
| R-02 | Historical access grants may lack retrievable approval evidence | Audit finding, unauthorized access not detectable, weak accountability | P1 | Procedure flowchart and reported email; records not yet inspected | Search approved evidence sources; record evidence gaps; implement evidence register and required ticket fields | UOA process owner + Mstar IT Lead | Open — assess |
| R-03 | Backup data stored in a cloud account may not have clearly acknowledged ownership or approved administration | Loss of data control, recovery failure, unauthorized access, service-exit dependency | P1 | Email reports HR/Admin awareness issue; architecture not yet verified | Map data flows and account ownership; obtain data-owner decision; verify access, retention, encryption, recovery, provider exit | UOA data owner | Open — assess |
| R-04 | Offboarding timing and email-retention process may be misunderstood or insufficiently separated | Former worker retains access, or business data is lost prematurely | P1 | QP-HRD-24 flowchart has 30-day email disable step; sign-in behavior requires clarification | Separate sign-in block, mailbox retention/delegation, and data handover; test leaver samples | UOA HR + system owners | Open — assess |
| R-05 | Audit responses may be delayed or unsupported by evidence | Reduced auditor confidence, extended audit, unresolved findings | P1 | Concern reported in email; detailed context not yet assessed | Audit liaison, Q&A log, evidence-linked answers, technical reviewer and escalation | Mstar IT Lead / management sponsor | Open — assess |
| R-06 | Software could be installed without adequate licensing or security validation | Legal/licensing exposure, malware, unsupported applications | P2 | QP-HRD-16 flowchart has request and approval steps; supporting controls unverified | Inventory software; verify licensing and sources; approved software baseline and exception workflow | IT / software asset owner | Open — assess |
| R-07 | Asset and purchase records may not be fully reconciled to deployed equipment | Lost assets, inaccurate financial inventory, weak offboarding | P2 | QP-HRD-17/18/22 flowcharts include purchasing, delivery and/or recordkeeping steps | Reconcile physical/sample assets to asset register, invoice, serial, custodian and return records | IT Asset Owner + Finance | Open — assess |
| R-08 | Material production changes may not have consistent authorization, testing or rollback evidence | Service outage, data loss, difficult incident recovery | P2 | Not evidenced by the six supplied flowcharts; other procedures may exist | Inventory change process and test sample records; establish change control if gap is confirmed | IT Operations / service owner | Open — assess |
| R-09 | Backup jobs may succeed without a proven ability to restore within business needs | Extended downtime or data loss during an incident | P1 | Backup evidence and restore tests not provided | Review job failures, retention and isolation; agree recovery targets; conduct witnessed restore test | UOA data owner / service owner | Open — assess |
| R-10 | Cloud/SaaS tenant administrators and data-sharing permissions may be unclear | Data leakage, loss of ownership, difficulty exiting provider | P2 | No complete cloud inventory provided | Build cloud/SaaS register; identify owner/admins/data; review external access and exit obligations | UOA management / service owner | Open — assess |

## 3. Required risk fields after fieldwork

For every risk, add:
- Verified scenario and evidence references.
- Business services/data affected and impact type.
- Likelihood and impact scores with rationale.
- Existing controls tested and test outcome.
- Inherent risk score.
- Additional control design and implementation status.
- Residual likelihood/impact and rationale.
- Treatment decision: mitigate, avoid, transfer/share, or accept.
- Named risk owner, acceptance authority, due date and review date.

## 4. Escalation

Escalate immediately to the designated sponsor where evidence indicates active unauthorized privileged access, imminent data loss, backup exposure, material service disruption, or inability to recover critical data. Preserve evidence and follow authorized incident/change processes; do not make unapproved destructive changes.

## Revision history

| Version | Date | Description | Status |
|---|---|---|---|
| 0.1 | 2026-10-09 | Initial risk scenarios for assessment from Audit feedback and supplied procedure flowcharts | Draft |
