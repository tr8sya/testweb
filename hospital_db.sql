-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2024 at 07:32 PM
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
-- Database: `hospital_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `deleted_visitors`
--

CREATE TABLE `deleted_visitors` (
  `HistoryID` int(11) NOT NULL,
  `VisitorID` int(11) DEFAULT NULL,
  `VisitorName` varchar(100) DEFAULT NULL,
  `VisitorPhone` varchar(15) DEFAULT NULL,
  `PatientID` int(11) DEFAULT NULL,
  `PatientName` varchar(100) DEFAULT NULL,
  `VisitingTime` datetime DEFAULT NULL,
  `ExitTime` datetime DEFAULT NULL,
  `DeletionTime` datetime DEFAULT current_timestamp(),
  `Reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `PatientID` int(11) NOT NULL,
  `PatientName` varchar(100) NOT NULL,
  `PatientRoom` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`PatientID`, `PatientName`, `PatientRoom`) VALUES
(1, 'Ahmad Bin Abdullah', 'Room 101'),
(2, 'Nur Aisyah Binti Ismail', 'Room 102'),
(3, 'Tan Kok Liang', 'Room 103'),
(4, 'Siti Nurhaliza Binti Omar', 'Room 104'),
(5, 'Mohamed Amirul Bin Zulkifli', 'Room 105'),
(6, 'Lim Mei Ying', 'Room 106'),
(7, 'Ali Bin Hassan', 'Room 107'),
(8, 'Fatimah Binti Mohamad', 'Room 108'),
(9, 'Chong Wei Lun', 'Room 109'),
(10, 'Zainab Binti Khalid', 'Room 110'),
(11, 'Farid Bin Razak', 'Room 111'),
(12, 'Shahrul Nizam Bin Daud', 'Room 112'),
(13, 'Noraini Binti Yusof', 'Room 113'),
(14, 'Wong Sze Yin', 'Room 114'),
(15, 'Ravi A/L Krishnan', 'Room 115');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE `visitors` (
  `VisitorID` int(11) NOT NULL,
  `VisitorName` varchar(100) NOT NULL,
  `VisitorPhone` varchar(12) DEFAULT NULL,
  `PatientID` int(11) DEFAULT NULL,
  `VisitingTime` datetime DEFAULT NULL,
  `ExitTime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `deleted_visitors`
--
ALTER TABLE `deleted_visitors`
  ADD PRIMARY KEY (`HistoryID`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`PatientID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`VisitorID`),
  ADD KEY `PatientID` (`PatientID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `deleted_visitors`
--
ALTER TABLE `deleted_visitors`
  MODIFY `HistoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `PatientID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `VisitorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `visitors`
--
ALTER TABLE `visitors`
  ADD CONSTRAINT `visitors_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `patients` (`PatientID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
