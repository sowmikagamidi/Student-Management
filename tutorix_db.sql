-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2026 at 11:24 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tutorix_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tx_class_batches`
--

CREATE TABLE `tx_class_batches` (
  `batch_id` int(11) NOT NULL,
  `board_id` varchar(4) NOT NULL,
  `class_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `class_name` varchar(200) NOT NULL,
  `section` varchar(100) DEFAULT NULL,
  `academic_year` year(4) DEFAULT NULL,
  `student_count` int(11) DEFAULT 0,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `created_dtm` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tx_class_batches`
--

INSERT INTO `tx_class_batches` (`batch_id`, `board_id`, `class_id`, `school_id`, `class_name`, `section`, `academic_year`, `student_count`, `start_date`, `end_date`, `created_dtm`) VALUES
(1, 'C', 6, 1, 'Class 6 - Section A', NULL, '2025', 0, NULL, NULL, '2026-05-13 16:11:15'),
(2, 'C', 7, 1, 'Class 7 - Section A', NULL, '2025', 0, NULL, NULL, '2026-05-13 16:11:15'),
(3, 'C', 8, 1, 'Class 8 - Section A', NULL, '2025', 0, NULL, NULL, '2026-05-13 16:11:15'),
(4, 'C', 9, 1, 'Class 9 - Section A', NULL, '2025', 0, NULL, NULL, '2026-05-13 16:11:15'),
(5, 'C', 10, 1, 'Class 10 - Section A', NULL, '2025', 0, NULL, NULL, '2026-05-13 16:11:15'),
(6, 'I', 6, 1, 'Class 6 - ICSE', NULL, '2025', 0, NULL, NULL, '2026-05-13 16:11:15'),
(7, 'I', 7, 1, 'Class 7 - ICSE', NULL, '2025', 0, NULL, NULL, '2026-05-13 16:11:15'),
(8, 'I', 8, 1, 'Class 8 - ICSE', NULL, '2025', 0, NULL, NULL, '2026-05-13 16:11:15'),
(9, 'I', 9, 1, 'Class 9 - ICSE', NULL, '2025', 0, NULL, NULL, '2026-05-13 16:11:15'),
(10, 'I', 10, 1, 'Class 10 - ICSE', NULL, '2025', 0, NULL, NULL, '2026-05-13 16:11:15'),
(11, 'C', 1, 1, 'Class 1', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:08:40'),
(12, 'C', 2, 1, 'Class 2', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:08:40'),
(13, 'C', 3, 1, 'Class 3', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:08:40'),
(14, 'C', 4, 1, 'Class 4', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:08:40'),
(15, 'C', 5, 1, 'Class 5', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:08:40'),
(16, 'C', 6, 1, 'Class 6', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:08:40'),
(17, 'C', 7, 1, 'Class 7', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:08:40'),
(18, 'C', 8, 1, 'Class 8', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:08:40'),
(19, 'C', 9, 1, 'Class 9', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:08:40'),
(20, 'C', 10, 1, 'Class 10', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:08:40'),
(21, 'C', 11, 1, 'Class 11', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:08:40'),
(22, 'C', 12, 1, 'Class 12', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:08:40'),
(23, 'C', 1, 2, 'Class 1', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:08:40'),
(24, 'C', 2, 2, 'Class 2', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:08:40'),
(25, 'C', 3, 2, 'Class 3', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:08:40'),
(26, 'C', 4, 2, 'Class 4', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:08:40'),
(27, 'C', 5, 2, 'Class 5', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:08:40'),
(28, 'C', 6, 2, 'Class 6', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:08:40'),
(29, 'C', 7, 2, 'Class 7', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:08:40'),
(30, 'C', 8, 2, 'Class 8', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:08:40'),
(31, 'C', 9, 2, 'Class 9', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:08:40'),
(32, 'C', 10, 2, 'Class 10', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:08:40'),
(33, 'C', 11, 2, 'Class 11', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:08:40'),
(34, 'C', 12, 2, 'Class 12', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:08:40'),
(35, 'C', 2, 5, 'Class 2 - Section A', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:22:36'),
(36, 'C', 1, 5, 'Class 1', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(37, 'C', 1, 3, 'Class 1', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(38, 'C', 1, 1, 'Class 1', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(39, 'C', 1, 2, 'Class 1', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(40, 'C', 1, 4, 'Class 1', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(41, 'C', 2, 5, 'Class 2', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(42, 'C', 2, 3, 'Class 2', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(43, 'C', 2, 1, 'Class 2', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(44, 'C', 2, 2, 'Class 2', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(45, 'C', 2, 4, 'Class 2', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(46, 'C', 3, 5, 'Class 3', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(47, 'C', 3, 3, 'Class 3', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(48, 'C', 3, 1, 'Class 3', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(49, 'C', 3, 2, 'Class 3', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(50, 'C', 3, 4, 'Class 3', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(51, 'C', 4, 5, 'Class 4', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(52, 'C', 4, 3, 'Class 4', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(53, 'C', 4, 1, 'Class 4', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(54, 'C', 4, 2, 'Class 4', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(55, 'C', 4, 4, 'Class 4', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(56, 'C', 5, 5, 'Class 5', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(57, 'C', 5, 3, 'Class 5', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(58, 'C', 5, 1, 'Class 5', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(59, 'C', 5, 2, 'Class 5', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(60, 'C', 5, 4, 'Class 5', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(61, 'C', 6, 5, 'Class 6', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(62, 'C', 6, 3, 'Class 6', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(63, 'C', 6, 1, 'Class 6', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(64, 'C', 6, 2, 'Class 6', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(65, 'C', 6, 4, 'Class 6', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(66, 'C', 7, 5, 'Class 7', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(67, 'C', 7, 3, 'Class 7', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(68, 'C', 7, 1, 'Class 7', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(69, 'C', 7, 2, 'Class 7', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(70, 'C', 7, 4, 'Class 7', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(71, 'C', 8, 5, 'Class 8', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(72, 'C', 8, 3, 'Class 8', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(73, 'C', 8, 1, 'Class 8', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(74, 'C', 8, 2, 'Class 8', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(75, 'C', 8, 4, 'Class 8', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(76, 'C', 9, 5, 'Class 9', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(77, 'C', 9, 3, 'Class 9', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(78, 'C', 9, 1, 'Class 9', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(79, 'C', 9, 2, 'Class 9', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(80, 'C', 9, 4, 'Class 9', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(81, 'C', 10, 5, 'Class 10', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(82, 'C', 10, 3, 'Class 10', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(83, 'C', 10, 1, 'Class 10', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(84, 'C', 10, 2, 'Class 10', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(85, 'C', 10, 4, 'Class 10', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(86, 'C', 11, 5, 'Class 11', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(87, 'C', 11, 3, 'Class 11', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(88, 'C', 11, 1, 'Class 11', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(89, 'C', 11, 2, 'Class 11', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(90, 'C', 11, 4, 'Class 11', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(91, 'C', 12, 5, 'Class 12', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(92, 'C', 12, 3, 'Class 12', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(93, 'C', 12, 1, 'Class 12', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(94, 'C', 12, 2, 'Class 12', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(95, 'C', 12, 4, 'Class 12', 'A', '2025', 0, NULL, NULL, '2026-05-13 17:28:01'),
(99, 'C', 3, 6, 'Class 3 - Section A', 'A', '2025', 0, NULL, NULL, '2026-05-13 18:05:59'),
(100, 'W', 8, 5, 'Class 8 - Section B', 'B', '2025', 0, NULL, NULL, '2026-05-14 10:04:16'),
(101, 'W', 7, 6, 'Class 7 - Section B', 'B', '2025', 0, NULL, NULL, '2026-05-14 10:18:31'),
(102, 'W', 6, 7, 'Class 6 - Section A', 'A', '2025', 0, NULL, NULL, '2026-05-14 10:32:53'),
(103, 'I', 12, 7, 'Class 12 - Section C', 'C', '2026', 0, NULL, NULL, '2026-05-14 10:42:34'),
(104, 'C', 12, 7, 'Class 12 - Section D', 'D', '2025', 0, NULL, NULL, '2026-05-14 14:03:07'),
(105, 'C', 12, 7, 'Class 12 - Section D', 'D', '2025', 0, NULL, NULL, '2026-05-14 14:03:24'),
(106, 'C', 2, 6, 'Class 2 - Section D', 'D', '2025', 0, NULL, NULL, '2026-05-14 15:26:36'),
(107, 'I', 12, 8, 'Class 12 - Section E', 'E', '2025', 0, NULL, NULL, '2026-05-14 17:47:58'),
(108, 'C', 12, 8, 'Class 12 - Section E', 'E', '2025', 0, NULL, NULL, '2026-05-15 10:10:37'),
(109, 'C', 12, 8, 'Class 12 - Section E', 'E', '2025', 0, NULL, NULL, '2026-05-15 10:10:51'),
(110, 'C', 1, 5, 'Class 1 - Section A', 'A', '2025', 0, NULL, NULL, '2026-05-15 10:19:20'),
(111, 'I', 7, 8, 'Class 7 - Section A', 'A', '2026', 0, NULL, NULL, '2026-05-15 10:32:10'),
(112, 'W', 4, 8, 'Class 4 - Section B', 'B', '2025', 0, NULL, NULL, '2026-05-15 10:32:24'),
(113, 'W', 4, 8, 'Class 4 - Section B', 'B', '2025', 0, NULL, NULL, '2026-05-15 11:37:59'),
(114, 'C', 1, 8, 'Class 1 - Section A', 'A', '2025', 0, NULL, NULL, '2026-05-15 11:51:07'),
(115, 'C', 1, 8, 'Class 1 - Section B', 'B', '2025', 100, NULL, NULL, '2026-05-15 12:02:45'),
(117, 'C', 3, 9, 'Class 3 - Section b', 'b', '2025', 100, NULL, NULL, '2026-05-15 16:45:36'),
(118, 'C', 4, 9, 'Class 4 - Section A', 'A', '2025', 100, NULL, NULL, '2026-05-15 16:45:43'),
(119, 'C', 6, 9, 'Class 6 - Section A', 'A', '2025', 100, NULL, NULL, '2026-05-15 16:45:59'),
(121, 'C', 12, 9, 'Class 12 - Section e', 'e', '2025', 100, NULL, NULL, '2026-05-15 16:46:17'),
(122, 'C', 9, 9, 'Class 9 - Section D', 'D', '2025', 100, NULL, NULL, '2026-05-15 16:46:28'),
(123, 'C', 1, 9, 'Class 1 - Section A', 'A', '2025', 100, NULL, NULL, '2026-05-19 14:21:57'),
(124, 'C', 2, 9, 'Class 2 - Section A', 'A', '2025', 100, NULL, NULL, '2026-05-19 14:22:17'),
(125, 'C', 4, 9, 'Class 4 - Section B', 'B', '2025', 100, NULL, NULL, '2026-05-19 14:22:42');

-- --------------------------------------------------------

--
-- Table structure for table `tx_mentor_batch_map`
--

CREATE TABLE `tx_mentor_batch_map` (
  `id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `board_id` varchar(4) NOT NULL,
  `mentor_id` int(11) NOT NULL,
  `subject_id` varchar(3) DEFAULT NULL,
  `school_id` int(11) NOT NULL,
  `created_dtm` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tx_mentor_batch_map`
--

INSERT INTO `tx_mentor_batch_map` (`id`, `batch_id`, `board_id`, `mentor_id`, `subject_id`, `school_id`, `created_dtm`) VALUES
(1, 41, 'C', 3, 'MAT', 5, '2026-05-14 17:11:23');

-- --------------------------------------------------------

--
-- Table structure for table `tx_purchase_history`
--

CREATE TABLE `tx_purchase_history` (
  `order_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `subscription_type` enum('D','B','P') NOT NULL,
  `subscription_days` int(11) NOT NULL,
  `board_id` varchar(4) NOT NULL,
  `academic_year` year(4) DEFAULT NULL,
  `currency` varchar(5) DEFAULT 'INR',
  `amount` float DEFAULT 0,
  `order_amount` float DEFAULT 0,
  `payment_status` enum('P','S','R','F','C') DEFAULT 'P',
  `payment_method` varchar(100) DEFAULT NULL,
  `discount_amount` float DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `created_dtm` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tx_school_api_keys`
--

CREATE TABLE `tx_school_api_keys` (
  `id` int(11) NOT NULL,
  `key` varchar(255) NOT NULL,
  `school_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `used` enum('Y','N') DEFAULT 'N',
  `validity` datetime NOT NULL,
  `used_date` date DEFAULT NULL,
  `created_dtm` datetime NOT NULL DEFAULT current_timestamp(),
  `register_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tx_school_api_keys`
