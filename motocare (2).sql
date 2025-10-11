-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2025 at 03:06 PM
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
  `service_id` int(11) DEFAULT NULL,
  `notes` varchar(500) NOT NULL,
  `vehicle_model` varchar(20) NOT NULL,
  `vehicle_plate_number` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `user_id`, `shop`, `preferred_time`, `time`, `status`, `repair_status`, `total_cost`, `vehicle_name`, `createdAt`, `service_id`, `notes`, `vehicle_model`, `vehicle_plate_number`) VALUES
(20, 14, '1', '2025-10-29', 14, 'cancelled', '', NULL, 'honda click', '2025-10-02 16:57:58', 4, 'ikaw na bahala', 'click 123', '14667'),
(21, 14, '1', '2025-10-04', 9, 'cancelled', '', NULL, 'susano', '2025-10-02 17:43:28', 2, 'wala naman', 'susano 45', 'AJO-146'),
(22, 14, '1', '2025-10-01', 10, 'cancelled', '', NULL, 'sa', '2025-10-02 17:47:51', 3, 'assa', 'kl', 'kl'),
(23, 14, '1', '2025-10-22', 15, 'cancelled', '', NULL, 'power GT', '2025-10-02 17:58:17', 1, 'SA', 'GT40', 'GT-5423'),
(24, 26, '1', '2025-10-15', 16, 'not accepted', '', NULL, 'honda click', '2025-10-03 19:49:43', 2, 'haha', 'GT40', '12345'),
(25, 38, '2', '2025-10-07', 9, 'not accepted', '', NULL, 'Honda', '2025-10-06 15:38:27', 0, '', 'Xrm125', 'Abcd1234'),
(26, 37, '1', '2025-10-17', 11, 'cancelled', '', NULL, 'sa', '2025-10-06 15:42:06', 2, 'sas', 'sa', 'sa'),
(27, 38, '2', '2025-10-07', 9, 'not accepted', '', NULL, 'Honda', '2025-10-06 15:43:37', 0, '', 'Xrm125', 'Abcd1234'),
(28, 37, '2', '', 0, 'not accepted', '', NULL, 'sa', '2025-10-06 15:43:50', 0, 'sa', 'sa', 'sa'),
(29, 38, '1', '2025-10-07', 9, 'not accepted', '', NULL, 'Honda', '2025-10-06 15:45:16', 0, '', 'Xrm125', 'Abcd1234'),
(30, 38, '1', '2025-10-08', 10, 'completed', '', NULL, 'Honda', '2025-10-06 15:50:33', 2, '', 'Xrm125', 'Abcd1234'),
(31, 14, '2', '2025-10-17', 15, 'not accepted', '', NULL, 'uwu', '2025-10-06 16:35:01', 8, '', 'uwu-35', '200'),
(32, 16, '2', '2025-10-07', 15, 'not accepted', '', NULL, 'tmx', '2025-10-07 05:46:30', 0, '', '', '');

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
  `shop_id` int(11) DEFAULT NULL,
  `description` varchar(500) NOT NULL,
  `icon` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service_name`, `min_cost`, `max_cost`, `createdAt`, `shop_id`, `description`, `icon`) VALUES
(1, 'Transmission', 10, 100, '2025-09-14', 1, 'hi', 'fas fa-battery-full'),
(2, 'Engine Repair', 10, 100, '2025-09-14', 1, 'wuysa', 'fas fa-battery-full'),
(3, 'Diagnostics', 5, 100, '2025-09-14', 1, '', 'fas fa-cogs'),
(4, 'Performance Tuning', 5, 100, '2025-09-14', 1, 'eto ya pangpa lakas hehe', 'fas fa-tools'),
(6, 'hilot', 5, 100, '2025-10-04', NULL, 'Hilot sa para ayus', 'fas fa-wrench'),
(8, 'Hilot sa agttang', 5, 100, '2025-10-07', 2, 'para sa waran agtang', 'fas fa-wrench');

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
  `name` varchar(20) DEFAULT NULL,
  `owner_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shop`
--

INSERT INTO `shop` (`id`, `icon`, `address`, `lg`, `lat`, `service_id`, `rating`, `hours`, `name`, `owner_id`) VALUES
(1, 'fas fa-wrench', 'San Roque St, Virac, Catanduanes', '124.237', '13.5875', 0, 4, '5', 'Molje Lube', 20),
(2, 'fas fa-cogs', 'Concepcion, Virac, Catanduanes', '124.2395', '13.5852', 0, 6, '3', 'Precision Tech Moto', 39),
(3, 'fas fa-motorcycle', 'Rawis, Virac, Catanduanes', '124.235', '13.589', 0, 7, '3', 'CT Gear', 0);

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
  `email_id` varchar(100) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `picture` varchar(100) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'customer',
  `username` varchar(50) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `shop_id` int(11) NOT NULL,
  `createdAt` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`ID`, `email`, `contact`, `address`, `password`, `email_id`, `fullname`, `picture`, `role`, `username`, `status`, `shop_id`, `createdAt`) VALUES
(14, 'fernandezjasper463@gmail.com', '0912-345-6789', 'sa piuso mo', 'TuIAOWMRpLls', '113393429651685815825', 'jasper fernandez', 'https://lh3.googleusercontent.com/a/ACg8ocKs_tj_aiJJPyMyFJkU6NOixEMQlOIm9F51_pEsT9ydRUFLsA=s96-c', 'customer', NULL, 'active', 0, '2025-10-03'),
(15, 'malrhinzo@gmail.com', '0912-345-6789', 'pogi', 'okY3iPY7HYPN', '114872420976022575907', 'uwuInz -', 'https://lh3.googleusercontent.com/a/ACg8ocIfneXs3wWxrQx4LRZtZUZQVgNwGra3ObcdvjuJIUHTaDdHnuIo=s96-c', 'customer', NULL, 'active', 0, '2025-10-03'),
(16, 'razelkatenazareno@gmail.com', '0912-345-6789', '', '8AvChKqfi2e4', '101644453373737763629', 'Razel Kate Nazareno', 'https://lh3.googleusercontent.com/a/ACg8ocIoHtKhRZ4uc8i-TPDaW48aa9UxV7N4eE5udWMs2p4NY28vERe6=s96-c', 'customer', NULL, 'active', 0, '2025-10-03'),
(18, 'staff@gmail.com', '09192', 'sa puso mo', 'staff', '12345', 'Jasper Angeles Fernandez', NULL, 'staff', 'staff', 'active', 1, '2025-10-03'),
(20, 'owner@gmail.com', '09197960151', 'sa puso mo', 'owner', '4545', 'Andrea Briol', 'http://wala.com/andrea.png', 'owner', 'owner', 'active', 0, '2025-10-03'),
(24, 'erika456@gmail.com', '09197', 'bagumbayan haha', 'japs4545', '', 'Erika Briones', NULL, 'staff', NULL, 'active', 1, '2025-10-03'),
(25, 'jaun@gmail.com', '09197960151', 'san fernando masbate\npating masbate city', 'jessel45', '', 'Juan Dela Cruz', NULL, 'staff', NULL, 'active', 1, '2025-10-03'),
(26, 'repairhubmotocare@gmail.com', '0919sa', 'sas', 'jas', '113442942623600500655', 'MotoCare RepairHub', 'https://lh3.googleusercontent.com/a/ACg8ocLxQvaN_dWrKKu_ge2qA3SWPXFYby2A9-B4BIBTFkebWbt3nw=s96-c', 'customer', NULL, 'active', 0, '2025-10-04'),
(31, 'pelorinajane130@gmail.com', '0912-345-6789', 'sjdnd', 'japs', '106085042395953237300', 'Jane Pelorina', 'https://lh3.googleusercontent.com/a/ACg8ocIidfEeYP8w2ozvDXxl67-wNFpqpqHmmjiljBp4suEQ8PhBzA=s96-c', 'customer', NULL, 'active', 0, '2025-10-06'),
(32, 'bfpprofiler@gmail.com', 'sas', 'sa', 'sa', '112221411111045681991', 'bfp profiler', 'https://lh3.googleusercontent.com/a/ACg8ocKxyfeN7iGWUcS0x519k-eY3_a1SP5YKSJCz6eGHEdsATT94g=s96-c', 'customer', NULL, 'active', 0, '2025-10-06'),
(37, 'fernandezmayma@gmail.com', '98', 'j', 'k', '111739984537885755255', 'mayma fernandez', 'https://lh3.googleusercontent.com/a/ACg8ocKXobs5yuCIzxlSoVikycrx6rFUSlbI2VUbw-v85CoYMdNugQ=s96-c', 'customer', NULL, 'active', 0, '2025-10-06'),
(38, 'josephsamosa07@gmail.com', '09123456789', 'Cavinitan', 'Ambisyosa', '109625179195717105310', 'Joseph Samosa', 'https://lh3.googleusercontent.com/a/ACg8ocL7gx3pQHKshLfKVUXJva4LMNhyFc042USbVORksmEo-bCPhGU=s96-c', 'customer', NULL, 'active', 0, '2025-10-06'),
(39, 'pretech@gmail.com', '09106266497', 'sa puso mo', 'pretech', '987', 'Precision Tech Moto', NULL, 'owner', NULL, 'active', 2, '2025-10-06');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `shop`
--
ALTER TABLE `shop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
