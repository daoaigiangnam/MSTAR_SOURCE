<?php
declare(strict_types=1);
require_once __DIR__ . '/lib/auth.php';
if (current_user()) { header('Location: workspace.php'); exit; }
$error = '';
$notice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf($_POST['csrf_token'] ?? null);
    $name = trim((string)($_POST['name'] ?? ''));
    $phone = trim((string)($_POST['phone'] ?? ''));
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $pass = (string)($_POST['password'] ?? '');
    $confirm = (string)($_POST['confirm_password'] ?? '');

    if ($name === '' || $phone === '' || $email === '') {
        $error = 'Vui lòng nhập đầy đủ Họ tên, Số điện thoại và Email.';
    } elseif (!preg_match('/^[0-9+() .-]{8,20}$/', $phone)) {
        $error = 'Số điện thoại không hợp lệ.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ.';
    } elseif (strlen($pass) < 8) {
        $error = 'Mật khẩu phải có ít nhất 8 ký tự.';
    } elseif ($pass !== $confirm) {
        $error = 'Mật khẩu nhập lại không khớp.';
    } else {
        try {
            $q = db()->prepare('SELECT id FROM users WHERE phone=? OR (email IS NOT NULL AND email=?) LIMIT 1');
            $q->execute([$phone, $email]);
            $existing = $q->fetch();

            if ($existing) {
                $error = 'Số điện thoại hoặc Email này đã được đăng ký.';
            } else {
                db()->beginTransaction();

                $q = db()->prepare("INSERT INTO users(name,phone,email,password_hash,status,role,expires_at,email_verified_at) VALUES(?,?,?,?,?,?,NULL,NULL)");
                $q->execute([
                    $name,
                    $phone,
                    $email,
                    password_hash($pass, PASSWORD_DEFAULT),
                    'pending',
                    'user'
                ]);

                $userId = (int)db()->lastInsertId();
                $token = new_token();
                save_token($userId, 'activation', $token, (int)app_config()['activation_token_minutes']);
                db()->commit();

                $activationUrl = rtrim((string)app_config()['public_base_url'], '/') . '/activate.php?token=' . urlencode($token);
                $minutes = (int)app_config()['activation_token_minutes'];
                $body = "Xin chào {$name},\n\n"
                    . "Cảm ơn bạn đã đăng ký tài khoản VideoDubing AI.\n\n"
                    . "Vui lòng nhấn vào liên kết bên dưới để kích hoạt tài khoản:\n"
                    . "{$activationUrl}\n\n"
                    . "Liên kết có hiệu lực trong {$minutes} phút.\n\n"
                    . "Sau khi kích hoạt, bạn có thể đăng nhập bằng Số điện thoại hoặc Email.\n\n"
                    . "Trân trọng,\nVideoDubing AI";

                if (send_account_mail($email, 'Kích hoạt tài khoản VideoDubing AI', $body)) {
                    header('Location: login.php?registered=1');
                    exit;
                }

                $notice = 'Tài khoản đã được tạo. Tuy nhiên hệ thống chưa gửi được Email kích hoạt. Vui lòng kiểm tra cấu hình SMTP hoặc gửi lại Email kích hoạt.';
            }
        } catch (Throwable $e) {
            if (db()->inTransaction()) db()->rollBack();
            error_log('VideoDubing registration error: ' . $e->getMessage());
            $error = 'Không thể tạo tài khoản. Kiểm tra cấu hình MySQL/SMTP.';
        }
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Đăng ký - VideoDubing AI</title>
<link rel="stylesheet" href="assets/css/app.css">
</head>
<body>
<main class="auth-page">
<section class="auth-card">
<a class="auth-brand" href="index.php">▶ <strong>VideoDubing AI</strong></a>
<h1>Tạo tài khoản miễn phí</h1>
<p class="hint">Đăng ký bằng <strong>Họ tên + Số điện thoại + Email + Mật khẩu</strong>. Sau khi đăng ký, hệ thống sẽ gửi Email để kích hoạt tài khoản.</p>
<?php if($notice):?><div class="alert ok"><?=htmlspecialchars($notice)?></div><?php endif;?>
<?php if($error):?><div class="alert error"><?=htmlspecialchars($error)?></div><?php endif;?>
<form method="post">
<input type="hidden" name="csrf_token" value="<?=htmlspecialchars(csrf_token())?>">
<div class="field"><label>Họ tên</label><input class="input" name="name" required value="<?=htmlspecialchars((string)($_POST['name']??''))?>"></div>
<div class="field"><label>Số điện thoại / Zalo</label><input class="input" type="tel" name="phone" required value="<?=htmlspecialchars((string)($_POST['phone']??''))?>"></div>
<div class="field"><label>Email nhận kích hoạt</label><input class="input" type="email" name="email" required autocomplete="email" value="<?=htmlspecialchars((string)($_POST['email']??''))?>" placeholder="you@example.com"></div>
<div class="field"><label>Mật khẩu</label><input class="input" type="password" name="password" minlength="8" required autocomplete="new-password"></div>
<div class="field"><label>Nhập lại mật khẩu</label><input class="input" type="password" name="confirm_password" minlength="8" required autocomplete="new-password"></div>
<button class="primary" style="width:100%">ĐĂNG KÝ & NHẬN EMAIL KÍCH HOẠT</button>
</form>
<p class="auth-bottom">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
</section>
</main>
</body>
</html>
