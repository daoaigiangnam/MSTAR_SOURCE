<?php
declare(strict_types=1);
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/lib/mailer.php';

$user = current_user();
$plans = [
    'trial' => ['name'=>'Dùng thử','days'=>7,'price'=>0],
    'basic' => ['name'=>'Cơ bản','days'=>30,'price'=>99000],
    'standard' => ['name'=>'Tiêu chuẩn','days'=>90,'price'=>249000],
    'pro' => ['name'=>'Chuyên nghiệp','days'=>180,'price'=>449000],
    'premium' => ['name'=>'Premium','days'=>365,'price'=>799000],
];
$code = (string)($_GET['plan'] ?? $_POST['plan'] ?? '');
$plan = $plans[$code] ?? null;
if (!$plan && $code !== '') $code = '';

$name = (string)($user['name'] ?? $_POST['name'] ?? '');
$email = (string)($user['email'] ?? $_POST['email'] ?? '');
$phone = (string)($_POST['phone'] ?? '');
$subject = (string)($_POST['subject'] ?? ($plan ? 'Đề nghị nâng cấp gói '.$plan['name'] : ''));
$message = (string)($_POST['message'] ?? ($plan ? 'Tôi muốn nâng cấp tài khoản lên gói '.$plan['name'].' - '.$plan['days'].' ngày - '.number_format($plan['price'],0,',','.').'đ.' : ''));
$sent = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($name); $email = trim($email); $phone = trim($phone);
    $subject = trim($subject); $message = trim($message);
    $errors = [];

    if ($name === '') $errors[] = 'Vui lòng nhập Họ tên.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không hợp lệ.';
    if ($message === '') $errors[] = 'Vui lòng nhập Nội dung.';
    if (mb_strlen($name)>120 || mb_strlen($email)>190 || mb_strlen($phone)>40 || mb_strlen($subject)>200 || mb_strlen($message)>5000) {
        $errors[] = 'Nội dung liên hệ vượt quá giới hạn cho phép.';
    }

    $receipt = $_FILES['receipt'] ?? null;
    if ($plan && $plan['price'] > 0) {
        if (!is_array($receipt) || ($receipt['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            $errors[] = 'Vui lòng upload biên nhận chuyển tiền.';
        } else {
            $size = (int)($receipt['size'] ?? 0);
            $tmp = (string)($receipt['tmp_name'] ?? '');
            $originalName = basename((string)($receipt['name'] ?? ''));
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            if ($size <= 0) {
                $errors[] = 'Biên nhận không hợp lệ.';
            } elseif ($size > 5 * 1024 * 1024) {
                $errors[] = 'Biên nhận tối đa 5MB.';
            } elseif ($tmp === '' || !is_uploaded_file($tmp)) {
                $errors[] = 'Không xác thực được file biên nhận.';
            } elseif (!in_array($ext, ['jpg','jpeg','png','pdf'], true)) {
                $errors[] = 'Biên nhận chỉ hỗ trợ JPG, PNG hoặc PDF.';
            } else {
                $validReceipt = false;
                if (in_array($ext, ['jpg','jpeg','png'], true)) {
                    $imageInfo = @getimagesize($tmp);
                    $validReceipt = is_array($imageInfo) && in_array((int)($imageInfo[2] ?? 0), [IMAGETYPE_JPEG, IMAGETYPE_PNG], true);
                } elseif ($ext === 'pdf') {
                    $fh = @fopen($tmp, 'rb');
                    if ($fh) {
                        $signature = fread($fh, 5);
                        fclose($fh);
                        $validReceipt = ($signature === '%PDF-');
                    }
                }
                if (!$validReceipt) $errors[] = 'File biên nhận không đúng định dạng JPG, PNG hoặc PDF.';
            }
        }
    }

    if (!$errors) {
        $subjectMail = $subject !== '' ? '[VideoDubing AI] '.$subject : '[VideoDubing AI] Liên hệ hỗ trợ';
        $body = "Có yêu cầu mới từ Landing Page VideoDubing AI\n\n";
        if ($plan) {
            $body .= "Loại yêu cầu: Nâng cấp tài khoản\nGói: {$plan['name']}\nThời hạn: {$plan['days']} ngày\nGiá: ".number_format($plan['price'],0,',','.')."đ\n";
        } else {
            $body .= "Loại yêu cầu: Liên hệ hỗ trợ\n";
        }
        $body .= "\nHọ tên: {$name}\nEmail: {$email}\nSố điện thoại / Zalo: {$phone}\n";
        if ($user) $body .= "User ID: ".(int)$user['id']."\nRole: ".($user['role'] ?? 'user')."\nHạn hiện tại: ".($user['expires_at'] ?? '')."\n";
        $body .= "\nNội dung:\n{$message}\n\nThời gian: ".date('Y-m-d H:i:s')."\nIP: ".($_SERVER['REMOTE_ADDR'] ?? 'unknown');

        $ok = send_contact_mail('hotro@review360.id.vn', $subjectMail, $body, ($plan && $plan['price'] > 0) ? $receipt : null);
        if ($ok) {
            $sent = true;
            $phone = ''; $subject = ''; $message = '';
        } else {
            $error = 'Không thể gửi email lúc này. Vui lòng kiểm tra cấu hình SMTP hoặc thử lại sau.';
        }
    } else {
        $error = implode(' ', $errors);
    }
}

function h($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Liên hệ · VideoDubing AI</title>
<link rel="stylesheet" href="assets/css/landing.css">
<style>
.contact-page{min-height:100vh;background:linear-gradient(180deg,#fff,#faf9ff);color:#172033;font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif}.contact-wrap{max-width:1120px;margin:auto;padding:35px 28px 80px}.contact-nav{display:flex;justify-content:space-between;align-items:center;margin-bottom:55px}.contact-brand{display:flex;align-items:center;gap:10px;text-decoration:none;color:#172033;font-weight:850}.contact-logo{width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#6847e8,#8a6bfa);display:grid;place-items:center;color:#fff}.contact-back{color:#667085;text-decoration:none;font-size:13px;font-weight:700}.contact-grid{display:grid;grid-template-columns:.85fr 1.25fr;gap:55px;align-items:start}.contact-intro .eyebrow{display:inline-flex;padding:6px 11px;border:1px solid #ddd5ff;border-radius:999px;background:#f5f2ff;color:#6045d7;font-size:9px;font-weight:900;letter-spacing:.08em}.contact-intro h1{font-size:48px;line-height:1.05;letter-spacing:-.055em;margin:18px 0 14px}.contact-intro h1 span{color:#6947e8}.contact-intro>p{color:#667085;font-size:15px;line-height:1.75;max-width:480px}.contact-info{margin-top:30px;display:grid;gap:12px}.contact-info-card{padding:16px 18px;border:1px solid #e7e9f0;border-radius:14px;background:#fff;box-shadow:0 8px 25px rgba(25,30,55,.035)}.contact-info-card small{display:block;color:#98a2b3;font-size:11px;margin-bottom:4px}.contact-info-card strong,.contact-info-card a{color:#344054;text-decoration:none;font-size:14px}.contact-form{background:#fff;border:1px solid #e1e4eb;border-radius:22px;padding:30px;box-shadow:0 25px 70px rgba(62,45,150,.10)}.contact-form h2{margin:0 0 6px;font-size:24px}.contact-form>p{margin:0 0 18px;color:#98a2b3;font-size:12px}.contact-fields{display:grid;grid-template-columns:1fr 1fr;gap:15px}.contact-field.full{grid-column:1/-1}.contact-field label{display:block;font-size:12px;font-weight:800;color:#344054;margin-bottom:7px}.contact-field input,.contact-field textarea{width:100%;border:1px solid #dfe3eb;border-radius:10px;padding:12px 13px;font:inherit;font-size:13px;color:#344054;background:#fff;outline:none}.contact-field input:focus,.contact-field textarea:focus{border-color:#8061ee;box-shadow:0 0 0 3px rgba(105,71,232,.09)}.contact-field textarea{min-height:145px;resize:vertical}.receipt-box{padding:15px;border:1px dashed #cfc6fa;border-radius:12px;background:#faf9ff}.receipt-box input{background:#fff}.receipt-help{display:block;color:#98a2b3;font-size:11px;margin-top:6px}.contact-submit{margin-top:18px;width:100%;border:0;border-radius:11px;padding:13px 18px;background:linear-gradient(135deg,#6847e8,#7955f0);color:#fff;font-weight:850;cursor:pointer;box-shadow:0 10px 24px rgba(105,71,232,.20)}.contact-alert{padding:12px 14px;border-radius:10px;margin-bottom:18px;font-size:12px}.contact-alert.ok{background:#eafaf1;color:#087a3d;border:1px solid #bfead0}.contact-alert.error{background:#fff1f2;color:#c83238;border:1px solid #ffd0d5}.selected-plan{margin-bottom:15px;border:1px solid #d8d0ff;background:#f7f4ff;border-radius:12px;padding:13px 15px;color:#513cc1}.selected-plan strong{display:block;font-size:14px}.selected-plan span{font-size:12px}.login-note{margin:0 0 15px;font-size:12px;color:#667085}.login-note a{color:#6947e8;font-weight:800;text-decoration:none}.payment-info{margin-top:30px;padding:17px 18px;border:1px solid #d8d0ff;border-radius:14px;background:linear-gradient(135deg,#f8f5ff,#fff);box-shadow:0 8px 25px rgba(25,30,55,.035)}.payment-info small{display:block;color:#6045d7;font-size:11px;font-weight:800;margin-bottom:7px}.payment-info strong{display:block;color:#344054;font-size:14px;line-height:1.7}.payment-info .account-number{font-size:16px;letter-spacing:.04em;color:#513cc1}@media(max-width:800px){.contact-grid{grid-template-columns:1fr;gap:30px}.contact-intro h1{font-size:38px}.contact-nav{margin-bottom:35px}}@media(max-width:560px){.contact-wrap{padding:24px 18px 50px}.contact-fields{grid-template-columns:1fr}.contact-field.full{grid-column:auto}.contact-form{padding:21px}.contact-intro h1{font-size:34px}}
</style>
</head>
<body class="contact-page">
<div class="contact-wrap">
<header class="contact-nav"><a class="contact-brand" href="index.php"><span class="contact-logo">▶</span>VideoDubing AI</a><a class="contact-back" href="index.php">← Về trang chủ</a></header>
<main class="contact-grid">
<section class="contact-intro"><span class="eyebrow">LIÊN HỆ</span><h1>Hãy để chúng tôi <span>hỗ trợ bạn</span></h1><p>Gửi yêu cầu qua biểu mẫu. Nếu nâng cấp tài khoản, đính kèm biên nhận chuyển tiền để Admin xác nhận.</p><div class="contact-info"><div class="contact-info-card"><small>Người phụ trách</small><strong>Đào Ái Giang Nam</strong></div><div class="contact-info-card"><small>Zalo / Điện thoại</small><a href="tel:0963731910">0963731910 (Zalo)</a></div><div class="contact-info-card"><small>Email hỗ trợ</small><a href="mailto:hotro@review360.id.vn">hotro@review360.id.vn</a></div><div class="contact-info-card"><small>Email liên hệ</small><a href="mailto:giangnam828314@gmail.com">giangnam828314@gmail.com</a></div></div><div class="payment-info"><small>THÔNG TIN THANH TOÁN</small><strong>Ngân hàng: BIDV</strong><strong>Chủ tài khoản: Đào Ái Giang Nam</strong><strong class="account-number">Số tài khoản: 1300459724</strong></div></section>
<section class="contact-form"><h2>Gửi yêu cầu</h2><p>Thông tin sẽ được gửi về <strong>hotro@review360.id.vn</strong>.</p>
<?php if($plan):?><div class="selected-plan"><strong>Gói đã chọn: <?=h($plan['name'])?></strong><span><?=$plan['days']?> ngày · <?=number_format($plan['price'],0,',','.')?>đ</span></div><?php endif;?>
<?php if($user):?><div class="login-note">✓ Đã đăng nhập: <strong><?=h($user['name'])?></strong> · <?=h($user['email'])?></div><?php else:?><div class="login-note">Đăng nhập để tự động gửi kèm thông tin tài khoản. <a href="login.php?redirect=<?=urlencode('contact.php'.($code?'?plan='.$code:''))?>">Đăng nhập</a></div><?php endif;?>
<?php if($sent):?><div class="contact-alert ok">✓ Đã gửi yêu cầu thành công. Admin sẽ kiểm tra và phản hồi sớm.</div><?php endif;?>
<?php if($error):?><div class="contact-alert error">⚠ <?=h($error)?></div><?php endif;?>
<form method="post" enctype="multipart/form-data"><input type="hidden" name="plan" value="<?=h($code)?>"><div class="contact-fields">
<div class="contact-field"><label>Họ và tên *</label><input name="name" value="<?=h($name)?>" required <?= $user?'readonly':''?>></div>
<div class="contact-field"><label>Email *</label><input type="email" name="email" value="<?=h($email)?>" required <?= $user?'readonly':''?>></div>
<div class="contact-field"><label>Số điện thoại / Zalo</label><input name="phone" value="<?=h($phone)?>"></div>
<div class="contact-field"><label>Chủ đề</label><input name="subject" value="<?=h($subject)?>"></div>
<div class="contact-field full"><label>Nội dung *</label><textarea name="message" required><?=h($message)?></textarea></div>
<?php if($plan && $plan['price']>0):?><div class="contact-field full receipt-box"><label>Biên nhận chuyển tiền *</label><input type="file" name="receipt" accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf" required><span class="receipt-help">JPG, PNG hoặc PDF · tối đa 5MB · file sẽ được đính kèm vào email gửi Admin.</span></div><?php endif;?>
</div><button class="contact-submit" type="submit">📩 <?= $plan?'Gửi yêu cầu nâng cấp':'Gửi yêu cầu' ?></button></form>
</section></main></div></body></html>
