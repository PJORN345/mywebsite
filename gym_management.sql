-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 19, 2025 at 10:18 PM
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
-- Database: `gym_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`, `created_at`) VALUES
(1, 'info@zetech.ac.ke', '$2y$10$/8X/jYnOVw25LlCSvmjkf.b0CVEtGGipwzKvIugy540AF5mT/PXwK', '2025-03-18 17:36:25'),
(2, 'admin@gmail.com', '$2y$10$RT1yIEJEne4yvD9pD0Fw3.MEFCLWU7LqkgOZ19w9K35cgPZ2i62Tq', '2025-03-19 10:58:10'),
(3, 'mango@gmail.com', '$2y$10$Us.48GmRkWaDjS2tvDDrjOnEAItM.xqXOVH.hrPv3npeSAUL/i6rm', '2025-03-19 20:32:39');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `check_in` datetime NOT NULL,
  `check_out` datetime DEFAULT NULL,
  `hours_required` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `activity`, `check_in`, `check_out`, `hours_required`) VALUES
(1, 3, 'Cardio', '2025-03-19 14:46:34', '2025-03-19 14:46:40', 0),
(2, 3, 'Lower body', '2025-03-19 15:26:08', '2025-03-19 15:26:10', 3),
(3, 1, 'Cardio', '2025-03-19 20:09:06', '2025-03-19 20:09:12', 0),
(4, 1, 'Lower body', '2025-03-19 21:50:03', '2025-03-19 21:57:59', 3),
(5, 1, 'Lower body', '2025-03-19 21:59:48', '2025-03-19 22:00:00', 3),
(6, 1, 'Lower body', '2025-03-19 22:00:33', '2025-03-19 22:39:53', 3),
(7, 1, 'Cardio', '2025-03-19 22:00:41', '2025-03-19 22:38:51', 2),
(8, 1, 'Cardio', '2025-03-19 22:07:11', '2025-03-19 22:07:59', 2),
(9, 1, 'Cardio', '2025-03-19 22:08:04', '2025-03-19 22:29:43', 2),
(10, 1, 'Cardio', '2025-03-19 22:29:48', '2025-03-19 22:29:51', 2),
(11, 1, 'Cardio', '2025-03-19 22:30:00', '2025-03-19 22:30:10', 2),
(12, 1, 'Lower body', '2025-03-19 22:30:31', '2025-03-19 22:30:36', 3),
(13, 1, 'Cardio', '2025-03-19 22:36:27', '2025-03-19 22:36:34', 2),
(14, 1, 'Lower body', '2025-03-19 22:37:20', '2025-03-19 22:37:25', 3),
(15, 1, 'Cardio', '2025-03-19 22:38:24', '2025-03-19 22:38:29', 2),
(16, 1, ' Upper body and core', '2025-03-19 22:47:33', NULL, 2),
(17, 1, 'Cardio', '2025-03-19 20:55:30', NULL, 2),
(18, 1, 'Lower body', '2025-03-19 20:55:59', NULL, 3),
(19, 1, ' Upper body and core', '2025-03-19 20:56:33', NULL, 2),
(20, 1, 'Cardio', '2025-03-19 20:56:46', NULL, 2),
(21, 1, 'Cardio', '2025-03-19 21:00:33', NULL, 2),
(22, 1, 'Cardio', '2025-03-19 21:00:50', NULL, 2),
(23, 1, 'Cardio', '2025-03-19 21:01:47', NULL, 2),
(24, 1, 'Lower body', '2025-03-19 21:01:58', NULL, 3),
(25, 1, 'Cardio', '2025-03-19 23:06:13', NULL, 2),
(26, 1, 'Cardio', '2025-03-19 23:12:48', '2025-03-19 23:26:58', 2),
(30, 3, 'Cardio', '2025-03-19 23:31:46', '2025-03-19 23:31:51', 2),
(31, 3, ' Upper body and core', '2025-03-19 23:41:50', '2025-03-19 23:43:04', 2);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `student_id`, `message`, `submitted_at`) VALUES
(1, 1, 'fbbffd', '2025-03-18 17:06:06');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `created_at`) VALUES
(1, 1, 'User ID: 1 checked out early. Remaining time: 01:59:58.', '2025-03-19 17:09:08'),
(2, 1, 'User ID: 1 checked out early. Remaining time: 01:59:58.', '2025-03-19 17:09:08');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `membership_type` enum('Monthly','Quarterly','Yearly') NOT NULL,
  `join_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `email`, `password`, `membership_type`, `join_date`) VALUES
(1, 'josh', 'muriukipjorn@gmail.com', '$2y$10$Epb0lgJtzI.K5WJv1aMuk.yh4EDa9lCB7kqfpkNGbhvraoUXirKG6', 'Monthly', '2025-03-18 16:57:21'),
(3, 'onions', 'onions@gmail.com', '$2y$10$opbe2q.4Hzxv1qc/GGEi9OlLwXpAkf/WwmFU/m2GCZQ5kVzyQcM9.', 'Quarterly', '2025-03-19 10:06:17');

-- --------------------------------------------------------

--
-- Table structure for table `weekly_activities`
--

CREATE TABLE `weekly_activities` (
  `id` int(11) NOT NULL,
  `day` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `activity` varchar(255) NOT NULL,
  `hours_required` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `weekly_activities`
--

INSERT INTO `weekly_activities` (`id`, `day`, `activity`, `hours_required`) VALUES
(1, 'Monday', 'Cardio', 2),
(2, 'Tuesday', 'Lower body', 3),
(3, 'Wednesday', ' Upper body and core', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `weekly_activities`
--
ALTER TABLE `weekly_activities`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `weekly_activities`
--
ALTER TABLE `weekly_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `students` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
