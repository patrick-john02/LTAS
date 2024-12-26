-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 04, 2024 at 06:58 PM
-- Server version: 10.6.19-MariaDB-cll-lve
-- PHP Version: 8.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hisgqmlh_dbkhe`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(16) NOT NULL,
  `password` varchar(16) NOT NULL,
  `AccessLevel` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `AccessLevel`) VALUES
(1, 'admin', 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `cases`
--

CREATE TABLE `cases` (
  `id` int(11) NOT NULL,
  `doc_no` varchar(50) DEFAULT NULL,
  `Category` varchar(255) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Author` varchar(255) NOT NULL,
  `DatePublished` timestamp NOT NULL DEFAULT current_timestamp(),
  `isArchive` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cases`
--

INSERT INTO `cases` (`id`, `doc_no`, `Category`, `Title`, `Description`, `Author`, `DatePublished`, `isArchive`) VALUES
(1, 'CASE-1111111', 'Rape', 'test', 'setest', 'setset', '2024-11-04 21:14:00', 0),
(2, 'CASE-1111111', 'Drugs', 'csdf', 'sdfsdf', 'sdfsdfsdf', '2024-11-05 00:14:00', 0),
(3, 'CASE-1111111-05', 'Murder', '23sdfsd', 'fsdf', 'sdfsdf', '2024-11-05 00:14:00', 1),
(4, 'ORD-1111111-554', 'Rape', 'sfdsdf', 'sfsdf', 'sdfsdfsdf', '2024-11-05 00:14:00', 0),
(5, 'CASE-1111111-05', 'Dengue', 'bhjg', 'gjghj', 'ghjghj', '2024-11-05 08:04:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `doc_no` varchar(255) DEFAULT NULL,
  `Title` varchar(20) NOT NULL,
  `Description` varchar(50) NOT NULL,
  `Author` varchar(20) NOT NULL,
  `Date Published` varchar(20) NOT NULL,
  `Category` varchar(20) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `d_status` varchar(50) DEFAULT 'Pending',
  `isArchive` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `doc_no`, `Title`, `Description`, `Author`, `Date Published`, `Category`, `file_path`, `user_id`, `d_status`, `isArchive`) VALUES
(28, NULL, 'test', 'test', 'test', '2024-10-31T10:09', 'Resolution', 'uploads/philippines_code_of_ethics_fo_professional_teachers.pdf', 6, 'First Reading', 1),
(29, NULL, 'test3', 'test3', 'test', '2024-10-31T10:10', 'Ordinances', 'uploads/philippines_code_of_ethics_fo_professional_teachers.pdf', 6, 'Pending', 0),
(30, NULL, 'test33', 'test33', 'test3', '2024-10-31T10:10', 'Ordinances', 'uploads/philippines_code_of_ethics_fo_professional_teachers.pdf', 6, 'First Reading', 0),
(31, NULL, 'Jsjsjjs', 'Jdjdhsj', 'Kheriz', '2024-10-31T14:12', 'Memorandum', 'uploads/inbound825649869187224913.pdf', 8, 'First Reading', 0),
(32, NULL, 'asdasd', 'asdasd', 'asdasd', '2024-10-31T10:13', 'Ordinances', 'uploads/philippines_code_of_ethics_fo_professional_teachers.pdf', 6, 'Pending', 1),
(33, NULL, 'Hhh', 'Hhh', 'Hhh', '2024-11-03T16:11', 'Memorandum', 'uploads/PRINCIPLES_AND_STRATEGIES_OF_TEACHING_1-2.pdf', 8, 'First Reading', 0),
(34, NULL, 'Bhhhyuu', 'Hhhh', 'Jhj', '2024-11-03T16:21', 'Memorandum', 'uploads/PRINCIPLES_AND_STRATEGIES_OF_TEACHING_1-2.pdf', 8, 'Second Reading', 1),
(35, 'ORD-1111111', '111111', '1111111', '11111', '2024-11-03T12:54', 'Memorandum', 'uploads/philippines_code_of_ethics_fo_professional_teachers.pdf', 6, 'Approved', 0),
(36, NULL, 'sdfsd', 'fsdfsd', 'fsdfsdf', '2024-11-03T13:07', 'Memorandum', 'uploads/philippines_code_of_ethics_fo_professional_teachers.pdf', NULL, 'Pending', 0),
(37, NULL, 'asda', 'asdasd', 'asdasd', '2024-11-03T13:15', 'Memorandum', 'uploads/philippines_code_of_ethics_fo_professional_teachers.pdf', NULL, 'Pending', 1),
(38, 'dsad', 'asdas', 'dasd', 'asd', '2024-11-03T13:17', 'Memorandum', 'uploads/philippines_code_of_ethics_fo_professional_teachers.pdf', NULL, 'Pending', 0),
(39, '2301', 'Jsjsjsj', 'Jajajjs', 'Jsjjsjs', '2024-11-03T17:43', 'Resolution', 'uploads/PRINCIPLES_AND_STRATEGIES_OF_TEACHING_1-2.pdf', 12, 'First Reading', 0),
(40, '123', 'Resolution', 'Jsjsj', 'Jsjsjjs', '2024-11-03T18:31', 'Resolution', 'uploads/PRINCIPLES_AND_STRATEGIES_OF_TEACHING_1-2.pdf', 12, 'Pending', 1);

-- --------------------------------------------------------

--
-- Table structure for table `documents_history`
--

CREATE TABLE `documents_history` (
  `id` int(11) NOT NULL,
  `doc_no` varchar(255) DEFAULT NULL,
  `Title` varchar(20) NOT NULL,
  `Description` varchar(50) NOT NULL,
  `Author` varchar(20) NOT NULL,
  `Date Published` varchar(20) NOT NULL,
  `Category` varchar(20) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `d_status` varchar(50) DEFAULT 'Pending',
  `date_updated` datetime DEFAULT current_timestamp(),
  `doc_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents_history`
--

INSERT INTO `documents_history` (`id`, `doc_no`, `Title`, `Description`, `Author`, `Date Published`, `Category`, `file_path`, `user_id`, `d_status`, `date_updated`, `doc_id`) VALUES
(6, NULL, 'tests', 'etsetse', 'tsetset', '2024-10-22T04:36', 'Memorandum', NULL, 1, 'First Reading', '2024-10-21 20:37:04', 26),
(7, NULL, 'tests', 'etsetse', 'tsetset', '2024-10-22T04:36', 'Memorandum', NULL, 1, 'Second Reading', '2024-10-21 20:37:25', 26),
(8, NULL, 'tests', 'etsetse', 'tsetset', '2024-10-22T04:36', 'Ordinances', NULL, 1, 'In Committee', '2024-10-21 20:37:40', 26),
(9, NULL, 'test33', 'test33', 'test3', '2024-10-31T10:10', 'Ordinances', NULL, 1, 'First Reading', '2024-10-31 02:15:38', 30),
(10, NULL, 'Jsjsjjs', 'Jdjdhsj', 'Kheriz', '2024-10-31T14:12', 'Memorandum', NULL, 8, 'First Reading', '2024-11-03 03:10:36', 31),
(11, NULL, 'Hhh', 'Hhh', 'Hhh', '2024-11-03T16:11', 'Memorandum', NULL, 8, 'First Reading', '2024-11-03 03:12:43', 33),
(12, NULL, 'Bhhhyuu', 'Hhhh', 'Jhj', '2024-11-03T16:21', 'Memorandum', NULL, 8, 'First Reading', '2024-11-03 03:22:56', 34),
(13, NULL, 'Bhhhyuu', 'Hhhh', 'Jhj', '2024-11-03T16:21', 'Memorandum', NULL, 8, 'Second Reading', '2024-11-03 03:23:08', 34),
(14, NULL, '111111', '1111111', '11111', '2024-11-03T12:54', 'Resolution', NULL, 1, 'Second Reading', '2024-11-03 04:02:58', 35),
(15, NULL, 'Jsjsjsj', 'Jajajjs', 'Jsjjsjs', '2024-11-03T17:43', 'Resolution', NULL, 12, 'First Reading', '2024-11-03 04:45:47', 39),
(16, NULL, '111111', '1111111', '11111', '2024-11-03T12:54', 'Memorandum', NULL, 12, 'Approved', '2024-11-03 04:56:22', 35),
(17, NULL, 'test', 'test', 'test', '2024-10-31T10:09', 'Resolution', NULL, 1, 'First Reading', '2024-11-03 05:16:18', 28);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `session_name` varchar(50) NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `isArchive` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `session_name`, `start_datetime`, `end_datetime`, `isArchive`) VALUES
(8, 'Hhshhshs', '2024-10-31 11:46:00', '2024-11-30 11:46:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `Username` varchar(15) NOT NULL,
  `Password` varchar(16) NOT NULL,
  `AccessLevel` varchar(10) NOT NULL,
  `FirstName` varchar(15) NOT NULL,
  `LastName` varchar(15) NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `dept` varchar(100) DEFAULT NULL,
  `u_status` varchar(20) NOT NULL DEFAULT 'Inactive',
  `otp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `Username`, `Password`, `AccessLevel`, `FirstName`, `LastName`, `position`, `email`, `dept`, `u_status`, `otp`) VALUES
