CREATE TABLE IF NOT EXISTS jobs (
  id TEXT PRIMARY KEY,
  url TEXT,
  extractor TEXT,
  type TEXT,
  title TEXT,
  thumbnail TEXT,
  duration INTEGER,
  format TEXT,
  quality TEXT,
  filename TEXT,
  filepath TEXT,
  filesize INTEGER,
  progress REAL DEFAULT 0,
  speed TEXT,
  eta TEXT,
  status TEXT DEFAULT 'waiting',
  retry_count INTEGER DEFAULT 0,
  worker TEXT,
  metadata TEXT,
  error TEXT,
  created_at TEXT,
  updated_at TEXT,
  finished_at TEXT
);

CREATE INDEX IF NOT EXISTS idx_jobs_status ON jobs(status);
CREATE INDEX IF NOT EXISTS idx_jobs_created ON jobs(created_at);
CREATE INDEX IF NOT EXISTS idx_jobs_extractor ON jobs(extractor);
