<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/api.php';
$pageTitle = 'Job Detail - ' . APP_NAME;
$jobId = trim($_GET['id'] ?? ''); $job = null; $error = null;
if ($jobId !== '') { try { $response = apiRequest('GET', '/api/v1/jobs/' . rawurlencode($jobId)); if ($response['status'] >= 200 && $response['status'] < 300) $job = $response['data']; else $error = $response['data']['message'] ?? 'Backend returned HTTP ' . $response['status']; } catch (Throwable $e) { $error = $e->getMessage(); } }
require __DIR__ . '/../../includes/header.php'; require __DIR__ . '/../../includes/navbar.php'; require __DIR__ . '/../../includes/sidebar.php';
?>
<main class="app-main">
<section class="page-header"><span class="eyebrow">Job Monitor</span><h1>Processing Status</h1><p>Live status from the VideoDubing-AI backend.</p></section>
<?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php elseif (!$job): ?><div class="alert">Job ID is required.</div>
<?php else: $status=$job['status']??'unknown'; $progress=(float)($job['progress']??0); ?>
<section class="card" data-job-monitor data-job-id="<?= htmlspecialchars($jobId) ?>">
<div class="job-head"><span class="badge" data-job-status><?= htmlspecialchars(strtoupper($status)) ?></span><code><?= htmlspecialchars($jobId) ?></code></div>
<h2>🚀 Full Workflow</h2><div class="progress-track"><div class="progress-value" data-job-bar style="width:<?= max(0,min(100,$progress)) ?>%"></div></div><p class="progress-label" data-job-progress><?= htmlspecialchars((string)$progress) ?>%</p>
<div class="pipeline-steps"><?php foreach (['Download','Extract Media','Separate Audio','Transcribe','Translate','TTS / Dubbing','Render','Final Result'] as $i=>$step): ?><div class="pipeline-step" data-step><span><?= $i+1 ?></span><strong><?= htmlspecialchars($step) ?></strong></div><?php endforeach; ?></div>
<div class="alert alert-error" data-job-error><?= !empty($job['error']) ? htmlspecialchars($job['error']) : '' ?></div>
<a class="btn btn-primary" data-job-result <?= $status === 'completed' ? '' : 'hidden' ?> href="<?= $status === 'completed' ? '../../api/output.php?id=' . urlencode($jobId) : '#' ?>">🎬 Download Result</a>
</section><?php endif; ?></main>
<script src="../../assets/js/job-monitor.js"></script>
<?php require __DIR__ . '/../../includes/footer.php'; ?>