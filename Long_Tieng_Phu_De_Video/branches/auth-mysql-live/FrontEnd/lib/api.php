<?php
declare(strict_types=1);

$config = require __DIR__ . '/../config/config.php';

function api_request(string $method, string $path, ?array $body = null): array {
    global $config;
    $url = $config['api_base_url'] . '/' . ltrim($path, '/');
    $ch = curl_init($url);
    $headers = ['Accept: application/json'];
    $options = [CURLOPT_RETURNTRANSFER => true, CURLOPT_CUSTOMREQUEST => strtoupper($method), CURLOPT_CONNECTTIMEOUT => 10, CURLOPT_TIMEOUT => $config['request_timeout'], CURLOPT_HTTPHEADER => $headers];
    if ($body !== null) {
        $options[CURLOPT_POSTFIELDS] = json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $headers[] = 'Content-Type: application/json';
        $options[CURLOPT_HTTPHEADER] = $headers;
    }
    curl_setopt_array($ch, $options);
    $raw = curl_exec($ch);
    $errno = curl_errno($ch);
    $error = curl_error($ch);
    $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($errno) return ['ok'=>false,'status'=>502,'data'=>['success'=>false,'message'=>'API connection failed: '.$error]];
    $data = json_decode((string)$raw, true);
    if ($data === null && $raw !== '') $data = ['success'=>false,'message'=>$raw];
    return ['ok'=>$status >= 200 && $status < 300,'status'=>$status,'data'=>$data ?? []];
}

/**
 * Stream a binary response directly from the API to the browser.
 * Successful responses are never accumulated in PHP memory.
 * Error responses are buffered because they are expected to be small JSON bodies.
 */
function api_binary_stream(string $method, string $path, ?array $body = null, string $contentDisposition = 'inline'): array {
    global $config;

    $url = $config['api_base_url'] . '/' . ltrim($path, '/');
    $ch = curl_init($url);

    $requestHeaders = ['Accept: audio/*,video/*,application/octet-stream,application/json'];
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
        &$errorBody,
        $contentDisposition
    ): int {
        $trimmed = trim($line);

        if ($trimmed === '') {
            return strlen($line);
        }

        if (preg_match('/^HTTP\/\S+\s+(\d{3})/', $trimmed, $matches)) {
            $status = (int)$matches[1];
            $streaming = $status >= 200 && $status < 300;

            if ($streaming && !$headersSent) {
                http_response_code($status);
                header('Cache-Control: no-store');
                header('X-Accel-Buffering: no');
                if ($contentDisposition !== '') {
                    header('Content-Disposition: ' . $contentDisposition);
                }
                $headersSent = true;
            }
        }

        if (stripos($trimmed, 'Content-Type:') === 0) {
            $contentType = trim(substr($trimmed, strlen('Content-Type:')));
            if ($streaming && $headersSent && $contentType !== '') {
                header('Content-Type: ' . $contentType);
            }
        }

        if (stripos($trimmed, 'Content-Length:') === 0) {
            $contentLength = trim(substr($trimmed, strlen('Content-Length:')));
            if ($streaming && $headersSent && $contentLength !== '') {
                header('Content-Length: ' . $contentLength);
            }
        }

        return strlen($line);
    };

    $writeFn = static function ($ch, string $chunk) use (&$streaming, &$errorBody): int {
        if ($streaming) {
            echo $chunk;
            if (function_exists('ob_flush')) {
                @ob_flush();
            }
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
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    $errno = curl_errno($ch);
    $error = curl_error($ch);
    $curlStatus = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($status === 0) {
        $status = $curlStatus;
    }
    if ($contentType === '') {
        $contentType = (string)curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    }
    curl_close($ch);

    if ($errno || $result === false) {
        if ($streaming) {
            return ['ok'=>false,'status'=>502,'content_type'=>'application/json','body'=>''];
        }
        return [
            'ok' => false,
            'status' => 502,
            'content_type' => 'application/json',
            'body' => json_encode([
                'success' => false,
                'message' => 'API connection failed: ' . $error
            ], JSON_UNESCAPED_UNICODE)
        ];
    }

    if (!$streaming) {
        return [
            'ok' => false,
            'status' => $status ?: 502,
            'content_type' => $contentType ?: 'application/json',
            'body' => $errorBody
        ];
    }

    return ['ok'=>true,'status'=>$status,'content_type'=>$contentType,'body'=>''];
}
