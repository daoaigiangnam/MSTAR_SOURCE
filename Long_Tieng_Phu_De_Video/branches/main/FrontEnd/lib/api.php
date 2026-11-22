<?php
declare(strict_types=1);

$config = require __DIR__ . '/../config/config.php';
require_once __DIR__ . '/failure-log.php';

function api_extract_job_id(string $path, ?array $body = null): ?string
{
    if (preg_match('~(?:^|/)jobs/([^/?]+)~', $path, $m)) {
        return rawurldecode($m[1]);
    }

    if (is_array($body)) {
        foreach (['job_id', 'jobId', 'id'] as $key) {
            $value = trim((string)($body[$key] ?? ''));
            if ($value !== '' && strlen($value) <= 128) return $value;
        }
    }

    return null;
}

function api_failure_operation(string $path): string
{
    $path = trim($path, '/');
    $parts = explode('/', $path);
    return $parts[0] !== '' ? $parts[0] : 'api_request';
}

function api_record_failure(
    string $method,
    string $path,
    ?array $body,
    int $status,
    string $errorType,
    ?string $errorCode,
    string $message,
    ?string $responseBody,
    float $startedAt,
    int $curlErrno,
    string $curlError,
    array $curlInfo
): void {
    global $config;

    $url = $config['api_base_url'] . '/' . ltrim($path, '/');
    $started = DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $startedAt));
    $durationMs = max(0, (int)round((microtime(true) - $startedAt) * 1000));

    record_frontend_failure([
        'job_id' => api_extract_job_id($path, $body),
        'module' => 'workplace',
        'action' => api_failure_operation($path),
        'operation' => api_failure_operation($path),
        'endpoint' => $url,
        'backend_name' => 'videodubing-api',
        'backend_endpoint' => $url,
        'http_method' => strtoupper($method),
        'request_url' => $url,
        'request_payload' => $body,
        'http_status' => $status > 0 ? $status : null,
        'error_type' => $errorType,
        'error_code' => $errorCode,
        'error_message' => $message,
        'response_body' => $responseBody,
        'started_at' => $started ? $started->format('Y-m-d H:i:s.u') : null,
        'failed_at' => (new DateTimeImmutable('now'))->format('Y-m-d H:i:s.u'),
        'duration_ms' => $durationMs,
        'curl_errno' => $curlErrno ?: null,
        'curl_error' => $curlError !== '' ? $curlError : null,
        'connect_time_ms' => isset($curlInfo['namelookup_time']) && isset($curlInfo['connect_time'])
            ? (int)round(((float)$curlInfo['connect_time'] - (float)$curlInfo['namelookup_time']) * 1000) : null,
        'start_transfer_time_ms' => isset($curlInfo['starttransfer_time']) ? (int)round((float)$curlInfo['starttransfer_time'] * 1000) : null,
        'total_time_ms' => isset($curlInfo['total_time']) ? (int)round((float)$curlInfo['total_time'] * 1000) : null,
        'video_url' => is_array($body) ? ($body['url'] ?? null) : null,
        'context' => [
            'api_path' => $path,
            'content_type' => $curlInfo['content_type'] ?? null,
            'http_version' => $curlInfo['http_version'] ?? null,
            'primary_ip' => $curlInfo['primary_ip'] ?? null,
            'request_size' => $curlInfo['request_size'] ?? null,
            'response_size' => $curlInfo['size_download'] ?? null,
        ],
    ]);
}

function api_request(string $method, string $path, ?array $body = null): array
{
    global $config;

    $url = $config['api_base_url'] . '/' . ltrim($path, '/');
    $startedAt = microtime(true);
    $ch = curl_init($url);

    $headers = [
        'Accept: application/json',
    ];

    $options = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => strtoupper($method),
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => $config['request_timeout'],
        CURLOPT_HTTPHEADER => $headers,
    ];

    if ($body !== null) {
        $headers[] = 'Content-Type: application/json';
        $options[CURLOPT_HTTPHEADER] = $headers;
        $options[CURLOPT_POSTFIELDS] = json_encode(
            $body,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR
        );
    }

    curl_setopt_array($ch, $options);

    $raw = curl_exec($ch);
    $errno = curl_errno($ch);
    $error = curl_error($ch);
    $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $info = curl_getinfo($ch);
    $contentType = (string)($info['content_type'] ?? '');
    unset($ch);

    if ($raw === false || $errno !== 0) {
        api_record_failure(
            $method,
            $path,
            $body,
            $status,
            $errno === CURLE_OPERATION_TIMEDOUT ? 'TIMEOUT' : ($errno === 104 ? 'CONNECTION_RESET' : 'CURL_ERROR'),
            $errno ? (string)$errno : null,
            'API connection failed: ' . ($error ?: 'Unknown cURL error'),
            null,
            $startedAt,
            $errno,
            $error,
            $info
        );

        return [
            'ok' => false,
            'status' => 502,
            'content_type' => 'application/json; charset=utf-8',
            'data' => [
                'success' => false,
                'message' => 'API connection failed: ' . ($error ?: 'Unknown cURL error'),
            ],
        ];
    }

    $data = json_decode((string)$raw, true);

    if (!is_array($data)) {
        api_record_failure(
            $method,
            $path,
            $body,
            $status,
            'INVALID_JSON',
            null,
            'API returned invalid JSON.',
            (string)$raw,
            $startedAt,
            0,
            '',
            $info
        );

        return [
            'ok' => false,
            'status' => $status ?: 502,
            'content_type' => 'application/json; charset=utf-8',
            'data' => [
                'success' => false,
                'message' => 'API returned invalid JSON.',
                'http_status' => $status,
                'content_type' => $contentType,
            ],
        ];
    }

    if ($status < 200 || $status >= 300) {
        api_record_failure(
            $method,
            $path,
            $body,
            $status,
            'HTTP_ERROR',
            isset($data['code']) ? (string)$data['code'] : null,
            (string)($data['message'] ?? ('API returned HTTP ' . $status)),
            (string)$raw,
            $startedAt,
            0,
            '',
            $info
        );
    }

    return [
        'ok' => $status >= 200 && $status < 300,
        'status' => $status,
        'content_type' => $contentType ?: 'application/json',
        'data' => $data,
    ];
}

