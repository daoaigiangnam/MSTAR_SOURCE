-- VideoDubing AI - payOS transaction reconciliation
-- Run after the payment provider migration.

SET NAMES utf8mb4;

ALTER TABLE payment_orders
    ADD COLUMN provider_transaction_id VARCHAR(128) NULL AFTER provider_order_id,
    ADD KEY idx_payment_orders_provider_tx (payment_provider, provider_transaction_id);

-- provider_order_id remains the immutable payOS orderCode.
-- provider_transaction_id stores the bank transaction reference.

-- Verification:
-- DESCRIBE payment_orders;
