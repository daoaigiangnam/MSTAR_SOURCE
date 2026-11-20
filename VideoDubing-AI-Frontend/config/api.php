<?php

require_once __DIR__ . '/config.php';

function apiRequest(string $method, string $endpoint, array $data = []): array
{
    $url = rtrim(API_BASE_URL, '/') . '/' . ltrim($endpoint, '/');
    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => strtoupper($method),
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Content-Type: application/json'
        ],
        CURLOPT_TIMEOUT => 60
    ]);

    if ($data !== []) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new RuntimeException($error);
    }

    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'status' => $status,
        'data' => json_decode($response, true)
    ];
}
