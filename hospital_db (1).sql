-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 01, 2024 at 11:02 AM
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
  `Reason` varchar(255) DEFAULT NULL,
  `PatientIC` varchar(20) DEFAULT NULL,
  `PatientRoom` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `PatientID` int(11) NOT NULL,
  `PatientName` varchar(100) NOT NULL,
  `PatientRoom` varchar(20) DEFAULT NULL,
  `PatientIC` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`PatientID`, `PatientName`, `PatientRoom`, `PatientIC`) VALUES
(1, 'Ahmad Zaki', 'A101', '901010-10-1010'),
(2, 'Nur Aisyah', 'B202', '920202-02-2020'),
(3, 'Mohd Firdaus', 'C303', '880303-03-3030'),
(4, 'Siti Amina', 'A102', '850404-04-4040'),
(5, 'Syed Imran', 'B203', '930505-05-5050'),
(6, 'Lim Wei Chong', 'C304', '940606-06-6060'),
(7, 'Tan Mei Ling', 'A103', '890707-07-7070'),
(8, 'Sharifah Noraini', 'B204', '880808-08-8080'),
(9, 'Zulkifli Rahman', 'C305', '860909-09-9090'),
(10, 'Norazman Ahmad', 'A104', '850101-10-1010'),
(11, 'Wan Muhammad', 'B205', '920202-11-1111'),
(12, 'Loh Chee Keong', 'C306', '940404-12-1212'),
(13, 'Kamarul Ariffin', 'A105', '890505-13-1313'),
(14, 'Raja Faizal', 'B206', '930606-14-1414'),
(15, 'Chong Wei Sheng', 'C307', '940707-15-1515'),
(16, 'Siti Nurhaliza', 'A106', '880808-16-1616'),
(17, 'Hafizah Hamid', 'B207', '850909-17-1717'),
(18, 'Mohd Faizal', 'C308', '920101-18-1818'),
(19, 'Haslinda Aziz', 'A107', '940202-19-1919'),
(20, 'Yong Wei Jin', 'B208', '890303-20-2020'),
(21, 'Amirul Hafiz', 'C309', '930404-21-2121'),
(22, 'Siti Khadijah', 'A108', '940505-22-2222'),
(23, 'Koh Soo Mei', 'B209', '880606-23-2323'),
(24, 'Ramesh Kumar', 'C310', '850707-24-2424'),
(25, 'Farid Ismail', 'A109', '920808-25-2525'),
(26, 'Sharul Nizam', 'B210', '940909-26-2626'),
(27, 'Goh Kok Leong', 'C311', '890101-27-2727'),
(28, 'Zarina Ali', 'A110', '930202-28-2828'),
(29, 'Shamsul Anuar', 'B211', '940303-29-2929'),
(30, 'Cheah Xin Yi', 'C312', '880404-30-3030'),
(31, 'Johan Ismail', 'A111', '850505-31-3131'),
(32, 'Azizah Mahmud', 'B212', '920606-32-3232'),
(33, 'Selvam Raj', 'C313', '940707-33-3333'),
(34, 'Rizal Zainal', 'A112', '890808-34-3434'),
(35, 'Mariam Bakar', 'B213', '930909-35-3535'),
(36, 'Sulaiman Ahmad', 'C314', '940101-36-3636'),
(37, 'Norain Kamaruddin', 'A113', '880202-37-3737'),
(38, 'Lee Jun Jie', 'B214', '850303-38-3838'),
(39, 'Rosli Hussin', 'C315', '920404-39-3939'),
(40, 'Nurul Izzati', 'A114', '940505-40-4040'),
(41, 'Kavitha Rajan', 'B215', '890606-41-4141'),
(42, 'Khairul Anwar', 'C316', '930707-42-4242'),
(43, 'Diana Fauziah', 'A115', '940808-43-4343'),
(44, 'Chan Wei Kiat', 'B216', '880909-44-4444'),
(45, 'Nadia Yasmin', 'C317', '850101-45-4545'),
(46, 'Azhar Hashim', 'A116', '920202-46-4646'),
(47, 'Tan Siew Lan', 'B217', '940404-47-4747'),
(48, 'Hassan Iskandar', 'C318', '890505-48-4848'),
(49, 'Nurul Ain', 'A117', '930606-49-4949'),
(50, 'Mohd Shafiq', 'B218', '940707-50-5050');

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
  `PatientIC` varchar(20) NOT NULL,
  `PatientRoom` varchar(20) DEFAULT NULL,
  `VisitingTime` datetime DEFAULT NULL,
  `ExitTime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
