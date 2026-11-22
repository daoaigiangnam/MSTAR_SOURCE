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

module.exports = router;
