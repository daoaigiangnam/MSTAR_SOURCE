<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/api.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Dashboard - ' . APP_NAME;
$apiStatus = null;
try {
    $health = apiRequest('GET', '/health');
    $apiStatus = $health['status'] >= 200 && $health['status'] < 300;
} catch (Throwable $e) {
    $apiStatus = false;
}

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/navbar.php';
require __DIR__ . '/includes/sidebar.php';
?>
<main class="app-main dashboard-page">
    <section class="page-heading">
        <span class="eyebrow">Overview</span>
        <h1>Dashboard</h1>
        <p>Manage the complete VideoDubing-AI workflow from one place.</p>
    </section>

    <section class="card-grid">
        <div class="card stat-card"><span>API</span><strong><?= $apiStatus ? 'Online' : 'Offline' ?></strong></div>
        <div class="card stat-card"><span>Workflow</span><strong>V1 Full</strong></div>
        <div class="card stat-card"><span>Processing</span><strong>Worker</strong></div>
    </section>

    <section class="card quick-start">
        <div>
            <span class="badge">V1</span>
            <h2>🚀 Start Full Workflow</h2>
            <p>Enter a video URL and continue through the backend's complete media pipeline.</p>
        </div>
        <a class="btn btn-primary" href="index.php">Start Workflow</a>
    </section>

    <section class="card workflow-summary">
        <h2>Pipeline</h2>
        <div class="pipeline-list">
            <?php foreach (['Video Info','Download','Extract Media','Transcribe','Translate','TTS / Dubbing','Render','Final Result'] as $i => $step): ?>
                <div><span><?= $i + 1 ?></span><?= htmlspecialchars($step) ?></div>
            <?php endforeach; ?>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
