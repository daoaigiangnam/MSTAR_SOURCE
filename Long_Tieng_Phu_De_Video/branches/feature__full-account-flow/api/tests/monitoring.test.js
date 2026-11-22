const test = require('node:test');
const assert = require('node:assert/strict');
const http = require('node:http');

const app = require('../src/app');
const config = require('../src/config');
const MonitoringService = require('../src/services/monitoring.service');

function request(pathname, options = {}) {
  return new Promise((resolve, reject) => {
    const server = app.listen(0, () => {
      const port = server.address().port;
      const req = http.request({
        hostname: '127.0.0.1',
        port,
        path: pathname,
        method: options.method || 'GET',
        headers: options.headers || {},
      }, (res) => {
        const chunks = [];
        res.on('data', chunk => chunks.push(chunk));
        res.on('end', () => {
          server.close();
          let body = null;
          try { body = JSON.parse(Buffer.concat(chunks).toString('utf8')); } catch { /* ignore */ }
          resolve({ status: res.statusCode, body });
        });
      });
      req.on('error', error => { server.close(); reject(error); });
      req.end();
    });
  });
}

test('monitoring service returns runtime and job metrics', () => {
  const metrics = MonitoringService.getMetrics();
  assert.equal(metrics.service, 'videodubing-api');
  assert.equal(metrics.status, 'ok');
  assert.equal(typeof metrics.timestamp, 'string');
  assert.equal(typeof metrics.process.uptime_seconds, 'number');
  assert.equal(typeof metrics.process.memory.rss_bytes, 'number');
  assert.equal(typeof metrics.jobs.total, 'number');
  assert.equal(typeof metrics.jobs.byStatus, 'object');
});

test('monitoring endpoint requires internal token', async () => {
  const original = config.internalToken;
  config.internalToken = 'monitoring-test-secret';
  try {
    const response = await request('/api/v1/monitoring');
    assert.equal(response.status, 401);
    assert.equal(response.body.message, 'Unauthorized');
  } finally {
    config.internalToken = original;
  }
});

test('monitoring endpoint returns metrics with valid token', async () => {
  const original = config.internalToken;
  config.internalToken = 'monitoring-test-secret';
  try {
    const response = await request('/api/v1/monitoring', {
      headers: { Authorization: 'Bearer monitoring-test-secret' },
    });
    assert.equal(response.status, 200);
    assert.equal(response.body.success, true);
    assert.equal(response.body.data.service, 'videodubing-api');
    assert.equal(typeof response.body.data.jobs.total, 'number');
  } finally {
    config.internalToken = original;
  }
});
