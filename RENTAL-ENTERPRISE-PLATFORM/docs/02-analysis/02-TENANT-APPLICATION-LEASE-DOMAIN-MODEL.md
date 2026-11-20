# Domain Analysis — Tenant / Application / Lease

## Status
ANALYSIS DRAFT — based on approved requirements.

## 1. Domain Model

```text
Organization
  └── Tenant
       ├── TenantContact
       ├── EmergencyContact
       ├── TenantDocument
       └── LeaseParticipant

Property
  └── Unit
       ├── Application
       │    ├── ApplicationParticipant
       │    ├── ScreeningCase
       │    └── ApplicationDecision
       │
       └── Lease
            ├── LeaseParticipant
            ├── LeaseTerm
            ├── LeaseAmendment
            └── LeaseEvent
```

## 2. Application → Tenant → Lease

An approved application does not automatically imply an active lease. The workflow is explicitly separated:

```text
Application Submitted
        ↓
Screening
        ↓
Decision
   ┌────┴────┐
Rejected   Approved
             ↓
       Tenant Confirmation
             ↓
        Lease Draft
             ↓
        Lease Approval
             ↓
          Active
```

This separation prevents screening/approval state from being confused with contractual occupancy state.

## 3. Occupancy Conflict Analysis

The system must evaluate conflicts using at minimum:
- organization scope
- unit/resource
- lease effective start
- lease effective end
- lease status
- occupancy policy

For a unit where overlapping active occupancy is prohibited, a new lease interval `[start, end)` conflicts with an existing qualifying interval when:

`new_start < existing_end AND existing_start < new_end`

Open-ended intervals are treated as having no finite end for conflict evaluation.

## 4. Lease Effective Dating

Lease terms must be effective-dated. A material amendment creates a new effective version or term record rather than silently rewriting historical terms.

Billing and reporting must resolve the applicable effective terms for the requested business date/period.

## 5. State Machines

### Application
```text
DRAFT
  ↓
SUBMITTED
  ↓
SCREENING
  ├── REJECTED
  ├── WITHDRAWN
  ├── EXPIRED
  └── APPROVED
```

### Lease
```text
DRAFT
  ↓
PENDING_APPROVAL
  ↓
APPROVED
  ↓
ACTIVE
  ├── NOTICE
  │     ↓
  │  TERMINATED
  └── EXPIRED
```

Renewal and amendment are modeled as controlled business operations, not arbitrary status changes.

## 6. Key Invariants

1. Tenant and lease records cannot cross organization security boundaries.
2. Application participants must be authorized for the organization.
3. Lease activation requires a valid unit and eligible participant set.
4. Prohibited occupancy overlaps must be rejected transactionally.
5. Historical lease terms must remain reconstructable.
6. A terminated lease remains available for audit, billing, reporting, and legal history.
7. Material changes create audit evidence.

## 7. Transaction Boundaries

The following operations require transactional consistency:
- application approval
- tenant creation/conversion
- lease activation
- lease amendment effective-date change
- lease termination
- occupancy conflict validation + lease activation

## 8. Design Questions to Resolve Before Implementation

- Exact participant model for individual, household, and guarantor.
- Whether a unit can have multiple concurrent leases by business policy.
- Whether lease renewal creates a new lease or a new version of the same lease.
- Required approval levels by organization/property.
- Screening provider integration boundaries.
- Document retention and privacy policies.

These questions remain explicitly open until approved and recorded in project decisions.
