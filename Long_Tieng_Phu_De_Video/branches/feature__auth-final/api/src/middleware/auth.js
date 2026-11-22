const crypto = require('crypto');
const config = require('../config');

function getBearerToken(req) {
  const authorization = req.get('authorization') || '';
  const match = authorization.match(/^Bearer\s+(.+)$/i);
  return match ? match[1].trim() : '';
}

function requireInternalToken(req, res, next) {
  if (!config.internalToken) {
    return res.status(503).json({
      success: false,
      message: 'Internal authentication is not configured',
    });
  }

  const provided = getBearerToken(req);
  const expected = Buffer.from(config.internalToken, 'utf8');
  const actual = Buffer.from(provided, 'utf8');

  if (expected.length !== actual.length || !crypto.timingSafeEqual(expected, actual)) {
    return res.status(401).json({
      success: false,
      message: 'Unauthorized',
    });
  }

  next();
}

module.exports = { getBearerToken, requireInternalToken };
