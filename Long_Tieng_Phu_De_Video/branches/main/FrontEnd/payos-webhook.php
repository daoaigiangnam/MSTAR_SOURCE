<?php
declare(strict_types=1);

/**
 * payOS webhook endpoint.
 * Payment processing stays entirely in FrontEnd PHP + MySQL.
 * VideoDubing backend API is not involved.
 */

header('Content-Type: application/json; charset=utf-8');

function payos_webhook_response(int $status, array $payload): void
{
    if ($status >= 400) {
        require_once __DIR__ . '/lib/payment.php';
        require_once __DIR__ . '/lib/payment-error-log.php';
        record_payment_error([
            'provider' => 'payos',
            'action' => 'webhook',
            'http_status' => $status,
            'error_type' => $status === 401 ? 'SIGNATURE_ERROR' : 'WEBHOOK_REQUEST_ERROR',
            'error_message' => (string)($payload['message'] ?? 'payOS webhook error'),
            'context' => [
                'request_method' => $_SERVER['REQUEST_METHOD'] ?? null,
                'remote_addr' => $_SERVER['REMOTE_ADDR'] ?? null,
            ],
        ]);
    }

    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

$pdo = null;
$webhookStartedAt = microtime(true);
$orderCode = null;
$providerTransactionId = null;

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        payos_webhook_response(405, ['success' => false, 'message' => 'Method Not Allowed']);
    }

    require_once __DIR__ . '/lib/payment.php';
    require_once __DIR__ . '/lib/payos.php';
    require_once __DIR__ . '/lib/payment-error-log.php';

    $cfg = payos_assert_configured();
    $body = file_get_contents('php://input');
    if ($body === false || trim($body) === '') {
        payos_webhook_response(400, ['success' => false, 'message' => 'Empty body']);
    }

    $payload = json_decode($body, true);
    if (!is_array($payload)) {
        payos_webhook_response(400, ['success' => false, 'message' => 'Invalid JSON']);
    }

    $data = is_array($payload['data'] ?? null) ? $payload['data'] : [];
    $signature = trim((string)($payload['signature'] ?? ''));
    if ($signature === '' || $data === []) {
        payos_webhook_response(400, ['success' => false, 'message' => 'Missing webhook signature/data']);
    }

    $expected = payos_data_signature($data, $cfg['checksum_key']);
    if (!hash_equals($expected, $signature)) {
        payos_webhook_response(401, ['success' => false, 'message' => 'Invalid signature']);
    }

    $orderCode = trim((string)($data['orderCode'] ?? ''));
    $amount = (int)($data['amount'] ?? 0);
    $paymentLinkId = trim((string)($data['paymentLinkId'] ?? ''));
    $reference = trim((string)($data['reference'] ?? ''));
    $providerTransactionId = $reference !== '' ? $reference : null;
    $description = trim((string)($data['description'] ?? ''));

    // Provider can legitimately send a signed non-success event. Acknowledge it
    // but retain a failure trace so payment issues can be investigated later.
    if (($payload['success'] ?? false) !== true || (string)($data['code'] ?? '') !== '00') {
        record_payment_error([
            'order_code' => $orderCode !== '' ? $orderCode : null,
            'provider' => 'payos',
            'action' => 'webhook_event',
            'error_type' => 'PROVIDER_EVENT_ERROR',
            'error_code' => (string)($data['code'] ?? ''),
            'error_message' => (string)($data['desc'] ?? 'payOS reported a non-success payment event'),
            'provider_transaction_id' => $providerTransactionId,
            'duration_ms' => (int)round((microtime(true) - $webhookStartedAt) * 1000),
            'context' => [
                'orderCode' => $orderCode,
                'amount' => $amount,
                'paymentLinkId' => $paymentLinkId,
                'description' => $description,
            ],
        ]);
        payos_webhook_response(200, ['success' => true]);
    }

    if ($orderCode === '' || $amount <= 0) {
        payos_webhook_response(400, ['success' => false, 'message' => 'Invalid payment data']);
    }

    $pdo = db();
    $pdo->beginTransaction();

    $orderStmt = $pdo->prepare(
        'SELECT id,order_code,user_id,plan_code,days,amount,status,payment_expires_at,
                payment_provider,provider_order_id,payos_payment_link_id
         FROM payment_orders
         WHERE payment_provider="payos" AND provider_order_id=?
         LIMIT 1
         FOR UPDATE'
    );
    $orderStmt->execute([$orderCode]);
    $order = $orderStmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        $pdo->commit();
        record_payment_error([
            'order_code' => $orderCode,
            'provider' => 'payos',
            'action' => 'webhook',
            'error_type' => 'UNKNOWN_ORDER',
            'error_message' => 'payOS webhook references an unknown payment order.',
            'provider_transaction_id' => $providerTransactionId,
            'duration_ms' => (int)round((microtime(true) - $webhookStartedAt) * 1000),
            'context' => ['amount' => $amount, 'paymentLinkId' => $paymentLinkId],
        ]);
        payos_webhook_response(200, ['success' => true]);
    }

    if (!empty($order['payos_payment_link_id']) && $paymentLinkId !== '' &&
        !hash_equals((string)$order['payos_payment_link_id'], $paymentLinkId)) {
        $pdo->commit();
        record_payment_error([
            'payment_order_id' => (int)$order['id'],
            'order_code' => $orderCode,
            'user_id' => (int)$order['user_id'],
            'provider' => 'payos',
            'action' => 'webhook',
            'error_type' => 'PAYMENT_LINK_MISMATCH',
            'error_message' => 'payOS paymentLinkId does not match the persisted order.',
            'provider_transaction_id' => $providerTransactionId,
            'duration_ms' => (int)round((microtime(true) - $webhookStartedAt) * 1000),
            'context' => ['received_paymentLinkId' => $paymentLinkId],
        ]);
        payos_webhook_response(200, ['success' => true]);
    }

    if ((int)$order['amount'] !== $amount) {
        $pdo->commit();
        record_payment_error([
            'payment_order_id' => (int)$order['id'],
            'order_code' => $orderCode,
            'user_id' => (int)$order['user_id'],
            'provider' => 'payos',
            'action' => 'webhook',
            'error_type' => 'AMOUNT_MISMATCH',
            'error_message' => 'Webhook amount does not match the payment order.',
            'provider_transaction_id' => $providerTransactionId,
            'duration_ms' => (int)round((microtime(true) - $webhookStartedAt) * 1000),
            'context' => ['expected_amount' => (int)$order['amount'], 'received_amount' => $amount],
        ]);
        payos_webhook_response(200, ['success' => true]);
    }

    if ($order['status'] !== 'pending') {
        $pdo->commit();
        payos_webhook_response(200, ['success' => true]);
    }

    if (!empty($order['payment_expires_at']) && strtotime((string)$order['payment_expires_at']) <= time()) {
        $pdo->prepare("UPDATE payment_orders SET status='expired' WHERE id=? AND status='pending'")
            ->execute([(int)$order['id']]);
        $pdo->commit();
        payos_webhook_response(200, ['success' => true]);
    }

    $userStmt = $pdo->prepare('SELECT id,expires_at FROM users WHERE id=? LIMIT 1 FOR UPDATE');
    $userStmt->execute([(int)$order['user_id']]);
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        throw new RuntimeException('Payment order references missing user');
    }

    $now = new DateTimeImmutable('now');
    $currentExpiry = !empty($user['expires_at']) ? new DateTimeImmutable((string)$user['expires_at']) : null;
    $baseDate = ($currentExpiry !== null && $currentExpiry > $now) ? $currentExpiry : $now;
    $days = (int)$order['days'];
    if ($days <= 0) {
        throw new RuntimeException('Payment order has invalid duration');
    }
    $newExpiry = $baseDate->modify('+' . $days . ' days');

    $pdo->prepare('UPDATE users SET expires_at=? WHERE id=?')
        ->execute([$newExpiry->format('Y-m-d H:i:s'), (int)$order['user_id']]);

    $update = $pdo->prepare(
        'UPDATE payment_orders
         SET status="paid", paid_at=NOW(), expires_at=?, provider_transaction_id=?
         WHERE id=? AND status="pending"'
    );
    $update->execute([
        $newExpiry->format('Y-m-d H:i:s'),
        $reference !== '' ? $reference : null,
        (int)$order['id'],
    ]);

    if ($update->rowCount() !== 1) {
        throw new RuntimeException('Failed to mark payOS order as paid');
    }

    $pdo->commit();
    payos_webhook_response(200, ['success' => true]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    record_payment_error([
        'payment_order_id' => isset($order['id']) ? (int)$order['id'] : null,
        'order_code' => $orderCode,
        'user_id' => isset($order['user_id']) ? (int)$order['user_id'] : null,
        'provider' => 'payos',
        'action' => 'webhook',
        'error_type' => 'EXCEPTION',
        'error_message' => $e->getMessage(),
        'provider_transaction_id' => $providerTransactionId,
        'duration_ms' => (int)round((microtime(true) - $webhookStartedAt) * 1000),
    ]);

    error_log('payOS webhook error: ' . $e->getMessage());
    payos_webhook_response(500, ['success' => false, 'message' => 'Internal server error']);
}
