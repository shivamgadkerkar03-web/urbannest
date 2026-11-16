-- ============================================================
-- UrbanNest - Urban Nation marketplace chat
-- Run AFTER urbanest.sql. Adds the product_messages table that
-- replaces the per-user JSON stored in tbluser.comm.
-- tbluser.comm is left in place and is no longer written to.
-- ============================================================

CREATE TABLE IF NOT EXISTS `product_messages` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `product_id`  INT(11) NOT NULL,
  `sender_id`   INT(11) NOT NULL,
  `receiver_id` INT(11) NOT NULL,
  `body`        TEXT NOT NULL,
  `is_read`     TINYINT(1) NOT NULL DEFAULT 0,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_receiver_unread` (`receiver_id`, `is_read`),
  KEY `idx_thread` (`product_id`, `sender_id`, `receiver_id`),
  KEY `idx_sender` (`sender_id`),
  CONSTRAINT `fk_pmsg_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`productId`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pmsg_sender` FOREIGN KEY (`sender_id`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pmsg_receiver` FOREIGN KEY (`receiver_id`) REFERENCES `tbluser` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Carry over the sample conversation shipped in urbanest.sql
-- (user 29 asking user 28 about product 1231235672). Only runs on an
-- empty table so re-running the file does not duplicate rows.
INSERT INTO `product_messages` (`product_id`, `sender_id`, `receiver_id`, `body`, `is_read`, `created_at`)
SELECT m.product_id, m.sender_id, m.receiver_id, m.body, 1, m.created_at
FROM (
  SELECT 1 AS seq, 1231235672 AS product_id, 29 AS sender_id, 28 AS receiver_id,
         'Hi, I would like to buy your product' AS body, '2024-05-17 09:27:06' AS created_at
  UNION ALL SELECT 2, 1231235672, 28, 29, 'Okay', '2024-05-17 09:27:26'
  UNION ALL SELECT 3, 1231235672, 28, 29, 'Do you have any meet up prefrence', '2024-05-17 09:27:46'
) m
WHERE NOT EXISTS (SELECT 1 FROM `product_messages`)
  AND EXISTS (SELECT 1 FROM `product` WHERE `productId` = 1231235672 AND `userId` = 28)
  AND EXISTS (SELECT 1 FROM `tbluser` WHERE `ID` = 29)
ORDER BY m.seq;
