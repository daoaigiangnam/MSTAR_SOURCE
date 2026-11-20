# Product Blueprint v1.0

## Product
Rental Enterprise Platform — multi-tenant SaaS for rental property operations.

## User Groups
1. Platform Admin
2. Property Owner
3. Property Manager
4. Staff
5. Accountant / Finance
6. Tenant

## Core Lifecycle
Lead/Application → Screening → Approval → Lease → Signature → Deposit → Check-in → Active Tenant → Billing/Payment/Maintenance → Renewal or Move-out → Final Settlement → Closed.

## Unit Lifecycle
Available → Reserved → Occupied → Notice → Move-out → Inspection → Maintenance/Cleaning → Available.

## Financial Lifecycle
Draft → Generated → Issued → Partial → Paid; overdue invoices may enter Collection. Special states include Cancelled, Refunded and Written-off.

## Enterprise Modules
IAM & Organization; Property; Unit; Tenant/CRM; Application; Screening; Lease; Move-in/out; Meter; Pricing; Billing; Invoice; Payment; Deposit; AR/Collections; Expense/AP; Maintenance; Asset; Vendor; Inventory; Inspection; Document; Notification/Communication; Workflow/Automation; Analytics/Reporting; Integrations/API; AI; Marketplace/Search/Booking as later expansion.

## Principles
- Multi-tenant by design
- API-first
- Security-first
- Central audit
- RBAC + resource-scoped authorization
- Modular boundaries
- Event-ready
- Mobile + Web
- Horizontal scalability
- Backward-compatible APIs