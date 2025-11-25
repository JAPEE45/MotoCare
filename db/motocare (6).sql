-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2025 at 05:38 AM
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
  `service_ids` varchar(255) DEFAULT NULL,
  `transaction_number` varchar(50) DEFAULT NULL,
  `notes` varchar(500) NOT NULL,
  `vehicle_model` varchar(20) NOT NULL,
  `vehicle_plate_number` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `user_id`, `shop`, `preferred_time`, `time`, `status`, `repair_status`, `total_cost`, `vehicle_name`, `createdAt`, `service_id`, `service_ids`, `transaction_number`, `notes`, `vehicle_model`, `vehicle_plate_number`) VALUES
(20, 14, '1', '2025-10-29', 14, 'cancelled', '', NULL, 'honda click', '2025-10-02 16:57:58', 4, '4', 'TRANS-00000000020', 'ikaw na bahala', 'click 123', '14667'),
(21, 14, '1', '2025-10-04', 9, 'cancelled', '', NULL, 'susano', '2025-10-02 17:43:28', 2, '2', 'TRANS-00000000021', 'wala naman', 'susano 45', 'AJO-146'),
(22, 14, '1', '2025-10-01', 10, 'cancelled', '', NULL, 'sa', '2025-10-02 17:47:51', 3, '3', 'TRANS-00000000022', 'assa', 'kl', 'kl'),
(23, 14, '1', '2025-10-22', 15, 'cancelled', '', NULL, 'power GT', '2025-10-02 17:58:17', 1, '1', 'TRANS-00000000023', 'SA', 'GT40', 'GT-5423'),
(24, 26, '1', '2025-10-15', 16, 'not accepted', '', NULL, 'honda click', '2025-10-03 19:49:43', 2, '2', 'TRANS-00000000024', 'haha', 'GT40', '12345'),
(25, 38, '2', '2025-10-07', 9, 'not accepted', '', NULL, 'Honda', '2025-10-06 15:38:27', 0, NULL, 'TRANS-00000000025', '', 'Xrm125', 'Abcd1234'),
(26, 37, '1', '2025-10-17', 11, 'cancelled', '', NULL, 'sa', '2025-10-06 15:42:06', 2, '2', 'TRANS-00000000026', 'sas', 'sa', 'sa'),
(27, 38, '2', '2025-10-07', 9, 'not accepted', '', NULL, 'Honda', '2025-10-06 15:43:37', 0, NULL, 'TRANS-00000000027', '', 'Xrm125', 'Abcd1234'),
(29, 38, '1', '2025-10-07', 9, 'not accepted', '', NULL, 'Honda', '2025-10-06 15:45:16', 0, NULL, 'TRANS-00000000029', '', 'Xrm125', 'Abcd1234'),
(30, 38, '1', '2025-10-08', 10, 'completed', '', NULL, 'Honda', '2025-10-06 15:50:33', 2, '2', 'TRANS-00000000030', '', 'Xrm125', 'Abcd1234'),
(31, 14, '2', '2025-10-17', 15, 'completed', '', NULL, 'uwu', '2025-10-06 16:35:01', 8, '8', 'TRANS-00000000031', '', 'uwu-35', '200'),
(32, 16, '2', '2025-10-07', 15, 'not accepted', '', NULL, 'tmx', '2025-10-07 05:46:30', 0, NULL, 'TRANS-00000000032', '', '', ''),
(33, 41, '2', '2025-10-07', 15, 'pending', '', NULL, 'honda click', '2025-10-09 05:41:51', 8, '8', 'TRANS-00000000033', 'pa change oil', '125', '123456'),
(34, 42, '1', '2025-10-07', 16, 'completed', '', NULL, 'honda click', '2025-10-09 06:28:38', 2, '2', 'TRANS-00000000034', 'paki dali', '125', '123456'),
(35, 43, '1', '2025-10-10', 9, 'pending', '', NULL, 'Toyota', '2025-10-09 06:43:04', 4, '4', 'TRANS-00000000035', '', 'Vios', 'ERC234'),
(36, 43, '2', '2025-10-09', 9, 'completed', '', NULL, 'Toyota', '2025-10-09 06:44:36', 8, '8', 'TRANS-00000000036', '', 'Vios', '2131kds'),
(37, 41, '2', '2025-10-08', 9, 'rejected', 'not accepted', NULL, 'smash', '2025-10-09 13:14:32', 8, '8', 'TRANS-00000000037', 'blabla', '125', '123456'),
(38, 14, '2', '2025-10-06', 10, 'completed', '', NULL, 'Saturn', '2025-10-12 05:30:21', 8, '8', 'TRANS-00000000038', 'Please don\'t touch the gas tank', 'sat-56', 'A6DRYHY'),
(39, 14, '1', '2025-10-08', 10, 'cancelled', '', NULL, 'asus', '2025-10-12 15:22:05', 1, '1', 'TRANS-00000000039', 'WALA LANG', 'asus45', 'ADFG536T'),
(40, 44, '1', '2025-10-14', 14, 'completed', '', NULL, 'Kawazaki', '2025-10-12 16:22:25', 2, '2', 'TRANS-00000000040', '', 'Barako175', 'Abcedee'),
(41, 44, '1', '2025-10-14', 16, 'completed', '', NULL, 'Honda', '2025-10-12 16:44:45', 1, '1', 'TRANS-00000000041', '', 'Smash110', 'Gamora12'),
(42, 47, '3', '2025-10-14', 10, 'completed', '', NULL, 'XRM125', '2025-10-13 05:03:41', 9, '9', 'TRANS-00000000042', 'None', 'Honda', 'H212526'),
(43, 38, '3', '2025-10-14', 14, 'pending', '', NULL, 'honda ', '2025-10-13 06:47:16', 9, '9', 'TRANS-00000000043', '', 'xrm  125', '052468'),
(44, 44, '2', '2025-10-14', 9, 'completed', '', NULL, 'honda', '2025-10-13 07:03:53', 8, '8', 'TRANS-00000000044', '', 'xrm  125', '052468'),
(45, 14, '3', '2025-10-13', 14, 'cancelled', '', NULL, 'a', '2025-10-13 14:38:03', 9, '9', 'TRANS-00000000045', 'wala', 'a', 'a'),
(46, 52, '3', '2025-11-11', 14, 'completed', '', NULL, 'Honda', '2025-10-14 00:59:45', 9, '9', 'TRANS-00000000046', '', 'Civic', 'ABC 1234'),
(47, 44, '3', '2025-10-17', 9, 'completed', '', NULL, 'tmx', '2025-10-14 04:26:30', 10, '10', 'TRANS-00000000047', 'Apura ngane ', 'XRM125', 'AO12345'),
(48, 56, '2', '2025-10-16', 15, 'not accepted', '', NULL, 'tmx', '2025-10-14 07:00:10', 0, NULL, 'TRANS-00000000048', '', 'xrm  125', 'AO12345'),
(49, 16, '1', '2025-10-22', 14, 'pending', '', NULL, 'pot', '2025-10-14 07:30:01', 2, '2', 'TRANS-00000000049', 'WALA', 'pot56', 'ADF4925F'),
(50, 59, '3', '2025-10-15', 9, 'completed', '', NULL, 'Honda', '2025-10-14 11:22:04', 9, '9', 'TRANS-00000000050', 'Hi po! Magpapa-book sana ako ng schedule para sa change oil.\n', 'XRM125 2017', '0501-0115061'),
(51, 62, '2', '2025-10-17', 9, 'completed', '', NULL, 'Toyota', '2025-10-16 02:52:16', 8, '8', 'TRANS-00000000051', '', 'Vios', 'E3C252'),
(52, 62, '3', '2025-10-17', 10, 'cancelled', '', NULL, 'Honda', '2025-10-16 02:57:43', 11, '11', 'TRANS-00000000052', '', 'Airblade', 'ErC 20202'),
(53, 62, '1', '2025-10-18', 14, 'cancelled', '', NULL, 'Toyota', '2025-10-16 06:09:58', 4, '4', 'TRANS-00000000053', '', 'Vios', 'ERC234'),
(54, 65, '2', '', 0, 'not accepted', '', NULL, '', '2025-10-16 06:45:43', 0, NULL, 'TRANS-00000000054', '', '', ''),
(55, 65, '3', '', 0, 'not accepted', '', NULL, '', '2025-10-16 08:11:02', 0, NULL, 'TRANS-00000000055', '', '', ''),
(56, 65, '1', '', 0, 'not accepted', '', NULL, '', '2025-10-16 08:46:36', 0, NULL, 'TRANS-00000000056', '', '', ''),
(57, 14, '2', '2025-10-02', 9, 'completed', '', NULL, 'Mama mo', '2025-10-18 09:12:36', 8, '8', 'TRANS-00000000057', 'HI', 'mama45', 'MAFR456'),
(58, 14, '2', '2025-09-30', 10, 'cancelled', '', NULL, 'Pusamo', '2025-10-18 14:25:08', 8, '8', 'TRANS-00000000058', 'hoy', 'PusaF', 'P54385'),
(59, 14, '3', '2025-11-20', 10, 'cancelled', '', NULL, 'Apple Me', '2025-10-18 16:25:44', 9, '9', 'TRANS-00000000059', 'ghi', 'Apple0sss', '68343785'),
(60, 37, '1', '2025-11-20', 15, 'cancelled', '', NULL, 'a', '2025-10-18 16:31:48', 2, '2', 'TRANS-00000000060', 'asa', 'aa', 'a'),
(61, 37, '2', '2025-11-26', 9, 'not accepted', '', NULL, 'c', '2025-10-18 16:32:40', 8, '8', 'TRANS-00000000061', 'uwu', 'c', 'c'),
(62, 41, '1', '2025-10-20', 10, 'cancelled', '', NULL, 'Yamaha ', '2025-10-19 04:40:33', 2, '2', 'TRANS-00000000062', 'blabla', 'YTX', '12345'),
(63, 14, '2', '2025-10-09', 9, 'cancelled', '', NULL, 'jsajs', '2025-10-19 04:45:03', 8, '8', 'TRANS-00000000063', 'hi', 'jaskj', 'aksja'),
(64, 14, '2', '2025-10-09', 14, 'cancelled', '', NULL, 'djhskdh', '2025-10-19 04:53:31', 8, '8', 'TRANS-00000000064', 'jaskj', 'hjhjk', 'hjkh'),
(65, 44, '1', '2025-10-22', 15, 'not accepted', '', NULL, 'HONDA', '2025-10-21 15:47:50', 2, '2', 'TRANS-00000000065', '', 'XRM125', 'AO12345'),
(66, 41, '1', '2025-10-23', 11, 'pending', '', NULL, 'HONDA', '2025-10-22 07:49:15', 1, '1', 'TRANS-00000000066', '', 'XRM125', '123456'),
(67, 41, '3', '2025-10-24', 9, 'not accepted', '', NULL, 'HONDA', '2025-10-22 23:49:13', 9, '9', 'TRANS-00000000067', '', 'XRM125', '123456'),
(68, 73, '1', '2025-10-24', 15, 'completed', '', NULL, 'HONDA', '2025-10-23 01:07:44', 2, '2', 'TRANS-00000000068', '', 'XRM125', '123456'),
(69, 73, '1', '2025-10-24', 15, 'completed', '', NULL, 'tmx', '2025-10-23 01:28:16', 3, '3', 'TRANS-00000000069', '', 'Vios', 'ERC234');

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
(1, 'Transmission', 10, 100, '2025-09-14', 1, '', 'fas fa-battery-full'),
(2, 'Engine Repair', 150, 400, '2025-09-14', 1, '', 'fas fa-battery-full'),
(3, 'Diagnostics', 50, 150, '2025-09-14', 1, '', 'fas fa-cogs'),
(4, 'Performance Tuning', 65, 250, '2025-09-14', 1, '', 'fas fa-tools'),
(6, 'hilot', 5, 100, '2025-10-04', NULL, 'Hilot sa para ayus', 'fas fa-wrench'),
(8, 'Change oil', 5, 100, '2025-10-07', 2, 'para sa waran agtang', 'fas fa-wrench'),
(9, 'Oil Change', 100, 350, '2025-10-13', 3, 'Changing oi;', 'fas fa-wrench'),
(10, 'Wheel Alignment', 450, 2700, '2025-10-14', 3, '', 'fas fa-tools'),
(11, 'Brake Repair', 300, 500, '2025-10-14', 3, '', 'fas fa-tools'),
(12, 'breakshow', 70, 150, '2025-10-16', 1, '', 'fas fa-wrench');

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
(3, 'fas fa-motorcycle', 'Purok 3, House no. 81 Rawis, Virac, Catanduanes, ', '124.235', '13.589', 0, 7, '3', 'CT Gear', 0);

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
  `shop_id` int(11) NOT NULL DEFAULT 0,
  `createdAt` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`ID`, `email`, `contact`, `address`, `password`, `email_id`, `fullname`, `picture`, `role`, `username`, `status`, `shop_id`, `createdAt`) VALUES
