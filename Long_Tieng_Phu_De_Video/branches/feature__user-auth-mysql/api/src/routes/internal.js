const express = require('express');
const JobService = require('../services/job.service');
const { requireInternalToken } = require('../middleware/auth');

const router = express.Router();

router.use(requireInternalToken);

router.post('/jobs/:id/progress', (req, res) => {
  const job = JobService.get(req.params.id);
  if (!job) return res.status(404).json({ success: false, message: 'Job not found' });

  const { progress, speed = null, eta = null, status = null } = req.body || {};
  if (typeof progress !== 'number' || progress < 0 || progress > 100) {
    return res.status(400).json({ success: false, message: 'progress must be a number from 0 to 100' });
  }

  JobService.updateProgress(req.params.id, progress, speed, eta);
  if (status) JobService.updateStatus(req.params.id, status);
  res.json({ success: true, data: JobService.get(req.params.id) });
});

router.post('/jobs/:id/complete', (req, res) => {
  const job = JobService.get(req.params.id);
  if (!job) return res.status(404).json({ success: false, message: 'Job not found' });

  const body = req.body || {};
  const media = body.media || {};
  JobService.updateInfo(req.params.id, {
    title: body.title,
    duration: body.duration,
    filename: body.filename,
    filepath: body.filepath,
    filesize: body.filesize,
    metadata: {
      media,
      final_path: media.final_path || null,
      subtitle_path: media.subtitle_path || null,
      dubbed_speech_path: media.dubbed_speech_path || null,
    },
  });
  JobService.updateProgress(req.params.id, 100, null, null);
  res.json({ success: true, data: JobService.updateStatus(req.params.id, 'completed') });
});

router.post('/jobs/:id/fail', (req, res) => {
  const job = JobService.get(req.params.id);
  if (!job) return res.status(404).json({ success: false, message: 'Job not found' });
  res.json({ success: true, data: JobService.updateStatus(req.params.id, 'failed', req.body?.error || 'Worker failed') });
});

module.exports = router;
