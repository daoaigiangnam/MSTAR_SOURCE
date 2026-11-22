<?php
declare(strict_types=1);

function payos_config(): array
{
    // Payment credentials are kept in the existing application config.php.
    // Do not commit the actual credentials to Git.
    $app = app_config();
    $payment = (array)($app['payment'] ?? []);

    $clientId = trim((string)($payment['payos_client_id'] ?? ''));
    if ($clientId === '') $clientId = trim((string)getenv('PAYOS_CLIENT_ID'));

    $apiKey = trim((string)($payment['payos_api_key'] ?? ''));
    if ($apiKey === '') $apiKey = trim((string)getenv('PAYOS_API_KEY'));

    $checksumKey = trim((string)($payment['payos_checksum_key'] ?? ''));
    if ($checksumKey === '') $checksumKey = trim((string)getenv('PAYOS_CHECKSUM_KEY'));

    $returnUrl = trim((string)($payment['payos_return_url'] ?? ''));
    if ($returnUrl === '') $returnUrl = trim((string)getenv('PAYOS_RETURN_URL'));
    if ($returnUrl === '') $returnUrl = rtrim((string)($app['public_base_url'] ?? 'https://videotool.review360.id.vn'), '/') . '/checkout.php';

    $cancelUrl = trim((string)($payment['payos_cancel_url'] ?? ''));
    if ($cancelUrl === '') $cancelUrl = trim((string)getenv('PAYOS_CANCEL_URL'));
    if ($cancelUrl === '') $cancelUrl = rtrim((string)($app['public_base_url'] ?? 'https://videotool.review360.id.vn'), '/') . '/pricing.php';

    return [
        'client_id' => $clientId,
        'api_key' => $apiKey,
        'checksum_key' => $checksumKey,
        'return_url' => rtrim($returnUrl, '/'),
        'cancel_url' => rtrim($cancelUrl, '/'),
    ];
}

function payos_assert_configured(): array
{
    $cfg = payos_config();
    foreach (['client_id', 'api_key', 'checksum_key'] as $key) {
        if ($cfg[$key] === '') {
            throw new RuntimeException('payOS chưa được cấu hình đầy đủ trên Server.');
        }
    }
    return $cfg;
}

/**
 * Create signature for POST /v2/payment-requests.
 * payOS documents the canonical fields as:
 * amount, cancelUrl, description, orderCode, returnUrl sorted alphabetically.
 */
function payos_signature(array $data, string $checksumKey): string
{
    $fields = [
        'amount' => (string)($data['amount'] ?? ''),
        'cancelUrl' => (string)($data['cancelUrl'] ?? ''),
        'description' => (string)($data['description'] ?? ''),
        'orderCode' => (string)($data['orderCode'] ?? ''),
        'returnUrl' => (string)($data['returnUrl'] ?? ''),
    ];
    ksort($fields);

    $query = [];
    foreach ($fields as $key => $value) {
        $query[] = $key . '=' . $value;
    }
    return hash_hmac('sha256', implode('&', $query), $checksumKey);
}

/** Verify webhook data using payOS's documented data-signature algorithm. */
function payos_data_signature(array $data, string $checksumKey): string
{
    ksort($data);
    $parts = [];

    foreach ($data as $key => $value) {
        if ($value === null || $value === 'undefined' || $value === 'null') {
            $value = '';
        } elseif (is_array($value)) {
            $value = json_encode(
                array_map(
                    static function ($item) {
                        if (is_array($item)) ksort($item);
                        return $item;
                    },
                    $value
                ),
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
            if ($value === false) {
                throw new RuntimeException('Không thể encode payOS webhook data để xác thực chữ ký.');
            }
        } elseif (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        } else {
            $value = (string)$value;
        }

        $parts[] = $key . '=' . $value;
    }

    return hash_hmac('sha256', implode('&', $parts), $checksumKey);
}

function payos_request(string $method, string $path, ?array $body = null): array
{
    $cfg = payos_assert_configured();
    $url = 'https://api-merchant.payos.vn' . $path;
    $headers = [
        'Accept: application/json',
        'x-client-id: ' . $cfg['client_id'],
        'x-api-key: ' . $cfg['api_key'],
    ];

    $payload = null;
    if ($body !== null) {
        $payload = json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($payload === false) throw new RuntimeException('Không thể encode payOS request.');
        $headers[] = 'Content-Type: application/json';
    }

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => strtoupper($method),
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_POSTFIELDS => $payload,
    ]);
    $raw = curl_exec($ch);
    $errno = curl_errno($ch);
    $error = curl_error($ch);
    $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($errno !== 0 || $raw === false) {
        throw new RuntimeException('payOS connection failed: ' . ($error !== '' ? $error : 'unknown cURL error'));
    }

    $data = json_decode((string)$raw, true);
    if (!is_array($data)) {
        throw new RuntimeException('payOS trả về dữ liệu không hợp lệ.');
    }

    if ($status < 200 || $status >= 300 || (($data['code'] ?? '00') !== '00' && isset($data['code']))) {
        $message = (string)($data['desc'] ?? $data['message'] ?? 'payOS API error');
        throw new RuntimeException('payOS: ' . $message);
    }

    return $data;
}

function payos_create_payment_link(array $order): array
{
    $cfg = payos_assert_configured();
    $orderCode = (int)$order['id'];
    $amount = (int)$order['amount'];

    $description = 'VD' . str_pad((string)$orderCode, 7, '0', STR_PAD_LEFT);
    $description = substr($description, 0, 9);

    $returnUrl = $cfg['return_url'] . '?payment=success&code=' . rawurlencode((string)$order['order_code']);
    $cancelUrl = $cfg['cancel_url'] . '?payment=cancelled&code=' . rawurlencode((string)$order['order_code']);

    $expiredAt = !empty($order['payment_expires_at'])
        ? strtotime((string)$order['payment_expires_at'])
        : null;

    $body = [
        'orderCode' => $orderCode,
        'amount' => $amount,
        'description' => $description,
        'returnUrl' => $returnUrl,
        'cancelUrl' => $cancelUrl,
        'signature' => payos_signature([
            'amount' => $amount,
            'cancelUrl' => $cancelUrl,
            'description' => $description,
            'orderCode' => $orderCode,
            'returnUrl' => $returnUrl,
        ], $cfg['checksum_key']),
    ];

    if ($expiredAt !== false && $expiredAt !== null) {
        $body['expiredAt'] = (int)$expiredAt;
    }

    $response = payos_request('POST', '/v2/payment-requests', $body);
    $data = is_array($response['data'] ?? null) ? $response['data'] : [];
    if (($data['checkoutUrl'] ?? '') === '') {
        throw new RuntimeException('payOS không trả về checkoutUrl.');
    }

    return $data;
}
