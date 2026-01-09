-- Run these SQL commands to create the community posts tables
-- Execute this in phpMyAdmin or MySQL

-- Community Posts Table
CREATE TABLE IF NOT EXISTS `community_posts` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `community_id` int(11) NOT NULL,
    `roll_no` varchar(30) NOT NULL,
    `content` text NOT NULL,
    `likes_count` int(11) DEFAULT 0,
    `comments_count` int(11) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `community_id` (`community_id`),
    KEY `roll_no` (`roll_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Post Likes Table
CREATE TABLE IF NOT EXISTS `post_likes` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `post_id` int(11) NOT NULL,
    `roll_no` varchar(30) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_like` (`post_id`, `roll_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample posts for testing (optional)
-- Make sure you have a community with id=1 and valid roll numbers
INSERT INTO `community_posts` (`community_id`, `roll_no`, `content`, `likes_count`, `comments_count`, `created_at`) VALUES
(1, '7376222AD101', 'Has anyone started preparing for the upcoming campus drive? Looking for study partners for DSA.', 12, 5, DATE_SUB(NOW(), INTERVAL 2 HOUR)),
(1, '7376222AD102', 'Just finished the final year project presentation! Check out our project repo here.', 24, 8, DATE_SUB(NOW(), INTERVAL 5 HOUR)),
(1, '7376222AD103', 'Reminder: Farewell party contribution deadline is tomorrow. Please pay ASAP.', 45, 12, DATE_SUB(NOW(), INTERVAL 1 DAY));
