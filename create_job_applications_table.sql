-- Run this SQL to create the job_applications table
-- Execute this in phpMyAdmin or MySQL

CREATE TABLE IF NOT EXISTS `job_applications` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `roll_no` varchar(30) NOT NULL,
    `job_id` varchar(30) NOT NULL,
    `full_name` varchar(100) NOT NULL,
    `email` varchar(100) NOT NULL,
    `phone` varchar(15) NOT NULL,
    `skills` text NOT NULL,
    `experience` varchar(50) DEFAULT NULL,
    `current_company` varchar(100) DEFAULT NULL,
    `linkedin` varchar(255) DEFAULT NULL,
    `cover_letter` text DEFAULT NULL,
    `expected_salary` varchar(50) DEFAULT NULL,
    `status` enum('pending','reviewed','shortlisted','rejected') DEFAULT 'pending',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
