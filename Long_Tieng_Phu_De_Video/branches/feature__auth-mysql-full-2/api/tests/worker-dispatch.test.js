const test = require('node:test');
const assert = require('node:assert/strict');

const WorkerService = require('../src/services/worker.service');

test('worker dispatch sends asynchronous job payload', async () => {
  const originalFetch = global.fetch;
  let request;
  global.fetch = async (url, options) => {
    request = { url, options };
    return { ok: true, json: async () => ({ success: true, job_id: 'job-1', status: 'accepted' }) };
  };

  try {
    const result = await WorkerService.dispatch('job-1', 'download', { url: 'https://example.com/test.mp4' });
    assert.equal(result.status, 'accepted');
    assert.equal(request.url.endsWith('/jobs'), true);
    assert.equal(request.options.method, 'POST');
    const body = JSON.parse(request.options.body);
    assert.equal(body.job_id, 'job-1');
    assert.equal(body.task, 'download');
    assert.equal(body.payload.url, 'https://example.com/test.mp4');
  } finally {
    global.fetch = originalFetch;
  }
});
