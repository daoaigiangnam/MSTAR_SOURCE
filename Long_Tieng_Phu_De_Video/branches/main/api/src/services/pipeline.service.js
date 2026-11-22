const crypto = require('crypto');
const JobService = require('./job.service');
const WorkerService = require('./worker.service');

const STAGES = new Set(['extract', 'separate', 'transcribe', 'translate', 'tts', 'render']);

async function dispatch(stage, payload = {}, options = {}) {
  if (!STAGES.has(stage)) throw new Error(`Unsupported pipeline stage: ${stage}`);
  const id = options.job_id || crypto.randomUUID();
  const job = JobService.create({
    id,
    url: options.url || null,
    extractor: options.extractor || null,
    type: options.type || stage,
  });
  JobService.updateStatus(id, 'queued');

  try {
    await WorkerService.dispatch(id, stage, payload);
    return JobService.get(id);
  } catch (error) {
    JobService.updateStatus(id, 'failed', error.message);
    throw error;
  }
}

module.exports = { dispatch, STAGES };
