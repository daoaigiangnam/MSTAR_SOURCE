-- FrontEnd MySQL: persistent User -> Backend Job mapping.
-- Backend API / SQLite jobs are intentionally unchanged.
-- Run this once against the existing `videoai` database.

CREATE TABLE IF NOT EXISTS job_history (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    job_id VARCHAR(128) NOT NULL,
    title VARCHAR(500) NULL,
    mode VARCHAR(30) NULL,
    is_hidden TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_job_history_user_job (user_id, job_id),
    KEY idx_job_history_user_visible (user_id, is_hidden, created_at),
    KEY idx_job_history_job_id (job_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
