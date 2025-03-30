-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2025 at 05:59 PM
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
-- Database: `testinv`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(17, 'Circuit Protection'),
(20, 'Demo'),
(13, 'Electrical Components'),
(19, 'Electrical Measurement Instruments'),
(15, 'Electrical Safety Equipment'),
(3, 'Finished Goods'),
(12, 'Home Appliances'),
(10, 'Lighting Fixtures'),
(11, 'Power Tools'),
(2, 'Raw Materials'),
(14, 'Smart Home Devices'),
(18, 'Switchgear and Panels'),
(16, 'Wiring and Cables');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `zip_code` varchar(20) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `customer_name`, `address`, `city`, `province`, `zip_code`, `telephone`, `email`, `date_added`) VALUES
(5, 'John Doe', '123 Main Street', 'New York', 'New York', '10001', '123-456-7890', 'johndoe@example.com', '2025-03-28 12:01:08'),
(6, 'Jane Smith', '456 Elm Street', 'Los Angeles', 'California', '90001', '987-654-3210', 'janesmith@example.com', '2025-03-28 12:01:08'),
(7, 'David Johnson', '789 Oak Avenue', 'Chicago', 'Illinois', '60601', '555-123-4567', 'david.johnson@example.com', '2025-03-28 12:01:08'),
(8, 'Emily Davis', '321 Maple Lane', 'Houston', 'Texas', '77001', '444-987-6543', 'emily.davis@example.com', '2025-03-28 12:01:08'),
(9, 'Michael Brown', '654 Pine Road', 'Miami', 'Florida', '33101', '333-789-4561', 'michael.brown@example.com', '2025-03-28 12:01:08'),
(10, 'Sarah Wilson', '987 Cedar Drive', 'Phoenix', 'Arizona', '85001', '222-567-8901', 'sarah.wilson@example.com', '2025-03-28 12:01:08'),
(11, 'Robert Martinez', '741 Birch Street', 'San Francisco', 'California', '94101', '111-234-5678', 'robert.martinez@example.com', '2025-03-28 12:01:08'),
(12, 'Laura Anderson', '258 Spruce Avenue', 'Seattle', 'Washington', '98101', '666-345-6789', 'laura.anderson@example.com', '2025-03-28 12:01:08'),
(13, 'James Taylor', '369 Redwood Blvd', 'Denver', 'Colorado', '80201', '777-456-7890', 'james.taylor@example.com', '2025-03-28 12:01:08'),
(14, 'Olivia White', '147 Willow Court', 'Boston', 'Massachusetts', '02101', '888-567-8902', 'olivia.white@example.com', '2025-03-28 12:01:08');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(11) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `buy_price` decimal(25,2) DEFAULT NULL,
  `sale_price` decimal(25,2) NOT NULL,
  `categorie_id` int(11) UNSIGNED NOT NULL,
  `media_id` int(11) DEFAULT 0,
  `date` datetime NOT NULL,
  `minimum_quantity` int(11) NOT NULL DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `quantity`, `buy_price`, `sale_price`, `categorie_id`, `media_id`, `date`, `minimum_quantity`) VALUES
