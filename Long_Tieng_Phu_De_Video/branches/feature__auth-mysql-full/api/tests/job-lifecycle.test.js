const test = require('node:test');
const assert = require('node:assert/strict');
const JobService = require('../src/services/job.service');
const internalRouter = require('../src/routes/internal');
const config = require('../src/config');
const express = require('express');

function makeApp() {
  const app = express();
  app.use(express.json());
  app.use('/internal', internalRouter);
  return app;
}

function request(app, method, path, body) {
  return new Promise((resolve, reject) => {
    const server = app.listen(0, () => {
      const port = server.address().port;
      const http = require('node:http');
      const data = JSON.stringify(body);
      const req = http.request({
        hostname: '127.0.0.1',
        port,
        path,
        method,
        headers: {
          'content-type': 'application/json',
          'content-length': Buffer.byteLength(data),
          authorization: `Bearer ${config.internalToken}`,
        },
      }, res => {
        let text = '';
        res.on('data', c => { text += c; });
        res.on('end', () => {
          server.close();
          resolve({ status: res.statusCode, body: JSON.parse(text) });
        });
      });
      req.on('error', e => { server.close(); reject(e); });
      req.write(data);
      req.end();
    });
  });
}

test('job lifecycle accepts progress and completion callbacks', async () => {
  const originalToken = config.internalToken;
  config.internalToken = 'test-internal-token';

  try {
    const id = `smoke-${Date.now()}`;
    JobService.create({ id, url: 'https://example.com/video', type: 'video' });
    const app = makeApp();

    const progress = await request(app, 'POST', `/internal/jobs/${id}/progress`, {
      progress: 50,
      status: 'processing',
    });
    assert.equal(progress.status, 200);
    assert.equal(progress.body.data.progress, 50);
    assert.equal(progress.body.data.status, 'processing');

    const complete = await request(app, 'POST', `/internal/jobs/${id}/complete`, {
      final_path: '/app/storage/final.mp4',
    });
    assert.equal(complete.status, 200);
    assert.equal(complete.body.data.status, 'completed');
    assert.equal(complete.body.data.progress, 100);
  } finally {
    config.internalToken = originalToken;
  }
});
