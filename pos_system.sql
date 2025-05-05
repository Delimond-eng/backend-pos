-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2025 at 01:26 PM
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
-- Database: `pos_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `expense_type_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `date`, `amount`, `description`, `expense_type_id`, `user_id`, `created_at`, `updated_at`) VALUES
(2, '2025-05-03', 1800.00, 'Lorem', 2, 1, '2025-05-03 14:51:52', '2025-05-03 14:51:52'),
(3, '2025-05-04', 4500.00, NULL, 1, 1, '2025-05-04 18:47:00', '2025-05-04 18:47:00');

-- --------------------------------------------------------

--
-- Table structure for table `expense_types`
--

CREATE TABLE `expense_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expense_types`
--

INSERT INTO `expense_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Transport', '2025-05-03 14:41:34', '2025-05-03 14:41:34'),
(2, 'Restauration', '2025-05-03 14:41:43', '2025-05-03 14:41:43');

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
-- Table structure for table `inventories`
--

CREATE TABLE `inventories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `status` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventories`
--

INSERT INTO `inventories` (`id`, `date`, `status`, `user_id`, `created_at`, `updated_at`) VALUES
(2, '2025-05-04', 'validated', 1, '2025-05-04 16:39:27', '2025-05-04 16:48:30'),
(3, '2025-05-04', 'validated', 1, '2025-05-04 17:32:05', '2025-05-04 17:32:45');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_lines`
--

