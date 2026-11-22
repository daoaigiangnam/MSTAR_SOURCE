# Media Test Fixtures

This directory is reserved for deterministic media fixtures used by integration tests.

Do not download remote videos during CI. Prefer a tiny locally generated MP4/WAV fixture created by FFmpeg in the test setup.

Recommended fixture properties:
- 2–3 seconds
- 320x240 video
- 48 kHz stereo audio
- deterministic color/test tone
