ALTER TABLE users
  ADD COLUMN phone VARCHAR(30) NULL AFTER name,
  ADD COLUMN email_verified_at DATETIME NULL AFTER status;

CREATE TABLE IF NOT EXISTS account_tokens (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  type ENUM('activation','password_reset') NOT NULL,
  token_hash CHAR(64) NOT NULL,
  expires_at DATETIME NOT NULL,
  used_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uk_account_tokens_hash (token_hash),
  KEY idx_account_tokens_user_type (user_id,type),
  KEY idx_account_tokens_expires (expires_at),
  CONSTRAINT fk_account_tokens_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
