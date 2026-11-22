<?php
declare(strict_types=1);

// CLI worker only. It runs independently of the user's browser/session.
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("CLI only\n");
}

require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/api.php';

$config = app_config();
$pdo = db();

$maxAttempts = 5;
$batchSize = 20;

function worker_log(string $message): void
{
    error_log('[VideoDubing job-email-worker] ' . $message);
}

function completion_link(string $jobId, string $type): string
{
    $base = rtrim((string)app_config()['public_base_url'], '/');
    return $base . '/ajax/api.php?action=output_file&id='
        . rawurlencode($jobId)
        . '&type=' . rawurlencode($type);
}

function extract_job_payload(array $response): array
{
    $payload = $response['data'] ?? [];
    if (isset($payload['data']) && is_array($payload['data'])) {
        return $payload['data'];
    }
    return is_array($payload) ? $payload : [];
}

function extract_files(array $response): array
{
    $payload = $response['data'] ?? [];
    if (isset($payload['files']) && is_array($payload['files'])) {
        return $payload['files'];
    }
    if (isset($payload['data']['files']) && is_array($payload['data']['files'])) {
        return $payload['data']['files'];
    }
    return [];
}

function result_links(string $jobId, array $files): array
{
    $map = [
        'original_video' => ['original', '⬇ Video gốc'],
        'dubbed_video' => ['dubbed', '🎙️ Video đã lồng tiếng'],
        'subtitle' => ['subtitle', '📝 Phụ đề (.srt)'],
        'subtitled_video' => ['subtitled', '🎬 Video đã burn phụ đề'],
    ];

    $links = [];
    foreach ($map as $key => [$type, $label]) {
        if (!empty($files[$key])) {
            $links[] = [
                'type' => $type,
                'label' => $label,
                'url' => completion_link($jobId, $type),
            ];
        }
    }
    return $links;
}

function completion_email(string $name, string $jobId, array $links, array $job): string
{
    $displayName = trim($name) !== '' ? trim($name) : 'bạn';
    $title = trim((string)($job['title'] ?? $job['filename'] ?? 'VideoDubing AI'));
    $lines = [
        'Xin chào ' . $displayName . ',',
        '',
        'Job VideoDubing AI của bạn đã hoàn thành.',
        '',
        'Job ID: ' . $jobId,
        'Tên Job: ' . $title,
        'Số kết quả: ' . count($links),
        '',
        'TOÀN BỘ LINK KẾT QUẢ:',
    ];

    foreach ($links as $index => $link) {
        $lines[] = ($index + 1) . '. ' . $link['label'];
        $lines[] = '   ' . $link['url'];
        $lines[] = '';
    }

    $lines[] = 'Lưu ý: Link tải kết quả đi qua FrontEnd và yêu cầu User đăng nhập.';
    $lines[] = 'Backend vẫn giữ Job/file theo chính sách cleanup hiện tại của hệ thống.';
    $lines[] = '';
    $lines[] = 'Trân trọng,';
    $lines[] = 'VideoDubing AI';

    return implode("\n", $lines);
}

function mark_failed(PDO $pdo, int $id, int $attempts, string $error): void
{
    $delayMinutes = min(30, max(1, (int)pow(2, max(0, $attempts - 1))));
    $statement = $pdo->prepare(
        'UPDATE job_email_notifications
         SET status = ?, error_message = ?, next_attempt_at = DATE_ADD(NOW(), INTERVAL ? MINUTE), updated_at = NOW()
         WHERE id = ?'
    );
    $statement->execute([
        $attempts >= 5 ? 'failed' : 'pending',
        mb_substr($error, 0, 2000),
        $delayMinutes,
        $id,
    ]);
}

// Recover a worker that died while sending.
$pdo->exec(
    "UPDATE job_email_notifications
     SET status='pending', next_attempt_at=NOW(), updated_at=NOW()
     WHERE status='sending' AND updated_at < DATE_SUB(NOW(), INTERVAL 10 MINUTE)"
);