(3, 'Ceiling Light', 57, 2.00, 5.00, 2, 0, '2025-03-26 18:48:53', 100),
(8, 'Double Outlet', 85, 13.00, 20.00, 3, 0, '2025-03-26 19:17:11', 120),
(19, 'LED Panel Light 600x600mm', 200, 45.00, 79.99, 10, 0, '2025-03-28 16:23:30', 30),
(20, 'Smart WiFi Color Bulb E27', 300, 15.00, 29.99, 10, 0, '2025-03-28 16:23:30', 50),
(21, 'Outdoor LED Floodlight 50W', 150, 35.00, 59.99, 10, 0, '2025-03-28 16:23:30', 20),
(22, 'DeWalt Cordless Drill 20V', 98, 149.00, 249.99, 11, 0, '2025-03-28 16:23:30', 15),
(23, 'Makita Angle Grinder 1100W', 71, 95.00, 169.99, 11, 0, '2025-03-28 16:23:30', 10),
(24, 'Bosch Reciprocating Saw', 59, 129.00, 229.99, 11, 0, '2025-03-28 16:23:30', 8),
(25, 'Samsung Microwave 32L Digital', 49, 129.00, 229.99, 12, 0, '2025-03-28 16:23:30', 10),
(26, 'LG Air Purifier with HEPA Filter', 75, 99.00, 179.99, 12, 0, '2025-03-28 16:23:30', 15),
(27, 'Panasonic Electric Kettle 1.7L', 200, 30.00, 49.99, 12, 0, '2025-03-28 16:23:30', 40),
(28, 'Double Wall Socket Outlet', 500, 5.00, 12.99, 13, 0, '2025-03-28 16:23:30', 100),
(29, 'USB Wall Charger Socket', 300, 8.00, 16.99, 13, 0, '2025-03-28 16:23:30', 50),
(30, 'Dimmer Light Switch', 250, 12.00, 24.99, 13, 0, '2025-03-28 16:23:30', 40),
(31, 'Smart WiFi Plug with Energy Monitoring', 250, 20.00, 39.99, 14, 0, '2025-03-28 16:23:30', 50),
(32, 'Smart Thermostat with Mobile App', 100, 89.00, 149.99, 14, 0, '2025-03-28 16:23:30', 20),
(33, 'WiFi Security Camera 1080p', 150, 59.00, 99.99, 14, 0, '2025-03-28 16:23:30', 25),
(34, 'Electrical Safety Gloves Class 0', 97, 25.00, 49.99, 15, 0, '2025-03-28 16:23:30', 20),
(35, 'Residual Current Device (RCD)', 79, 45.00, 79.99, 15, 0, '2025-03-28 16:23:30', 15),
(36, 'Arc Flash Protection Suit', 24, 199.00, 349.99, 15, 0, '2025-03-28 16:23:30', 5),
(37, 'Copper electrical cable 100m roll 2.5mm', 200, 45.00, 79.99, 16, 0, '2025-03-28 16:23:30', 30),
(38, 'Ethernet Network Cable CAT6 305m', 99, 59.00, 99.99, 16, 0, '2025-03-28 16:23:30', 20),
(39, 'Heat Shrink Tube Assortment Kit', 150, 15.00, 29.99, 16, 0, '2025-03-28 16:23:30', 25),
(40, 'Circuit Breaker 20A Single Pole', 297, 12.00, 24.99, 17, 0, '2025-03-28 16:23:30', 50),
(41, 'Surge Protector 4-Outlet', 250, 25.00, 49.99, 17, 0, '2025-03-28 16:23:30', 40),
(42, 'Miniature Circuit Breaker 10A', 200, 8.00, 16.99, 17, 0, '2025-03-28 16:23:30', 30),
(43, 'Main Electrical Distribution Board', 50, 199.00, 349.99, 18, 0, '2025-03-28 16:23:30', 10),
(44, 'Industrial Electrical Panel 24 Modules', 40, 159.00, 279.99, 18, 0, '2025-03-28 16:23:30', 8),
(45, 'Compact Switchboard Enclosure', 75, 89.00, 159.99, 18, 0, '2025-03-28 16:23:30', 15),
(46, 'Digital Multimeter with True RMS', 100, 59.00, 99.99, 19, 0, '2025-03-28 16:23:30', 20),
(47, 'Clamp Meter AC/DC Current', 78, 79.00, 129.99, 19, 0, '2025-03-28 16:23:30', 15),
(48, 'Infrared Thermometer', 60, 49.00, 89.99, 19, 0, '2025-03-28 16:23:30', 10),
(49, 'Light Bulb', 100, 150.00, 300.00, 13, 0, '2025-03-28 18:22:33', 500);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_returns`
--

CREATE TABLE `purchase_returns` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `return_quantity` int(11) NOT NULL,
  `return_date` datetime NOT NULL,
  `reason` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `purchase_returns`
--

INSERT INTO `purchase_returns` (`id`, `product_id`, `return_quantity`, `return_date`, `reason`) VALUES
(3, 23, 9, '2025-03-28 12:21:05', 'g');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(25,2) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `product_id`, `qty`, `price`, `date`) VALUES
(44, 8, 1, 20.00, '2025-03-27'),
(46, 8, 1, 20.00, '2025-03-27'),
(47, 8, 1, 20.00, '2025-03-27'),
(49, 8, 1, 20.00, '2025-03-27'),
(50, 8, 1, 20.00, '2025-03-27'),
(51, 8, 1, 20.00, '2025-03-27'),
(60, 8, 1, 20.00, '2025-03-27'),
(65, 3, 10, 50.00, '2025-03-27'),
(66, 8, 1, 20.00, '2025-03-28'),
(67, 36, 1, 349.99, '2025-03-28'),
(69, 40, 1, 24.99, '2025-03-28'),
(70, 47, 1, 129.99, '2025-03-28'),
(71, 3, 1, 5.00, '2025-03-28'),
(72, 40, 1, 24.99, '2025-03-28'),
(73, 3, 1, 5.00, '2025-03-28'),
(74, 47, 1, 129.99, '2025-03-28'),
(75, 24, 1, 229.99, '2025-03-28'),
(76, 36, 1, 349.99, '2025-03-28'),
(77, 22, 1, 249.99, '2025-03-28'),
(78, 25, 1, 229.99, '2025-03-28'),
(79, 38, 1, 99.99, '2025-03-28'),
(80, 34, 1, 49.99, '2025-03-28'),
(81, 22, 1, 249.99, '2025-03-28'),
(82, 34, 1, 49.99, '2025-03-28'),
(83, 36, 1, 349.99, '2025-03-28'),
(84, 36, 1, 349.99, '2025-03-28'),
(85, 40, 1, 24.99, '2025-03-28'),
(86, 3, 1, 5.00, '2025-03-28'),
(87, 36, 1, 349.99, '2025-03-28'),
(88, 3, 1, 5.00, '2025-03-28'),
(89, 36, 1, 349.99, '2025-03-28'),
(90, 34, 1, 49.99, '2025-03-28'),
(91, 35, 1, 79.99, '2025-03-28');

-- --------------------------------------------------------

--
-- Table structure for table `sales_returns`
--

CREATE TABLE `sales_returns` (
  `id` int(11) UNSIGNED NOT NULL,
  `sale_id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(25,2) NOT NULL,
  `return_date` date NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `return_condition` enum('good','damaged','defective','other') NOT NULL,
  `returned_by` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_returns`
--

INSERT INTO `sales_returns` (`id`, `sale_id`, `product_id`, `quantity`, `price`, `return_date`, `reason`, `return_condition`, `returned_by`) VALUES
(1, 2, 3, 1, 15.00, '2025-03-13', 'test2', 'damaged', 9),
(2, 2, 3, 1, 15.00, '2025-03-13', 't31', 'good', 9),
(3, 57, 8, 1, 20.00, '2025-03-27', 'TE1', 'damaged', 9),
(4, 68, 24, 1, 229.99, '2025-03-28', 'rr', 'good', 9);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_level` int(11) NOT NULL,
  `image` varchar(255) DEFAULT 'no_image.jpg',
  `status` int(1) NOT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `user_level`, `image`, `status`, `last_login`) VALUES
