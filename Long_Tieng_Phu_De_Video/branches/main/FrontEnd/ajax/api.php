<?php
declare(strict_types=1);

require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/api.php';

header('Cache-Control: no-store');
header('Content-Type: application/json; charset=utf-8');

$user = require_ajax_login();

$action = $_GET['action'] ?? '';

function json_response(array $data, int $status = 200): never
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function request_json_body(): array
{
    $body = json_decode((string) file_get_contents('php://input'), true);
    return is_array($body) ? $body : [];
}

function job_history_user_id(): int
{
    $current = current_user();
    return (int)($current['id'] ?? 0);
}

try {
    switch ($action) {
        case 'heartbeat':
            json_response(['success' => true, 'data' => ['alive' => true, 'server_time' => date('c')]]);

        case 'job_history_list':
            $statement = db()->prepare(
                'SELECT job_id,title,mode,is_hidden,created_at,updated_at
                 FROM job_history
                 WHERE user_id=? AND is_hidden=0
                 ORDER BY created_at DESC,id DESC
                 LIMIT 50'
            );
            $statement->execute([job_history_user_id()]);
            json_response(['success' => true, 'data' => ['jobs' => $statement->fetchAll()]]);

        case 'job_history_save':
            $body = request_json_body();
            $jobId = trim((string)($body['job_id'] ?? ''));
            if ($jobId === '' || strlen($jobId) > 128) {
                json_response(['success' => false, 'message' => 'Job ID không hợp lệ.'], 400);
            }
            $title = trim((string)($body['title'] ?? ''));
            $title = $title !== '' ? mb_substr($title, 0, 500) : null;
            $mode = trim((string)($body['mode'] ?? ''));
            $mode = $mode !== '' ? mb_substr($mode, 0, 30) : null;

            $statement = db()->prepare(
                'INSERT INTO job_history (user_id,job_id,title,mode,is_hidden)
                 VALUES (?,?,?,?,0)
                 ON DUPLICATE KEY UPDATE
                    title=COALESCE(VALUES(title),title),
                    mode=COALESCE(VALUES(mode),mode),
                    updated_at=CURRENT_TIMESTAMP'
            );
            $statement->execute([job_history_user_id(), $jobId, $title, $mode]);
            json_response(['success' => true, 'data' => ['job_id' => $jobId]]);

        case 'job_history_hide':
            $body = request_json_body();
            $jobId = trim((string)($body['job_id'] ?? ''));
            if ($jobId === '') {
                json_response(['success' => false, 'message' => 'Thiếu job ID.'], 400);
            }
            $statement = db()->prepare('UPDATE job_history SET is_hidden=1,updated_at=CURRENT_TIMESTAMP WHERE user_id=? AND job_id=?');
            $statement->execute([job_history_user_id(), $jobId]);
            json_response(['success' => true, 'data' => ['job_id' => $jobId, 'hidden' => true]]);

        case 'languages':
            $r = api_request('GET', 'languages');
            json_response($r['data'], $r['status'] ?: 502);

        case 'voices':
            $language = trim((string) ($_GET['language'] ?? ''));
            $path = 'voices' . ($language !== '' ? '?language=' . rawurlencode($language) : '');
            $r = api_request('GET', $path);
            json_response($r['data'], $r['status'] ?: 502);

        case 'preview':
            $body = request_json_body();
            $r = api_binary_stream('POST', 'voices/preview', $body, 'inline; filename="voice-preview.mp3"');
            if (!$r['ok']) {
                $error = json_decode((string) ($r['body'] ?? ''), true);
                json_response(is_array($error) ? $error : [
                    'success' => false,
                    'message' => 'Không thể tạo file nghe thử.',
                ], $r['status'] ?: 502);
            }
            exit;

        case 'tts':
            $body = request_json_body();
            $r = api_binary_stream('POST', 'voices/synthesize', $body, 'attachment; filename="videodubing-tts.mp3"');
            if (!$r['ok']) {
                $error = json_decode((string) ($r['body'] ?? ''), true);
                json_response(is_array($error) ? $error : [
                    'success' => false,
                    'message' => 'Không thể tạo file MP3.',
                ], $r['status'] ?: 502);
            }
            exit;

        case 'create':
            $body = request_json_body();
            $r = api_request('POST', 'download', $body);
            json_response($r['data'], $r['status'] ?: 502);

        case 'job':
            $id = rawurlencode((string) ($_GET['id'] ?? ''));
            if ($id === '') {
                json_response(['success' => false, 'message' => 'Thiếu job ID.'], 400);
            }
            $r = api_request('GET', 'jobs/' . $id);
            json_response($r['data'], $r['status'] ?: 502);

        case 'files':
            $id = rawurlencode((string) ($_GET['id'] ?? ''));
            if ($id === '') {
                json_response(['success' => false, 'message' => 'Thiếu job ID.'], 400);
            }
            $r = api_request('GET', 'output/' . $id . '/files');
            json_response($r['data'], $r['status'] ?: 502);

        case 'output_file':
            $id = rawurlencode((string) ($_GET['id'] ?? ''));
            $type = (string) ($_GET['type'] ?? '');
            if ($id === '') {
                json_response(['success' => false, 'message' => 'Thiếu job ID.'], 400);
            }
            if (!in_array($type, ['original', 'subtitle', 'subtitled', 'dubbed'], true)) {
                json_response(['success' => false, 'message' => 'Loại file không hợp lệ.'], 400);
            }
            $fallback = [
                'original' => $id . '.mp4',
                'subtitle' => 'subtitles.srt',
                'subtitled' => 'subtitled.mp4',
                'dubbed' => 'final.mp4',
            ];
            $r = api_binary_stream(
                'GET',
                'output/' . $id . '/' . $type,
                null,
                'attachment; filename="' . $fallback[$type] . '"'
            );
            if (!$r['ok']) {
                $error = json_decode((string) ($r['body'] ?? ''), true);
                json_response(is_array($error) ? $error : [
                    'success' => false,
                    'message' => 'Không thể tải file kết quả.',
                ], $r['status'] ?: 502);
            }
            exit;

        case 'output':
            $id = rawurlencode((string) ($_GET['id'] ?? ''));
            if ($id === '') {
                json_response(['success' => false, 'message' => 'Thiếu job ID.'], 400);
            }
            $r = api_binary_stream('GET', 'output/' . $id, null, 'attachment; filename="' . $id . '.mp4"');
            if (!$r['ok']) {
                $error = json_decode((string) ($r['body'] ?? ''), true);
                json_response(is_array($error) ? $error : [
                    'success' => false,
                    'message' => 'Không thể tải file kết quả.',
                ], $r['status'] ?: 502);
            }
            exit;

        default:
            json_response(['success' => false, 'message' => 'Unknown action.'], 404);
    }
} catch (Throwable $e) {
    error_log('VideoDubing frontend API proxy error: ' . $e->getMessage());
    json_response([
        'success' => false,
        'message' => 'Lỗi máy chủ khi xử lý yêu cầu.',
    ], 500);
}