--

INSERT INTO `tx_school_api_keys` (`id`, `key`, `school_id`, `class_id`, `used`, `validity`, `used_date`, `created_dtm`, `register_date`) VALUES
(1, 'TPTX-2025-2026-CL06', 1, 6, 'N', '2027-05-14 10:23:51', NULL, '2026-05-14 10:23:51', NULL),
(2, 'TPTX-2025-2026-CL07', 1, 7, 'N', '2027-05-14 10:23:51', NULL, '2026-05-14 10:23:51', NULL),
(3, 'RATX-2025-2026-1234', 1, 8, 'N', '2026-11-14 10:23:51', NULL, '2026-05-14 10:23:51', NULL),
(4, 'LMTX-2026-2027-CL06-F571', 7, 5, 'N', '2025-05-30 11:02:00', NULL, '2026-05-14 10:33:11', NULL),
(5, 'LMTX-2025-2026-KTRM', 5, 2, 'N', '2026-05-15 10:41:00', NULL, '2026-05-14 10:41:13', NULL),
(8, 'EDTX-2026-2027-CL05', 4, 5, 'N', '2026-05-26 10:54:00', NULL, '2026-05-14 10:54:11', NULL),
(9, 'EDTX-2026-2027-CL09', 2, 6, 'N', '2026-05-22 11:15:00', NULL, '2026-05-14 11:09:12', NULL),
(10, 'EDTX-2026-2027-BTXC', 7, 12, 'N', '2026-05-01 11:32:00', NULL, '2026-05-14 11:31:42', NULL),
(12, 'EDTX-2026-2027-CL06', 7, 6, 'N', '2026-06-05 14:03:00', NULL, '2026-05-14 14:04:10', NULL),
(13, 'RATX-2026-2027-FOZ9', 5, 1, 'N', '2026-08-14 09:47:42', NULL, '2026-05-14 15:17:44', NULL),
(14, 'LMTX-2025-2026-RMAD', 7, 12, 'N', '2026-08-14 10:34:36', NULL, '2026-05-14 16:04:38', NULL),
(15, 'RATX-2026-2027-SPH3', 8, 5, 'N', '2026-05-22 17:44:00', NULL, '2026-05-14 17:44:17', NULL),
(16, 'LMTX-2026-2027-CL05', 5, 5, 'N', '2026-11-15 04:51:14', NULL, '2026-05-15 10:21:16', NULL),
(17, 'EDTX-2026-2027-CL03', 9, 3, 'N', '2026-08-15 11:16:59', NULL, '2026-05-15 16:47:01', NULL),
(18, 'LMTX-2026-2027-CL09', 1, 9, 'N', '2027-05-18 00:00:00', NULL, '2026-05-18 13:17:27', NULL),
(19, 'LMTX-2026-2027-CL10', 9, 10, 'N', '2027-05-18 00:00:00', NULL, '2026-05-18 14:49:16', NULL),
(20, 'TPTX-2026-2027-CL02', 9, 2, 'N', '2027-05-19 00:00:00', NULL, '2026-05-19 11:21:17', NULL),
(21, 'EDTX-2026-2027-CL01', 9, 1, 'N', '2027-05-19 00:00:00', NULL, '2026-05-19 14:40:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tx_school_details`
--

CREATE TABLE `tx_school_details` (
  `school_id` int(11) NOT NULL,
  `school_code` varchar(20) NOT NULL,
  `school_name` varchar(255) NOT NULL,
  `board_id` varchar(4) DEFAULT 'C',
  `address` text DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `country_code` varchar(60) NOT NULL DEFAULT 'IN',
  `contact_person` varchar(100) DEFAULT NULL,
  `contact_email` varchar(150) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `gst_number` varchar(20) DEFAULT NULL,
  `status` enum('A','I') DEFAULT 'A',
  `created_dtm` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_dtm` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tx_school_details`
--

INSERT INTO `tx_school_details` (`school_id`, `school_code`, `school_name`, `board_id`, `address`, `city`, `state`, `postal_code`, `country_code`, `contact_person`, `contact_email`, `contact_phone`, `gst_number`, `status`, `created_dtm`, `updated_dtm`) VALUES
(1, 'TEST001', 'Test School', 'C', '', '', '', '', 'IN', '', '', '', '', 'A', '2026-05-13 15:28:39', '2026-05-14 17:27:04'),
(2, 'TEST002', 'chaitanya', 'C', NULL, 'hyderabad', 'telangana', NULL, 'IN', NULL, NULL, NULL, NULL, 'A', '2026-05-13 15:56:41', NULL),
(3, 'ABC003', 'narayana', 'C', '', 'hyderabad', 'telangana', '', 'IN', 'Sathish', '', '', '', 'A', '2026-05-13 15:57:15', '2026-05-14 11:26:26'),
(4, 'TEST004', 'oakridge', 'I', '2-84,Vijaya medicals , bajar street ,', 'Chennai', 'Tamilnadu', '533238', 'IN', 'Gamidi Lakshmi Sowmika', 'sowmikagamidi2004@gmail.com', '8639995359', '', 'A', '2026-05-13 16:04:54', '2026-05-13 16:07:21'),
(5, '12345', 'shamrock', 'W', 'madhapur', 'hyderabad', 'telangana', '500049', 'IN', 'sruthi', 'sruthi@gmail.com', '59841236884', '', 'A', '2026-05-13 17:18:52', NULL),
(6, 'TEST005', 'chaitanya', 'W', 'kondapur', 'rangareddy', 'telangana', '500049', 'IN', 'sai', 'sai@gmail.com', '8521473695', '', 'A', '2026-05-13 18:04:46', NULL),
(7, 'ABC004', 'narayana', 'W', '', '', '', '', 'IN', 'sharmila', '', '', '', 'A', '2026-05-14 10:08:34', '2026-05-14 14:02:43'),
(8, 'TEST006', 'shamrock', 'W', 'whitefield', 'Bangalore', 'Karnataka', '533238', 'IN', 'sanjay', 'sanjay@gmail.com', '8639995359', '', 'A', '2026-05-14 17:43:27', '2026-05-14 17:43:47'),
(9, 'GVP', 'Gayathri', 'I', 'postoffice road', '', '', '533239', 'IN', 'deepu', 'deepu@gmail.com', '8521479635', '', 'A', '2026-05-15 16:45:02', '2026-05-18 09:31:22');

-- --------------------------------------------------------

--
-- Table structure for table `tx_school_holidays`
--

CREATE TABLE `tx_school_holidays` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `academic_year` varchar(10) DEFAULT NULL COMMENT 'e.g. 2025',
  `class_id` int(11) DEFAULT NULL COMMENT 'NULL = all classes',
  `board_id` varchar(4) DEFAULT 'C',
  `holiday_date` date NOT NULL,
  `holiday_end_date` date DEFAULT NULL COMMENT 'If set, this becomes a vacation range: holiday_date to holiday_end_date',
  `holiday_name` varchar(200) DEFAULT NULL,
  `holiday_type` enum('H','W','O') NOT NULL DEFAULT 'H' COMMENT 'H=Holiday, W=Weekend/Custom-off, O=Other non-working',
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_dtm` datetime DEFAULT current_timestamp(),
  `updated_dtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tx_school_holidays`
--

INSERT INTO `tx_school_holidays` (`id`, `school_id`, `academic_year`, `class_id`, `board_id`, `holiday_date`, `holiday_end_date`, `holiday_name`, `holiday_type`, `is_deleted`, `created_dtm`, `updated_dtm`) VALUES
(1, 9, '2025', NULL, 'C', '2025-01-26', NULL, 'Republic Day', 'H', 0, '2026-05-20 12:52:45', NULL),
(2, 9, '2025', NULL, 'C', '2025-08-15', NULL, 'Independence Day', 'H', 0, '2026-05-20 12:52:45', NULL),
(3, 9, '2025', NULL, 'C', '2025-06-01', '2025-06-30', 'Summer Vacation', 'H', 1, '2026-05-20 12:52:45', NULL),
(4, 9, '2025', 10, 'C', '2025-10-20', '2025-10-23', 'Diwali Break', 'H', 0, '2026-05-20 12:52:45', '2026-05-20 13:45:45'),
(5, 8, '2025', NULL, 'W', '2025-10-01', '2025-10-15', 'Puja Holidays', 'H', 0, '2026-05-20 12:52:45', NULL),
(6, 6, '2027', 5, 'C', '2026-05-20', NULL, 'Holi', 'H', 0, '2026-05-20 14:01:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tx_school_licence`
--

CREATE TABLE `tx_school_licence` (
  `licence_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `school_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `subscription_type` enum('D','B','P') NOT NULL,
  `subscription_qty` int(11) DEFAULT 0,
  `available_qty` int(11) DEFAULT 0,
  `joining_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `created_dtm` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_dtm` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `used_status` enum('Y','N') DEFAULT 'N',
  `amount` decimal(10,2) DEFAULT 0.00,
  `paid_amount` decimal(10,2) DEFAULT 0.00,
  `discount` decimal(10,2) DEFAULT 0.00,
  `gateway_charges` decimal(10,2) DEFAULT 0.00,
  `tax` decimal(10,2) DEFAULT 0.00,
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_id` varchar(100) DEFAULT NULL,
  `currency` varchar(5) DEFAULT 'INR',
  `payment_method` varchar(50) DEFAULT NULL,
  `licence_type` varchar(10) DEFAULT 'lms',
  `batch_id` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tx_school_licence`
--

INSERT INTO `tx_school_licence` (`licence_id`, `order_id`, `school_id`, `class_id`, `subscription_type`, `subscription_qty`, `available_qty`, `joining_date`, `expiry_date`, `created_dtm`, `updated_dtm`, `used_status`, `amount`, `paid_amount`, `discount`, `gateway_charges`, `tax`, `transaction_id`, `payment_id`, `currency`, `payment_method`, `licence_type`, `batch_id`, `is_deleted`) VALUES
(1, NULL, 5, 9, '', NULL, NULL, '2026-05-13', '2027-05-13', '2026-05-13 17:28:49', '2026-05-19 10:51:15', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'tv', NULL, 0),
(2, NULL, 4, 1, '', NULL, NULL, '2026-05-06', '2027-05-30', '2026-05-13 17:30:43', '2026-05-19 10:51:15', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'tv', NULL, 0),
(3, NULL, 4, 2, '', NULL, NULL, '2026-05-14', '2027-05-13', '2026-05-13 17:31:19', '2026-05-19 10:51:15', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'tv', NULL, 0),
(4, NULL, 4, 8, '', 1, 1, '2026-05-13', '2027-05-13', '2026-05-13 17:39:24', '2026-05-19 10:51:15', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'tv', NULL, 0),
(5, NULL, 6, 3, '', 1, 1, '2026-05-13', '2027-05-13', '2026-05-13 18:06:20', '2026-05-19 10:51:15', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'tv', NULL, 0),
(6, NULL, 2, 10, '', 1, 1, '2026-05-14', '2027-05-14', '2026-05-14 10:05:18', '2026-05-19 10:51:15', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'tv', NULL, 0),
(7, NULL, 6, 7, 'P', 1, 1, '2026-05-14', '2027-05-14', '2026-05-14 10:19:18', '2026-05-19 10:51:15', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'tv', NULL, 0),
(8, NULL, 6, 7, 'P', 1, 1, '2026-05-14', '2027-05-14', '2026-05-14 14:14:26', '2026-05-19 10:51:15', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'tv', NULL, 0),
(10, NULL, 5, 8, 'D', 1, 1, '2026-05-14', '2027-05-14', '2026-05-14 15:16:40', '2026-05-19 10:51:15', 'Y', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'tv', NULL, 0),
(11, NULL, 5, 1, 'D', 0, 0, '2026-05-14', '2027-05-14', '2026-05-14 15:17:44', '2026-05-19 10:51:15', 'Y', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'tv', NULL, 0),
(12, 1, 5, 1, 'P', NULL, NULL, '0000-00-00', '2027-05-14', '2026-05-14 15:20:01', '2026-05-15 10:31:09', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'lms', NULL, 0),
(13, NULL, 7, 12, 'D', 0, 0, '2026-05-14', '2027-05-14', '2026-05-14 16:04:38', '2026-05-19 10:51:15', 'Y', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'tv', NULL, 0),
(14, NULL, 8, 5, 'D', 0, 0, '2026-05-14', '2027-05-14', '2026-05-14 17:44:17', '2026-05-19 10:51:15', 'Y', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'tv', NULL, 0),
(15, NULL, 8, 6, 'B', 1, 1, '2026-05-14', '2027-05-14', '2026-05-14 17:44:47', '2026-05-19 10:51:15', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'tv', NULL, 0),
(16, NULL, 5, 2, 'D', 0, 0, '2026-05-15', '2027-05-15', '2026-05-15 10:20:49', '2026-05-19 10:51:15', 'Y', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'tv', NULL, 0),
(17, NULL, 5, 5, 'D', 0, 0, '2026-05-15', '2027-05-15', '2026-05-15 10:21:16', '2026-05-19 10:51:15', 'Y', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'tv', NULL, 0),
(18, 2, 8, 7, 'B', 1, 1, '2026-05-15', '2027-05-15', '2026-05-15 10:32:42', NULL, 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'lms', NULL, 0),
(19, NULL, 8, 1, 'D', 1, 1, '2026-05-15', '0000-00-00', '2026-05-15 12:40:28', '2026-05-19 10:51:15', 'N', 2000.00, 999.98, 0.00, 20.00, 180.00, NULL, NULL, 'INR', 'RZ', 'tv', NULL, 0),
(21, NULL, 9, 3, 'D', 0, 0, '2026-05-15', '2027-05-15', '2026-05-15 16:47:01', '2026-05-19 10:51:15', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'tv', NULL, 0),
(23, NULL, 9, 6, 'D', 0, 0, '2026-05-18', '2027-05-18', '2026-05-18 13:12:34', '2026-05-19 10:51:15', 'Y', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'tv', NULL, 0),
(24, NULL, 9, 12, 'D', 0, 0, '2026-05-18', '2027-05-18', '2026-05-18 13:13:12', '2026-05-19 10:51:15', 'Y', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'tv', NULL, 0),
(25, NULL, 1, 6, 'D', 0, 0, '2026-05-18', '2027-05-18', '2026-05-18 13:13:47', '2026-05-19 10:51:15', 'Y', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'tv', NULL, 0),
(26, NULL, 1, 9, 'D', 0, 0, '2026-05-18', '2027-05-18', '2026-05-18 13:17:27', '2026-05-20 10:49:45', 'Y', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'tv', NULL, 1),
(27, NULL, 9, 10, 'D', 0, 0, '2026-05-18', '2027-05-18', '2026-05-18 14:49:16', '2026-05-19 10:51:15', 'Y', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'tv', NULL, 0),
(28, 0, 9, 2, 'D', 1, 0, '2026-05-18', '0000-00-00', '2026-05-18 16:23:20', '2026-05-19 10:51:15', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', 'RZ', 'tv', NULL, 0),
(29, 0, 9, 6, 'D', 1, 0, '2026-05-18', '0000-00-00', '2026-05-18 16:24:52', '2026-05-19 10:51:15', 'N', 1000.00, 990.00, 100.00, 19.80, 178.20, NULL, NULL, 'INR', 'BT', 'tv', NULL, 0),
(31, 0, 7, 12, 'D', 1, 0, '2026-05-18', '0000-00-00', '2026-05-18 16:59:51', '2026-05-19 10:51:15', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', 'CH', 'tv', NULL, 0),
(32, 0, 9, 2, 'D', 1, 0, '2026-05-18', '0000-00-00', '2026-05-18 17:03:50', '2026-05-19 10:51:15', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', 'RZ', 'tv', NULL, 0),
(33, 0, 7, 6, 'D', 1, 0, '2026-05-18', '0000-00-00', '2026-05-18 17:12:00', '2026-05-19 10:51:15', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', 'RZ', 'tv', NULL, 0),
(34, 0, 7, 6, 'D', 1, 0, '2026-05-18', '0000-00-00', '2026-05-18 17:18:59', '2026-05-19 10:51:15', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', 'RZ', 'tv', NULL, 0),
(35, 0, 1, 5, 'D', 1, 0, '2026-05-19', '0000-00-00', '2026-05-19 09:29:54', '2026-05-19 10:51:15', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', 'RZ', 'tv', NULL, 0),
(36, 0, 1, 7, 'D', 1, 0, '2026-05-19', '0000-00-00', '2026-05-19 09:52:05', '2026-05-19 10:51:15', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', 'BT', 'tv', NULL, 0),
(37, 0, 9, 9, 'D', 1, 0, '2026-05-19', '0000-00-00', '2026-05-19 10:27:25', '2026-05-19 10:51:15', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', 'RZ', 'tv', NULL, 0),
(38, 0, 9, 9, 'D', 1, 0, '2026-05-19', '0000-00-00', '2026-05-19 10:32:06', '2026-05-19 10:51:15', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', 'BT', 'tv', NULL, 0),
(39, 0, 9, 9, 'D', 1, 0, '2026-05-19', '0000-00-00', '2026-05-19 10:35:18', '2026-05-19 10:51:15', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', 'RZ', 'tv', NULL, 0),
(41, NULL, 9, 1, 'D', 0, 0, '2026-05-19', '2027-05-27', '2026-05-19 14:40:08', '2026-05-20 09:30:05', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'tv', 123, 1),
(44, 0, 8, 4, '', 1, 0, '0000-00-00', '2027-05-19', '2026-05-19 18:13:18', NULL, 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'lms', NULL, 0),
(45, 0, 9, 1, 'B', 100, 100, '2026-05-20', '2027-05-20', '2026-05-20 09:29:26', '2026-05-20 09:52:36', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'lms', NULL, 0),
(46, NULL, 9, 12, 'D', 0, 0, '2026-05-20', '2027-05-20', '2026-05-20 09:48:37', NULL, 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'tv', NULL, 0),
(47, 0, 1, 5, 'P', 100, 98, '2026-05-20', '2027-05-20', '2026-05-20 10:10:46', '2026-05-20 10:32:21', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'lms', NULL, 0),
(48, 0, 1, 10, 'D', 100, 99, '2026-05-20', '2027-05-20', '2026-05-20 10:25:00', '2026-05-20 10:49:28', 'N', 2000.00, 1900.03, 99.97, 0.00, 0.00, NULL, NULL, 'INR', 'Razorpay', 'lms', NULL, 0),
(49, 0, 1, 1, 'D', 1, 0, '2026-05-20', '2027-05-20', '2026-05-20 10:43:51', '2026-05-20 10:44:03', 'N', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', 'Razorpay', 'lms', NULL, 1),
(50, 0, 2, 5, 'D', 100, 99, '2026-05-20', '2027-05-20', '2026-05-20 10:44:53', NULL, 'N', 5000.00, 4000.00, 1000.00, 0.00, 0.00, NULL, NULL, 'INR', 'Razorpay', 'lms', NULL, 0),
(51, NULL, 4, 9, 'D', 0, 0, '2026-05-20', '2027-05-20', '2026-05-20 10:55:55', NULL, 'Y', 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'INR', NULL, 'tv', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tx_student_batch_map`
--

CREATE TABLE `tx_student_batch_map` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `created_dtm` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tx_student_batch_map`
--

INSERT INTO `tx_student_batch_map` (`id`, `student_id`, `batch_id`, `created_dtm`) VALUES
(1, 2, 103, '2026-05-14 16:38:23'),
(2, 5, 107, '2026-05-15 09:36:43'),
(3, 6, 111, '2026-05-15 13:04:50'),
(4, 7, 112, '2026-05-15 14:36:24'),
(5, 8, 1, '2026-05-15 15:28:17'),
(6, 9, 2, '2026-05-15 15:28:17'),
(7, 10, 3, '2026-05-15 15:28:17'),
(8, 11, 1, '2026-05-15 15:31:38'),
(9, 12, 122, '2026-05-15 17:21:37'),
(10, 13, 1, '2026-05-18 10:30:27'),
(11, 14, 2, '2026-05-18 10:30:27'),
(12, 15, 103, '2026-05-19 09:42:35'),
(13, 16, 7, '2026-05-19 09:59:15'),
(14, 17, 1, '2026-05-19 11:29:20'),
(15, 18, 123, '2026-05-19 14:46:49');

-- --------------------------------------------------------

--
-- Table structure for table `tx_student_enrollment`
--

CREATE TABLE `tx_student_enrollment` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `student_id` int(11) NOT NULL,
  `subscription_type` enum('B','P','D') DEFAULT 'D',
  `class_id` int(11) NOT NULL,
  `subject_id` varchar(50) DEFAULT NULL,
  `board_id` varchar(4) NOT NULL,
  `school_id` int(11) NOT NULL,
  `joining_date` datetime NOT NULL,
  `expiry_date` datetime NOT NULL,
  `created_dtm` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tx_student_enrollment`
--

INSERT INTO `tx_student_enrollment` (`id`, `order_id`, `student_id`, `subscription_type`, `class_id`, `subject_id`, `board_id`, `school_id`, `joining_date`, `expiry_date`, `created_dtm`) VALUES
(1, NULL, 1, 'B', 1, NULL, 'C', 1, '2026-05-14 00:00:00', '2027-05-14 00:00:00', '2026-05-14 11:46:38'),
(2, NULL, 2, 'D', 6, NULL, 'C', 7, '2026-05-14 00:00:00', '2027-05-14 00:00:00', '2026-05-14 16:38:23'),
(3, NULL, 5, 'B', 12, NULL, 'C', 8, '2026-05-15 00:00:00', '2027-05-15 00:00:00', '2026-05-15 09:36:43'),
(4, NULL, 6, 'B', 7, NULL, 'C', 8, '2026-05-15 00:00:00', '2027-05-15 00:00:00', '2026-05-15 13:04:50'),
(5, NULL, 7, 'D', 4, NULL, 'W', 8, '2026-05-15 00:00:00', '2027-05-15 00:00:00', '2026-05-15 14:36:24'),
(6, NULL, 8, 'D', 6, NULL, 'C', 8, '2026-05-14 00:00:00', '2027-05-14 00:00:00', '2026-05-15 15:28:17'),
(7, NULL, 9, 'B', 7, NULL, 'I', 8, '2026-05-14 00:00:00', '2027-05-14 00:00:00', '2026-05-15 15:28:17'),
(8, NULL, 10, 'P', 8, NULL, 'W', 8, '2026-05-14 00:00:00', '2027-05-14 00:00:00', '2026-05-15 15:28:17'),
(9, NULL, 11, 'D', 6, NULL, 'C', 8, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2026-05-15 15:31:38'),
(10, NULL, 12, 'D', 9, NULL, 'C', 9, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2026-05-15 17:21:37'),
(11, NULL, 13, 'D', 6, NULL, 'C', 9, '0000-00-00 00:00:00', '2027-05-14 00:00:00', '2026-05-18 10:30:27'),
(12, NULL, 14, 'B', 7, NULL, 'I', 9, '0000-00-00 00:00:00', '2027-05-14 00:00:00', '2026-05-18 10:30:27'),
(13, NULL, 15, 'D', 12, NULL, 'C', 7, '0000-00-00 00:00:00', '2027-05-19 00:00:00', '2026-05-19 09:42:35'),
(14, NULL, 16, 'D', 7, NULL, 'C', 1, '0000-00-00 00:00:00', '2027-05-19 00:00:00', '2026-05-19 09:59:15'),
(15, NULL, 17, 'D', 6, NULL, 'C', 1, '0000-00-00 00:00:00', '2027-05-14 00:00:00', '2026-05-19 11:29:20'),
(16, NULL, 18, 'B', 1, NULL, 'C', 9, '0000-00-00 00:00:00', '2027-05-19 00:00:00', '2026-05-19 14:46:49'),
(17, NULL, 19, 'B', 5, NULL, 'C', 1, '0000-00-00 00:00:00', '2027-05-20 00:00:00', '2026-05-20 10:31:22'),
(18, NULL, 20, 'D', 6, NULL, '', 1, '0000-00-00 00:00:00', '2027-05-20 00:00:00', '2026-05-20 10:32:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `user_name` varchar(200) DEFAULT NULL,
  `email_id` varchar(150) DEFAULT '',
  `country_code` varchar(6) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `role` enum('admin','teacher','mentor','student','accountant') DEFAULT 'student',
  `password` varchar(255) NOT NULL,
  `current_class` int(11) DEFAULT NULL,
  `user_status` char(1) NOT NULL DEFAULT 'A' COMMENT 'A-Active,B-Blocked,D-Disabled,X-Deleted',
  `user_type` char(5) NOT NULL DEFAULT 'U' COMMENT 'U-User,A-Admin,M-SME,T-Teacher,ST-School Teacher,H-HR,E-Executive,LCT-Live Class Teacher,S-School/Principal,SA-Study Admin,SU-Study User,R-Moderator',
  `gender` char(1) DEFAULT NULL,
  `created_dtm` datetime NOT NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0,
  `updated_dtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `user_name`, `email_id`, `country_code`, `school_id`, `mobile_number`, `role`, `password`, `current_class`, `user_status`, `user_type`, `gender`, `created_dtm`, `is_deleted`, `updated_dtm`) VALUES
(1, NULL, NULL, 'test@example.com', NULL, 1, '9876543210', 'student', '482c811da5d5b4bc6d497ffa98491e38', NULL, 'D', 'U', NULL, '2026-05-14 11:46:38', 1, NULL),
(2, NULL, NULL, '', NULL, 7, NULL, 'student', '482c811da5d5b4bc6d497ffa98491e38', NULL, 'A', 'U', NULL, '2026-05-14 16:38:23', 0, NULL),
(3, NULL, NULL, '', NULL, 5, NULL, 'teacher', '482c811da5d5b4bc6d497ffa98491e38', NULL, 'A', 'U', NULL, '2026-05-14 17:11:23', 0, NULL),
(4, NULL, NULL, '', NULL, 8, NULL, 'mentor', '482c811da5d5b4bc6d497ffa98491e38', NULL, 'A', 'U', NULL, '2026-05-14 17:47:14', 0, NULL),
(5, NULL, NULL, '', NULL, 8, NULL, 'student', '482c811da5d5b4bc6d497ffa98491e38', NULL, 'A', 'U', NULL, '2026-05-15 09:36:43', 0, NULL),
(6, NULL, NULL, '', NULL, 8, NULL, 'student', '482c811da5d5b4bc6d497ffa98491e38', NULL, 'A', 'U', NULL, '2026-05-15 13:04:50', 0, NULL),
(7, NULL, NULL, '', NULL, 8, NULL, 'student', '482c811da5d5b4bc6d497ffa98491e38', NULL, 'A', 'U', NULL, '2026-05-15 14:36:24', 0, NULL),
(8, NULL, NULL, '', NULL, 8, NULL, 'student', '482c811da5d5b4bc6d497ffa98491e38', NULL, 'A', 'U', NULL, '2026-05-15 15:28:17', 0, NULL),
(9, NULL, NULL, '', NULL, 8, NULL, 'student', '482c811da5d5b4bc6d497ffa98491e38', NULL, 'A', 'U', NULL, '2026-05-15 15:28:17', 0, NULL),
(10, NULL, NULL, '', NULL, 8, NULL, 'student', '482c811da5d5b4bc6d497ffa98491e38', NULL, 'A', 'U', NULL, '2026-05-15 15:28:17', 0, NULL),
(11, 'sowmika', NULL, 'sowmikagamidi2004@gmail.com', NULL, 8, '8639995359', 'student', '482c811da5d5b4bc6d497ffa98491e38', NULL, 'A', 'U', NULL, '2026-05-15 15:31:37', 0, NULL),
(12, 'sai_kona', 'sai', 'sai@gmail.com', '+91', 9, '9876541235', 'student', '482c811da5d5b4bc6d497ffa98491e38', 9, 'A', '0', 'M', '2026-05-15 17:21:37', 0, NULL),
(13, 'John Doe', 'johndoe', 'johndoe@school.com', NULL, 9, '9876543210', 'student', '482c811da5d5b4bc6d497ffa98491e38', NULL, 'A', 'SU', NULL, '2026-05-18 10:30:27', 0, NULL),
(14, 'Jane Smith', 'janesmith', 'jane@school.com', NULL, 9, '9876543211', 'student', '482c811da5d5b4bc6d497ffa98491e38', NULL, 'A', 'SU', NULL, '2026-05-18 10:30:27', 0, NULL),
(15, 'sruthi', 'sruthi', 'sruthi@gmail.com', '+91', 7, '9876543217', 'student', '482c811da5d5b4bc6d497ffa98491e38', 12, 'A', '0', 'F', '2026-05-19 09:42:35', 0, NULL),
(16, 'sruthi', 'sruthi2', 'sruthi123@gmail.com', '+91', 1, '7896543216', 'student', '482c811da5d5b4bc6d497ffa98491e38', 7, 'A', '0', 'F', '2026-05-19 09:59:15', 0, NULL),
(17, 'John Doe', 'johndoe1', 'john@school.com', NULL, 1, '9876543210', 'student', '482c811da5d5b4bc6d497ffa98491e38', NULL, 'D', 'SU', NULL, '2026-05-19 11:29:20', 1, NULL),
(18, 'Gamidi Lakshmi Sowmika', 'gamidi.lakshmi.sowmika', 'sowmikagamidi2084@gmail.com', '+1', 9, '9090890789', 'student', '482c811da5d5b4bc6d497ffa98491e38', 1, 'A', '0', 'F', '2026-05-19 14:46:49', 0, NULL),
(19, 'vishnu', 'vishnu', 'vishnu@gmail.com', '+91', 1, '9876543214', 'student', '482c811da5d5b4bc6d497ffa98491e38', 5, 'A', '0', 'M', '2026-05-20 10:31:22', 0, NULL),
(20, 'teja', 'teja', 'teja@gmail.com', '+91', 1, '8765432190', 'student', '482c811da5d5b4bc6d497ffa98491e38', 6, 'A', '0', 'F', '2026-05-20 10:32:21', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tx_class_batches`
--
ALTER TABLE `tx_class_batches`
  ADD PRIMARY KEY (`batch_id`),
  ADD KEY `idx_school_id` (`school_id`);

--
-- Indexes for table `tx_mentor_batch_map`
--
ALTER TABLE `tx_mentor_batch_map`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_batch_id` (`batch_id`),
  ADD KEY `idx_mentor_id` (`mentor_id`);

--
-- Indexes for table `tx_purchase_history`
--
ALTER TABLE `tx_purchase_history`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `idx_school_id` (`school_id`);

--
-- Indexes for table `tx_school_api_keys`
--
ALTER TABLE `tx_school_api_keys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_key` (`key`),
  ADD KEY `idx_school_id` (`school_id`);

--
-- Indexes for table `tx_school_details`
--
ALTER TABLE `tx_school_details`
  ADD PRIMARY KEY (`school_id`),
  ADD UNIQUE KEY `uk_school_code` (`school_code`);

--
-- Indexes for table `tx_school_holidays`
--
ALTER TABLE `tx_school_holidays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_school_id` (`school_id`),
  ADD KEY `idx_academic_year` (`academic_year`),
  ADD KEY `idx_holiday_date` (`holiday_date`);

--
-- Indexes for table `tx_school_licence`
--
ALTER TABLE `tx_school_licence`
  ADD PRIMARY KEY (`licence_id`),
  ADD KEY `idx_school_id` (`school_id`),
  ADD KEY `idx_order_id` (`order_id`);

--
-- Indexes for table `tx_student_batch_map`
--
ALTER TABLE `tx_student_batch_map`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_student_id` (`student_id`),
  ADD KEY `idx_batch_id` (`batch_id`);

--
-- Indexes for table `tx_student_enrollment`
--
ALTER TABLE `tx_student_enrollment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_student_id` (`student_id`),
  ADD KEY `idx_school_id` (`school_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `unique_user_name` (`user_name`),
  ADD KEY `idx_school_id` (`school_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tx_class_batches`
--
ALTER TABLE `tx_class_batches`
  MODIFY `batch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `tx_mentor_batch_map`
--
ALTER TABLE `tx_mentor_batch_map`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tx_purchase_history`
--
ALTER TABLE `tx_purchase_history`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tx_school_api_keys`
--
ALTER TABLE `tx_school_api_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tx_school_details`
--
ALTER TABLE `tx_school_details`
  MODIFY `school_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tx_school_holidays`
--
ALTER TABLE `tx_school_holidays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tx_school_licence`
--
ALTER TABLE `tx_school_licence`
  MODIFY `licence_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `tx_student_batch_map`
--
ALTER TABLE `tx_student_batch_map`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tx_student_enrollment`
--
ALTER TABLE `tx_student_enrollment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