/** Stream a successful binary response directly to the browser. */
function api_binary_stream(
    string $method,
    string $path,
    ?array $body = null,
    string $contentDisposition = 'inline'
): array {
    global $config;

    $url = $config['api_base_url'] . '/' . ltrim($path, '/');
    $startedAt = microtime(true);
    $ch = curl_init($url);

    $requestHeaders = [
        'Accept: audio/*,video/*,application/octet-stream,application/json',
    ];

    $status = 0;
    $contentType = '';
    $errorBody = '';
    $streaming = false;
    $headersSent = false;

    $headerFn = static function ($ch, string $line) use (
        &$status,
        &$contentType,
        &$streaming,
        &$headersSent,
        $contentDisposition
    ): int {
        $trimmed = trim($line);

        if ($trimmed === '') return strlen($line);

        if (preg_match('/^HTTP\/\S+\s+(\d{3})/', $trimmed, $matches)) {
            $status = (int)$matches[1];
            $streaming = $status >= 200 && $status < 300;

            if ($streaming && !$headersSent) {
                http_response_code($status);
                header('Cache-Control: no-store');
                header('X-Accel-Buffering: no-store');
                if ($contentDisposition !== '') header('Content-Disposition: ' . $contentDisposition);
                $headersSent = true;
            }
        }

        if (stripos($trimmed, 'Content-Type:') === 0) {
            $contentType = trim(substr($trimmed, strlen('Content-Type:')));
            if ($streaming && $headersSent && $contentType !== '') header('Content-Type: ' . $contentType);
        }

        if (stripos($trimmed, 'Content-Length:') === 0) {
            $contentLength = trim(substr($trimmed, strlen('Content-Length:')));
            if ($streaming && $headersSent && $contentLength !== '') header('Content-Length: ' . $contentLength);
        }

        return strlen($line);
    };

    $writeFn = static function ($ch, string $chunk) use (&$streaming, &$errorBody): int {
        if ($streaming) {
            echo $chunk;
            if (function_exists('ob_flush')) @ob_flush();
            flush();
        } else {
            $errorBody .= $chunk;
        }
        return strlen($chunk);
    };

    $options = [
        CURLOPT_RETURNTRANSFER => false,
        CURLOPT_CUSTOMREQUEST => strtoupper($method),
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => $config['request_timeout'],
        CURLOPT_HTTPHEADER => $requestHeaders,
        CURLOPT_HEADERFUNCTION => $headerFn,
        CURLOPT_WRITEFUNCTION => $writeFn,
    ];

    if ($body !== null) {
        $requestHeaders[] = 'Content-Type: application/json';
        $options[CURLOPT_HTTPHEADER] = $requestHeaders;
        $options[CURLOPT_POSTFIELDS] = json_encode(
            $body,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR
        );
    }

    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    $errno = curl_errno($ch);
    $error = curl_error($ch);
    $curlStatus = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $info = curl_getinfo($ch);

    if ($status === 0) $status = $curlStatus;
    if ($contentType === '') $contentType = (string)($info['content_type'] ?? '');
    unset($ch);

    if ($errno !== 0 || $result === false) {
        api_record_failure(
            $method,
            $path,
            $body,
            $status,
            $errno === CURLE_OPERATION_TIMEDOUT ? 'TIMEOUT' : ($errno === 104 ? 'CONNECTION_RESET' : 'CURL_ERROR'),
            $errno ? (string)$errno : null,
            'API connection failed: ' . ($error ?: 'Unknown cURL error'),
            $errorBody !== '' ? $errorBody : null,
            $startedAt,
            $errno,
            $error,
            $info
        );

        return [
            'ok' => false,
            'status' => 502,
            'content_type' => 'application/json; charset=utf-8',
            'body' => json_encode([
                'success' => false,
                'message' => 'API connection failed: ' . ($error ?: 'Unknown cURL error'),
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ];
    }

    if (!$streaming) {
        api_record_failure(
            $method,
            $path,
            $body,
            $status,
            'HTTP_ERROR',
            null,
            'API returned HTTP ' . ($status ?: 502),
            $errorBody,
            $startedAt,
            0,
            '',
            $info
        );

        return [
            'ok' => false,
            'status' => $status ?: 502,
            'content_type' => $contentType ?: 'application/json; charset=utf-8',
            'body' => $errorBody,
        ];
    }

    return [
        'ok' => true,
        'status' => $status,
        'content_type' => $contentType,
        'body' => '',
    ];
}
