const express = require('express');
const config = require('../config');

const router = express.Router();

router.get('/', async (_req, res, next) => {
  try {
    const response = await fetch(`${config.workerUrl}/languages`);
    const data = await response.json();
    if (!response.ok) return res.status(response.status).json(data);
    res.json(data);
  } catch (error) {
    next(error);
  }
});

module.exports = router;
