# Logging and Security Monitoring Procedure

- **Document ID:** UOA-IT-LOG-PRO-001
- **Version:** 0.1 — Draft
- **Status:** For review; not approved
- **Process owner:** IT Operations / security owner (to confirm)

## 1. Purpose

Define baseline steps for identifying relevant logs, protecting their integrity, reviewing alerts and escalating suspicious activity.

## 2. Scope

In-scope sources may include identity and MFA logs, VPN/firewall, endpoint protection, servers, cloud administration, business applications, databases, NAS/file access, backup platforms and change/ticket systems. Actual sources and technical capabilities must be inventoried.

## 3. Logging requirements

1. Maintain a log-source register listing system, event types, owner, collection status, retention, access and alerting capability.
2. Prioritize authentication, privileged actions, access grants/revocations, remote access, security-policy changes, backup deletion/configuration changes and critical system errors.
3. Synchronize system time to an approved time source where supported and record timezone in investigations.
4. Restrict log access and administrative modification; protect logs from unauthorized deletion or alteration where feasible.
5. Avoid collecting more personal or sensitive data than required. Apply approved access and retention rules.
6. Confirm alert routing and escalation contacts. An enabled alert without tested delivery is not sufficient evidence of an operational control.
7. Record known blind spots, unavailable logs and exceptions with owner and remediation date.

## 4. Routine monitoring procedure

1. Review configured alerts and monitoring dashboards at the approved cadence.
2. Triage suspicious events using source logs and relevant context.
3. Record event ID, detection time, source, affected identity/asset, facts observed and confidence.
4. Distinguish benign/authorized activity, false positives and unresolved suspicious events.
5. Escalate possible account compromise, unusual privileged activity, unauthorized access changes, suspicious VPN activity, backup deletion, malware and data exfiltration indicators through incident management.
6. Preserve relevant logs before retention expiry and avoid unnecessary changes to the affected system until the incident owner approves.
7. Document the decision, action, owner and evidence reference.
8. Verify the alert source and delivery path periodically using a safe approved test.

## 5. Example alert scenarios to assess

- Repeated failed authentication followed by success
- Privileged role or VPN permission granted outside the approved process
- New or changed administrator identity
- Disabled MFA or security logging
- Unexpected firewall rule or remote-management exposure
- Backup job disabled, retention shortened or recovery copy deleted
- Unusual bulk file access/deletion
- Endpoint protection disabled or malware alert
- Cloud sharing made public or external access broadened

These are candidate use cases; enable only those supported by actual platforms and approved monitoring scope.

## 6. Retention and privacy

UOA must approve log-retention periods and access roles based on system capability, business needs, contracts and applicable legal requirements. Do not assume a universal retention period. Restrict evidence to authorized reviewers and record exports by reference rather than committing sensitive raw logs to Git.

## 7. Monitoring register fields

Source/system; owner; log types; collection method; time sync; retention; access roles; alert scenarios; notification route; last alert test; known gaps; incident link; evidence ID; review date.

## 8. Change history

| Version | Date | Change |
|---|---|---|
| 0.1 | 2026-10-09 | Initial draft |
