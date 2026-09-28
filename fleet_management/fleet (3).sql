-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2025 at 03:46 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fleet`
--

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `Vehicle_id` int(11) NOT NULL,
  `PlateNo` varchar(255) NOT NULL,
  `VehicleType` varchar(255) NOT NULL,
  `Model` varchar(255) NOT NULL,
  `ChessisNo` varchar(255) NOT NULL,
  `Capacity` varchar(255) NOT NULL,
  `ProductionDate` varchar(255) NOT NULL,
  `EngineNo` varchar(25) NOT NULL,
  `EnginePower` varchar(255) NOT NULL,
  `Owner` varchar(255) NOT NULL,
  `Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`Vehicle_id`, `PlateNo`, `VehicleType`, `Model`, `ChessisNo`, `Capacity`, `ProductionDate`, `EngineNo`, `EnginePower`, `Owner`, `Date`) VALUES
(9, '1983', 'Minibus', 'toyota', '1234', 'High', '2025-05-21', '1', '1234', 'Abayya Campus', '0000-00-00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`PlateNo`),
  ADD UNIQUE KEY `Vehicle_id` (`Vehicle_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `Vehicle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
