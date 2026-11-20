# Architecture & Product Decisions

This file records decisions that have been explicitly approved for the project.

## ADR-001 — Modular Monolith
Status: ACCEPTED

Use a modular monolith for the initial backend. Domain boundaries must remain explicit so selected modules can be extracted later if scale requires it.

## ADR-002 — API First
Status: ACCEPTED

Mobile, web, and external clients communicate through versioned REST APIs. Initial API namespace: `/api/v1`.

## ADR-003 — Flutter Mobile
Status: ACCEPTED

Flutter is the target mobile application technology.

## ADR-004 — MySQL Transactional Database
Status: ACCEPTED

MySQL 8 is the primary transactional database target.

## ADR-005 — Multi-Tenant Platform
Status: ACCEPTED

The platform is designed as multi-tenant from the foundation. Tenant isolation is enforced server-side and is not delegated to the UI.

## ADR-006 — RBAC + Resource Scope / Policy
Status: ACCEPTED

Authorization combines roles, permissions, and resource scope/policy.

## ADR-007 — GitHub as Project Source of Truth
Status: ACCEPTED

The repository is the official record for approved requirements, analysis, design, architecture decisions, source code, test cases, test results, defects, changelog, releases, and operational documentation.

## ADR-008 — Professional Engineering Traceability
Status: ACCEPTED

Every material approved requirement must be traceable through analysis, design, implementation, test cases, test results, UAT, and release/freeze status.

## ADR-009 — Tenant / Application / Lease Business Baseline
Status: ACCEPTED

The Product Owner approved the following baseline on 2026-08-10:
- Support individual and organization tenants, with household/co-tenant/guarantor relationships modeled separately.
- Prevent overlapping active leases on the same unit by default; controlled exceptions require explicit property policy and authorization.
- Model renewal as a subsequent effective term/version while preserving historical lease state.
- Material lease amendments require effective dating, approval where applicable, and audit evidence.
- Approval workflows are configurable by organization/property and transaction type/value.
- Support both internal and external screening integrations; external providers cannot directly activate leases.
- Apply configurable retention/privacy controls while preserving legally, financially, and audit-relevant history.

## ADR-010 — Vietnamese Project Documentation
Status: ACCEPTED

Project discussions, business analysis, requirements, design documentation, test cases, and test results use Vietnamese. Code and technical identifiers use English technical conventions.

## Change Rule
New decisions are added only after explicit approval in project discussion. Superseded decisions remain documented with their replacement rationale.
