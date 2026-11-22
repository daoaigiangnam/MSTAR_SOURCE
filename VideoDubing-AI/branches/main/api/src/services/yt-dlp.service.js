const { execFile } = require('child_process');
const { promisify } = require('util');

const execFileAsync = promisify(execFile);

async function version() {
  const { stdout } = await execFileAsync('yt-dlp', ['--version']);
  return stdout.trim();
}

async function info(url) {
  const { stdout } = await execFileAsync('yt-dlp', ['-J', '--no-playlist', url]);
  return JSON.parse(stdout);
}

module.exports = { version, info };
