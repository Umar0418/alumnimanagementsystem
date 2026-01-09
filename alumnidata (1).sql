-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2026 at 08:22 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `alumnidata`
--

-- --------------------------------------------------------

--
-- Table structure for table `alumni`
--

CREATE TABLE `alumni` (
  `id` int(11) NOT NULL,
  `roll_no` varchar(30) NOT NULL,
  `name` varchar(100) NOT NULL,
  `graduation_year` varchar(10) NOT NULL,
  `degree` varchar(50) NOT NULL,
  `department` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alumni`
--

INSERT INTO `alumni` (`id`, `roll_no`, `name`, `graduation_year`, `degree`, `department`) VALUES
(1, '22992', '', '2025', 'BE', 'CSE'),
(2, '192210071', '', '2022', 'be', 'CSE'),
(3, '192210070', '', '2021', 'BE', 'CSE'),
(4, '192210098', '', '2022', 'BE', 'CSE');

-- --------------------------------------------------------

--
-- Table structure for table `alumni_directory`
--

CREATE TABLE `alumni_directory` (
  `id` int(11) NOT NULL,
  `roll_no` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `department` varchar(50) DEFAULT NULL,
  `batch_year` varchar(10) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `mentorship` enum('yes','no') DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alumni_directory`
--

INSERT INTO `alumni_directory` (`id`, `roll_no`, `name`, `department`, `batch_year`, `company`, `location`, `mentorship`, `created_at`) VALUES
(1, '192210097', 'Ram', 'CSE', '2022', 'TCS', 'Chennai', 'no', '2025-12-29 03:59:07'),
(2, '192210071', 'Lakshmi Nivas', 'CSE', '2022', 'HCL', 'CHENNAI', 'yes', '2025-12-29 07:14:13'),
(3, '192210070', 'Nivas', 'CSE', '2021', 'HCL', 'Chennai', '', '2025-12-29 07:25:43');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `target` enum('students','alumni','both') DEFAULT 'both',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `message`, `target`, `created_at`) VALUES
(2, 'Alumni Reunion', 'Alumni reunion will be held on 20th August at college auditorium.', 'alumni', '2025-12-15 03:25:23'),
(3, 'Pongal Holidays', 'Pongal holidays from Jan 10 to jan 18', 'students', '2025-12-26 09:18:35'),
(7, 'Simam2026', 'Join and enjoy the event on 10/01/2026', 'students', '2026-01-05 03:50:34'),
(8, 'Pongal Fest', 'All Join the evnet and enjoy', 'both', '2026-01-06 06:44:28');

-- --------------------------------------------------------

--
-- Table structure for table `communities`
--

CREATE TABLE `communities` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `communities`
--

INSERT INTO `communities` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Alumni Tech Community', ' Community for tech alumni', '2025-12-16 04:29:19');

-- --------------------------------------------------------

--
-- Table structure for table `community_members`
--

