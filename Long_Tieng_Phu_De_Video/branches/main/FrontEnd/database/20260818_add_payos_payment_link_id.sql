-- VideoDubing AI - payOS reconciliation metadata
-- Run once after 20260818_add_payment_provider.sql

SET NAMES utf8mb4;

ALTER TABLE payment_orders
    ADD COLUMN payos_payment_link_id VARCHAR(128) NULL AFTER provider_order_id,
    ADD KEY idx_payment_orders_payos_link (payos_payment_link_id);

-- Verification:
-- DESCRIBE payment_orders;
