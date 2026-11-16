-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: urbanest_clean
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `adroomie`
--

DROP TABLE IF EXISTS `adroomie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `adroomie` (
  `roomieId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(10) NOT NULL,
  `choosePG` varchar(200) DEFAULT NULL,
  `name` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
  `contactNo` varchar(10) DEFAULT NULL,
  `roomDetails` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `hide` tinyint(1) DEFAULT 0,
  `Gender` varchar(10) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`roomieId`),
  KEY `tblroomie_ibfk_1` (`userId`),
  KEY `fk_adroomie_tblpg` (`choosePG`),
  CONSTRAINT `adroomie_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_adroomie_tblpg` FOREIGN KEY (`choosePG`) REFERENCES `tblpg` (`PGTitle`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adroomie`
--

LOCK TABLES `adroomie` WRITE;
/*!40000 ALTER TABLE `adroomie` DISABLE KEYS */;
INSERT INTO `adroomie` VALUES (23,28,'Laxmi Niwas','Chandrakant Tamoskar','9876500028','The room is fully furnished with a single bed, a study desk, a wardrobe, and an attached bathroom. It also includes Wi-Fi and access to the common kitchen and living area.','Laxmi Nivas, Behind 20 Pt, Near Socorro Mazid And Socorro Panchayat, Socorro, Porvorim, Bardez, Goa.',0,'Male','default-paying-guest-accommodations-5.jpg');
/*!40000 ALTER TABLE `adroomie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `userId` int(10) NOT NULL,
  `productId` int(10) NOT NULL AUTO_INCREMENT,
  `productName` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `size` varchar(90) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` int(10) NOT NULL,
  `sellersName` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `images` varchar(255) NOT NULL,
  PRIMARY KEY (`productId`),
  KEY `product_ibfk_1` (`userId`),
  CONSTRAINT `product_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1231235691 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (28,1231235672,'HP Victus Gaming Laptop','E','HP','15.6-inch (39.6 cm) ','HP Victus Gaming Laptop, AMD Ryzen 5 5600H, 4GB RTX 3050 GPU, 15.6-inch (39.6 cm) FHD IPS 144Hz, 8GB DDR4, 512GB SSD, Backlit KB, 9ms Response time (Win 11, MSO, Silver, 2.37 kg), fb0050AX',60000,'Chandrakant Tamoskar','Socorro, Porvorim, Bardez, Goa','6647215fae516.jpg'),(31,1231235673,'Ergonomic Office Chair','F','Green Soul','Standard','Mesh back office chair with lumbar support, 1 year old, no tears.',3500,'Aarav Sharma','Panaji, Goa','01b9cab1632e45756df80ddaab542e91.jpg'),(32,1231235674,'Study Desk Setup','F','IKEA','120 x 60 cm','Sturdy study table with drawer, perfect for students. Minor scratches.',2800,'Priya Nair','Bengaluru, Karnataka','174f0a05f27ba4764aa0661139671a01.jpg'),(33,1231235675,'Lenovo Netbook','E','Lenovo','10.1-inch','Lightweight netbook, good for notes and browsing. Charger included.',6500,'Rohan Desai','Pune, Maharashtra','f6930b20836e88f7d03becd3f42727a0.jpg'),(34,1231235676,'Vintage Bicycle','L','Hercules','26-inch','Classic city bike, recently serviced, new brake pads.',2500,'Sneha Kulkarni','Pune, Maharashtra','86bdaa4bb10c68d119898a5b14116be9.jpg'),(35,1231235677,'Double Door Refrigerator','E','Samsung','253 L','Frost-free fridge, 3 years old, cooling works perfectly.',11000,'Karan Mehta','Bengaluru, Karnataka','c75fa806a0089389f69efea9ecbdc186.jpg'),(36,1231235678,'Microwave Oven','E','LG','20 L','Solo microwave in great condition, moving out sale.',3200,'Ananya Iyer','Mumbai, Maharashtra','41eedad2a31e1cda6f47bb321bb28a85.jpg'),(37,1231235679,'Wooden Bookshelf','F','Urban Ladder','5 shelves','Solid wood bookshelf, easy to dismantle and move.',2200,'Vikram Rao','Mumbai, Maharashtra','1a4de19f9ffc5528154a4508934dc30e.jpg'),(38,1231235680,'Acoustic Guitar','L','Yamaha','Full size','Yamaha F310 with bag and capo. Great for beginners.',5500,'Meera Pillai','Bengaluru, Karnataka','3fbfece5195506242bf11a135817fb22.jpg'),(39,1231235681,'LED Desk Lamp','L','Philips','Adjustable','Touch control desk lamp with 3 brightness levels.',800,'Arjun Kamat','Margao, Goa','66081785de73fc1038a6a66385dfec45.jpg'),(40,1231235682,'3-Seater Sofa','F','Godrej Interio','3-seater','Comfortable fabric sofa, beige, cushions included.',9000,'Isha Patil','Bengaluru, Karnataka','702ef443f8f453e49c280464990ca80f.jpg'),(41,1231235683,'Wired Earphones','E','Apple','Standard','Original EarPods, barely used, with box.',900,'Rahul Verma','Pune, Maharashtra','4247f931ad6b5f1f2d1006fd6bc5bc74.jpg'),(42,1231235684,'Laptop Backpack','L','Wildcraft','30 L','Water-resistant backpack with laptop sleeve.',1200,'Divya Shetty','Mysuru, Karnataka','3ce2e1e74109e1be13a800750d8d13d0.jpg'),(32,1231235685,'Table Fan','E','Usha','400 mm','Powerful table fan, 3 speeds, oscillation works.',1100,'Priya Nair','Bengaluru, Karnataka','7b5d9ecf02a4ba52b08823929aec545d.jpg'),(35,1231235686,'32-inch LED TV','E','Mi','32-inch','Smart TV with remote and wall mount.',8500,'Karan Mehta','Bengaluru, Karnataka','5558eb70233661f643222b7330472124.jpg'),(37,1231235687,'Wooden Coffee Table','F','Pepperfry','Medium','Sheesham wood coffee table, solid build.',2600,'Vikram Rao','Mumbai, Maharashtra','b9bb38564989745eea7e476b9af86514.jpg'),(40,1231235688,'Retro Wall Clock','L','Ajanta','12-inch','Red retro wall clock, silent sweep movement.',450,'Isha Patil','Bengaluru, Karnataka','096e8ff2fefb55b8a69524e892c3222b.jpg'),(33,1231235689,'Inkjet Printer','E','HP','Compact','HP DeskJet all-in-one, prints and scans. New cartridge.',2900,'Rohan Desai','Pune, Maharashtra','30444d33e7b885310a13ec833003b3f5.jpg'),(41,1231235690,'Film Camera','E','Canon','35 mm','Vintage film camera with zoom lens, collector piece.',7000,'Rahul Verma','Pune, Maharashtra','28c525d0581b51d7dc2618084c6aab0b.jpg');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_messages`
--

DROP TABLE IF EXISTS `product_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `body` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_receiver_unread` (`receiver_id`,`is_read`),
  KEY `idx_thread` (`product_id`,`sender_id`,`receiver_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_messages`
--

LOCK TABLES `product_messages` WRITE;
/*!40000 ALTER TABLE `product_messages` DISABLE KEYS */;
INSERT INTO `product_messages` VALUES (1,1231235672,29,28,'Hi, I would like to buy your product',1,'2024-05-17 09:27:06'),(2,1231235672,28,29,'Okay',1,'2024-05-17 09:27:26'),(3,1231235672,28,29,'Do you have any meet up prefrence',1,'2024-05-17 09:27:46'),(4,1231235672,29,28,'hey',1,'2026-11-16 01:51:35'),(5,1231235672,28,29,'hellooo',1,'2026-11-16 01:53:04'),(6,1231235672,28,29,'stylee',1,'2026-11-16 01:53:41'),(7,1231235673,34,31,'Hi, is the chair still available?',1,'2026-11-10 07:59:57'),(8,1231235673,31,34,'Yes, it is!',1,'2026-11-10 08:19:57'),(9,1231235673,34,31,'Can you do 3000?',1,'2026-11-10 08:39:57'),(10,1231235673,31,34,'Okay, 3200 final.',1,'2026-11-10 08:59:57'),(11,1231235673,34,31,'Deal, I will pick it up tomorrow.',0,'2026-11-10 09:19:57'),(12,1231235680,31,38,'Hey, does the guitar have any scratches?',1,'2026-11-11 07:59:57'),(13,1231235680,38,31,'Just a tiny one on the back, plays perfectly.',1,'2026-11-11 08:19:57'),(14,1231235680,31,38,'Great, can I come try it?',0,'2026-11-11 08:39:57'),(15,1231235677,36,35,'Is the fridge price negotiable?',1,'2026-11-12 07:59:57'),(16,1231235677,35,36,'A little. What do you have in mind?',1,'2026-11-12 08:19:57'),(17,1231235677,36,35,'10000?',0,'2026-11-12 08:39:57'),(18,1231235686,33,35,'Does the TV come with the remote?',1,'2026-11-13 07:59:57'),(19,1231235686,35,33,'Yes, remote and wall mount both included.',0,'2026-11-13 08:19:57');
/*!40000 ALTER TABLE `product_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roommate_blocks`
--

DROP TABLE IF EXISTS `roommate_blocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roommate_blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blocker_id` int(10) NOT NULL,
  `blocked_id` int(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_block` (`blocker_id`,`blocked_id`),
  KEY `idx_blocker` (`blocker_id`),
  KEY `fk_rblk_blocked` (`blocked_id`),
  CONSTRAINT `fk_rblk_blocked` FOREIGN KEY (`blocked_id`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_rblk_blocker` FOREIGN KEY (`blocker_id`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roommate_blocks`
--

LOCK TABLES `roommate_blocks` WRITE;
/*!40000 ALTER TABLE `roommate_blocks` DISABLE KEYS */;
/*!40000 ALTER TABLE `roommate_blocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roommate_conversations`
--

DROP TABLE IF EXISTS `roommate_conversations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roommate_conversations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user1_id` int(10) NOT NULL,
  `user2_id` int(10) NOT NULL,
  `last_message_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_conversation` (`user1_id`,`user2_id`),
  KEY `idx_user1` (`user1_id`),
  KEY `idx_user2` (`user2_id`),
  CONSTRAINT `fk_rconv_user1` FOREIGN KEY (`user1_id`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_rconv_user2` FOREIGN KEY (`user2_id`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roommate_conversations`
--

LOCK TABLES `roommate_conversations` WRITE;
/*!40000 ALTER TABLE `roommate_conversations` DISABLE KEYS */;
INSERT INTO `roommate_conversations` VALUES (1,28,29,'2026-11-15 20:20:58','2026-11-02 04:56:56'),(2,29,30,'2026-11-15 16:34:02','2026-11-02 05:51:36'),(3,32,34,'2026-11-06 03:24:57','2026-11-06 02:09:57'),(4,32,40,'2026-11-07 03:09:57','2026-11-07 02:09:57'),(5,33,41,'2026-11-08 03:09:57','2026-11-08 02:09:57'),(6,31,37,'2026-11-09 02:54:57','2026-11-09 02:09:57');
/*!40000 ALTER TABLE `roommate_conversations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roommate_listings`
--

DROP TABLE IF EXISTS `roommate_listings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roommate_listings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `locality` varchar(200) DEFAULT NULL,
  `full_address` text DEFAULT NULL,
  `monthly_rent` int(10) NOT NULL,
  `security_deposit` int(10) DEFAULT 0,
  `room_type` enum('Single','Double','Shared') DEFAULT 'Single',
  `available_spaces` int(5) DEFAULT 1,
  `accommodation_type` enum('PG','Flat','Apartment','Hostel') DEFAULT 'PG',
  `available_from` date DEFAULT NULL,
  `furnished` enum('Fully Furnished','Semi-Furnished','Unfurnished') DEFAULT 'Unfurnished',
  `amenities` text DEFAULT NULL COMMENT 'JSON array of amenity strings',
  `gender_preference` enum('Male','Female','Any') DEFAULT 'Any',
  `preferred_age_min` int(3) DEFAULT 18,
  `preferred_age_max` int(3) DEFAULT 45,
  `lifestyle_prefs` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `images` text DEFAULT NULL COMMENT 'JSON array of image filenames',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_city` (`city`),
  KEY `idx_rent` (`monthly_rent`),
  KEY `idx_active` (`is_active`),
  CONSTRAINT `fk_rl_user` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roommate_listings`
--

LOCK TABLES `roommate_listings` WRITE;
/*!40000 ALTER TABLE `roommate_listings` DISABLE KEYS */;
INSERT INTO `roommate_listings` VALUES (1,29,'Near kristu Jyanti','Banglore','kothnur','windsor garden layout',8000,30000,'Double',1,'Apartment','2026-11-20','Fully Furnished','[\"Wi-Fi\",\"CCTV\",\"Housekeeping\"]','Male',18,27,'0','Clean room','[\"listing_6ae7a0ad6acf0.jpeg\"]',1,'2026-11-01 20:27:25',NULL),(2,32,'Spacious 2BHK near Koramangala','Bengaluru','Koramangala','6th Block, Koramangala, Bengaluru',9000,20000,'Double',1,'Flat','2026-12-08','Fully Furnished','[\"Washing Machine\",\"Housekeeping\",\"Water Purifier\",\"CCTV\"]','Female',18,35,'Working women preferred, non-smoker','Bright 2BHK with balcony, 5 min walk to the metro and cafes. Looking for one flatmate.','[\"listing_544d403d29b5.jpg\"]',1,'2026-10-22 02:09:57',NULL),(3,33,'Room in shared flat, Baner','Pune','Baner','Near Baner Road, Baner, Pune',7500,15000,'Single',1,'Flat','2026-12-05','Semi-Furnished','[\"Refrigerator\",\"Washing Machine\",\"Parking\",\"CCTV\"]','Male',18,35,'Working professionals, vegetarian preferred','Private room in a 3BHK with two working professionals. Gym and pool in the society.','[\"listing_0a21d821c98e.jpg\"]',1,'2026-10-25 02:09:57',NULL),(4,35,'Flatmate wanted in Bandra','Mumbai','Bandra West','Hill Road, Bandra West, Mumbai',16000,40000,'Shared',1,'Apartment','2026-11-21','Fully Furnished','[\"Wi-Fi\",\"AC\",\"Parking\",\"CCTV\",\"Refrigerator\",\"Water Purifier\"]','Male',18,35,'Clean and social, okay with pets','Sea-facing apartment, fully furnished with Wi-Fi, maid and cook.','[\"listing_87a57e409cf4.jpg\"]',1,'2026-10-28 02:09:57',NULL),(5,37,'Single room near Panjim market','Panaji','Altinho','Altinho, Panaji, Goa',6000,12000,'Single',1,'Flat','2026-11-22','Semi-Furnished','[\"Water Purifier\",\"CCTV\",\"Wi-Fi\",\"Washing Machine\"]','Any',18,35,'Students OK, quiet neighbourhood','Cosy single room in a 2BHK, walking distance to Panjim market and bus stand.','[\"listing_dcf7a4422e8a.jpg\"]',1,'2026-10-31 02:09:57',NULL),(6,38,'Whitefield 3BHK, one room free','Bengaluru','Whitefield','Near ITPL, Whitefield, Bengaluru',8500,25000,'Single',1,'Apartment','2026-12-03','Fully Furnished','[\"Water Purifier\",\"Housekeeping\",\"Power Backup\",\"TV\"]','Female',18,35,'Working women, early risers','Gated society with power backup, gym and 24x7 security.','[\"listing_1add106fe1c7.jpg\"]',1,'2026-11-03 02:09:57',NULL),(7,40,'Shared room near Christ University','Bengaluru','Hosur Road','Hosur Road, Near Christ University, Bengaluru',5500,10000,'Shared',2,'PG','2026-12-13','Fully Furnished','[\"Housekeeping\",\"Geyser\",\"Parking\",\"AC\"]','Female',18,35,'Students only, vegetarian','Two spots available in a shared room. Meals included.','[\"listing_b0a429781c57.jpg\"]',1,'2026-11-06 02:09:57',NULL),(8,41,'Hinjewadi flat near Phase 2','Pune','Hinjewadi','Phase 2, Hinjewadi, Pune',7000,14000,'Double',1,'Flat','2026-12-01','Semi-Furnished','[\"AC\",\"Geyser\",\"TV\",\"Water Purifier\",\"Power Backup\",\"Housekeeping\",\"Wi-Fi\"]','Male',18,35,'IT professionals, non-smoker','Two-bedroom flat close to the IT park. Looking for one more flatmate.','[\"listing_3f1b1498d4ea.jpg\"]',1,'2026-11-09 02:09:57',NULL);
/*!40000 ALTER TABLE `roommate_listings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roommate_messages`
--

DROP TABLE IF EXISTS `roommate_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roommate_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `conversation_id` int(11) NOT NULL,
  `sender_id` int(10) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_conversation` (`conversation_id`),
  KEY `idx_sender` (`sender_id`),
  KEY `idx_read` (`is_read`),
  CONSTRAINT `fk_rmsg_conv` FOREIGN KEY (`conversation_id`) REFERENCES `roommate_conversations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_rmsg_sender` FOREIGN KEY (`sender_id`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roommate_messages`
--

LOCK TABLES `roommate_messages` WRITE;
/*!40000 ALTER TABLE `roommate_messages` DISABLE KEYS */;
INSERT INTO `roommate_messages` VALUES (1,1,29,'Hii',1,'2026-11-02 04:57:03'),(2,1,29,'How are you doing',1,'2026-11-02 04:57:11'),(3,1,29,'i am fine',1,'2026-11-02 04:58:10'),(4,1,29,'heyy',1,'2026-11-02 04:59:58'),(5,1,29,'hey',1,'2026-11-02 05:09:58'),(6,1,29,'heyy',1,'2026-11-02 05:10:04'),(7,1,29,'Hiii',1,'2026-11-02 05:10:36'),(8,1,29,'noo',1,'2026-11-02 05:10:53'),(11,1,29,'Heyy',1,'2026-11-02 05:16:05'),(12,1,28,'Wassup',1,'2026-11-02 05:17:17'),(13,2,29,'hey',1,'2026-11-02 05:51:48'),(14,2,30,'hiii',1,'2026-11-02 05:52:45'),(15,2,30,'Suppp',1,'2026-11-02 06:21:37'),(16,2,29,'hi',0,'2026-11-15 16:34:02'),(17,1,28,'hi',1,'2026-11-15 16:37:40'),(18,1,28,'Hey',1,'2026-11-15 16:38:08'),(19,1,28,'Hi',1,'2026-11-15 16:38:22'),(20,1,28,'hi',1,'2026-11-15 16:51:03'),(21,1,28,'Hey',1,'2026-11-15 16:51:15'),(22,1,28,'Hey',1,'2026-11-15 16:51:39'),(23,1,28,'tgg',1,'2026-11-15 20:15:40'),(24,1,29,'hii',1,'2026-11-15 20:17:22'),(25,1,28,'hey',1,'2026-11-15 20:18:08'),(26,1,29,'Stylee',1,'2026-11-15 20:20:58'),(27,3,34,'Hi! Is the room in your flat still available?',1,'2026-11-06 02:24:57'),(28,3,32,'Yes it is! When are you planning to move in?',1,'2026-11-06 02:39:57'),(29,3,34,'Probably next month. Can I come see it this weekend?',1,'2026-11-06 02:54:57'),(30,3,32,'Sure, Saturday 11 am works. I will share the location.',1,'2026-11-06 03:09:57'),(31,3,34,'Perfect, see you then!',0,'2026-11-06 03:24:57'),(32,4,40,'Hey, saw your listing. What is the deposit?',1,'2026-11-07 02:24:57'),(33,4,32,'It is two months of rent, refundable.',1,'2026-11-07 02:39:57'),(34,4,40,'Got it. Is cooking allowed?',1,'2026-11-07 02:54:57'),(35,4,32,'Yes, the kitchen is fully equipped.',0,'2026-11-07 03:09:57'),(36,5,33,'Hello! I think our profiles match really well.',1,'2026-11-08 02:24:57'),(37,5,41,'Haha yes, 85% match! Are you working nearby?',1,'2026-11-08 02:39:57'),(38,5,33,'Yes, Phase 1. Shall we talk over a call?',1,'2026-11-08 02:54:57'),(39,5,41,'Sure, ping me your number.',0,'2026-11-08 03:09:57'),(40,6,31,'Hi, is the Panjim room available from the 1st?',1,'2026-11-09 02:24:57'),(41,6,37,'Yes, it is free from the 1st.',1,'2026-11-09 02:39:57'),(42,6,31,'Great, I will confirm by tomorrow.',0,'2026-11-09 02:54:57');
/*!40000 ALTER TABLE `roommate_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roommate_preferences`
--

DROP TABLE IF EXISTS `roommate_preferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roommate_preferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `preferred_city` varchar(100) NOT NULL,
  `preferred_locality` varchar(200) DEFAULT NULL,
  `budget_min` int(10) DEFAULT 0,
  `budget_max` int(10) DEFAULT 0,
  `accommodation_type` enum('PG','Flat','Apartment','Hostel','Any') DEFAULT 'Any',
  `room_type` enum('Single','Double','Shared','Any') DEFAULT 'Any',
  `move_in_date` date DEFAULT NULL,
  `people_required` int(3) DEFAULT 1,
  `gender_preference` enum('Male','Female','Any') DEFAULT 'Any',
  `preferred_age_min` int(3) DEFAULT 18,
  `preferred_age_max` int(3) DEFAULT 45,
  `food_preference` enum('Vegetarian','Non-Vegetarian','Both') DEFAULT 'Both',
  `smoking` enum('Yes','No','Occasionally') DEFAULT 'No',
  `drinking` enum('Yes','No','Occasionally') DEFAULT 'No',
  `work_study_status` enum('Working','Studying','Other') DEFAULT 'Working',
  `additional_requirements` text DEFAULT NULL,
  `lifestyle_prefs` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_city` (`preferred_city`),
  KEY `idx_budget` (`budget_max`),
  CONSTRAINT `fk_rpref_user` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roommate_preferences`
--

LOCK TABLES `roommate_preferences` WRITE;
/*!40000 ALTER TABLE `roommate_preferences` DISABLE KEYS */;
INSERT INTO `roommate_preferences` VALUES (3,29,'banglore','kothnur',9000,18000,'Flat','Single','2026-12-20',1,'Male',23,33,'Vegetarian','No','No','Working','Near metro station','Updated lifestyle preference',1,'2026-11-01 20:39:36','2026-11-01 20:41:52'),(4,31,'Panaji',NULL,4000,8000,'Any','Any','2026-12-06',1,'Male',18,45,'Both','No','No','Studying','Close to college or office, good Wi-Fi','Clean, quiet and friendly',1,'2026-11-16 06:39:57',NULL),(5,34,'Pune',NULL,5000,9000,'Any','Any','2026-12-06',1,'Female',18,45,'Both','No','No','Studying','Close to college or office, good Wi-Fi','Clean, quiet and friendly',1,'2026-11-16 06:39:57',NULL),(6,36,'Mumbai',NULL,10000,18000,'Any','Any','2026-12-06',1,'Female',18,45,'Both','No','No','Studying','Close to college or office, good Wi-Fi','Clean, quiet and friendly',1,'2026-11-16 06:39:57',NULL),(7,39,'Margao',NULL,4000,7000,'Any','Any','2026-12-06',1,'Male',18,45,'Both','No','No','Studying','Close to college or office, good Wi-Fi','Clean, quiet and friendly',1,'2026-11-16 06:39:57',NULL),(8,42,'Mysuru',NULL,5000,9000,'Any','Any','2026-12-06',1,'Female',18,45,'Both','No','No','Working','Close to college or office, good Wi-Fi','Clean, quiet and friendly',1,'2026-11-16 06:39:57',NULL),(9,33,'Pune',NULL,6000,10000,'Any','Any','2026-12-06',1,'Male',18,45,'Both','No','No','Working','Close to college or office, good Wi-Fi','Clean, quiet and friendly',1,'2026-11-16 06:39:57',NULL);
/*!40000 ALTER TABLE `roommate_preferences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roommate_profiles`
--

DROP TABLE IF EXISTS `roommate_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roommate_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `profile_photo` varchar(255) DEFAULT 'default-profile.png',
  `age` int(3) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `college_company` varchar(200) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `preferred_locality` varchar(200) DEFAULT NULL,
  `monthly_budget` int(10) DEFAULT NULL,
  `move_in_date` date DEFAULT NULL,
  `food_preference` enum('Vegetarian','Non-Vegetarian','Both') DEFAULT 'Both',
  `smoking` enum('Yes','No','Occasionally') DEFAULT 'No',
  `drinking` enum('Yes','No','Occasionally') DEFAULT 'No',
  `sleeping_schedule` enum('Early Bird','Night Owl','Flexible') DEFAULT 'Flexible',
  `cleanliness` enum('Very Clean','Moderate','Relaxed') DEFAULT 'Moderate',
  `social_lifestyle` enum('Very Social','Moderate','Introvert') DEFAULT 'Moderate',
  `about_me` text DEFAULT NULL,
  `is_looking` tinyint(1) DEFAULT 1 COMMENT '1=actively looking, 0=not looking',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_profile` (`user_id`),
  KEY `idx_city` (`city`),
  KEY `idx_budget` (`monthly_budget`),
  CONSTRAINT `fk_rp_user` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roommate_profiles`
--

LOCK TABLES `roommate_profiles` WRITE;
/*!40000 ALTER TABLE `roommate_profiles` DISABLE KEYS */;
INSERT INTO `roommate_profiles` VALUES (1,28,'profile_6af9e3d6b846c.jpeg',23,'Male','sgd','dsjs','Banglore','kothnur',2000,'0000-00-00','Both','No','No','Flexible','Moderate','Moderate','',1,'2026-11-15 16:54:46',NULL),(2,31,'profile_a3b1d5d04bb3.svg',22,'Male','Student','Goa University','Panaji','',8000,'2026-12-13','Both','No','No','Early Bird','Relaxed','Moderate','Hi, I am Aarav, Student at Goa University. Looking for a friendly, tidy roommate in Panaji.',1,'2026-11-16 06:39:57',NULL),(3,32,'profile_3fcc272de2f9.svg',24,'Female','Software Engineer','Infosys','Bengaluru','',12000,'2026-12-22','Non-Vegetarian','No','No','Early Bird','Very Clean','Moderate','Hi, I am Priya, Software Engineer at Infosys. Looking for a friendly, tidy roommate in Bengaluru.',1,'2026-11-16 06:39:57',NULL),(4,33,'profile_36a56b76daae.svg',25,'Male','Data Analyst','TCS','Pune','',7000,'2026-12-20','Non-Vegetarian','No','No','Night Owl','Relaxed','Introvert','Hi, I am Rohan, Data Analyst at TCS. Looking for a friendly, tidy roommate in Pune.',1,'2026-11-16 06:39:57',NULL),(5,34,'profile_6c9222f40032.svg',21,'Female','Student','Fergusson College','Pune','',12000,'2026-12-07','Non-Vegetarian','Occasionally','No','Early Bird','Moderate','Moderate','Hi, I am Sneha, Student at Fergusson College. Looking for a friendly, tidy roommate in Pune.',1,'2026-11-16 06:39:57',NULL),(6,35,'profile_e9a02052de4b.svg',27,'Male','Product Designer','Swiggy','Bengaluru','',9000,'2026-11-24','Both','No','No','Flexible','Very Clean','Moderate','Hi, I am Karan, Product Designer at Swiggy. Looking for a friendly, tidy roommate in Bengaluru.',1,'2026-11-16 06:39:57',NULL),(7,36,'profile_778a402e9258.svg',23,'Female','Student','IIT Bombay','Mumbai','',10000,'2026-12-18','Both','No','Occasionally','Flexible','Very Clean','Very Social','Hi, I am Ananya, Student at IIT Bombay. Looking for a friendly, tidy roommate in Mumbai.',1,'2026-11-16 06:39:57',NULL),(8,37,'profile_01ff54f54410.svg',26,'Male','Bank Officer','HDFC Bank','Mumbai','',6000,'2026-12-11','Non-Vegetarian','Occasionally','No','Flexible','Very Clean','Introvert','Hi, I am Vikram, Bank Officer at HDFC Bank. Looking for a friendly, tidy roommate in Mumbai.',1,'2026-11-16 06:39:57',NULL),(9,38,'profile_bc90b72befe7.svg',25,'Female','Nurse','Manipal Hospital','Bengaluru','',12000,'2026-11-30','Vegetarian','No','Yes','Night Owl','Moderate','Moderate','Hi, I am Meera, Nurse at Manipal Hospital. Looking for a friendly, tidy roommate in Bengaluru.',1,'2026-11-16 06:39:57',NULL),(10,39,'profile_10e1949cf7d6.svg',23,'Male','Student','NIT Goa','Margao','',12000,'2026-11-27','Non-Vegetarian','No','Occasionally','Early Bird','Moderate','Introvert','Hi, I am Arjun, Student at NIT Goa. Looking for a friendly, tidy roommate in Margao.',1,'2026-11-16 06:39:57',NULL),(11,40,'profile_522781c960fa.svg',22,'Female','Student','Christ University','Bengaluru','',12000,'2026-11-22','Both','Occasionally','No','Flexible','Moderate','Very Social','Hi, I am Isha, Student at Christ University. Looking for a friendly, tidy roommate in Bengaluru.',1,'2026-11-16 06:39:57',NULL),(12,41,'profile_133604b3925b.svg',28,'Male','Marketing Executive','Amazon','Pune','',7000,'2026-11-26','Both','No','Occasionally','Night Owl','Relaxed','Moderate','Hi, I am Rahul, Marketing Executive at Amazon. Looking for a friendly, tidy roommate in Pune.',1,'2026-11-16 06:39:57',NULL),(13,42,'profile_09b6aa9a1f7d.svg',26,'Female','Content Writer','Freelance','Mysuru','',9000,'2026-12-10','Non-Vegetarian','No','Occasionally','Flexible','Moderate','Very Social','Hi, I am Divya, Content Writer at Freelance. Looking for a friendly, tidy roommate in Mysuru.',1,'2026-11-16 06:39:57',NULL);
/*!40000 ALTER TABLE `roommate_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roommate_requests`
--

DROP TABLE IF EXISTS `roommate_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roommate_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(10) NOT NULL,
  `receiver_id` int(10) NOT NULL,
  `listing_id` int(11) DEFAULT NULL COMMENT 'Optional: which listing triggered the request',
  `status` enum('Pending','Accepted','Rejected','Cancelled') DEFAULT 'Pending',
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_request` (`sender_id`,`receiver_id`),
  KEY `idx_sender` (`sender_id`),
  KEY `idx_receiver` (`receiver_id`),
  KEY `idx_status` (`status`),
  KEY `fk_rreq_listing` (`listing_id`),
  CONSTRAINT `fk_rreq_listing` FOREIGN KEY (`listing_id`) REFERENCES `roommate_listings` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_rreq_receiver` FOREIGN KEY (`receiver_id`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_rreq_sender` FOREIGN KEY (`sender_id`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roommate_requests`
--

LOCK TABLES `roommate_requests` WRITE;
/*!40000 ALTER TABLE `roommate_requests` DISABLE KEYS */;
INSERT INTO `roommate_requests` VALUES (1,28,29,1,'Accepted',NULL,'2026-11-02 04:53:50','2026-11-02 04:56:56'),(2,30,29,1,'Accepted',NULL,'2026-11-02 05:50:29','2026-11-02 05:51:36'),(3,34,32,2,'Accepted','Hi, I would like to connect about your listing.','2026-11-03 23:09:57',NULL),(4,40,32,2,'Accepted','Hi, I would like to connect about your listing.','2026-11-04 23:09:57',NULL),(5,33,41,8,'Accepted','Hi, I would like to connect about your listing.','2026-11-05 23:09:57',NULL),(6,31,37,5,'Accepted','Hi, I would like to connect about your listing.','2026-11-06 23:09:57',NULL),(7,36,35,4,'Pending','Hi, I would like to connect about your listing.','2026-11-07 23:09:57',NULL),(8,38,35,4,'Pending','Hi, I would like to connect about your listing.','2026-11-08 23:09:57',NULL),(9,42,40,7,'Pending','Hi, I would like to connect about your listing.','2026-11-09 23:09:57',NULL),(10,39,37,5,'Rejected','Hi, I would like to connect about your listing.','2026-11-10 23:09:57',NULL),(11,35,38,6,'Pending','Hi, I would like to connect about your listing.','2026-11-11 23:09:57',NULL);
/*!40000 ALTER TABLE `roommate_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbladmin`
--

DROP TABLE IF EXISTS `tbladmin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbladmin` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `AdminName` varchar(120) DEFAULT NULL,
  `UserName` varchar(120) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Email` varchar(120) DEFAULT NULL,
  `Password` varchar(120) DEFAULT NULL,
  `AdminRegdate` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbladmin`
--

LOCK TABLES `tbladmin` WRITE;
/*!40000 ALTER TABLE `tbladmin` DISABLE KEYS */;
INSERT INTO `tbladmin` VALUES (1,'Admin','admin',9876500001,'admin@example.com','$2y$10$6mhMeNLHusxTBPKMcB/Vc.AAN.uItOuxrtVF.UKlX8cLFkWbrZ.aa',NULL);
/*!40000 ALTER TABLE `tbladmin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblbookpg`
--

DROP TABLE IF EXISTS `tblbookpg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblbookpg` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `Userid` int(10) DEFAULT NULL,
  `Pgid` int(10) DEFAULT NULL,
  `BookingNumber` int(10) NOT NULL,
  `CheckinDate` date DEFAULT NULL,
  `UserMsg` varchar(500) DEFAULT NULL,
  `BookingDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `Remark` varchar(50) DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL,
  `RemDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`ID`),
  KEY `tblbookpg_ibfk_1` (`Userid`),
  KEY `tblbookpg_ibfk_2` (`Pgid`),
  CONSTRAINT `tblbookpg_ibfk_1` FOREIGN KEY (`Userid`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tblbookpg_ibfk_2` FOREIGN KEY (`Pgid`) REFERENCES `tblpg` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblbookpg`
--

LOCK TABLES `tblbookpg` WRITE;
/*!40000 ALTER TABLE `tblbookpg` DISABLE KEYS */;
INSERT INTO `tblbookpg` VALUES (52,28,27,967985075,'0000-00-00','I\'m interested in renting a room at your PG. Could we arrange a time to meet and discuss the details?','2024-05-17 08:57:08','Thank you for reaching out.','Confirmed','2024-05-17 09:01:19'),(53,29,27,382119022,'2026-11-11','Stylee','2026-11-01 18:58:55','Okays','Confirmed','2026-11-01 20:11:38'),(54,30,27,446663302,'2026-11-02','i want to book this pg ','2026-11-02 05:50:04',NULL,NULL,NULL),(55,31,27,966377394,'2026-11-25','I would like to book a bed from next month. Is it available?','2026-10-17 02:09:57',NULL,NULL,NULL),(56,32,29,133361894,'2026-12-02','Can I visit the PG this weekend before confirming?','2026-10-18 01:09:57','Room reserved. See you on the check-in date.','Confirmed','2026-10-19 02:09:57'),(57,33,30,550148659,'2026-12-28','Looking for a single room near my college. Please confirm availability.','2026-10-19 00:09:57','Welcome! Please carry your ID proof at check-in.','Confirmed','2026-10-20 02:09:57'),(58,34,31,484294495,'2026-11-23','Interested in a twin-sharing room. Are meals included?','2026-10-19 23:09:57','Requested room type is full this month.','Cancelled','2026-10-21 02:09:57'),(59,35,32,698424036,'2026-12-28','Moving to the city for a new job, need a room from the 1st.','2026-10-20 22:09:57',NULL,NULL,NULL),(60,36,33,895952870,'2026-11-24','I would like to book a bed from next month. Is it available?','2026-10-22 02:09:57','Room reserved. See you on the check-in date.','Confirmed','2026-10-23 02:09:57'),(61,37,34,618599822,'2026-12-11','Can I visit the PG this weekend before confirming?','2026-10-23 01:09:57',NULL,NULL,NULL),(62,38,35,972141340,'2026-12-09','Looking for a single room near my college. Please confirm availability.','2026-10-24 00:09:57','Room reserved. See you on the check-in date.','Confirmed','2026-10-25 02:09:57'),(63,39,36,430626207,'2026-12-14','Interested in a twin-sharing room. Are meals included?','2026-10-24 23:09:57','Sorry, no rooms available for those dates.','Cancelled','2026-10-26 02:09:57'),(64,40,37,444473018,'2026-11-29','Moving to the city for a new job, need a room from the 1st.','2026-10-25 22:09:57','Room reserved. See you on the check-in date.','Confirmed','2026-10-27 02:09:57'),(65,41,38,792440149,'2026-11-28','I would like to book a bed from next month. Is it available?','2026-10-27 02:09:57',NULL,NULL,NULL),(66,42,41,981580201,'2026-12-28','Can I visit the PG this weekend before confirming?','2026-10-28 01:09:57','Room reserved. See you on the check-in date.','Confirmed','2026-10-29 02:09:57'),(67,31,42,469737021,'2026-12-23','Looking for a single room near my college. Please confirm availability.','2026-10-29 00:09:57',NULL,NULL,NULL),(68,32,43,553094388,'2026-12-05','Interested in a twin-sharing room. Are meals included?','2026-10-29 23:09:57','Room reserved. See you on the check-in date.','Confirmed','2026-10-31 02:09:57'),(69,33,44,260801051,'2026-12-18','Moving to the city for a new job, need a room from the 1st.','2026-10-30 22:09:57','Sorry, no rooms available for those dates.','Cancelled','2026-11-01 02:09:57'),(70,34,45,138194539,'2026-12-21','I would like to book a bed from next month. Is it available?','2026-11-01 02:09:57',NULL,NULL,NULL),(71,35,46,988896595,'2026-11-23','Can I visit the PG this weekend before confirming?','2026-11-02 01:09:57','Welcome! Please carry your ID proof at check-in.','Confirmed','2026-11-03 02:09:57'),(72,36,28,616457290,'2026-11-19','Looking for a single room near my college. Please confirm availability.','2026-11-03 00:09:57',NULL,NULL,NULL),(73,37,27,867929081,'2026-11-27','Interested in a twin-sharing room. Are meals included?','2026-11-03 23:09:57','Welcome! Please carry your ID proof at check-in.','Confirmed','2026-11-05 02:09:57'),(74,38,29,730287576,'2026-12-30','Moving to the city for a new job, need a room from the 1st.','2026-11-04 22:09:57',NULL,NULL,NULL),(75,39,30,572669408,'2026-12-11','I would like to book a bed from next month. Is it available?','2026-11-06 02:09:57','Welcome! Please carry your ID proof at check-in.','Confirmed','2026-11-07 02:09:57'),(76,40,31,178974072,'2026-12-29','Can I visit the PG this weekend before confirming?','2026-11-07 01:09:57','Requested room type is full this month.','Cancelled','2026-11-08 02:09:57');
/*!40000 ALTER TABLE `tblbookpg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcity`
--

DROP TABLE IF EXISTS `tblcity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcity` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `StateID` int(10) DEFAULT NULL,
  `City` varchar(120) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ID`),
  UNIQUE KEY `City` (`City`),
  KEY `tblcity_ibfk_1` (`StateID`),
  CONSTRAINT `tblcity_ibfk_1` FOREIGN KEY (`StateID`) REFERENCES `tblstate` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcity`
--

LOCK TABLES `tblcity` WRITE;
/*!40000 ALTER TABLE `tblcity` DISABLE KEYS */;
INSERT INTO `tblcity` VALUES (1,1,'Mumbai ','2024-01-11 11:45:52'),(2,2,'North Goa','2024-01-11 11:46:14'),(3,2,'South Goa','2024-01-11 11:46:14'),(9,1,'Pune','2024-05-17 08:44:04'),(10,1,'Sawantwadi','2024-05-17 08:44:28'),(11,3,'Bengaluru','2026-10-01 09:00:00'),(12,3,'Mysuru','2026-10-01 09:00:00'),(13,3,'Mangaluru','2026-10-01 09:00:00');
/*!40000 ALTER TABLE `tblcity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblowner`
--

DROP TABLE IF EXISTS `tblowner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblowner` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `FullName` varchar(200) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Password` varchar(120) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `VerificationFile` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblowner`
--

LOCK TABLES `tblowner` WRITE;
/*!40000 ALTER TABLE `tblowner` DISABLE KEYS */;
INSERT INTO `tblowner` VALUES (12,'Tejas Naik','tejas@example.com',9876500012,'$2y$10$6mhMeNLHusxTBPKMcB/Vc.AAN.uItOuxrtVF.UKlX8cLFkWbrZ.aa','2024-05-17 08:47:13','uploads/images.png',1),(13,'Sam','sam@example.com',9876500013,'$2y$10$6mhMeNLHusxTBPKMcB/Vc.AAN.uItOuxrtVF.UKlX8cLFkWbrZ.aa','2026-11-16 06:09:29','uploads/8bb6924cd564c8e7cab595f34d1828f2.jpg',1),(14,'Nikhil Joshi','nikhil.joshi@example.com',9822012345,'$2y$10$6mhMeNLHusxTBPKMcB/Vc.AAN.uItOuxrtVF.UKlX8cLFkWbrZ.aa','2026-11-16 06:39:57','uploads/images.png',0);
/*!40000 ALTER TABLE `tblowner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblpages`
--

DROP TABLE IF EXISTS `tblpages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblpages` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PageType` varchar(200) DEFAULT NULL,
  `PageTitle` mediumtext DEFAULT NULL,
  `PageDescription` mediumtext DEFAULT NULL,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblpages`
--

LOCK TABLES `tblpages` WRITE;
/*!40000 ALTER TABLE `tblpages` DISABLE KEYS */;
INSERT INTO `tblpages` VALUES (1,'aboutus','About Us','<div class=\"black\" style=\"text-align: left;\"><span style=\"font-size:16.0pt;mso-bidi-font-size:11.0pt;\r\n line-height:115%;\r\nfont-family:&quot;\r\nCalibri&quot;\r\n,\r\nsans-serif;\r\nmso-ascii-theme-font:minor-latin;\r\nmso-fareast-font-family:Calibri;\r\nmso-fareast-theme-font:minor-latin;\r\nmso-hansi-theme-font: minor-latin;\r\nmso-bidi-font-family:&quot;\r\nTimes New Roman&quot;\r\n;\r\nmso-bidi-theme-font:minor-bidi;\r\nmso-ansi-language:EN-US;\r\nmso-fareast-language:EN-US;\r\nmso-bidi-language:AR-SA\">Our\r\n vision at Urban Nest is to go beyond the conventional approach to PG accommodations. We aim to provide a platform where comfort,\r\nstyle,\r\nand community converge seamlessly. From booking the perfect PG to discovering unique thrift store finds and connecting with compatible roommates,\r\nUrban Nest is your partner in creating a living space that resonates with your individuality.</span><br></div>','2024-04-03 13:32:26'),(2,'contactus','Contact Us','<b><span style=\"font-size:16.0pt;mso-bidi-font-size:11.0pt;line-height:115%;font-family:\r\n&quot;Calibri&quot;,sans-serif;mso-ascii-theme-font:minor-latin;mso-fareast-font-family:\r\nCalibri;mso-fareast-theme-font:minor-latin;mso-hansi-theme-font:minor-latin;\r\nmso-bidi-font-family:&quot;Times New Roman&quot;;mso-bidi-theme-font:minor-bidi;\r\nmso-ansi-language:EN-US;mso-fareast-language:EN-US;mso-bidi-language:AR-SA\">UrbanNest</span></b>\r\n<div class=\"white\">\r\n    <font face=\"Calibri, sans-serif\"><span style=\"font-size: 26.6667px;\"><b><br></b></span></font>\r\n    <div>\r\n        <p class=\"MsoNormal\"><span style=\"font-size:14.0pt;mso-bidi-font-size:11.0pt;line-height:115%\"><b>Contact Number:</b> 9999999999<o:p></o:p></span></p>\r\n    </div>\r\n    <div>\r\n        <p class=\"MsoNormal\"><span style=\"font-size:14.0pt;mso-bidi-font-size:11.0pt;line-height:115%\"><b>Email: </b><span class=\"MsoHyperlink\"><a\r\n                        href=\"mailto:urbannest@gmail.com\">urbannest@gmail.com</a></span>\r\n                <o:p></o:p>\r\n            </span></p>\r\n\r\n        <p class=\"MsoNormal\"><span style=\"font-size:14.0pt;mso-bidi-font-size:11.0pt;line-height:115%\"><b>Address:&nbsp;</b>UrbanNest&nbsp;Goa<o:p></o:p></span></p>\r\n    </div>\r\n</div>','2024-04-03 13:34:07');
/*!40000 ALTER TABLE `tblpages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblpg`
--

DROP TABLE IF EXISTS `tblpg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblpg` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
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
  `LastUpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`ID`),
  UNIQUE KEY `PGTitle` (`PGTitle`),
  KEY `tblpg_ibfk_1` (`OwnerID`),
  KEY `tblpg_ibfk_2` (`StateName`),
  KEY `tblpg_ibfk_3` (`CityName`),
  CONSTRAINT `tblpg_ibfk_1` FOREIGN KEY (`OwnerID`) REFERENCES `tblowner` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tblpg_ibfk_2` FOREIGN KEY (`StateName`) REFERENCES `tblstate` (`StateName`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tblpg_ibfk_3` FOREIGN KEY (`CityName`) REFERENCES `tblcity` (`City`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblpg`
--

LOCK TABLES `tblpg` WRITE;
/*!40000 ALTER TABLE `tblpg` DISABLE KEYS */;
INSERT INTO `tblpg` VALUES (27,12,'Goa','North Goa','Laxmi Niwas','Boys','3000','2','Laxmi Nivas, Behind 20 Pt, Near Socorro Mazid And Socorro Panchayat, Socorro, Porvorim, Bardez, Goa.','44afcf61c6914b9186800dad92ff820e.jpg','Yes','Yes','Yes','No','No','Yes','Yes','No','No','Yes','','Yes','2024-05-17 08:48:14',1,'2026-11-16 06:11:45'),(28,12,'Maharastra','Mumbai ','MR BOYS','Boys','25000','20','Porvorim','b2a6580753e30038e95f2cd662220de5.jpg','Yes','No','Yes','No','Yes','Yes','No','Yes','Yes','Yes','Yes','Yes','2026-11-01 20:11:02',1,'2026-11-16 06:39:57'),(29,13,'Goa','South Goa','Sea Breeze Residency','Girls','6500','8','Near Colva Beach Road, Colva, Salcete, Goa','2d4855b05674d1db3f4007aabc27304e.jpg','Yes','No','Yes','Yes','No','Yes','Yes','Yes','Yes','Yes','No','Yes','2026-10-02 10:15:00',1,'2026-11-16 06:39:57'),(30,12,'Goa','North Goa','Panjim Comfort Stay','Boys','5500','10','18th June Road, Near Church Square, Panaji, Goa','b8a64c55670b3856d224c1096c2f2110.jpg','Yes','Yes','Yes','Yes','Yes','No','Yes','No','Yes','Yes','Yes','Yes','2026-10-05 11:20:00',1,'2026-11-16 06:39:57'),(31,13,'Goa','North Goa','Mapusa Student Nest','Both','4500','12','Near Mapusa Market, Feira Alta, Mapusa, Goa','39e7a4691e066d92152449f02a76f4f1.jpg','Yes','Yes','No','Yes','No','No','Yes','Yes','No','Yes','No','Yes','2026-10-08 09:40:00',1,'2026-11-16 06:39:57'),(32,12,'Maharastra','Pune','Hinjewadi Tech Stay','Boys','9000','15','Phase 1, Near Wipro Circle, Hinjewadi, Pune','947fc89a6c7fb42114478b5875c60f75.jpg','Yes','Yes','Yes','Yes','Yes','Yes','Yes','Yes','Yes','Yes','No','Yes','2026-10-10 14:05:00',1,'2026-11-16 06:39:57'),(33,13,'Maharastra','Pune','Kothrud Ladies Hostel','Girls','8000','10','Karve Road, Near Kothrud Bus Depot, Pune','603ca2fc4d3c1d2f9171b5634379a6a7.jpg','Yes','No','Yes','Yes','No','Yes','Yes','Yes','Yes','Yes','Yes','Yes','2026-10-12 16:30:00',1,'2026-11-16 06:39:57'),(34,12,'Maharastra','Mumbai ','Andheri Urban Living','Both','14000','18','Near Andheri Station (East), Andheri, Mumbai','401c13a1e398383bdc9932754ece24c2.jpg','Yes','No','Yes','Yes','Yes','No','Yes','Yes','Yes','Yes','No','Yes','2026-10-15 12:00:00',1,'2026-11-16 06:39:57'),(35,13,'Maharastra','Mumbai ','Powai Lakeview PG','Girls','16000','12','Hiranandani Gardens, Powai, Mumbai','9788556ce061fc7de3ce804f10af9940.jpg','Yes','Yes','Yes','Yes','Yes','Yes','Yes','Yes','Yes','Yes','Yes','Yes','2026-10-18 10:45:00',1,'2026-11-16 06:39:57'),(36,12,'Maharastra','Sawantwadi','Sawantwadi Home Stay','Both','3500','6','Near Moti Talav, Sawantwadi, Sindhudurg','584ec3b1a97776dd9abc48fc572c6da4.jpg','Yes','Yes','No','No','No','Yes','No','No','No','Yes','Yes','Yes','2026-10-21 08:30:00',1,'2026-11-16 06:39:57'),(37,13,'Karnataka','Bengaluru','Koramangala Co-Living','Both','12000','20','5th Block, Near Forum Mall, Koramangala, Bengaluru','49ef412670ace31a24d7c02704655c02.jpg','Yes','Yes','Yes','Yes','Yes','Yes','Yes','Yes','Yes','Yes','No','Yes','2026-10-24 13:10:00',1,'2026-11-16 06:39:57'),(38,12,'Karnataka','Bengaluru','Kothnur Student PG','Boys','7500','14','Near Kristu Jayanti College, Kothnur, Bengaluru','71ba6c5e9df6902b7b19b7329c8845cb.jpg','Yes','Yes','Yes','Yes','No','No','Yes','Yes','Yes','Yes','Yes','Yes','2026-10-27 15:25:00',1,'2026-11-16 06:39:57'),(39,13,'Karnataka','Mysuru','Mysuru Palace View PG','Girls','6000','8','Near Mysore Palace, Chamrajpura, Mysuru','e37141e53b6bf4ba28ec6a1f6c42f15a.jpg','Yes','No','Yes','Yes','No','Yes','Yes','No','Yes','Yes','No','Yes','2026-11-10 11:00:00',0,'2026-11-16 06:39:57'),(40,12,'Karnataka','Mangaluru','Mangaluru Coastal Stay','Boys','5000','9','Near Hampankatta Circle, Mangaluru','a9d0ee2660852eb16f9164c4cbc7eb52.jpg','Yes','Yes','No','Yes','No','No','Yes','Yes','No','Yes','Yes','No','2026-11-12 17:40:00',0,'2026-11-16 06:39:57'),(41,13,'Maharastra','Pune','Viman Nagar Elite PG','Both','11000','16','Near Phoenix Marketcity, Viman Nagar, Pune','9fb1b0a3cbd98e2ca9a3ceee337d6c23.jpg','Yes','Yes','Yes','Yes','Yes','Yes','Yes','Yes','Yes','Yes','No','Yes','2026-10-27 02:09:57',1,'2026-11-16 06:39:57'),(42,12,'Goa','South Goa','Margao Central Stay','Boys','5000','10','Near Margao Railway Station, Margao, Goa','20cbaa7972afd80a62c66acba9c91541.jpg','Yes','Yes','Yes','Yes','No','No','Yes','Yes','Yes','Yes','Yes','Yes','2026-10-30 02:09:57',1,'2026-11-16 06:39:57'),(43,13,'Karnataka','Bengaluru','HSR Layout Ladies PG','Girls','10500','14','Sector 2, Near BDA Complex, HSR Layout, Bengaluru','c2546c98b1b548dbecac0ccf09cf7641.jpg','Yes','No','Yes','Yes','Yes','Yes','Yes','Yes','Yes','Yes','No','Yes','2026-11-02 02:09:57',1,'2026-11-16 06:39:57'),(44,12,'Maharastra','Mumbai ','Dadar Budget Rooms','Boys','9500','12','Near Dadar Station (West), Dadar, Mumbai','ea9d4ee4fe920b707b4d2debb6d3d008.jpg','Yes','No','Yes','No','No','No','Yes','Yes','Yes','Yes','No','Yes','2026-11-05 02:09:57',1,'2026-11-16 06:39:57'),(45,13,'Karnataka','Bengaluru','Whitefield Tech Nest','Both','13500','22','Near ITPL Main Road, Whitefield, Bengaluru','ce3ce141a91ad10bfa5365cfb727a1eb.jpg','Yes','Yes','Yes','Yes','Yes','Yes','Yes','Yes','Yes','Yes','Yes','Yes','2026-11-08 02:09:57',1,'2026-11-16 06:39:57'),(46,12,'Goa','North Goa','Calangute Beach PG','Both','7000','8','Near Calangute Beach Road, Calangute, Goa','89d300c615a0442898affd36b6aea60d.jpg','Yes','Yes','Yes','Yes','Yes','Yes','No','Yes','No','Yes','No','Yes','2026-11-11 02:09:57',1,'2026-11-16 06:39:57');
/*!40000 ALTER TABLE `tblpg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblstate`
--

DROP TABLE IF EXISTS `tblstate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblstate` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `StateName` varchar(120) DEFAULT NULL,
  `RegState` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ID`),
  UNIQUE KEY `StateName` (`StateName`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblstate`
--

LOCK TABLES `tblstate` WRITE;
/*!40000 ALTER TABLE `tblstate` DISABLE KEYS */;
INSERT INTO `tblstate` VALUES (1,'Maharastra','2024-01-21 01:37:03'),(2,'Goa','2024-01-11 01:37:03'),(3,'Karnataka','2024-05-17 08:44:51');
/*!40000 ALTER TABLE `tblstate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbluser`
--

DROP TABLE IF EXISTS `tbluser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbluser` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `FullName` varchar(200) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `Password` varchar(200) DEFAULT NULL,
  `RegDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `LastUpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `comm` longtext NOT NULL DEFAULT '{}',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbluser`
--

LOCK TABLES `tbluser` WRITE;
/*!40000 ALTER TABLE `tbluser` DISABLE KEYS */;
INSERT INTO `tbluser` VALUES (28,'Chandrakant Tamoskar','chandrakant@example.com',9876500028,NULL,'$2y$10$6mhMeNLHusxTBPKMcB/Vc.AAN.uItOuxrtVF.UKlX8cLFkWbrZ.aa','2024-05-17 08:48:48','2026-11-16 06:44:29','{}'),(29,'Shivam Gadkerkar','shivam@example.com',9876500029,NULL,'$2y$10$6mhMeNLHusxTBPKMcB/Vc.AAN.uItOuxrtVF.UKlX8cLFkWbrZ.aa','2024-05-17 09:26:13','2026-11-16 06:44:29','{}'),(30,'Apoorva','apoorva@example.com',9876500030,NULL,'$2y$10$6mhMeNLHusxTBPKMcB/Vc.AAN.uItOuxrtVF.UKlX8cLFkWbrZ.aa','2026-11-02 05:48:05','2026-11-16 06:44:29','{}'),(31,'Aarav Sharma','aarav.sharma@example.com',9088637542,NULL,'$2y$10$6mhMeNLHusxTBPKMcB/Vc.AAN.uItOuxrtVF.UKlX8cLFkWbrZ.aa','2026-09-17 02:09:57','2026-11-16 06:44:29','{}'),(32,'Priya Nair','priya.nair@example.com',9034837113,NULL,'$2y$10$6mhMeNLHusxTBPKMcB/Vc.AAN.uItOuxrtVF.UKlX8cLFkWbrZ.aa','2026-09-21 02:09:57','2026-11-16 06:44:29','{}'),(33,'Rohan Desai','rohan.desai@example.com',9081148567,NULL,'$2y$10$6mhMeNLHusxTBPKMcB/Vc.AAN.uItOuxrtVF.UKlX8cLFkWbrZ.aa','2026-09-25 02:09:57','2026-11-16 06:44:29','{}'),(34,'Sneha Kulkarni','sneha.kulkarni@example.com',9013344769,NULL,'$2y$10$6mhMeNLHusxTBPKMcB/Vc.AAN.uItOuxrtVF.UKlX8cLFkWbrZ.aa','2026-09-29 02:09:57','2026-11-16 06:44:29','{}'),(35,'Karan Mehta','karan.mehta@example.com',9065189739,NULL,'$2y$10$6mhMeNLHusxTBPKMcB/Vc.AAN.uItOuxrtVF.UKlX8cLFkWbrZ.aa','2026-10-03 02:09:57','2026-11-16 06:44:29','{}'),(36,'Ananya Iyer','ananya.iyer@example.com',9052248123,NULL,'$2y$10$6mhMeNLHusxTBPKMcB/Vc.AAN.uItOuxrtVF.UKlX8cLFkWbrZ.aa','2026-10-07 02:09:57','2026-11-16 06:44:29','{}'),(37,'Vikram Rao','vikram.rao@example.com',9058620086,NULL,'$2y$10$6mhMeNLHusxTBPKMcB/Vc.AAN.uItOuxrtVF.UKlX8cLFkWbrZ.aa','2026-10-11 02:09:57','2026-11-16 06:44:29','{}'),(38,'Meera Pillai','meera.pillai@example.com',9054879560,NULL,'$2y$10$6mhMeNLHusxTBPKMcB/Vc.AAN.uItOuxrtVF.UKlX8cLFkWbrZ.aa','2026-10-15 02:09:57','2026-11-16 06:44:29','{}'),(39,'Arjun Kamat','arjun.kamat@example.com',9058306184,NULL,'$2y$10$6mhMeNLHusxTBPKMcB/Vc.AAN.uItOuxrtVF.UKlX8cLFkWbrZ.aa','2026-10-19 02:09:57','2026-11-16 06:44:29','{}'),(40,'Isha Patil','isha.patil@example.com',9019915691,NULL,'$2y$10$6mhMeNLHusxTBPKMcB/Vc.AAN.uItOuxrtVF.UKlX8cLFkWbrZ.aa','2026-10-23 02:09:57','2026-11-16 06:44:29','{}'),(41,'Rahul Verma','rahul.verma@example.com',9065511527,NULL,'$2y$10$6mhMeNLHusxTBPKMcB/Vc.AAN.uItOuxrtVF.UKlX8cLFkWbrZ.aa','2026-10-27 02:09:57','2026-11-16 06:44:29','{}'),(42,'Divya Shetty','divya.shetty@example.com',9054489564,NULL,'$2y$10$6mhMeNLHusxTBPKMcB/Vc.AAN.uItOuxrtVF.UKlX8cLFkWbrZ.aa','2026-10-31 02:09:57','2026-11-16 06:44:29','{}');
/*!40000 ALTER TABLE `tbluser` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed
