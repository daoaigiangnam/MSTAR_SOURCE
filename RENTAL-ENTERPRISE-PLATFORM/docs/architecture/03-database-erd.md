# Database / ERD v1.0

## Scope

This phase defines the enterprise data foundation for multi-tenant rental property management. It covers the identity/organization boundary and the property core: portfolio, property, site, building, floor, unit type, unit, tenant, lease and audit foundations.

Financial, maintenance, workflow and analytics tables will be added in later phases without breaking the core identifiers or tenant boundary.

## Design principles

- Every business record is scoped to `organization_id` unless it is explicitly platform-global.
- UUIDs are used as public identifiers; internal numeric IDs are avoided in API contracts.
- `created_at`, `updated_at`, and actor fields are retained for traceability.
- Business history is not destroyed by hard deletes.
- Lease and occupancy data use explicit status and effective dates.
- Money is stored as `DECIMAL(19,4)` with an explicit currency code.
- Foreign keys protect referential integrity.
- Unique constraints are tenant-scoped.
- Audit records are append-oriented.
- Soft deletion is available for master data where restoration is meaningful.

## Core relationship map

```text
organization
  ├── organization_users ── user
  ├── roles ── role_permissions
  ├── portfolios
  │    └── properties
  │         └── sites
  │              └── buildings
  │                   └── floors
  │                        └── units ── unit_types
  │                              │
  │                              └── leases ── tenants
  │
  └── audit_logs
```

## Organization boundary

`organization_id` is the mandatory tenant boundary for all tenant-owned records. Authorization must resolve the organization before repository queries execute. UI filtering is never considered a security control.

## Property hierarchy

The hierarchy intentionally separates reusable unit classification from physical inventory:

- Portfolio: grouping of properties for an owner/operator.
- Property: a managed real-estate asset.
- Site: a physical site/campus within a property when needed.
- Building: a structure.
- Floor: a level in a building.
- Unit Type: reusable commercial/product definition.
- Unit: the rentable physical inventory item.

This supports room rentals, apartments, houses and future commercial units without changing the core model.

## Tenant model

A tenant is a party/person record, not merely a current room occupant. Historical leases remain linked to the same tenant. A later phase can add organizations, households, guarantors and screening records without changing the lease identity.

## Lease model

A lease is a time-bounded legal/operational agreement between an organization, tenant and unit. The database keeps `start_date`, `end_date`, `status`, `monthly_rent`, deposit amount and currency. Amendments, signatures and recurring charges will be separate records in later phases.

A unit can have many historical leases, but application logic must prevent overlapping active occupancy intervals unless the product explicitly enables co-tenancy.

## Audit model

Security-sensitive and administrative operations write append-only audit records. Financial audit fields are intentionally present from the foundation so later modules can reference the same audit mechanism.

## Delete policy

- Organization, user, property, unit, tenant and lease records are not physically deleted during normal operations.
- Master records use `deleted_at` where restoration is supported.
- Historical financial/legal records will never be deleted by normal CRUD APIs.
- Corrections use new records, adjustments or state transitions.

## Phase gates

- ERD reviewed
- Tenant isolation rules reviewed
- Foreign keys and uniqueness reviewed
- Indexes reviewed
- Migration runs cleanly on an empty MySQL 8 database
- Migration can be rolled forward deterministically
- Seed strategy documented before implementation

## Future extensions

The stable IDs established here will be referenced by applications/screening, move-in/out, meters, pricing/billing/invoices, payments/deposits/AR, maintenance/assets/vendors/inventory, documents/notifications/workflows and reporting/analytics/AI.
