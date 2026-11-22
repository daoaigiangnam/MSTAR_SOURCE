const config = require('../config');

async function dispatch(jobId, task, payload = {}) {
  const response = await fetch(`${config.workerUrl}/jobs`, {
    method: 'POST',
    headers: { 'content-type': 'application/json' },
    body: JSON.stringify({ job_id: jobId, task, payload }),
  });
  if (!response.ok) {
    const text = await response.text();
    throw new Error(`Worker returned ${response.status}: ${text}`);
  }
  return response.json();
}

async function health() {
  const response = await fetch(`${config.workerUrl}/health`);
  if (!response.ok) throw new Error(`Worker health returned ${response.status}`);
  return response.json();
}

module.exports = { dispatch, health };
