-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2025 at 10:23 PM
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
-- Database: `cigarette_tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `activity_text` varchar(255) NOT NULL,
  `activity_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `activity_text`, `activity_time`) VALUES
(1, 'Admin added 3 cigarettes for Tuhin', '2025-04-25 15:46:55');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`) VALUES
(1, 'admin@gmail.com', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `cigarettes`
--

CREATE TABLE `cigarettes` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `unit_price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cigarettes`
--

INSERT INTO `cigarettes` (`id`, `name`, `stock_quantity`, `price`, `image_path`, `created_at`, `unit_price`) VALUES
(14, 'Lucky Strike', 20, 12.00, '../uploads/full__2103_lucky-strike-supersonic.jpg', '2025-04-26 04:09:25', NULL),
(15, 'Gold Flake', 20, 12.00, '../uploads/BAN_DHA_L1_007_1.jpg', '2025-04-26 04:09:43', NULL),
(16, 'Derby', 20, 8.00, '../uploads/BAN_DHA_M4_03_005_0_0_2_4.jpg', '2025-04-26 04:10:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cigarette_logs`
--

CREATE TABLE `cigarette_logs` (
  `id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `cigarette_id` int(11) NOT NULL,
  `logged_at` datetime DEFAULT current_timestamp(),
  `log_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `phone` varchar(20) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `paid_amount` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `name`, `email`, `created_at`, `phone`, `photo`, `paid_amount`) VALUES
(6, 'Tanjil', 'info.taufiq09@gmail.com', '2025-04-26 01:29:58', '01645551349', '../uploads/members/144615963_2839772702977037_5431129722954286994_n.jpg', 0.00),
(7, 'Tanim', 'admin@tanim.com', '2025-04-26 01:30:07', '01756095371', '../uploads/members/IMG20230720141301.jpg', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `member_clear_logs`
--

CREATE TABLE `member_clear_logs` (
  `member_id` int(11) DEFAULT NULL,
  `cleared_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member_clear_logs`
--

INSERT INTO `member_clear_logs` (`member_id`, `cleared_at`) VALUES
(6, '2025-04-26 03:05:13'),
(6, '2025-04-26 03:05:37'),
(7, '2025-04-26 03:05:43'),
(6, '2025-04-26 03:15:17'),
(6, '2025-04-26 04:31:36'),
(6, '2025-04-26 17:37:34'),
(7, '2025-04-26 18:42:13'),
(6, '2025-04-26 23:08:38'),
(7, '2025-04-27 00:26:39'),
(6, '2025-04-27 00:26:42'),
(6, '2025-04-27 01:19:08'),
(7, '2025-04-27 01:19:10'),
(6, '2025-04-27 01:41:18'),
(7, '2025-04-27 01:41:21'),
(7, '2025-04-27 01:46:25'),
(6, '2025-04-27 01:46:29'),
(6, '2025-04-27 02:03:06');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_logs`
--

CREATE TABLE `payment_logs` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `cigarettes`
--
ALTER TABLE `cigarettes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cigarette_logs`
--
ALTER TABLE `cigarette_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_logs`
--
ALTER TABLE `payment_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_member_id` (`member_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cigarettes`
--
ALTER TABLE `cigarettes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `cigarette_logs`
--
ALTER TABLE `cigarette_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_logs`
--
ALTER TABLE `payment_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cigarette_logs`
--
ALTER TABLE `cigarette_logs`
  ADD CONSTRAINT `cigarette_logs_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_logs`
--
ALTER TABLE `payment_logs`
  ADD CONSTRAINT `fk_member_id` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_logs_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
