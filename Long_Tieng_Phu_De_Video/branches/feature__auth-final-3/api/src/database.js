const fs = require('fs');
const path = require('path');
const Database = require('better-sqlite3');
const config = require('./config');

const databasePath = path.resolve(__dirname, '..', config.databasePath);
fs.mkdirSync(path.dirname(databasePath), { recursive: true });

const db = new Database(databasePath);
db.pragma('journal_mode = WAL');

const schemaPath = path.resolve(__dirname, '..', 'database', 'schema.sql');
if (fs.existsSync(schemaPath)) {
  db.exec(fs.readFileSync(schemaPath, 'utf8'));
}

module.exports = db;
