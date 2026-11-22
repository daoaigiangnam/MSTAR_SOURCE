const express = require('express');
const DownloadService = require('../services/download.service');

const router = express.Router();

function normalizeUrls(body) {
  if (Array.isArray(body?.urls)) {
    return body.urls.map(value => String(value || '').trim()).filter(Boolean);
  }
  if (typeof body?.url === 'string' && body.url.trim()) {
    return [body.url.trim()];
  }
  return [];
}

router.post('/', async (req, res, next) => {
  try {
    const body = req.body || {};
    const urls = normalizeUrls(body);
    if (!urls.length) {
      return res.status(400).json({ success: false, message: 'url or urls is required' });
    }

    const numericFlag = Number(body.flag ?? 1);
    if (![0, 1, 2].includes(numericFlag)) {
      return res.status(400).json({
        success: false,
        message: 'flag must be 0 (download), 1 (dubbing), or 2 (subtitle)',
      });
    }

    // target_language is intentionally optional for both dubbing and subtitle.
    // When omitted, the worker detects the source language and uses that same
    // language as the target, so the original language is preserved.

    const jobs = [];
    for (const url of urls) {
      const job = await DownloadService.create({
        url,
        flag: numericFlag,
        extractor: body.extractor,
        type: body.type,
        format: body.format,
        quality: body.quality,
        source_language: body.source_language,
        target_language: body.target_language,
        tts_voice: body.tts_voice,
        translation_endpoint: body.translation_endpoint,
        whisper_model: body.whisper_model,
        keep_background: body.keep_background,
        sync: body.sync,
        dispatch: true,
      });
      jobs.push(job);
    }

    res.status(202).json({
      success: true,
      data: jobs.length === 1 ? jobs[0] : { flag: numericFlag, total: jobs.length, jobs },
    });
  } catch (error) {
    next(error);
  }
});

module.exports = router;