CREATE TABLE `inventory_lines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `inventory_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `theoretical_qty` int(11) NOT NULL,
  `real_qty` int(11) NOT NULL,
  `difference` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_lines`
--

INSERT INTO `inventory_lines` (`id`, `inventory_id`, `product_id`, `theoretical_qty`, `real_qty`, `difference`, `created_at`, `updated_at`) VALUES
(1, 2, 4, 40, 38, -2, '2025-05-04 16:48:30', '2025-05-04 16:48:30'),
(2, 2, 3, 10, 8, -2, '2025-05-04 16:48:30', '2025-05-04 16:48:30'),
(3, 2, 2, 12, 10, -2, '2025-05-04 16:48:30', '2025-05-04 16:48:30'),
(4, 3, 4, 38, 38, 0, '2025-05-04 17:32:45', '2025-05-04 17:32:45'),
(5, 3, 3, 8, 8, 0, '2025-05-04 17:32:45', '2025-05-04 17:32:45'),
(6, 3, 2, 10, 10, 0, '2025-05-04 17:32:45', '2025-05-04 17:32:45'),
(7, 3, 1, 32, 34, 2, '2025-05-04 17:32:45', '2025-05-04 17:32:45');

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_12_13_080930_create_jobs_table', 1),
(6, '2025_05_01_001636_create_product_categories_table', 1),
(7, '2025_05_01_001732_create_products_table', 1),
(8, '2025_05_01_001825_create_purchases_table', 1),
(9, '2025_05_01_001844_create_purchase_items_table', 1),
(10, '2025_05_01_001902_create_sales_table', 1),
(11, '2025_05_01_001923_create_sale_items_table', 1),
(12, '2025_05_01_001943_create_expenses_table', 1),
(13, '2025_05_01_002032_create_stock_outputs_table', 1),
(14, '2025_05_01_002104_create_inventories_table', 1),
(15, '2025_05_01_002131_create_inventory_lines_table', 1),
(16, '2025_05_01_002157_create_stock_movements_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `code_barre` varchar(255) DEFAULT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `photo`, `name`, `category_id`, `code_barre`, `unit_price`, `stock`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Gateaux new lis', 2, NULL, 1500.00, 34, '2025-05-03 14:25:54', '2025-05-04 17:32:45'),
(2, NULL, 'Cornet de bœuf', 4, NULL, 7500.00, 0, '2025-05-03 14:53:19', '2025-05-04 23:27:16'),
(3, NULL, 'Farnine de froma', 4, NULL, 45000.00, 0, '2025-05-03 14:53:48', '2025-05-04 18:48:11'),
(4, NULL, 'Jus Tangawisi', 3, NULL, 2000.00, 38, '2025-05-03 14:54:13', '2025-05-04 16:48:30');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Produits frais', '2025-05-03 14:25:54', '2025-05-03 14:25:54'),
(2, 'Épicerie', '2025-05-03 14:25:54', '2025-05-03 14:25:54'),
(3, 'Boissons', '2025-05-03 14:25:54', '2025-05-03 14:25:54'),
(4, 'Produits bio', '2025-05-03 14:25:54', '2025-05-03 14:25:54'),
(5, 'Produits surgelés', '2025-05-03 14:25:54', '2025-05-03 14:25:54'),
(6, 'Pâtisserie', '2025-05-03 14:25:54', '2025-05-03 14:25:54'),
(7, 'Pour animaux', '2025-05-03 14:25:54', '2025-05-03 14:25:54'),
(8, 'Produit de base', '2025-05-03 21:04:17', '2025-05-03 21:04:17');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `date` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `supplier_name`, `date`, `total_amount`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'New Lys', '2025-05-03', 25000.00, 1, '2025-05-03 14:26:41', '2025-05-03 14:26:41'),
(2, 'New Lys', '2025-05-03', 22000.00, 1, '2025-05-03 14:28:20', '2025-05-03 14:28:20'),
(3, NULL, '2025-05-03', 375000.00, 1, '2025-05-03 14:55:06', '2025-05-03 14:55:06'),
(4, NULL, '2025-05-03', 36000.00, 1, '2025-05-03 20:32:26', '2025-05-03 20:32:26'),
(5, NULL, '2025-05-03', 72000.00, 1, '2025-05-03 20:32:55', '2025-05-03 20:32:55'),
(6, NULL, '2025-05-03', 300000.00, 1, '2025-05-03 20:34:22', '2025-05-03 20:34:22'),
(7, NULL, '2025-05-04', 37500.00, 1, '2025-05-04 04:30:58', '2025-05-04 04:30:58');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

CREATE TABLE `purchase_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_items`
--

INSERT INTO `purchase_items` (`id`, `purchase_id`, `product_id`, `quantity`, `unit_price`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 25, 1000.00, '2025-05-03 14:26:41', '2025-05-03 14:26:41'),
(2, 2, 1, 22, 1000.00, '2025-05-03 14:28:20', '2025-05-03 14:28:20'),
(3, 3, 3, 15, 25000.00, '2025-05-03 14:55:06', '2025-05-03 14:55:06'),
(4, 4, 4, 30, 1200.00, '2025-05-03 20:32:26', '2025-05-03 20:32:26'),
(5, 5, 2, 12, 6000.00, '2025-05-03 20:32:55', '2025-05-03 20:32:55'),
(6, 6, 3, 12, 25000.00, '2025-05-03 20:34:22', '2025-05-03 20:34:22'),
(7, 7, 4, 25, 1500.00, '2025-05-04 04:30:58', '2025-05-04 04:30:58');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `date` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `customer_name`, `date`, `total_amount`, `user_id`, `created_at`, `updated_at`) VALUES
(3, '', '2025-05-03', 48000.00, 1, '2025-05-03 18:08:40', '2025-05-04 00:30:12'),
(4, '', '2025-05-03', 91500.00, 1, '2025-05-03 18:09:09', '2025-05-04 00:28:14'),
(5, '', '2025-05-03', 6000.00, 1, '2025-05-03 18:09:18', '2025-05-03 23:04:39'),
(6, '', '2025-05-03', 91500.00, 1, '2025-05-03 18:34:46', '2025-05-03 18:34:46'),
(7, '', '2025-05-03', 48000.00, 1, '2025-05-03 18:38:30', '2025-05-03 18:38:30'),
(8, '', '2025-05-03', 135000.00, 1, '2025-05-03 20:22:25', '2025-05-03 20:22:25'),
(9, '', '2025-05-03', 6000.00, 1, '2025-05-03 20:30:30', '2025-05-03 20:30:30'),
(10, '', '2025-05-03', 90000.00, 1, '2025-05-03 20:39:00', '2025-05-03 20:39:00'),
(11, '', '2025-05-03', 93500.00, 1, '2025-05-03 22:26:51', '2025-05-03 22:26:51'),
(12, '', '2025-05-04', 6000.00, 1, '2025-05-04 04:31:52', '2025-05-04 04:31:52'),
(13, '', '2025-05-04', 51000.00, 1, '2025-05-04 05:07:22', '2025-05-04 05:07:22'),
(14, '', '2025-05-04', 360000.00, 1, '2025-05-04 18:48:11', '2025-05-04 18:48:11'),
(15, '', '2025-05-05', 22500.00, 1, '2025-05-04 23:26:50', '2025-05-04 23:26:50'),
(16, '', '2025-05-05', 52500.00, 1, '2025-05-04 23:27:16', '2025-05-04 23:27:16');

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `product_id`, `quantity`, `unit_price`, `created_at`, `updated_at`) VALUES
(1, 3, 3, 1, 45000.00, '2025-05-03 18:08:40', '2025-05-04 00:30:12'),
(2, 3, 1, 2, 1500.00, '2025-05-03 18:08:40', '2025-05-03 18:08:40'),
(3, 4, 3, 2, 45000.00, '2025-05-03 18:09:09', '2025-05-04 00:28:14'),
(4, 4, 1, 1, 1500.00, '2025-05-03 18:09:10', '2025-05-03 23:06:34'),
(5, 5, 1, 4, 1500.00, '2025-05-03 18:09:18', '2025-05-03 18:09:18'),
(6, 5, 3, 0, 45000.00, '2025-05-03 18:09:18', '2025-05-03 23:04:39'),
(7, 6, 3, 2, 45000.00, '2025-05-03 18:34:46', '2025-05-03 18:34:46'),
(8, 6, 1, 1, 1500.00, '2025-05-03 18:34:46', '2025-05-03 18:34:46'),
(9, 7, 3, 1, 45000.00, '2025-05-03 18:38:30', '2025-05-03 18:38:30'),
(10, 7, 1, 2, 1500.00, '2025-05-03 18:38:30', '2025-05-03 18:38:30'),
(11, 8, 3, 3, 45000.00, '2025-05-03 20:22:25', '2025-05-03 20:22:25'),
(12, 9, 1, 4, 1500.00, '2025-05-03 20:30:30', '2025-05-03 20:30:30'),
(13, 10, 3, 2, 45000.00, '2025-05-03 20:39:00', '2025-05-03 20:39:00'),
(14, 11, 4, 1, 2000.00, '2025-05-03 22:26:51', '2025-05-03 22:26:51'),
(15, 11, 1, 1, 1500.00, '2025-05-03 22:26:51', '2025-05-03 22:26:51'),
(16, 11, 3, 2, 45000.00, '2025-05-03 22:26:51', '2025-05-03 22:26:51'),
(17, 12, 4, 3, 2000.00, '2025-05-04 04:31:52', '2025-05-04 04:31:52'),
(18, 13, 4, 3, 2000.00, '2025-05-04 05:07:22', '2025-05-04 05:07:22'),
(19, 13, 3, 1, 45000.00, '2025-05-04 05:07:22', '2025-05-04 05:07:22'),
(20, 14, 3, 8, 45000.00, '2025-05-04 18:48:11', '2025-05-04 18:48:11'),
(21, 15, 2, 3, 7500.00, '2025-05-04 23:26:50', '2025-05-04 23:26:50'),
(22, 16, 2, 7, 7500.00, '2025-05-04 23:27:16', '2025-05-04 23:27:16');

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `product_id`, `quantity`, `type`, `created_at`, `updated_at`) VALUES
(1, 1, 25, 'purchase', '2025-05-03 14:26:41', '2025-05-03 14:26:41'),
(2, 1, 22, 'purchase', '2025-05-03 14:28:20', '2025-05-03 14:28:20'),
(3, 3, 15, 'purchase', '2025-05-03 14:55:06', '2025-05-03 14:55:06'),
(4, 3, -2, 'sale', '2025-05-03 18:08:40', '2025-05-03 18:08:40'),
(5, 1, -2, 'sale', '2025-05-03 18:08:40', '2025-05-03 18:08:40'),
(6, 3, -3, 'sale', '2025-05-03 18:09:10', '2025-05-03 18:09:10'),
(7, 1, -4, 'sale', '2025-05-03 18:09:10', '2025-05-03 18:09:10'),
(8, 1, -4, 'sale', '2025-05-03 18:09:18', '2025-05-03 18:09:18'),
(9, 3, -4, 'sale', '2025-05-03 18:09:18', '2025-05-03 18:09:18'),
(10, 3, -2, 'sale', '2025-05-03 18:34:46', '2025-05-03 18:34:46'),
(11, 1, -1, 'sale', '2025-05-03 18:34:46', '2025-05-03 18:34:46'),
(12, 3, -1, 'sale', '2025-05-03 18:38:30', '2025-05-03 18:38:30'),
(13, 1, -2, 'sale', '2025-05-03 18:38:30', '2025-05-03 18:38:30'),
(14, 3, -3, 'sale', '2025-05-03 20:22:25', '2025-05-03 20:22:25'),
(15, 1, -4, 'sale', '2025-05-03 20:30:30', '2025-05-03 20:30:30'),
(16, 4, 30, 'purchase', '2025-05-03 20:32:26', '2025-05-03 20:32:26'),
(17, 2, 12, 'purchase', '2025-05-03 20:32:55', '2025-05-03 20:32:55'),
(18, 3, 12, 'purchase', '2025-05-03 20:34:22', '2025-05-03 20:34:22'),
(19, 3, -2, 'sale', '2025-05-03 20:39:00', '2025-05-03 20:39:00'),
(20, 4, -1, 'sale', '2025-05-03 22:26:51', '2025-05-03 22:26:51'),
(21, 1, -1, 'sale', '2025-05-03 22:26:51', '2025-05-03 22:26:51'),
(22, 3, -2, 'sale', '2025-05-03 22:26:51', '2025-05-03 22:26:51'),
(23, 3, 4, 'return', '2025-05-03 23:04:39', '2025-05-03 23:04:39'),
(24, 1, 3, 'return', '2025-05-03 23:06:34', '2025-05-03 23:06:34'),
(25, 3, 1, 'return', '2025-05-04 00:28:14', '2025-05-04 00:28:14'),
(26, 3, 1, 'return', '2025-05-04 00:30:12', '2025-05-04 00:30:12'),
(27, 4, 25, 'purchase', '2025-05-04 04:30:58', '2025-05-04 04:30:58'),
(28, 4, -3, 'sale', '2025-05-04 04:31:52', '2025-05-04 04:31:52'),
(29, 4, -3, 'sale', '2025-05-04 05:07:22', '2025-05-04 05:07:22'),
(30, 3, -1, 'sale', '2025-05-04 05:07:22', '2025-05-04 05:07:22'),
(31, 4, -8, 'adjustment', '2025-05-04 16:18:44', '2025-05-04 16:18:44'),
(32, 3, -3, 'adjustment', '2025-05-04 16:18:44', '2025-05-04 16:18:44'),
(33, 4, -2, 'adjustment', '2025-05-04 16:48:30', '2025-05-04 16:48:30'),
(34, 3, -2, 'adjustment', '2025-05-04 16:48:30', '2025-05-04 16:48:30'),
(35, 2, -2, 'adjustment', '2025-05-04 16:48:30', '2025-05-04 16:48:30'),
(36, 1, 2, 'adjustment', '2025-05-04 17:32:45', '2025-05-04 17:32:45'),
(37, 3, -8, 'sale', '2025-05-04 18:48:11', '2025-05-04 18:48:11'),
(38, 2, -3, 'sale', '2025-05-04 23:26:50', '2025-05-04 23:26:50'),
(39, 2, -7, 'sale', '2025-05-04 23:27:16', '2025-05-04 23:27:16');

-- --------------------------------------------------------

--
-- Table structure for table `stock_outputs`
--

CREATE TABLE `stock_outputs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `date` date NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `access` varchar(255) NOT NULL DEFAULT 'allowed',
  `state` varchar(255) NOT NULL DEFAULT 'allowed',
  `create_At` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `role`, `password`, `access`, `state`, `create_At`) VALUES
