-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Dec 08, 2018 at 02:59 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 5.6.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ewa`
--

-- --------------------------------------------------------

--
-- Table structure for table `angebot`
--

CREATE TABLE `angebot` (
  `PizzaName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Bilddatei` varchar(10000) COLLATE utf8_unicode_ci NOT NULL,
  `Preis` decimal(10,2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `angebot`
--

INSERT INTO `angebot` (`PizzaName`, `Bilddatei`, `Preis`) VALUES
('Pizza Casanova', 'images/pizza4.jpg', '5.50'),
('Pizza Hawaii', 'images/pizza3.png', '5.00'),
('Pizza Margherita', 'images/pizza1.jpg', '4.00'),
('Pizza Salami', 'images/pizza2.jpg', '4.50');

-- --------------------------------------------------------

--
-- Table structure for table `bestelltepizza`
--

CREATE TABLE `bestelltepizza` (
  `PizzaID` int(11) NOT NULL,
  `fBestellungID` int(10) UNSIGNED DEFAULT NULL,
  `fPizzaName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Status` tinyint(4) NOT NULL COMMENT '0: bestellt 1: im Ofen 2: fertig 3: unterwegs 4: geliefert'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bestelltepizza`
--

INSERT INTO `bestelltepizza` (`PizzaID`, `fBestellungID`, `fPizzaName`, `Status`) VALUES
(6, 3, 'Pizza Casanova', 0),
(7, 3, 'Pizza Hawaii', 0),
(8, 3, 'Pizza Margherita', 0),
(9, 3, 'Pizza Salami', 0),
(10, 3, 'Pizza Margherita', 0),
(11, 5, 'Pizza Hawaii', 0),
(12, 6, 'Pizza Casanova', 0),
(13, 7, 'Pizza Hawaii', 0);

-- --------------------------------------------------------

--
-- Table structure for table `bestellung`
--

CREATE TABLE `bestellung` (
  `BestellungID` int(11) UNSIGNED NOT NULL,
  `Adresse` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `Bestelltzeitpunkt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bestellung`
--

INSERT INTO `bestellung` (`BestellungID`, `Adresse`, `Bestelltzeitpunkt`, `Name`) VALUES
(3, 'Inselstrasse 100, 64287, Darmstadt', '2018-12-05 00:26:16', 'Kim Khanh Luu'),
(5, 'Strasse 1000, 10000, Berlin', '2018-12-05 02:14:09', 'Max'),
(6, 'Street 200, 1235, Hamburg', '2018-12-05 02:18:35', 'Bin'),
(7, 'fads 89, 23109, Hannover', '2018-12-05 02:19:37', 'D');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `angebot`
--
ALTER TABLE `angebot`
  ADD PRIMARY KEY (`PizzaName`);

--
-- Indexes for table `bestelltepizza`
--
ALTER TABLE `bestelltepizza`
  ADD PRIMARY KEY (`PizzaID`),
  ADD KEY `FK_Angebot_BestelltePizza` (`fPizzaName`),
  ADD KEY `FK_Bestellung_BestelltePizza` (`fBestellungID`);

--
-- Indexes for table `bestellung`
--
ALTER TABLE `bestellung`
  ADD PRIMARY KEY (`BestellungID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bestelltepizza`
--
ALTER TABLE `bestelltepizza`
  MODIFY `PizzaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `bestellung`
--
ALTER TABLE `bestellung`
  MODIFY `BestellungID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bestelltepizza`
--
ALTER TABLE `bestelltepizza`
  ADD CONSTRAINT `FK_Angebot_BestelltePizza` FOREIGN KEY (`fPizzaName`) REFERENCES `angebot` (`PizzaName`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Bestellung_BestelltePizza` FOREIGN KEY (`fBestellungID`) REFERENCES `bestellung` (`BestellungID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
