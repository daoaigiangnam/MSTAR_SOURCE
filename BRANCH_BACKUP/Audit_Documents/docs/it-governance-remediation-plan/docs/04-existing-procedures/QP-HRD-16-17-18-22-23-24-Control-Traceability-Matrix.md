# QP-HRD-16/17/18/22/23/24 — Cross-Procedure Control Traceability Matrix

**Document status:** Draft 0.1 — for process-owner and Audit review  
**Purpose:** Establish a single review and testing view across six existing QP-HRD processes. This matrix is a working aid, not a replacement for the controlled procedures or formal approval matrix.

## 1. Mandatory validation before sign-off

For each process, obtain the latest controlled copy and record its document ID, version, effective date, owner, approver, and location. The actual source flowcharts and any related forms must be examined before declaring a gap. The matrix below identifies test questions and evidence to collect; it does not assert that a control is absent.

## 2. Cross-procedure traceability matrix

| Procedure | Control objective | Key control questions | Evidence to inspect | Accountable owner to confirm | Test approach | Current status |
|---|---|---|---|---|---|---|
| QP-HRD-16 Software Installation Request | Only authorized and permitted software is installed | Is there a traceable request? Are required approvals documented before installation? Are license and security requirements addressed? Does installed software match approval? | Controlled procedure; request/ticket; dated approvals; license/procurement evidence where applicable; endpoint inventory or installation record; completion evidence | Process owner to confirm; IT executor to confirm | Sample approved, rejected, urgent and completed requests where available; reconcile request to endpoint state | Not tested; evidence required |
| QP-HRD-17 Employee IT Equipment Purchase | Employee equipment purchases are justified and authorized | Is business need documented? Was approval obtained before commitment? Were procurement, receipt, asset registration and handover completed? | Request; approval and budget evidence; quotations/selection records; purchase order; delivery/acceptance; asset register; handover acknowledgement | Process owner and procurement/finance roles to confirm | Trace purchase to asset and asset back to purchase evidence | Not tested; evidence required |
| QP-HRD-18 Company IT Equipment Purchase | Company-level IT investments are authorized, procured and controlled | Is need and budget approved? Is supplier selection traceable? Do received items match order? Is asset registration and production deployment/change control complete? | Business case; budget and approvals; quotation/selection; contract/PO; receipt; asset register; change record and technical acceptance where applicable | Process owner, procurement/finance, service owner to confirm | Risk-based sample by value, criticality, exception and deployment status | Not tested; evidence required |
| QP-HRD-22 IT Account and Equipment Provisioning for New Employee | New-joiner provisioning is based on authorized HR/manager instructions and role need | Is the onboarding source authorized? Are requested systems and equipment explicit? Does actual access match role and approvals? Was account activation appropriately timed? | HR/onboarding instruction; manager/system-owner approval as required; access specification; account/group/license records; asset issue and handover; completion record | HR process owner, line manager, IT and system owners to confirm | Trace onboarding instruction forward to accounts/equipment; trace sampled accounts back to approved instruction | Not tested; evidence required |
| QP-HRD-23 User Access Request and Authorization | Access changes are approved, least-privileged, and traceable | Is the requester authorized? Are data/system owners identified? Is approval prior to grant? Does provisioned access match approved scope? Are exceptions time-bounded? | Request/ticket; approval evidence; owner confirmation; access matrix; system logs/export; implementation and validation record | Requesting manager, system/data owner, IT to confirm | Compare approved scope to actual permissions for risk-based samples | Not tested; evidence required |
| QP-HRD-24 User Account and Access Termination | Access is revoked promptly and completely at exit or other trigger | Is there an authoritative termination/change notification? Are all accounts, VPN, privileged access, tokens and sessions covered as applicable? Is completion verified? Are retention and deletion decisions separate and approved? | HR termination/change notice; access inventory; disable/revoke logs; VPN/SSO/application evidence; asset return; mailbox/data retention decision; completion checklist | HR process owner, IT, system/data owners to confirm | Trace leaver sample to every in-scope system; verify timestamps and exceptions | Not tested; evidence required |

