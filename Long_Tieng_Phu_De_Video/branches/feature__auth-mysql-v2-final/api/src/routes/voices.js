const express = require('express');
const config = require('../config');

const router = express.Router();

router.get('/', async (req, res, next) => {
  try {
    const language = req.query.language ? `?language=${encodeURIComponent(req.query.language)}` : '';
    const response = await fetch(`${config.workerUrl}/voices${language}`);
    const data = await response.json();
    if (!response.ok) return res.status(response.status).json(data);
    res.json(data);
  } catch (error) {
    next(error);
  }
});

router.post('/preview', async (req, res, next) => {
  try {
    const response = await fetch(`${config.workerUrl}/voices/preview`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(req.body || {}),
    });

    const contentType = response.headers.get('content-type') || '';
    if (!response.ok || !contentType.startsWith('audio/')) {
      const text = await response.text();
      let data;
      try { data = JSON.parse(text); } catch { data = { success: false, message: text || 'Voice preview failed' }; }
      return res.status(response.status).json(data);
    }

    const audio = Buffer.from(await response.arrayBuffer());
    res.set('Content-Type', contentType);
    res.set('Content-Disposition', 'inline; filename="voice-preview.mp3"');
    res.set('Cache-Control', 'no-store');
    res.send(audio);
  } catch (error) {
    next(error);
  }
});

module.exports = router;
