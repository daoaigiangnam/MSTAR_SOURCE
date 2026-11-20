# IT Information Security Policy

- **Document ID:** UOA-IT-SEC-POL-001
- **Version:** 0.1 — Draft
- **Status:** For review; not approved
- **Policy owner:** UOA management / designated security owner (to confirm)
- **Technical owner:** IT (to confirm)
- **Effective date:** To be assigned after formal approval

## 1. Purpose

Define baseline security governance for protecting UOA information, systems and services against unauthorized access, misuse, loss, disruption and compromise.

## 2. Scope

Applies to employees, contractors, service providers, business data, endpoints, servers, networks, identity platforms, cloud services, applications and backup repositories used to conduct UOA business. Actual applicability must be reconciled with contracts and approved organizational scope.

## 3. Security principles

1. Business data and systems must have accountable owners and approved access.
2. Grant only the minimum access needed for a defined business purpose.
3. Use named user identities; shared or service identities require a documented owner and approved exception/control.
4. Protect privileged and remote access with MFA wherever supported and required.
5. Store company data only in approved locations and services.
6. Maintain supported systems, security updates and secure configurations based on risk and operational constraints.
7. Enable appropriate logs and monitoring for material systems and privileged actions where supported.
8. Protect backup copies and ensure recovery capability is tested.
9. Report suspected security incidents promptly through the approved incident route.
10. Keep evidence traceable and restrict access according to classification.
11. Do not store passwords, API tokens, private keys, recovery codes or raw sensitive production data in Git.
12. Exceptions must have an owner, risk assessment, compensating controls, approval and expiry date.

## 4. Minimum control domains

### Identity and access
Use approved request/authorization and JML processes. Review privileged, VPN, vendor and sensitive-data access at an approved cadence. Revoke access when no longer required.

### Asset and configuration management
Maintain an asset/service inventory. Establish secure baselines for supported devices, servers, network devices, cloud tenants and applications. Track material deviations.

### Vulnerability and patch management
Identify vulnerabilities from supported sources, assess exposure and business impact, prioritize remediation under approved targets, test material patches and document exceptions.

### Network and remote access
Restrict management interfaces, segment networks where feasible, use approved remote-access gateways, review firewall rules and remove unused exposure.

### Data protection and backup
Apply data classification, least-privileged access, approved storage, encryption where supported and required, retention controls and tested restore procedures.

### Logging and incident response
Protect logs against unauthorized alteration where feasible, define monitoring and escalation, preserve relevant evidence and maintain incident records.

### Third-party services
Review vendor access, contract/security requirements, incident notification, data return/export and offboarding.

### Change management
Require traceable approval, impact assessment, testing, implementation plan and rollback/recovery approach for material production changes.

## 5. Roles

- **UOA management:** Approves risk appetite, policy, resources and material exceptions.
- **Business/data owner:** Defines data sensitivity, business need, permitted access and recovery priorities.
- **IT:** Implements technical controls, maintains records and escalates deficiencies.
- **HR/Admin:** Provides authoritative personnel lifecycle notifications according to approved process.
- **Procurement/Legal/Compliance:** Reviews supplier, contract and applicable obligation matters where assigned.
- **Users:** Protect credentials/devices and report suspicious activity.
- **Internal Audit:** Independently evaluates control design and operating effectiveness; it should not operate the controls it audits.

## 6. Risk acceptance and exceptions

Record the control gap, affected assets/data, likelihood/impact, existing controls, mitigation, accountable risk owner, approver and expiry/review date. Risk acceptance must follow UOA's delegated authority and cannot waive applicable legal or contractual obligations.

## 7. Awareness and compliance

Provide role-appropriate security guidance and ensure users know how to report incidents, phishing, lost devices and unauthorized access. Non-compliance is handled under applicable company policy and process.

## 8. Review and approval

Review at least annually and after significant incidents, business changes or audit findings. Before approval, UOA must appoint the policy owner, confirm scope, define risk acceptance authority and link supporting procedures.

| Role | Name | Decision | Date |
|---|---|---|---|
| UOA management | To confirm | Pending | — |
| Security/policy owner | To confirm | Pending | — |
| IT owner | To confirm | Pending | — |

## 9. Change history

| Version | Date | Change |
|---|---|---|
| 0.1 | 2026-10-09 | Initial draft |
