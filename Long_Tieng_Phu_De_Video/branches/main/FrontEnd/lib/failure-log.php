<?php
declare(strict_types=1);

/**
 * Failure-only diagnostic logger for VideoDubbing FrontEnd.
 * Never called for successful requests/jobs.
 */
require_once __DIR__ . '/auth.php';

function failure_log_uuid(): string
{
    $bytes = random_bytes(16);
    $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
    $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
}

function failure_log_meminfo(): array
{
    $result = [
        'total' => null,
        'available' => null,
        'used' => null,
    ];

    $raw = @file_get_contents('/proc/meminfo');
    if ($raw === false) return $result;

    $values = [];
    foreach (preg_split('/\r?\n/', $raw) as $line) {
        if (preg_match('/^([A-Za-z_]+):\s+(\d+)\s+kB$/', trim($line), $m)) {
            $values[$m[1]] = (int)$m[2] * 1024;
        }
    }

    $result['total'] = $values['MemTotal'] ?? null;
    $result['available'] = $values['MemAvailable'] ?? ($values['MemFree'] ?? null);
    if ($result['total'] !== null && $result['available'] !== null) {
        $result['used'] = max(0, $result['total'] - $result['available']);
    }
    return $result;
}

function failure_log_resources(): array
{
    $load = function_exists('sys_getloadavg') ? sys_getloadavg() : [null, null, null];
    $mem = failure_log_meminfo();
    $diskPath = '/';
    $diskFree = @disk_free_space($diskPath);
    $diskTotal = @disk_total_space($diskPath);
    $diskPercent = null;

    if ($diskFree !== false && $diskTotal !== false && $diskTotal > 0) {
        $diskPercent = round(($diskFree / $diskTotal) * 100, 2);
    }

    return [
        'memory_usage_bytes' => memory_get_usage(true),
        'memory_peak_bytes' => memory_get_peak_usage(true),
        'memory_limit' => ini_get('memory_limit') ?: null,
        'load_1' => $load[0] ?? null,
        'load_5' => $load[1] ?? null,
        'load_15' => $load[2] ?? null,
        'memory_total_bytes' => $mem['total'],
        'memory_available_bytes' => $mem['available'],
        'memory_used_bytes' => $mem['used'],
        'disk_free_bytes' => $diskFree !== false ? $diskFree : null,
        'disk_total_bytes' => $diskTotal !== false ? $diskTotal : null,
        'disk_free_percent' => $diskPercent,
        'hostname' => gethostname() ?: null,
        'php_version' => PHP_VERSION,
        'os_info' => php_uname(),
    ];
}

function failure_log_user_id(): ?int
{
    if (function_exists('current_user')) {
        $user = current_user();
        $id = (int)($user['id'] ?? 0);
        return $id > 0 ? $id : null;
    }
    return null;
}

function failure_log_request_payload(?array $payload): ?string
{
    if ($payload === null) return null;
    try {
        return json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE);
    } catch (Throwable $e) {
        return null;
    }
}

function record_frontend_failure(array $data): ?string
{
    try {
        $traceId = (string)($data['trace_id'] ?? failure_log_uuid());
        $resources = failure_log_resources();
        $context = is_array($data['context'] ?? null) ? $data['context'] : [];
        $context['resource_snapshot'] = $resources;

        $stmt = db()->prepare(
            'INSERT INTO frontend_failure_logs
            (trace_id,job_id,user_id,module,action,endpoint,http_method,request_url,request_payload,
             http_status,error_type,error_code,error_message,response_body,started_at,failed_at,duration_ms,
             video_url,video_duration_seconds,file_size_bytes,operation,memory_usage_bytes,memory_peak_bytes,
             memory_limit,load_1,load_5,load_15,memory_total_bytes,memory_available_bytes,memory_used_bytes,
             disk_free_bytes,disk_total_bytes,disk_free_percent,hostname,php_version,os_info,backend_name,
             backend_endpoint,curl_errno,curl_error,connect_time_ms,start_transfer_time_ms,total_time_ms,
             user_agent,ip_address,context_json)
             VALUES
            (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)'
        );

        $now = new DateTimeImmutable('now');
        $failedAt = $data['failed_at'] ?? $now->format('Y-m-d H:i:s.u');
        $startedAt = $data['started_at'] ?? null;

        $stmt->execute([
            $traceId,
            $data['job_id'] ?? null,
            $data['user_id'] ?? failure_log_user_id(),
            $data['module'] ?? 'workplace',
            $data['action'] ?? null,
            $data['endpoint'] ?? null,
            $data['http_method'] ?? null,
            $data['request_url'] ?? null,
            failure_log_request_payload($data['request_payload'] ?? null),
            isset($data['http_status']) ? (int)$data['http_status'] : null,
            $data['error_type'] ?? null,
            $data['error_code'] ?? null,
            $data['error_message'] ?? null,
            $data['response_body'] ?? null,
            $startedAt,
            $failedAt,
            isset($data['duration_ms']) ? (int)$data['duration_ms'] : null,
            $data['video_url'] ?? null,
            isset($data['video_duration_seconds']) ? (float)$data['video_duration_seconds'] : null,
            isset($data['file_size_bytes']) ? (int)$data['file_size_bytes'] : null,
            $data['operation'] ?? null,
            $resources['memory_usage_bytes'],
            $resources['memory_peak_bytes'],
            $resources['memory_limit'],
            $resources['load_1'],
            $resources['load_5'],
            $resources['load_15'],
            $resources['memory_total_bytes'],
            $resources['memory_available_bytes'],
            $resources['memory_used_bytes'],
            $resources['disk_free_bytes'],
            $resources['disk_total_bytes'],
            $resources['disk_free_percent'],
            $resources['hostname'],
            $resources['php_version'],
            $resources['os_info'],
            $data['backend_name'] ?? null,
            $data['backend_endpoint'] ?? null,
            isset($data['curl_errno']) ? (int)$data['curl_errno'] : null,
            $data['curl_error'] ?? null,
            isset($data['connect_time_ms']) ? (int)$data['connect_time_ms'] : null,
            isset($data['start_transfer_time_ms']) ? (int)$data['start_transfer_time_ms'] : null,
            isset($data['total_time_ms']) ? (int)$data['total_time_ms'] : null,
            $_SERVER['HTTP_USER_AGENT'] ?? null,
            $_SERVER['REMOTE_ADDR'] ?? null,
            json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE),
        ]);

        return $traceId;
    } catch (Throwable $e) {
        error_log('Frontend failure log write failed: ' . $e->getMessage());
        return null;
    }
}
