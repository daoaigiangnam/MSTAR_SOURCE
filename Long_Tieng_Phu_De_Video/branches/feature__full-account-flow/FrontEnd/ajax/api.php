<?php
declare(strict_types=1);
require_once __DIR__ . '/../lib/auth.php';
require_login();
require_once __DIR__ . '/../lib/api.php';
header('Cache-Control: no-store');
$action = $_GET['action'] ?? '';
try {
    if ($action === 'languages') { $r=api_request('GET','languages'); http_response_code($r['status']); header('Content-Type: application/json; charset=utf-8'); echo json_encode($r['data'],JSON_UNESCAPED_UNICODE); exit; }
    if ($action === 'voices') { $language=trim((string)($_GET['language']??'')); $r=api_request('GET','voices'.($language?'?language='.rawurlencode($language):'')); http_response_code($r['status']); header('Content-Type: application/json; charset=utf-8'); echo json_encode($r['data'],JSON_UNESCAPED_UNICODE); exit; }
    if ($action === 'preview') { $body=json_decode(file_get_contents('php://input'),true)?:[]; $r=api_binary_stream('POST','voices/preview',$body,'inline; filename="voice-preview.mp3"'); if(!$r['ok']){http_response_code($r['status']?:502);header('Content-Type: application/json; charset=utf-8');echo $r['body'];} exit; }
    if ($action === 'create') { $body=json_decode(file_get_contents('php://input'),true)?:[]; $r=api_request('POST','download',$body); http_response_code($r['status']); header('Content-Type: application/json; charset=utf-8'); echo json_encode($r['data'],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); exit; }
    if ($action === 'job') { $id=rawurlencode((string)($_GET['id']??'')); $r=api_request('GET','jobs/'.$id); http_response_code($r['status']); header('Content-Type: application/json; charset=utf-8'); echo json_encode($r['data'],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); exit; }
    if ($action === 'output') { $id=rawurlencode((string)($_GET['id']??'')); $r=api_binary_stream('GET','output/'.$id,null,'attachment; filename="'.$id.'.mp4"'); if(!$r['ok']){http_response_code($r['status']?:502);header('Content-Type: application/json; charset=utf-8');echo $r['body'];} exit; }
    http_response_code(404); header('Content-Type: application/json; charset=utf-8'); echo json_encode(['success'=>false,'message'=>'Unknown action']);
} catch(Throwable $e){ http_response_code(500); header('Content-Type: application/json; charset=utf-8'); echo json_encode(['success'=>false,'message'=>$e->getMessage()],JSON_UNESCAPED_UNICODE); }
