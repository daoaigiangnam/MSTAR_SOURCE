<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/mailer.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    $config = require __DIR__ . '/../config/config.php';

    // Long-running video jobs must not lose the login session while the browser
    // is waiting. Heartbeat requests keep the session active, while these values
    // also protect against PHP's default short session lifetime.
    $sessionLifetime = 7 * 24 * 60 * 60;
    @ini_set('session.gc_maxlifetime', (string)$sessionLifetime);
    @ini_set('session.cookie_lifetime', (string)$sessionLifetime);

    $sessionPath = (string)($config['session_save_path'] ?? '');
    if ($sessionPath !== '') {
        if (!is_dir($sessionPath)) {
            @mkdir($sessionPath, 0700, true);
        }
        if (is_dir($sessionPath) && is_writable($sessionPath)) {
            session_save_path($sessionPath);
        }
    }
    session_name($config['session_name']);
    session_set_cookie_params([
        'lifetime' => $sessionLifetime,
        'httponly' => true,
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || strtolower((string)($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https',
        'samesite' => 'Lax',
        'path' => '/',
    ]);
    session_start();
}

function app_config(): array
{
    static $config = null;
    return $config ??= require __DIR__ . '/../config/config.php';
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $pdo = require __DIR__ . '/../config/database.php';
    return $pdo;
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function login_user(array $user): void
{
    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id' => (int)$user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role'],
        'expires_at' => $user['expires_at'],
    ];
}

function logout_user(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'] ?? '', $params['secure'], $params['httponly']);
    }
    session_destroy();
}

/** Account may authenticate when active/verified even if its subscription has expired. */
function user_can_login(array $user): bool
{
    return ($user['status'] ?? '') === 'active' && !empty($user['email_verified_at']);
}

/** Account can use paid workspace functions only while its expiry is in the future. */
function user_can_use_workspace(array $user): bool
{
    return user_can_login($user)
        && !empty($user['expires_at'])
        && strtotime((string)$user['expires_at']) > time();
}

function user_is_active(array $user): bool
{
    return user_can_use_workspace($user);
}

function refresh_session_user(array $fresh): array
{
    $_SESSION['user'] = [
        'id' => (int)$fresh['id'],
        'name' => $fresh['name'],
        'email' => $fresh['email'],
        'role' => $fresh['role'],
        'expires_at' => $fresh['expires_at'],
    ];
    return $fresh;
}

function require_login(): array
{
    $user = current_user();
    if (!$user) {
        header('Location: login.php');
        exit;
    }
    $statement = db()->prepare('SELECT id,name,email,role,status,expires_at,email_verified_at FROM users WHERE id=? LIMIT 1');
    $statement->execute([$user['id']]);
    $fresh = $statement->fetch();
    if (!$fresh || !user_can_login($fresh)) {
        logout_user();
        header('Location: login.php?expired=1');
        exit;
    }
    return refresh_session_user($fresh);
}

function require_ajax_login(): array
{
    $user = current_user();
    header('Content-Type: application/json; charset=utf-8');
    if (!$user) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
    $statement = db()->prepare('SELECT id,name,email,role,status,expires_at,email_verified_at FROM users WHERE id=? LIMIT 1');
    $statement->execute([$user['id']]);
    $fresh = $statement->fetch();
    if (!$fresh || !user_can_login($fresh)) {
        logout_user();
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Tài khoản đã bị vô hiệu hóa hoặc chưa kích hoạt.'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
    if (!user_can_use_workspace($fresh)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Tài khoản đã hết hạn. Vui lòng gia hạn gói để tiếp tục sử dụng chức năng.'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
    return refresh_session_user($fresh);
}

function require_admin(): array
{
    $user = require_login();
    if (($user['role'] ?? '') !== 'admin') {
        http_response_code(403);
        exit('Forbidden');
    }
    return $user;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}

function verify_csrf(?string $token): void
{
    if (!$token || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(419);
        exit('Invalid CSRF token');
    }
}

function new_token(): string { return bin2hex(random_bytes(32)); }

function save_token(int $userId, string $type, string $plain, int $minutes): void
{
    $query = db()->prepare('INSERT INTO account_tokens(user_id,type,token_hash,expires_at) VALUES(?,?,?,?)');
    $query->execute([$userId, $type, hash('sha256', $plain), (new DateTimeImmutable("+{$minutes} minutes"))->format('Y-m-d H:i:s')]);
}

function consume_token(string $plain, string $type): ?array
{
    $query = db()->prepare('SELECT t.id,t.user_id,u.email,u.name FROM account_tokens t JOIN users u ON u.id=t.user_id WHERE t.type=? AND t.token_hash=? AND t.used_at IS NULL AND t.expires_at>NOW() LIMIT 1');
    $query->execute([$type, hash('sha256', $plain)]);
    $row = $query->fetch();
    if (!$row) return null;
    db()->prepare('UPDATE account_tokens SET used_at=NOW() WHERE id=?')->execute([$row['id']]);
    return $row;
}
