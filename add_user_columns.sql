-- Add degree and batch_number columns to users table
-- Run this SQL on your alumnidata database

ALTER TABLE users 
ADD COLUMN degree VARCHAR(100) DEFAULT NULL,
ADD COLUMN batch_number VARCHAR(20) DEFAULT NULL;

-- If you get an error that columns already exist, that's fine - they're already there
