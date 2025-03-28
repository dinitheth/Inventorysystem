-- Database Backup for testinv
-- Generated: 2025-03-28 06:40:10

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `categories` VALUES('1','Demo Category');
INSERT INTO `categories` VALUES('9','Demo887');
INSERT INTO `categories` VALUES('3','Finished Goods');
INSERT INTO `categories` VALUES('5','Machinery');
INSERT INTO `categories` VALUES('4','Packing Materials');
INSERT INTO `categories` VALUES('2','Raw Materials');
INSERT INTO `categories` VALUES('8','Stationery Items');
INSERT INTO `categories` VALUES('6','Work in Progress');


DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `zip_code` varchar(20) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



DROP TABLE IF EXISTS `media`;
CREATE TABLE `media` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;



DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `buy_price` decimal(25,2) DEFAULT NULL,
  `sale_price` decimal(25,2) NOT NULL,
  `categorie_id` int(11) unsigned NOT NULL,
  `media_id` int(11) DEFAULT 0,
  `date` datetime NOT NULL,
  `minimum_quantity` int(11) NOT NULL DEFAULT 10,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `categorie_id` (`categorie_id`),
  KEY `media_id` (`media_id`),
  CONSTRAINT `FK_products` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `products` VALUES('3','Wheat','61','2.00','5.00','2','0','2021-04-04 18:48:53','100');
INSERT INTO `products` VALUES('6','Portable Band Saw XBP02Z','34','280.00','415.00','5','0','2021-04-04 19:13:35','30');
INSERT INTO `products` VALUES('8','Chicken of the Sea Sardines W','86','13.00','20.00','3','0','2021-04-04 19:17:11','120');
INSERT INTO `products` VALUES('10','Hasbro Marvel Legends Series Toys','96','219.00','322.00','3','0','2021-04-04 19:20:28','100');
INSERT INTO `products` VALUES('11','Packing Chips','73','21.00','31.00','4','0','2021-04-04 19:25:22','200');
INSERT INTO `products` VALUES('12','Classic Desktop Tape Dispenser 38','157','5.00','10.00','8','0','2021-04-04 19:48:01','100');
INSERT INTO `products` VALUES('13','Small Bubble Cushioning Wrap','198','8.00','19.00','4','0','2021-04-04 19:49:00','300');
INSERT INTO `products` VALUES('14','test2555','1000','500.00','1000.00','3','0','2025-02-02 12:01:57','500');
INSERT INTO `products` VALUES('16','test32','444','56.00','56.00','5','0','2025-03-27 16:57:02','3455');
INSERT INTO `products` VALUES('17','test2555333','777','455.00','667.00','5','0','2025-03-27 16:58:42','455');
INSERT INTO `products` VALUES('18','test2555dd','55','445.78','565.87','5','0','2025-03-27 17:05:19','5444');


DROP TABLE IF EXISTS `purchase_returns`;
CREATE TABLE `purchase_returns` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) unsigned NOT NULL,
  `return_quantity` int(11) NOT NULL,
  `return_date` datetime NOT NULL,
  `reason` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `purchase_returns_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `purchase_returns` VALUES('2','6','4','2025-03-27 17:55:02','tg');


DROP TABLE IF EXISTS `sales`;
CREATE TABLE `sales` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) unsigned NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(25,2) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `SK` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `sales` VALUES('44','8','1','20.00','2025-03-27');
INSERT INTO `sales` VALUES('45','10','1','322.00','2025-03-27');
INSERT INTO `sales` VALUES('46','8','1','20.00','2025-03-27');
INSERT INTO `sales` VALUES('47','8','1','20.00','2025-03-27');
INSERT INTO `sales` VALUES('48','12','1','10.00','2025-03-27');
INSERT INTO `sales` VALUES('49','8','1','20.00','2025-03-27');
INSERT INTO `sales` VALUES('50','8','1','20.00','2025-03-27');
INSERT INTO `sales` VALUES('51','8','1','20.00','2025-03-27');
INSERT INTO `sales` VALUES('52','12','1','10.00','2025-03-27');
INSERT INTO `sales` VALUES('53','11','1','31.00','2025-03-27');
INSERT INTO `sales` VALUES('55','12','1','10.00','2025-03-27');
INSERT INTO `sales` VALUES('56','6','1','415.00','2025-03-27');
INSERT INTO `sales` VALUES('58','10','1','322.00','2025-03-27');
INSERT INTO `sales` VALUES('60','8','1','20.00','2025-03-27');
INSERT INTO `sales` VALUES('62','10','1','322.00','2025-03-27');
INSERT INTO `sales` VALUES('63','10','1','322.00','2025-03-27');
INSERT INTO `sales` VALUES('64','13','1','19.00','2025-03-27');
INSERT INTO `sales` VALUES('65','3','10','50.00','2025-03-27');
INSERT INTO `sales` VALUES('66','10','1','322.00','2025-03-27');
INSERT INTO `sales` VALUES('67','11','1','31.00','2025-03-27');


DROP TABLE IF EXISTS `sales_returns`;
CREATE TABLE `sales_returns` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) unsigned NOT NULL,
  `product_id` int(11) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(25,2) NOT NULL,
  `return_date` date NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `return_condition` enum('good','damaged','defective','other') NOT NULL,
  `returned_by` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `returned_by` (`returned_by`),
  CONSTRAINT `sales_returns_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `sales_returns_ibfk_2` FOREIGN KEY (`returned_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `sales_returns` VALUES('1','2','3','1','15.00','2025-03-13','test2','damaged','9');
INSERT INTO `sales_returns` VALUES('2','2','3','1','15.00','2025-03-13','t31','good','9');
INSERT INTO `sales_returns` VALUES('3','57','8','1','20.00','2025-03-27','TE1','damaged','9');


DROP TABLE IF EXISTS `user_groups`;
CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(150) NOT NULL,
  `group_level` int(11) NOT NULL,
  `group_status` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_level` (`group_level`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `user_groups` VALUES('1','Admin','1','1');
INSERT INTO `user_groups` VALUES('2','special','2','1');
INSERT INTO `user_groups` VALUES('3','User','3','1');


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_level` int(11) NOT NULL,
  `image` varchar(255) DEFAULT 'no_image.jpg',
  `status` int(1) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_level` (`user_level`),
  CONSTRAINT `FK_user` FOREIGN KEY (`user_level`) REFERENCES `user_groups` (`group_level`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `users` VALUES('7','Din','d1','40bd001563085fc35165329ea1ff5c5ecbdbbeef','3','no_image.jpg','1','2025-03-13 15:29:50');
INSERT INTO `users` VALUES('9','te','te1','40bd001563085fc35165329ea1ff5c5ecbdbbeef','1','no_image.jpg','1','2025-03-28 06:39:48');
INSERT INTO `users` VALUES('10','Ashen','Ash123','40bd001563085fc35165329ea1ff5c5ecbdbbeef','1','no_image.jpg','1','');
INSERT INTO `users` VALUES('11','Dinith','Dinith','40bd001563085fc35165329ea1ff5c5ecbdbbeef','1','no_image.jpg','1','');


