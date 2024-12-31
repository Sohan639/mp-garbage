-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2024 at 07:36 PM
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
  `asset_id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('Road','Building','Land','Other') NOT NULL,
  `location` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mp_citizen`
--

CREATE TABLE `mp_citizen` (
  `citizen_id` bigint(20) NOT NULL,
  `house_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `date_of_birth` date NOT NULL,
  `aadhar_number` char(12) DEFAULT NULL,
  `pan_card_number` char(10) DEFAULT NULL,
  `driving_liscense` char(20) DEFAULT NULL,
  `address` text NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `email_id` varchar(20) DEFAULT NULL,
  `citizen_password` varchar(100) DEFAULT NULL,
  `is_head_of_family` tinyint(4) NOT NULL,
  `relation_with_hof` int(11) DEFAULT NULL,
  `annual_income` float NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `profile_photo` varchar(200) DEFAULT NULL,
  `house_photo` varchar(200) DEFAULT NULL,
  `alternate_photo` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mp_citizen_assets`
--

CREATE TABLE `mp_citizen_assets` (
  `citizen_assets_id` int(11) NOT NULL,
  `citizen_id` int(11) NOT NULL,
  `assets_id` int(11) NOT NULL,
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
  `citizen_id` bigint(20) DEFAULT NULL,
  `scheme_id` bigint(20) DEFAULT NULL,
  `enrollment_date` date NOT NULL,
  `status` enum('Active','Inactive','Completed') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mp_employment`
--

CREATE TABLE `mp_employment` (
  `employment_id` bigint(20) NOT NULL,
  `citizen_id` bigint(20) NOT NULL,
  `service_type` enum('Govt','Employed','Self Employed','Retired','House Wife') NOT NULL,
  `employer_name` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `retirement_date` date DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mp_health_data`
--

CREATE TABLE `mp_health_data` (
  `health_id` bigint(20) NOT NULL,
  `citizen_id` bigint(20) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `blood_group` enum('A+','A-','B+','B-','AB+','AB-','O+','O-','Other') NOT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `health_status` set('Diabetes','Blood Pressure','Cholesterol','Obesity','Thyroid','Other') DEFAULT 'Other',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mp_house`
--

CREATE TABLE `mp_house` (
  `house_id` int(11) NOT NULL,
  `house_number` int(11) NOT NULL,
  `head_of_family` int(11) NOT NULL,
  `address` text NOT NULL,
  `members_count` int(11) DEFAULT NULL,
  `income_level` enum('low','middle','high') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mp_qualifications`
--

CREATE TABLE `mp_qualifications` (
  `qualification_id` bigint(20) NOT NULL,
  `citizen_id` bigint(20) NOT NULL,
  `qualification_name` varchar(100) NOT NULL,
  `institution_name` varchar(100) NOT NULL,
  `year_of_passing` year(4) NOT NULL,
  `grade` varchar(20) DEFAULT NULL,
  `degree_type` enum('Graduate','Postgraduate','Diploma','Certificates','Other') DEFAULT 'Other',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mp_scheme_master`
--

CREATE TABLE `mp_scheme_master` (
  `scheme_id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `eligibility_criteria` text DEFAULT NULL,
  `benefits` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mp_taxes`
--

CREATE TABLE `mp_taxes` (
  `tax_id` bigint(20) NOT NULL,
  `citizen_id` bigint(20) DEFAULT NULL,
  `type` enum('Property Tax','Income Tax','Other') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `receipt_number` varchar(50) DEFAULT NULL,
  `tax_year` year(4) NOT NULL
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
(1, 'akshaya bhandare', 'akshaya.bhandare@infi-pre.com', '$2y$10$wgCj82S/B7fNWPnd1al13.tnY0XqKrdLzOhSnsxzCFB1rvnpHYwLK', 'admin', 1, '2024-11-19 17:44:41', 1, '2024-11-19 18:06:54', NULL);

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
(126, 'App\\Models\\User', 1, 'MP-BE', '6c9003b07adc5fee06f9cfc637bc6be6b68f096d8ccef1f7bb01e8a0c5675457', '[\"*\"]', '2024-08-16 02:33:47', NULL, '2024-08-13 03:58:56', '2024-08-16 02:33:47'),
(172, 'App\\Models\\User', 1, 'MP-BE', '6da351d858291d0d2e016c787ab60c6e054cfdd4bcc3325d37ca5368d973b11e', '[\"*\"]', NULL, NULL, '2024-11-19 12:36:59', '2024-11-19 12:36:59'),
(173, 'App\\Models\\User', 1, 'MP-BE', '91df993c36809dc4c0b73af374cf797a91cb7d16efe7516dc0e48ddf1a42f32f', '[\"*\"]', NULL, NULL, '2024-11-19 12:38:05', '2024-11-19 12:38:05'),
(174, 'App\\Models\\User', 1, 'MP-BE', '5715c1689bb144eb99b5dd22a8cd04a734f349fc3e9f6cc9c6725b2988600e78', '[\"*\"]', NULL, NULL, '2024-11-19 12:39:07', '2024-11-19 12:39:07'),
(175, 'App\\Models\\User', 1, 'MP-BE', '5d6a8f13afb9c74e13901f125efde0bae065baa74e8f5f2dfa3a832b637609b8', '[\"*\"]', NULL, NULL, '2024-11-19 12:39:46', '2024-11-19 12:39:46'),
(176, 'App\\Models\\User', 1, 'MP-BE', 'a19409b3dc14e0c275444dc9de387e8fd539bcdff9a71574df3801825937f145', '[\"*\"]', NULL, NULL, '2024-11-19 12:40:23', '2024-11-19 12:40:23');

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
  ADD PRIMARY KEY (`citizen_id`),
  ADD UNIQUE KEY `aadhar_number` (`aadhar_number`),
  ADD UNIQUE KEY `pan_card_number` (`pan_card_number`),
  ADD UNIQUE KEY `mobile_number` (`mobile_number`),
  ADD KEY `house_id` (`house_id`);

--
-- Indexes for table `mp_citizen_assets`
--
ALTER TABLE `mp_citizen_assets`
  ADD PRIMARY KEY (`citizen_assets_id`);

--
-- Indexes for table `mp_citizen_scheme`
--
ALTER TABLE `mp_citizen_scheme`
  ADD PRIMARY KEY (`citizen_scheme_id`),
  ADD KEY `scheme_id` (`scheme_id`),
  ADD KEY `mp_citizen_scheme_ibfk_1` (`citizen_id`);

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
  ADD PRIMARY KEY (`house_id`),
  ADD UNIQUE KEY `house_number` (`house_number`);

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
  ADD PRIMARY KEY (`tax_id`),
  ADD UNIQUE KEY `receipt_number` (`receipt_number`),
  ADD KEY `citizen_id` (`citizen_id`);

--
-- Indexes for table `mp_users`
--
ALTER TABLE `mp_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `relationship_master`
--
ALTER TABLE `relationship_master`
  ADD PRIMARY KEY (`relation_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mp_assets`
--
ALTER TABLE `mp_assets`
  MODIFY `asset_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_citizen`
--
ALTER TABLE `mp_citizen`
  MODIFY `citizen_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_citizen_assets`
--
ALTER TABLE `mp_citizen_assets`
  MODIFY `citizen_assets_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_employment`
--
ALTER TABLE `mp_employment`
  MODIFY `employment_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_health_data`
--
ALTER TABLE `mp_health_data`
  MODIFY `health_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_house`
--
ALTER TABLE `mp_house`
  MODIFY `house_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_qualifications`
--
ALTER TABLE `mp_qualifications`
  MODIFY `qualification_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_scheme_master`
--
ALTER TABLE `mp_scheme_master`
  MODIFY `scheme_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_taxes`
--
ALTER TABLE `mp_taxes`
  MODIFY `tax_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_users`
--
ALTER TABLE `mp_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

--
-- AUTO_INCREMENT for table `relationship_master`
--
ALTER TABLE `relationship_master`
  MODIFY `relation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `mp_citizen`
--
ALTER TABLE `mp_citizen`
  ADD CONSTRAINT `mp_citizen_ibfk_1` FOREIGN KEY (`house_id`) REFERENCES `mp_house` (`house_id`);

--
-- Constraints for table `mp_citizen_scheme`
--
ALTER TABLE `mp_citizen_scheme`
  ADD CONSTRAINT `mp_citizen_scheme_ibfk_1` FOREIGN KEY (`citizen_id`) REFERENCES `mp_citizen` (`citizen_id`),
  ADD CONSTRAINT `mp_citizen_scheme_ibfk_2` FOREIGN KEY (`scheme_id`) REFERENCES `mp_scheme_master` (`scheme_id`);

--
-- Constraints for table `mp_taxes`
--
ALTER TABLE `mp_taxes`
  ADD CONSTRAINT `mp_taxes_ibfk_1` FOREIGN KEY (`citizen_id`) REFERENCES `mp_citizen` (`citizen_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
