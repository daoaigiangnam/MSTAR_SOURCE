# Data Ownership and Classification Policy

- **Document ID:** UOA-IT-DATA-POL-001
- **Version:** 0.1 — Draft
- **Status:** For review; not approved
- **Policy owner:** UOA business management / designated data governance owner (to confirm)
- **Technical custodian:** IT (to confirm)
- **Approvers:** UOA management, relevant data owners, HR/Admin and Legal/Compliance where applicable (to confirm)
- **Effective date:** To be assigned after formal approval
- **Review cycle:** At least annually and after material changes

## 1. Purpose

Establish clear accountability for company data, including data stored on file servers, NAS, endpoints, SaaS platforms and backup repositories. This policy supports controlled access, backup ownership, retention, recovery and audit evidence.

## 2. Scope

Applies to UOA business data and systems managed by UOA or its service providers, including data stored in third-party or Mstar-managed accounts. The exact systems, data locations and contractual arrangements must be inventoried before this policy is approved.

## 3. Core rules

1. **Business ownership is not the same as technical administration.** The business data owner decides permitted use, access, retention and business criticality; IT implements approved controls.
2. Each material dataset or system must have a named business owner and a named technical custodian. Record role names and approved contacts in a controlled register.
3. Data must not be moved to a personal, vendor-owned or otherwise unapproved cloud account merely for convenience.
4. The account owner, subscription payer, technical administrator, data owner and party entitled to retrieve/export data must be recorded separately where they differ.
5. Third-party storage requires documented approval and review of contract terms, access, data location, retention, incident notification, export and exit arrangements.
6. Access to production data and backup data follows least privilege and approved access procedures.
7. Data classification and retention requirements must be defined by the business owner and reviewed with Legal/Compliance where required. Do not infer legal requirements without checking applicable contracts and law.
8. No backup copy may be deleted, relocated or made inaccessible until the owner, retention requirement, recovery dependency and change approval have been verified.

## 4. Suggested classification model

| Class | Description | Minimum handling expectation |
|---|---|---|
| Public | Approved for public release | Prevent unauthorized modification; publish only through approved channels |
| Internal | Routine internal business information | Authenticated access; share only with authorized personnel |
| Confidential | Customer, employee, commercial, financial or operational information requiring restricted access | Need-to-know access, controlled sharing, logging where available, approved storage |
| Restricted | Highly sensitive data or data with strict contractual/regulatory controls | Explicit owner approval, narrowly scoped access, enhanced monitoring and separately controlled recovery access |

The data owner and UOA management must confirm whether this classification model fits UOA. Existing customer classifications and contractual requirements take precedence where applicable.

## 5. Roles and responsibilities

- **Business/Data Owner:** Classifies data; approves access and retention; sets business recovery priorities and validates recovered data.
- **IT Custodian:** Maintains inventory, implements access/backup controls, monitors jobs, preserves logs and executes approved restore requests.
- **HR/Admin:** Supplies authoritative joiner/mover/leaver status and validates employment-related approvals where applicable.
- **Security/Compliance/Legal (if designated):** Advises on security requirements, contracts, retention and incident obligations.
- **Service Provider:** Performs only contracted activities; uses named, authorized access; provides evidence and supports export/exit as agreed.
- **Internal Audit:** Independently assesses evidence and control effectiveness; should not be the operational control owner.

## 6. Ownership and storage register

For each system or dataset, record at minimum:
- System/dataset name and business purpose
- Business owner and delegate
- Technical custodian and provider
- Classification and data subjects/categories (high-level only)
- Primary storage location and backup locations
- Account/subscription owner and billing owner
- Authorized administrator groups
- Retention and deletion rules
- Recovery priority, approved RPO and RTO
- Contract/reference and data export/exit method
- Approval reference, review date and outstanding decisions

Do not store passwords, access tokens, private keys or raw sensitive data in this register or Git repository.

## 7. Required actions before approval

- Inventory current data repositories, including NAS, Google Drive and any Mstar-managed cloud account.
- Validate the reported concern that some backup data may be stored in an Mstar cloud account; record evidence and scope without treating the report alone as a verified finding.
- Identify the affected datasets, business owners, account owner, administrators, access path, retention and recovery dependencies.
- Obtain a written decision from UOA management and relevant data owners on the approved backup destination and account ownership model.
- Confirm contractual, privacy, residency and exit requirements with the appropriate reviewers.

## 8. Exceptions and enforcement

Exceptions must state the business reason, affected data/systems, compensating controls, owner, approver and expiry date. Unapproved storage or access must be escalated through the incident/risk process. Do not remove existing backup copies until a safe, approved replacement and successful restore evidence exist.

## 9. Approval record

| Role | Name | Decision | Date | Evidence/reference |
|---|---|---|---|---|
| UOA management | To confirm | Pending | — | — |
| Business/data owner(s) | To confirm | Pending | — | — |
| IT owner | To confirm | Pending | — | — |
| Legal/Compliance, if required | To confirm | Pending | — | — |

## 10. Change history

| Version | Date | Change | Author/owner |
|---|---|---|---|
| 0.1 | 2026-10-09 | Initial draft for review | IT / to confirm |
