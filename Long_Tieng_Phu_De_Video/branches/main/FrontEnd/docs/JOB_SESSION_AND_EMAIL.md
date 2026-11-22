# Job Session + Completion Email

## Architecture

The FrontEnd uses two independent protections for long-running video jobs:

1. **Browser/session protection**
   - PHP session lifetime is 7 days.
   - `session-guard.js` sends a heartbeat every 5 minutes while the page is visible.
   - Job IDs are stored in `localStorage` so Workspace can restore jobs after reload/re-login.

2. **Server-side completion email worker**
   - `cron/job-email-worker.php` runs every minute from Linux cron.
   - It does not depend on the user's browser, tab, or PHP login session.
   - When Backend reports `completed`, it reads the output file list and sends one email containing all available result links.
   - A FrontEnd MySQL table prevents duplicate emails and supports retry.

Backend API and Backend database are not modified.

## Database

Run once:

```sql
FrontEnd/database/20260818_create_job_email_notifications.sql
```

The table stores `job_id -> user_id -> email -> email delivery status`.
It does not modify `users` or `account_tokens`.

## Cron

Example (run every minute):

```bash
* * * * * /usr/bin/php /www/wwwroot/videotool.review360.id.vn/cron/job-email-worker.php >> /www/wwwroot/videotool.review360.id.vn/storage/job-email-worker.log 2>&1
```

Adjust the FrontEnd path and PHP binary for the server.

The worker is CLI-only and can be run manually for testing:

```bash
php FrontEnd/cron/job-email-worker.php
```

## SMTP

Existing `MAIL_*` environment variables are used:

- `MAIL_HOST`
- `MAIL_PORT`
- `MAIL_USERNAME`
- `MAIL_PASSWORD`
- `MAIL_ENCRYPTION`
- `MAIL_FROM`
- `MAIL_FROM_NAME`

No SMTP secret is committed to Git.

## Email link lifetime

The email contains FrontEnd download links. The user must be logged in when opening them. Backend cleanup remains unchanged: completed/failed jobs and their files are retained according to the existing 2-hour cleanup policy.

Therefore the worker should run every minute so the completion email is normally sent shortly after the Backend reports completion, well before cleanup.
