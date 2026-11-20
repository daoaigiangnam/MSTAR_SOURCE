<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/api.php';

$pageTitle = 'Video Info / Full Workflow - ' . APP_NAME;
$info = null;
$error = null;
$url = trim($_POST['url'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $url !== '') {
    try {
        $response = apiRequest('POST', '/api/v1/info', ['url' => $url]);
        if ($response['status'] >= 200 && $response['status'] < 300) {
            $info = $response['data'];
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
        <span class="eyebrow">V1 Full Workflow</span>
        <h1>Video Information</h1>
        <p>Connect directly to the VideoDubing-AI backend.</p>
    </section>

    <section class="card">
        <form method="post">
            <label for="video-url">Video URL</label>
            <div class="actions">
                <input id="video-url" name="url" type="url" value="<?= htmlspecialchars($url) ?>" placeholder="https://youtube.com/..." required>
                <button class="btn" type="reset">Clear</button>
                <button class="btn btn-primary" type="submit">Get Info</button>
            </div>
        </form>
    </section>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($info): ?>
        <section class="card video-info-card">
            <div class="video-info-grid">
                <?php if (!empty($info['thumbnail'])): ?>
                    <img class="video-thumbnail" src="<?= htmlspecialchars($info['thumbnail']) ?>" alt="Video thumbnail">
                <?php endif; ?>
                <div>
                    <span class="badge">API Response</span>
                    <h2><?= htmlspecialchars($info['title'] ?? 'Untitled video') ?></h2>
                    <dl class="metadata">
                        <dt>Extractor</dt><dd><?= htmlspecialchars($info['extractor'] ?? '-') ?></dd>
                        <dt>Duration</dt><dd><?= htmlspecialchars((string)($info['duration'] ?? '-')) ?></dd>
                        <dt>Uploader</dt><dd><?= htmlspecialchars($info['uploader'] ?? '-') ?></dd>
                        <dt>URL</dt><dd><?= htmlspecialchars($info['webpage_url'] ?? $url) ?></dd>
                    </dl>
                    <form method="post" action="../processing/jobs.php">
                        <input type="hidden" name="url" value="<?= htmlspecialchars($url) ?>">
                        <input type="hidden" name="type" value="video">
                        <button class="btn btn-primary" type="submit">🚀 Start Full Workflow</button>
                    </form>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>
<?php require __DIR__ . '/../../includes/footer.php'; ?>