# IT Incident Management Policy and Procedure

- **Document ID:** UOA-IT-INC-POL-001
- **Version:** 0.1 — Draft
- **Status:** For review; not approved
- **Policy owner:** IT owner (to confirm)
- **Approver:** UOA management / delegated authority (to confirm)

## 1. Purpose

Establish a consistent process to report, record, prioritize, investigate, resolve and learn from IT incidents, including service outages, data loss, suspected security events and backup/recovery failures.

## 2. Scope

Applies to employees, IT personnel and service providers supporting UOA systems, data, endpoints, networks, identity platforms, cloud services and business applications. Suspected security incidents must also follow any approved security incident and contractual notification requirements.

## 3. Policy requirements

1. All incidents must be recorded in the approved ticketing or incident system with a unique ID.
2. Staff must have a clear reporting route and must not be discouraged from reporting suspected incidents.
3. Classify incidents by business impact, urgency, affected scope and risk—not solely by technical complexity.
4. Assign an accountable incident owner and record timestamps, actions, decisions and communications.
5. Escalate critical outages, suspected data exposure, compromised privileged accounts, ransomware, mass deletion and loss of recoverable backups without delay.
6. Preserve relevant logs and evidence in a manner that limits alteration and unnecessary disclosure.
7. Only authorized personnel may perform containment or recovery actions; emergency actions must be recorded as soon as practicable.
8. Communicate status to affected stakeholders at a cadence appropriate to impact and approved communication rules.
9. Validate service restoration with technical checks and, where appropriate, business-owner confirmation.
10. Conduct a post-incident review for major or recurring incidents and track corrective/preventive actions.
11. Do not close an incident while material risks or required follow-up actions are unassigned.
12. Maintain confidentiality and avoid placing passwords, tokens, private keys or raw sensitive data in ordinary tickets or Git.

## 4. Incident categories

| Category | Examples (illustrative, not exhaustive) |
|---|---|
| Availability | Service unavailable, network outage, application failure |
| Access/identity | Unauthorized access, suspected credential compromise, incorrect permissions |
| Data | Accidental deletion, corruption, unauthorized disclosure |
| Backup/recovery | Failed critical backup, unavailable recovery copy, failed restore |
| Security | Malware, suspicious activity, ransomware, firewall/security-control failure |
| Change-related | Failed change, unexpected production impact |

## 5. Severity and response targets

UOA must approve severity criteria, on-call contacts and response/communication targets. Until then, use the existing authorized escalation route and prioritize based on credible business/customer impact. Do not invent an SLA or claim that an unapproved target is binding.

## 6. Incident workflow

### Step 1 — Report and log
Capture reporter, detection time, affected service/assets, symptoms, business impact, scope, recent changes and safe contact details.

### Step 2 — Triage and assign
Assign an incident owner, category, provisional severity and response team. Link relevant change, access, backup or vendor tickets.

### Step 3 — Contain and preserve
Take proportionate steps to limit harm. Preserve relevant logs and timestamps. Avoid destructive cleanup or wiping evidence before the appropriate response owner decides it is safe.

### Step 4 — Investigate and communicate
Maintain a factual timeline separating confirmed facts, hypotheses and unknowns. Provide stakeholder updates through approved channels. For suspected data/security incidents, involve designated security, management, Legal/Compliance or customer contacts as required by the approved response plan.

### Step 5 — Recover and validate
Restore service or data using approved recovery procedures. Verify functionality, integrity, access controls, monitoring and backup operation. Obtain business validation where needed.

### Step 6 — Close and learn
Record root cause when established, impact, actions, recovery point, customer/business communication, evidence references, residual risks and corrective actions. For major or recurring events, complete a post-incident review and track action owners/due dates.

## 7. Minimum incident record

| Field | Entry |
|---|---|
| Incident ID / linked tickets | |
| Reported/detected time and timezone | |
| Reporter / incident owner | |
| Service/assets affected | |
| Category / severity | |
| Facts confirmed / unknowns | |
| Business/customer impact | |
| Recent related changes | |
| Timeline and actions | |
| Escalations and communications | |
| Evidence references | |
| Containment / recovery steps | |
| Service restored time | |
| Business validation | |
| Root cause / contributing factors | |
| Corrective actions / owner / due date | |
| Closure reviewer / date | |

## 8. Post-incident review

For major or recurring incidents, review:
- What happened and when?
- What was the verified impact?
- Which controls worked or failed?
- Were access, change, backup or monitoring procedures followed?
- What evidence supports each conclusion?
- What corrective/preventive actions are needed?
- Who owns each action and by when?
- How and when will effectiveness be tested?

Avoid assigning blame without evidence. Preserve factual records and distinguish allegations, confirmed events and unresolved questions.

## 9. Approval and review

Severity thresholds, escalation contacts, response targets and external notification rules must be completed and approved before the procedure is treated as operational.

## 10. Change history

| Version | Date | Change |
|---|---|---|
| 0.1 | 2026-10-09 | Initial draft |
