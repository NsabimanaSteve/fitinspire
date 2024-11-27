-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2024 at 10:17 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fitinspiredb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(1) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `email`, `password`) VALUES
(1, 'FIHadmin@gmail.com', '$2y$10$Al.pau8IegpzfY8WgfPrGO8dL9S535jpNU6WT8NhDGJFDWF.VAF3C');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `feedback_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `guidance`
--

CREATE TABLE `guidance` (
  `guidance_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nutrition_advice` text NOT NULL,
  `workouts` text NOT NULL,
  `techniques` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `memberships`
--

CREATE TABLE `memberships` (
  `membership_id` int(11) NOT NULL,
  `membershiptype_id` varchar(50) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `paymenttype` varchar(11) DEFAULT NULL,
  `payment` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `membershiptype`
--

CREATE TABLE `membershiptype` (
  `membershiptype_id` int(11) NOT NULL,
  `name` varchar(11) DEFAULT NULL,
  `membershiptype_duration` varchar(100) NOT NULL,
  `membershiptype_price` decimal(10,2) NOT NULL,
  `description` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `membershiptype`
--

INSERT INTO `membershiptype` (`membershiptype_id`, `name`, `membershiptype_duration`, `membershiptype_price`, `description`, `created_at`) VALUES
(1, 'Basic', 'One Month', '0.00', 'Limited membership duration, No personal training, No fitness guidance', '2024-11-26 08:59:07'),
(2, 'Standard', 'Twelve Months', '49.99', 'Everything in Basic, Full access to gym equipment, Training, and Locker-room services', '2024-11-26 09:07:31'),
(3, 'Premium', 'In perpetuity', '99.99', 'Everything in Standard, 24/7 access to gym facility, Parking Privileges, Fitness guidance and advice', '2024-11-26 09:14:07');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `pay_id` int(11) NOT NULL,
  `amt` double NOT NULL,
  `user_id` int(11) NOT NULL,
  `membershiptype_id` int(11) NOT NULL,
  `currency` text NOT NULL,
  `payment_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `trainers`
--

CREATE TABLE `trainers` (
  `trainer_id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `specialization` varchar(50) DEFAULT NULL,
  `fun_fact` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trainers`
--

INSERT INTO `trainers` (`trainer_id`, `fname`, `lname`, `email`, `password`, `specialization`, `fun_fact`, `created_at`, `status`) VALUES
(1, '0', 'Tsatsu', 'dorontsatsuFIHtrainer@gmail.com', '$2y$10$QWA5rYSFxWz/KJuyWPUxw.qBIdc3eTPthU966EjADLK0UF5hEcs6O', NULL, NULL, '2024-11-24 20:40:58', 0),
(2, '0', 'Doe', 'johndoeFIHtrainer@gmail.com', '$2y$10$bu75banfyHXaOZjhq6.7AOWcnsh3YU3qa.CLFFzfNIxaBIGhGKo2q', NULL, NULL, '2024-11-24 20:42:06', 0),
(3, '0', 'mon', 'monFIHtrainer@gmail.com', '$2y$10$txYrNNgmBJIZNRjhpPzfW.8qGQyoZlwN/opMfuX99lNkV5exk0WJe', NULL, NULL, '2024-11-24 22:53:23', 0),
(4, 'Jeff', 'Damer', 'jeffFIHtrainer@gmail.com', '$2y$10$neba9NIUf2k1lFNRhgeAK.JaHu9I3WLmRRLtp3RpFN.2zC.9v/Gs6', NULL, NULL, '2024-11-25 23:33:45', 0),
(5, 'Dante', 'Vergil', 'danteFIHtrainer@gmail.com', '$2y$10$odqcU0JZBnW6OwSllyMdj.18e/2PiaKfQ4Hsb4WafJ8TOMuK9uR7O', NULL, NULL, '2024-11-26 02:47:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `membershiptype_id` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `fname`, `lname`, `email`, `password`, `membershiptype_id`, `created_at`) VALUES
(1, 'Kofi', 'Tsatsu', 'kofi@gmail.com', '$2y$10$Cj6onUNc96LT5lMAs06RmeSITjcaA8fN938YVGgD0Uyn39PqP12km', 1, '2024-11-23 00:16:05'),
(2, 'Roseline', 'Tsatsu', 'roselinetsatsu@gmail.com', '$2y$10$G6SO0u6kkHzY3o5QNpJTV.zMnZJ7.Tl41rXXFYwwGZA6MbBnztU9u', 1, '2024-11-23 01:25:29'),
(3, 'Doron', 'Pela', 'doronFIHtrainer@gmail.com', '$2y$10$QIF.qqsDe.zT13hRodi.iuXbGM1RXQEJi8Vd45RDclj8VNL25mkxi', 1, '2024-11-24 17:39:18'),
(4, 'Joo', 'Lin', 'joo@gmail.com', '$2y$10$bKAZX0uwb5xulv8Qwgp7j.gO4CukTYHkfWBA8.mw2MPyHIVz3d7DC', 1, '2024-11-24 19:00:25'),
(5, '0', 'Tsatsu', 'roselinetsatsu12@gmail.com', '$2y$10$QuvHida2Mf1TCDjomnlR/etrJSnGDJqa6P9mbTeT9SHKCQi9WP2ri', 1, '2024-11-24 20:38:03'),
(6, 'Doron', 'Pela', 'doronpela@gmail.com', '$2y$10$axA/VtxKWYrMr3OAMm4TqOooeONFnDptU7/LhWtiNGyE6HyPTM/AK', 1, '2024-11-25 23:28:15'),
(7, 'Doron', 'Pela', 'doron@gmail.com', '$2y$10$bEKJNbWjxJfDajzMrPqWiOlBOZvaTJfO5XDdtS2Thw8tlSzGJ5ZXm', 1, '2024-11-25 23:29:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`);

--
-- Indexes for table `guidance`
--
ALTER TABLE `guidance`
  ADD PRIMARY KEY (`guidance_id`);

--
-- Indexes for table `memberships`
--
ALTER TABLE `memberships`
  ADD PRIMARY KEY (`membership_id`);

--
-- Indexes for table `membershiptype`
--
ALTER TABLE `membershiptype`
  ADD PRIMARY KEY (`membershiptype_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`pay_id`);

--
-- Indexes for table `trainers`
--
ALTER TABLE `trainers`
  ADD PRIMARY KEY (`trainer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guidance`
--
ALTER TABLE `guidance`
  MODIFY `guidance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `memberships`
--
ALTER TABLE `memberships`
  MODIFY `membership_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `membershiptype`
--
ALTER TABLE `membershiptype`
  MODIFY `membershiptype_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `pay_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trainers`
--
ALTER TABLE `trainers`
  MODIFY `trainer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
