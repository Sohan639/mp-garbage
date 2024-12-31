-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2024 at 05:01 AM
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
  `citizen_id` bigint(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('Road','Building','Land','Other') NOT NULL,
  `location` text DEFAULT NULL,
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
  `house_id` bigint(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `aadhar_number` char(20) DEFAULT NULL,
  `pan_card_number` char(20) DEFAULT NULL,
  `driving_license` char(20) DEFAULT NULL,
  `address` text NOT NULL,
  `mobile_number` int(12) NOT NULL,
  `alternate_phone` int(12) DEFAULT NULL,
  `email_id` varchar(50) DEFAULT NULL,
  `citizen_password` varchar(200) DEFAULT NULL,
  `is_head_of_family` tinyint(4) NOT NULL,
  `relation_with_hof` int(11) NOT NULL,
  `annual_income` float NOT NULL,
  `profile_photo` varchar(200) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mp_citizen`
--

INSERT INTO `mp_citizen` (`citizen_id`, `house_id`, `name`, `gender`, `date_of_birth`, `aadhar_number`, `pan_card_number`, `driving_license`, `address`, `mobile_number`, `alternate_phone`, `email_id`, `citizen_password`, `is_head_of_family`, `relation_with_hof`, `annual_income`, `profile_photo`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 1, 'Gopal Vaman Naik', 'Male', '1951-03-19', '34553355', 'AWD23444', '3455AADAD', 'Cumbharjua', 708302888, 708302888, 'gopal@infi-pre.com', 'gopal123', 1, 1, 100000, 'mp/house/4HuwuOP0Idq8xMFpE0t1BTNeOfp4kWWC7tsQOBkC.png', 1, '2024-11-22 07:37:38', 1, '2024-11-22 07:37:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mp_citizen_assets`
--

CREATE TABLE `mp_citizen_assets` (
  `citizen_assets_id` int(11) NOT NULL,
  `citizen_id` int(11) NOT NULL,
  `assets_id` int(11) NOT NULL,
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
  `citizen_scheme_id` int(11) NOT NULL,
  `citizen_id` bigint(20) NOT NULL,
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
  `retirement_date` date DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
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
  `health_status` set('Diabetes','Blood Pressure','Cholesterol','Obesity','Thyroid','Other') DEFAULT 'Other',
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
  `house_image` varchar(100) DEFAULT NULL,
  `house_status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mp_house`
--

INSERT INTO `mp_house` (`house_id`, `house_number`, `head_of_family`, `address`, `members_count`, `income_level`, `house_image`, `house_status`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, '321', NULL, 'Janki Surchem Bhat Cumbharjua', NULL, 'low', 'mp/house/zW69DJLYMhSVhWnOR9F6Hv3olAkyZ2ozOhWQ1bjS.jpg', 1, '2024-11-22 07:05:18', 1, '2024-11-22 07:05:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mp_qualifications`
--

CREATE TABLE `mp_qualifications` (
  `qualification_id` int(11) NOT NULL,
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
  `tax_id` int(11) NOT NULL,
  `citizen_id` bigint(20) NOT NULL,
  `type` enum('Property Tax','Income Tax','Other') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `receipt_number` varchar(50) DEFAULT NULL,
  `tax_year` year(4) NOT NULL,
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
(1, 'akshaya bhandare', 'akshaya.bhandare@infi-pre.com', '$2y$10$wgCj82S/B7fNWPnd1al13.tnY0XqKrdLzOhSnsxzCFB1rvnpHYwLK', 'admin', 1, '2024-11-19 12:14:41', 1, '2024-11-19 12:36:54', NULL);

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
(1, 'App\\Models\\User', 1, 'MP-BE', '6c9003b07adc5fee06f9cfc637bc6be6b68f096d8ccef1f7bb01e8a0c5675457', '[\"*\"]', '2024-11-21 21:03:47', NULL, '2024-08-12 22:28:56', '2024-08-15 21:03:47'),
(183, 'App\\Models\\User', 1, 'MP-BE', '2d2ec71de8bb7d865b3d8fcf2f0718228ef2aca68ce69d1d4c5681a62d50d1d7', '[\"*\"]', '2024-11-22 07:37:38', NULL, '2024-11-22 06:51:33', '2024-11-22 07:37:38');

-- --------------------------------------------------------

--
-- Table structure for table `relationship_master`
--

CREATE TABLE `relationship_master` (
  `relation_id` int(11) NOT NULL,
  `relation_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indexes for table `mp_qualifications`
--
ALTER TABLE `mp_qualifications`
  ADD PRIMARY KEY (`qualification_id`);

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
  MODIFY `citizen_id` bigint(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mp_citizen_assets`
--
ALTER TABLE `mp_citizen_assets`
  MODIFY `citizen_assets_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_citizen_scheme`
--
ALTER TABLE `mp_citizen_scheme`
  MODIFY `citizen_scheme_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_employment`
--
ALTER TABLE `mp_employment`
  MODIFY `employment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_health_data`
--
ALTER TABLE `mp_health_data`
  MODIFY `health_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_house`
--
ALTER TABLE `mp_house`
  MODIFY `house_id` bigint(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mp_qualifications`
--
ALTER TABLE `mp_qualifications`
  MODIFY `qualification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_scheme_master`
--
ALTER TABLE `mp_scheme_master`
  MODIFY `scheme_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_taxes`
--
ALTER TABLE `mp_taxes`
  MODIFY `tax_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
