const express = require('express');
const fs = require('fs');
const path = require('path');
const JobService = require('../services/job.service');
const config = require('../config');

const router = express.Router();

function safeStoragePath(filePath) {
  if (!filePath) return null;
  const storageRoot = path.resolve(config.storagePath);
  const resolved = path.resolve(filePath);
  const relative = path.relative(storageRoot, resolved);
  if (relative.startsWith('..') || path.isAbsolute(relative)) {
    throw new Error('Invalid output path');
  }
  return resolved;
}

function resolveOutputPath(job) {
  const storageRoot = path.resolve(config.storagePath);
  const finalPath = path.resolve(storageRoot, 'downloads', job.id, 'output', 'final.mp4');

  if (fs.existsSync(finalPath)) return finalPath;

  // Download-only jobs expose the downloaded media through the same endpoint.
  if (job.filepath) return safeStoragePath(job.filepath);
  return finalPath;
}

router.get('/:id', (req, res) => {
  const job = JobService.get(req.params.id);
  if (!job) return res.status(404).json({ success: false, message: 'Job not found' });
  if (job.status !== 'completed') {
    return res.status(409).json({ success: false, message: `Job is ${job.status}`, data: job });
  }

  let outputPath;
  try {
    outputPath = resolveOutputPath(job);
  } catch (error) {
    return res.status(400).json({ success: false, message: error.message });
  }

  if (!fs.existsSync(outputPath)) {
    return res.status(404).json({ success: false, message: 'Output file not found' });
  }

  const filename = job.filename || `${req.params.id}.mp4`;
  return res.download(outputPath, filename);
});

module.exports = router;
