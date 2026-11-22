<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('videodubing_session');
    session_set_cookie_params(['httponly'=>true,'secure'=>(!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off'),'samesite'=>'Lax']);
    session_start();
}
function app_config(): array { static $c=null; return $c ??= require __DIR__.'/../config/config.php'; }
function db(): PDO { static $pdo=null; if($pdo instanceof PDO)return $pdo; $host=getenv('MYSQL_HOST')?:'127.0.0.1';$port=getenv('MYSQL_PORT')?:'3306';$name=getenv('MYSQL_DATABASE')?:'videodubing';$user=getenv('MYSQL_USER')?:'videodubing';$pass=getenv('MYSQL_PASSWORD')?:'';$pdo=new PDO("mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4",$user,$pass,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);return $pdo; }
function current_user():?array{return $_SESSION['user']??null;}
function login_user(array $u):void{session_regenerate_id(true);$_SESSION['user']=['id'=>(int)$u['id'],'name'=>$u['name'],'email'=>$u['email'],'role'=>$u['role'],'expires_at'=>$u['expires_at']];}
function logout_user():void{$_SESSION=[];if(ini_get('session.use_cookies')){$p=session_get_cookie_params();setcookie(session_name(),'',time()-42000,$p['path'],$p['domain']??'',$p['secure'],$p['httponly']);}session_destroy();}
function user_is_active(array $u):bool{return ($u['status']??'')==='active'&&!empty($u['email_verified_at'])&&!empty($u['expires_at'])&&strtotime($u['expires_at'])>time();}
function require_login():array{$u=current_user();if(!$u){header('Location: login.php');exit;}$st=db()->prepare('SELECT id,name,email,role,status,expires_at,email_verified_at FROM users WHERE id=? LIMIT 1');$st->execute([$u['id']]);$fresh=$st->fetch();if(!$fresh||!user_is_active($fresh)){logout_user();header('Location: login.php?expired=1');exit;}login_user($fresh);return $fresh;}
function require_admin():array{$u=require_login();if(($u['role']??'')!=='admin'){http_response_code(403);exit('Forbidden');}return $u;}
function new_token():string{return bin2hex(random_bytes(32));}
function save_token(int $userId,string $type,string $plain,int $minutes):void{$q=db()->prepare('INSERT INTO account_tokens(user_id,type,token_hash,expires_at) VALUES(?,?,?,?)');$q->execute([$userId,$type,hash('sha256',$plain),(new DateTimeImmutable("+{$minutes} minutes"))->format('Y-m-d H:i:s')]);}
function consume_token(string $plain,string $type):?array{$q=db()->prepare('SELECT t.id,t.user_id,u.email,u.name FROM account_tokens t JOIN users u ON u.id=t.user_id WHERE t.type=? AND t.token_hash=? AND t.used_at IS NULL AND t.expires_at>NOW() LIMIT 1');$q->execute([$type,hash('sha256',$plain)]);$row=$q->fetch();if(!$row)return null;db()->prepare('UPDATE account_tokens SET used_at=NOW() WHERE id=?')->execute([$row['id']]);return $row;}
function send_account_mail(string $to,string $subject,string $body):bool{$from=app_config()['mail_from'];$headers="From: {$from}\r\nMIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8\r\n";return @mail($to,'=?UTF-8?B?'.base64_encode($subject).'?=',$body,$headers);}
