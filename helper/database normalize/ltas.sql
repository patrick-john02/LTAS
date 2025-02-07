-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 07, 2025 at 03:02 PM
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
-- Database: `ltas`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `AccessLevel` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `user_id`, `AccessLevel`) VALUES
(2, 1, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Ordinance'),
(2, 'Resolution');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `doc_no` varchar(255) DEFAULT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL,
  `author` varchar(20) NOT NULL,
  `date_published` datetime NOT NULL,
  `category_id` int(11) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `d_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `isArchive` int(11) NOT NULL DEFAULT 0,
  `resolution_no` varchar(255) DEFAULT NULL,
  `ordinance_no` varchar(255) DEFAULT NULL,
  `approval_timestamp` datetime DEFAULT NULL,
  `Category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `doc_no`, `title`, `description`, `author`, `date_published`, `category_id`, `file_path`, `user_id`, `d_status`, `isArchive`, `resolution_no`, `ordinance_no`, `approval_timestamp`, `Category`) VALUES
(1, 'DOC001', 'Resolution on Budget', 'Budget approval document', 'John Doe', '2025-02-07 09:00:00', 1, '/path/to/document.pdf', 1, 'Pending', 0, NULL, 'ORD001', NULL, NULL),
(2, 'DOC002', 'HR Policy Update', 'Updated HR guidelines', 'Jane Doe', '2025-02-07 10:00:00', 2, '/path/to/hr_document.pdf', 2, 'Approved', 0, 'RES002', '', '2025-02-07 12:00:00', NULL);

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
  `rejection_timestamp` datetime DEFAULT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_timeline`
--

INSERT INTO `document_timeline` (`id`, `document_id`, `action`, `changed_column`, `old_value`, `new_value`, `performed_by`, `timestamp`, `rejection_timestamp`, `comment`) VALUES
(1, 1, 'Pending', 'N/A', 'N/A', 'N/A', 'John Doe', '2025-02-07 09:00:00', NULL, 'Initial creation of document'),
(2, 2, 'Approve', 'N/A', 'N/A', 'N/A', 'Jane Doe', '2025-02-07 12:00:00', NULL, 'Document approved for HR policy update');

-- --------------------------------------------------------

--
-- Stand-in structure for view `old_admin`
-- (See below for the actual view)
--
CREATE TABLE `old_admin` (
`ID` int(11)
,`Username` varchar(15)
,`Password` varchar(255)
,`AccessLevel` varchar(10)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `old_documents`
-- (See below for the actual view)
--
CREATE TABLE `old_documents` (
`DocumentID` int(11)
,`DocNo` varchar(255)
,`Title` varchar(50)
,`Description` varchar(100)
,`Author` varchar(20)
,`DatePublished` datetime
,`Category` varchar(50)
,`FilePath` varchar(255)
,`UserID` int(11)
,`DStatus` enum('Pending','Approved','Rejected')
,`IsArchive` int(11)
,`ResolutionNo` varchar(255)
,`OrdinanceNo` varchar(255)
,`ApprovalTimestamp` datetime
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `old_document_timeline`
-- (See below for the actual view)
--
CREATE TABLE `old_document_timeline` (
`TimelineID` int(11)
,`DocumentID` int(11)
,`Action` enum('Pending','First Reading','Second Reading','Approve','Reject','In Committee')
,`ChangedColumn` varchar(50)
,`OldValue` text
,`NewValue` text
,`PerformedBy` varchar(50)
,`Timestamp` datetime
,`RejectionTimestamp` datetime
,`Comment` text
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `old_users`
-- (See below for the actual view)
--
CREATE TABLE `old_users` (
`ID` int(11)
,`Username` varchar(15)
,`Password` varchar(255)
,`AccessLevel` varchar(100)
,`FirstName` varchar(15)
,`LastName` varchar(15)
,`Position` varchar(100)
,`Email` varchar(100)
,`Dept` varchar(100)
,`u_status` enum('Active','Inactive')
,`otp` int(11)
,`isPasswordReset` tinyint(1)
);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `Username` varchar(15) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `FirstName` varchar(15) NOT NULL,
  `LastName` varchar(15) NOT NULL,
  `Position` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Dept` varchar(100) DEFAULT NULL,
  `u_status` enum('Active','Inactive') NOT NULL DEFAULT 'Inactive',
  `otp` int(11) DEFAULT NULL,
  `is_password_reset` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `Username`, `Password`, `FirstName`, `LastName`, `Position`, `Email`, `Dept`, `u_status`, `otp`, `is_password_reset`) VALUES
