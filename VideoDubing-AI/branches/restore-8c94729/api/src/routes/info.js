const express = require('express');
const YtDlpService = require('../services/yt-dlp.service');

const router = express.Router();

router.post('/', async (req, res, next) => {
  try {
    const { url } = req.body || {};
    if (!url) return res.status(400).json({ success: false, message: 'url is required' });

    const info = await YtDlpService.info(url);
    res.json({
      success: true,
      data: {
        id: info.id || null,
        extractor: info.extractor_key || info.extractor || null,
        title: info.title || null,
        description: info.description || null,
        thumbnail: info.thumbnail || null,
        duration: info.duration || null,
        uploader: info.uploader || null,
        channel: info.channel || null,
        webpage_url: info.webpage_url || url,
        formats: info.formats || [],
      },
    });
  } catch (error) {
    next(error);
  }
});

module.exports = router;
