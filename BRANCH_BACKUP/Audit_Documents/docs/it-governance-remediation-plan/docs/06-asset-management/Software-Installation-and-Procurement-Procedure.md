# Software Installation and Procurement Procedure

- **Document ID:** UOA-IT-SW-PRO-001
- **Version:** 0.1 — Draft
- **Status:** For review; not approved
- **Process owner:** IT / Procurement (to confirm)

## 1. Purpose

Control software requests, licensing, security review, purchase, installation, renewal and removal. This draft is intended to align with the supplied process reference **QP-HRD-16 Software Installation Request**, **QP-HRD-17 Employee IT Equipment Purchase** and **QP-HRD-18 Company IT Equipment Purchase**. The source flowcharts must be reviewed alongside this draft before the final process is approved; this document does not claim to reproduce every step or role in those flowcharts.

## 2. General rules

1. Users must not install or purchase business software outside the approved process, except under an approved emergency procedure.
2. Request and approval must be traceable to the requester, manager/budget owner and IT review.
3. IT checks compatibility, supportability, security, data access, vendor trust, licensing and operational support before installation.
4. Procurement confirms budget, quote, purchase authority and supplier records under the applicable purchasing policy.
5. Use authorized distribution sources and approved license/subscription arrangements.
6. Do not store license keys, activation tokens or credentials in Git or ordinary tickets.
7. Record who requested, approved, purchased, installed and received the software/license.
8. Review software that processes company/customer data or requires elevated privileges before approval.
9. Record installation, version, device/user, license assignment and renewal/end date.
10. Removal or reassignment of software must follow license terms and preserve required business data.

## 3. Software installation request workflow

### Step 1 — Request
Requester submits an approved ticket/form including software name/version, business purpose, user/device, data handled, required permissions, duration and whether a purchase is needed.

### Step 2 — Manager/business approval
The responsible manager confirms business need and funding/charge code where applicable. Approval must be explicit and traceable.

### Step 3 — IT assessment
IT assesses:
- Source authenticity and publisher reputation
- Supported version and compatibility
- Security/privacy implications and requested privileges
- Data access, integrations and network requirements
- License type, number of seats, renewal and uninstall terms
- Existing approved alternatives and support obligations

### Step 4 — Procurement approval (if paid)
Procurement/budget owner follows the applicable purchasing procedure, validates quote/vendor and records order/invoice evidence.

### Step 5 — Installation
Authorized IT personnel install from an approved source using least privilege. Record device/user, software/version, date, license assignment and ticket reference.

### Step 6 — Verification and closure
Confirm application works, license is valid, endpoint security remains active and required documentation is updated. Notify requester and close with evidence reference.

## 4. Equipment purchase interfaces

For employee and company IT equipment purchase requests, follow the currently approved QP-HRD-17 and QP-HRD-18 processes. Before approving or fulfilling a request, ensure the record identifies business need, specification, budget authority, procurement evidence, receiving party and asset-registration owner. If a source flowchart specifies different roles or sequence, reconcile this draft through formal process review rather than silently overriding the approved source.

## 5. Exceptions and emergency installation

An exception must document urgency, scope, risk, approving authority, compensating controls and expiry. Emergency installation should use the minimum necessary scope and be reviewed retrospectively. An urgent request does not authorize bypassing license terms or security requirements.

## 6. Minimum request record

| Field | Entry |
|---|---|
| Request/ticket ID | |
| Requester / department | |
| Manager approval and timestamp | |
| Software / version / publisher | |
| Business purpose | |
| User / device / environment | |
| Data and permissions involved | |
| IT security/compatibility review | |
| License / seats / renewal | |
| Procurement reference, if applicable | |
| Installation operator / date | |
| Verification result | |
| Asset/software register updated | |
| Exception / risk reference | |
| Evidence / closure reference | |

## 7. Review

Periodically review installed software and licenses against the approved register. Investigate unapproved software, unsupported versions, duplicate subscriptions, unused seats and approaching renewals. Cadence is to be approved based on risk and available tooling.

## 8. Approval record

| Role | Name | Decision | Date |
|---|---|---|---|
| IT owner | To confirm | Pending | — |
| Business/budget owner | To confirm | Pending | — |
| Procurement, where applicable | To confirm | Pending | — |

## 9. Change history

| Version | Date | Change |
|---|---|---|
| 0.1 | 2026-10-09 | Initial draft based on the identified process references; reconcile with source flowcharts before approval |
