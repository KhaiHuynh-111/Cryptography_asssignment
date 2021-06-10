-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jun 10, 2021 at 10:41 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crypt_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `otp_expired`
--

CREATE TABLE `otp_expired` (
  `email` varchar(255) NOT NULL,
  `expire` datetime NOT NULL,
  `otp` varchar(255) NOT NULL,
  `is_expired` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `otp_expired`
--

INSERT INTO `otp_expired` (`email`, `expire`, `otp`, `is_expired`) VALUES
('luc.nguyenkhmt@hcmut.edu.vn', '2021-06-08 20:12:34', '415854', 1),
('luc.nguyenkhmt@hcmut.edu.vn', '2021-06-08 20:17:12', '335813', 1),
('luc.nguyenkhmt@hcmut.edu.vn', '2021-06-08 20:32:42', '751868', 1),
('luc.nguyenkhmt@hcmut.edu.vn', '2021-06-08 21:08:23', '320669', 1),
('luc.nguyenkhmt@hcmut.edu.vn', '2021-06-08 23:01:20', '836920', 1),
('luc.nguyenkhmt@hcmut.edu.vn', '2021-06-08 23:06:59', '757359', 1),
('luc.nguyenkhmt@hcmut.edu.vn', '2021-06-08 23:21:35', '758032', 1),
('luc.nguyenkhmt@hcmut.edu.vn', '2021-06-08 23:34:53', '967412', 1),
('luc.nguyenkhmt@hcmut.edu.vn', '2021-06-08 23:36:39', '175795', 1),
('luc.nguyenkhmt@hcmut.edu.vn', '2021-06-08 23:37:18', '896539', 1),
('luc.nguyenkhmt@hcmut.edu.vn', '2021-06-08 23:38:34', '757299', 1),
('luc.nguyenkhmt@hcmut.edu.vn', '2021-06-08 23:39:12', '312142', 1),
('khai.huynhmt1805@hcmut.edu.vn', '2021-06-10 14:42:41', '931656', 1),
('khai.huynhmt1805@hcmut.edu.vn', '2021-06-10 15:36:48', '850982', 1);

-- --------------------------------------------------------

--
-- Table structure for table `userlog`
--

CREATE TABLE `userlog` (
  `id` int(11) NOT NULL,
  `userId` varchar(45) NOT NULL,
  `username` varchar(255) NOT NULL,
  `role` varchar(15) NOT NULL,
  `userIp` varbinary(32) NOT NULL,
  `action` varchar(16) NOT NULL,
  `loginTime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userlog`
--

INSERT INTO `userlog` (`id`, `userId`, `username`, `role`, `userIp`, `action`, `loginTime`) VALUES
(1, 'nb9k99tragm59j2hcgfeiddha0', 'mew', 'user', 0x3a3a31, 'Logout', '2021-06-10 07:42:36'),
(2, 'nb9k99tragm59j2hcgfeiddha0', 'mew', 'user', 0x3a3a31, 'Login', '2021-06-10 07:42:52'),
(3, 'nb9k99tragm59j2hcgfeiddha0', 'mew', 'user', 0x3a3a31, 'Logout', '2021-06-10 08:36:07'),
(4, 'nb9k99tragm59j2hcgfeiddha0', 'admin', 'admin', 0x3a3a31, 'Login', '2021-06-10 08:36:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `userlog`
--
ALTER TABLE `userlog`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `userlog`
--
ALTER TABLE `userlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
