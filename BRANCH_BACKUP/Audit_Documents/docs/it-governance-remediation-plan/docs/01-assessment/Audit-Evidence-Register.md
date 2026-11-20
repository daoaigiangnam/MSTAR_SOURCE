# Audit Evidence Register — UOA / Mstar
**Document ID:** UOA-IT-EVID-001  
**Version:** 0.1 — Draft template  
**Status:** Draft  
**Owner (proposed):** Audit liaison / Mstar IT Lead  
**Storage:** Evidence binaries and sensitive records must remain in an approved restricted repository; this file should contain IDs and sanitized descriptions only.

## 1. Purpose

Provide a traceable index of evidence requested, received, reviewed, and used to support audit responses and CAPA closure.

## 2. Evidence handling rules

1. Assign a unique evidence ID before or at collection.
2. Record source system/document, collection date/time and timezone, collector, scope, and file location.
3. Preserve original exports/logs as read-only where practicable; work on controlled copies.
4. Restrict access according to data sensitivity and business need.
5. Do not commit credentials, tokens, private keys, unnecessary personal information, raw sensitive logs, or customer data to the Git repository.
6. Record evidence that is unavailable, incomplete, or not applicable; do not silently omit it.
7. Any redaction must preserve the original restricted copy and record who redacted the review copy and why.
8. Keep approvals and logs in their original system where possible; link to the controlled record instead of duplicating sensitive content.

## 3. Register template

| Evidence ID | Audit finding / control | Evidence description | Source system / document | Period / scope | Collected by | Collected at (timezone) | Restricted repository reference | Sensitivity / access group | Completeness review | Used for response/CAPA | Reviewer / date | Notes |
|---|---|---|---|---|---|---|---|---|---|---|---|---|
| EV-0001 | CAPA-01 | VPN account inventory export | To confirm | Full in-scope VPN population | To assign | To record | To assign | Restricted | Pending | Yes/No | Pending | Do not store raw export in Git |
| EV-0002 | CAPA-01 / CAPA-02 | Access request and approval samples | Ticket/email/workflow | Risk-based sample | To assign | To record | To assign | Restricted | Pending | Yes/No | Pending | Validate authority and timestamp |
| EV-0003 | CAPA-03 | Backup/cloud architecture and owner confirmation | Backup console/cloud admin/document | All UOA data in backup scope | To assign | To record | To assign | Restricted | Pending | Yes/No | Pending | Do not publish tenant IDs or sensitive configuration unnecessarily |
| EV-0004 | CAPA-03 | Backup job and restore-test result | Backup platform | Approved test period | To assign | To record | To assign | Restricted | Pending | Yes/No | Pending | Store detailed logs in controlled evidence repository |
| EV-0005 | CAPA-06 | Leaver access disablement sample | HR/identity/VPN/system logs | Risk-based sample | To assign | To record | To assign | Restricted | Pending | Yes/No | Pending | Avoid excess personal data |

## 4. Evidence state definitions

- **Requested:** evidence request issued.
- **Received:** source material collected, not necessarily reviewed.
- **Under review:** completeness/authenticity/scope being checked.
- **Accepted:** reviewer confirms it supports the stated point.
- **Partial:** evidence supports only part of the claim or population.
- **Unavailable:** source searched but evidence not found; document search scope and owner response.
- **Rejected:** unreliable, wrong period/scope, altered without traceability, or otherwise insufficient; explain why.
- **Not applicable:** control does not apply; record owner rationale and reviewer approval.

## 5. Evidence quality checklist

For every item, confirm:
- Is the source authoritative for the claim?
- Is the collection period and scope clear?
- Does it include reliable timestamps and a traceable source?
- Is it complete enough for the conclusion being made?
- Has it been protected against unintended modification?
- Is the reviewer independent of the action where practicable?
- Is the conclusion within the evidence's actual limits?
- Is access restricted appropriately?

## 6. Revision history

| Version | Date | Description | Status |
|---|---|---|---|
| 0.1 | 2026-10-09 | Initial evidence register template | Draft |
