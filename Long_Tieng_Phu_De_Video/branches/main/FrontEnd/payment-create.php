<?php
declare(strict_types=1);

require_once __DIR__ . '/lib/payment.php';
require_once __DIR__ . '/lib/payment-provider.php';
require_once __DIR__ . '/lib/payos.php';
require_once __DIR__ . '/lib/payment-error-log.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    payment_json(['success' => false, 'message' => 'Method Not Allowed'], 405);
}

$user = require_login();
verify_csrf(payment_csrf_from_request());

$paymentStartedAt = microtime(true);
$provider = 'unknown';
$orderId = null;
$orderCode = null;

try {
    $planCode = strtolower(trim((string)($_POST['plan'] ?? $_POST['plan_code'] ?? '')));
    $plan = payment_plan($planCode);

    if (!$plan || (int)$plan['price'] <= 0) {
        record_payment_error([
            'provider' => 'unknown',
            'action' => 'create',
            'error_type' => 'INVALID_PLAN',
            'error_message' => 'Gói thanh toán không hợp lệ.',
            'user_id' => (int)$user['id'],
            'duration_ms' => (int)round((microtime(true) - $paymentStartedAt) * 1000),
            'context' => ['plan_code' => $planCode],
        ]);
        payment_json(['success' => false, 'message' => 'Gói thanh toán không hợp lệ.'], 422);
    }

    $config = app_config();
    $paymentConfig = $config['payment'];
    $pdo = db();
    $provider = payment_default_provider($pdo);

    $prefix = strtoupper(trim((string)($paymentConfig['order_prefix'] ?? 'DH')));
    $digits = max(6, (int)($paymentConfig['order_digits'] ?? 10));
    $paymentMinutes = max(5, min(60, (int)($paymentConfig['payment_expire_minutes'] ?? 15)));

    $orderCode = payment_generate_order_code($pdo, $prefix, $digits);
    $paymentExpiresAt = (new DateTimeImmutable('now'))
        ->modify('+' . $paymentMinutes . ' minutes')
        ->format('Y-m-d H:i:s');

    $stmt = $pdo->prepare(
        'INSERT INTO payment_orders
        (order_code, user_id, plan_code, plan_name, days, amount, payment_provider, status, payment_expires_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, \'pending\', ?)'
    );
    $stmt->execute([
        $orderCode,
        (int)$user['id'],
        $plan['code'],
        $plan['name'],
        (int)$plan['days'],
        (int)$plan['price'],
        $provider,
        $paymentExpiresAt,
    ]);

    $orderId = (int)$pdo->lastInsertId();

    if ($provider === 'sepay') {
        $bank = strtoupper(trim((string)($paymentConfig['sepay_bank_gateway'] ?? 'BIDV')));
        $account = trim((string)($paymentConfig['sepay_bank_sub_account'] ?? ''));
        if ($account === '') {
            throw new RuntimeException('Chưa cấu hình tài khoản VA thanh toán SePay.');
        }

        $qrUrl = payment_qr_url($account, $bank, (int)$plan['price'], $orderCode);
        payment_json([
            'success' => true,
            'message' => 'Đã tạo đơn thanh toán.',
            'provider' => 'sepay',
            'order' => [
                'id' => $orderId,
                'order_code' => $orderCode,
                'plan_code' => $plan['code'],
                'plan_name' => $plan['name'],
                'days' => (int)$plan['days'],
                'amount' => (int)$plan['price'],
                'status' => 'pending',
                'payment_expires_at' => $paymentExpiresAt,
                'qr_url' => $qrUrl,
                'bank' => $bank,
                'account_number' => $account,
                'transfer_content' => $orderCode,
            ],
        ]);
    }

    if ($provider === 'payos') {
        $payos = payos_create_payment_link([
            'id' => $orderId,
            'order_code' => $orderCode,
            'amount' => (int)$plan['price'],
        ]);

        $providerOrderId = isset($payos['orderCode']) ? (string)$payos['orderCode'] : (string)$orderId;
        $paymentLinkId = trim((string)($payos['paymentLinkId'] ?? ''));
        $update = $pdo->prepare('UPDATE payment_orders SET provider_order_id=?, payos_payment_link_id=? WHERE id=?');
        $update->execute([$providerOrderId, $paymentLinkId !== '' ? $paymentLinkId : null, $orderId]);

        payment_json([
            'success' => true,
            'message' => 'Đã tạo đơn thanh toán.',
            'provider' => 'payos',
            'order' => [
                'id' => $orderId,
                'order_code' => $orderCode,
                'plan_code' => $plan['code'],
                'plan_name' => $plan['name'],
                'days' => (int)$plan['days'],
                'amount' => (int)$plan['price'],
                'status' => 'pending',
                'payment_expires_at' => $paymentExpiresAt,
                'checkout_url' => $payos['checkoutUrl'] ?? null,
                'qr_code' => $payos['qrCode'] ?? null,
                'payment_link_id' => $paymentLinkId !== '' ? $paymentLinkId : null,
            ],
        ]);
    }

    throw new RuntimeException('Payment provider không được hỗ trợ.');
} catch (Throwable $e) {
    record_payment_error([
        'payment_order_id' => $orderId,
        'order_code' => $orderCode,
        'user_id' => (int)$user['id'],
        'provider' => $provider,
        'action' => 'create',
        'error_type' => 'EXCEPTION',
        'error_message' => $e->getMessage(),
        'duration_ms' => (int)round((microtime(true) - $paymentStartedAt) * 1000),
        'context' => [
            'plan_code' => $planCode ?? null,
        ],
    ]);

    error_log('Payment create error: ' . $e->getMessage());
    payment_json(['success' => false, 'message' => $e->getMessage() !== '' ? $e->getMessage() : 'Không thể tạo đơn thanh toán. Vui lòng thử lại.'], 500);
}