$statement = $pdo->query(
    "SELECT id, job_id, user_id, email, attempts
     FROM job_email_notifications
     WHERE attempts < {$maxAttempts}
       AND status IN ('pending','failed')
       AND (next_attempt_at IS NULL OR next_attempt_at <= NOW())
     ORDER BY created_at ASC
     LIMIT {$batchSize}"
);
$rows = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {
    $id = (int)$row['id'];

    // Claim atomically so two cron processes cannot send the same email.
    $claim = $pdo->prepare(
        "UPDATE job_email_notifications
         SET status='sending', attempts=attempts+1, last_checked_at=NOW(), updated_at=NOW()
         WHERE id=? AND status IN ('pending','failed') AND attempts < ?"
    );
    $claim->execute([$id, $maxAttempts]);
    if ($claim->rowCount() !== 1) {
        continue;
    }

    $attempts = (int)$row['attempts'] + 1;
    $jobId = (string)$row['job_id'];

    try {
        $jobResponse = api_request('GET', 'jobs/' . rawurlencode($jobId));
        if (!$jobResponse['ok']) {
            throw new RuntimeException(
                'Backend job status HTTP ' . (string)$jobResponse['status']
                . ': ' . (string)($jobResponse['data']['message'] ?? 'unknown error')
            );
        }

        $job = extract_job_payload($jobResponse);
        $status = strtolower(trim((string)($job['status'] ?? '')));

        if ($status !== 'completed') {
            // Failed/cancelled jobs are recorded but do not trigger a completion email.
            if (in_array($status, ['failed', 'cancelled'], true)) {
                $pdo->prepare(
                    'UPDATE job_email_notifications
                     SET status="sent", result_count=0, error_message=?, updated_at=NOW()
                     WHERE id=?'
                )->execute(['Job status: ' . $status . ' (no completion email required).', $id]);
            } else {
                $pdo->prepare(
                    'UPDATE job_email_notifications
                     SET status="pending", next_attempt_at=DATE_ADD(NOW(), INTERVAL 1 MINUTE), updated_at=NOW()
                     WHERE id=?'
                )->execute([$id]);
            }
            continue;
        }

        $filesResponse = api_request('GET', 'output/' . rawurlencode($jobId) . '/files');
        if (!$filesResponse['ok']) {
            throw new RuntimeException(
                'Backend output/files HTTP ' . (string)$filesResponse['status']
                . ': ' . (string)($filesResponse['data']['message'] ?? 'unknown error')
            );
        }

        $files = extract_files($filesResponse);
        $links = result_links($jobId, $files);

        if (!$links) {
            throw new RuntimeException('Job completed nhưng chưa có file kết quả để gửi email.');
        }

        // Snapshot the latest email from users when possible.
        $userStatement = $pdo->prepare('SELECT name, email FROM users WHERE id=? LIMIT 1');
        $userStatement->execute([(int)$row['user_id']]);
        $user = $userStatement->fetch(PDO::FETCH_ASSOC) ?: [];
        $email = trim((string)($user['email'] ?? $row['email']));
        $name = trim((string)($user['name'] ?? ''));
        if ($email === '') {
            throw new RuntimeException('User không có email để nhận kết quả.');
        }

        $subject = '[VideoDubing AI] Job ' . $jobId . ' đã hoàn thành';
        $body = completion_email($name, $jobId, $links, $job);

        if (!send_account_mail($email, $subject, $body)) {
            throw new RuntimeException('SMTP không gửi được email. Kiểm tra MAIL_* và log mailer.');
        }

        $pdo->prepare(
            'UPDATE job_email_notifications
             SET status="sent", email=?, result_count=?, sent_at=NOW(), error_message=NULL, updated_at=NOW()
             WHERE id=?'
        )->execute([$email, count($links), $id]);

        worker_log("sent completion email for job {$jobId} to {$email}");
    } catch (Throwable $e) {
        mark_failed($pdo, $id, $attempts, $e->getMessage());
        worker_log("job {$jobId} email attempt {$attempts} failed: {$e->getMessage()}");
    }
}
