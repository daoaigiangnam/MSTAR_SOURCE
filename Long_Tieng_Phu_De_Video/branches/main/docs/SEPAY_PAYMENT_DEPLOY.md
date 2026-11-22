# VideoDubing AI - SePay Payment Deployment

## Architecture

Payment is handled entirely by the FrontEnd PHP + MySQL.

- FrontEnd PHP: user, order, QR, payment status, SePay webhook, subscription expiry.
- MySQL: `users`, `payment_orders`, `sepay_transactions`.
- Node/Video API: video processing only; no payment code or payment database.

## Public webhook

`https://videotool.review360.id.vn/sepay-webhook.php`

## New files

### 1. `FrontEnd/lib/payment.php`
Shared payment functions:
- paid plan definitions
- secure order-code generation (`DH` + configured digits)
- VietQR URL generation
- payment JSON response helper
- payment CSRF helper

### 2. `FrontEnd/payment-create.php`
Authenticated endpoint that:
- validates the selected plan server-side
- creates a `pending` order in MySQL
- generates a unique payment code
- creates a dynamic VietQR URL
- returns order/payment information to the checkout page

### 3. `FrontEnd/payment-status.php`
Authenticated endpoint that:
- returns only the logged-in user's own order
- expires an unpaid order after the configured timeout
- returns `pending`, `paid`, or `expired`

### 4. `FrontEnd/checkout.php`
Payment UI:
- displays package and amount
- creates the order
- displays QR
- shows payment code
- polls payment status every 3 seconds
- redirects to the workspace after successful payment

### 5. `FrontEnd/sepay-webhook.php`
Production webhook:
- HMAC-SHA256 verification
- replay protection
- BIDV + VA validation
- payment-code validation
- exact amount validation
- idempotency by SePay transaction ID
- MySQL transaction + row locks
- extends `users.expires_at`
- changes order `pending` -> `paid`

## Existing file changes

### `FrontEnd/pricing.php`
Paid package buttons now open `checkout.php?plan=...` instead of the manual contact/receipt flow. Package names, prices and durations are unchanged.

### `FrontEnd/config/config.php`
Adds:
- `SEPAY_BANK_SUB_ACCOUNT`
- `PAYMENT_EXPIRE_MINUTES` (default 15)

The webhook secret is still read from `SEPAY_WEBHOOK_SECRET` and is never stored in Git.

## Database

Fresh installation:

`FrontEnd/database/20260817_create_payment_tables.sql`

If the original payment migration was already executed before the final schema update:

`FrontEnd/database/20260817_upgrade_payment_tables.sql`

Existing tables `users` and `account_tokens` are not recreated.

## Server environment

Set these values in the FrontEnd server environment:

```env
SEPAY_WEBHOOK_SECRET=YOUR_SEPAY_SECRET
SEPAY_BANK_GATEWAY=BIDV
SEPAY_BANK_ACCOUNT=YOUR_BIDV_ACCOUNT
SEPAY_BANK_SUB_ACCOUNT=YOUR_BIDV_OFFICIAL_VA
PAYMENT_ORDER_PREFIX=DH
PAYMENT_ORDER_DIGITS=10
PAYMENT_EXPIRE_MINUTES=15
```

Do not commit the real Secret Key or production credentials to GitHub.

## SePay webhook configuration

- Event: incoming money
- Bank: the linked BIDV account
- VA: the official BIDV VA used for payment
- Verify payment: enabled
- Skip transactions without payment code: enabled
- Payment-code prefix: `DH`
- Content-Type: JSON
- Retry: enabled
- Authentication: HMAC-SHA256
- Alert after 3 consecutive failures: enabled

## QR

The checkout uses SePay's documented VietQR URL format. For an official BIDV VA, the VA number is used as the QR `acc` value, with the order code in `des`.

## Deployment order

1. Deploy the new/changed FrontEnd files.
2. Run the payment database migration.
3. Set the production environment variables.
4. Verify `https://videotool.review360.id.vn/sepay-webhook.php` is reachable over HTTPS.
5. Use SePay's **Test send** from the webhook menu and verify HTTP 200 + `{"success":true}`.
6. Log in to VideoDubing AI and create a real payment order.
7. Scan the QR and make a small real payment.
8. Verify `payment_orders.status = paid`, a row exists in `sepay_transactions`, and `users.expires_at` was extended once.

A SePay test-send payload is not a real payment and must not be used as proof of end-to-end payment activation; perform a small real transaction after the endpoint test.
