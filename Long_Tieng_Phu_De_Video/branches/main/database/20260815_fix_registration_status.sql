-- Fix: allow newly registered accounts to remain pending until Email activation.
-- Run once on the existing production database.
USE videodubing;

ALTER TABLE users
  MODIFY COLUMN status ENUM('pending','active','blocked') NOT NULL DEFAULT 'pending';
