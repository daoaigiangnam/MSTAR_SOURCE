<?php
declare(strict_types=1);

require_once __DIR__ . '/lib/payment.php';

$planCode = strtolower(trim((string)($_GET['plan'] ?? '')));
$plan = payment_plan($planCode);

if (!$plan) {
    header('Location: pricing.php');
    exit;
}

$user = current_user();
if (!$user) {
    header('Location: login.php');
    exit;
}

$stmt = db()->prepare('SELECT id,name,email,role,status,expires_at,email_verified_at FROM users WHERE id=? LIMIT 1');
$stmt->execute([(int)$user['id']]);
$fresh = $stmt->fetch();
if (!$fresh || !user_can_login($fresh)) {
    logout_user();
    header('Location: login.php?expired=1');
    exit;
}
refresh_session_user($fresh);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$csrf = csrf_token();
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Thanh toán · <?=e($plan['name'])?> · VideoDubing AI</title>
<link rel="stylesheet" href="assets/css/landing.css">
<style>
body{background:#f7f8fc;color:#172033;font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif}.checkout{max-width:920px;margin:auto;padding:28px 20px 70px}.nav{margin-bottom:26px}.checkout-head{text-align:center;padding:28px 0}.checkout-head h1{margin:0 0 8px;font-size:34px;letter-spacing:-.04em}.checkout-head p{margin:0;color:#667085}.payment-card{background:#fff;border:1px solid #e4e7ec;border-radius:20px;box-shadow:0 18px 50px rgba(16,24,40,.08);padding:28px}.summary{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:24px}.summary-item{border:1px solid #eaecf0;border-radius:12px;padding:14px}.summary-item small{display:block;color:#98a2b3;font-size:11px;margin-bottom:5px}.summary-item strong{font-size:17px}.create-wrap{text-align:center}.primary{border:0;border-radius:10px;background:#6947e8;color:#fff;padding:13px 24px;font-weight:800;cursor:pointer}.primary.secondary{background:#fff;color:#6947e8;border:1px solid #d0d5dd;margin-top:10px}.primary:disabled{opacity:.6;cursor:not-allowed}.payment-area{display:none;margin-top:24px;border-top:1px solid #eaecf0;padding-top:24px}.payment-grid{display:grid;grid-template-columns:300px 1fr;gap:28px;align-items:start}.qr-box{border:1px solid #eaecf0;border-radius:16px;padding:14px;text-align:center;background:#fff;min-height:270px;display:flex;align-items:center;justify-content:center}.qr-box img{width:100%;max-width:270px;aspect-ratio:1;object-fit:contain}.qr-code{width:270px;height:270px;display:flex;align-items:center;justify-content:center}.qr-code img{max-width:100%;max-height:100%}.order-code{font-size:22px;font-weight:900;letter-spacing:.04em}.amount{font-size:28px;font-weight:900;margin:8px 0}.copy{border:1px solid #d0d5dd;background:#fff;border-radius:8px;padding:8px 12px;cursor:pointer}.status{border-radius:12px;padding:13px 15px;background:#f2f4f7;margin:16px 0}.status.success{background:#ecfdf3;color:#027a48}.status.error{background:#fef3f2;color:#b42318}.notice{color:#667085;font-size:13px;line-height:1.7}.countdown{font-weight:800}.back{display:inline-block;margin-top:20px;color:#6947e8;text-decoration:none}.provider-badge{display:inline-block;border-radius:999px;padding:5px 10px;background:#f2f4f7;font-size:12px;font-weight:800;color:#475467;margin-bottom:10px}.payos-link{display:none}.payos-link.show{display:inline-block}.sepay-info{display:block}.sepay-info.hide{display:none}@media(max-width:700px){.summary,.payment-grid{grid-template-columns:1fr}.payment-card{padding:20px}.qr-box{min-height:240px}.qr-code{width:240px;height:240px}}
</style>
</head>
<body>
<main class="checkout">
<header class="nav"><a class="brand" href="index.php"><div class="logo">▶</div><strong>VideoDubing AI</strong></a><nav class="navlinks"><a href="index.php">Trang chủ</a><a href="pricing.php">Bảng giá</a></nav></header>
<section class="checkout-head">
<h1>Thanh toán gói <?=e($plan['name'])?></h1>
<p>Thanh toán một lần · Hệ thống tự động xác nhận và kích hoạt gói sau khi thanh toán thành công</p>
</section>
<section class="payment-card">
<div class="summary">
<div class="summary-item"><small>Gói</small><strong><?=e($plan['name'])?></strong></div>
<div class="summary-item"><small>Thời hạn</small><strong><?=$plan['days']?> ngày</strong></div>
<div class="summary-item"><small>Số tiền</small><strong><?=e($plan['label'])?></strong></div>
<div class="summary-item"><small>Tài khoản</small><strong><?=e((string)$fresh['email'])?></strong></div>
</div>
<div class="create-wrap" id="createWrap"><button class="primary" id="createBtn">Tạo đơn &amp; hiển thị QR</button></div>
<div class="payment-area" id="paymentArea">
<div class="payment-grid">
<div>
<div class="provider-badge" id="providerBadge">Payment</div>
<div class="qr-box"><img id="qrImage" alt="QR thanh toán"><div class="qr-code" id="qrCode"></div></div>
</div>
<div>
<div class="notice" id="paymentNotice">Quét QR bằng ứng dụng ngân hàng và kiểm tra đúng số tiền trước khi chuyển.</div>
<div class="status" id="statusBox">Đang chờ tạo đơn...</div>
<div><small>Mã đơn / nội dung thanh toán</small><div class="order-code" id="orderCode">-</div></div>
<div class="amount" id="orderAmount">-</div>
<div class="notice sepay-info" id="sepayInfo">Ngân hàng: <strong id="bankName">BIDV</strong><br>Số tài khoản/VA: <strong id="bankAccount">-</strong></div>
<div class="notice" id="payosInfo" style="display:none">Thanh toán được xử lý qua <strong>payOS</strong>. Bạn có thể quét QR hoặc mở trang thanh toán payOS.</div>
<div class="notice">Thời gian còn lại: <span class="countdown" id="countdown">-</span></div>
<button class="copy" id="copyCode" type="button">Sao chép mã thanh toán</button>
<a class="primary secondary payos-link" id="payosLink" href="#" target="_blank" rel="noopener">Mở trang thanh toán payOS</a>
</div>
</div>
<a class="back" href="pricing.php">← Quay lại bảng giá</a>
</div>
</section>
</section>
</main>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
const csrfToken = <?=json_encode($csrf, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)?>;
const planCode = <?=json_encode($plan['code'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)?>;
const createBtn = document.getElementById('createBtn');
const createWrap = document.getElementById('createWrap');
const paymentArea = document.getElementById('paymentArea');
const qrImage = document.getElementById('qrImage');
const qrCodeBox = document.getElementById('qrCode');
const providerBadge = document.getElementById('providerBadge');
const paymentNotice = document.getElementById('paymentNotice');
const payosInfo = document.getElementById('payosInfo');
const sepayInfo = document.getElementById('sepayInfo');
const payosLink = document.getElementById('payosLink');
const statusBox = document.getElementById('statusBox');
const orderCode = document.getElementById('orderCode');
const orderAmount = document.getElementById('orderAmount');
const bankName = document.getElementById('bankName');
const bankAccount = document.getElementById('bankAccount');
const countdown = document.getElementById('countdown');
const copyCode = document.getElementById('copyCode');
let currentCode = '';
let pollTimer = null;
let countdownTimer = null;
let expiresAt = null;
let currentProvider = '';

function formatMoney(value){return new Intl.NumberFormat('vi-VN').format(value) + 'đ';}
function setStatus(text, type=''){statusBox.textContent=text;statusBox.className='status' + (type ? ' '+type : '');}
function updateCountdown(){
    if(!expiresAt){countdown.textContent='-';return;}
    const seconds=Math.max(0,Math.floor((new Date(expiresAt.replace(' ','T')).getTime()-Date.now())/1000));
    const m=Math.floor(seconds/60), s=seconds%60;
    countdown.textContent=`${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
    if(seconds<=0){clearInterval(countdownTimer);setStatus('Đơn thanh toán đã hết hạn. Vui lòng tạo đơn mới.','error');clearInterval(pollTimer);}
}
function renderPayOSQr(text){
    qrImage.style.display='none';
    qrCodeBox.innerHTML='';
    if(!text){throw new Error('payOS không trả về dữ liệu QR.');}
    new QRCode(qrCodeBox,{text:String(text),width:270,height:270,correctLevel:QRCode.CorrectLevel.M});
}
function renderSePayQr(url){
    qrCodeBox.innerHTML='';
    qrImage.style.display='block';
    qrImage.src=url || '';
}
async function createOrder(){
    createBtn.disabled=true;
    createBtn.textContent='Đang tạo đơn...';
    try{
        const body=new URLSearchParams({plan:planCode,csrf_token:csrfToken});
        const response=await fetch('payment-create.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded','X-CSRF-Token':csrfToken},body});
        const data=await response.json();
        if(!response.ok || !data.success) throw new Error(data.message || 'Không thể tạo đơn.');
        const o=data.order;
        currentProvider=String(data.provider || '');
        currentCode=o.order_code; expiresAt=o.payment_expires_at;
        orderCode.textContent=o.order_code; orderAmount.textContent=formatMoney(o.amount);
        providerBadge.textContent=currentProvider==='payos' ? 'payOS' : 'SePay';

        if(currentProvider==='payos'){
            renderPayOSQr(o.qr_code);
            payosLink.href=o.checkout_url || '#';
            payosLink.classList.toggle('show',!!o.checkout_url);
            payosInfo.style.display='block';
            sepayInfo.classList.add('hide');
            paymentNotice.textContent='Quét QR bằng ứng dụng ngân hàng để thanh toán qua payOS, hoặc mở trang thanh toán payOS.';
            copyCode.style.display='none';
        }else{
            renderSePayQr(o.qr_url);
            payosLink.classList.remove('show');
            payosInfo.style.display='none';
            sepayInfo.classList.remove('hide');
            bankName.textContent=o.bank || 'BIDV';
            bankAccount.textContent=o.account_number || '-';
            paymentNotice.textContent='Quét QR bằng ứng dụng ngân hàng và kiểm tra đúng số tiền trước khi chuyển.';
            copyCode.style.display='inline-block';
        }

        createWrap.style.display='none'; paymentArea.style.display='block';
        setStatus('Đang chờ thanh toán...');
        updateCountdown(); countdownTimer=setInterval(updateCountdown,1000);
        pollTimer=setInterval(checkStatus,3000); checkStatus();
    }catch(error){setStatus(error.message,'error');createBtn.disabled=false;createBtn.textContent='Tạo đơn & hiển thị QR';}
}
async function checkStatus(){
    if(!currentCode)return;
    try{
        const response=await fetch('payment-status.php?code='+encodeURIComponent(currentCode),{cache:'no-store'});
        const data=await response.json();
        if(!response.ok || !data.success)return;
        const status=data.order.status;
        if(status==='paid'){
            clearInterval(pollTimer); clearInterval(countdownTimer);
            setStatus('Thanh toán thành công! Gói đã được kích hoạt.','success');
            countdown.textContent='Hoàn tất';
            setTimeout(()=>{window.location.href='workspace.php';},1500);
        }else if(status==='expired'){
            clearInterval(pollTimer); clearInterval(countdownTimer);
            setStatus('Đơn thanh toán đã hết hạn. Vui lòng tạo đơn mới.','error');
        }
    }catch(error){
        // Keep polling. A temporary browser/network failure must not cancel the order.
    }
}
copyCode.addEventListener('click',async()=>{if(!currentCode)return;try{await navigator.clipboard.writeText(currentCode);copyCode.textContent='Đã sao chép';setTimeout(()=>copyCode.textContent='Sao chép mã thanh toán',1500);}catch(e){}});
createBtn.addEventListener('click',createOrder);
</script>
</body>
</html>
