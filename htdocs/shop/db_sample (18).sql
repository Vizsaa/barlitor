-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2025 at 05:20 PM
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
-- Database: `db_sample`
--

-- --------------------------------------------------------

--
-- Table structure for table `barcode`
--

CREATE TABLE `barcode` (
  `barcode_ean` char(13) NOT NULL,
  `item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `barcode`
--

INSERT INTO `barcode` (`barcode_ean`, `item_id`) VALUES
('2239872376872', 11),
('3453458677628', 5),
('4587263646878', 9),
('6241234586487', 8),
('6241527746363', 4),
('6241527836173', 1),
('6241574635234', 2),
('6264537836173', 3),
('6434564564544', 6),
('8476736836876', 7),
('9473625532534', 8),
('9473627464543', 8),
('9879879837489', 11);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `title` char(4) DEFAULT NULL,
  `fname` varchar(32) DEFAULT NULL,
  `lname` varchar(32) NOT NULL,
  `addressline` varchar(64) DEFAULT NULL,
  `town` varchar(32) DEFAULT NULL,
  `zipcode` char(10) NOT NULL,
  `phone` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `title`, `fname`, `lname`, `addressline`, `town`, `zipcode`, `phone`) VALUES
(1, 'Miss', 'jenny', 'stones', '27 Rowan Avenue', 'hightown', 'NT21AQ', '023 9876'),
(2, 'Mr', 'Andrew', 'stones', '52 The willows', 'lowtown', 'LT57RA', '876 3527'),
(3, 'Miss', 'Alex', 'Matthew', '4 The Street', 'Nicetown', 'NT22TX', '010 4567'),
(4, 'Mr', 'Adrian', 'MAtthew', 'The Barn', 'Yuleville', 'YV672WR', '487 3871'),
(5, 'Mr', 'Simon', 'Cozens', '7 Shady Lane', 'Oahenham', 'OA36Qw', '514 5926'),
(6, 'Mr', 'Neil', 'Matther', '5 Pasture Lane', 'Nicetown', 'NT37RT', '267 1232'),
(7, 'Mr', 'Richard', 'stones', '34 Holly Way', 'Bingham', 'BG42WE', '342 5982'),
(8, 'Mrs', 'Ann', 'stones', '34 Holly Way', 'Bingham', 'BG42WE', '342 5982'),
(9, 'Mrs', 'Christine', 'Hickman', '36 Queen Street', 'Histon', 'HT35EM', '342 5432'),
(10, 'Mr', 'Mike', 'Howard', '86 Dysart Street', 'Tibsville', 'TB37FG', '505 5482'),
(11, 'Mr', 'Dave', 'Jones', '54 Vale Rise', 'Bingham', 'BG38GD', '342 8264'),
(12, 'Mr', 'Richard', 'Neil', '42 Thached Way', 'Winersbay', 'WB36GQ', '505 6482'),
(13, 'Mrs', 'Laura', 'Hendy', '73 MArgaritta Way', 'Oxbridge', 'OX23HX', '821 2335'),
(14, 'Mr', 'Bill', 'ONeil', '2 Beamer Street', 'Welltown', 'WT38GM', '435 1234'),
(15, 'Mr', 'David', 'Hudson', '4 The Square', 'Milltown', 'MT26RT', '961 4526'),
(16, '', 'Doe', 'Jane', '', '', '', ''),
(17, 'Mrs.', 'Jane', 'Doe', 'Taguig', 'Western Bicutan', '1630', '09186824721');

-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

CREATE TABLE `expense` (
  `expense_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `expense_date` date NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` varchar(64) NOT NULL,
  `cost_price` decimal(7,2) DEFAULT NULL,
  `sell_price` decimal(7,2) DEFAULT NULL,
  `image_path` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `supplier_id` int(11) DEFAULT NULL,
  `type` enum('product','tool') NOT NULL DEFAULT 'product'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`item_id`, `title`, `description`, `cost_price`, `sell_price`, `image_path`, `created_at`, `updated_at`, `deleted_at`, `category`, `stock_quantity`, `supplier_id`, `type`) VALUES
(2, 'Engine', 'Rubik Cube', 7.45, 11.49, '../uploads/items/1762442179_w.png', NULL, NULL, NULL, 'Engine', 23229, 3, ''),
(5, 'adadada', 'PIcture Frame', 7.54, 9.95, '', NULL, NULL, NULL, 'Electrical', 0, 2, ''),
(6, '', 'Fan Small', 9.23, 15.75, '', NULL, NULL, NULL, NULL, 0, NULL, ''),
(7, 'Mo-THOR', 'Fan Large', 13.36, 19.95, '../uploads/items/1763566510_3b322069-3895-42b2-b21a-6a24b3aa8d93.jpg', NULL, NULL, NULL, 'Consumables', 2, 4, ''),
(8, '', 'ToothBrush', 0.75, 1.45, '', NULL, NULL, NULL, NULL, 0, NULL, ''),
(9, '', 'Roman Coin', 2.34, 2.45, '', NULL, NULL, NULL, NULL, 0, NULL, ''),
(10, '', 'Carrier Bag', 0.01, 0.00, '', NULL, NULL, NULL, NULL, 0, NULL, ''),
(11, '', 'Speakers', 19.73, 25.32, '', NULL, NULL, NULL, NULL, 0, NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `item_reviews`
--

CREATE TABLE `item_reviews` (
  `review_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_reviews`
--

INSERT INTO `item_reviews` (`review_id`, `item_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 2, 13, 5, 'I like it!!', '2025-11-19 16:14:50');

