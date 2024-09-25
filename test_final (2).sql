-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 25, 2024 at 03:18 AM
-- Server version: 8.0.36
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test_final`
--

-- --------------------------------------------------------

--
-- Table structure for table `ear_left`
--

CREATE TABLE `ear_left` (
  `ID` int NOT NULL,
  `frequency` int NOT NULL,
  `dB_level` float NOT NULL,
  `user_id` int NOT NULL,
  `test_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `ear_left`
--

INSERT INTO `ear_left` (`ID`, `frequency`, `dB_level`, `user_id`, `test_date`) VALUES
(1, 250, 10, 8, '2024-09-25 02:02:06'),
(2, 500, 120, 8, '2024-09-25 02:02:06'),
(3, 1000, 0, 8, '2024-09-25 02:02:06'),
(4, 2000, 25, 8, '2024-09-25 02:02:06'),
(5, 4000, 20, 8, '2024-09-25 02:02:06'),
(6, 6000, 10, 8, '2024-09-25 02:02:06'),
(7, 8000, 0, 8, '2024-09-25 02:02:06'),
(8, 250, 10, 8, '2024-09-25 03:03:45'),
(9, 500, 10, 8, '2024-09-25 03:03:45'),
(10, 500, 10, 8, '2024-09-25 03:09:57'),
(11, 1000, 10, 8, '2024-09-25 03:09:57'),
(12, 2000, 10, 8, '2024-09-25 03:09:57'),
(13, 4000, 10, 8, '2024-09-25 03:09:57'),
(14, 6000, 10, 8, '2024-09-25 03:09:57');

-- --------------------------------------------------------

--
-- Table structure for table `ear_right`
--

CREATE TABLE `ear_right` (
  `ID` int NOT NULL,
  `frequency` int NOT NULL,
  `dB_level` float NOT NULL,
  `user_id` int NOT NULL,
  `test_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `ear_right`
--

INSERT INTO `ear_right` (`ID`, `frequency`, `dB_level`, `user_id`, `test_date`) VALUES
(1, 250, 10, 8, '2024-09-25 02:16:20'),
(2, 500, 20, 8, '2024-09-25 02:16:20'),
(3, 1000, 25, 8, '2024-09-25 02:16:20'),
(4, 2000, 5, 8, '2024-09-25 02:16:20'),
(5, 4000, 15, 8, '2024-09-25 02:16:20'),
(6, 6000, 10, 8, '2024-09-25 02:16:20'),
(7, 8000, 25, 8, '2024-09-25 02:16:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_general_ci DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password_hash`, `role`, `created_at`) VALUES
(1, 'admin', '1234', 'admin', '2024-09-25 01:51:31'),
(8, 'user', '1234', 'user', '2024-09-25 02:43:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ear_left`
--
ALTER TABLE `ear_left`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `KEY` (`user_id`);

--
-- Indexes for table `ear_right`
--
ALTER TABLE `ear_right`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `KEY1` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ear_left`
--
ALTER TABLE `ear_left`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `ear_right`
--
ALTER TABLE `ear_right`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ear_left`
--
ALTER TABLE `ear_left`
  ADD CONSTRAINT `KEY` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ear_right`
--
ALTER TABLE `ear_right`
  ADD CONSTRAINT `KEY1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
