USE videodubing;

-- Free registration no longer requires Email.
ALTER TABLE users MODIFY email VARCHAR(190) NULL;

-- Phone becomes the primary login identifier for free accounts.
-- Run this only if the existing users table does not already have this index.
ALTER TABLE users ADD UNIQUE KEY uq_users_phone (phone);
