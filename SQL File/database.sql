-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 10, 2022 at 01:24 PM
-- Server version: 10.3.36-MariaDB-log-cll-lve
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bankpro extended`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `firstname` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `image` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `admin_password` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `firstname`, `lastname`, `image`, `admin_email`, `admin_password`) VALUES
(1, 'Admin', 'Admin', 'jamesavatar.png', 'support@dirtyscripts.shop', '$2y$10$Ysp7iiUXB4O.p.vm/H.V5eya846d4sHiBlpkc23JPMCbwEnFVddIC');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `device` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ipAddress` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `datenow` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `card`
--

CREATE TABLE `card` (
  `id` int(11) NOT NULL,
  `seria_key` text COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `card_number` text COLLATE utf8_unicode_ci NOT NULL,
  `card_name` text COLLATE utf8_unicode_ci NOT NULL,
  `card_expiration` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `card_security` text COLLATE utf8_unicode_ci NOT NULL,
  `card_limit` double NOT NULL DEFAULT 5000,
  `card_limit_remain` double NOT NULL DEFAULT 5000,
  `card_status` int(11) NOT NULL DEFAULT 2 COMMENT '1=Active,2=Process,3=hold, 4=PAUSE',
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `card_request`
--

CREATE TABLE `card_request` (
  `id` int(11) NOT NULL,
  `reference_id` text COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `card_type` text COLLATE utf8_unicode_ci NOT NULL,
  `card_reason` text COLLATE utf8_unicode_ci NOT NULL,
  `card_request_status` int(11) NOT NULL DEFAULT 2,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crypto_currency`
--

CREATE TABLE `crypto_currency` (
  `id` int(11) NOT NULL,
  `crypto_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `wallet_address` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `crypto_currency`
--

INSERT INTO `crypto_currency` (`id`, `crypto_name`, `wallet_address`, `created_at`) VALUES
(1, 'Bitcoin', '8rtknjbhgfgvsnkjhgxfgxjhkx', '2022-10-21 17:33:59');

-- --------------------------------------------------------

--
-- Table structure for table `deposit`
--

CREATE TABLE `deposit` (
  `d_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `refrence_id` text COLLATE utf8_unicode_ci NOT NULL,
  `image` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` double NOT NULL,
  `wallet_address` text COLLATE utf8_unicode_ci NOT NULL,
  `crypto_id` int(11) NOT NULL,
  `crypto_status` int(15) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `domestic_transfer`
--

CREATE TABLE `domestic_transfer` (
  `dom_id` int(11) NOT NULL,
  `acct_id` int(11) DEFAULT NULL,
  `refrence_id` text COLLATE utf8_unicode_ci NOT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `bank_name` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_name` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_number` int(15) NOT NULL,
  `trans_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'domestic transfer',
  `acct_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `acct_remarks` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `dom_status` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan`
--

CREATE TABLE `loan` (
  `loan_id` int(11) NOT NULL,
  `loan_reference_id` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_id` int(11) NOT NULL,
  `amount` double DEFAULT 0,
  `loan_remarks` text COLLATE utf8_unicode_ci NOT NULL,
  `loan_status` int(11) NOT NULL DEFAULT 0,
  `loan_message` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `FullName` text NOT NULL,
  `PhoneNumber` text NOT NULL,
  `locationcus` text NOT NULL,
  `Addresscus` text NOT NULL,
  `City` text NOT NULL,
  `Customer` text NOT NULL,
  `Messagecus` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `image` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `about_us` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `url_name` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `url_tel` varchar(15) DEFAULT NULL,
  `url_email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `trans_limit_min` double DEFAULT NULL,
  `trans_limit_max` double DEFAULT NULL,
  `livechat` text NOT NULL COMMENT 'Live Widget',
  `twillio_status` int(11) NOT NULL DEFAULT 0 COMMENT '0=off,1=on',
  `billing_code` int(11) NOT NULL DEFAULT 0 COMMENT '0=off,1=on',
  `transfer` int(11) NOT NULL DEFAULT 1 COMMENT '0=off,1=on',
  `bank_deposit` int(11) NOT NULL DEFAULT 0 COMMENT '0=off,1=on'
  
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `image`, `about_us`, `url_name`, `url_tel`, `url_email`, `trans_limit_min`, `trans_limit_max`, `livechat`, `twillio_status`, `billing_code`, `transfer`, `bank_deposit`) VALUES
(1, 'logo.png', 'Online Banking Script', 'Dirty Scripts', '2348114313795', 'support@dirtyscripts.shop', 500, 500000, '', 0, 1, 1,0);

-- --------------------------------------------------------

--
-- Table structure for table `temp_trans`
--

CREATE TABLE `temp_trans` (
  `wire_id` int(11) NOT NULL,
  `acct_id` int(11) DEFAULT NULL,
  `trans_id` text COLLATE utf8_unicode_ci NOT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `bank_name` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_name_id` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_number` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trans_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'wire transfer',
  `acct_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_country` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_swift` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_routing` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_remarks` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `wire_status` int(11) NOT NULL DEFAULT 0,
  `trans_otp` int(11) DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------
 
--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `trans_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `refrence_id` text COLLATE utf8_unicode_ci NOT NULL,
  `amount` double NOT NULL,
  `trans_type` int(15) NOT NULL,
  `sender_name` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `trans_status` int(11) NOT NULL DEFAULT 0,
  `created_at` text COLLATE utf8_unicode_ci NOT NULL,
  `time_created` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `acct_username` varchar(200) DEFAULT NULL,
  `firstname` varchar(200) DEFAULT NULL,
  `lastname` varchar(200) DEFAULT NULL,
  `image` text DEFAULT NULL,
  `acct_no` varchar(50) DEFAULT NULL,
  `billing_code` int(11) NOT NULL DEFAULT 0 COMMENT '0=deactive,1=active',
  `transfer` int(11) NOT NULL DEFAULT 1 COMMENT '0=deactive,1=active	',
  `acct_balance` double DEFAULT 0,
  `avail_balance` double DEFAULT 0,
  `loan_balance` double DEFAULT 0,
  `acct_limit` double DEFAULT NULL,
  `limit_remain` double DEFAULT NULL,
  `acct_type` varchar(200) DEFAULT NULL,
  `acct_gender` text DEFAULT NULL,
  `marital_status` text DEFAULT NULL,
  `acct_currency` varchar(50) DEFAULT NULL,
  `acct_status` varchar(50) DEFAULT 'active' COMMENT 'active, hold',
  `acct_email` varchar(200) DEFAULT NULL,
  `acct_phone` varchar(20) DEFAULT NULL,
  `acct_occupation` text DEFAULT NULL,
  `acct_dob` text DEFAULT NULL,
  `ssn` varchar(200) DEFAULT NULL,
  `frontID` text DEFAULT NULL,
  `backID` text DEFAULT NULL,
  `country` text DEFAULT NULL,
  `state` text DEFAULT NULL,
  `acct_password` text DEFAULT NULL,
  `acct_pin` varchar(4) DEFAULT NULL,
  `acct_otp` int(11) DEFAULT NULL,
  `acct_cot` varchar(15) DEFAULT NULL,
  `acct_imf` varchar(15) DEFAULT NULL,
  `acct_tax` varchar(15) DEFAULT NULL,
  `mgr_name` text DEFAULT NULL,
  `mgr_no` text DEFAULT NULL,
  `mgr_email` text DEFAULT NULL,
  `mgr_id` text DEFAULT NULL,
  `mgr_image` text DEFAULT NULL,
  `acct_address` text DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `acct_username`, `firstname`, `lastname`, `image`, `acct_no`, `billing_code`, `transfer`, `acct_balance`, `avail_balance`, `loan_balance`, `acct_limit`, `limit_remain`, `acct_type`, `acct_gender`, `marital_status`, `acct_currency`, `acct_status`, `acct_email`, `acct_phone`, `acct_occupation`, `acct_dob`, `ssn`, `frontID`, `backID`, `country`, `state`, `acct_password`, `acct_pin`, `acct_otp`, `acct_cot`, `acct_imf`, `acct_tax`, `mgr_name`, `mgr_no`, `mgr_email`, `mgr_id`, `mgr_image`, `acct_address`, `createdAt`) VALUES
(9, 'ofofonobs', 'Oluwaseun', 'Ikuesan', 'Testprofile-10.jpeg', '0022521726', 1, 1, 795064.44, 0, 866, 1100, -222506, 'Savings', 'male', 'single', 'USD', 'active', 'ofofonobs@gmail.com', '+2349035669201', 'Test Test', '2022-03-08', NULL, NULL, NULL, 'Albania', 'Test', '$2y$10$UaoEfbSOLJBmb.tIgmJaGuyJT0oSoNyI6Ehgq08ZJ7AKqeloxWwh.', '1234', 197600, '1234', '1234', '1234', 'Test Manager', '1234567890', 'manager1@gmail.com', '000000', 'manager1', 'Test Test', '2022-03-27 18:29:40');

-- --------------------------------------------------------

--
-- Table structure for table `v_bank`
--

CREATE TABLE `v_bank` (
  `id` int(11) NOT NULL,
  `bank_name` text COLLATE utf8_unicode_ci NOT NULL,
  `routine_no` text COLLATE utf8_unicode_ci NOT NULL,
  `acct_no` text COLLATE utf8_unicode_ci NOT NULL,
  `swift_code` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wire_transfer`
--

CREATE TABLE `wire_transfer` (
  `wire_id` int(11) NOT NULL,
  `acct_id` int(11) NOT NULL,
  `refrence_id` text COLLATE utf8_unicode_ci NOT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `bank_name` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_name` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_number` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `trans_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'wire transfer',
  `acct_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `acct_country` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_swift` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_routing` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `acct_remarks` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `wire_status` int(11) NOT NULL DEFAULT 0,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------


--
-- Table structure for table `withdrawal`
--

CREATE TABLE `withdrawal` (
  `id` int(11) NOT NULL,
  `reference_id` varchar(200) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `withdraw_method` varchar(200) NOT NULL,
  `trans_type` int(11) NOT NULL,
  `wallet_address` text NOT NULL,
  `bankname` text NOT NULL,
  `account_number` text NOT NULL,
  `routineno` text NOT NULL,
  `acctname` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `card`
--
ALTER TABLE `card`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `card_request`
--
ALTER TABLE `card_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crypto_currency`
--
ALTER TABLE `crypto_currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposit`
--
ALTER TABLE `deposit`
  ADD PRIMARY KEY (`d_id`);

--
-- Indexes for table `domestic_transfer`
--
ALTER TABLE `domestic_transfer`
  ADD PRIMARY KEY (`dom_id`);

--
-- Indexes for table `loan`
--
ALTER TABLE `loan`
  ADD PRIMARY KEY (`loan_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `temp_trans`
--
ALTER TABLE `temp_trans`
  ADD PRIMARY KEY (`wire_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`trans_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `v_bank`
--
ALTER TABLE `v_bank`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wire_transfer`
--
ALTER TABLE `wire_transfer`
  ADD PRIMARY KEY (`wire_id`);

--
-- Indexes for table `withdrawal`
--
ALTER TABLE `withdrawal`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `card`
--
ALTER TABLE `card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `card_request`
--
ALTER TABLE `card_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `crypto_currency`
--
ALTER TABLE `crypto_currency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `deposit`
--
ALTER TABLE `deposit`
  MODIFY `d_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `domestic_transfer`
--
ALTER TABLE `domestic_transfer`
  MODIFY `dom_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan`
--
ALTER TABLE `loan`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `temp_trans`
--
ALTER TABLE `temp_trans`
  MODIFY `wire_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `trans_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `v_bank`
--
ALTER TABLE `v_bank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `wire_transfer`
--
ALTER TABLE `wire_transfer`
  MODIFY `wire_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdrawal`
--
ALTER TABLE `withdrawal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
