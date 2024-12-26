-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2024 at 08:00 PM
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
-- Database: `hisgqmlh_dbkhe_v15`
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
(1, 'admin@gmail.com', 'Admin123', 'admin');

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
  `Title` varchar(50) NOT NULL,
  `Description` varchar(100) NOT NULL,
  `Author` varchar(20) NOT NULL,
  `Date Published` datetime NOT NULL,
  `Category` varchar(20) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `d_status` varchar(50) DEFAULT 'Pending',
  `isArchive` int(11) NOT NULL DEFAULT 0,
  `resolution_no` varchar(255) DEFAULT NULL,
  `ordinance_no` varchar(255) DEFAULT NULL,
  `approval_timestamp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `doc_no`, `Title`, `Description`, `Author`, `Date Published`, `Category`, `file_path`, `user_id`, `d_status`, `isArchive`, `resolution_no`, `ordinance_no`, `approval_timestamp`) VALUES
(90, 'DOC-94', 'Implementation of Sustainability Initiatives', 'This resolution authorizes the introduction of a series of sustainability initiatives aimed at reduc', 'kheriz', '2024-12-16 06:34:10', 'Ordinance', 'uploads/Module-3-Pointers-and-Linked-Lists (1).pdf', 9, 'Reject', 1, NULL, 'ORDINANCE NO. 6 SERIES OF 2024', NULL),
(96, 'DOC-96', 'Implementation of Sustainability Initiatives', 'This resolution authorizes the introduction of a series of sustainability initiatives aimed at reduc', 'pppp', '2024-12-18 00:00:00', 'Resolution', 'uploads/sample.pdf', 1, 'Approved', 0, 'RESOLUTION NO. 2024-13', NULL, '2024-12-17 22:58:42'),
(98, 'DOC-98', 'Implementation of Sustainability Initiatives', 'This resolution authorizes the introduction of a series of sustainability initiatives aimed at reduc', 'nnn', '2024-12-19 00:00:00', 'Resolution', 'uploads/pdf.pdf', 1, 'First Reading', 0, 'RESOLUTION NO. 2024-15', NULL, NULL),
(100, 'DOC-92', 'ordinance for officers', 'this is a sample description', 'sample author', '2024-12-17 00:00:00', 'Ordinance', 'uploads/sample.pdf', 1, 'Pending', 0, '', 'ORDINANCE NO. 9 SERIES OF 2024', '2024-12-17 22:58:42'),
(101, 'DOC-101', 'This is a title', 'this is a sample description', 'Kheriz Somera', '2024-12-17 12:40:00', 'Resolution', 'uploads/sample.pdf', 1, 'Pending', 1, 'RESOLUTION NO. 2024-12-0000001', NULL, NULL),
(102, 'DOC-102', 'Implementation of Sustainability Initiatives', 'this is a sample description', 'Kheriz Somera', '2024-12-17 12:43:00', 'Resolution', 'uploads/sample.pdf', 1, 'Pending', 1, 'RESOLUTION NO. 2024-12-0000002', NULL, NULL),
(103, 'DOC-103', 'Implementation of Sustainability Initiatives', 'this is a sample description', 'Kheriz Somera', '2024-12-17 12:44:00', 'Ordinance', 'uploads/sample.pdf', 9, 'Pending', 0, NULL, 'ORDINANCE NO. 10 SERIES OF 2024', '2024-12-17 22:58:42'),
(107, NULL, 'Implementation of Sustainability Initiatives', 'this is a sample description', 'Kheriz Somera', '2024-12-17 17:48:00', 'Resolution', 'uploads/sample.pdf', 9, 'Pending', 0, 'RESOLUTION NO. 2024-12-0000002', NULL, NULL);

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
(92, NULL, 'dsasd', 'sdsdsd', 'khe', '2024-12-16T08:40', 'Resolution', NULL, 1, 'Second Reading', '2024-12-16 08:44:21', 92),
(93, NULL, 'dsasd', 'sdsdsd', 'khe', '2024-12-16T08:40', 'Resolution', NULL, 1, 'In Committee', '2024-12-16 08:44:34', 92),
(94, NULL, 'dsa', 'dsa', 'khe', '2024-12-16T08:40', 'Ordinances', NULL, 1, 'Second Reading', '2024-12-16 08:44:47', 91);

-- --------------------------------------------------------

--
-- Table structure for table `document_timeline`
--

CREATE TABLE `document_timeline` (
  `id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `action` enum('Pending','First Reading','Second Reading','Approve','Reject','In Committee') NOT NULL,
  `changed_column` varchar(50) DEFAULT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `performed_by` varchar(50) NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_timeline`
--

INSERT INTO `document_timeline` (`id`, `document_id`, `action`, `changed_column`, `old_value`, `new_value`, `performed_by`, `timestamp`, `comment`) VALUES
(27, 90, 'First Reading', NULL, NULL, NULL, 'admin@gmail.com', '2024-12-17 10:46:04', 'yow'),
(28, 90, 'Second Reading', NULL, NULL, NULL, 'admin@gmail.com', '2024-12-17 10:46:17', ''),
(29, 96, 'Pending', NULL, NULL, NULL, 'admin@gmail.com', '2024-12-17 22:44:32', ''),
(30, 96, 'First Reading', NULL, NULL, NULL, 'admin@gmail.com', '2024-12-17 22:44:36', ''),
(31, 96, 'Second Reading', NULL, NULL, NULL, 'admin@gmail.com', '2024-12-17 22:44:37', ''),
(32, 96, 'Approve', NULL, NULL, NULL, 'admin@gmail.com', '2024-12-17 22:44:39', ''),
(33, 98, 'First Reading', NULL, NULL, NULL, 'admin@gmail.com', '2024-12-17 22:51:22', '');

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
  `otp` int(11) DEFAULT NULL,
  `is_password_reset` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `Username`, `Password`, `AccessLevel`, `FirstName`, `LastName`, `position`, `email`, `dept`, `u_status`, `otp`, `is_password_reset`) VALUES
(9, 'Khe', 'kheriz', 'user', 'Kheriz', 'Somera', 'Somera', 'patrickdulin02@gmail.com', 'Somera', 'Active', 42989, 0),
(12, 'KherizSomera', 'BrvzkhyX', 'user', 'Kheriz', 'Macugay', 'Secretary ', 'Kherizsomera@gmail.com', 'Legislative ', 'active', 70250, 0),
(60, 'khez', 'BrvzkhyX', 'User', 'kheriz', 'somera', 'Secretary', 'kherizsomera@gmail.com', 'Legislative', 'Active', NULL, 0),
(63, 'sisu', 'VaCrsZU0', 'User', 'peter', 'griffin', '', 'peter@gmail.com', 'Legislative', 'Active', 123, 0),
(64, 'akihiko', 'w32vngXS', 'User', 'aki', 'larry', 'secretary', 'griffin@gmail.com', 'Secretary', 'Active', 307671, 0);

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
-- Indexes for table `document_timeline`
--
ALTER TABLE `document_timeline`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_id` (`document_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cases`
--
ALTER TABLE `cases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `documents_history`
--
ALTER TABLE `documents_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `document_timeline`
--
ALTER TABLE `document_timeline`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `document_timeline`
--
ALTER TABLE `document_timeline`
  ADD CONSTRAINT `document_timeline_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
