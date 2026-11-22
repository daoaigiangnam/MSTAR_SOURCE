<?php
declare(strict_types=1);

return [
    'api_base_url' => rtrim(getenv('VIDEODUBING_API_URL') ?: 'http://127.0.0.1:3000/api/v1', '/'),
    'request_timeout' => (int)(getenv('VIDEODUBING_API_TIMEOUT') ?: 60),
    'public_base_url' => rtrim(getenv('VIDEODUBING_WEB_URL') ?: 'https://videotool.review360.id.vn', '/'),
    'mail_from' => getenv('VIDEODUBING_MAIL_FROM') ?: 'no-reply@review360.id.vn',
];
