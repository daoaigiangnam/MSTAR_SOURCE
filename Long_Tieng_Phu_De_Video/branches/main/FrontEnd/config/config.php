<?php
declare(strict_types=1);

return [
    'app_name' => getenv('APP_NAME') ?: 'VideoDubing AI',
    'api_base_url' => rtrim((string)(getenv('VIDEODUBING_API_URL') ?: 'http://127.0.0.1:3000/api/v1'), '/'),
    'request_timeout' => (int)(getenv('VIDEODUBING_API_TIMEOUT') ?: 60),
    'public_base_url' => rtrim((string)(getenv('VIDEODUBING_WEB_URL') ?: 'https://videotool.review360.id.vn'), '/'),
    'trial_days' => max(1, (int)(getenv('TRIAL_DAYS') ?: 30)),
    'activation_token_minutes' => max(15, (int)(getenv('ACTIVATION_TOKEN_MINUTES') ?: 1440)),
    'password_reset_token_minutes' => max(15, (int)(getenv('PASSWORD_RESET_TOKEN_MINUTES') ?: 60)),
    'session_name' => getenv('SESSION_NAME') ?: 'videodubing_session',
    'session_save_path' => getenv('SESSION_SAVE_PATH') ?: (sys_get_temp_dir() . '/videodubing-ai-sessions'),
    'mail' => [
        'host' => getenv('MAIL_HOST') ?: '',
        'port' => (int)(getenv('MAIL_PORT') ?: 587),
        'username' => getenv('MAIL_USERNAME') ?: '',
        'password' => getenv('MAIL_PASSWORD') ?: '',
        'encryption' => strtolower(getenv('MAIL_ENCRYPTION') ?: 'tls'),
        'from_email' => getenv('MAIL_FROM') ?: 'no-reply@review360.id.vn',
        'from_name' => getenv('MAIL_FROM_NAME') ?: 'VideoDubing AI',
    ],
    'payment' => [
        'sepay_webhook_secret' => getenv('SEPAY_WEBHOOK_SECRET') ?: '',
        'sepay_bank_gateway' => strtoupper(getenv('SEPAY_BANK_GATEWAY') ?: 'BIDV'),
        'sepay_bank_account' => getenv('SEPAY_BANK_ACCOUNT') ?: '',
        'sepay_bank_sub_account' => getenv('SEPAY_BANK_SUB_ACCOUNT') ?: '',
        'order_prefix' => getenv('PAYMENT_ORDER_PREFIX') ?: 'DH',
        'order_digits' => max(6, (int)(getenv('PAYMENT_ORDER_DIGITS') ?: 10)),
        'payment_expire_minutes' => max(5, min(60, (int)(getenv('PAYMENT_EXPIRE_MINUTES') ?: 15))),
    ],
    'db' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => (int)(getenv('DB_PORT') ?: 3306),
        'name' => getenv('DB_NAME') ?: 'videodubing',
        'user' => getenv('DB_USER') ?: 'videodubing',
        'password' => getenv('DB_PASSWORD') ?: '',
        'charset' => 'utf8mb4',
    ],
];
