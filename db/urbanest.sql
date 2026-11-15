-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2024 at 11:29 AM
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
-- Database: `urbanest`
--

-- --------------------------------------------------------

--
-- Table structure for table `adroomie`
--

CREATE TABLE `adroomie` (
  `roomieId` int(11) NOT NULL,
  `userId` int(10) NOT NULL,
  `choosePG` varchar(200) DEFAULT NULL,
  `name` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
  `contactNo` varchar(10) DEFAULT NULL,
  `roomDetails` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `hide` tinyint(1) DEFAULT 0,
  `Gender` varchar(10) DEFAULT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adroomie`
--

INSERT INTO `adroomie` (`roomieId`, `userId`, `choosePG`, `name`, `contactNo`, `roomDetails`, `location`, `hide`, `Gender`, `image`) VALUES
(23, 28, 'Laxmi Niwas', 'Chandrakant Tamoskar', '8999989824', 'The room is fully furnished with a single bed, a study desk, a wardrobe, and an attached bathroom. It also includes Wi-Fi and access to the common kitchen and living area.', 'Laxmi Nivas, Behind 20 Pt, Near Socorro Mazid And Socorro Panchayat, Socorro, Porvorim, Bardez, Goa.', 0, 'Male', 'default-paying-guest-accommodations-5.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `userId` int(10) NOT NULL,
  `productId` int(10) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `size` varchar(90) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` int(10) NOT NULL,
  `sellersName` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `images` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`userId`, `productId`, `productName`, `category`, `brand`, `size`, `description`, `price`, `sellersName`, `location`, `images`) VALUES
(28, 1231235672, 'HP Victus Gaming Laptop', 'E', 'HP', '15.6-inch (39.6 cm) ', 'HP Victus Gaming Laptop, AMD Ryzen 5 5600H, 4GB RTX 3050 GPU, 15.6-inch (39.6 cm) FHD IPS 144Hz, 8GB DDR4, 512GB SSD, Backlit KB, 9ms Response time (Win 11, MSO, Silver, 2.37 kg), fb0050AX', 60000, 'Chandrakant Tamoskar', 'Socorro, Porvorim, Bardez, Goa', '6647215fae516.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `ID` int(10) NOT NULL,
  `AdminName` varchar(120) DEFAULT NULL,
  `UserName` varchar(120) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Email` varchar(120) DEFAULT NULL,
  `Password` varchar(120) DEFAULT NULL,
  `AdminRegdate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`ID`, `AdminName`, `UserName`, `MobileNumber`, `Email`, `Password`, `AdminRegdate`) VALUES
