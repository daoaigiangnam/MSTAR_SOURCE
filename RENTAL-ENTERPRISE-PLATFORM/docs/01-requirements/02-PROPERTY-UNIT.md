# Domain Requirements — Property, Building, Floor & Unit

## Status
DRAFT — pending Product Owner approval.

## Scope
Property portfolio structure, physical locations, buildings, floors, rentable units, unit types, availability, and unit lifecycle.

## Functional Requirements

### REQ-PROPERTY-001 — Portfolio
The system shall allow an organization to manage one or more property portfolios.

### REQ-PROPERTY-002 — Property
A property shall represent a managed rental location and belong to exactly one organization.

### REQ-PROPERTY-003 — Property Metadata
A property shall support identity, name, address, geographic information, status, ownership/management metadata, and configurable operational settings.

### REQ-PROPERTY-004 — Building
A property may contain one or more buildings. Buildings shall have their own identity, status, and metadata.

### REQ-PROPERTY-005 — Floor
A building may contain ordered floors. Floor numbering must support practical real-world numbering conventions, including basement and ground-floor representations.

### REQ-PROPERTY-006 — Unit
A building/floor structure shall contain rentable units. A unit shall have a stable identifier and belong to one property context.

### REQ-PROPERTY-007 — Unit Type
The system shall support reusable unit types with configurable characteristics such as area, bedroom count, bathroom count, occupancy capacity, and default pricing rules where applicable.

### REQ-PROPERTY-008 — Unit Status
A unit shall support a controlled lifecycle including at minimum available, reserved, occupied, maintenance, inactive, and retired states, with business rules governing valid transitions.

### REQ-PROPERTY-009 — Availability
The platform shall calculate or expose unit availability based on current unit state, reservations/applications, active leases, and configured business rules.

### REQ-PROPERTY-010 — Multiple Pricing Models
The design shall allow pricing to be configured per unit, unit type, lease, or pricing rule without losing historical prices used by existing agreements.

### REQ-PROPERTY-011 — Attributes
Units shall support configurable attributes and amenities without requiring schema changes for every new attribute category.

### REQ-PROPERTY-012 — Media
Authorized users shall be able to associate photos and documents with properties, buildings, floors, and units through the document/storage subsystem.

### REQ-PROPERTY-013 — Ownership / Management Context
The system shall distinguish organization management context from property-level operational responsibility where required.

### REQ-PROPERTY-014 — Archive
Properties and units shall support controlled archival/deactivation without destroying historical leases, invoices, payments, maintenance, or audit records.

### REQ-PROPERTY-015 — Bulk Operations
Authorized users shall be able to perform controlled bulk operations for appropriate property/unit metadata, with validation and audit logging.

### REQ-PROPERTY-016 — Import / Export
The platform shall support authorized import/export of property and unit master data using validated templates and error reporting.

## Business Rules

### BR-PROPERTY-001
Every property and unit belongs to one organization tenant.

### BR-PROPERTY-002
A unit cannot simultaneously have mutually exclusive lifecycle states.

### BR-PROPERTY-003
Historical lease and financial records must remain traceable to the unit even if the unit is retired.

### BR-PROPERTY-004
Changing a default unit price must not rewrite historical financial amounts already committed to invoices or leases.

### BR-PROPERTY-005
Bulk updates must validate each record and produce an auditable result for successes and failures.

## Acceptance Criteria
1. An organization can create a portfolio, property, building, floor, and unit hierarchy.
2. Authorized users can manage unit types and unit attributes.
3. Unit status transitions follow defined business rules.
4. Historical data remains intact after archival.
5. Cross-organization access is denied.
6. Pricing history is preserved for committed transactions.
7. Import/export operations provide validation feedback and audit evidence.

## Traceability
Requirements will be mapped to use cases, domain/data design, APIs, migrations, implementation, test cases, test results, and UAT.
