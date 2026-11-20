# Pre-Merge Quality Gate and Review Checklist

**Status:** Draft 0.1 — review checklist for PR preparation.  
**Purpose:** Reduce the risk of publishing inconsistent, duplicated, unapproved, or unsupported governance documents. This checklist does not imply that a review has already passed.

## 1. Repository and document integrity

- [ ] Confirm every referenced path exists on the PR branch.
- [ ] Confirm the README directory tree and document index match the actual files.
- [ ] Confirm no duplicate or conflicting policy/procedure exists without a clear hierarchy.
- [ ] Confirm cross-references use exact document names and current paths.
- [ ] Confirm each document has a title, status, owner/approver fields where needed, and version/review metadata.
- [ ] Confirm templates are clearly labeled as templates and contain no invented records.
- [ ] Confirm no secrets, credentials, private keys, tokens, raw sensitive production exports, or unnecessary personal data are committed.
- [ ] Confirm any example data is visibly marked as illustrative and not presented as actual evidence.
- [ ] Confirm all new files are UTF-8 text and render correctly in GitHub Markdown.

## 2. Governance and factual accuracy

- [ ] Clearly distinguish verified facts, reported concerns, hypotheses and unresolved questions.
- [ ] Do not claim any QP-HRD procedure is compliant/non-compliant before examining its current controlled version and actual operating evidence.
- [ ] Confirm every control test references a defined criterion and a real source population.
- [ ] Confirm no test result is pre-populated as pass or fail without evidence.
- [ ] Confirm approval roles are described as “to be confirmed” unless supported by an approved authority matrix.
- [ ] Confirm risk categories and deadlines are labeled as interim/proposed unless formally approved.
- [ ] Confirm draft documents do not imply management or Audit approval.
- [ ] Confirm CAPA closure requires implementation evidence and retest/effectiveness evidence where applicable.
- [ ] Confirm historical missing approvals are recorded transparently and not recreated or backdated.

## 3. Access and security controls

- [ ] Access requests, approvals, implementation evidence and actual permissions are distinguishable.
- [ ] VPN/privileged access tests do not direct unapproved production changes.
- [ ] Offboarding checks distinguish sign-in blocking, token/session revocation, mailbox/data retention, and deletion.
- [ ] Exceptions have scope, owner, approval, compensating control and expiry/review date.
- [ ] Logging, retention, MFA and periodic review requirements are tied to the approved policy and actual system scope.

## 4. Backup and recovery controls

- [ ] Backup inventory records data owner, storage/account owner, scope, location, retention and access.
- [ ] Backup-job success is not treated as proof of successful recovery.
- [ ] RPO/RTO are sourced from approved business/service-owner decisions, not invented.
- [ ] Restore tests use an approved target and avoid overwriting production.
- [ ] Migration/cutover includes integrity/completeness validation, restore evidence, rollback and authorized sign-off.
- [ ] Source deletion is a separate decision after target and recovery validation.
- [ ] Capacity assumptions distinguish raw, usable, quota-limited and retention/versioning overhead.

## 5. Evidence and reporting

- [ ] Evidence IDs point to an approved restricted repository; raw restricted evidence is not copied into Git.
- [ ] Each finding includes criterion, verified condition, evidence, risk, owner, due date and action plan.
- [ ] Inconclusive results and limitations are visible in the report.
- [ ] Dashboard formulas specify numerator, denominator, reporting period, source and refresh time.
- [ ] Reported counts reconcile to source registers; stale or unavailable sources are flagged.
- [ ] Handover records identify approved recipient, authority, transfer channel, date/time and package scope.

## 6. Required review decisions

| Review item | Decision | Reviewer / evidence |
|---|---|---|
| Source procedure versions validated | Pending | To be recorded |
| Document hierarchy and terminology reconciled | Pending | To be recorded |
| Security/privacy scan of repository content | Pending | To be recorded |
| Cross-links and file paths checked | Pending | To be recorded |
| CAPA lifecycle and status values aligned | Pending | To be recorded |
| Backup ownership/recovery gates reviewed | Pending | To be recorded |
| README/index updated | Pending | To be recorded |
| Authorized approver decision | Pending | To be recorded |

## 7. Release gate

The PR should remain draft until:
1. The file list and cross-references have been checked.
2. Draft status and unverified assumptions are clear.
3. Security-sensitive content has been reviewed.
4. The accountable process owners review the proposed documents.
5. Conflicts with current controlled procedures are resolved or explicitly recorded as decisions required.
6. The authorized reviewer approves the release state.

Passing this checklist is not equivalent to management approval of every policy. Approval of each controlled policy/procedure must follow the organization's document-control process.

## 8. Review record

- Reviewer:
- Review date:
- Files/sections sampled:
- Issues found:
- Corrective actions:
- Final decision: Pending
