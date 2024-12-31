-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2024 at 07:08 AM
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

--
-- Dumping data for table `mp_assets`
--

INSERT INTO `mp_assets` (`asset_id`, `name`, `type`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'Mobile', 'Other', 1, '2024-11-25 06:53:17', 1, '2024-11-25 06:53:17', NULL);

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

INSERT INTO `mp_citizen` (`citizen_id`, `house_id`, `name`, `gender`, `date_of_birth`, `aadhar_number`, `pan_card_number`, `driving_license`, `address`, `mobile_number`, `alternate_phone`, `email_id`, `citizen_password`, `is_head_of_family`, `relation_with_hof`, `annual_income`, `electricity_bill_no`, `water_bill_no`, `profile_photo`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 1, 'Gopal Vaman Naik', 'Male', '1951-03-19', '34553355', 'AWD23444', '3455AADAD', 'Cumbharjua', 708302888, 708302888, 'gopal@infi-pre.com', '$2y$10$wgCj82S/B7fNWPnd1al13.tnY0XqKrdLzOhSnsxzCFB', 1, 1, 100000, NULL, NULL, 'mp/house/4HuwuOP0Idq8xMFpE0t1BTNeOfp4kWWC7tsQOBkC.png', 1, '2024-11-22 07:37:38', 1, '2024-11-27 04:39:49', NULL),
(2, 2, 'Sujata Naik', 'Female', '2000-03-19', '450879298888', 'AWD4563T', 'TYHEDD222', 'Janki Surchem Bhat Cumbharjua Goa', 708302776, 880674568, 'gopal23@infi-pre.com', 'gopal123', 1, 1, 100000, NULL, NULL, 'mp/citizen/aGtMumeCusVUFCU6ydd1QCQK8oCoHL9fJ7zrTLEL.webp', 1, '2024-11-25 02:36:25', 1, '2024-11-27 04:38:24', NULL),
(3, 3, 'Gauresh', 'Male', '1992-03-19', '450879298888', 'AWD4563T', 'TYHEDD222', 'Janki Surchem Bhat Cumbharjua Goa', 708302776, 880674568, 'g2323opal@infi-pre.c', 'gopal123', 0, 1, 100000, NULL, NULL, 'mp/citizen/40kUqysBQlOcco8GPiCYTWzfadVhJJuxYi6XVZog.webp', 1, '2024-11-25 02:36:46', 1, '2024-11-27 04:38:29', NULL),
(4, 2, 'Gopal V. Naik', 'Male', '2010-03-19', '450879298888', 'AWD4563T', 'TYHEDD222', 'Janki Surchem Bhat Cumbharjua Goa', 708302776, 880674568, 'gopal@infi-pre.com23', 'gopal123', 0, 1, 100000, NULL, NULL, 'mp/citizen/0rzcMIoiTdiybetVAucJlTBnZN4Ky6V8Oxv4t9ul.webp', 1, '2024-11-25 06:30:40', 1, '2024-11-27 04:38:36', NULL),
(5, 5, 'Gauresh Bhandare', 'Male', '2001-11-21', '2125255', '52555', '5565445', 'Panaji', 673118138, 673118138, 'udemyuser1@elms.com', 'Admin@123', 1, 1, 120000, NULL, NULL, 'mp/citizen/2nbqPulfUhMQgbfXyq7RTKo1cRNmAAD6uo0tnn2V.webp', 1, '2024-11-25 11:05:07', 1, '2024-11-25 11:05:07', NULL),
(6, 5, 'Stephen Salvator', 'Male', '1994-11-21', '5565464', '456456', '456456', 'Panaji', 6363636, 8996733, 'stephensalvator@infi', 'ttyjhtyh', 0, 2, 12546, NULL, NULL, 'mp/citizen/QAhkJfFzq6xlNqz0NAo9pewv3DQLZz0XuV8SALf9.webp', 1, '2024-11-25 11:05:53', 1, '2024-11-25 11:05:53', NULL),
(7, 5, 'Gauresh Bhandare', 'Male', '2001-11-21', '2125255', '52555', '5565445', 'Panaji', 673118138, 673118138, 'udemyuser1@elms.com', 'Admin@123', 1, 1, 120000, NULL, NULL, 'mp/citizen/cUpR2hGjMK4gu6DpDr8oW723Nc7hH8ZCQlijC87X.webp', 1, '2024-11-25 11:07:01', 1, '2024-11-25 11:07:01', NULL),
(8, 8, 'asdff', 'Male', '2024-11-21', '2424', '234', '2342', '234', 234, 234, '234', '234', 1, 1, 234, 234, 234, '1', 1, '2024-11-26 10:57:12', 1, '2024-11-26 10:57:12', NULL),
(9, 2, 'Gopal V. Naik', 'Male', '1951-03-19', '450879298888', 'AWD4563T', 'TYHEDD222', 'Janki Surchem Bhat Cumbharjua Goa', 708302776, 880674568, 'gopal@infi-pre.com', 'gopal123', 1, 1, 100000, NULL, NULL, '3HVWPoeRIHQ3SolJVVjy2CyfxRfu4bYW1m8YlBJq.png', 1, '2024-11-27 07:23:00', 1, '2024-11-27 07:23:00', NULL);

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
-- Table structure for table `mp_file_upload`
--

