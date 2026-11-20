# Tenant, Application & Lease Requirements

## Document Status
APPROVED — Product Owner approval recorded on 2026-08-10.

## Scope
This baseline defines the approved requirements for the tenant lifecycle from prospective applicant through active tenancy and lease termination.

## Tenant Management
- REQ-TENANT-001: The system shall maintain a unique tenant profile within the organization scope.
- REQ-TENANT-002: A tenant profile shall support identity, contact, emergency-contact, document, communication-preference, and status information.
- REQ-TENANT-003: The system shall preserve tenant history and shall not destroy historical lease/accounting relationships through ordinary profile deactivation.
- REQ-TENANT-004: Tenant access and staff access shall be restricted by organization and authorized resource scope.
- REQ-TENANT-005: The system shall maintain an auditable history for material tenant changes.

## Application
- REQ-APPLICATION-001: The system shall support rental applications against an available unit or applicable inventory offering.
- REQ-APPLICATION-002: An application shall have a controlled lifecycle from draft/submitted through screening, decision, withdrawal/rejection, or approval.
- REQ-APPLICATION-003: The system shall prevent an approved application from creating conflicting occupancy for the same unit and effective period.
- REQ-APPLICATION-004: Screening information shall be access-controlled and auditable.
- REQ-APPLICATION-005: Application documents and decision evidence shall be retained according to configured policy.

## Lease
- REQ-LEASE-001: The system shall create a lease only for an authorized tenant/participant and an eligible unit/resource.
- REQ-LEASE-002: A lease shall have effective start/end dates, financial terms, deposit terms, status, participants, and applicable rules.
- REQ-LEASE-003: Lease lifecycle transitions shall be controlled and auditable.
- REQ-LEASE-004: The system shall prevent overlapping active leases for the same unit where business rules prohibit overlap.
- REQ-LEASE-005: Lease amendments shall preserve the original agreement history and record effective dates and approval information.
- REQ-LEASE-006: Lease termination shall preserve historical occupancy, billing, payment, and audit relationships.
- REQ-LEASE-007: Lease data used for billing shall be traceable to the applicable approved lease terms.

## Business Rules
- BR-TENANT-001: Tenant identity is organization-scoped.
- BR-APPLICATION-001: Only authorized staff may approve or reject an application.
- BR-LEASE-001: A lease cannot become ACTIVE without satisfying required approval and data validation rules.
- BR-LEASE-002: Material lease changes require an audit record and effective date.
- BR-LEASE-003: Historical records required for financial, legal, or audit purposes must remain immutable or versioned according to the data policy.

## Acceptance Criteria
The module must demonstrate tenant creation/update, application lifecycle, approval controls, lease creation, lifecycle transitions, overlap prevention, amendment history, access control, and auditability through linked test cases before it can be marked FROZEN.

## Traceability
Detailed mapping is maintained in `docs/01-requirements/REQUIREMENT_TRACEABILITY_MATRIX.md` and will be expanded as design and implementation artifacts are created.
