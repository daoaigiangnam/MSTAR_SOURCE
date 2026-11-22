<?php
declare(strict_types=1);
require_once __DIR__ . '/lib/auth.php';
$token = trim((string)($_GET['token'] ?? $_POST['token'] ?? ''));
$error = '';
$ok = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf($_POST['csrf_token'] ?? null);
    $pass = (string)($_POST['password'] ?? '');
    $confirm = (string)($_POST['confirm_password'] ?? '');
    if (strlen($pass) < 8) $error = 'Mật khẩu phải có ít nhất 8 ký tự.';
    elseif ($pass !== $confirm) $error = 'Mật khẩu nhập lại không khớp.';
    else try {
        $row = consume_token($token, 'password_reset');
        if (!$row) $error = 'Link đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.';
        else { db()->prepare('UPDATE users SET password_hash=? WHERE id=?')->execute([password_hash($pass, PASSWORD_DEFAULT), $row['user_id']]); $ok = true; }
    } catch (Throwable $e) { $error = 'Không thể đổi mật khẩu. Kiểm tra cấu hình MySQL.'; }
}
?><!doctype html><html lang="vi"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Đặt lại mật khẩu</title><link rel="stylesheet" href="assets/css/app.css"></head><body><main class="auth-page"><section class="auth-card"><a class="auth-brand" href="index.php">▶ <strong>VideoDubing AI</strong></a><?php if($ok):?><h1>Đã đổi mật khẩu</h1><p class="hint">Mật khẩu mới đã được lưu.</p><a class="primary auth-btn" href="login.php">Đăng nhập</a><?php else:?><h1>Đặt lại mật khẩu</h1><?php if($error):?><div class="alert error"><?=htmlspecialchars($error)?></div><?php endif;?><form method="post"><input type="hidden" name="csrf_token" value="<?=htmlspecialchars(csrf_token())?>"><input type="hidden" name="token" value="<?=htmlspecialchars($token)?>"><div class="field"><label>Mật khẩu mới</label><input class="input" type="password" name="password" minlength="8" required></div><div class="field"><label>Nhập lại mật khẩu</label><input class="input" type="password" name="confirm_password" minlength="8" required></div><button class="primary" style="width:100%">ĐỔI MẬT KHẨU</button></form><?php endif;?></section></main></body></html>
