<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('videodubing_session');
    session_set_cookie_params([
        'httponly' => true,
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        'samesite' => 'Lax',
    ]);
    session_start();
}

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $host = getenv('MYSQL_HOST') ?: '127.0.0.1';
    $port = getenv('MYSQL_PORT') ?: '3306';
    $name = getenv('MYSQL_DATABASE') ?: 'videodubing';
    $user = getenv('MYSQL_USER') ?: 'videodubing';
    $pass = getenv('MYSQL_PASSWORD') ?: '';
    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false]);
    return $pdo;
}
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function login_user(array $u): void { session_regenerate_id(true); $_SESSION['user'] = ['id'=>(int)$u['id'],'name'=>$u['name'],'email'=>$u['email'],'role'=>$u['role'],'expires_at'=>$u['expires_at']]; }
function logout_user(): void { $_SESSION=[]; if (ini_get('session.use_cookies')) { $p=session_get_cookie_params(); setcookie(session_name(),'',time()-42000,$p['path'],$p['domain'] ?? '',$p['secure'],$p['httponly']); } session_destroy(); }
function user_is_active(array $u): bool { return ($u['status'] ?? '') === 'active' && (!empty($u['expires_at']) && strtotime($u['expires_at']) > time()); }
function require_login(): array { $u=current_user(); if (!$u) { header('Location: login.php'); exit; } $st=db()->prepare('SELECT id,name,email,role,status,expires_at FROM users WHERE id=? LIMIT 1'); $st->execute([$u['id']]); $fresh=$st->fetch(); if (!$fresh || !user_is_active($fresh)) { logout_user(); header('Location: login.php?expired=1'); exit; } login_user($fresh); return $fresh; }
function require_admin(): array { $u=require_login(); if (($u['role'] ?? '') !== 'admin') { http_response_code(403); exit('Forbidden'); } return $u; }
