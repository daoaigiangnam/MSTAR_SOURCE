<?php
declare(strict_types=1);

require_once __DIR__ . '/payment.php';

function payment_default_provider(PDO $pdo): string
{
    $stmt = $pdo->query("SELECT provider_code FROM payment_provider_settings WHERE is_enabled=1 AND is_default=1 ORDER BY id LIMIT 1");
    $provider = strtolower(trim((string)$stmt->fetchColumn()));
    if ($provider !== '') {
        return $provider;
    }

    // Safe fallback: preserve the already-working SePay flow.
    return 'sepay';
}

function payment_provider_enabled(PDO $pdo, string $provider): bool
{
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM payment_provider_settings WHERE provider_code=? AND is_enabled=1');
    $stmt->execute([strtolower(trim($provider))]);
    return (int)$stmt->fetchColumn() === 1;
}

function payment_set_default_provider(PDO $pdo, string $provider): void
{
    $provider = strtolower(trim($provider));
    if (!in_array($provider, ['sepay', 'payos'], true)) {
        throw new InvalidArgumentException('Payment provider không hợp lệ.');
    }

    $pdo->beginTransaction();
    try {
        $lock = $pdo->prepare('SELECT id,is_enabled FROM payment_provider_settings WHERE provider_code=? LIMIT 1 FOR UPDATE');
        $lock->execute([$provider]);
        $row = $lock->fetch(PDO::FETCH_ASSOC);
        if (!$row || !(int)$row['is_enabled']) {
            throw new RuntimeException('Provider chưa được bật hoặc chưa cấu hình.');
        }

        $pdo->exec('UPDATE payment_provider_settings SET is_default=0');
        $stmt = $pdo->prepare('UPDATE payment_provider_settings SET is_default=1,updated_at=CURRENT_TIMESTAMP WHERE provider_code=?');
        $stmt->execute([$provider]);
        $pdo->commit();
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        throw $e;
    }
}

function payment_set_enabled(PDO $pdo, string $provider, bool $enabled): void
{
    $provider = strtolower(trim($provider));
    if (!in_array($provider, ['sepay', 'payos'], true)) {
        throw new InvalidArgumentException('Payment provider không hợp lệ.');
    }

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare('SELECT is_default FROM payment_provider_settings WHERE provider_code=? LIMIT 1 FOR UPDATE');
        $stmt->execute([$provider]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            throw new RuntimeException('Không tìm thấy provider.');
        }

        if (!$enabled && (int)$row['is_default'] === 1) {
            throw new RuntimeException('Không thể tắt provider mặc định. Hãy chọn provider khác làm mặc định trước.');
        }

        $update = $pdo->prepare('UPDATE payment_provider_settings SET is_enabled=?,updated_at=CURRENT_TIMESTAMP WHERE provider_code=?');
        $update->execute([$enabled ? 1 : 0, $provider]);
        $pdo->commit();
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        throw $e;
    }
}

function payment_provider_rows(PDO $pdo): array
{
    return $pdo->query('SELECT id,provider_code,provider_name,is_enabled,is_default,created_at,updated_at FROM payment_provider_settings ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
}

function payment_provider_client(PDO $pdo, string $provider): array
{
    $provider = strtolower(trim($provider));
    if ($provider === 'sepay') {
        return ['code' => 'sepay', 'name' => 'SePay'];
    }
    if ($provider === 'payos') {
        return ['code' => 'payos', 'name' => 'payOS'];
    }
    throw new InvalidArgumentException('Provider không được hỗ trợ.');
}