(9, 'te', 'te1', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 1, 'no_image.jpg', 1, '2025-03-27 16:25:08'),
(17, 'Dinith', 'd1', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 1, 'no_image.jpg', 1, '2025-03-28 12:25:10'),
(18, 'Ashen', 'a1', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 1, 'no_image.jpg', 1, '0000-00-00 00:00:00'),
(19, 'Oshadi', 'o1', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 1, 'no_image.jpg', 1, '0000-00-00 00:00:00'),
(20, 'Lakshitha', 'l1', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 1, 'no_image.jpg', 1, '0000-00-00 00:00:00'),
(22, 'Abhilash', 'a1', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 1, 'no_image.jpg', 1, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(150) NOT NULL,
  `group_level` int(11) NOT NULL,
  `group_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`id`, `group_name`, `group_level`, `group_status`) VALUES
(1, 'Admin', 1, 1),
(3, 'User', 3, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `categorie_id` (`categorie_id`),
  ADD KEY `media_id` (`media_id`);

--
-- Indexes for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `sales_returns`
--
ALTER TABLE `sales_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `returned_by` (`returned_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_level` (`user_level`);

--
-- Indexes for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_level` (`group_level`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `sales_returns`
--
ALTER TABLE `sales_returns`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_products` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  ADD CONSTRAINT `purchase_returns_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `SK` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sales_returns`
--
ALTER TABLE `sales_returns`
  ADD CONSTRAINT `sales_returns_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `sales_returns_ibfk_2` FOREIGN KEY (`returned_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_user` FOREIGN KEY (`user_level`) REFERENCES `user_groups` (`group_level`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