CREATE TABLE `mp_file_upload` (
  `file_upload_id` bigint(50) NOT NULL,
  `table_id` bigint(50) NOT NULL,
  `table_name` varchar(100) NOT NULL,
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
  `house_type` enum('owned','rent') DEFAULT NULL,
  `house_status` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
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

--
-- Dumping data for table `mp_relationship_master`
--

INSERT INTO `mp_relationship_master` (`relation_id`, `relation_name`) VALUES
(1, 'Self'),
(2, 'Mother');

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

--
-- Dumping data for table `mp_scheme_master`
--

INSERT INTO `mp_scheme_master` (`scheme_id`, `name`, `description`, `eligibility_criteria`, `benefits`, `start_date`, `end_date`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'Griha Aadhar Scheme', 'Griha Aadhar Scheme is for women.', 'Griha Aadhar Scheme:\r\n\r\n1. Women should be house wife\r\n2. No income from other sources', '1500/- every month', '2024-11-01', '2030-11-30', 1, '2024-11-25 06:51:59', 1, '2024-11-25 06:59:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mp_taxes`
--

CREATE TABLE `mp_taxes` (
  `tax_id` bigint(50) NOT NULL,
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
(183, 'App\\Models\\User', 1, 'MP-BE', '2d2ec71de8bb7d865b3d8fcf2f0718228ef2aca68ce69d1d4c5681a62d50d1d7', '[\"*\"]', '2024-11-27 07:42:15', NULL, '2024-11-22 06:51:33', '2024-11-27 07:42:15'),
(184, 'App\\Models\\User', 1, 'MP-BE', '8c4d1609bb682e28f8ca84108353ffcb1b88f3a18513ae1df625d1c475cce137', '[\"*\"]', '2024-11-25 02:41:09', NULL, '2024-11-25 01:25:46', '2024-11-25 02:41:09'),
(185, 'App\\Models\\User', 1, 'MP-BE', 'cb3bc9e7a65b781a7b849fa1890047576646733dbfe669a21b7d766cb5dd5adc', '[\"*\"]', '2024-11-25 08:26:12', NULL, '2024-11-25 06:58:45', '2024-11-25 08:26:12'),
(186, 'App\\Models\\User', 1, 'MP-BE', '66fa0137c8f501cca22d95fafbf57d61bde16dbc9db4f6b353792adb4714817c', '[\"*\"]', '2024-11-25 11:09:48', NULL, '2024-11-25 10:51:46', '2024-11-25 11:09:48'),
(187, 'App\\Models\\User', 1, 'MP-BE', '0bd3521df8016794d5fa8d0df3580dff00b91a3267def692bc1dfa2bff91f609', '[\"*\"]', '2024-11-26 11:05:43', NULL, '2024-11-26 10:49:29', '2024-11-26 11:05:43'),
(188, 'App\\Models\\Citizen', 1, 'MP-BE-CITIZEN', 'f55853eac9c9926a7cf81579f6e08dfab9e9412d3bc3bc14c53d3823bf9f607d', '[\"*\"]', NULL, NULL, '2024-11-26 23:17:54', '2024-11-26 23:17:54'),
(189, 'App\\Models\\User', 1, 'MP-BE', '063490f0cc3623b4133f6a9b9633b10e7900af057055b2ad6a433d813db0cdec', '[\"*\"]', NULL, NULL, '2024-11-26 23:18:16', '2024-11-26 23:18:16'),
(190, 'App\\Models\\Citizen', 1, 'MP-BE-CITIZEN', '0931b6c2c9b9d441000c131af636e5420807e159dd0c13734af8ff1606f178e0', '[\"*\"]', '2024-11-27 05:23:32', NULL, '2024-11-26 23:43:49', '2024-11-27 05:23:32'),
(192, 'App\\Models\\User', 1, 'MP-BE', '42d400488b470b3a4d01963005e321f621fd4b87c9cbbafb65224e16e1641657', '[\"*\"]', '2024-11-27 06:40:54', NULL, '2024-11-27 03:54:02', '2024-11-27 06:40:54'),
(193, 'App\\Models\\Citizen', 1, 'MP-BE-CITIZEN', '55d7ab075bcb6081aaa1d38901c71b94fac6ad60bde1439953d3b33656b5f53c', '[\"*\"]', NULL, NULL, '2024-11-27 05:50:47', '2024-11-27 05:50:47'),
(194, 'App\\Models\\User', 1, 'MP-BE', '8ff6a7d56dc8ce0aa1d8a3c5e8b6fa79d6d50744d6efe81edc6933600da4ecf2', '[\"*\"]', '2024-11-27 07:22:59', NULL, '2024-11-27 05:50:58', '2024-11-27 07:22:59');

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
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mp_citizen`
--
ALTER TABLE `mp_citizen`
  MODIFY `citizen_id` bigint(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
-- AUTO_INCREMENT for table `mp_qualifications`
--
ALTER TABLE `mp_qualifications`
  MODIFY `qualification_id` bigint(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_relationship_master`
--
ALTER TABLE `mp_relationship_master`
  MODIFY `relation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mp_scheme_master`
--
ALTER TABLE `mp_scheme_master`
  MODIFY `scheme_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mp_taxes`
--
ALTER TABLE `mp_taxes`
  MODIFY `tax_id` bigint(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_users`
--
ALTER TABLE `mp_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
