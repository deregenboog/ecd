-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: database
-- Generation Time: Dec 07, 2016 at 10:35 AM
-- Server version: 5.5.53-0+deb8u1
-- PHP Version: 5.6.24-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ecd`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` varchar(36) NOT NULL,
  `model` varchar(255) NOT NULL,
  `foreign_key` varchar(36) NOT NULL,
  `dirname` varchar(255) DEFAULT NULL,
  `basename` varchar(255) NOT NULL,
  `checksum` varchar(255) NOT NULL,
  `group` varchar(255) DEFAULT NULL,
  `alternative` varchar(50) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `awbz_hoofdaannemers`
--

CREATE TABLE `awbz_hoofdaannemers` (
`id` int(11) NOT NULL,
  `klant_id` int(11) DEFAULT NULL,
  `begindatum` date NOT NULL,
  `einddatum` date DEFAULT NULL,
  `hoofdaannemer_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `awbz_indicaties`
--

CREATE TABLE `awbz_indicaties` (
`id` int(11) NOT NULL,
  `klant_id` int(11) DEFAULT NULL,
  `begindatum` date NOT NULL,
  `einddatum` date DEFAULT NULL,
  `begeleiding_per_week` int(5) DEFAULT NULL,
  `activering_per_week` int(5) DEFAULT NULL,
  `hoofdaannemer_id` int(11) DEFAULT NULL,
  `aangevraagd_id` int(1) DEFAULT NULL,
  `aangevraagd_datum` date DEFAULT NULL,
  `aangevraagd_niet` tinyint(1) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `awbz_intakes`
--

CREATE TABLE `awbz_intakes` (
`id` int(11) NOT NULL,
  `klant_id` int(11) DEFAULT NULL,
  `medewerker_id` int(11) DEFAULT NULL,
  `datum_intake` date DEFAULT NULL,
  `verblijfstatus_id` int(11) DEFAULT NULL,
  `postadres` varchar(255) DEFAULT NULL,
  `postcode` varchar(6) DEFAULT NULL,
  `woonplaats` varchar(255) DEFAULT NULL,
  `verblijf_in_NL_sinds` date DEFAULT NULL,
  `verblijf_in_amsterdam_sinds` date DEFAULT NULL,
  `legitimatie_id` int(11) DEFAULT NULL,
  `legitimatie_nummer` varchar(255) DEFAULT NULL,
  `legitimatie_geldig_tot` date DEFAULT NULL,
  `verslavingsfrequentie_id` int(11) DEFAULT NULL,
  `verslavingsperiode_id` int(11) DEFAULT NULL,
  `woonsituatie_id` int(11) DEFAULT NULL,
  `verwachting_dienstaanbod` text,
  `toekomstplannen` text,
  `opmerking_andere_instanties` text,
  `medische_achtergrond` text,
  `locatie1_id` int(11) DEFAULT NULL,
  `locatie2_id` int(11) DEFAULT NULL,
  `indruk` text,
  `doelgroep` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `verslaving_overig` varchar(255) DEFAULT NULL,
  `inkomen_overig` varchar(255) DEFAULT NULL,
  `informele_zorg` tinyint(1) DEFAULT '0',
  `dagbesteding` tinyint(1) DEFAULT '0',
  `inloophuis` tinyint(1) DEFAULT '0',
  `hulpverlening` tinyint(1) DEFAULT '0',
  `mag_gebruiken` tinyint(1) DEFAULT NULL,
  `primaireproblematiek_id` int(11) DEFAULT NULL,
  `primaireproblematieksfrequentie_id` int(11) DEFAULT NULL,
  `primaireproblematieksperiode_id` int(11) DEFAULT NULL,
  `eerste_gebruik` date DEFAULT NULL,
  `locatie3_id` int(11) DEFAULT NULL,
  `infobaliedoelgroep_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `awbz_intakes_primaireproblematieksgebruikswijzen`
--

CREATE TABLE `awbz_intakes_primaireproblematieksgebruikswijzen` (
`id` int(11) NOT NULL,
  `awbz_intake_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `primaireproblematieksgebruikswijze_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `awbz_intakes_verslavingen`
--

CREATE TABLE `awbz_intakes_verslavingen` (
`id` int(11) NOT NULL,
  `awbz_intake_id` int(11) NOT NULL,
  `verslaving_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `awbz_intakes_verslavingsgebruikswijzen`
--

CREATE TABLE `awbz_intakes_verslavingsgebruikswijzen` (
`id` int(11) NOT NULL,
  `awbz_intake_id` int(11) NOT NULL,
  `verslavingsgebruikswijze_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `backup_iz_deelnemers`
--

CREATE TABLE `backup_iz_deelnemers` (
  `id` int(11) NOT NULL DEFAULT '0',
  `model` varchar(50) CHARACTER SET utf8 NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `datum_aanmelding` date DEFAULT NULL,
  `binnengekomen_via` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `organisatie` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `naam_aanmelder` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `email_aanmelder` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `telefoon_aanmelder` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `notitie` text CHARACTER SET utf8,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `datumafsluiting` date DEFAULT NULL,
  `iz_afsluiting_id` int(11) DEFAULT NULL,
  `contact_ontstaan` varchar(100) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `back_on_tracks`
--

CREATE TABLE `back_on_tracks` (
`id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `startdatum` date DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `intakedatum` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bedrijfitems`
--

CREATE TABLE `bedrijfitems` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `bedrijfsector_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bedrijfsectoren`
--

CREATE TABLE `bedrijfsectoren` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bot_koppelingen`
--

CREATE TABLE `bot_koppelingen` (
`id` int(11) NOT NULL,
  `medewerker_id` int(11) DEFAULT NULL,
  `back_on_track_id` int(11) DEFAULT NULL,
  `startdatum` date DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bot_verslagen`
--

CREATE TABLE `bot_verslagen` (
`id` int(11) NOT NULL,
  `contact_type` varchar(50) DEFAULT NULL,
  `verslag` text,
  `medewerker_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `klant_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `categorieen`
--

CREATE TABLE `categorieen` (
`id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contactjournals`
--

CREATE TABLE `contactjournals` (
`id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `text` text NOT NULL,
  `is_tb` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contactsoorts`
--

CREATE TABLE `contactsoorts` (
`id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `doorverwijzers`
--

CREATE TABLE `doorverwijzers` (
`id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `startdatum` date NOT NULL,
  `einddatum` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gd27`
--

CREATE TABLE `gd27` (
  `naam` varchar(50) DEFAULT NULL,
  `voornaam` varchar(50) DEFAULT NULL,
  `achternaam` varchar(50) DEFAULT NULL,
  `geboortedatum` datetime DEFAULT NULL,
  `klant_id` int(11) DEFAULT NULL,
  `db_voornaam` varchar(50) DEFAULT NULL,
  `db_achternaam` varchar(50) DEFAULT NULL,
  `roepnaam` varchar(50) DEFAULT NULL,
  `land` varchar(255) DEFAULT NULL,
  `nationaliteit` varchar(255) DEFAULT NULL,
  `woonsituatie` varchar(255) DEFAULT NULL,
  `inschrijving` date DEFAULT NULL,
  `id` int(11) DEFAULT NULL,
`idd` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `geslachten`
--

CREATE TABLE `geslachten` (
`id` int(11) NOT NULL,
  `afkorting` varchar(255) NOT NULL,
  `volledig` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `groepsactiviteiten`
--

CREATE TABLE `groepsactiviteiten` (
`id` int(11) NOT NULL,
  `groepsactiviteiten_groep_id` int(11) NOT NULL,
  `naam` varchar(100) DEFAULT NULL,
  `datum` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `afgesloten` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `groepsactiviteiten_afsluitingen`
--

CREATE TABLE `groepsactiviteiten_afsluitingen` (
`id` int(11) NOT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `groepsactiviteiten_groepen`
--

CREATE TABLE `groepsactiviteiten_groepen` (
`id` int(11) NOT NULL,
  `naam` varchar(100) NOT NULL,
  `startdatum` date DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `werkgebied` varchar(20) DEFAULT NULL,
  `activiteiten_registreren` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `groepsactiviteiten_groepen_klanten`
--

CREATE TABLE `groepsactiviteiten_groepen_klanten` (
`id` int(11) NOT NULL,
  `groepsactiviteiten_groep_id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `groepsactiviteiten_reden_id` int(11) DEFAULT NULL,
  `startdatum` date DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `communicatie_email` tinyint(1) DEFAULT NULL,
  `communicatie_telefoon` tinyint(1) DEFAULT NULL,
  `communicatie_post` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `groepsactiviteiten_groepen_vrijwilligers`
--

CREATE TABLE `groepsactiviteiten_groepen_vrijwilligers` (
`id` int(11) NOT NULL,
  `groepsactiviteiten_groep_id` int(11) NOT NULL,
  `vrijwilliger_id` int(11) NOT NULL,
  `groepsactiviteiten_reden_id` int(11) DEFAULT NULL,
  `startdatum` date DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `communicatie_email` tinyint(1) DEFAULT NULL,
  `communicatie_telefoon` tinyint(1) DEFAULT NULL,
  `communicatie_post` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `groepsactiviteiten_intakes`
--

CREATE TABLE `groepsactiviteiten_intakes` (
`id` int(11) NOT NULL,
  `model` varchar(255) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `gespreksverslag` text,
  `informele_zorg` int(4) DEFAULT NULL,
  `dagbesteding` int(4) DEFAULT NULL,
  `inloophuis` int(4) DEFAULT NULL,
  `hulpverlening` int(4) DEFAULT NULL,
  `intakedatum` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `groepsactiviteiten_afsluiting_id` int(11) DEFAULT NULL,
  `ondernemen` tinyint(1) DEFAULT NULL,
  `overdag` tinyint(1) DEFAULT NULL,
  `ontmoeten` tinyint(1) DEFAULT NULL,
  `regelzaken` tinyint(1) DEFAULT NULL,
  `gezin_met_kinderen` tinyint(1) DEFAULT NULL,
  `afsluitdatum` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `groepsactiviteiten_klanten`
--

CREATE TABLE `groepsactiviteiten_klanten` (
`id` int(11) NOT NULL,
  `groepsactiviteit_id` int(11) DEFAULT NULL,
  `klant_id` int(11) DEFAULT NULL,
  `afmeld_status` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `groepsactiviteiten_redenen`
--

CREATE TABLE `groepsactiviteiten_redenen` (
`id` int(11) NOT NULL,
  `naam` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `groepsactiviteiten_verslagen`
--

CREATE TABLE `groepsactiviteiten_verslagen` (
`id` int(11) NOT NULL,
  `model` varchar(255) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `medewerker_id` int(11) DEFAULT NULL,
  `opmerking` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `groepsactiviteiten_vrijwilligers`
--

CREATE TABLE `groepsactiviteiten_vrijwilligers` (
`id` int(11) NOT NULL,
  `groepsactiviteit_id` int(11) DEFAULT NULL,
  `vrijwilliger_id` int(11) DEFAULT NULL,
  `afmeld_status` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hi5_answers`
--

CREATE TABLE `hi5_answers` (
`id` int(11) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `hi5_question_id` int(11) NOT NULL,
  `hi5_answer_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hi5_answer_types`
--

CREATE TABLE `hi5_answer_types` (
`id` int(11) NOT NULL,
  `answer_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hi5_evaluaties`
--

CREATE TABLE `hi5_evaluaties` (
`id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `datumevaluatie` date NOT NULL,
  `werkproject` varchar(255) NOT NULL,
  `aantal_dagdelen` int(11) NOT NULL,
  `startdatumtraject` date NOT NULL,
  `datum_intake` date NOT NULL,
  `verslagvan` date NOT NULL,
  `verslagtm` date NOT NULL,
  `extraanwezigen` varchar(255) NOT NULL,
  `opmerkingen_overige` text NOT NULL,
  `afspraken_afgelopen` text NOT NULL,
  `watdoejij` text NOT NULL,
  `watdoetb` text NOT NULL,
  `watdoewb` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hi5_evaluaties_hi5_evaluatie_questions`
--

CREATE TABLE `hi5_evaluaties_hi5_evaluatie_questions` (
`id` int(11) NOT NULL,
  `hi5_evaluatie_id` int(11) NOT NULL,
  `hi5_evaluatie_question_id` int(11) NOT NULL,
  `hi5er_radio` int(11) NOT NULL,
  `hi5er_details` text NOT NULL,
  `wb_radio` int(11) NOT NULL,
  `wb_details` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hi5_evaluatie_paragraphs`
--

CREATE TABLE `hi5_evaluatie_paragraphs` (
`id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hi5_evaluatie_questions`
--

CREATE TABLE `hi5_evaluatie_questions` (
`id` int(11) NOT NULL,
  `hi5_evaluatie_paragraph_id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hi5_intakes`
--

CREATE TABLE `hi5_intakes` (
`id` int(11) NOT NULL,
  `klant_id` int(11) DEFAULT NULL,
  `medewerker_id` int(11) DEFAULT NULL,
  `datum_intake` date DEFAULT NULL,
  `verblijfstatus_id` int(11) DEFAULT NULL,
  `postadres` varchar(255) DEFAULT NULL,
  `postcode` varchar(6) DEFAULT NULL,
  `woonplaats` varchar(255) DEFAULT NULL,
  `verblijf_in_NL_sinds` date DEFAULT NULL,
  `verblijf_in_amsterdam_sinds` date DEFAULT NULL,
  `verslaving_overig` varchar(255) DEFAULT NULL,
  `inkomen_overig` varchar(255) DEFAULT NULL,
  `locatie1_id` int(11) DEFAULT NULL,
  `locatie2_id` int(11) DEFAULT NULL,
  `woonsituatie_id` int(11) DEFAULT NULL,
  `werklocatie_id` int(11) DEFAULT NULL,
  `mag_gebruiken` tinyint(1) DEFAULT NULL,
  `legitimatie_id` int(11) DEFAULT NULL,
  `legitimatie_nummer` varchar(255) DEFAULT NULL,
  `legitimatie_geldig_tot` date DEFAULT NULL,
  `primaireproblematiek_id` int(11) DEFAULT NULL,
  `primaireproblematieksfrequentie_id` int(11) DEFAULT NULL,
  `primaireproblematieksperiode_id` int(11) DEFAULT NULL,
  `eerste_gebruik` date DEFAULT NULL,
  `verslavingsfrequentie_id` int(11) DEFAULT NULL,
  `verslavingsperiode_id` int(11) DEFAULT NULL,
  `bedrijfitem_1_id` int(11) DEFAULT NULL,
  `bedrijfitem_2_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `locatie3_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hi5_intakes_answers`
--

CREATE TABLE `hi5_intakes_answers` (
`id` int(11) NOT NULL,
  `hi5_intake_id` int(11) NOT NULL,
  `hi5_answer_id` int(11) NOT NULL,
  `hi5_answer_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hi5_intakes_inkomens`
--

CREATE TABLE `hi5_intakes_inkomens` (
`id` int(11) NOT NULL,
  `hi5_intake_id` int(11) NOT NULL,
  `inkomen_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hi5_intakes_instanties`
--

CREATE TABLE `hi5_intakes_instanties` (
`id` int(11) NOT NULL,
  `hi5_intake_id` int(11) NOT NULL,
  `instantie_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hi5_intakes_primaireproblematieksgebruikswijzen`
--

CREATE TABLE `hi5_intakes_primaireproblematieksgebruikswijzen` (
`id` int(11) NOT NULL,
  `hi5_intake_id` int(11) NOT NULL,
  `primaireproblematieksgebruikswijze_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hi5_intakes_verslavingen`
--

CREATE TABLE `hi5_intakes_verslavingen` (
`id` int(11) NOT NULL,
  `hi5_intake_id` int(11) NOT NULL,
  `verslaving_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hi5_intakes_verslavingsgebruikswijzen`
--

CREATE TABLE `hi5_intakes_verslavingsgebruikswijzen` (
`id` int(11) NOT NULL,
  `hi5_intake_id` int(11) NOT NULL,
  `verslavingsgebruikswijze_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hi5_questions`
--

CREATE TABLE `hi5_questions` (
`id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hoofdaannemers`
--

CREATE TABLE `hoofdaannemers` (
`id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `i18n`
--

CREATE TABLE `i18n` (
`id` int(10) NOT NULL,
  `locale` varchar(6) NOT NULL,
  `model` varchar(255) NOT NULL,
  `foreign_key` int(10) NOT NULL,
  `field` varchar(255) NOT NULL,
  `content` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `infobaliedoelgroepen`
--

CREATE TABLE `infobaliedoelgroepen` (
`id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inkomens`
--

CREATE TABLE `inkomens` (
`id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inkomens_awbz_intakes`
--

CREATE TABLE `inkomens_awbz_intakes` (
`id` int(11) NOT NULL,
  `awbz_intake_id` int(11) NOT NULL,
  `inkomen_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inkomens_intakes`
--

CREATE TABLE `inkomens_intakes` (
`id` int(11) NOT NULL,
  `intake_id` int(11) NOT NULL,
  `inkomen_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `instanties`
--

CREATE TABLE `instanties` (
`id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `instanties_awbz_intakes`
--

CREATE TABLE `instanties_awbz_intakes` (
`id` int(11) NOT NULL,
  `awbz_intake_id` int(11) NOT NULL,
  `instantie_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `instanties_intakes`
--

CREATE TABLE `instanties_intakes` (
`id` int(11) NOT NULL,
  `intake_id` int(11) NOT NULL,
  `instantie_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `intakes`
--

CREATE TABLE `intakes` (
`id` int(11) NOT NULL,
  `klant_id` int(11) DEFAULT NULL,
  `medewerker_id` int(11) DEFAULT NULL,
  `datum_intake` date DEFAULT NULL,
  `verblijfstatus_id` int(11) DEFAULT NULL,
  `postadres` varchar(255) DEFAULT NULL,
  `postcode` varchar(6) DEFAULT NULL,
  `woonplaats` varchar(255) DEFAULT NULL,
  `verblijf_in_NL_sinds` date DEFAULT NULL,
  `verblijf_in_amsterdam_sinds` date DEFAULT NULL,
  `legitimatie_id` int(11) DEFAULT NULL,
  `legitimatie_nummer` varchar(255) DEFAULT NULL,
  `legitimatie_geldig_tot` date DEFAULT NULL,
  `primaireproblematiek_id` int(11) DEFAULT NULL,
  `primaireproblematieksfrequentie_id` int(11) DEFAULT NULL,
  `primaireproblematieksperiode_id` int(11) DEFAULT NULL,
  `verslavingsfrequentie_id` int(11) DEFAULT NULL,
  `verslavingsperiode_id` int(11) DEFAULT NULL,
  `verslaving_overig` varchar(255) DEFAULT NULL,
  `eerste_gebruik` date DEFAULT NULL,
  `inkomen_overig` varchar(255) DEFAULT NULL,
  `woonsituatie_id` int(11) DEFAULT NULL,
  `verwachting_dienstaanbod` text,
  `toekomstplannen` text,
  `opmerking_andere_instanties` text,
  `medische_achtergrond` text,
  `locatie1_id` int(11) DEFAULT NULL,
  `locatie2_id` int(11) DEFAULT NULL,
  `mag_gebruiken` tinyint(1) DEFAULT NULL,
  `indruk` text,
  `doelgroep` tinyint(1) DEFAULT NULL,
  `informele_zorg` tinyint(1) DEFAULT '0',
  `dagbesteding` tinyint(1) DEFAULT '0',
  `inloophuis` tinyint(1) DEFAULT '0',
  `hulpverlening` tinyint(1) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `locatie3_id` int(11) DEFAULT NULL,
  `infobaliedoelgroep_id` int(11) DEFAULT NULL,
  `toegang_vrouwen_nacht_opvang` tinyint(1) DEFAULT '0',
  `telefoonnummer` varchar(255) DEFAULT NULL,
  `toegang_inloophuis` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `intakes_primaireproblematieksgebruikswijzen`
--

CREATE TABLE `intakes_primaireproblematieksgebruikswijzen` (
`id` int(11) NOT NULL,
  `intake_id` int(11) NOT NULL,
  `primaireproblematieksgebruikswijze_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `intakes_verslavingen`
--

CREATE TABLE `intakes_verslavingen` (
`id` int(11) NOT NULL,
  `intake_id` int(11) NOT NULL,
  `verslaving_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `intakes_verslavingsgebruikswijzen`
--

CREATE TABLE `intakes_verslavingsgebruikswijzen` (
`id` int(11) NOT NULL,
  `intake_id` int(11) NOT NULL,
  `verslavingsgebruikswijze_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inventarisaties`
--

CREATE TABLE `inventarisaties` (
`id` int(11) NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(11) DEFAULT NULL,
  `actief` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(255) DEFAULT NULL,
  `titel` varchar(255) NOT NULL,
  `actie` varchar(255) NOT NULL,
  `startdatum` date NOT NULL,
  `einddatum` date DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rght` int(11) DEFAULT NULL,
  `depth` tinyint(3) DEFAULT NULL,
  `dropdown_metadata` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inventarisaties_verslagen`
--

CREATE TABLE `inventarisaties_verslagen` (
`id` int(11) NOT NULL,
  `verslag_id` int(11) NOT NULL DEFAULT '0',
  `inventarisatie_id` int(11) NOT NULL DEFAULT '0',
  `doorverwijzer_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `iz_afsluitingen`
--

CREATE TABLE `iz_afsluitingen` (
`id` int(11) NOT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `iz_deelnemers`
--

CREATE TABLE `iz_deelnemers` (
`id` int(11) NOT NULL,
  `model` varchar(50) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `datum_aanmelding` date DEFAULT NULL,
  `binnengekomen_via` varchar(50) DEFAULT NULL,
  `organisatie` varchar(100) DEFAULT NULL,
  `naam_aanmelder` varchar(100) DEFAULT NULL,
  `email_aanmelder` varchar(100) DEFAULT NULL,
  `telefoon_aanmelder` varchar(100) DEFAULT NULL,
  `notitie` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `datumafsluiting` date DEFAULT NULL,
  `iz_afsluiting_id` int(11) DEFAULT NULL,
  `contact_ontstaan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `iz_deelnemers_iz_intervisiegroepen`
--

CREATE TABLE `iz_deelnemers_iz_intervisiegroepen` (
`id` int(11) NOT NULL,
  `iz_deelnemer_id` int(11) NOT NULL,
  `iz_intervisiegroep_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `iz_deelnemers_iz_projecten`
--

CREATE TABLE `iz_deelnemers_iz_projecten` (
`id` int(11) NOT NULL,
  `iz_deelnemer_id` int(11) NOT NULL,
  `iz_project_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `iz_eindekoppelingen`
--

CREATE TABLE `iz_eindekoppelingen` (
`id` int(11) NOT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `iz_intakes`
--

CREATE TABLE `iz_intakes` (
`id` int(11) NOT NULL,
  `iz_deelnemer_id` int(11) NOT NULL,
  `medewerker_id` int(11) DEFAULT NULL,
  `intake_datum` date DEFAULT NULL,
  `gesprek_verslag` text,
  `ondernemen` tinyint(1) DEFAULT NULL,
  `overdag` tinyint(1) DEFAULT NULL,
  `ontmoeten` tinyint(1) DEFAULT NULL,
  `regelzaken` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modifed` datetime DEFAULT NULL,
  `stagiair` tinyint(1) DEFAULT NULL,
  `gezin_met_kinderen` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `iz_intervisiegroepen`
--

CREATE TABLE `iz_intervisiegroepen` (
`id` int(11) NOT NULL,
  `naam` varchar(100) DEFAULT NULL,
  `startdatum` date DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `medewerker_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `iz_koppelingen`
--

CREATE TABLE `iz_koppelingen` (
`id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `iz_deelnemer_id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `startdatum` date DEFAULT NULL,
  `koppeling_startdatum` date DEFAULT NULL,
  `koppeling_einddatum` date DEFAULT NULL,
  `iz_eindekoppeling_id` int(11) DEFAULT NULL,
  `koppeling_succesvol` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `iz_vraagaanbod_id` int(11) DEFAULT NULL,
  `iz_koppeling_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `iz_ontstaan_contacten`
--

CREATE TABLE `iz_ontstaan_contacten` (
`id` int(11) NOT NULL,
  `naam` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `iz_projecten`
--

CREATE TABLE `iz_projecten` (
`id` int(11) NOT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `startdatum` date DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `heeft_koppelingen` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `iz_verslagen`
--

CREATE TABLE `iz_verslagen` (
`id` int(11) NOT NULL,
  `iz_deelnemer_id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `opmerking` longtext,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `iz_koppeling_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `iz_via_personen`
--

CREATE TABLE `iz_via_personen` (
`id` int(11) NOT NULL,
  `naam` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `iz_vraagaanboden`
--

CREATE TABLE `iz_vraagaanboden` (
`id` int(11) NOT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `klanten`
--

CREATE TABLE `klanten` (
`id` int(11) NOT NULL,
  `MezzoID` int(11) NOT NULL,
  `voornaam` varchar(255) DEFAULT NULL,
  `tussenvoegsel` varchar(255) DEFAULT NULL,
  `achternaam` varchar(255) DEFAULT NULL,
  `roepnaam` varchar(255) DEFAULT NULL,
  `geslacht_id` int(11) NOT NULL DEFAULT '0',
  `geboortedatum` date DEFAULT NULL,
  `land_id` int(11) NOT NULL DEFAULT '1',
  `nationaliteit_id` int(11) NOT NULL DEFAULT '1',
  `BSN` varchar(255) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `laatste_TBC_controle` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `laste_intake_id` int(11) DEFAULT NULL,
  `disabled` tinyint(1) DEFAULT '0',
  `laatste_registratie_id` int(11) DEFAULT NULL,
  `doorverwijzen_naar_amoc` tinyint(1) DEFAULT '0',
  `merged_id` int(11) DEFAULT NULL,
  `adres` varchar(255) DEFAULT NULL,
  `postcode` varchar(6) DEFAULT NULL,
  `werkgebied` varchar(20) DEFAULT NULL,
  `plaats` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobiel` varchar(255) DEFAULT NULL,
  `telefoon` varchar(255) DEFAULT NULL,
  `opmerking` text,
  `geen_post` tinyint(1) DEFAULT NULL,
  `first_intake_date` date DEFAULT NULL,
  `geen_email` tinyint(1) DEFAULT NULL,
  `overleden` tinyint(1) DEFAULT NULL,
  `last_zrm` date DEFAULT NULL,
  `postcodegebied` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `klantinventarisaties`
--

CREATE TABLE `klantinventarisaties` (
`id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL DEFAULT '0',
  `inventarisatie_id` int(11) NOT NULL DEFAULT '0',
  `doorverwijzer_id` int(11) NOT NULL DEFAULT '0',
  `datum` date NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `landen`
--

CREATE TABLE `landen` (
`id` int(11) NOT NULL,
  `land` varchar(255) NOT NULL,
  `AFK2` varchar(5) NOT NULL,
  `AFK3` varchar(5) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `legitimaties`
--

CREATE TABLE `legitimaties` (
`id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `locaties`
--

CREATE TABLE `locaties` (
`id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `nachtopvang` tinyint(1) NOT NULL DEFAULT '0',
  `gebruikersruimte` tinyint(1) NOT NULL DEFAULT '0',
  `datum_van` date NOT NULL,
  `datum_tot` date NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `maatschappelijkwerk` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `locatie_tijden`
--

CREATE TABLE `locatie_tijden` (
`id` int(11) NOT NULL,
  `locatie_id` int(11) NOT NULL,
  `dag_van_de_week` int(4) NOT NULL,
  `sluitingstijd` time NOT NULL,
  `openingstijd` time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
`id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `foreign_key` varchar(36) DEFAULT NULL,
  `medewerker_id` varchar(36) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `change` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `medewerkers`
--

CREATE TABLE `medewerkers` (
`id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `voornaam` varchar(255) DEFAULT NULL,
  `tussenvoegsel` varchar(255) DEFAULT NULL,
  `achternaam` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `eerste_bezoek` datetime NOT NULL,
  `laatste_bezoek` datetime NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `uidnumber` int(11) NOT NULL,
  `active` int(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nationaliteiten`
--

CREATE TABLE `nationaliteiten` (
`id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `afkorting` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `notities`
--

CREATE TABLE `notities` (
`id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `datum` datetime NOT NULL,
  `opmerking` text NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `opmerkingen`
--

CREATE TABLE `opmerkingen` (
`id` int(11) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `beschrijving` varchar(255) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `gezien` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pfo_aard_relaties`
--

CREATE TABLE `pfo_aard_relaties` (
`id` int(11) NOT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `startdatum` date DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pfo_clienten`
--

CREATE TABLE `pfo_clienten` (
`id` int(10) unsigned NOT NULL,
  `roepnaam` varchar(255) DEFAULT NULL,
  `tussenvoegsel` varchar(255) DEFAULT NULL,
  `achternaam` varchar(255) DEFAULT NULL,
  `geslacht_id` int(11) DEFAULT NULL,
  `geboortedatum` date DEFAULT NULL,
  `adres` varchar(255) DEFAULT NULL,
  `postcode` varchar(50) DEFAULT NULL,
  `woonplaats` varchar(255) DEFAULT NULL,
  `telefoon` varchar(255) DEFAULT NULL,
  `telefoon_mobiel` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `notitie` text,
  `medewerker_id` int(11) DEFAULT NULL,
  `groep` int(11) DEFAULT NULL,
  `aard_relatie` int(11) DEFAULT NULL,
  `dubbele_diagnose` int(4) DEFAULT NULL,
  `eerdere_hulpverlening` tinyint(1) DEFAULT NULL,
  `via` text,
  `hulpverleners` text,
  `contacten` text,
  `begeleidings_formulier` date DEFAULT NULL,
  `brief_huisarts` date DEFAULT NULL,
  `evaluatie_formulier` date DEFAULT NULL,
  `datum_afgesloten` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pfo_clienten_supportgroups`
--

CREATE TABLE `pfo_clienten_supportgroups` (
`id` int(11) NOT NULL,
  `pfo_client_id` int(11) NOT NULL,
  `pfo_supportgroup_client_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pfo_clienten_verslagen`
--

CREATE TABLE `pfo_clienten_verslagen` (
`id` int(11) NOT NULL,
  `pfo_client_id` int(11) DEFAULT NULL,
  `pfo_verslag_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pfo_groepen`
--

CREATE TABLE `pfo_groepen` (
`id` int(11) NOT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `startdatum` date DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pfo_verslagen`
--

CREATE TABLE `pfo_verslagen` (
`id` int(11) NOT NULL,
  `contact_type` varchar(50) DEFAULT NULL,
  `verslag` text,
  `medewerker_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `postcodegebieden`
--

CREATE TABLE `postcodegebieden` (
  `postcodegebied` varchar(255) NOT NULL,
`id` int(11) NOT NULL,
  `van` int(11) NOT NULL,
  `tot` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `queue_tasks`
--

CREATE TABLE `queue_tasks` (
`id` int(11) NOT NULL,
  `model` varchar(50) DEFAULT NULL,
  `foreign_key` varchar(36) NOT NULL,
  `action` varchar(50) DEFAULT NULL,
  `data` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `run_after` datetime DEFAULT NULL,
  `batch` varchar(50) DEFAULT NULL,
  `output` text,
  `executed` datetime DEFAULT NULL,
  `status` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `redenen`
--

CREATE TABLE `redenen` (
`id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `registraties`
--

CREATE TABLE `registraties` (
`id` int(11) NOT NULL,
  `locatie_id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `binnen` datetime NOT NULL,
  `buiten` datetime DEFAULT NULL,
  `douche` int(11) NOT NULL,
  `mw` int(11) NOT NULL,
  `kleding` tinyint(1) NOT NULL,
  `maaltijd` tinyint(1) NOT NULL,
  `activering` tinyint(1) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `gbrv` int(11) NOT NULL,
  `closed` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `schorsingen`
--

CREATE TABLE `schorsingen` (
`id` int(11) NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date NOT NULL,
  `locatie_id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `remark` text NOT NULL,
  `gezien` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `overig_reden` varchar(255) DEFAULT NULL,
  `aangifte` tinyint(1) DEFAULT NULL,
  `nazorg` tinyint(1) DEFAULT NULL,
  `aggressie_tegen_medewerker` int(4) DEFAULT NULL,
  `aggressie_doelwit` varchar(255) DEFAULT NULL,
  `agressie` int(4) DEFAULT NULL,
  `aggressie_tegen_medewerker2` int(4) DEFAULT NULL,
  `aggressie_doelwit2` varchar(255) DEFAULT NULL,
  `aggressie_tegen_medewerker3` int(4) DEFAULT NULL,
  `aggressie_doelwit3` varchar(255) DEFAULT NULL,
  `aggressie_tegen_medewerker4` int(4) DEFAULT NULL,
  `aggressie_doelwit4` varchar(255) DEFAULT NULL,
  `locatiehoofd` varchar(100) NOT NULL,
  `bijzonderheden` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `schorsingen_redenen`
--

CREATE TABLE `schorsingen_redenen` (
`id` int(11) NOT NULL,
  `schorsing_id` int(11) NOT NULL,
  `reden_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stadsdelen`
--

CREATE TABLE `stadsdelen` (
  `postcode` varchar(255) NOT NULL,
  `stadsdeel` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_avgduration`
--

CREATE TABLE `tmp_avgduration` (
  `label` varchar(64) DEFAULT NULL,
  `range_start` int(11) DEFAULT NULL,
  `range_end` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_err`
--

CREATE TABLE `tmp_err` (
  `koppeling` int(11) NOT NULL DEFAULT '0',
  `deelnemer` int(11) NOT NULL DEFAULT '0',
  `vrijwilliger` int(11) DEFAULT '0',
  `klant` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_izid`
--

CREATE TABLE `tmp_izid` (
  `id` int(11) NOT NULL DEFAULT '0',
  `iz_deelnemer_id` int(11) NOT NULL,
  `iz_intervisiegroep_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_open_days`
--

CREATE TABLE `tmp_open_days` (
  `locatie_id` int(11) NOT NULL,
  `open_day` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_registraties`
--

CREATE TABLE `tmp_registraties` (
  `id` int(11) NOT NULL DEFAULT '0',
  `locatie_id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `binnen` datetime NOT NULL,
  `buiten` datetime DEFAULT NULL,
  `douche` int(11) NOT NULL,
  `mw` int(11) NOT NULL,
  `kleding` tinyint(1) NOT NULL,
  `maaltijd` tinyint(1) NOT NULL,
  `activering` tinyint(1) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `gbrv` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_rm`
--

CREATE TABLE `tmp_rm` (
  `id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_stadsdelen`
--

CREATE TABLE `tmp_stadsdelen` (
  `postcode` varchar(255) NOT NULL,
  `stadsdeel` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_tmp_unique_2015`
--

CREATE TABLE `tmp_tmp_unique_2015` (
  `klant_id` int(11) NOT NULL,
  `locaties` longblob,
  `binnen` longblob
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_visitors`
--

CREATE TABLE `tmp_visitors` (
  `klant_id` int(11) NOT NULL,
  `land_id` int(11) NOT NULL DEFAULT '1',
  `geslacht` varchar(255) CHARACTER SET utf8 NOT NULL,
  `date` date DEFAULT NULL,
  `verslaving_id` int(11) DEFAULT '0',
  `woonsituatie_id` int(11) DEFAULT NULL,
  `verblijfstatus_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_visits`
--

CREATE TABLE `tmp_visits` (
  `locatie_id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `gender` varchar(255) CHARACTER SET utf8 NOT NULL,
  `duration` decimal(31,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trajecten`
--

CREATE TABLE `trajecten` (
`id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `trajectbegeleider_id` int(11) NOT NULL,
  `werkbegeleider_id` int(11) NOT NULL,
  `klant_telefoonnummer` varchar(255) NOT NULL,
  `administratienummer` varchar(255) NOT NULL,
  `klantmanager` varchar(255) NOT NULL,
  `manager_telefoonnummer` varchar(255) NOT NULL,
  `manager_email` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `verblijfstatussen`
--

CREATE TABLE `verblijfstatussen` (
`id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `verslagen`
--

CREATE TABLE `verslagen` (
`id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `opmerking` text,
  `datum` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `medewerker` varchar(255) DEFAULT NULL,
  `locatie_id` int(11) DEFAULT NULL,
  `aanpassing_verslag` int(5) DEFAULT NULL,
  `contactsoort_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `verslaginfos`
--

CREATE TABLE `verslaginfos` (
`id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `advocaat` varchar(255) NOT NULL,
  `contact` text NOT NULL,
  `casemanager_id` int(11) DEFAULT NULL,
  `casemanager_email` varchar(255) NOT NULL,
  `casemanager_telefoonnummer` varchar(255) NOT NULL,
  `trajectbegeleider_id` int(11) DEFAULT NULL,
  `trajectbegeleider_email` varchar(255) NOT NULL,
  `trajectbegeleider_telefoonnummer` varchar(255) NOT NULL,
  `trajecthouder_extern_organisatie` varchar(255) NOT NULL,
  `trajecthouder_extern_naam` varchar(255) NOT NULL,
  `trajecthouder_extern_email` varchar(255) NOT NULL,
  `trajecthouder_extern_telefoonnummer` varchar(255) NOT NULL,
  `overige_contactpersonen_extern` text NOT NULL,
  `instantie` varchar(255) NOT NULL,
  `registratienummer` varchar(255) NOT NULL,
  `budgettering` varchar(255) NOT NULL,
  `contactpersoon` varchar(255) NOT NULL,
  `klantmanager_naam` varchar(255) NOT NULL,
  `klantmanager_email` varchar(255) NOT NULL,
  `klantmanager_telefoonnummer` varchar(255) NOT NULL,
  `sociaal_netwerk` text NOT NULL,
  `bankrekeningnummer` varchar(255) NOT NULL,
  `polisnummer_ziektekostenverzekering` varchar(255) NOT NULL,
  `inschrijfnummer` varchar(255) NOT NULL,
  `wachtwoord` varchar(255) NOT NULL,
  `telefoonnummer` varchar(255) NOT NULL,
  `adres` text NOT NULL,
  `overigen` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `verslavingen`
--

CREATE TABLE `verslavingen` (
`id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `verslavingsfrequenties`
--

CREATE TABLE `verslavingsfrequenties` (
`id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `verslavingsgebruikswijzen`
--

CREATE TABLE `verslavingsgebruikswijzen` (
`id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `verslavingsperiodes`
--

CREATE TABLE `verslavingsperiodes` (
`id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `vrijwilligers`
--

CREATE TABLE `vrijwilligers` (
`id` int(11) NOT NULL,
  `voornaam` varchar(255) DEFAULT NULL,
  `tussenvoegsel` varchar(255) DEFAULT NULL,
  `achternaam` varchar(255) DEFAULT NULL,
  `roepnaam` varchar(255) DEFAULT NULL,
  `geslacht_id` int(11) NOT NULL DEFAULT '0',
  `geboortedatum` date DEFAULT NULL,
  `land_id` int(11) NOT NULL DEFAULT '1',
  `nationaliteit_id` int(11) NOT NULL DEFAULT '1',
  `BSN` varchar(255) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `adres` varchar(255) DEFAULT NULL,
  `postcode` varchar(6) DEFAULT NULL,
  `werkgebied` varchar(20) DEFAULT NULL,
  `plaats` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobiel` varchar(255) DEFAULT NULL,
  `telefoon` varchar(255) DEFAULT NULL,
  `opmerking` text,
  `geen_post` tinyint(1) DEFAULT NULL,
  `disabled` tinyint(1) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `geen_email` tinyint(1) DEFAULT NULL,
  `postcodegebied` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `woonsituaties`
--

CREATE TABLE `woonsituaties` (
`id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `zrm_reports`
--

CREATE TABLE `zrm_reports` (
`id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `model` varchar(50) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `request_module` varchar(50) NOT NULL,
  `inkomen` int(11) DEFAULT NULL,
  `dagbesteding` int(11) DEFAULT NULL,
  `huisvesting` int(11) DEFAULT NULL,
  `gezinsrelaties` int(11) DEFAULT NULL,
  `geestelijke_gezondheid` int(11) DEFAULT NULL,
  `fysieke_gezondheid` int(11) DEFAULT NULL,
  `verslaving` int(11) DEFAULT NULL,
  `adl_vaardigheden` int(11) DEFAULT NULL,
  `sociaal_netwerk` int(11) DEFAULT NULL,
  `maatschappelijke_participatie` int(11) DEFAULT NULL,
  `justitie` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `zrm_settings`
--

CREATE TABLE `zrm_settings` (
`id` int(11) NOT NULL,
  `request_module` varchar(50) NOT NULL,
  `inkomen` tinyint(1) DEFAULT NULL,
  `dagbesteding` tinyint(1) DEFAULT NULL,
  `huisvesting` tinyint(1) DEFAULT NULL,
  `gezinsrelaties` tinyint(1) DEFAULT NULL,
  `geestelijke_gezondheid` tinyint(1) DEFAULT NULL,
  `fysieke_gezondheid` tinyint(1) DEFAULT NULL,
  `verslaving` tinyint(1) DEFAULT NULL,
  `adl_vaardigheden` tinyint(1) DEFAULT NULL,
  `sociaal_netwerk` tinyint(1) DEFAULT NULL,
  `maatschappelijke_participatie` tinyint(1) DEFAULT NULL,
  `justitie` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `awbz_hoofdaannemers`
--
ALTER TABLE `awbz_hoofdaannemers`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_awbz_hoofdaannemers_klant_id` (`klant_id`), ADD KEY `idx_awbz_hoofdaannemers_hoofdaannemer_id` (`hoofdaannemer_id`);

--
-- Indexes for table `awbz_indicaties`
--
ALTER TABLE `awbz_indicaties`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_awbz_indicaties_klant_id` (`klant_id`);

--
-- Indexes for table `awbz_intakes`
--
ALTER TABLE `awbz_intakes`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_awbz_intakes_klant_id` (`klant_id`);

--
-- Indexes for table `awbz_intakes_primaireproblematieksgebruikswijzen`
--
ALTER TABLE `awbz_intakes_primaireproblematieksgebruikswijzen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `awbz_intakes_verslavingen`
--
ALTER TABLE `awbz_intakes_verslavingen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `awbz_intakes_verslavingsgebruikswijzen`
--
ALTER TABLE `awbz_intakes_verslavingsgebruikswijzen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `back_on_tracks`
--
ALTER TABLE `back_on_tracks`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_back_on_tracks_dates` (`startdatum`,`einddatum`);

--
-- Indexes for table `bedrijfitems`
--
ALTER TABLE `bedrijfitems`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bedrijfsectoren`
--
ALTER TABLE `bedrijfsectoren`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bot_koppelingen`
--
ALTER TABLE `bot_koppelingen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bot_verslagen`
--
ALTER TABLE `bot_verslagen`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_bto_verslagen_klant_id` (`klant_id`);

--
-- Indexes for table `categorieen`
--
ALTER TABLE `categorieen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contactjournals`
--
ALTER TABLE `contactjournals`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_contactjournals_klant_id` (`klant_id`);

--
-- Indexes for table `contactsoorts`
--
ALTER TABLE `contactsoorts`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doorverwijzers`
--
ALTER TABLE `doorverwijzers`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gd27`
--
ALTER TABLE `gd27`
 ADD PRIMARY KEY (`idd`);

--
-- Indexes for table `geslachten`
--
ALTER TABLE `geslachten`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groepsactiviteiten`
--
ALTER TABLE `groepsactiviteiten`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groepsactiviteiten_afsluitingen`
--
ALTER TABLE `groepsactiviteiten_afsluitingen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groepsactiviteiten_groepen`
--
ALTER TABLE `groepsactiviteiten_groepen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groepsactiviteiten_groepen_klanten`
--
ALTER TABLE `groepsactiviteiten_groepen_klanten`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groepsactiviteiten_groepen_vrijwilligers`
--
ALTER TABLE `groepsactiviteiten_groepen_vrijwilligers`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groepsactiviteiten_intakes`
--
ALTER TABLE `groepsactiviteiten_intakes`
 ADD PRIMARY KEY (`id`), ADD KEY `groepsactiviteiten_intakes_foreign_key_model_idx` (`foreign_key`,`model`);

--
-- Indexes for table `groepsactiviteiten_klanten`
--
ALTER TABLE `groepsactiviteiten_klanten`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groepsactiviteiten_redenen`
--
ALTER TABLE `groepsactiviteiten_redenen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groepsactiviteiten_verslagen`
--
ALTER TABLE `groepsactiviteiten_verslagen`
 ADD PRIMARY KEY (`id`), ADD KEY `foreign_key_model_idx` (`foreign_key`,`model`);

--
-- Indexes for table `groepsactiviteiten_vrijwilligers`
--
ALTER TABLE `groepsactiviteiten_vrijwilligers`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hi5_answers`
--
ALTER TABLE `hi5_answers`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hi5_answer_types`
--
ALTER TABLE `hi5_answer_types`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hi5_evaluaties`
--
ALTER TABLE `hi5_evaluaties`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_hi5_evaluaties_klant_id` (`klant_id`);

--
-- Indexes for table `hi5_evaluaties_hi5_evaluatie_questions`
--
ALTER TABLE `hi5_evaluaties_hi5_evaluatie_questions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hi5_evaluatie_paragraphs`
--
ALTER TABLE `hi5_evaluatie_paragraphs`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hi5_evaluatie_questions`
--
ALTER TABLE `hi5_evaluatie_questions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hi5_intakes`
--
ALTER TABLE `hi5_intakes`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_hi5_intakes_klant_id` (`klant_id`);

--
-- Indexes for table `hi5_intakes_answers`
--
ALTER TABLE `hi5_intakes_answers`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hi5_intakes_inkomens`
--
ALTER TABLE `hi5_intakes_inkomens`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hi5_intakes_instanties`
--
ALTER TABLE `hi5_intakes_instanties`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hi5_intakes_primaireproblematieksgebruikswijzen`
--
ALTER TABLE `hi5_intakes_primaireproblematieksgebruikswijzen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hi5_intakes_verslavingen`
--
ALTER TABLE `hi5_intakes_verslavingen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hi5_intakes_verslavingsgebruikswijzen`
--
ALTER TABLE `hi5_intakes_verslavingsgebruikswijzen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hi5_questions`
--
ALTER TABLE `hi5_questions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hoofdaannemers`
--
ALTER TABLE `hoofdaannemers`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `i18n`
--
ALTER TABLE `i18n`
 ADD PRIMARY KEY (`id`), ADD KEY `locale` (`locale`), ADD KEY `model` (`model`), ADD KEY `row_id` (`foreign_key`), ADD KEY `field` (`field`);

--
-- Indexes for table `infobaliedoelgroepen`
--
ALTER TABLE `infobaliedoelgroepen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inkomens`
--
ALTER TABLE `inkomens`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inkomens_awbz_intakes`
--
ALTER TABLE `inkomens_awbz_intakes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inkomens_intakes`
--
ALTER TABLE `inkomens_intakes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instanties`
--
ALTER TABLE `instanties`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instanties_awbz_intakes`
--
ALTER TABLE `instanties_awbz_intakes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instanties_intakes`
--
ALTER TABLE `instanties_intakes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `intakes`
--
ALTER TABLE `intakes`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_intakes_woonsituatie_id` (`woonsituatie_id`), ADD KEY `idx_intakes_klant_id` (`klant_id`), ADD KEY `idx_intakes_verblijfstatus_id` (`verblijfstatus_id`);

--
-- Indexes for table `intakes_primaireproblematieksgebruikswijzen`
--
ALTER TABLE `intakes_primaireproblematieksgebruikswijzen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `intakes_verslavingen`
--
ALTER TABLE `intakes_verslavingen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `intakes_verslavingsgebruikswijzen`
--
ALTER TABLE `intakes_verslavingsgebruikswijzen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventarisaties`
--
ALTER TABLE `inventarisaties`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventarisaties_verslagen`
--
ALTER TABLE `inventarisaties_verslagen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `iz_afsluitingen`
--
ALTER TABLE `iz_afsluitingen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `iz_deelnemers`
--
ALTER TABLE `iz_deelnemers`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_iz_deelnemers_persoon` (`model`,`foreign_key`);

--
-- Indexes for table `iz_deelnemers_iz_intervisiegroepen`
--
ALTER TABLE `iz_deelnemers_iz_intervisiegroepen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `iz_deelnemers_iz_projecten`
--
ALTER TABLE `iz_deelnemers_iz_projecten`
 ADD PRIMARY KEY (`id`), ADD KEY `iz_deelnemers_iz_projecten_id_deelnemr` (`iz_deelnemer_id`), ADD KEY `iz_deelnemers_iz_projecten_iz_project_id` (`iz_project_id`);

--
-- Indexes for table `iz_eindekoppelingen`
--
ALTER TABLE `iz_eindekoppelingen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `iz_intakes`
--
ALTER TABLE `iz_intakes`
 ADD PRIMARY KEY (`id`), ADD KEY `iz_deelnemer_id` (`iz_deelnemer_id`);

--
-- Indexes for table `iz_intervisiegroepen`
--
ALTER TABLE `iz_intervisiegroepen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `iz_koppelingen`
--
ALTER TABLE `iz_koppelingen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `iz_ontstaan_contacten`
--
ALTER TABLE `iz_ontstaan_contacten`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `iz_projecten`
--
ALTER TABLE `iz_projecten`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `iz_verslagen`
--
ALTER TABLE `iz_verslagen`
 ADD PRIMARY KEY (`id`), ADD KEY `idz_iz_verslag_iz_koppeling_id` (`iz_koppeling_id`);

--
-- Indexes for table `iz_via_personen`
--
ALTER TABLE `iz_via_personen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `iz_vraagaanboden`
--
ALTER TABLE `iz_vraagaanboden`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `klanten`
--
ALTER TABLE `klanten`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_klanten_geboortedatum` (`geboortedatum`), ADD KEY `idx_klanten_first_intake_date` (`first_intake_date`), ADD KEY `idx_klanten_werkgebied` (`werkgebied`);

--
-- Indexes for table `klantinventarisaties`
--
ALTER TABLE `klantinventarisaties`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `landen`
--
ALTER TABLE `landen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `legitimaties`
--
ALTER TABLE `legitimaties`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locaties`
--
ALTER TABLE `locaties`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locatie_tijden`
--
ALTER TABLE `locatie_tijden`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_logs_model_foreign_key_created` (`model`,`foreign_key`,`created`), ADD KEY `idx_logs_medewerker_id` (`medewerker_id`), ADD KEY `idx_logs_model_foreign_key` (`model`,`foreign_key`);

--
-- Indexes for table `medewerkers`
--
ALTER TABLE `medewerkers`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nationaliteiten`
--
ALTER TABLE `nationaliteiten`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notities`
--
ALTER TABLE `notities`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_notities_klant_id` (`klant_id`);

--
-- Indexes for table `opmerkingen`
--
ALTER TABLE `opmerkingen`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_opmerkingen_klant_id` (`klant_id`);

--
-- Indexes for table `pfo_aard_relaties`
--
ALTER TABLE `pfo_aard_relaties`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pfo_clienten`
--
ALTER TABLE `pfo_clienten`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_pfo_clienten_roepnaam` (`roepnaam`), ADD KEY `idx_pfo_clienten_achternaam` (`achternaam`), ADD KEY `idx_pfo_clienten_groep` (`groep`), ADD KEY `idx_pfo_clienten_medewerker_id` (`medewerker_id`);

--
-- Indexes for table `pfo_clienten_supportgroups`
--
ALTER TABLE `pfo_clienten_supportgroups`
 ADD PRIMARY KEY (`id`), ADD KEY `pfo_cl_s_pfo_client_id` (`pfo_client_id`), ADD KEY `pfo_cl_s_pfo_supportgroup_client_id` (`pfo_supportgroup_client_id`);

--
-- Indexes for table `pfo_clienten_verslagen`
--
ALTER TABLE `pfo_clienten_verslagen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pfo_groepen`
--
ALTER TABLE `pfo_groepen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pfo_verslagen`
--
ALTER TABLE `pfo_verslagen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `postcodegebieden`
--
ALTER TABLE `postcodegebieden`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `queue_tasks`
--
ALTER TABLE `queue_tasks`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_queue_tasks_status_modified` (`modified`,`status`);

--
-- Indexes for table `redenen`
--
ALTER TABLE `redenen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registraties`
--
ALTER TABLE `registraties`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_registraties_klant_id_locatie_id` (`klant_id`,`locatie_id`), ADD KEY `idx_registraties_locatie_id_closed` (`locatie_id`,`closed`);

--
-- Indexes for table `schorsingen`
--
ALTER TABLE `schorsingen`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_schorsingen_klant_id` (`klant_id`);

--
-- Indexes for table `schorsingen_redenen`
--
ALTER TABLE `schorsingen_redenen`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_schorsingen_redenen_schorsing_id` (`schorsing_id`);

--
-- Indexes for table `stadsdelen`
--
ALTER TABLE `stadsdelen`
 ADD PRIMARY KEY (`postcode`);

--
-- Indexes for table `tmp_open_days`
--
ALTER TABLE `tmp_open_days`
 ADD KEY `idx_tmp_open_days_locatie_id` (`locatie_id`), ADD KEY `idx_tmp_open_days_open_day` (`open_day`);

--
-- Indexes for table `tmp_visitors`
--
ALTER TABLE `tmp_visitors`
 ADD KEY `idx_tmp_visitors_land_id` (`land_id`), ADD KEY `idx_tmp_visitors_verslaving_id` (`verslaving_id`), ADD KEY `idx_tmp_visitors_klant_id` (`klant_id`), ADD KEY `idx_tmp_visitors_date` (`date`), ADD KEY `idx_tmp_visitors_woonsituatie_id` (`woonsituatie_id`), ADD KEY `idx_tmp_visitors_verblijfstatus_id` (`verblijfstatus_id`), ADD KEY `idx_tmp_visitors_geslacht` (`geslacht`);

--
-- Indexes for table `tmp_visits`
--
ALTER TABLE `tmp_visits`
 ADD KEY `idx_tmp_visits_locatie_id` (`locatie_id`), ADD KEY `idx_tmp_visits_klant_id` (`klant_id`), ADD KEY `idx_tmp_visits_date` (`date`), ADD KEY `idx_tmp_visits_duration` (`duration`), ADD KEY `idx_tmp_visits_gender` (`gender`);

--
-- Indexes for table `trajecten`
--
ALTER TABLE `trajecten`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `uq_klant_id` (`klant_id`);

--
-- Indexes for table `verblijfstatussen`
--
ALTER TABLE `verblijfstatussen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verslagen`
--
ALTER TABLE `verslagen`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_klant` (`klant_id`), ADD KEY `idx_locatie_id` (`locatie_id`), ADD KEY `idx_datum` (`datum`);

--
-- Indexes for table `verslaginfos`
--
ALTER TABLE `verslaginfos`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `uq_klant` (`klant_id`);

--
-- Indexes for table `verslavingen`
--
ALTER TABLE `verslavingen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verslavingsfrequenties`
--
ALTER TABLE `verslavingsfrequenties`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verslavingsgebruikswijzen`
--
ALTER TABLE `verslavingsgebruikswijzen`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verslavingsperiodes`
--
ALTER TABLE `verslavingsperiodes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vrijwilligers`
--
ALTER TABLE `vrijwilligers`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_klanten_geboortedatum` (`geboortedatum`), ADD KEY `idx_vrijwilligers_werkgebied` (`werkgebied`);

--
-- Indexes for table `woonsituaties`
--
ALTER TABLE `woonsituaties`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zrm_reports`
--
ALTER TABLE `zrm_reports`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zrm_settings`
--
ALTER TABLE `zrm_settings`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `awbz_hoofdaannemers`
--
ALTER TABLE `awbz_hoofdaannemers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `awbz_indicaties`
--
ALTER TABLE `awbz_indicaties`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `awbz_intakes`
--
ALTER TABLE `awbz_intakes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `awbz_intakes_primaireproblematieksgebruikswijzen`
--
ALTER TABLE `awbz_intakes_primaireproblematieksgebruikswijzen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `awbz_intakes_verslavingen`
--
ALTER TABLE `awbz_intakes_verslavingen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `awbz_intakes_verslavingsgebruikswijzen`
--
ALTER TABLE `awbz_intakes_verslavingsgebruikswijzen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `back_on_tracks`
--
ALTER TABLE `back_on_tracks`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bedrijfitems`
--
ALTER TABLE `bedrijfitems`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bedrijfsectoren`
--
ALTER TABLE `bedrijfsectoren`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bot_koppelingen`
--
ALTER TABLE `bot_koppelingen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bot_verslagen`
--
ALTER TABLE `bot_verslagen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `categorieen`
--
ALTER TABLE `categorieen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `contactjournals`
--
ALTER TABLE `contactjournals`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `contactsoorts`
--
ALTER TABLE `contactsoorts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `doorverwijzers`
--
ALTER TABLE `doorverwijzers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gd27`
--
ALTER TABLE `gd27`
MODIFY `idd` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `geslachten`
--
ALTER TABLE `geslachten`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groepsactiviteiten`
--
ALTER TABLE `groepsactiviteiten`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groepsactiviteiten_afsluitingen`
--
ALTER TABLE `groepsactiviteiten_afsluitingen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groepsactiviteiten_groepen`
--
ALTER TABLE `groepsactiviteiten_groepen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groepsactiviteiten_groepen_klanten`
--
ALTER TABLE `groepsactiviteiten_groepen_klanten`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groepsactiviteiten_groepen_vrijwilligers`
--
ALTER TABLE `groepsactiviteiten_groepen_vrijwilligers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groepsactiviteiten_intakes`
--
ALTER TABLE `groepsactiviteiten_intakes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groepsactiviteiten_klanten`
--
ALTER TABLE `groepsactiviteiten_klanten`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groepsactiviteiten_redenen`
--
ALTER TABLE `groepsactiviteiten_redenen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groepsactiviteiten_verslagen`
--
ALTER TABLE `groepsactiviteiten_verslagen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groepsactiviteiten_vrijwilligers`
--
ALTER TABLE `groepsactiviteiten_vrijwilligers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hi5_answers`
--
ALTER TABLE `hi5_answers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hi5_answer_types`
--
ALTER TABLE `hi5_answer_types`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hi5_evaluaties`
--
ALTER TABLE `hi5_evaluaties`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hi5_evaluaties_hi5_evaluatie_questions`
--
ALTER TABLE `hi5_evaluaties_hi5_evaluatie_questions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hi5_evaluatie_paragraphs`
--
ALTER TABLE `hi5_evaluatie_paragraphs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hi5_evaluatie_questions`
--
ALTER TABLE `hi5_evaluatie_questions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hi5_intakes`
--
ALTER TABLE `hi5_intakes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hi5_intakes_answers`
--
ALTER TABLE `hi5_intakes_answers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hi5_intakes_inkomens`
--
ALTER TABLE `hi5_intakes_inkomens`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hi5_intakes_instanties`
--
ALTER TABLE `hi5_intakes_instanties`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hi5_intakes_primaireproblematieksgebruikswijzen`
--
ALTER TABLE `hi5_intakes_primaireproblematieksgebruikswijzen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hi5_intakes_verslavingen`
--
ALTER TABLE `hi5_intakes_verslavingen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hi5_intakes_verslavingsgebruikswijzen`
--
ALTER TABLE `hi5_intakes_verslavingsgebruikswijzen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hi5_questions`
--
ALTER TABLE `hi5_questions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hoofdaannemers`
--
ALTER TABLE `hoofdaannemers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `i18n`
--
ALTER TABLE `i18n`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `infobaliedoelgroepen`
--
ALTER TABLE `infobaliedoelgroepen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inkomens`
--
ALTER TABLE `inkomens`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inkomens_awbz_intakes`
--
ALTER TABLE `inkomens_awbz_intakes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inkomens_intakes`
--
ALTER TABLE `inkomens_intakes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `instanties`
--
ALTER TABLE `instanties`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `instanties_awbz_intakes`
--
ALTER TABLE `instanties_awbz_intakes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `instanties_intakes`
--
ALTER TABLE `instanties_intakes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `intakes`
--
ALTER TABLE `intakes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `intakes_primaireproblematieksgebruikswijzen`
--
ALTER TABLE `intakes_primaireproblematieksgebruikswijzen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `intakes_verslavingen`
--
ALTER TABLE `intakes_verslavingen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `intakes_verslavingsgebruikswijzen`
--
ALTER TABLE `intakes_verslavingsgebruikswijzen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inventarisaties`
--
ALTER TABLE `inventarisaties`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inventarisaties_verslagen`
--
ALTER TABLE `inventarisaties_verslagen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `iz_afsluitingen`
--
ALTER TABLE `iz_afsluitingen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `iz_deelnemers`
--
ALTER TABLE `iz_deelnemers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `iz_deelnemers_iz_intervisiegroepen`
--
ALTER TABLE `iz_deelnemers_iz_intervisiegroepen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `iz_deelnemers_iz_projecten`
--
ALTER TABLE `iz_deelnemers_iz_projecten`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `iz_eindekoppelingen`
--
ALTER TABLE `iz_eindekoppelingen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `iz_intakes`
--
ALTER TABLE `iz_intakes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `iz_intervisiegroepen`
--
ALTER TABLE `iz_intervisiegroepen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `iz_koppelingen`
--
ALTER TABLE `iz_koppelingen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `iz_ontstaan_contacten`
--
ALTER TABLE `iz_ontstaan_contacten`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `iz_projecten`
--
ALTER TABLE `iz_projecten`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `iz_verslagen`
--
ALTER TABLE `iz_verslagen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `iz_via_personen`
--
ALTER TABLE `iz_via_personen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `iz_vraagaanboden`
--
ALTER TABLE `iz_vraagaanboden`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `klanten`
--
ALTER TABLE `klanten`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `klantinventarisaties`
--
ALTER TABLE `klantinventarisaties`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `landen`
--
ALTER TABLE `landen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `legitimaties`
--
ALTER TABLE `legitimaties`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `locaties`
--
ALTER TABLE `locaties`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `locatie_tijden`
--
ALTER TABLE `locatie_tijden`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `medewerkers`
--
ALTER TABLE `medewerkers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `nationaliteiten`
--
ALTER TABLE `nationaliteiten`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notities`
--
ALTER TABLE `notities`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `opmerkingen`
--
ALTER TABLE `opmerkingen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pfo_aard_relaties`
--
ALTER TABLE `pfo_aard_relaties`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pfo_clienten`
--
ALTER TABLE `pfo_clienten`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pfo_clienten_supportgroups`
--
ALTER TABLE `pfo_clienten_supportgroups`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pfo_clienten_verslagen`
--
ALTER TABLE `pfo_clienten_verslagen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pfo_groepen`
--
ALTER TABLE `pfo_groepen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pfo_verslagen`
--
ALTER TABLE `pfo_verslagen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `postcodegebieden`
--
ALTER TABLE `postcodegebieden`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `queue_tasks`
--
ALTER TABLE `queue_tasks`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `redenen`
--
ALTER TABLE `redenen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `registraties`
--
ALTER TABLE `registraties`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `schorsingen`
--
ALTER TABLE `schorsingen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `schorsingen_redenen`
--
ALTER TABLE `schorsingen_redenen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `trajecten`
--
ALTER TABLE `trajecten`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `verblijfstatussen`
--
ALTER TABLE `verblijfstatussen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `verslagen`
--
ALTER TABLE `verslagen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `verslaginfos`
--
ALTER TABLE `verslaginfos`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `verslavingen`
--
ALTER TABLE `verslavingen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `verslavingsfrequenties`
--
ALTER TABLE `verslavingsfrequenties`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `verslavingsgebruikswijzen`
--
ALTER TABLE `verslavingsgebruikswijzen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `verslavingsperiodes`
--
ALTER TABLE `verslavingsperiodes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `vrijwilligers`
--
ALTER TABLE `vrijwilligers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `woonsituaties`
--
ALTER TABLE `woonsituaties`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `zrm_reports`
--
ALTER TABLE `zrm_reports`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `zrm_settings`
--
ALTER TABLE `zrm_settings`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;