<?php
declare(strict_types=1); require_once __DIR__.'/lib/auth.php';
if(current_user()){header('Location: workspace.php');exit;}
$error='';$notice='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $name=trim((string)($_POST['name']??''));$phone=trim((string)($_POST['phone']??''));$email=strtolower(trim((string)($_POST['email']??'')));$pass=(string)($_POST['password']??'');$confirm=(string)($_POST['confirm_password']??'');
 if($name===''||$phone===''||!filter_var($email,FILTER_VALIDATE_EMAIL))$error='Vui lòng nhập đầy đủ Họ tên, Di động và Email hợp lệ.';
 elseif(!preg_match('/^[0-9+() .-]{8,20}$/',$phone))$error='Số di động không hợp lệ.';
 elseif(strlen($pass)<8)$error='Mật khẩu phải có ít nhất 8 ký tự.';
 elseif($pass!==$confirm)$error='Mật khẩu nhập lại không khớp.';
 else try{
  $q=db()->prepare('SELECT id FROM users WHERE email=? LIMIT 1');$q->execute([$email]);
  if($q->fetch())$error='Email này đã tồn tại. Vui lòng dùng email khác hoặc chọn Quên mật khẩu.';
  else{
   db()->beginTransaction();$q=db()->prepare("INSERT INTO users(name,phone,email,password_hash,status,role,expires_at,email_verified_at) VALUES(?,?,?,?,?,?,NULL,NULL)");$q->execute([$name,$phone,$email,password_hash($pass,PASSWORD_DEFAULT),'active','user']);$id=(int)db()->lastInsertId();$token=new_token();$q=db()->prepare('INSERT INTO account_tokens(user_id,type,token_hash,expires_at) VALUES(?,?,?,?)');$q->execute([$id,'activation',hash('sha256',$token),(new DateTimeImmutable('+24 hours'))->format('Y-m-d H:i:s')]);db()->commit();
   $link=app_config()['public_base_url'].'/activate.php?token='.rawurlencode($token);$sent=send_account_mail($email,'Kích hoạt tài khoản VideoDubing AI',"Xin chào {$name},\n\nCảm ơn bạn đã đăng ký VideoDubing AI. Tài khoản mặc định được dùng thử 1 tháng kể từ khi kích hoạt.\n\nHãy bấm liên kết sau để kích hoạt:\n{$link}\n\nLiên kết có hiệu lực 24 giờ.\n\nVideoDubing AI");
   $notice=$sent?'Đăng ký thành công. Link kích hoạt đã được gửi tới Email. Tài khoản có 1 tháng dùng thử kể từ khi kích hoạt.':'Đăng ký thành công nhưng hệ thống chưa gửi được Email. Hãy cấu hình mail server và gửi lại link kích hoạt.';
  }
 }catch(Throwable $e){if(db()->inTransaction())db()->rollBack();$error='Không thể tạo tài khoản. Kiểm tra cấu hình MySQL.';}
}
?>
<!doctype html><html lang="vi"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Đăng ký - VideoDubing AI</title><link rel="stylesheet" href="assets/css/app.css"></head><body><main class="auth-page"><section class="auth-card"><a class="auth-brand" href="index.php">▶ <strong>VideoDubing AI</strong></a><h1>Tạo tài khoản</h1><p class="hint">Tài khoản mới được dùng thử <strong>1 tháng</strong> sau khi kích hoạt Email.</p><?php if($notice):?><div class="alert ok"><?=htmlspecialchars($notice)?></div><?php endif;?><?php if($error):?><div class="alert error"><?=htmlspecialchars($error)?></div><?php endif;?><form method="post"><div class="field"><label>Họ tên</label><input class="input" name="name" required value="<?=htmlspecialchars((string)($_POST['name']??''))?>"></div><div class="field"><label>Di động</label><input class="input" type="tel" name="phone" required value="<?=htmlspecialchars((string)($_POST['phone']??''))?>"></div><div class="field"><label>Email</label><input class="input" type="email" name="email" required value="<?=htmlspecialchars((string)($_POST['email']??''))?>"></div><div class="field"><label>Mật khẩu</label><input class="input" type="password" name="password" minlength="8" required></div><div class="field"><label>Nhập lại mật khẩu</label><input class="input" type="password" name="confirm_password" minlength="8" required></div><button class="primary" style="width:100%">ĐĂNG KÝ</button></form><p class="auth-bottom">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p></section></main></body></html>