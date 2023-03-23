-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2023 at 08:24 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simpleshare`
--
CREATE DATABASE IF NOT EXISTS `simpleshare` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `simpleshare`;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `encodedName` varchar(255) NOT NULL,
  `uploaded` datetime NOT NULL,
  `share_list_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `sharelist`
--

CREATE TABLE `sharelist` (
  `id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sharelist`
--

INSERT INTO `sharelist` (`id`, `url`, `user_id`) VALUES
(1, 'XPyLbAIz', 1),
(2, 'xmSWIuPC', 1),
(3, 'EzKHMAcz', 1),
(4, 'IuVATWKG', 1),
(5, 'dWnIKDfM', 1),
(6, 'pBvcBbUD', 1),
(7, 'fjrsoQJQ', 1),
(8, 'LB6KfZlu', 1),
(9, 'nK0SVgrx', 1),
(10, 'AxmNgDT9', 1),
(11, 'FtEdOjjF', 1),
(12, 'mvmajK71', 1),
(13, 'f8zDycqC', 1),
(14, 'KxhE6c9O', 1),
(15, 'bS0Jfjk9', 1),
(16, 'e5fbWGUP', 1),
(17, 'h2pA3aM0', 1),
(18, 'FkI8NAkq', 1),
(19, 'zGOKT7if', 1),
(20, 'yoZDPC86', 1),
(21, 'HuRmceu8', 1),
(22, 'NGpU7wjZ', 1),
(23, '4b1ut74P', 1),
(24, 'WwvCHIPs', 1),
(25, '1dS1Lobk', 1),
(26, 'zR12IYHj', 1),
(27, 'Rd1iglSo', 1),
(28, 'aTwsBjID', 1),
(29, 'TEVneNBU', 1),
(30, 'cfiX8DZw', 1),
(31, 'UngnqRjN', 1),
(32, 'eO45nKAN', 1),
(33, 'V2xFtnEQ', 1),
(34, 'KdmTEOEo', 1),
(35, 'CckYrHGj', 1),
(36, 'sBqIlTgU', 1),
(37, 'K93cBJIn', 1),
(38, '0yZxQVco', 1),
(39, 'vFZpjEZy', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `share_list_id` (`share_list_id`);

--
-- Indexes for table `sharelist`
--
ALTER TABLE `sharelist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=454;

--
-- AUTO_INCREMENT for table `sharelist`
--
ALTER TABLE `sharelist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`share_list_id`) REFERENCES `sharelist` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
