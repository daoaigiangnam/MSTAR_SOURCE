-- ============================================================
-- VideoDubing AI - Payment schema upgrade
-- Use this file ONLY if 20260817_create_payment_tables.sql was
-- already executed before the payment_expires_at/unique changes.
-- Safe to run repeatedly on MySQL 5.7+.
-- ============================================================

SET @db_name = DATABASE();

-- Add payment_expires_at when it does not exist.
SET @sql = (
    SELECT IF(
        EXISTS(
            SELECT 1
            FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = @db_name
              AND TABLE_NAME = 'payment_orders'
              AND COLUMN_NAME = 'payment_expires_at'
        ),
        'SELECT 1',
        'ALTER TABLE payment_orders ADD COLUMN payment_expires_at DATETIME NULL AFTER sepay_transaction_id'
    )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add the expiry index when it does not exist.
SET @sql = (
    SELECT IF(
        EXISTS(
            SELECT 1
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = @db_name
              AND TABLE_NAME = 'payment_orders'
              AND INDEX_NAME = 'idx_payment_orders_expiry'
        ),
        'SELECT 1',
        'ALTER TABLE payment_orders ADD KEY idx_payment_orders_expiry (status, payment_expires_at)'
    )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Replace the old non-unique SePay reference index with a unique index.
SET @sql = (
    SELECT IF(
        EXISTS(
            SELECT 1
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = @db_name
              AND TABLE_NAME = 'payment_orders'
              AND INDEX_NAME = 'uq_payment_orders_sepay'
        ),
        'SELECT 1',
        IF(
            EXISTS(
                SELECT 1
                FROM information_schema.STATISTICS
                WHERE TABLE_SCHEMA = @db_name
                  AND TABLE_NAME = 'payment_orders'
                  AND INDEX_NAME = 'idx_payment_orders_sepay'
            ),
            'ALTER TABLE payment_orders DROP INDEX idx_payment_orders_sepay, ADD UNIQUE KEY uq_payment_orders_sepay (sepay_transaction_id)',
            'ALTER TABLE payment_orders ADD UNIQUE KEY uq_payment_orders_sepay (sepay_transaction_id)'
        )
    )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SELECT 'Payment schema upgrade completed' AS result;
