const crypto = require('crypto');
const db = require('../database');

function create(data) {
  const id = data.id || crypto.randomUUID();
  const now = new Date().toISOString();
  db.prepare(`
    INSERT INTO jobs (id, url, extractor, type, status, progress, created_at, updated_at)
    VALUES (?, ?, ?, ?, 'waiting', 0, ?, ?)
  `).run(id, data.url || null, data.extractor || null, data.type || 'video', now, now);
  return get(id);
}

function get(id) {
  return db.prepare('SELECT * FROM jobs WHERE id = ?').get(id) || null;
}

function updateStatus(id, status, error = null) {
  const now = new Date().toISOString();
  const finishedAt = ['completed', 'failed', 'cancelled'].includes(status) ? now : null;
  db.prepare(`
    UPDATE jobs SET status = ?, error = ?, finished_at = COALESCE(?, finished_at), updated_at = ?
    WHERE id = ?
  `).run(status, error, finishedAt, now, id);
  return get(id);
}

function updateProgress(id, progress, speed = null, eta = null) {
  const now = new Date().toISOString();
  db.prepare('UPDATE jobs SET progress = ?, speed = ?, eta = ?, updated_at = ? WHERE id = ?')
    .run(progress, speed, eta, now, id);
  return get(id);
}

function updateInfo(id, info = {}) {
  const now = new Date().toISOString();
  const metadata = info.metadata !== undefined
    ? JSON.stringify(info.metadata)
    : null;
  db.prepare(`
    UPDATE jobs SET
      title = COALESCE(?, title),
      thumbnail = COALESCE(?, thumbnail),
      duration = COALESCE(?, duration),
      format = COALESCE(?, format),
      quality = COALESCE(?, quality),
      filename = COALESCE(?, filename),
      filepath = COALESCE(?, filepath),
      filesize = COALESCE(?, filesize),
      metadata = COALESCE(?, metadata),
      updated_at = ?
    WHERE id = ?
  `).run(
    info.title ?? null,
    info.thumbnail ?? null,
    info.duration ?? null,
    info.format ?? null,
    info.quality ?? null,
    info.filename ?? null,
    info.filepath ?? null,
    info.filesize ?? null,
    metadata,
    now,
    id,
  );
  return get(id);
}

module.exports = { create, get, updateStatus, updateProgress, updateInfo };
