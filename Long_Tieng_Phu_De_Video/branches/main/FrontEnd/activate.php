<?php
declare(strict_types=1);
require_once __DIR__ . '/lib/auth.php';
$token = trim((string)($_GET['token'] ?? ''));
$ok = false;
$error = '';
if ($token !== '') {
    try {
        $row = consume_token($token, 'activation');
        if (!$row) {
            $error = 'Link kích hoạt không hợp lệ hoặc đã hết hạn.';
        } else {
            $days = max(1, (int)app_config()['trial_days']);
            db()->prepare("UPDATE users SET email_verified_at=NOW(),expires_at=DATE_ADD(NOW(),INTERVAL {$days} DAY),status='active' WHERE id=?")
                ->execute([$row['user_id']]);
            $ok = true;
        }
    } catch (Throwable $e) { $error = 'Không thể kích hoạt tài khoản. Kiểm tra cấu hình MySQL.'; }
}
?><!doctype html><html lang="vi"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Kích hoạt tài khoản</title><link rel="stylesheet" href="assets/css/app.css"></head><body><main class="auth-page"><section class="auth-card"><a class="auth-brand" href="index.php">▶ <strong>VideoDubing AI</strong></a><?php if($ok):?><h1>Kích hoạt thành công 🎉</h1><p class="hint">Tài khoản đã được kích hoạt và có <strong><?=htmlspecialchars((string)app_config()['trial_days'])?> ngày dùng thử</strong>.</p><a class="primary auth-btn" href="login.php">Đăng nhập</a><?php else:?><h1>Không thể kích hoạt</h1><div class="alert error"><?=htmlspecialchars($error)?></div><a class="secondary auth-btn" href="register.php">Đăng ký lại</a><?php endif;?></section></main></body></html>
