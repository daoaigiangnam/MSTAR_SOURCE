const crypto = require('crypto');
const JobService = require('./job.service');
const WorkerService = require('./worker.service');

function normalizeSourceLanguage(value) {
  if (value == null) return null;
  const normalized = String(value).trim().toLowerCase();
  if (!normalized || ['auto', 'automatic', 'detect', 'detected'].includes(normalized)) {
    return null;
  }
  return normalized.replace('_', '-').split('-')[0];
}

async function create(data) {
  const id = data.id || crypto.randomUUID();
  const numericFlag = Number(data.flag ?? 1);
  const flag = numericFlag === 0 ? 0 : numericFlag === 2 ? 2 : 1;

  const hasTargetLanguage = data.target_language != null && String(data.target_language).trim() !== '';

  // Keep the existing worker pipeline unchanged:
  // - flag 0 -> download_only
  // - flag 1 without target -> download (worker keeps original media when target is absent)
  // - flag 1 with target -> download task; process_download performs dubbing
  // - flag 2 -> subtitle
  const task = flag === 0
    ? 'download_only'
    : flag === 2
      ? 'subtitle'
      : 'download';

  const job = JobService.create({
    id,
    url: data.url,
    extractor: data.extractor || null,
    type: flag === 0 ? 'download' : flag === 2 ? 'subtitle' : (data.type || 'video'),
  });

  if (!data.dispatch) return job;

  try {
    JobService.updateStatus(id, 'queued');
    const workerResult = await WorkerService.dispatch(id, task, {
      url: data.url,
      extractor: data.extractor || null,
      type: data.type || 'video',
      format: data.format || null,
      quality: data.quality || null,
      source_language: normalizeSourceLanguage(data.source_language),
      target_language: hasTargetLanguage ? String(data.target_language).trim() : null,
      tts_voice: data.tts_voice || null,
      translation_endpoint: data.translation_endpoint || null,
      whisper_model: data.whisper_model || 'small',
      keep_background: data.keep_background !== false,
      sync: data.sync !== false,
    });

    return JobService.updateStatus(id, workerResult.status || 'accepted');
  } catch (error) {
    JobService.updateStatus(id, 'failed', error.message);
    throw error;
  }
}

module.exports = { create };
