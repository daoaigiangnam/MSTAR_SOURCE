# Failure Trace Logging

## Purpose

The FrontEnd records **failures only**. Successful video jobs, successful API calls and successful payments are not written to these diagnostic tables.

Two MySQL tables are used:

- `frontend_failure_logs`: VideoDubing/Workplace API failures, including server resource snapshot at failure time.
- `payment_error_logs`: SePay/payOS/payment workflow failures.

## Deployment

1. Pull the latest `main` branch.
2. Run:

```sql
SOURCE FrontEnd/database/20260819_create_failure_logs.sql;
```

or execute the SQL file in phpMyAdmin/Adminer/MySQL client.

3. Verify:

```sql
SHOW TABLES LIKE 'frontend_failure_logs';
SHOW TABLES LIKE 'payment_error_logs';
```

## Workplace failure trace

`FrontEnd/lib/api.php` now records failures from the FrontEnd API proxy, including:

- HTTP errors
- invalid JSON
- cURL errors
- timeout
- connection reset
- request/response timing
- Job ID when it can be identified
- API endpoint and operation
- server load average 1/5/15 minutes
- host RAM total/used/available
- disk total/free/free percentage
- PHP memory usage/peak
- hostname, PHP version and OS
- response body and diagnostic context

The resource snapshot is collected immediately when the failure is detected.

## Payment failure trace

Payment failures are written by:

- `payment-create.php`
- `payos-webhook.php`
- `sepay-webhook.php`

The logger intentionally does **not** store API keys, checksum keys, secrets or Authorization tokens.

Payment success is not logged.

## Useful queries

```sql
SELECT id,trace_id,job_id,module,action,http_status,error_type,error_message,
       failed_at,load_1,load_5,load_15,memory_used_bytes,disk_free_percent
FROM frontend_failure_logs
ORDER BY failed_at DESC
LIMIT 100;
```

```sql
SELECT id,trace_id,provider,action,order_code,payment_order_id,
       http_status,error_type,error_message,failed_at
FROM payment_error_logs
ORDER BY failed_at DESC
LIMIT 100;
```

## Important

The logger is diagnostic only. It does not change job status, payment status, subscription expiry or provider behavior. Existing SePay/payOS payment processing remains unchanged except that failures are recorded for traceability.
