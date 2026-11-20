# RENTAL ENTERPRISE PLATFORM

Enterprise Rental Property Management Platform.

## Status

Phase 0 — Foundation / Enterprise Blueprint

## Architecture

- Mobile: Flutter
- Backend: PHP 8.3 Modular Monolith
- API: REST + OpenAPI
- Database: MySQL 8
- Cache: Redis
- Queue: Background Workers
- Storage: S3/MinIO-compatible Object Storage
- Security: IAM + RBAC + ABAC/Resource Scope + Audit
- Deployment: Docker + CI/CD

## Product Domains

Property, Unit, Tenant, Application, Lease, Move-in/Move-out, Meter, Pricing, Billing, Invoice, Payment, Deposit, AR, Maintenance, Asset, Vendor, Inventory, Inspection, Documents, Notifications, Workflow, Reporting, Integrations and AI.

## Development Principle

Design → Review → Test → Freeze → Next module.

Business architecture is kept independent from infrastructure/vendor choices.