# Database

## Current phase

Phase 1 — Enterprise foundation and property core.

## Engine

MySQL 8.x with `utf8mb4`.

## Migration

- `migrations/001_foundation_core.sql`

The migration establishes:

- organizations
- users
- organization_users
- roles
- organization_user_roles
- portfolios
- properties
- sites
- buildings
- floors
- unit_types
- units
- tenants
- leases
- audit_logs

## Identifier policy

Database identifiers are UUID values represented as `BINARY(16)` for storage efficiency. APIs expose UUID strings rather than database-specific binary representations.

## Tenant isolation

Every tenant-owned table carries `organization_id`. Application repositories must always require an organization scope. Cross-organization references must be rejected before persistence; later hardening migrations may add composite tenant-aware foreign keys where appropriate.

## Financial integrity

No financial transaction tables are created in this migration. Phase 2 will introduce invoice/payment/deposit/AR ledgers with idempotency and immutable transaction history.
