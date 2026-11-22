# VideoDubing AI FrontEnd

## 1. MySQL

Create the database and tables:

```bash
mysql -u root -p < database/schema.sql
```

## 2. PHP dependencies : Dùng PHP 7.4

From `FrontEnd/`:

```bash
composer install --no-dev --optimize-autoloader
```

This installs PHPMailer for SMTP delivery.

## 3. Environment

Do not commit production secrets. Configure these variables for PHP-FPM/systemd:

```text
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=videodubing
DB_USER=videodubing
DB_PASSWORD=your-db-password

MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=no-reply@review360.id.vn
MAIL_PASSWORD=your-smtp-password
MAIL_ENCRYPTION=tls
MAIL_FROM=no-reply@review360.id.vn
MAIL_FROM_NAME=VideoDubing AI

VIDEODUBING_API_URL=http://127.0.0.1:3000/api/v1
VIDEODUBING_WEB_URL=https://videotool.review360.id.vn
TRIAL_DAYS=30
```

### PHP session storage

The FrontEnd explicitly uses a shared session directory so `/workspace.php` and `/ajax/api.php` can share the same login session even when the server has multiple PHP-FPM versions/pools.

Default:

```text
/tmp/videodubing-ai-sessions
```

The path can be overridden with:

```text
SESSION_SAVE_PATH=/path/to/shared/session-directory
```

The application creates the directory automatically when possible. If a custom path is used, make sure the PHP-FPM user can read and write it.

## 4. Create the first administrator

Run from `FrontEnd/` on the server:

```bash
php bin/create_admin.php "System Administrator" admin@example.com 'ChangeThisPassword!'
```

Use a strong production password. The script is CLI-only and refuses to overwrite an existing account.

## 5. URLs

- Public landing page: `/`
- Login: `/login.php`
- Register: `/register.php`
- Workspace: `/workspace.php`
- Admin portal: `/admin.php`

Only users with `role=admin` can open the Admin portal.

## 6. Admin functions

The Admin portal currently supports:

- Dashboard totals
- Create User
- Default trial period for manually created users
- Extend expiry by 7/30/90/180/365 days
- Change user password
- Block/unblock user
- View status, expiry and last login

## 7. Account flow

Registration creates an inactive/unverified account with no expiry. An activation token is sent by SMTP. After activation, the configured `TRIAL_DAYS` period starts.

Password reset uses a separate one-time token with a configurable expiry.
