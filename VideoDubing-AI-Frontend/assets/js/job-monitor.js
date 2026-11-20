document.addEventListener('DOMContentLoaded', () => {
    const monitor = document.querySelector('[data-job-monitor]');
    if (!monitor) return;
    const jobId = monitor.dataset.jobId;
    if (!jobId) return;
    const statusEl = monitor.querySelector('[data-job-status]');
    const progressEl = monitor.querySelector('[data-job-progress]');
    const barEl = monitor.querySelector('[data-job-bar]');
    const errorEl = monitor.querySelector('[data-job-error]');
    const resultEl = monitor.querySelector('[data-job-result]');
    const steps = [...monitor.querySelectorAll('[data-step]')];
    const terminal = ['completed', 'failed', 'error', 'cancelled'];
    function normalize(value) { return String(value || '').toLowerCase().replace(/[\s_-]+/g, ''); }
    function updateSteps(job) {
        const raw = normalize(job.status);
        const progress = Number(job.progress || 0);
        let current = Math.min(7, Math.floor(progress / 12.5));
        if (raw.includes('download')) current = 0;
        else if (raw.includes('extract')) current = 1;
        else if (raw.includes('separat')) current = 2;
        else if (raw.includes('transcrib')) current = 3;
        else if (raw.includes('translat')) current = 4;
        else if (raw.includes('tts') || raw.includes('dubb')) current = 5;
        else if (raw.includes('render')) current = 7;
        else if (raw.includes('complete')) current = 8;
        steps.forEach((step, index) => {
            step.classList.toggle('is-complete', index < current || raw.includes('complete'));
            step.classList.toggle('is-active', index === current && !raw.includes('complete'));
        });
    }
    async function poll() {
        try {
            const response = await fetch(`../../api/job.php?id=${encodeURIComponent(jobId)}`, { headers: { 'Accept': 'application/json' }, cache: 'no-store' });
            const payload = await response.json();
            const job = payload.data || payload.job || payload;
            const status = String(job.status || 'unknown');
            const progress = Math.max(0, Math.min(100, Number(job.progress || 0)));
            statusEl.textContent = status.toUpperCase();
            progressEl.textContent = `${progress}%`;
            barEl.style.width = `${progress}%`;
            if (job.error) errorEl.textContent = job.error;
            updateSteps(job);
            if (status.toLowerCase() === 'completed') {
                resultEl.hidden = false;
                resultEl.href = `../../api/output.php?id=${encodeURIComponent(jobId)}`;
            }
            if (!terminal.includes(status.toLowerCase())) window.setTimeout(poll, 3000);
        } catch (error) {
            errorEl.textContent = `Monitoring error: ${error.message}`;
            window.setTimeout(poll, 5000);
        }
    }
    poll();
});
