# System Architecture v1.0

## High-level

Clients (Flutter Mobile, Web Portal, Admin Console, External API) connect through HTTPS/WAF/API Gateway to a PHP 8.3 Modular Monolith.

The application is organized into bounded domain modules and platform services. Infrastructure consists of MySQL 8, Redis, background queue/workers and S3/MinIO-compatible object storage.

## Domain Modules

IAM, Organization, Property, Unit, Tenant, Application, Screening, Lease, MoveInOut, Meter, Pricing, Billing, Invoice, Payment, Deposit, AR, Expense, Maintenance, Asset, Vendor, Inventory, Inspection, Document, Notification, Workflow, Reporting, Integration and AI.

## Rules

- Domain modules must not access each other's database tables directly without an explicit application/domain interface.
- HTTP requests must not perform long-running work synchronously when it can be queued.
- Payment state changes must be verified server-side and be idempotent.
- Tenant isolation is enforced server-side, never only in UI filters.
- Audit events are centralized for security-sensitive and financial operations.
- API versioning starts at `/api/v1`.

## Scaling Path

Start as a modular monolith. Add horizontal application nodes, read replicas, workers and reporting infrastructure as load grows. Extract a bounded context into a service only when operational or scaling requirements justify it.

## Client Strategy

Mobile is optimized for field/tenant workflows. Web is optimized for administration, finance, bulk operations and reporting.