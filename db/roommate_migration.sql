-- ============================================================
-- UrbanNest - Roommate Feature Migration
-- Run this AFTER the existing urbanest.sql has been imported.
-- This file ONLY adds new tables and does NOT touch existing ones.
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Table: roommate_profiles
-- One profile per user for roommate-matching preferences
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `roommate_profiles` (
  `id`                  INT(11) NOT NULL AUTO_INCREMENT,
  `user_id`             INT(10) NOT NULL,
  `profile_photo`       VARCHAR(255) DEFAULT 'default-profile.png',
  `age`                 INT(3) DEFAULT NULL,
  `gender`              ENUM('Male','Female','Other') DEFAULT NULL,
  `occupation`          VARCHAR(100) DEFAULT NULL,
  `college_company`     VARCHAR(200) DEFAULT NULL,
  `city`                VARCHAR(100) DEFAULT NULL,
  `preferred_locality`  VARCHAR(200) DEFAULT NULL,
  `monthly_budget`      INT(10) DEFAULT NULL,
  `move_in_date`        DATE DEFAULT NULL,
  `food_preference`     ENUM('Vegetarian','Non-Vegetarian','Both') DEFAULT 'Both',
  `smoking`             ENUM('Yes','No','Occasionally') DEFAULT 'No',
  `drinking`            ENUM('Yes','No','Occasionally') DEFAULT 'No',
  `sleeping_schedule`   ENUM('Early Bird','Night Owl','Flexible') DEFAULT 'Flexible',
  `cleanliness`         ENUM('Very Clean','Moderate','Relaxed') DEFAULT 'Moderate',
  `social_lifestyle`    ENUM('Very Social','Moderate','Introvert') DEFAULT 'Moderate',
  `about_me`            TEXT DEFAULT NULL,
  `is_looking`          TINYINT(1) DEFAULT 1 COMMENT '1=actively looking, 0=not looking',
  `created_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`          TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_profile` (`user_id`),
  KEY `idx_city` (`city`),
  KEY `idx_budget` (`monthly_budget`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table: roommate_listings
-- For users who HAVE a room and want to share it
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `roommate_listings` (
  `id`                  INT(11) NOT NULL AUTO_INCREMENT,
  `user_id`             INT(10) NOT NULL,
  `title`               VARCHAR(255) NOT NULL,
  `city`                VARCHAR(100) NOT NULL,
  `locality`            VARCHAR(200) DEFAULT NULL,
  `full_address`        TEXT DEFAULT NULL,
  `monthly_rent`        INT(10) NOT NULL,
  `security_deposit`    INT(10) DEFAULT 0,
  `room_type`           ENUM('Single','Double','Shared') DEFAULT 'Single',
  `available_spaces`    INT(5) DEFAULT 1,
  `accommodation_type`  ENUM('PG','Flat','Apartment','Hostel') DEFAULT 'PG',
  `available_from`      DATE DEFAULT NULL,
  `furnished`           ENUM('Fully Furnished','Semi-Furnished','Unfurnished') DEFAULT 'Unfurnished',
  `amenities`           TEXT DEFAULT NULL COMMENT 'JSON array of amenity strings',
  `gender_preference`   ENUM('Male','Female','Any') DEFAULT 'Any',
  `preferred_age_min`   INT(3) DEFAULT 18,
  `preferred_age_max`   INT(3) DEFAULT 45,
  `lifestyle_prefs`     TEXT DEFAULT NULL,
  `description`         TEXT DEFAULT NULL,
  `images`              TEXT DEFAULT NULL COMMENT 'JSON array of image filenames',
  `is_active`           TINYINT(1) DEFAULT 1,
  `created_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`          TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_city` (`city`),
  KEY `idx_rent` (`monthly_rent`),
  KEY `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table: roommate_preferences
-- For users who NEED a room (no accommodation yet)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `roommate_preferences` (
  `id`                  INT(11) NOT NULL AUTO_INCREMENT,
  `user_id`             INT(10) NOT NULL,
  `preferred_city`      VARCHAR(100) NOT NULL,
  `preferred_locality`  VARCHAR(200) DEFAULT NULL,
  `budget_min`          INT(10) DEFAULT 0,
  `budget_max`          INT(10) DEFAULT 0,
  `accommodation_type`  ENUM('PG','Flat','Apartment','Hostel','Any') DEFAULT 'Any',
  `room_type`           ENUM('Single','Double','Shared','Any') DEFAULT 'Any',
  `move_in_date`        DATE DEFAULT NULL,
  `people_required`     INT(3) DEFAULT 1,
  `gender_preference`   ENUM('Male','Female','Any') DEFAULT 'Any',
  `preferred_age_min`   INT(3) DEFAULT 18,
  `preferred_age_max`   INT(3) DEFAULT 45,
  `food_preference`     ENUM('Vegetarian','Non-Vegetarian','Both') DEFAULT 'Both',
  `smoking`             ENUM('Yes','No','Occasionally') DEFAULT 'No',
  `drinking`            ENUM('Yes','No','Occasionally') DEFAULT 'No',
  `work_study_status`   ENUM('Working','Studying','Other') DEFAULT 'Working',
  `additional_requirements` TEXT DEFAULT NULL,
  `lifestyle_prefs`     TEXT DEFAULT NULL,
  `is_active`           TINYINT(1) DEFAULT 1,
  `created_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`          TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_city` (`preferred_city`),
  KEY `idx_budget` (`budget_max`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table: roommate_requests
-- Connection requests between users
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `roommate_requests` (
  `id`                  INT(11) NOT NULL AUTO_INCREMENT,
  `sender_id`           INT(10) NOT NULL,
  `receiver_id`         INT(10) NOT NULL,
  `listing_id`          INT(11) DEFAULT NULL COMMENT 'Optional: which listing triggered the request',
  `status`              ENUM('Pending','Accepted','Rejected','Cancelled') DEFAULT 'Pending',
  `message`             TEXT DEFAULT NULL,
  `created_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`          TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_request` (`sender_id`, `receiver_id`),
  KEY `idx_sender` (`sender_id`),
  KEY `idx_receiver` (`receiver_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table: roommate_conversations
-- One conversation per pair of mutually accepted users
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `roommate_conversations` (
  `id`                  INT(11) NOT NULL AUTO_INCREMENT,
  `user1_id`            INT(10) NOT NULL,
  `user2_id`            INT(10) NOT NULL,
  `last_message_at`     TIMESTAMP NULL DEFAULT NULL,
  `created_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_conversation` (`user1_id`, `user2_id`),
  KEY `idx_user1` (`user1_id`),
  KEY `idx_user2` (`user2_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table: roommate_messages
-- Individual messages within a conversation
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `roommate_messages` (
  `id`                  INT(11) NOT NULL AUTO_INCREMENT,
  `conversation_id`     INT(11) NOT NULL,
  `sender_id`           INT(10) NOT NULL,
  `message`             TEXT NOT NULL,
  `is_read`             TINYINT(1) DEFAULT 0,
  `created_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_conversation` (`conversation_id`),
  KEY `idx_sender` (`sender_id`),
  KEY `idx_read` (`is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table: roommate_blocks (optional safety feature)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `roommate_blocks` (
  `id`                  INT(11) NOT NULL AUTO_INCREMENT,
  `blocker_id`          INT(10) NOT NULL,
  `blocked_id`          INT(10) NOT NULL,
  `created_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_block` (`blocker_id`, `blocked_id`),
  KEY `idx_blocker` (`blocker_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Foreign Key Constraints (linking to existing tbluser)
-- --------------------------------------------------------
ALTER TABLE `roommate_profiles`
  ADD CONSTRAINT `fk_rp_user` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `roommate_listings`
  ADD CONSTRAINT `fk_rl_user` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `roommate_preferences`
  ADD CONSTRAINT `fk_rpref_user` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `roommate_requests`
  ADD CONSTRAINT `fk_rreq_sender` FOREIGN KEY (`sender_id`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rreq_receiver` FOREIGN KEY (`receiver_id`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rreq_listing` FOREIGN KEY (`listing_id`) REFERENCES `roommate_listings` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `roommate_conversations`
  ADD CONSTRAINT `fk_rconv_user1` FOREIGN KEY (`user1_id`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rconv_user2` FOREIGN KEY (`user2_id`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `roommate_messages`
  ADD CONSTRAINT `fk_rmsg_conv` FOREIGN KEY (`conversation_id`) REFERENCES `roommate_conversations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rmsg_sender` FOREIGN KEY (`sender_id`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `roommate_blocks`
  ADD CONSTRAINT `fk_rblk_blocker` FOREIGN KEY (`blocker_id`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rblk_blocked` FOREIGN KEY (`blocked_id`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;
-- ============================================================
-- END OF MIGRATION
-- To rollback: DROP TABLE roommate_blocks, roommate_messages,
--   roommate_conversations, roommate_requests,
--   roommate_preferences, roommate_listings, roommate_profiles;
-- ============================================================
