USE videodubing;

-- Restore the email-based activation flow for new registrations.
-- Email remains nullable for legacy accounts, but new registration requires it in PHP.
ALTER TABLE users MODIFY email VARCHAR(190) NULL;

-- Keep phone unique as the primary login identifier.
-- Add this index only if it does not already exist.
-- ALTER TABLE users ADD UNIQUE KEY uq_users_phone (phone);
