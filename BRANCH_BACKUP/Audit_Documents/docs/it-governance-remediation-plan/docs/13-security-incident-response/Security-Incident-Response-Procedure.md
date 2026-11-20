# Security Incident Response Procedure

- **Document ID:** UOA-IT-SEC-IR-PRO-001
- **Version:** 0.1 — Draft
- **Status:** For review; not approved
- **Process owner:** Designated security incident lead (to confirm)

## 1. Purpose

Provide a controlled response to suspected or confirmed cybersecurity incidents, preserving evidence while containing harm and restoring trusted operations.

## 2. Scope

Potential compromise of identities, endpoints, servers, networks, applications, cloud accounts, customer/company data and backup/recovery infrastructure. Coordinate with the general IT incident process and any contractual/customer notification plan.

## 3. Response principles

- Prioritize human safety, business continuity and containment proportionate to risk.
- Preserve logs, timestamps and relevant evidence.
- Separate confirmed facts from hypotheses.
- Use authorized incident leadership and communications.
- Avoid destructive remediation that could destroy evidence unless required to prevent greater harm and approved by the incident lead.
- Do not notify external parties or make legal conclusions without the authorized decision process.
- Do not place secrets, raw customer data or sensitive incident evidence in Git.

## 4. Response lifecycle

### Phase 1 — Report and triage
Create an incident ID; record reporter, detection time/timezone, affected assets/accounts, indicators, symptoms, suspected data involved and immediate business impact. Classify provisional severity using UOA-approved criteria.

### Phase 2 — Escalate and coordinate
Notify the designated incident lead, IT owner and management contacts according to the approved escalation tree. Involve Legal/Compliance, HR, customer contacts, insurer or law enforcement only according to approved responsibilities and applicable obligations.

### Phase 3 — Contain
Select proportionate containment actions, such as disabling a compromised account, revoking sessions/tokens, isolating an endpoint, blocking an indicator or restricting external sharing. Assess service and evidence impacts before acting. Record approver, action and timestamp. Emergency containment must be documented as soon as practicable.

### Phase 4 — Preserve and investigate
Preserve relevant authentication, VPN, endpoint, firewall, cloud, application and backup logs where available. Record evidence source, collector, time, access restrictions and integrity measures where feasible. Build a timeline and distinguish verified facts, indicators and open questions.

### Phase 5 — Eradicate and recover
Remove the cause only after an authorized plan is agreed. Patch or rebuild affected assets where needed; rotate/revoke compromised credentials and keys using approved secure channels; restore from a verified recovery point; validate security controls and monitor for recurrence.

### Phase 6 — Communicate and close
Provide status updates through approved channels. Determine notification requirements through the authorized business/legal process. Record scope, impact, actions, recovery validation, residual risk, evidence references and closure approval.

### Phase 7 — Post-incident review
For major incidents, review detection, containment, decision-making, recovery, communication and control gaps. Create CAPA actions with owners and dates; test their effectiveness before claiming closure.

## 5. Priority scenario playbooks

### Suspected privileged-account compromise
- Preserve relevant identity/MFA/VPN logs.
- Use authorized incident leadership to disable/restrict access and revoke sessions.
- Check recent privilege grants, account recovery changes and administrative activity.
- Rotate credentials/secrets through secure approved processes.
- Validate all privileged accounts and monitor for recurrence.

### Ransomware or bulk file changes
- Escalate immediately and isolate affected systems where safe.
- Protect backup consoles and recovery copies from the suspected compromise.
- Preserve endpoint, identity, network and file activity evidence.
- Do not reconnect or restore affected systems until a recovery plan is approved.
- Restore only from a verified clean recovery point and validate data/service integrity.

### Suspected cloud-data exposure
- Identify tenant/account, dataset, sharing link or principal, exposure period and permissions.
- Restrict exposure through an authorized action and preserve audit logs.
- Identify affected data owners and potential recipients without unnecessary distribution of sensitive content.
- Escalate notification and contractual assessment to designated management/Legal/Compliance roles.
- Verify access removal and document residual uncertainty.

### Backup compromise or failed recovery
- Determine last known good copy and whether credentials, retention or immutability settings changed.
- Restrict compromised backup administration and preserve job/configuration logs.
- Avoid deleting or overwriting recovery copies.
- Coordinate with business owners on recovery priorities and use the approved restore procedure.

## 6. Incident record

Incident ID; detection/report time; severity; incident lead; affected assets/accounts; confirmed facts and unknowns; indicators; containment decisions; evidence references; communications; recovery point; verification results; external-notification decision owner; CAPA IDs; closure approval/date.

## 7. Required readiness items

Before approval, UOA must designate incident lead and deputies, on-call/escalation contacts, severity thresholds, approved communication channels, decision authority for containment, evidence repository, vendor contacts and legal/customer notification decision process. Test the plan with a tabletop exercise.

## 8. Change history

| Version | Date | Change |
|---|---|---|
| 0.1 | 2026-10-09 | Initial draft |
