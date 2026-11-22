<?php
declare(strict_types=1);

/**
 * SePay Live Webhook endpoint.
 * Payment ownership: FrontEnd PHP + MySQL only.
 */

header('Content-Type: application/json; charset=utf-8');

function respond(int $status, array $payload): void
{
    if ($status >= 400) {
        require_once __DIR__ . '/lib/payment-error-log.php';
        record_payment_error([
            'provider' => 'sepay',
            'action' => 'webhook',
            'http_status' => $status,
            'error_type' => $status === 401 ? 'SIGNATURE_ERROR' : 'WEBHOOK_REQUEST_ERROR',
            'error_message' => (string)($payload['message'] ?? 'SePay webhook error'),
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
$sepayId = null;
$code = null;

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        respond(405, ['success' => false, 'message' => 'Method Not Allowed']);
    }

    require_once __DIR__ . '/lib/payment-error-log.php';

    $config = require __DIR__ . '/config/config.php';
    $paymentConfig = $config['payment'];

    $secret = (string)($paymentConfig['sepay_webhook_secret'] ?? '');
    if ($secret === '') {
        record_payment_error([
            'provider' => 'sepay',
            'action' => 'webhook',
            'error_type' => 'CONFIGURATION_ERROR',
            'error_message' => 'SEPAY_WEBHOOK_SECRET is not configured',
        ]);
        respond(500, ['success' => false, 'message' => 'Webhook is not configured']);
    }

    $body = file_get_contents('php://input');
    if ($body === false || $body === '') {
        respond(400, ['success' => false, 'message' => 'Empty body']);
    }

    $signature = trim((string)($_SERVER['HTTP_X_SEPAY_SIGNATURE'] ?? ''));
    $timestamp = (int)($_SERVER['HTTP_X_SEPAY_TIMESTAMP'] ?? 0);

    if ($timestamp <= 0 || abs(time() - $timestamp) > 300) {
        respond(401, ['success' => false, 'message' => 'Request expired']);
    }

    $expectedSignature = 'sha256=' . hash_hmac(
        'sha256',
        $timestamp . '.' . $body,
        $secret
    );

    if ($signature === '' || !hash_equals($expectedSignature, $signature)) {
        respond(401, ['success' => false, 'message' => 'Invalid signature']);
    }

    $data = json_decode($body, true);
    if (!is_array($data)) {
        respond(400, ['success' => false, 'message' => 'Invalid JSON']);
    }

    $required = [
        'id',
        'gateway',
        'transactionDate',
        'accountNumber',
        'content',
        'transferType',
        'transferAmount',
    ];

    foreach ($required as $field) {
        if (!array_key_exists($field, $data)) {
            respond(400, ['success' => false, 'message' => 'Missing field: ' . $field]);
        }
    }

    $sepayId = (int)$data['id'];
    $gateway = strtoupper(trim((string)$data['gateway']));
    $transactionDate = trim((string)$data['transactionDate']);
    $accountNumber = trim((string)$data['accountNumber']);
    $subAccount = isset($data['subAccount']) && $data['subAccount'] !== null ? trim((string)$data['subAccount']) : null;
    $code = isset($data['code']) && $data['code'] !== null ? strtoupper(trim((string)$data['code'])) : null;
    $content = (string)$data['content'];
    $description = isset($data['description']) ? (string)$data['description'] : null;
    $transferType = strtolower(trim((string)$data['transferType']));
    $transferAmount = (int)$data['transferAmount'];
    $accumulated = isset($data['accumulated']) ? (int)$data['accumulated'] : null;
    $referenceCode = isset($data['referenceCode']) ? trim((string)$data['referenceCode']) : null;

    if ($sepayId <= 0 || $gateway === '' || $accountNumber === '' || $transferAmount <= 0) {
        respond(400, ['success' => false, 'message' => 'Invalid transaction data']);
    }

    if (!in_array($transferType, ['in', 'out'], true)) {
        respond(400, ['success' => false, 'message' => 'Invalid transfer type']);
    }

    if ($transferType !== 'in') {
        record_payment_error([
            'provider' => 'sepay',
            'action' => 'webhook_event',
            'error_type' => 'NON_INCOMING_TRANSFER',
            'error_message' => 'Only incoming transfers can activate a payment order.',
            'provider_transaction_id' => (string)$sepayId,
            'context' => ['transfer_type' => $transferType],
        ]);
        respond(200, ['success' => true]);
    }

    $expectedGateway = strtoupper(trim((string)($paymentConfig['sepay_bank_gateway'] ?? 'BIDV')));
    if ($expectedGateway !== '' && $gateway !== $expectedGateway) {
        record_payment_error([
            'provider' => 'sepay',
            'action' => 'webhook',
            'error_type' => 'GATEWAY_MISMATCH',
            'error_message' => 'SePay gateway does not match the configured payment gateway.',
            'provider_transaction_id' => (string)$sepayId,
            'context' => ['expected' => $expectedGateway, 'received' => $gateway],
        ]);
        respond(200, ['success' => true]);
    }

    $expectedAccount = trim((string)($paymentConfig['sepay_bank_account'] ?? ''));
    if ($expectedAccount !== '' && $accountNumber !== $expectedAccount) {
        record_payment_error([
            'provider' => 'sepay',
            'action' => 'webhook',
            'error_type' => 'ACCOUNT_MISMATCH',
            'error_message' => 'SePay account number does not match the configured account.',
            'provider_transaction_id' => (string)$sepayId,
        ]);
        respond(200, ['success' => true]);
    }

    $expectedSubAccount = trim((string)($paymentConfig['sepay_bank_sub_account'] ?? ''));
    if ($expectedSubAccount !== '' && $subAccount !== $expectedSubAccount) {
        record_payment_error([
            'provider' => 'sepay',
            'action' => 'webhook',
            'error_type' => 'SUB_ACCOUNT_MISMATCH',
            'error_message' => 'SePay virtual account does not match the configured account.',
            'provider_transaction_id' => (string)$sepayId,
        ]);
        respond(200, ['success' => true]);
    }

    $orderPrefix = strtoupper(trim((string)($paymentConfig['order_prefix'] ?? 'DH')));
    $orderDigits = max(1, (int)($paymentConfig['order_digits'] ?? 10));

    if ($code === null || $code === '') {
        record_payment_error([
            'provider' => 'sepay',
            'action' => 'webhook',
            'error_type' => 'MISSING_PAYMENT_CODE',
            'error_message' => 'SePay transaction does not contain a payment order code.',
            'provider_transaction_id' => (string)$sepayId,
        ]);
        respond(200, ['success' => true]);
    }

    if (!preg_match('/^' . preg_quote($orderPrefix, '/') . '\\d{' . $orderDigits . '}$/', $code)) {
        record_payment_error([
            'order_code' => $code,
            'provider' => 'sepay',
            'action' => 'webhook',
            'error_type' => 'INVALID_PAYMENT_CODE',
            'error_message' => 'SePay payment code does not match the configured order format.',
            'provider_transaction_id' => (string)$sepayId,
        ]);
        respond(200, ['success' => true]);
    }

    $dbConfig = $config['db'];
    if ($dbConfig['name'] === '' || $dbConfig['user'] === '') {
        throw new RuntimeException('Database configuration is incomplete');
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=%s',
        $dbConfig['host'],
        $dbConfig['port'],
        $dbConfig['name'],
        $dbConfig['charset']
    );

    $pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $pdo->beginTransaction();

    $insert = $pdo->prepare(
        'INSERT IGNORE INTO sepay_transactions
        (sepay_id, gateway, transaction_date, account_number, sub_account, code,
         transfer_type, transfer_amount, accumulated, content, description, reference_code, raw_payload)
        VALUES (:sepay_id, :gateway, :transaction_date, :account_number, :sub_account, :code,
                :transfer_type, :transfer_amount, :accumulated, :content, :description, :reference_code, :raw_payload)'
    );

    $insert->execute([
        'sepay_id' => $sepayId,
        'gateway' => $gateway,
        'transaction_date' => $transactionDate,
        'account_number' => $accountNumber,
        'sub_account' => $subAccount,
        'code' => $code,
        'transfer_type' => $transferType,
        'transfer_amount' => $transferAmount,
        'accumulated' => $accumulated,
        'content' => $content,
        'description' => $description,
        'reference_code' => $referenceCode,
        'raw_payload' => $body,
    ]);

    if ($insert->rowCount() === 0) {
        $pdo->commit();
        respond(200, ['success' => true]);
    }

    $orderStmt = $pdo->prepare(
        'SELECT id, user_id, plan_code, days, amount, status, payment_expires_at
         FROM payment_orders
         WHERE order_code = :order_code
         LIMIT 1
         FOR UPDATE'
    );
    $orderStmt->execute(['order_code' => $code]);
    $order = $orderStmt->fetch();

    if (!$order) {
        $pdo->commit();
        record_payment_error([
            'order_code' => $code,
            'provider' => 'sepay',
            'action' => 'webhook',
            'error_type' => 'UNKNOWN_ORDER',
            'error_message' => 'SePay webhook references an unknown payment order.',
            'provider_transaction_id' => (string)$sepayId,
            'duration_ms' => (int)round((microtime(true) - $webhookStartedAt) * 1000),
        ]);
        respond(200, ['success' => true]);
    }

    if ($order['status'] !== 'pending') {
        $pdo->commit();
        respond(200, ['success' => true]);
    }

    if (!empty($order['payment_expires_at']) && strtotime((string)$order['payment_expires_at']) <= time()) {
        $pdo->prepare("UPDATE payment_orders SET status='expired' WHERE id=? AND status='pending'")
            ->execute([(int)$order['id']]);
        $pdo->commit();
        respond(200, ['success' => true]);
    }

    if ($transferAmount !== (int)$order['amount']) {
        $pdo->commit();
        record_payment_error([
            'payment_order_id' => (int)$order['id'],
            'order_code' => $code,
            'user_id' => (int)$order['user_id'],
            'provider' => 'sepay',
            'action' => 'webhook',
            'error_type' => 'AMOUNT_MISMATCH',
            'error_message' => 'SePay transfer amount does not match the payment order.',
            'provider_transaction_id' => (string)$sepayId,
            'duration_ms' => (int)round((microtime(true) - $webhookStartedAt) * 1000),
            'context' => ['expected_amount' => (int)$order['amount'], 'received_amount' => $transferAmount],
        ]);
        respond(200, ['success' => true]);
    }

    $userStmt = $pdo->prepare(
        'SELECT id, expires_at FROM users WHERE id = :id LIMIT 1 FOR UPDATE'
    );
    $userStmt->execute(['id' => (int)$order['user_id']]);
    $user = $userStmt->fetch();

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

    $updateUser = $pdo->prepare('UPDATE users SET expires_at = :expires_at WHERE id = :id');
    $updateUser->execute([
        'expires_at' => $newExpiry->format('Y-m-d H:i:s'),
        'id' => (int)$order['user_id'],
    ]);

    $updateOrder = $pdo->prepare(
        'UPDATE payment_orders
         SET status = \'paid\', sepay_transaction_id = :sepay_id,
             paid_at = NOW(), expires_at = :expires_at
         WHERE id = :id AND status = \'pending\''
    );
    $updateOrder->execute([
        'sepay_id' => $sepayId,
        'expires_at' => $newExpiry->format('Y-m-d H:i:s'),
        'id' => (int)$order['id'],
    ]);

    if ($updateOrder->rowCount() !== 1) {
        throw new RuntimeException('Failed to mark payment order as paid');
    }

    $pdo->commit();
    respond(200, ['success' => true]);
} catch (Throwable $e) {
    if ($pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    record_payment_error([
        'payment_order_id' => isset($order['id']) ? (int)$order['id'] : null,
        'order_code' => $code,
        'user_id' => isset($order['user_id']) ? (int)$order['user_id'] : null,
        'provider' => 'sepay',
        'action' => 'webhook',
        'error_type' => 'EXCEPTION',
        'error_message' => $e->getMessage(),
        'provider_transaction_id' => $sepayId !== null ? (string)$sepayId : null,
        'duration_ms' => (int)round((microtime(true) - $webhookStartedAt) * 1000),
    ]);

    error_log('SePay webhook error: ' . $e->getMessage());
    respond(500, ['success' => false, 'message' => 'Internal server error']);
}
