-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2024 at 02:01 PM
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
-- Database: `mauxi_panchayat`
--

-- --------------------------------------------------------

--
-- Table structure for table `mp_assets`
--

CREATE TABLE `mp_assets` (
  `asset_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('Road','Building','Land','Other') NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mp_citizen`
--

CREATE TABLE `mp_citizen` (
  `citizen_id` bigint(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `aadhar_number` char(13) DEFAULT NULL,
  `pan_card_number` char(11) DEFAULT NULL,
  `driving_license` char(20) DEFAULT NULL,
  `address` text NOT NULL,
  `mobile_number` int(11) NOT NULL,
  `alternate_phone` int(12) DEFAULT NULL,
  `email_id` varchar(20) DEFAULT NULL,
  `citizen_password` varchar(50) DEFAULT NULL,
  `is_head_of_family` tinyint(4) NOT NULL,
  `relation_with_hof` int(11) NOT NULL,
  `annual_income` float NOT NULL,
  `electricity_bill_no` bigint(50) DEFAULT NULL,
  `water_bill_no` bigint(50) DEFAULT NULL,
  `passport_no` varchar(100) DEFAULT NULL,
  `profile_photo` varchar(200) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mp_citizen_assets`
--

CREATE TABLE `mp_citizen_assets` (
  `citizen_assets_id` bigint(50) NOT NULL,
  `citizen_id` bigint(50) NOT NULL,
  `assets_id` int(11) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mp_citizen_scheme`
--

CREATE TABLE `mp_citizen_scheme` (
  `citizen_scheme_id` bigint(50) NOT NULL,
  `citizen_id` bigint(50) NOT NULL,
  `scheme_id` int(11) NOT NULL,
  `enrollment_date` date DEFAULT NULL,
  `status` enum('Active','Inactive','Completed') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mp_employment`
--

CREATE TABLE `mp_employment` (
  `employment_id` int(11) NOT NULL,
  `citizen_id` bigint(20) NOT NULL,
  `service_type` enum('Govt','Employed','Self Employed','Retired','House Wife') NOT NULL,
  `employer_name` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mp_file_upload`
--

CREATE TABLE `mp_file_upload` (
  `file_upload_id` bigint(50) NOT NULL,
  `table_name` varchar(100) NOT NULL,
  `table_id` bigint(50) NOT NULL,
  `document_type` varchar(100) DEFAULT NULL,
  `file_name` varchar(200) NOT NULL,
  `file_url` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mp_health_data`
--

CREATE TABLE `mp_health_data` (
  `health_id` int(11) NOT NULL,
  `citizen_id` bigint(20) NOT NULL,
  `blood_group` enum('A+','A-','B+','B-','AB+','AB-','O+','O-','Other') NOT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `health_status` varchar(100) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mp_house`
--

CREATE TABLE `mp_house` (
  `house_id` bigint(50) NOT NULL,
  `house_number` varchar(20) NOT NULL,
  `head_of_family` bigint(50) DEFAULT NULL,
  `address` text NOT NULL,
  `members_count` int(11) DEFAULT NULL,
  `income_level` enum('low','middle','high') DEFAULT NULL,
  `house_type` enum('owned','rent') DEFAULT NULL,
  `house_length` bigint(50) DEFAULT NULL,
  `house_breadth` bigint(50) DEFAULT NULL,
  `house_height` bigint(50) DEFAULT NULL,
  `no_of_floors` tinyint(4) DEFAULT NULL,
  `house_status` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mp_house_citizen`
--

CREATE TABLE `mp_house_citizen` (
  `house_citizen_id` bigint(50) NOT NULL,
  `house_id` bigint(50) NOT NULL,
  `citizen_id` bigint(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mp_qualifications`
--

CREATE TABLE `mp_qualifications` (
  `qualification_id` bigint(50) NOT NULL,
  `citizen_id` bigint(20) NOT NULL,
  `qualification_name` varchar(100) NOT NULL,
  `institution_name` varchar(100) NOT NULL,
  `year_of_passing` year(4) NOT NULL,
  `grade` varchar(20) DEFAULT NULL,
  `degree_type` enum('Graduate','Postgraduate','Diploma','Certificates','Other') DEFAULT 'Other',
  `status` tinyint(4) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mp_relationship_master`
--

CREATE TABLE `mp_relationship_master` (
  `relation_id` int(11) NOT NULL,
  `relation_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mp_scheme_master`
--

CREATE TABLE `mp_scheme_master` (
  `scheme_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `eligibility_criteria` text DEFAULT NULL,
  `benefits` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mp_taxes`
--

CREATE TABLE `mp_taxes` (
  `tax_id` bigint(50) NOT NULL,
  `house_id` bigint(50) DEFAULT NULL,
  `citizen_id` bigint(50) DEFAULT NULL,
  `type` enum('House Tax','Professional Tax','Rent Tax','Income Tax','Other') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `receipt_number` varchar(50) DEFAULT NULL,
  `tax_year` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mp_users`
--

CREATE TABLE `mp_users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','officer','citizen') DEFAULT 'citizen',
  `user_status` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mp_users`
--

INSERT INTO `mp_users` (`user_id`, `name`, `email`, `password`, `role`, `user_status`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'akshaya bhandare', 'akshaya.bhandare@infi-pre.com', '$2y$10$wgCj82S/B7fNWPnd1al13.tnY0XqKrdLzOhSnsxzCFB1rvnpHYwLK', 'admin', 1, '2024-11-19 12:14:41', 1, '2024-12-03 07:40:24', NULL),
(2, 'Rama Shirodkar', 'rama@infi-pre.com', '$2y$10$wgCj82S/B7fNWPnd1al13.tnY0XqKrdLzOhSnsxzCFB1rvnpHYwLK', 'officer', 1, '2024-11-28 12:50:04', 0, '2024-11-28 12:50:22', NULL),
(3, 'Gauresh', 'gauresh@info.com', '$2y$12$INaWcoJCuB3ohrXBHiXeruIFic.xGuclnT.a7mBw6D3MijTI4jeVu', 'officer', 1, '2024-12-03 02:48:41', 1, '2024-12-03 08:54:44', NULL),
(5, 'lol', 'lol@lol.com', '$2y$12$YqfzWymTx40i7w3jeWEKwuCCrkGRC9irNR3xhMMIR7uzOlxs0sLhq', 'officer', 1, '2024-12-03 07:17:26', 1, '2024-12-03 12:47:26', NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(183, 'App\\Models\\User', 1, 'MP-BE', '2d2ec71de8bb7d865b3d8fcf2f0718228ef2aca68ce69d1d4c5681a62d50d1d7', '[\"*\"]', '2024-12-03 06:41:25', NULL, '2024-11-22 06:51:33', '2024-12-03 06:41:25'),
(208, 'App\\Models\\User', 1, 'MP-BE', 'fe53bfbab4b49c77f91deb12963b21082d13b0f0c869a113c1b6c0ae3261c6db', '[\"*\"]', NULL, NULL, '2024-11-29 01:22:57', '2024-11-29 01:22:57'),
(209, 'App\\Models\\User', 1, 'MP-BE', '154a4142a52f216fa6c2accd2c0dbb4b92a758c4d47743bddb1f90ce1490532d', '[\"*\"]', '2024-11-29 12:45:30', NULL, '2024-11-29 01:22:59', '2024-11-29 12:45:30'),
(210, 'App\\Models\\User', 1, 'MP-BE', '1fec2775b4f0fb105af737cdfb7fa10fd7955ccea432310679bebc139950b2d6', '[\"*\"]', '2024-12-03 07:29:36', NULL, '2024-12-03 07:14:33', '2024-12-03 07:29:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mp_assets`
--
ALTER TABLE `mp_assets`
  ADD PRIMARY KEY (`asset_id`);

--
-- Indexes for table `mp_citizen`
--
ALTER TABLE `mp_citizen`
  ADD PRIMARY KEY (`citizen_id`);

--
-- Indexes for table `mp_citizen_assets`
--
ALTER TABLE `mp_citizen_assets`
  ADD PRIMARY KEY (`citizen_assets_id`);

--
-- Indexes for table `mp_citizen_scheme`
--
ALTER TABLE `mp_citizen_scheme`
  ADD PRIMARY KEY (`citizen_scheme_id`);

--
-- Indexes for table `mp_employment`
--
ALTER TABLE `mp_employment`
  ADD PRIMARY KEY (`employment_id`);

--
-- Indexes for table `mp_file_upload`
--
ALTER TABLE `mp_file_upload`
  ADD PRIMARY KEY (`file_upload_id`);

--
-- Indexes for table `mp_health_data`
--
ALTER TABLE `mp_health_data`
  ADD PRIMARY KEY (`health_id`);

--
-- Indexes for table `mp_house`
--
ALTER TABLE `mp_house`
  ADD PRIMARY KEY (`house_id`);

--
-- Indexes for table `mp_house_citizen`
--
ALTER TABLE `mp_house_citizen`
  ADD PRIMARY KEY (`house_citizen_id`);

--
-- Indexes for table `mp_qualifications`
--
ALTER TABLE `mp_qualifications`
  ADD PRIMARY KEY (`qualification_id`);

--
-- Indexes for table `mp_relationship_master`
--
ALTER TABLE `mp_relationship_master`
  ADD PRIMARY KEY (`relation_id`);

--
-- Indexes for table `mp_scheme_master`
--
ALTER TABLE `mp_scheme_master`
  ADD PRIMARY KEY (`scheme_id`);

--
-- Indexes for table `mp_taxes`
--
ALTER TABLE `mp_taxes`
  ADD PRIMARY KEY (`tax_id`);

--
-- Indexes for table `mp_users`
--
ALTER TABLE `mp_users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mp_assets`
--
ALTER TABLE `mp_assets`
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_citizen`
--
ALTER TABLE `mp_citizen`
  MODIFY `citizen_id` bigint(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_citizen_assets`
--
ALTER TABLE `mp_citizen_assets`
  MODIFY `citizen_assets_id` bigint(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_citizen_scheme`
--
ALTER TABLE `mp_citizen_scheme`
  MODIFY `citizen_scheme_id` bigint(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_employment`
--
ALTER TABLE `mp_employment`
  MODIFY `employment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_file_upload`
--
ALTER TABLE `mp_file_upload`
  MODIFY `file_upload_id` bigint(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_health_data`
--
ALTER TABLE `mp_health_data`
  MODIFY `health_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_house`
--
ALTER TABLE `mp_house`
  MODIFY `house_id` bigint(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_house_citizen`
--
ALTER TABLE `mp_house_citizen`
  MODIFY `house_citizen_id` bigint(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_qualifications`
--
ALTER TABLE `mp_qualifications`
  MODIFY `qualification_id` bigint(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_relationship_master`
--
ALTER TABLE `mp_relationship_master`
  MODIFY `relation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_scheme_master`
--
ALTER TABLE `mp_scheme_master`
  MODIFY `scheme_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_taxes`
--
ALTER TABLE `mp_taxes`
  MODIFY `tax_id` bigint(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_users`
--
ALTER TABLE `mp_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
