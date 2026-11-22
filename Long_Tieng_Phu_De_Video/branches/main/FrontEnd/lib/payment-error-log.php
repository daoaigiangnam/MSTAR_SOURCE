<?php
declare(strict_types=1);

/** Failure-only payment diagnostics. Never stores API keys, checksum keys or auth tokens. */

function payment_error_db(): PDO
{
    if (function_exists('db')) return db();
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $pdo = require __DIR__ . '/../config/database.php';
    return $pdo;
}

function payment_error_uuid(): string
{
    $bytes = random_bytes(16);
    $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
    $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
}

function record_payment_error(array $data): ?string
{
    try {
        $traceId = (string)($data['trace_id'] ?? payment_error_uuid());
        $context = is_array($data['context'] ?? null) ? $data['context'] : [];
        $context['failure_recorded_at'] = (new DateTimeImmutable('now'))->format('Y-m-d H:i:s.u');

        $stmt = payment_error_db()->prepare(
            'INSERT INTO payment_error_logs
            (trace_id,payment_order_id,order_code,user_id,provider,action,endpoint,http_status,
             error_type,error_code,error_message,response_body,provider_transaction_id,started_at,
             failed_at,duration_ms,curl_errno,curl_error,context_json)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)'
        );

        $stmt->execute([
            $traceId,
            isset($data['payment_order_id']) ? (int)$data['payment_order_id'] : null,
            $data['order_code'] ?? null,
            isset($data['user_id']) ? (int)$data['user_id'] : null,
            (string)($data['provider'] ?? 'unknown'),
            $data['action'] ?? null,
            $data['endpoint'] ?? null,
            isset($data['http_status']) ? (int)$data['http_status'] : null,
            $data['error_type'] ?? null,
            $data['error_code'] ?? null,
            $data['error_message'] ?? null,
            $data['response_body'] ?? null,
            $data['provider_transaction_id'] ?? null,
            $data['started_at'] ?? null,
            $data['failed_at'] ?? (new DateTimeImmutable('now'))->format('Y-m-d H:i:s.u'),
            isset($data['duration_ms']) ? (int)$data['duration_ms'] : null,
            isset($data['curl_errno']) ? (int)$data['curl_errno'] : null,
            $data['curl_error'] ?? null,
            json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE),
        ]);

        return $traceId;
    } catch (Throwable $e) {
        error_log('Payment error log write failed: ' . $e->getMessage());
        return null;
    }
}