(1, 'john', '123', 'John', 'Doe', 'Admin', 'john.doe@example.com', 'IT', 'Active', 123456, 0),
(2, 'jane', '123', 'Jane', 'Smith', 'Manager', 'jane.smith@example.com', 'HR', 'Active', 654321, 0);

-- --------------------------------------------------------

--
-- Structure for view `old_admin`
--
DROP TABLE IF EXISTS `old_admin`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `old_admin`  AS SELECT `a`.`id` AS `ID`, `u`.`Username` AS `Username`, `u`.`Password` AS `Password`, `a`.`AccessLevel` AS `AccessLevel` FROM (`admin` `a` join `users` `u` on(`a`.`user_id` = `u`.`ID`)) ;

-- --------------------------------------------------------

--
-- Structure for view `old_documents`
--
DROP TABLE IF EXISTS `old_documents`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `old_documents`  AS SELECT `d`.`id` AS `DocumentID`, `d`.`doc_no` AS `DocNo`, `d`.`title` AS `Title`, `d`.`description` AS `Description`, `d`.`author` AS `Author`, `d`.`date_published` AS `DatePublished`, `c`.`name` AS `Category`, `d`.`file_path` AS `FilePath`, `d`.`user_id` AS `UserID`, `d`.`d_status` AS `DStatus`, `d`.`isArchive` AS `IsArchive`, `d`.`resolution_no` AS `ResolutionNo`, `d`.`ordinance_no` AS `OrdinanceNo`, `d`.`approval_timestamp` AS `ApprovalTimestamp` FROM (`documents` `d` join `categories` `c` on(`d`.`category_id` = `c`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `old_document_timeline`
--
DROP TABLE IF EXISTS `old_document_timeline`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `old_document_timeline`  AS SELECT `document_timeline`.`id` AS `TimelineID`, `document_timeline`.`document_id` AS `DocumentID`, `document_timeline`.`action` AS `Action`, `document_timeline`.`changed_column` AS `ChangedColumn`, `document_timeline`.`old_value` AS `OldValue`, `document_timeline`.`new_value` AS `NewValue`, `document_timeline`.`performed_by` AS `PerformedBy`, `document_timeline`.`timestamp` AS `Timestamp`, `document_timeline`.`rejection_timestamp` AS `RejectionTimestamp`, `document_timeline`.`comment` AS `Comment` FROM `document_timeline` ;

-- --------------------------------------------------------

--
-- Structure for view `old_users`
--
DROP TABLE IF EXISTS `old_users`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `old_users`  AS SELECT `users`.`ID` AS `ID`, `users`.`Username` AS `Username`, `users`.`Password` AS `Password`, `users`.`Position` AS `AccessLevel`, `users`.`FirstName` AS `FirstName`, `users`.`LastName` AS `LastName`, `users`.`Position` AS `Position`, `users`.`Email` AS `Email`, `users`.`Dept` AS `Dept`, `users`.`u_status` AS `u_status`, `users`.`otp` AS `otp`, `users`.`is_password_reset` AS `isPasswordReset` FROM `users` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `doc_no` (`doc_no`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `document_timeline`
--
ALTER TABLE `document_timeline`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_id` (`document_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `document_timeline`
--
ALTER TABLE `document_timeline`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `document_timeline`
--
ALTER TABLE `document_timeline`
  ADD CONSTRAINT `document_timeline_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
