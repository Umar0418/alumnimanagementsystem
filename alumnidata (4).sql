-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 27, 2026 at 05:05 AM
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
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `usertype` enum('admin') DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `phone`, `password`, `usertype`, `created_at`) VALUES
(1, 'Admin User', 'admin@test.com', '9281234567', '$2y$10$cf16VFmI6R6Ejfjcfswd3.MCMpfyixbmPwO0SV29K1eP8/fjL.eIm', 'admin', '2026-01-22 02:51:41'),
(3, 'Chandu', 'chandu@gmail.com', '9856231478', '123456', 'admin', '2026-01-22 02:57:14'),
(19, 'Admin', 'admin@gmail.com', '928123456', '123456', 'admin', '2026-01-22 03:29:36'),
(20, 'Rajesh', 'rajesh@gmail.com', '9632587410', '123456', 'admin', '2026-01-22 03:32:36'),
(41, 'ha', 'ha@gmail.com', '1234567890', '123456', 'admin', '2026-01-23 04:25:33'),
(42, 'Rambo', 'rambo@gmail.com', '9784561230', '123456', 'admin', '2026-01-23 05:23:46'),
(43, 'nash', 'b.abhiyadav.07@gmail.com', '9704963388', '123456', 'admin', '2026-01-24 04:14:48');

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
(4, '192210098', '', '2022', 'BE', 'CSE'),
(5, '123', '', '2020', 'BE', 'CSE');

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
  `degree` varchar(100) DEFAULT NULL,
  `cgpa` decimal(3,2) DEFAULT NULL,
  `interests` text DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `profile_image` varchar(255) NOT NULL,
  `linkedin` varchar(255) NOT NULL,
  `mentorship` enum('yes','no') DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alumni_directory`
--

INSERT INTO `alumni_directory` (`id`, `roll_no`, `name`, `department`, `batch_year`, `degree`, `cgpa`, `interests`, `address`, `company`, `location`, `profile_image`, `linkedin`, `mentorship`, `created_at`) VALUES
(1, '192210097', 'Ram', 'CSE', '2022', NULL, NULL, NULL, NULL, 'TCS', 'Chennai', '', '', 'no', '2025-12-29 03:59:07'),
(2, '192210071', 'Lakshmi Nivas', 'CSE', '2022', NULL, NULL, NULL, NULL, 'HCL', 'CHENNAI', 'uploads/Feature graphics.png', '', 'yes', '2025-12-29 07:14:13'),
(3, '192210070', 'Nivas', 'CSE', '2022', 'B.Trch', 8.00, 'Data Engineer', 'Hyderabad', 'Saveetha', 'Chennai', 'uploads/profiles/profile_192210070_1769166544.jpg', '0', '', '2025-12-29 07:25:43'),
(4, '22989', 'Amar', 'kojja', '2022', NULL, NULL, NULL, NULL, 'Kojja', 'kojja', '', '', 'no', '2026-01-23 06:17:24'),
(5, '134789', 'nash', '', '', '', 0.00, '', '', '', '', 'uploads/profiles/profile_134789_1769244473.jpg', '', 'no', '2026-01-24 05:20:51');

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
(2, 'Alumni Reunion', 'Alumni reunion will be held on 25th August at college auditorium.', 'alumni', '2025-12-15 03:25:23'),
(3, 'Pongal Holidays', 'Pongal holidays from Jan 10 to jan 18', 'students', '2025-12-26 09:18:35'),
(8, 'Pongal Fest', 'All Join the evnet and enjoy please kindly join', 'both', '2026-01-06 06:44:28'),
(11, 'Saveetha', 'event host', 'students', '2026-01-24 03:58:41');

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
(1, 'Alumni Tech Community', ' Community for tech alumni', '2025-12-16 04:29:19'),
(2, 'Tech Club', ' Community for tech Students ', '2026-01-20 04:26:35'),
(3, 'Tech Club 2', 'Community for tech Students', '2026-01-20 04:37:36');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `event_banner` varchar(255) DEFAULT NULL,
  `target_audience` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `event_time`, `venue`, `created_at`, `event_banner`, `target_audience`) VALUES
(12, 'Cyber event', 'all should participate', '2026-02-04', '09:10:00', 'Zoom meeting', '2026-01-22 03:51:20', '', 'All Users'),
(14, 'saleee', 'venk', '2026-08-09', '12:00:00', 'ground near sail', '2026-01-23 08:51:12', 'uploads/events/event_1769158272_697336800df78.jpg', 'All Users'),
(15, 'pets', 'pets can Play', '2026-01-27', '10:30:00', 'ongole', '2026-01-24 04:27:16', 'uploads/events/event_1769228836_69744a24641b7.jpg', 'All Users');

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
(6, 2, '192210070', '2026-01-03 04:02:20'),
(7, 7, '12900', '2026-01-19 03:25:07'),
(8, 12, '192210070', '2026-01-22 04:26:16'),
(9, 12, '12900', '2026-01-23 06:22:01'),
(10, 1, '123', '2026-01-23 08:06:02'),
(11, 15, '134789', '2026-01-24 07:26:04'),
(12, 12, '123456', '2026-01-27 02:26:29'),
(13, 14, '123456', '2026-01-27 02:37:55'),
(14, 14, '123456', '2026-01-27 02:37:56');

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
(5, 'Hostel Breakage', 'students all should pay', 20000.00, 0.00, '2025-12-20', '2025-12-29 09:49:41'),
(9, 'poster fund', 'for posters', 50000.00, 0.00, '0000-00-00', '2026-01-24 04:00:49');

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
  `posted_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `company`, `description`, `location`, `job_type`, `salary`, `last_date`, `posted_by`, `created_at`) VALUES
