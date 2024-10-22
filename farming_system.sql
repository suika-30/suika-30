-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2024 at 04:16 AM
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
-- Database: `farming_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `action_log`
--

CREATE TABLE `action_log` (
  `log_id` int(11) NOT NULL,
  `farmer_id` int(11) DEFAULT NULL,
  `crop_id` int(11) DEFAULT NULL,
  `action_taken` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `action_log`
--

INSERT INTO `action_log` (`log_id`, `farmer_id`, `crop_id`, `action_taken`, `date`) VALUES
(1, 1, 1, 'Planted corn despite warning', '2024-09-15'),
(2, 2, 2, 'Applied fertilizer', '2024-09-20'),
(3, 3, 3, 'Checked soil moisture', '2024-09-25'),
(4, 4, 4, 'Weeded crop area', '2024-09-22'),
(5, 5, 5, 'Harvested cucumber', '2024-09-23');

-- --------------------------------------------------------

--
-- Table structure for table `crops`
--

CREATE TABLE `crops` (
  `crop_id` int(11) NOT NULL,
  `farmer_id` int(11) DEFAULT NULL,
  `crop_type` varchar(50) DEFAULT NULL,
  `expected_harvest_time` date DEFAULT NULL,
  `growth_stage` varchar(50) DEFAULT NULL,
  `planting_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crops`
--

INSERT INTO `crops` (`crop_id`, `farmer_id`, `crop_type`, `expected_harvest_time`, `growth_stage`, `planting_date`) VALUES
(1, 1, 'Corn', '2024-12-01', 'Seedling', '2024-09-15'),
(2, 2, 'Rice', '2025-01-10', 'Germination', '2024-09-20'),
(3, 3, 'Tomato', '2024-11-10', 'Flowering', '2024-09-25'),
(4, 4, 'Peanut', '2024-12-15', 'Growth', '2024-09-19'),
(5, 5, 'Cucumber', '2024-12-20', 'Harvest', '2024-09-21');

-- --------------------------------------------------------

--
-- Table structure for table `farmers`
--

CREATE TABLE `farmers` (
  `farmer_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `farm_size` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmers`
--

INSERT INTO `farmers` (`farmer_id`, `name`, `location`, `farm_size`) VALUES
(1, 'Juan Dela Cruz', 'Ilocos Norte', 10.5),
(2, 'Maria Santos', 'Batangas', 5.2),
(3, 'Pedro Reyes', 'Pampanga', 8.3),
(4, 'Luz Navarro', 'Cebu', 12.7),
(5, 'Ramon Diaz', 'Davao', 6.4);

-- --------------------------------------------------------

--
-- Table structure for table `fertilizer_recommendations`
--

CREATE TABLE `fertilizer_recommendations` (
  `fertilizer_id` int(11) NOT NULL,
  `crop_id` int(11) DEFAULT NULL,
  `recommended_fertilizer` varchar(50) DEFAULT NULL,
  `application_time` varchar(50) DEFAULT NULL,
  `fertilizer_status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fertilizer_recommendations`
--

INSERT INTO `fertilizer_recommendations` (`fertilizer_id`, `crop_id`, `recommended_fertilizer`, `application_time`, `fertilizer_status`) VALUES
(1, 1, 'Urea', 'Pre-planting', 1),
(2, 2, 'Phosphate', 'Mid-growth', 0),
(3, 3, 'Potash', 'Post-planting', 1),
(4, 4, 'NPK', 'Flowering', 0),
(5, 5, 'Compost', 'Pre-harvest', 1);

-- --------------------------------------------------------

--
-- Table structure for table `soil_quality`
--

CREATE TABLE `soil_quality` (
  `soil_id` int(11) NOT NULL,
  `farmer_id` int(11) DEFAULT NULL,
  `soil_type` varchar(50) DEFAULT NULL,
  `nutrient_level` varchar(50) DEFAULT NULL,
  `last_updated` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `soil_quality`
--

INSERT INTO `soil_quality` (`soil_id`, `farmer_id`, `soil_type`, `nutrient_level`, `last_updated`) VALUES
(1, 1, 'Loamy', 'High Nitrogen', '2024-09-20'),
(2, 2, 'Clay', 'Medium Phosphorus', '2024-09-22'),
(3, 3, 'Sandy', 'Low Potassium', '2024-09-23'),
(4, 4, 'Peaty', 'High Organic Matter', '2024-09-21'),
(5, 5, 'Chalky', 'High Calcium', '2024-09-24');

-- --------------------------------------------------------

--
-- Table structure for table `weather`
--

CREATE TABLE `weather` (
  `weather_id` int(11) NOT NULL,
  `farmer_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `weather_forecast` varchar(50) DEFAULT NULL,
  `suitable_for_planting` tinyint(1) DEFAULT NULL,
  `warning_message` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `weather`
--

INSERT INTO `weather` (`weather_id`, `farmer_id`, `date`, `weather_forecast`, `suitable_for_planting`, `warning_message`) VALUES
(1, 1, '2024-10-01', 'Sunny', 1, NULL),
(2, 2, '2024-10-02', 'Rainy', 0, 'Heavy rains expected, avoid planting.'),
(3, 3, '2024-10-03', 'Cloudy', 1, NULL),
(4, 4, '2024-10-04', 'Stormy', 0, 'Storm approaching, delay planting.'),
(5, 5, '2024-10-05', 'Sunny', 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `action_log`
--
ALTER TABLE `action_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `farmer_id` (`farmer_id`),
  ADD KEY `crop_id` (`crop_id`);

--
-- Indexes for table `crops`
--
ALTER TABLE `crops`
  ADD PRIMARY KEY (`crop_id`),
  ADD KEY `farmer_id` (`farmer_id`);

--
-- Indexes for table `farmers`
--
ALTER TABLE `farmers`
  ADD PRIMARY KEY (`farmer_id`);

--
-- Indexes for table `fertilizer_recommendations`
--
ALTER TABLE `fertilizer_recommendations`
  ADD PRIMARY KEY (`fertilizer_id`),
  ADD KEY `crop_id` (`crop_id`);

--
-- Indexes for table `soil_quality`
--
ALTER TABLE `soil_quality`
  ADD PRIMARY KEY (`soil_id`),
  ADD KEY `farmer_id` (`farmer_id`);

--
-- Indexes for table `weather`
--
ALTER TABLE `weather`
  ADD PRIMARY KEY (`weather_id`),
  ADD KEY `farmer_id` (`farmer_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `action_log`
--
ALTER TABLE `action_log`
  ADD CONSTRAINT `action_log_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`farmer_id`),
  ADD CONSTRAINT `action_log_ibfk_2` FOREIGN KEY (`crop_id`) REFERENCES `crops` (`crop_id`);

--
-- Constraints for table `crops`
--
ALTER TABLE `crops`
  ADD CONSTRAINT `crops_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`farmer_id`);

--
-- Constraints for table `fertilizer_recommendations`
--
ALTER TABLE `fertilizer_recommendations`
  ADD CONSTRAINT `fertilizer_recommendations_ibfk_1` FOREIGN KEY (`crop_id`) REFERENCES `crops` (`crop_id`);

--
-- Constraints for table `soil_quality`
--
ALTER TABLE `soil_quality`
  ADD CONSTRAINT `soil_quality_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`farmer_id`);

--
-- Constraints for table `weather`
--
ALTER TABLE `weather`
  ADD CONSTRAINT `weather_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`farmer_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