(14, 'fernandezjasper463@gmail.com', '0919796015', 'sa puso mo hah', 'jessel4545Q', '113393429651685815825', 'jasper fernandez hayYAYAY', 'https://lh3.googleusercontent.com/a/ACg8ocKs_tj_aiJJPyMyFJkU6NOixEMQlOIm9F51_pEsT9ydRUFLsA=s96-c', 'customer', NULL, 'active', 0, '2025-10-03'),
(15, 'malrhinzo@gmail.com', '0912-345-6789', 'pogi', 'okY3iPY7HYPN', '114872420976022575907', 'uwuInz -', 'https://lh3.googleusercontent.com/a/ACg8ocIfneXs3wWxrQx4LRZtZUZQVgNwGra3ObcdvjuJIUHTaDdHnuIo=s96-c', 'customer', NULL, 'active', 0, '2025-10-03'),
(16, 'razelkatenazareno@gmail.com', '0912-345-6789', '', '8AvChKqfi2e4', '101644453373737763629', 'Razel Kate Nazareno', 'https://lh3.googleusercontent.com/a/ACg8ocIoHtKhRZ4uc8i-TPDaW48aa9UxV7N4eE5udWMs2p4NY28vERe6=s96-c', 'customer', NULL, 'active', 0, '2025-10-03'),
(18, 'fernandezmayma@gmail.com', '0912', 'Purok Robarub, Pating (Pob.)', 'staff', '12345', 'mayma ako', NULL, 'staff', 'staff', 'active', 1, '2025-10-03'),
(20, 'moljelube@gmail.com', '09197960151', 'Gogon sirangan, virac, catanduanes', 'moljelube', '4545', 'Molje Lube Enterprice', 'http://wala.com/andrea.png', 'owner', 'owner', 'active', 1, '2025-10-03'),
(24, 'erika456@gmail.com', '09197', 'bagumbayan haha', 'japs4545', '', 'Erika Briones', NULL, 'staff', NULL, 'active', 1, '2025-10-03'),
(25, 'jaun@gmail.com', '09197960151', 'san fernando masbate\npating masbate city', 'jessel45', '', 'Juan Dela Cruz', NULL, 'staff', NULL, 'active', 1, '2025-10-03'),
(31, 'pelorinajane130@gmail.com', '0912-345-6789', 'sjdnd', 'japs', '106085042395953237300', 'Jane Pelorina', 'https://lh3.googleusercontent.com/a/ACg8ocIidfEeYP8w2ozvDXxl67-wNFpqpqHmmjiljBp4suEQ8PhBzA=s96-c', 'customer', NULL, 'active', 0, '2025-10-06'),
(32, 'bfpprofiler@gmail.com', 'sas', 'sa', 'sa', '112221411111045681991', 'bfp profiler', 'https://lh3.googleusercontent.com/a/ACg8ocKxyfeN7iGWUcS0x519k-eY3_a1SP5YKSJCz6eGHEdsATT94g=s96-c', 'customer', NULL, 'active', 0, '2025-10-06'),
(37, 'fernandezmayma@gmail.com', '98', 'j', 'k', '111739984537885755255', 'mayma fernandez', 'https://lh3.googleusercontent.com/a/ACg8ocKXobs5yuCIzxlSoVikycrx6rFUSlbI2VUbw-v85CoYMdNugQ=s96-c', 'customer', NULL, 'active', 0, '2025-10-06'),
(38, 'josephsamosa07@gmail.com', '09123456789', 'Cavinitan', 'Ambisyosa', '109625179195717105310', 'Joseph Samosa', 'https://lh3.googleusercontent.com/a/ACg8ocL7gx3pQHKshLfKVUXJva4LMNhyFc042USbVORksmEo-bCPhGU=s96-c', 'customer', NULL, 'active', 0, '2025-10-06'),
(39, 'pretech@gmail.com', '09106266497', 'sa puso mo mo', 'pretech', '987', 'Precision Tech Moto', NULL, 'owner', NULL, 'active', 2, '2025-10-06'),
(41, 'nazarenorazel2@gmail.com', '09659881556', 'Alibuag, San Andres', '123456789', '101439993775507050560', 'Razel Kate Nazareno', 'https://lh3.googleusercontent.com/a/ACg8ocKq7msaYirI7m5MzmG-TpL5waSVEvAzJ-sf2DLnYwTNvBsNrA=s96-c', 'customer', NULL, 'active', 0, '2025-10-09'),
(42, 'timwatbennyjane@gmail.com', '09659881556', 'bato, catanduanes', 'qwerty1234', '111100156604437675612', 'Bennyjane Timwat', 'https://lh3.googleusercontent.com/a/ACg8ocL7rkSOCpQPFeJR6Wgc4yP78cDzgNyeSvRPTzciFC2ywwRO8g=s96-c', 'customer', NULL, 'active', 0, '2025-10-09'),
(43, 'jdazaquinzon@gmail.com', '09669158725', 'Bigaa, Virac, Catanduanes', 'Jqui@0344', '100079395268593286841', 'Jerico Quinzon', 'https://lh3.googleusercontent.com/a/ACg8ocLUzwCz7niviR2cjsSxHlpXZCVQkVUxH-E7QAW10j08T_P-5kM=s96-c', 'customer', NULL, 'active', 0, '2025-10-09'),
(44, 'andreaoraa1993@gmail.com', '09101628267', 'Cavinitan, Virac', '123456789', '109046699919166566631', 'Andrea Oraa', 'https://lh3.googleusercontent.com/a/ACg8ocJbX-mB_9ZfHntD6i2rc04A7iqEepiVV_5vywpjitdruZEQ6Q=s96-c', 'customer', NULL, 'active', 0, '2025-10-12'),
(45, 'joa125790@gmail.com', '09197960151', 'Cebu City', 'jaon4545', NULL, 'Joan mae Jesus', NULL, 'staff', NULL, 'active', 2, '2025-10-13'),
(46, 'ctgear@gmail.com', '09106266497', 'Purok 3, house no. 81, Virac, Catanduanes', 'ctgear', '999999999999999', 'Joan Mae Santos', NULL, 'owner', NULL, 'active', 3, '2025-10-13'),
(47, 'necilynoraa@gmail.com', '09212573877', '277 BMBA 4th Avenue Brgy. 118 Caloocan City', 'Maisy262015', '104233225838104040120', 'Necelyn Oraa', 'https://lh3.googleusercontent.com/a/ACg8ocJ6WMXvVXt_I8NtXT7et0EEPHsNjKYlkoM6c4AnTbt1e9HuL9dC=s96-c', 'customer', NULL, 'active', 0, '2025-10-13'),
(49, 'andreaoraa@gmail.com', '09123456789', 'Bigaa', 'ctgearstaff', NULL, 'Andrea Oraa', NULL, 'staff', NULL, 'active', 3, '2025-10-13'),
(52, 'aliatanante000@gmail.com', '09483759984', 'San Roque Virac Catanduanes', 'mamamoBlue11', '101275147914952568285', 'Althea Mediario', 'https://lh3.googleusercontent.com/a/ACg8ocJQatlsRhbWaptJZgKAkenzN5vRykIbVCJ0cswLo1L2DjqvOw=s96-c', 'customer', NULL, 'active', 0, '2025-10-14'),
(53, 'torcuatordivine121@gmail.com', '09483282964', 'Tinago, viga, Catanduanes', '123', '102830732813933547865', 'divine', 'https://lh3.googleusercontent.com/a/ACg8ocJyqdPl0lT4Xv-EUe1ZPOLoIyS8_l40LqZfFFIHZetZK0hMwA=s96-c', 'customer', NULL, 'active', 0, '2025-10-14'),
(55, 'garquejohnrenan@gmail.com', '09659881556', 'Comagaycay', '012704', NULL, 'Razel Nazareno', NULL, 'staff', NULL, 'active', 2, '2025-10-14'),
(56, 'johnchristayo30@gmail.com', '09070011557', 'virac', 'august301997', '115743979607712165092', 'John chris Tayo', 'https://lh3.googleusercontent.com/a/ACg8ocK_CGBR-BgcbkYnQEu-MLzy6dsj64n0vzkYIRGv1QtVu0yjxg=s96-c', 'customer', NULL, 'active', 0, '2025-10-14'),
(57, 'jlemttcr@gmail.com', '099277675901', 'Bagumbayan', 'Zxcv4211!', '108843813989580354438', 'Justine Enrico Celis', 'https://lh3.googleusercontent.com/a/ACg8ocKqOxCdwki5Vy6shPzE1Lyi49SVMdYe4Cfnne2p6uM8-sC24g=s96-c', 'customer', NULL, 'active', 0, '2025-10-14'),
(58, 'tuazonclyde8@gmail.com', '09202575283', 'valencia virac', 'clyde12345', '102442950638724025623', 'clyde tuazon', 'https://lh3.googleusercontent.com/a/ACg8ocL0Xm6zDzQl8wc8ZinKCATqcpEUS-usqwq6MR0kLDv64VpHmw=s96-c', 'customer', NULL, 'active', 0, '2025-10-14'),
(59, 'danielomadto1891@gmail.com', '09816617805', 'Datag West, Caramoran, Catanduanes', 'dhan1991', '100122264510616086266', 'Daniel Oraa', 'https://lh3.googleusercontent.com/a/ACg8ocL1bT7TVUhEW-dGAs9zkGv0o5JDxMZqslC9e4FzIX89ZZ3Wu74=s96-c', 'customer', NULL, 'active', 0, '2025-10-14'),
(62, 'jericodquinzon@gmail.com', '09212248271', 'Bigaa Virac Catanduanes', 'Jqui@0344', '117736953149631241823', 'Jerico Quinzon', 'https://lh3.googleusercontent.com/a/ACg8ocJzSGZEhKie3PQIFnEt3VJhj32lI7pfDJfRpX3hUuYQdYXJM0Myzg=s96-c', 'customer', NULL, 'active', 0, '2025-10-16'),
(64, 'andreaoraa02@gmail.com', '09669156832', 'cavinitan', 'andrea', NULL, 'ANDREA ORAA', NULL, 'staff', NULL, 'active', 2, '2025-10-16'),
(65, 'leugimzenemij14@gmail.com', '09060231776', 'Gigmoto', 'migueljimenez', '116431096461522673822', 'Miguel Dela Cruz Jimenez', NULL, 'customer', NULL, 'active', 0, '2025-10-16'),
(71, 'andreaoraa1@gmail.com', '09669156832', 'Datag', '12345', NULL, 'Andrea', NULL, 'staff', NULL, 'active', 1, '2025-10-19'),
(72, 'mathildadelacruz14@gmail.com', '09060231776', 'Gigmoto', '123123123', '107230551485702610041', 'Mathilda Dela Cruz', 'https://lh3.googleusercontent.com/a/ACg8ocI8HogPR4FCAMNLMRjPV_rxGJf57WvP2TgKxJrsMXSLzmO_tw=s96-c', 'customer', NULL, 'active', 0, '2025-10-22'),
(73, 'zcel.tablizo14@gmail.com', '09659881556', 'Alibuag', 'razelkate', '116679600534609393574', 'zcel tablizo', 'https://lh3.googleusercontent.com/a/ACg8ocKfJAS4JAWa8EJnU8ZNYbZG4yLVB-TvcTVJFFWbj9GVVZ_Tsn5a=s96-c', 'customer', NULL, 'active', 0, '2025-10-23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_transaction_number` (`transaction_number`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `shop`
--
ALTER TABLE `shop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
