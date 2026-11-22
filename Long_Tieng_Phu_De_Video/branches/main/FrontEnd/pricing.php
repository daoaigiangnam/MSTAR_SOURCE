<?php
declare(strict_types=1);
require_once __DIR__ . '/lib/payment.php';

$plans = array_merge([
    ['code'=>'trial','name'=>'Dùng thử','days'=>7,'price'=>0,'label'=>'Miễn phí'],
], array_values(payment_plans()));

function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Bảng giá · VideoDubing AI</title>
<link rel="stylesheet" href="assets/css/landing.css">
<style>
.pricing-page{max-width:1280px;margin:auto;padding:0 32px 70px;color:#172033;font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif}.pricing-head{text-align:center;padding:72px 10px 36px}.pricing-head .eyebrow{display:inline-flex;padding:6px 11px;border:1px solid #ddd5ff;border-radius:999px;background:#f5f2ff;color:#6045d7;font-size:10px;font-weight:900;letter-spacing:.08em}.pricing-head h1{font-size:clamp(38px,5vw,58px);line-height:1.05;letter-spacing:-.06em;margin:18px 0 12px}.pricing-head h1 span{color:#6947e8}.pricing-head p{max-width:700px;margin:auto;color:#667085;font-size:15px;line-height:1.7}.pricing-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:14px;align-items:stretch}.plan{position:relative;border:1px solid #e3e6ed;border-radius:18px;background:#fff;padding:24px 20px;box-shadow:0 10px 28px rgba(25,30,55,.045);display:flex;flex-direction:column}.plan.featured{border:2px solid #6947e8;box-shadow:0 22px 55px rgba(105,71,232,.16);transform:translateY(-8px)}.plan-badge{position:absolute;top:-13px;left:50%;transform:translateX(-50%);background:#6947e8;color:#fff;border-radius:999px;padding:5px 11px;font-size:10px;font-weight:900;white-space:nowrap}.plan h2{font-size:19px;margin:0 0 7px}.plan-days{color:#98a2b3;font-size:12px}.plan-price{margin:18px 0 20px}.plan-price strong{font-size:31px;line-height:1;letter-spacing:-.04em}.plan-price span{display:block;color:#98a2b3;font-size:11px;margin-top:6px}.plan ul{list-style:none;padding:0;margin:0 0 22px;flex:1}.plan li{font-size:12px;color:#475467;margin:10px 0;display:flex;gap:7px}.plan li:before{content:'✓';color:#6947e8;font-weight:900}.plan .plan-btn{display:flex;align-items:center;justify-content:center;border-radius:10px;padding:11px 12px;text-decoration:none;font-size:12px;font-weight:850;border:1px solid #dfe3eb;color:#344054;background:#fff}.plan.featured .plan-btn,.plan:first-child .plan-btn{border-color:#6947e8;background:linear-gradient(135deg,#6847e8,#7955f0);color:#fff}.pricing-note{text-align:center;color:#98a2b3;font-size:12px;margin-top:20px}@media(max-width:1050px){.pricing-grid{grid-template-columns:repeat(2,1fr)}.plan.featured{transform:none}}@media(max-width:620px){.pricing-page{padding:0 18px 50px}.pricing-grid{grid-template-columns:1fr}.pricing-head{padding:48px 5px 28px}}
</style>
</head>
<body>
<main class="pricing-page">
<header class="nav"><a class="brand" href="index.php"><div class="logo">▶</div><strong>VideoDubing AI</strong></a><nav class="navlinks"><a href="index.php">Trang chủ</a><a href="index.php#tinh-nang">Tính năng</a><a class="active" href="pricing.php">Bảng giá</a><a href="contact.php">Liên hệ</a><a class="btn btn-light" href="login.php">Đăng nhập</a><a class="btn btn-primary" href="register.php">Bắt đầu miễn phí</a></nav></header>
<section class="pricing-head"><span class="eyebrow">BẢNG GIÁ</span><h1>Chọn gói phù hợp với <span>bạn</span></h1><p>Thanh toán một lần bằng QR BIDV. Hệ thống tự động xác nhận giao dịch qua SePay và cộng thời hạn vào tài khoản.</p></section>
<section class="pricing-grid">
<?php foreach($plans as $i=>$p): ?><article class="plan <?=($p['code']==='standard'?'featured':'')?>">
<?php if($p['code']==='standard'): ?><div class="plan-badge">⭐ PHỔ BIẾN NHẤT</div><?php endif; ?>
<h2><?=e($p['name'])?></h2><div class="plan-days"><?=$p['days']?> ngày sử dụng</div>
<div class="plan-price"><strong><?=e($p['label'])?></strong><span><?=$p['price']===0?'Trải nghiệm miễn phí':'Thanh toán một lần'?></span></div>
<ul><li>Download video</li><li>Lồng tiếng AI</li><li>Làm phụ đề</li><li>Text → Speech</li><li>Hỗ trợ đa ngôn ngữ</li></ul>
<?php if($p['code']==='trial'): ?>
<a class="plan-btn" href="register.php">Đăng ký</a>
<?php else: ?>
<a class="plan-btn" href="checkout.php?plan=<?=e($p['code'])?>">Thanh toán</a>
<?php endif; ?>
</article><?php endforeach; ?>
</section>
<div class="pricing-note">Gói 7 ngày được đăng ký miễn phí. Các gói trả phí thanh toán qua QR BIDV và được tự động kích hoạt sau khi SePay xác nhận.</div>
</main></body></html>
