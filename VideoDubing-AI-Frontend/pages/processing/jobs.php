<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/api.php';

$pageTitle = 'Processing Jobs - ' . APP_NAME;
$url = trim($_POST['url'] ?? '');
$job = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $url !== '') {
    try {
        $payload = [
            'url' => $url,
            'extractor' => trim($_POST['extractor'] ?? ''),
            'type' => trim($_POST['type'] ?? 'video'),
            'format' => trim($_POST['format'] ?? ''),
            'quality' => trim($_POST['quality'] ?? '')
        ];
        $payload = array_filter($payload, static fn($value) => $value !== '');
        $response = apiRequest('POST', '/api/v1/jobs', $payload);
        if ($response['status'] >= 200 && $response['status'] < 300) {
            $job = $response['data'];
        } else {
            $error = $response['data']['message'] ?? 'Backend returned HTTP ' . $response['status'];
        }
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/navbar.php';
require __DIR__ . '/../../includes/sidebar.php';
?>
<main class="app-main">
    <section class="page-header">
        <span class="eyebrow">V1</span>
        <h1>Full Workflow Job</h1>
        <p>Create and monitor a real backend processing job.</p>
    </section>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($job): ?>
        <section class="card">
            <span class="badge">Job Created</span>
            <h2><?= htmlspecialchars($job['id'] ?? $job['job_id'] ?? 'Unknown') ?></h2>
            <p>Status: <strong><?= htmlspecialchars($job['status'] ?? 'waiting') ?></strong></p>
            <p>Progress: <strong><?= htmlspecialchars((string)($job['progress'] ?? 0)) ?>%</strong></p>
            <a class="btn btn-primary" href="job.php?id=<?= urlencode($job['id'] ?? $job['job_id'] ?? '') ?>">View Job</a>
        </section>
    <?php else: ?>
        <section class="card">
            <form method="post">
                <label for="job-url">Video URL</label>
                <input id="job-url" name="url" type="url" value="<?= htmlspecialchars($url) ?>" required>
                <div class="actions">
                    <button class="btn btn-primary" type="submit">🚀 Create Full Workflow Job</button>
                </div>
            </form>
        </section>
    <?php endif; ?>
</main>
<?php require __DIR__ . '/../../includes/footer.php'; ?>