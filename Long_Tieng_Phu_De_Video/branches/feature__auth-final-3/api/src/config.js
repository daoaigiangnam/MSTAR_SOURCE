const path = require('path');

module.exports = {
  port: Number(process.env.PORT || 3000),
  databasePath: process.env.DATABASE_PATH || path.resolve(__dirname, '../data/videodubing.db'),
  storagePath: process.env.STORAGE_PATH || path.resolve(__dirname, '../../storage'),
  workerUrl: process.env.WORKER_URL || 'http://worker:8000',
  internalToken: process.env.INTERNAL_TOKEN || '',
};
