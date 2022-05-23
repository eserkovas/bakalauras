-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2022 m. Geg 23 d. 22:20
-- Server version: 10.3.25-MariaDB-log
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bakalauras`
--

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `prekes`
--

CREATE TABLE `prekes` (
  `id` int(9) NOT NULL,
  `pavadinimas` varchar(64) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `kodas` varchar(32) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `svoris` int(16) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

--
-- Sukurta duomenų kopija lentelei `prekes`
--

INSERT INTO `prekes` (`id`, `pavadinimas`, `kodas`, `svoris`) VALUES
(1, 'Ledai', '255', 75),
(2, 'Bananai', '756', 0),
(3, 'Taburetė', '355', 2300);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `prekes_sandeliai`
--

CREATE TABLE `prekes_sandeliai` (
  `id` int(9) NOT NULL,
  `preke` int(9) DEFAULT NULL,
  `sandelis` int(9) DEFAULT NULL,
  `likutis` int(9) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

--
-- Sukurta duomenų kopija lentelei `prekes_sandeliai`
--

INSERT INTO `prekes_sandeliai` (`id`, `preke`, `sandelis`, `likutis`) VALUES
(2, 1, 1, 1423),
(3, 1, 3, 1),
(4, 3, 1, 3);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `prekes_tiekejai`
--

CREATE TABLE `prekes_tiekejai` (
  `id` int(9) NOT NULL,
  `preke` int(9) DEFAULT NULL,
  `tiekejas` int(9) DEFAULT NULL,
  `kaina` double DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

--
-- Sukurta duomenų kopija lentelei `prekes_tiekejai`
--

INSERT INTO `prekes_tiekejai` (`id`, `preke`, `tiekejas`, `kaina`) VALUES
(1, 3, 1, 19990),
(2, 1, 1, 149),
(3, 1, 5, 125);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `prekes_uzsakymai`
--

CREATE TABLE `prekes_uzsakymai` (
  `id` int(9) NOT NULL,
  `preke` int(9) DEFAULT NULL,
  `sandelis` int(9) DEFAULT NULL,
  `kiekis` int(9) NOT NULL DEFAULT 0,
  `tiekejas` int(9) DEFAULT NULL,
  `suma` int(9) NOT NULL DEFAULT 0,
  `data` datetime DEFAULT current_timestamp(),
  `patvirtintas` int(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Sukurta duomenų kopija lentelei `prekes_uzsakymai`
--

INSERT INTO `prekes_uzsakymai` (`id`, `preke`, `sandelis`, `kiekis`, `tiekejas`, `suma`, `data`, `patvirtintas`) VALUES
(1, 1, 3, 4, 5, 500, '2022-05-11 10:30:34', 0),
(6, 3, 1, 5, 1, 99950, '2022-05-11 11:36:39', 1);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `sandeliai`
--

CREATE TABLE `sandeliai` (
  `id` int(9) NOT NULL,
  `pavadinimas` varchar(32) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `adresas` varchar(64) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `miestas` varchar(32) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `kodas` varchar(5) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `telefonas` varchar(12) COLLATE utf8_lithuanian_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

--
-- Sukurta duomenų kopija lentelei `sandeliai`
--

INSERT INTO `sandeliai` (`id`, `pavadinimas`, `adresas`, `miestas`, `kodas`, `telefonas`) VALUES
(1, 'Centrinis sandėliukas', 'A. Miesto g. 53', 'Kaunas', '43091', '+37060000012'),
(3, 'Kitas sandėliukas', 'A. Gatvės g. 55', 'Panevėžys', '34009', NULL);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `tiekejai`
--

CREATE TABLE `tiekejai` (
  `id` int(9) NOT NULL,
  `pavadinimas` varchar(64) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `telefonas` varchar(12) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `pastas` varchar(64) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `prekes` varchar(256) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `pastaba` varchar(256) COLLATE utf8_lithuanian_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

--
-- Sukurta duomenų kopija lentelei `tiekejai`
--

INSERT INTO `tiekejai` (`id`, `pavadinimas`, `telefonas`, `pastas`, `prekes`, `pastaba`) VALUES
(1, 'UAB \\&#34;Tiekita\\&#34;', '+37060000004', 'tiekejas@imone.lt', 'Drabužiai, baldai, maisto produktai', 'Apmokėjimas tik pavedimais'),
(5, 'UAB \\&#34;Maistinga\\&#34;', '+37060000009', 'tiekimas@maistinga.lt', 'Maisto prekės', NULL);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `uzsakymai`
--

CREATE TABLE `uzsakymai` (
  `id` int(9) NOT NULL,
  `data` datetime DEFAULT current_timestamp(),
  `statusas` int(1) DEFAULT 0,
  `sandelis` int(9) DEFAULT NULL,
  `svoris` int(9) DEFAULT NULL,
  `dezes` int(3) DEFAULT NULL,
  `gavejas` varchar(128) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `gavejo_telefonas` varchar(12) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `gavejo_pastas` varchar(64) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `adresas` varchar(64) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `miestas` varchar(32) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `kodas` varchar(5) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `siuntos_kodas` varchar(32) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `siuntos_data` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

--
-- Sukurta duomenų kopija lentelei `uzsakymai`
--

INSERT INTO `uzsakymai` (`id`, `data`, `statusas`, `sandelis`, `svoris`, `dezes`, `gavejas`, `gavejo_telefonas`, `gavejo_pastas`, `adresas`, `miestas`, `kodas`, `siuntos_kodas`, `siuntos_data`) VALUES
(1, '2022-05-05 11:36:22', 0, 1, NULL, NULL, 'Vardenis Pavardenis', '+37060000001', 'el.pastas@gavejas.lt', 'A. Gatvelės g. 87-2', 'Vilnius', '03944', 'AB123456789LT', NULL);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `uzsakymai_prekes`
--

CREATE TABLE `uzsakymai_prekes` (
  `id` int(9) NOT NULL,
  `uzsakymas` int(9) DEFAULT NULL,
  `preke` int(9) DEFAULT NULL,
  `kiekis` int(9) NOT NULL DEFAULT 0,
  `svoris` int(9) NOT NULL DEFAULT 0,
  `informacija` varchar(128) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `paruosta` int(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

--
-- Sukurta duomenų kopija lentelei `uzsakymai_prekes`
--

INSERT INTO `uzsakymai_prekes` (`id`, `uzsakymas`, `preke`, `kiekis`, `svoris`, `informacija`, `paruosta`) VALUES
(1, 1, 1, 5, 75, NULL, 0),
(2, 1, 2, 3, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `vartotojai`
--

CREATE TABLE `vartotojai` (
  `id` int(9) NOT NULL,
  `vardas` varchar(32) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `pastas` varchar(32) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `slaptazodis` varchar(64) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `grupe` int(9) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

--
-- Sukurta duomenų kopija lentelei `vartotojai`
--

INSERT INTO `vartotojai` (`id`, `vardas`, `pastas`, `slaptazodis`, `grupe`) VALUES
(9, 'Darbuotojas', 'darbuotojas@imone.lt', '$2y$10$neOx9WwPRrHj/sSXx2XZQOEYW/VfNdTzas8OArez9hkD6v2PBZ3Sm', 1),
(10, 'Vadybininkas', 'vadybininkas@imone.lt', '$2y$10$Ge0wzuMD4Sv.vQtO.T9RBeWYmKIsh/5lR1PXMcsJgYlOKPwFhGZAy', 2),
(11, 'Administratorius', 'admin@imone.lt', '$2y$10$MrFeAjNzNdWMSPu6VZmMf.dG6ljxAGsZERiJyhRJlnjt1GJIMlZn6', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `prekes`
--
ALTER TABLE `prekes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prekes_sandeliai`
--
ALTER TABLE `prekes_sandeliai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prekes_tiekejai`
--
ALTER TABLE `prekes_tiekejai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prekes_uzsakymai`
--
ALTER TABLE `prekes_uzsakymai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sandeliai`
--
ALTER TABLE `sandeliai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tiekejai`
--
ALTER TABLE `tiekejai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uzsakymai`
--
ALTER TABLE `uzsakymai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uzsakymai_prekes`
--
ALTER TABLE `uzsakymai_prekes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vartotojai`
--
ALTER TABLE `vartotojai`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `prekes`
--
ALTER TABLE `prekes`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `prekes_sandeliai`
--
ALTER TABLE `prekes_sandeliai`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `prekes_tiekejai`
--
ALTER TABLE `prekes_tiekejai`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `prekes_uzsakymai`
--
ALTER TABLE `prekes_uzsakymai`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sandeliai`
--
ALTER TABLE `sandeliai`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tiekejai`
--
ALTER TABLE `tiekejai`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `uzsakymai`
--
ALTER TABLE `uzsakymai`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `uzsakymai_prekes`
--
ALTER TABLE `uzsakymai_prekes`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vartotojai`
--
ALTER TABLE `vartotojai`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
