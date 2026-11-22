const os = require('os');
const db = require('../database');

function getJobStats() {
  const rows = db.prepare(`
    SELECT status, COUNT(*) AS count
    FROM jobs
    GROUP BY status
    ORDER BY status
  `).all();

  const byStatus = {};
  let total = 0;
  for (const row of rows) {
    byStatus[row.status] = Number(row.count);
    total += Number(row.count);
  }

  return { total, byStatus };
}

function getMetrics() {
  const memory = process.memoryUsage();
  const jobStats = getJobStats();

  return {
    service: 'videodubing-api',
    status: 'ok',
    timestamp: new Date().toISOString(),
    process: {
      pid: process.pid,
      uptime_seconds: Math.floor(process.uptime()),
      node_version: process.version,
      memory: {
        rss_bytes: memory.rss,
        heap_used_bytes: memory.heapUsed,
        heap_total_bytes: memory.heapTotal,
        external_bytes: memory.external,
      },
    },
    system: {
      platform: process.platform,
      arch: process.arch,
      hostname: os.hostname(),
      cpu_count: os.cpus().length,
      load_average: os.loadavg(),
      total_memory_bytes: os.totalmem(),
      free_memory_bytes: os.freemem(),
    },
    jobs: jobStats,
  };
}

module.exports = { getJobStats, getMetrics };
