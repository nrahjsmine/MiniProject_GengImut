-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 14, 2025 at 03:18 AM
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
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `payment_proof` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rentals`
--

INSERT INTO `rentals` (`rent_id`, `id`, `vehicle_id`, `total_amount`, `start_date`, `end_date`, `status`, `payment_proof`) VALUES
(24, 11, 5, 800.00, '2025-11-01', '2025-11-02', 'approved', '1762415215_qrpayment.jpg'),
(26, 3, 5, 800.00, '2025-11-15', '2025-11-16', 'approved', '1763086326_receipt demo.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `rental_history`
--

CREATE TABLE `rental_history` (
  `history_id` int(11) NOT NULL,
  `rent_id` int(11) DEFAULT NULL,
  `id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `deleted_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rental_history`
--

INSERT INTO `rental_history` (`history_id`, `rent_id`, `id`, `vehicle_id`, `total_amount`, `start_date`, `end_date`, `status`, `payment_proof`, `deleted_at`) VALUES
(1, 18, 10, 7, 750.00, '2025-11-01', '2025-11-02', 'rejected', NULL, '2025-11-01 11:47:45'),
(2, 20, 10, 9, 1200.00, '2025-11-01', '2025-11-02', 'rejected', NULL, '2025-11-01 13:47:35'),
(3, 22, 10, 7, 750.00, '2025-11-01', '2025-11-02', 'approved', NULL, '2025-11-05 21:05:10');

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
(10, 'ena', '0123874095', 'ena26@gmail.com', '$2y$10$p9ld42i9.a0v/p28YX2XkuHsx.EryEwTASSKyI1zat7UZMhXrluTK', 'customer', '2025-10-30 12:29:34'),
(11, 'siti annis', '0123874095', 'anninis@gmail.com', '$2y$10$w20oh79AhVeDSAbS6DtDP.Jd68ZnN5qLAW8DcyHszmRg6HlkfBuJu', 'customer', '2025-11-01 04:19:23'),
(12, 'Ameer Johann', '0163797919', 'ameer@gmail.com', '$2y$10$3fJWRNehXm/6XG0wP1.w9uRntv/N9Dc7TEGlsAqk4v3LmYwWpu9Zm', 'customer', '2025-11-06 13:38:02'),
(13, 'Blair Waldorf', '01911099011', 'blair@gmail.com', '$2y$10$8kgnk.oShBED.IMhmxv8ney7tt63K8b4Jyvji5FHLtLYXxuTFk/vu', 'customer', '2025-11-06 14:58:51'),
(14, 'Dan Humphrey', '011 7891011', 'dan@gmail.com', 'dan12378', 'customer', '2025-11-06 15:15:05'),
(15, 'amer faheem', '01456789', 'faheemamer@gmail.com', '$2y$10$FnovC6s9aiI8lFDVa3EZ2eZFPBOvsOd1scuky1R9//LI.MBNELu6a', 'customer', '2025-11-13 13:50:31');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `vehicle_id` int(11) UNSIGNED NOT NULL,
  `model` varchar(100) NOT NULL,
  `plate_no` varchar(20) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `availability` enum('available','maintenance') DEFAULT 'available',
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`vehicle_id`, `model`, `plate_no`, `price`, `availability`, `image`) VALUES
(5, 'BMW M5 Competition', 'ABC1234', 800.00, 'available', 'Screenshot 2025-10-23 195102.jpg'),
(6, 'Mercedes-AMG E63 S', 'DEF5678', 780.00, 'available', 'Screenshot 2025-10-23 200155.jpg'),
(7, 'Audi RS7 Sportback', 'GHI9012', 750.00, 'maintenance', 'Screenshot 2025-10-23 200829.jpg'),
(8, 'Porsche Panamera Turbo S', 'JKL3456', 950.00, 'available', 'Screenshot 2025-10-23 200859.jpg'),
(9, 'Lamborghini Urus', 'MNO7890', 1200.00, 'available', 'Lambo Urus.jpg'),
(10, 'Range Rover SV', 'PQR1122', 900.00, 'available', 'Range Rover Sv.jpg'),
(11, 'BMW XM', 'STU3344', 900.00, 'available', 'BMW XM.jpg'),
(12, 'Porsche Cayenne Turbo GT', 'VWX5566', 1100.00, 'available', 'Porsche Cayenne.jpg'),
(13, 'Ferrari 296 GT3', 'GT3F55', 2000.00, 'maintenance', 'AF CORSE.jpg'),
(14, 'Porsche 911 GT3 R', 'GT3P911', 1900.00, 'available', 'Manthey Ema.jpg'),
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
-- Indexes for table `rental_history`
--
ALTER TABLE `rental_history`
  ADD PRIMARY KEY (`history_id`);

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
  MODIFY `rent_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `rental_history`
--
ALTER TABLE `rental_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
