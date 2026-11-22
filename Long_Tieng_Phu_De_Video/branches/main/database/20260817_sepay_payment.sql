USE videodubing;

CREATE TABLE IF NOT EXISTS payment_orders (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    order_code VARCHAR(32) NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    plan_code VARCHAR(30) NOT NULL,
    plan_name VARCHAR(100) NOT NULL,
    days INT UNSIGNED NOT NULL,
    amount BIGINT UNSIGNED NOT NULL,
    status ENUM('pending','paid','expired','cancelled') NOT NULL DEFAULT 'pending',
    sepay_transaction_id BIGINT NULL,
    paid_at DATETIME NULL,
    expires_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_payment_orders_code (order_code),
    UNIQUE KEY uq_payment_orders_sepay_transaction (sepay_transaction_id),
    KEY idx_payment_orders_user_status (user_id, status),
    KEY idx_payment_orders_status_created (status, created_at),
    CONSTRAINT fk_payment_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS sepay_transactions (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    sepay_id BIGINT NOT NULL,
    gateway VARCHAR(100) NOT NULL,
    transaction_date DATETIME NOT NULL,
    account_number VARCHAR(100) NULL,
    sub_account VARCHAR(250) NULL,
    code VARCHAR(250) NULL,
    transfer_type VARCHAR(10) NOT NULL,
    transfer_amount BIGINT NOT NULL DEFAULT 0,
    accumulated BIGINT NOT NULL DEFAULT 0,
    content TEXT NULL,
    description TEXT NULL,
    reference_code VARCHAR(255) NULL,
    raw_payload JSON NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_sepay_transactions_sepay_id (sepay_id),
    KEY idx_sepay_transactions_code (code),
    KEY idx_sepay_transactions_account_date (account_number, transaction_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
