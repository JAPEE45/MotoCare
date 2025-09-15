-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 15, 2025 at 04:10 AM
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
-- Database: `motocare`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `shop` varchar(100) NOT NULL,
  `preferred_time` varchar(50) NOT NULL,
  `time` int(50) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'pending',
  `repair_status` varchar(100) NOT NULL,
  `total_cost` int(11) DEFAULT NULL,
  `vehicle_name` varchar(20) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `service_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `user_id`, `shop`, `preferred_time`, `time`, `status`, `repair_status`, `total_cost`, `vehicle_name`, `createdAt`, `service_id`) VALUES
(11, 14, '1', '2025-09-15', 9, 'progress', 'pending', NULL, 'japee', '2025-09-14 19:34:52', 1);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `min_cost` int(11) NOT NULL,
  `max_cost` int(11) NOT NULL,
  `createdAt` date NOT NULL DEFAULT current_timestamp(),
  `shop_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service_name`, `min_cost`, `max_cost`, `createdAt`, `shop_id`) VALUES
(1, 'Transmission', 10, 100, '2025-09-14', 1),
(2, 'Engine Repair', 5, 100, '2025-09-14', 1),
(3, 'Diagnostics', 5, 100, '2025-09-14', 1),
(4, 'Performance Tuning', 5, 100, '2025-09-14', 1);

-- --------------------------------------------------------

--
-- Table structure for table `shop`
--

CREATE TABLE `shop` (
  `id` int(11) NOT NULL,
  `icon` varchar(20) NOT NULL,
  `address` varchar(100) NOT NULL,
  `lg` varchar(20) NOT NULL,
  `lat` varchar(20) NOT NULL,
  `service_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `hours` varchar(20) NOT NULL,
  `name` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shop`
--

INSERT INTO `shop` (`id`, `icon`, `address`, `lg`, `lat`, `service_id`, `rating`, `hours`, `name`) VALUES
(1, 'fas fa-wrench', 'San Roque St, Virac, Catanduanes', '124.237', '13.5875', 0, 4, '5', 'Molje Lube'),
(2, 'fas fa-cogs', 'Concepcion, Virac, Catanduanes', '124.2395', '13.5852', 0, 6, '3', 'Precision Tech Moto'),
(3, 'fas fa-motorcycle', 'Rawis, Virac, Catanduanes', '124.235', '13.589', 0, 7, '3', 'CT Gear');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `ID` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `address` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email_id` varchar(100) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `picture` varchar(100) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'customer',
  `username` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`ID`, `email`, `contact`, `address`, `password`, `email_id`, `fullname`, `picture`, `role`, `username`) VALUES
(14, 'fernandezjasper463@gmail.com', '0912-345-6789', 'sa piuso mo', 'TuIAOWMRpLls', '113393429651685815825', 'jasper fernandez', 'https://lh3.googleusercontent.com/a/ACg8ocKs_tj_aiJJPyMyFJkU6NOixEMQlOIm9F51_pEsT9ydRUFLsA=s96-c', 'customer', NULL),
(15, 'malrhinzo@gmail.com', '0912-345-6789', 'pogi', 'okY3iPY7HYPN', '114872420976022575907', 'uwuInz -', 'https://lh3.googleusercontent.com/a/ACg8ocIfneXs3wWxrQx4LRZtZUZQVgNwGra3ObcdvjuJIUHTaDdHnuIo=s96-c', 'customer', NULL),
(16, 'razelkatenazareno@gmail.com', '0912-345-6789', '', '8AvChKqfi2e4', '101644453373737763629', 'Razel Kate Nazareno', 'https://lh3.googleusercontent.com/a/ACg8ocIoHtKhRZ4uc8i-TPDaW48aa9UxV7N4eE5udWMs2p4NY28vERe6=s96-c', 'customer', NULL),
(17, 'pelorinajane130@gmail.com', '0912-345-6789', 'Dfif', 'vzG9EdM55iZ1', '106085042395953237300', 'Jane Pelorina', 'https://lh3.googleusercontent.com/a/ACg8ocIidfEeYP8w2ozvDXxl67-wNFpqpqHmmjiljBp4suEQ8PhBzA=s96-c', 'customer', NULL),
(18, '', '', '', 'staff', '', 'Jasper Angeles Fernandez', NULL, 'staff', 'staff');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shop`
--
ALTER TABLE `shop`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `shop`
--
ALTER TABLE `shop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
