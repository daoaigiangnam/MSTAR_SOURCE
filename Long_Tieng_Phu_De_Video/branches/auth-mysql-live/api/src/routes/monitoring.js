const express = require('express');
const MonitoringService = require('../services/monitoring.service');
const { requireInternalToken } = require('../middleware/auth');

const router = express.Router();

router.use(requireInternalToken);

router.get('/', (_req, res) => {
  res.json({ success: true, data: MonitoringService.getMetrics() });
});

module.exports = router;
