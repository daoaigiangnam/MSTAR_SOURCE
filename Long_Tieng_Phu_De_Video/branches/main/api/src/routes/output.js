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
  const finalPath = path.resolve(storageRoot, 'downloads', job.id, 'output', 'subtitled.mp4');
  const dubbedPath = path.resolve(storageRoot, 'downloads', job.id, 'output', 'final.mp4');

  if (fs.existsSync(finalPath)) return finalPath;
  if (fs.existsSync(dubbedPath)) return dubbedPath;

  if (job.filepath) return safeStoragePath(job.filepath);
  return finalPath;
}

function firstExistingFile(candidates) {
  for (const candidate of candidates) {
    if (!candidate) continue;
    try {
      const resolved = safeStoragePath(candidate);
      if (resolved && fs.existsSync(resolved) && fs.statSync(resolved).isFile()) {
        return resolved;
      }
    } catch (_) {
      // Ignore invalid metadata paths and continue with known fallback paths.
    }
  }
  return null;
}

function resolveJobFile(job, type) {
  const storageRoot = path.resolve(config.storagePath);
  const jobRoot = path.resolve(storageRoot, 'downloads', job.id);
  const metadata = job.metadata || {};
  const media = metadata.media || {};

  if (type === 'original') {
    const metadataCandidates = [
      media.download_path,
      media.original_video_path,
      job.filepath,
    ];

    for (const candidate of metadataCandidates) {
      const metadataFile = firstExistingFile([candidate]);
      if (!metadataFile) continue;
      const relative = path.relative(jobRoot, metadataFile);
      const parts = relative.split(path.sep);
      if (parts[0] !== 'output' && parts[0] !== 'media') return metadataFile;
    }

    if (fs.existsSync(jobRoot)) {
      const files = fs.readdirSync(jobRoot, { withFileTypes: true })
        .filter((entry) => entry.isFile())
        .map((entry) => path.join(jobRoot, entry.name))
        .filter((file) => path.extname(file).toLowerCase() === '.mp4');

      if (files.length > 0) return files[0];
    }

    return null;
  }

  if (type === 'subtitle') {
    return firstExistingFile([
      path.join(jobRoot, 'media', 'transcript', 'subtitles.srt'),
      media.subtitle_path,
      metadata.subtitle_path,
    ]);
  }

  if (type === 'subtitled') {
    return firstExistingFile([
      path.join(jobRoot, 'output', 'subtitled.mp4'),
      media.final_path,
      metadata.final_path,
    ]);
  }

  if (type === 'dubbed') {
    return firstExistingFile([
      path.join(jobRoot, 'output', 'final.mp4'),
      media.final_path,
      metadata.final_path,
    ]);
  }

  return null;
}

function publicFileUrl(req, id, type) {
  return `${req.protocol}://${req.get('host')}/api/v1/output/${encodeURIComponent(id)}/${type}`;
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

router.get('/:id/files', (req, res) => {
  const job = JobService.get(req.params.id);
  if (!job) return res.status(404).json({ success: false, message: 'Job not found' });
  if (job.status !== 'completed') {
    return res.status(409).json({ success: false, message: `Job is ${job.status}` });
  }

  const files = {};
  const definitions = [
    ['original_video', 'original'],
    ['subtitle', 'subtitle'],
    ['subtitled_video', 'subtitled'],
    ['dubbed_video', 'dubbed'],
  ];

  for (const [key, type] of definitions) {
    const filePath = resolveJobFile(job, type);
    if (!filePath) continue;

    files[key] = {
      filename: path.basename(filePath),
      filesize: fs.statSync(filePath).size,
      url: publicFileUrl(req, req.params.id, type),
    };
  }

  return res.json({
    success: true,
    job_id: req.params.id,
    status: job.status,
    files,
  });
});

router.get('/:id/:type', (req, res) => {
  const job = JobService.get(req.params.id);
  if (!job) return res.status(404).json({ success: false, message: 'Job not found' });
  if (job.status !== 'completed') {
    return res.status(409).json({ success: false, message: `Job is ${job.status}` });
  }

  const allowed = new Set(['original', 'subtitle', 'subtitled', 'dubbed']);
  if (!allowed.has(req.params.type)) {
    return res.status(400).json({ success: false, message: 'Unsupported file type' });
  }

  const filePath = resolveJobFile(job, req.params.type);
  if (!filePath) {
    return res.status(404).json({ success: false, message: 'Requested file not found' });
  }

  const fallbackNames = {
    original: job.filename || `${req.params.id}.mp4`,
    subtitle: 'subtitles.srt',
    subtitled: 'subtitled.mp4',
    dubbed: 'final.mp4',
  };

  return res.download(filePath, fallbackNames[req.params.type]);
});

module.exports = router;
