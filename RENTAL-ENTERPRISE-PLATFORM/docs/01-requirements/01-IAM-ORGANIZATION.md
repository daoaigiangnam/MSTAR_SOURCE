# Domain Requirements — IAM & Organization

## Status
DRAFT — pending Product Owner approval.

## Scope
Identity, authentication, authorization, organization tenancy, users, roles, permissions, sessions, and audit foundations.

## Actors
- Platform Administrator
- Organization Owner
- Organization Manager
- Staff
- Finance User
- Tenant

## Functional Requirements

### REQ-IAM-001 — User Identity
The system shall maintain a unique user identity with a stable identifier and lifecycle status (active, suspended, locked, deactivated).

### REQ-IAM-002 — Authentication
The system shall authenticate users through approved authentication methods and shall not expose credentials or authentication secrets to client applications.

### REQ-IAM-003 — Session Security
The system shall support secure access and refresh token/session lifecycle, revocation, expiration, and protection against replay where applicable.

### REQ-IAM-004 — Password Security
Where password authentication is enabled, passwords shall be stored only as strong one-way password hashes and shall never be stored in plaintext.

### REQ-IAM-005 — MFA
The platform shall support multi-factor authentication for privileged accounts and allow organizational policy to require MFA for selected roles.

### REQ-IAM-006 — Organization
The platform shall support multiple independent organizations (tenants) within one platform installation.

### REQ-IAM-007 — Organization Membership
A user shall be able to belong to one or more organizations when permitted by platform policy. Membership shall have its own status and role/scope assignments.

### REQ-IAM-008 — Tenant Isolation
Users shall only access resources belonging to organizations for which they have authorized membership and scope. Tenant isolation shall be enforced server-side.

### REQ-IAM-009 — RBAC
The system shall support roles and permissions. Permissions shall represent actions such as view, create, update, delete, approve, export, and administer.

### REQ-IAM-010 — Resource Scope
Authorization shall support resource-level scope such as organization, portfolio, property, building, or unit where required by the business role.

### REQ-IAM-011 — Least Privilege
Default access shall be deny-by-default. A user receives only permissions granted through active role/policy assignments.

### REQ-IAM-012 — Administrative Separation
Platform administration shall be logically separated from organization-level administration. Platform administrators must not be treated as ordinary organization users.

### REQ-IAM-013 — Account Lifecycle
The platform shall support invitation, activation, suspension, lockout, password reset, deactivation, and reactivation workflows subject to policy.

### REQ-IAM-014 — Audit
Security-sensitive identity and authorization actions shall generate immutable audit records containing actor, action, target, timestamp, organization context, and relevant request metadata.

### REQ-IAM-015 — Access Revocation
Deactivated, suspended, or removed users shall lose access according to defined token/session revocation rules without relying on client-side logout alone.

### REQ-IAM-016 — Organization Settings
Organizations shall have configurable security and operational settings, subject to platform-level constraints.

### REQ-IAM-017 — Data Export / Privacy
The platform shall provide controlled mechanisms for authorized export or removal of user/account data where required by policy or applicable regulation.

### REQ-IAM-018 — Service Accounts
The platform shall support non-human service identities for controlled integrations where required, with scoped credentials and auditable activity.

## Business Rules

### BR-IAM-001
A user cannot access an organization resource without an active membership or an explicitly authorized platform-level policy.

### BR-IAM-002
A role assignment is not sufficient by itself to cross organization boundaries.

### BR-IAM-003
Deleting a user must not delete historical audit or financial records that require the user identity for traceability.

### BR-IAM-004
Privileged actions require successful authorization at the server boundary.

### BR-IAM-005
Permission changes must take effect according to a defined cache/session invalidation policy.

## Non-Functional Requirements

- Authentication secrets must be protected in transit and at rest.
- Authorization checks must be centralized and testable.
- Identity operations must be auditable.
- The design must support horizontal scaling without local-session dependence.
- IAM failures must fail closed for protected resources.

## Acceptance Criteria
1. A user can authenticate successfully when valid and active.
2. Invalid, suspended, and deactivated accounts are denied appropriately.
3. Cross-organization resource access is denied even when a valid resource ID is supplied.
4. Role/permission checks are enforced server-side.
5. Privileged actions create audit evidence.
6. Revoked access cannot continue indefinitely through an old session/token.
7. Automated tests cover positive and negative authorization paths.

## Traceability
Requirements in this document will be mapped to use cases, design documents, APIs, database objects, source code, test cases, test results, and UAT evidence in the Requirement Traceability Matrix.
