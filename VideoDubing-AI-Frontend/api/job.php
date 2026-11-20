<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/api.php';
header('Content-Type: application/json; charset=utf-8');
$id = trim($_GET['id'] ?? '');
if ($id === '') { http_response_code(400); echo json_encode(['success'=>false,'message'=>'Job ID is required']); exit; }
try {
    $response = apiRequest('GET', '/api/v1/jobs/' . rawurlencode($id));
    http_response_code((int)$response['status']);
    echo json_encode($response['data'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(502);
    echo json_encode(['success'=>false,'message'=>$e->getMessage()], JSON_UNESCAPED_UNICODE);
}
