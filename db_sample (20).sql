-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2026 at 04:33 PM
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
  `item_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barcode`
--

INSERT INTO `barcode` (`barcode_ean`, `item_id`) VALUES
('1234567890123', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(10) UNSIGNED NOT NULL,
  `title` char(4) DEFAULT NULL,
  `fname` varchar(32) DEFAULT NULL,
  `lname` varchar(32) NOT NULL,
  `addressline` varchar(64) DEFAULT NULL,
  `town` varchar(32) DEFAULT NULL,
  `zipcode` char(10) NOT NULL,
  `phone` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `title`, `fname`, `lname`, `addressline`, `town`, `zipcode`, `phone`) VALUES
(1, 'Mr.', 'Alice', 'Wonderland', '123 Rabbit Hole', 'London', '12345', '09123456789');

-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

CREATE TABLE `expense` (
  `expense_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `expense_date` date NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expense`
--

INSERT INTO `expense` (`expense_id`, `title`, `amount`, `expense_date`, `notes`) VALUES
(1, 'Electricity Bill', 1500.00, '2026-03-22', 'Utilities for the month');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_id` int(10) UNSIGNED NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `sell_price` decimal(10,2) DEFAULT NULL,
  `image_path` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `supplier_id` int(10) UNSIGNED DEFAULT NULL,
  `type` enum('product','tool') NOT NULL DEFAULT 'product'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`item_id`, `title`, `description`, `cost_price`, `sell_price`, `image_path`, `created_at`, `updated_at`, `deleted_at`, `category`, `stock_quantity`, `supplier_id`, `type`) VALUES
(1, 'Spark Plug', 'NGK Iridium spark plug for most engines', 150.00, 250.00, 'images/items/sparkplug.jpg', '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL, 'Engine', 50, 1, 'product'),
(2, 'Oil Filter', 'High-quality oil filter for sedans', 200.00, 350.00, 'images/items/oilfilter.jpg', '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL, 'Engine', 30, 1, 'product'),
(3, 'Brake Pad Set', 'Ceramic brake pads for front wheels', 800.00, 1200.00, 'images/items/diskbrake front.jpg', '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL, 'Bodywork', 20, 1, 'product'),
(4, 'Impact Wrench', 'Electric impact wrench for rent', 3000.00, 150.00, 'images/items/impact wrench.jpg', '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL, 'Other', 5, 2, 'tool'),
(5, 'Akrapovič Slip-On Exhaust (Titanium)', 'Lightweight high-performance exhaust that improves throttle response and engine sound.', 38500.00, 46160.00, 'images/items/Akrapovič Slip-On Exhaust (Titanium).jpg', '2026-03-21 22:33:56', '2026-03-22 07:31:25', NULL, 'Engine', 9, 1, 'product'),
(6, 'Alpinestars Tech-Air 5 Airbag Vest', 'An autonomous wearable airbag system that provides upper body protection during a crash.', 39000.00, 48500.00, 'images/items/Alpinestars Tech-Air 5 Airbag Vest.png', '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL, 'Bodywork (Safety)', 5, 2, 'product'),
(7, 'Yuasa YTZ6V Maintenance-Free Battery', 'High-performance AGM battery commonly used for NMAX, Click, and ADV models.', 1650.00, 2100.00, 'images/items/Yuasa YTZ6V Maintenance-Free Battery.jpg', '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL, 'Electrical', 25, 1, 'product'),
(8, 'Quad Lock Vibration Dampener', 'A specialized mount add-on that protects smartphone camera sensors from engine vibrations.', 1150.00, 1699.00, 'images/items/Quad Lock Vibration Dampener.jpg', '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL, 'Other (Accessories)', 50, 2, 'product'),
(9, 'Forcite MK1S Smart Helmet', 'Carbon fiber helmet with built-in 4K camera, navigation LEDs, and Harman Kardon audio.', 51000.00, 62500.00, 'images/items/Forcite MK1S Smart Helmet.jpg', '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL, 'Other (Safety)', 3, 2, 'product'),
(10, 'Fluke 101 Digital Multimeter', 'Professional-grade handheld tool for diagnosing battery and electrical wiring issues.', 2800.00, 3840.00, 'images/items/Fluke 101 Digital Multimeter.jpg', '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL, 'Electrical', 12, 2, 'product'),
(11, 'Knipex 86 03 180 Pliers Wrench', 'Replaces a full set of metric and imperial wrenches; smooth jaws prevent damage to chrome bolts.', 2950.00, 3730.00, 'images/items/Knipex 86 03 180 Pliers Wrench.jpg', '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL, 'Bodywork (Tools)', 8, 2, 'product'),
(12, '1/2\" Drive Click-Type Torque Wrench', 'Tool used to ensure axle nuts and engine bolts are tightened to exact manufacturer specs.', 1200.00, 1600.00, 'images/items/Drive Click-Type Torque Wrench.jpg', '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL, 'Other (Maintenance)', 5, 2, 'tool'),
(13, 'Motion Pro PBR Chain Tool', 'Specialty tool used to break, press, and rivet motorcycle drive chains.', 5800.00, 500.00, 'images/items/Motion Pro PBR Chain Tool.jpg', '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL, 'Other (Maintenance)', 2, 2, 'tool'),
(14, 'Spectro V-Twin Full Synthetic Oil Kit', 'Complete kit including engine oil, filter, and O-rings for a single service.', 3400.00, 4250.00, 'images/items/Spectro V-Twin Full Synthetic Oil Kit.jpg', '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL, 'Consumables', 15, 1, 'product'),
(15, 'Yamaha Sniper 155 Throttle Body (34mm)', 'Performance upgrade to increase air intake, providing better acceleration and top-end power.', 3100.00, 3850.00, 'images/items/Yamaha Sniper 155 Throttle Body (34mm).png', '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL, 'Engine', 12, 1, 'product'),
(16, 'Firefly Mini Driving Light V2 (Dual Color)', 'Auxiliary LED lights with high-beam (white) and low-beam (yellow) for better visibility in rain or fog.', 280.00, 425.00, 'images/items/Firefly Mini Driving Light V2 (Dual Color).jpg', '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL, 'Electrical', 40, 1, 'product'),
(17, 'RCB (Racing Boy) S1 Forged Brake Master Cylinder', 'High-precision brake lever and pump that provides a more responsive and \"solid\" braking feel.', 2100.00, 2850.00, 'images/items/RCB (Racing Boy) S1 Forged Brake Master Cylinder.jpg', '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL, 'Bodywork', 8, 1, 'product'),
(18, 'Metzeler Roadtec Scooter Tire (110/70-13)', 'Premium wet-weather tire designed for high-grip performance on Philippine asphalt.', 1100.00, 2990.00, 'images/items/Metzeler Roadtec Scooter Tire.jpg', '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL, 'Consumables', 20, 1, 'product'),
(19, 'RK Takasago Gold Chain & Sprocket Set (428 Series)', 'Heavy-duty drive chain set known for durability and reduced friction; a favorite for underbone bikes.', 1800.00, 2450.00, 'images/items/RK Takasago Gold Chain & Sprocket Set (428 Series).jpg', '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL, 'Consumables', 15, 1, 'product'),
(20, 'PIAA OTO Style Horn (12V)', 'A loud, car-like deep tone horn upgrade to ensure you are heard by larger vehicles on the road.', 210.00, 325.00, 'images/items/PIAA OTO Style Horn (12V).jpg', '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL, 'Electrical', 30, 1, 'product'),
(21, 'Oxford Aquatex Waterproof Motorcycle Cover', 'Double-stitched nylon cover that protects the bike from UV rays, rain, and dust during storage.', 1050.00, 1490.00, 'images/items/Oxford Aquatex Waterproof Motorcycle Cover.jpg', '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL, 'Other', 10, 2, 'product'),
(22, 'Makita Brushless Impact Wrench (1/2\" Drive)', 'High-torque power tool used for quickly removing stubborn axle nuts or CVT bolts.', 1650.00, 250.00, 'images/items/Makita Brushless Impact Wrench.jpg', '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL, 'Other (Tools)', 4, 2, 'tool'),
(23, 'Flyman 46-Piece Socket Wrench Set', 'A comprehensive set of sockets and driver bits used for almost all general motorcycle repairs.', 850.00, 1150.00, 'images/items/Flyman 46-Piece Socket Wrench Set.jpg', '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL, 'Other (Tools)', 20, 2, 'product'),
(24, '12V Portable Jump Starter (98800mAh)', 'A compact power bank capable of jump-starting a motorcycle with a dead battery in seconds.', 820.00, 1098.00, 'images/items/12V Portable Jump Starter (98800mAh).jpg', '2026-03-21 22:33:56', '2026-03-22 06:20:26', NULL, 'Electrical', 14, 2, 'product'),
(25, 'Alpinestars Supertech R10 Helmet', 'The result of over 10 years of intensive study, development, and testing, the goal of the Supertech family of helmets is to create the most advanced, protective, and performance-enhancing helmets possible for racers and riders worldwide.', 12999.00, 15999.00, 'images/items/item_69bfc96b022b02.30227071.png', '2026-03-22 02:50:19', '2026-03-22 06:51:31', NULL, 'Other', 29, 1, 'product'),
(26, 'NGK Iridium Spark Plug', 'Premium iridium spark plug for 4-stroke motorcycle engines. Long-lasting and reliable ignition performance.', 120.00, 250.00, '', '2026-03-22 06:08:43', '2026-03-22 06:10:09', '2026-03-22 06:10:09', 'Engine', 75, NULL, 'product'),
(27, 'Motul 5100 10W-40 Engine Oil (1L)', 'Semi-synthetic 4-stroke motorcycle engine oil. Excellent thermal stability and engine protection.', 320.00, 550.00, '', '2026-03-22 06:08:43', '2026-03-22 06:10:09', '2026-03-22 06:10:09', 'Engine', 120, NULL, 'product'),
(28, 'EBC Double-H Sintered Brake Pads', 'High-performance sintered brake pads for sport and street riding. Excellent wet and dry braking.', 450.00, 850.00, '', '2026-03-22 06:08:43', '2026-03-22 06:10:09', '2026-03-22 06:10:09', 'Brakes', 40, NULL, 'product'),
(29, 'DID 520VX3 Gold X-Ring Chain', 'Professional-grade X-ring chain with gold side plates. Superior durability for street and track use.', 1800.00, 3200.00, '', '2026-03-22 06:08:43', '2026-03-22 06:10:09', '2026-03-22 06:10:09', 'Drivetrain', 25, NULL, 'product'),
(30, 'Koso RX-2N GP Style Speedometer', 'Digital speedometer with tachometer, odometer, and fuel gauge. Compact, modern dashboard upgrade.', 2200.00, 3800.00, '', '2026-03-22 06:08:43', '2026-03-22 06:10:09', '2026-03-22 06:10:09', 'Electrical', 15, NULL, 'product'),
(31, 'Motion Pro Cable Luber V3', 'Professional cable lubrication tool. Makes throttle and clutch cable maintenance quick and mess-free.', 350.00, 650.00, '', '2026-03-22 06:08:43', '2026-03-22 06:10:09', '2026-03-22 06:10:09', 'Tools', 30, NULL, 'tool'),
(32, 'Park Tool Torque Wrench TW-5.2', 'Precision click-type torque wrench with 3-15 Nm range. Essential for proper bolt tightening on delicate components.', 1500.00, 2800.00, '', '2026-03-22 06:08:43', '2026-03-22 06:10:09', '2026-03-22 06:10:09', 'Tools', 10, NULL, 'tool'),
(33, 'K&N High-Flow Air Filter', 'Washable and reusable high-flow air filter. Increases airflow by up to 50% over stock paper filters.', 800.00, 1500.00, '', '2026-03-22 06:08:43', '2026-03-22 06:10:09', '2026-03-22 06:10:09', 'Engine', 35, NULL, 'product'),
(34, 'Yoshimura Alpha T Slip-On Exhaust', 'Stainless steel slip-on exhaust with carbon fiber end cap. Improved performance and aggressive sound.', 8500.00, 15999.00, '', '2026-03-22 06:08:43', '2026-03-22 06:10:09', '2026-03-22 06:10:09', 'Exhaust', 5, NULL, 'product'),
(35, 'Renthal Fatbar Handlebar', 'Oversized 28.6mm handlebar with variable wall thickness. 7075-T6 aluminum for maximum strength.', 2000.00, 3500.00, '', '2026-03-22 06:08:43', '2026-03-22 06:10:09', '2026-03-22 06:10:09', 'Chassis', 20, NULL, 'product');

-- --------------------------------------------------------

--
-- Table structure for table `item_images`
--

CREATE TABLE `item_images` (
  `image_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_images`
--

INSERT INTO `item_images` (`image_id`, `item_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'images/items/sparkplug.jpg', 1, 0, '2026-03-21 22:33:56', '2026-03-21 22:33:56'),
(8, 25, 'images/items/item_69bfc96b022b02.30227071.png', 1, 0, NULL, NULL),
(9, 25, 'images/items/item_69bfc96b0569b0.43594315.jpg', 0, 1, NULL, NULL),
(10, 25, 'images/items/item_69bfc96b0677f1.19106388.jpg', 0, 2, NULL, NULL),
(11, 25, 'images/items/item_69bfc96b073c99.99478928.png', 0, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `item_reviews`
--

CREATE TABLE `item_reviews` (
  `review_id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_reviews`
--

INSERT INTO `item_reviews` (`review_id`, `item_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 2, 5, 'Excellent quality!', '2026-03-22 06:33:56'),
(2, 24, 3, 4, 'Pretty effective!', '2026-03-22 14:45:53'),
(3, 25, 3, 4, 'IT PROTECTED MY HEAD very gud', '2026-03-22 14:52:20'),
(4, 5, 3, 5, 'MY MOTOR NOW GOES BWOPAPAPAP', '2026-03-22 15:32:18');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '0001_01_01_000003_create_suppliers_table', 1),
(5, '0001_01_01_000004_create_items_table', 1),
(6, '0001_01_01_000005_create_barcodes_table', 1),
(7, '0001_01_01_000006_create_stock_table', 1),
(8, '0001_01_01_000007_create_customer_table', 1),
(9, '0001_01_01_000008_create_orderinfo_table', 1),
(10, '0001_01_01_000009_create_orderline_table', 1),
(11, '0001_01_01_000010_create_payment_table', 1),
(12, '0001_01_01_000011_create_products_sold_table', 1),
(13, '0001_01_01_000012_create_rental_table', 1),
(14, '0001_01_01_000013_create_expense_table', 1),
(15, '0001_01_01_000014_create_item_reviews_table', 1),
(16, '2026_03_11_212302_create_item_images_table', 1),
(17, '2026_03_11_213428_add_status_to_users_table', 1),
(18, '2026_03_11_215606_add_email_verification_token_to_users_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orderinfo`
--

CREATE TABLE `orderinfo` (
  `orderinfo_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(11) NOT NULL DEFAULT 0,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date_placed` date NOT NULL,
  `date_shipped` date DEFAULT NULL,
  `shipping` decimal(7,2) DEFAULT NULL,
  `status` enum('Processing','Delivered','Canceled') NOT NULL DEFAULT 'Processing',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orderinfo`
--

INSERT INTO `orderinfo` (`orderinfo_id`, `customer_id`, `user_id`, `date_placed`, `date_shipped`, `shipping`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '2026-03-22', '2026-03-24', 50.00, 'Delivered', '2026-03-21 22:33:56', '2026-03-21 22:33:56'),
(2, 0, 3, '2026-03-22', NULL, NULL, 'Delivered', '2026-03-22 06:20:26', '2026-03-22 06:34:46'),
(3, 0, 3, '2026-03-22', NULL, NULL, 'Delivered', '2026-03-22 06:51:31', '2026-03-22 06:51:59'),
(4, 0, 3, '2026-03-22', NULL, NULL, 'Delivered', '2026-03-22 07:31:25', '2026-03-22 07:31:51');

-- --------------------------------------------------------

--
-- Table structure for table `orderline`
--

CREATE TABLE `orderline` (
  `orderinfo_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `rate` decimal(7,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orderline`
--

INSERT INTO `orderline` (`orderinfo_id`, `product_id`, `quantity`, `rate`) VALUES
(1, 1, 2, 250.00);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(10) UNSIGNED NOT NULL,
  `transaction_id` int(10) UNSIGNED NOT NULL,
  `payment_type` varchar(32) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `transaction_id`, `payment_type`, `amount`, `payment_date`) VALUES
(1, 1, 'Credit Card', 550.00, '2026-03-21 22:33:56'),
(2, 2, NULL, 1098.00, '2026-03-22 06:20:26'),
(3, 3, NULL, 15999.00, '2026-03-22 06:51:31'),
(4, 4, NULL, 46160.00, '2026-03-22 07:31:25');

-- --------------------------------------------------------

--
-- Table structure for table `products_sold`
--

CREATE TABLE `products_sold` (
  `materials_sold_id` int(10) UNSIGNED NOT NULL,
  `transaction_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `rate_charged` decimal(7,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products_sold`
--

INSERT INTO `products_sold` (`materials_sold_id`, `transaction_id`, `product_id`, `quantity`, `rate_charged`) VALUES
(1, 1, 1, 2, 250.00),
(2, 2, 24, 1, 1098.00),
(3, 3, 25, 1, 15999.00),
(4, 4, 5, 1, 46160.00);

-- --------------------------------------------------------

--
-- Table structure for table `rental`
--

CREATE TABLE `rental` (
  `rental_id` int(10) UNSIGNED NOT NULL,
  `transaction_id` int(10) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `due_date` date NOT NULL,
  `rate_charged` decimal(7,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rental`
--

INSERT INTO `rental` (`rental_id`, `transaction_id`, `customer_id`, `item_id`, `start_date`, `due_date`, `rate_charged`, `quantity`) VALUES
(1, 1, 2, 4, '2026-03-22', '2026-03-23', 150.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `item_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`item_id`, `quantity`) VALUES
(1, 50);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(50) DEFAULT NULL,
  `lead_time` varchar(50) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `name`, `contact_email`, `contact_phone`, `lead_time`, `website`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'AutoParts Co.', 'info@autoparts.com', '09171234567', '3-5 days', 'https://autoparts.com', '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL),
(2, 'ToolRent PH', 'hello@toolrent.ph', '09181234567', '1-2 days', 'https://toolrent.ph', '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `email_verification_token` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'customer',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
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

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `email_verification_token`, `password`, `role`, `status`, `remember_token`, `created_at`, `updated_at`, `fcm_token`, `avatar`, `title`, `fname`, `lname`, `addressline`, `town`, `zipcode`, `phone`) VALUES
(1, 'Admin User', 'admin@brutor.com', '2026-03-21 22:33:56', NULL, '$2y$12$jTPlYzDzzY3AqHG4gigOseLdkHIrwumfkWWHAKFlyxccci1XOFhY2', 'admin', 'active', NULL, '2026-03-21 22:33:56', '2026-03-21 22:33:56', NULL, 'images/avatars/jerome.jpg', NULL, 'Admin', 'User', NULL, NULL, NULL, NULL),
(2, 'John Doe', 'john@example.com', '2026-03-21 22:33:56', NULL, '$2y$12$2ey5v56cLUkQi7Q0EoSgF.2sME6NbFr1KMQIvNT1ZFgFJ2PSa5CJS', 'customer', 'active', NULL, '2026-03-21 22:33:56', '2026-03-22 06:40:52', NULL, 'images/avatars/jerome.jpg', NULL, 'John', 'Doe', NULL, NULL, NULL, NULL),
(3, 'Ericksson Brutas', 'erickssonbrutas@motor.com', '2026-03-22 03:05:55', NULL, '$2y$12$iO6ra.xg6BrfYElYyBoUtuVN9X8fneWDPT6n8QaJPA80A4koal4/e', 'customer', 'active', NULL, '2026-03-22 02:51:36', '2026-03-22 06:33:52', NULL, 'images/avatars/user_3_1774188797.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'Demo Student', 'demostudent@example.com', '2026-03-22 03:00:21', NULL, '$2y$12$Bcqe8Bxvg8EM0kUuHSBt..sYhqdlzGB1AXWnW.MDx3oTXDdjIM3ly', 'customer', 'active', NULL, '2026-03-22 03:00:14', '2026-03-22 03:00:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'Demo Student 2', 'demostudent2@example.com', '2026-03-22 03:03:38', NULL, '$2y$12$YkUG4rE7923NNdmDU5zbRuv6TsizAHEuHXS7XbFh3tkymmER5RZz.', 'customer', 'active', NULL, '2026-03-22 03:03:31', '2026-03-22 03:03:38', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barcode`
--
ALTER TABLE `barcode`
  ADD PRIMARY KEY (`barcode_ean`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

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
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `item_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `item_images`
--
ALTER TABLE `item_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `item_images_item_id_foreign` (`item_id`);

--
-- Indexes for table `item_reviews`
--
ALTER TABLE `item_reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD UNIQUE KEY `item_user_unique` (`item_id`,`user_id`),
  ADD KEY `item_reviews_user_id_foreign` (`user_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orderinfo`
--
ALTER TABLE `orderinfo`
  ADD PRIMARY KEY (`orderinfo_id`),
  ADD KEY `orderinfo_user_id_foreign` (`user_id`);

--
-- Indexes for table `orderline`
--
ALTER TABLE `orderline`
  ADD PRIMARY KEY (`orderinfo_id`,`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `payment_transaction_id_foreign` (`transaction_id`);

--
-- Indexes for table `products_sold`
--
ALTER TABLE `products_sold`
  ADD PRIMARY KEY (`materials_sold_id`),
  ADD KEY `products_sold_transaction_id_foreign` (`transaction_id`),
  ADD KEY `products_sold_product_id_foreign` (`product_id`);

--
-- Indexes for table `rental`
--
ALTER TABLE `rental`
  ADD PRIMARY KEY (`rental_id`),
  ADD KEY `rental_transaction_id_foreign` (`transaction_id`),
  ADD KEY `rental_customer_id_foreign` (`customer_id`),
  ADD KEY `rental_item_id_foreign` (`item_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
  MODIFY `customer_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
  MODIFY `expense_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `item_images`
--
ALTER TABLE `item_images`
  MODIFY `image_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `item_reviews`
--
ALTER TABLE `item_reviews`
  MODIFY `review_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `orderinfo`
--
ALTER TABLE `orderinfo`
  MODIFY `orderinfo_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products_sold`
--
ALTER TABLE `products_sold`
  MODIFY `materials_sold_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rental`
--
ALTER TABLE `rental`
  MODIFY `rental_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`) ON DELETE SET NULL;

--
-- Constraints for table `item_images`
--
ALTER TABLE `item_images`
  ADD CONSTRAINT `item_images_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE;

--
-- Constraints for table `item_reviews`
--
ALTER TABLE `item_reviews`
  ADD CONSTRAINT `item_reviews_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orderinfo`
--
ALTER TABLE `orderinfo`
  ADD CONSTRAINT `orderinfo_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `orderinfo` (`orderinfo_id`) ON DELETE CASCADE;

--
-- Constraints for table `products_sold`
--
ALTER TABLE `products_sold`
  ADD CONSTRAINT `products_sold_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_sold_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `orderinfo` (`orderinfo_id`) ON DELETE CASCADE;

--
-- Constraints for table `rental`
--
ALTER TABLE `rental`
  ADD CONSTRAINT `rental_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rental_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rental_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `orderinfo` (`orderinfo_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
