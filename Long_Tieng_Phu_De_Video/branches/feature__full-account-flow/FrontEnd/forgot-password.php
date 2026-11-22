<?php
declare(strict_types=1); require_once __DIR__.'/lib/auth.php';
$error='';$notice='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $email=strtolower(trim((string)($_POST['email']??'')));
 if(!filter_var($email,FILTER_VALIDATE_EMAIL))$error='Vui lòng nhập Email hợp lệ.';else try{
  $q=db()->prepare('SELECT id,name,email FROM users WHERE email=? LIMIT 1');$q->execute([$email]);$u=$q->fetch();
  if($u){$token=new_token();$q=db()->prepare('INSERT INTO account_tokens(user_id,type,token_hash,expires_at) VALUES(?,?,?,?)');$q->execute([$u['id'],'password_reset',hash('sha256',$token),(new DateTimeImmutable('+60 minutes'))->format('Y-m-d H:i:s')]);$link=app_config()['public_base_url'].'/reset-password.php?token='.rawurlencode($token);send_account_mail($u['email'],'Đặt lại mật khẩu VideoDubing AI',"Xin chào {$u['name']},\n\nBạn đã yêu cầu đặt lại mật khẩu. Link có hiệu lực 60 phút:\n{$link}\n\nNếu không phải bạn yêu cầu, hãy bỏ qua Email này.");}
  $notice='Nếu Email tồn tại trong hệ thống, liên kết đặt lại mật khẩu đã được gửi. Hãy kiểm tra cả Spam/Junk.';
 }catch(Throwable $e){$error='Không thể xử lý yêu cầu. Kiểm tra cấu hình MySQL.';}
}
?><!doctype html><html lang="vi"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Quên mật khẩu</title><link rel="stylesheet" href="assets/css/app.css"></head><body><main class="auth-page"><section class="auth-card"><a class="auth-brand" href="index.php">▶ <strong>VideoDubing AI</strong></a><h1>Quên mật khẩu</h1><p class="hint">Nhập Email đã đăng ký để nhận liên kết đặt lại mật khẩu.</p><?php if($notice):?><div class="alert ok"><?=htmlspecialchars($notice)?></div><?php endif;?><?php if($error):?><div class="alert error"><?=htmlspecialchars($error)?></div><?php endif;?><form method="post"><div class="field"><label>Email</label><input class="input" type="email" name="email" required autofocus></div><button class="primary" style="width:100%">GỬI LINK ĐẶT LẠI</button></form><p class="auth-bottom"><a href="login.php">Quay lại đăng nhập</a></p></section></main></body></html>