CREATE TABLE `community_members` (
  `id` int(11) NOT NULL,
  `community_id` int(11) NOT NULL,
  `roll_no` varchar(30) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_members`
--

INSERT INTO `community_members` (`id`, `community_id`, `roll_no`, `joined_at`) VALUES
(1, 1, '22992', '2025-12-16 07:00:46'),
(2, 1, '22990', '2025-12-16 07:04:35');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `venue` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `event_time`, `venue`, `created_at`) VALUES
(1, 'Star  Sports Event', 'Students can participate in sports', '2026-01-06', '00:09:06', 'College ground', '2025-12-24 05:19:09'),
(2, 'Star Summit', 'All students should attend the event', '2025-12-30', '09:00:00', 'Saveetha Ground', '2025-12-26 03:54:02'),
(3, 'Star Summit', 'All students  must attend', '0000-00-00', '09:00:00', 'SSE Ground', '2025-12-26 06:51:09'),
(4, 'Star Summit', 'All students must attend .', '0000-00-00', '09:00:00', 'SSE Ground', '2025-12-26 07:10:33');

-- --------------------------------------------------------

--
-- Table structure for table `event_registrations`
--

CREATE TABLE `event_registrations` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `roll_no` varchar(30) NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_registrations`
--

INSERT INTO `event_registrations` (`id`, `event_id`, `roll_no`, `registered_at`) VALUES
(1, 2, '12900', '2025-12-15 09:19:07'),
(2, 2, '22992', '2025-12-15 09:19:39'),
(3, 2, '22992', '2025-12-29 05:15:30'),
(4, 2, '192210070', '2025-12-29 06:48:06'),
(5, 2, '12900', '2025-12-31 04:37:11'),
(6, 2, '192210070', '2026-01-03 04:02:20');

-- --------------------------------------------------------

--
-- Table structure for table `funds`
--

CREATE TABLE `funds` (
  `id` int(11) NOT NULL,
  `fund_title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `target_amount` decimal(10,2) DEFAULT NULL,
  `collected_amount` decimal(10,2) DEFAULT 0.00,
  `last_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `funds`
--

INSERT INTO `funds` (`id`, `fund_title`, `description`, `target_amount`, `collected_amount`, `last_date`, `created_at`) VALUES
(3, 'Library Funds', 'To provide more books to students.', 30000.00, 0.00, '0000-00-00', '2025-12-26 07:39:31'),
(5, 'Hostel Breakage', 'students all should pay', 20000.00, 0.00, '2025-12-20', '2025-12-29 09:49:41');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `company` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `job_type` varchar(50) DEFAULT NULL,
  `salary` varchar(50) DEFAULT NULL,
  `last_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `company`, `description`, `location`, `job_type`, `salary`, `last_date`, `created_at`) VALUES
(2, 'Software Engineer', 'TCS', 'Backend developer role', 'Chennai', 'Full Time', '3–5 LPA', '2025-06-30', '2025-12-14 11:10:58'),
(3, 'Software engineer', 'HCL', 'devops', 'Chennai', 'Full Time', '5-6LPA', '0000-00-00', '2025-12-26 08:41:19'),
(4, 'SALES MANAGER', 'UNLOX', 'Related to the sales department', 'Chennai', 'Part Time', '3-4LPA', '0000-00-00', '2026-01-03 03:33:59');

-- --------------------------------------------------------

--
-- Table structure for table `mentee_requests`
--

CREATE TABLE `mentee_requests` (
  `id` int(11) NOT NULL,
  `roll_no` varchar(30) NOT NULL,
  `mentor_roll_no` varchar(30) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mentee_requests`
--

INSERT INTO `mentee_requests` (`id`, `roll_no`, `mentor_roll_no`, `status`, `created_at`) VALUES
(1, '12900', '192210070', 'pending', '2025-12-15 08:37:50');

-- --------------------------------------------------------

--
-- Table structure for table `mentor_requests`
--

CREATE TABLE `mentor_requests` (
  `id` int(11) NOT NULL,
  `roll_no` varchar(30) NOT NULL,
  `mentorship_field` varchar(100) NOT NULL,
  `working_hours` varchar(50) NOT NULL,
  `mentorship_style` varchar(100) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mentor_requests`
--

INSERT INTO `mentor_requests` (`id`, `roll_no`, `mentorship_field`, `working_hours`, `mentorship_style`, `status`, `created_at`) VALUES
(1, '22992', 'Front end developer', '6-7hrs', 'one to one', 'approved', '2025-12-15 08:08:45'),
(2, '22999', 'Back end developer', '4-5hrs', 'one to one', 'approved', '2025-12-15 08:25:49'),
(3, '192210070', 'ccarrer', '1-2 hours / week', 'ety', 'rejected', '2025-12-27 09:14:59'),
(4, '192210070', 'CAREER GUIDANCE', '1-2 hours / week', 'one to one', 'rejected', '2025-12-30 07:41:07'),
(5, '192210070', 'Career Guidance', '1-2 hours / week', 'one to one', 'rejected', '2026-01-02 08:54:27');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `roll_no` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `reset_token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `roll_no`, `email`, `reset_token`, `expires_at`, `created_at`) VALUES
(1, '192210070', 'nivas@gmail.com', '2dae27d0f42addd335a5ebbf194a7f03ce42e63050a4774a30d62dae73434e25', '2025-12-27 05:18:30', '2025-12-27 04:03:30'),
(2, '192210070', 'nivas@gmail.com', '993fe6306bd05d606b5f4fc97fe31fd16002a0c1cda31ec84db15d9db2c6be2c', '2025-12-30 10:41:05', '2025-12-30 08:41:05'),
(3, '192210070', 'nivas@gmail.com', 'd3c0e131618c59c640e4a889e4a05957144def3171715e3709b57ea38ed15a6c', '2025-12-30 10:44:32', '2025-12-30 08:44:32'),
(4, '192210098', 'anamalamuriumar@gmail.com', '63e80d6247def6858f1a09ac1b37936a3ab477da18ec5232301d2b77e7247e7d', '2025-12-31 10:05:06', '2025-12-31 08:05:06'),
(5, '192210098', 'anamalamuriumar@gmail.com', '0a3a934b3f1e28976981cb271a55cac5806a13624c8379e01693dc84cddb31fe', '2025-12-31 10:12:38', '2025-12-31 08:12:38');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `roll_no` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(50) NOT NULL,
  `graduation_year` varchar(10) DEFAULT NULL,
  `degree` varchar(50) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `roll_no`, `email`, `phone`, `password`, `graduation_year`, `degree`, `department`, `created_at`) VALUES
(1, 'Ram', '22981', 'ram@gmail.com', '', '123', '2024', 'BSc Computer Science', 'CSE', '2025-12-14 10:04:39'),
(4, 'Raja', '22989', 'raja@gmail.com', '', '123', '2024', 'BSc Computer Science', 'CSE', '2025-12-15 07:27:41'),
(7, 'Rajendra', '12900', '', '', '', '2024', 'BSc Computer Science', 'CSE', '2025-12-15 07:46:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `roll_no` varchar(30) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(50) NOT NULL,
  `usertype` enum('student','alumni','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `roll_no`, `name`, `email`, `phone`, `password`, `usertype`, `created_at`) VALUES
(1, '22980', 'dhatchu', 'dhatchu@gmail.com', '9876543210', '123', 'admin', '2025-12-15 07:12:21'),
(2, '22982', 'Umar', 'umar@gmail.com', '9876544211', '123456', 'admin', '2025-12-15 07:14:11'),
(3, '22989', 'Amar', 'amar@gmail.com', '9876544217', '123', 'alumni', '2025-12-15 07:16:47'),
(4, '22990', 'Raja', 'raja@gmail.com', '9076544217', '123', 'alumni', '2025-12-15 07:19:52'),
(6, '22999', 'Raju', 'raju@gmail.com', '9076544217', '123', 'alumni', '2025-12-15 07:26:13'),
(8, '22998', 'Raj', 'raj@gmail.com', '9076544214', '123', 'alumni', '2025-12-15 07:29:51'),
(9, '22993', 'jaswanth', 'jaswanth@gmail.com', '9076544214', '123', 'alumni', '2025-12-15 07:33:30'),
(10, '22992', 'jaswanth', 'jaswanth1@gmail.com', '9076544214', '123', 'alumni', '2025-12-15 07:37:14'),
(13, '12900', 'Rajendra', 'rajendra@gmail.com', '9876543219', '123', 'student', '2025-12-15 07:46:50'),
(14, '192210071', 'Lakshmi Nivas', 'pashamlakshmi@gmail.com', '8142344071', '123', 'alumni', '2025-12-27 03:39:11'),
(33, '192210070', 'Nivas', 'nivas@gmail.com', '9484521387', '123', 'alumni', '2025-12-27 03:44:05'),
(34, '192210098', 'Naveen', 'anamalamuriumar@gmail.com', '8399754210', '123', 'alumni', '2025-12-31 08:02:27'),
(35, '', 'Ramarao', 'ramarao@gmail.com', '9463154461', '123456', 'admin', '2026-01-07 03:03:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alumni`
--
ALTER TABLE `alumni`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roll_no` (`roll_no`);

--
-- Indexes for table `alumni_directory`
--
ALTER TABLE `alumni_directory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roll_no` (`roll_no`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `communities`
--
ALTER TABLE `communities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `community_members`
--
ALTER TABLE `community_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `funds`
--
ALTER TABLE `funds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mentee_requests`
--
ALTER TABLE `mentee_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mentor_requests`
--
ALTER TABLE `mentor_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roll_no` (`roll_no`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `reset_token` (`reset_token`),
  ADD KEY `roll_no` (`roll_no`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roll_no` (`roll_no`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roll_no` (`roll_no`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alumni`
--
ALTER TABLE `alumni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `alumni_directory`
--
ALTER TABLE `alumni_directory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `communities`
--
ALTER TABLE `communities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `community_members`
--
ALTER TABLE `community_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `event_registrations`
--
ALTER TABLE `event_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `funds`
--
ALTER TABLE `funds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mentee_requests`
--
ALTER TABLE `mentee_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mentor_requests`
--
ALTER TABLE `mentor_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `mentor_requests`
--
ALTER TABLE `mentor_requests`
  ADD CONSTRAINT `mentor_requests_ibfk_1` FOREIGN KEY (`roll_no`) REFERENCES `users` (`roll_no`);

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`roll_no`) REFERENCES `users` (`roll_no`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
