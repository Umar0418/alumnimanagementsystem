-- Run this SQL to add the topic column to mentee_requests table
-- Execute this in phpMyAdmin or MySQL

ALTER TABLE `mentee_requests` 
ADD COLUMN `topic` TEXT NULL AFTER `mentor_roll_no`;