(1, 'Software developer', 'HCL', 'Developer', 'Chennai', 'Part time', '2LPA', '2026-02-28', 'nash', '2026-01-09 02:50:29'),
(2, 'RPA Developer', 'Praison venture\'s', 'Backend developer role', 'Chennai', 'part time', '2000000', '2026-01-31', NULL, '2026-01-21 05:38:42'),
(3, 'developer python', 'tech crop', 'work from home', 'remote', 'full time', '100lpa', '2026-01-31', NULL, '2026-01-24 04:16:18');

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

CREATE TABLE `job_applications` (
  `id` int(11) NOT NULL,
  `roll_no` varchar(30) NOT NULL,
  `job_id` varchar(20) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `skills` text NOT NULL,
  `experience` text DEFAULT NULL,
  `current_company` varchar(150) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `cover_letter` text DEFAULT NULL,
  `expected_salary` varchar(50) DEFAULT NULL,
  `status` enum('pending','reviewed','shortlisted','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_applications`
--

INSERT INTO `job_applications` (`id`, `roll_no`, `job_id`, `full_name`, `email`, `phone`, `skills`, `experience`, `current_company`, `linkedin`, `cover_letter`, `expected_salary`, `status`, `created_at`) VALUES
(1, '192210070', '', 'Nivas', 'nivas@gmail.com', '9484521387', 'java', '1-2 Years', '', '', '', '', 'pending', '2026-01-23 09:13:41'),
(2, '134789', '', 'nash', 'burraabinash07@gmail.com', '+19704963388', 'react', 'Fresher', '', '', '', '', 'pending', '2026-01-24 05:52:41'),
(3, '123456', '3', 'abiansh', 'burraabinash0370.sse@saveetha.com', '+19704963388', 'react', 'Fresher', '', '', '', '', 'pending', '2026-01-27 02:25:57'),
(4, '123456', '1', 'abinash burra', 'burraabinash0370.sse@saveetha.com', '+19704963388', 'Urdu', 'Fresher', '', '', '', '', 'pending', '2026-01-27 02:38:38');

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
(1, '22992', 'Front end developer', '6-7hrs', 'one to one', 'rejected', '2025-12-15 08:08:45'),
(2, '22999', 'Back end developer', '4-5hrs', 'one to one', 'rejected', '2025-12-15 08:25:49'),
(3, '192210070', 'ccarrer', '1-2 hours / week', 'ety', 'pending', '2025-12-27 09:14:59'),
(4, '192210070', 'CAREER GUIDANCE', '1-2 hours / week', 'one to one', 'approved', '2025-12-30 07:41:07'),
(5, '192210070', 'Career Guidance', '1-2 hours / week', 'one to one', 'approved', '2026-01-02 08:54:27'),
(6, '192210070', 'Developer', '1-2 hours / week', 'One to one', 'rejected', '2026-01-09 02:53:33'),
(7, '123', 'Backend developer', '1-2 hours / week', 'one to one', 'rejected', '2026-01-23 08:04:18'),
(16, '134789', 'developer', '1-2 hours / week', 'to', 'approved', '2026-01-24 05:13:10');

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
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `graduation_year` varchar(10) DEFAULT NULL,
  `degree` varchar(50) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `year` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `roll_no`, `email`, `phone`, `password`, `graduation_year`, `degree`, `department`, `year`, `created_at`) VALUES
(1, 'Ram', '22981', 'ram@gmail.com', '', '123', '2024', 'BSc Computer Science', 'CSE', NULL, '2025-12-14 10:04:39'),
(4, 'Raja', '22989', 'raja@gmail.com', '', '123', '2024', 'BSc Computer Science', 'CSE', NULL, '2025-12-15 07:27:41'),
(7, 'Rajendra', '12900', '', '', '', '2024', 'BSc Computer Science', 'CSE', NULL, '2025-12-15 07:46:50'),
(8, 'abiansh', '123456', 'burraabinash0370.sse@saveetha.com', NULL, NULL, '3rd year', NULL, 'cse', NULL, '2026-01-27 02:24:40');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `address` varchar(255) DEFAULT NULL,
  `cgpa` decimal(3,2) DEFAULT NULL,
  `profile_image` varchar(255) NOT NULL,
  `interests` text DEFAULT NULL,
  `department` varchar(255) NOT NULL,
  `degree` varchar(100) DEFAULT NULL,
  `batch_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `roll_no`, `name`, `email`, `phone`, `password`, `usertype`, `created_at`, `address`, `cgpa`, `profile_image`, `interests`, `department`, `degree`, `batch_number`) VALUES
(1, '22980', 'dhatchu', 'dhatchu@gmail.com', '9876543210', '123', 'admin', '2025-12-15 07:12:21', NULL, NULL, '', NULL, '', NULL, NULL),
(2, '22982', 'Umar', 'umar@gmail.com', '9876544211', '123456', 'admin', '2025-12-15 07:14:11', NULL, NULL, '', NULL, '', NULL, NULL),
(3, '22989', 'Amar', 'amar@gmail.com', '9876544217', '123', 'alumni', '2025-12-15 07:16:47', NULL, NULL, '', NULL, '', NULL, NULL),
(4, '22990', 'Raja', 'raja@gmail.com', '9076544217', '123', 'alumni', '2025-12-15 07:19:52', NULL, NULL, '', NULL, '', NULL, NULL),
(6, '22999', 'Raju', 'raju@gmail.com', '9076544217', '123', 'alumni', '2025-12-15 07:26:13', NULL, NULL, '', NULL, '', NULL, NULL),
(8, '22998', 'Raj', 'raj@gmail.com', '9076544214', '123', 'student', '2025-12-15 07:29:51', NULL, NULL, '', NULL, '', NULL, NULL),
(9, '22993', 'jaswanth', 'jaswanth@gmail.com', '9076544214', '123', 'alumni', '2025-12-15 07:33:30', NULL, NULL, '', NULL, '', NULL, NULL),
(10, '22992', 'jaswanth', 'jaswanth1@gmail.com', '9076544214', '123', 'alumni', '2025-12-15 07:37:14', NULL, NULL, '', NULL, '', NULL, NULL),
(13, '12900', 'Rajendra', 'rajendra@gmail.com', '', '123', 'student', '2025-12-15 07:46:50', '', 8.00, '', '', '', NULL, NULL),
(14, '192210071', 'Lakshmi Nivas', 'pashamlakshmi@gmail.com', '8142344071', '123', 'alumni', '2025-12-27 03:39:11', NULL, NULL, '', NULL, '', NULL, NULL),
(33, '192210070', 'Nivas', 'nivas@gmail.com', '9484521387', '123456', 'alumni', '2025-12-27 03:44:05', NULL, NULL, '', NULL, '', NULL, NULL),
(34, '192210098', 'Naveen', 'anamalamuriumar@gmail.com', '8399754210', '123', 'alumni', '2025-12-31 08:02:27', NULL, NULL, '', NULL, '', NULL, NULL),
(35, '', 'Ramarao', 'ramarao@gmail.com', '9463154461', '123456', 'admin', '2026-01-07 03:03:10', NULL, NULL, '', NULL, '', NULL, NULL),
(60, 'ADMIN_1769052576', 'Admin', 'admin@gmail.com', '928123456', '123456', 'admin', '2026-01-22 03:29:36', NULL, NULL, '', NULL, '', NULL, NULL),
(61, 'ADMIN_1769052756', 'Rajesh', 'rajesh@gmail.com', '9632587410', '123456', 'admin', '2026-01-22 03:32:36', NULL, NULL, '', NULL, '', NULL, NULL),
(62, '123', 'Bogolu Chaitanya', 'chaithanyabogolu@gmail.com', '9875461230', '123', 'alumni', '2026-01-22 17:58:27', NULL, NULL, '', NULL, '', NULL, NULL),
(70, 'ADMIN_1769227785', 'B Abinash', 'b.abhiyadav.07@gmail.com', '9704963388', 'abhi07', 'admin', '2026-01-24 04:09:45', NULL, NULL, '', NULL, '', NULL, NULL),
(71, '134789', 'nash', 'burraabinash07@gmail.com', '+19704963388', '123456', 'alumni', '2026-01-24 05:11:10', '', 0.00, 'uploads/profiles/profile_134789_1769244473.jpg', '', '', '', ''),
(72, '123456', 'abinash burra', 'burraabinash0370.sse@saveetha.com', '+19704963388', '12345678', 'student', '2026-01-27 02:24:40', '', 0.00, '', '', '', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_posted_by` (`posted_by`);

--
-- Indexes for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roll_no` (`roll_no`),
  ADD KEY `job_id` (`job_id`);

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
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `alumni`
--
ALTER TABLE `alumni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `alumni_directory`
--
ALTER TABLE `alumni_directory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `communities`
--
ALTER TABLE `communities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `community_members`
--
ALTER TABLE `community_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `event_registrations`
--
ALTER TABLE `event_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `funds`
--
ALTER TABLE `funds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `job_applications`
--
ALTER TABLE `job_applications`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

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