(1, 'kheriz', 'kheriz', 'user', 'khe', 'somera', '', '', '', 'active', 30291),
(6, 'aa', 'aa', 'user', 'ttt', 'ttt', 'ttt', 'jake.robin005@gmail.com', 'ttt', 'active', 90122),
(7, 'bb', 'bb', 'user', 'sdfdsf', 'sfdsdf', 'sfdsd', 'dsfdsfs', 'fsdfsdfs', 'Inactive', NULL),
(8, 'Kheriz', 'Kheriz04', 'user', 'Kheriz', 'Somera', 'Secretary', 'kherizsomera@gmail.com', 'Legislative', 'active', 15963),
(9, 'Khe', 'kheriz', 'user', 'Kheriz', 'Somera', 'Somera', 'fvinarao2@gmail.com', 'Somera', 'Inactive', NULL),
(10, 'Khrz', 'kheriz', 'user', 'Kheriz ', 'Somera ', 'Secretary ', 'kherizsomera@gmail.com', 'Legislative ', 'Inactive', NULL),
(11, 'fritz', 'fritz*5', 'user', 'fritz ann', 'rabanal', 'secretary', 'fritzrabanal@gmail.com', 'legislative', 'active', 89708),
(12, 'Kherizsomera@gm', 'Kheriz123$', 'user', 'Khe', 'Macugay', 'Secretary ', 'Kherizsomera@gmail.com ', 'Legislative ', 'active', 70250),
(13, 'Francis ', 'Vinarao13$', 'user', 'Francis', 'Vinarao', 'Secretary ', 'vinaraofrancis4@gmail.com', 'Legislative ', 'active', NULL),
(14, 'MFV', 'Francis13$', 'user', 'Francis', 'Narag', 'secretary', 'vinaraofrancis4@gmail.com', 'legislative', 'active', 64386);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cases`
--
ALTER TABLE `cases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents_history`
--
ALTER TABLE `documents_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cases`
--
ALTER TABLE `cases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `documents_history`
--
ALTER TABLE `documents_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
