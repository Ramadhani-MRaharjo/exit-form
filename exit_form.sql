-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 29, 2026 at 04:20 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `exit_form`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_5c785c036466adea360111aa28563bfd556b5fba', 'i:3;', 1790654971),
('laravel_cache_5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1790654971;', 1790654971);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `code`, `is_active`, `created_at`, `updated_at`) VALUES
(14, 'IT Security', NULL, 1, '2026-09-27 06:16:16', '2026-09-27 06:16:16'),
(53, 'IT Application Development', NULL, 1, '2026-09-28 20:01:18', '2026-09-28 20:01:18'),
(99, 'Marketing', NULL, 1, '2026-09-28 21:05:53', '2026-09-28 21:05:53');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `employee_number` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `joined_at` date DEFAULT NULL,
  `left_at` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `user_id`, `department_id`, `employee_number`, `name`, `email`, `position`, `joined_at`, `left_at`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, '1040', 'Shella Cintiya', 'shella@exitform.test', 'Software Engineer', NULL, NULL, '2026-09-23 21:35:25', '2026-09-23 21:35:25'),
(15, 18, 14, '3211', 'Rizkie', 'rizkie@gmail.com', NULL, NULL, NULL, '2026-09-27 06:16:16', '2026-09-27 06:16:16'),
(58, 69, 53, '255', 'Dhani', 'dhani@gmail.com', NULL, NULL, NULL, '2026-09-28 20:01:18', '2026-09-28 20:01:18'),
(70, 93, 53, '3221', 'agus', 'agus@gmail.com', NULL, NULL, NULL, '2026-09-28 20:17:26', '2026-09-28 20:17:26'),
(114, 186, 99, '544', 'Laras', 'laras@gmail.com', NULL, NULL, NULL, '2026-09-28 21:05:53', '2026-09-28 21:05:53');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `handover_forms`
--

