-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 31, 2025 at 04:56 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `enduro_rentals`
--

-- --------------------------------------------------------

--
-- Table structure for table `rentals`
--

CREATE TABLE `rentals` (
  `rent_id` int(10) UNSIGNED NOT NULL,
  `id` int(10) UNSIGNED NOT NULL,
  `vehicle_id` int(10) UNSIGNED NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rentals`
--

INSERT INTO `rentals` (`rent_id`, `id`, `vehicle_id`, `total_amount`, `start_date`, `end_date`, `status`) VALUES
(2, 3, 7, 1500.00, '2025-10-29', '2025-10-30', 'approved'),
(7, 10, 14, 5700.00, '2025-10-31', '2025-11-02', 'approved'),
(9, 10, 11, 3520.00, '2025-11-02', '2025-11-05', 'approved'),
(11, 10, 5, 1600.00, '2025-11-01', '2025-11-02', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone_number`, `email`, `password`, `user_type`, `created_at`) VALUES
(1, 'Norah', '01161263913', 'norah.fzal@gmail.com', '$2y$10$mOtbaagBHQgPyrV6Kt.lSezZeM06NWEw2Q63yx4RkFq4t21p0rbyy', 'customer', '2025-10-28 11:39:03'),
(2, 'jaja', '0132922125', 'norahjasmine@gmail.com', '$2y$10$ciS3DHs9lOL5H5eYJNCoseaxMTM1Ic0q1DplvVGI2DEVOoWt5VoWu', 'customer', '2025-10-28 12:17:47'),
(3, 'reza', '011234567', 'rezaariellano@gmail.com', '$2y$10$EMU6aXpTVDCviwpLsHbn0OI/mCW9Zg0eDwjYTK7/cwNGfH73sCt2u', 'customer', '2025-10-28 12:29:28'),
(9, 'admine', '01234567', 'admin@example.com', '$2y$10$Hj6TDg9uu.sxTXWvO5gPqep5jVk2DwebTdSlb.iATL9dVr7GfoJh.', 'admin', '2025-10-29 05:34:56'),
(10, 'ena', '0123874095', 'ena26@gmail.com', '$2y$10$p9ld42i9.a0v/p28YX2XkuHsx.EryEwTASSKyI1zat7UZMhXrluTK', 'customer', '2025-10-30 12:29:34');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `vehicle_id` int(11) UNSIGNED NOT NULL,
  `model` varchar(100) NOT NULL,
  `plate_no` varchar(20) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `availability` enum('available','booked') DEFAULT 'available',
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`vehicle_id`, `model`, `plate_no`, `price`, `availability`, `image`) VALUES
(5, 'BMW M5 Competition', 'ABC1234', 800.00, 'booked', 'Screenshot 2025-10-23 195102.jpg'),
(6, 'Mercedes-AMG E63 S', 'DEF5678', 780.00, 'available', 'Screenshot 2025-10-23 200155.jpg'),
(7, 'Audi RS7 Sportback', 'GHI9012', 750.00, 'booked', 'Screenshot 2025-10-23 200829.jpg'),
(8, 'Porsche Panamera Turbo S', 'JKL3456', 950.00, 'available', 'Screenshot 2025-10-23 200859.jpg'),
(9, 'Lamborghini Urus', 'MNO7890', 1200.00, 'available', 'Lambo Urus.jpg'),
(10, 'Range Rover SV', 'PQR1122', 900.00, 'available', 'Range Rover Sv.jpg'),
(11, 'BMW XM', 'STU3344', 880.00, 'booked', 'BMW XM.jpg'),
(12, 'Porsche Cayenne Turbo GT', 'VWX5566', 1100.00, 'available', 'Porsche Cayenne.jpg'),
(13, 'Ferrari 296 GT3', 'GT3F55', 2000.00, 'available', 'AF CORSE.jpg'),
(14, 'Porsche 911 GT3 R', 'GT3P911', 1900.00, 'booked', 'Manthey Ema.jpg'),
(15, 'Mercedes-AMG GT3 EVO', 'GT3MAMG', 1850.00, 'available', 'Mercedes.jpg'),
(16, 'Corvette Z06 GT3.R', 'GT3CORV', 1750.00, 'available', 'corvette.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`rent_id`),
  ADD KEY `id` (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`vehicle_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `rent_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `vehicle_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `rentals_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rentals_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
