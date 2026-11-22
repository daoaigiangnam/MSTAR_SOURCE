const express = require('express');
const helmet = require('helmet');
const compression = require('compression');
const cors = require('cors');
const jobsRouter = require('./routes/jobs');
const infoRouter = require('./routes/info');
const downloadRouter = require('./routes/download');
const outputRouter = require('./routes/output');
const monitoringRouter = require('./routes/monitoring');
const pipelineRouter = require('./routes/pipeline');
const voicesRouter = require('./routes/voices');
const languagesRouter = require('./routes/languages');
const internalRouter = require('./routes/internal');
const WorkerService = require('./services/worker.service');
const config = require('./config');

const app = express();
app.use(helmet());
app.use(compression());
app.use(cors());
app.use(express.json({ limit: '25mb' }));

app.get('/health', (_req, res) => {
  res.json({ success: true, service: 'videodubing-api', status: 'ok' });
});

app.get('/api/v1', (_req, res) => {
  res.json({
    success: true,
    name: 'VideoDubing-AI API',
    version: 'v1',
    features: {
      multilingual_dubbing: true,
      same_language_passthrough: true,
      stage_apis: ['extract', 'separate', 'transcribe', 'translate', 'tts', 'render'],
      language_api: true,
      voice_api: true,
    },
  });
});

app.get('/api/v1/worker/health', async (_req, res, next) => {
  try { res.json({ success: true, data: await WorkerService.health() }); }
  catch (error) { next(error); }
});

app.use('/api/v1/jobs', jobsRouter);
app.use('/api/v1/info', infoRouter);
app.use('/api/v1/download', downloadRouter);
app.use('/api/v1/output', outputRouter);
app.use('/api/v1/monitoring', monitoringRouter);
app.use('/api/v1/pipeline', pipelineRouter);
app.use('/api/v1/languages', languagesRouter);
app.use('/api/v1/voices', voicesRouter);
app.use('/internal', internalRouter);

app.use((err, _req, res, _next) => {
  console.error(err);
  res.status(500).json({ success: false, message: err.message || 'Internal server error' });
});

module.exports = app;

if (require.main === module) {
  app.listen(config.port, () => console.log(`VideoDubing API listening on ${config.port}`));
}
