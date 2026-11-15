-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2024 at 12:06 PM
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
-- Database: `urbannationcopy`
--

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `productId` int(10) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `size` int(90) NOT NULL,
  `color` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `usedFor` varchar(255) NOT NULL,
  `price` int(10) NOT NULL,
  `overallCondition` varchar(255) NOT NULL,
  `sellersName` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `meetUpPreference` varchar(255) NOT NULL,
  `images` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`productId`, `productName`, `category`, `brand`, `size`, `color`, `description`, `usedFor`, `price`, `overallCondition`, `sellersName`, `location`, `meetUpPreference`, `images`) VALUES
(1231235641, 'logitech gaming mouse', 'E', 'logitech', 10, 'black', 'asdfghjk', 'gaming', 1200, 'very good', 'tejas', 'porvorim', 'panjim', ''),
(1231235642, 'boat nirvana ion', 'E', 'BOAT', 6, 'black', 'axyz', 'music', 2000, 'good', 'raj', 'marcel', 'old goa', ''),
(1231235643, 'nike jordan air 1', 'F', 'nike', 9, 'green', 'qwert', 'casual wear', 5000, 'nice', 'shivam', 'mapusa', 'panjim', 0x5b5d),
(1231235644, 'nike jordan air 1', 'F', 'nike', 9, 'green', 'qwert', 'casual wear', 5000, 'nice', 'shivam', 'mapusa', 'panjim', 0x5b5d),
(1231235645, 'fastrack watch', 'L', 'Fastrack', 35, 'black and red', 'leather belt', 'too see time', 1000, 'very nice', 'darren', 'old goa', 'marcel', 0x5b5d),
(1231235646, 'fastrack watch', 'L', 'Fastrack', 35, 'black and red', 'leather belt', 'too see time', 1000, 'very nice', 'darren', 'old goa', 'marcel', 0x5b5d),
(1231235647, 'dmart bottle', 'L', 'sport', 11, 'black', 'xyz', 'abc', 100, 'good', 'nikita', 'chodna', 'porvorim', 0x5b5d),
(1231235650, 'sdfsfds', 'C', 'dsfsdfs', 56, 'fsdfds', 'dsddfsdfsaf', 'sadfsdfsa', 300, 'dsfsdfdsf', 'dsfsfs', 'sdfsddfsd', 'sdfsdfs', ''),
(1231235651, 'zdvsdg', 'E', 'edsgsrfh', 12, 'xghxf', 'sgfsdgds', 'gsdgs', 400, 'sgsrt', 'srtwrtstr', 'rtrwy', 'yrwyrrw', ''),
(1231235652, 'zdvsdg', 'E', 'edsgsrfh', 12, 'xghxf', 'sgfsdgds', 'gsdgs', 400, 'sgsrt', 'srtwrtstr', 'rtrwy', 'yrwyrrw', ''),
(1231235653, 'agdrfgh', 'Hm', 'wrtywrt', 12, 'sfhwrshgy', 'sghshshj', 'ryjrtyjtredt', 600, 'rgrwyyrr', 'yrwesyrwy', 'yrywreywr', 'wryrrwsywy', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `productId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1231235654;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
