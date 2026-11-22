<?php
declare(strict_types=1);

require_once __DIR__ . '/lib/payment.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    payment_json(['success' => false, 'message' => 'Method Not Allowed'], 405);
}

$user = require_login();

try {
    $code = strtoupper(trim((string)($_GET['code'] ?? '')));
    $config = app_config();
    $prefix = strtoupper(trim((string)($config['payment']['order_prefix'] ?? 'DH')));
    $digits = max(6, (int)($config['payment']['order_digits'] ?? 10));

    if (!preg_match('/^' . preg_quote($prefix, '/') . '\\d{' . $digits . '}$/', $code)) {
        payment_json(['success' => false, 'message' => 'Mã đơn hàng không hợp lệ.'], 422);
    }

    $stmt = db()->prepare(
        'SELECT id, order_code, plan_code, plan_name, days, amount, status,
                payment_expires_at, paid_at, expires_at
         FROM payment_orders
         WHERE order_code = ? AND user_id = ?
         LIMIT 1'
    );
    $stmt->execute([$code, (int)$user['id']]);
    $order = $stmt->fetch();

    if (!$order) {
        payment_json(['success' => false, 'message' => 'Không tìm thấy đơn thanh toán.'], 404);
    }

    if ($order['status'] === 'pending' && !empty($order['payment_expires_at']) && strtotime((string)$order['payment_expires_at']) <= time()) {
        $update = db()->prepare("UPDATE payment_orders SET status='expired' WHERE id=? AND status='pending'");
        $update->execute([(int)$order['id']]);
        $order['status'] = 'expired';
    }

    $paymentConfig = $config['payment'];
    $bank = strtoupper(trim((string)($paymentConfig['sepay_bank_gateway'] ?? 'BIDV')));
    $account = trim((string)($paymentConfig['sepay_bank_sub_account'] ?? ''));

    $order['qr_url'] = $account !== ''
        ? payment_qr_url($account, $bank, (int)$order['amount'], (string)$order['order_code'])
        : null;
    $order['bank'] = $bank;
    $order['account_number'] = $account;

    payment_json([
        'success' => true,
        'order' => payment_public_order($order),
    ]);
} catch (Throwable $e) {
    error_log('Payment status error: ' . $e->getMessage());
    payment_json(['success' => false, 'message' => 'Không thể kiểm tra trạng thái thanh toán.'], 500);
}
