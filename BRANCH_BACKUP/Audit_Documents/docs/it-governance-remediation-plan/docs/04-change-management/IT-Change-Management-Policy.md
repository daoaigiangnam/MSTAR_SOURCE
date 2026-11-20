# IT Change Management Policy

- **Document ID:** UOA-IT-CHG-POL-001
- **Version:** 0.1 — Draft
- **Status:** For review; not approved
- **Policy owner:** IT owner (to confirm)
- **Approver:** UOA management / delegated change authority (to confirm)
- **Effective date:** To be assigned after approval

## 1. Purpose

Ensure changes to IT systems, applications, networks, security controls and data services are assessed, authorized, tested, implemented and reviewed in a controlled and traceable manner.

## 2. Scope

Applies to production and business-critical environments managed by UOA or its service providers, including infrastructure, endpoints and identity services, network/firewall/VPN, cloud/SaaS configuration, applications, databases, file storage, backup and security controls. Routine service requests may use an approved standard-change path only after that path has been documented and authorized.

## 3. Policy requirements

1. Every non-standard production change must have a traceable change record before implementation, except where an emergency change procedure applies.
2. The record must describe purpose, scope, affected assets/services, risk, business impact, dependencies, outage expectations, test plan, implementation plan, rollback plan, owner and planned window.
3. The requester must not be the sole approver of a material production change. Segregate request, approval and implementation roles where practicable.
4. Approval must come from the authorized service/business owner and technical/change authority according to the change's risk and impact.
5. Changes affecting customer data, security, identity/access, network connectivity, backup/retention or service availability require explicit impact and risk assessment.
6. Production changes must be tested in a suitable non-production environment where feasible. If testing is not feasible, document the reason, compensating controls and approval.
7. Changes must be implemented by authorized personnel using approved accounts and the agreed plan.
8. Capture pre-change state/configuration where feasible, implementation results, verification evidence and rollback outcome if used.
9. Emergency changes may use expedited approval but must be logged, authorized by the designated emergency authority and reviewed retrospectively within the approved timeframe.
10. Failed or partially completed changes must be escalated, stabilized and either rolled back or moved to a documented recovery plan.
11. Close a change only after post-implementation checks, stakeholder communication and evidence capture.
12. Change records and evidence must be retained in the approved repository according to the records schedule.
13. Shared credentials, secrets, private keys and raw sensitive production exports must not be placed in Git or ordinary change tickets.

## 4. Change categories

| Category | Definition | Required control |
|---|---|---|
| Standard | Low-risk, repeatable, pre-approved procedure with defined scope and rollback | Approved catalogue/runbook, eligibility checks, execution log |
| Normal | Planned change requiring case-specific assessment and approval | Full change record, risk review, testing, approval and post-check |
| Emergency | Needed urgently to restore service or reduce an imminent material risk | Expedited documented authorization, minimum safe implementation, retrospective review |
| Failed/rollback | Change did not meet success criteria or increased risk | Incident/change linkage, recovery action, root-cause review as appropriate |

A change must not be labelled “standard” merely because it is frequent. It must first be assessed and formally approved for that category.

## 5. Risk and approval model

Use UOA-approved thresholds to classify risk. At minimum assess:
- Business/customer impact and service outage
- Data loss/corruption and recoverability
- Security exposure, access changes and compliance/contractual impact
- Scope, complexity, dependencies and reversibility
- Testing coverage and implementation window
- Provider involvement and ability to restore prior state

The approval matrix and thresholds must be completed by UOA before the policy becomes effective. Until then, use the existing authorized escalation path and do not infer approval from silence.

## 6. Minimum evidence

Change ID; requester; reason; affected systems; risk/impact assessment; owner approvals; test results; implementation/rollback plan; scheduled window; implementer; start/end timestamps; pre/post verification; communication; incidents; closure decision; evidence references.

## 7. Metrics and oversight

The change owner should periodically review change success/failure rate, emergency-change volume, unauthorized changes, rollbacks, incidents attributable to changes and overdue post-implementation reviews. Define targets only after establishing a reliable baseline and approval.

## 8. Exceptions

Document the reason, affected scope, risk, compensating controls, approver and expiry. Exceptions do not waive legal, contractual or customer obligations.

## 9. Approval record

| Role | Name | Decision | Date |
|---|---|---|---|
| UOA management / delegated authority | To confirm | Pending | — |
| IT owner | To confirm | Pending | — |
| Business/service owner(s) | To confirm | Pending | — |

## 10. Change history

| Version | Date | Change |
|---|---|---|
| 0.1 | 2026-10-09 | Initial draft |
