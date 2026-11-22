<?php
declare(strict_types=1);

require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/lib/payment-provider.php';

$admin = require_admin();
$config = app_config();
$pdo = db();
$message = '';
$error = '';

function h_payment($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        verify_csrf($_POST['csrf_token'] ?? null);
        $action = trim((string)($_POST['action'] ?? ''));
        $provider = strtolower(trim((string)($_POST['provider'] ?? '')));

        if ($action === 'set_default') {
            payment_set_default_provider($pdo, $provider);
            $message = 'Đã đổi Payment Provider mặc định sang ' . $provider . '.';
        } elseif ($action === 'toggle') {
            $enabled = (string)($_POST['enabled'] ?? '') === '1';
            payment_set_enabled($pdo, $provider, $enabled);
            $message = ($enabled ? 'Đã bật ' : 'Đã tắt ') . $provider . '.';
        } else {
            throw new RuntimeException('Thao tác không hợp lệ.');
        }
    } catch (Throwable $e) {
        $error = $e->getMessage() ?: 'Không thể cập nhật cấu hình thanh toán.';
    }
}

$providers = payment_provider_rows($pdo);
$csrf = csrf_token();
$defaultProvider = payment_default_provider($pdo);

// Payment credentials are configured in config/config.php, not .env.
$payosConfig = is_array($config['payment'] ?? null) ? $config['payment'] : [];
$payosReady = !empty($payosConfig['payos_client_id'])
    && !empty($payosConfig['payos_api_key'])
    && !empty($payosConfig['payos_checksum_key']);
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Payment Provider · VideoDubing AI</title>
<link rel="stylesheet" href="assets/css/app.css">
<style>
.wrap{max-width:1100px;margin:0 auto}.head{display:flex;justify-content:space-between;align-items:flex-end;gap:20px;margin-bottom:18px}.head h2{margin:0}.head p{margin:6px 0 0;color:var(--muted);font-size:13px}.card-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.provider-card{border:1px solid var(--border);border-radius:16px;padding:18px;background:#fff}.provider-head{display:flex;justify-content:space-between;align-items:center;gap:14px}.provider-name{font-weight:800;font-size:18px}.status{font-size:12px;font-weight:800;padding:5px 9px;border-radius:999px}.status.on{background:#ecfdf3;color:#087a3d}.status.off{background:#fef2f2;color:#c83238}.meta{margin-top:14px;color:var(--muted);font-size:13px;line-height:1.7}.actions{display:flex;gap:8px;flex-wrap:wrap;margin-top:16px}.default-box{margin-bottom:16px;border:1px solid #ddd5ff;background:#faf9ff;border-radius:14px;padding:16px}.default-box strong{color:#5b3ed0}.warn{margin-top:10px;color:#9a6700;font-size:12px}.back{display:inline-block;margin-bottom:18px}.table-wrap{overflow:auto}@media(max-width:760px){.card-grid{grid-template-columns:1fr}.head{align-items:flex-start;flex-direction:column}}
</style>
</head>
<body>
<main class="app wrap">
<header class="top"><div class="brand"><div class="logo">▶</div><div><h1>VideoDubing AI</h1><p>Cấu hình Payment Provider</p></div></div><div class="userbar"><span>👤 <?=h_payment($admin['name'])?></span><a href="admin.php">Users</a><a href="workspace.php">Workspace</a><a href="logout.php">Đăng xuất</a></div></header>
<a class="back" href="admin.php">← Quay lại Admin</a>
<div class="head"><div><h2>Payment Provider</h2><p>User không chọn provider. Hệ thống tự dùng provider mặc định do Admin cấu hình.</p></div></div>
<?php if($message):?><div class="alert ok">✓ <?=h_payment($message)?></div><?php endif;?>
<?php if($error):?><div class="alert error">⚠ <?=h_payment($error)?></div><?php endif;?>
<section class="default-box"><div>Provider mặc định hiện tại: <strong><?=h_payment(strtoupper($defaultProvider))?></strong></div><div class="warn">Provider đã được dùng cho Order cũ sẽ không thay đổi khi Admin chuyển mặc định.</div></section>
<section class="card-grid">
<?php foreach($providers as $provider): $code=(string)$provider['provider_code']; $enabled=(int)$provider['is_enabled']===1; $isDefault=(int)$provider['is_default']===1; ?>
<article class="provider-card">
<div class="provider-head"><div class="provider-name"><?=h_payment($provider['provider_name'])?></div><span class="status <?=$enabled?'on':'off'?>"><?=$enabled?'Đang bật':'Đang tắt'?></span></div>
<div class="meta">Mã provider: <strong><?=h_payment($code)?></strong><br>Mặc định: <strong><?=$isDefault?'Có':'Không'?></strong>
<?php if($code==='payos'):?><br>Credential: <strong><?=$payosReady?'Đã cấu hình':'Chưa cấu hình'?></strong><?php endif;?></div>
<div class="actions">
<?php if(!$isDefault):?><form method="post"><input type="hidden" name="csrf_token" value="<?=h_payment($csrf)?>"><input type="hidden" name="action" value="set_default"><input type="hidden" name="provider" value="<?=h_payment($code)?>"><button class="primary" type="submit" <?=$enabled?'':'disabled'?>>Đặt làm mặc định</button></form><?php endif;?>
<?php if(!$isDefault):?><form method="post"><input type="hidden" name="csrf_token" value="<?=h_payment($csrf)?>"><input type="hidden" name="action" value="toggle"><input type="hidden" name="provider" value="<?=h_payment($code)?>"><input type="hidden" name="enabled" value="<?=$enabled?'0':'1'?>"><button class="secondary" type="submit"><?=$enabled?'Tắt':'Bật'?></button></form><?php endif;?>
</div>
</article>
<?php endforeach; ?>
</section>
</main>
</body>
</html>
