<?php
declare(strict_types=1);
require_once __DIR__ . '/lib/auth.php';
$redirect=(string)($_GET['redirect']??$_POST['redirect']??'');
if(!preg_match('/^(?:contact|workspace)\.php(?:\?.*)?$/',$redirect))$redirect='';
if (current_user()) {
    if ($redirect!=='') header('Location: '.$redirect);
    else header('Location: '.((current_user()['role']??'')==='admin'?'admin.php':'workspace.php'));
    exit;
}
$error='';
$notice=isset($_GET['registered'])?'Đăng ký thành công. Vui lòng kiểm tra Email để kích hoạt tài khoản.':(isset($_GET['expired'])?'Tài khoản chưa được kích hoạt hoặc đã bị vô hiệu hóa.':'');
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf($_POST['csrf_token']??null); $login=trim((string)($_POST['login']??'')); $pass=(string)($_POST['password']??'');
 try{
  $q=db()->prepare('SELECT id,name,phone,email,password_hash,status,role,expires_at,email_verified_at FROM users WHERE phone=? OR (email IS NOT NULL AND email=?) LIMIT 1');$q->execute([$login,strtolower($login)]);$u=$q->fetch();
  if(!$u||!password_verify($pass,$u['password_hash']))$error='Số điện thoại hoặc mật khẩu không đúng.';
  elseif(!user_can_login($u))$error='Tài khoản chưa được kích hoạt hoặc đã bị vô hiệu hóa.';
  else{db()->prepare('UPDATE users SET last_login_at=NOW() WHERE id=?')->execute([$u['id']]);login_user($u);if($redirect!=='')header('Location: '.$redirect);else header('Location: '.($u['role']==='admin'?'admin.php':'workspace.php'));exit;}
 }catch(Throwable $e){$error='Không thể đăng nhập. Kiểm tra cấu hình MySQL.';}
}
?><!doctype html><html lang="vi"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Đăng nhập - VideoDubing AI</title><link rel="stylesheet" href="assets/css/app.css"></head><body><main class="auth-page"><section class="auth-card"><a class="auth-brand" href="index.php">▶ <strong>VideoDubing AI</strong></a><h1>Đăng nhập</h1><p class="hint"><?= $redirect!==''?'Đăng nhập để tiếp tục gửi yêu cầu nâng cấp.':'Đăng nhập bằng <strong>Số điện thoại</strong> hoặc Email.' ?></p><?php if($notice):?><div class="alert ok"><?=htmlspecialchars($notice)?></div><?php endif;?><?php if($error):?><div class="alert error"><?=htmlspecialchars($error)?></div><?php endif;?><form method="post"><input type="hidden" name="csrf_token" value="<?=htmlspecialchars(csrf_token())?>"><input type="hidden" name="redirect" value="<?=htmlspecialchars($redirect,ENT_QUOTES,'UTF-8')?>"><div class="field"><label>Số điện thoại / Email</label><input class="input" name="login" required autofocus autocomplete="username"></div><div class="field"><label>Mật khẩu</label><input class="input" type="password" name="password" required autocomplete="current-password"></div><button class="primary" style="width:100%">ĐĂNG NHẬP</button></form><p class="auth-bottom"><a href="forgot-password.php">Quên mật khẩu?</a></p><p class="auth-bottom">Chưa có tài khoản? <a href="register.php">Đăng ký miễn phí</a></p></section></main></body></html>
