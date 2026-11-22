const test = require('node:test');
const assert = require('node:assert/strict');

const JobService = require('../src/services/job.service');
const WorkerService = require('../src/services/worker.service');
const DownloadService = require('../src/services/download.service');

async function runRoutingCase(source_language, target_language) {
  const originalCreate = JobService.create;
  const originalUpdateStatus = JobService.updateStatus;
  const originalDispatch = WorkerService.dispatch;

  let dispatchedTask = null;
  let dispatchedPayload = null;

  JobService.create = data => ({ id: data.id, type: data.type });
  JobService.updateStatus = () => ({ status: 'accepted' });
  WorkerService.dispatch = async (_jobId, task, payload) => {
    dispatchedTask = task;
    dispatchedPayload = payload;
    return { status: 'accepted' };
  };

  try {
    await DownloadService.create({
      url: 'https://example.com/video',
      flag: 1,
      source_language,
      target_language,
      dispatch: true,
    });
    return { task: dispatchedTask, payload: dispatchedPayload };
  } finally {
    JobService.create = originalCreate;
    JobService.updateStatus = originalUpdateStatus;
    WorkerService.dispatch = originalDispatch;
  }
}

test('FLAG=1 Chinese -> zh-Hans is download_only', async () => {
  const result = await runRoutingCase('Chinese', 'zh-Hans');
  assert.equal(result.task, 'download_only');
  assert.equal(result.payload.source_language, 'zh');
  assert.equal(result.payload.target_language, 'zh-Hans');
});

test('FLAG=1 zh -> vi remains dubbing', async () => {
  const result = await runRoutingCase('zh', 'vi');
  assert.equal(result.task, 'download');
});

test('FLAG=1 zh -> zh-Hant remains dubbing', async () => {
  const result = await runRoutingCase('zh', 'zh-Hant');
  assert.equal(result.task, 'download');
});
