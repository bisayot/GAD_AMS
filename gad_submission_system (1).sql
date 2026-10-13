-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 12, 2026 at 12:40 PM
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
-- Database: `gad_submission_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `accomplishment_budget_items`
--

CREATE TABLE `accomplishment_budget_items` (
  `id` int(11) NOT NULL,
  `accomplishment_report_id` int(11) NOT NULL,
  `venue_id` int(11) UNSIGNED DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `item_name` varchar(100) NOT NULL,
  `sub_item` varchar(100) DEFAULT NULL,
  `pax` int(11) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accomplishment_budget_items`
--

INSERT INTO `accomplishment_budget_items` (`id`, `accomplishment_report_id`, `venue_id`, `category_id`, `item_name`, `sub_item`, `pax`, `amount`) VALUES
(18, 1, 2, NULL, 'Breakfast', '₱220.00 x 10 pax', 10, 2200.00),
(19, 1, 2, NULL, 'PM Snack', '₱80.00 x 1500 pax', 1500, 120000.00),
(20, 1, 2, NULL, 'Function Room/Venue', '₱1,700.00 x 1 item', NULL, 1700.00),
(21, 1, 2, NULL, 'Accommodation', '₱0.00 x 1 item', NULL, 0.00),
(22, 1, 2, NULL, 'Equipment Rental', '₱0.00 x 1 item', NULL, 0.00),
(23, 1, 2, NULL, 'Transportation', '₱0.00 x 1 item', NULL, 0.00),
(24, 1, 2, NULL, 'Professional Fee/Honoraria', '₱2,258.25 x 0 speakers', NULL, 0.00),
(25, 1, 2, NULL, 'Token/s', '₱1,000.00 x 0 recipients', NULL, 0.00),
(26, 1, 2, NULL, 'Materials and Supplies', '₱0.00 x 1 item', NULL, 0.00),
(27, 1, 1, NULL, 'Breakfast', '₱220.00 x 10 pax', 10, 2200.00),
(28, 1, 1, NULL, 'Function Room/Venue', '₱0.00 x 1 item', NULL, 0.00),
(29, 1, 1, NULL, 'Accommodation', '₱0.00 x 1 item', NULL, 0.00),
(30, 1, 1, NULL, 'Equipment Rental', '₱0.00 x 1 item', NULL, 0.00),
(31, 1, 1, NULL, 'Transportation', '₱0.00 x 1 item', NULL, 0.00),
(32, 1, 1, NULL, 'Professional Fee/Honoraria', '₱2,258.25 x 0 speakers', NULL, 0.00),
(33, 1, 1, NULL, 'Token/s', '₱1,000.00 x 0 recipients', NULL, 0.00),
(34, 1, 1, NULL, 'Materials and Supplies', '₱0.00 x 1 item', NULL, 0.00),
(35, 2, 2, NULL, 'Breakfast', '₱220.00 x 10 pax x 1 days', 10, 2200.00),
(36, 2, 2, NULL, 'Function Room/Venue', '₱0.00', NULL, 0.00),
(37, 2, 2, NULL, 'Accommodation', '₱0.00', NULL, 0.00),
(38, 2, 2, NULL, 'Equipment Rental', '₱0.00', NULL, 0.00),
(39, 2, 2, NULL, 'Transportation', '₱0.00', NULL, 0.00),
(40, 2, 2, NULL, 'Professional Fee/Honoraria', '₱2,258.25 x 0 speakers', NULL, 0.00),
(41, 2, 2, NULL, 'Token/s', '₱1,000.00 x 0 recipients', NULL, 0.00),
(42, 2, 2, NULL, 'Materials and Supplies', '₱0.00', NULL, 0.00),
(43, 3, 2, NULL, 'Breakfast', '₱220.00 x 16555 pax', 16555, 3642100.00),
(44, 3, 2, NULL, 'Function Room/Venue', '₱0.00', NULL, 0.00),
(45, 3, 2, NULL, 'Accommodation', '₱0.00', NULL, 0.00),
(46, 3, 2, NULL, 'Equipment Rental', '₱0.00', NULL, 0.00),
(47, 3, 2, NULL, 'Transportation', '₱0.00', NULL, 0.00),
(48, 3, 2, NULL, 'Professional Fee/Honoraria', '₱2,258.25 x 0 speakers', NULL, 0.00),
(49, 3, 2, NULL, 'Token/s', '₱1,000.00 x 0 recipients', NULL, 0.00),
(50, 3, 2, NULL, 'Materials and Supplies', '₱0.00', NULL, 0.00),
(51, 3, 37, NULL, 'Breakfast', '₱350.00 x 1655 pax', 1655, 579250.00),
(52, 3, 37, NULL, 'Function Room/Venue', '₱0.00', NULL, 0.00),
(53, 3, 37, NULL, 'Accommodation', '₱0.00', NULL, 0.00),
(54, 3, 37, NULL, 'Equipment Rental', '₱0.00', NULL, 0.00),
(55, 3, 37, NULL, 'Transportation', '₱0.00', NULL, 0.00),
(56, 3, 37, NULL, 'Professional Fee/Honoraria', '₱2,258.25 x 0 speakers', NULL, 0.00),
(57, 3, 37, NULL, 'Token/s', '₱1,000.00 x 0 recipients', NULL, 0.00),
(58, 3, 37, NULL, 'Materials and Supplies', '₱0.00', NULL, 0.00),
(93, 4, 2, NULL, 'Lunch', '₱220.00 x 60 pax', 60, 13200.00),
(94, 4, 2, NULL, 'Function Room/Venue', '₱0.00 x 1 item', NULL, 0.00),
(95, 4, 2, NULL, 'Accommodation', '₱0.00 x 1 item', NULL, 0.00),
(96, 4, 2, NULL, 'Equipment Rental', '₱0.00 x 1 item', NULL, 0.00),
(97, 4, 2, NULL, 'Transportation', '₱0.00 x 1 item', NULL, 0.00),
(98, 4, 2, NULL, 'Professional Fee/Honoraria', '₱2,258.25 x 0 speakers', NULL, 0.00),
(99, 4, 2, NULL, 'Token/s', '₱1,000.00 x 0 recipients', NULL, 0.00),
(100, 4, 2, NULL, 'Materials and Supplies', '₱0.00 x 1 item', NULL, 0.00),
(101, 4, 1, NULL, 'Breakfast', '₱220.00 x 60 pax', 60, 13200.00),
(102, 4, 1, NULL, 'Function Room/Venue', '₱0.00 x 1 item', NULL, 0.00),
(103, 4, 1, NULL, 'Accommodation', '₱0.00 x 1 item', NULL, 0.00),
(104, 4, 1, NULL, 'Equipment Rental', '₱0.00 x 1 item', NULL, 0.00),
(105, 4, 1, NULL, 'Transportation', '₱0.00 x 1 item', NULL, 0.00),
(106, 4, 1, NULL, 'Professional Fee/Honoraria', '₱2,258.25 x 0 speakers', NULL, 0.00),
(107, 4, 1, NULL, 'Token/s', '₱1,000.00 x 0 recipients', NULL, 0.00),
(108, 4, 1, NULL, 'Materials and Supplies', '₱0.00 x 1 item', NULL, 0.00),
(109, 6, 2, NULL, 'Breakfast', '₱220.00 x 10 pax x 3 days', 10, 6600.00),
(110, 6, 2, NULL, 'Function Room/Venue', '₱0.00', NULL, 0.00),
(111, 6, 2, NULL, 'Accommodation', '₱0.00', NULL, 0.00),
(112, 6, 2, NULL, 'Equipment Rental', '₱0.00', NULL, 0.00),
(113, 6, 2, NULL, 'Transportation', '₱0.00', NULL, 0.00),
(114, 6, 2, NULL, 'Professional Fee/Honoraria', '₱2,258.25 x 0 speakers', NULL, 0.00),
(115, 6, 2, NULL, 'Token/s', '₱1,000.00 x 0 recipients', NULL, 0.00),
(116, 6, 2, NULL, 'Materials and Supplies', '₱0.00', NULL, 0.00),
(117, 5, 2, NULL, 'Breakfast', '₱220.00 x 10 pax x 1 days', 10, 2200.00),
(118, 5, 2, NULL, 'PM Snack', '₱85.00 x 10 pax x 1 days', 10, 850.00),
(119, 5, 2, NULL, 'Function Room/Venue', '₱0.00 x 1 item', NULL, 0.00),
(120, 5, 2, NULL, 'Accommodation', '₱0.00 x 1 item', NULL, 0.00),
(121, 5, 2, NULL, 'Equipment Rental', '₱0.00 x 1 item', NULL, 0.00),
(122, 5, 2, NULL, 'Transportation', '₱0.00 x 1 item', NULL, 0.00),
(123, 5, 2, NULL, 'Professional Fee/Honoraria', '₱2,258.25 x 0 speakers', NULL, 0.00),
(124, 5, 2, NULL, 'Token/s', '₱1,000.00 x 0 recipients', NULL, 0.00),
(125, 5, 2, NULL, 'Materials and Supplies', '₱0.00 x 1 item', NULL, 0.00),
(126, 5, 37, NULL, 'Breakfast', '₱350.00 x 10 pax x 1 days', 10, 3500.00),
(127, 5, 37, NULL, 'PM Snack', '₱150.00 x 10 pax x 1 days', 10, 1500.00),
(128, 5, 37, NULL, 'Function Room/Venue', '₱0.00 x 1 item', NULL, 0.00),
(129, 5, 37, NULL, 'Accommodation', '₱0.00 x 1 item', NULL, 0.00),
(130, 5, 37, NULL, 'Equipment Rental', '₱0.00 x 1 item', NULL, 0.00),
(131, 5, 37, NULL, 'Transportation', '₱0.00 x 1 item', NULL, 0.00),
(132, 5, 37, NULL, 'Professional Fee/Honoraria', '₱2,258.25 x 0 speakers', NULL, 0.00),
(133, 5, 37, NULL, 'Token/s', '₱1,000.00 x 0 recipients', NULL, 0.00),
(134, 5, 37, NULL, 'Materials and Supplies', '₱0.00 x 1 item', NULL, 0.00),
(159, 7, 2, NULL, 'Breakfast', '₱220.00 x 60 pax x 3 days', 60, 39600.00),
(160, 7, 2, NULL, 'Function Room/Venue', '₱0.00 x 1 item', NULL, 0.00),
(161, 7, 2, NULL, 'Accommodation', '₱0.00 x 1 item', NULL, 0.00),
(162, 7, 2, NULL, 'Equipment Rental', '₱0.00 x 1 item', NULL, 0.00),
(163, 7, 2, NULL, 'Transportation', '₱0.00 x 1 item', NULL, 0.00),
(164, 7, 2, NULL, 'Professional Fee/Honoraria', '₱2,258.25 x 0 speakers', NULL, 0.00),
(165, 7, 2, NULL, 'Token/s', '₱1,000.00 x 0 recipients', NULL, 0.00),
(166, 7, 2, NULL, 'Materials and Supplies', '₱0.00 x 1 item', NULL, 0.00),
(167, 8, 2, NULL, 'Breakfast', '₱220.00 x 100 pax x 1 days', 100, 22000.00),
(168, 8, 2, NULL, 'Function Room/Venue', '₱0.00 x 1 item', NULL, 0.00),
(169, 8, 2, NULL, 'Accommodation', '₱0.00 x 1 item', NULL, 0.00),
(170, 8, 2, NULL, 'Equipment Rental', '₱0.00 x 1 item', NULL, 0.00),
(171, 8, 2, NULL, 'Transportation', '₱0.00 x 1 item', NULL, 0.00),
(172, 8, 2, NULL, 'Professional Fee/Honoraria', '₱2,258.25 x 0 speakers', NULL, 0.00),
(173, 8, 2, NULL, 'Token/s', '₱1,000.00 x 0 recipients', NULL, 0.00),
(174, 8, 2, NULL, 'Materials and Supplies', '₱0.00 x 1 item', NULL, 0.00),
(175, 8, 41, NULL, 'Breakfast', '₱350.00 x 100 pax x 1 days', 100, 35000.00),
(176, 8, 41, NULL, 'Function Room/Venue', '₱0.00 x 1 item', NULL, 0.00),
(177, 8, 41, NULL, 'Accommodation', '₱0.00 x 1 item', NULL, 0.00),
(178, 8, 41, NULL, 'Equipment Rental', '₱0.00 x 1 item', NULL, 0.00),
(179, 8, 41, NULL, 'Transportation', '₱0.00 x 1 item', NULL, 0.00),
(180, 8, 41, NULL, 'Professional Fee/Honoraria', '₱2,258.25 x 0 speakers', NULL, 0.00),
(181, 8, 41, NULL, 'Token/s', '₱1,000.00 x 0 recipients', NULL, 0.00),
(182, 8, 41, NULL, 'Materials and Supplies', '₱0.00 x 1 item', NULL, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `accomplishment_report`
--

CREATE TABLE `accomplishment_report` (
  `id` int(11) NOT NULL,
  `control_number` varchar(50) NOT NULL,
  `act_design_id` int(11) DEFAULT NULL,
  `activity_title` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `schedule_type` enum('continuous','staggered') DEFAULT 'continuous',
  `venue` varchar(255) NOT NULL,
  `attendees` int(11) NOT NULL,
  `male` int(11) NOT NULL,
  `female` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `attachment` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `remarks` text DEFAULT NULL,
  `venue_id` int(11) DEFAULT NULL,
  `assessment_date` date DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `is_viewed_by_admin` tinyint(1) DEFAULT 0,
  `is_archived` tinyint(1) DEFAULT 0,
  `archived_at` datetime DEFAULT NULL,
  `is_inside_bsu` tinyint(1) NOT NULL DEFAULT 1,
  `revision_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accomplishment_report`
--

INSERT INTO `accomplishment_report` (`id`, `control_number`, `act_design_id`, `activity_title`, `start_date`, `end_date`, `start_time`, `end_time`, `schedule_type`, `venue`, `attendees`, `male`, `female`, `rating`, `attachment`, `user_id`, `status`, `created_at`, `remarks`, `venue_id`, `assessment_date`, `deleted_at`, `deleted_by`, `is_viewed_by_admin`, `is_archived`, `archived_at`, `is_inside_bsu`, `revision_count`) VALUES
(1, '2026-09-001', 1, 'test 1', '2026-09-30', '2026-10-01', '13:57:00', '13:57:00', 'continuous', 'BSU Covered Court', 130, 60, 70, 5, '[\"1790406161_054ee5487b300bb50f12.pdf\",\"1790406161_cbaed58a1132b801bdc2.pdf\"]', 47, 'Verified', '2026-09-26 07:02:41', '', NULL, NULL, NULL, NULL, 0, 1, '2026-09-30 03:31:34', 1, 1),
(2, '2026-09-002', 3, 'hvchjvhjv', '2026-09-30', '2026-09-30', '04:50:00', '16:50:00', 'staggered', 'BSU Covered Court', 135, 65, 70, 5, '[\"1790499187_616e62499fac7925ad77.pdf\"]', 47, 'Verified', '2026-09-27 08:53:07', '', NULL, NULL, NULL, NULL, 0, 1, '2026-09-27 08:54:07', 1, 0),
(3, '2026-09-003', 6, 'd sdvs', '2026-10-14', '2026-10-14', '10:42:00', '14:43:00', 'staggered', 'BSU Covered Court', 80, 15, 65, 5, '[\"1790738449_5875354c4a9a0e4dee44.pdf\",\"1790738449_9bc05253e8add968d57d.pdf\"]', 2, 'Verified', '2026-09-30 03:20:49', '', NULL, NULL, NULL, NULL, 0, 1, '2026-09-30 03:21:31', 1, 0),
(4, '2026-10-001', 2, 'Test 2', '2026-10-12', '2026-10-21', '11:10:00', '18:11:00', 'continuous', 'BSU Covered Court', 79, 34, 45, 5, '[\"1791270884_3868cf069e48e66754b7.pdf\"]', 2, 'Verified', '2026-10-06 07:14:44', '', NULL, NULL, NULL, NULL, 0, 1, '2026-10-06 08:46:21', 1, 1),
(5, '2026-10-002', 5, 'dcsdvs', '2026-10-02', '2026-10-02', '05:23:00', '12:00:00', 'continuous', 'BSU Covered Court', 30, 10, 20, 5, '[\"1791273057_2d54144d1f8cc735df37.pdf\",\"1791273057_76de8fb235f98c318c9b.pdf\"]', 47, 'Pending', '2026-10-06 07:50:57', 'dvsdvsd', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, 1),
(6, '2026-10-003', 7, 'sdsds123', '2026-10-12', '2026-10-14', '10:53:00', '17:53:00', 'continuous', 'BSU Covered Court', 79, 34, 45, 5, '[\"1791349309_c45f4318a44edbb0116f.pdf\"]', 47, 'Verified', '2026-10-07 05:01:49', '', NULL, NULL, NULL, NULL, 0, 1, '2026-10-07 05:02:39', 1, 0),
(7, '2026-10-004', 9, 'test staff ad', '2026-10-13', '2026-10-15', '08:00:00', '20:00:00', 'continuous', 'BSU Covered Court', 37, 17, 20, 5, '[\"1791623669_f8d44baadbc6491709d3.pdf\",\"1791623669_61a717652c5e5f3a4495.pdf\"]', 47, 'Verified', '2026-10-10 09:14:29', '', NULL, NULL, NULL, NULL, 0, 1, '2026-10-10 09:17:58', 1, 1),
(8, '2026-10-005', 10, 'test twg ad', '2026-10-14', '2026-10-14', '08:00:00', '19:00:00', 'continuous', 'BSU Covered Court', 37, 17, 20, 4, '[\"1791623723_4c7965a5859935dfca43.pdf\",\"1791623723_42e3f52e89ab21eae3a5.pdf\"]', 2, 'Pending', '2026-10-10 09:15:23', '- Undefined Beneficiaries\n- Over-Ceiling Costs\n- Expense Misclassification', NULL, NULL, '2026-10-10 09:18:22', 1, 0, 0, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `accomplishment_schedules`
--

CREATE TABLE `accomplishment_schedules` (
  `id` int(11) UNSIGNED NOT NULL,
  `accomplishment_report_id` int(11) NOT NULL,
  `schedule_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `meals_and_snacks` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accomplishment_schedules`
--

INSERT INTO `accomplishment_schedules` (`id`, `accomplishment_report_id`, `schedule_date`, `start_time`, `end_time`, `meals_and_snacks`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 1, '2026-09-30', '13:57:00', '16:57:00', '{\"breakfast\":false,\"am_snack\":false,\"lunch\":false,\"pm_snack\":false,\"dinner\":false}', '2026-09-26 07:14:04', '2026-09-26 07:14:04', NULL),
(4, 1, '2026-10-01', '07:57:00', '13:57:00', '{\"breakfast\":false,\"am_snack\":false,\"lunch\":false,\"pm_snack\":false,\"dinner\":false}', '2026-09-26 07:14:04', '2026-09-26 07:14:04', NULL),
(5, 2, '2026-09-30', '04:50:00', '16:50:00', '{\"breakfast\":false,\"am_snack\":false,\"lunch\":false,\"pm_snack\":false,\"dinner\":false}', '2026-09-27 08:53:07', '2026-09-27 08:53:07', NULL),
(6, 3, '2026-10-14', '10:42:00', '14:43:00', '{\"breakfast\":false,\"am_snack\":false,\"lunch\":false,\"pm_snack\":false,\"dinner\":false}', '2026-09-30 03:20:49', '2026-09-30 03:20:49', NULL),
(16, 4, '2026-10-11', '11:10:00', '18:11:00', '{\"breakfast\":false,\"am_snack\":false,\"lunch\":false,\"pm_snack\":false,\"dinner\":false}', '2026-10-06 08:45:56', '2026-10-06 08:45:56', NULL),
(17, 4, '2026-10-12', '11:10:00', '18:11:00', '{\"breakfast\":false,\"am_snack\":false,\"lunch\":false,\"pm_snack\":false,\"dinner\":false}', '2026-10-06 08:45:56', '2026-10-06 08:45:56', NULL),
(18, 4, '2026-10-13', '11:10:00', '18:11:00', '{\"breakfast\":false,\"am_snack\":false,\"lunch\":false,\"pm_snack\":false,\"dinner\":false}', '2026-10-06 08:45:56', '2026-10-06 08:45:56', NULL),
(19, 4, '2026-10-14', '11:10:00', '18:11:00', '{\"breakfast\":false,\"am_snack\":false,\"lunch\":false,\"pm_snack\":false,\"dinner\":false}', '2026-10-06 08:45:56', '2026-10-06 08:45:56', NULL),
(20, 4, '2026-10-15', '11:10:00', '18:11:00', '{\"breakfast\":false,\"am_snack\":false,\"lunch\":false,\"pm_snack\":false,\"dinner\":false}', '2026-10-06 08:45:56', '2026-10-06 08:45:56', NULL),
(21, 4, '2026-10-18', '11:10:00', '18:11:00', '{\"breakfast\":false,\"am_snack\":false,\"lunch\":false,\"pm_snack\":false,\"dinner\":false}', '2026-10-06 08:45:56', '2026-10-06 08:45:56', NULL),
(22, 4, '2026-10-19', '11:10:00', '18:11:00', '{\"breakfast\":false,\"am_snack\":false,\"lunch\":false,\"pm_snack\":false,\"dinner\":false}', '2026-10-06 08:45:56', '2026-10-06 08:45:56', NULL),
(23, 4, '2026-10-20', '11:10:00', '18:11:00', '{\"breakfast\":false,\"am_snack\":false,\"lunch\":false,\"pm_snack\":false,\"dinner\":false}', '2026-10-06 08:45:56', '2026-10-06 08:45:56', NULL),
(24, 6, '2026-10-11', '10:53:00', '17:53:00', '{\"breakfast\":false,\"am_snack\":false,\"lunch\":false,\"pm_snack\":false,\"dinner\":false}', '2026-10-07 05:01:49', '2026-10-07 05:01:49', NULL),
(25, 6, '2026-10-12', '10:53:00', '17:53:00', '{\"breakfast\":false,\"am_snack\":false,\"lunch\":false,\"pm_snack\":false,\"dinner\":false}', '2026-10-07 05:01:49', '2026-10-07 05:01:49', NULL),
(26, 6, '2026-10-13', '10:53:00', '17:53:00', '{\"breakfast\":false,\"am_snack\":false,\"lunch\":false,\"pm_snack\":false,\"dinner\":false}', '2026-10-07 05:01:49', '2026-10-07 05:01:49', NULL),
(27, 5, '2026-10-02', '05:23:00', '12:00:00', '{\"breakfast\":false,\"am_snack\":false,\"lunch\":false,\"pm_snack\":false,\"dinner\":false}', '2026-10-09 13:36:48', '2026-10-09 13:36:48', NULL),
(32, 7, '2026-10-12', '08:00:00', '20:00:00', '{\"breakfast\":false,\"am_snack\":false,\"lunch\":false,\"pm_snack\":false,\"dinner\":false}', '2026-10-10 09:17:15', '2026-10-10 09:17:15', NULL),
(33, 7, '2026-10-13', '08:00:00', '20:00:00', '{\"breakfast\":false,\"am_snack\":false,\"lunch\":false,\"pm_snack\":false,\"dinner\":false}', '2026-10-10 09:17:15', '2026-10-10 09:17:15', NULL),
(34, 7, '2026-10-14', '08:00:00', '20:00:00', '{\"breakfast\":false,\"am_snack\":false,\"lunch\":false,\"pm_snack\":false,\"dinner\":false}', '2026-10-10 09:17:15', '2026-10-10 09:17:15', NULL),
(35, 8, '2026-10-14', '08:00:00', '19:00:00', '{\"breakfast\":false,\"am_snack\":false,\"lunch\":false,\"pm_snack\":false,\"dinner\":false}', '2026-10-10 09:17:29', '2026-10-10 09:17:29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `activity_budget_items`
--

CREATE TABLE `activity_budget_items` (
  `id` int(11) NOT NULL,
  `act_design_id` int(11) NOT NULL,
  `venue_id` int(11) UNSIGNED DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `item_name` varchar(100) NOT NULL,
  `sub_item` varchar(100) DEFAULT NULL,
  `pax` int(11) DEFAULT NULL,
  `unit_cost` decimal(15,2) DEFAULT 0.00,
  `multipliers` text DEFAULT NULL,
  `formula` varchar(255) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_budget_items`
--

INSERT INTO `activity_budget_items` (`id`, `act_design_id`, `venue_id`, `category_id`, `item_name`, `sub_item`, `pax`, `unit_cost`, `multipliers`, `formula`, `amount`) VALUES
(18, 1, 2, NULL, 'Breakfast', '₱220.00 × 10 pax', 10, 220.00, '[{\"label\":\"pax\",\"value\":10}]', '₱220.00 × 10 pax', 2200.00),
(19, 1, 2, NULL, 'PM Snack', '₱80.00 × 1500 pax', 1500, 80.00, '[{\"label\":\"pax\",\"value\":1500}]', '₱80.00 × 1500 pax', 120000.00),
(20, 1, 2, NULL, 'Function Room/Venue', '₱1,700.00', NULL, 1700.00, '[]', '₱1,700.00', 1700.00),
(21, 1, 2, NULL, 'Accommodation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(22, 1, 2, NULL, 'Equipment Rental', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(23, 1, 2, NULL, 'Transportation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(24, 1, 2, NULL, 'Professional Fee/Honoraria', '₱2,258.25 × 0 speakers', NULL, 2258.25, '[{\"label\":\"speakers\",\"value\":0}]', '₱2,258.25 × 0 speakers', 0.00),
(25, 1, 2, NULL, 'Token/s', '₱1,000.00 × 0 recipients', NULL, 1000.00, '[{\"label\":\"recipients\",\"value\":0}]', '₱1,000.00 × 0 recipients', 0.00),
(26, 1, 2, NULL, 'Materials and Supplies', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(27, 1, 1, NULL, 'Breakfast', '₱220.00 × 10 pax', 10, 220.00, '[{\"label\":\"pax\",\"value\":10}]', '₱220.00 × 10 pax', 2200.00),
(28, 1, 1, NULL, 'Function Room/Venue', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(29, 1, 1, NULL, 'Accommodation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(30, 1, 1, NULL, 'Equipment Rental', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(31, 1, 1, NULL, 'Transportation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(32, 1, 1, NULL, 'Professional Fee/Honoraria', '₱2,258.25 × 0 speakers', NULL, 2258.25, '[{\"label\":\"speakers\",\"value\":0}]', '₱2,258.25 × 0 speakers', 0.00),
(33, 1, 1, NULL, 'Token/s', '₱1,000.00 × 0 recipients', NULL, 1000.00, '[{\"label\":\"recipients\",\"value\":0}]', '₱1,000.00 × 0 recipients', 0.00),
(34, 1, 1, NULL, 'Materials and Supplies', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(51, 3, 2, NULL, 'Breakfast', '₱220.00 × 10 pax × 1 days', 10, 220.00, '[{\"label\":\"pax\",\"value\":10},{\"label\":\"days\",\"value\":1}]', '₱220.00 × 10 pax × 1 days', 2200.00),
(52, 3, 2, NULL, 'Function Room/Venue', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(53, 3, 2, NULL, 'Accommodation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(54, 3, 2, NULL, 'Equipment Rental', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(55, 3, 2, NULL, 'Transportation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(56, 3, 2, NULL, 'Professional Fee/Honoraria', '₱2,258.25 × 0 speakers', NULL, 2258.25, '[{\"label\":\"speakers\",\"value\":0}]', '₱2,258.25 × 0 speakers', 0.00),
(57, 3, 2, NULL, 'Token/s', '₱1,000.00 × 0 recipients', NULL, 1000.00, '[{\"label\":\"recipients\",\"value\":0}]', '₱1,000.00 × 0 recipients', 0.00),
(58, 3, 2, NULL, 'Materials and Supplies', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(74, 5, 2, NULL, 'Breakfast', '₱220.00 × 10 pax × 1 days', 10, 220.00, '[{\"label\":\"pax\",\"value\":10},{\"label\":\"days\",\"value\":1}]', '₱220.00 × 10 pax × 1 days', 2200.00),
(75, 5, 2, NULL, 'PM Snack', '₱85.00 × 10 pax × 1 days', 10, 85.00, '[{\"label\":\"pax\",\"value\":10},{\"label\":\"days\",\"value\":1}]', '₱85.00 × 10 pax × 1 days', 850.00),
(76, 5, 2, NULL, 'Function Room/Venue', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(77, 5, 2, NULL, 'Accommodation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(78, 5, 2, NULL, 'Equipment Rental', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(79, 5, 2, NULL, 'Transportation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(80, 5, 2, NULL, 'Professional Fee/Honoraria', '₱2,258.25 × 0 speakers', NULL, 2258.25, '[{\"label\":\"speakers\",\"value\":0}]', '₱2,258.25 × 0 speakers', 0.00),
(81, 5, 2, NULL, 'Token/s', '₱1,000.00 × 0 recipients', NULL, 1000.00, '[{\"label\":\"recipients\",\"value\":0}]', '₱1,000.00 × 0 recipients', 0.00),
(82, 5, 2, NULL, 'Materials and Supplies', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(83, 5, 37, NULL, 'Breakfast', '₱350.00 × 10 pax × 1 days', 10, 350.00, '[{\"label\":\"pax\",\"value\":10},{\"label\":\"days\",\"value\":1}]', '₱350.00 × 10 pax × 1 days', 3500.00),
(84, 5, 37, NULL, 'PM Snack', '₱150.00 × 10 pax × 1 days', 10, 150.00, '[{\"label\":\"pax\",\"value\":10},{\"label\":\"days\",\"value\":1}]', '₱150.00 × 10 pax × 1 days', 1500.00),
(85, 5, 37, NULL, 'Function Room/Venue', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(86, 5, 37, NULL, 'Accommodation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(87, 5, 37, NULL, 'Equipment Rental', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(88, 5, 37, NULL, 'Transportation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(89, 5, 37, NULL, 'Professional Fee/Honoraria', '₱2,258.25 × 0 speakers', NULL, 2258.25, '[{\"label\":\"speakers\",\"value\":0}]', '₱2,258.25 × 0 speakers', 0.00),
(90, 5, 37, NULL, 'Token/s', '₱1,000.00 × 0 recipients', NULL, 1000.00, '[{\"label\":\"recipients\",\"value\":0}]', '₱1,000.00 × 0 recipients', 0.00),
(91, 5, 37, NULL, 'Materials and Supplies', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(108, 6, 2, NULL, 'Breakfast', '₱220.00 × 16555 pax', 16555, 220.00, '[{\"label\":\"pax\",\"value\":16555}]', '₱220.00 × 16555 pax', 3642100.00),
(109, 6, 2, NULL, 'Function Room/Venue', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(110, 6, 2, NULL, 'Accommodation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(111, 6, 2, NULL, 'Equipment Rental', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(112, 6, 2, NULL, 'Transportation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(113, 6, 2, NULL, 'Professional Fee/Honoraria', '₱2,258.25 × 0 speakers', NULL, 2258.25, '[{\"label\":\"speakers\",\"value\":0}]', '₱2,258.25 × 0 speakers', 0.00),
(114, 6, 2, NULL, 'Token/s', '₱1,000.00 × 0 recipients', NULL, 1000.00, '[{\"label\":\"recipients\",\"value\":0}]', '₱1,000.00 × 0 recipients', 0.00),
(115, 6, 2, NULL, 'Materials and Supplies', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(116, 6, 37, NULL, 'Breakfast', '₱350.00 × 1655 pax', 1655, 350.00, '[{\"label\":\"pax\",\"value\":1655}]', '₱350.00 × 1655 pax', 579250.00),
(117, 6, 37, NULL, 'Function Room/Venue', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(118, 6, 37, NULL, 'Accommodation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(119, 6, 37, NULL, 'Equipment Rental', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(120, 6, 37, NULL, 'Transportation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(121, 6, 37, NULL, 'Professional Fee/Honoraria', '₱2,258.25 × 0 speakers', NULL, 2258.25, '[{\"label\":\"speakers\",\"value\":0}]', '₱2,258.25 × 0 speakers', 0.00),
(122, 6, 37, NULL, 'Token/s', '₱1,000.00 × 0 recipients', NULL, 1000.00, '[{\"label\":\"recipients\",\"value\":0}]', '₱1,000.00 × 0 recipients', 0.00),
(123, 6, 37, NULL, 'Materials and Supplies', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(124, 2, 2, NULL, 'Lunch', '₱220.00 × 60 pax', 60, 220.00, '[{\"label\":\"pax\",\"value\":60}]', '₱220.00 × 60 pax', 13200.00),
(125, 2, 2, NULL, 'Function Room/Venue', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(126, 2, 2, NULL, 'Accommodation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(127, 2, 2, NULL, 'Equipment Rental', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(128, 2, 2, NULL, 'Transportation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(129, 2, 2, NULL, 'Professional Fee/Honoraria', '₱2,258.25 × 0 speakers', NULL, 2258.25, '[{\"label\":\"speakers\",\"value\":0}]', '₱2,258.25 × 0 speakers', 0.00),
(130, 2, 2, NULL, 'Token/s', '₱1,000.00 × 0 recipients', NULL, 1000.00, '[{\"label\":\"recipients\",\"value\":0}]', '₱1,000.00 × 0 recipients', 0.00),
(131, 2, 2, NULL, 'Materials and Supplies', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(132, 2, 1, NULL, 'Breakfast', '₱220.00 × 60 pax', 60, 220.00, '[{\"label\":\"pax\",\"value\":60}]', '₱220.00 × 60 pax', 13200.00),
(133, 2, 1, NULL, 'Function Room/Venue', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(134, 2, 1, NULL, 'Accommodation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(135, 2, 1, NULL, 'Equipment Rental', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(136, 2, 1, NULL, 'Transportation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(137, 2, 1, NULL, 'Professional Fee/Honoraria', '₱2,258.25 × 0 speakers', NULL, 2258.25, '[{\"label\":\"speakers\",\"value\":0}]', '₱2,258.25 × 0 speakers', 0.00),
(138, 2, 1, NULL, 'Token/s', '₱1,000.00 × 0 recipients', NULL, 1000.00, '[{\"label\":\"recipients\",\"value\":0}]', '₱1,000.00 × 0 recipients', 0.00),
(139, 2, 1, NULL, 'Materials and Supplies', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(140, 7, 2, NULL, 'Breakfast', '₱220.00 × 10 pax × 3 days', 10, 220.00, '[{\"label\":\"pax\",\"value\":10},{\"label\":\"days\",\"value\":3}]', '₱220.00 × 10 pax × 3 days', 6600.00),
(141, 7, 2, NULL, 'Function Room/Venue', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(142, 7, 2, NULL, 'Accommodation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(143, 7, 2, NULL, 'Equipment Rental', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(144, 7, 2, NULL, 'Transportation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(145, 7, 2, NULL, 'Professional Fee/Honoraria', '₱2,258.25 × 0 speakers', NULL, 2258.25, '[{\"label\":\"speakers\",\"value\":0}]', '₱2,258.25 × 0 speakers', 0.00),
(146, 7, 2, NULL, 'Token/s', '₱1,000.00 × 0 recipients', NULL, 1000.00, '[{\"label\":\"recipients\",\"value\":0}]', '₱1,000.00 × 0 recipients', 0.00),
(147, 7, 2, NULL, 'Materials and Supplies', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(163, 8, 1, NULL, 'Breakfast', '₱220.00 × 60 pax × 2 days', 60, 220.00, '[{\"label\":\"pax\",\"value\":60},{\"label\":\"days\",\"value\":2}]', '₱220.00 × 60 pax × 2 days', 26400.00),
(164, 8, 1, NULL, 'Function Room/Venue', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(165, 8, 1, NULL, 'Accommodation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(166, 8, 1, NULL, 'Equipment Rental', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(167, 8, 1, NULL, 'Transportation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(168, 8, 1, NULL, 'Professional Fee/Honoraria', '₱2,258.25 × 0 speakers', NULL, 2258.25, '[{\"label\":\"speakers\",\"value\":0}]', '₱2,258.25 × 0 speakers', 0.00),
(169, 8, 1, NULL, 'Token/s', '₱1,000.00 × 0 recipients', NULL, 1000.00, '[{\"label\":\"recipients\",\"value\":0}]', '₱1,000.00 × 0 recipients', 0.00),
(170, 8, 1, NULL, 'Materials and Supplies', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(203, 10, 2, NULL, 'Breakfast', '₱220.00 × 100 pax × 1 days', 100, 220.00, '[{\"label\":\"pax\",\"value\":100},{\"label\":\"days\",\"value\":1}]', '₱220.00 × 100 pax × 1 days', 22000.00),
(204, 10, 2, NULL, 'Function Room/Venue', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(205, 10, 2, NULL, 'Accommodation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(206, 10, 2, NULL, 'Equipment Rental', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(207, 10, 2, NULL, 'Transportation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(208, 10, 2, NULL, 'Professional Fee/Honoraria', '₱2,258.25 × 0 speakers', NULL, 2258.25, '[{\"label\":\"speakers\",\"value\":0}]', '₱2,258.25 × 0 speakers', 0.00),
(209, 10, 2, NULL, 'Token/s', '₱1,000.00 × 0 recipients', NULL, 1000.00, '[{\"label\":\"recipients\",\"value\":0}]', '₱1,000.00 × 0 recipients', 0.00),
(210, 10, 2, NULL, 'Materials and Supplies', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(211, 10, 41, NULL, 'Breakfast', '₱350.00 × 100 pax × 1 days', 100, 350.00, '[{\"label\":\"pax\",\"value\":100},{\"label\":\"days\",\"value\":1}]', '₱350.00 × 100 pax × 1 days', 35000.00),
(212, 10, 41, NULL, 'Function Room/Venue', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(213, 10, 41, NULL, 'Accommodation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(214, 10, 41, NULL, 'Equipment Rental', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(215, 10, 41, NULL, 'Transportation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(216, 10, 41, NULL, 'Professional Fee/Honoraria', '₱2,258.25 × 0 speakers', NULL, 2258.25, '[{\"label\":\"speakers\",\"value\":0}]', '₱2,258.25 × 0 speakers', 0.00),
(217, 10, 41, NULL, 'Token/s', '₱1,000.00 × 0 recipients', NULL, 1000.00, '[{\"label\":\"recipients\",\"value\":0}]', '₱1,000.00 × 0 recipients', 0.00),
(218, 10, 41, NULL, 'Materials and Supplies', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(219, 9, 2, NULL, 'Breakfast', '₱220.00 × 60 pax × 3 days', 60, 220.00, '[{\"label\":\"pax\",\"value\":60},{\"label\":\"days\",\"value\":3}]', '₱220.00 × 60 pax × 3 days', 39600.00),
(220, 9, 2, NULL, 'Function Room/Venue', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(221, 9, 2, NULL, 'Accommodation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(222, 9, 2, NULL, 'Equipment Rental', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(223, 9, 2, NULL, 'Transportation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(224, 9, 2, NULL, 'Professional Fee/Honoraria', '₱2,258.25 × 0 speakers', NULL, 2258.25, '[{\"label\":\"speakers\",\"value\":0}]', '₱2,258.25 × 0 speakers', 0.00),
(225, 9, 2, NULL, 'Token/s', '₱1,000.00 × 0 recipients', NULL, 1000.00, '[{\"label\":\"recipients\",\"value\":0}]', '₱1,000.00 × 0 recipients', 0.00),
(226, 9, 2, NULL, 'Materials and Supplies', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(227, 4, 2, NULL, 'Breakfast', '₱220.00 × 60 pax', 60, 220.00, '[{\"label\":\"pax\",\"value\":60}]', '₱220.00 × 60 pax', 13200.00),
(228, 4, 2, NULL, 'Function Room/Venue', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(229, 4, 2, NULL, 'Accommodation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(230, 4, 2, NULL, 'Equipment Rental', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(231, 4, 2, NULL, 'Transportation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(232, 4, 2, NULL, 'Professional Fee/Honoraria', '₱2,258.25 × 0 speakers', NULL, 2258.25, '[{\"label\":\"speakers\",\"value\":0}]', '₱2,258.25 × 0 speakers', 0.00),
(233, 4, 2, NULL, 'Token/s', '₱1,000.00 × 0 recipients', NULL, 1000.00, '[{\"label\":\"recipients\",\"value\":0}]', '₱1,000.00 × 0 recipients', 0.00),
(234, 4, 2, NULL, 'Materials and Supplies', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(235, 4, 37, NULL, 'Function Room/Venue', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(236, 4, 37, NULL, 'Accommodation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(237, 4, 37, NULL, 'Equipment Rental', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(238, 4, 37, NULL, 'Transportation', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00),
(239, 4, 37, NULL, 'Professional Fee/Honoraria', '₱2,258.25 × 3 speakers', NULL, 2258.25, '[{\"label\":\"speakers\",\"value\":3}]', '₱2,258.25 × 3 speakers', 6774.75),
(240, 4, 37, NULL, 'Token/s', '₱1,000.00 × 0 recipients', NULL, 1000.00, '[{\"label\":\"recipients\",\"value\":0}]', '₱1,000.00 × 0 recipients', 0.00),
(241, 4, 37, NULL, 'Materials and Supplies', '₱0.00', NULL, 0.00, '[]', '₱0.00', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `activity_classifications`
--

CREATE TABLE `activity_classifications` (
  `id` int(11) NOT NULL,
  `classification_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_classifications`
--

INSERT INTO `activity_classifications` (`id`, `classification_name`) VALUES
(1, 'Client-focused activity'),
(2, 'Organization-focused activity'),
(3, 'Attributed program');

-- --------------------------------------------------------

--
-- Table structure for table `activity_design`
--

CREATE TABLE `activity_design` (
  `act_design_id` int(11) NOT NULL,
  `activity_title` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `attachment` varchar(500) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `gpb_id` int(11) DEFAULT NULL,
  `venue` varchar(255) DEFAULT NULL,
  `target_participants` int(11) DEFAULT NULL,
  `proposed_budget` decimal(15,2) DEFAULT NULL,
  `form_type` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `accomplishment_deadline` date DEFAULT NULL,
  `venue_id` int(11) DEFAULT NULL,
  `assessment_date` date DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `gad_mandate_id` int(11) DEFAULT NULL,
  `gender_issue_id` int(11) DEFAULT NULL,
  `classification_id` int(11) DEFAULT NULL,
  `is_viewed_by_admin` tinyint(1) DEFAULT 0,
  `is_archived` tinyint(1) DEFAULT 0,
  `control_number` varchar(255) DEFAULT NULL,
  `modification_request_status` enum('none','pending','approved','rejected') NOT NULL DEFAULT 'none',
  `modification_remarks` text DEFAULT NULL,
  `is_modified` tinyint(1) NOT NULL DEFAULT 0,
  `archived_at` datetime DEFAULT NULL,
  `is_inside_bsu` tinyint(1) NOT NULL DEFAULT 1,
  `schedule_type` varchar(50) DEFAULT NULL,
  `revision_count` int(11) NOT NULL DEFAULT 0,
  `modification_count` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_design`
--

INSERT INTO `activity_design` (`act_design_id`, `activity_title`, `start_date`, `end_date`, `start_time`, `end_time`, `status`, `attachment`, `user_id`, `gpb_id`, `venue`, `target_participants`, `proposed_budget`, `form_type`, `remarks`, `accomplishment_deadline`, `venue_id`, `assessment_date`, `deleted_at`, `deleted_by`, `gad_mandate_id`, `gender_issue_id`, `classification_id`, `is_viewed_by_admin`, `is_archived`, `control_number`, `modification_request_status`, `modification_remarks`, `is_modified`, `archived_at`, `is_inside_bsu`, `schedule_type`, `revision_count`, `modification_count`, `created_at`, `updated_at`) VALUES
(1, 'test 1', '2026-09-30', '2026-10-01', '13:57:00', '13:57:00', 'Approved', '1790402469_8a6754de3f7602898e0a.pdf', 47, NULL, NULL, 1510, 126100.00, 1, '', '2026-10-22', 2, '2026-09-26', '2026-10-10 09:03:56', 1, 1, 1, 1, 0, 1, '2026-09-001', 'none', NULL, 0, '2026-09-26 06:13:31', 1, 'staggered', 1, 0, '2026-09-30 10:40:51', '2026-10-10 17:03:56'),
(2, 'Test 2', '2026-10-12', '2026-10-21', '11:10:00', '18:11:00', 'Approved', '1790403091_55e1266d935552d6d2d0.pdf', 2, NULL, NULL, 120, 26400.00, 1, '', '2026-11-11', 2, '2026-10-06', NULL, NULL, 1, 1, 1, 0, 1, '2026-10-001', 'none', NULL, 0, '2026-10-06 07:13:08', 1, 'continuous', 1, 0, '2026-09-30 10:40:51', '2026-10-06 16:45:56'),
(3, 'hvchjvhjv', '2026-09-30', '2026-09-30', '04:50:00', '16:50:00', 'Approved', '1790499031_916cdb0396b3dfc12ada.pdf', 47, NULL, NULL, 10, 2200.00, 2, '', '2026-10-21', 2, '2026-09-27', '2026-10-10 09:04:38', 47, 1, 1, 1, 0, 1, '2026-09-002', 'none', NULL, 0, '2026-09-27 08:51:22', 1, 'staggered', 0, 0, '2026-09-30 10:40:51', '2026-10-12 18:13:14'),
(4, 'Test 1', '2026-10-20', '2026-10-23', '05:21:00', '19:21:00', 'Pending', '1790673745_8336f9fea6372ea51c31.pdf', 2, NULL, NULL, 60, 19974.75, 1, '- Undefined Beneficiaries\n- Over-Ceiling Costs\n- Expense Misclassification\n\nAdditional Notes:\nfdfdf', '2026-10-14', 2, NULL, NULL, NULL, 1, 1, 1, 0, 0, NULL, 'none', NULL, 0, NULL, 1, 'continuous', 2, 0, '2026-09-30 10:40:51', '2026-10-10 17:21:09'),
(5, 'dcsdvs', '2026-10-02', '2026-10-02', '05:23:00', '12:00:00', 'Approved', '1790673901_95f52d7442fd9cbbf347.pdf', 47, NULL, NULL, 20, 8050.00, 1, '', '2026-10-23', 2, '2026-10-06', NULL, NULL, 1, 1, 1, 0, 1, '2026-10-002', 'none', NULL, 0, '2026-10-06 07:49:39', 1, 'staggered', 1, 0, '2026-09-30 10:40:51', '2026-10-09 21:36:48'),
(6, 'd sdvs', '2026-10-14', '2026-10-14', '10:42:00', '14:43:00', 'Approved', '1790736224_1642b8e18bcae41aed34.pdf', 2, NULL, NULL, 18210, 4221350.00, 1, '', '2026-11-04', 2, '2026-09-30', NULL, NULL, 1, 1, 1, 0, 1, '2026-09-003', 'approved', 'dddd', 0, '2026-09-30 03:19:58', 2, 'staggered', 1, 1, '2026-09-30 10:43:45', '2026-10-12 18:13:14'),
(7, 'sdsds', '2026-10-12', '2026-10-14', '10:53:00', '17:53:00', 'Approved', '1791348840_dee259bdf66f21c3845d.pdf', 47, NULL, NULL, 10, 6600.00, 1, '', '2026-11-04', 2, '2026-10-07', NULL, NULL, 1, 1, 1, 0, 1, '2026-10-003', 'none', NULL, 0, '2026-10-07 04:54:45', 1, 'continuous', 0, 0, '2026-10-07 12:54:00', '2026-10-12 18:13:14'),
(8, 'sfvsfvsd', '2026-10-14', '2026-10-15', '08:16:00', '15:16:00', 'Pending', '1791548208_0c8a9fe6461a74c8265d.pdf', 57, NULL, NULL, 60, 26400.00, 1, NULL, NULL, 1, NULL, NULL, NULL, 1, 1, 1, 1, 0, NULL, 'none', NULL, 0, NULL, 1, 'continuous', 0, 0, '2026-10-09 20:16:48', '2026-10-10 13:57:49'),
(9, 'test staff ad', '2026-10-14', '2026-10-16', '08:00:00', '20:00:00', 'Approved', '1791623290_aefc158c68f22caa30cc.pdf', 47, NULL, NULL, 60, 39600.00, 1, '', '2026-11-06', 2, '2026-10-10', NULL, NULL, 1, 1, 1, 0, 1, '2026-10-004', 'none', NULL, 1, '2026-10-10 09:12:59', 2, 'continuous', 1, 0, '2026-10-10 17:08:10', '2026-10-10 17:19:07'),
(10, 'test twg ad', '2026-10-14', '2026-10-14', '08:00:00', '19:00:00', 'Approved', '1791623376_c505f9ff683d59f2766b.pdf', 2, NULL, NULL, 200, 57000.00, 1, '', '2026-11-04', 2, '2026-10-10', NULL, NULL, 1, 1, 1, 0, 1, '2026-10-005', 'approved', 'sdvsdv', 0, '2026-10-10 09:13:27', 2, 'staggered', 1, 1, '2026-10-10 17:09:36', '2026-10-10 17:20:06');

-- --------------------------------------------------------

--
-- Table structure for table `activity_design_issues`
--

CREATE TABLE `activity_design_issues` (
  `id` int(11) NOT NULL,
  `act_design_id` int(11) NOT NULL,
  `issue_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_design_issues`
--

INSERT INTO `activity_design_issues` (`id`, `act_design_id`, `issue_id`) VALUES
(5, 1, 1),
(16, 2, 1),
(7, 3, 1),
(56, 4, 1),
(57, 4, 2),
(58, 4, 3),
(59, 4, 4),
(28, 5, 1),
(12, 6, 1),
(24, 7, 1),
(25, 7, 2),
(26, 7, 3),
(27, 8, 1),
(53, 9, 1),
(54, 9, 2),
(55, 9, 3),
(50, 10, 1),
(51, 10, 2),
(52, 10, 3);

-- --------------------------------------------------------

--
-- Table structure for table `activity_design_mandates`
--

CREATE TABLE `activity_design_mandates` (
  `id` int(11) NOT NULL,
  `act_design_id` int(11) NOT NULL,
  `mandate_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_design_mandates`
--

INSERT INTO `activity_design_mandates` (`id`, `act_design_id`, `mandate_id`) VALUES
(9, 1, 1),
(10, 1, 2),
(31, 2, 1),
(32, 2, 2),
(13, 3, 1),
(14, 3, 2),
(89, 4, 1),
(90, 4, 2),
(91, 4, 3),
(92, 4, 4),
(93, 4, 5),
(47, 5, 1),
(48, 5, 2),
(23, 6, 1),
(24, 6, 2),
(42, 7, 1),
(43, 7, 2),
(44, 7, 3),
(45, 7, 4),
(46, 8, 1),
(85, 9, 1),
(86, 9, 2),
(87, 9, 3),
(88, 9, 4),
(80, 10, 1),
(81, 10, 2),
(82, 10, 3),
(83, 10, 4),
(84, 10, 5);

-- --------------------------------------------------------

--
-- Table structure for table `activity_design_venues`
--

CREATE TABLE `activity_design_venues` (
  `id` int(11) UNSIGNED NOT NULL,
  `act_design_id` int(11) NOT NULL,
  `venue_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_design_venues`
--

INSERT INTO `activity_design_venues` (`id`, `act_design_id`, `venue_id`) VALUES
(3, 1, 2),
(4, 1, 1),
(7, 3, 2),
(10, 5, 2),
(11, 5, 37),
(14, 6, 2),
(15, 6, 37),
(16, 2, 2),
(17, 2, 1),
(18, 7, 2),
(21, 8, 1),
(26, 10, 2),
(27, 10, 41),
(28, 9, 2),
(29, 4, 2),
(30, 4, 37);

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `created_at`) VALUES
(1, 47, 'Login', 'GAD Staff logged in', '2026-09-25 06:54:01'),
(2, 1, 'Login', 'Jude Tayaben logged in', '2026-09-25 11:35:43'),
(3, 47, 'Login', 'GAD Staff logged in', '2026-09-25 11:35:50'),
(4, 2, 'Login', 'CA TWG logged in', '2026-09-25 11:36:00'),
(5, 1, 'Login', 'Jude Tayaben logged in', '2026-09-26 05:47:28'),
(6, 2, 'Login', 'CA TWG logged in', '2026-09-26 05:47:47'),
(7, 47, 'Login', 'GAD Staff logged in', '2026-09-26 05:47:54'),
(8, 47, 'Submit Document', 'submitted Activity Design: test 1', '2026-09-26 06:01:09'),
(9, 1, 'Update Status', 'requested revision for Activity Design: test 1', '2026-09-26 06:06:27'),
(10, 47, 'Update Document', 'resubmitted Activity Design: test 1', '2026-09-26 06:08:00'),
(11, 2, 'Submit Document', 'submitted Activity Design: Test 2', '2026-09-26 06:11:31'),
(12, 1, 'Update Status', 'requested revision for Activity Design: Test 2', '2026-09-26 06:12:28'),
(13, 1, 'Approve Document', 'approved Activity Design: test 1', '2026-09-26 06:13:31'),
(14, 47, 'Submit Document', 'submitted Accomplishment Report: test 1', '2026-09-26 07:02:41'),
(15, 1, 'Update Status', 'requested revision for Accomplishment Report: test 1', '2026-09-26 07:10:52'),
(16, 47, 'Update Document', 'resubmitted Accomplishment Report: test 1', '2026-09-26 07:14:04'),
(17, 1, 'Login', 'Jude Tayaben logged in', '2026-09-27 07:41:51'),
(18, 47, 'Login', 'GAD Staff logged in', '2026-09-27 07:41:57'),
(19, 2, 'Login', 'CA TWG logged in', '2026-09-27 07:42:06'),
(20, 47, 'Login', 'GAD Staff logged in', '2026-09-27 08:31:01'),
(21, 47, 'Submit Document', 'submitted Activity Design: hvchjvhjv', '2026-09-27 08:50:31'),
(22, 1, 'Approve Document', 'approved Activity Design: hvchjvhjv', '2026-09-27 08:51:22'),
(23, 47, 'Submit Document', 'submitted Accomplishment Report: hvchjvhjv', '2026-09-27 08:53:07'),
(24, 1, 'Approve Document', 'verified Accomplishment Report: hvchjvhjv', '2026-09-27 08:54:07'),
(25, 2, 'Login', 'CA TWG logged in', '2026-09-27 12:11:25'),
(26, 47, 'Login', 'GAD Staff logged in', '2026-09-27 12:11:29'),
(27, 1, 'Login', 'Jude Tayaben logged in', '2026-09-27 12:11:31'),
(28, 47, 'Login', 'GAD Staff logged in', '2026-09-27 12:33:41'),
(29, 47, 'Login', 'GAD Staff logged in', '2026-09-28 06:41:06'),
(30, 1, 'Login', 'Jude Tayaben logged in', '2026-09-28 06:41:10'),
(31, 2, 'Login', 'CA TWG logged in', '2026-09-28 06:41:35'),
(32, 1, 'Login', 'Jude Tayaben logged in', '2026-09-29 09:20:09'),
(33, 47, 'Login', 'GAD Staff logged in', '2026-09-29 09:20:13'),
(34, 2, 'Login', 'CA TWG logged in', '2026-09-29 09:20:22'),
(35, 2, 'Submit Document', 'submitted Activity Design: Test 1', '2026-09-29 09:22:25'),
(36, 47, 'Submit Document', 'submitted Activity Design: dcsdvs', '2026-09-29 09:25:01'),
(37, 1, 'Update Status', 'requested revision for Activity Design: Test 1', '2026-09-29 09:28:54'),
(38, 1, 'Update Status', 'requested revision for Activity Design: dcsdvs', '2026-09-29 09:29:11'),
(39, 1, 'Login', 'Jude Tayaben logged in', '2026-09-29 13:55:10'),
(40, 47, 'Login', 'GAD Staff logged in', '2026-09-29 13:55:18'),
(41, 2, 'Login', 'CA TWG logged in', '2026-09-29 13:55:29'),
(42, 1, 'Login', 'Jude Tayaben logged in', '2026-09-30 02:11:29'),
(43, 47, 'Login', 'GAD Staff logged in', '2026-09-30 02:11:39'),
(44, 2, 'Login', 'CA TWG logged in', '2026-09-30 02:11:51'),
(45, 2, 'Submit Document', 'submitted Activity Design: d sdvs', '2026-09-30 02:43:45'),
(46, 1, 'Update Status', 'requested revision for Activity Design: d sdvs', '2026-09-30 02:44:44'),
(47, 2, 'Update Document', 'resubmitted Activity Design: d sdvs', '2026-09-30 03:19:27'),
(48, 1, 'Approve Document', 'approved Activity Design: d sdvs', '2026-09-30 03:19:58'),
(49, 2, 'Submit Document', 'submitted Accomplishment Report: d sdvs', '2026-09-30 03:20:49'),
(50, 1, 'Approve Document', 'verified Accomplishment Report: d sdvs', '2026-09-30 03:21:31'),
(51, 1, 'Approve Document', 'verified Accomplishment Report: test 1', '2026-09-30 03:31:34'),
(52, 1, 'Login', 'Jude Tayaben logged in', '2026-10-04 13:00:02'),
(53, 47, 'Login', 'GAD Staff logged in', '2026-10-04 13:10:06'),
(54, 2, 'Login', 'CA TWG logged in', '2026-10-04 13:10:20'),
(55, 2, 'Login', 'CA TWG logged in', '2026-10-05 01:20:24'),
(56, 47, 'Login', 'GAD Staff logged in', '2026-10-05 01:20:27'),
(57, 1, 'Login', 'Jude Tayaben logged in', '2026-10-05 01:20:29'),
(58, 1, 'Login', 'Jude Tayaben logged in', '2026-10-05 03:19:45'),
(59, 2, 'Login', 'CA TWG logged in', '2026-10-05 09:16:26'),
(60, 47, 'Login', 'GAD Staff logged in', '2026-10-05 09:16:38'),
(61, 1, 'Login', 'Jude Tayaben logged in', '2026-10-05 09:16:42'),
(62, 2, 'Login', 'CA TWG logged in', '2026-10-05 11:16:49'),
(63, 2, 'Login', 'CA TWG logged in', '2026-10-05 22:56:49'),
(64, 47, 'Login', 'GAD Staff logged in', '2026-10-05 22:57:01'),
(65, 1, 'Login', 'Jude Tayaben logged in', '2026-10-05 22:57:06'),
(66, 2, 'Update Document', 'resubmitted Activity Design: Test 2', '2026-10-05 23:46:44'),
(67, 2, 'Request Modification', 'requested modification for Activity Design: d sdvs', '2026-10-06 06:49:34'),
(68, 47, 'Approve Modification', 'approved modification request for Activity Design: d sdvs', '2026-10-06 06:49:56'),
(69, 1, 'Approve Document', 'approved Activity Design: Test 2', '2026-10-06 07:13:08'),
(70, 2, 'Submit Document', 'submitted Accomplishment Report: Test 2', '2026-10-06 07:14:44'),
(71, 1, 'Approve Document', 'approved Activity Design: dcsdvs', '2026-10-06 07:49:39'),
(72, 47, 'Submit Document', 'submitted Accomplishment Report: dcsdvs', '2026-10-06 07:50:57'),
(73, 1, 'Update Status', 'requested revision for Accomplishment Report: Test 2', '2026-10-06 07:51:30'),
(74, 1, 'Update Status', 'requested revision for Accomplishment Report: dcsdvs', '2026-10-06 07:51:47'),
(75, 2, 'Update Document', 'resubmitted Accomplishment Report: Test 2', '2026-10-06 08:45:56'),
(76, 1, 'Approve Document', 'verified Accomplishment Report: Test 2', '2026-10-06 08:46:21'),
(77, 1, 'Login', 'Jude Tayaben logged in', '2026-10-06 14:36:07'),
(78, 1, 'Login', 'Jude Tayaben logged in', '2026-10-07 02:52:19'),
(79, 47, 'Login', 'GAD Staff logged in', '2026-10-07 02:52:33'),
(80, 2, 'Login', 'CA TWG logged in', '2026-10-07 02:52:46'),
(81, 47, 'Login', 'GAD Staff logged in', '2026-10-07 03:37:08'),
(82, 1, 'Update User', 'updated user account: George Pacyaden', '2026-10-07 03:42:46'),
(83, 1, 'Update User', 'updated user account: Joshua Duligas', '2026-10-07 03:46:14'),
(84, 47, 'Submit Document', 'submitted Activity Design: sdsds', '2026-10-07 04:54:00'),
(85, 1, 'Approve Document', 'approved Activity Design: sdsds', '2026-10-07 04:54:45'),
(86, 2, 'Update Document', 'resubmitted Activity Design: Test 1', '2026-10-07 04:55:10'),
(87, 1, 'Update User', 'updated user account: Joshua Duligas', '2026-10-07 04:58:21'),
(88, 47, 'Submit Document', 'submitted Accomplishment Report: sdsds123', '2026-10-07 05:01:49'),
(89, 1, 'Approve Document', 'verified Accomplishment Report: sdsds123', '2026-10-07 05:02:39'),
(90, 1, 'Login', 'Jude Tayaben logged in', '2026-10-07 12:06:04'),
(91, 47, 'Login', 'GAD Staff logged in', '2026-10-07 12:06:12'),
(92, 1, 'Login', 'Jude Tayaben logged in', '2026-10-07 12:13:40'),
(93, 1, 'Login', 'Jude Tayaben logged in', '2026-10-08 00:02:18'),
(94, 47, 'Login', 'GAD Staff logged in', '2026-10-08 00:02:24'),
(95, 1, 'Update Status', 'requested revision for Activity Design: Test 1', '2026-10-08 02:37:42'),
(96, 2, 'Login', 'CA TWG logged in', '2026-10-08 02:38:32'),
(97, 2, 'Update Profile', 'updated their display name', '2026-10-08 03:36:10'),
(98, 2, 'Trash Document', 'moved to trash Activity Design: d sdvs', '2026-10-08 03:51:18'),
(99, 47, 'Suspend User', 'suspended user account: Joshua Duligas', '2026-10-08 04:06:08'),
(100, 47, 'Restore User', 'restored user account: Joshua Duligas', '2026-10-08 04:06:11'),
(101, 47, 'Login', 'GAD Staff logged in', '2026-10-08 08:57:12'),
(102, 1, 'Login', 'Jude Tayaben logged in', '2026-10-08 09:49:40'),
(103, 47, 'Login', 'GAD Staff logged in', '2026-10-08 09:49:52'),
(104, 2, 'Login', 'Ca TWG logged in', '2026-10-08 10:47:57'),
(105, 52, 'Login', 'Joshua Duligas logged in', '2026-10-08 10:53:41'),
(106, 52, 'Login', 'Joshua Duligas logged in', '2026-10-08 10:56:05'),
(107, 52, 'Login', 'Joshua Duligas logged in', '2026-10-08 11:05:33'),
(108, 54, 'Register User', 'registered a new user: John Doe', '2026-10-08 11:08:36'),
(109, 1, 'Suspend User', 'suspended user account: John Doe', '2026-10-08 11:19:06'),
(110, 1, 'Delete User Credentials', 'permanently deleted credentials for user: John Doe', '2026-10-08 11:19:09'),
(111, 55, 'Register User', 'registered a new user: John Doe', '2026-10-08 11:28:29'),
(112, 47, 'Suspend User', 'suspended user account: John Doe', '2026-10-08 11:30:20'),
(113, 47, 'Delete User Credentials', 'permanently deleted credentials for user: John Doe', '2026-10-08 11:30:23'),
(114, 56, 'Register User', 'registered a new user: John Doe', '2026-10-08 11:31:16'),
(115, 1, 'Login', 'Jude Tayaben logged in', '2026-10-09 11:37:41'),
(116, 47, 'Login', 'GAD Staff logged in', '2026-10-09 11:37:48'),
(117, 47, 'Register User', 'registered a new user: mark anthony', '2026-10-09 12:13:42'),
(118, 57, 'Login', 'mark anthony logged in', '2026-10-09 12:14:31'),
(119, 57, 'Update Profile', 'uploaded a new profile picture', '2026-10-09 12:14:56'),
(120, 57, 'Update Profile', 'updated profile and designation details', '2026-10-09 12:15:49'),
(121, 57, 'Submit Document', 'submitted Activity Design: sfvsfvsd', '2026-10-09 12:16:48'),
(122, 1, 'Login', 'Jude Tayaben logged in', '2026-10-09 12:17:06'),
(123, 1, 'Login', 'Jude Tayaben logged in', '2026-10-09 12:22:07'),
(124, 47, 'Login', 'GAD Staff logged in', '2026-10-09 12:22:13'),
(125, 57, 'Login', 'mark anthony logged in', '2026-10-09 12:23:11'),
(126, 57, 'Update Profile', 'removed profile picture', '2026-10-09 12:23:28'),
(127, 57, 'Update Profile', 'uploaded a new profile picture', '2026-10-09 12:23:40'),
(128, 47, 'Update Document', 'resubmitted Accomplishment Report: dcsdvs', '2026-10-09 13:36:48'),
(129, 1, 'Login', 'Jude Tayaben logged in', '2026-10-10 04:53:31'),
(130, 2, 'Login', 'Ca TWG logged in', '2026-10-10 04:53:50'),
(131, 47, 'Login', 'GAD Staff logged in', '2026-10-10 04:54:09'),
(132, 2, 'Update Profile', 'updated profile and designation details', '2026-10-10 09:00:33'),
(133, 2, 'Update Profile', 'updated profile and designation details', '2026-10-10 09:00:36'),
(134, 2, 'Update Profile', 'updated profile and designation details', '2026-10-10 09:00:43'),
(135, 1, 'Trash Document', 'moved to trash Activity Design: test 1', '2026-10-10 09:03:56'),
(136, 47, 'Trash Document', 'moved to trash Activity Design: hvchjvhjv', '2026-10-10 09:04:38'),
(137, 2, 'Send Message', 'sent a message: ', '2026-10-10 09:05:31'),
(138, 47, 'Submit Document', 'submitted Activity Design: test staff ad', '2026-10-10 09:08:10'),
(139, 2, 'Submit Document', 'submitted Activity Design: test twg ad', '2026-10-10 09:09:36'),
(140, 1, 'Update Status', 'requested revision for Activity Design: test staff ad', '2026-10-10 09:10:33'),
(141, 1, 'Update Status', 'requested revision for Activity Design: test twg ad', '2026-10-10 09:11:06'),
(142, 47, 'Update Document', 'resubmitted Activity Design: test staff ad', '2026-10-10 09:12:05'),
(143, 2, 'Update Document', 'resubmitted Activity Design: test twg ad', '2026-10-10 09:12:19'),
(144, 1, 'Approve Document', 'approved Activity Design: test staff ad', '2026-10-10 09:12:59'),
(145, 1, 'Approve Document', 'approved Activity Design: test twg ad', '2026-10-10 09:13:27'),
(146, 47, 'Submit Document', 'submitted Accomplishment Report: test staff ad', '2026-10-10 09:14:29'),
(147, 2, 'Submit Document', 'submitted Accomplishment Report: test twg ad', '2026-10-10 09:15:23'),
(148, 1, 'Update Status', 'requested revision for Accomplishment Report: test staff ad', '2026-10-10 09:16:13'),
(149, 1, 'Update Status', 'requested revision for Accomplishment Report: test twg ad', '2026-10-10 09:16:34'),
(150, 47, 'Update Document', 'resubmitted Accomplishment Report: test staff ad', '2026-10-10 09:17:15'),
(151, 2, 'Update Document', 'resubmitted Accomplishment Report: test twg ad', '2026-10-10 09:17:29'),
(152, 1, 'Approve Document', 'verified Accomplishment Report: test staff ad', '2026-10-10 09:17:58'),
(153, 1, 'Trash Document', 'moved to trash Accomplishment Report: test twg ad', '2026-10-10 09:18:22'),
(154, 47, 'Update Document', 'resubmitted Activity Design: test staff ad', '2026-10-10 09:19:07'),
(155, 2, 'Request Modification', 'requested modification for Activity Design: test twg ad', '2026-10-10 09:19:43'),
(156, 47, 'Approve Modification', 'approved modification request for Activity Design: test twg ad', '2026-10-10 09:20:06'),
(157, 2, 'Update Document', 'resubmitted Activity Design: Test 1', '2026-10-10 09:20:33'),
(158, 2, 'Login', 'Caa TWG logged in', '2026-10-11 04:28:19'),
(159, 2, 'Trash Message', 'moved a message thread to trash', '2026-10-11 04:49:33');

-- --------------------------------------------------------

--
-- Table structure for table `activity_schedules`
--

CREATE TABLE `activity_schedules` (
  `id` int(11) UNSIGNED NOT NULL,
  `act_design_id` int(11) NOT NULL,
  `schedule_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `meals_and_snacks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_schedules`
--

INSERT INTO `activity_schedules` (`id`, `act_design_id`, `schedule_date`, `start_time`, `end_time`, `created_at`, `updated_at`, `meals_and_snacks`) VALUES
(3, 1, '2026-09-30', '13:57:00', '16:57:00', '2026-09-26 06:08:00', '2026-09-26 06:08:00', NULL),
(4, 1, '2026-10-01', '07:57:00', '13:57:00', '2026-09-26 06:08:00', '2026-09-26 06:08:00', NULL),
(21, 3, '2026-09-30', '04:50:00', '16:50:00', '2026-09-27 08:50:31', '2026-09-27 08:50:31', NULL),
(26, 5, '2026-10-02', '05:23:00', '12:00:00', '2026-09-29 09:25:01', '2026-09-29 09:25:01', NULL),
(28, 6, '2026-10-14', '10:42:00', '14:43:00', '2026-09-30 03:19:27', '2026-09-30 03:19:27', NULL),
(29, 2, '2026-10-12', '11:10:00', '18:11:00', '2026-10-05 23:46:44', '2026-10-05 23:46:44', NULL),
(30, 2, '2026-10-13', '11:10:00', '18:11:00', '2026-10-05 23:46:44', '2026-10-05 23:46:44', NULL),
(31, 2, '2026-10-14', '11:10:00', '18:11:00', '2026-10-05 23:46:44', '2026-10-05 23:46:44', NULL),
(32, 2, '2026-10-15', '11:10:00', '18:11:00', '2026-10-05 23:46:44', '2026-10-05 23:46:44', NULL),
(33, 2, '2026-10-16', '11:10:00', '18:11:00', '2026-10-05 23:46:44', '2026-10-05 23:46:44', NULL),
(34, 2, '2026-10-19', '11:10:00', '18:11:00', '2026-10-05 23:46:44', '2026-10-05 23:46:44', NULL),
(35, 2, '2026-10-20', '11:10:00', '18:11:00', '2026-10-05 23:46:44', '2026-10-05 23:46:44', NULL),
(36, 2, '2026-10-21', '11:10:00', '18:11:00', '2026-10-05 23:46:44', '2026-10-05 23:46:44', NULL),
(37, 7, '2026-10-12', '10:53:00', '17:53:00', '2026-10-07 04:54:00', '2026-10-07 04:54:00', NULL),
(38, 7, '2026-10-13', '10:53:00', '17:53:00', '2026-10-07 04:54:00', '2026-10-07 04:54:00', NULL),
(39, 7, '2026-10-14', '10:53:00', '17:53:00', '2026-10-07 04:54:00', '2026-10-07 04:54:00', NULL),
(44, 8, '2026-10-14', '08:16:00', '15:16:00', '2026-10-09 12:16:48', '2026-10-09 12:16:48', NULL),
(45, 8, '2026-10-15', '08:16:00', '15:16:00', '2026-10-09 12:16:48', '2026-10-09 12:16:48', NULL),
(53, 10, '2026-10-14', '08:00:00', '19:00:00', '2026-10-10 09:12:19', '2026-10-10 09:12:19', NULL),
(54, 9, '2026-10-14', '08:00:00', '20:00:00', '2026-10-10 09:19:07', '2026-10-10 09:19:07', NULL),
(55, 9, '2026-10-15', '08:00:00', '20:00:00', '2026-10-10 09:19:07', '2026-10-10 09:19:07', NULL),
(56, 9, '2026-10-16', '08:00:00', '20:00:00', '2026-10-10 09:19:07', '2026-10-10 09:19:07', NULL),
(57, 4, '2026-10-20', '05:21:00', '19:21:00', '2026-10-10 09:20:33', '2026-10-10 09:20:33', NULL),
(58, 4, '2026-10-21', '05:21:00', '19:21:00', '2026-10-10 09:20:33', '2026-10-10 09:20:33', NULL),
(59, 4, '2026-10-22', '05:21:00', '19:21:00', '2026-10-10 09:20:33', '2026-10-10 09:20:33', NULL),
(60, 4, '2026-10-23', '05:21:00', '19:21:00', '2026-10-10 09:20:33', '2026-10-10 09:20:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `archived_annual_reports`
--

CREATE TABLE `archived_annual_reports` (
  `id` int(11) UNSIGNED NOT NULL,
  `fiscal_year` varchar(4) NOT NULL,
  `html_content` longtext NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `archived_annual_reports`
--

INSERT INTO `archived_annual_reports` (`id`, `fiscal_year`, `html_content`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '2026', '<div data-v-996bf7cb=\"\" class=\"report-wrapper\"><div class=\"masthead\"><div class=\"masthead-top\"><div><p class=\"eyebrow\">Editable Copy</p><h1>Annual Gender &amp; Development<br>Accomplishment Report</h1></div></div></div><div class=\"info-wrap\"><div class=\"info-panel\" id=\"info-panel\"><div class=\"field\"><label>Fiscal Year</label><div class=\"val\" id=\"f-fiscalyear\">2026</div></div><div class=\"field\"><label>Organization</label><div class=\"val\" id=\"f-org\">Benguet State University</div></div><div class=\"field\"><label>Organization Category</label><div class=\"val\" id=\"f-category\">State Universities and Colleges</div></div><div class=\"field\"><label>Organization Hierarchy</label><div class=\"val\" id=\"f-hierarchy\">Benguet State University</div></div><div class=\"field stat\"><label>Total Budget / GAA of Organization</label><div class=\"val\" id=\"f-totalbudget\">₱1,062,488,000.00</div></div><div class=\"field stat\"><label>Actual GAD Expenditure</label><div class=\"val\" id=\"f-actualgad\">₱4,223,550.00</div></div><div class=\"field stat\"><label>Original Budget</label><div class=\"val\" id=\"f-origbudget\">₱243,541,950.62</div></div><div class=\"field computed\"><label>% Utilization of Budget <span class=\"auto-tag\">auto</span></label><div class=\"val\" id=\"f-util\">1.73%</div></div><div class=\"field computed\"><label>% of GAD Expenditure <span class=\"auto-tag\">auto</span></label><div class=\"val\" id=\"f-pctgad\">0.40%</div></div></div></div><div class=\"table-wrap\"><div class=\"scroller\"><table id=\"gad-table\"><thead><tr><th class=\"col-num\"><span class=\"colnum\">1</span>#</th><th><span class=\"colnum\">2</span>Gender Issue / GAD Mandate</th><th><span class=\"colnum\">3</span>Cause of Gender Issue</th><th><span class=\"colnum\">4</span>GAD Result Statement / Objective</th><th><span class=\"colnum\">5</span>Relevant Org. MFO/PAP/PPA</th><th><span class=\"colnum\">6</span>GAD Activity</th><th><span class=\"colnum\">7</span>Performance Indicators / Targets<em>text + M/F, auto-totaled</em></th><th><span class=\"colnum\">8</span>Actual Result (Outputs/Outcomes)<em>text + M/F, auto-totaled</em></th><th><span class=\"colnum\">9</span>Total Agency Approved Budget<em>line items, auto-totaled</em></th><th><span class=\"colnum\">10</span>Actual Cost / Expenditure<em>line items, auto-totaled</em></th><th><span class=\"colnum\">11</span>Responsible Unit / Office</th><th><span class=\"colnum\">12</span>Variance / Remarks</th></tr></thead><tbody id=\"table-body\"><tr class=\"section-row\"><td colspan=\"13\">CLIENT-FOCUSED ACTIVITIES</td></tr><tr data-row-id=\"1\"><td class=\"col-num\">1</td><td data-col=\"issue\">Republic Act No. 10931, Universal Access to Quality Tertiary Education Act Section 8 on Affirmative Action Program; CHED Memorandum Orders on GAD Mainstreaming in Higher Education Institutions (HEIs)</td><td data-col=\"cause\">Extraordinary life situations due to disasters, calamities, and socio-cultural discrimination</td><td data-col=\"result\">To promote equitable access and participation of both women and men from GIDAs in tertiary education through gender-responsive implementation of the Affirmative Action Agenda.</td><td data-col=\"org\">Higher Education Program</td><td data-col=\"activity\">Implementation of Affirmative Action Agenda</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">Number of served disadvantaged students - 100% disadvantaged students</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: 345</div></div><div class=\"gc-compare\" style=\"display: none;\"></div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.3065287008424451\"><div class=\"items-list\" id=\"abId0.2954584048068314\" abineguid=\"FCFBDD105DCE4B90AE9BD7E21C91462E\"><div class=\"item-line\" id=\"abId0.11558248129106774\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.7419850562293101\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.7926905877511886\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.09440158032541235\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱700,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.8917862700026304\"><div class=\"items-list\" id=\"abId0.48583464506986274\" abineguid=\"A4863A06AB1B4DD89A497604ABB8781F\"><div class=\"item-line\" id=\"abId0.4467203720539765\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.7967515303527988\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.3418573732315465\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.4711977848506783\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱4,223,550.00</div><div class=\"variance-badge over\">Over by ₱3,523,550.00</div></div></td><td data-col=\"responsible\">OSS</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"2\"><td class=\"col-num\">2</td><td data-col=\"issue\">Republic Act No. 10931, Universal Access to Quality Tertiary Education Act Section 8 on Affirmative Action Program; CHED Memorandum Orders on GAD Mainstreaming in Higher Education Institutions (HEIs)</td><td data-col=\"cause\">High tuition and miscellaneous fees, compounded by socio-cultural expectations for women to prioritize domestic roles over education</td><td data-col=\"result\">To promote gender equality in access to tertiary education by eliminating financial barriers for both male and female students.</td><td data-col=\"org\">Higher Education Program</td><td data-col=\"activity\">Provision of free tuition fee under RA 10931 to eligible male and female students of the university.</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">Percentage of qualified students granted free tuition - 100% of qualified students granted free tuition.</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare\" style=\"display: none;\"></div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.35209809237582457\"><div class=\"items-list\" id=\"abId0.6477944989591973\"><div class=\"item-line\" id=\"abId0.6255948154510018\" abineguid=\"CCE79BAB31C945389883F212EC3CAECF\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.07235137155618077\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱131,100,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.5734349648184458\"><div class=\"items-list\" id=\"abId0.23315355567872176\"><div class=\"item-line\" id=\"abId0.8457170501983786\" abineguid=\"006DE1EA299548D08365FF9CFAC8D54C\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.5600837786313174\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱131,100,000.00</div></div></td><td data-col=\"responsible\">OSS, OUR, UHS</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"3\"><td class=\"col-num\">3</td><td data-col=\"issue\">CHED Memorandum Order No. 01 series 2015</td><td data-col=\"cause\">Limited activities to increase awareness of men and women students to GAD-related information (1st year students, transferees)</td><td data-col=\"result\">To increase the students level of awareness and appreciation on GAD</td><td data-col=\"org\">Higher Education Program</td><td data-col=\"activity\">Conduct GAD orientation/ forum/ seminar to BSU 1st year/ transferees students (face to face/ online: 14 colleges)</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">No. of students oriented on GAD - 4,000 students oriented on GAD (F:2750 M:1250)</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: 5,500</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare negative\" style=\"display: inline-block;\">vs target (5,500): -5,500</div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.049072944918222006\"><div class=\"items-list\" id=\"abId0.059466857998993206\" abineguid=\"1DB12FA5E3F0497782244915B63A4143\"><div class=\"item-line\" id=\"abId0.8543774971325555\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.08034392097821752\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.5815202288292538\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.03639526636684465\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.6989668158421628\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.032250485219587954\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱453,363.26</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.2003645322508456\"><div class=\"items-list\" id=\"abId0.3525785980529067\" abineguid=\"1BE58BDD862C426689D5559E19E46E50\"><div class=\"item-line\" id=\"abId0.7062653797088476\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.2619085402755763\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.5173904412117306\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.14323146997976877\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.7203634934326094\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.4178523413433973\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱453,363.26</div></div></td><td data-col=\"responsible\">OSS, GAD Office, 3 Campuses (La Trinidad, Bokod &amp; Buguias Campus)</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"4\"><td class=\"col-num\">4</td><td data-col=\"issue\">CHED Memorandum Order No. 01 series 2015</td><td data-col=\"cause\">Student leaders have limited understanding on GAD in the University</td><td data-col=\"result\">To empower student leaders regarding GAD responsive leadership (La Trinidad Campus, Bokod Campus and Buguias Campus)</td><td data-col=\"org\">Higher Education Program</td><td data-col=\"activity\">Continuous conduct of GAD responsive leadership training for student</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">No. of training conducted to increase GAD awareness and responsiveness of students leaders - 2 training (Female:200 Male:100) (La Trinidad Campus, Bokod Campus and Buguias Campus)</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: 400</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare negative\" style=\"display: inline-block;\">vs target (400): -400</div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.514444644225574\"><div class=\"items-list\" id=\"abId0.8867924950683452\" abineguid=\"781C914EA73242758807676D6E5C550F\"><div class=\"item-line\" id=\"abId0.8273380340653808\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.9974726579579721\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.007584448873785177\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.8912570794863409\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.9147017951225869\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.445743639642193\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱150,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.9261920420210779\"><div class=\"items-list\" id=\"abId0.2175073017916337\" abineguid=\"B67F16DF311345E38BA448B9E8DFA093\"><div class=\"item-line\" id=\"abId0.3579059760444604\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.1221803959279063\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.2365969262203561\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.6949721870121449\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.3264477359548261\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.8853092760960174\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱150,000.00</div></div></td><td data-col=\"responsible\">OSS</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"5\"><td class=\"col-num\">5</td><td data-col=\"issue\">Part VII of CHED Memorandum Order Number 1, series 2015 on Gender-Responsive Research and Extension Program</td><td data-col=\"cause\">Presence of gender inequality, poverty and GAD-related concerns in the community</td><td data-col=\"result\">To sustain GAD-related extension activities delivering technology transfer, Livelihood Program, Technical Assistance, and Advocacy to community partners to help promote gender equality, poverty reduction and sustainable development</td><td data-col=\"org\">Extension Services / Research Services / Advance Education Services / Higher Education Services</td><td data-col=\"activity\">Conduct of Extension project/ activities to partner organizational/ communities as component of Gender Responsive Extension Program (GREP) to partner organization/ communities</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">No. of extension activities conducted within the year - 24 Extension program/project/ activities conducted within the year (Female:560 Male:500)</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: 1,120</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare negative\" style=\"display: inline-block;\">vs target (1,120): -1,120</div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.9589546332283408\"><div class=\"items-list\" id=\"abId0.4710878643925476\" abineguid=\"F4790816F4504E408AC369096C2D1FF7\"><div class=\"item-line\" id=\"abId0.7869698734501837\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.9466970076538493\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.48103602952819624\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.4961892803195228\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱3,500,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.810731187006506\"><div class=\"items-list\" id=\"abId0.42796364054338587\" abineguid=\"94E51D2B7F2E49699E12DC0472C53E1D\"><div class=\"item-line\" id=\"abId0.32180651196294663\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.6636286936955346\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.9816414662833808\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.5370363471370015\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱3,500,000.00</div></div></td><td data-col=\"responsible\">Research and Extension, various offices/ colleges in the University/ external campuses</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"6\"><td class=\"col-num\">6</td><td data-col=\"issue\">Limited access of PWDs to gender-responsive programs and services / DBM-DSWD Joint Circular No. 2003-01 (at least 1% of budget for senior citizen and PWD programs)</td><td data-col=\"cause\">Limited access of PWDs to gender-responsive programs and services</td><td data-col=\"result\">Improved access of PWDs to gender-responsive, inclusive, and empowering programs and services.</td><td data-col=\"org\">Research Services / Extension Services / Advance Education Services / Higher Education Services</td><td data-col=\"activity\">Awareness of women PWDs who benefited from the program</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">Number of women PWDs who benefited from the program - F/M. No. of GAD program/project/activity provided for PWD - At least 1 program/project/activity</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare\" style=\"display: none;\"></div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.45508231885000516\"><div class=\"items-list\" id=\"abId0.029276810148727628\"><div class=\"item-line\" id=\"abId0.16842215874777688\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.10914801752767678\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.47403114708345806\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.4223509270084663\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱350,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.805991371516974\"><div class=\"items-list\" id=\"abId0.3636922379092111\"><div class=\"item-line\" id=\"abId0.41311953728474116\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.5592436103910329\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.4670428279504176\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.36312160277897876\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱350,000.00</div></div></td><td data-col=\"responsible\">HRMO, OSS</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"7\"><td class=\"col-num\">7</td><td data-col=\"issue\">Lack of senior citizens access to gender-responsive programs and services / DBM-DSWD Joint Circular No. 2003-01 (at least 1% of budget for senior citizen and PWD programs)</td><td data-col=\"cause\">Absence of sustainable and gender-responsive university programs for senior citizens.</td><td data-col=\"result\">Improved access of senior citizens to gender-responsive, inclusive, and empowering programs and services</td><td data-col=\"org\">Research Services / Extension Services / Advance Education Services / Higher Education Services</td><td data-col=\"activity\">Program: BSU Kalinga for women Senior Citizens</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">Number of Programs provided for Senior Citizens - At least 1 program for BSU. Number of women senior citizens who benefited from the program - F21 M23</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare\" style=\"display: none;\"></div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.7120942459041468\"><div class=\"items-list\" id=\"abId0.01873479964954916\"><div class=\"item-line\" id=\"abId0.09895793291941535\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.5554425115182308\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱250,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.06142500820969443\"><div class=\"items-list\" id=\"abId0.3446545930737709\"><div class=\"item-line\" id=\"abId0.3586429493403104\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.5570071761909712\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱250,000.00</div></div></td><td data-col=\"responsible\">GAD Office, Colleges, External Campuses</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"8\"><td class=\"col-num\">8</td><td data-col=\"issue\">Low number of women\'s participation in sports / MCW-IRR Section 14</td><td data-col=\"cause\">Minimal attendance of female students to competitive and non-competitive sports</td><td data-col=\"result\">To increase female students level of participation and awareness on Gender in Sports</td><td data-col=\"org\">Higher Education Program</td><td data-col=\"activity\">Participate in sports activities targeted for female students</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">No. of sports activities supported through allocation of budget for sports and socio-cultural activities/ E-sports (i.e. Annual Women\'s Martial Arts Festival) - 2 sports activities (Female:20)</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: 40</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare negative\" style=\"display: inline-block;\">vs target (40): -40</div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.6011619552266527\"><div class=\"items-list\" id=\"abId0.7219622668012621\"><div class=\"item-line\" id=\"abId0.41444787532678606\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.6040341035914232\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.9857496497715232\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.06829675507678779\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.4774469289860014\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.677631638345606\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱160,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.42130179787051003\"><div class=\"items-list\" id=\"abId0.5689700521108039\"><div class=\"item-line\" id=\"abId0.05651986067406867\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.09128102630850399\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.7234950959914934\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.38054993509631185\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.9064918725766503\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.29501971132196747\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱160,000.00</div></div></td><td data-col=\"responsible\">CHK</td><td data-col=\"remarks\"></td></tr><tr class=\"add-row\"><td colspan=\"13\"><button>+ Add row to client</button></td></tr><tr class=\"section-row\"><td colspan=\"13\">ORGANIZATION-FOCUSED ACTIVITIES</td></tr><tr data-row-id=\"9\"><td class=\"col-num\">9</td><td data-col=\"issue\">Programs on Awards and Incentives for Service Excellence (PRAISE, CSC Res. No. 010112 and CSC MC No.1, s.2001); GFPS Memo Circular No. 2011-01; BOR Res. No. 2316, s.2014</td><td data-col=\"cause\">Low recognition/appreciation on the Gender Mainstreaming in BSU</td><td data-col=\"result\">Strengthen Gender Mainstreaming through recognition of GAD implementation in the University</td><td data-col=\"org\">Research Services / Extension Services / Advance Education Services / Higher Education Services</td><td data-col=\"activity\">Provide recognition and award to GAD implementer and other GAD-related award (GAD implementer for students and employees, GAD Advocate Award)</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">No. of award will be provided through BSU-PRAISE - At least 1 GAD Advocate award will be provided through BSU-PRAISE</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare\" style=\"display: none;\"></div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.9333357787070101\"><div class=\"items-list\" id=\"abId0.8581568150146028\"><div class=\"item-line\" id=\"abId0.3791488799357827\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.1990614265432573\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.24566432955785544\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.259818617762858\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱205,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.643126081926992\"><div class=\"items-list\" id=\"abId0.05230569806721952\"><div class=\"item-line\" id=\"abId0.2773632867007142\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.5929578759770968\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.35397231376135996\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.7000282439155804\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱205,000.00</div></div></td><td data-col=\"responsible\">HRDO, HRMO, BSU-PRAISE Committee, GAD Office</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"10\"><td class=\"col-num\">10</td><td data-col=\"issue\">Limited application of GAD Mainstreaming (GM) in Instruction, Research, Extension and Production / Magna Carta of Women (RA 9710)</td><td data-col=\"cause\">Low awareness among personnel in the University about GAD mainstreaming</td><td data-col=\"result\">To enhance GAD mainstreaming in Administration, Academic, Research and Extension, Production</td><td data-col=\"org\">Research Services / Extension Services / Advance Education Services / Higher Education Services</td><td data-col=\"activity\">Conduct GAD related Gender Mainstreaming capability building and competency acquisition</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">No. of training/workshop/seminars conducted - 25 training/workshop/seminars/Learning and Development (F:1500 M:1000)</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: 3,000</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare negative\" style=\"display: inline-block;\">vs target (3,000): -3,000</div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.2440959842881525\"><div class=\"items-list\" id=\"abId0.8918999672475086\"><div class=\"item-line\" id=\"abId0.3612853524798171\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.6166158690402143\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.5949452572010229\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.041330138662307836\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱4,000,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.4622015593630704\"><div class=\"items-list\" id=\"abId0.2292154914482838\"><div class=\"item-line\" id=\"abId0.8674602404797219\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.39218301726489635\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.69927006492081\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.9378490069109885\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱4,000,000.00</div></div></td><td data-col=\"responsible\">GAD Office, HRDO, Research and Extension, OQAA, All Colleges with External Campuses</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"11\"><td class=\"col-num\">11</td><td data-col=\"issue\">Magna Carta of Women IRR Section 37 - Gender Mainstreaming as a Strategy for Implementing the Magna Carta of Women</td><td data-col=\"cause\">Productivity of employees affected due to filial obligations, affecting promotion of women to higher positions or from participating in capability enhancement sessions</td><td data-col=\"result\">Inadequate support services to personnel and students with children</td><td data-col=\"org\">Research Services / Extension Services / Advance Education Services / Higher Education Services</td><td data-col=\"activity\">Operationalize of BSU College of Nursing Reproductive Health Care Center</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">No. of maintained Reproductive Health Care Center - 1 maintained BSU CN Reproductive Health Care Center</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare\" style=\"display: none;\"></div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.722447757823959\"><div class=\"items-list\" id=\"abId0.41514958498566445\"><div class=\"item-line\" id=\"abId0.6523375462987108\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.6692507713219704\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.9407289895521974\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.8958191152439816\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱120,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.7381393635420173\"><div class=\"items-list\" id=\"abId0.6402903513413147\"><div class=\"item-line\" id=\"abId0.18268111740837056\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.7951708178402135\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.7903199134395981\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.2556155864372579\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱120,000.00</div></div></td><td data-col=\"responsible\">College of Nursing</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"12\"><td class=\"col-num\">12</td><td data-col=\"issue\">Executive Order No. 340 s.1997 - Directing National Government Agencies and GOCCs to provide Day Care Services for their Employees\' Children under five years of age</td><td data-col=\"cause\">Problems of parents and students related to child care</td><td data-col=\"result\">Ensure opportunities of personnel and students to have access on agency care services to children to avoid absenteeism</td><td data-col=\"org\">Research Services / Extension Services / Advance Education Services / Higher Education Services</td><td data-col=\"activity\">Maintenance of Child Minding Center for working parents in ensuring that they have a safe place to leave their child while they are at their work places</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">No. of established child minding center - Fully maintained new established and existing child minding centers at BSU La Trinidad, Bokod Campus and Buguias Campus</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare\" style=\"display: none;\"></div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.7860355085492867\"><div class=\"items-list\" id=\"abId0.33223391732940954\"><div class=\"item-line\" id=\"abId0.5085581491530305\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.8003375937854013\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.11141725751983966\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.7154907859606169\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱230,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.8511788885859742\"><div class=\"items-list\" id=\"abId0.1481732148732674\"><div class=\"item-line\" id=\"abId0.5303256232973693\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.7173053639398894\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.893276822104541\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.6278355413197579\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱230,000.00</div></div></td><td data-col=\"responsible\">GAD Office, External Campuses</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"13\"><td class=\"col-num\">13</td><td data-col=\"issue\">Magna Carta of Women (RA 9710)</td><td data-col=\"cause\">Low integration of gender mainstreaming of BSU</td><td data-col=\"result\">To strengthen the GAD integration in the operations of BSU</td><td data-col=\"org\">Research Services / Extension Services / Advance Education Services / Higher Education Services</td><td data-col=\"activity\">Create a Monitoring Team to conduct monitoring and evaluation of the utilization/outcome of GAD PAPs and ensure effectiveness of the GAD PAPs</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">No. of monitoring and assessment meetings with reports conducted - 4 monitoring and assessment meetings with reports conducted</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare\" style=\"display: none;\"></div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.07952269999379569\"><div class=\"items-list\" id=\"abId0.26245220710880257\"><div class=\"item-line\" id=\"abId0.6463568763520681\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.3780152649476336\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.9933419328909864\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.7390295315715424\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱330,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.597548411623096\"><div class=\"items-list\" id=\"abId0.913185259573303\"><div class=\"item-line\" id=\"abId0.6900603322319521\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.23723957648222882\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.24774908306314958\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.19351664504556165\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱330,000.00</div></div></td><td data-col=\"responsible\">GAD Office</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"14\"><td class=\"col-num\">14</td><td data-col=\"issue\">Magna Carta of Women IRR Section 37-C - Creation and/or Strengthening of the GAD Focal Points (GFPs)</td><td data-col=\"cause\">Low level of capacity of GFPS to develop and implement GAD programs and activities due to new members</td><td data-col=\"result\">Capacitated GFPS members in order to implement GAD PAP\'s and advance GAD Mainstreaming (GM) in the University</td><td data-col=\"org\">Research Services / Extension Services / Advance Education Services / Higher Education Services</td><td data-col=\"activity\">For GFPS/Secretariat: GMEF/HGDG/GPB/GAD Agenda/GAD Deepening Session and TOT among other related trainings and capacity building activities (Regional/National GAD-related trainings/seminars/forum/workshop)</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">No. training/seminars/workshop attendance for each GFPS-member on GAD related updates and mandates - At least 1 each (Female:31, Male:15)</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: 62</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare negative\" style=\"display: inline-block;\">vs target (62): -62</div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.7439589699015198\"><div class=\"items-list\" id=\"abId0.9360199507478599\"><div class=\"item-line\" id=\"abId0.8840231230192755\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.39169651363406954\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.7641723834363436\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.8802588883138294\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱896,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.8734097242364336\"><div class=\"items-list\" id=\"abId0.41252992802910415\"><div class=\"item-line\" id=\"abId0.5463107833192493\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.10443957612604682\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.40551319726206747\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.7617880146301241\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱896,000.00</div></div></td><td data-col=\"responsible\">GAD Office</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"15\"><td class=\"col-num\">15</td><td data-col=\"issue\">Section 37-C2 Rule VI of the Magna Carta of Women\'s IRR on duties and function of the GAD Focal Point System / Magna Carta of Women (RA 9710)</td><td data-col=\"cause\">Compliance to provisions regarding regular monitoring of gender mainstreaming efforts</td><td data-col=\"result\">To ensure operations of GAD Office as well as monitor and evaluate GM efforts of the University</td><td data-col=\"org\">Research Services / Extension Services / Advance Education Services / Higher Education Services</td><td data-col=\"activity\">Regular coordination and meetings of GAD-GFPS (Execom, GFPS-TWG members and external campus TWG members) and emergency meeting when necessary</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">No. of reports on regular meetings per campus - At least 6 reports available at year end, plus RGADC quarterly meeting/s</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare\" style=\"display: none;\"></div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.972392801606073\"><div class=\"items-list\" id=\"abId0.6301557965309833\"><div class=\"item-line\" id=\"abId0.6054614552343344\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.3939621641996415\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.4582247581035195\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.8472699970304798\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱211,720.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.8415313094891291\"><div class=\"items-list\" id=\"abId0.4931588166865051\"><div class=\"item-line\" id=\"abId0.713738672712338\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.2923995801568433\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.7108270607102435\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.9608201153458586\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱211,720.00</div></div></td><td data-col=\"responsible\">GAD Office</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"16\"><td class=\"col-num\">16</td><td data-col=\"issue\">Duties and function of the GAD Focal Point System / CHED Memo 2015-1</td><td data-col=\"cause\">No plantilla personnel assigned to plan, implement and monitor GAD PAPs on a full-time basis</td><td data-col=\"result\">To ensure operations of GAD Office as well as monitor and evaluate GM efforts of the University</td><td data-col=\"org\">Research Services / Extension Services / Advance Education Services / Higher Education Services</td><td data-col=\"activity\">Engage support staff to assist in the implementation of GFPS PPA\'s and Gender Mainstreaming in the university through rehiring of GAD staff and Student Assistant</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">Salary of GAD Staff: Casual. No. of rehired personnel - At least 2 staff renewed/rehired (Casual) and at least 3 Student Assistant/SPES per semester</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare\" style=\"display: none;\"></div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.7383961629408116\"><div class=\"items-list\" id=\"abId0.042865272528717746\"><div class=\"item-line\" id=\"abId0.6819029475493485\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.719737759127646\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱550,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.748607642121789\"><div class=\"items-list\" id=\"abId0.32758161056589796\"><div class=\"item-line\" id=\"abId0.002199345922734408\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.20257905169195622\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱550,000.00</div></div></td><td data-col=\"responsible\">GAD Office</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"17\"><td class=\"col-num\">17</td><td data-col=\"issue\">Low level of Awareness on Gender Mainstreaming (GM) among newly hired personnel / Magna Carta of Women (RA 9710), CHED Memo 2015-1</td><td data-col=\"cause\">Lack of regular orientation and refresher training on gender sensitivity and GAD mandates</td><td data-col=\"result\">To enhance awareness and understanding of gender concepts, GAD mandates, and gender-responsive work practices among newly hired and current personnel</td><td data-col=\"org\">Research Services / Extension Services / Advance Education Services / Higher Education Services</td><td data-col=\"activity\">Conduct Gender Sensitivity Training (GST) for newly hired and current personnel (continuing activity)</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">No. of trainings conducted - 1 training for at least 100% of newly hired personnel and 3 refresher trainings for current personnel</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare\" style=\"display: none;\"></div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.28497238804714164\"><div class=\"items-list\" id=\"abId0.06754706134403576\"><div class=\"item-line\" id=\"abId0.523289698117281\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.9137715344012245\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.28721030842451123\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.23230383022478096\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.38697166823221396\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.4235965563150895\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.15475352162576317\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.20763084859440162\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.43241704875309195\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.42822740780166535\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱421,728.32</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.3810880209718144\"><div class=\"items-list\" id=\"abId0.18408423860535095\"><div class=\"item-line\" id=\"abId0.7807222632464444\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.6804262199957305\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.3125926812607793\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.4254338744323717\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.5210099693095113\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.06637256832766059\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.06279498570370601\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.45429292254879194\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.7859265877044667\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.5702123345476463\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱421,728.32</div></div></td><td data-col=\"responsible\">GAD Office</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"18\"><td class=\"col-num\">18</td><td data-col=\"issue\">Part V, Rule II, Section 4 of CHED Memorandum Order No. 1 Series of 2015</td><td data-col=\"cause\">Limited number of GAD library and related learning materials across various disciplines</td><td data-col=\"result\">To increase the provision of adequate and accessible library and related learning materials across various disciplines and educational levels</td><td data-col=\"org\">Research Services / Extension Services / Advance Education Services / Higher Education Services</td><td data-col=\"activity\">Provision of knowledge products (books, magazines, multi-media) for adequate and accessible library and related learning materials in support to gender-responsive Curriculum Programs</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">No. of procured library and learning materials - 200 books</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare\" style=\"display: none;\"></div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.5020530931222932\"><div class=\"items-list\" id=\"abId0.014781069837788485\"><div class=\"item-line\" id=\"abId0.8326264274609845\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.882634985252756\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.03816541112212635\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.1790331930970076\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱2,600,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.7523007512137495\"><div class=\"items-list\" id=\"abId0.7032653078191106\"><div class=\"item-line\" id=\"abId0.0917675140710752\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.06174616954491641\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.471987882404323\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.8139729084492499\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱2,600,000.00</div></div></td><td data-col=\"responsible\">ULIS</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"19\"><td class=\"col-num\">19</td><td data-col=\"issue\">Development and Dissemination of Gender and Development (GAD) Information, Education, and Communication (IEC) Materials</td><td data-col=\"cause\">Presence of Gender Based Violence (GBV) issues/reports/cases in the university</td><td data-col=\"result\">Institutionalize GAD mechanisms in the University and sustain awareness campaigns on sexual harassment and gender-based violence</td><td data-col=\"org\">Research Services / Extension Services / Advance Education Services / Higher Education Services</td><td data-col=\"activity\">Development and Dissemination of Gender and Development (GAD) Information, Education, and Communication (IEC) Materials</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">Official Publication with GAD articles/pictures. Maintained GAD Bulletin board - At least 8. Sector-specific downloadable knowledge products - At least 2</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare\" style=\"display: none;\"></div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.4261829115281358\"><div class=\"items-list\" id=\"abId0.7997980523268322\"><div class=\"item-line\" id=\"abId0.7658815551910552\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.10577090006539447\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.305809564107946\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.13248294013985618\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.03545637880957242\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.47488785660747435\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱296,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.30193448173051884\"><div class=\"items-list\" id=\"abId0.6719872572907803\"><div class=\"item-line\" id=\"abId0.0928449812503982\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.5647452388565123\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.25635597127329335\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.27664856019650574\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.7707928657285166\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.6942779808075881\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱296,000.00</div></div></td><td data-col=\"responsible\">UPAO, GAD Office</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"20\"><td class=\"col-num\">20</td><td data-col=\"issue\">Institutionalizing GAD database and Sex-Disaggregated Database / Magna Carta of Women (RA 9710) Section 36 on Sex-Disaggregated Database</td><td data-col=\"cause\">Minimal awareness and appreciation on the relevance of the centralized Sex-Disaggregated database</td><td data-col=\"result\">To establish a centralized GAD-related database of the University</td><td data-col=\"org\">Research Services / Extension Services / Advance Education Services / Higher Education Services</td><td data-col=\"activity\">Updating of Sex-Disaggregated Data (SDD) and other data related to personnel/students/clients for gender analysis and report preparation</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">1 well-organized and maintained GAD database. Establishment of GAD-Database system per college/unit.</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare\" style=\"display: none;\"></div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.15535980552247286\"><div class=\"items-list\" id=\"abId0.3944755503815218\"><div class=\"item-line\" id=\"abId0.3818971111514826\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.0029959260552629985\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.35565210398123237\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.9846321017985228\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱1,100,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.4187107793125536\"><div class=\"items-list\" id=\"abId0.7570883910263243\"><div class=\"item-line\" id=\"abId0.5760218225338718\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.9284439504343316\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.604714399613399\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.04291121218161276\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱1,100,000.00</div></div></td><td data-col=\"responsible\">ICT, GAD Office</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"21\"><td class=\"col-num\">21</td><td data-col=\"issue\">Magna Carta for Women, Chapter IV Section 10 and RA 10121, Section 2 &amp; 9</td><td data-col=\"cause\">Limited resources of the DSWD and LGU to provide for students who are transient residents and limited appreciation on women\'s role in nation building among employees and students, especially new ones</td><td data-col=\"result\">To ensure that disaster assistance provided to distressed students are gender-responsive</td><td data-col=\"org\">Research Services / Extension Services / Advance Education Services / Higher Education Services</td><td data-col=\"activity\">Provision of gender-responsive services to employees and students who experienced crisis/disaster (e.g. distribution of hygiene kits for both women and men)</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">No. of pax of the most affected employees/students during crises - 1,000</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare\" style=\"display: none;\"></div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.8369815327821715\"><div class=\"items-list\" id=\"abId0.11272263152051831\"><div class=\"item-line\" id=\"abId0.5051134576673567\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.8181219398609542\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.8909911960373496\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.8549093118070472\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱210,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.9403153977342956\"><div class=\"items-list\" id=\"abId0.5772611994686775\"><div class=\"item-line\" id=\"abId0.8389247747450551\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.18928290934564462\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.4776193187873389\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.17643326019299843\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱210,000.00</div></div></td><td data-col=\"responsible\">GAD Office, HDRO, NSTP, various offices/all colleges in the University</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"22\"><td class=\"col-num\">22</td><td data-col=\"issue\">RA 9710 Sec.18 Special Leave for Women; RA 8187 Paternity Leave; RA 8972 Sec.8 Solo Parents Welfare Act; RA 9262 Sec.43 Anti-VAWC Act</td><td data-col=\"cause\">Employees may require special leaves due to parental obligations, health concerns and other circumstances</td><td data-col=\"result\">Enhanced support services for employees in need of special leaves</td><td data-col=\"org\">Research Services / Extension Services / Advance Education Services / Higher Education Services</td><td data-col=\"activity\">Provision of gender leaves and conduct of Seminar on Gender Related Leaves for Newly Hired Employees</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">No. of Maternity, Paternity, Solo parent, gynecological, VAWC leaves availed and 1 Seminar conducted (M:20 F:50) - 100%</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: 40</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare negative\" style=\"display: inline-block;\">vs target (40): -40</div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.4267134080546445\"><div class=\"items-list\" id=\"abId0.256038792278814\"><div class=\"item-line\" id=\"abId0.9218011299097396\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.23250331796650958\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱1,000,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.5275085833231388\"><div class=\"items-list\" id=\"abId0.23427518787273494\"><div class=\"item-line\" id=\"abId0.2854955735818291\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.5867100451469329\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱1,000,000.00</div></div></td><td data-col=\"responsible\">HRMO, CBOO, various offices/colleges in the University</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"23\"><td class=\"col-num\">23</td><td data-col=\"issue\">Proclamation 227 (Women\'s Role in History Month) and Proclamation 1172, s.2006 (18-Day Campaign to End VAW)</td><td data-col=\"cause\">The need to highlight women\'s rights, their role in nation building, and to provide a platform against VAW, gender-based violence, Safe Spaces Act (RA 11313)</td><td data-col=\"result\">To strengthen awareness of BSU students/employees on women\'s rights and their role in national development and nation building</td><td data-col=\"org\">Research Services / Extension Services / Advance Education Services / Higher Education Services</td><td data-col=\"activity\">Participation to 18-Day Campaign to end VAW and Women\'s Month Celebration/activities and programs organized by PCW and other agency/ies</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">No. of activities conducted per campus - At least 1 per campus</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare\" style=\"display: none;\"></div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.5839952737148274\"><div class=\"items-list\" id=\"abId0.16992037007910066\"><div class=\"item-line\" id=\"abId0.272469736747843\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.7882407937328348\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.6693396212976618\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.6593380937351534\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱450,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.13013463692820515\"><div class=\"items-list\" id=\"abId0.07210023495462858\"><div class=\"item-line\" id=\"abId0.6643456477069161\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.6968707406630595\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.0934975910334267\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.925551204452971\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱450,000.00</div></div></td><td data-col=\"responsible\">GAD Office, various offices/colleges in the University/external campus</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"24\"><td class=\"col-num\">24</td><td data-col=\"issue\">Productivity of employees affected due to filial obligations / Magna Carta of Women IRR Section 37</td><td data-col=\"cause\">Inadequate support services for personnel/students with young children and breastfeeding mothers (RA 10028)</td><td data-col=\"result\">Inadequate support services to personnel and students with children</td><td data-col=\"org\">Research Services / Extension Services / Advance Education Services / Higher Education Services</td><td data-col=\"activity\">Establishment/maintenance of breastfeeding station established in the preceding years</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">Fully maintained Lactation rooms - 100% at BSU La Trinidad, Bokod and Buguias Campus</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: 100</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare negative\" style=\"display: inline-block;\">vs target (100): -100</div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.6036663512550159\"><div class=\"items-list\" id=\"abId0.3164413449314506\"><div class=\"item-line\" id=\"abId0.4722320724028055\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.1858022186449373\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.45046560653638945\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.258540059176693\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱220,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.7726699090094926\"><div class=\"items-list\" id=\"abId0.9016679716685225\"><div class=\"item-line\" id=\"abId0.41729482716868505\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.22436182478435496\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.47083639473762384\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.6609318699125529\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱220,000.00</div></div></td><td data-col=\"responsible\">GAD Office, External Campuses</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"25\"><td class=\"col-num\">25</td><td data-col=\"issue\">RA 9710, PCW-NEDA-DBM Joint Circular 2012-01, CSC MC No.12 s.2005</td><td data-col=\"cause\">Lack of regular gender-related capacity-building activities and insufficient integration of gender sensitivity in employee development programs</td><td data-col=\"result\">To enhance the gender awareness and sensitivity of BSU employees, enabling them to recognize and eliminate gender bias and stereotyping, and to foster a gender-responsive and equitable workplace</td><td data-col=\"org\">Research Services / Extension Services / Advance Education Services / Higher Education Services</td><td data-col=\"activity\">Conduct of gender sensitivity orientations for BSU Personnel (continuing activity)</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">No. of trainings conducted for BSU personnel - At least 3</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare\" style=\"display: none;\"></div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.27878632312847085\"><div class=\"items-list\" id=\"abId0.210824507404773\"><div class=\"item-line\" id=\"abId0.24315204304287608\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.21737666128353228\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.2421151488170752\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.4405115110615998\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.27258143388153977\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.4599254608496701\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱253,796.24</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.1721943741005768\"><div class=\"items-list\" id=\"abId0.8738167840325332\"><div class=\"item-line\" id=\"abId0.9217585661036929\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.9582689041200804\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.6397235644060435\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.36107608028511384\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.026241094637441953\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.9555631387433767\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱253,796.24</div></div></td><td data-col=\"responsible\">GAD Office</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"26\"><td class=\"col-num\">26</td><td data-col=\"issue\">Establishment of Gender-Responsive Curricular Programs / Part V of CMO 01, s.2015</td><td data-col=\"cause\">Limited subject for GAD Integration of Gender-Responsive Instruction and Curriculum Development</td><td data-col=\"result\">Integration of gender mainstreaming in curriculum/subjects in all levels</td><td data-col=\"org\">Research Services / Extension Services / Advance Education Services / Higher Education Services</td><td data-col=\"activity\">Preparation of syllabi and classroom teaching integrating gender perspective</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">Number of faculty members integrating gender perspective in the syllabus - 567 permanent and 125 COS females/males</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare\" style=\"display: none;\"></div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.018288653812875366\"><div class=\"items-list\" id=\"abId0.39144787877724263\"><div class=\"item-line\" id=\"abId0.6875668389305668\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.8081144309893309\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.9343226541121308\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.3224454260544918\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱58,294,972.71</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.8074096840217854\"><div class=\"items-list\" id=\"abId0.9837858685930642\"><div class=\"item-line\" id=\"abId0.9012822241554924\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.8336338944745344\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.2817677932293836\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.6772958308863365\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱58,294,972.71</div></div></td><td data-col=\"responsible\">GAD Office, GFPS-TWG members, all colleges</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"27\"><td class=\"col-num\">27</td><td data-col=\"issue\">Section 37-C2 of the Magna Carta of Women (MCW) IRR - mandates establishment of a GAD Focal Point System (GFPS)</td><td data-col=\"cause\">Sustained operations of the existing GAD Office - administrative, logistical, and financial support for day-to-day functioning; maintenance of GAD database; coordination of GFPS and GAD-related activities across colleges and units</td><td data-col=\"result\">To ensure the continuous and efficient operation of a functional, gender-responsive GAD Office that leads, monitors, and evaluates GAD mainstreaming efforts in the university.</td><td data-col=\"org\">Research Services / Extension Services / Advance Education Services / Higher Education Services</td><td data-col=\"activity\">Sustaining Gender Mainstreaming and Institutional Support in the University</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\">Fully maintained GAD Office - 100%</div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare\" style=\"display: none;\"></div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.11491569020040582\"><div class=\"items-list\" id=\"abId0.4966968234476481\"><div class=\"item-line\" id=\"abId0.9598875548708783\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.693806705982563\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.2884087610445415\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.11024517905451192\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.878624448898478\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.43468275489745645\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.637141481995641\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.9747351348241226\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱8,052,370.09</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.7938853251044975\"><div class=\"items-list\" id=\"abId0.3127029558416806\"><div class=\"item-line\" id=\"abId0.2080093247179744\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.8743362863026976\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.28861369234114076\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.8432344099011704\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.17515942473933432\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.12705047752964904\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div><div class=\"item-line\" id=\"abId0.7015861505803872\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.9585458513932646\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱8,052,370.09</div></div></td><td data-col=\"responsible\">GAD Office</td><td data-col=\"remarks\"></td></tr><tr class=\"add-row\"><td colspan=\"13\"><button>+ Add row to org</button></td></tr><tr class=\"section-row\"><td colspan=\"13\">ATTRIBUTED PROGRAM</td></tr><tr data-row-id=\"28\"><td class=\"col-num\">28</td><td data-col=\"issue\"></td><td data-col=\"cause\"></td><td data-col=\"result\"></td><td data-col=\"org\"></td><td data-col=\"activity\">Transportation Equipment Outlay</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare\" style=\"display: none;\"></div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.8964883625508684\"><div class=\"items-list\" id=\"abId0.4380605369969055\"><div class=\"item-line\" id=\"abId0.07716945796451413\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.33329496759036525\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱12,285,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.9486733566816358\"><div class=\"items-list\" id=\"abId0.592397632795679\"><div class=\"item-line\" id=\"abId0.22617067159279314\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.6861866843688632\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱12,285,000.00</div></div></td><td data-col=\"responsible\">TASU, PMO, SPMO</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"29\"><td class=\"col-num\">29</td><td data-col=\"issue\"></td><td data-col=\"cause\"></td><td data-col=\"result\"></td><td data-col=\"org\"></td><td data-col=\"activity\">Repair and Maintenance of Office Building and other Structures</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare\" style=\"display: none;\"></div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.16670792497977005\"><div class=\"items-list\" id=\"abId0.8798853109137906\"><div class=\"item-line\" id=\"abId0.7070552120295337\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.2533388814564891\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱6,402,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.059972478839038335\"><div class=\"items-list\" id=\"abId0.5929256925019106\"><div class=\"item-line\" id=\"abId0.6535693263339318\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.38548389180827103\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱6,402,000.00</div></div></td><td data-col=\"responsible\">PU, PMO, SPMO</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"30\"><td class=\"col-num\">30</td><td data-col=\"issue\"></td><td data-col=\"cause\"></td><td data-col=\"result\"></td><td data-col=\"org\"></td><td data-col=\"activity\">Bamboo Industry Development for Environment Conservation and Countryside</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare\" style=\"display: none;\"></div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.2761352411697927\"><div class=\"items-list\" id=\"abId0.7899573780966113\"><div class=\"item-line\" id=\"abId0.9363393838751486\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.2656516083034467\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱3,750,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.026050674193438095\"><div class=\"items-list\" id=\"abId0.3189708932449866\"><div class=\"item-line\" id=\"abId0.2588561733873169\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.9860936335350994\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱3,750,000.00</div></div></td><td data-col=\"responsible\">College of Forestry</td><td data-col=\"remarks\"></td></tr><tr data-row-id=\"31\"><td class=\"col-num\">31</td><td data-col=\"issue\"></td><td data-col=\"cause\"></td><td data-col=\"result\"></td><td data-col=\"org\"></td><td data-col=\"activity\">Benguet State University Student Information and Accounting System (SIAS)</td><td data-col=\"indicators\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div></div></td><td data-col=\"actualResult\"><div class=\"gender-cell\"><div class=\"gc-text\"></div><div class=\"gc-gender-row\"><div class=\"gc-field\"><label>M</label><input type=\"number\" class=\"gc-amt gc-m\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-field\"><label>F</label><input type=\"number\" class=\"gc-amt gc-f\" placeholder=\"—\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><div class=\"gc-total\">Total: —</div></div><div class=\"gc-compare\" style=\"display: none;\"></div></div></td><td data-col=\"budget\"><div class=\"items-editor\" id=\"abId0.7076313785039049\"><div class=\"items-list\" id=\"abId0.08875392973122576\"><div class=\"item-line\" id=\"abId0.09690638922878592\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.26253710268250396\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱5,000,000.00</div></div></td><td data-col=\"cost\"><div class=\"items-editor\" id=\"abId0.819565602031238\"><div class=\"items-list\" id=\"abId0.3057240219877718\"><div class=\"item-line\" id=\"abId0.40754043479808366\"><input type=\"text\" class=\"item-label\" placeholder=\"Line item description\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><div class=\"item-meta-row\" id=\"abId0.3653620388427383\"><div class=\"item-amt-wrap\"><span class=\"peso\">₱</span><input type=\"number\" step=\"0.01\" class=\"item-amt\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"></div><input type=\"text\" class=\"item-src\" title=\"Fund source\" readonly=\"true\" style=\"pointer-events: none; border-width: medium; border-style: none; border-color: currentcolor; border-image: none; background: transparent;\"><button type=\"button\" class=\"item-rm\" title=\"Remove line item\">×</button></div></div></div><button type=\"button\" class=\"item-add\">+ line item</button><div class=\"item-subtotal\">Subtotal: ₱0.00</div><div class=\"variance-badge under\">Under by ₱5,000,000.00</div></div></td><td data-col=\"responsible\">ICT, PMO</td><td data-col=\"remarks\"></td></tr><tr class=\"add-row\"><td colspan=\"13\"><button>+ Add row to attributed</button></td></tr></tbody><tfoot><tr class=\"foot-computed\"><td colspan=\"8\" class=\"foot-label\">Total (Computed sum)</td><td id=\"computed-budget-total\">₱243,541,950.62</td><td id=\"computed-cost-total\">₱4,223,550.00</td><td colspan=\"3\" id=\"computed-variance-total\">Under by ₱239,318,400.62</td></tr><tr class=\"foot-gender\"><td colspan=\"6\" class=\"foot-label\">Computed headcount reach (live, sum of M/F fields)</td><td id=\"computed-target-total\">Target — M: 5,181 · F: 5,081 · Total: 10,262</td><td id=\"computed-actual-total\">Actual — M: 140 · F: 205 · Total: 345</td><td colspan=\"5\"></td></tr></tfoot></table></div></div></div>', 1, '2026-10-05 10:01:25', '2026-10-05 10:01:25');

-- --------------------------------------------------------

--
-- Table structure for table `budget_categories`
--

CREATE TABLE `budget_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `budget_categories`
--

INSERT INTO `budget_categories` (`id`, `name`) VALUES
(1, 'Meals and Snacks'),
(2, 'Function Room / Venue'),
(3, 'Accommodation'),
(4, 'Equipment Rental'),
(5, 'Professional Fee / Honoraria'),
(6, 'Tokens'),
(7, 'Materials and Supplies'),
(8, 'Transportation'),
(9, 'Other Expenses');

-- --------------------------------------------------------

--
-- Table structure for table `budget_item_mandate_allocations`
--

CREATE TABLE `budget_item_mandate_allocations` (
  `id` int(11) NOT NULL,
  `budget_item_id` int(11) NOT NULL,
  `item_type` enum('AD','AR') NOT NULL,
  `mandate_id` int(11) NOT NULL,
  `gpb_budget_line_id` varchar(255) DEFAULT NULL,
  `allocated_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `budget_item_mandate_allocations`
--

INSERT INTO `budget_item_mandate_allocations` (`id`, `budget_item_id`, `item_type`, `mandate_id`, `gpb_budget_line_id`, `allocated_amount`, `created_at`, `updated_at`) VALUES
(14, 35, 'AR', 1, 'c1-l2', 2200.00, '2026-10-08 02:51:27', '2026-10-08 02:51:27'),
(15, 43, 'AR', 1, 'c1-l2', 3642100.00, '2026-10-08 02:51:27', '2026-10-08 02:51:27'),
(16, 51, 'AR', 1, 'c1-l2', 579250.00, '2026-10-08 02:51:27', '2026-10-08 02:51:27'),
(17, 109, 'AR', 1, 'c1-l1', 1000.00, '2026-10-08 02:51:27', '2026-10-08 02:51:27');

-- --------------------------------------------------------

--
-- Table structure for table `contact_inquiries`
--

CREATE TABLE `contact_inquiries` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'new',
  `responded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_results`
--

CREATE TABLE `evaluation_results` (
  `id` int(11) UNSIGNED NOT NULL,
  `accomplishment_report_id` int(11) NOT NULL,
  `question_key` varchar(100) NOT NULL,
  `score` decimal(4,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluation_results`
--

INSERT INTO `evaluation_results` (`id`, `accomplishment_report_id`, `question_key`, `score`) VALUES
(8, 1, 'time_management', 5.00),
(9, 1, 'orderliness_and_program_flow', 5.00),
(10, 1, 'appropriateness_of_venue', 5.00),
(11, 1, 'sound_system_and_hall_preparation', 5.00),
(12, 1, 'restrooms', 5.00),
(13, 1, 'food_and_drinks', 5.00),
(14, 1, 'dfddfdfd', 5.00),
(15, 2, 'time_management', 5.00),
(16, 2, 'orderliness_and_program_flow', 5.00),
(17, 2, 'appropriateness_of_venue', 5.00),
(18, 2, 'sound_system_and_hall_preparation', 5.00),
(19, 2, 'restrooms', 5.00),
(20, 2, 'food_and_drinks', 5.00),
(21, 3, 'time_management', 5.00),
(22, 3, 'orderliness_and_program_flow', 5.00),
(23, 3, 'appropriateness_of_venue', 5.00),
(24, 3, 'sound_system_and_hall_preparation', 5.00),
(25, 3, 'restrooms', 5.00),
(26, 3, 'food_and_drinks', 5.00),
(39, 4, 'time_management', 5.00),
(40, 4, 'orderliness_and_program_flow', 5.00),
(41, 4, 'appropriateness_of_venue', 5.00),
(42, 4, 'sound_system_and_hall_preparation', 5.00),
(43, 4, 'restrooms', 5.00),
(44, 4, 'food_and_drinks', 5.00),
(45, 6, 'time_management', 5.00),
(46, 6, 'orderliness_and_program_flow', 5.00),
(47, 6, 'appropriateness_of_venue', 5.00),
(48, 6, 'sound_system_and_hall_preparation', 5.00),
(49, 6, 'restrooms', 5.00),
(50, 6, 'food_and_drinks', 5.00),
(51, 5, 'time_management', 5.00),
(52, 5, 'orderliness_and_program_flow', 5.00),
(53, 5, 'appropriateness_of_venue', 5.00),
(54, 5, 'sound_system_and_hall_preparation', 5.00),
(55, 5, 'restrooms', 5.00),
(56, 5, 'food_and_drinks', 5.00),
(69, 7, 'time_management', 4.99),
(70, 7, 'orderliness_and_program_flow', 5.00),
(71, 7, 'appropriateness_of_venue', 5.00),
(72, 7, 'sound_system_and_hall_preparation', 5.00),
(73, 7, 'restrooms', 5.00),
(74, 7, 'food_and_drinks', 5.00),
(75, 8, 'time_management', 5.00),
(76, 8, 'orderliness_and_program_flow', 0.00),
(77, 8, 'appropriateness_of_venue', 5.00),
(78, 8, 'sound_system_and_hall_preparation', 5.00),
(79, 8, 'restrooms', 5.00),
(80, 8, 'food_and_drinks', 5.00);

-- --------------------------------------------------------

--
-- Table structure for table `form_types`
--

CREATE TABLE `form_types` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `form_types`
--

INSERT INTO `form_types` (`id`, `name`) VALUES
(1, 'In-Service Training Design and Request'),
(2, 'Employees\' Activity Design'),
(3, 'Extension Training Design'),
(4, 'External Training Form');

-- --------------------------------------------------------

--
-- Table structure for table `gpb_items`
--

CREATE TABLE `gpb_items` (
  `id` int(11) NOT NULL,
  `fiscal_year` int(11) NOT NULL,
  `section` enum('client_focused','organization_focused','attributed_program','client','org','attributed') NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `mandate` text DEFAULT NULL,
  `cause` text DEFAULT NULL,
  `objective` text DEFAULT NULL,
  `result` text DEFAULT NULL,
  `ppa` text DEFAULT NULL,
  `mfo` text DEFAULT NULL,
  `activity` text DEFAULT NULL,
  `targets` text DEFAULT NULL,
  `indicators` text DEFAULT NULL,
  `budget` decimal(15,2) DEFAULT 0.00,
  `source` varchar(50) DEFAULT NULL,
  `office` varchar(255) DEFAULT NULL,
  `responsible` varchar(255) DEFAULT NULL,
  `budget_lines` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`budget_lines`)),
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gpb_items`
--

INSERT INTO `gpb_items` (`id`, `fiscal_year`, `section`, `sort_order`, `mandate`, `cause`, `objective`, `result`, `ppa`, `mfo`, `activity`, `targets`, `indicators`, `budget`, `source`, `office`, `responsible`, `budget_lines`, `created_at`, `updated_at`) VALUES
(1, 2026, 'client', 1, 'Republic Act No. 10931, Universal Access to Quality Tertiary Education Act Section 8 on Affirmative Action Program; CHED Memorandum Orders on GAD Mainstreaming in Higher Education Institutions (HEIs)', 'Extraordinary life situations due to disasters, calamities, and socio-cultural discrimination', 'To promote equitable access and participation of both women and men from GIDAs in tertiary education through gender-responsive implementation of the Affirmative Action Agenda.', 'To promote equitable access and participation of both women and men from GIDAs in tertiary education through gender-responsive implementation of the Affirmative Action Agenda.', 'Higher Education Program', 'Higher Education Program', 'Implementation of Affirmative Action Agenda', 'Number of served disadvantaged students - 100% disadvantaged students', 'Number of served disadvantaged students - 100% disadvantaged students', 700000.00, 'GAA', 'OSS', 'OSS', '[{\"id\":\"c1-l1\",\"label\":\"PS Attribution\",\"amount\":500000,\"source\":\"GAA\"},{\"id\":\"c1-l2\",\"label\":\"Supplies and Materials\",\"amount\":200000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(2, 2026, 'client', 2, 'Republic Act No. 10931, Universal Access to Quality Tertiary Education Act Section 8 on Affirmative Action Program; CHED Memorandum Orders on GAD Mainstreaming in Higher Education Institutions (HEIs)', 'High tuition and miscellaneous fees, compounded by socio-cultural expectations for women to prioritize domestic roles over education', 'To promote gender equality in access to tertiary education by eliminating financial barriers for both male and female students.', 'To promote gender equality in access to tertiary education by eliminating financial barriers for both male and female students.', 'Higher Education Program', 'Higher Education Program', 'Provision of free tuition fee under RA 10931 to eligible male and female students of the university.', 'Percentage of qualified students granted free tuition - 100% of qualified students granted free tuition.', 'Percentage of qualified students granted free tuition - 100% of qualified students granted free tuition.', 131100000.00, 'GAA', 'OSS, OUR, UHS', 'OSS, OUR, UHS', '[{\"id\":\"c2-l1\",\"label\":\"Tuition Fee\",\"amount\":131100000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(3, 2026, 'client', 3, 'CHED Memorandum Order No. 01 series 2015', 'Limited activities to increase awareness of men and women students to GAD-related information (1st year students, transferees)', 'To increase the students level of awareness and appreciation on GAD', 'To increase the students level of awareness and appreciation on GAD', 'Higher Education Program', 'Higher Education Program', 'Conduct GAD orientation/ forum/ seminar to BSU 1st year/ transferees students (face to face/ online: 14 colleges)', 'No. of students oriented on GAD - 4,000 students oriented on GAD (F:2750 M:1250)', 'No. of students oriented on GAD - 4,000 students oriented on GAD (F:2750 M:1250)', 453363.26, 'GAA', 'OSS, GAD Office, 3 Campuses (La Trinidad, Bokod & Buguias Campus)', 'OSS, GAD Office, 3 Campuses (La Trinidad, Bokod & Buguias Campus)', '[{\"id\":\"c3-l1\",\"label\":\"Meals and Snack\",\"amount\":318800,\"source\":\"GAA\"},{\"id\":\"c3-l2\",\"label\":\"Supplies and Materials\",\"amount\":10000,\"source\":\"GAA\"},{\"id\":\"c3-l3\",\"label\":\"PS Attribution\",\"amount\":124563.26,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(4, 2026, 'client', 4, 'CHED Memorandum Order No. 01 series 2015', 'Student leaders have limited understanding on GAD in the University', 'To empower student leaders regarding GAD responsive leadership (La Trinidad Campus, Bokod Campus and Buguias Campus)', 'To empower student leaders regarding GAD responsive leadership (La Trinidad Campus, Bokod Campus and Buguias Campus)', 'Higher Education Program', 'Higher Education Program', 'Continuous conduct of GAD responsive leadership training for student', 'No. of training conducted to increase GAD awareness and responsiveness of students leaders - 2 training (Female:200 Male:100) (La Trinidad Campus, Bokod Campus and Buguias Campus)', 'No. of training conducted to increase GAD awareness and responsiveness of students leaders - 2 training (Female:200 Male:100) (La Trinidad Campus, Bokod Campus and Buguias Campus)', 150000.00, 'GAA', 'OSS', 'OSS', '[{\"id\":\"c4-l1\",\"label\":\"Supplies and Materials\",\"amount\":30000,\"source\":\"GAA\"},{\"id\":\"c4-l2\",\"label\":\"Snack\",\"amount\":20000,\"source\":\"GAA\"},{\"id\":\"c4-l3\",\"label\":\"PS Attribution\",\"amount\":100000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(5, 2026, 'client', 5, 'Part VII of CHED Memorandum Order Number 1, series 2015 on Gender-Responsive Research and Extension Program', 'Presence of gender inequality, poverty and GAD-related concerns in the community', 'To sustain GAD-related extension activities delivering technology transfer, Livelihood Program, Technical Assistance, and Advocacy to community partners to help promote gender equality, poverty reduction and sustainable development', 'To sustain GAD-related extension activities delivering technology transfer, Livelihood Program, Technical Assistance, and Advocacy to community partners to help promote gender equality, poverty reduction and sustainable development', 'Extension Services / Research Services / Advance Education Services / Higher Education Services', 'Extension Services / Research Services / Advance Education Services / Higher Education Services', 'Conduct of Extension project/ activities to partner organizational/ communities as component of Gender Responsive Extension Program (GREP) to partner organization/ communities', 'No. of extension activities conducted within the year - 24 Extension program/project/ activities conducted within the year (Female:560 Male:500)', 'No. of extension activities conducted within the year - 24 Extension program/project/ activities conducted within the year (Female:560 Male:500)', 3500000.00, 'GAA', 'Research and Extension, various offices/ colleges in the University/ external campuses', 'Research and Extension, various offices/ colleges in the University/ external campuses', '[{\"id\":\"c5-l1\",\"label\":\"Seminar Package \\/ Meals & Snacks \\/ Fuel for Transportation \\/ Vehicle Rental \\/ Other Professional Services\",\"amount\":2500000,\"source\":\"GAA\"},{\"id\":\"c5-l2\",\"label\":\"PS Attribution\",\"amount\":1000000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(6, 2026, 'client', 6, 'Limited access of PWDs to gender-responsive programs and services / DBM-DSWD Joint Circular No. 2003-01 (at least 1% of budget for senior citizen and PWD programs)', 'Limited access of PWDs to gender-responsive programs and services', 'Improved access of PWDs to gender-responsive, inclusive, and empowering programs and services.', 'Improved access of PWDs to gender-responsive, inclusive, and empowering programs and services.', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Awareness of women PWDs who benefited from the program', 'Number of women PWDs who benefited from the program - F/M. No. of GAD program/project/activity provided for PWD - At least 1 program/project/activity', 'Number of women PWDs who benefited from the program - F/M. No. of GAD program/project/activity provided for PWD - At least 1 program/project/activity', 350000.00, 'GAA', 'HRMO, OSS', 'HRMO, OSS', '[{\"id\":\"c6-l1\",\"label\":\"PS Attribution\",\"amount\":100000,\"source\":\"GAA\"},{\"id\":\"c6-l2\",\"label\":\"Supplies & Materials \\/ Meals & Snacks\",\"amount\":250000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(7, 2026, 'client', 7, 'Lack of senior citizens access to gender-responsive programs and services / DBM-DSWD Joint Circular No. 2003-01 (at least 1% of budget for senior citizen and PWD programs)', 'Absence of sustainable and gender-responsive university programs for senior citizens.', 'Improved access of senior citizens to gender-responsive, inclusive, and empowering programs and services', 'Improved access of senior citizens to gender-responsive, inclusive, and empowering programs and services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Program: BSU Kalinga for women Senior Citizens', 'Number of Programs provided for Senior Citizens - At least 1 program for BSU. Number of women senior citizens who benefited from the program - F21 M23', 'Number of Programs provided for Senior Citizens - At least 1 program for BSU. Number of women senior citizens who benefited from the program - F21 M23', 250000.00, 'GAA', 'GAD Office, Colleges, External Campuses', 'GAD Office, Colleges, External Campuses', '[{\"id\":\"c7-l1\",\"label\":\"Supplies & Materials \\/ Meals & Snacks\",\"amount\":250000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(8, 2026, 'client', 8, 'Low number of women\'s participation in sports / MCW-IRR Section 14', 'Minimal attendance of female students to competitive and non-competitive sports', 'To increase female students level of participation and awareness on Gender in Sports', 'To increase female students level of participation and awareness on Gender in Sports', 'Higher Education Program', 'Higher Education Program', 'Participate in sports activities targeted for female students', 'No. of sports activities supported through allocation of budget for sports and socio-cultural activities/ E-sports (i.e. Annual Women\'s Martial Arts Festival) - 2 sports activities (Female:20)', 'No. of sports activities supported through allocation of budget for sports and socio-cultural activities/ E-sports (i.e. Annual Women\'s Martial Arts Festival) - 2 sports activities (Female:20)', 160000.00, 'GAA', 'CHK', 'CHK', '[{\"id\":\"c8-l1\",\"label\":\"Registration & Travelling Expenses\",\"amount\":80000,\"source\":\"GAA\"},{\"id\":\"c8-l2\",\"label\":\"Meals and Snacks\",\"amount\":60000,\"source\":\"GAA\"},{\"id\":\"c8-l3\",\"label\":\"PS Attribution\",\"amount\":20000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(9, 2026, 'org', 9, 'Programs on Awards and Incentives for Service Excellence (PRAISE, CSC Res. No. 010112 and CSC MC No.1, s.2001); GFPS Memo Circular No. 2011-01; BOR Res. No. 2316, s.2014', 'Low recognition/appreciation on the Gender Mainstreaming in BSU', 'Strengthen Gender Mainstreaming through recognition of GAD implementation in the University', 'Strengthen Gender Mainstreaming through recognition of GAD implementation in the University', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Provide recognition and award to GAD implementer and other GAD-related award (GAD implementer for students and employees, GAD Advocate Award)', 'No. of award will be provided through BSU-PRAISE - At least 1 GAD Advocate award will be provided through BSU-PRAISE', 'No. of award will be provided through BSU-PRAISE - At least 1 GAD Advocate award will be provided through BSU-PRAISE', 205000.00, 'GAA', 'HRDO, HRMO, BSU-PRAISE Committee, GAD Office', 'HRDO, HRMO, BSU-PRAISE Committee, GAD Office', '[{\"id\":\"o9-l1\",\"label\":\"Incentive GAD Advocate Award\",\"amount\":5000,\"source\":\"GAA\"},{\"id\":\"o9-l2\",\"label\":\"PS Attribution\",\"amount\":200000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(10, 2026, 'org', 10, 'Limited application of GAD Mainstreaming (GM) in Instruction, Research, Extension and Production / Magna Carta of Women (RA 9710)', 'Low awareness among personnel in the University about GAD mainstreaming', 'To enhance GAD mainstreaming in Administration, Academic, Research and Extension, Production', 'To enhance GAD mainstreaming in Administration, Academic, Research and Extension, Production', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Conduct GAD related Gender Mainstreaming capability building and competency acquisition', 'No. of training/workshop/seminars conducted - 25 training/workshop/seminars/Learning and Development (F:1500 M:1000)', 'No. of training/workshop/seminars conducted - 25 training/workshop/seminars/Learning and Development (F:1500 M:1000)', 4000000.00, 'GAA', 'GAD Office, HRDO, Research and Extension, OQAA, All Colleges with External Campuses', 'GAD Office, HRDO, Research and Extension, OQAA, All Colleges with External Campuses', '[{\"id\":\"o10-l1\",\"label\":\"Seminar Package \\/ Meals & Snacks \\/ Fuel for Transportation \\/ Vehicle Rental \\/ Professional Services (La Trinidad, Bokod, Buguias)\",\"amount\":3500000,\"source\":\"GAA\"},{\"id\":\"o10-l2\",\"label\":\"PS Attribution\",\"amount\":500000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(11, 2026, 'org', 11, 'Magna Carta of Women IRR Section 37 - Gender Mainstreaming as a Strategy for Implementing the Magna Carta of Women', 'Productivity of employees affected due to filial obligations, affecting promotion of women to higher positions or from participating in capability enhancement sessions', 'Inadequate support services to personnel and students with children', 'Inadequate support services to personnel and students with children', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Operationalize of BSU College of Nursing Reproductive Health Care Center', 'No. of maintained Reproductive Health Care Center - 1 maintained BSU CN Reproductive Health Care Center', 'No. of maintained Reproductive Health Care Center - 1 maintained BSU CN Reproductive Health Care Center', 120000.00, 'GAA', 'College of Nursing', 'College of Nursing', '[{\"id\":\"o11-l1\",\"label\":\"Supplies and Materials\",\"amount\":20000,\"source\":\"GAA\"},{\"id\":\"o11-l2\",\"label\":\"PS Attribution\",\"amount\":100000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(12, 2026, 'org', 12, 'Executive Order No. 340 s.1997 - Directing National Government Agencies and GOCCs to provide Day Care Services for their Employees\' Children under five years of age', 'Problems of parents and students related to child care', 'Ensure opportunities of personnel and students to have access on agency care services to children to avoid absenteeism', 'Ensure opportunities of personnel and students to have access on agency care services to children to avoid absenteeism', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Maintenance of Child Minding Center for working parents in ensuring that they have a safe place to leave their child while they are at their work places', 'No. of established child minding center - Fully maintained new established and existing child minding centers at BSU La Trinidad, Bokod Campus and Buguias Campus', 'No. of established child minding center - Fully maintained new established and existing child minding centers at BSU La Trinidad, Bokod Campus and Buguias Campus', 230000.00, 'GAA', 'GAD Office, External Campuses', 'GAD Office, External Campuses', '[{\"id\":\"o12-l1\",\"label\":\"Supplies and Materials\",\"amount\":130000,\"source\":\"GAA\"},{\"id\":\"o12-l2\",\"label\":\"PS Attribution\",\"amount\":100000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(13, 2026, 'org', 13, 'Magna Carta of Women (RA 9710)', 'Low integration of gender mainstreaming of BSU', 'To strengthen the GAD integration in the operations of BSU', 'To strengthen the GAD integration in the operations of BSU', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Create a Monitoring Team to conduct monitoring and evaluation of the utilization/outcome of GAD PAPs and ensure effectiveness of the GAD PAPs', 'No. of monitoring and assessment meetings with reports conducted - 4 monitoring and assessment meetings with reports conducted', 'No. of monitoring and assessment meetings with reports conducted - 4 monitoring and assessment meetings with reports conducted', 330000.00, 'GAA', 'GAD Office', 'GAD Office', '[{\"id\":\"o13-l1\",\"label\":\"Supplies and Materials\",\"amount\":10000,\"source\":\"GAA\"},{\"id\":\"o13-l2\",\"label\":\"PS Attribution\",\"amount\":320000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(14, 2026, 'org', 14, 'Magna Carta of Women IRR Section 37-C - Creation and/or Strengthening of the GAD Focal Points (GFPs)', 'Low level of capacity of GFPS to develop and implement GAD programs and activities due to new members', 'Capacitated GFPS members in order to implement GAD PAP\'s and advance GAD Mainstreaming (GM) in the University', 'Capacitated GFPS members in order to implement GAD PAP\'s and advance GAD Mainstreaming (GM) in the University', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'For GFPS/Secretariat: GMEF/HGDG/GPB/GAD Agenda/GAD Deepening Session and TOT among other related trainings and capacity building activities (Regional/National GAD-related trainings/seminars/forum/workshop)', 'No. training/seminars/workshop attendance for each GFPS-member on GAD related updates and mandates - At least 1 each (Female:31, Male:15)', 'No. training/seminars/workshop attendance for each GFPS-member on GAD related updates and mandates - At least 1 each (Female:31, Male:15)', 896000.00, 'GAA', 'GAD Office', 'GAD Office', '[{\"id\":\"o14-l1\",\"label\":\"GFPS TWG PAPs\",\"amount\":396000,\"source\":\"GAA\"},{\"id\":\"o14-l2\",\"label\":\"PS Attribution\",\"amount\":500000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(15, 2026, 'org', 15, 'Section 37-C2 Rule VI of the Magna Carta of Women\'s IRR on duties and function of the GAD Focal Point System / Magna Carta of Women (RA 9710)', 'Compliance to provisions regarding regular monitoring of gender mainstreaming efforts', 'To ensure operations of GAD Office as well as monitor and evaluate GM efforts of the University', 'To ensure operations of GAD Office as well as monitor and evaluate GM efforts of the University', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Regular coordination and meetings of GAD-GFPS (Execom, GFPS-TWG members and external campus TWG members) and emergency meeting when necessary', 'No. of reports on regular meetings per campus - At least 6 reports available at year end, plus RGADC quarterly meeting/s', 'No. of reports on regular meetings per campus - At least 6 reports available at year end, plus RGADC quarterly meeting/s', 211720.00, 'GAA', 'GAD Office', 'GAD Office', '[{\"id\":\"o15-l1\",\"label\":\"Meals & Snack\",\"amount\":111720,\"source\":\"GAA\"},{\"id\":\"o15-l2\",\"label\":\"PS Attribution\",\"amount\":100000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(16, 2026, 'org', 16, 'Duties and function of the GAD Focal Point System / CHED Memo 2015-1', 'No plantilla personnel assigned to plan, implement and monitor GAD PAPs on a full-time basis', 'To ensure operations of GAD Office as well as monitor and evaluate GM efforts of the University', 'To ensure operations of GAD Office as well as monitor and evaluate GM efforts of the University', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Engage support staff to assist in the implementation of GFPS PPA\'s and Gender Mainstreaming in the university through rehiring of GAD staff and Student Assistant', 'Salary of GAD Staff: Casual. No. of rehired personnel - At least 2 staff renewed/rehired (Casual) and at least 3 Student Assistant/SPES per semester', 'Salary of GAD Staff: Casual. No. of rehired personnel - At least 2 staff renewed/rehired (Casual) and at least 3 Student Assistant/SPES per semester', 550000.00, 'GAA', 'GAD Office', 'GAD Office', '[{\"id\":\"o16-l1\",\"label\":\"Salary of GAD Staff and SPES \\/ Student Assistant\",\"amount\":550000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(17, 2026, 'org', 17, 'Low level of Awareness on Gender Mainstreaming (GM) among newly hired personnel / Magna Carta of Women (RA 9710), CHED Memo 2015-1', 'Lack of regular orientation and refresher training on gender sensitivity and GAD mandates', 'To enhance awareness and understanding of gender concepts, GAD mandates, and gender-responsive work practices among newly hired and current personnel', 'To enhance awareness and understanding of gender concepts, GAD mandates, and gender-responsive work practices among newly hired and current personnel', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Conduct Gender Sensitivity Training (GST) for newly hired and current personnel (continuing activity)', 'No. of trainings conducted - 1 training for at least 100% of newly hired personnel and 3 refresher trainings for current personnel', 'No. of trainings conducted - 1 training for at least 100% of newly hired personnel and 3 refresher trainings for current personnel', 421728.32, 'GAA', 'GAD Office', 'GAD Office', '[{\"id\":\"o17-l1\",\"label\":\"Meals & Snack\",\"amount\":167200,\"source\":\"GAA\"},{\"id\":\"o17-l2\",\"label\":\"Token\",\"amount\":4000,\"source\":\"GAA\"},{\"id\":\"o17-l3\",\"label\":\"Professional Fee\",\"amount\":144528.32,\"source\":\"GAA\"},{\"id\":\"o17-l4\",\"label\":\"Supplies and Materials\",\"amount\":6000,\"source\":\"GAA\"},{\"id\":\"o17-l5\",\"label\":\"PS Attribution\",\"amount\":100000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(18, 2026, 'org', 18, 'Part V, Rule II, Section 4 of CHED Memorandum Order No. 1 Series of 2015', 'Limited number of GAD library and related learning materials across various disciplines', 'To increase the provision of adequate and accessible library and related learning materials across various disciplines and educational levels', 'To increase the provision of adequate and accessible library and related learning materials across various disciplines and educational levels', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Provision of knowledge products (books, magazines, multi-media) for adequate and accessible library and related learning materials in support to gender-responsive Curriculum Programs', 'No. of procured library and learning materials - 200 books', 'No. of procured library and learning materials - 200 books', 2600000.00, 'GAA', 'ULIS', 'ULIS', '[{\"id\":\"o18-l1\",\"label\":\"Books and Instructional Materials\",\"amount\":2500000,\"source\":\"GAA\"},{\"id\":\"o18-l2\",\"label\":\"PS Attribution\",\"amount\":100000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(19, 2026, 'org', 19, 'Development and Dissemination of Gender and Development (GAD) Information, Education, and Communication (IEC) Materials', 'Presence of Gender Based Violence (GBV) issues/reports/cases in the university', 'Institutionalize GAD mechanisms in the University and sustain awareness campaigns on sexual harassment and gender-based violence', 'Institutionalize GAD mechanisms in the University and sustain awareness campaigns on sexual harassment and gender-based violence', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Development and Dissemination of Gender and Development (GAD) Information, Education, and Communication (IEC) Materials', 'Official Publication with GAD articles/pictures. Maintained GAD Bulletin board - At least 8. Sector-specific downloadable knowledge products - At least 2', 'Official Publication with GAD articles/pictures. Maintained GAD Bulletin board - At least 8. Sector-specific downloadable knowledge products - At least 2', 296000.00, 'GAA', 'UPAO, GAD Office', 'UPAO, GAD Office', '[{\"id\":\"o19-l1\",\"label\":\"Shamag\",\"amount\":96000,\"source\":\"GAA\"},{\"id\":\"o19-l2\",\"label\":\"PS Attribution\",\"amount\":100000,\"source\":\"GAA\"},{\"id\":\"o19-l3\",\"label\":\"Supplies and Materials\",\"amount\":100000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(20, 2026, 'org', 20, 'Institutionalizing GAD database and Sex-Disaggregated Database / Magna Carta of Women (RA 9710) Section 36 on Sex-Disaggregated Database', 'Minimal awareness and appreciation on the relevance of the centralized Sex-Disaggregated database', 'To establish a centralized GAD-related database of the University', 'To establish a centralized GAD-related database of the University', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Updating of Sex-Disaggregated Data (SDD) and other data related to personnel/students/clients for gender analysis and report preparation', '1 well-organized and maintained GAD database. Establishment of GAD-Database system per college/unit.', '1 well-organized and maintained GAD database. Establishment of GAD-Database system per college/unit.', 1100000.00, 'GAA', 'ICT, GAD Office', 'ICT, GAD Office', '[{\"id\":\"o20-l1\",\"label\":\"PS Attribution\",\"amount\":100000,\"source\":\"GAA\"},{\"id\":\"o20-l2\",\"label\":\"Maintenance of SDD \\/ Internet connection\",\"amount\":1000000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(21, 2026, 'org', 21, 'Magna Carta for Women, Chapter IV Section 10 and RA 10121, Section 2 & 9', 'Limited resources of the DSWD and LGU to provide for students who are transient residents and limited appreciation on women\'s role in nation building among employees and students, especially new ones', 'To ensure that disaster assistance provided to distressed students are gender-responsive', 'To ensure that disaster assistance provided to distressed students are gender-responsive', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Provision of gender-responsive services to employees and students who experienced crisis/disaster (e.g. distribution of hygiene kits for both women and men)', 'No. of pax of the most affected employees/students during crises - 1,000', 'No. of pax of the most affected employees/students during crises - 1,000', 210000.00, 'GAA', 'GAD Office, HDRO, NSTP, various offices/all colleges in the University', 'GAD Office, HDRO, NSTP, various offices/all colleges in the University', '[{\"id\":\"o21-l1\",\"label\":\"Crisis pack (\\u20b1200\\/pack x 1,000 pax)\",\"amount\":200000,\"source\":\"GAA\"},{\"id\":\"o21-l2\",\"label\":\"PS TWG Members\",\"amount\":10000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(22, 2026, 'org', 22, 'RA 9710 Sec.18 Special Leave for Women; RA 8187 Paternity Leave; RA 8972 Sec.8 Solo Parents Welfare Act; RA 9262 Sec.43 Anti-VAWC Act', 'Employees may require special leaves due to parental obligations, health concerns and other circumstances', 'Enhanced support services for employees in need of special leaves', 'Enhanced support services for employees in need of special leaves', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Provision of gender leaves and conduct of Seminar on Gender Related Leaves for Newly Hired Employees', 'No. of Maternity, Paternity, Solo parent, gynecological, VAWC leaves availed and 1 Seminar conducted (M:20 F:50) - 100%', 'No. of Maternity, Paternity, Solo parent, gynecological, VAWC leaves availed and 1 Seminar conducted (M:20 F:50) - 100%', 1000000.00, 'GAA', 'HRMO, CBOO, various offices/colleges in the University', 'HRMO, CBOO, various offices/colleges in the University', '[{\"id\":\"o22-l1\",\"label\":\"PS Attribution\",\"amount\":1000000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(23, 2026, 'org', 23, 'Proclamation 227 (Women\'s Role in History Month) and Proclamation 1172, s.2006 (18-Day Campaign to End VAW)', 'The need to highlight women\'s rights, their role in nation building, and to provide a platform against VAW, gender-based violence, Safe Spaces Act (RA 11313)', 'To strengthen awareness of BSU students/employees on women\'s rights and their role in national development and nation building', 'To strengthen awareness of BSU students/employees on women\'s rights and their role in national development and nation building', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Participation to 18-Day Campaign to end VAW and Women\'s Month Celebration/activities and programs organized by PCW and other agency/ies', 'No. of activities conducted per campus - At least 1 per campus', 'No. of activities conducted per campus - At least 1 per campus', 450000.00, 'GAA', 'GAD Office, various offices/colleges in the University/external campus', 'GAD Office, various offices/colleges in the University/external campus', '[{\"id\":\"o23-l1\",\"label\":\"Activities \\/ Programs\",\"amount\":250000,\"source\":\"GAA\"},{\"id\":\"o23-l2\",\"label\":\"PS Attribution\",\"amount\":200000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(24, 2026, 'org', 24, 'Productivity of employees affected due to filial obligations / Magna Carta of Women IRR Section 37', 'Inadequate support services for personnel/students with young children and breastfeeding mothers (RA 10028)', 'Inadequate support services to personnel and students with children', 'Inadequate support services to personnel and students with children', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Establishment/maintenance of breastfeeding station established in the preceding years', 'Fully maintained Lactation rooms - 100% at BSU La Trinidad, Bokod and Buguias Campus', 'Fully maintained Lactation rooms - 100% at BSU La Trinidad, Bokod and Buguias Campus', 220000.00, 'GAA', 'GAD Office, External Campuses', 'GAD Office, External Campuses', '[{\"id\":\"o24-l1\",\"label\":\"Supplies and Materials\",\"amount\":50000,\"source\":\"GAA\"},{\"id\":\"o24-l2\",\"label\":\"PS Attribution\",\"amount\":170000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(25, 2026, 'org', 25, 'RA 9710, PCW-NEDA-DBM Joint Circular 2012-01, CSC MC No.12 s.2005', 'Lack of regular gender-related capacity-building activities and insufficient integration of gender sensitivity in employee development programs', 'To enhance the gender awareness and sensitivity of BSU employees, enabling them to recognize and eliminate gender bias and stereotyping, and to foster a gender-responsive and equitable workplace', 'To enhance the gender awareness and sensitivity of BSU employees, enabling them to recognize and eliminate gender bias and stereotyping, and to foster a gender-responsive and equitable workplace', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Conduct of gender sensitivity orientations for BSU Personnel (continuing activity)', 'No. of trainings conducted for BSU personnel - At least 3', 'No. of trainings conducted for BSU personnel - At least 3', 253796.24, 'GAA', 'GAD Office', 'GAD Office', '[{\"id\":\"o25-l1\",\"label\":\"Supplies and Materials\",\"amount\":10000,\"source\":\"GAA\"},{\"id\":\"o25-l2\",\"label\":\"Meals & Snack \\/ Professional Fee\",\"amount\":233796.24,\"source\":\"GAA\"},{\"id\":\"o25-l3\",\"label\":\"PS\",\"amount\":10000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(26, 2026, 'org', 26, 'Establishment of Gender-Responsive Curricular Programs / Part V of CMO 01, s.2015', 'Limited subject for GAD Integration of Gender-Responsive Instruction and Curriculum Development', 'Integration of gender mainstreaming in curriculum/subjects in all levels', 'Integration of gender mainstreaming in curriculum/subjects in all levels', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Preparation of syllabi and classroom teaching integrating gender perspective', 'Number of faculty members integrating gender perspective in the syllabus - 567 permanent and 125 COS females/males', 'Number of faculty members integrating gender perspective in the syllabus - 567 permanent and 125 COS females/males', 58294972.71, 'GAA', 'GAD Office, GFPS-TWG members, all colleges', 'GAD Office, GFPS-TWG members, all colleges', '[{\"id\":\"o26-l1\",\"label\":\"PS - 567 Teaching employees and COS\",\"amount\":51294972.71,\"source\":\"GAA\"},{\"id\":\"o26-l2\",\"label\":\"Teaching Overload\",\"amount\":7000000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(27, 2026, 'org', 27, 'Section 37-C2 of the Magna Carta of Women (MCW) IRR - mandates establishment of a GAD Focal Point System (GFPS)', 'Sustained operations of the existing GAD Office - administrative, logistical, and financial support for day-to-day functioning; maintenance of GAD database; coordination of GFPS and GAD-related activities across colleges and units', 'To ensure the continuous and efficient operation of a functional, gender-responsive GAD Office that leads, monitors, and evaluates GAD mainstreaming efforts in the university.', 'To ensure the continuous and efficient operation of a functional, gender-responsive GAD Office that leads, monitors, and evaluates GAD mainstreaming efforts in the university.', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Research Services / Extension Services / Advance Education Services / Higher Education Services', 'Sustaining Gender Mainstreaming and Institutional Support in the University', 'Fully maintained GAD Office - 100%', 'Fully maintained GAD Office - 100%', 8052370.09, 'GAA', 'GAD Office', 'GAD Office', '[{\"id\":\"o27-l1\",\"label\":\"PS on Procurement Process\",\"amount\":30992.09,\"source\":\"GAA\"},{\"id\":\"o27-l2\",\"label\":\"PS Attribution: Execom & TWG members\",\"amount\":7219424,\"source\":\"GAA\"},{\"id\":\"o27-l3\",\"label\":\"Supplies, Equipment and Materials\",\"amount\":300000,\"source\":\"GAA\"},{\"id\":\"o27-l4\",\"label\":\"PS of GAD Director (50%)\",\"amount\":501954,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(28, 2026, 'attributed', 28, '', '', '', '', '', '', 'Transportation Equipment Outlay', '', '', 12285000.00, 'GAA', 'TASU, PMO, SPMO', 'TASU, PMO, SPMO', '[{\"id\":\"a28-l1\",\"label\":\"Transportation Equipment Outlay\",\"amount\":12285000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(29, 2026, 'attributed', 29, '', '', '', '', '', '', 'Repair and Maintenance of Office Building and other Structures', '', '', 6402000.00, 'GAA', 'PU, PMO, SPMO', 'PU, PMO, SPMO', '[{\"id\":\"a29-l1\",\"label\":\"Repair and Maintenance - Office Building and other Structures\",\"amount\":6402000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(30, 2026, 'attributed', 30, '', '', '', '', '', '', 'Bamboo Industry Development for Environment Conservation and Countryside', '', '', 3750000.00, 'GAA', 'College of Forestry', 'College of Forestry', '[{\"id\":\"a30-l1\",\"label\":\"Bamboo Industry Development for Environment Conservation and Countryside\",\"amount\":3750000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30'),
(31, 2026, 'attributed', 31, '', '', '', '', '', '', 'Benguet State University Student Information and Accounting System (SIAS)', '', '', 5000000.00, 'GAA', 'ICT, PMO', 'ICT, PMO', '[{\"id\":\"a31-l1\",\"label\":\"Student Information and Accounting System (SIAS)\",\"amount\":5000000,\"source\":\"GAA\"}]', '2026-07-13 06:31:30', '2026-07-13 06:31:30');

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `id` int(11) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'public',
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `holidays`
--

INSERT INTO `holidays` (`id`, `date`, `name`, `type`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '2026-01-01', 'New Year\'s Day', 'public', NULL, '2026-08-26 03:27:24', '2026-08-26 03:27:24'),
(2, '2026-02-17', 'Chinese New Year', 'public', NULL, '2026-08-26 03:27:24', '2026-08-26 03:27:24'),
(3, '2026-04-02', 'Maundy Thursday', 'public', NULL, '2026-08-26 03:27:24', '2026-08-26 03:27:24'),
(4, '2026-04-03', 'Good Friday', 'public', NULL, '2026-08-26 03:27:24', '2026-08-26 03:27:24'),
(5, '2026-04-04', 'Holy Saturday', 'public', NULL, '2026-08-26 03:27:24', '2026-08-26 03:27:24'),
(6, '2026-04-09', 'Day of Valor', 'public', NULL, '2026-08-26 03:27:24', '2026-08-26 03:27:24'),
(7, '2026-05-01', 'Labour Day', 'public', NULL, '2026-08-26 03:27:24', '2026-08-26 03:27:24'),
(8, '2026-06-12', 'Independence Day', 'public', NULL, '2026-08-26 03:27:24', '2026-08-26 03:27:24'),
(9, '2026-08-21', 'Ninoy Aquino Day', 'public', NULL, '2026-08-26 03:27:24', '2026-08-26 03:27:24'),
(10, '2026-08-31', 'National Heroes Day', 'public', NULL, '2026-08-26 03:27:24', '2026-08-26 03:27:24'),
(11, '2026-10-31', 'All Saints\' Day Eve', 'public', NULL, '2026-08-26 03:27:24', '2026-08-26 03:27:24'),
(12, '2026-11-01', 'All Saints\' Day', 'public', NULL, '2026-08-26 03:27:24', '2026-08-26 03:27:24'),
(13, '2026-11-30', 'Bonifacio Day', 'public', NULL, '2026-08-26 03:27:24', '2026-08-26 03:27:24'),
(14, '2026-12-08', 'Feast of the Immaculate Conception of Mary', 'public', NULL, '2026-08-26 03:27:24', '2026-08-26 03:27:24'),
(15, '2026-12-24', 'Christmas Eve', 'public', NULL, '2026-08-26 03:27:24', '2026-08-26 03:27:24'),
(16, '2026-12-25', 'Christmas Day', 'public', NULL, '2026-08-26 03:27:24', '2026-08-26 03:27:24'),
(17, '2026-12-30', 'Rizal Day', 'public', NULL, '2026-08-26 03:27:24', '2026-08-26 03:27:24'),
(18, '2026-12-31', 'Last Day of The Year', 'public', NULL, '2026-08-26 03:27:24', '2026-08-26 03:27:24');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `recipient_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` int(11) UNSIGNED DEFAULT NULL,
  `message_text` text NOT NULL,
  `document_type` varchar(100) DEFAULT NULL,
  `document_id` text DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `deleted_by_sender_at` datetime DEFAULT NULL,
  `deleted_by_recipient_at` datetime DEFAULT NULL,
  `is_announcement` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `recipient_id`, `parent_id`, `message_text`, `document_type`, `document_id`, `is_read`, `created_at`, `deleted_by_sender_at`, `deleted_by_recipient_at`, `is_announcement`) VALUES
(1, 2, 1, NULL, 'test', NULL, NULL, 0, '2026-10-10 09:05:23', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-06-19-022849', 'App\\Database\\Migrations\\CreateMessagesTable', 'default', 'App', 1781836185, 1),
(2, '2026-06-19-033911', 'App\\Database\\Migrations\\AlterMessagesDocumentId', 'default', 'App', 1781840369, 2),
(3, '2026-06-19-063936', 'App\\Database\\Migrations\\AddThreadAndTrashToMessages', 'default', 'App', 1781851202, 3),
(4, '2026-06-19-082034', 'App\\Database\\Migrations\\AddDeletedAtToDocuments', 'default', 'App', 1781857260, 4),
(5, '2026-06-23-025857', 'App\\Database\\Migrations\\CreateActivityLogsTable', 'default', 'App', 1782183569, 5),
(6, '2026-06-24-000000', 'App\\Database\\Migrations\\AddLastLoginToUsers', 'default', 'App', 1782263806, 6),
(7, '2026-06-24-000001', 'App\\Database\\Migrations\\AddIsAnnouncementToMessages', 'default', 'App', 1782264591, 7),
(8, '2026-06-26-014303', 'App\\Database\\Migrations\\DropTitleFromMessages', 'default', 'App', 1782438211, 8),
(9, '2026-06-28-000000', 'App\\Database\\Migrations\\AddBudgetTrackingTables', 'default', 'App', 1782635571, 9),
(10, '2026-06-28-120229', 'App\\Database\\Migrations\\AddDynamicFormTables', 'default', 'App', 1782649887, 10),
(11, '2026-06-28-124500', 'App\\Database\\Migrations\\AlterGadMandatesAndIssues', 'default', 'App', 1782650786, 11),
(12, '2026-07-06-140440', 'App\\Database\\Migrations\\ConsolidateUserProfiles', 'default', 'App', 1783346776, 12),
(13, '2026-07-06-224628', 'App\\Database\\Migrations\\ImplementSoftDeletes', 'default', 'App', 1783378166, 13),
(14, '2026-07-07-065600', 'App\\Database\\Migrations\\NormalizeBudgetItems', 'default', 'App', 1783378659, 14),
(15, '2026-07-06-230544', 'App\\Database\\Migrations\\NormalizeEvaluationResults', 'default', 'App', 1783379201, 15),
(16, '2026-07-06-231227', 'App\\Database\\Migrations\\NormalizeGpbBudgetBreakdown', 'default', 'App', 1783379574, 16),
(17, '2026-07-06-232214', 'App\\Database\\Migrations\\DropGpbBudgetTriggers', 'default', 'App', 1783380163, 17),
(18, '2026-07-06-233134', 'App\\Database\\Migrations\\DropLegacyMandateTable', 'default', 'App', 1783380715, 18),
(19, '2026-07-08-025213', 'App\\Database\\Migrations\\AddModificationFieldsToActivityDesign', 'default', 'App', 1783479262, 19),
(20, '2026-07-13-070642', 'App\\Database\\Migrations\\CreateGpbItemsTable', 'default', 'App', 1784032286, 20),
(21, '2026-07-13-124813', 'App\\Database\\Migrations\\AddArchivedAtToDocuments', 'default', 'App', 1784032286, 20),
(22, '2026-07-14-122738', 'App\\Database\\Migrations\\CreateBudgetItemMandateAllocationsTable', 'default', 'App', 1784032286, 20),
(23, '2026-07-17-001059', 'App\\Database\\Migrations\\CreateArchivedAnnualReportsTable', 'default', 'App', 1784247144, 21),
(24, '2026-07-17-031815', 'App\\Database\\Migrations\\AddIsInsideBsuToVenuesAndSubmissions', 'default', 'App', 1784258396, 22),
(25, '2026-07-27-021925', 'App\\Database\\Migrations\\AddGpbBudgetLineIdToAllocations', 'default', 'App', 1785118785, 23),
(26, '2026-08-11-014400', 'App\\Database\\Migrations\\ContactInquiries', 'default', 'App', 1786412667, 24),
(27, '2026-08-17-053807', 'App\\Database\\Migrations\\CreateNotificationsTable', 'default', 'App', 1786945196, 25),
(28, '2026-08-26-014222', 'App\\Database\\Migrations\\CreateHolidaysTable', 'default', 'App', 1787709151, 26),
(29, '2026-08-26-021121', 'App\\Database\\Migrations\\CreateActivitySchedulesTable', 'default', 'App', 1787710415, 27),
(30, '2026-08-26-042227', 'App\\Database\\Migrations\\AddMealsToActivitySchedules', 'default', 'App', 1787718172, 28),
(32, '2026-08-27-070800', 'App\\Database\\Migrations\\AddScheduleTypeToAccomplishmentReport', 'default', 'App', 1787814897, 29),
(33, '2026-08-27-070859', 'App\\Database\\Migrations\\CreateAccomplishmentSchedulesTable', 'default', 'App', 1787814897, 29),
(34, '2026-08-30-210000', 'App\\Database\\Migrations\\CreateNewsIecTable', 'default', 'App', 1788095560, 30),
(35, '2026-09-21-235646', 'App\\Database\\Migrations\\CreateActivityDesignVenuesTable', 'default', 'App', 1790059325, 31),
(36, '2026-09-21-235707', 'App\\Database\\Migrations\\AddVenueIdToActivityBudgetItems', 'default', 'App', 1790059325, 31),
(37, '2026-09-23-053133', 'App\\Database\\Migrations\\AddRevisionModificationCount', 'default', 'App', 1790141795, 32),
(38, '2026-09-24-052749', 'App\\Database\\Migrations\\AddAdvancedFieldsToActivityBudgetItems', 'default', 'App', 1790227691, 33);

-- --------------------------------------------------------

--
-- Table structure for table `news_iec`
--

CREATE TABLE `news_iec` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` text DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `category` enum('News','IEC','Announcement') NOT NULL DEFAULT 'News',
  `published_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news_iec`
--

INSERT INTO `news_iec` (`id`, `title`, `description`, `image_path`, `tags`, `category`, `published_by`, `created_at`, `updated_at`) VALUES
(1, 'fbdfb', 'fbdfbdf', '[\"1791281521_ba6d72e9178ff142f20e.png\"]', '', 'IEC', 1, '2026-10-06 10:12:01', '2026-10-06 10:12:01'),
(2, 'test', 'asa', NULL, '', 'News', 1, '2026-10-10 09:02:53', '2026-10-10 09:02:53');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `link`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 47, 'Activity Design Submitted', 'Your Activity Design \"test 1\" has been successfully submitted and is pending review.', 'info', '/staff/ad-list', 1, '2026-09-26 06:01:09', '2026-09-30 03:31:57'),
(2, 1, 'New Activity Design Submitted', 'A new Activity Design \"test 1\" has been submitted and is pending review.', 'info', '/admin/ad-list', 0, '2026-09-26 06:01:16', '2026-09-26 06:01:16'),
(3, 47, 'New Activity Design Submitted', 'A new Activity Design \"test 1\" has been submitted and is pending review.', 'info', '/staff/ad-list', 1, '2026-09-26 06:01:19', '2026-09-30 03:31:57'),
(4, 47, 'Revision Required', 'Your Activity Design \"test 1\" requires revision. Remarks: sdcsdv', 'warning', '/staff/ad-list', 1, '2026-09-26 06:06:27', '2026-09-30 03:31:57'),
(5, 1, 'Activity Design Resubmitted', 'Activity Design \"test 1\" has been resubmitted and is pending review.', 'info', '/admin/ad-list', 0, '2026-09-26 06:08:00', '2026-09-26 06:08:00'),
(6, 47, 'Activity Design Resubmitted', 'Activity Design \"test 1\" has been resubmitted and is pending review.', 'info', '/staff/ad-list', 1, '2026-09-26 06:08:04', '2026-09-30 03:31:57'),
(7, 2, 'Activity Design Submitted', 'Your Activity Design \"Test 2\" has been successfully submitted and is pending review.', 'info', '/college/submitted-list', 0, '2026-09-26 06:11:31', '2026-09-26 06:11:31'),
(8, 1, 'New Activity Design Submitted', 'A new Activity Design \"Test 2\" has been submitted and is pending review.', 'info', '/admin/ad-list', 1, '2026-09-26 06:11:35', '2026-09-26 06:12:13'),
(9, 47, 'New Activity Design Submitted', 'A new Activity Design \"Test 2\" has been submitted and is pending review.', 'info', '/staff/ad-list', 1, '2026-09-26 06:11:38', '2026-09-30 03:31:57'),
(10, 2, 'Revision Required', 'Your Activity Design \"Test 2\" requires revision. Remarks: cv dfvd', 'warning', '/college/submitted-list', 0, '2026-09-26 06:12:28', '2026-09-26 06:12:28'),
(11, 47, 'Activity Design Approved', 'Your Activity Design \"test 1\" has been approved.', 'success', '/staff/ad-list', 1, '2026-09-26 06:13:31', '2026-09-30 03:31:57'),
(12, 47, 'Accomplishment Report Submitted', 'Your Accomplishment Report \"test 1\" has been successfully submitted and is pending review.', 'info', '/staff/ar-list', 1, '2026-09-26 07:02:41', '2026-09-30 03:31:57'),
(13, 1, 'New Accomplishment Report Submitted', 'A new Accomplishment Report \"test 1\" has been submitted and is pending review.', 'info', '/admin/ar-list', 0, '2026-09-26 07:02:46', '2026-09-26 07:02:46'),
(14, 47, 'New Accomplishment Report Submitted', 'A new Accomplishment Report \"test 1\" has been submitted and is pending review.', 'info', '/staff/ar-list', 1, '2026-09-26 07:02:50', '2026-09-30 03:31:57'),
(15, 47, 'Revision Required', 'Your Accomplishment Report \"test 1\" requires revision. Remarks: fgfgf', 'warning', '/staff/ar-list', 1, '2026-09-26 07:10:52', '2026-09-30 03:31:57'),
(16, 1, 'Accomplishment Report Resubmitted', 'Accomplishment Report \"test 1\" has been resubmitted and is pending review.', 'info', '/admin/ar-list', 0, '2026-09-26 07:14:04', '2026-09-26 07:14:04'),
(17, 47, 'Accomplishment Report Resubmitted', 'Accomplishment Report \"test 1\" has been resubmitted and is pending review.', 'info', '/staff/ar-list', 1, '2026-09-26 07:14:08', '2026-09-30 03:31:57'),
(18, 47, 'Activity Design Submitted', 'Your Activity Design \"hvchjvhjv\" has been successfully submitted and is pending review.', 'info', '/staff/ad-list', 1, '2026-09-27 08:50:31', '2026-09-30 03:31:57'),
(19, 1, 'New Activity Design Submitted', 'A new Activity Design \"hvchjvhjv\" has been submitted and is pending review.', 'info', '/admin/ad-list', 0, '2026-09-27 08:50:38', '2026-09-27 08:50:38'),
(20, 47, 'New Activity Design Submitted', 'A new Activity Design \"hvchjvhjv\" has been submitted and is pending review.', 'info', '/staff/ad-list', 1, '2026-09-27 08:50:44', '2026-09-30 03:31:57'),
(21, 47, 'Activity Design Approved', 'Your Activity Design \"hvchjvhjv\" has been approved.', 'success', '/staff/ad-list', 1, '2026-09-27 08:51:22', '2026-09-30 03:31:57'),
(22, 47, 'Accomplishment Report Submitted', 'Your Accomplishment Report \"hvchjvhjv\" has been successfully submitted and is pending review.', 'info', '/staff/ar-list', 1, '2026-09-27 08:53:07', '2026-09-30 03:31:57'),
(23, 1, 'New Accomplishment Report Submitted', 'A new Accomplishment Report \"hvchjvhjv\" has been submitted and is pending review.', 'info', '/admin/ar-list', 0, '2026-09-27 08:53:14', '2026-09-27 08:53:14'),
(24, 47, 'New Accomplishment Report Submitted', 'A new Accomplishment Report \"hvchjvhjv\" has been submitted and is pending review.', 'info', '/staff/ar-list', 1, '2026-09-27 08:53:18', '2026-09-30 03:31:57'),
(25, 47, 'Accomplishment Report Verified', 'Your Accomplishment Report \"hvchjvhjv\" has been verified and archived.', 'success', '/staff/ar-list', 1, '2026-09-27 08:54:08', '2026-09-30 03:31:57'),
(26, 2, 'Activity Design Submitted', 'Your Activity Design \"Test 1\" has been successfully submitted and is pending review.', 'info', '/college/submitted-list', 0, '2026-09-29 09:22:25', '2026-09-29 09:22:25'),
(27, 1, 'New Activity Design Submitted', 'A new Activity Design \"Test 1\" has been submitted and is pending review.', 'info', '/admin/ad-list', 0, '2026-09-29 09:22:32', '2026-09-29 09:22:32'),
(28, 47, 'New Activity Design Submitted', 'A new Activity Design \"Test 1\" has been submitted and is pending review.', 'info', '/staff/ad-list', 1, '2026-09-29 09:22:32', '2026-09-30 03:31:57'),
(29, 47, 'Activity Design Submitted', 'Your Activity Design \"dcsdvs\" has been successfully submitted and is pending review.', 'info', '/staff/ad-list', 1, '2026-09-29 09:25:01', '2026-09-30 03:31:57'),
(30, 1, 'New Activity Design Submitted', 'A new Activity Design \"dcsdvs\" has been submitted and is pending review.', 'info', '/admin/ad-list', 0, '2026-09-29 09:25:07', '2026-09-29 09:25:07'),
(31, 47, 'New Activity Design Submitted', 'A new Activity Design \"dcsdvs\" has been submitted and is pending review.', 'info', '/staff/ad-list', 1, '2026-09-29 09:25:17', '2026-09-30 03:31:57'),
(32, 2, 'Revision Required', 'Your Activity Design \"Test 1\" requires revision. Remarks: fdvdf', 'warning', '/college/submitted-list', 0, '2026-09-29 09:28:54', '2026-09-29 09:28:54'),
(33, 47, 'Revision Required', 'Your Activity Design \"dcsdvs\" requires revision. Remarks: dfvdfbd', 'warning', '/staff/ad-list', 1, '2026-09-29 09:29:11', '2026-09-30 03:31:57'),
(34, 2, 'Activity Design Submitted', 'Your Activity Design \"d sdvs\" has been successfully submitted and is pending review.', 'info', '/college/submitted-list', 0, '2026-09-30 02:43:45', '2026-09-30 02:43:45'),
(35, 1, 'New Activity Design Submitted', 'A new Activity Design \"d sdvs\" has been submitted and is pending review.', 'info', '/admin/ad-list', 0, '2026-09-30 02:43:51', '2026-09-30 02:43:51'),
(36, 47, 'New Activity Design Submitted', 'A new Activity Design \"d sdvs\" has been submitted and is pending review.', 'info', '/staff/ad-list', 1, '2026-09-30 02:43:56', '2026-09-30 03:31:57'),
(37, 2, 'Revision Required', 'Your Activity Design \"d sdvs\" requires revision. Remarks: sdsd', 'warning', '/college/submitted-list', 0, '2026-09-30 02:44:44', '2026-09-30 02:44:44'),
(38, 1, 'Activity Design Resubmitted', 'Activity Design \"d sdvs\" has been resubmitted and is pending review.', 'info', '/admin/ad-list', 0, '2026-09-30 03:19:27', '2026-09-30 03:19:27'),
(39, 47, 'Activity Design Resubmitted', 'Activity Design \"d sdvs\" has been resubmitted and is pending review.', 'info', '/staff/ad-list', 1, '2026-09-30 03:19:33', '2026-09-30 03:31:57'),
(40, 2, 'Activity Design Approved', 'Your Activity Design \"d sdvs\" has been approved.', 'success', '/college/submitted-list', 0, '2026-09-30 03:19:58', '2026-09-30 03:19:58'),
(41, 2, 'Accomplishment Report Submitted', 'Your Accomplishment Report \"d sdvs\" has been successfully submitted and is pending review.', 'info', '/college/submitted-list', 0, '2026-09-30 03:20:49', '2026-09-30 03:20:49'),
(42, 1, 'New Accomplishment Report Submitted', 'A new Accomplishment Report \"d sdvs\" has been submitted and is pending review.', 'info', '/admin/ar-list', 0, '2026-09-30 03:20:53', '2026-09-30 03:20:53'),
(43, 47, 'New Accomplishment Report Submitted', 'A new Accomplishment Report \"d sdvs\" has been submitted and is pending review.', 'info', '/staff/ar-list', 1, '2026-09-30 03:20:59', '2026-09-30 03:31:57'),
(44, 2, 'Accomplishment Report Verified', 'Your Accomplishment Report \"d sdvs\" has been verified and archived.', 'success', '/college/submitted-list', 0, '2026-09-30 03:21:31', '2026-09-30 03:21:31'),
(45, 47, 'Accomplishment Report Verified', 'Your Accomplishment Report \"test 1\" has been verified and archived.', 'success', '/staff/ar-list', 1, '2026-09-30 03:31:34', '2026-09-30 03:31:57'),
(46, 47, 'Allocate Budget for Verified AR', 'The Accomplishment Report \"test 1\" has been verified and archived. Please review and allocate its budget.', 'warning', '/staff/budget-distribution', 1, '2026-09-30 03:31:38', '2026-09-30 03:31:53'),
(47, 1, 'Activity Design Resubmitted', 'Activity Design \"Test 2\" has been resubmitted and is pending review.', 'info', '/admin/ad-list', 0, '2026-10-05 23:46:44', '2026-10-05 23:46:44'),
(48, 47, 'Activity Design Resubmitted', 'Activity Design \"Test 2\" has been resubmitted and is pending review.', 'info', '/staff/ad-list', 0, '2026-10-05 23:46:53', '2026-10-05 23:46:53'),
(49, 1, 'Modification Requested', 'A modification request for Activity Design \"d sdvs\" has been submitted and is pending review.', 'info', '/admin/ad-list', 0, '2026-10-06 06:49:34', '2026-10-06 06:49:34'),
(50, 47, 'Modification Requested', 'A modification request for Activity Design \"d sdvs\" has been submitted and is pending review.', 'info', '/staff/ad-list', 0, '2026-10-06 06:49:41', '2026-10-06 06:49:41'),
(51, 2, 'Modification Requested', 'Your modification request for \"d sdvs\" has been sent and is pending review.', 'info', '/college/submitted-list', 0, '2026-10-06 06:49:41', '2026-10-06 06:49:41'),
(52, 2, 'Modification Approved', 'Your modification request for \"d sdvs\" has been approved.', 'success', '/college/submitted-list', 0, '2026-10-06 06:49:56', '2026-10-06 06:49:56'),
(53, 2, 'Activity Design Approved', 'Your Activity Design \"Test 2\" has been approved.', 'success', '/college/submitted-list', 0, '2026-10-06 07:13:08', '2026-10-06 07:13:08'),
(54, 47, 'Allocate Budget for Approved AD', 'The Activity Design \"Test 2\" has been approved and archived. Please allocate its budget.', 'warning', '/staff/budget-distribution', 0, '2026-10-06 07:13:15', '2026-10-06 07:13:15'),
(55, 2, 'Accomplishment Report Submitted', 'Your Accomplishment Report \"Test 2\" has been successfully submitted and is pending review.', 'info', '/college/submitted-list', 0, '2026-10-06 07:14:44', '2026-10-06 07:14:44'),
(56, 1, 'New Accomplishment Report Submitted', 'A new Accomplishment Report \"Test 2\" has been submitted and is pending review.', 'info', '/admin/ar-list', 0, '2026-10-06 07:14:50', '2026-10-06 07:14:50'),
(57, 47, 'New Accomplishment Report Submitted', 'A new Accomplishment Report \"Test 2\" has been submitted and is pending review.', 'info', '/staff/ar-list', 0, '2026-10-06 07:14:56', '2026-10-06 07:14:56'),
(58, 47, 'Activity Design Approved', 'Your Activity Design \"dcsdvs\" has been approved.', 'success', '/staff/ad-list', 0, '2026-10-06 07:49:39', '2026-10-06 07:49:39'),
(59, 47, 'Allocate Budget for Approved AD', 'The Activity Design \"dcsdvs\" has been approved and archived. Please allocate its budget.', 'warning', '/staff/budget-distribution', 0, '2026-10-06 07:49:47', '2026-10-06 07:49:47'),
(60, 47, 'Accomplishment Report Submitted', 'Your Accomplishment Report \"dcsdvs\" has been successfully submitted and is pending review.', 'info', '/staff/ar-list', 0, '2026-10-06 07:50:57', '2026-10-06 07:50:57'),
(61, 1, 'New Accomplishment Report Submitted', 'A new Accomplishment Report \"dcsdvs\" has been submitted and is pending review.', 'info', '/admin/ar-list', 0, '2026-10-06 07:51:03', '2026-10-06 07:51:03'),
(62, 47, 'New Accomplishment Report Submitted', 'A new Accomplishment Report \"dcsdvs\" has been submitted and is pending review.', 'info', '/staff/ar-list', 0, '2026-10-06 07:51:09', '2026-10-06 07:51:09'),
(63, 2, 'Revision Required', 'Your Accomplishment Report \"Test 2\" requires revision. Remarks: svsdvs', 'warning', '/college/submitted-list', 0, '2026-10-06 07:51:30', '2026-10-06 07:51:30'),
(64, 47, 'Revision Required', 'Your Accomplishment Report \"dcsdvs\" requires revision. Remarks: dvsdvsd', 'warning', '/staff/ar-list', 0, '2026-10-06 07:51:47', '2026-10-06 07:51:47'),
(65, 1, 'Accomplishment Report Resubmitted', 'Accomplishment Report \"Test 2\" has been resubmitted and is pending review.', 'info', '/admin/ar-list', 1, '2026-10-06 08:45:56', '2026-10-06 08:53:20'),
(66, 47, 'Accomplishment Report Resubmitted', 'Accomplishment Report \"Test 2\" has been resubmitted and is pending review.', 'info', '/staff/ar-list', 0, '2026-10-06 08:46:01', '2026-10-06 08:46:01'),
(67, 2, 'Accomplishment Report Verified', 'Your Accomplishment Report \"Test 2\" has been verified and archived.', 'success', '/college/submitted-list', 1, '2026-10-06 08:46:21', '2026-10-10 09:01:31'),
(68, 47, 'Allocate Budget for Verified AR', 'The Accomplishment Report \"Test 2\" has been verified and archived. Please review and allocate its budget.', 'warning', '/staff/budget-distribution', 0, '2026-10-06 08:46:25', '2026-10-06 08:46:25'),
(69, 47, 'Activity Design Submitted', 'Your Activity Design \"sdsds\" has been successfully submitted and is pending review.', 'info', '/staff/ad-list', 0, '2026-10-07 04:54:00', '2026-10-07 04:54:00'),
(70, 1, 'New Activity Design Submitted', 'A new Activity Design \"sdsds\" has been submitted and is pending review.', 'info', '/admin/ad-list', 0, '2026-10-07 04:54:06', '2026-10-07 04:54:06'),
(71, 47, 'New Activity Design Submitted', 'A new Activity Design \"sdsds\" has been submitted and is pending review.', 'info', '/staff/ad-list', 0, '2026-10-07 04:54:12', '2026-10-07 04:54:12'),
(72, 52, 'New Activity Design Submitted', 'A new Activity Design \"sdsds\" has been submitted and is pending review.', 'info', '/staff/ad-list', 0, '2026-10-07 04:54:18', '2026-10-07 04:54:18'),
(73, 47, 'Activity Design Approved', 'Your Activity Design \"sdsds\" has been approved.', 'success', '/staff/ad-list', 0, '2026-10-07 04:54:45', '2026-10-07 04:54:45'),
(74, 47, 'Allocate Budget for Approved AD', 'The Activity Design \"sdsds\" has been approved and archived. Please allocate its budget.', 'warning', '/staff/budget-distribution', 0, '2026-10-07 04:54:49', '2026-10-07 04:54:49'),
(75, 52, 'Allocate Budget for Approved AD', 'The Activity Design \"sdsds\" has been approved and archived. Please allocate its budget.', 'warning', '/staff/budget-distribution', 0, '2026-10-07 04:54:52', '2026-10-07 04:54:52'),
(76, 1, 'Activity Design Resubmitted', 'Activity Design \"Test 1\" has been resubmitted and is pending review.', 'info', '/admin/ad-list', 0, '2026-10-07 04:55:10', '2026-10-07 04:55:10'),
(77, 47, 'Activity Design Resubmitted', 'Activity Design \"Test 1\" has been resubmitted and is pending review.', 'info', '/staff/ad-list', 0, '2026-10-07 04:55:16', '2026-10-07 04:55:16'),
(78, 52, 'Activity Design Resubmitted', 'Activity Design \"Test 1\" has been resubmitted and is pending review.', 'info', '/staff/ad-list', 0, '2026-10-07 04:55:19', '2026-10-07 04:55:19'),
(79, 47, 'Accomplishment Report Submitted', 'Your Accomplishment Report \"sdsds123\" has been successfully submitted and is pending review.', 'info', '/staff/ar-list', 0, '2026-10-07 05:01:49', '2026-10-07 05:01:49'),
(80, 1, 'New Accomplishment Report Submitted', 'A new Accomplishment Report \"sdsds123\" has been submitted and is pending review.', 'info', '/admin/ar-list', 0, '2026-10-07 05:01:56', '2026-10-07 05:01:56'),
(81, 47, 'New Accomplishment Report Submitted', 'A new Accomplishment Report \"sdsds123\" has been submitted and is pending review.', 'info', '/staff/ar-list', 0, '2026-10-07 05:02:01', '2026-10-07 05:02:01'),
(82, 47, 'Accomplishment Report Verified', 'Your Accomplishment Report \"sdsds123\" has been verified and archived.', 'success', '/staff/ar-list', 0, '2026-10-07 05:02:39', '2026-10-07 05:02:39'),
(83, 47, 'Allocate Budget for Verified AR', 'The Accomplishment Report \"sdsds123\" has been verified and archived. Please review and allocate its budget.', 'warning', '/staff/budget-distribution', 0, '2026-10-07 05:02:44', '2026-10-07 05:02:44'),
(84, 2, 'Revision Required', 'Your Activity Design \"Test 1\" requires revision. Remarks: - Undefined Beneficiaries\n- Over-Ceiling Costs\n- Expense Misclassification\n\nAdditional Notes:\nfdfdf', 'warning', '/college/submitted-list', 1, '2026-10-08 02:37:42', '2026-10-10 08:49:53'),
(85, 1, 'New User Registration', 'A new user John Doe (markanthony889966@gmail.com) has registered. Role: Non-TWG, Office: Budget Office.', 'info', '/admin/user-management', 0, '2026-10-08 11:08:36', '2026-10-08 11:08:36'),
(86, 47, 'New User Registration', 'A new user John Doe (markanthony889966@gmail.com) has registered. Role: Non-TWG, Office: Budget Office.', 'info', '/staff/user-management', 0, '2026-10-08 11:08:42', '2026-10-08 11:08:42'),
(87, 1, 'New User Registration', 'A new user John Doe (markanthony889966@gmail.com) has registered. Role: Non-TWG, Office: College of Information Sciences.', 'info', '/admin/user-management', 0, '2026-10-08 11:28:29', '2026-10-08 11:28:29'),
(88, 1, 'New User Registration', 'A new user John Doe (markanthony889966@gmail.com) has registered. Role: Non-TWG, Office: College of Information Sciences.', 'info', '/admin/user-management', 0, '2026-10-08 11:31:16', '2026-10-08 11:31:16'),
(89, 47, 'New User Registration', 'A new user John Doe (markanthony889966@gmail.com) has registered. Role: Non-TWG, Office: College of Information Sciences.', 'info', '/staff/user-management', 0, '2026-10-08 11:31:22', '2026-10-08 11:31:22'),
(90, 1, 'New User Registration', 'A new user mark anthony (bu@bsu.edu.ph) has registered. Role: TWG, Office: Cis.', 'info', '/admin/user-management', 0, '2026-10-09 12:13:42', '2026-10-09 12:13:42'),
(91, 47, 'New User Registration', 'A new user mark anthony (bu@bsu.edu.ph) has registered. Role: TWG, Office: Cis.', 'info', '/staff/user-management', 0, '2026-10-09 12:13:51', '2026-10-09 12:13:51'),
(92, 57, 'Activity Design Submitted', 'Your Activity Design \"sfvsfvsd\" has been successfully submitted and is pending review.', 'info', '/college/submitted-list', 0, '2026-10-09 12:16:48', '2026-10-09 12:16:48'),
(93, 1, 'New Activity Design Submitted', 'A new Activity Design \"sfvsfvsd\" has been submitted and is pending review.', 'info', '/admin/ad-list', 0, '2026-10-09 12:16:54', '2026-10-09 12:16:54'),
(94, 47, 'New Activity Design Submitted', 'A new Activity Design \"sfvsfvsd\" has been submitted and is pending review.', 'info', '/staff/ad-list', 0, '2026-10-09 12:17:00', '2026-10-09 12:17:00'),
(95, 1, 'Accomplishment Report Resubmitted', 'Accomplishment Report \"dcsdvs\" has been resubmitted and is pending review.', 'info', '/admin/ar-list', 0, '2026-10-09 13:36:48', '2026-10-09 13:36:48'),
(96, 47, 'Accomplishment Report Resubmitted', 'Accomplishment Report \"dcsdvs\" has been resubmitted and is pending review.', 'info', '/staff/ar-list', 0, '2026-10-09 13:36:55', '2026-10-09 13:36:55'),
(97, 1, 'New Message Received', 'You received a new message from Caa TWG.', 'info', '/admin/messages', 0, '2026-10-10 09:05:23', '2026-10-10 09:05:23'),
(98, 47, 'Activity Design Submitted', 'Your Activity Design \"test staff ad\" has been successfully submitted and is pending review.', 'info', '/staff/ad-list', 0, '2026-10-10 09:08:10', '2026-10-10 09:08:10'),
(99, 1, 'New Activity Design Submitted', 'A new Activity Design \"test staff ad\" has been submitted and is pending review.', 'info', '/admin/ad-list', 0, '2026-10-10 09:08:17', '2026-10-10 09:08:17'),
(100, 47, 'New Activity Design Submitted', 'A new Activity Design \"test staff ad\" has been submitted and is pending review.', 'info', '/staff/ad-list', 0, '2026-10-10 09:08:23', '2026-10-10 09:08:23'),
(101, 2, 'Activity Design Submitted', 'Your Activity Design \"test twg ad\" has been successfully submitted and is pending review.', 'info', '/college/submitted-list', 0, '2026-10-10 09:09:36', '2026-10-10 09:09:36'),
(102, 1, 'New Activity Design Submitted', 'A new Activity Design \"test twg ad\" has been submitted and is pending review.', 'info', '/admin/ad-list', 0, '2026-10-10 09:09:43', '2026-10-10 09:09:43'),
(103, 47, 'New Activity Design Submitted', 'A new Activity Design \"test twg ad\" has been submitted and is pending review.', 'info', '/staff/ad-list', 0, '2026-10-10 09:09:50', '2026-10-10 09:09:50'),
(104, 47, 'Revision Required', 'Your Activity Design \"test staff ad\" requires revision. Remarks: - Undefined Beneficiaries\n- Over-Ceiling Costs\n- Expense Misclassification', 'warning', '/staff/ad-list', 0, '2026-10-10 09:10:33', '2026-10-10 09:10:33'),
(105, 2, 'Revision Required', 'Your Activity Design \"test twg ad\" requires revision. Remarks: - Over-Ceiling Costs\n- Expense Misclassification\n- Not in Approved GPB', 'warning', '/college/submitted-list', 0, '2026-10-10 09:11:06', '2026-10-10 09:11:06'),
(106, 1, 'Activity Design Resubmitted', 'Activity Design \"test staff ad\" has been resubmitted and is pending review.', 'info', '/admin/ad-list', 0, '2026-10-10 09:12:05', '2026-10-10 09:12:05'),
(107, 47, 'Activity Design Resubmitted', 'Activity Design \"test staff ad\" has been resubmitted and is pending review.', 'info', '/staff/ad-list', 0, '2026-10-10 09:12:10', '2026-10-10 09:12:10'),
(108, 1, 'Activity Design Resubmitted', 'Activity Design \"test twg ad\" has been resubmitted and is pending review.', 'info', '/admin/ad-list', 0, '2026-10-10 09:12:19', '2026-10-10 09:12:19'),
(109, 47, 'Activity Design Resubmitted', 'Activity Design \"test twg ad\" has been resubmitted and is pending review.', 'info', '/staff/ad-list', 0, '2026-10-10 09:12:24', '2026-10-10 09:12:24'),
(110, 47, 'Activity Design Approved', 'Your Activity Design \"test staff ad\" has been approved.', 'success', '/staff/ad-list', 0, '2026-10-10 09:12:59', '2026-10-10 09:12:59'),
(111, 47, 'Allocate Budget for Approved AD', 'The Activity Design \"test staff ad\" has been approved and archived. Please allocate its budget.', 'warning', '/staff/budget-distribution', 0, '2026-10-10 09:13:05', '2026-10-10 09:13:05'),
(112, 2, 'Activity Design Approved', 'Your Activity Design \"test twg ad\" has been approved.', 'success', '/college/submitted-list', 0, '2026-10-10 09:13:27', '2026-10-10 09:13:27'),
(113, 47, 'Allocate Budget for Approved AD', 'The Activity Design \"test twg ad\" has been approved and archived. Please allocate its budget.', 'warning', '/staff/budget-distribution', 0, '2026-10-10 09:13:32', '2026-10-10 09:13:32'),
(114, 47, 'Accomplishment Report Submitted', 'Your Accomplishment Report \"test staff ad\" has been successfully submitted and is pending review.', 'info', '/staff/ar-list', 0, '2026-10-10 09:14:29', '2026-10-10 09:14:29'),
(115, 1, 'New Accomplishment Report Submitted', 'A new Accomplishment Report \"test staff ad\" has been submitted and is pending review.', 'info', '/admin/ar-list', 0, '2026-10-10 09:14:35', '2026-10-10 09:14:35'),
(116, 47, 'New Accomplishment Report Submitted', 'A new Accomplishment Report \"test staff ad\" has been submitted and is pending review.', 'info', '/staff/ar-list', 0, '2026-10-10 09:14:41', '2026-10-10 09:14:41'),
(117, 2, 'Accomplishment Report Submitted', 'Your Accomplishment Report \"test twg ad\" has been successfully submitted and is pending review.', 'info', '/college/submitted-list', 0, '2026-10-10 09:15:23', '2026-10-10 09:15:23'),
(118, 1, 'New Accomplishment Report Submitted', 'A new Accomplishment Report \"test twg ad\" has been submitted and is pending review.', 'info', '/admin/ar-list', 0, '2026-10-10 09:15:28', '2026-10-10 09:15:28'),
(119, 47, 'New Accomplishment Report Submitted', 'A new Accomplishment Report \"test twg ad\" has been submitted and is pending review.', 'info', '/staff/ar-list', 0, '2026-10-10 09:15:33', '2026-10-10 09:15:33'),
(120, 47, 'Revision Required', 'Your Accomplishment Report \"test staff ad\" requires revision. Remarks: - Undefined Beneficiaries\n- Over-Ceiling Costs', 'warning', '/staff/ar-list', 0, '2026-10-10 09:16:13', '2026-10-10 09:16:13'),
(121, 2, 'Revision Required', 'Your Accomplishment Report \"test twg ad\" requires revision. Remarks: - Undefined Beneficiaries\n- Over-Ceiling Costs\n- Expense Misclassification', 'warning', '/college/submitted-list', 0, '2026-10-10 09:16:34', '2026-10-10 09:16:34'),
(122, 1, 'Accomplishment Report Resubmitted', 'Accomplishment Report \"test staff ad\" has been resubmitted and is pending review.', 'info', '/admin/ar-list', 0, '2026-10-10 09:17:15', '2026-10-10 09:17:15'),
(123, 47, 'Accomplishment Report Resubmitted', 'Accomplishment Report \"test staff ad\" has been resubmitted and is pending review.', 'info', '/staff/ar-list', 0, '2026-10-10 09:17:22', '2026-10-10 09:17:22'),
(124, 1, 'Accomplishment Report Resubmitted', 'Accomplishment Report \"test twg ad\" has been resubmitted and is pending review.', 'info', '/admin/ar-list', 0, '2026-10-10 09:17:29', '2026-10-10 09:17:29'),
(125, 47, 'Accomplishment Report Resubmitted', 'Accomplishment Report \"test twg ad\" has been resubmitted and is pending review.', 'info', '/staff/ar-list', 0, '2026-10-10 09:17:35', '2026-10-10 09:17:35'),
(126, 47, 'Accomplishment Report Verified', 'Your Accomplishment Report \"test staff ad\" has been verified and archived.', 'success', '/staff/ar-list', 0, '2026-10-10 09:17:58', '2026-10-10 09:17:58'),
(127, 47, 'Allocate Budget for Verified AR', 'The Accomplishment Report \"test staff ad\" has been verified and archived. Please review and allocate its budget.', 'warning', '/staff/budget-distribution', 0, '2026-10-10 09:18:03', '2026-10-10 09:18:03'),
(128, 1, 'Activity Design Modified', 'GAD Staff modified the approved Activity Design \"test staff ad\".', 'info', '/admin/ad-list', 0, '2026-10-10 09:19:07', '2026-10-10 09:19:07'),
(129, 47, 'Activity Design Modified', 'GAD Staff modified the approved Activity Design \"test staff ad\".', 'info', '/staff/ad-list', 0, '2026-10-10 09:19:12', '2026-10-10 09:19:12'),
(130, 1, 'Modification Requested', 'A modification request for Activity Design \"test twg ad\" has been submitted and is pending review.', 'info', '/admin/ad-list', 0, '2026-10-10 09:19:43', '2026-10-10 09:19:43'),
(131, 47, 'Modification Requested', 'A modification request for Activity Design \"test twg ad\" has been submitted and is pending review.', 'info', '/staff/ad-list', 0, '2026-10-10 09:19:49', '2026-10-10 09:19:49'),
(132, 2, 'Modification Requested', 'Your modification request for \"test twg ad\" has been sent and is pending review.', 'info', '/college/submitted-list', 0, '2026-10-10 09:19:55', '2026-10-10 09:19:55'),
(133, 2, 'Modification Approved', 'Your modification request for \"test twg ad\" has been approved.', 'success', '/college/submitted-list', 0, '2026-10-10 09:20:06', '2026-10-10 09:20:06'),
(134, 1, 'Activity Design Resubmitted', 'Activity Design \"Test 1\" has been resubmitted and is pending review.', 'info', '/admin/ad-list', 0, '2026-10-10 09:20:33', '2026-10-10 09:20:33'),
(135, 47, 'Activity Design Resubmitted', 'Activity Design \"Test 1\" has been resubmitted and is pending review.', 'info', '/staff/ad-list', 0, '2026-10-10 09:20:38', '2026-10-10 09:20:38');

-- --------------------------------------------------------

--
-- Table structure for table `office_units`
--

CREATE TABLE `office_units` (
  `office_id` int(11) NOT NULL,
  `office_name` varchar(255) NOT NULL,
  `location` varchar(100) NOT NULL DEFAULT 'La Trinidad Campus',
  `office_acronym` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `office_units`
--

INSERT INTO `office_units` (`office_id`, `office_name`, `location`, `office_acronym`) VALUES
(1, 'Gender and Development Office', 'La Trinidad Campus', 'GAD'),
(2, 'College of Agriculture', 'La Trinidad Campus', 'CA'),
(3, 'Registrar\'s Office BSU Buguias Campus', 'Buguias Campus', 'Buguias-RO'),
(4, 'Human Resources and Management Office BSU Bokod Campus', 'Bokod Campus', 'Bokod-HRMO'),
(5, 'International Relations Office', 'La Trinidad Campus', 'IRO'),
(6, 'Disaster Risk Reduction Management', 'La Trinidad Campus', 'DRRM'),
(7, 'College of Social Science', 'La Trinidad Campus', 'CSS'),
(8, 'College of Applied Techonology BSU Bokod', 'Bokod Campus', 'Bokod-CAT'),
(9, 'University Business Affairs Office', 'La Trinidad Campus', 'UBAO'),
(10, 'University Library and Information Services BSU Buguias', 'Buguias Campus', 'Buguias-ULIS'),
(11, 'College of Veterinary Medicine', 'La Trinidad Campus', 'CVM'),
(12, 'Compensation, Benefits and Other Obligations', 'La Trinidad Campus', 'CBOO'),
(13, 'Records Office and Archives', 'La Trinidad Campus', 'ROA'),
(14, 'Budget Office', 'La Trinidad Campus', 'BO'),
(15, 'Office for Quality Assurance and Accreditation', 'La Trinidad Campus', 'OQAA'),
(16, 'University Health Services BSU Buguias', 'Buguias Campus', 'Buguias-UHS'),
(17, 'College of Natural Sciences', 'La Trinidad Campus', 'CNS'),
(18, 'College of Public Administration and Governance', 'La Trinidad Campus', 'CPAG'),
(19, 'Information and Communications Technolgy', 'La Trinidad Campus', 'ICT'),
(20, 'General Services Office', 'La Trinidad Campus', 'GSO'),
(21, 'College of Engineering', 'La Trinidad Campus', 'CE'),
(22, 'College of Nursing', 'La Trinidad Campus', 'CN'),
(23, 'BSU Office of Student Services', 'La Trinidad Campus', 'OSS'),
(24, 'University Public Affairs Office', 'La Trinidad Campus', 'UPAO'),
(25, 'Accounting Office', 'La Trinidad Campus', 'AO'),
(26, 'College of Human Kenetics', 'La Trinidad Campus', 'CHK'),
(27, 'Horticulture', 'La Trinidad Campus', 'Horticulture'),
(28, 'University Health Services BSU Bokod', 'Bokod Campus', 'Bokod-UHS'),
(29, 'College of Agriculture BSU Buguias', 'Buguias Campus', 'Buguias-CA'),
(30, 'Human Resources Development Office', 'La Trinidad Campus', 'HRDO'),
(31, 'Budget Office BSU Buguias', 'Buguias Campus', 'Buguias-BO'),
(32, 'College of Information Sciences', 'La Trinidad Campus', 'CIS'),
(33, 'Procurement Management Office BSU Bokod', 'Bokod Campus', 'Bokod-PMO'),
(34, 'Procurement Management Office', 'La Trinidad Campus', 'PMO'),
(36, 'College of Arts and Humanities', 'La Trinidad Campus', 'CAH'),
(37, 'College of Teacher Education', 'La Trinidad Campus', 'CTE'),
(38, 'Human Resource and Management Office', 'La Trinidad Campus', 'HRMO'),
(39, 'College of Home Economics and Technology', 'La Trinidad Campus', 'CHET'),
(40, 'Supply Property Management Office', 'La Trinidad Campus', 'SPMO'),
(41, 'University Library and Information Services', 'La Trinidad Campus', 'ULIS'),
(42, 'College of Numeracy and Applied Sciences', 'La Trinidad Campus', 'CNAS'),
(43, 'Northern Philippines Root Crops Research  & Training Center', 'La Trinidad Campus', 'NPRCRTC'),
(44, 'Open University', 'La Trinidad Campus', 'OU'),
(45, 'College of Education BSU Bokod', 'Bokod Campus', 'Bokod-CE'),
(46, 'College of Forestry', 'La Trinidad Campus', 'CF'),
(56, 'Cis', 'La Trinidad Campus', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `fiscal_year` int(11) NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `fiscal_year`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'name', 'Benguet State University', 2026, NULL, '2026-07-13 06:17:58', '2026-07-13 06:31:30'),
(2, 'category', 'State Universities and Colleges', 2026, NULL, '2026-07-13 06:17:58', '2026-07-13 06:31:30'),
(3, 'hierarchy', 'Benguet State University', 2026, NULL, '2026-07-13 06:17:58', '2026-07-13 06:31:30'),
(4, 'year', '2026', 2026, NULL, '2026-07-13 06:17:58', '2026-07-13 06:31:30'),
(5, 'totalOrgBudget', '1062488000', 2026, NULL, '2026-07-13 06:17:58', '2026-07-13 06:31:30'),
(6, 'otherSources', '0', 2026, NULL, '2026-07-13 06:17:58', '2026-07-13 06:31:30'),
(7, 'preparedByName', 'Jude Laoagan Tayaben, GAD Director', 2026, NULL, '2026-07-13 06:17:58', '2026-07-13 06:31:30'),
(8, 'approvedByName', 'Kenneth Alip Laruan, President', 2026, NULL, '2026-07-13 06:17:58', '2026-07-13 06:31:30'),
(9, 'name', 'Benguet State University', 2027, NULL, '2026-07-13 06:22:47', '2026-07-13 06:22:47'),
(10, 'category', 'State Universities and Colleges', 2027, NULL, '2026-07-13 06:22:47', '2026-07-13 06:22:47'),
(11, 'hierarchy', 'Benguet State University', 2027, NULL, '2026-07-13 06:22:47', '2026-07-13 06:22:47'),
(12, 'year', '2027', 2027, NULL, '2026-07-13 06:22:47', '2026-07-13 06:22:47'),
(13, 'totalOrgBudget', '1062488000', 2027, NULL, '2026-07-13 06:22:47', '2026-07-13 06:22:47'),
(14, 'otherSources', '0', 2027, NULL, '2026-07-13 06:22:47', '2026-07-13 06:22:47'),
(15, 'preparedByName', 'Jude Laoagan Tayaben, GAD Director', 2027, NULL, '2026-07-13 06:22:47', '2026-07-13 06:22:47'),
(16, 'approvedByName', 'Kenneth Alip Laruan, President', 2027, NULL, '2026-07-13 06:22:47', '2026-07-13 06:22:47'),
(17, 'name', 'Benguet State Universityy', 20264, NULL, '2026-07-13 06:29:35', '2026-07-13 06:29:35'),
(18, 'category', 'State Universities and Collegess', 20264, NULL, '2026-07-13 06:29:35', '2026-07-13 06:29:35'),
(19, 'hierarchy', 'Benguet State Universityy', 20264, NULL, '2026-07-13 06:29:35', '2026-07-13 06:29:35'),
(20, 'year', '20264', 20264, NULL, '2026-07-13 06:29:35', '2026-07-13 06:29:35'),
(21, 'totalOrgBudget', '10624880000', 20264, NULL, '2026-07-13 06:29:35', '2026-07-13 06:29:35'),
(22, 'otherSources', '1', 20264, NULL, '2026-07-13 06:29:35', '2026-07-13 06:29:35'),
(23, 'preparedByName', 'Jude Laoagan Tayaben, GAD Director', 20264, NULL, '2026-07-13 06:29:35', '2026-07-13 06:29:35'),
(24, 'approvedByName', 'Kenneth Alip Laruan, President', 20264, NULL, '2026-07-13 06:29:35', '2026-07-13 06:29:35'),
(25, 'baseline_amounts', '{\"meals_inside\":220,\"meals_outside\":350,\"snacks_inside\":85,\"snacks_outside\":150,\"pf_honoraria\":2258.25,\"tokens\":1000,\"materials\":1000,\"transportation_limit\":20000}', 0, NULL, '2026-07-17 04:01:46', '2026-07-17 04:05:34'),
(26, 'system_settings', '{\"ad_submission_limit_enabled\":false}', 0, NULL, '2026-07-20 02:07:20', '2026-10-10 09:07:13'),
(27, 'last_cleanup_run', '2026-08-27 05:56:22', 0, NULL, '2026-07-23 04:57:55', '2026-08-27 05:56:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `student_id` varchar(255) DEFAULT NULL,
  `office_id` int(11) DEFAULT NULL,
  `year_level` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` datetime DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `profile_role` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `email_verified_at`, `password`, `reset_token`, `reset_token_expires_at`, `role`, `full_name`, `student_id`, `office_id`, `year_level`, `remember_token`, `deleted_at`, `created_at`, `updated_at`, `last_login`, `first_name`, `middle_name`, `last_name`, `profile_role`) VALUES
(1, 'Gender and Development Office', 'gad.office@bsu.edu.ph', NULL, '$2y$10$a9XVQgTdygySA0E7XCNf4euNdZmuXjqGxSvUbQEzd5X7qiFmPNae6', NULL, NULL, 'admin', 'Jude Tayaben', NULL, 1, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-10-10 04:53:31', '2026-10-10 04:53:31', '', NULL, '', 'Director'),
(2, 'College of Agriculture', 'ca@bsu.edu.ph', NULL, '$2y$10$CKShTYh97GNm4C1Y20XFneDWhDBXhvtNyUwftPM9aDAbz4u9mz6Jy', NULL, NULL, 'twg', 'Caa TWG', NULL, 2, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-10-11 04:28:18', '2026-10-11 04:28:18', '', NULL, '', 'TWG'),
(3, 'Registrar\'s Office BSU Buguias Campus', 'buguias.registrar@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', 'George Pacyaden', NULL, 3, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-10-07 03:45:11', '2026-06-29 04:00:21', '', NULL, '', 'TWG'),
(4, 'Human Resources and Management Office BSU Bokod Campus', 'bokod.hrmo@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 4, NULL, 'Bokod-HRMO', NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(5, 'International Relations Office', 'iro@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 5, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(6, 'Disaster Risk Reduction Management', 'drrm@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 6, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(7, 'College of Social Science', 'css@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 7, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(8, 'College of Applied Techonology BSU Bokod Campus', 'bokod.cat@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 8, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(9, 'University Business Affairs Office', 'ubao@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 9, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(10, 'University Library and Information Service BSU Buguias Campus', 'ulis.buguias@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 10, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(11, 'College of Veterinary Medicine', 'vm@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 11, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(12, 'Compensarion, Benefits and Other Obligations', 'cboo@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 12, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(13, 'Records Office and Archives', 'roa@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 13, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(14, 'Budget Office', 'bo@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 14, NULL, 'BO', NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(15, 'Office for Quality Assurance and Accreditation', 'oqaa@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 15, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(16, 'University Health Services BSU Buguias Campus', 'buguias.uhs@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 16, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(17, 'College of Natural Sciences', 'cns@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 17, NULL, 'CNS', NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(18, 'College of Public Administration and Governance', 'cpag@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 18, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(19, 'Information and Communications Technolgy', 'ict@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 19, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(20, 'General Services Office', 'gso@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 20, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(21, 'College of Engineering', 'ce@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 21, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(22, 'College of Nursing', 'cn@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 22, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(23, 'BSU Office of Student Services', 'oss@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 23, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(24, 'University Public Affairs Office', 'upao@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 24, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(25, 'Accounting Office', 'ao@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 25, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(26, 'College of Human Kenetics', 'chk@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 26, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(27, 'Horticulture', 'h@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 27, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(28, 'Bokod Focal Person, University Health Services', 'bokod.uhs@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 28, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(29, 'Buguias Focal Person, College of Agriculture', 'buguias.ca@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 29, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(30, 'Human Resources Development Office', 'hrdo@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 30, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(31, 'Budget Office Buguias Campus', 'buguias.bo@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 31, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(32, 'College of Information Sciences', 'cis@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 32, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(33, 'Procurement Management Office BSU Bokod Campus', 'bokod.pmo@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 33, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(34, 'Procurement Management Office', 'pmo@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 34, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(35, 'Office of Student Services', 'oss.2@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 23, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-10-09 12:03:26', NULL, '', NULL, '', 'TWG'),
(36, 'College of Arts and Humanities', 'cah@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 36, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(37, 'College of Teacher Education', 'cte@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 37, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(38, 'Human Resource and Management Office', 'hrmo@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 38, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(39, 'College of Home Economics and Technology', 'chet@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 39, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(40, 'Supply Property Management Office', 'spmo@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 40, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(41, 'University Library and Information Services', 'ulis@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 41, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(42, 'College of Numeracy and Applied Sciences', 'cnas@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 42, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(43, 'Northern Philippines Root Crops Research  & Training Center', 'nprcrtc@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 43, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(44, 'Open University', 'ou@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 44, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(45, 'College of Education BSU Bokod Campus', 'bokod.ce@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 45, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(46, 'College of Forestry', 'cf@bsu.edu.ph', NULL, '$2y$10$tTZl3CqrG5J/qcGZS5Z/8uMPrk0kJdcGCQb/qnI.tOWHlpdscXa7m', NULL, NULL, 'twg', NULL, NULL, 46, NULL, NULL, NULL, '2026-05-25 11:58:10', '2026-07-06 14:06:16', NULL, '', NULL, '', 'TWG'),
(47, 'gad.staff', 'gad.staff@bsu.edu.ph', NULL, '$2y$12$fbD/jvk.znEQnBmKq4.ebOojmijHJO/zU7.P7Tzo.zV3FgvP8PzNe', NULL, NULL, 'gad_staff', 'GAD Staff', NULL, 1, NULL, NULL, NULL, '2026-03-26 15:53:56', '2026-10-10 04:54:09', '2026-10-10 04:54:09', 'GAD', 'Staff', 'User', 'Staff'),
(51, 'marksantos', NULL, NULL, NULL, NULL, NULL, 'deleted', 'Mark Santos', NULL, 32, NULL, NULL, '2026-09-16 17:09:31', '2026-06-17 12:57:12', '2026-09-17 01:09:31', '2026-06-30 01:05:39', 'Mark', '', 'Santos', 'Non-TWG'),
(52, 'bisayotduligas', 'bisayotduligas@gmail.com', NULL, '$2y$10$JpRWDi5O/IQLqslAOTtZy.kr77UwS.Dm2vzto7EgK4zJckMIjlcV2', '6f9995f94064d6ef1989ab3c2a77bdc6e9fb4fc0f16a040daf98f04e8cde135f', '2026-09-10 10:31:22', 'non-twg', 'Joshua Duligas', NULL, 32, NULL, NULL, NULL, '2026-06-25 02:46:48', '2026-10-08 11:05:33', '2026-10-08 11:05:33', '', NULL, '', 'Non-TWG'),
(53, 'johndoe', NULL, NULL, NULL, NULL, NULL, 'deleted', 'John Doe', NULL, NULL, NULL, NULL, '2026-09-16 17:05:33', '2026-09-12 06:18:40', '2026-10-09 12:03:26', '2026-09-12 06:19:19', 'John', '', 'Doe', 'TWG'),
(54, 'markanthony889966', NULL, NULL, NULL, NULL, NULL, 'deleted', 'John Doe', NULL, 14, NULL, NULL, '2026-10-08 03:19:09', '2026-10-08 11:08:36', '2026-10-08 11:19:09', NULL, 'John ', '', 'Doe', 'Non-TWG'),
(55, 'markanthony889966', NULL, NULL, NULL, NULL, NULL, 'deleted', 'John Doe', NULL, 32, NULL, NULL, '2026-10-08 03:30:23', '2026-10-08 11:28:29', '2026-10-08 11:30:23', NULL, 'John', '', 'Doe', 'Non-TWG'),
(56, 'markanthony889966', 'markanthony889966@gmail.com', NULL, '$2y$10$Xgd4iztdgpjZuGUkXsoA5u7SkCK2St9fYcYGyHoWv137O/2ZlGAVe', NULL, NULL, 'non-twg', 'John Doe', NULL, 32, NULL, NULL, NULL, '2026-10-08 11:31:16', '2026-10-08 11:31:16', NULL, 'John', '', 'Doe', 'Non-TWG'),
(57, 'bu', 'bu@bsu.edu.ph', NULL, '$2y$10$WOVZSKa.YJc2lD14PY6cpObTL1EuTWgushtxwdBoKWm.Nv1N5yt5O', NULL, NULL, 'twg', 'mark anthony', NULL, 56, NULL, NULL, NULL, '2026-10-09 12:13:42', '2026-10-09 12:23:11', '2026-10-09 12:23:11', 'mark', '', 'anthony', 'TWG');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `sex` enum('Male','Female','Prefer not to say') DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `position` varchar(150) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `student_id` varchar(100) DEFAULT NULL,
  `year_level` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `first_name`, `middle_name`, `last_name`, `sex`, `profile_picture`, `position`, `department`, `student_id`, `year_level`, `created_at`, `updated_at`) VALUES
(1, 1, 'Jude', NULL, 'Tayaben', NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(2, 2, 'Caa', NULL, 'TWG', NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-10 01:00:43'),
(3, 3, 'George', NULL, 'Pacyaden', NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(9, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(10, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(11, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(12, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(13, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(14, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(15, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(16, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(17, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(18, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(19, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(20, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(21, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(22, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(23, 23, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(24, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(25, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(26, 26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(27, 27, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(28, 28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(29, 29, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(30, 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(31, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(32, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(33, 33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(34, 34, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(35, 35, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(36, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(37, 37, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(38, 38, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(39, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(40, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(41, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(42, 42, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(43, 43, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(44, 44, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(45, 45, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(46, 46, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(47, 47, 'GAD', 'Staff', 'User', NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(48, 51, 'Mark', '', 'Santos', NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(49, 52, 'Joshua', NULL, 'Duligas', NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(50, 53, 'John', '', 'Doe', NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(51, 54, 'John ', '', 'Doe', NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(52, 55, 'John', '', 'Doe', NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(53, 56, 'John', '', 'Doe', NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-09 12:03:26', '2026-10-09 12:03:26'),
(64, 57, 'mark', NULL, 'anthony', 'Male', 'uploads/avatars/avatar_57_1791548620.png', NULL, NULL, NULL, NULL, '2026-10-09 04:13:42', '2026-10-09 12:30:19');

-- --------------------------------------------------------

--
-- Table structure for table `venues`
--

CREATE TABLE `venues` (
  `venue_id` int(11) NOT NULL,
  `venue_name` varchar(255) NOT NULL,
  `is_inside_bsu` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venues`
--

INSERT INTO `venues` (`venue_id`, `venue_name`, `is_inside_bsu`) VALUES
(1, 'BSU Gymnasium', 1),
(2, 'BSU Covered Court', 1),
(3, 'RDC Hall', 1),
(4, 'VP AdFin Hall', 1),
(5, 'International Dorm Hall', 1),
(6, 'IRO Hall', 1),
(7, 'Gladiola Center', 1),
(8, 'RSDC Executive Hall', 1),
(9, 'CTE DSG Hall', 1),
(10, 'CHET Hall', 1),
(11, 'CHK Function Hall', 1),
(12, 'Carnation Hall', 1),
(13, 'Everlasting Hall', 1),
(14, 'Solibao Hall', 1),
(15, 'Igorota Hall', 1),
(16, 'Dimas Hall, IHFSA', 1),
(17, 'OSS Social Hall', 1),
(18, 'Main Auditorium', 1),
(37, 'yo', 0),
(38, 'CIS', 0),
(39, 'CIS', 1),
(40, 'DORGING', 1),
(41, 'Yo 2', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accomplishment_budget_items`
--
ALTER TABLE `accomplishment_budget_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_abi_report` (`accomplishment_report_id`),
  ADD KEY `fk_abi_category` (`category_id`);

--
-- Indexes for table `accomplishment_report`
--
ALTER TABLE `accomplishment_report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_report_user` (`user_id`),
  ADD KEY `idx_ar_control_number` (`control_number`),
  ADD KEY `fk_ar_design` (`act_design_id`),
  ADD KEY `fk_ar_venue` (`venue_id`);

--
-- Indexes for table `accomplishment_schedules`
--
ALTER TABLE `accomplishment_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accomplishment_schedules_accomplishment_report_id_foreign` (`accomplishment_report_id`);

--
-- Indexes for table `activity_budget_items`
--
ALTER TABLE `activity_budget_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_actbi_design` (`act_design_id`),
  ADD KEY `fk_actbi_category` (`category_id`);

--
-- Indexes for table `activity_classifications`
--
ALTER TABLE `activity_classifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_design`
--
ALTER TABLE `activity_design`
  ADD PRIMARY KEY (`act_design_id`),
  ADD KEY `fk_activity_user` (`user_id`),
  ADD KEY `fk_activity_gpb` (`gpb_id`),
  ADD KEY `fk_act_venue` (`venue_id`),
  ADD KEY `fk_act_classification` (`classification_id`),
  ADD KEY `fk_ad_form_type` (`form_type`);

--
-- Indexes for table `activity_design_issues`
--
ALTER TABLE `activity_design_issues`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `act_design_id` (`act_design_id`,`issue_id`);

--
-- Indexes for table `activity_design_mandates`
--
ALTER TABLE `activity_design_mandates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `act_design_id` (`act_design_id`,`mandate_id`);

--
-- Indexes for table `activity_design_venues`
--
ALTER TABLE `activity_design_venues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_adv_design` (`act_design_id`),
  ADD KEY `fk_adv_venue` (`venue_id`);

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `activity_schedules`
--
ALTER TABLE `activity_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_schedules_act_design_id_foreign` (`act_design_id`);

--
-- Indexes for table `archived_annual_reports`
--
ALTER TABLE `archived_annual_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_aar_user` (`created_by`);

--
-- Indexes for table `budget_categories`
--
ALTER TABLE `budget_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `budget_item_mandate_allocations`
--
ALTER TABLE `budget_item_mandate_allocations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `budget_item_id_item_type` (`budget_item_id`,`item_type`),
  ADD KEY `mandate_id` (`mandate_id`);

--
-- Indexes for table `contact_inquiries`
--
ALTER TABLE `contact_inquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_inquiries_responder` (`responded_by`);

--
-- Indexes for table `evaluation_results`
--
ALTER TABLE `evaluation_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accomplishment_report_id` (`accomplishment_report_id`);

--
-- Indexes for table `form_types`
--
ALTER TABLE `form_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gpb_items`
--
ALTER TABLE `gpb_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `date` (`date`),
  ADD KEY `fk_holidays_updater` (`updated_by`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_msg_sender` (`sender_id`),
  ADD KEY `fk_msg_recipient` (`recipient_id`),
  ADD KEY `fk_msg_reply` (`parent_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_iec`
--
ALTER TABLE `news_iec`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_news_user` (`published_by`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `office_units`
--
ALTER TABLE `office_units`
  ADD PRIMARY KEY (`office_id`),
  ADD UNIQUE KEY `office_name` (`office_name`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_fiscal_year` (`key`,`fiscal_year`),
  ADD KEY `fk_settings_updater` (`updated_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_users_office_id` (`office_id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_id` (`user_id`);

--
-- Indexes for table `venues`
--
ALTER TABLE `venues`
  ADD PRIMARY KEY (`venue_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accomplishment_budget_items`
--
ALTER TABLE `accomplishment_budget_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT for table `accomplishment_report`
--
ALTER TABLE `accomplishment_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `accomplishment_schedules`
--
ALTER TABLE `accomplishment_schedules`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `activity_budget_items`
--
ALTER TABLE `activity_budget_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;

--
-- AUTO_INCREMENT for table `activity_classifications`
--
ALTER TABLE `activity_classifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `activity_design`
--
ALTER TABLE `activity_design`
  MODIFY `act_design_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `activity_design_issues`
--
ALTER TABLE `activity_design_issues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `activity_design_mandates`
--
ALTER TABLE `activity_design_mandates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `activity_design_venues`
--
ALTER TABLE `activity_design_venues`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `activity_schedules`
--
ALTER TABLE `activity_schedules`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `archived_annual_reports`
--
ALTER TABLE `archived_annual_reports`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `budget_categories`
--
ALTER TABLE `budget_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `budget_item_mandate_allocations`
--
ALTER TABLE `budget_item_mandate_allocations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `contact_inquiries`
--
ALTER TABLE `contact_inquiries`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `evaluation_results`
--
ALTER TABLE `evaluation_results`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `form_types`
--
ALTER TABLE `form_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gpb_items`
--
ALTER TABLE `gpb_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `news_iec`
--
ALTER TABLE `news_iec`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `office_units`
--
ALTER TABLE `office_units`
  MODIFY `office_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `venues`
--
ALTER TABLE `venues`
  MODIFY `venue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accomplishment_budget_items`
--
ALTER TABLE `accomplishment_budget_items`
  ADD CONSTRAINT `fk_abi_category` FOREIGN KEY (`category_id`) REFERENCES `budget_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_abi_report` FOREIGN KEY (`accomplishment_report_id`) REFERENCES `accomplishment_report` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `accomplishment_report`
--
ALTER TABLE `accomplishment_report`
  ADD CONSTRAINT `fk_ar_design` FOREIGN KEY (`act_design_id`) REFERENCES `activity_design` (`act_design_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_ar_venue` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`venue_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_report_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `accomplishment_schedules`
--
ALTER TABLE `accomplishment_schedules`
  ADD CONSTRAINT `accomplishment_schedules_accomplishment_report_id_foreign` FOREIGN KEY (`accomplishment_report_id`) REFERENCES `accomplishment_report` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `activity_budget_items`
--
ALTER TABLE `activity_budget_items`
  ADD CONSTRAINT `fk_actbi_category` FOREIGN KEY (`category_id`) REFERENCES `budget_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_actbi_design` FOREIGN KEY (`act_design_id`) REFERENCES `activity_design` (`act_design_id`) ON DELETE CASCADE;

--
-- Constraints for table `activity_design`
--
ALTER TABLE `activity_design`
  ADD CONSTRAINT `fk_act_classification` FOREIGN KEY (`classification_id`) REFERENCES `activity_classifications` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_act_venue` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`venue_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_activity_gpb` FOREIGN KEY (`gpb_id`) REFERENCES `gpb_items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_activity_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ad_form_type` FOREIGN KEY (`form_type`) REFERENCES `form_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `activity_design_issues`
--
ALTER TABLE `activity_design_issues`
  ADD CONSTRAINT `fk_adi_design` FOREIGN KEY (`act_design_id`) REFERENCES `activity_design` (`act_design_id`) ON DELETE CASCADE;

--
-- Constraints for table `activity_design_mandates`
--
ALTER TABLE `activity_design_mandates`
  ADD CONSTRAINT `fk_adm_design` FOREIGN KEY (`act_design_id`) REFERENCES `activity_design` (`act_design_id`) ON DELETE CASCADE;

--
-- Constraints for table `activity_design_venues`
--
ALTER TABLE `activity_design_venues`
  ADD CONSTRAINT `fk_adv_design` FOREIGN KEY (`act_design_id`) REFERENCES `activity_design` (`act_design_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_adv_venue` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`venue_id`) ON DELETE CASCADE;

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_alogs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `activity_schedules`
--
ALTER TABLE `activity_schedules`
  ADD CONSTRAINT `activity_schedules_act_design_id_foreign` FOREIGN KEY (`act_design_id`) REFERENCES `activity_design` (`act_design_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `archived_annual_reports`
--
ALTER TABLE `archived_annual_reports`
  ADD CONSTRAINT `fk_aar_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `contact_inquiries`
--
ALTER TABLE `contact_inquiries`
  ADD CONSTRAINT `fk_inquiries_responder` FOREIGN KEY (`responded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `evaluation_results`
--
ALTER TABLE `evaluation_results`
  ADD CONSTRAINT `fk_er_report` FOREIGN KEY (`accomplishment_report_id`) REFERENCES `accomplishment_report` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `holidays`
--
ALTER TABLE `holidays`
  ADD CONSTRAINT `fk_holidays_updater` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_msg_recipient` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_msg_reply` FOREIGN KEY (`parent_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_msg_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `news_iec`
--
ALTER TABLE `news_iec`
  ADD CONSTRAINT `fk_news_user` FOREIGN KEY (`published_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `fk_settings_updater` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_office_id` FOREIGN KEY (`office_id`) REFERENCES `office_units` (`office_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `fk_profile_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
