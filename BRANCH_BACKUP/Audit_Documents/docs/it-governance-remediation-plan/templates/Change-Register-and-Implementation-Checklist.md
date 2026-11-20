# Change Register and Implementation Checklist

- **Document ID:** UOA-IT-CHG-TPL-001
- **Version:** 0.1 — Draft template
- **Status:** For review; not approved
- **Register owner:** IT Operations (to confirm)

## A. Change register — recommended columns

| Column | Description |
|---|---|
| Change ID | Unique identifier linked to ticket |
| Request date / requester | Who requested and when |
| Business reason | Purpose and expected outcome |
| Category | Standard / Normal / Emergency |
| Risk level | Based on UOA-approved criteria |
| Service / assets | Systems and business services affected |
| Business owner | Person/role accountable for service impact |
| Technical owner / implementer | Accountable IT role and authorized implementer |
| Planned window | Approved date/time and timezone |
| Approval references | Traceable IDs/timestamps, not copied secrets |
| Test status | Not started / Pass / Fail / Approved exception |
| Rollback plan | Reference to documented plan |
| Actual start/end | Actual implementation timestamps |
| Result | Success / Partial / Failed / Rolled back |
| Incident link | Related incident if applicable |
| Evidence reference | Restricted evidence repository ID |
| Closure / review | Reviewer, date and residual actions |

## B. Pre-implementation checklist

- [ ] Change record is complete and linked to the request/ticket.
- [ ] Change category and risk are assigned using approved criteria.
- [ ] Business/service owner and technical owner are identified.
- [ ] Required approvals are explicit and timestamped.
- [ ] Scope, assets and dependencies are verified.
- [ ] Security, access, data, customer and availability impacts are assessed.
- [ ] Test plan/results are recorded, or a justified exception is approved.
- [ ] Backup/configuration capture is completed where relevant and verified.
- [ ] Implementation steps and rollback trigger are documented.
- [ ] Maintenance window and stakeholder communications are confirmed.
- [ ] Implementer has authorized access and correct target/environment.
- [ ] Monitoring and escalation coverage are ready.

## C. During implementation checklist

- [ ] Confirm target environment before executing changes.
- [ ] Record actual start time and implementer.
- [ ] Follow approved steps and record material deviations.
- [ ] Stop and reassess if scope or risk changes materially.
- [ ] Avoid disabling security/logging/backup controls without explicit approval.
- [ ] Record any incident and rollback/recovery decision.

## D. Post-implementation checklist

- [ ] Technical success criteria pass.
- [ ] Business/service owner validation completed where required.
- [ ] Monitoring, connectivity, security and backup controls are healthy.
- [ ] Users/customers/stakeholders are informed as appropriate.
- [ ] Evidence is stored in the approved location.
- [ ] Failures, deviations and residual risks are documented.
- [ ] Follow-up actions have owners and due dates.
- [ ] Closure decision is recorded by the authorized role.
- [ ] Emergency changes have a scheduled retrospective review.

## E. Emergency change retrospective

| Question | Result / evidence |
|---|---|
| Was the emergency criterion met? | |
| Was authorization obtained and recorded? | |
| Was the minimum safe scope used? | |
| Were security and logging controls restored? | |
| Did verification meet success criteria? | |
| Was rollback/recovery needed? | |
| What caused the emergency? | |
| What preventive action is required? | |
| Reviewer / date / evidence ID | |

## F. Record-handling rules

- Use approved ticketing and evidence repositories.
- Keep secrets and sensitive production data out of this register and Git.
- Never backdate approvals or alter original evidence.
- Mark unknown values “To verify”; do not assume a change was approved because it succeeded.
