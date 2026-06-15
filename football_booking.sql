-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 15, 2026 at 03:51 AM
-- Server version: 8.4.7
-- PHP Version: 8.5.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `football_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_activity_logs`
--

DROP TABLE IF EXISTS `admin_activity_logs`;
CREATE TABLE IF NOT EXISTS `admin_activity_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `admin_id` int NOT NULL,
  `action_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_activity_logs`
--

INSERT INTO `admin_activity_logs` (`id`, `admin_id`, `action_type`, `description`, `ip_address`, `created_at`) VALUES
(1, 2, 'CREATE_CATEGORY', 'Tạo danh mục mới: aaaaaaaaa', '::1', '2026-06-13 09:21:41'),
(2, 2, 'UPDATE_BOOKING', 'Xác nhận đã thanh toán đơn đặt sân ID 2', '::1', '2026-06-13 09:28:50'),
(3, 2, 'UPDATE_BOOKING', 'Xác nhận đã thanh toán đơn đặt sân ID 1', '::1', '2026-06-13 09:29:05'),
(4, 2, 'UPDATE_BOOKING', 'Cập nhật trạng thái đơn đặt sân ID 2 thành CONFIRMED', '::1', '2026-06-13 09:29:07'),
(5, 2, 'UPDATE_BOOKING', 'Xác nhận đã thanh toán đơn đặt sân ID 2', '::1', '2026-06-13 09:29:10'),
(6, 2, 'UPDATE_BOOKING', 'Cập nhật trạng thái đơn đặt sân ID 2 thành CONFIRMED', '::1', '2026-06-13 09:29:12'),
(7, 2, 'UPDATE_BOOKING', 'Xác nhận đã thanh toán đơn đặt sân ID 2', '::1', '2026-06-13 09:29:13'),
(8, 2, 'UPDATE_BOOKING', 'Cập nhật trạng thái đơn đặt sân ID 2 thành CONFIRMED', '::1', '2026-06-13 09:29:15'),
(9, 2, 'UPDATE_BOOKING', 'Cập nhật trạng thái đơn đặt sân ID 1 thành CONFIRMED', '::1', '2026-06-13 09:29:17'),
(10, 2, 'UPDATE_BOOKING', 'Xác nhận đã thanh toán đơn đặt sân ID 2', '::1', '2026-06-13 09:31:46'),
(11, 2, 'UPDATE_BOOKING', 'Xác nhận đã thanh toán đơn đặt sân ID 2', '::1', '2026-06-13 09:31:57'),
(12, 2, 'UPDATE_BOOKING', 'Xác nhận đã thanh toán đơn đặt sân ID 2', '::1', '2026-06-13 09:32:29'),
(13, 2, 'UPDATE_BOOKING', 'Xác nhận đã thanh toán đơn đặt sân ID 2', '::1', '2026-06-13 09:34:10'),
(14, 2, 'UPDATE_BOOKING', 'Xác nhận đã thanh toán đơn đặt sân ID 1', '::1', '2026-06-13 09:34:15'),
(15, 2, 'UPDATE_CATEGORY', 'Cập nhật danh mục ID 6', '::1', '2026-06-14 01:31:39'),
(16, 2, 'UPDATE_BOOKING', 'Cập nhật trạng thái đơn đặt sân ID 3 thành CONFIRMED', '::1', '2026-06-15 00:19:53'),
(17, 2, 'UPDATE_PITCH', 'Cập nhật sân bóng ID 4', '::1', '2026-06-15 00:20:31'),
(18, 2, 'REPLY_REVIEW', 'Phản hồi đánh giá ID 2', '::1', '2026-06-15 00:21:40'),
(19, 2, 'UPDATE_PITCH', 'Cập nhật sân bóng ID 4', '::1', '2026-06-15 00:22:20'),
(20, 2, 'CREATE_PITCH', 'Tạo sân bóng mới: Sân A2', '::1', '2026-06-15 02:36:52'),
(21, 2, 'CREATE_PITCH', 'Tạo sân bóng mới: Sân Cup C1', '::1', '2026-06-15 03:05:30'),
(22, 2, 'UPDATE_PITCH', 'Cập nhật sân bóng ID 4', '::1', '2026-06-15 03:05:45'),
(23, 2, 'UPDATE_PITCH', 'Cập nhật sân bóng ID 3', '::1', '2026-06-15 03:05:53'),
(24, 2, 'UPDATE_PITCH', 'Cập nhật sân bóng ID 2', '::1', '2026-06-15 03:06:00'),
(25, 2, 'UPDATE_PITCH', 'Cập nhật sân bóng ID 5', '::1', '2026-06-15 03:06:06'),
(26, 2, 'UPDATE_PITCH', 'Cập nhật sân bóng ID 1', '::1', '2026-06-15 03:06:13');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `voucher_id` int DEFAULT NULL,
  `pitch_id` int NOT NULL,
  `customer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `total_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('PENDING','CONFIRMED','CANCELLED','PAID') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_bookings_user` (`user_id`),
  KEY `fk_bookings_pitch` (`pitch_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `voucher_id`, `pitch_id`, `customer_name`, `customer_phone`, `booking_date`, `start_time`, `end_time`, `total_price`, `discount_amount`, `status`, `created_at`) VALUES
(1, 2, NULL, 3, 'Lọ Tiên Tôn', '0911901782', '2026-06-13', '19:30:00', '21:00:00', 525000.00, 0.00, 'PAID', '2026-06-13 06:38:39'),
(2, 2, 1, 1, 'Lọ Tiên Tôn', '0911901782', '2026-06-13', '18:00:00', '20:00:00', 350000.00, 50000.00, 'PAID', '2026-06-13 08:55:39'),
(3, 2, 0, 1, 'Lọ Chí Tôn', '0911901782', '2026-06-15', '16:00:00', '17:00:00', 200000.00, 0.00, 'CONFIRMED', '2026-06-15 00:19:26');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Sân 5', 'Sân dành cho 5 người chơi mỗi đội.', '2026-06-13 06:09:59'),
(2, 'Sân 7', 'Sân dành cho 7 người chơi mỗi đội', '2026-06-13 06:09:59'),
(3, 'Sân 11', 'Sân tiêu chuẩn 11 người chơi mỗi đội', '2026-06-13 06:09:59'),
(6, 'aaaaaaaaa', 'aaaaaaaaaaaaaaa', '2026-06-13 09:21:41');

-- --------------------------------------------------------

--
-- Table structure for table `pitches`
--

DROP TABLE IF EXISTS `pitches`;
CREATE TABLE IF NOT EXISTS `pitches` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int NOT NULL,
  `price_per_hour` decimal(10,2) NOT NULL,
  `status` enum('active','maintenance') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pitches_category` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pitches`
--

INSERT INTO `pitches` (`id`, `name`, `category_id`, `price_per_hour`, `status`, `created_at`, `image`) VALUES
(1, 'Sân A1 (Mini 5)', 1, 200000.00, 'active', '2026-06-06 03:50:17', 'pitches/1781492773_S1.jpg'),
(2, 'Sân A2 (Mini 5)', 1, 200000.00, 'active', '2026-06-06 03:50:17', 'pitches/1781492760_S1.jpg'),
(3, 'Sân B1 (Đại 7)', 2, 350000.00, 'active', '2026-06-06 03:50:17', 'pitches/1781492753_S2.jpg'),
(4, 'Sân C1 (Sân 11)', 3, 600000.00, 'maintenance', '2026-06-06 03:50:17', 'pitches/1781492745_image.png'),
(5, 'Sân A2', 2, 30000.00, 'active', '2026-06-15 02:36:52', 'pitches/1781492766_S2.jpg'),
(6, 'Sân Cup C1', 3, 500000.00, 'active', '2026-06-15 03:05:30', 'pitches/1781492730_S1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `pitch_id` int NOT NULL,
  `booking_id` int NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_reply` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('PENDING','REPLIED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'PENDING',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `pitch_id` (`pitch_id`),
  KEY `booking_id` (`booking_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `pitch_id`, `booking_id`, `comment`, `image_path`, `admin_reply`, `status`, `created_at`) VALUES
(1, 2, 3, 1, 'cùi bắp', 'public/uploads/reviews/1781336252_hehe.jpg', 'cái con gà này', 'REPLIED', '2026-06-13 07:37:32'),
(2, 2, 1, 3, 'adhsgdhgjhAGDH', 'public/uploads/reviews/1781482880_nen.png', 'HGSDGSHGHSAD', 'REPLIED', '2026-06-15 00:21:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('customer','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','locked') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `fullname`, `phone`, `role`, `created_at`, `status`) VALUES
(2, 'admin', '$2y$12$vWhyk3XRWG3Pf2WeQnSlU.oxDERwfyKc0QN1nX.bpeF1w6VA/lz9C', 'Lọ Chí Tôn', '0911901782', 'admin', '2026-06-13 04:24:43', 'active'),
(3, 'nguyenvana', '$2y$12$vWhyk3XRWG3Pf2WeQnSlU.oxDERwfyKc0QN1nX.bpeF1w6VA/lz9C', 'Nguyễn Văn A', '0901234567', 'customer', '2026-06-13 06:12:11', 'active'),
(4, 'tranthib', '$2y$12$vWhyk3XRWG3Pf2WeQnSlU.oxDERwfyKc0QN1nX.bpeF1w6VA/lz9C', 'Trần Thị B', '0987654321', 'customer', '2026-06-13 06:12:11', 'active'),
(5, 'lethanhc', '$2y$12$vWhyk3XRWG3Pf2WeQnSlU.oxDERwfyKc0QN1nX.bpeF1w6VA/lz9C', 'Lê Thành C', '0912345678', 'customer', '2026-06-13 06:12:11', 'active'),
(6, 'phamvand', '$2y$12$vWhyk3XRWG3Pf2WeQnSlU.oxDERwfyKc0QN1nX.bpeF1w6VA/lz9C', 'Phạm Văn D', '0923456789', 'customer', '2026-06-13 06:12:11', 'active'),
(7, 'hoangminhe', '$2y$12$vWhyk3XRWG3Pf2WeQnSlU.oxDERwfyKc0QN1nX.bpeF1w6VA/lz9C', 'Hoàng Minh E', '0934567890', 'customer', '2026-06-13 06:12:11', 'active'),
(8, 'dothif', '$2y$12$vWhyk3XRWG3Pf2WeQnSlU.oxDERwfyKc0QN1nX.bpeF1w6VA/lz9C', 'Đỗ Thị F', '0945678901', 'customer', '2026-06-13 06:12:11', 'active'),
(9, 'vovan_g', '$2y$12$vWhyk3XRWG3Pf2WeQnSlU.oxDERwfyKc0QN1nX.bpeF1w6VA/lz9C', 'Võ Văn G', '0956789012', 'customer', '2026-06-13 06:12:11', 'active'),
(10, 'ngothih', '$2y$12$vWhyk3XRWG3Pf2WeQnSlU.oxDERwfyKc0QN1nX.bpeF1w6VA/lz9C', 'Ngô Thị H', '0967890123', 'customer', '2026-06-13 06:12:11', 'active'),
(11, 'buituank', '$2y$12$vWhyk3XRWG3Pf2WeQnSlU.oxDERwfyKc0QN1nX.bpeF1w6VA/lz9C', 'Bùi Tuấn K', '0978901234', 'customer', '2026-06-13 06:12:11', 'active'),
(12, 'admin_phu', '$2y$12$vWhyk3XRWG3Pf2WeQnSlU.oxDERwfyKc0QN1nX.bpeF1w6VA/lz9C', 'Admin Phụ', '0999999999', 'admin', '2026-06-13 06:12:11', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `user_vouchers`
--

DROP TABLE IF EXISTS `user_vouchers`;
CREATE TABLE IF NOT EXISTS `user_vouchers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `voucher_id` int NOT NULL,
  `is_used` tinyint(1) DEFAULT '0',
  `claimed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_voucher_unique` (`user_id`,`voucher_id`),
  KEY `voucher_id` (`voucher_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_vouchers`
--

INSERT INTO `user_vouchers` (`id`, `user_id`, `voucher_id`, `is_used`, `claimed_at`) VALUES
(1, 2, 1, 1, '2026-06-13 08:38:31');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

DROP TABLE IF EXISTS `vouchers`;
CREATE TABLE IF NOT EXISTS `vouchers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `usage_limit` int NOT NULL DEFAULT '0',
  `used_count` int NOT NULL DEFAULT '0',
  `expires_at` datetime NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `discount_amount`, `usage_limit`, `used_count`, `expires_at`, `status`, `created_at`, `updated_at`) VALUES
(1, 'CONGA50K', 50000.00, 5, 1, '2026-07-16 15:15:00', 'active', '2026-06-13 08:15:05', '2026-06-13 08:55:39');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_activity_logs`
--
ALTER TABLE `admin_activity_logs`
  ADD CONSTRAINT `admin_activity_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_bookings_pitch` FOREIGN KEY (`pitch_id`) REFERENCES `pitches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bookings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `pitches`
--
ALTER TABLE `pitches`
  ADD CONSTRAINT `fk_pitches_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`pitch_id`) REFERENCES `pitches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_vouchers`
--
ALTER TABLE `user_vouchers`
  ADD CONSTRAINT `user_vouchers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_vouchers_ibfk_2` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