## 3. End-to-end control handoffs

| Handoff | Required traceability question | Evidence link |
|---|---|---|
| HR/manager instruction → QP-HRD-22 provisioning | Does the source instruction identify the role, start date, manager and required access/equipment? | HR/onboarding reference ID |
| QP-HRD-22 provisioning → QP-HRD-23 authorization | Are non-standard or sensitive permissions separately authorized where required? | Access request and approval IDs |
| QP-HRD-23 authorization → technical implementation | Do actual permissions match approved scope, and is implementation attributable to an authorized executor? | Ticket, approval and implementation evidence IDs |
| QP-HRD-24 termination → all systems | Is every relevant identity, remote-access path and privileged credential addressed, with completion verified? | Offboarding case and per-system evidence IDs |
| QP-HRD-17/18 purchase → asset register | Can the organization trace approved purchases through receipt to an accountable asset record and custodian? | Purchase, receipt and asset IDs |
| QP-HRD-16 installation → software/asset inventory | Can the installed software be reconciled to authorization and inventory records? | Request, endpoint and inventory evidence IDs |

## 4. Common evidence fields

For each tested item, capture:
- Test ID and sample ID
- Controlled procedure ID/version and exact criterion/step
- Population, period, selection method and rationale
- Request/case reference and relevant system or asset reference
- Required approver role and approval timestamp
- Execution timestamp and executor role
- Evidence ID and approved restricted storage location
- Expected result, observed result, and pass/fail/inconclusive decision
- Finding ID, risk, accountable owner, due date, interim control and CAPA ID
- Retest evidence, effectiveness result and closure approver

Do not put passwords, access tokens, private keys, full HR records, or unredacted production exports into this repository.

## 5. Decision rules

1. **Do not presume a control failure from the flowchart alone.** Verify the current procedure, associated forms, actual records and system evidence.
2. **Do not presume a control operated because a procedure says it should.** Test operating evidence.
3. **Missing evidence:** classify as an evidence/control gap or inconclusive result according to the test criterion; avoid unsupported allegations of intent.
4. **Retrospective approval:** record the actual approval time. Do not describe it as prior authorization.
5. **Conflicting documents:** record the conflict, owner, decision needed and approver. Do not silently harmonize requirements.
6. **Exceptions:** require documented rationale, authorized risk acceptance where applicable, compensating control, owner and expiry/review date.
7. **CAPA closure:** requires implementation evidence and a retest/effectiveness check, not only document publication.

## 6. Decisions required from Mstar

- Confirm the controlled version and owner for each QP-HRD procedure.
- Confirm the approval matrix and delegation of authority for user access, VPN/privileged access, software installation, and purchases.
- Confirm authoritative HR notification channels and expected timing for joiner/mover/leaver events.
- Confirm system and data owners and the authoritative inventory of accounts, applications, VPN, privileged access, assets and software.
- Confirm approved evidence repository, access restrictions, retention, and who may disclose evidence to UOA Audit.
- Approve risk-based sampling scope and who may sign off test conclusions.
- Assign owners and due dates for any confirmed findings.

## 7. Review sign-off

| Role | Name | Decision | Date / reference |
|---|---|---|---|
| IT process owner | To be assigned | Pending | Pending |
| HR process owner | To be assigned | Pending | Pending |
| Procurement/Finance owner | To be assigned | Pending | Pending |
| System/Data owners | To be assigned | Pending | Pending |
| Internal Audit / control reviewer | To be assigned | Pending | Pending |
| Authorized approver | To be assigned | Pending | Pending |

**Approval note:** This matrix is a draft review instrument. It does not establish that any specific process is compliant or non-compliant until the controlled source documents and actual evidence have been examined.
