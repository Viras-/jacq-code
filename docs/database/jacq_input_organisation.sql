-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 14. Dez 2013 um 07:20
-- Server Version: 5.5.33
-- PHP-Version: 5.3.17

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `jacq_input`
--
CREATE DATABASE IF NOT EXISTS `jacq_input` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `jacq_input`;

--
-- TRUNCATE Tabelle vor dem Einfügen `tbl_organisation`
--

TRUNCATE TABLE `tbl_organisation`;
--
-- Daten für Tabelle `tbl_organisation`
--

INSERT INTO `tbl_organisation` (`id`, `description`, `department`, `greenhouse`, `parent_organisation_id`, `gardener_id`, `ipen_code`) VALUES
(1, 'unknown', NULL, 0, NULL, NULL, NULL),
(4, 'Universität Wien', '', 0, 1, 1, NULL),
(6, 'Botanischer Garten', '', 0, 4, 1, 'WU'),
(7, 'Glashaus', 'Glashaus', 1, 6, 1, NULL),
(8, 'Sukkulente', 'Glashaus', 1, 7, 1, NULL),
(9, 'Kalthaus', 'Glashaus', 1, 7, 1, NULL),
(10, 'Freiland', 'Freiland', 0, 6, 1, NULL),
(11, 'Warmhaus', 'Glashaus', 1, 7, 1, NULL),
(12, 'Orchideen', 'Glashaus', 1, 7, 1, NULL),
(13, 'G1 (Kalthaus)', 'Glashaus', 1, 9, 1, NULL),
(14, 'G9 (Orchideen)', 'Glashaus', 1, 12, 1, NULL),
(15, 'G12 (Bromelien)', 'Glashaus', 1, 12, 1, NULL),
(16, 'G11 (Xerophyten)', 'Glashaus', 1, 12, 1, NULL),
(17, 'G13 (Orchideen-Kalthaus)', 'Glashaus', 1, 12, 1, NULL),
(18, 'G16 (Orchideen Warmhaus)', 'Glashaus', 1, 12, 1, NULL),
(19, 'G20 (Kaphaus)', 'Glashaus', 1, 8, 1, NULL),
(20, 'G20A (Kaphaus)', 'Glashaus', 1, 8, 1, NULL),
(21, 'G1 (Sukkulente)', 'Glashaus', 1, 8, 1, NULL),
(22, 'G5 (Sukkulentenhaus West)', 'Glashaus', 1, 8, 1, NULL),
(23, 'G4 (Sukkulentenhaus Ost)', 'Glashaus', 1, 8, 1, NULL),
(24, 'G6 (Tropenhaus)', 'Glashaus', 1, 11, 1, NULL),
(25, 'G7 (Warmhaus 2)', 'Glashaus', 1, 11, 1, NULL),
(26, 'G8 (Warmhaus 1)', 'Glashaus', 1, 11, 1, NULL),
(27, 'G18 (Jacquinhaus)', 'Glashaus', 1, 11, 1, NULL),
(28, 'G19 (Victoriabecken)', 'Glashaus', 1, 11, 1, NULL),
(29, 'System', 'Freiland', 0, 10, 1, NULL),
(30, 'Experimentalgarten', 'Freiland', 0, 10, 1, NULL),
(31, 'Nutzpflanzen', 'Freiland', 0, 10, 1, NULL),
(32, 'Monocotyledonen 19', 'Freiland', 0, 10, 1, NULL),
(33, 'Arboretum', 'Freiland', 0, 10, 1, NULL),
(34, 'Hostischer Garten', 'Freiland', 0, 10, 1, NULL),
(35, 'Baumschule', 'Freiland', 0, 10, 1, NULL),
(36, 'Österreich', 'Freiland', 0, 10, 1, NULL),
(37, 'Alpinum', 'Freiland', 0, 10, 1, NULL),
(38, 'Biologische Gruppe', 'Freiland', 0, 10, 1, NULL),
(39, 'Genetische Gruppe', 'Freiland', 0, 10, 1, NULL),
(40, 'Betriebshof', 'Freiland', 0, 10, 1, NULL),
(41, 'Parkplatz', 'Freiland', 0, 10, 1, NULL),
(42, 'Grünfläche hinter dem Institut', 'Freiland', 0, 10, 1, NULL),
(43, 'Institutsvorplatz', 'Freiland', 0, 10, 1, NULL),
(44, 'Geographische Gruppe', 'Freiland', 0, 10, 1, NULL),
(45, 'Gruppe 51 (Alpinum)', 'Freiland', 0, 37, 1, NULL),
(46, 'Gruppe 56, Experimentalgarten', 'Freiland', 0, 37, 1, NULL),
(47, 'Gruppe 20', 'Freiland', 0, 33, 1, NULL),
(48, 'Gruppe 21', 'Freiland', 0, 33, 1, NULL),
(49, 'Gruppe 22', 'Freiland', 0, 33, 1, NULL),
(50, 'Gruppe 23', 'Freiland', 0, 33, 1, NULL),
(51, 'Gruppe 24', 'Freiland', 0, 33, 1, NULL),
(52, 'Gruppe 25', 'Freiland', 0, 33, 1, NULL),
(53, 'Baumschule', 'Freiland', 0, 35, 1, NULL),
(54, 'Gruppe 54 (Betriebshof)', 'Freiland', 0, 40, 1, NULL),
(55, 'Gruppe 52 (bütenbiologische + morphologische)', '', 0, 38, 1, NULL),
(56, 'G1', 'Freiland', 0, 30, 1, NULL),
(57, 'G21A (Experimentalhaus Ost)', 'Freiland', 1, 30, 1, NULL),
(58, 'G21B (Experimentalhaus West)', 'Freiland', 1, 30, 1, NULL),
(59, 'Gruppe 56 (Experimentalgarten)', 'Freiland', 0, 30, 1, NULL),
(60, 'Gruppe 60 (Kastenanlage)', 'Freiland', 0, 30, 1, NULL),
(61, 'Gruppe 53 (genetische)', 'Freiland', 0, 39, 1, NULL),
(62, 'Gruppe 60', 'Freiland', 0, 44, 1, NULL),
(63, 'Gruppe 58', 'Freiland', 0, 42, 1, NULL),
(64, 'Gruppe 26', 'Freiland', 0, 34, 1, NULL),
(65, 'Gruppe 27', 'Freiland', 0, 34, 1, NULL),
(66, 'Gruppe 28', 'Freiland', 0, 34, 1, NULL),
(67, 'Gruppe 29', 'Freiland', 0, 34, 1, NULL),
(68, 'Gruppe 30', 'Freiland', 0, 34, 1, NULL),
(69, 'Gruppe 31', 'Freiland', 0, 34, 1, NULL),
(70, 'Gruppe 32', 'Freiland', 0, 34, 1, NULL),
(71, 'Gruppe 33', 'Freiland', 0, 34, 1, NULL),
(72, 'Gruppe 34', 'Freiland', 0, 34, 1, NULL),
(73, 'Gruppe 59', 'Freiland', 0, 43, 1, NULL),
(74, 'Gruppe 19', 'Freiland', 0, 32, 1, NULL),
(75, 'Gruppe 60 (Kastenanlage)', 'Freiland', 0, 32, 1, NULL),
(76, 'G2 (Quergang)', 'Freiland', 0, 31, 1, NULL),
(77, 'Gruppe 48 (Nutzpflanzen)', 'Freiland', 0, 31, 1, NULL),
(78, 'Gruppe 60 (Kastenanlage)', 'Freiland', 0, 31, 1, NULL),
(79, 'Gruppe 57', 'Freiland', 0, 41, 1, NULL),
(80, 'G1', 'Freiland', 0, 29, 1, NULL),
(81, 'G19 (Victoriabecken)', 'Freiland', 0, 29, 1, NULL),
(82, 'Gruppe 49 (Allee bei Nutzpflanzen)', 'Freiland', 0, 29, 1, NULL),
(83, 'Gruppe 57', 'Freiland', 0, 29, 1, NULL),
(84, 'Gruppe 1 System Dicots', 'Freiland', 0, 29, 1, ''),
(85, 'Gruppe 2 System Dicots', 'Freiland', 0, 29, 1, ''),
(86, 'Gruppe 3 System Dicots', 'Freiland', 0, 29, 1, ''),
(87, 'Gruppe 4 System Dicots', 'Freiland', 0, 29, 1, ''),
(88, 'Gruppe 5 System Dicots', 'Freiland', 0, 29, 1, ''),
(89, 'Gruppe 36', 'Freiland', 0, 36, 2, NULL),
(90, 'Gruppe 37', 'Freiland', 0, 36, 2, NULL),
(91, 'Gruppe 35', 'Freiland', 0, 36, 2, NULL),
(92, 'Gruppe 38', 'Freiland', 0, 36, 2, NULL),
(93, 'Gruppe 39', 'Freiland', 0, 36, 2, NULL),
(94, 'Gruppe 40', 'Freiland', 0, 36, 2, NULL),
(95, 'Gruppe 41', 'Freiland', 0, 36, 2, NULL),
(96, 'Gruppe 42', 'Freiland', 0, 36, 2, NULL),
(97, 'Gruppe 43', 'Freiland', 0, 36, 2, NULL),
(98, 'Gruppe 44', 'Freiland', 0, 36, 2, NULL),
(99, 'Gruppe 45', 'Freiland', 0, 36, 2, NULL),
(100, 'Gruppe 46', 'Freiland', 0, 36, 2, NULL),
(101, 'Gruppe 47', 'Freiland', 0, 36, 2, NULL),
(102, 'Baumschule', 'Freiland', 0, 36, 2, NULL),
(103, 'Gruppe 56 (Experimentalgarten)', 'Freiland', 0, 36, 2, NULL),
(104, 'Gruppe 6 System Dicots', 'Freiland', 0, 29, 1, ''),
(105, 'Gruppe 7 System Dicots', 'Freiland', 0, 29, 1, ''),
(106, 'Gruppe 8 System Dicots', 'Freiland', 0, 29, 1, ''),
(107, 'Gruppe 9 System Dicots', 'Freiland', 0, 29, 1, ''),
(108, 'Gruppe 10 System Dicots', 'Freiland', 0, 29, 1, ''),
(109, 'Gruppe 11 System Dicots', 'Freiland', 0, 29, 1, ''),
(110, 'Gruppe 12 System Dicots', 'Freiland', 0, 29, 1, ''),
(111, 'Gruppe 13 System Dicots', 'Freiland', 0, 29, 1, ''),
(112, 'Gruppe 14 System Dicots', 'Freiland', 0, 29, 1, ''),
(113, 'Gruppe 15 System Dicots', 'Freiland', 0, 29, 1, ''),
(114, 'Gruppe 16 System Dicots', 'Freiland', 0, 29, 1, ''),
(115, 'Gruppe 17 System Dicots', 'Freiland', 0, 29, 1, ''),
(116, 'Gruppe 18 System Dicots', 'Freiland', 0, 29, 1, ''),
(117, 'G6 (Tropenhaus)', 'Orchideen', 1, 12, 1, ''),
(118, 'G15 (Orchideen temp. Haus)', 'Orchideen', 1, 12, 1, ''),
(119, 'Gruppe 50 (Umfeld Gewächshäuser)', 'System', 0, 29, 1, ''),
(120, 'Aquarien', 'Orchideen', 0, 12, 1, ''),
(121, 'Index Seminum', '', 1, 6, 1, '');
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
