<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';

/**
 * Paid plans are kept in one place so pricing.php and payment-create.php
 * always use the same code, price and subscription duration.
 */
function payment_plans(): array
{
    return [
        'basic' => [
            'code' => 'basic',
            'name' => 'Cơ bản',
            'days' => 30,
            'price' => 99000,
            'label' => '99.000đ',
        ],
        'standard' => [
            'code' => 'standard',
            'name' => 'Tiêu chuẩn',
            'days' => 90,
            'price' => 249000,
            'label' => '249.000đ',
        ],
        'pro' => [
            'code' => 'pro',
            'name' => 'Chuyên nghiệp',
            'days' => 180,
            'price' => 449000,
            'label' => '449.000đ',
        ],
        'premium' => [
            'code' => 'premium',
            'name' => 'Premium',
            'days' => 365,
            'price' => 799000,
            'label' => '799.000đ',
        ],
    ];
}

function payment_plan(string $code): ?array
{
    $plans = payment_plans();
    return $plans[$code] ?? null;
}

function payment_json(array $payload, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function payment_csrf_from_request(): string
{
    $header = trim((string)($_SERVER['HTTP_X_CSRF_TOKEN'] ?? ''));
    if ($header !== '') return $header;
    return trim((string)($_POST['csrf_token'] ?? ''));
}

function payment_generate_order_code(PDO $pdo, string $prefix, int $digits): string
{
    $digits = max(6, min(18, $digits));
    $max = (10 ** $digits) - 1;

    for ($i = 0; $i < 10; $i++) {
        $number = str_pad((string)random_int(0, $max), $digits, '0', STR_PAD_LEFT);
        $code = $prefix . $number;

        $stmt = $pdo->prepare('SELECT id FROM payment_orders WHERE order_code = ? LIMIT 1');
        $stmt->execute([$code]);
        if (!$stmt->fetchColumn()) return $code;
    }

    throw new RuntimeException('Unable to generate a unique payment order code');
}

function payment_qr_url(string $account, string $bank, int $amount, string $orderCode): string
{
    return 'https://vietqr.app/img?' . http_build_query([
        'acc' => $account,
        'bank' => $bank,
        'amount' => $amount,
        'des' => $orderCode,
    ], '', '&', PHP_QUERY_RFC3986);
}

function payment_public_order(array $order): array
{
    return [
        'order_code' => (string)$order['order_code'],
        'plan_code' => (string)$order['plan_code'],
        'plan_name' => (string)$order['plan_name'],
        'days' => (int)$order['days'],
        'amount' => (int)$order['amount'],
        'status' => (string)$order['status'],
        'payment_expires_at' => $order['payment_expires_at'] ?? null,
        'qr_url' => $order['qr_url'] ?? null,
        'bank' => $order['bank'] ?? null,
        'account_number' => $order['account_number'] ?? null,
        'transfer_content' => (string)$order['order_code'],
        'paid_at' => $order['paid_at'] ?? null,
        'subscription_expires_at' => $order['expires_at'] ?? null,
    ];
}