CREATE TABLE `handover_forms` (
  `id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `form_number` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `handover_date` date NOT NULL,
  `effective_exit_date` date DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','submitted','approved','rejected','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `urgent_activity` text COLLATE utf8mb4_unicode_ci,
  `urgent_document` text COLLATE utf8mb4_unicode_ci,
  `routine_activity` text COLLATE utf8mb4_unicode_ci,
  `routine_document` text COLLATE utf8mb4_unicode_ci,
  `project_name` text COLLATE utf8mb4_unicode_ci,
  `project_document` text COLLATE utf8mb4_unicode_ci,
  `additional_notes` text COLLATE utf8mb4_unicode_ci,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `handover_forms`
--

INSERT INTO `handover_forms` (`id`, `employee_id`, `created_by`, `form_number`, `handover_date`, `effective_exit_date`, `reason`, `status`, `notes`, `urgent_activity`, `urgent_document`, `routine_activity`, `routine_document`, `project_name`, `project_document`, `additional_notes`, `submitted_at`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'ST-2026-0001', '2026-09-24', NULL, NULL, 'approved', NULL, 'Serah-terima deployment dan monitoring aplikasi', 'Dokumen deployment pada folder Engineering', NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-24 18:49:07', '2026-09-23 21:35:25', '2026-09-24 18:49:07'),
(2, 1, 1, 'ST-2026-0002', '2026-09-24', NULL, NULL, 'draft', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-23 21:35:25', '2026-09-23 21:35:25'),
(9, 15, 1, 'ST-2026-0003', '2026-09-27', NULL, NULL, 'approved', NULL, 'dff', 'ff', 'ff', 'f', 'f', 'f', 'fff', '2026-09-27 06:16:45', '2026-09-27 06:19:54', '2026-09-27 06:16:45', '2026-09-27 06:19:54'),
(10, 15, 1, 'ST-2026-0004', '2026-09-27', NULL, NULL, 'rejected', NULL, 'dd', NULL, 'x', 'x', 'xx', 'x', 'xx', '2026-09-27 07:02:59', NULL, '2026-09-27 07:02:59', '2026-09-27 08:13:07'),
(40, 1, 3, 'ST-2026-0005', '2026-09-29', NULL, NULL, 'submitted', NULL, 'test', 'test', 'test', 'test', 'test', 'test', 'test', '2026-09-28 20:04:32', NULL, '2026-09-28 20:04:32', '2026-09-28 20:04:32'),
(48, 1, 3, 'ST-2026-0007', '2026-09-29', NULL, NULL, 'approved', NULL, 'DDD', 'DD', 'DD', 'DF', 'FSDF', 'FSE', 'SFS', '2026-09-28 20:18:05', '2026-09-28 20:18:35', '2026-09-28 20:18:05', '2026-09-28 20:18:35'),
(69, 58, 1, 'ST-2026-0008', '2026-09-29', NULL, NULL, 'draft', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-28 20:45:38', '2026-09-28 20:45:38'),
(81, 58, 69, 'ST-2026-0009', '2026-09-29', NULL, NULL, 'approved', NULL, 'IT DEV', 'IT DEV', 'IT DEV', 'IT DEV', 'IT DEV', 'IT DEV', 'IT DEV', '2026-09-28 20:59:34', '2026-09-28 20:59:47', '2026-09-28 20:59:34', '2026-09-28 20:59:47'),
(82, 114, 186, 'ST-2026-0010', '2026-09-29', NULL, NULL, 'rejected', NULL, 'eeee', 'errrr', 'ada', 'da', 'ada', 'ada', 'ada', '2026-09-28 21:06:21', NULL, '2026-09-28 21:06:21', '2026-09-28 21:08:40'),
(83, 114, 186, 'ST-2026-0011', '2026-09-29', NULL, NULL, 'approved', NULL, 'cdccd', 'dcc', 'c', 'cc', 'c', 'c', 'c', '2026-09-28 21:06:48', '2026-09-28 21:08:42', '2026-09-28 21:06:48', '2026-09-28 21:08:42');

-- --------------------------------------------------------

--
-- Table structure for table `handover_items`
--

CREATE TABLE `handover_items` (
  `id` bigint UNSIGNED NOT NULL,
  `handover_form_id` bigint UNSIGNED NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `asset_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `condition_before` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condition_after` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_returned` tinyint(1) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `handover_signatures`
--

CREATE TABLE `handover_signatures` (
  `id` bigint UNSIGNED NOT NULL,
  `handover_form_id` bigint UNSIGNED NOT NULL,
  `role` enum('employee','supervisor','hr','it','finance','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_09_24_000000_create_handover_workflow_tables', 1),
(5, '2026_09_24_010000_align_exit_form_requirements', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('08sRkQM3K9Z6k4KMYAh3Yoa39uBzrUWlXrBZbSNm', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUnIzZDE1Y0dSa1Q1bDB4R1NHWGE2bjFvYWxuWGNrU09zMTF6R1hMSSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9zZXJhaC10ZXJpbWEvODMvcHJpbnQiO3M6NToicm91dGUiO3M6MTQ6ImhhbmRvdmVyLnByaW50Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1790654952),
('CWWdW23bWwnw9LdIwYZOFZ3Tb6PhnQMk3yqEtdTc', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.139.1 Chrome/150.0.7871.250 Electron/43.6.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoieXYySFVVRVVKb1NJeWNhMGJFVUFuUkF6cnV4bGh5U1RBbUZCQWQ3ViI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM0OiJodHRwOi8vMTI3LjAuMC4xOjgwMDEvc2VyYWgtdGVyaW1hIjtzOjU6InJvdXRlIjtzOjE0OiJoYW5kb3Zlci5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1790654868),
('irR2Pu3SKvDWir4hEoxwmJ46IjAp8yBKucwsX1Sq', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiNFBsdUFLbEVteFBtSjRnbXpJcGJubmNBclQ1YUd5QzdOZ3A5YWdVMiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790651495),
('QWXKa9RcNo9YVNzvv7IHzLJpwU3IXgtjvLOf8hbj', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.139.1 Chrome/150.0.7871.250 Electron/43.6.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRkJxdE9Md2ZjVnIzek9Rdk04N3pmUkJkZW44cmc5VU1sN3pOZmptdyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODA4MS9zZXJhaC10ZXJpbWEiO3M6NToicm91dGUiO3M6MTQ6ImhhbmRvdmVyLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1790653017);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `nik` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('employee','approver','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'employee',
  `allowed` tinyint(1) NOT NULL DEFAULT '1',
  `approver_id` bigint UNSIGNED DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nik`, `name`, `department`, `email`, `type`, `allowed`, `approver_id`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, '1000', 'Andi Rahman', 'People Ops', 'admin@exitform.test', 'admin', 1, NULL, NULL, '$2y$12$zjub12vvydleidQDVsBpN.N4B7xAMGpxmF3QQEhRTwg6tzWM.Upr.', NULL, '2026-09-23 21:35:24', '2026-09-23 21:35:24'),
(2, '1001', 'Sari Puspita', 'People Ops', 'approver@exitform.test', 'approver', 1, NULL, NULL, '$2y$12$0jF.R3fvepj38470KCNYDOzhjuvRuP.C/mIIb/OU1LzL336ZvBVyK', NULL, '2026-09-23 21:35:25', '2026-09-23 21:35:25'),
(3, '1040', 'Shella Cintiya', 'Engineering', 'shella@exitform.test', 'employee', 1, 2, NULL, '$2y$12$eSp2ITKjdgJfZOlZk.3WBOEvOBgoqcOK6dt1bVrNDtqBAkoK.6YH6', NULL, '2026-09-23 21:35:25', '2026-09-23 21:35:25'),
(4, '222', 'Ramadhani Maulana Raharjo', 'Marketing', 'rmadhani.mr@gmail.com', 'approver', 1, NULL, NULL, '$2y$12$Gn.3qjg1zizRAlKx.rp.rOCssxCMkDkTuZ2Qacp9UQOKqBwBZiVCq', NULL, '2026-09-24 18:49:55', '2026-09-24 18:49:55'),
(18, '3211', 'Rizkie', 'IT Security', 'rizkie@gmail.com', 'employee', 1, 4, NULL, '$2y$12$H8HuV3pz.XR93Sxgnnk5yudcbacgGAa8DCks2htl8U5y/wpldKcc6', NULL, '2026-09-27 06:16:16', '2026-09-27 06:16:16'),
(69, '255', 'Dhani', 'IT Application Development', 'dhani@gmail.com', 'employee', 1, 2, NULL, '$2y$12$NQotM/uG74FQSsG5KtmQkeR8H4HnHlb7UQ5nP1SpbjHDN5k1VMjr2', NULL, '2026-09-28 20:01:18', '2026-09-28 20:11:08'),
(93, '3221', 'agus', 'IT Application Development', 'agus@gmail.com', 'approver', 1, 4, NULL, '$2y$12$OPEpG2QFroi.JUUZ3ibrfuIIrVbKlCmd3s4m2hjY2u58ufv2z/rVO', NULL, '2026-09-28 20:17:26', '2026-09-28 20:19:31'),
(186, '544', 'Laras', 'Marketing', 'laras@gmail.com', 'employee', 1, 93, NULL, '$2y$12$98stvrWQU59evkoaZdngC..3gdZfhSPxEu5NuymlpZwWM4jGxpAtW', NULL, '2026-09-28 21:05:53', '2026-09-28 21:05:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_name_unique` (`name`),
  ADD UNIQUE KEY `departments_code_unique` (`code`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_employee_number_unique` (`employee_number`),
  ADD UNIQUE KEY `employees_user_id_unique` (`user_id`),
  ADD KEY `employees_department_id_foreign` (`department_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `handover_forms`
--
ALTER TABLE `handover_forms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `handover_forms_form_number_unique` (`form_number`),
  ADD KEY `handover_forms_employee_id_foreign` (`employee_id`),
  ADD KEY `handover_forms_created_by_foreign` (`created_by`);

--
-- Indexes for table `handover_items`
--
ALTER TABLE `handover_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `handover_items_handover_form_id_foreign` (`handover_form_id`);

--
-- Indexes for table `handover_signatures`
--
ALTER TABLE `handover_signatures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `handover_signatures_handover_form_id_role_unique` (`handover_form_id`,`role`);

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
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_nik_unique` (`nik`),
  ADD KEY `users_approver_id_foreign` (`approver_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `handover_forms`
--
ALTER TABLE `handover_forms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `handover_items`
--
ALTER TABLE `handover_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `handover_signatures`
--
ALTER TABLE `handover_signatures`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `handover_forms`
--
ALTER TABLE `handover_forms`
  ADD CONSTRAINT `handover_forms_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `handover_forms_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `handover_items`
--
ALTER TABLE `handover_items`
  ADD CONSTRAINT `handover_items_handover_form_id_foreign` FOREIGN KEY (`handover_form_id`) REFERENCES `handover_forms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `handover_signatures`
--
ALTER TABLE `handover_signatures`
  ADD CONSTRAINT `handover_signatures_handover_form_id_foreign` FOREIGN KEY (`handover_form_id`) REFERENCES `handover_forms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_approver_id_foreign` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
