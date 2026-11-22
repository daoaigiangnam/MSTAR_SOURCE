const test = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const crypto = require('node:crypto');

const app = require('../src/app');
const JobService = require('../src/services/job.service');
const config = require('../src/config');

function request(pathname, options = {}) {
  return new Promise((resolve, reject) => {
    const server = app.listen(0, () => {
      const port = server.address().port;
      const http = require('node:http');
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
          const body = Buffer.concat(chunks);
          let parsed = null;
          try { parsed = JSON.parse(body.toString('utf8')); } catch { /* binary/non-JSON response */ }
          resolve({ status: res.statusCode, headers: res.headers, body: parsed, raw: body });
        });
      });
      req.on('error', err => { server.close(); reject(err); });
      if (options.body) req.write(options.body);
      req.end();
    });
  });
}

test('API health endpoint', async () => {
  const response = await request('/health');
  assert.equal(response.status, 200);
  assert.equal(response.body.status, 'ok');
});

test('API version endpoint', async () => {
  const response = await request('/api/v1');
  assert.equal(response.status, 200);
  assert.equal(response.body.version, 'v1');
});

test('Unknown job returns 404', async () => {
  const response = await request('/api/v1/jobs/does-not-exist');
  assert.equal(response.status, 404);
  assert.equal(response.body.message, 'Job not found');
});

test('Unknown output returns 404', async () => {
  const response = await request('/api/v1/output/does-not-exist');
  assert.equal(response.status, 404);
  assert.equal(response.body.message, 'Job not found');
});

test('Completed job exposes downloadable final output', async () => {
  const id = crypto.randomUUID();
  const outputDir = path.resolve(config.storagePath, 'downloads', id, 'output');
  const outputPath = path.join(outputDir, 'final.mp4');
  fs.mkdirSync(outputDir, { recursive: true });
  fs.writeFileSync(outputPath, Buffer.from('fake-mp4-output'));

  try {
    JobService.create({ id, url: 'https://example.com/video', extractor: 'test', type: 'video' });
    JobService.updateStatus(id, 'completed');
    const response = await request(`/api/v1/output/${id}`);
    assert.equal(response.status, 200);
    assert.match(response.headers['content-type'] || '', /video\/mp4/);
    assert.equal(response.raw.toString(), 'fake-mp4-output');
  } finally {
    fs.rmSync(path.resolve(config.storagePath, 'downloads', id), { recursive: true, force: true });
  }
});
