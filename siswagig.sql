-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2026 at 09:18 AM
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
-- Database: `siswagig`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `freelancer_id` int(11) NOT NULL,
  `freelancer_name` varchar(100) NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `job_id`, `freelancer_id`, `freelancer_name`, `status`, `applied_at`) VALUES
(1, 2, 3, '', 'accepted', '2026-06-13 11:14:27'),
(2, 2, 4, '', 'accepted', '2026-06-13 12:25:53'),
(3, 4, 5, '', 'accepted', '2026-06-15 03:14:46'),
(4, 5, 5, '', 'accepted', '2026-06-15 23:28:21'),
(5, 6, 6, '', 'accepted', '2026-06-17 11:51:14'),
(6, 7, 6, '', 'pending', '2026-06-17 12:02:03'),
(7, 8, 6, '', 'pending', '2026-06-18 13:52:14'),
(8, 9, 8, '', 'pending', '2026-06-23 04:29:09');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `details` text DEFAULT NULL,
  `requirement` text DEFAULT NULL,
  `platform` enum('Online','In-person') DEFAULT 'Online',
  `datetime` datetime DEFAULT NULL,
  `payment_type` enum('One-time','Per hour') DEFAULT 'One-time',
  `duration` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `posted_by` varchar(100) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `posted_by_id` int(11) NOT NULL,
  `status` enum('open','in_progress','completed') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `description`, `details`, `requirement`, `platform`, `datetime`, `payment_type`, `duration`, `amount`, `posted_by`, `user_id`, `posted_by_id`, `status`, `created_at`) VALUES
(2, 'bina website', 'buat website simple', 'freelance siswa', 'HTML, CSS, JAVASCRIPT', 'Online', '2026-06-13 06:53:07', 'One-time', '7', 200.00, 'Juju', 1, 1, 'in_progress', '2026-06-13 04:53:33'),
(4, 'buat aplikasi', 'buat aplikasi ringkas', 'aplikasi music player', 'HTML, CSS, JAVASCRIPT,PHP', 'Online', '2026-06-13 22:35:40', 'One-time', '20', 250.00, 'Juju', 1, 1, 'completed', '2026-06-13 20:36:40'),
(5, 'buat code java', 'buat code java', 'simple dan mudah', 'JAVA', 'Online', '2026-06-16 01:26:09', 'One-time', '3', 10.00, 'Juju', 1, 1, 'completed', '2026-06-15 23:27:05'),
(6, 'Cuci Kereta', 'Jangan sampai calar', 'kereta mercedez benz', 'TIADA KEMAHIRAN DIPERLUKAN', 'In-person', '2026-06-17 13:49:14', 'One-time', '1 HARI', 50.00, 'Izuddin', 5, 5, 'completed', '2026-06-17 11:50:04'),
(7, 'Help me study for exam', 'CSC264 Chapter 6', 'I\'m pretty weak at coding', 'HTML, CSS, JAVASCRIPT,PHP', 'In-person', '2026-06-17 13:58:29', 'Per hour', '2 HARI', 30.00, 'Izuddin', 5, 5, 'open', '2026-06-17 12:00:57'),
(8, 'buat code html', 'html', 'simple', 'html', 'Online', '2026-06-18 15:50:59', 'One-time', '3', 25.00, 'Juju', 1, 1, 'open', '2026-06-18 13:51:32'),
(9, 'Buat app MIT', 'buat di website MIT inventor app', 'simple dan clean', 'Designer, Interface, Database', 'Online', '2026-06-22 10:39:42', 'One-time', '5', 50.00, 'Juju', 1, 1, 'open', '2026-06-22 08:40:55');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `freelancer_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `hours_worked` decimal(5,2) DEFAULT NULL,
  `payment_type` varchar(50) NOT NULL,
  `status` enum('pending','completed') DEFAULT 'pending',
  `paid_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `job_id`, `client_id`, `freelancer_id`, `amount`, `hours_worked`, `payment_type`, `status`, `paid_at`, `created_at`) VALUES
(1, 5, 1, 5, 10.00, 0.00, 'One-time', 'completed', '2026-06-16 07:32:05', '2026-06-16 07:32:05'),
(2, 4, 1, 5, 250.00, 0.00, 'One-time', 'completed', '2026-06-17 19:47:05', '2026-06-17 19:47:05'),
(3, 6, 5, 6, 50.00, 0.00, 'One-time', 'completed', '2026-06-17 19:56:34', '2026-06-17 19:56:34');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `account_no` varchar(20) NOT NULL,
  `role` enum('freelancer','client','admin') DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `hourly_rate` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `account_no`, `role`, `skills`, `hourly_rate`, `created_at`) VALUES
(1, 'Juju', 'mohdizwan1312@gmail.com', '1234', '12345678987654', 'freelancer', 'HTML,Django,Flutter', 50.00, '2026-06-13 00:02:15'),
(3, 'Qiwez', 'mohdizwan13@gmail.com', '1234', '123698745963214785', 'freelancer', 'HTML,SEO,Copywriting,UI/UX Design', 25.00, '2026-06-13 11:12:06'),
(4, 'iwex', 'mohdizwan15@gmail.com', '1234', '14896516846513', 'freelancer', 'HTML,CSS,SEo', 25.00, '2026-06-13 12:23:46'),
(5, 'Izuddin', 'izuddin1165@gmail.com', '1234', '1234567890987654', 'freelancer', 'Flutter,Django,UI/UX Design', 25.00, '2026-06-13 12:29:05'),
(6, 'Kuwe', 'kuwe1213@gmail.com', '1234', '48846125168849', 'freelancer', 'Video Editing,Machine Learning,WordPress', 25.00, '2026-06-15 02:56:31'),
(7, 'Admin', 'admin@siswagig.com', 'admin123', '000000000000', 'admin', NULL, 0.00, '2026-06-15 23:40:25'),
(8, 'Muaz', 'muaz1213@gmail.com', '1234', '1233490987654323456', 'freelancer', 'SEO,HTML,CSS', 25.00, '2026-06-23 04:02:05'),
(9, 'ahmad', 'ahmad123@gmail.com', '1234', '123456789098', 'freelancer', 'Flutter,Django,UI/UX Design', 123.00, '2026-06-23 05:54:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `freelancer_id` (`freelancer_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posted_by_id` (`posted_by_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `freelancer_id` (`freelancer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`freelancer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`posted_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`freelancer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
