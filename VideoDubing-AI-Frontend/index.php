<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Full Workflow - ' . APP_NAME;
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/navbar.php';
require __DIR__ . '/includes/sidebar.php';
?>
<main class="app-main workflow-page">
    <section class="page-heading">
        <span class="eyebrow">VideoDubing-AI V1</span>
        <h1>Full Workflow</h1>
        <p>One workflow from video URL to the final processed video.</p>
    </section>

    <section class="card workflow-start">
        <div>
            <span class="badge">FULL PIPELINE</span>
            <h2>Start a new video job</h2>
            <p>Video Info → Download → Extract → Transcribe → Translate → TTS/Dubbing → Render → Result</p>
        </div>
        <form class="url-form" method="post" action="pages/video/download.php">
            <label for="video_url">Video URL</label>
            <div class="url-row">
                <input id="video_url" name="url" type="url" placeholder="https://youtube.com/..." required>
                <button class="btn btn-secondary" type="reset">Clear</button>
                <button class="btn btn-primary" type="submit">Get Video Info</button>
            </div>
        </form>
    </section>

    <section class="workflow-steps" aria-label="Workflow steps">
        <?php foreach (['Video Info','Download','Extract Media','Transcribe','Translate','TTS / Dubbing','Render','Final Result'] as $i => $step): ?>
            <div class="workflow-step">
                <span><?= $i + 1 ?></span>
                <strong><?= htmlspecialchars($step) ?></strong>
            </div>
        <?php endforeach; ?>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
