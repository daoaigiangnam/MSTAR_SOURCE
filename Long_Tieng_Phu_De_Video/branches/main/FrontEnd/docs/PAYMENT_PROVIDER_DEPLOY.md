# Payment Provider Deployment

## Goal

Keep the existing SePay payment flow and add payOS as a second FrontEnd payment provider.

Users never select a provider. Admin enables providers and selects exactly one default provider for new orders.

## Database

Run once against the FrontEnd MySQL database:

```sql
SOURCE FrontEnd/database/20260818_add_payment_provider.sql;
```

The migration:

- adds `payment_provider` and `provider_order_id` to `payment_orders`;
- creates `payment_provider_settings`;
- registers `sepay` as enabled/default;
- registers `payos` as disabled/non-default.

Existing SePay orders stay associated with SePay.

## Environment

Do not commit real credentials.

Add to the FrontEnd server environment:

```env
PAYOS_CLIENT_ID=...
PAYOS_API_KEY=...
PAYOS_CHECKSUM_KEY=...
PAYOS_RETURN_URL=https://videotool.review360.id.vn/checkout.php
PAYOS_CANCEL_URL=https://videotool.review360.id.vn/pricing.php
```

Keep the existing SePay variables unchanged.

## Admin

Open:

`/admin-payment.php`

Enable payOS only after its credentials are configured. Set it as default when ready.

Changing the default affects new orders only. Existing orders keep their stored `payment_provider`.

## payOS webhook

Configure the payOS webhook URL as:

`https://videotool.review360.id.vn/payos-webhook.php`

## SePay

Do not remove or disable the existing `sepay-webhook.php`.

## Important

The VideoDubbing backend API and backend SQLite database are not changed by this payment-provider work. All payment processing remains in FrontEnd PHP + MySQL.