-- --------------------------------------------------------

--
-- Table structure for table `orderinfo`
--

CREATE TABLE `orderinfo` (
  `orderinfo_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date_placed` date NOT NULL,
  `date_shipped` date DEFAULT NULL,
  `shipping` decimal(7,2) DEFAULT NULL,
  `status` enum('Processing','Delivered','Canceled') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `orderinfo`
--

INSERT INTO `orderinfo` (`orderinfo_id`, `customer_id`, `user_id`, `date_placed`, `date_shipped`, `shipping`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, '2000-03-13', '2000-03-17', 2.99, 'Processing', NULL, NULL),
(2, 8, NULL, '2000-06-23', '2000-06-23', 0.00, 'Processing', NULL, NULL),
(3, 15, NULL, '2000-09-02', '2000-09-12', 3.99, 'Processing', NULL, NULL),
(4, 13, NULL, '2000-09-03', '2000-09-10', 2.99, 'Processing', NULL, NULL),
(5, 8, NULL, '2000-07-21', '2000-07-24', 0.00, 'Processing', NULL, NULL),
(15, 1, NULL, '2023-03-09', '2023-03-09', 10.00, 'Processing', NULL, NULL),
(16, 1, NULL, '2023-03-09', '2023-03-09', 10.00, 'Processing', NULL, NULL),
(18, 1, NULL, '2023-03-10', '2023-03-10', 10.00, 'Processing', '2023-03-09 22:57:10', '2023-03-09 22:57:10'),
(21, 1, NULL, '2023-03-10', '2023-03-10', 10.00, 'Processing', '2023-03-09 23:20:35', '2023-03-09 23:20:35'),
(22, 1, NULL, '2023-03-10', '2023-03-10', 10.00, 'Processing', '2023-03-09 23:21:13', '2023-03-09 23:21:13'),
(27, 0, 13, '2025-11-19', NULL, NULL, 'Processing', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orderline`
--

CREATE TABLE `orderline` (
  `orderinfo_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `rate` decimal(7,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `orderline`
--

INSERT INTO `orderline` (`orderinfo_id`, `product_id`, `quantity`, `rate`) VALUES
(1, 4, 1, 0.00),
(1, 7, 1, 0.00),
(1, 9, 1, 0.00),
(2, 1, 1, 0.00),
(2, 10, 1, 0.00),
(2, 7, 2, 0.00),
(2, 4, 2, 0.00),
(3, 2, 1, 0.00),
(3, 1, 1, 0.00),
(4, 5, 2, 0.00),
(5, 1, 1, 0.00),
(5, 3, 1, 0.00),
(15, 1, 1, 0.00),
(15, 2, 1, 0.00),
(15, 4, 1, 0.00),
(16, 1, 3, 0.00),
(16, 2, 2, 0.00),
(18, 1, 2, 0.00),
(18, 2, 2, 0.00),
(18, 4, 2, 0.00),
(21, 4, 1, 0.00),
(21, 1, 1, 0.00),
(22, 1, 1, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `paid_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `transaction_id`, `amount_paid`, `paid_on`) VALUES
(1, 27, 50.00, '2025-11-19 08:24:01');

-- --------------------------------------------------------

--
-- Table structure for table `products_sold`
--

CREATE TABLE `products_sold` (
  `materials_sold_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `rate_charged` decimal(7,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products_sold`
--

INSERT INTO `products_sold` (`materials_sold_id`, `transaction_id`, `product_id`, `quantity`, `rate_charged`) VALUES
(2, 27, 2, 3, 11.49);

-- --------------------------------------------------------

--
-- Table structure for table `rental`
--

CREATE TABLE `rental` (
  `rental_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `due_date` date NOT NULL,
  `rate_charged` decimal(7,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`item_id`, `quantity`) VALUES
(2, 8),
(5, 3),
(7, 8),
(8, 18),
(10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(50) DEFAULT NULL,
  `lead_time` varchar(50) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `name`, `contact_email`, `contact_phone`, `lead_time`, `website`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'MotorParts Co', 'motorparts@example.com', '09171234567', '3 days', 'https://motorparts.example.com', '2025-11-19 11:45:10', '2025-11-19 12:03:26', NULL),
(2, 'ElectroMoto', 'contact@electromoto.com', '09181234567', '5 days', 'https://electromoto.example.com', '2025-11-19 11:45:10', '2025-11-19 11:58:14', '2025-11-19 11:58:14'),
(3, 'FastMoto Supplies', 'fast@supply.com', '09191234567', '2 days', 'https://fastmoto.com', '2025-11-19 11:45:10', '2025-11-19 11:45:10', NULL),
(4, 'Innomoto Cycle Parts', 'sales@innomotocycleparts.com', '(0939) 929-9551', '2 days', 'https://www.innomotocycleparts.com', '2025-11-19 11:49:06', '2025-11-19 11:49:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'customer',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fcm_token` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `title` char(4) DEFAULT NULL,
  `fname` varchar(50) DEFAULT NULL,
  `lname` varchar(50) DEFAULT NULL,
  `addressline` varchar(255) DEFAULT NULL,
  `town` varchar(50) DEFAULT NULL,
  `zipcode` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`, `fcm_token`, `avatar`, `title`, `fname`, `lname`, `addressline`, `town`, `zipcode`, `phone`) VALUES
(3, 'Admin User', 'admin@shop.com', NULL, '1234', 'admin', NULL, '2025-10-14 13:51:10', '2025-10-14 13:51:10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'Jane Doe', 'jane111111@mail.com', NULL, '1234', 'customer', NULL, '2025-11-16 09:21:57', NULL, NULL, 'uploads/avatars/user_1763284917_389f56c4.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'Jane Doe', 'jane1sfsffesfse11111@mail.com', NULL, '12345', 'customer', NULL, '2025-11-16 09:23:26', NULL, NULL, 'uploads/avatars/user_1763285006_61ce851f.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 'Mike Israa', 'mikeI@mail.com', NULL, '12345', 'customer', NULL, '2025-11-16 09:24:45', NULL, NULL, 'uploads/avatars/user_1763285085_e122e2e3.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 'ASUS ROG', 'asusrog@mail.com', NULL, '12345', 'customer', NULL, '2025-11-16 09:27:45', NULL, NULL, 'uploads/avatars/user_1763285265_9292073d.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 'Natalie Lu', 'wisp@mail.com', NULL, '121212', 'customer', NULL, '2025-11-16 11:32:44', NULL, NULL, 'uploads/avatars/user_13_1763547117.jpeg', 'Miss', 'Natalie', 'Lu', 'Taguig', 'Bagumbayan', '1631', '09312094371'),
(14, 'user1', 'mail@mail.com', NULL, '123456', 'customer', NULL, '2025-11-16 11:53:43', NULL, NULL, 'uploads/avatars/user_1763294023_2441182d.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 'Jared Tenit', 'jared123@mail.com', NULL, '123456', 'customer', NULL, '2025-11-19 10:13:12', NULL, NULL, 'uploads/avatars/user_15_1763547265.png', 'Mr.', 'Jared', 'Tenit', 'Pasay', '76', '1992', '092394781');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barcode`
--
ALTER TABLE `barcode`
  ADD PRIMARY KEY (`barcode_ean`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`expense_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `item_ibfk_1` (`supplier_id`);

--
-- Indexes for table `item_reviews`
--
ALTER TABLE `item_reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD UNIQUE KEY `item_user_unique` (`item_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orderinfo`
--
ALTER TABLE `orderinfo`
  ADD PRIMARY KEY (`orderinfo_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `transaction_id` (`transaction_id`);

--
-- Indexes for table `products_sold`
--
ALTER TABLE `products_sold`
  ADD PRIMARY KEY (`materials_sold_id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `item_id` (`product_id`);

--
-- Indexes for table `rental`
--
ALTER TABLE `rental`
  ADD PRIMARY KEY (`rental_id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `item_reviews`
--
ALTER TABLE `item_reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orderinfo`
--
ALTER TABLE `orderinfo`
  MODIFY `orderinfo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products_sold`
--
ALTER TABLE `products_sold`
  MODIFY `materials_sold_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rental`
--
ALTER TABLE `rental`
  MODIFY `rental_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`) ON DELETE SET NULL;

--
-- Constraints for table `item_reviews`
--
ALTER TABLE `item_reviews`
  ADD CONSTRAINT `item_reviews_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `orderinfo` (`orderinfo_id`) ON DELETE CASCADE;

--
-- Constraints for table `products_sold`
--
ALTER TABLE `products_sold`
  ADD CONSTRAINT `products_sold_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `orderinfo` (`orderinfo_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_sold_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE;

--
-- Constraints for table `rental`
--
ALTER TABLE `rental`
  ADD CONSTRAINT `rental_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `orderinfo` (`orderinfo_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rental_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rental_ibfk_3` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
