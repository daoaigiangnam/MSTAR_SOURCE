const express = require('express');
const JobService = require('../services/job.service');
const DownloadService = require('../services/download.service');

const router = express.Router();

function withOutputUrl(req, job) {
  if (!job) return job;
  return {
    ...job,
    output_url: job.status === 'completed' ? `${req.protocol}://${req.get('host')}/api/v1/output/${job.id}` : null,
  };
}

router.post('/', async (req, res, next) => {
  try {
    const {
      url,
      extractor,
      type,
      format,
      quality,
      source_language,
      target_language,
      tts_voice,
      translation_endpoint,
      whisper_model,
      keep_background,
      sync,
    } = req.body || {};

    if (!url) return res.status(400).json({ success: false, message: 'url is required' });

    const job = await DownloadService.create({
      url,
      extractor,
      type,
      format,
      quality,
      source_language,
      target_language,
      tts_voice,
      translation_endpoint,
      whisper_model,
      keep_background,
      sync,
      dispatch: true,
    });

    res.status(202).json({ success: true, data: withOutputUrl(req, job) });
  } catch (error) {
    next(error);
  }
});

router.get('/:id', (req, res) => {
  const job = JobService.get(req.params.id);
  if (!job) return res.status(404).json({ success: false, message: 'Job not found' });
  res.json({ success: true, data: withOutputUrl(req, job) });
});

module.exports = router;
