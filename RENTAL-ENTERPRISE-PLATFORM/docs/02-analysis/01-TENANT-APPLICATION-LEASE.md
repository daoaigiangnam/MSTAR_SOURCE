# Analysis — Tenant, Application & Lease

## Status
APPROVED REQUIREMENT BASELINE → ANALYSIS IN PROGRESS

## Domain Lifecycle
Prospect → Application → Screening → Decision → Tenant → Lease Draft → Approval → Active Lease → Amendment / Renewal → Notice → Terminated → Historical

## Core Domain Objects
- Tenant
- Tenant Contact
- Emergency Contact
- Tenant Document
- Application
- Application Participant
- Screening Case
- Screening Result
- Application Decision
- Lease
- Lease Participant
- Lease Term
- Lease Amendment
- Lease Event

## Key Invariants
1. Organization is the security boundary for tenant and lease data.
2. A unit cannot have conflicting active occupancy where overlap is prohibited.
3. Lease amendments must not overwrite historical financial/legal state without version/effective-date preservation.
4. Application approval and lease activation are distinct state transitions.
5. Billing must reference the effective lease terms applicable to the billing period.
6. Material lifecycle changes require audit evidence.

## State Models
### Application
DRAFT → SUBMITTED → SCREENING → APPROVED / REJECTED / WITHDRAWN / EXPIRED

### Lease
DRAFT → PENDING_APPROVAL → APPROVED → ACTIVE → NOTICE → TERMINATED / EXPIRED

State transitions must be implemented through explicit business rules rather than unrestricted status updates.

## Next Design Work
- Domain ERD
- State transition model
- Lease effective-dating model
- Occupancy conflict model
- Application-to-tenant conversion model
- Authorization/resource-scope matrix
- Test scenario catalogue
