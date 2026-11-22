const crypto = require('crypto');
const JobService = require('./job.service');
const WorkerService = require('./worker.service');

function normalizeLanguage(value) {
  if (value == null) return null;
  const normalized = String(value).trim().toLowerCase().replace(/_/g, '-');
  if (!normalized || ['auto', 'automatic', 'detect', 'detected'].includes(normalized)) {
    return null;
  }

  // UI/source language may arrive as a display name instead of an ISO code.
  // Keep this mapping intentionally narrow so existing multilingual routing
  // remains unchanged. In particular, Chinese == zh == zh-Hans.
  const aliases = {
    chinese: 'zh',
    '中文': 'zh',
  };
  const canonical = aliases[normalized] || normalized;

  return canonical
    .split('-')
    .map((part, index) => index === 0 ? part : (part.length === 4 ? part[0].toUpperCase() + part.slice(1) : part.toUpperCase()))
    .join('-');
}

function languageIdentity(value) {
  const normalized = normalizeLanguage(value);
  if (!normalized) return null;

  const [family, scriptOrRegion] = normalized.split('-');
  if (family !== 'zh') return family;

  const variant = String(scriptOrRegion || '').toLowerCase();
  return ['hant', 'tw', 'hk', 'mo'].includes(variant) ? 'zh-Hant' : 'zh-Hans';
}

function sameLanguage(source, target) {
  const sourceIdentity = languageIdentity(source);
  const targetIdentity = languageIdentity(target);
  return Boolean(sourceIdentity && targetIdentity && sourceIdentity === targetIdentity);
}

async function create(data) {
  const id = data.id || crypto.randomUUID();
  const numericFlag = Number(data.flag ?? 1);
  const flag = numericFlag === 0 ? 0 : numericFlag === 2 ? 2 : 1;

  // FLAG=1 + source/target are semantically the same language means there is
  // nothing to dub. This is especially important for Chinese: the source may
  // be supplied as the UI label "Chinese" while the target is "zh-Hans".
  // Keep this change isolated to FLAG=1; all other language cases are unchanged.
  const sameSourceTarget = flag === 1 && sameLanguage(data.source_language, data.target_language);
  const task = flag === 0 || sameSourceTarget ? 'download_only' : flag === 2 ? 'subtitle' : 'download';

  const job = JobService.create({
    id,
    url: data.url,
    extractor: data.extractor || null,
    type: flag === 0 || sameSourceTarget ? 'download' : flag === 2 ? 'subtitle' : (data.type || 'video'),
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
      // Preserve script/region tags such as zh-Hans. Worker separately maps
      // them to the base code only when calling Whisper.
      source_language: normalizeLanguage(data.source_language),
      target_language: normalizeLanguage(data.target_language),
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
