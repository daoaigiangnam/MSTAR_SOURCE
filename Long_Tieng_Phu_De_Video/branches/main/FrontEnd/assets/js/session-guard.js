(() => {
  'use strict';

  // Job ownership/history is stored in the FrontEnd MySQL database.
  // Browser storage is used only once to migrate the previous per-user
  // local history; it is not the source of truth anymore.
  const userId = String(document.body?.dataset?.userId || '').trim();
  const HEARTBEAT_MS = 5 * 60 * 1000;
  const RESTORE_DELAY_MS = 500;
  const MAX_JOBS = 50;
  const remoteCache = new Map();
  let restoring = false;

  function jobId(job) {
    if (typeof job === 'string') return job;
    return String(job?.id || job?.job_id || '').trim();
  }

  function historyRequest(action, options = {}) {
    const method = options.method || 'GET';
    return fetch('ajax/api.php?action=' + encodeURIComponent(action), {
      method,
      headers: options.body ? { 'Content-Type': 'application/json' } : {},
      body: options.body ? JSON.stringify(options.body) : undefined,
      cache: 'no-store',
      credentials: 'same-origin'
    }).then(async r => {
      const text = await r.text();
      let data = null;
      try { data = text ? JSON.parse(text) : null; } catch (_) {}
      if (r.status === 401) {
        window.location.href = 'login.php?expired=1';
        throw new Error(data?.message || 'Phiên đăng nhập đã hết hạn.');
      }
      if (!r.ok) {
        const error = new Error(data?.message || `HTTP ${r.status}`);
        error.status = r.status;
        throw error;
      }
      return data;
    });
  }

  async function saveRemote(job) {
    const id = jobId(job);
    if (!id || !userId) return;
    const snapshot = {
      job_id: id,
      title: String(job?.title || job?.filename || 'Video').slice(0, 500),
      mode: String(job?.mode || '').slice(0, 30),
      created_at: job?.created_at || null
    };
    const signature = JSON.stringify(snapshot);
    if (remoteCache.get(id) === signature) return;
    remoteCache.set(id, signature);
    try {
      await historyRequest('job_history_save', { method: 'POST', body: snapshot });
    } catch (e) {
      remoteCache.delete(id);
      console.warn('VideoDubing: cannot save job history:', id, e);
    }
  }

  async function migrateLocalHistory() {
    if (!userId) return;
    const keys = [
      `videodubing_active_jobs_v1_user_${userId}`,
      'videodubing_active_jobs_v1'
    ];
    for (const key of keys) {
      try {
        const raw = localStorage.getItem(key);
        if (!raw) continue;
        const jobs = JSON.parse(raw);
        if (!Array.isArray(jobs)) continue;
        for (const job of jobs.slice(-MAX_JOBS)) await saveRemote(job);
        localStorage.removeItem(key);
      } catch (e) {
        console.warn('VideoDubing: local job migration failed:', e);
      }
    }
  }

  async function removeJob(id) {
    id = String(id || '').trim();
    if (!id) return;
    try {
      await historyRequest('job_history_hide', {
        method: 'POST',
        body: { job_id: id }
      });
    } catch (e) {
      console.warn('VideoDubing: cannot hide job history:', id, e);
    }
    remoteCache.delete(id);
  }

  async function rememberVisibleJobs() {
    const elements = [...document.querySelectorAll('#jobs .job[data-id]')];
    for (const el of elements) {
      const id = String(el.dataset.id || '').trim();
      if (!id) continue;
      await saveRemote({ id, title: el.querySelector('.job-head strong')?.textContent || 'Video' });
    }
  }

  async function restoreJobs() {
    if (restoring || !userId || typeof window.api !== 'function') return;
    restoring = true;
    try {
      await migrateLocalHistory();
      const d = await historyRequest('job_history_list');
      const saved = Array.isArray(d?.data?.jobs) ? d.data.jobs : (Array.isArray(d?.jobs) ? d.jobs : []);
      for (const savedJob of saved.slice(0, MAX_JOBS)) {
        const id = jobId(savedJob);
        if (!id) continue;
        try {
          const response = await window.api('job', { query: '&id=' + encodeURIComponent(id) });
          const job = response?.data || response;
          if (!job || !job.id) {
            await removeJob(id);
            continue;
          }
          const snapshot = {
            ...savedJob,
            ...job,
            id,
            mode: job.mode || savedJob.mode || ''
          };
          remoteCache.set(id, JSON.stringify({
            job_id: id,
            title: String(snapshot.title || snapshot.filename || 'Video').slice(0, 500),
            mode: String(snapshot.mode || '').slice(0, 30),
            created_at: snapshot.created_at || savedJob.created_at || null
          }));
          if (typeof window.render === 'function') window.render(snapshot);
          const status = String(snapshot.status || '').toLowerCase();
          if (!['completed', 'failed', 'cancelled', 'canceled'].includes(status) && typeof window.poll === 'function') {
            window.poll(id);
          }
        } catch (e) {
          if (e?.status === 404) {
            await removeJob(id);
            continue;
          }
          // Network/API failure must not remove history.
          console.warn('VideoDubing: cannot refresh restored job:', id, e);
        }
      }
    } catch (e) {
      console.warn('VideoDubing: cannot restore job history:', e);
    } finally {
      restoring = false;
    }
  }

  async function heartbeat() {
    if (document.hidden) return;
    try {
      const r = await fetch('ajax/api.php?action=heartbeat', {
        method: 'GET',
        cache: 'no-store',
        credentials: 'same-origin',
        headers: { 'X-VideoDubing-Heartbeat': '1' }
      });
      if (r.status === 401) {
        console.warn('VideoDubing: session expired.');
        return;
      }
      if (!r.ok) console.warn('VideoDubing: heartbeat failed:', r.status);
    } catch (e) {
      console.warn('VideoDubing: heartbeat network error:', e);
    }
  }

  window.sessionGuard = {
    saveJobSnapshot: saveRemote,
    removeJob,
    restoreJobs,
    rememberVisibleJobs,
    userId
  };

  document.addEventListener('visibilitychange', () => {
    if (!document.hidden) {
      heartbeat();
      restoreJobs();
    }
  });

  setTimeout(() => {
    restoreJobs();
    heartbeat();
  }, RESTORE_DELAY_MS);

  setInterval(heartbeat, HEARTBEAT_MS);
})();
