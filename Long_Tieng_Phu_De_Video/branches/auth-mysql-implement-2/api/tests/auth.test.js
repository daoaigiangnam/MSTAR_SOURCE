const test = require('node:test');
const assert = require('node:assert/strict');

const config = require('../src/config');
const { getBearerToken, requireInternalToken } = require('../src/middleware/auth');

function responseMock() {
  return {
    statusCode: null,
    body: null,
    status(code) {
      this.statusCode = code;
      return this;
    },
    json(body) {
      this.body = body;
      return this;
    },
  };
}

function requestMock(headers = {}) {
  const normalized = Object.fromEntries(
    Object.entries(headers).map(([key, value]) => [key.toLowerCase(), value]),
  );
  return {
    get(name) {
      return normalized[name.toLowerCase()];
    },
  };
}

test('Bearer token is parsed from Authorization header', () => {
  const req = requestMock({ Authorization: 'Bearer test-token-123' });
  assert.equal(getBearerToken(req), 'test-token-123');
});

test('missing Authorization header is rejected', () => {
  const original = config.internalToken;
  config.internalToken = 'test-secret';
  try {
    const res = responseMock();
    let nextCalled = false;
    requireInternalToken(requestMock(), res, () => { nextCalled = true; });
    assert.equal(res.statusCode, 401);
    assert.equal(res.body.message, 'Unauthorized');
    assert.equal(nextCalled, false);
  } finally {
    config.internalToken = original;
  }
});

test('invalid Bearer token is rejected', () => {
  const original = config.internalToken;
  config.internalToken = 'test-secret';
  try {
    const res = responseMock();
    let nextCalled = false;
    requireInternalToken(
      requestMock({ Authorization: 'Bearer wrong-secret' }),
      res,
      () => { nextCalled = true; },
    );
    assert.equal(res.statusCode, 401);
    assert.equal(res.body.message, 'Unauthorized');
    assert.equal(nextCalled, false);
  } finally {
    config.internalToken = original;
  }
});

test('valid Bearer token is accepted', () => {
  const original = config.internalToken;
  config.internalToken = 'test-secret';
  try {
    const res = responseMock();
    let nextCalled = false;
    requireInternalToken(
      requestMock({ Authorization: 'Bearer test-secret' }),
      res,
      () => { nextCalled = true; },
    );
    assert.equal(res.statusCode, null);
    assert.equal(nextCalled, true);
  } finally {
    config.internalToken = original;
  }
});

test('internal authentication fails closed when token is not configured', () => {
  const original = config.internalToken;
  config.internalToken = '';
  try {
    const res = responseMock();
    let nextCalled = false;
    requireInternalToken(requestMock(), res, () => { nextCalled = true; });
    assert.equal(res.statusCode, 503);
    assert.equal(res.body.message, 'Internal authentication is not configured');
    assert.equal(nextCalled, false);
  } finally {
    config.internalToken = original;
  }
});
