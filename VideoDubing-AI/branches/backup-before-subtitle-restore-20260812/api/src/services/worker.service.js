const config = require('../config');

const DISPATCH_RETRIES = 3;
const DISPATCH_RETRY_DELAY_MS = 1000;

function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

function isRetryableNetworkError(error) {
  const code = error?.cause?.code || error?.code;
  return ['ECONNREFUSED', 'ECONNRESET', 'EAI_AGAIN', 'ENOTFOUND', 'UND_ERR_CONNECT_TIMEOUT'].includes(code);
}

async function dispatch(jobId, task, payload = {}) {
  const requestBody = JSON.stringify({ job_id: jobId, task, payload });
  let lastError;

  for (let attempt = 1; attempt <= DISPATCH_RETRIES; attempt += 1) {
    try {
      const response = await fetch(`${config.workerUrl}/jobs`, {
        method: 'POST',
        headers: { 'content-type': 'application/json' },
        body: requestBody,
      });

      if (!response.ok) {
        const text = await response.text();
        throw new Error(`Worker returned ${response.status}: ${text}`);
      }
      return response.json();
    } catch (error) {
      lastError = error;
      if (!isRetryableNetworkError(error) || attempt === DISPATCH_RETRIES) {
        throw error;
      }
      await sleep(DISPATCH_RETRY_DELAY_MS * attempt);
    }
  }

  throw lastError;
}

async function health() {
  const response = await fetch(`${config.workerUrl}/health`);
  if (!response.ok) throw new Error(`Worker health returned ${response.status}`);
  return response.json();
}

module.exports = { dispatch, health };