(1, 'Admin', 'admin', 1234561230, 'admin@gmail.com', 'f925916e2754e5e03f75dd58a5733251', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblbookpg`
--

CREATE TABLE `tblbookpg` (
  `ID` int(10) NOT NULL,
  `Userid` int(10) DEFAULT NULL,
  `Pgid` int(10) DEFAULT NULL,
  `BookingNumber` int(10) NOT NULL,
  `CheckinDate` date DEFAULT NULL,
  `UserMsg` varchar(500) DEFAULT NULL,
  `BookingDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `Remark` varchar(50) DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL,
  `RemDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblbookpg`
--

INSERT INTO `tblbookpg` (`ID`, `Userid`, `Pgid`, `BookingNumber`, `CheckinDate`, `UserMsg`, `BookingDate`, `Remark`, `Status`, `RemDate`) VALUES
(52, 28, 27, 967985075, '0000-00-00', 'I\'m interested in renting a room at your PG. Could we arrange a time to meet and discuss the details?', '2024-05-17 08:57:08', 'Thank you for reaching out.', 'Confirmed', '2024-05-17 09:01:19');

-- --------------------------------------------------------

--
-- Table structure for table `tblcity`
--

CREATE TABLE `tblcity` (
  `ID` int(10) NOT NULL,
  `StateID` int(10) DEFAULT NULL,
  `City` varchar(120) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblcity`
--

INSERT INTO `tblcity` (`ID`, `StateID`, `City`, `CreationDate`) VALUES
(1, 1, 'Mumbai ', '2024-01-11 11:45:52'),
(2, 2, 'North Goa', '2024-01-11 11:46:14'),
(3, 2, 'South Goa', '2024-01-11 11:46:14'),
(9, 1, 'Pune', '2024-05-17 08:44:04'),
(10, 1, 'Sawantwadi', '2024-05-17 08:44:28');

-- --------------------------------------------------------

--
-- Table structure for table `tblowner`
--

CREATE TABLE `tblowner` (
  `ID` int(10) NOT NULL,
  `FullName` varchar(200) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Password` varchar(120) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `VerificationFile` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblowner`
--

INSERT INTO `tblowner` (`ID`, `FullName`, `Email`, `MobileNumber`, `Password`, `RegDate`, `VerificationFile`, `status`) VALUES
(12, 'Tejas Naik', 'tejasnaik@gmail.com', 9764413130, 'f925916e2754e5e03f75dd58a5733251', '2024-05-17 08:47:13', 'uploads/images.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblpages`
--

CREATE TABLE `tblpages` (
  `ID` int(11) NOT NULL,
  `PageType` varchar(200) DEFAULT NULL,
  `PageTitle` mediumtext DEFAULT NULL,
  `PageDescription` mediumtext DEFAULT NULL,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblpages`
--

INSERT INTO `tblpages` (`ID`, `PageType`, `PageTitle`, `PageDescription`, `UpdationDate`) VALUES
(1, 'aboutus', 'About Us', '<div class=\"black\" style=\"text-align: left;\"><span style=\"font-size:16.0pt;mso-bidi-font-size:11.0pt;\r\n line-height:115%;\r\nfont-family:&quot;\r\nCalibri&quot;\r\n,\r\nsans-serif;\r\nmso-ascii-theme-font:minor-latin;\r\nmso-fareast-font-family:Calibri;\r\nmso-fareast-theme-font:minor-latin;\r\nmso-hansi-theme-font: minor-latin;\r\nmso-bidi-font-family:&quot;\r\nTimes New Roman&quot;\r\n;\r\nmso-bidi-theme-font:minor-bidi;\r\nmso-ansi-language:EN-US;\r\nmso-fareast-language:EN-US;\r\nmso-bidi-language:AR-SA\">Our\r\n vision at Urban Nest is to go beyond the conventional approach to PG accommodations. We aim to provide a platform where comfort,\r\nstyle,\r\nand community converge seamlessly. From booking the perfect PG to discovering unique thrift store finds and connecting with compatible roommates,\r\nUrban Nest is your partner in creating a living space that resonates with your individuality.</span><br></div>', '2024-04-03 13:32:26'),
(2, 'contactus', 'Contact Us', '<b><span style=\"font-size:16.0pt;mso-bidi-font-size:11.0pt;line-height:115%;font-family:\r\n&quot;Calibri&quot;,sans-serif;mso-ascii-theme-font:minor-latin;mso-fareast-font-family:\r\nCalibri;mso-fareast-theme-font:minor-latin;mso-hansi-theme-font:minor-latin;\r\nmso-bidi-font-family:&quot;Times New Roman&quot;;mso-bidi-theme-font:minor-bidi;\r\nmso-ansi-language:EN-US;mso-fareast-language:EN-US;mso-bidi-language:AR-SA\">UrbanNest</span></b>\r\n<div class=\"white\">\r\n    <font face=\"Calibri, sans-serif\"><span style=\"font-size: 26.6667px;\"><b><br></b></span></font>\r\n    <div>\r\n        <p class=\"MsoNormal\"><span style=\"font-size:14.0pt;mso-bidi-font-size:11.0pt;line-height:115%\"><b>Contact Number:</b> 9999999999<o:p></o:p></span></p>\r\n    </div>\r\n    <div>\r\n        <p class=\"MsoNormal\"><span style=\"font-size:14.0pt;mso-bidi-font-size:11.0pt;line-height:115%\"><b>Email: </b><span class=\"MsoHyperlink\"><a\r\n                        href=\"mailto:urbannest@gmail.com\">urbannest@gmail.com</a></span>\r\n                <o:p></o:p>\r\n            </span></p>\r\n\r\n        <p class=\"MsoNormal\"><span style=\"font-size:14.0pt;mso-bidi-font-size:11.0pt;line-height:115%\"><b>Address:&nbsp;</b>UrbanNest&nbsp;Goa<o:p></o:p></span></p>\r\n    </div>\r\n</div>', '2024-04-03 13:34:07');

-- --------------------------------------------------------

--
-- Table structure for table `tblpg`
--

CREATE TABLE `tblpg` (
  `ID` int(10) NOT NULL,
  `OwnerID` int(20) NOT NULL,
  `StateName` varchar(200) DEFAULT NULL,
  `CityName` varchar(200) DEFAULT NULL,
  `PGTitle` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Type` varchar(120) DEFAULT NULL,
  `RPmonth` varchar(120) DEFAULT NULL,
  `norooms` varchar(45) DEFAULT NULL,
  `Address` varchar(120) DEFAULT NULL,
  `Image` varchar(200) DEFAULT NULL,
  `Electricity` varchar(40) DEFAULT NULL,
  `Parking` varchar(40) DEFAULT NULL,
  `Refregerator` varchar(40) DEFAULT NULL,
  `Furnished` varchar(40) DEFAULT NULL,
  `AC` varchar(40) DEFAULT NULL,
  `Balcony` varchar(40) DEFAULT NULL,
  `StudyTable` varchar(40) DEFAULT NULL,
  `Laundry` varchar(40) DEFAULT NULL,
  `Security` varchar(40) DEFAULT NULL,
  `MealsBreakfast` varchar(45) DEFAULT NULL,
  `MealLunch` varchar(45) NOT NULL,
  `MealDinner` varchar(45) NOT NULL,
  `RegDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `IsActive` int(1) DEFAULT NULL,
  `LastUpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblpg`
--

INSERT INTO `tblpg` (`ID`, `OwnerID`, `StateName`, `CityName`, `PGTitle`, `Type`, `RPmonth`, `norooms`, `Address`, `Image`, `Electricity`, `Parking`, `Refregerator`, `Furnished`, `AC`, `Balcony`, `StudyTable`, `Laundry`, `Security`, `MealsBreakfast`, `MealLunch`, `MealDinner`, `RegDate`, `IsActive`, `LastUpdationDate`) VALUES
(27, 12, 'Goa', 'North Goa', 'Laxmi Niwas', 'Boys', '3000', '2', 'Laxmi Nivas, Behind 20 Pt, Near Socorro Mazid And Socorro Panchayat, Socorro, Porvorim, Bardez, Goa.', '44afcf61c6914b9186800dad92ff820e.jpg', 'Yes', 'Yes', 'Yes', 'No', 'No', 'Yes', 'Yes', 'No', 'No', 'Yes', '', 'Yes', '2024-05-17 08:48:14', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblstate`
--

CREATE TABLE `tblstate` (
  `ID` int(10) NOT NULL,
  `StateName` varchar(120) DEFAULT NULL,
  `RegState` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblstate`
--

INSERT INTO `tblstate` (`ID`, `StateName`, `RegState`) VALUES
(1, 'Maharastra', '2024-01-21 01:37:03'),
(2, 'Goa', '2024-01-11 01:37:03'),
(3, 'Karnataka', '2024-05-17 08:44:51');

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `ID` int(10) NOT NULL,
  `FullName` varchar(200) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `Password` varchar(200) DEFAULT NULL,
  `RegDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `LastUpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `comm` longtext NOT NULL DEFAULT '{}'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`ID`, `FullName`, `Email`, `MobileNumber`, `dob`, `Password`, `RegDate`, `LastUpdationDate`, `comm`) VALUES
(28, 'Chandrakant Tamoskar', 'chandu@gmail.com', 8999989824, NULL, 'f925916e2754e5e03f75dd58a5733251', '2024-05-17 08:48:48', NULL, '{}'),
(29, 'Shivam Gadkerkar', 'shivam@gmail.com', 9823939192, NULL, 'f925916e2754e5e03f75dd58a5733251', '2024-05-17 09:26:13', '2024-05-17 09:27:47', '{\"28\":[{\"content\":\"_send_object_to_user_\",\"timestamp\":\"2024-05-17 09:26:36\",\"read\":true,\"sender\":\"buy\",\"pimage\":\"6647215fae516.jpg\",\"pname\":\"HP Victus Gaming Laptop\",\"pprice\":\"60000\"},{\"content\":\"Hi, I would like to buy your product\",\"timestamp\":\"2024-05-17 09:27:06\",\"read\":true,\"sender\":\"buy\"},{\"content\":\"Okay \",\"timestamp\":\"2024-05-17 09:27:26\",\"read\":true,\"sender\":\"sell\"},{\"content\":\"Do you have any meet up prefrence\",\"timestamp\":\"2024-05-17 09:27:46\",\"read\":true,\"sender\":\"sell\"}]}');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adroomie`
--
ALTER TABLE `adroomie`
  ADD PRIMARY KEY (`roomieId`),
  ADD KEY `tblroomie_ibfk_1` (`userId`),
  ADD KEY `fk_adroomie_tblpg` (`choosePG`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productId`),
  ADD KEY `product_ibfk_1` (`userId`);

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblbookpg`
--
ALTER TABLE `tblbookpg`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `tblbookpg_ibfk_1` (`Userid`),
  ADD KEY `tblbookpg_ibfk_2` (`Pgid`);

--
-- Indexes for table `tblcity`
--
ALTER TABLE `tblcity`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `City` (`City`),
  ADD KEY `tblcity_ibfk_1` (`StateID`);

--
-- Indexes for table `tblowner`
--
ALTER TABLE `tblowner`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblpages`
--
ALTER TABLE `tblpages`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblpg`
--
ALTER TABLE `tblpg`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `PGTitle` (`PGTitle`),
  ADD KEY `tblpg_ibfk_1` (`OwnerID`),
  ADD KEY `tblpg_ibfk_2` (`StateName`),
  ADD KEY `tblpg_ibfk_3` (`CityName`);

--
-- Indexes for table `tblstate`
--
ALTER TABLE `tblstate`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `StateName` (`StateName`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adroomie`
--
ALTER TABLE `adroomie`
  MODIFY `roomieId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `productId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1231235673;

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblbookpg`
--
ALTER TABLE `tblbookpg`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `tblcity`
--
ALTER TABLE `tblcity`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tblowner`
--
ALTER TABLE `tblowner`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblpages`
--
ALTER TABLE `tblpages`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblpg`
--
ALTER TABLE `tblpg`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tblstate`
--
ALTER TABLE `tblstate`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adroomie`
--
ALTER TABLE `adroomie`
  ADD CONSTRAINT `adroomie_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_adroomie_tblpg` FOREIGN KEY (`choosePG`) REFERENCES `tblpg` (`PGTitle`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblbookpg`
--
ALTER TABLE `tblbookpg`
  ADD CONSTRAINT `tblbookpg_ibfk_1` FOREIGN KEY (`Userid`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tblbookpg_ibfk_2` FOREIGN KEY (`Pgid`) REFERENCES `tblpg` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblcity`
--
ALTER TABLE `tblcity`
  ADD CONSTRAINT `tblcity_ibfk_1` FOREIGN KEY (`StateID`) REFERENCES `tblstate` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblpg`
--
ALTER TABLE `tblpg`
  ADD CONSTRAINT `tblpg_ibfk_1` FOREIGN KEY (`OwnerID`) REFERENCES `tblowner` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tblpg_ibfk_2` FOREIGN KEY (`StateName`) REFERENCES `tblstate` (`StateName`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tblpg_ibfk_3` FOREIGN KEY (`CityName`) REFERENCES `tblcity` (`City`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
