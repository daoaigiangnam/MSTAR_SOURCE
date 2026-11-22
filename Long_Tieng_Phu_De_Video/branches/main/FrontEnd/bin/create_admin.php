<?php
declare(strict_types=1);

require_once __DIR__ . '/../lib/auth.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

$args = $argv;
if (count($args) !== 4) {
    fwrite(STDERR, "Usage: php create_admin.php \"Full Name\" email@example.com \"Password\"\n");
    exit(1);
}

[, $name, $email, $password] = $args;
$email = strtolower(trim($email));

if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
    fwrite(STDERR, "Invalid admin data. Password must be at least 8 characters.\n");
    exit(1);
}

$check = db()->prepare('SELECT id,role FROM users WHERE email=? LIMIT 1');
$check->execute([$email]);
$existing = $check->fetch();

if ($existing) {
    if ($existing['role'] === 'admin') {
        fwrite(STDERR, "Admin email already exists.\n");
        exit(2);
    }
    fwrite(STDERR, "Email already belongs to a normal user.\n");
    exit(3);
}

$stmt = db()->prepare("INSERT INTO users(name,phone,email,password_hash,status,role,email_verified_at,expires_at) VALUES(?,?,?,?,?,?,NOW(),DATE_ADD(NOW(),INTERVAL 3650 DAY))");
$stmt->execute([$name, 'N/A', $email, password_hash($password, PASSWORD_DEFAULT), 'active', 'admin']);

echo "Admin created successfully: {$email}\n";
