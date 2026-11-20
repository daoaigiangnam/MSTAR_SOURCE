# IT Access Revocation & Offboarding Checklist — UOA / Mstar
**Template ID:** UOA-IT-FRM-ACC-002  
**Version:** 0.1 — Draft  
**Control note:** Align final version with approved HR offboarding rules and the amended QP-HRD-24. This checklist does not itself authorize account deletion or data destruction.

## A. Personnel event
- Case/ticket ID:
- Person/contractor identifier:
- Department / manager:
- HR/Admin notice reference:
- Event: [ ] Resignation [ ] Termination [ ] Contract end [ ] Transfer [ ] Emergency/suspicious exit
- Last working date/time and timezone:
- Authorized access-disable date/time:
- Is earlier containment authorized/required? [ ] Yes [ ] No [ ] Needs decision
- Decision owner and reference:

## B. Data and business continuity decisions — owner to approve
- Business data owner:
- Data handover recipient:
- Files/data to transfer and approved destination:
- Email/mailbox decision: [ ] Retain [ ] Delegate [ ] Export under approved process [ ] Disable/delete later per retention rule [ ] Other
- Legal hold / retention requirement checked? [ ] Yes [ ] No [ ] N/A with reason
- Approval reference:
- Note: mailbox/data retention is separate from the decision to block sign-in.

## C. Access revocation checklist
For each system, record the action, timestamp, result and evidence reference. Add rows for every system in the approved inventory.

| System / access type | Account or reference | Action (block/revoke/rotate) | Completed by | Date/time/timezone | Result | Evidence ref | Exception / owner |
|---|---|---|---|---|---|---|---|
| Identity / SSO | | | | | | | |
| VPN / remote access | | | | | | | |
| Email / collaboration sign-in | | | | | | | |
| File storage / NAS | | | | | | | |
| Cloud / SaaS | | | | | | | |
| Servers / admin access | | | | | | | |
| Firewall / network / security systems | | | | | | | |
| Backup platform | | | | | | | |
| API tokens / refresh tokens / certificates | | | | | | | |
| Third-party/vendor access | | | | | | | |
| Other systems from inventory | | | | | | | |

## D. Assets and handover
- [ ] Laptop/desktop returned or documented exception
- [ ] Mobile device returned/wiped under approved policy
- [ ] Security key/badge/access card returned
- [ ] Storage/media and other IT assets returned
- [ ] Asset Register updated
- [ ] Business data handover verified by owner
- [ ] Shared/service secrets reviewed and rotated where justified
- Evidence references / exception details:

## E. Verification
- [ ] All in-scope systems checked against current system inventory
- [ ] Sign-in blocking verified
- [ ] VPN and privileged access revocation verified
- [ ] Active sessions/tokens addressed where supported
- [ ] Mailbox/data retention decision separately approved
- [ ] Register and ticket updated
- [ ] Evidence repository reference added
- [ ] Outstanding risks escalated

Completed by / date:
Independent checker / date:
HR/Admin confirmation / date:
System/data owner confirmation / date:
Closure decision and reference:

## F. Exceptions
Every outstanding access must have a named owner, business justification, compensating control, risk owner approval and expiry/review date. Do not close this checklist while a material exception is unassigned.

