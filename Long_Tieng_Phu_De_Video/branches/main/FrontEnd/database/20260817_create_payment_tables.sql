-- ============================================================
-- VideoDubing AI - Payment Database Migration
-- Date: 2026-08-17
-- Scope: FrontEnd PHP + MySQL only
--
-- Existing tables NOT modified:
--   - users
--   - account_tokens
--
-- users.expires_at is used by the payment webhook to extend
-- the customer's subscription after a successful payment.
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- Payment Orders
-- One row represents one customer purchase/order.
-- ============================================================
CREATE TABLE IF NOT EXISTS payment_orders (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    order_code VARCHAR(20) NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,

    plan_code VARCHAR(50) NOT NULL,
    plan_name VARCHAR(100) NOT NULL,
    days INT UNSIGNED NOT NULL,
    amount BIGINT UNSIGNED NOT NULL,

    status ENUM('pending','paid','cancelled','expired')
        NOT NULL DEFAULT 'pending',

    sepay_transaction_id BIGINT UNSIGNED NULL,
    payment_expires_at DATETIME NULL,
    paid_at DATETIME NULL,
    expires_at DATETIME NULL,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_payment_orders_code (order_code),
    UNIQUE KEY uq_payment_orders_sepay (sepay_transaction_id),
    KEY idx_payment_orders_user (user_id),
    KEY idx_payment_orders_status (status),
    KEY idx_payment_orders_expiry (status, payment_expires_at),
    KEY idx_payment_orders_created (created_at),

    CONSTRAINT fk_payment_orders_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE RESTRICT
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SePay Transactions
-- Raw payment events received from SePay.
-- sepay_id is unique to make webhook processing idempotent.
-- ============================================================
CREATE TABLE IF NOT EXISTS sepay_transactions (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    sepay_id BIGINT UNSIGNED NOT NULL,

    gateway VARCHAR(50) NOT NULL,
    transaction_date DATETIME NULL,

    account_number VARCHAR(100) NOT NULL,
    sub_account VARCHAR(100) NULL,
    code VARCHAR(50) NULL,

    transfer_type ENUM('in','out') NOT NULL,
    transfer_amount BIGINT UNSIGNED NOT NULL,
    accumulated BIGINT UNSIGNED NULL,

    content TEXT NULL,
    description TEXT NULL,
    reference_code VARCHAR(100) NULL,

    raw_payload JSON NULL,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_sepay_transactions_id (sepay_id),
    KEY idx_sepay_transactions_code (code),
    KEY idx_sepay_transactions_account (account_number),
    KEY idx_sepay_transactions_date (transaction_date),
    KEY idx_sepay_transactions_reference (reference_code)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- Verification queries (run manually after migration)
-- ============================================================
-- SHOW TABLES LIKE 'payment_orders';
-- SHOW TABLES LIKE 'sepay_transactions';
-- DESCRIBE payment_orders;
-- DESCRIBE sepay_transactions;
