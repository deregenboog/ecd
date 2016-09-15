-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 16, 2010 at 11:24 AM
-- Server version: 5.1.41
-- PHP Version: 5.3.2-1ubuntu4.2

-- This SQL file defines the necessary metadata for Regenboog ECD. Changes to
-- this metadata in your development installation must be recorded here, so
-- that it can be exported to all development, test and production
-- installations.


SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `regenboog`
--

-- --------------------------------------------------------

--
-- Dumping data for table `doorverwijzers`
--

TRUNCATE TABLE `doorverwijzers`;

INSERT INTO `doorverwijzers` (`id`, `naam`, `startdatum`, `einddatum`, `created`, `modified`, `type`) VALUES
(4, 'DWI/Fibu', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(5, 'GGD V&A', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(6, 'Jellinek/Mentrum  JOT teams', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(7, 'GGD Poliklinieken en AMT', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(8, 'Nachtopvang voorzieningen', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(9, 'Instroomhuis', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(10, 'Jellinek/Mentrum ACT en Rehab', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(11, 'Inloop voozieningen', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(12, 'Sociale pensions', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(13, 'Regenboog/AMOC', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(14, 'Leger des Heils', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(15, 'HVO/Querido', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(16, 'Reclassering', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(17, 'Dagbesteding', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(18, 'MDHG', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(19, 'Streetcornerwork WorkForce', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(20, 'Klinieken', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(21, 'AMW', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(22, 'Streetcornerwork veldwerk', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(23, 'Zorginstellingen buiten Amsterdam', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(24, 'Schuldhulpverlening', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(25, 'UWV', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(26, 'Ziekenhuizen', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(27, 'Huisvestingsorganisaties', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(28, 'Bewindvoerders', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(29, 'Jeugdhulpverlening', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(30, 'Drugspastoraat', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(31, 'Overige instanties', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(32, 'De Meren', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(33, 'Tandarts', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(34, 'Zorginstellingen binnen Amsterdam', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(35, 'Deurwaarder', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(36, 'GGD 24 uursdienst', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(37, 'Ziekenfonds', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(38, 'Huisarts', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(39, 'GGZ InGeest (Buitenamstel)', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(40, 'Verpleeghuizen', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(41, 'Ombudsman', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(46, 'RBG Vriendendiensten', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(45, 'RBG Buddyzorg', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(44, 'RBG overig', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(43, 'Advocaat', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(42, 'Apotheek', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(47, 'Politie', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(48, 'Justitie', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(49, 'Rechtbank', '2010-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(51, 'Lopend hulpverleningscontract', '2011-03-16', NULL, NULL, NULL, 'Trajecthouder'),
(56, 'Trajecthouder Jellinek', '2011-03-16', NULL, NULL, NULL, 'Trajecthouder'),
(55, 'Trajecthouder GGD', '2011-03-16', NULL, NULL, NULL, 'Trajecthouder'),
(54, 'Trajecthouder HvO-Querido', '2011-03-16', NULL, NULL, NULL, 'Trajecthouder'),
(53, 'Trajecthouder Volksbond', '2011-03-16', NULL, NULL, NULL, 'Trajecthouder'),
(52, 'Trajecthouder Mentrum', '2011-03-16', NULL, NULL, NULL, 'Trajecthouder'),
(57, 'Land van herkomst', '2011-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(58, 'Ambassade', '2011-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(59, 'Hulpverlening in land van herkomst/buiten NL', '2011-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(60, 'Familie/vrienden', '2011-03-16', NULL, NULL, NULL, 'Doorverwijzer'),
(61, 'Dokter Valckenier', '2011-03-16', NULL, NULL, NULL, 'Doorverwijzer');

-- --------------------------------------------------------

--
-- Dumping data for table `geslachten`
--

TRUNCATE TABLE `geslachten`;

INSERT INTO `geslachten` (`id`, `afkorting`, `volledig`, `created`, `modified`) VALUES
(1, 'M', 'Man', NULL, NULL),
(2, 'V', 'Vrouw', NULL, NULL),
(3, 'onb', 'Onbekend', NULL, NULL);

-- --------------------------------------------------------


--
-- Dumping data for table `hoofdaannemers`
--

TRUNCATE TABLE `hoofdaannemers`;

INSERT INTO `hoofdaannemers` (`id`, `naam`, `created`, `modified`) VALUES
(1, 'De Regenboog Groep', '2011-03-29 12:43:46', '2011-03-29 12:43:51'),
(2, 'GGZ Ingeest', '2011-03-29 12:43:46', '2011-03-29 12:43:51'),
(3, 'HVO-Querido', '2011-03-29 12:43:46', '2011-03-29 12:43:51'),
(4, 'Arkin', '2011-03-29 12:43:46', '2011-03-29 12:43:51'),
(5, 'De Volksbond', '2011-03-29 12:43:46', '2011-03-29 12:43:51'),
(6, 'Leger des Heils', '2011-03-29 12:43:46', '2011-03-29 12:43:51'),
(7, 'GGD', '2011-03-29 12:43:46', '2011-03-29 12:43:51');

-- --------------------------------------------------------


--
-- Dumping data for table `categorieen`
--

TRUNCATE TABLE `categorieen`;

INSERT INTO `categorieen` (`id`, `naam`, `created`, `modified`) VALUES
(1, 'Afspraak', NULL, NULL),
(2, 'Bellen', NULL, NULL),
(3, 'Nieuws', NULL, NULL),
(4, 'Overig', NULL, NULL);


-- --------------------------------------------------------

--
-- Dumping data for table `inkomens`
--

TRUNCATE TABLE `inkomens`;

INSERT INTO `inkomens` (`id`, `naam`, `datum_van`, `datum_tot`, `created`, `modified`) VALUES
(1, 'Loon', '2010-01-06', NULL, NULL, NULL),
(2, 'Uitkering DWI', '2010-01-06', NULL, NULL, NULL),
(3, 'Uitkering UWV', '2010-01-06', NULL, NULL, NULL),
(4, 'Uitkering Wajong', '2010-01-01', NULL, NULL, NULL),
(5, 'Uitkering WAO', '2010-01-06', NULL, NULL, NULL),
(6, 'Uitkering overig', '2010-01-06', NULL, NULL, NULL),
(7, 'Onbekend', '2010-01-06', NULL, NULL, NULL);

-- --------------------------------------------------------



-- --------------------------------------------------------

--
-- Dumping data for table `instanties`
--

TRUNCATE TABLE `instanties`;

INSERT INTO `instanties` (`id`, `naam`, `datum_van`, `datum_tot`, `created`, `modified`) VALUES
(1, 'Geen', '2010-01-13', '0000-00-00', NULL, NULL),
(2, 'GGD', '2010-01-13', '0000-00-00', NULL, NULL),
(3, 'Psychiatrie', '2010-01-13', '0000-00-00', NULL, NULL),
(4, 'Reclassering', '2010-01-01', '0000-00-00', NULL, NULL),
(5, 'Verslavingszorg', '2010-01-13', '0000-00-00', NULL, NULL),
(6, 'Overig', '2010-01-13', '0000-00-00', NULL, NULL),
(7, 'Onbekend', '2010-01-13', '0000-00-00', NULL, NULL);

-- --------------------------------------------------------


-- --------------------------------------------------------

--
-- Dumping data for table `inventarisaties`
--
TRUNCATE TABLE `inventarisaties`;

INSERT INTO `inventarisaties` (`id`, `order`, `parent_id`, `actief`, `type`, `titel`, `actie`, `startdatum`, `einddatum`, `lft`, `rght`, `depth`) VALUES
(35, 1, NULL, 1, NULL, 'Wonen', '', '0000-00-00', NULL, 1, 26, 0),
(36, 2, NULL, 1, NULL, 'Dagbesteding', '', '0000-00-00', NULL, 37, 64, 0),
(37, 3, NULL, 1, NULL, 'Inkomen', '', '0000-00-00', NULL, 65, 76, 0),
(38, 4, NULL, 1, NULL, 'Schulden', '', '0000-00-00', NULL, 77, 84, 0),
(39, 5, NULL, 1, NULL, 'Verslaving', '', '0000-00-00', NULL, 85, 96, 0),
(40, 6, NULL, 1, NULL, 'Psychiatrie', '', '0000-00-00', NULL, 97, 104, 0),
(41, 7, NULL, 1, NULL, 'Repatrieringswens', '', '0000-00-00', NULL, 105, 116, 0),
(42, 8, NULL, 1, NULL, 'Sociaal netwerk', '', '0000-00-00', NULL, 117, 124, 0),
(43, 9, NULL, 1, NULL, 'Psychosociaal', '', '0000-00-00', NULL, 125, 136, 0),
(44, 10, NULL, 1, NULL, 'Medische zorg', '', '0000-00-00', NULL, 137, 144, 0),
(45, 11, NULL, 1, NULL, 'Prostitutie', '', '0000-00-00', NULL, 145, 156, 0),
(46, 12, NULL, 1, NULL, 'Justitie', '', '0000-00-00', NULL, 157, 164, 0),
(47, 13, NULL, 1, NULL, 'Lopend hulpverleningscontact ', 'D', '0000-00-00', NULL, 165, 172, 0),
(48, 1, 35, 1, NULL, 'Dakloos', 'N', '0000-00-00', NULL, 4, 17, 1),
(49, 2, NULL, 1, NULL, 'Eigen netwerk', 'N', '0000-00-00', NULL, 27, 36, 0),
(50, 3, 35, 1, NULL, 'Eigen woning', 'N', '0000-00-00', NULL, 18, 23, 1),
(51, 4, 35, 1, NULL, 'Pension etc', 'S', '0000-00-00', NULL, 24, 25, 1),
(52, 1, 48, 1, NULL, 'Rechthebbend', 'N', '0000-00-00', NULL, 5, 10, 2),
(53, 2, 48, 1, NULL, 'Niet rechthebbend', 'N', '0000-00-00', NULL, 11, 16, 2),
(54, 1, 52, 1, NULL, 'Hulpvraag', 'Doorverwijzer', '0000-00-00', NULL, 6, 7, 3),
(55, 2, 52, 1, NULL, 'Geen hulpvraag', 'S', '0000-00-00', NULL, 8, 9, 3),
(56, 1, 53, 1, NULL, 'Hulpvraag', 'Doorverwijzer', '0000-00-00', NULL, 12, 13, 3),
(57, 2, 53, 1, NULL, 'Geen hulpvraag', 'S', '0000-00-00', NULL, 14, 15, 3),
(58, 1, 49, 1, NULL, 'Rechthebbend', 'N', '0000-00-00', NULL, 28, 33, 1),
(59, 2, 49, 1, NULL, 'Niet rechthebbend', 'Doorverwijzer', '0000-00-00', NULL, 34, 35, 1),
(60, 1, 58, 1, NULL, 'Hulpvraag', 'Doorverwijzer', '0000-00-00', NULL, 29, 30, 2),
(61, 2, 58, 1, NULL, 'Geen hulpvraag', 'S', '0000-00-00', NULL, 31, 32, 2),
(62, 1, 50, 1, NULL, 'Dreigende uithuiszetting', 'Doorverwijzer', '0000-00-00', NULL, 19, 20, 2),
(63, 2, 50, 1, NULL, 'Geen dreigende uithuiszetting', 'Doorverwijzer', '0000-00-00', NULL, 21, 22, 2),
(64, 1, 44, 1, NULL, 'Noodzakelijk', 'Doorverwijzer', '0000-00-00', NULL, 140, 141, 1),
(65, 2, 44, 1, NULL, 'Niet noodzakelijk', 'S', '0000-00-00', NULL, 142, 143, 1),
(66, 1, 45, 1, NULL, 'Ja', 'N', '0000-00-00', NULL, 148, 153, 1),
(67, 2, 45, 1, NULL, 'Nee', 'S', '0000-00-00', NULL, 154, 155, 1),
(68, 1, 66, 1, NULL, 'Hulpvraag', 'Doorverwijzer', '0000-00-00', NULL, 149, 150, 2),
(69, 2, 66, 1, NULL, 'Geen hulpvraag', 'S', '0000-00-00', NULL, 151, 152, 2),
(70, 1, 46, 1, NULL, 'Ja', 'Doorverwijzer', '0000-00-00', NULL, 160, 161, 1),
(71, 2, 46, 1, NULL, 'Nee', 'S', '0000-00-00', NULL, 162, 163, 1),
(72, 1, 42, 1, NULL, 'Hulpvraag', 'Doorverwijzer', '0000-00-00', NULL, 120, 121, 1),
(73, 2, 42, 1, NULL, 'Geen hulpvraag', 'S', '0000-00-00', NULL, 122, 123, 1),
(74, 1, 76, 1, NULL, 'Duidelijk', 'Doorverwijzer', '0000-00-00', NULL, 129, 130, 2),
(75, 2, 76, 1, NULL, '(Nog) niet duidelijk', 'Doorverwijzer', '0000-00-00', NULL, 131, 132, 2),
(76, 1, 43, 1, NULL, 'Hulpvraag', 'N', '0000-00-00', NULL, 128, 133, 1),
(77, 2, 43, 1, NULL, 'Geen hulpvraag', 'S', '0000-00-00', NULL, 134, 135, 1),
(78, 1, 41, 1, NULL, 'Ja, binnen Nederland', 'D', '0000-00-00', NULL, 108, 109, 1),
(79, 2, 41, 1, NULL, 'Ja, binnen EU', 'Doorverwijzer', '0000-00-00', NULL, 110, 111, 1),
(80, 3, 41, 1, NULL, 'Ja, buiten EU', 'Doorverwijzer', '0000-00-00', NULL, 112, 113, 1),
(81, 4, 41, 1, NULL, 'Nee', 'S', '0000-00-00', NULL, 114, 115, 1),
(82, 2, 40, 1, NULL, 'Niet van toepassing', 'S', '0000-00-00', NULL, 102, 103, 1),
(83, 1, 40, 1, NULL, 'Vermoeden', 'Doorverwijzer', '0000-00-00', NULL, 100, 101, 1),
(84, 1, 39, 1, NULL, 'Ja', 'N', '0000-00-00', NULL, 88, 93, 1),
(85, 2, 39, 1, NULL, 'Nee', 'S', '0000-00-00', NULL, 94, 95, 1),
(86, 2, 84, 1, NULL, 'Hulpvraag', 'Doorverwijzer', '0000-00-00', NULL, 91, 92, 2),
(87, 1, 84, 1, NULL, 'Geen hulpvraag', 'S', '0000-00-00', NULL, 89, 90, 2),
(88, 1, 37, 1, NULL, 'Ja', 'S', '0000-00-00', NULL, 68, 69, 1),
(89, 2, 37, 1, NULL, 'Nee', 'N', '0000-00-00', NULL, 70, 75, 1),
(90, 2, 89, 1, NULL, 'Rechthebbend', 'Doorverwijzer', '0000-00-00', NULL, 73, 74, 2),
(91, 1, 89, 1, NULL, 'Niet rechthebbend', 'S', '0000-00-00', NULL, 71, 72, 2),
(92, 1, 38, 1, NULL, 'Ja', 'Doorverwijzer', '0000-00-00', NULL, 80, 81, 1),
(93, 2, 38, 1, NULL, 'Nee', 'S', '0000-00-00', NULL, 82, 83, 1),
(94, 2, 36, 1, NULL, 'Wel', 'N', '0000-00-00', NULL, 50, 63, 1),
(95, 1, 36, 1, NULL, 'Niet', 'N', '0000-00-00', NULL, 40, 49, 1),
(96, 2, 95, 1, NULL, 'Hulpvraag', 'N', '0000-00-00', NULL, 43, 48, 2),
(97, 1, 95, 1, NULL, 'Geen hulpvraag', 'S', '0000-00-00', NULL, 41, 42, 2),
(98, 1, 96, 1, NULL, 'Rechthebbend', 'Doorverwijzer', '0000-00-00', NULL, 44, 45, 3),
(99, 2, 96, 1, NULL, 'Niet rechthebbend', 'Doorverwijzer', '0000-00-00', NULL, 46, 47, 3),
(100, 1, 94, 1, NULL, 'Via uitkerende instantie', 'S', '0000-00-00', NULL, 51, 52, 2),
(102, 2, 94, 1, NULL, 'Niet via uitkerende instantie', 'N', '0000-00-00', NULL, 53, 62, 2),
(103, 1, 102, 1, NULL, 'Recht op uitkering', 'N', '0000-00-00', NULL, 54, 59, 3),
(104, 2, 102, 1, NULL, 'Geen recht op uitkering', 'S', '0000-00-00', NULL, 60, 61, 3),
(105, 1, 103, 1, NULL, 'Heeft uitkering', 'Doorverwijzer', '0000-00-00', NULL, 55, 56, 4),
(106, 2, 103, 1, NULL, 'Heeft geen uitkering', 'Doorverwijzer', '0000-00-00', NULL, 57, 58, 4),
(107, 1, 47, 1, NULL, 'Ja', 'Trajecthouder', '0000-00-00', NULL, 168, 169, 1),
(108, 2, 47, 1, NULL, 'Nee', 'S', '0000-00-00', NULL, 170, 171, 1),
(111, 0, 36, 1, NULL, 'Niets te melden', 'S', '0000-00-00', NULL, 38, 39, 1),
(112, 0, 37, 1, NULL, 'Niets te melden', 'S', '0000-00-00', NULL, 66, 67, 1),
(113, 0, 38, 1, NULL, 'Niets te melden', 'S', '0000-00-00', NULL, 78, 79, 1),
(114, 0, 39, 1, NULL, 'Niets te melden', 'S', '0000-00-00', NULL, 86, 87, 1),
(115, 0, 40, 1, NULL, 'Niets te melden', 'S', '0000-00-00', NULL, 98, 99, 1),
(116, 0, 41, 1, NULL, 'Niets te melden', 'S', '0000-00-00', NULL, 106, 107, 1),
(117, 0, 42, 1, NULL, 'Niets te melden', 'S', '0000-00-00', NULL, 118, 119, 1),
(118, 0, 43, 1, NULL, 'Niets te melden', 'S', '0000-00-00', NULL, 126, 127, 1),
(119, 0, 44, 1, NULL, 'Niets te melden', 'S', '0000-00-00', NULL, 138, 139, 1),
(120, 0, 35, 1, NULL, 'Niets te melden', 'S', '0000-00-00', NULL, 2, 3, 1),
(121, 0, 45, 1, NULL, 'Niets te melden', 'S', '0000-00-00', NULL, 146, 147, 1),
(122, 0, 46, 1, NULL, 'Niets te melden', 'S', '0000-00-00', NULL, 158, 159, 1),
(123, 0, 47, 1, NULL, 'Niets te melden', 'S', '0000-00-00', NULL, 166, 167, 1),
(124, 20, NULL, 1, NULL, 'AWBZ indicatie', 'D', '2010-12-13', NULL, 173, 178, 0),
(125, 1, 124, 1, NULL, 'Ja', 'S', '2010-12-13', NULL, 174, 175, 1),
(126, 2, 124, 1, NULL, 'Nee', 'S', '2010-12-13', NULL, 176, 177, 1);


-- --------------------------------------------------------

--
-- Dumping data for table `legitimaties`
--

TRUNCATE TABLE `legitimaties`;

INSERT INTO `legitimaties` (`id`, `naam`, `datum_van`, `datum_tot`, `created`, `modified`) VALUES
(1, 'Geen', '2010-01-13', NULL, NULL, NULL),
(2, 'ID-kaart', '2010-01-13', NULL, NULL, NULL),
(3, 'Nederlands rijbewijs', '2010-01-13', NULL, NULL, NULL),
(4, 'Paspoort', '2010-01-13', NULL, NULL, NULL),
(5, 'Verblijfsdocument', '2010-01-01', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Dumping data for table `locaties`
--

TRUNCATE TABLE `locaties`;

INSERT INTO `locaties` (`id`, `naam`, `gebruikersruimte`, `nachtopvang`, `maatschappelijkwerk`, `datum_van`, `datum_tot`, `created`, `modified`) VALUES
(1, 'Blaka Watra', 0, 0, 0, '2010-02-18', '0000-00-00', NULL, NULL),
(2, 'Princehof', 1, 0, 0, '2010-02-18', '0000-00-00', NULL, NULL),
(5, 'AMOC', 0, 0, 0, '2010-04-29', '0000-00-00', NULL, NULL),
(9, 'De Eik', 0, 0, 0, '2010-04-29', '0000-00-00', NULL, NULL),
(10, 'De Kloof', 0, 0, 0, '2010-04-29', '0000-00-00', NULL, NULL),
(11, 'Makom', 0, 0, 0, '2010-04-29', '0000-00-00', NULL, NULL),
(12, 'Nachtopvang De Regenboog Groep', 0, 1, 0, '2010-04-29', '0000-00-00', NULL, NULL),
(13, 'Ondro Bong', 0, 0, 0, '2010-04-29', '0000-00-00', NULL, NULL),
(14, 'Oud West', 0, 0, 0, '2010-04-29', '0000-00-00', NULL, NULL),
(15, 'De Spreekbuis', 0, 0, 0, '2010-04-29', '0000-00-00', NULL, NULL),
(16, 'Tabe Rienks Huis', 0, 0, 0, '2010-04-29', '0000-00-00', NULL, NULL),
(17, 'Vrouwen Nacht Opvang', 0, 1, 0, '2010-04-29', '0000-00-00', NULL, NULL),
(18, 'Westerpark', 0, 0, 0, '2010-04-29', '0000-00-00', NULL, NULL),
(19, 'Droogbak', 0, 0, 0, '2011-11-08', '0000-00-00', NULL, NULL),
(20, 'Valentijn', 0, 0, 0, '2011-11-08', '0000-00-00', NULL, NULL),
(21, 'Blaka Watra Gebruikersruimte', 1, 0, 0, '2011-11-08', '0000-00-00', NULL, NULL),
(22, 'Amoc Gebruikersruimte', 1, 0, 0, '2011-11-08', '0000-00-00', NULL, NULL),
(23, 'Noorderpark', 0, 0, 0, '2011-11-08', '0000-00-00', NULL, NULL),
(24, 'Derde Schinkel', 0, 0, 0, '2014-09-16', '0000-00-00', NULL, NULL),
(25, 'Politie', 0, 0, 1, '0000-00-00', '0000-00-00', NULL, NULL),
(26, 'Penitentiaire Inrichting', 0, 0, 1, '0000-00-00', '0000-00-00', NULL, NULL),
(27, 'Rederij Kees', 0, 0, 1, '0000-00-00', '0000-00-00', NULL, NULL),
(28, 'Verbergen locaties', 0, 0, 1, '0000-00-00', '0000-00-00', NULL, NULL),
(29, 'Westerpark', 0, 0, 1, '0000-00-00', '0000-00-00', NULL, NULL),
(30, 'De Eik', 0, 0, 1, '0000-00-00', '0000-00-00', NULL, NULL);


-- --------------------------------------------------------

--
-- Dumping data for table `redenen`
--
TRUNCATE TABLE `redenen`;

INSERT INTO `redenen` (`id`, `naam`, `created`, `modified`) VALUES
(1, 'Dealen', NULL, NULL),
(2, 'Drinken', NULL, NULL),
(3, 'Drugs gebruiken', NULL, NULL),
(4, 'Stelen', NULL, NULL),
(5, 'Fysieke agressie', NULL, NULL),
(6, 'Verbale agressie', NULL, NULL),
(7, 'Overlast omgeving locatie', NULL, NULL),
(100, 'Overig', NULL, NULL);

--
-- Dumping data for table `verblijfstatussen`
--
TRUNCATE TABLE `verblijfstatussen`;

INSERT INTO `verblijfstatussen` (`id`, `naam`, `datum_van`, `datum_tot`, `created`, `modified`) VALUES
(1, 'Legaal', '2010-01-13', NULL, NULL, NULL),
(2, 'Werkvergunning', '2010-01-13', NULL, NULL, NULL),
(3, 'Niet rechthebbend (uit Nederland, behalve Amsterdam)', '2010-01-13', NULL, NULL, '2011-03-11 10:24:17'),
(4, 'Onbekend', '2010-01-13', NULL, NULL, NULL),
(6, 'Illegaal (uit buiten Europa)', '0000-00-00', NULL, '2011-03-11 10:24:17', '2011-03-11 10:24:17'),
(7, 'Niet rechthebbend (uit EU, behalve Nederland)', '0000-00-00', NULL, '2011-03-11 10:24:17', '2011-03-11 10:24:17');

-- --------------------------------------------------------

--
-- Dumping data for table `verslavingen`
--
TRUNCATE TABLE `verslavingen`;

INSERT INTO `verslavingen` (`id`, `naam`, `datum_van`, `datum_tot`, `created`, `modified`) VALUES
(1, 'Alcohol', '2010-01-06', NULL, NULL, NULL),
(2, 'Amfetamine', '2010-01-06', NULL, NULL, NULL),
(3, 'Cannabis', '2010-01-06', NULL, NULL, NULL),
(4, 'Cocaine', '2010-01-06', NULL, NULL, NULL),
(5, 'Ecstasy', '2010-01-06', NULL, NULL, NULL),
(6, 'Gokken', '2010-01-06', NULL, NULL, NULL),
(7, 'Heroine', '2010-01-06', NULL, NULL, NULL),
(8, 'Medicijnen', '2010-01-06', NULL, NULL, NULL),
(9, 'Methadon', '2010-01-06', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Dumping data for table `verslavingsfrequenties`
--
TRUNCATE TABLE  `verslavingsfrequenties`;

INSERT INTO `verslavingsfrequenties` (`id`, `naam`, `datum_van`, `datum_tot`, `created`, `modified`) VALUES
(1, 'Niet van toepassing', '2010-01-01', NULL, NULL, NULL),
(2, 'Meerdere keren per dag', '2010-01-13', NULL, NULL, NULL),
(3, 'Dagelijks', '2010-01-13', NULL, NULL, NULL),
(4, 'Meerdere keren per week', '2010-01-13', NULL, NULL, NULL),
(5, 'Wekelijks', '2010-01-13', NULL, NULL, NULL),
(6, 'Onregelmatig', '2010-01-13', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Dumping data for table `verslavingsgebruikswijzen`
--

-- CREATE TABLE IF NOT EXISTS `verslavingsgebruikswijzen` (
--  `id` int(11) NOT NULL AUTO_INCREMENT,
--  `naam` varchar(255) NOT NULL,
--  `datum_van` date NOT NULL,
-- `datum_tot` date DEFAULT NULL,
--  `created` datetime DEFAULT NULL,
--  `modified` datetime DEFAULT NULL,
--  PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 10240 kB' AUTO_INCREMENT=8 ;

TRUNCATE TABLE `verslavingsgebruikswijzen`;

INSERT INTO `verslavingsgebruikswijzen` (`id`, `naam`, `datum_van`, `datum_tot`, `created`, `modified`) VALUES
(1, 'Basen', '2010-01-01', NULL, NULL, NULL),
(2, 'Chinezen', '2010-01-01', NULL, NULL, NULL),
(3, 'Drinken', '2010-01-01', NULL, NULL, NULL),
(4, 'Roken', '2010-01-01', NULL, NULL, NULL),
(5, 'Slikken', '2010-01-01', NULL, NULL, NULL),
(6, 'Snuiven', '2010-01-01', NULL, NULL, NULL),
(7, 'Spuiten', '2010-01-01', NULL, NULL, NULL);

--
-- Dumping data for table `verslavingsperiodes`
--
TRUNCATE TABLE `verslavingsperiodes`;

INSERT INTO `verslavingsperiodes` (`id`, `naam`, `datum_van`, `datum_tot`, `created`, `modified`) VALUES
(1, 'Niet van toepassing', '2010-01-01', NULL, NULL, NULL),
(2, 'Minder dan 3 maanden', '2010-01-13', NULL, NULL, NULL),
(3, '3 tot 6 maanden', '2010-01-13', NULL, NULL, NULL),
(4, '6 tot 12 maanden', '2010-01-13', NULL, NULL, NULL),
(5, '1 tot 2 jaar', '2010-01-13', NULL, NULL, NULL),
(6, '2 tot 5 jaar', '2010-01-13', NULL, NULL, NULL),
(7, '5 tot 10 jaar', '2010-01-13', NULL, NULL, NULL),
(8, 'Meer dan 10 jaar', '2010-01-13', NULL, NULL, NULL),
(9, 'Onbekend', '2010-01-13', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Dumping data for table `woonsituaties`
--
TRUNCATE TABLE `woonsituaties`;

INSERT INTO `woonsituaties` (`id`, `naam`, `datum_van`, `datum_tot`, `created`, `modified`) VALUES
(10, 'Eigen huis (huur/koop)', NULL, NULL, NULL, NULL),
(11, 'Pension/kosthuis', NULL, NULL, NULL, NULL),
(12, 'Ouderlijk huis', '2010-01-06', NULL, NULL, NULL),
(13, 'Op kamers', '2010-01-06', NULL, NULL, NULL),
(14, 'Familie/kennissen/relatie', '2010-01-06', NULL, NULL, NULL),
(15, 'Penitentiaire inrichting', '2010-01-06', NULL, NULL, NULL),
(16, 'Klinische voorziening', '2010-01-06', NULL, NULL, NULL),
(17, 'Andersoortig instituut', '2010-01-06', NULL, NULL, NULL),
(19, 'Tehuis voor vak- en thuislozen/sociaal pension', '2010-01-06', NULL, NULL, NULL),
(97, 'Op straat/zwervend', NULL, NULL, NULL, NULL),
(98, 'Anderszins', NULL, NULL, NULL, NULL),
(99, 'Onbekend', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Dumping data for table `bedrijfsectoren`
--
TRUNCATE TABLE `bedrijfsectoren`;

INSERT INTO `bedrijfsectoren` (`id`, `name`) VALUES
(1, 'Horeca'),
(2, 'Creatief / Productie'),
(3, 'Fietsen / Boten'),
(4, 'Receptie / Administratie / Verkoop'),
(5, 'Meewerkend bezoeker / Vrijwilliger'),
(6, 'Klussen'),
(7, 'Schoonmaak'),
(8, 'Groenvoorziening'),
(9, 'Ontspanning / Sport'),
(10, 'Vervoer / Reis'),
(11, 'Diverse');

-- --------------------------------------------------------

--
-- Dumping data for table `bedrijfitems`
--

TRUNCATE TABLE `bedrijfitems`;

INSERT INTO `bedrijfitems` (`id`, `name`, `bedrijfsector_id`) VALUES
(1, 'Diaconie Diner voor dames (RGB)', 1),
(2, 'Kok (RGB)', 1),
(3, 'Banketbakkerij (RGB)', 1),
(4, 'Buurtboerderij (RGB)', 1),
(5, 'Restaurant Linnaeushof (HVO)', 1),
(6, 'Kantine (HVO)', 1),
(7, 'De Eetkamer (HVO)', 1),
(8, 'Medewerker Restaurant de Steigerstek (HVO)', 1),
(9, 'Appeltaartimperium (ROADS)', 1),
(10, 'Kantineproject (ROADS)', 1),
(11, 'Lunchcafé Freud (ROADS)', 1),
(12, 'Restaurant Freud (ROADS)', 1),
(13, 'Keuken Clubhuis (WHV)', 1),
(14, 'Koken (WHV)', 1),
(15, 'Lunchroom (WHV)', 1),
(16, 'Eetwinkel De Buren (WHV)', 1),
(17, 'Gastheer/Vrow (DE PRAEL)', 1),
(18, 'De Gravin (SNWA)', 1),
(19, 'Rotonde lunch (ARKIN)', 1),
(20, 'Tuinhuis koken (ARKIN)', 1),
(21, 'Werkproject Barmedewerker (ARKIN)', 1),
(22, 'Atelier Kunstsuite (RGB)', 2),
(23, 'Medewerk(st)er Creade Atelier en Winkel Kado Artikelen (RGB)', 2),
(24, 'Theatergroep Diaconie (RGB)', 2),
(25, 'Schrijfclub Kantlijn Diaconie (RGB)', 2),
(26, 'Productiewerk (RGB)', 2),
(27, 'Desycle werkplaats (RGB)', 2),
(28, 'Sieradenmakerij (RGB)', 2),
(29, 'Kaarsenmakerij (RGB)', 2),
(30, 'Grafisch Werkcentrum Oost (RGB)', 2),
(31, 'De Klei-academie (RGB)', 2),
(32, 'Productiemedewerker brouwerij (DE PRAEL)', 2),
(33, 'Houtwerk (SWNA)', 2),
(34, 'Boekbindwinkel (SNWA)', 2),
(35, 'ZigZag (SNWA)', 2),
(38, 'StenGoed (SNWA)', 2),
(39, 'Repro (SNWA)', 2),
(40, 'Eigenwijs (ROADS)', 2),
(41, 'Fijnhout Drukkerij (ROADS)', 2),
(42, '(ROADS) on Wheels (ROADS)', 2),
(43, 'De Houtstek (ROADS)', 2),
(44, 'Grafisch Buro (ROADS)', 2),
(45, 'Basic Looks (HVO)', 2),
(46, 'Basic Wheels (HVO)', 2),
(47, 'Net Cliënten (HVO)', 2),
(48, 'Uitgeverij Tobi Vroegh (HVO)', 2),
(49, 'The Rebels (HVO)', 2),
(50, 'Login Werkplaats (HVO)', 2),
(51, 'Muziekproject Zuid Oost (HVO)', 2),
(52, 'Mode met een Missie (HVO)', 2),
(53, 'Meubelproject (HVO)', 2),
(54, 'Bijrijder- / Magazijnmedewerker (HVO)', 2),
(55, 'Kustkop Schilderen (ARKIN)', 2),
(56, 'Rotonde Crea (ARKIN)', 2),
(57, 'Tuinhuis Crea (ARKIN)', 2),
(58, 'Vetter Kaarsen (ARKIN)', 2),
(59, 'Vetter Crea (ARKIN)', 2),
(60, 'Vetter Film (ARKIN)', 2),
(61, 'Vetter Hout (ARKIN)', 2),
(62, 'Fietsenmakerij (RGB)', 3),
(63, 'Fiets Oké (SNWA)', 3),
(64, 'Botenproject (HVO)', 3),
(65, 'Recycle (ROADS)', 3),
(66, 'Winkel- / Magazijnmedewerker (HVO)', 4),
(67, 'Receptie Dac Linneaushof (HVO)', 4),
(68, 'Administratiebedrijf (SNWA)', 4),
(69, 'Ondersteuner Administratie (DE PRAEL)', 4),
(70, 'Winkelmedewerker (DE PRAEL)', 4),
(71, 'Rondleider (DE PRAEL)', 4),
(72, 'Telefonist Warmline (WHV)', 4),
(73, 'Administratie (WHV)', 4),
(74, 'Balie (WHV)', 4),
(75, 'Leonatix (ROADS)', 4),
(76, 'Leonatique (ROADS)', 4),
(77, 'Baliemedewerker / Receptionist (ARKIN)', 4),
(78, 'Werkproject Administratiegroep (ARKIN)', 4),
(79, 'Tuinhuis verkopen (ARKIN)', 4),
(80, 'Meewerkend bezoeker (RGB)', 5),
(81, 'Voorman (RGB)', 5),
(82, 'Meer voor ons door ons (HVO)', 5),
(83, 'Dienstenwinkel Osdorp (HVO)', 5),
(84, 'Corvershof (RGB)', 6),
(85, 'Intern Klussenteam (RGB)', 6),
(86, 'Diensten bij woenen (HVO)', 6),
(87, 'Homeservice (WHV)', 6),
(88, 'Speeltuinonderhoud (ARKIN)', 6),
(89, 'Onderhoudsploeg Netwerk (ARKIN)', 6),
(90, 'Tuinhuis Onderhoud (ARKIN)', 6),
(91, 'Veeter facilitair (ARKIN)', 6),
(92, 'Schoonmaakploeg (RGB)', 7),
(93, 'Veegploeg (RGB)', 7),
(94, 'Wasserette (RGB)', 7),
(95, 'Basic Totaal (HVO)', 7),
(96, 'Wasserette (HVO)', 7),
(97, 'Meerbeheer (SNWA)', 8),
(98, 'Groene Dam (SNWA)', 8),
(99, 'Groene Vingers (ROADS)', 8),
(100, 'Basic Green (HVO)', 8),
(101, 'Buitenploeg en externe opdrachten (ARKIN)', 8),
(102, 'Diaconie Meditatie (RGB)', 9),
(103, 'Diaconie Straatklinkers (RGB)', 9),
(104, 'Diaconie Filosofie (RGB)', 9),
(105, 'Sport (ARKIN)', 9),
(106, 'Bakfietskoerier (RGB)', 10),
(107, 'Bijrijder (RGB)', 10),
(108, 'Magazijnbeheer en Transport (DE PRAEL)', 10),
(109, 'Fietskoerier (WHV)', 10),
(110, 'Cab4u (ROADS)', 10),
(111, 'Vervoerproject (HVO)', 10),
(112, 'Bezorgdienst Horeca (HVO)', 10),
(113, 'Magical Mistery Toer (HVO)', 10),
(114, 'Oriëtatietraject (RGB)', 11),
(115, 'Online Computer Reparatie en Winkel (ROADS)', 11),
(116, 'Actief Computer Centrum (HVO)', 11),
(117, 'Kunstkop Uitleen (ARKIN)', 11),
(118, 'Research Onderzoekbureau door Cliënten voor Cliënten (ARKIN)', 11);


-- --------------------------------------------------------

TRUNCATE TABLE `hi5_questions`;

INSERT INTO `hi5_questions` (`id`, `question`, `category`, `order`) VALUES
(1, 'Welke opleidingen heeft u in het verleden gevolgd?', 'Vaardigheden en in de persoon gelegen factoren', 1),
(2, 'Waar was u goed in en waar bent u nu goed in?', 'Vaardigheden en in de persoon gelegen factoren', 2),
(3, 'Kunt u iets vertellen over uw werkervaring/vrijwilligers werk', 'Vaardigheden en in de persoon gelegen factoren', 3),
(4, 'Als u iets nieuws moet leren, hoe doet u dat dan? (Voordoen, uitproberen, lezen, over nadenken)', 'Vaardigheden en in de persoon gelegen factoren', 4),
(5, 'Wat maakt werken leuk?', 'Sfeer en persoonlijkheid', 5),
(6, 'Waar kunt u wel en niet tegen?', 'Sfeer en persoonlijkheid', 6),
(7, 'Wat moet er echt in het werk aanwezig zijn om leuk en goed te kunnen werken?', 'Sfeer en persoonlijkheid', 7),
(8, 'Als u uzelf in een paar zinnen zou moeten omschrijven, wat zou u dan zeggen?', 'Sfeer en persoonlijkheid', 8),
(9, 'Wilt u iets vertellen over uw achtergrond?', 'Sociale achtergrond en netwerk', 9),
(10, 'Wie zit er in uw netwerk / met welke mensen heeft u contact?', 'Sociale achtergrond en netwerk', 10),
(11, 'In hoeverre is bovenstaande van invloed op uw dagelijks leven?', 'Sociale achtergrond en netwerk', 11),
(12, 'Wat wilt u de aankomende periode graag leren?', 'Leerdoelen', 12),
(13, 'Algemeen', 'Leerdoelen', 13),
(14, 'Regels en afspraken', 'Leerdoelen', 14),
(15, 'Functioneren op de werkvloer', 'Leerdoelen', 15),
(16, 'Omgaan met bezoekers (alleen inloop)', 'Leerdoelen', 16),
(17, 'Samenwerking met collega''s', 'Leerdoelen', 17),
(18, 'Omgang met de begeleiders', 'Leerdoelen', 18),
(19, 'Waar wil je dat de dagbesteding uiteindelijk toe leidt?', 'Toekomst', 19);

-- --------------------------------------------------------

TRUNCATE TABLE `hi5_answer_types`;

INSERT INTO `hi5_answer_types` (`id`, `answer_type`) VALUES
(1, 'checkbox'),
(2, 'open'),
(3, 'dropdown');


-- --------------------------------------------------------



TRUNCATE TABLE `hi5_answers`;

INSERT INTO `hi5_answers` (`id`, `answer`, `hi5_question_id`, `hi5_answer_type_id`) VALUES
(1, 'Lagere school', 1, 3),
(2, 'Middelbare school', 1, 3),
(3, 'LBO', 1, 3),
(4, 'MBO', 1, 3),
(5, 'HBO / Universiteit', 1, 3),
(6, '', 1, 2),
(7, 'Betaalde baan', 3, 1),
(8, 'Vrijwilligerswerk', 3, 1),
(9, 'Eerder traject (gesubsidieerd)', 3, 1),
(10, '0 tot 5 jaar', 3, 3),
(11, '6 tot 10 jaar', 3, 3),
(12, '11 jaar of langer', 3, 3),
(13, '', 3, 2),
(14, '', 2, 2),
(15, '', 4, 2),
(16, 'Iets te doen hebben overdag', 5, 1),
(17, 'Samenwerken met anderen', 5, 1),
(18, 'Iets maken', 5, 1),
(19, 'Ergens over nadenken', 5, 1),
(20, 'De dagdeelvergoeding', 5, 1),
(21, 'Mezelf nuttig voelen', 5, 1),
(22, 'Iets leren', 5, 1),
(23, 'Toekomstperspectief', 5, 1),
(24, 'Mezelf ontwikkelen', 5, 1),
(25, '', 6, 2),
(26, '', 7, 2),
(27, '', 8, 2),
(28, '', 9, 2),
(29, '', 10, 2),
(30, '', 11, 2),
(31, 'Erachter komen waar ik goed in ben', 13, 1),
(32, 'Erachter komen waar ik minder goed in ben', 13, 1),
(33, 'Erachter komen welke werkzaamheden het beste bij mij passen', 13, 1),
(34, '', 13, 2),
(35, 'Op tijd komen', 14, 1),
(36, 'Werken volgens rooster', 14, 1),
(37, 'Tijdig afmelden', 14, 1),
(38, 'Aan algemene regels houden', 14, 1),
(39, '', 14, 2),
(40, 'Zelfstandig taken uitvoeren', 15, 1),
(41, 'Inzet en motivatie tonen', 15, 1),
(42, 'Initiatief tonen', 15, 1),
(43, 'Verantwoordelijkheid dragen', 15, 1),
(44, 'Tevreden zijn en plezier hebben', 15, 1),
(45, '', 15, 2),
(46, 'Signaleren van problemen en overtredingen', 16, 1),
(47, 'Bezoekers aanspreken en corrigeren indien nodig', 16, 1),
(48, 'Inschakelen dagcoördinator indien nodig', 16, 1),
(49, '', 16, 2),
(50, 'Plezier beleven aan de omgang met collega''s', 17, 1),
(51, 'Leren van collega''s', 17, 1),
(52, 'Kunnen omgaan met onderlinge irritaties', 17, 1),
(53, 'Actieve deelname aan de nabespreking', 17, 1),
(54, 'Problemen bespreekbaar maken', 17, 1),
(55, '', 17, 2),
(56, 'Leiding kunnen ontvangen, zich laten sturen', 18, 1),
(57, 'Om kunnen gaan met kritiek', 18, 1),
(58, 'Leren van de begeleiders en collega´s', 18, 1),
(59, 'Zelf een gesprek aanvragen als daar een reden voor is', 18, 1),
(60, 'Kritiek op de werkbegeleiding formuleren', 18, 1),
(61, '', 18, 2),
(62, '', 19, 2);

-- --------------------------------------------------------

TRUNCATE TABLE `hi5_evaluatie_paragraphs`;

INSERT INTO `hi5_evaluatie_paragraphs` (`id`, `text`) VALUES
(1, 'Regels en afspraken'),
(2, 'Functioneren op de werkvloer'),
(3, 'Omgaan met bezoekers (alleen inloop)'),
(4, 'Samenwerking met collega''s'),
(5, 'Omgang met de begeleiders');

-- --------------------------------------------------------

TRUNCATE TABLE `hi5_evaluatie_questions`;

INSERT INTO `hi5_evaluatie_questions` (`id`, `hi5_evaluatie_paragraph_id`, `text`) VALUES
(1, 1, 'Op tijd komen'),
(2, 1, 'Werken volgens rooster'),
(3, 1, 'Tijdig afmelden'),
(4, 1, 'Zich aan algemene regels houden'),
(5, 2, 'Taken uitvoeren'),
(6, 2, 'Inzet en motivatie'),
(7, 2, 'Initiatief tonen'),
(8, 2, 'Verantwoordelijkheid dragen'),
(9, 2, 'Tevredenheid en plezier'),
(10, 3, 'Signaleren van problemen en overtredingen'),
(11, 3, 'Bezoekers aanspreken en corrigeren'),
(12, 3, 'Inschakelen dagcoördinator'),
(13, 4, 'Plezier beleven aan de omgang met collega''s'),
(14, 4, 'Leren van collega''s'),
(15, 4, 'Kunnen omgaan met onderlinge irritaties'),
(16, 4, 'Actieve deelname aan de nabespreking'),
(17, 4, 'Problemen bespreekbaar maken'),
(18, 5, 'Leiding ontvangen, zich laten sturen'),
(19, 5, 'Om kunnen gaan met kritiek'),
(20, 5, 'Leren van de begeleiders'),
(21, 5, 'Zelf een gesprek aanvragen als daar een reden voor is'),
(22, 5, 'Kritiek op de werkbegeleiding');


TRUNCATE TABLE contactsoorts;

INSERT INTO contactsoorts (id, text) VALUES
(1, 'Clientgebonden'),
(2, 'Telefonisch'),
(3, 'Face-to-face');

TRUNCATE TABLE `infobaliedoelgroepen`;

INSERT INTO `infobaliedoelgroepen` (`id`, `naam`) VALUES
(1, 'De Regenboog Groep'),
(2, 'Informatie en advies');

TRUNCATE TABLE locatie_tijden;
insert into locatie_tijden (id, locatie_id, dag_van_de_week, openingstijd, sluitingstijd) values
-- |  1 | Blaka Watra                    |                1 | 19:00:00 | 19:00:00 | 19:00:00 | 19:00:00  | 19:00:00 | 19:00:00 | 19:00:00 |
-- Blaka Watra: open 11.00u (inloop en gebr. ruimte) sluiting 19.00 uur (ma. t/m zon.)
(null, 1, 1, '10:30:00', '18:30:00'),
(null, 1, 2, '10:30:00', '18:30:00'),
(null, 1, 3, '10:30:00', '18:30:00'),
(null, 1, 4, '10:30:00', '18:30:00'),
(null, 1, 5, '10:30:00', '18:30:00'),
(null, 1, 6, '10:30:00', '18:30:00'),
(null, 1, 0, '10:30:00', '18:30:00'),

-- |  2 | Princehof                      |                0 | 19:00:00 | 19:00:00 | 19:00:00 | 19:00:00  | 19:00:00 | 19:00:00 | 19:00:00 |
-- Princehof 
-- Maandag t/m Zondag 10:00 - 18:00 
(null, 2, 1, '10:00:00', '18:00:00'),
(null, 2, 2, '10:00:00', '18:00:00'),
(null, 2, 3, '10:00:00', '18:00:00'),
(null, 2, 4, '10:00:00', '18:00:00'),
(null, 2, 5, '10:00:00', '18:00:00'),
(null, 2, 6, '10:00:00', '18:00:00'),
(null, 2, 0, '10:00:00', '18:00:00'),

-- |  5 | AMOC                           |                1 | 20:00:00 | 20:00:00 | 20:00:00 | 20:00:00  | 20:00:00 | 20:00:00 | 20:00:00 |
-- AMOC inloophuis (Teestube) open: 10.00u sluiting 18.00u van maandag tot en met vrijdag
-- AMOC gebruikersruimte open: 12.00u en sluiting 20.00u van maandag tot en met zondag
(null, 5, 0, '10:00:00', '20:00:00'),
(null, 5, 1, '10:00:00', '20:00:00'),
(null, 5, 2, '10:00:00', '20:00:00'),
(null, 5, 3, '10:00:00', '20:00:00'),
(null, 5, 4, '10:00:00', '20:00:00'),
(null, 5, 5, '10:00:00', '20:00:00'),
(null, 5, 6, '10:00:00', '20:00:00'),

-- |  9 | De Eik                         |                0 | 16:00:00 | 16:00:00 | 16:00:00 | 16:00:00  | 16:00:00 | 16:00:00 | 16:00:00 |
-- de Eik: open; open: 8.30u en sluiting 16.00 uur (ma. t/m zon.)
(null, 9, 0, '16:00:00', '16:00:01'),

-- | 10 | De Kloof                       |                0 | --:--:-- | 16:00:00 | 16:00:00 | 16:00:00  | 16:00:00 | 16:00:00 | --:--:-- |
-- Kloof: open: 10.00u en sluiting 16.00 uur (ma. t/m vrij.)
(null, 10, 1, '08:30:00', '15:00:00'),
(null, 10, 2, '08:30:00', '15:00:00'),
(null, 10, 3, '08:30:00', '15:00:00'),
(null, 10, 4, '08:30:00', '15:00:00'),
(null, 10, 5, '08:30:00', '15:00:00'),

-- | 11 | Makom                          |                0 | 16:00:00 | 20:00:00 | 16:00:00 | 20:00:00  | --:--:-- | 20:00:00 | 12:30:00 |
-- zondag: van 11.30 tot 16.00 uur
(null, 11, 0, '09:30:00',  '16:00:00'),
-- maandag: van 12.00 tot 20:30:00 uur
(null, 11, 1, '12:00:00', '20:30:00'),
-- dinsdag: van 12.00 tot 20.30 uur
(null, 11, 2, '12:00:00', '20:30:00'),
-- woensdag: van 12.00 tot 20:30 
(null, 11, 3, '12:00:00', '20:30:00'),
-- donderdag: closed
-- vrijdag: van 09.30 tot 20:30
(null, 11, 5, '09:30:00', '20:30:00'),
-- zaterdag: van 10.00 tot 12.30 uur
(null, 11, 6, '09:30:00', '13:00:00'),

-- | 12 | Nachtopvang De Regenboog Groep |                0 | 14:00:00 | 14:00:00 | 14:00:00 | 14:00:00  | 14:00:00 | 14:00:00 | 14:00:00 |
-- The day of the week refers to the closing time. If the location opens the
-- day before, we need to use negative numbers. In this case, -4 h means 20:00
-- on the previous day.
(null, 12, 0, '-04:00:00', '09:00:00'),
(null, 12, 1, '-04:00:00', '09:00:00'),
(null, 12, 2, '-04:00:00', '09:00:00'),
(null, 12, 3, '-04:00:00', '09:00:00'),
(null, 12, 4, '-04:00:00', '09:00:00'),
(null, 12, 5, '-04:00:00', '09:00:00'),
(null, 12, 6, '-04:00:00', '09:00:00'),

-- | 13 | Ondro Bong                     |                0 | --:--:-- | 18:00:00 | 18:00:00 | 18:00:00  | 18:00:00 | 18:00:00 | --:--:-- |
-- Ondro Bong: open : 11.00u en sluiting om 18.00 uur (ma. t/m vrij.)
(null, 13, 1, '11:00:00', '18:00:00'),
(null, 13, 2, '11:00:00', '18:00:00'),
(null, 13, 3, '11:00:00', '18:00:00'),
(null, 13, 4, '11:00:00', '18:00:00'),
(null, 13, 5, '11:00:00', '18:00:00'),


-- | 14 | Oud West                       |                0 | 16:00:00 | 16:00:00 | 16:00:00 | 16:00:00  | 16:00:00 | 16:00:00 | 16:00:00 |
-- Oud West: openingstijden: maandag tot en met zondag 09.00 - 12.00 en 13.00 -16.00 uur
-- Behalve op: dinsdag van 09.00 tot 12.00 en van 18.00 tot 21.00 uur,
(null, 14, 0, '08:00:00', '16:00:00'),
(null, 14, 1, '08:00:00', '16:00:00'),
(null, 14, 2, '08:00:00', '21:00:00'),
(null, 14, 3, '08:00:00', '16:00:00'),
(null, 14, 4, '08:00:00', '16:00:00'),
(null, 14, 5, '08:00:00', '16:00:00'),
(null, 14, 6, '08:00:00', '16:00:00'),

-- | 15 | De Spreekbuis                  |                0 | 19:00:00 | 19:00:00 | 19:00:00 | 19:00:00  | 19:00:00 | 19:00:00 | 19:00:00 |
-- Spreekbuis:  open om 8.00u en sluiting om 13.00 uur (ma. t/m vrij.)
(null, 15, 0, '08:00:00', '13:00:00'),
(null, 15, 1, '08:00:00', '13:00:00'),
(null, 15, 2, '08:00:00', '13:00:00'),
(null, 15, 3, '08:00:00', '13:00:00'),
(null, 15, 4, '08:00:00', '13:00:00'),
(null, 15, 5, '08:00:00', '13:00:00'),
(null, 15, 6, '08:00:00', '13:00:00'),

-- | 16 | Tabe Rienks Huis               |                0 | --:--:-- | 16:00:00 | 16:00:00 | 16:00:00  | 16:00:00 | 16:00:00 | --:--:-- |
-- Tabe Rienks Huis 
-- Maandag t/m Vrijdag 10:00 - 16:30 
(null, 16, 1, '10:00:00', '16:30:00'),
(null, 16, 2, '10:00:00', '16:30:00'),
(null, 16, 3, '10:00:00', '16:30:00'),
(null, 16, 4, '10:00:00', '16:30:00'),
(null, 16, 5, '10:00:00', '16:30:00'),
(null, 16, 6, '10:00:00', '16:30:00'),

-- | 17 | Vrouwen Nacht Opvang           |                0 | --:--:-- | 23:59:59 | --:--:-- | 23:59:59  | --:--:-- | 23:59:59 | --:--:-- |
-- Maandag t/m Zaterdag 18:30 - 23:00
(null, 17, 1, '18:30:00', '23:00:00'),
(null, 17, 2, '18:30:00', '23:00:00'),
(null, 17, 3, '18:30:00', '23:00:00'),
(null, 17, 4, '18:30:00', '23:00:00'),
(null, 17, 5, '18:30:00', '23:00:00'),
(null, 17, 6, '18:30:00', '23:00:00'),

-- | 18 | Westerpark                     |                0 | 18:00:00 | 18:00:00 | 18:00:00 | 18:00:00  | 18:00:00 | 18:00:00 | 18:00:00 |
-- Westerpark : open om 12.00u en sluiting om 18.00u (maandag tot en met zondag)
(null, 18, 0, '08:30:00', '18:15:00'),
(null, 18, 1, '08:30:00', '18:15:00'),
(null, 18, 2, '08:30:00', '18:15:00'),
(null, 18, 3, '08:30:00', '18:15:00'),
(null, 18, 4, '08:30:00', '18:15:00'),
(null, 18, 5, '08:30:00', '18:15:00'),
(null, 18, 6, '08:30:00', '18:15:00'),

-- Droogbak
-- * Droogbak opens daily from 11 to 19.
(null, 19, 0, '11:00:00', '19:00:00'),
(null, 19, 1, '11:00:00', '19:00:00'),
(null, 19, 2, '11:00:00', '19:00:00'),
(null, 19, 3, '11:00:00', '19:00:00'),
(null, 19, 4, '11:00:00', '19:00:00'),
(null, 19, 5, '11:00:00', '19:00:00'),
(null, 19, 6, '11:00:00', '19:00:00'),

-- |  20 | Valentijn
(null, 20, 1, '09:00:00', '16:00:00'),
(null, 20, 2, '09:00:00', '16:00:00'),
(null, 20, 3, '09:00:00', '16:00:00'),
(null, 20, 4, '09:00:00', '16:00:00'),
(null, 20, 5, '09:00:00', '16:00:00'),
(null, 20, 6, '09:00:00', '16:00:00'),

-- |  21 | Blaka Watra gebruikersruimte  
(null, 21, 0, '11:00:00', '19:00:00'),
(null, 21, 1, '11:00:00', '19:00:00'),
(null, 21, 2, '11:00:00', '19:00:00'),
(null, 21, 3, '11:00:00', '19:00:00'),
(null, 21, 4, '11:00:00', '19:00:00'),
(null, 21, 5, '11:00:00', '19:00:00'),
(null, 21, 6, '11:00:00', '19:00:00'),

-- |  22 | AMOC gebruikersruimte 
(null, 22, 0, '10:00:00', '20:00:00'),
(null, 22, 1, '10:00:00', '20:00:00'),
(null, 22, 2, '10:00:00', '20:00:00'),
(null, 22, 3, '10:00:00', '20:00:00'),
(null, 22, 4, '10:00:00', '20:00:00'),
(null, 22, 5, '10:00:00', '20:00:00'),
(null, 22, 6, '10:00:00', '20:00:00'),

-- |  23 | Noorderpark
(null, 23, 0, '09:00:00', '21:00:00'),
(null, 23, 1, '09:00:00', '21:00:00'),
(null, 23, 2, '09:00:00', '21:00:00'),
(null, 23, 3, '09:00:00', '21:00:00'),
(null, 23, 4, '09:00:00', '21:00:00'),
(null, 23, 5, '09:00:00', '21:00:00'),
(null, 23, 6, '09:00:00', '21:00:00')

;
