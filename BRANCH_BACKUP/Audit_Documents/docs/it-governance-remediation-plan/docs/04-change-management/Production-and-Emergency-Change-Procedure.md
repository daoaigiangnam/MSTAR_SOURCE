# Production and Emergency Change Procedure

- **Document ID:** UOA-IT-CHG-PRO-002
- **Version:** 0.1 — Draft
- **Status:** For review; not approved
- **Procedure owner:** IT Operations (to confirm)

## 1. Purpose

Provide additional safeguards for changes made directly to production and for urgent changes needed to restore service or reduce imminent risk.

## 2. Production change controls

Before a planned production change:
1. Confirm an approved change record and authorized approvers.
2. Verify scope, affected assets, prerequisites and dependency owners.
3. Confirm tested implementation and rollback/recovery steps, including backup or configuration capture where relevant.
4. Verify access rights and ensure the implementer is authorized.
5. Confirm maintenance window, communication, monitoring and escalation coverage.
6. Confirm success criteria and the person empowered to stop or roll back.
7. Ensure the target environment and configuration are correctly identified before execution.

During implementation:
- Follow the approved sequence and record actual timestamps.
- Validate the target before destructive actions.
- Do not disable security, logging or backup controls unless explicitly assessed and approved.
- Stop and escalate if scope changes materially, an unexpected risk appears or success criteria are not met.

After implementation:
- Run technical and business verification as appropriate.
- Confirm monitoring, connectivity, security controls and backup jobs remain healthy.
- Communicate completion or incident status.
- Store evidence and document deviations, rollback and residual risks.

## 3. Emergency change criteria

An emergency change is limited to an urgent action necessary to restore a materially disrupted service, contain an active incident or reduce an imminent material security/business risk. Convenience, poor planning or an overdue request alone does not make a change an emergency.

## 4. Emergency workflow

1. Open an emergency change/incident record as soon as practicable.
2. State the emergency, impact of delay, affected services and proposed minimum safe action.
3. Obtain expedited authorization from the designated emergency authority. The names, delegated powers and contact route must be approved by UOA in advance.
4. Assess likely impact and reversibility; capture configuration/state where feasible.
5. Implement the smallest change likely to address the emergency, using authorized access.
6. Verify service/security outcome and monitor for regression.
7. Record exact actions, timestamps, deviations, outcomes and evidence.
8. Complete retrospective review within the timeframe approved by UOA; record whether the change was justified, whether normal controls were restored and what prevention actions are required.

If prior approval is impossible because delay would materially worsen the incident, document the reason and notify the authorized emergency authority as soon as safely practicable. This exception must not become routine practice.

## 5. Failed change / rollback

Trigger rollback or recovery when predefined success criteria fail, risk exceeds tolerance, or material unexpected impact occurs. The decision owner must be identified in advance. If rollback is unsafe or impossible, stabilize service, open/update an incident and follow the approved recovery plan.

## 6. Emergency change record

| Field | Entry |
|---|---|
| Emergency change / incident ID | |
| Detection time and reporter | |
| Emergency reason and impact of delay | |
| Affected service/assets | |
| Approver and authorization time | |
| Risk assessment / minimum safe action | |
| State/configuration captured | |
| Implementer and exact timestamps | |
| Verification and monitoring result | |
| Rollback/recovery decision | |
| Stakeholder communication | |
| Retrospective reviewer/date | |
| Follow-up actions and owners | |
| Evidence reference | |

## 7. Review metrics

Review emergency changes, repeat causes, missing approvals, unsuccessful changes and overdue retrospective reviews. Use the results to improve planning and standard-change eligibility.

## 8. Change history

| Version | Date | Change |
|---|---|---|
| 0.1 | 2026-10-09 | Initial draft |
