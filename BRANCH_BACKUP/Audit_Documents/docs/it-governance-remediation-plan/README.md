# UOA / Mstar — IT Audit & Governance Documents

This repository contains controlled draft documents and working registers for the UOA IT governance remediation and audit-readiness programme.

## Document status

Documents are drafts unless their header explicitly states that they have been formally reviewed and approved by the designated authority. Do not treat a draft as an effective company policy or approved operating procedure.

## Repository structure

- `docs/00-governance/` — programme plan and governance
- `docs/01-assessment/` — current-state assessment, risks, audit findings, CAPA and evidence register
- `docs/02-access-management/` — access policy, authorization matrix, VPN/privileged access, access review and lifecycle procedures
- `docs/03-data-backup-recovery/` — data ownership/classification, cloud storage, backup operations, recovery and restore testing
- `docs/04-change-management/` — change governance and production/emergency change procedures
- `docs/05-incident-management/` — IT incident management
- `docs/06-asset-management/` — asset lifecycle, software installation and procurement
- `docs/06-audit-readiness/` — CAPA closure and effectiveness checklist
- `docs/07-identity-lifecycle/` — joiner/mover/leaver procedure
- `docs/08-vendor-management/` — vendor and third-party access governance
- `docs/09-audit-evidence/` — audit evidence and CAPA reporting
- `docs/10-security-governance/` — information security policy
- `docs/11-vulnerability-management/` — vulnerability and patch management
- `docs/12-security-monitoring/` — logging and security monitoring
- `docs/13-security-incident-response/` — security incident response
- `docs/14-control-testing/` — IT control testing plan
- `docs/15-governance-reporting/` — governance and CAPA dashboard specification
- `docs/16-governance-raci/` — proposed governance RACI
- `templates/` — reusable request forms, registers, checklists and test records

The six supplied QP-HRD process flowcharts are in scope for review, but revisions or no-change decisions must be documented separately after the source process owners confirm the current controlled versions.

## Programme entry point

[IT Governance Remediation Master Plan](docs/00-governance/IT-Governance-Remediation-Master-Plan.md)

## Working rules

1. Distinguish verified facts from reported concerns, assumptions, proposals and items requiring validation.
2. Never create retrospective approvals, backdate records, or alter original audit evidence.
3. Do not commit passwords, tokens, private keys, raw production logs, or unnecessary personal/sensitive data.
4. Each controlled document should identify its document ID, version, status, owner, approver, effective date (after approval), revision history, evidence requirements and review cadence as appropriate.
5. Close a CAPA only after implementation evidence and effectiveness verification are recorded and accepted by the authorized role.
6. Use reviewed commits and the pull-request workflow; do not merge draft documentation until the required owners review and approve it.
7. Use evidence IDs and restricted-repository references for sensitive audit material rather than storing the evidence itself in an unrestricted repository.

## Source limitations

Initial planning is based on the Audit feedback email and six one-page process flowcharts supplied for review. The flowcharts alone do not prove whether a control operated in practice. Supporting policies, forms, tickets, approvals, system logs, asset registers, cloud configurations and actual control operation must be validated before findings or CAPAs are confirmed as closed.