(1, 'admin', 'admin', '$2y$10$KSzL.HWef8GwDWGJDS5eeO/fxfAWIp2BkLxzae8J902kkEMG1WxKC', 'allowed', 'allowed', '2025-05-03 14:25:54'),
(2, 'Johanna', 'vendor', '$2y$10$VuW7/VqHYXy6/MBDC0afde3JRWz.mM.se.6RFJT1m/urUpfXlodBy', 'allowed', 'allowed', '2025-05-03 14:25:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_expense_type_id_foreign` (`expense_type_id`),
  ADD KEY `expenses_user_id_foreign` (`user_id`);

--
-- Indexes for table `expense_types`
--
ALTER TABLE `expense_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventories_user_id_foreign` (`user_id`);

--
-- Indexes for table `inventory_lines`
--
ALTER TABLE `inventory_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_lines_inventory_id_foreign` (`inventory_id`),
  ADD KEY `inventory_lines_product_id_foreign` (`product_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_user_id_foreign` (`user_id`);

--
-- Indexes for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_items_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_user_id_foreign` (`user_id`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_items_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_product_id_foreign` (`product_id`);

--
-- Indexes for table `stock_outputs`
--
ALTER TABLE `stock_outputs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_outputs_product_id_foreign` (`product_id`),
  ADD KEY `stock_outputs_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `expense_types`
--
ALTER TABLE `expense_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inventory_lines`
--
ALTER TABLE `inventory_lines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `purchase_items`
--
ALTER TABLE `purchase_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `stock_outputs`
--
ALTER TABLE `stock_outputs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_expense_type_id_foreign` FOREIGN KEY (`expense_type_id`) REFERENCES `expense_types` (`id`),
  ADD CONSTRAINT `expenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `inventories`
--
ALTER TABLE `inventories`
  ADD CONSTRAINT `inventories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `inventory_lines`
--
ALTER TABLE `inventory_lines`
  ADD CONSTRAINT `inventory_lines_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`),
  ADD CONSTRAINT `inventory_lines_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD CONSTRAINT `purchase_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_items_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`);

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_outputs`
--
ALTER TABLE `stock_outputs`
  ADD CONSTRAINT `stock_outputs_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_outputs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
