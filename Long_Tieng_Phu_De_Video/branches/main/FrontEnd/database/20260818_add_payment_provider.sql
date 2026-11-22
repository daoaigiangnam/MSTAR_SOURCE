-- ============================================================
-- VideoDubing AI - Payment Provider Abstraction
-- Date: 2026-08-18
-- Scope: FrontEnd PHP + MySQL only
--
-- Adds provider metadata without changing the existing SePay
-- transaction table. Existing SePay orders remain valid.
-- ============================================================

SET NAMES utf8mb4;

ALTER TABLE payment_orders
    ADD COLUMN payment_provider VARCHAR(30) NOT NULL DEFAULT 'sepay' AFTER amount,
    ADD COLUMN provider_order_id VARCHAR(128) NULL AFTER payment_provider,
    ADD KEY idx_payment_orders_provider (payment_provider),
    ADD KEY idx_payment_orders_provider_order (payment_provider, provider_order_id);

-- One active/default provider is controlled by the FrontEnd Admin UI.
CREATE TABLE IF NOT EXISTS payment_provider_settings (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    provider_code VARCHAR(30) NOT NULL,
    provider_name VARCHAR(100) NOT NULL,
    is_enabled TINYINT(1) NOT NULL DEFAULT 0,
    is_default TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_payment_provider_code (provider_code),
    KEY idx_payment_provider_default (is_default),
    KEY idx_payment_provider_enabled (is_enabled)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO payment_provider_settings (provider_code, provider_name, is_enabled, is_default)
VALUES
    ('sepay', 'SePay', 1, 1),
    ('payos', 'payOS', 0, 0)
ON DUPLICATE KEY UPDATE
    provider_name = VALUES(provider_name);

-- Existing payment_orders are explicitly SePay orders.
UPDATE payment_orders
SET payment_provider = 'sepay'
WHERE payment_provider IS NULL OR payment_provider = '';

-- Verification:
-- SELECT * FROM payment_provider_settings ORDER BY id;
-- DESCRIBE payment_orders;
