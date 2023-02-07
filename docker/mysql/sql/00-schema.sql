-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Gegenereerd op: 20 jan 2023 om 08:18
-- Serverversie: 8.0.31-0ubuntu0.20.04.2
-- PHP-versie: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecd`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `amoc_landen`
--

CREATE TABLE `amoc_landen` (
  `id` int NOT NULL,
  `land_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `app_klant_document`
--

CREATE TABLE `app_klant_document` (
  `klant_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `app_vrijwilliger_document`
--

CREATE TABLE `app_vrijwilliger_document` (
  `vrijwilliger_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `attachments`
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
  `user_id` int DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `awbz_hoofdaannemers`
--

CREATE TABLE `awbz_hoofdaannemers` (
  `id` int NOT NULL,
  `klant_id` int DEFAULT NULL,
  `begindatum` date NOT NULL,
  `einddatum` date DEFAULT NULL,
  `hoofdaannemer_id` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `awbz_indicaties`
--

CREATE TABLE `awbz_indicaties` (
  `id` int NOT NULL,
  `klant_id` int DEFAULT NULL,
  `begindatum` date NOT NULL,
  `einddatum` date DEFAULT NULL,
  `begeleiding_per_week` int DEFAULT NULL,
  `activering_per_week` int DEFAULT NULL,
  `hoofdaannemer_id` int DEFAULT NULL,
  `aangevraagd_id` int DEFAULT NULL,
  `aangevraagd_datum` date DEFAULT NULL,
  `aangevraagd_niet` tinyint(1) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `awbz_intakes`
--

CREATE TABLE `awbz_intakes` (
  `id` int NOT NULL,
  `klant_id` int DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL,
  `datum_intake` date DEFAULT NULL,
  `verblijfstatus_id` int DEFAULT NULL,
  `postadres` varchar(255) DEFAULT NULL,
  `postcode` varchar(6) DEFAULT NULL,
  `woonplaats` varchar(255) DEFAULT NULL,
  `verblijf_in_NL_sinds` date DEFAULT NULL,
  `verblijf_in_amsterdam_sinds` date DEFAULT NULL,
  `legitimatie_id` int DEFAULT NULL,
  `legitimatie_nummer` varchar(255) DEFAULT NULL,
  `legitimatie_geldig_tot` date DEFAULT NULL,
  `verslavingsfrequentie_id` int DEFAULT NULL,
  `verslavingsperiode_id` int DEFAULT NULL,
  `woonsituatie_id` int DEFAULT NULL,
  `verwachting_dienstaanbod` text,
  `toekomstplannen` text,
  `opmerking_andere_instanties` text,
  `medische_achtergrond` text,
  `locatie1_id` int DEFAULT NULL,
  `locatie2_id` int DEFAULT NULL,
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
  `primaireproblematiek_id` int DEFAULT NULL,
  `primaireproblematieksfrequentie_id` int DEFAULT NULL,
  `primaireproblematieksperiode_id` int DEFAULT NULL,
  `eerste_gebruik` date DEFAULT NULL,
  `locatie3_id` int DEFAULT NULL,
  `infobaliedoelgroep_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `awbz_intakes_primaireproblematieksgebruikswijzen`
--

CREATE TABLE `awbz_intakes_primaireproblematieksgebruikswijzen` (
  `id` int NOT NULL,
  `awbz_intake_id` int NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `primaireproblematieksgebruikswijze_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `awbz_intakes_verslavingen`
--

CREATE TABLE `awbz_intakes_verslavingen` (
  `id` int NOT NULL,
  `awbz_intake_id` int NOT NULL,
  `verslaving_id` int NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `awbz_intakes_verslavingsgebruikswijzen`
--

CREATE TABLE `awbz_intakes_verslavingsgebruikswijzen` (
  `id` int NOT NULL,
  `awbz_intake_id` int NOT NULL,
  `verslavingsgebruikswijze_id` int NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `backup_iz_deelnemers`
--

CREATE TABLE `backup_iz_deelnemers` (
  `id` int NOT NULL DEFAULT '0',
  `model` varchar(50) CHARACTER SET utf8mb3 NOT NULL,
  `foreign_key` int NOT NULL,
  `datum_aanmelding` date DEFAULT NULL,
  `binnengekomen_via` varchar(50) CHARACTER SET utf8mb3 DEFAULT NULL,
  `organisatie` varchar(100) CHARACTER SET utf8mb3 DEFAULT NULL,
  `naam_aanmelder` varchar(100) CHARACTER SET utf8mb3 DEFAULT NULL,
  `email_aanmelder` varchar(100) CHARACTER SET utf8mb3 DEFAULT NULL,
  `telefoon_aanmelder` varchar(100) CHARACTER SET utf8mb3 DEFAULT NULL,
  `notitie` text CHARACTER SET utf8mb3,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `datumafsluiting` date DEFAULT NULL,
  `iz_afsluiting_id` int DEFAULT NULL,
  `contact_ontstaan` varchar(100) CHARACTER SET utf8mb3 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `back_on_tracks`
--

CREATE TABLE `back_on_tracks` (
  `id` int NOT NULL,
  `klant_id` int NOT NULL,
  `startdatum` date DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `intakedatum` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `bedrijfitems`
--

CREATE TABLE `bedrijfitems` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `bedrijfsector_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `bedrijfsectoren`
--

CREATE TABLE `bedrijfsectoren` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `bot_koppelingen`
--

CREATE TABLE `bot_koppelingen` (
  `id` int NOT NULL,
  `medewerker_id` int DEFAULT NULL,
  `back_on_track_id` int DEFAULT NULL,
  `startdatum` date DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `bot_verslagen`
--

CREATE TABLE `bot_verslagen` (
  `id` int NOT NULL,
  `contact_type` varchar(50) DEFAULT NULL,
  `verslag` text,
  `medewerker_id` int DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `klant_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `buurtboerderij_afsluitredenen`
--

CREATE TABLE `buurtboerderij_afsluitredenen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `buurtboerderij_vrijwilligers`
--

CREATE TABLE `buurtboerderij_vrijwilligers` (
  `id` int NOT NULL,
  `vrijwilliger_id` int NOT NULL,
  `afsluitreden_id` int DEFAULT NULL,
  `medewerker_id` int NOT NULL,
  `aanmelddatum` date NOT NULL,
  `afsluitdatum` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `categorieen`
--

CREATE TABLE `categorieen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_afsluitredenen_vrijwilligers`
--

CREATE TABLE `clip_afsluitredenen_vrijwilligers` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_behandelaars`
--

CREATE TABLE `clip_behandelaars` (
  `id` int NOT NULL,
  `medewerker_id` int DEFAULT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_binnen_via`
--

CREATE TABLE `clip_binnen_via` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_clienten`
--

CREATE TABLE `clip_clienten` (
  `id` int NOT NULL,
  `viacategorie_id` int DEFAULT NULL,
  `behandelaar_id` int DEFAULT NULL,
  `aanmelddatum` date NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `geslacht_id` int DEFAULT NULL,
  `werkgebied` varchar(255) DEFAULT NULL,
  `postcodegebied` varchar(255) DEFAULT NULL,
  `etniciteit` varchar(255) DEFAULT NULL,
  `geboortedatum` date DEFAULT NULL,
  `voornaam` varchar(255) DEFAULT NULL,
  `roepnaam` varchar(255) DEFAULT NULL,
  `tussenvoegsel` varchar(255) DEFAULT NULL,
  `achternaam` varchar(255) DEFAULT NULL,
  `adres` varchar(255) DEFAULT NULL,
  `postcode` varchar(255) DEFAULT NULL,
  `plaats` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobiel` varchar(255) DEFAULT NULL,
  `telefoon` varchar(255) DEFAULT NULL,
  `regipro_volgnr` varchar(255) DEFAULT NULL,
  `regipro_person_id` varchar(255) DEFAULT NULL,
  `regipro_client_id` varchar(255) DEFAULT NULL,
  `organisatie` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_client_document`
--

CREATE TABLE `clip_client_document` (
  `client_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_communicatiekanalen`
--

CREATE TABLE `clip_communicatiekanalen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_contactmomenten`
--

CREATE TABLE `clip_contactmomenten` (
  `id` int NOT NULL,
  `vraag_id` int NOT NULL,
  `behandelaar_id` int DEFAULT NULL,
  `datum` date NOT NULL,
  `opmerking` longtext,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_deelnames`
--

CREATE TABLE `clip_deelnames` (
  `id` int NOT NULL,
  `clip_vrijwilliger_id` int NOT NULL,
  `overig` varchar(255) DEFAULT NULL,
  `datum` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `mwTraining_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_documenten`
--

CREATE TABLE `clip_documenten` (
  `id` int NOT NULL,
  `behandelaar_id` int DEFAULT NULL,
  `naam` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_hulpvragersoorten`
--

CREATE TABLE `clip_hulpvragersoorten` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_leeftijdscategorieen`
--

CREATE TABLE `clip_leeftijdscategorieen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_locaties`
--

CREATE TABLE `clip_locaties` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_memos`
--

CREATE TABLE `clip_memos` (
  `id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `datum` datetime NOT NULL,
  `onderwerp` varchar(255) NOT NULL,
  `memo` longtext NOT NULL,
  `intake` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_training`
--

CREATE TABLE `clip_training` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_viacategorieen`
--

CREATE TABLE `clip_viacategorieen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_vraagsoorten`
--

CREATE TABLE `clip_vraagsoorten` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_vraag_document`
--

CREATE TABLE `clip_vraag_document` (
  `vraag_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_vragen`
--

CREATE TABLE `clip_vragen` (
  `id` int NOT NULL,
  `client_id` int NOT NULL,
  `soort_id` int NOT NULL,
  `hulpvrager_id` int DEFAULT NULL,
  `communicatiekanaal_id` int DEFAULT NULL,
  `leeftijdscategorie_id` int DEFAULT NULL,
  `behandelaar_id` int DEFAULT NULL,
  `omschrijving` varchar(255) NOT NULL,
  `startdatum` date NOT NULL,
  `afsluitdatum` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `hulpCollegaGezocht` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_vrijwilligers`
--

CREATE TABLE `clip_vrijwilligers` (
  `id` int NOT NULL,
  `vrijwilliger_id` int NOT NULL,
  `binnen_via_id` int DEFAULT NULL,
  `afsluitreden_id` int DEFAULT NULL,
  `medewerker_id` int NOT NULL,
  `aanmelddatum` date NOT NULL,
  `afsluitdatum` date DEFAULT NULL,
  `stagiair` tinyint(1) NOT NULL,
  `startdatum` date DEFAULT NULL,
  `notitieIntake` varchar(255) DEFAULT NULL,
  `datumNotitieIntake` datetime DEFAULT NULL,
  `trainingOverig` varchar(255) DEFAULT NULL,
  `trainingOverigDatum` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `medewerkerLocatie_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_vrijwilliger_document`
--

CREATE TABLE `clip_vrijwilliger_document` (
  `vrijwilliger_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_vrijwilliger_locatie`
--

CREATE TABLE `clip_vrijwilliger_locatie` (
  `vrijwilliger_id` int NOT NULL,
  `locatie_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_vrijwilliger_memo`
--

CREATE TABLE `clip_vrijwilliger_memo` (
  `vrijwilliger_id` int NOT NULL,
  `memo_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contactjournals`
--

CREATE TABLE `contactjournals` (
  `id` int NOT NULL,
  `klant_id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `datum` date NOT NULL,
  `text` text NOT NULL,
  `is_tb` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contactsoorts`
--

CREATE TABLE `contactsoorts` (
  `id` int NOT NULL,
  `text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_afsluitingen`
--

CREATE TABLE `dagbesteding_afsluitingen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `discr` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_beschikbaarheid`
--

CREATE TABLE `dagbesteding_beschikbaarheid` (
  `id` int NOT NULL,
  `deelname_id` int DEFAULT NULL,
  `maandagVan` time DEFAULT NULL,
  `maandagTot` time DEFAULT NULL,
  `dinsdagVan` time DEFAULT NULL,
  `dinsdagTot` time DEFAULT NULL,
  `woensdagVan` time DEFAULT NULL,
  `woensdagTot` time DEFAULT NULL,
  `donderdagVan` time DEFAULT NULL,
  `donderdagTot` time DEFAULT NULL,
  `vrijdagVan` time DEFAULT NULL,
  `vrijdagTot` time DEFAULT NULL,
  `zaterdagVan` time DEFAULT NULL,
  `zaterdagTot` time DEFAULT NULL,
  `zondagVan` time DEFAULT NULL,
  `zondagTot` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_contactpersonen`
--

CREATE TABLE `dagbesteding_contactpersonen` (
  `id` int NOT NULL,
  `deelnemer_id` int DEFAULT NULL,
  `soort` varchar(255) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `telefoon` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `opmerking` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_dagdelen`
--

CREATE TABLE `dagbesteding_dagdelen` (
  `id` int NOT NULL,
  `traject_id` int NOT NULL,
  `project_id` int NOT NULL,
  `datum` date NOT NULL,
  `dagdeel` varchar(255) NOT NULL,
  `aanwezigheid` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_deelnames`
--

CREATE TABLE `dagbesteding_deelnames` (
  `id` int NOT NULL,
  `traject_id` int DEFAULT NULL,
  `project_id` int DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_deelnemers`
--

CREATE TABLE `dagbesteding_deelnemers` (
  `id` int NOT NULL,
  `afsluiting_id` int DEFAULT NULL,
  `klant_id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `risDossiernummer` varchar(255) DEFAULT NULL,
  `aanmelddatum` date NOT NULL,
  `afsluitdatum` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `werkbegeleider` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_deelnemer_document`
--

CREATE TABLE `dagbesteding_deelnemer_document` (
  `deelnemer_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_deelnemer_verslag`
--

CREATE TABLE `dagbesteding_deelnemer_verslag` (
  `deelnemer_id` int NOT NULL,
  `verslag_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_documenten`
--

CREATE TABLE `dagbesteding_documenten` (
  `id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_locaties`
--

CREATE TABLE `dagbesteding_locaties` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_projecten`
--

CREATE TABLE `dagbesteding_projecten` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `kpl` int DEFAULT NULL,
  `locatie_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_rapportages`
--

CREATE TABLE `dagbesteding_rapportages` (
  `id` int NOT NULL,
  `traject_id` int DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL,
  `datum` date NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_rapportage_document`
--

CREATE TABLE `dagbesteding_rapportage_document` (
  `rapportage_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_resultaatgebieden`
--

CREATE TABLE `dagbesteding_resultaatgebieden` (
  `id` int NOT NULL,
  `traject_id` int DEFAULT NULL,
  `soort_id` int DEFAULT NULL,
  `startdatum` date NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_resultaatgebiedsoorten`
--

CREATE TABLE `dagbesteding_resultaatgebiedsoorten` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_trajectcoaches`
--

CREATE TABLE `dagbesteding_trajectcoaches` (
  `id` int NOT NULL,
  `medewerker_id` int DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_trajecten`
--

CREATE TABLE `dagbesteding_trajecten` (
  `id` int NOT NULL,
  `deelnemer_id` int NOT NULL,
  `soort_id` int NOT NULL,
  `resultaatgebied_id` int DEFAULT NULL,
  `trajectcoach_id` int DEFAULT NULL,
  `afsluiting_id` int DEFAULT NULL,
  `startdatum` date NOT NULL,
  `einddatum` date NOT NULL,
  `afsluitdatum` date DEFAULT NULL,
  `ondersteuningsplanVerwerkt` tinyint(1) DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `evaluatiedatum` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_trajectsoorten`
--

CREATE TABLE `dagbesteding_trajectsoorten` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_traject_document`
--

CREATE TABLE `dagbesteding_traject_document` (
  `traject_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_traject_locatie`
--

CREATE TABLE `dagbesteding_traject_locatie` (
  `traject_id` int NOT NULL,
  `locatie_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_traject_project`
--

CREATE TABLE `dagbesteding_traject_project` (
  `traject_id` int NOT NULL,
  `project_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_traject_verslag`
--

CREATE TABLE `dagbesteding_traject_verslag` (
  `traject_id` int NOT NULL,
  `verslag_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_verslagen`
--

CREATE TABLE `dagbesteding_verslagen` (
  `id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `datum` datetime NOT NULL,
  `opmerking` longtext,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_werkdoelen`
--

CREATE TABLE `dagbesteding_werkdoelen` (
  `id` int NOT NULL,
  `deelnemer_id` int DEFAULT NULL,
  `traject_id` int DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL,
  `tekst` longtext,
  `datum` date NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `documenten`
--

CREATE TABLE `documenten` (
  `id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `discr` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `doelstellingen`
--

CREATE TABLE `doelstellingen` (
  `id` int NOT NULL,
  `repository` varchar(255) DEFAULT NULL,
  `jaar` int NOT NULL,
  `aantal` int NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `kostenplaats` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `doorverwijzers`
--

CREATE TABLE `doorverwijzers` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `startdatum` date NOT NULL,
  `einddatum` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `eropuit_klanten`
--

CREATE TABLE `eropuit_klanten` (
  `id` int NOT NULL,
  `klant_id` int NOT NULL,
  `uitschrijfreden_id` int DEFAULT NULL,
  `inschrijfdatum` date NOT NULL,
  `uitschrijfdatum` date DEFAULT NULL,
  `communicatieEmail` tinyint(1) DEFAULT NULL,
  `communicatieTelefoon` tinyint(1) DEFAULT NULL,
  `communicatiePost` tinyint(1) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `eropuit_uitschrijfredenen`
--

CREATE TABLE `eropuit_uitschrijfredenen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `eropuit_vrijwilligers`
--

CREATE TABLE `eropuit_vrijwilligers` (
  `id` int NOT NULL,
  `vrijwilliger_id` int NOT NULL,
  `uitschrijfreden_id` int DEFAULT NULL,
  `inschrijfdatum` date NOT NULL,
  `uitschrijfdatum` date DEFAULT NULL,
  `communicatieEmail` tinyint(1) DEFAULT NULL,
  `communicatieTelefoon` tinyint(1) DEFAULT NULL,
  `communicatiePost` tinyint(1) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ext_log_entries`
--

CREATE TABLE `ext_log_entries` (
  `id` int NOT NULL,
  `action` varchar(8) NOT NULL,
  `logged_at` datetime NOT NULL,
  `object_id` varchar(64) DEFAULT NULL,
  `object_class` varchar(255) NOT NULL,
  `version` int NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '(DC2Type:array)',
  `username` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ga_activiteitannuleringsredenen`
--

CREATE TABLE `ga_activiteitannuleringsredenen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ga_activiteiten`
--

CREATE TABLE `ga_activiteiten` (
  `id` int NOT NULL,
  `groep_id` int DEFAULT NULL,
  `annuleringsreden_id` int DEFAULT NULL,
  `naam` varchar(255) NOT NULL,
  `datum` datetime DEFAULT NULL,
  `afgesloten` tinyint(1) DEFAULT NULL,
  `aantalAnoniemeDeelnemers` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ga_deelnames`
--

CREATE TABLE `ga_deelnames` (
  `id` int NOT NULL,
  `activiteit_id` int NOT NULL,
  `dossier_id` int NOT NULL,
  `status` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ga_documenten`
--

CREATE TABLE `ga_documenten` (
  `id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ga_dossierafsluitredenen`
--

CREATE TABLE `ga_dossierafsluitredenen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ga_dossiers`
--

CREATE TABLE `ga_dossiers` (
  `id` int NOT NULL,
  `afsluitreden_id` int DEFAULT NULL,
  `klant_id` int DEFAULT NULL,
  `vrijwilliger_id` int DEFAULT NULL,
  `aanmelddatum` date NOT NULL,
  `afsluitdatum` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `discr` varchar(255) NOT NULL,
  `medewerker_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ga_dossier_document`
--

CREATE TABLE `ga_dossier_document` (
  `dossier_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ga_gaklantintake_zrm`
--

CREATE TABLE `ga_gaklantintake_zrm` (
  `gaklantintake_id` int NOT NULL,
  `zrm_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ga_groepen`
--

CREATE TABLE `ga_groepen` (
  `id` int NOT NULL,
  `werkgebied` varchar(255) DEFAULT NULL,
  `naam` varchar(100) NOT NULL,
  `activiteitenRegistreren` tinyint(1) NOT NULL,
  `startdatum` date NOT NULL,
  `einddatum` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `discr` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ga_intakes`
--

CREATE TABLE `ga_intakes` (
  `id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `dossier_id` int DEFAULT NULL,
  `gespreksverslag` longtext,
  `intakedatum` date DEFAULT NULL,
  `ondernemen` tinyint(1) DEFAULT NULL,
  `overdag` tinyint(1) DEFAULT NULL,
  `ontmoeten` tinyint(1) DEFAULT NULL,
  `regelzaken` tinyint(1) DEFAULT NULL,
  `informele_zorg` tinyint(1) DEFAULT NULL,
  `dagbesteding` tinyint(1) DEFAULT NULL,
  `inloophuis` tinyint(1) DEFAULT NULL,
  `hulpverlening` tinyint(1) DEFAULT NULL,
  `gezin_met_kinderen` tinyint(1) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ga_intake_zrm`
--

CREATE TABLE `ga_intake_zrm` (
  `intake_id` int NOT NULL,
  `zrm_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ga_lidmaatschappen`
--

CREATE TABLE `ga_lidmaatschappen` (
  `id` int NOT NULL,
  `groep_id` int NOT NULL,
  `dossier_id` int NOT NULL,
  `groepsactiviteiten_reden_id` int DEFAULT NULL,
  `startdatum` date DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `communicatieEmail` tinyint(1) DEFAULT NULL,
  `communicatieTelefoon` tinyint(1) DEFAULT NULL,
  `communicatiePost` tinyint(1) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ga_memos`
--

CREATE TABLE `ga_memos` (
  `id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `datum` datetime NOT NULL,
  `onderwerp` varchar(255) NOT NULL,
  `memo` longtext NOT NULL,
  `intake` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ga_redenen`
--

CREATE TABLE `ga_redenen` (
  `id` int NOT NULL,
  `naam` varchar(100) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ga_verslagen`
--

CREATE TABLE `ga_verslagen` (
  `id` int NOT NULL,
  `dossier_id` int DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL,
  `opmerking` longtext,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `gd27`
--

CREATE TABLE `gd27` (
  `naam` varchar(50) DEFAULT NULL,
  `voornaam` varchar(50) DEFAULT NULL,
  `achternaam` varchar(50) DEFAULT NULL,
  `geboortedatum` datetime DEFAULT NULL,
  `klant_id` int DEFAULT NULL,
  `db_voornaam` varchar(50) DEFAULT NULL,
  `db_achternaam` varchar(50) DEFAULT NULL,
  `roepnaam` varchar(50) DEFAULT NULL,
  `land` varchar(255) DEFAULT NULL,
  `nationaliteit` varchar(255) DEFAULT NULL,
  `woonsituatie` varchar(255) DEFAULT NULL,
  `inschrijving` date DEFAULT NULL,
  `id` int DEFAULT NULL,
  `idd` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `geslachten`
--

CREATE TABLE `geslachten` (
  `id` int NOT NULL,
  `afkorting` varchar(255) NOT NULL,
  `volledig` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ggw_gebieden`
--

CREATE TABLE `ggw_gebieden` (
  `naam` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `groepsactiviteiten`
--

CREATE TABLE `groepsactiviteiten` (
  `id` int NOT NULL,
  `groepsactiviteiten_groep_id` int NOT NULL,
  `naam` varchar(100) DEFAULT NULL,
  `datum` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `afgesloten` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `groepsactiviteiten_afsluitingen`
--

CREATE TABLE `groepsactiviteiten_afsluitingen` (
  `id` int NOT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `groepsactiviteiten_groepen`
--

CREATE TABLE `groepsactiviteiten_groepen` (
  `id` int NOT NULL,
  `naam` varchar(100) NOT NULL,
  `startdatum` date DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `werkgebied` varchar(20) DEFAULT NULL,
  `activiteiten_registreren` tinyint(1) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `groepsactiviteiten_groepen_klanten`
--

CREATE TABLE `groepsactiviteiten_groepen_klanten` (
  `id` int NOT NULL,
  `groepsactiviteiten_groep_id` int NOT NULL,
  `klant_id` int NOT NULL,
  `groepsactiviteiten_reden_id` int DEFAULT NULL,
  `startdatum` date DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `communicatie_email` tinyint(1) DEFAULT NULL,
  `communicatie_telefoon` tinyint(1) DEFAULT NULL,
  `communicatie_post` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `groepsactiviteiten_groepen_vrijwilligers`
--

CREATE TABLE `groepsactiviteiten_groepen_vrijwilligers` (
  `id` int NOT NULL,
  `groepsactiviteiten_groep_id` int NOT NULL,
  `vrijwilliger_id` int NOT NULL,
  `groepsactiviteiten_reden_id` int DEFAULT NULL,
  `startdatum` date DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `communicatie_email` tinyint(1) DEFAULT NULL,
  `communicatie_telefoon` tinyint(1) DEFAULT NULL,
  `communicatie_post` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `groepsactiviteiten_intakes`
--

CREATE TABLE `groepsactiviteiten_intakes` (
  `id` int NOT NULL,
  `model` varchar(255) NOT NULL,
  `foreign_key` int NOT NULL,
  `medewerker_id` int DEFAULT NULL,
  `gespreksverslag` text,
  `informele_zorg` int DEFAULT NULL,
  `dagbesteding` int DEFAULT NULL,
  `inloophuis` int DEFAULT NULL,
  `hulpverlening` int DEFAULT NULL,
  `intakedatum` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `groepsactiviteiten_afsluiting_id` int DEFAULT NULL,
  `ondernemen` tinyint(1) DEFAULT NULL,
  `overdag` tinyint(1) DEFAULT NULL,
  `ontmoeten` tinyint(1) DEFAULT NULL,
  `regelzaken` tinyint(1) DEFAULT NULL,
  `gezin_met_kinderen` tinyint(1) DEFAULT NULL,
  `afsluitdatum` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `groepsactiviteiten_klanten`
--

CREATE TABLE `groepsactiviteiten_klanten` (
  `id` int NOT NULL,
  `groepsactiviteit_id` int DEFAULT NULL,
  `klant_id` int DEFAULT NULL,
  `afmeld_status` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `groepsactiviteiten_redenen`
--

CREATE TABLE `groepsactiviteiten_redenen` (
  `id` int NOT NULL,
  `naam` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `groepsactiviteiten_verslagen`
--

CREATE TABLE `groepsactiviteiten_verslagen` (
  `id` int NOT NULL,
  `model` varchar(255) NOT NULL,
  `foreign_key` int NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL,
  `opmerking` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `groepsactiviteiten_vrijwilligers`
--

CREATE TABLE `groepsactiviteiten_vrijwilligers` (
  `id` int NOT NULL,
  `groepsactiviteit_id` int DEFAULT NULL,
  `vrijwilliger_id` int DEFAULT NULL,
  `afmeld_status` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hi5_answers`
--

CREATE TABLE `hi5_answers` (
  `id` int NOT NULL,
  `answer` varchar(255) NOT NULL,
  `hi5_question_id` int NOT NULL,
  `hi5_answer_type_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hi5_answer_types`
--

CREATE TABLE `hi5_answer_types` (
  `id` int NOT NULL,
  `answer_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hi5_evaluaties`
--

CREATE TABLE `hi5_evaluaties` (
  `id` int NOT NULL,
  `klant_id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `datumevaluatie` date NOT NULL,
  `werkproject` varchar(255) NOT NULL,
  `aantal_dagdelen` int NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hi5_evaluaties_hi5_evaluatie_questions`
--

CREATE TABLE `hi5_evaluaties_hi5_evaluatie_questions` (
  `id` int NOT NULL,
  `hi5_evaluatie_id` int NOT NULL,
  `hi5_evaluatie_question_id` int NOT NULL,
  `hi5er_radio` int NOT NULL,
  `hi5er_details` text NOT NULL,
  `wb_radio` int NOT NULL,
  `wb_details` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hi5_evaluatie_paragraphs`
--

CREATE TABLE `hi5_evaluatie_paragraphs` (
  `id` int NOT NULL,
  `text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hi5_evaluatie_questions`
--

CREATE TABLE `hi5_evaluatie_questions` (
  `id` int NOT NULL,
  `hi5_evaluatie_paragraph_id` int NOT NULL,
  `text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hi5_intakes`
--

CREATE TABLE `hi5_intakes` (
  `id` int NOT NULL,
  `klant_id` int DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL,
  `datum_intake` date DEFAULT NULL,
  `verblijfstatus_id` int DEFAULT NULL,
  `postadres` varchar(255) DEFAULT NULL,
  `postcode` varchar(6) DEFAULT NULL,
  `woonplaats` varchar(255) DEFAULT NULL,
  `verblijf_in_NL_sinds` date DEFAULT NULL,
  `verblijf_in_amsterdam_sinds` date DEFAULT NULL,
  `verslaving_overig` varchar(255) DEFAULT NULL,
  `inkomen_overig` varchar(255) DEFAULT NULL,
  `locatie1_id` int DEFAULT NULL,
  `locatie2_id` int DEFAULT NULL,
  `woonsituatie_id` int DEFAULT NULL,
  `werklocatie_id` int DEFAULT NULL,
  `mag_gebruiken` tinyint(1) DEFAULT NULL,
  `legitimatie_id` int DEFAULT NULL,
  `legitimatie_nummer` varchar(255) DEFAULT NULL,
  `legitimatie_geldig_tot` date DEFAULT NULL,
  `primaireproblematiek_id` int DEFAULT NULL,
  `primaireproblematieksfrequentie_id` int DEFAULT NULL,
  `primaireproblematieksperiode_id` int DEFAULT NULL,
  `eerste_gebruik` date DEFAULT NULL,
  `verslavingsfrequentie_id` int DEFAULT NULL,
  `verslavingsperiode_id` int DEFAULT NULL,
  `bedrijfitem_1_id` int DEFAULT NULL,
  `bedrijfitem_2_id` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `locatie3_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hi5_intakes_answers`
--

CREATE TABLE `hi5_intakes_answers` (
  `id` int NOT NULL,
  `hi5_intake_id` int NOT NULL,
  `hi5_answer_id` int NOT NULL,
  `hi5_answer_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hi5_intakes_inkomens`
--

CREATE TABLE `hi5_intakes_inkomens` (
  `id` int NOT NULL,
  `hi5_intake_id` int NOT NULL,
  `inkomen_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hi5_intakes_instanties`
--

CREATE TABLE `hi5_intakes_instanties` (
  `id` int NOT NULL,
  `hi5_intake_id` int NOT NULL,
  `instantie_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hi5_intakes_primaireproblematieksgebruikswijzen`
--

CREATE TABLE `hi5_intakes_primaireproblematieksgebruikswijzen` (
  `id` int NOT NULL,
  `hi5_intake_id` int NOT NULL,
  `primaireproblematieksgebruikswijze_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hi5_intakes_verslavingen`
--

CREATE TABLE `hi5_intakes_verslavingen` (
  `id` int NOT NULL,
  `hi5_intake_id` int NOT NULL,
  `verslaving_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hi5_intakes_verslavingsgebruikswijzen`
--

CREATE TABLE `hi5_intakes_verslavingsgebruikswijzen` (
  `id` int NOT NULL,
  `hi5_intake_id` int NOT NULL,
  `verslavingsgebruikswijze_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hi5_questions`
--

CREATE TABLE `hi5_questions` (
  `id` int NOT NULL,
  `question` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `order` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hoofdaannemers`
--

CREATE TABLE `hoofdaannemers` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_activiteiten`
--

CREATE TABLE `hs_activiteiten` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_arbeiders`
--

CREATE TABLE `hs_arbeiders` (
  `id` int NOT NULL,
  `inschrijving` date NOT NULL,
  `uitschrijving` date DEFAULT NULL,
  `rijbewijs` tinyint(1) DEFAULT NULL,
  `dtype` varchar(255) NOT NULL,
  `actief` tinyint(1) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_betalingen`
--

CREATE TABLE `hs_betalingen` (
  `id` int NOT NULL,
  `factuur_id` int NOT NULL,
  `referentie` varchar(255) DEFAULT NULL,
  `datum` date NOT NULL,
  `info` longtext,
  `bedrag` decimal(10,2) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_declaraties`
--

CREATE TABLE `hs_declaraties` (
  `id` int NOT NULL,
  `klus_id` int DEFAULT NULL,
  `factuur_id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `datum` date NOT NULL,
  `info` longtext NOT NULL,
  `bedrag` double NOT NULL,
  `declaratieCategorie_id` int NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_declaratie_categorieen`
--

CREATE TABLE `hs_declaratie_categorieen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_declaratie_document`
--

CREATE TABLE `hs_declaratie_document` (
  `declaratie_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_dienstverleners`
--

CREATE TABLE `hs_dienstverleners` (
  `id` int NOT NULL,
  `klant_id` int NOT NULL,
  `hulpverlener_naam` varchar(255) DEFAULT NULL,
  `hulpverlener_organisatie` varchar(255) DEFAULT NULL,
  `hulpverlener_telefoon` varchar(255) DEFAULT NULL,
  `hulpverlener_email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_dienstverlener_document`
--

CREATE TABLE `hs_dienstverlener_document` (
  `dienstverlener_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_dienstverlener_memo`
--

CREATE TABLE `hs_dienstverlener_memo` (
  `dienstverlener_id` int NOT NULL,
  `memo_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_documenten`
--

CREATE TABLE `hs_documenten` (
  `id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_facturen`
--

CREATE TABLE `hs_facturen` (
  `id` int NOT NULL,
  `klant_id` int DEFAULT NULL,
  `nummer` varchar(255) NOT NULL,
  `datum` date NOT NULL,
  `betreft` varchar(255) NOT NULL,
  `bedrag` decimal(10,2) NOT NULL,
  `locked` tinyint(1) NOT NULL,
  `class` varchar(255) NOT NULL,
  `opmerking` longtext,
  `oninbaar` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_factuur_klus`
--

CREATE TABLE `hs_factuur_klus` (
  `factuur_id` int NOT NULL,
  `klus_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_herinneringen`
--

CREATE TABLE `hs_herinneringen` (
  `id` int NOT NULL,
  `factuur_id` int DEFAULT NULL,
  `datum` date NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_klanten`
--

CREATE TABLE `hs_klanten` (
  `id` int NOT NULL,
  `geslacht_id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `erp_id` int DEFAULT NULL,
  `bsn` varchar(255) DEFAULT NULL,
  `rekeningnummer` varchar(255) DEFAULT NULL,
  `werkgebied` varchar(255) DEFAULT NULL,
  `inschrijving` date NOT NULL,
  `uitschrijving` date DEFAULT NULL,
  `laatsteContact` date DEFAULT NULL,
  `actief` tinyint(1) NOT NULL,
  `bewindvoerder` longtext,
  `saldo` decimal(10,2) NOT NULL,
  `voornaam` varchar(255) DEFAULT NULL,
  `tussenvoegsel` varchar(255) DEFAULT NULL,
  `achternaam` varchar(255) DEFAULT NULL,
  `adres` varchar(255) DEFAULT NULL,
  `postcode` varchar(255) DEFAULT NULL,
  `plaats` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobiel` varchar(255) DEFAULT NULL,
  `telefoon` varchar(255) DEFAULT NULL,
  `hulpverlener_naam` varchar(255) DEFAULT NULL,
  `hulpverlener_organisatie` varchar(255) DEFAULT NULL,
  `hulpverlener_telefoon` varchar(255) DEFAULT NULL,
  `hulpverlener_email` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `postcodegebied` varchar(255) DEFAULT NULL,
  `afwijkendFactuuradres` tinyint(1) NOT NULL,
  `status` varchar(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_klant_document`
--

CREATE TABLE `hs_klant_document` (
  `klant_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_klant_memo`
--

CREATE TABLE `hs_klant_memo` (
  `klant_id` int NOT NULL,
  `memo_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_klussen`
--

CREATE TABLE `hs_klussen` (
  `id` int NOT NULL,
  `klant_id` int DEFAULT NULL,
  `medewerker_id` int NOT NULL,
  `startdatum` date NOT NULL,
  `einddatum` date DEFAULT NULL,
  `onHold` tinyint(1) NOT NULL,
  `status` varchar(255) NOT NULL,
  `annuleringsdatum` date DEFAULT NULL,
  `onHoldTot` date DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_klus_activiteit`
--

CREATE TABLE `hs_klus_activiteit` (
  `klus_id` int NOT NULL,
  `activiteit_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_klus_dienstverlener`
--

CREATE TABLE `hs_klus_dienstverlener` (
  `klus_id` int NOT NULL,
  `dienstverlener_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_klus_document`
--

CREATE TABLE `hs_klus_document` (
  `klus_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_klus_memo`
--

CREATE TABLE `hs_klus_memo` (
  `klus_id` int NOT NULL,
  `memo_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_klus_vrijwilliger`
--

CREATE TABLE `hs_klus_vrijwilliger` (
  `klus_id` int NOT NULL,
  `vrijwilliger_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_memos`
--

CREATE TABLE `hs_memos` (
  `id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `datum` datetime NOT NULL,
  `memo` longtext NOT NULL,
  `intake` tinyint(1) NOT NULL,
  `onderwerp` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_registraties`
--

CREATE TABLE `hs_registraties` (
  `id` int NOT NULL,
  `klus_id` int DEFAULT NULL,
  `factuur_id` int DEFAULT NULL,
  `arbeider_id` int NOT NULL,
  `activiteit_id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `datum` date NOT NULL,
  `start` time DEFAULT NULL,
  `eind` time DEFAULT NULL,
  `reiskosten` double DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dagdelen` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_vrijwilligers`
--

CREATE TABLE `hs_vrijwilligers` (
  `id` int NOT NULL,
  `vrijwilliger_id` int NOT NULL,
  `hulpverlener_naam` varchar(255) DEFAULT NULL,
  `hulpverlener_organisatie` varchar(255) DEFAULT NULL,
  `hulpverlener_telefoon` varchar(255) DEFAULT NULL,
  `hulpverlener_email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_vrijwilliger_document`
--

CREATE TABLE `hs_vrijwilliger_document` (
  `vrijwilliger_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_vrijwilliger_memo`
--

CREATE TABLE `hs_vrijwilliger_memo` (
  `vrijwilliger_id` int NOT NULL,
  `memo_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `i18n`
--

CREATE TABLE `i18n` (
  `id` int NOT NULL,
  `locale` varchar(6) NOT NULL,
  `model` varchar(255) NOT NULL,
  `foreign_key` int NOT NULL,
  `field` varchar(255) NOT NULL,
  `content` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `infobaliedoelgroepen`
--

CREATE TABLE `infobaliedoelgroepen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inkomens`
--

CREATE TABLE `inkomens` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inkomens_awbz_intakes`
--

CREATE TABLE `inkomens_awbz_intakes` (
  `id` int NOT NULL,
  `awbz_intake_id` int NOT NULL,
  `inkomen_id` int NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inkomens_intakes`
--

CREATE TABLE `inkomens_intakes` (
  `id` int NOT NULL,
  `intake_id` int NOT NULL,
  `inkomen_id` int NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inloop_afsluiting_redenen`
--

CREATE TABLE `inloop_afsluiting_redenen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `actief` tinyint(1) NOT NULL,
  `land` tinyint(1) NOT NULL,
  `gewicht` int NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inloop_afsluitredenen_vrijwilligers`
--

CREATE TABLE `inloop_afsluitredenen_vrijwilligers` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inloop_binnen_via`
--

CREATE TABLE `inloop_binnen_via` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inloop_deelnames`
--

CREATE TABLE `inloop_deelnames` (
  `id` int NOT NULL,
  `inloop_vrijwilliger_id` int NOT NULL,
  `datum` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `inloopTraining_id` int NOT NULL,
  `overig` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inloop_documenten`
--

CREATE TABLE `inloop_documenten` (
  `id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inloop_dossier_statussen`
--

CREATE TABLE `inloop_dossier_statussen` (
  `id` int NOT NULL,
  `klant_id` int NOT NULL,
  `medewerker_id` int DEFAULT NULL,
  `reden_id` int DEFAULT NULL,
  `land_id` int DEFAULT NULL,
  `datum` date NOT NULL,
  `toelichting` longtext,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `class` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inloop_incidenten`
--

CREATE TABLE `inloop_incidenten` (
  `id` int NOT NULL,
  `locatie_id` int DEFAULT NULL,
  `klant_id` int NOT NULL,
  `datum` date NOT NULL,
  `remark` longtext,
  `politie` tinyint(1) NOT NULL DEFAULT '0',
  `ambulance` tinyint(1) NOT NULL DEFAULT '0',
  `crisisdienst` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inloop_intake_zrm`
--

CREATE TABLE `inloop_intake_zrm` (
  `intake_id` int NOT NULL,
  `zrm_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inloop_memos`
--

CREATE TABLE `inloop_memos` (
  `id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `datum` datetime NOT NULL,
  `onderwerp` varchar(255) NOT NULL,
  `memo` longtext NOT NULL,
  `intake` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inloop_toegang`
--

CREATE TABLE `inloop_toegang` (
  `klant_id` int NOT NULL,
  `locatie_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inloop_training`
--

CREATE TABLE `inloop_training` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inloop_vrijwilligers`
--

CREATE TABLE `inloop_vrijwilligers` (
  `id` int NOT NULL,
  `vrijwilliger_id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `aanmelddatum` date NOT NULL,
  `binnen_via_id` int DEFAULT NULL,
  `afsluitreden_id` int DEFAULT NULL,
  `afsluitdatum` date DEFAULT NULL,
  `stagiair` tinyint(1) NOT NULL,
  `startdatum` date DEFAULT NULL,
  `notitieIntake` varchar(255) DEFAULT NULL,
  `datumNotitieIntake` date DEFAULT NULL,
  `medewerkerLocatie_id` int DEFAULT NULL,
  `trainingOverig` varchar(255) DEFAULT NULL,
  `trainingOverigDatum` varchar(255) DEFAULT NULL,
  `locatie_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inloop_vrijwilliger_document`
--

CREATE TABLE `inloop_vrijwilliger_document` (
  `vrijwilliger_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inloop_vrijwilliger_locatie`
--

CREATE TABLE `inloop_vrijwilliger_locatie` (
  `vrijwilliger_id` int NOT NULL,
  `locatie_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inloop_vrijwilliger_memo`
--

CREATE TABLE `inloop_vrijwilliger_memo` (
  `vrijwilliger_id` int NOT NULL,
  `memo_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `instanties`
--

CREATE TABLE `instanties` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `instanties_awbz_intakes`
--

CREATE TABLE `instanties_awbz_intakes` (
  `id` int NOT NULL,
  `awbz_intake_id` int NOT NULL,
  `instantie_id` int NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `instanties_intakes`
--

CREATE TABLE `instanties_intakes` (
  `id` int NOT NULL,
  `intake_id` int NOT NULL,
  `instantie_id` int NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `intakes`
--

CREATE TABLE `intakes` (
  `id` int NOT NULL,
  `klant_id` int DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL,
  `datum_intake` date DEFAULT NULL,
  `verblijfstatus_id` int DEFAULT NULL,
  `postadres` varchar(255) DEFAULT NULL,
  `postcode` varchar(6) DEFAULT NULL,
  `woonplaats` varchar(255) DEFAULT NULL,
  `verblijf_in_NL_sinds` date DEFAULT NULL,
  `verblijf_in_amsterdam_sinds` date DEFAULT NULL,
  `legitimatie_id` int DEFAULT NULL,
  `legitimatie_nummer` varchar(255) DEFAULT NULL,
  `legitimatie_geldig_tot` date DEFAULT NULL,
  `primaireproblematiek_id` int DEFAULT NULL,
  `primaireproblematieksfrequentie_id` int DEFAULT NULL,
  `primaireproblematieksperiode_id` int DEFAULT NULL,
  `verslavingsfrequentie_id` int DEFAULT NULL,
  `verslavingsperiode_id` int DEFAULT NULL,
  `verslaving_overig` varchar(255) DEFAULT NULL,
  `eerste_gebruik` date DEFAULT NULL,
  `inkomen_overig` varchar(255) DEFAULT NULL,
  `woonsituatie_id` int DEFAULT NULL,
  `verwachting_dienstaanbod` text,
  `toekomstplannen` text,
  `opmerking_andere_instanties` text,
  `medische_achtergrond` text,
  `locatie1_id` int DEFAULT NULL,
  `locatie2_id` int DEFAULT NULL,
  `mag_gebruiken` tinyint(1) DEFAULT NULL,
  `indruk` text,
  `doelgroep` tinyint(1) DEFAULT NULL,
  `informele_zorg` tinyint(1) DEFAULT '0',
  `dagbesteding` tinyint(1) DEFAULT '0',
  `inloophuis` tinyint(1) DEFAULT '0',
  `hulpverlening` tinyint(1) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `locatie3_id` int DEFAULT NULL,
  `infobaliedoelgroep_id` int DEFAULT NULL,
  `toegang_vrouwen_nacht_opvang` tinyint(1) DEFAULT '0',
  `telefoonnummer` varchar(255) DEFAULT NULL,
  `toegang_inloophuis` tinyint(1) DEFAULT NULL,
  `amoc_toegang_tot` date DEFAULT NULL,
  `zrm_id` int DEFAULT NULL,
  `medewerker_id_before_constraint` int DEFAULT NULL,
  `klant_id_before_constraint` int DEFAULT NULL,
  `woonsituatie_id_before_constraint` int DEFAULT NULL,
  `overigen_toegang_van` date DEFAULT NULL,
  `ondro_bong_toegang_van` date DEFAULT NULL,
  `geinformeerd_opslaan_gegevens` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `intakes_primaireproblematieksgebruikswijzen`
--

CREATE TABLE `intakes_primaireproblematieksgebruikswijzen` (
  `id` int NOT NULL,
  `intake_id` int NOT NULL,
  `primaireproblematieksgebruikswijze_id` int NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `intakes_verslavingen`
--

CREATE TABLE `intakes_verslavingen` (
  `id` int NOT NULL,
  `intake_id` int NOT NULL,
  `verslaving_id` int NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `intakes_verslavingsgebruikswijzen`
--

CREATE TABLE `intakes_verslavingsgebruikswijzen` (
  `id` int NOT NULL,
  `intake_id` int NOT NULL,
  `verslavingsgebruikswijze_id` int NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inventarisaties`
--

CREATE TABLE `inventarisaties` (
  `id` int NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `parent_id` int DEFAULT NULL,
  `actief` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(255) DEFAULT NULL,
  `titel` varchar(255) NOT NULL,
  `actie` varchar(255) NOT NULL,
  `startdatum` date NOT NULL,
  `einddatum` date DEFAULT NULL,
  `lft` int DEFAULT NULL,
  `rght` int DEFAULT NULL,
  `depth` tinyint DEFAULT NULL,
  `dropdown_metadata` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inventarisaties_verslagen`
--

CREATE TABLE `inventarisaties_verslagen` (
  `id` int NOT NULL,
  `verslag_id` int NOT NULL DEFAULT '0',
  `inventarisatie_id` int NOT NULL DEFAULT '0',
  `doorverwijzer_id` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_afsluitingen`
--

CREATE TABLE `iz_afsluitingen` (
  `id` int NOT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_deelnemers`
--

CREATE TABLE `iz_deelnemers` (
  `id` int NOT NULL,
  `model` varchar(50) NOT NULL,
  `foreign_key` int DEFAULT NULL,
  `datum_aanmelding` date DEFAULT NULL,
  `binnengekomen_via` int DEFAULT NULL,
  `organisatie` varchar(100) DEFAULT NULL,
  `naam_aanmelder` varchar(100) DEFAULT NULL,
  `email_aanmelder` varchar(100) DEFAULT NULL,
  `telefoon_aanmelder` varchar(100) DEFAULT NULL,
  `notitie` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `datumafsluiting` date DEFAULT NULL,
  `iz_afsluiting_id` int DEFAULT NULL,
  `contact_ontstaan` int DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_deelnemers_documenten`
--

CREATE TABLE `iz_deelnemers_documenten` (
  `izdeelnemer_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_deelnemers_iz_intervisiegroepen`
--

CREATE TABLE `iz_deelnemers_iz_intervisiegroepen` (
  `id` int NOT NULL,
  `iz_deelnemer_id` int NOT NULL,
  `iz_intervisiegroep_id` int NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_deelnemers_iz_projecten`
--

CREATE TABLE `iz_deelnemers_iz_projecten` (
  `id` int NOT NULL,
  `iz_deelnemer_id` int NOT NULL,
  `iz_project_id` int NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_documenten`
--

CREATE TABLE `iz_documenten` (
  `id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_doelgroepen`
--

CREATE TABLE `iz_doelgroepen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_doelstellingen`
--

CREATE TABLE `iz_doelstellingen` (
  `id` int NOT NULL,
  `project_id` int DEFAULT NULL,
  `jaar` int NOT NULL,
  `aantal` int NOT NULL,
  `stadsdeel` varchar(255) DEFAULT NULL,
  `categorie` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_eindekoppelingen`
--

CREATE TABLE `iz_eindekoppelingen` (
  `id` int NOT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_hulpaanbod_hulpvraagsoort`
--

CREATE TABLE `iz_hulpaanbod_hulpvraagsoort` (
  `hulpaanbod_id` int NOT NULL,
  `hulpvraagsoort_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_hulpvraagsoorten`
--

CREATE TABLE `iz_hulpvraagsoorten` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `toelichting` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_hulpvraag_succesindicator`
--

CREATE TABLE `iz_hulpvraag_succesindicator` (
  `hulpvraag_id` int NOT NULL,
  `succesindicator_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_hulpvraag_succesindicatorfinancieel`
--

CREATE TABLE `iz_hulpvraag_succesindicatorfinancieel` (
  `hulpvraag_id` int NOT NULL,
  `succesindicatorfinancieel_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_hulpvraag_succesindicatorparticipatie`
--

CREATE TABLE `iz_hulpvraag_succesindicatorparticipatie` (
  `hulpvraag_id` int NOT NULL,
  `succesindicatorparticipatie_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_hulpvraag_succesindicatorpersoonlijk`
--

CREATE TABLE `iz_hulpvraag_succesindicatorpersoonlijk` (
  `hulpvraag_id` int NOT NULL,
  `succesindicatorpersoonlijk_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_intakes`
--

CREATE TABLE `iz_intakes` (
  `id` int NOT NULL,
  `iz_deelnemer_id` int NOT NULL,
  `medewerker_id` int DEFAULT NULL,
  `intake_datum` date DEFAULT NULL,
  `gesprek_verslag` text,
  `ondernemen` tinyint(1) DEFAULT NULL,
  `overdag` tinyint(1) DEFAULT NULL,
  `ontmoeten` tinyint(1) DEFAULT NULL,
  `regelzaken` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modifed` datetime DEFAULT NULL,
  `stagiair` tinyint(1) DEFAULT NULL,
  `gezin_met_kinderen` tinyint(1) DEFAULT NULL,
  `zrm_id` int DEFAULT NULL,
  `ongedocumenteerd` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_intake_zrm`
--

CREATE TABLE `iz_intake_zrm` (
  `intake_id` int NOT NULL,
  `zrm_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_intervisiegroepen`
--

CREATE TABLE `iz_intervisiegroepen` (
  `id` int NOT NULL,
  `naam` varchar(100) DEFAULT NULL,
  `startdatum` date DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_koppelingen`
--

CREATE TABLE `iz_koppelingen` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `iz_deelnemer_id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `startdatum` date DEFAULT NULL,
  `koppeling_startdatum` date DEFAULT NULL,
  `koppeling_einddatum` date DEFAULT NULL,
  `iz_eindekoppeling_id` int DEFAULT NULL,
  `koppeling_succesvol` tinyint(1) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `einddatum` date DEFAULT NULL,
  `iz_vraagaanbod_id` int DEFAULT NULL,
  `iz_koppeling_id` int DEFAULT NULL,
  `discr` varchar(255) NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `info` longtext,
  `dagdeel` varchar(255) DEFAULT NULL,
  `spreekt_nederlands` tinyint(1) DEFAULT '1',
  `hulpvraagsoort_id` int DEFAULT NULL,
  `voorkeur_voor_nederlands` tinyint(1) DEFAULT NULL,
  `voorkeurGeslacht_id` int DEFAULT NULL,
  `coachend` tinyint(1) DEFAULT NULL,
  `expat` tinyint(1) DEFAULT NULL,
  `tussenevaluatiedatum` datetime DEFAULT NULL,
  `eindevaluatiedatum` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_koppeling_doelgroep`
--

CREATE TABLE `iz_koppeling_doelgroep` (
  `koppeling_id` int NOT NULL,
  `doelgroep_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_matchingklant_doelgroep`
--

CREATE TABLE `iz_matchingklant_doelgroep` (
  `matchingklant_id` int NOT NULL,
  `doelgroep_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_matchingvrijwilliger_doelgroep`
--

CREATE TABLE `iz_matchingvrijwilliger_doelgroep` (
  `matchingvrijwilliger_id` int NOT NULL,
  `doelgroep_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_matchingvrijwilliger_hulpvraagsoort`
--

CREATE TABLE `iz_matchingvrijwilliger_hulpvraagsoort` (
  `matchingvrijwilliger_id` int NOT NULL,
  `hulpvraagsoort_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_matching_klanten`
--

CREATE TABLE `iz_matching_klanten` (
  `id` int NOT NULL,
  `iz_klant_id` int DEFAULT NULL,
  `hulpvraagsoort_id` int DEFAULT NULL,
  `info` varchar(255) NOT NULL,
  `spreekt_nederlands` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_matching_vrijwilligers`
--

CREATE TABLE `iz_matching_vrijwilligers` (
  `id` int NOT NULL,
  `iz_vrijwilliger_id` int DEFAULT NULL,
  `info` varchar(255) NOT NULL,
  `voorkeur_voor_nederlands` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_ontstaan_contacten`
--

CREATE TABLE `iz_ontstaan_contacten` (
  `id` int NOT NULL,
  `naam` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_projecten`
--

CREATE TABLE `iz_projecten` (
  `id` int NOT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `startdatum` date DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `heeft_koppelingen` tinyint(1) DEFAULT NULL,
  `prestatie_strategy` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_reserveringen`
--

CREATE TABLE `iz_reserveringen` (
  `id` int NOT NULL,
  `hulpvraag_id` int NOT NULL,
  `hulpaanbod_id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `startdatum` date NOT NULL,
  `einddatum` date NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_succesindicatoren`
--

CREATE TABLE `iz_succesindicatoren` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `discr` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_verslagen`
--

CREATE TABLE `iz_verslagen` (
  `id` int NOT NULL,
  `iz_deelnemer_id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `opmerking` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `iz_koppeling_id` int DEFAULT NULL,
  `discr` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_via_personen`
--

CREATE TABLE `iz_via_personen` (
  `id` int NOT NULL,
  `naam` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_vraagaanboden`
--

CREATE TABLE `iz_vraagaanboden` (
  `id` int NOT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `klanten`
--

CREATE TABLE `klanten` (
  `id` int NOT NULL,
  `MezzoID` int NOT NULL,
  `voornaam` varchar(255) DEFAULT NULL,
  `tussenvoegsel` varchar(255) DEFAULT NULL,
  `achternaam` varchar(255) DEFAULT NULL,
  `roepnaam` varchar(255) DEFAULT NULL,
  `geslacht_id` int NOT NULL DEFAULT '0',
  `geboortedatum` date DEFAULT NULL,
  `land_id` int NOT NULL DEFAULT '1',
  `nationaliteit_id` int NOT NULL DEFAULT '1',
  `BSN` varchar(255) DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL,
  `laatste_TBC_controle` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `laste_intake_id` int DEFAULT NULL,
  `disabled` tinyint(1) DEFAULT '0',
  `laatste_registratie_id` int DEFAULT NULL,
  `doorverwijzen_naar_amoc` tinyint(1) DEFAULT '0',
  `merged_id` int DEFAULT NULL,
  `adres` varchar(255) DEFAULT NULL,
  `postcode` varchar(6) DEFAULT NULL,
  `werkgebied` varchar(255) DEFAULT NULL,
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
  `postcodegebied` varchar(55) DEFAULT NULL,
  `huidigeStatus_id` int DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `first_intake_id` int DEFAULT NULL,
  `huidigeMwStatus_id` int DEFAULT NULL,
  `corona_besmet_vanaf` date DEFAULT NULL,
  `partner_id` int DEFAULT NULL,
  `maatschappelijkWerker_id` int DEFAULT NULL,
  `mwBinnenViaOptieKlant_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `klantinventarisaties`
--

CREATE TABLE `klantinventarisaties` (
  `id` int NOT NULL,
  `klant_id` int NOT NULL DEFAULT '0',
  `inventarisatie_id` int NOT NULL DEFAULT '0',
  `doorverwijzer_id` int NOT NULL DEFAULT '0',
  `datum` date NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `klant_taal`
--

CREATE TABLE `klant_taal` (
  `id` int NOT NULL,
  `klant_id` int NOT NULL,
  `taal_id` int NOT NULL,
  `voorkeur` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `landen`
--

CREATE TABLE `landen` (
  `id` int NOT NULL,
  `land` varchar(255) NOT NULL,
  `AFK2` varchar(5) NOT NULL,
  `AFK3` varchar(5) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `legitimaties`
--

CREATE TABLE `legitimaties` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `locaties`
--

CREATE TABLE `locaties` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `nachtopvang` tinyint(1) NOT NULL DEFAULT '0',
  `gebruikersruimte` tinyint(1) NOT NULL DEFAULT '0',
  `datum_van` date NOT NULL,
  `datum_tot` date DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `maatschappelijkwerk` tinyint(1) DEFAULT '0',
  `tbc_check` tinyint(1) NOT NULL DEFAULT '0',
  `wachtlijst` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `locatie_tijden`
--

CREATE TABLE `locatie_tijden` (
  `id` int NOT NULL,
  `locatie_id` int NOT NULL,
  `dag_van_de_week` int NOT NULL,
  `sluitingstijd` time NOT NULL,
  `openingstijd` time NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `logs`
--

CREATE TABLE `logs` (
  `id` int NOT NULL,
  `created` datetime DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `foreign_key` varchar(36) DEFAULT NULL,
  `medewerker_id` varchar(36) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `change` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `logs_2014`
--

CREATE TABLE `logs_2014` (
  `id` int NOT NULL,
  `created` datetime DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `foreign_key` varchar(36) DEFAULT NULL,
  `medewerker_id` varchar(36) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `change` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `logs_2015`
--

CREATE TABLE `logs_2015` (
  `id` int NOT NULL,
  `created` datetime DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `foreign_key` varchar(36) DEFAULT NULL,
  `medewerker_id` varchar(36) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `change` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `logs_2016`
--

CREATE TABLE `logs_2016` (
  `id` int NOT NULL,
  `created` datetime DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `foreign_key` varchar(36) DEFAULT NULL,
  `medewerker_id` varchar(36) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `change` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `logs_2017`
--

CREATE TABLE `logs_2017` (
  `id` int NOT NULL,
  `created` datetime DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `foreign_key` varchar(36) DEFAULT NULL,
  `medewerker_id` varchar(36) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `change` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `medewerkers`
--

CREATE TABLE `medewerkers` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `voornaam` varchar(255) DEFAULT NULL,
  `tussenvoegsel` varchar(255) DEFAULT NULL,
  `achternaam` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `eerste_bezoek` datetime NOT NULL,
  `laatste_bezoek` datetime NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `uidnumber` varchar(255) NOT NULL,
  `active` int NOT NULL DEFAULT '1',
  `groups` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `ldap_groups` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `roles` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(1024) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `migration_versions`
--

INSERT INTO `migration_versions` (`version`) VALUES
('20161201111838'),
('20161212162259'),
('20161221100937'),
('20170213133953'),
('20170308151229'),
('20170322125917'),
('20170329130917'),
('20170330122223'),
('20170330130308'),
('20170403133912'),
('20170406144536'),
('20170410092513'),
('20170410121504'),
('20170412143819'),
('20170426144554'),
('20170519083942'),
('20170522110053'),
('20170522185619'),
('20170529140111'),
('20170529142453'),
('20170601130850'),
('20170619122732'),
('20170713114136'),
('20170817131037'),
('20170823085203'),
('20170823101929'),
('20170823104309'),
('20170823110800'),
('20170823125554'),
('20170824174536'),
('20170831101720'),
('20170925112450'),
('20170928105854'),
('20171006094954'),
('20171011092632'),
('20171012100518'),
('20171026175507'),
('20171030080739'),
('20171101072746'),
('20171106165428'),
('20171108141044'),
('20171109125508'),
('20171115185650'),
('20171120123247'),
('20171122151746'),
('20171123160128'),
('20171127190054'),
('20171128123751'),
('20171129144311'),
('20171129161804'),
('20171130093533'),
('20171130162242'),
('20171207154511'),
('20171211093230'),
('20171218153330'),
('20171219180644'),
('20171221210548'),
('20180110102918'),
('20180110114114'),
('20180111145046'),
('20180115145622'),
('20180119104637'),
('20180122092231'),
('20180122100115'),
('20180208092627'),
('20180208103259'),
('20180305090933'),
('20180322123040'),
('20180322123753'),
('20180329101525'),
('20180329104205'),
('20180405094810'),
('20180417080553'),
('20180417130021'),
('20180424090940'),
('20180424120916'),
('20180425092513'),
('20180426125353'),
('20180501144543'),
('20180515103809'),
('20180711092613'),
('20180820090920'),
('20180821092424'),
('20180821134509'),
('20180824084127'),
('20180830073832'),
('20180830124159'),
('20180913143950'),
('20180913143953'),
('20180917120729'),
('20180926070729'),
('20181001074038'),
('20181002093231'),
('20181106110520'),
('20181112132039'),
('20181119121422'),
('20181120095853'),
('20181217084600'),
('20181217124642'),
('20190107144904'),
('20190117121119'),
('20190128084438'),
('20190128151531'),
('20190129095955'),
('20190129121330'),
('20190129125113'),
('20190204150751'),
('20190205101226'),
('20190205125125'),
('20190205151956'),
('20190207093336'),
('20190212100701'),
('20190212133944'),
('20190219142847'),
('20190221120254'),
('20190226143417'),
('20190311121425'),
('20190319081723'),
('20190319090440'),
('20190326150253'),
('20190328114627'),
('20190328140321'),
('20190328142716'),
('20190401091613'),
('20190416123035'),
('20190724124736'),
('20190830064704'),
('20190910111159'),
('20190916093536'),
('20191008080513'),
('20191008115751'),
('20191010101053'),
('20191014072424'),
('20191028130211'),
('20191104105518'),
('20191125080129'),
('20191128132928'),
('20191128134814'),
('20191203074502'),
('20191204060459'),
('20191216094952'),
('20200106124136'),
('20200114065243'),
('20200114073433'),
('20200114112655'),
('20200114115409'),
('20200123110641'),
('20200123153704'),
('20200128070248'),
('20200128085013'),
('20200130074837'),
('20200130094842'),
('20200203103108'),
('20200227115030'),
('20200324124541'),
('20200514063942'),
('20200610070754'),
('20200610092301'),
('20200625074741'),
('20200630070653'),
('20200702082748'),
('20200706110934'),
('20200713105157'),
('20200714085755'),
('20200720111911'),
('20200921195329'),
('20201016085835'),
('20201109101551'),
('20201123103104'),
('20201218093703'),
('20210115080234'),
('20210129130758'),
('20210205144213'),
('20210205162440'),
('20210205181912'),
('20210208081711'),
('20210212095834'),
('20210212101518'),
('20210226112642'),
('20210226144622'),
('20210312071815'),
('20210402063259'),
('20210514084430'),
('20210514120228'),
('20210514125434'),
('20210528094420'),
('20210611135504'),
('20210827082650'),
('20210910085049'),
('20210910112204'),
('20210915085000'),
('20210915122543'),
('20210916130456'),
('20210922145733'),
('20210923101810'),
('20211001075830'),
('20211104125449'),
('20211104144517'),
('20211118084245'),
('20211118140638'),
('20211202120734'),
('20211216085646'),
('20211216114235'),
('20211224103730'),
('20211224113404'),
('20220114125437'),
('20220203094823'),
('20220225135903'),
('20220225155127'),
('20220302092545'),
('20220302132929'),
('20220310090908'),
('20220311084120'),
('20220324115350'),
('20220421122133'),
('20220616092125'),
('20220616092524'),
('20220629133317'),
('20220713103910'),
('20220713121540'),
('20220713144250'),
('20221007085836'),
('20221116145922'),
('20221213141832'),
('20221214132028'),
('20221215092017'),
('20221216090600'),
('20221220160029');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mw_afsluiting_redenen`
--

CREATE TABLE `mw_afsluiting_redenen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `actief` tinyint(1) NOT NULL,
  `gewicht` int NOT NULL,
  `land` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mw_afsluiting_resultaat`
--

CREATE TABLE `mw_afsluiting_resultaat` (
  `afsluiting_id` int NOT NULL,
  `resultaat_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mw_afsluitredenen_vrijwilligers`
--

CREATE TABLE `mw_afsluitredenen_vrijwilligers` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mw_binnen_via`
--

CREATE TABLE `mw_binnen_via` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `class` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mw_deelnames`
--

CREATE TABLE `mw_deelnames` (
  `id` int NOT NULL,
  `inloop_vrijwilliger_id` int NOT NULL,
  `overig` varchar(255) DEFAULT NULL,
  `datum` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `mwTraining_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mw_documenten`
--

CREATE TABLE `mw_documenten` (
  `id` int NOT NULL,
  `klant_id` int DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL,
  `naam` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mw_dossier_statussen`
--

CREATE TABLE `mw_dossier_statussen` (
  `id` int NOT NULL,
  `klant_id` int NOT NULL,
  `medewerker_id` int DEFAULT NULL,
  `reden_id` int DEFAULT NULL,
  `land_id` int DEFAULT NULL,
  `datum` date NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `class` varchar(255) NOT NULL,
  `toelichting` longtext,
  `locatie_id` int DEFAULT NULL,
  `binnenViaOptieKlant_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mw_resultaten`
--

CREATE TABLE `mw_resultaten` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mw_training`
--

CREATE TABLE `mw_training` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mw_vrijwilligers`
--

CREATE TABLE `mw_vrijwilligers` (
  `id` int NOT NULL,
  `vrijwilliger_id` int NOT NULL,
  `binnen_via_id` int DEFAULT NULL,
  `afsluitreden_id` int DEFAULT NULL,
  `medewerker_id` int NOT NULL,
  `aanmelddatum` date NOT NULL,
  `afsluitdatum` date DEFAULT NULL,
  `stagiair` tinyint(1) NOT NULL,
  `notitieIntake` text COLLATE utf8mb4_unicode_ci,
  `datumNotitieIntake` datetime DEFAULT NULL,
  `trainingOverig` varchar(255) CHARACTER SET utf8mb3 DEFAULT NULL,
  `trainingOverigDatum` varchar(255) CHARACTER SET utf8mb3 DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `locatie_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mw_vrijwilliger_document`
--

CREATE TABLE `mw_vrijwilliger_document` (
  `vrijwilliger_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mw_vrijwilliger_locatie`
--

CREATE TABLE `mw_vrijwilliger_locatie` (
  `vrijwilliger_id` int NOT NULL,
  `locatie_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mw_vrijwilliger_memo`
--

CREATE TABLE `mw_vrijwilliger_memo` (
  `vrijwilliger_id` int NOT NULL,
  `memo_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `nationaliteiten`
--

CREATE TABLE `nationaliteiten` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `afkorting` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `notities`
--

CREATE TABLE `notities` (
  `id` int NOT NULL,
  `klant_id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `datum` datetime NOT NULL,
  `opmerking` text NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oekklant_oekdossierstatus`
--

CREATE TABLE `oekklant_oekdossierstatus` (
  `oekklant_id` int NOT NULL,
  `oekdossierstatus_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oekraine_afsluiting_redenen`
--

CREATE TABLE `oekraine_afsluiting_redenen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `actief` tinyint(1) NOT NULL,
  `gewicht` int NOT NULL,
  `land` tinyint(1) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oekraine_afsluitredenen_vrijwilligers`
--

CREATE TABLE `oekraine_afsluitredenen_vrijwilligers` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oekraine_bezoekers`
--

CREATE TABLE `oekraine_bezoekers` (
  `id` int NOT NULL,
  `intake_id` int DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `appKlant_id` int NOT NULL,
  `dossierStatus_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oekraine_bezoeker_document`
--

CREATE TABLE `oekraine_bezoeker_document` (
  `bezoeker_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oekraine_documenten`
--

CREATE TABLE `oekraine_documenten` (
  `id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oekraine_dossier_statussen`
--

CREATE TABLE `oekraine_dossier_statussen` (
  `id` int NOT NULL,
  `bezoeker_id` int NOT NULL,
  `medewerker_id` int DEFAULT NULL,
  `reden_id` int DEFAULT NULL,
  `land_id` int DEFAULT NULL,
  `datum` date NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `class` varchar(255) NOT NULL,
  `toelichting` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oekraine_incidenten`
--

CREATE TABLE `oekraine_incidenten` (
  `id` int NOT NULL,
  `locatie_id` int DEFAULT NULL,
  `datum` date NOT NULL,
  `remark` longtext,
  `politie` tinyint(1) NOT NULL,
  `ambulance` tinyint(1) NOT NULL,
  `crisisdienst` tinyint(1) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bezoeker_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oekraine_inkomens`
--

CREATE TABLE `oekraine_inkomens` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oekraine_inkomens_intakes`
--

CREATE TABLE `oekraine_inkomens_intakes` (
  `intake_id` int NOT NULL,
  `inkomen_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oekraine_intakes`
--

CREATE TABLE `oekraine_intakes` (
  `id` int NOT NULL,
  `bezoeker_id` int DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL,
  `woonlocatie_id` int DEFAULT NULL,
  `verblijfstatus_id` int DEFAULT NULL,
  `legitimatie_id` int DEFAULT NULL,
  `kamernummer` varchar(255) DEFAULT NULL,
  `datum_intake` date DEFAULT NULL,
  `postadres` varchar(255) DEFAULT NULL,
  `postcode` varchar(255) DEFAULT NULL,
  `woonplaats` varchar(255) DEFAULT NULL,
  `telefoonnummer` varchar(255) DEFAULT NULL,
  `inkomen_overig` varchar(255) DEFAULT NULL,
  `legitimatie_nummer` varchar(255) DEFAULT NULL,
  `legitimatie_geldig_tot` date DEFAULT NULL,
  `verblijf_in_NL_sinds` date DEFAULT NULL,
  `verblijf_in_amsterdam_sinds` date DEFAULT NULL,
  `opmerking_andere_instanties` varchar(255) DEFAULT NULL,
  `medische_achtergrond` varchar(255) DEFAULT NULL,
  `toekomstplannen` varchar(255) DEFAULT NULL,
  `indruk` varchar(255) DEFAULT NULL,
  `informele_zorg` varchar(255) DEFAULT NULL,
  `werkhulp` varchar(255) NOT NULL,
  `hulpverlening` varchar(255) NOT NULL,
  `geinformeerd_opslaan_gegevens` tinyint(1) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `intakelocatie_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oekraine_intakes_instanties`
--

CREATE TABLE `oekraine_intakes_instanties` (
  `intake_id` int NOT NULL,
  `instantie_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oekraine_locaties`
--

CREATE TABLE `oekraine_locaties` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oekraine_registraties`
--

CREATE TABLE `oekraine_registraties` (
  `id` int NOT NULL,
  `locatie_id` int DEFAULT NULL,
  `bezoeker_id` int DEFAULT NULL,
  `binnen` datetime NOT NULL,
  `binnen_date` date DEFAULT NULL,
  `buiten` datetime DEFAULT NULL,
  `douche` int NOT NULL,
  `mw` int NOT NULL,
  `gbrv` int NOT NULL,
  `kleding` tinyint(1) NOT NULL,
  `maaltijd` tinyint(1) NOT NULL,
  `activering` tinyint(1) NOT NULL,
  `veegploeg` tinyint(1) NOT NULL,
  `aantalSpuiten` int DEFAULT NULL,
  `closed` tinyint(1) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oekraine_registraties_recent`
--

CREATE TABLE `oekraine_registraties_recent` (
  `registratie_id` int NOT NULL,
  `locatie_id` int DEFAULT NULL,
  `bezoeker_id` int DEFAULT NULL,
  `max_buiten` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oekraine_toegang`
--

CREATE TABLE `oekraine_toegang` (
  `bezoeker_id` int NOT NULL,
  `locatie_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oekraine_verslagen`
--

CREATE TABLE `oekraine_verslagen` (
  `id` int NOT NULL,
  `bezoeker_id` int DEFAULT NULL,
  `locatie_id` int DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL,
  `contactsoort_id` int DEFAULT NULL,
  `datum` date NOT NULL,
  `opmerking` longtext NOT NULL,
  `medewerker` varchar(255) DEFAULT NULL,
  `aanpassing_verslag` int DEFAULT NULL,
  `verslagType` int NOT NULL DEFAULT '1',
  `accessType` int NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_deelnames`
--

CREATE TABLE `oek_deelnames` (
  `id` int NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `oekTraining_id` int NOT NULL,
  `oekKlant_id` int NOT NULL,
  `oekDeelnameStatus_id` int DEFAULT NULL,
  `doorverwezenNaar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_deelname_statussen`
--

CREATE TABLE `oek_deelname_statussen` (
  `id` int NOT NULL,
  `datum` date NOT NULL,
  `status` varchar(255) NOT NULL,
  `oekDeelname_id` int NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_documenten`
--

CREATE TABLE `oek_documenten` (
  `id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_dossier_statussen`
--

CREATE TABLE `oek_dossier_statussen` (
  `id` int NOT NULL,
  `verwijzing_id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `datum` date NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `oekKlant_id` int NOT NULL,
  `class` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_groepen`
--

CREATE TABLE `oek_groepen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `aantal_bijeenkomsten` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_klanten`
--

CREATE TABLE `oek_klanten` (
  `id` int NOT NULL,
  `klant_id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `voedselbankklant` tinyint(1) NOT NULL,
  `opmerking` longtext,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `oekDossierStatus_id` int DEFAULT NULL,
  `oekAanmelding_id` int DEFAULT NULL,
  `oekAfsluiting_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_lidmaatschappen`
--

CREATE TABLE `oek_lidmaatschappen` (
  `id` int NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `oekGroep_id` int NOT NULL,
  `oekKlant_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_memos`
--

CREATE TABLE `oek_memos` (
  `id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `datum` datetime NOT NULL,
  `onderwerp` varchar(255) NOT NULL,
  `memo` longtext NOT NULL,
  `intake` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_trainingen`
--

CREATE TABLE `oek_trainingen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `startdatum` date NOT NULL,
  `starttijd` time DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `locatie` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `oekGroep_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_verwijzingen`
--

CREATE TABLE `oek_verwijzingen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `actief` tinyint(1) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `class` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_vrijwilligers`
--

CREATE TABLE `oek_vrijwilligers` (
  `id` int NOT NULL,
  `vrijwilliger_id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `active` tinyint(1) NOT NULL,
  `afsluitdatum` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_vrijwilliger_document`
--

CREATE TABLE `oek_vrijwilliger_document` (
  `vrijwilliger_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_vrijwilliger_memo`
--

CREATE TABLE `oek_vrijwilliger_memo` (
  `vrijwilliger_id` int NOT NULL,
  `memo_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `opmerkingen`
--

CREATE TABLE `opmerkingen` (
  `id` int NOT NULL,
  `categorie_id` int NOT NULL,
  `beschrijving` varchar(255) NOT NULL,
  `klant_id` int NOT NULL,
  `gezien` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `medewerker_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pfo_aard_relaties`
--

CREATE TABLE `pfo_aard_relaties` (
  `id` int NOT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `startdatum` date DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pfo_clienten`
--

CREATE TABLE `pfo_clienten` (
  `id` int NOT NULL,
  `roepnaam` varchar(255) DEFAULT NULL,
  `tussenvoegsel` varchar(255) DEFAULT NULL,
  `achternaam` varchar(255) DEFAULT NULL,
  `geslacht_id` int DEFAULT NULL,
  `geboortedatum` date DEFAULT NULL,
  `adres` varchar(255) DEFAULT NULL,
  `postcode` varchar(50) DEFAULT NULL,
  `plaats` varchar(255) DEFAULT NULL,
  `telefoon` varchar(255) DEFAULT NULL,
  `mobiel` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `notitie` text,
  `medewerker_id` int DEFAULT NULL,
  `groep` int DEFAULT NULL,
  `aard_relatie` int DEFAULT NULL,
  `dubbele_diagnose` int DEFAULT NULL,
  `eerdere_hulpverlening` tinyint(1) DEFAULT NULL,
  `via` text,
  `hulpverleners` text,
  `contacten` text,
  `begeleidings_formulier` date DEFAULT NULL,
  `brief_huisarts` date DEFAULT NULL,
  `evaluatie_formulier` date DEFAULT NULL,
  `datum_afgesloten` date DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `werkgebied` varchar(255) DEFAULT NULL,
  `postcodegebied` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pfo_clienten_documenten`
--

CREATE TABLE `pfo_clienten_documenten` (
  `document_id` int NOT NULL,
  `client_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pfo_clienten_supportgroups`
--

CREATE TABLE `pfo_clienten_supportgroups` (
  `id` int NOT NULL,
  `pfo_client_id` int NOT NULL,
  `pfo_supportgroup_client_id` int NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pfo_clienten_verslagen`
--

CREATE TABLE `pfo_clienten_verslagen` (
  `id` int NOT NULL,
  `pfo_client_id` int DEFAULT NULL,
  `pfo_verslag_id` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pfo_documenten`
--

CREATE TABLE `pfo_documenten` (
  `id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pfo_groepen`
--

CREATE TABLE `pfo_groepen` (
  `id` int NOT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `startdatum` date DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pfo_verslagen`
--

CREATE TABLE `pfo_verslagen` (
  `id` int NOT NULL,
  `contact_type` varchar(50) DEFAULT NULL,
  `verslag` text,
  `medewerker_id` int DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `postcodegebieden`
--

CREATE TABLE `postcodegebieden` (
  `postcodegebied` varchar(255) NOT NULL,
  `id` int NOT NULL,
  `van` int NOT NULL,
  `tot` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `postcodes`
--

CREATE TABLE `postcodes` (
  `postcode` varchar(255) NOT NULL,
  `stadsdeel` varchar(255) NOT NULL,
  `postcodegebied` varchar(255) DEFAULT NULL,
  `system` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `queue_tasks`
--

CREATE TABLE `queue_tasks` (
  `id` int NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `redenen`
--

CREATE TABLE `redenen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `registraties`
--

CREATE TABLE `registraties` (
  `id` int NOT NULL,
  `locatie_id` int DEFAULT NULL,
  `klant_id` int DEFAULT NULL,
  `binnen` datetime NOT NULL,
  `buiten` datetime DEFAULT NULL,
  `douche` int NOT NULL,
  `mw` int NOT NULL,
  `kleding` tinyint(1) NOT NULL,
  `maaltijd` tinyint(1) NOT NULL,
  `activering` tinyint(1) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `gbrv` int NOT NULL,
  `closed` tinyint(1) DEFAULT NULL,
  `binnen_date` date DEFAULT NULL,
  `klant_id_before_constraint` int DEFAULT NULL,
  `locatie_id_before_constraint` int DEFAULT NULL,
  `veegploeg` tinyint(1) NOT NULL,
  `aantalSpuiten` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `registraties_2010`
--

CREATE TABLE `registraties_2010` (
  `id` int NOT NULL,
  `locatie_id` int DEFAULT NULL,
  `klant_id` int DEFAULT NULL,
  `binnen` datetime NOT NULL,
  `buiten` datetime DEFAULT NULL,
  `douche` int NOT NULL,
  `mw` int NOT NULL,
  `kleding` tinyint(1) NOT NULL,
  `maaltijd` tinyint(1) NOT NULL,
  `activering` tinyint(1) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `gbrv` int NOT NULL,
  `closed` tinyint(1) DEFAULT NULL,
  `binnen_date` date DEFAULT NULL,
  `klant_id_before_constraint` int DEFAULT NULL,
  `locatie_id_before_constraint` int DEFAULT NULL,
  `veegploeg` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `registraties_2011`
--

CREATE TABLE `registraties_2011` (
  `id` int NOT NULL,
  `locatie_id` int DEFAULT NULL,
  `klant_id` int DEFAULT NULL,
  `binnen` datetime NOT NULL,
  `buiten` datetime DEFAULT NULL,
  `douche` int NOT NULL,
  `mw` int NOT NULL,
  `kleding` tinyint(1) NOT NULL,
  `maaltijd` tinyint(1) NOT NULL,
  `activering` tinyint(1) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `gbrv` int NOT NULL,
  `closed` tinyint(1) DEFAULT NULL,
  `binnen_date` date DEFAULT NULL,
  `klant_id_before_constraint` int DEFAULT NULL,
  `locatie_id_before_constraint` int DEFAULT NULL,
  `veegploeg` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `registraties_2012`
--

CREATE TABLE `registraties_2012` (
  `id` int NOT NULL,
  `locatie_id` int DEFAULT NULL,
  `klant_id` int DEFAULT NULL,
  `binnen` datetime NOT NULL,
  `buiten` datetime DEFAULT NULL,
  `douche` int NOT NULL,
  `mw` int NOT NULL,
  `kleding` tinyint(1) NOT NULL,
  `maaltijd` tinyint(1) NOT NULL,
  `activering` tinyint(1) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `gbrv` int NOT NULL,
  `closed` tinyint(1) DEFAULT NULL,
  `binnen_date` date DEFAULT NULL,
  `klant_id_before_constraint` int DEFAULT NULL,
  `locatie_id_before_constraint` int DEFAULT NULL,
  `veegploeg` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `registraties_2013`
--

CREATE TABLE `registraties_2013` (
  `id` int NOT NULL,
  `locatie_id` int DEFAULT NULL,
  `klant_id` int DEFAULT NULL,
  `binnen` datetime NOT NULL,
  `buiten` datetime DEFAULT NULL,
  `douche` int NOT NULL,
  `mw` int NOT NULL,
  `kleding` tinyint(1) NOT NULL,
  `maaltijd` tinyint(1) NOT NULL,
  `activering` tinyint(1) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `gbrv` int NOT NULL,
  `closed` tinyint(1) DEFAULT NULL,
  `binnen_date` date DEFAULT NULL,
  `klant_id_before_constraint` int DEFAULT NULL,
  `locatie_id_before_constraint` int DEFAULT NULL,
  `veegploeg` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `registraties_recent`
--

CREATE TABLE `registraties_recent` (
  `registratie_id` int NOT NULL,
  `locatie_id` int DEFAULT NULL,
  `klant_id` int DEFAULT NULL,
  `max_buiten` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `schorsingen`
--

CREATE TABLE `schorsingen` (
  `id` int NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date NOT NULL,
  `locatie_id` int DEFAULT NULL,
  `klant_id` int NOT NULL,
  `remark` longtext,
  `gezien` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `overig_reden` varchar(255) DEFAULT NULL,
  `aangifte` tinyint(1) NOT NULL DEFAULT '0',
  `nazorg` tinyint(1) NOT NULL DEFAULT '0',
  `aggressie_tegen_medewerker` int DEFAULT NULL,
  `aggressie_doelwit` varchar(255) DEFAULT NULL,
  `agressie` tinyint(1) NOT NULL DEFAULT '0',
  `aggressie_tegen_medewerker2` int DEFAULT NULL,
  `aggressie_doelwit2` varchar(255) DEFAULT NULL,
  `aggressie_tegen_medewerker3` int DEFAULT NULL,
  `aggressie_doelwit3` varchar(255) DEFAULT NULL,
  `aggressie_tegen_medewerker4` int DEFAULT NULL,
  `aggressie_doelwit4` varchar(255) DEFAULT NULL,
  `locatiehoofd` varchar(100) DEFAULT NULL,
  `bijzonderheden` longtext,
  `terugkeergesprekGehad` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `schorsingen_redenen`
--

CREATE TABLE `schorsingen_redenen` (
  `id` int NOT NULL,
  `schorsing_id` int NOT NULL,
  `reden_id` int NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `schorsing_locatie`
--

CREATE TABLE `schorsing_locatie` (
  `schorsing_id` int NOT NULL,
  `locatie_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `scip_beschikbaarheid`
--

CREATE TABLE `scip_beschikbaarheid` (
  `id` int NOT NULL,
  `deelname_id` int DEFAULT NULL,
  `maandagVan` time DEFAULT NULL,
  `maandagTot` time DEFAULT NULL,
  `dinsdagVan` time DEFAULT NULL,
  `dinsdagTot` time DEFAULT NULL,
  `woensdagVan` time DEFAULT NULL,
  `woensdagTot` time DEFAULT NULL,
  `donderdagVan` time DEFAULT NULL,
  `donderdagTot` time DEFAULT NULL,
  `vrijdagVan` time DEFAULT NULL,
  `vrijdagTot` time DEFAULT NULL,
  `zaterdagVan` time DEFAULT NULL,
  `zaterdagTot` time DEFAULT NULL,
  `zondagVan` time DEFAULT NULL,
  `zondagTot` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `scip_deelnames`
--

CREATE TABLE `scip_deelnames` (
  `id` int NOT NULL,
  `deelnemer_id` int DEFAULT NULL,
  `project_id` int DEFAULT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `scip_deelnemers`
--

CREATE TABLE `scip_deelnemers` (
  `id` int NOT NULL,
  `klant_id` int DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `functie` varchar(255) DEFAULT NULL,
  `werkbegeleider` varchar(255) DEFAULT NULL,
  `risNummer` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `evaluatiedatum` date DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `scip_deelnemer_document`
--

CREATE TABLE `scip_deelnemer_document` (
  `deelnemer_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `scip_deelnemer_label`
--

CREATE TABLE `scip_deelnemer_label` (
  `deelnemer_id` int NOT NULL,
  `label_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `scip_documenten`
--

CREATE TABLE `scip_documenten` (
  `id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `filename` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `naam` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `scip_labels`
--

CREATE TABLE `scip_labels` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `scip_projecten`
--

CREATE TABLE `scip_projecten` (
  `id` int NOT NULL,
  `kpl` varchar(255) DEFAULT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `scip_toegangsrechten`
--

CREATE TABLE `scip_toegangsrechten` (
  `id` int NOT NULL,
  `medewerker_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `scip_toegangsrecht_project`
--

CREATE TABLE `scip_toegangsrecht_project` (
  `toegangsrecht_id` int NOT NULL,
  `project_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `scip_verslagen`
--

CREATE TABLE `scip_verslagen` (
  `id` int NOT NULL,
  `deelnemer_id` int DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL,
  `tekst` longtext,
  `datum` date NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `isEvaluatie` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `scip_werkdoelen`
--

CREATE TABLE `scip_werkdoelen` (
  `id` int NOT NULL,
  `deelnemer_id` int DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL,
  `tekst` longtext,
  `datum` date NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `stadsdelen`
--

CREATE TABLE `stadsdelen` (
  `postcode` varchar(255) NOT NULL,
  `stadsdeel` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `talen`
--

CREATE TABLE `talen` (
  `id` int NOT NULL,
  `favoriet` tinyint(1) NOT NULL,
  `afkorting` varchar(255) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tmp_avgduration`
--

CREATE TABLE `tmp_avgduration` (
  `label` varchar(64) DEFAULT NULL,
  `range_start` int DEFAULT NULL,
  `range_end` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tmp_err`
--

CREATE TABLE `tmp_err` (
  `koppeling` int NOT NULL DEFAULT '0',
  `deelnemer` int NOT NULL DEFAULT '0',
  `vrijwilliger` int DEFAULT '0',
  `klant` int DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tmp_izid`
--

CREATE TABLE `tmp_izid` (
  `id` int NOT NULL DEFAULT '0',
  `iz_deelnemer_id` int NOT NULL,
  `iz_intervisiegroep_id` int NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tmp_open_days`
--

CREATE TABLE `tmp_open_days` (
  `locatie_id` int DEFAULT NULL,
  `open_day` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tmp_registraties`
--

CREATE TABLE `tmp_registraties` (
  `id` int NOT NULL DEFAULT '0',
  `locatie_id` int NOT NULL,
  `klant_id` int NOT NULL,
  `binnen` datetime NOT NULL,
  `buiten` datetime DEFAULT NULL,
  `douche` int NOT NULL,
  `mw` int NOT NULL,
  `kleding` tinyint(1) NOT NULL,
  `maaltijd` tinyint(1) NOT NULL,
  `activering` tinyint(1) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `gbrv` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tmp_rm`
--

CREATE TABLE `tmp_rm` (
  `id` int NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tmp_stadsdelen`
--

CREATE TABLE `tmp_stadsdelen` (
  `postcode` varchar(255) NOT NULL,
  `stadsdeel` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tmp_tmp_unique_2015`
--

CREATE TABLE `tmp_tmp_unique_2015` (
  `klant_id` int NOT NULL,
  `locaties` longblob,
  `binnen` longblob
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tmp_visitors`
--

CREATE TABLE `tmp_visitors` (
  `klant_id` int DEFAULT NULL,
  `land_id` int NOT NULL DEFAULT '1',
  `geslacht` varchar(255) CHARACTER SET utf8mb3 NOT NULL,
  `date` date DEFAULT NULL,
  `verslaving_id` int DEFAULT '0',
  `woonsituatie_id` int DEFAULT NULL,
  `verblijfstatus_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tmp_visitors_veegploeg`
--

CREATE TABLE `tmp_visitors_veegploeg` (
  `klant_id` int DEFAULT NULL,
  `land_id` int NOT NULL DEFAULT '1',
  `geslacht` varchar(255) CHARACTER SET utf8mb3 NOT NULL,
  `date` date DEFAULT NULL,
  `verslaving_id` int DEFAULT '0',
  `woonsituatie_id` int DEFAULT NULL,
  `verblijfstatus_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tmp_visits`
--

CREATE TABLE `tmp_visits` (
  `locatie_id` int DEFAULT NULL,
  `klant_id` int DEFAULT NULL,
  `date` date DEFAULT NULL,
  `gender` varchar(255) CHARACTER SET utf8mb3 NOT NULL,
  `duration` decimal(31,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tmp_visits_veegploeg`
--

CREATE TABLE `tmp_visits_veegploeg` (
  `locatie_id` int DEFAULT NULL,
  `klant_id` int DEFAULT NULL,
  `date` date DEFAULT NULL,
  `gender` varchar(255) CHARACTER SET utf8mb3 NOT NULL,
  `duration` decimal(31,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `trajecten`
--

CREATE TABLE `trajecten` (
  `id` int NOT NULL,
  `klant_id` int NOT NULL,
  `trajectbegeleider_id` int NOT NULL,
  `werkbegeleider_id` int NOT NULL,
  `klant_telefoonnummer` varchar(255) NOT NULL,
  `administratienummer` varchar(255) NOT NULL,
  `klantmanager` varchar(255) NOT NULL,
  `manager_telefoonnummer` varchar(255) NOT NULL,
  `manager_email` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_aanvullinginkomen`
--

CREATE TABLE `tw_aanvullinginkomen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_afsluitingen`
--

CREATE TABLE `tw_afsluitingen` (
  `id` int NOT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `discr` varchar(255) NOT NULL,
  `tonen` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_afsluitredenen_vrijwilligers`
--

CREATE TABLE `tw_afsluitredenen_vrijwilligers` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_alcohol`
--

CREATE TABLE `tw_alcohol` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_binnen_via`
--

CREATE TABLE `tw_binnen_via` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_coordinatoren`
--

CREATE TABLE `tw_coordinatoren` (
  `id` int NOT NULL,
  `medewerker_id` int DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_dagbesteding`
--

CREATE TABLE `tw_dagbesteding` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_deelnames`
--

CREATE TABLE `tw_deelnames` (
  `id` int NOT NULL,
  `tw_vrijwilliger_id` int NOT NULL,
  `overig` varchar(255) DEFAULT NULL,
  `datum` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `mwTraining_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_deelnemers`
--

CREATE TABLE `tw_deelnemers` (
  `id` int NOT NULL,
  `appKlant_id` int NOT NULL,
  `medewerker_id` int DEFAULT NULL,
  `pandeigenaar_id` int DEFAULT NULL,
  `aanmelddatum` date NOT NULL,
  `afsluitdatum` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `model` varchar(255) NOT NULL,
  `afsluiting_id` int DEFAULT NULL,
  `pandeigenaar_toelichting` varchar(255) DEFAULT NULL,
  `rekeningnummer` varchar(255) DEFAULT NULL,
  `wpi` tinyint(1) NOT NULL,
  `klantmanager` varchar(255) DEFAULT NULL,
  `automatischeIncasso` tinyint(1) DEFAULT NULL,
  `ksgw` tinyint(1) DEFAULT NULL,
  `waPolis` tinyint(1) DEFAULT NULL,
  `huurBudget_id` int DEFAULT NULL,
  `werk_id` int DEFAULT NULL,
  `duurThuisloos_id` int DEFAULT NULL,
  `ambulantOndersteuner_id` int DEFAULT NULL,
  `begeleider` varchar(255) DEFAULT NULL,
  `project_id` int DEFAULT NULL,
  `zrm_id` int DEFAULT NULL,
  `dagbesteding_id` int DEFAULT NULL,
  `ritme_id` int DEFAULT NULL,
  `huisdieren_id` int DEFAULT NULL,
  `roken_id` int DEFAULT NULL,
  `softdrugs_id` int DEFAULT NULL,
  `traplopen_id` int DEFAULT NULL,
  `bindingRegio_id` int DEFAULT NULL,
  `moScreening_id` int DEFAULT NULL,
  `inschrijvingWoningnet_id` int DEFAULT NULL,
  `inkomen_id` int DEFAULT NULL,
  `huurtoeslag` tinyint(1) DEFAULT NULL,
  `kwijtschelding` tinyint(1) DEFAULT NULL,
  `verhuurprijs` int DEFAULT NULL,
  `intakeStatus_id` int DEFAULT NULL,
  `alcohol_id` int DEFAULT NULL,
  `shortlist_id` int DEFAULT NULL,
  `inkomensverklaring` varchar(255) DEFAULT NULL,
  `toelichtingInkomen` varchar(255) DEFAULT NULL,
  `kwijtschelding_id` int DEFAULT NULL,
  `aanvullingInkomen_id` int DEFAULT NULL,
  `huisgenoot_id` int DEFAULT NULL,
  `samenvatting` longtext,
  `huurprijs` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_deelnemer_document`
--

CREATE TABLE `tw_deelnemer_document` (
  `deelnemer_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_deelnemer_verslag`
--

CREATE TABLE `tw_deelnemer_verslag` (
  `deelnemer_id` int NOT NULL,
  `verslag_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_duurthuisloos`
--

CREATE TABLE `tw_duurthuisloos` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `minVal` int DEFAULT NULL,
  `maxVal` int DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_huisdieren`
--

CREATE TABLE `tw_huisdieren` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_huuraanbiedingen`
--

CREATE TABLE `tw_huuraanbiedingen` (
  `id` int NOT NULL,
  `verhuurder_id` int DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL,
  `startdatum` date NOT NULL,
  `afsluitdatum` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `afsluiting_id` int DEFAULT NULL,
  `datumToestemmingAangevraagd` date DEFAULT NULL,
  `datumToestemmingToegekend` date DEFAULT NULL,
  `vormvanovereenkomst_id` int DEFAULT NULL,
  `project_id` int DEFAULT NULL,
  `huurprijs` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_huuraanbod_verslag`
--

CREATE TABLE `tw_huuraanbod_verslag` (
  `huuraanbod_id` int NOT NULL,
  `verslag_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_huurbudget`
--

CREATE TABLE `tw_huurbudget` (
  `id` int NOT NULL,
  `minVal` int DEFAULT NULL,
  `maxVal` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_huurders_tw_projecten`
--

CREATE TABLE `tw_huurders_tw_projecten` (
  `tw_huurder_id` int NOT NULL,
  `tw_project_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_huurovereenkomsten`
--

CREATE TABLE `tw_huurovereenkomsten` (
  `id` int NOT NULL,
  `huuraanbod_id` int DEFAULT NULL,
  `huurverzoek_id` int DEFAULT NULL,
  `medewerker_id` int NOT NULL,
  `startdatum` date NOT NULL,
  `opzegdatum` date DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `afsluiting_id` int DEFAULT NULL,
  `afsluitdatum` date DEFAULT NULL,
  `vorm` varchar(50) DEFAULT NULL,
  `opzegbrief_verstuurd` tinyint(1) NOT NULL,
  `isReservering` tinyint(1) NOT NULL,
  `huurovereenkomstType_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_huurovereenkomst_document`
--

CREATE TABLE `tw_huurovereenkomst_document` (
  `huurovereenkomst_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_huurovereenkomst_findocument`
--

CREATE TABLE `tw_huurovereenkomst_findocument` (
  `huurovereenkomst_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_huurovereenkomst_finverslag`
--

CREATE TABLE `tw_huurovereenkomst_finverslag` (
  `huurovereenkomst_id` int NOT NULL,
  `verslag_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_huurovereenkomst_type`
--

CREATE TABLE `tw_huurovereenkomst_type` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_huurovereenkomst_verslag`
--

CREATE TABLE `tw_huurovereenkomst_verslag` (
  `huurovereenkomst_id` int NOT NULL,
  `verslag_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_huurverzoeken`
--

CREATE TABLE `tw_huurverzoeken` (
  `id` int NOT NULL,
  `klant_id` int DEFAULT NULL,
  `medewerker_id` int NOT NULL,
  `startdatum` date NOT NULL,
  `afsluitdatum` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `afsluiting_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_huurverzoeken_tw_projecten`
--

CREATE TABLE `tw_huurverzoeken_tw_projecten` (
  `tw_huurverzoek_id` int NOT NULL,
  `tw_project_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_huurverzoek_verslag`
--

CREATE TABLE `tw_huurverzoek_verslag` (
  `huurverzoek_id` int NOT NULL,
  `verslag_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_inkomen`
--

CREATE TABLE `tw_inkomen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `order` int NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_inschrijvingwoningnet`
--

CREATE TABLE `tw_inschrijvingwoningnet` (
  `id` int NOT NULL,
  `label` varchar(255) NOT NULL,
  `order` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_intakes`
--

CREATE TABLE `tw_intakes` (
  `id` int NOT NULL,
  `deelnemer_id` int DEFAULT NULL,
  `medewerker_id` int NOT NULL,
  `intake_datum` date NOT NULL,
  `gezin_met_kinderen` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_intakestatus`
--

CREATE TABLE `tw_intakestatus` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_kwijtschelding`
--

CREATE TABLE `tw_kwijtschelding` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_locaties`
--

CREATE TABLE `tw_locaties` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_moscreening`
--

CREATE TABLE `tw_moscreening` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_pandeigenaar`
--

CREATE TABLE `tw_pandeigenaar` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `pandeigenaartype_id` int NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_pandeigenaartype`
--

CREATE TABLE `tw_pandeigenaartype` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_projecten`
--

CREATE TABLE `tw_projecten` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `startdatum` date NOT NULL,
  `einddatum` date DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_regio`
--

CREATE TABLE `tw_regio` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_ritme`
--

CREATE TABLE `tw_ritme` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_roken`
--

CREATE TABLE `tw_roken` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_softdrugs`
--

CREATE TABLE `tw_softdrugs` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_superdocumenten`
--

CREATE TABLE `tw_superdocumenten` (
  `id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `datum` date NOT NULL,
  `class` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_superverslagen`
--

CREATE TABLE `tw_superverslagen` (
  `id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `datum` datetime NOT NULL,
  `opmerking` longtext,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `class` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_training`
--

CREATE TABLE `tw_training` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_traplopen`
--

CREATE TABLE `tw_traplopen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_vormvanovereenkomst`
--

CREATE TABLE `tw_vormvanovereenkomst` (
  `id` int NOT NULL,
  `label` varchar(255) NOT NULL,
  `startdate` date NOT NULL,
  `enddate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_vrijwilligers`
--

CREATE TABLE `tw_vrijwilligers` (
  `id` int NOT NULL,
  `vrijwilliger_id` int NOT NULL,
  `binnen_via_id` int DEFAULT NULL,
  `afsluitreden_id` int DEFAULT NULL,
  `medewerker_id` int NOT NULL,
  `aanmelddatum` date NOT NULL,
  `afsluitdatum` date DEFAULT NULL,
  `stagiair` tinyint(1) NOT NULL,
  `notitieIntake` varchar(255) DEFAULT NULL,
  `datumNotitieIntake` datetime DEFAULT NULL,
  `trainingOverig` varchar(255) DEFAULT NULL,
  `trainingOverigDatum` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_vrijwilliger_document`
--

CREATE TABLE `tw_vrijwilliger_document` (
  `vrijwilliger_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_vrijwilliger_locatie`
--

CREATE TABLE `tw_vrijwilliger_locatie` (
  `vrijwilliger_id` int NOT NULL,
  `locatie_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_vrijwilliger_memo`
--

CREATE TABLE `tw_vrijwilliger_memo` (
  `vrijwilliger_id` int NOT NULL,
  `memo_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_werk`
--

CREATE TABLE `tw_werk` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tw_woningbouwcorporaties`
--

CREATE TABLE `tw_woningbouwcorporaties` (
  `id` int NOT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `uhk_deelnemers`
--

CREATE TABLE `uhk_deelnemers` (
  `id` int NOT NULL,
  `klant_id` int DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL,
  `contactpersoonNazorg` varchar(255) DEFAULT NULL,
  `aanmelder_id` int DEFAULT NULL,
  `aanmelddatum` date NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `uhk_deelnemer_document`
--

CREATE TABLE `uhk_deelnemer_document` (
  `deelnemer_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `uhk_documenten`
--

CREATE TABLE `uhk_documenten` (
  `id` int NOT NULL,
  `medewerker_id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `uhk_verslagen`
--

CREATE TABLE `uhk_verslagen` (
  `id` int NOT NULL,
  `deelnemer_id` int DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL,
  `tekst` longtext,
  `datum` date NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `verblijfstatussen`
--

CREATE TABLE `verblijfstatussen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `verslagen`
--

CREATE TABLE `verslagen` (
  `id` int NOT NULL,
  `klant_id` int DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL,
  `opmerking` text,
  `datum` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `medewerker` varchar(255) DEFAULT NULL,
  `locatie_id` int DEFAULT NULL,
  `aanpassing_verslag` int DEFAULT NULL,
  `contactsoort_id` int DEFAULT NULL,
  `verslagType` int NOT NULL DEFAULT '1',
  `accessType` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `verslaginfos`
--

CREATE TABLE `verslaginfos` (
  `id` int NOT NULL,
  `klant_id` int NOT NULL,
  `advocaat` varchar(255) DEFAULT NULL,
  `contact` longtext,
  `casemanager_id` int DEFAULT NULL,
  `casemanager_email` varchar(255) DEFAULT NULL,
  `casemanager_telefoonnummer` varchar(255) DEFAULT NULL,
  `trajectbegeleider_id` int DEFAULT NULL,
  `trajectbegeleider_email` varchar(255) DEFAULT NULL,
  `trajectbegeleider_telefoonnummer` varchar(255) DEFAULT NULL,
  `trajecthouder_extern_organisatie` varchar(255) DEFAULT NULL,
  `trajecthouder_extern_naam` varchar(255) DEFAULT NULL,
  `trajecthouder_extern_email` varchar(255) DEFAULT NULL,
  `trajecthouder_extern_telefoonnummer` varchar(255) DEFAULT NULL,
  `overige_contactpersonen_extern` longtext,
  `instantie` varchar(255) DEFAULT NULL,
  `registratienummer` varchar(255) DEFAULT NULL,
  `budgettering` varchar(255) DEFAULT NULL,
  `contactpersoon` varchar(255) DEFAULT NULL,
  `klantmanager_naam` varchar(255) DEFAULT NULL,
  `klantmanager_email` varchar(255) DEFAULT NULL,
  `klantmanager_telefoonnummer` varchar(255) DEFAULT NULL,
  `sociaal_netwerk` longtext,
  `bankrekeningnummer` varchar(255) DEFAULT NULL,
  `polisnummer_ziektekostenverzekering` varchar(255) DEFAULT NULL,
  `inschrijfnummer` varchar(255) DEFAULT NULL,
  `wachtwoord` varchar(255) DEFAULT NULL,
  `telefoonnummer` varchar(255) DEFAULT NULL,
  `adres` longtext,
  `overigen` longtext,
  `risDatumTot` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `verslavingen`
--

CREATE TABLE `verslavingen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `verslavingsfrequenties`
--

CREATE TABLE `verslavingsfrequenties` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `verslavingsgebruikswijzen`
--

CREATE TABLE `verslavingsgebruikswijzen` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `verslavingsperiodes`
--

CREATE TABLE `verslavingsperiodes` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `villa_afsluitredenen_vrijwilligers`
--

CREATE TABLE `villa_afsluitredenen_vrijwilligers` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `villa_binnen_via`
--

CREATE TABLE `villa_binnen_via` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `villa_deelnames`
--

CREATE TABLE `villa_deelnames` (
  `id` int NOT NULL,
  `villa_vrijwilliger_id` int NOT NULL,
  `overig` varchar(255) DEFAULT NULL,
  `datum` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `mwTraining_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `villa_documenten`
--

CREATE TABLE `villa_documenten` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `villa_training`
--

CREATE TABLE `villa_training` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `villa_vrijwilligers`
--

CREATE TABLE `villa_vrijwilligers` (
  `id` int NOT NULL,
  `vrijwilliger_id` int NOT NULL,
  `binnen_via_id` int DEFAULT NULL,
  `afsluitreden_id` int DEFAULT NULL,
  `medewerker_id` int NOT NULL,
  `aanmelddatum` date NOT NULL,
  `afsluitdatum` date DEFAULT NULL,
  `stagiair` tinyint(1) NOT NULL,
  `startdatum` date DEFAULT NULL,
  `notitieIntake` varchar(255) DEFAULT NULL,
  `datumNotitieIntake` datetime DEFAULT NULL,
  `trainingOverig` varchar(255) DEFAULT NULL,
  `trainingOverigDatum` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `medewerkerLocatie_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `villa_vrijwilliger_document`
--

CREATE TABLE `villa_vrijwilliger_document` (
  `vrijwilliger_id` int NOT NULL,
  `document_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `villa_vrijwilliger_memo`
--

CREATE TABLE `villa_vrijwilliger_memo` (
  `vrijwilliger_id` int NOT NULL,
  `memo_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `vrijwilligers`
--

CREATE TABLE `vrijwilligers` (
  `id` int NOT NULL,
  `voornaam` varchar(255) DEFAULT NULL,
  `tussenvoegsel` varchar(255) DEFAULT NULL,
  `achternaam` varchar(255) DEFAULT NULL,
  `roepnaam` varchar(255) DEFAULT NULL,
  `geslacht_id` int NOT NULL DEFAULT '0',
  `geboortedatum` date DEFAULT NULL,
  `land_id` int NOT NULL DEFAULT '1',
  `nationaliteit_id` int NOT NULL DEFAULT '1',
  `BSN` varchar(255) DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL,
  `adres` varchar(255) DEFAULT NULL,
  `postcode` varchar(6) DEFAULT NULL,
  `werkgebied` varchar(55) DEFAULT NULL,
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
  `postcodegebied` varchar(50) DEFAULT NULL,
  `vog_aangevraagd` tinyint(1) NOT NULL DEFAULT '0',
  `vog_aanwezig` tinyint(1) NOT NULL DEFAULT '0',
  `overeenkomst_aanwezig` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `werkgebieden`
--

CREATE TABLE `werkgebieden` (
  `naam` varchar(255) NOT NULL,
  `zichtbaar` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `woonsituaties`
--

CREATE TABLE `woonsituaties` (
  `id` int NOT NULL,
  `naam` varchar(255) NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `zrm_reports`
--

CREATE TABLE `zrm_reports` (
  `id` int NOT NULL,
  `klant_id` int NOT NULL,
  `model` varchar(50) DEFAULT NULL,
  `foreign_key` int DEFAULT NULL,
  `request_module` varchar(50) NOT NULL,
  `inkomen` int DEFAULT NULL,
  `dagbesteding` int DEFAULT NULL,
  `huisvesting` int DEFAULT NULL,
  `gezinsrelaties` int DEFAULT NULL,
  `geestelijke_gezondheid` int DEFAULT NULL,
  `fysieke_gezondheid` int DEFAULT NULL,
  `verslaving` int DEFAULT NULL,
  `adl_vaardigheden` int DEFAULT NULL,
  `sociaal_netwerk` int DEFAULT NULL,
  `maatschappelijke_participatie` int DEFAULT NULL,
  `justitie` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `discr` varchar(5) NOT NULL,
  `financien` int DEFAULT NULL,
  `werk_opleiding` int DEFAULT NULL,
  `tijdsbesteding` int DEFAULT NULL,
  `huiselijke_relaties` int DEFAULT NULL,
  `lichamelijke_gezondheid` int DEFAULT NULL,
  `middelengebruik` int DEFAULT NULL,
  `basale_adl` int DEFAULT NULL,
  `instrumentele_adl` int DEFAULT NULL,
  `medewerker_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `zrm_settings`
--

CREATE TABLE `zrm_settings` (
  `id` int NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `zrm_v2_reports`
--

CREATE TABLE `zrm_v2_reports` (
  `id` int NOT NULL,
  `klant_id` int NOT NULL,
  `model` varchar(255) NOT NULL,
  `foreign_key` int NOT NULL,
  `request_module` varchar(255) NOT NULL,
  `financien` int DEFAULT NULL,
  `werk_opleiding` int DEFAULT NULL,
  `tijdsbesteding` int DEFAULT NULL,
  `huisvesting` int DEFAULT NULL,
  `huiselijke_relaties` int DEFAULT NULL,
  `geestelijke_gezondheid` int DEFAULT NULL,
  `lichamelijke_gezondheid` int DEFAULT NULL,
  `middelengebruik` int DEFAULT NULL,
  `basale_adl` int DEFAULT NULL,
  `instrumentele_adl` int DEFAULT NULL,
  `sociaal_netwerk` int DEFAULT NULL,
  `maatschappelijke_participatie` int DEFAULT NULL,
  `justitie` int DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `zrm_v2_settings`
--

CREATE TABLE `zrm_v2_settings` (
  `id` int NOT NULL,
  `request_module` varchar(50) NOT NULL,
  `financien` tinyint(1) DEFAULT NULL,
  `werk_opleiding` tinyint(1) DEFAULT NULL,
  `tijdsbesteding` tinyint(1) DEFAULT NULL,
  `huisvesting` tinyint(1) DEFAULT NULL,
  `huiselijke_relaties` tinyint(1) DEFAULT NULL,
  `geestelijke_gezondheid` tinyint(1) DEFAULT NULL,
  `lichamelijke_gezondheid` tinyint(1) DEFAULT NULL,
  `middelengebruik` tinyint(1) DEFAULT NULL,
  `basale_adl` tinyint(1) DEFAULT NULL,
  `instrumentele_adl` tinyint(1) DEFAULT NULL,
  `sociaal_netwerk` tinyint(1) DEFAULT NULL,
  `maatschappelijke_participatie` tinyint(1) DEFAULT NULL,
  `justitie` tinyint(1) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `amoc_landen`
--
ALTER TABLE `amoc_landen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_2B24A60A1994904A` (`land_id`);

--
-- Indexen voor tabel `app_klant_document`
--
ALTER TABLE `app_klant_document`
  ADD PRIMARY KEY (`klant_id`,`document_id`),
  ADD UNIQUE KEY `UNIQ_7BA5F5BC33F7837` (`document_id`),
  ADD KEY `IDX_7BA5F5B3C427B2F` (`klant_id`);

--
-- Indexen voor tabel `app_vrijwilliger_document`
--
ALTER TABLE `app_vrijwilliger_document`
  ADD PRIMARY KEY (`vrijwilliger_id`,`document_id`),
  ADD UNIQUE KEY `UNIQ_D5E9A8C9C33F7837` (`document_id`),
  ADD KEY `IDX_D5E9A8C976B43BDC` (`vrijwilliger_id`);

--
-- Indexen voor tabel `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `awbz_hoofdaannemers`
--
ALTER TABLE `awbz_hoofdaannemers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_awbz_hoofdaannemers_klant_id` (`klant_id`),
  ADD KEY `idx_awbz_hoofdaannemers_hoofdaannemer_id` (`hoofdaannemer_id`);

--
-- Indexen voor tabel `awbz_indicaties`
--
ALTER TABLE `awbz_indicaties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_awbz_indicaties_klant_id` (`klant_id`);

--
-- Indexen voor tabel `awbz_intakes`
--
ALTER TABLE `awbz_intakes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_awbz_intakes_klant_id` (`klant_id`);

--
-- Indexen voor tabel `awbz_intakes_primaireproblematieksgebruikswijzen`
--
ALTER TABLE `awbz_intakes_primaireproblematieksgebruikswijzen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `awbz_intakes_verslavingen`
--
ALTER TABLE `awbz_intakes_verslavingen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `awbz_intakes_verslavingsgebruikswijzen`
--
ALTER TABLE `awbz_intakes_verslavingsgebruikswijzen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `back_on_tracks`
--
ALTER TABLE `back_on_tracks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_back_on_tracks_dates` (`startdatum`,`einddatum`);

--
-- Indexen voor tabel `bedrijfitems`
--
ALTER TABLE `bedrijfitems`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `bedrijfsectoren`
--
ALTER TABLE `bedrijfsectoren`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `bot_koppelingen`
--
ALTER TABLE `bot_koppelingen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `bot_verslagen`
--
ALTER TABLE `bot_verslagen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_bto_verslagen_klant_id` (`klant_id`);

--
-- Indexen voor tabel `buurtboerderij_afsluitredenen`
--
ALTER TABLE `buurtboerderij_afsluitredenen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `buurtboerderij_vrijwilligers`
--
ALTER TABLE `buurtboerderij_vrijwilligers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_57645FD776B43BDC` (`vrijwilliger_id`),
  ADD KEY `IDX_57645FD7CA12F7AE` (`afsluitreden_id`),
  ADD KEY `IDX_57645FD73D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `categorieen`
--
ALTER TABLE `categorieen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `clip_afsluitredenen_vrijwilligers`
--
ALTER TABLE `clip_afsluitredenen_vrijwilligers`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `clip_behandelaars`
--
ALTER TABLE `clip_behandelaars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_4B016D223D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `clip_binnen_via`
--
ALTER TABLE `clip_binnen_via`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `clip_clienten`
--
ALTER TABLE `clip_clienten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B7F4C67EC5BB5F49` (`viacategorie_id`),
  ADD KEY `IDX_B7F4C67E35A09212` (`behandelaar_id`),
  ADD KEY `IDX_B7F4C67E1C729A47` (`geslacht_id`),
  ADD KEY `IDX_B7F4C67E46708ED5` (`werkgebied`),
  ADD KEY `IDX_B7F4C67EFB02B9C2` (`postcodegebied`);

--
-- Indexen voor tabel `clip_client_document`
--
ALTER TABLE `clip_client_document`
  ADD PRIMARY KEY (`client_id`,`document_id`),
  ADD KEY `IDX_18AEA4C519EB6921` (`client_id`),
  ADD KEY `IDX_18AEA4C5C33F7837` (`document_id`);

--
-- Indexen voor tabel `clip_communicatiekanalen`
--
ALTER TABLE `clip_communicatiekanalen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `clip_contactmomenten`
--
ALTER TABLE `clip_contactmomenten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8C4DFF3D2CE1D7E6` (`vraag_id`),
  ADD KEY `IDX_8C4DFF3D35A09212` (`behandelaar_id`);

--
-- Indexen voor tabel `clip_deelnames`
--
ALTER TABLE `clip_deelnames`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_BDA50576459F3233` (`mwTraining_id`),
  ADD KEY `IDX_BDA50576B280D297` (`clip_vrijwilliger_id`);

--
-- Indexen voor tabel `clip_documenten`
--
ALTER TABLE `clip_documenten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_98FCA35A09212` (`behandelaar_id`);

--
-- Indexen voor tabel `clip_hulpvragersoorten`
--
ALTER TABLE `clip_hulpvragersoorten`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `clip_leeftijdscategorieen`
--
ALTER TABLE `clip_leeftijdscategorieen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `clip_locaties`
--
ALTER TABLE `clip_locaties`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `clip_memos`
--
ALTER TABLE `clip_memos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_BB25CF7C3D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `clip_training`
--
ALTER TABLE `clip_training`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `clip_viacategorieen`
--
ALTER TABLE `clip_viacategorieen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `clip_vraagsoorten`
--
ALTER TABLE `clip_vraagsoorten`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `clip_vraag_document`
--
ALTER TABLE `clip_vraag_document`
  ADD PRIMARY KEY (`vraag_id`,`document_id`),
  ADD KEY `IDX_37F7BFD72CE1D7E6` (`vraag_id`),
  ADD KEY `IDX_37F7BFD7C33F7837` (`document_id`);

--
-- Indexen voor tabel `clip_vragen`
--
ALTER TABLE `clip_vragen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C28C591719EB6921` (`client_id`),
  ADD KEY `IDX_C28C59173DEE50DF` (`soort_id`),
  ADD KEY `IDX_C28C591717F2E03B` (`hulpvrager_id`),
  ADD KEY `IDX_C28C591771CC83CE` (`communicatiekanaal_id`),
  ADD KEY `IDX_C28C59172EC18014` (`leeftijdscategorie_id`),
  ADD KEY `IDX_C28C591735A09212` (`behandelaar_id`);

--
-- Indexen voor tabel `clip_vrijwilligers`
--
ALTER TABLE `clip_vrijwilligers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_E0E2570B76B43BDC` (`vrijwilliger_id`),
  ADD KEY `IDX_E0E2570B4C676E6B` (`binnen_via_id`),
  ADD KEY `IDX_E0E2570BCA12F7AE` (`afsluitreden_id`),
  ADD KEY `IDX_E0E2570BEA9C84FE` (`medewerkerLocatie_id`),
  ADD KEY `IDX_E0E2570B3D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `clip_vrijwilliger_document`
--
ALTER TABLE `clip_vrijwilliger_document`
  ADD PRIMARY KEY (`vrijwilliger_id`,`document_id`),
  ADD UNIQUE KEY `UNIQ_74EC4DF8C33F7837` (`document_id`),
  ADD KEY `IDX_74EC4DF876B43BDC` (`vrijwilliger_id`);

--
-- Indexen voor tabel `clip_vrijwilliger_locatie`
--
ALTER TABLE `clip_vrijwilliger_locatie`
  ADD PRIMARY KEY (`vrijwilliger_id`,`locatie_id`),
  ADD KEY `IDX_96F1920576B43BDC` (`vrijwilliger_id`),
  ADD KEY `IDX_96F192054947630C` (`locatie_id`);

--
-- Indexen voor tabel `clip_vrijwilliger_memo`
--
ALTER TABLE `clip_vrijwilliger_memo`
  ADD PRIMARY KEY (`vrijwilliger_id`,`memo_id`),
  ADD UNIQUE KEY `UNIQ_DDCB9E0DB4D32439` (`memo_id`),
  ADD KEY `IDX_DDCB9E0D76B43BDC` (`vrijwilliger_id`);

--
-- Indexen voor tabel `contactjournals`
--
ALTER TABLE `contactjournals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_contactjournals_klant_id` (`klant_id`);

--
-- Indexen voor tabel `contactsoorts`
--
ALTER TABLE `contactsoorts`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `dagbesteding_afsluitingen`
--
ALTER TABLE `dagbesteding_afsluitingen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `dagbesteding_beschikbaarheid`
--
ALTER TABLE `dagbesteding_beschikbaarheid`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_912C9E7AC18FA9D5` (`deelname_id`);

--
-- Indexen voor tabel `dagbesteding_contactpersonen`
--
ALTER TABLE `dagbesteding_contactpersonen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C14C44B85DFA57A1` (`deelnemer_id`);

--
-- Indexen voor tabel `dagbesteding_dagdelen`
--
ALTER TABLE `dagbesteding_dagdelen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_traject_datum_dagdeel_idx` (`traject_id`,`datum`,`dagdeel`),
  ADD KEY `IDX_54F41972A0CADD4` (`traject_id`),
  ADD KEY `IDX_54F41972166D1F9C` (`project_id`);

--
-- Indexen voor tabel `dagbesteding_deelnames`
--
ALTER TABLE `dagbesteding_deelnames`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_328AD7035DFA57A1` (`traject_id`),
  ADD KEY `IDX_328AD703166D1F9C` (`project_id`);

--
-- Indexen voor tabel `dagbesteding_deelnemers`
--
ALTER TABLE `dagbesteding_deelnemers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_6EAE83E73C427B2F` (`klant_id`),
  ADD KEY `IDX_6EAE83E7ECDAD1A9` (`afsluiting_id`),
  ADD KEY `IDX_6EAE83E73D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `dagbesteding_deelnemer_document`
--
ALTER TABLE `dagbesteding_deelnemer_document`
  ADD PRIMARY KEY (`deelnemer_id`,`document_id`),
  ADD KEY `IDX_89539E645DFA57A1` (`deelnemer_id`),
  ADD KEY `IDX_89539E64C33F7837` (`document_id`);

--
-- Indexen voor tabel `dagbesteding_deelnemer_verslag`
--
ALTER TABLE `dagbesteding_deelnemer_verslag`
  ADD PRIMARY KEY (`deelnemer_id`,`verslag_id`),
  ADD KEY `IDX_BA9CAC335DFA57A1` (`deelnemer_id`),
  ADD KEY `IDX_BA9CAC33D949475D` (`verslag_id`);

--
-- Indexen voor tabel `dagbesteding_documenten`
--
ALTER TABLE `dagbesteding_documenten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_20E925AB3D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `dagbesteding_locaties`
--
ALTER TABLE `dagbesteding_locaties`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `dagbesteding_projecten`
--
ALTER TABLE `dagbesteding_projecten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6AD94DA34947630C` (`locatie_id`);

--
-- Indexen voor tabel `dagbesteding_rapportages`
--
ALTER TABLE `dagbesteding_rapportages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_FBA61484A0CADD4` (`traject_id`),
  ADD KEY `IDX_FBA614843D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `dagbesteding_rapportage_document`
--
ALTER TABLE `dagbesteding_rapportage_document`
  ADD PRIMARY KEY (`rapportage_id`,`document_id`),
  ADD KEY `IDX_8ED5B83668A3850` (`rapportage_id`),
  ADD KEY `IDX_8ED5B83C33F7837` (`document_id`);

--
-- Indexen voor tabel `dagbesteding_resultaatgebieden`
--
ALTER TABLE `dagbesteding_resultaatgebieden`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_4F7529D3A0CADD4` (`traject_id`),
  ADD KEY `IDX_4F7529D33DEE50DF` (`soort_id`);

--
-- Indexen voor tabel `dagbesteding_resultaatgebiedsoorten`
--
ALTER TABLE `dagbesteding_resultaatgebiedsoorten`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `dagbesteding_trajectcoaches`
--
ALTER TABLE `dagbesteding_trajectcoaches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C703865F3D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `dagbesteding_trajecten`
--
ALTER TABLE `dagbesteding_trajecten`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_670A67F21BE6904` (`resultaatgebied_id`),
  ADD KEY `IDX_670A67F25DFA57A1` (`deelnemer_id`),
  ADD KEY `IDX_670A67F23DEE50DF` (`soort_id`),
  ADD KEY `IDX_670A67F2ECDAD1A9` (`afsluiting_id`),
  ADD KEY `IDX_670A67F2AEDCD25A` (`trajectcoach_id`);

--
-- Indexen voor tabel `dagbesteding_trajectsoorten`
--
ALTER TABLE `dagbesteding_trajectsoorten`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `dagbesteding_traject_document`
--
ALTER TABLE `dagbesteding_traject_document`
  ADD PRIMARY KEY (`traject_id`,`document_id`),
  ADD KEY `IDX_5408B1ADA0CADD4` (`traject_id`),
  ADD KEY `IDX_5408B1ADC33F7837` (`document_id`);

--
-- Indexen voor tabel `dagbesteding_traject_locatie`
--
ALTER TABLE `dagbesteding_traject_locatie`
  ADD PRIMARY KEY (`traject_id`,`locatie_id`),
  ADD KEY `IDX_1D887470A0CADD4` (`traject_id`),
  ADD KEY `IDX_1D8874704947630C` (`locatie_id`);

--
-- Indexen voor tabel `dagbesteding_traject_project`
--
ALTER TABLE `dagbesteding_traject_project`
  ADD PRIMARY KEY (`traject_id`,`project_id`),
  ADD KEY `IDX_9DF4F8B0A0CADD4` (`traject_id`),
  ADD KEY `IDX_9DF4F8B0166D1F9C` (`project_id`);

--
-- Indexen voor tabel `dagbesteding_traject_verslag`
--
ALTER TABLE `dagbesteding_traject_verslag`
  ADD PRIMARY KEY (`traject_id`,`verslag_id`),
  ADD KEY `IDX_ECCFAC5CA0CADD4` (`traject_id`),
  ADD KEY `IDX_ECCFAC5CD949475D` (`verslag_id`);

--
-- Indexen voor tabel `dagbesteding_verslagen`
--
ALTER TABLE `dagbesteding_verslagen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F41415953D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `dagbesteding_werkdoelen`
--
ALTER TABLE `dagbesteding_werkdoelen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_56257F585DFA57A1` (`deelnemer_id`),
  ADD KEY `IDX_56257F58A0CADD4` (`traject_id`),
  ADD KEY `IDX_56257F583D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `documenten`
--
ALTER TABLE `documenten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8751AD653D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `doelstellingen`
--
ALTER TABLE `doelstellingen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `doorverwijzers`
--
ALTER TABLE `doorverwijzers`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `eropuit_klanten`
--
ALTER TABLE `eropuit_klanten`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_4B38B9823C427B2F` (`klant_id`),
  ADD KEY `IDX_4B38B9825D010236` (`uitschrijfreden_id`);

--
-- Indexen voor tabel `eropuit_uitschrijfredenen`
--
ALTER TABLE `eropuit_uitschrijfredenen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `eropuit_vrijwilligers`
--
ALTER TABLE `eropuit_vrijwilligers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_3D566A3E76B43BDC` (`vrijwilliger_id`),
  ADD KEY `IDX_3D566A3E5D010236` (`uitschrijfreden_id`);

--
-- Indexen voor tabel `ext_log_entries`
--
ALTER TABLE `ext_log_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_class_lookup_idx` (`object_class`),
  ADD KEY `log_date_lookup_idx` (`logged_at`),
  ADD KEY `log_user_lookup_idx` (`username`),
  ADD KEY `log_version_lookup_idx` (`object_id`,`object_class`,`version`);

--
-- Indexen voor tabel `ga_activiteitannuleringsredenen`
--
ALTER TABLE `ga_activiteitannuleringsredenen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `ga_activiteiten`
--
ALTER TABLE `ga_activiteiten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_56418A359EB44EC5` (`groep_id`),
  ADD KEY `IDX_56418A35209ADBBB` (`annuleringsreden_id`);

--
-- Indexen voor tabel `ga_deelnames`
--
ALTER TABLE `ga_deelnames`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_activiteit_dossier_idx` (`activiteit_id`,`dossier_id`),
  ADD KEY `IDX_F577BB9C5A8A0A1` (`activiteit_id`),
  ADD KEY `IDX_F577BB9C611C0C56` (`dossier_id`);

--
-- Indexen voor tabel `ga_documenten`
--
ALTER TABLE `ga_documenten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_409E56123D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `ga_dossierafsluitredenen`
--
ALTER TABLE `ga_dossierafsluitredenen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `ga_dossiers`
--
ALTER TABLE `ga_dossiers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_470A2E33CA12F7AE` (`afsluitreden_id`),
  ADD KEY `IDX_470A2E333C427B2F` (`klant_id`),
  ADD KEY `IDX_470A2E3376B43BDC` (`vrijwilliger_id`),
  ADD KEY `IDX_470A2E333D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `ga_dossier_document`
--
ALTER TABLE `ga_dossier_document`
  ADD PRIMARY KEY (`dossier_id`,`document_id`),
  ADD UNIQUE KEY `UNIQ_63244AA1C33F7837` (`document_id`),
  ADD KEY `IDX_63244AA1611C0C56` (`dossier_id`);

--
-- Indexen voor tabel `ga_gaklantintake_zrm`
--
ALTER TABLE `ga_gaklantintake_zrm`
  ADD PRIMARY KEY (`gaklantintake_id`,`zrm_id`),
  ADD UNIQUE KEY `UNIQ_D6805671C8250F57` (`zrm_id`),
  ADD KEY `IDX_D68056715FA93F88` (`gaklantintake_id`);

--
-- Indexen voor tabel `ga_groepen`
--
ALTER TABLE `ga_groepen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_EEBF811346708ED5` (`werkgebied`);

--
-- Indexen voor tabel `ga_intakes`
--
ALTER TABLE `ga_intakes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_21B32922611C0C56` (`dossier_id`),
  ADD KEY `IDX_21B329223D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `ga_intake_zrm`
--
ALTER TABLE `ga_intake_zrm`
  ADD PRIMARY KEY (`intake_id`,`zrm_id`),
  ADD UNIQUE KEY `UNIQ_4F2ECDF1733DE450` (`intake_id`),
  ADD UNIQUE KEY `UNIQ_4F2ECDF1C8250F57` (`zrm_id`);

--
-- Indexen voor tabel `ga_lidmaatschappen`
--
ALTER TABLE `ga_lidmaatschappen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D9F3C3F59EB44EC5` (`groep_id`),
  ADD KEY `IDX_D9F3C3F5611C0C56` (`dossier_id`),
  ADD KEY `IDX_D9F3C3F5248D162C` (`groepsactiviteiten_reden_id`);

--
-- Indexen voor tabel `ga_memos`
--
ALTER TABLE `ga_memos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_692930E83D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `ga_redenen`
--
ALTER TABLE `ga_redenen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `ga_verslagen`
--
ALTER TABLE `ga_verslagen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_33E9790A611C0C56` (`dossier_id`),
  ADD KEY `IDX_33E9790A3D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `gd27`
--
ALTER TABLE `gd27`
  ADD PRIMARY KEY (`idd`);

--
-- Indexen voor tabel `geslachten`
--
ALTER TABLE `geslachten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexen voor tabel `ggw_gebieden`
--
ALTER TABLE `ggw_gebieden`
  ADD PRIMARY KEY (`naam`);

--
-- Indexen voor tabel `groepsactiviteiten`
--
ALTER TABLE `groepsactiviteiten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9DB0AE2768AE5B4C` (`groepsactiviteiten_groep_id`);

--
-- Indexen voor tabel `groepsactiviteiten_afsluitingen`
--
ALTER TABLE `groepsactiviteiten_afsluitingen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `groepsactiviteiten_groepen`
--
ALTER TABLE `groepsactiviteiten_groepen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `groepsactiviteiten_groepen_klanten`
--
ALTER TABLE `groepsactiviteiten_groepen_klanten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E248EC8D68AE5B4C` (`groepsactiviteiten_groep_id`),
  ADD KEY `IDX_E248EC8D3C427B2F` (`klant_id`),
  ADD KEY `IDX_E248EC8D248D162C` (`groepsactiviteiten_reden_id`);

--
-- Indexen voor tabel `groepsactiviteiten_groepen_vrijwilligers`
--
ALTER TABLE `groepsactiviteiten_groepen_vrijwilligers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_81655E2368AE5B4C` (`groepsactiviteiten_groep_id`),
  ADD KEY `IDX_81655E2376B43BDC` (`vrijwilliger_id`),
  ADD KEY `IDX_81655E23248D162C` (`groepsactiviteiten_reden_id`);

--
-- Indexen voor tabel `groepsactiviteiten_intakes`
--
ALTER TABLE `groepsactiviteiten_intakes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `groepsactiviteiten_intakes_foreign_key_model_idx` (`foreign_key`,`model`),
  ADD KEY `IDX_843277B64BCC47A` (`groepsactiviteiten_afsluiting_id`),
  ADD KEY `IDX_843277B3D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `groepsactiviteiten_klanten`
--
ALTER TABLE `groepsactiviteiten_klanten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_560B17693C427B2F` (`klant_id`),
  ADD KEY `IDX_560B17695BF7B988` (`groepsactiviteit_id`);

--
-- Indexen voor tabel `groepsactiviteiten_redenen`
--
ALTER TABLE `groepsactiviteiten_redenen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `groepsactiviteiten_verslagen`
--
ALTER TABLE `groepsactiviteiten_verslagen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foreign_key_model_idx` (`foreign_key`,`model`),
  ADD KEY `IDX_BF289BE03D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `groepsactiviteiten_vrijwilligers`
--
ALTER TABLE `groepsactiviteiten_vrijwilligers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_78A2C7E476B43BDC` (`vrijwilliger_id`);

--
-- Indexen voor tabel `hi5_answers`
--
ALTER TABLE `hi5_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `hi5_answer_types`
--
ALTER TABLE `hi5_answer_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `hi5_evaluaties`
--
ALTER TABLE `hi5_evaluaties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_hi5_evaluaties_klant_id` (`klant_id`);

--
-- Indexen voor tabel `hi5_evaluaties_hi5_evaluatie_questions`
--
ALTER TABLE `hi5_evaluaties_hi5_evaluatie_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `hi5_evaluatie_paragraphs`
--
ALTER TABLE `hi5_evaluatie_paragraphs`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `hi5_evaluatie_questions`
--
ALTER TABLE `hi5_evaluatie_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `hi5_intakes`
--
ALTER TABLE `hi5_intakes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_hi5_intakes_klant_id` (`klant_id`);

--
-- Indexen voor tabel `hi5_intakes_answers`
--
ALTER TABLE `hi5_intakes_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `hi5_intakes_inkomens`
--
ALTER TABLE `hi5_intakes_inkomens`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `hi5_intakes_instanties`
--
ALTER TABLE `hi5_intakes_instanties`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `hi5_intakes_primaireproblematieksgebruikswijzen`
--
ALTER TABLE `hi5_intakes_primaireproblematieksgebruikswijzen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `hi5_intakes_verslavingen`
--
ALTER TABLE `hi5_intakes_verslavingen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `hi5_intakes_verslavingsgebruikswijzen`
--
ALTER TABLE `hi5_intakes_verslavingsgebruikswijzen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `hi5_questions`
--
ALTER TABLE `hi5_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `hoofdaannemers`
--
ALTER TABLE `hoofdaannemers`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `hs_activiteiten`
--
ALTER TABLE `hs_activiteiten`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `hs_arbeiders`
--
ALTER TABLE `hs_arbeiders`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `hs_betalingen`
--
ALTER TABLE `hs_betalingen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_EADEA9FFC35D3E` (`factuur_id`);

--
-- Indexen voor tabel `hs_declaraties`
--
ALTER TABLE `hs_declaraties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_AF23D292BA5374AF` (`klus_id`),
  ADD KEY `IDX_AF23D292C35D3E` (`factuur_id`),
  ADD KEY `IDX_AF23D2921EE52B26` (`declaratieCategorie_id`),
  ADD KEY `IDX_AF23D2923D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `hs_declaratie_categorieen`
--
ALTER TABLE `hs_declaratie_categorieen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `hs_declaratie_document`
--
ALTER TABLE `hs_declaratie_document`
  ADD PRIMARY KEY (`declaratie_id`,`document_id`),
  ADD UNIQUE KEY `UNIQ_9E1A25FEC33F7837` (`document_id`),
  ADD KEY `IDX_9E1A25FE6AE7FC36` (`declaratie_id`);

--
-- Indexen voor tabel `hs_dienstverleners`
--
ALTER TABLE `hs_dienstverleners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_4949548D3C427B2F` (`klant_id`);

--
-- Indexen voor tabel `hs_dienstverlener_document`
--
ALTER TABLE `hs_dienstverlener_document`
  ADD PRIMARY KEY (`dienstverlener_id`,`document_id`),
  ADD UNIQUE KEY `UNIQ_DEBCC7F2C33F7837` (`document_id`),
  ADD KEY `IDX_DEBCC7F2A1690166` (`dienstverlener_id`);

--
-- Indexen voor tabel `hs_dienstverlener_memo`
--
ALTER TABLE `hs_dienstverlener_memo`
  ADD PRIMARY KEY (`dienstverlener_id`,`memo_id`),
  ADD UNIQUE KEY `UNIQ_F546B7DDB4D32439` (`memo_id`),
  ADD KEY `IDX_F546B7DDA1690166` (`dienstverlener_id`);

--
-- Indexen voor tabel `hs_documenten`
--
ALTER TABLE `hs_documenten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_87CDF0443D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `hs_facturen`
--
ALTER TABLE `hs_facturen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_31669C163C427B2F` (`klant_id`);

--
-- Indexen voor tabel `hs_factuur_klus`
--
ALTER TABLE `hs_factuur_klus`
  ADD PRIMARY KEY (`factuur_id`,`klus_id`),
  ADD KEY `IDX_B3DD2838C35D3E` (`factuur_id`),
  ADD KEY `IDX_B3DD2838BA5374AF` (`klus_id`);

--
-- Indexen voor tabel `hs_herinneringen`
--
ALTER TABLE `hs_herinneringen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D18DFCA3C35D3E` (`factuur_id`);

--
-- Indexen voor tabel `hs_klanten`
--
ALTER TABLE `hs_klanten`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_erp_id_idx` (`erp_id`),
  ADD KEY `IDX_CC6284A1C729A47` (`geslacht_id`),
  ADD KEY `IDX_CC6284A3D707F64` (`medewerker_id`),
  ADD KEY `IDX_CC6284A46708ED5` (`werkgebied`),
  ADD KEY `IDX_CC6284AFB02B9C2` (`postcodegebied`);

--
-- Indexen voor tabel `hs_klant_document`
--
ALTER TABLE `hs_klant_document`
  ADD PRIMARY KEY (`klant_id`,`document_id`),
  ADD UNIQUE KEY `UNIQ_795E7D0BC33F7837` (`document_id`),
  ADD KEY `IDX_795E7D0B3C427B2F` (`klant_id`);

--
-- Indexen voor tabel `hs_klant_memo`
--
ALTER TABLE `hs_klant_memo`
  ADD PRIMARY KEY (`klant_id`,`memo_id`),
  ADD UNIQUE KEY `UNIQ_17707706B4D32439` (`memo_id`),
  ADD KEY `IDX_177077063C427B2F` (`klant_id`);

--
-- Indexen voor tabel `hs_klussen`
--
ALTER TABLE `hs_klussen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_3E9A80CF3C427B2F` (`klant_id`),
  ADD KEY `IDX_3E9A80CF3D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `hs_klus_activiteit`
--
ALTER TABLE `hs_klus_activiteit`
  ADD PRIMARY KEY (`klus_id`,`activiteit_id`),
  ADD KEY `IDX_AE073F88BA5374AF` (`klus_id`),
  ADD KEY `IDX_AE073F885A8A0A1` (`activiteit_id`);

--
-- Indexen voor tabel `hs_klus_dienstverlener`
--
ALTER TABLE `hs_klus_dienstverlener`
  ADD PRIMARY KEY (`klus_id`,`dienstverlener_id`),
  ADD KEY `IDX_70F96EFBBA5374AF` (`klus_id`),
  ADD KEY `IDX_70F96EFBA1690166` (`dienstverlener_id`);

--
-- Indexen voor tabel `hs_klus_document`
--
ALTER TABLE `hs_klus_document`
  ADD PRIMARY KEY (`klus_id`,`document_id`),
  ADD UNIQUE KEY `UNIQ_869EC9C5C33F7837` (`document_id`),
  ADD KEY `IDX_869EC9C5BA5374AF` (`klus_id`);

--
-- Indexen voor tabel `hs_klus_memo`
--
ALTER TABLE `hs_klus_memo`
  ADD PRIMARY KEY (`klus_id`,`memo_id`),
  ADD UNIQUE KEY `UNIQ_208D08ECB4D32439` (`memo_id`),
  ADD KEY `IDX_208D08ECBA5374AF` (`klus_id`);

--
-- Indexen voor tabel `hs_klus_vrijwilliger`
--
ALTER TABLE `hs_klus_vrijwilliger`
  ADD PRIMARY KEY (`klus_id`,`vrijwilliger_id`),
  ADD KEY `IDX_6E1EDAA1BA5374AF` (`klus_id`),
  ADD KEY `IDX_6E1EDAA176B43BDC` (`vrijwilliger_id`);

--
-- Indexen voor tabel `hs_memos`
--
ALTER TABLE `hs_memos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_4048AFA33D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `hs_registraties`
--
ALTER TABLE `hs_registraties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_62041BC2BA5374AF` (`klus_id`),
  ADD KEY `IDX_62041BC2C35D3E` (`factuur_id`),
  ADD KEY `IDX_62041BC26650623E` (`arbeider_id`),
  ADD KEY `IDX_62041BC25A8A0A1` (`activiteit_id`),
  ADD KEY `IDX_62041BC23D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `hs_vrijwilligers`
--
ALTER TABLE `hs_vrijwilligers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_3FE7029676B43BDC` (`vrijwilliger_id`);

--
-- Indexen voor tabel `hs_vrijwilliger_document`
--
ALTER TABLE `hs_vrijwilliger_document`
  ADD PRIMARY KEY (`vrijwilliger_id`,`document_id`),
  ADD UNIQUE KEY `UNIQ_EAEC53F3C33F7837` (`document_id`),
  ADD KEY `IDX_EAEC53F376B43BDC` (`vrijwilliger_id`);

--
-- Indexen voor tabel `hs_vrijwilliger_memo`
--
ALTER TABLE `hs_vrijwilliger_memo`
  ADD PRIMARY KEY (`vrijwilliger_id`,`memo_id`),
  ADD UNIQUE KEY `UNIQ_938D702FB4D32439` (`memo_id`),
  ADD KEY `IDX_938D702F76B43BDC` (`vrijwilliger_id`);

--
-- Indexen voor tabel `i18n`
--
ALTER TABLE `i18n`
  ADD PRIMARY KEY (`id`),
  ADD KEY `locale` (`locale`),
  ADD KEY `model` (`model`),
  ADD KEY `row_id` (`foreign_key`),
  ADD KEY `field` (`field`);

--
-- Indexen voor tabel `infobaliedoelgroepen`
--
ALTER TABLE `infobaliedoelgroepen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `inkomens`
--
ALTER TABLE `inkomens`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `inkomens_awbz_intakes`
--
ALTER TABLE `inkomens_awbz_intakes`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `inkomens_intakes`
--
ALTER TABLE `inkomens_intakes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_66CE79C0733DE450` (`intake_id`),
  ADD KEY `IDX_66CE79C0DE7E5B0` (`inkomen_id`);

--
-- Indexen voor tabel `inloop_afsluiting_redenen`
--
ALTER TABLE `inloop_afsluiting_redenen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `inloop_afsluitredenen_vrijwilligers`
--
ALTER TABLE `inloop_afsluitredenen_vrijwilligers`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `inloop_binnen_via`
--
ALTER TABLE `inloop_binnen_via`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `inloop_deelnames`
--
ALTER TABLE `inloop_deelnames`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CFB194F341D2A6EF` (`inloopTraining_id`),
  ADD KEY `IDX_CFB194F3B280D297` (`inloop_vrijwilliger_id`);

--
-- Indexen voor tabel `inloop_documenten`
--
ALTER TABLE `inloop_documenten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9DA9ECF43D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `inloop_dossier_statussen`
--
ALTER TABLE `inloop_dossier_statussen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_12D2B5703C427B2F` (`klant_id`),
  ADD KEY `IDX_12D2B5703D707F64` (`medewerker_id`),
  ADD KEY `IDX_12D2B570D29703A5` (`reden_id`),
  ADD KEY `IDX_12D2B5701994904A` (`land_id`);

--
-- Indexen voor tabel `inloop_incidenten`
--
ALTER TABLE `inloop_incidenten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F85DD4754947630C` (`locatie_id`),
  ADD KEY `IDX_F85DD4753C427B2F` (`klant_id`);

--
-- Indexen voor tabel `inloop_intake_zrm`
--
ALTER TABLE `inloop_intake_zrm`
  ADD PRIMARY KEY (`intake_id`,`zrm_id`),
  ADD UNIQUE KEY `UNIQ_92197717C8250F57` (`zrm_id`),
  ADD KEY `IDX_92197717733DE450` (`intake_id`);

--
-- Indexen voor tabel `inloop_memos`
--
ALTER TABLE `inloop_memos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9ACAE40D3D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `inloop_toegang`
--
ALTER TABLE `inloop_toegang`
  ADD PRIMARY KEY (`klant_id`,`locatie_id`),
  ADD KEY `IDX_C2038C803C427B2F` (`klant_id`),
  ADD KEY `IDX_C2038C804947630C` (`locatie_id`);

--
-- Indexen voor tabel `inloop_training`
--
ALTER TABLE `inloop_training`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `inloop_vrijwilligers`
--
ALTER TABLE `inloop_vrijwilligers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_5611048076B43BDC` (`vrijwilliger_id`),
  ADD KEY `IDX_561104803D707F64` (`medewerker_id`),
  ADD KEY `IDX_561104804C676E6B` (`binnen_via_id`),
  ADD KEY `IDX_56110480CA12F7AE` (`afsluitreden_id`),
  ADD KEY `IDX_56110480EA9C84FE` (`medewerkerLocatie_id`),
  ADD KEY `IDX_561104804947630C` (`locatie_id`);

--
-- Indexen voor tabel `inloop_vrijwilliger_document`
--
ALTER TABLE `inloop_vrijwilliger_document`
  ADD PRIMARY KEY (`vrijwilliger_id`,`document_id`),
  ADD UNIQUE KEY `UNIQ_6401B15DC33F7837` (`document_id`),
  ADD KEY `IDX_6401B15D76B43BDC` (`vrijwilliger_id`);

--
-- Indexen voor tabel `inloop_vrijwilliger_locatie`
--
ALTER TABLE `inloop_vrijwilliger_locatie`
  ADD PRIMARY KEY (`vrijwilliger_id`,`locatie_id`),
  ADD KEY `IDX_A1776D9F76B43BDC` (`vrijwilliger_id`),
  ADD KEY `IDX_A1776D9F4947630C` (`locatie_id`);

--
-- Indexen voor tabel `inloop_vrijwilliger_memo`
--
ALTER TABLE `inloop_vrijwilliger_memo`
  ADD PRIMARY KEY (`vrijwilliger_id`,`memo_id`),
  ADD UNIQUE KEY `UNIQ_94FA9B19B4D32439` (`memo_id`),
  ADD KEY `IDX_94FA9B1976B43BDC` (`vrijwilliger_id`);

--
-- Indexen voor tabel `instanties`
--
ALTER TABLE `instanties`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `instanties_awbz_intakes`
--
ALTER TABLE `instanties_awbz_intakes`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `instanties_intakes`
--
ALTER TABLE `instanties_intakes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9D745955733DE450` (`intake_id`),
  ADD KEY `IDX_9D7459552A1C57EF` (`instantie_id`);

--
-- Indexen voor tabel `intakes`
--
ALTER TABLE `intakes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_AB70F5AEC8250F57` (`zrm_id`),
  ADD KEY `IDX_AB70F5AE3D707F64` (`medewerker_id`),
  ADD KEY `IDX_AB70F5AE3C427B2F` (`klant_id`),
  ADD KEY `IDX_AB70F5AE48D0634A` (`verblijfstatus_id`),
  ADD KEY `IDX_AB70F5AECD268935` (`locatie2_id`),
  ADD KEY `IDX_AB70F5AEDF9326DB` (`locatie1_id`),
  ADD KEY `IDX_AB70F5AE759AEE50` (`locatie3_id`),
  ADD KEY `IDX_AB70F5AEEB38826A` (`legitimatie_id`),
  ADD KEY `IDX_AB70F5AED106567A` (`infobaliedoelgroep_id`),
  ADD KEY `IDX_AB70F5AE6DF0864` (`primaireproblematiek_id`),
  ADD KEY `IDX_AB70F5AE694ADD79` (`primaireproblematieksfrequentie_id`),
  ADD KEY `IDX_AB70F5AEDC542336` (`primaireproblematieksperiode_id`),
  ADD KEY `IDX_AB70F5AE4B616C78` (`verslavingsfrequentie_id`),
  ADD KEY `IDX_AB70F5AE1C06E73B` (`verslavingsperiode_id`),
  ADD KEY `IDX_AB70F5AEC7424D3F` (`woonsituatie_id`);

--
-- Indexen voor tabel `intakes_primaireproblematieksgebruikswijzen`
--
ALTER TABLE `intakes_primaireproblematieksgebruikswijzen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5BE550D2733DE450` (`intake_id`),
  ADD KEY `IDX_5BE550D2DB7A239E` (`primaireproblematieksgebruikswijze_id`);

--
-- Indexen voor tabel `intakes_verslavingen`
--
ALTER TABLE `intakes_verslavingen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_AD93CFF3733DE450` (`intake_id`),
  ADD KEY `IDX_AD93CFF310258C8` (`verslaving_id`);

--
-- Indexen voor tabel `intakes_verslavingsgebruikswijzen`
--
ALTER TABLE `intakes_verslavingsgebruikswijzen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A2AFE8FE733DE450` (`intake_id`),
  ADD KEY `IDX_A2AFE8FE8BDA2F32` (`verslavingsgebruikswijze_id`);

--
-- Indexen voor tabel `inventarisaties`
--
ALTER TABLE `inventarisaties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1343F8F1727ACA70` (`parent_id`);

--
-- Indexen voor tabel `inventarisaties_verslagen`
--
ALTER TABLE `inventarisaties_verslagen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_45A2DE14D949475D` (`verslag_id`),
  ADD KEY `IDX_45A2DE1430AF6145` (`inventarisatie_id`),
  ADD KEY `IDX_45A2DE14D8291178` (`doorverwijzer_id`);

--
-- Indexen voor tabel `iz_afsluitingen`
--
ALTER TABLE `iz_afsluitingen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `iz_deelnemers`
--
ALTER TABLE `iz_deelnemers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_model_foreign_key_idx` (`model`,`foreign_key`),
  ADD KEY `IDX_89B5B51CFBE387F6` (`iz_afsluiting_id`),
  ADD KEY `IDX_89B5B51C782093FC` (`contact_ontstaan`),
  ADD KEY `IDX_89B5B51CF0A6F57E` (`binnengekomen_via`),
  ADD KEY `IDX_89B5B51C7E366551` (`foreign_key`),
  ADD KEY `IDX_89B5B51C3D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `iz_deelnemers_documenten`
--
ALTER TABLE `iz_deelnemers_documenten`
  ADD PRIMARY KEY (`izdeelnemer_id`,`document_id`),
  ADD UNIQUE KEY `UNIQ_66AE504FC33F7837` (`document_id`),
  ADD KEY `IDX_66AE504F55B482C2` (`izdeelnemer_id`);

--
-- Indexen voor tabel `iz_deelnemers_iz_intervisiegroepen`
--
ALTER TABLE `iz_deelnemers_iz_intervisiegroepen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_3A903EEF495B2A54` (`iz_intervisiegroep_id`),
  ADD KEY `IDX_3A903EEFD3124B3F` (`iz_deelnemer_id`);

--
-- Indexen voor tabel `iz_deelnemers_iz_projecten`
--
ALTER TABLE `iz_deelnemers_iz_projecten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_65A512DB56CEA1A9` (`iz_project_id`),
  ADD KEY `IDX_65A512DBD3124B3F` (`iz_deelnemer_id`);

--
-- Indexen voor tabel `iz_documenten`
--
ALTER TABLE `iz_documenten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C7F213503D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `iz_doelgroepen`
--
ALTER TABLE `iz_doelgroepen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_95359FC8FC4DB938` (`naam`);

--
-- Indexen voor tabel `iz_doelstellingen`
--
ALTER TABLE `iz_doelstellingen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_project_jaar_stadsdeel_idx` (`project_id`,`jaar`,`stadsdeel`),
  ADD UNIQUE KEY `unique_project_jaar_categorie_idx` (`project_id`,`jaar`,`categorie`),
  ADD KEY `IDX_D76C6C73166D1F9C` (`project_id`),
  ADD KEY `IDX_D76C6C73A13D3FD8` (`stadsdeel`);

--
-- Indexen voor tabel `iz_eindekoppelingen`
--
ALTER TABLE `iz_eindekoppelingen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `iz_hulpaanbod_hulpvraagsoort`
--
ALTER TABLE `iz_hulpaanbod_hulpvraagsoort`
  ADD PRIMARY KEY (`hulpaanbod_id`,`hulpvraagsoort_id`),
  ADD KEY `IDX_D839A990B42008F3` (`hulpaanbod_id`),
  ADD KEY `IDX_D839A990950213F` (`hulpvraagsoort_id`);

--
-- Indexen voor tabel `iz_hulpvraagsoorten`
--
ALTER TABLE `iz_hulpvraagsoorten`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_86CE34D4FC4DB938` (`naam`),
  ADD KEY `id` (`id`,`naam`);

--
-- Indexen voor tabel `iz_hulpvraag_succesindicator`
--
ALTER TABLE `iz_hulpvraag_succesindicator`
  ADD PRIMARY KEY (`hulpvraag_id`,`succesindicator_id`),
  ADD KEY `IDX_BDDCA8FA8450D8C` (`hulpvraag_id`),
  ADD KEY `IDX_BDDCA8FA7B2005C` (`succesindicator_id`);

--
-- Indexen voor tabel `iz_hulpvraag_succesindicatorfinancieel`
--
ALTER TABLE `iz_hulpvraag_succesindicatorfinancieel`
  ADD PRIMARY KEY (`hulpvraag_id`,`succesindicatorfinancieel_id`),
  ADD KEY `IDX_3A3B526FA8450D8C` (`hulpvraag_id`),
  ADD KEY `IDX_3A3B526F3FEB2492` (`succesindicatorfinancieel_id`);

--
-- Indexen voor tabel `iz_hulpvraag_succesindicatorparticipatie`
--
ALTER TABLE `iz_hulpvraag_succesindicatorparticipatie`
  ADD PRIMARY KEY (`hulpvraag_id`,`succesindicatorparticipatie_id`),
  ADD KEY `IDX_128F9138A8450D8C` (`hulpvraag_id`),
  ADD KEY `IDX_128F913865A9F272` (`succesindicatorparticipatie_id`);

--
-- Indexen voor tabel `iz_hulpvraag_succesindicatorpersoonlijk`
--
ALTER TABLE `iz_hulpvraag_succesindicatorpersoonlijk`
  ADD PRIMARY KEY (`hulpvraag_id`,`succesindicatorpersoonlijk_id`),
  ADD KEY `IDX_BC9D7F44A8450D8C` (`hulpvraag_id`),
  ADD KEY `IDX_BC9D7F44F9892974` (`succesindicatorpersoonlijk_id`);

--
-- Indexen voor tabel `iz_intakes`
--
ALTER TABLE `iz_intakes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_11EFC53DC8250F57` (`zrm_id`),
  ADD KEY `iz_deelnemer_id` (`iz_deelnemer_id`),
  ADD KEY `IDX_11EFC53D3D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `iz_intake_zrm`
--
ALTER TABLE `iz_intake_zrm`
  ADD PRIMARY KEY (`intake_id`,`zrm_id`),
  ADD UNIQUE KEY `UNIQ_C84288B3C8250F57` (`zrm_id`),
  ADD KEY `IDX_C84288B3733DE450` (`intake_id`);

--
-- Indexen voor tabel `iz_intervisiegroepen`
--
ALTER TABLE `iz_intervisiegroepen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_86CA227E3D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `iz_koppelingen`
--
ALTER TABLE `iz_koppelingen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_24E5FDC23D707F64` (`medewerker_id`),
  ADD KEY `IDX_24E5FDC2166D1F9C` (`project_id`),
  ADD KEY `IDX_24E5FDC28B6598D9` (`iz_eindekoppeling_id`),
  ADD KEY `IDX_24E5FDC2D3124B3F` (`iz_deelnemer_id`),
  ADD KEY `IDX_24E5FDC2C9788B0A` (`voorkeurGeslacht_id`),
  ADD KEY `IDX_24E5FDC28B2EFA2C` (`iz_koppeling_id`),
  ADD KEY `IDX_24E5FDC2950213F` (`hulpvraagsoort_id`),
  ADD KEY `IDX_24E5FDC2C217B9F` (`iz_vraagaanbod_id`),
  ADD KEY `discr` (`discr`,`deleted`),
  ADD KEY `medewerker_id` (`medewerker_id`,`discr`,`deleted`),
  ADD KEY `discr_2` (`discr`,`deleted`,`project_id`),
  ADD KEY `discr_3` (`discr`,`deleted`,`hulpvraagsoort_id`) USING BTREE;

--
-- Indexen voor tabel `iz_koppeling_doelgroep`
--
ALTER TABLE `iz_koppeling_doelgroep`
  ADD PRIMARY KEY (`koppeling_id`,`doelgroep_id`),
  ADD KEY `IDX_8E6CE05D5C6E6B2` (`koppeling_id`),
  ADD KEY `IDX_8E6CE05DE5A2DFCE` (`doelgroep_id`);

--
-- Indexen voor tabel `iz_matchingklant_doelgroep`
--
ALTER TABLE `iz_matchingklant_doelgroep`
  ADD PRIMARY KEY (`matchingklant_id`,`doelgroep_id`),
  ADD KEY `IDX_9A957F94CC045EED` (`matchingklant_id`),
  ADD KEY `IDX_9A957F94E5A2DFCE` (`doelgroep_id`);

--
-- Indexen voor tabel `iz_matchingvrijwilliger_doelgroep`
--
ALTER TABLE `iz_matchingvrijwilliger_doelgroep`
  ADD PRIMARY KEY (`matchingvrijwilliger_id`,`doelgroep_id`),
  ADD KEY `IDX_AA83F9B42B829AB5` (`matchingvrijwilliger_id`),
  ADD KEY `IDX_AA83F9B4E5A2DFCE` (`doelgroep_id`);

--
-- Indexen voor tabel `iz_matchingvrijwilliger_hulpvraagsoort`
--
ALTER TABLE `iz_matchingvrijwilliger_hulpvraagsoort`
  ADD PRIMARY KEY (`matchingvrijwilliger_id`,`hulpvraagsoort_id`),
  ADD KEY `IDX_11DF7DC02B829AB5` (`matchingvrijwilliger_id`),
  ADD KEY `IDX_11DF7DC0950213F` (`hulpvraagsoort_id`);

--
-- Indexen voor tabel `iz_matching_klanten`
--
ALTER TABLE `iz_matching_klanten`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_A5321A4355FE26E1` (`iz_klant_id`),
  ADD KEY `IDX_A5321A43950213F` (`hulpvraagsoort_id`);

--
-- Indexen voor tabel `iz_matching_vrijwilligers`
--
ALTER TABLE `iz_matching_vrijwilligers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_1CA45FA7C99F99BF` (`iz_vrijwilliger_id`);

--
-- Indexen voor tabel `iz_ontstaan_contacten`
--
ALTER TABLE `iz_ontstaan_contacten`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `iz_projecten`
--
ALTER TABLE `iz_projecten`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `iz_reserveringen`
--
ALTER TABLE `iz_reserveringen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B9D71E14A8450D8C` (`hulpvraag_id`),
  ADD KEY `IDX_B9D71E14B42008F3` (`hulpaanbod_id`),
  ADD KEY `IDX_B9D71E143D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `iz_succesindicatoren`
--
ALTER TABLE `iz_succesindicatoren`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_178943F7FC4DB938` (`naam`);

--
-- Indexen voor tabel `iz_verslagen`
--
ALTER TABLE `iz_verslagen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_570FE99B3D707F64` (`medewerker_id`),
  ADD KEY `IDX_570FE99BD3124B3F` (`iz_deelnemer_id`),
  ADD KEY `IDX_570FE99B8B2EFA2C` (`iz_koppeling_id`);

--
-- Indexen voor tabel `iz_via_personen`
--
ALTER TABLE `iz_via_personen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `iz_vraagaanboden`
--
ALTER TABLE `iz_vraagaanboden`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `klanten`
--
ALTER TABLE `klanten`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_F538C5BC8B2671BDEB3B4E33` (`huidigeStatus_id`,`deleted`),
  ADD UNIQUE KEY `UNIQ_F538C5BC8B2671BD` (`huidigeStatus_id`,`id`),
  ADD UNIQUE KEY `UNIQ_F538C5BCCC5FC3F9` (`huidigeMwStatus_id`),
  ADD UNIQUE KEY `UNIQ_F538C5BC9393F8FE` (`partner_id`),
  ADD UNIQUE KEY `UNIQ_F538C5BC21E96F13` (`mwBinnenViaOptieKlant_id`),
  ADD KEY `idx_klanten_geboortedatum` (`geboortedatum`),
  ADD KEY `idx_klanten_first_intake_date` (`first_intake_date`),
  ADD KEY `idx_klanten_werkgebied` (`werkgebied`),
  ADD KEY `IDX_F538C5BC1C729A47` (`geslacht_id`),
  ADD KEY `IDX_F538C5BCCECBFEB7` (`nationaliteit_id`),
  ADD KEY `IDX_F538C5BC3D707F64` (`medewerker_id`),
  ADD KEY `IDX_F538C5BC1994904A` (`land_id`),
  ADD KEY `idx_klanten_postcodegebied` (`postcodegebied`),
  ADD KEY `IDX_F538C5BCC1BEA629` (`merged_id`),
  ADD KEY `IDX_F538C5BC1D103C3F` (`laste_intake_id`),
  ADD KEY `IDX_F538C5BC8B2671BD` (`huidigeStatus_id`),
  ADD KEY `IDX_F538C5BC8F30741E` (`first_intake_id`),
  ADD KEY `corona_besmet_idx` (`corona_besmet_vanaf`),
  ADD KEY `FK_F538C5BC815E1ED` (`laatste_registratie_id`),
  ADD KEY `IDX_F538C5BCEB8E119C` (`maatschappelijkWerker_id`),
  ADD KEY `achternaam` (`achternaam`,`id`,`geslacht_id`,`laste_intake_id`,`huidigeMwStatus_id`) USING BTREE;

--
-- Indexen voor tabel `klantinventarisaties`
--
ALTER TABLE `klantinventarisaties`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `klant_taal`
--
ALTER TABLE `klant_taal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_84884583C427B2FA41FDDD` (`klant_id`,`taal_id`),
  ADD KEY `IDX_84884583C427B2F` (`klant_id`),
  ADD KEY `IDX_8488458A41FDDD` (`taal_id`);

--
-- Indexen voor tabel `landen`
--
ALTER TABLE `landen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `legitimaties`
--
ALTER TABLE `legitimaties`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `locaties`
--
ALTER TABLE `locaties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`,`naam`,`datum_van`,`datum_tot`),
  ADD KEY `id_2` (`id`,`datum_van`),
  ADD KEY `id_3` (`id`,`datum_van`,`datum_tot`),
  ADD KEY `datum_tot` (`id`,`datum_tot`) USING BTREE;

--
-- Indexen voor tabel `locatie_tijden`
--
ALTER TABLE `locatie_tijden`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_logs_model_foreign_key_created` (`model`,`foreign_key`,`created`),
  ADD KEY `idx_logs_medewerker_id` (`medewerker_id`),
  ADD KEY `idx_logs_model_foreign_key` (`model`,`foreign_key`);

--
-- Indexen voor tabel `logs_2014`
--
ALTER TABLE `logs_2014`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_logs_model_foreign_key_created` (`model`,`foreign_key`,`created`),
  ADD KEY `idx_logs_medewerker_id` (`medewerker_id`),
  ADD KEY `idx_logs_model_foreign_key` (`model`,`foreign_key`);

--
-- Indexen voor tabel `logs_2015`
--
ALTER TABLE `logs_2015`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_logs_model_foreign_key_created` (`model`,`foreign_key`,`created`),
  ADD KEY `idx_logs_medewerker_id` (`medewerker_id`),
  ADD KEY `idx_logs_model_foreign_key` (`model`,`foreign_key`);

--
-- Indexen voor tabel `logs_2016`
--
ALTER TABLE `logs_2016`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_logs_model_foreign_key_created` (`model`,`foreign_key`,`created`),
  ADD KEY `idx_logs_medewerker_id` (`medewerker_id`),
  ADD KEY `idx_logs_model_foreign_key` (`model`,`foreign_key`);

--
-- Indexen voor tabel `logs_2017`
--
ALTER TABLE `logs_2017`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_logs_model_foreign_key_created` (`model`,`foreign_key`,`created`),
  ADD KEY `idx_logs_medewerker_id` (`medewerker_id`),
  ADD KEY `idx_logs_model_foreign_key` (`model`,`foreign_key`);

--
-- Indexen voor tabel `medewerkers`
--
ALTER TABLE `medewerkers`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexen voor tabel `mw_afsluiting_redenen`
--
ALTER TABLE `mw_afsluiting_redenen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `mw_afsluiting_resultaat`
--
ALTER TABLE `mw_afsluiting_resultaat`
  ADD PRIMARY KEY (`afsluiting_id`,`resultaat_id`),
  ADD KEY `IDX_EBA6C1A2ECDAD1A9` (`afsluiting_id`),
  ADD KEY `IDX_EBA6C1A2B0A9B358` (`resultaat_id`);

--
-- Indexen voor tabel `mw_afsluitredenen_vrijwilligers`
--
ALTER TABLE `mw_afsluitredenen_vrijwilligers`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `mw_binnen_via`
--
ALTER TABLE `mw_binnen_via`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `mw_deelnames`
--
ALTER TABLE `mw_deelnames`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_59035D7541D2A6EF` (`mwTraining_id`),
  ADD KEY `IDX_59035D75B280D297` (`inloop_vrijwilliger_id`);

--
-- Indexen voor tabel `mw_documenten`
--
ALTER TABLE `mw_documenten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_99E478283C427B2F` (`klant_id`),
  ADD KEY `IDX_99E478283D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `mw_dossier_statussen`
--
ALTER TABLE `mw_dossier_statussen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D74783BB3C427B2F` (`klant_id`),
  ADD KEY `IDX_D74783BB3D707F64` (`medewerker_id`),
  ADD KEY `IDX_D74783BBD29703A5` (`reden_id`),
  ADD KEY `IDX_D74783BB1994904A` (`land_id`),
  ADD KEY `class` (`class`,`id`,`klant_id`),
  ADD KEY `IDX_D74783BB4947630C` (`locatie_id`),
  ADD KEY `IDX_D74783BB305E4E53` (`binnenViaOptieKlant_id`);

--
-- Indexen voor tabel `mw_resultaten`
--
ALTER TABLE `mw_resultaten`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `mw_training`
--
ALTER TABLE `mw_training`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `mw_vrijwilligers`
--
ALTER TABLE `mw_vrijwilligers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_CFC2BAE376B43BDC` (`vrijwilliger_id`),
  ADD KEY `IDX_CFC2BAE34C676E6B` (`binnen_via_id`),
  ADD KEY `IDX_CFC2BAE3CA12F7AE` (`afsluitreden_id`),
  ADD KEY `IDX_CFC2BAE33D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `mw_vrijwilliger_document`
--
ALTER TABLE `mw_vrijwilliger_document`
  ADD PRIMARY KEY (`vrijwilliger_id`,`document_id`),
  ADD UNIQUE KEY `UNIQ_2930918C33F7837` (`document_id`),
  ADD KEY `IDX_293091876B43BDC` (`vrijwilliger_id`);

--
-- Indexen voor tabel `mw_vrijwilliger_locatie`
--
ALTER TABLE `mw_vrijwilliger_locatie`
  ADD PRIMARY KEY (`vrijwilliger_id`,`locatie_id`),
  ADD KEY `IDX_35F4E24576B43BDC` (`vrijwilliger_id`),
  ADD KEY `IDX_35F4E2454947630C` (`locatie_id`);

--
-- Indexen voor tabel `mw_vrijwilliger_memo`
--
ALTER TABLE `mw_vrijwilliger_memo`
  ADD PRIMARY KEY (`vrijwilliger_id`,`memo_id`),
  ADD UNIQUE KEY `UNIQ_516FADD2B4D32439` (`memo_id`),
  ADD KEY `IDX_516FADD276B43BDC` (`vrijwilliger_id`);

--
-- Indexen voor tabel `nationaliteiten`
--
ALTER TABLE `nationaliteiten`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `notities`
--
ALTER TABLE `notities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notities_klant_id` (`klant_id`);

--
-- Indexen voor tabel `oekklant_oekdossierstatus`
--
ALTER TABLE `oekklant_oekdossierstatus`
  ADD PRIMARY KEY (`oekklant_id`,`oekdossierstatus_id`),
  ADD KEY `IDX_1EF9C0A61833A719` (`oekklant_id`),
  ADD KEY `IDX_1EF9C0A6B689C3C1` (`oekdossierstatus_id`);

--
-- Indexen voor tabel `oekraine_afsluiting_redenen`
--
ALTER TABLE `oekraine_afsluiting_redenen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `oekraine_afsluitredenen_vrijwilligers`
--
ALTER TABLE `oekraine_afsluitredenen_vrijwilligers`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `oekraine_bezoekers`
--
ALTER TABLE `oekraine_bezoekers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_7027BCA15C217849` (`appKlant_id`),
  ADD UNIQUE KEY `UNIQ_7027BCA1EF862509` (`dossierStatus_id`),
  ADD UNIQUE KEY `UNIQ_7027BCA1733DE450` (`intake_id`);

--
-- Indexen voor tabel `oekraine_bezoeker_document`
--
ALTER TABLE `oekraine_bezoeker_document`
  ADD PRIMARY KEY (`bezoeker_id`,`document_id`),
  ADD UNIQUE KEY `UNIQ_DEE5EC46C33F7837` (`document_id`),
  ADD KEY `IDX_DEE5EC468AEEBAAE` (`bezoeker_id`);

--
-- Indexen voor tabel `oekraine_documenten`
--
ALTER TABLE `oekraine_documenten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7DB476213D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `oekraine_dossier_statussen`
--
ALTER TABLE `oekraine_dossier_statussen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_19DBBEBC8AEEBAAE` (`bezoeker_id`),
  ADD KEY `IDX_19DBBEBC3D707F64` (`medewerker_id`),
  ADD KEY `IDX_19DBBEBCD29703A5` (`reden_id`),
  ADD KEY `IDX_19DBBEBC1994904A` (`land_id`);

--
-- Indexen voor tabel `oekraine_incidenten`
--
ALTER TABLE `oekraine_incidenten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_18404EA04947630C` (`locatie_id`),
  ADD KEY `IDX_18404EA08AEEBAAE` (`bezoeker_id`);

--
-- Indexen voor tabel `oekraine_inkomens`
--
ALTER TABLE `oekraine_inkomens`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `oekraine_inkomens_intakes`
--
ALTER TABLE `oekraine_inkomens_intakes`
  ADD PRIMARY KEY (`intake_id`,`inkomen_id`),
  ADD KEY `IDX_55D038FE733DE450` (`intake_id`),
  ADD KEY `IDX_55D038FEDE7E5B0` (`inkomen_id`);

--
-- Indexen voor tabel `oekraine_intakes`
--
ALTER TABLE `oekraine_intakes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C84A94C38AEEBAAE` (`bezoeker_id`),
  ADD KEY `IDX_C84A94C33D707F64` (`medewerker_id`),
  ADD KEY `IDX_C84A94C385332A14` (`woonlocatie_id`),
  ADD KEY `IDX_C84A94C348D0634A` (`verblijfstatus_id`),
  ADD KEY `IDX_C84A94C3EB38826A` (`legitimatie_id`),
  ADD KEY `IDX_C84A94C355E45319` (`intakelocatie_id`);

--
-- Indexen voor tabel `oekraine_intakes_instanties`
--
ALTER TABLE `oekraine_intakes_instanties`
  ADD PRIMARY KEY (`intake_id`,`instantie_id`),
  ADD KEY `IDX_86E955E3733DE450` (`intake_id`),
  ADD KEY `IDX_86E955E32A1C57EF` (`instantie_id`);

--
-- Indexen voor tabel `oekraine_locaties`
--
ALTER TABLE `oekraine_locaties`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `oekraine_registraties`
--
ALTER TABLE `oekraine_registraties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7E55584C4947630C` (`locatie_id`),
  ADD KEY `IDX_7E55584C4947630C4C74DE4C5E4E404` (`locatie_id`,`closed`,`binnen_date`),
  ADD KEY `IDX_7E55584C8AEEBAAE` (`bezoeker_id`);

--
-- Indexen voor tabel `oekraine_registraties_recent`
--
ALTER TABLE `oekraine_registraties_recent`
  ADD PRIMARY KEY (`registratie_id`),
  ADD KEY `IDX_8C35B27E4947630C` (`locatie_id`),
  ADD KEY `IDX_8C35B27E8AEEBAAE` (`bezoeker_id`);

--
-- Indexen voor tabel `oekraine_toegang`
--
ALTER TABLE `oekraine_toegang`
  ADD PRIMARY KEY (`bezoeker_id`,`locatie_id`),
  ADD KEY `IDX_894DD3E68AEEBAAE` (`bezoeker_id`),
  ADD KEY `IDX_894DD3E64947630C` (`locatie_id`);

--
-- Indexen voor tabel `oekraine_verslagen`
--
ALTER TABLE `oekraine_verslagen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C15C9D6F8AEEBAAE` (`bezoeker_id`),
  ADD KEY `IDX_C15C9D6F3D707F64` (`medewerker_id`),
  ADD KEY `IDX_C15C9D6FD3899023` (`contactsoort_id`),
  ADD KEY `idx_datum` (`datum`),
  ADD KEY `idx_locatie_id` (`locatie_id`);

--
-- Indexen voor tabel `oek_deelnames`
--
ALTER TABLE `oek_deelnames`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_A6C1F2014DF034FD` (`oekDeelnameStatus_id`),
  ADD KEY `IDX_A6C1F201120845B9` (`oekTraining_id`),
  ADD KEY `IDX_A6C1F201E145C54F` (`oekKlant_id`);

--
-- Indexen voor tabel `oek_deelname_statussen`
--
ALTER TABLE `oek_deelname_statussen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_4CBB9BCD6D7A74BD` (`oekDeelname_id`);

--
-- Indexen voor tabel `oek_documenten`
--
ALTER TABLE `oek_documenten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CE730FA23D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `oek_dossier_statussen`
--
ALTER TABLE `oek_dossier_statussen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D8FAC765E145C54F` (`oekKlant_id`),
  ADD KEY `IDX_D8FAC765D8B4CBDF` (`verwijzing_id`),
  ADD KEY `IDX_D8FAC7653D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `oek_groepen`
--
ALTER TABLE `oek_groepen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `oek_klanten`
--
ALTER TABLE `oek_klanten`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_A501F8F73C427B2F` (`klant_id`),
  ADD KEY `IDX_A501F8F723473A1F` (`oekDossierStatus_id`),
  ADD KEY `IDX_A501F8F7C45AE93C` (`oekAanmelding_id`),
  ADD KEY `IDX_A501F8F7B99C329A` (`oekAfsluiting_id`),
  ADD KEY `IDX_A501F8F73D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `oek_lidmaatschappen`
--
ALTER TABLE `oek_lidmaatschappen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7B0B7DFF43B3F0A5` (`oekGroep_id`),
  ADD KEY `IDX_7B0B7DFFE145C54F` (`oekKlant_id`);

--
-- Indexen voor tabel `oek_memos`
--
ALTER TABLE `oek_memos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8F8DED693D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `oek_trainingen`
--
ALTER TABLE `oek_trainingen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B0D582D43B3F0A5` (`oekGroep_id`);

--
-- Indexen voor tabel `oek_verwijzingen`
--
ALTER TABLE `oek_verwijzingen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `oek_vrijwilligers`
--
ALTER TABLE `oek_vrijwilligers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_2D75CD3476B43BDC` (`vrijwilliger_id`),
  ADD KEY `IDX_2D75CD343D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `oek_vrijwilliger_document`
--
ALTER TABLE `oek_vrijwilliger_document`
  ADD PRIMARY KEY (`vrijwilliger_id`,`document_id`),
  ADD UNIQUE KEY `UNIQ_725F2FCAC33F7837` (`document_id`),
  ADD KEY `IDX_725F2FCA76B43BDC` (`vrijwilliger_id`);

--
-- Indexen voor tabel `oek_vrijwilliger_memo`
--
ALTER TABLE `oek_vrijwilliger_memo`
  ADD PRIMARY KEY (`vrijwilliger_id`,`memo_id`),
  ADD UNIQUE KEY `UNIQ_5ED2E90CB4D32439` (`memo_id`),
  ADD KEY `IDX_5ED2E90C76B43BDC` (`vrijwilliger_id`);

--
-- Indexen voor tabel `opmerkingen`
--
ALTER TABLE `opmerkingen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_opmerkingen_klant_id` (`klant_id`),
  ADD KEY `IDX_C2C23B29BCF5E72D` (`categorie_id`),
  ADD KEY `IDX_C2C23B293D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `pfo_aard_relaties`
--
ALTER TABLE `pfo_aard_relaties`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `pfo_clienten`
--
ALTER TABLE `pfo_clienten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pfo_clienten_roepnaam` (`roepnaam`),
  ADD KEY `idx_pfo_clienten_achternaam` (`achternaam`),
  ADD KEY `IDX_3C237EDD1C729A47` (`geslacht_id`),
  ADD KEY `IDX_3C237EDDC41BE3` (`aard_relatie`),
  ADD KEY `IDX_3C237EDD27025694` (`groep`),
  ADD KEY `IDX_3C237EDD3D707F64` (`medewerker_id`),
  ADD KEY `IDX_3C237EDD46708ED5` (`werkgebied`),
  ADD KEY `IDX_3C237EDDFB02B9C2` (`postcodegebied`);

--
-- Indexen voor tabel `pfo_clienten_documenten`
--
ALTER TABLE `pfo_clienten_documenten`
  ADD PRIMARY KEY (`client_id`,`document_id`),
  ADD UNIQUE KEY `UNIQ_A14FB5DEC33F7837` (`document_id`),
  ADD KEY `IDX_A14FB5DE19EB6921` (`client_id`);

--
-- Indexen voor tabel `pfo_clienten_supportgroups`
--
ALTER TABLE `pfo_clienten_supportgroups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_39F077D963E315A` (`pfo_client_id`),
  ADD KEY `IDX_39F077D93926A77` (`pfo_supportgroup_client_id`);

--
-- Indexen voor tabel `pfo_clienten_verslagen`
--
ALTER TABLE `pfo_clienten_verslagen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_EC92AD411E813AB1` (`pfo_verslag_id`),
  ADD KEY `IDX_EC92AD4163E315A` (`pfo_client_id`);

--
-- Indexen voor tabel `pfo_documenten`
--
ALTER TABLE `pfo_documenten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_4099D0893D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `pfo_groepen`
--
ALTER TABLE `pfo_groepen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `pfo_verslagen`
--
ALTER TABLE `pfo_verslagen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_346FE20A3D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `postcodegebieden`
--
ALTER TABLE `postcodegebieden`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `postcodes`
--
ALTER TABLE `postcodes`
  ADD PRIMARY KEY (`postcode`),
  ADD KEY `IDX_71DDD65DA13D3FD8` (`stadsdeel`),
  ADD KEY `IDX_71DDD65DFB02B9C2` (`postcodegebied`);

--
-- Indexen voor tabel `queue_tasks`
--
ALTER TABLE `queue_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_queue_tasks_status_modified` (`modified`,`status`);

--
-- Indexen voor tabel `redenen`
--
ALTER TABLE `redenen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `registraties`
--
ALTER TABLE `registraties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_registraties_klant_id_locatie_id` (`klant_id`,`locatie_id`),
  ADD KEY `idx_registraties_locatie_id_closed` (`locatie_id`,`closed`),
  ADD KEY `IDX_FB4123F44947630C4C74DE4C5E4E404` (`locatie_id`,`closed`,`binnen_date`),
  ADD KEY `IDX_FB4123F44947630C` (`locatie_id`),
  ADD KEY `binnen` (`binnen`),
  ADD KEY `klant_locatie_binnen_buiten` (`klant_id`,`locatie_id`,`binnen`,`buiten`),
  ADD KEY `klant_id` (`klant_id`) USING BTREE;

--
-- Indexen voor tabel `registraties_2010`
--
ALTER TABLE `registraties_2010`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_registraties_klant_id_locatie_id` (`klant_id`,`locatie_id`),
  ADD KEY `idx_registraties_locatie_id_closed` (`locatie_id`,`closed`),
  ADD KEY `IDX_FB4123F44947630C4C74DE4C5E4E404` (`locatie_id`,`closed`,`binnen_date`),
  ADD KEY `IDX_FB4123F43C427B2F` (`klant_id`),
  ADD KEY `IDX_FB4123F44947630C` (`locatie_id`);

--
-- Indexen voor tabel `registraties_2011`
--
ALTER TABLE `registraties_2011`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_registraties_klant_id_locatie_id` (`klant_id`,`locatie_id`),
  ADD KEY `idx_registraties_locatie_id_closed` (`locatie_id`,`closed`),
  ADD KEY `IDX_FB4123F44947630C4C74DE4C5E4E404` (`locatie_id`,`closed`,`binnen_date`),
  ADD KEY `IDX_FB4123F43C427B2F` (`klant_id`),
  ADD KEY `IDX_FB4123F44947630C` (`locatie_id`);

--
-- Indexen voor tabel `registraties_2012`
--
ALTER TABLE `registraties_2012`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_registraties_klant_id_locatie_id` (`klant_id`,`locatie_id`),
  ADD KEY `idx_registraties_locatie_id_closed` (`locatie_id`,`closed`),
  ADD KEY `IDX_FB4123F44947630C4C74DE4C5E4E404` (`locatie_id`,`closed`,`binnen_date`),
  ADD KEY `IDX_FB4123F43C427B2F` (`klant_id`),
  ADD KEY `IDX_FB4123F44947630C` (`locatie_id`);

--
-- Indexen voor tabel `registraties_2013`
--
ALTER TABLE `registraties_2013`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_registraties_klant_id_locatie_id` (`klant_id`,`locatie_id`),
  ADD KEY `idx_registraties_locatie_id_closed` (`locatie_id`,`closed`),
  ADD KEY `IDX_FB4123F44947630C4C74DE4C5E4E404` (`locatie_id`,`closed`,`binnen_date`),
  ADD KEY `IDX_FB4123F43C427B2F` (`klant_id`),
  ADD KEY `IDX_FB4123F44947630C` (`locatie_id`);

--
-- Indexen voor tabel `registraties_recent`
--
ALTER TABLE `registraties_recent`
  ADD PRIMARY KEY (`registratie_id`),
  ADD KEY `IDX_B1AD39F04947630C` (`locatie_id`),
  ADD KEY `IDX_B1AD39F03C427B2F` (`klant_id`);

--
-- Indexen voor tabel `schorsingen`
--
ALTER TABLE `schorsingen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9E658EBF3C427B2F` (`klant_id`),
  ADD KEY `IDX_9E658EBF4947630C` (`locatie_id`);

--
-- Indexen voor tabel `schorsingen_redenen`
--
ALTER TABLE `schorsingen_redenen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_BB99D0FFD29703A5` (`reden_id`),
  ADD KEY `IDX_BB99D0FFA52077DE` (`schorsing_id`);

--
-- Indexen voor tabel `schorsing_locatie`
--
ALTER TABLE `schorsing_locatie`
  ADD PRIMARY KEY (`schorsing_id`,`locatie_id`),
  ADD KEY `IDX_52DA6766A52077DE` (`schorsing_id`),
  ADD KEY `IDX_52DA67664947630C` (`locatie_id`);

--
-- Indexen voor tabel `scip_beschikbaarheid`
--
ALTER TABLE `scip_beschikbaarheid`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8897F00FC18FA9D5` (`deelname_id`);

--
-- Indexen voor tabel `scip_deelnames`
--
ALTER TABLE `scip_deelnames`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_FC67EC2F5DFA57A1` (`deelnemer_id`),
  ADD KEY `IDX_FC67EC2F166D1F9C` (`project_id`);

--
-- Indexen voor tabel `scip_deelnemers`
--
ALTER TABLE `scip_deelnemers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_5CB8023F3C427B2F` (`klant_id`),
  ADD UNIQUE KEY `UNIQ_5CB8023F3D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `scip_deelnemer_document`
--
ALTER TABLE `scip_deelnemer_document`
  ADD PRIMARY KEY (`deelnemer_id`,`document_id`),
  ADD UNIQUE KEY `UNIQ_7CA418EBC33F7837` (`document_id`),
  ADD KEY `IDX_7CA418EB5DFA57A1` (`deelnemer_id`);

--
-- Indexen voor tabel `scip_deelnemer_label`
--
ALTER TABLE `scip_deelnemer_label`
  ADD PRIMARY KEY (`deelnemer_id`,`label_id`),
  ADD KEY `IDX_2AF2CF895DFA57A1` (`deelnemer_id`),
  ADD KEY `IDX_2AF2CF8933B92F39` (`label_id`);

--
-- Indexen voor tabel `scip_documenten`
--
ALTER TABLE `scip_documenten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_12FFA4733D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `scip_labels`
--
ALTER TABLE `scip_labels`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `scip_projecten`
--
ALTER TABLE `scip_projecten`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `scip_toegangsrechten`
--
ALTER TABLE `scip_toegangsrechten`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_25CBD12E3D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `scip_toegangsrecht_project`
--
ALTER TABLE `scip_toegangsrecht_project`
  ADD PRIMARY KEY (`toegangsrecht_id`,`project_id`),
  ADD KEY `IDX_DA60099DAC60ED89` (`toegangsrecht_id`),
  ADD KEY `IDX_DA60099D166D1F9C` (`project_id`);

--
-- Indexen voor tabel `scip_verslagen`
--
ALTER TABLE `scip_verslagen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_3AF92EB95DFA57A1` (`deelnemer_id`),
  ADD KEY `IDX_3AF92EB93D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `scip_werkdoelen`
--
ALTER TABLE `scip_werkdoelen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6433FE805DFA57A1` (`deelnemer_id`),
  ADD KEY `IDX_6433FE803D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `stadsdelen`
--
ALTER TABLE `stadsdelen`
  ADD PRIMARY KEY (`postcode`);

--
-- Indexen voor tabel `talen`
--
ALTER TABLE `talen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tmp_open_days`
--
ALTER TABLE `tmp_open_days`
  ADD KEY `idx_tmp_open_days_locatie_id` (`locatie_id`),
  ADD KEY `idx_tmp_open_days_open_day` (`open_day`);

--
-- Indexen voor tabel `tmp_visitors`
--
ALTER TABLE `tmp_visitors`
  ADD KEY `idx_tmp_visitors_land_id` (`land_id`),
  ADD KEY `idx_tmp_visitors_verslaving_id` (`verslaving_id`),
  ADD KEY `idx_tmp_visitors_klant_id` (`klant_id`),
  ADD KEY `idx_tmp_visitors_date` (`date`),
  ADD KEY `idx_tmp_visitors_woonsituatie_id` (`woonsituatie_id`),
  ADD KEY `idx_tmp_visitors_verblijfstatus_id` (`verblijfstatus_id`),
  ADD KEY `idx_tmp_visitors_geslacht` (`geslacht`);

--
-- Indexen voor tabel `tmp_visitors_veegploeg`
--
ALTER TABLE `tmp_visitors_veegploeg`
  ADD KEY `idx_tmp_visitors_veegploeg_land_id` (`land_id`),
  ADD KEY `idx_tmp_visitors_veegploeg_verslaving_id` (`verslaving_id`),
  ADD KEY `idx_tmp_visitors_veegploeg_klant_id` (`klant_id`),
  ADD KEY `idx_tmp_visitors_veegploeg_date` (`date`),
  ADD KEY `idx_tmp_visitors_veegploeg_woonsituatie_id` (`woonsituatie_id`),
  ADD KEY `idx_tmp_visitors_veegploeg_verblijfstatus_id` (`verblijfstatus_id`),
  ADD KEY `idx_tmp_visitors_veegploeg_geslacht` (`geslacht`);

--
-- Indexen voor tabel `tmp_visits`
--
ALTER TABLE `tmp_visits`
  ADD KEY `idx_tmp_visits_locatie_id` (`locatie_id`),
  ADD KEY `idx_tmp_visits_klant_id` (`klant_id`),
  ADD KEY `idx_tmp_visits_date` (`date`),
  ADD KEY `idx_tmp_visits_duration` (`duration`),
  ADD KEY `idx_tmp_visits_gender` (`gender`);

--
-- Indexen voor tabel `tmp_visits_veegploeg`
--
ALTER TABLE `tmp_visits_veegploeg`
  ADD KEY `idx_tmp_visits_veegploeg_locatie_id` (`locatie_id`),
  ADD KEY `idx_tmp_visits_veegploeg_klant_id` (`klant_id`),
  ADD KEY `idx_tmp_visits_veegploeg_date` (`date`),
  ADD KEY `idx_tmp_visits_veegploeg_duration` (`duration`),
  ADD KEY `idx_tmp_visits_veegploeg_gender` (`gender`);

--
-- Indexen voor tabel `trajecten`
--
ALTER TABLE `trajecten`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_klant_id` (`klant_id`);

--
-- Indexen voor tabel `tw_aanvullinginkomen`
--
ALTER TABLE `tw_aanvullinginkomen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_afsluitingen`
--
ALTER TABLE `tw_afsluitingen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_afsluitredenen_vrijwilligers`
--
ALTER TABLE `tw_afsluitredenen_vrijwilligers`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_alcohol`
--
ALTER TABLE `tw_alcohol`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_binnen_via`
--
ALTER TABLE `tw_binnen_via`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_coordinatoren`
--
ALTER TABLE `tw_coordinatoren`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8FA922023D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `tw_dagbesteding`
--
ALTER TABLE `tw_dagbesteding`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_deelnames`
--
ALTER TABLE `tw_deelnames`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C8B28A18459F3233` (`mwTraining_id`),
  ADD KEY `IDX_B0B3FDE19D1883DD` (`tw_vrijwilliger_id`),
  ADD KEY `IDX_C8B28A18629A95E` (`tw_vrijwilliger_id`);

--
-- Indexen voor tabel `tw_deelnemers`
--
ALTER TABLE `tw_deelnemers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E43172563D707F64` (`medewerker_id`),
  ADD KEY `IDX_E4317256ECDAD1A9` (`afsluiting_id`),
  ADD KEY `IDX_E4317256C0B11400` (`pandeigenaar_id`),
  ADD KEY `IDX_E43172564A06A057` (`huurBudget_id`),
  ADD KEY `IDX_E4317256D93D6B19` (`duurThuisloos_id`),
  ADD KEY `IDX_E43172561AAF70BD` (`werk_id`),
  ADD KEY `IDX_E4317256166D1F9C` (`project_id`),
  ADD KEY `IDX_E4317256C8250F57` (`zrm_id`),
  ADD KEY `IDX_E43172562BB8C0FB` (`ambulantOndersteuner_id`),
  ADD KEY `IDX_E43172565C217849` (`appKlant_id`),
  ADD KEY `IDX_E4317256A90F3026` (`bindingRegio_id`),
  ADD KEY `IDX_E4317256F9E2779E` (`dagbesteding_id`),
  ADD KEY `IDX_E4317256F190A78A` (`ritme_id`),
  ADD KEY `IDX_E431725693EEEFEC` (`huisdieren_id`),
  ADD KEY `IDX_E431725687B1EE3E` (`roken_id`),
  ADD KEY `IDX_E431725625BA5A47` (`softdrugs_id`),
  ADD KEY `IDX_E4317256D6E2DB5B` (`traplopen_id`),
  ADD KEY `IDX_E4317256D00EBD14` (`moScreening_id`),
  ADD KEY `IDX_E4317256DE7E5B0` (`inkomen_id`),
  ADD KEY `IDX_E43172565721A070` (`pandeigenaar_id`),
  ADD KEY `IDX_E43172562657F54F` (`intakeStatus_id`),
  ADD KEY `IDX_E43172565357D7EE` (`alcohol_id`),
  ADD KEY `IDX_E4317256452FF74C` (`inschrijvingWoningnet_id`),
  ADD KEY `IDX_E4317256B4770027` (`shortlist_id`),
  ADD KEY `IDX_E431725650B81711` (`aanvullingInkomen_id`),
  ADD KEY `IDX_E4317256BD67529F` (`kwijtschelding_id`),
  ADD KEY `IDX_E431725635972C83` (`huisgenoot_id`);

--
-- Indexen voor tabel `tw_deelnemer_document`
--
ALTER TABLE `tw_deelnemer_document`
  ADD PRIMARY KEY (`deelnemer_id`,`document_id`),
  ADD KEY `IDX_466075955DFA57A1` (`deelnemer_id`),
  ADD KEY `IDX_46607595C33F7837` (`document_id`);

--
-- Indexen voor tabel `tw_deelnemer_verslag`
--
ALTER TABLE `tw_deelnemer_verslag`
  ADD PRIMARY KEY (`deelnemer_id`,`verslag_id`),
  ADD KEY `IDX_33E2C4055DFA57A1` (`deelnemer_id`),
  ADD KEY `IDX_33E2C405D949475D` (`verslag_id`);

--
-- Indexen voor tabel `tw_duurthuisloos`
--
ALTER TABLE `tw_duurthuisloos`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_huisdieren`
--
ALTER TABLE `tw_huisdieren`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_huuraanbiedingen`
--
ALTER TABLE `tw_huuraanbiedingen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8E0AAC377E18485D` (`verhuurder_id`),
  ADD KEY `IDX_8E0AAC373D707F64` (`medewerker_id`),
  ADD KEY `IDX_8E0AAC37ECDAD1A9` (`afsluiting_id`),
  ADD KEY `IDX_8E0AAC37B79D9579` (`vormvanovereenkomst_id`),
  ADD KEY `IDX_8E0AAC37166D1F9C` (`project_id`);

--
-- Indexen voor tabel `tw_huuraanbod_verslag`
--
ALTER TABLE `tw_huuraanbod_verslag`
  ADD PRIMARY KEY (`huuraanbod_id`,`verslag_id`),
  ADD KEY `IDX_46EB8E0B656E2280` (`huuraanbod_id`),
  ADD KEY `IDX_46EB8E0BD949475D` (`verslag_id`);

--
-- Indexen voor tabel `tw_huurbudget`
--
ALTER TABLE `tw_huurbudget`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_huurders_tw_projecten`
--
ALTER TABLE `tw_huurders_tw_projecten`
  ADD PRIMARY KEY (`tw_huurder_id`,`tw_project_id`),
  ADD KEY `IDX_34E5855EB36F4CA5` (`tw_huurder_id`),
  ADD KEY `IDX_34E5855E3B4A66E3` (`tw_project_id`);

--
-- Indexen voor tabel `tw_huurovereenkomsten`
--
ALTER TABLE `tw_huurovereenkomsten`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_98F99DF6656E2280` (`huuraanbod_id`),
  ADD UNIQUE KEY `UNIQ_98F99DF645DA3BB7` (`huurverzoek_id`),
  ADD KEY `IDX_98F99DF63D707F64` (`medewerker_id`),
  ADD KEY `IDX_98F99DF6ECDAD1A9` (`afsluiting_id`),
  ADD KEY `IDX_98F99DF67F442D` (`huurovereenkomstType_id`);

--
-- Indexen voor tabel `tw_huurovereenkomst_document`
--
ALTER TABLE `tw_huurovereenkomst_document`
  ADD PRIMARY KEY (`huurovereenkomst_id`,`document_id`),
  ADD KEY `IDX_C5DF83BD870B85BC` (`huurovereenkomst_id`),
  ADD KEY `IDX_C5DF83BDC33F7837` (`document_id`);

--
-- Indexen voor tabel `tw_huurovereenkomst_findocument`
--
ALTER TABLE `tw_huurovereenkomst_findocument`
  ADD PRIMARY KEY (`huurovereenkomst_id`,`document_id`),
  ADD KEY `IDX_B9C41948870B85BC` (`huurovereenkomst_id`),
  ADD KEY `IDX_B9C41948C33F7837` (`document_id`);

--
-- Indexen voor tabel `tw_huurovereenkomst_finverslag`
--
ALTER TABLE `tw_huurovereenkomst_finverslag`
  ADD PRIMARY KEY (`huurovereenkomst_id`,`verslag_id`),
  ADD KEY `IDX_98E469DC870B85BC` (`huurovereenkomst_id`),
  ADD KEY `IDX_98E469DCD949475D` (`verslag_id`);

--
-- Indexen voor tabel `tw_huurovereenkomst_type`
--
ALTER TABLE `tw_huurovereenkomst_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_huurovereenkomst_verslag`
--
ALTER TABLE `tw_huurovereenkomst_verslag`
  ADD PRIMARY KEY (`huurovereenkomst_id`,`verslag_id`),
  ADD KEY `IDX_5F912B12870B85BC` (`huurovereenkomst_id`),
  ADD KEY `IDX_5F912B12D949475D` (`verslag_id`);

--
-- Indexen voor tabel `tw_huurverzoeken`
--
ALTER TABLE `tw_huurverzoeken`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B59AA1219E4835DA` (`klant_id`),
  ADD KEY `IDX_B59AA1213D707F64` (`medewerker_id`),
  ADD KEY `IDX_B59AA121ECDAD1A9` (`afsluiting_id`),
  ADD KEY `IDX_B59AA1213C427B2F` (`klant_id`);

--
-- Indexen voor tabel `tw_huurverzoeken_tw_projecten`
--
ALTER TABLE `tw_huurverzoeken_tw_projecten`
  ADD PRIMARY KEY (`tw_huurverzoek_id`,`tw_project_id`),
  ADD KEY `IDX_1466F1BBB2BC36B2` (`tw_huurverzoek_id`),
  ADD KEY `IDX_1466F1BB3B4A66E3` (`tw_project_id`);

--
-- Indexen voor tabel `tw_huurverzoek_verslag`
--
ALTER TABLE `tw_huurverzoek_verslag`
  ADD PRIMARY KEY (`huurverzoek_id`,`verslag_id`),
  ADD KEY `IDX_2D7DDF5C45DA3BB7` (`huurverzoek_id`),
  ADD KEY `IDX_2D7DDF5CD949475D` (`verslag_id`);

--
-- Indexen voor tabel `tw_inkomen`
--
ALTER TABLE `tw_inkomen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_inschrijvingwoningnet`
--
ALTER TABLE `tw_inschrijvingwoningnet`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_intakes`
--
ALTER TABLE `tw_intakes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_32F028325DFA57A1` (`deelnemer_id`),
  ADD KEY `IDX_32F028323D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `tw_intakestatus`
--
ALTER TABLE `tw_intakestatus`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_kwijtschelding`
--
ALTER TABLE `tw_kwijtschelding`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_locaties`
--
ALTER TABLE `tw_locaties`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_moscreening`
--
ALTER TABLE `tw_moscreening`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_pandeigenaar`
--
ALTER TABLE `tw_pandeigenaar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_131D6069EBDA8F64` (`pandeigenaartype_id`);

--
-- Indexen voor tabel `tw_pandeigenaartype`
--
ALTER TABLE `tw_pandeigenaartype`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_projecten`
--
ALTER TABLE `tw_projecten`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_regio`
--
ALTER TABLE `tw_regio`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_ritme`
--
ALTER TABLE `tw_ritme`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_roken`
--
ALTER TABLE `tw_roken`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_softdrugs`
--
ALTER TABLE `tw_softdrugs`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_superdocumenten`
--
ALTER TABLE `tw_superdocumenten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1633B5253D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `tw_superverslagen`
--
ALTER TABLE `tw_superverslagen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_2DCE6D3F3D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `tw_training`
--
ALTER TABLE `tw_training`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_traplopen`
--
ALTER TABLE `tw_traplopen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_vormvanovereenkomst`
--
ALTER TABLE `tw_vormvanovereenkomst`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_vrijwilligers`
--
ALTER TABLE `tw_vrijwilligers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_F49E8AA376B43BDC` (`vrijwilliger_id`),
  ADD KEY `IDX_F49E8AA34C676E6B` (`binnen_via_id`),
  ADD KEY `IDX_F49E8AA3CA12F7AE` (`afsluitreden_id`),
  ADD KEY `IDX_F49E8AA33D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `tw_vrijwilliger_document`
--
ALTER TABLE `tw_vrijwilliger_document`
  ADD PRIMARY KEY (`vrijwilliger_id`,`document_id`),
  ADD UNIQUE KEY `UNIQ_2ED02FBCC33F7837` (`document_id`),
  ADD KEY `IDX_2ED02FBC76B43BDC` (`vrijwilliger_id`);

--
-- Indexen voor tabel `tw_vrijwilliger_locatie`
--
ALTER TABLE `tw_vrijwilliger_locatie`
  ADD PRIMARY KEY (`vrijwilliger_id`,`locatie_id`),
  ADD KEY `IDX_AF4CCDFB76B43BDC` (`vrijwilliger_id`),
  ADD KEY `IDX_AF4CCDFB4947630C` (`locatie_id`);

--
-- Indexen voor tabel `tw_vrijwilliger_memo`
--
ALTER TABLE `tw_vrijwilliger_memo`
  ADD PRIMARY KEY (`vrijwilliger_id`,`memo_id`),
  ADD UNIQUE KEY `UNIQ_4915EB03B4D32439` (`memo_id`),
  ADD KEY `IDX_4915EB0376B43BDC` (`vrijwilliger_id`);

--
-- Indexen voor tabel `tw_werk`
--
ALTER TABLE `tw_werk`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `tw_woningbouwcorporaties`
--
ALTER TABLE `tw_woningbouwcorporaties`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `uhk_deelnemers`
--
ALTER TABLE `uhk_deelnemers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_739D2F673C427B2F` (`klant_id`),
  ADD KEY `FK_739D2F673D707F64` (`medewerker_id`),
  ADD KEY `IDX_739D2F6775E6B4CA` (`aanmelder_id`);

--
-- Indexen voor tabel `uhk_deelnemer_document`
--
ALTER TABLE `uhk_deelnemer_document`
  ADD PRIMARY KEY (`deelnemer_id`,`document_id`),
  ADD UNIQUE KEY `UNIQ_40FD8494C33F7837` (`document_id`),
  ADD KEY `IDX_40FD84945DFA57A1` (`deelnemer_id`);

--
-- Indexen voor tabel `uhk_documenten`
--
ALTER TABLE `uhk_documenten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_3DDA892B3D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `uhk_verslagen`
--
ALTER TABLE `uhk_verslagen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_70A8F1855DFA57A1` (`deelnemer_id`),
  ADD KEY `IDX_70A8F1853D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `verblijfstatussen`
--
ALTER TABLE `verblijfstatussen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `verslagen`
--
ALTER TABLE `verslagen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_locatie_id` (`locatie_id`),
  ADD KEY `idx_datum` (`datum`),
  ADD KEY `IDX_2BBABA713C427B2F` (`klant_id`),
  ADD KEY `IDX_2BBABA713D707F64` (`medewerker_id`),
  ADD KEY `IDX_2BBABA71D3899023` (`contactsoort_id`),
  ADD KEY `klant_id_med_id` (`klant_id`,`medewerker_id`,`verslagType`),
  ADD KEY `id` (`id`,`klant_id`,`created`) USING BTREE,
  ADD KEY `klant_id` (`klant_id`,`verslagType`);

--
-- Indexen voor tabel `verslaginfos`
--
ALTER TABLE `verslaginfos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_3D2FCA833C427B2F` (`klant_id`),
  ADD KEY `IDX_3D2FCA831B81E585` (`casemanager_id`),
  ADD KEY `IDX_3D2FCA831EC41507` (`trajectbegeleider_id`);

--
-- Indexen voor tabel `verslavingen`
--
ALTER TABLE `verslavingen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `verslavingsfrequenties`
--
ALTER TABLE `verslavingsfrequenties`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `verslavingsgebruikswijzen`
--
ALTER TABLE `verslavingsgebruikswijzen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `verslavingsperiodes`
--
ALTER TABLE `verslavingsperiodes`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `villa_afsluitredenen_vrijwilligers`
--
ALTER TABLE `villa_afsluitredenen_vrijwilligers`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `villa_binnen_via`
--
ALTER TABLE `villa_binnen_via`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `villa_deelnames`
--
ALTER TABLE `villa_deelnames`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_BA87C788459F3233` (`mwTraining_id`),
  ADD KEY `IDX_BA87C7882BAC9748` (`villa_vrijwilliger_id`);

--
-- Indexen voor tabel `villa_documenten`
--
ALTER TABLE `villa_documenten`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `villa_training`
--
ALTER TABLE `villa_training`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `villa_vrijwilligers`
--
ALTER TABLE `villa_vrijwilligers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_2DA6A73276B43BDC` (`vrijwilliger_id`),
  ADD KEY `IDX_2DA6A7324C676E6B` (`binnen_via_id`),
  ADD KEY `IDX_2DA6A732CA12F7AE` (`afsluitreden_id`),
  ADD KEY `IDX_2DA6A732EA9C84FE` (`medewerkerLocatie_id`),
  ADD KEY `IDX_2DA6A7323D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `villa_vrijwilliger_document`
--
ALTER TABLE `villa_vrijwilliger_document`
  ADD PRIMARY KEY (`vrijwilliger_id`,`document_id`),
  ADD UNIQUE KEY `UNIQ_F5C5B67C33F7837` (`document_id`),
  ADD KEY `IDX_F5C5B6776B43BDC` (`vrijwilliger_id`);

--
-- Indexen voor tabel `villa_vrijwilliger_memo`
--
ALTER TABLE `villa_vrijwilliger_memo`
  ADD PRIMARY KEY (`vrijwilliger_id`,`memo_id`),
  ADD UNIQUE KEY `UNIQ_94EC6EFAB4D32439` (`memo_id`),
  ADD KEY `IDX_94EC6EFA76B43BDC` (`vrijwilliger_id`);

--
-- Indexen voor tabel `vrijwilligers`
--
ALTER TABLE `vrijwilligers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vrijwilligers_werkgebied` (`werkgebied`),
  ADD KEY `IDX_F0C4D2373D707F64` (`medewerker_id`),
  ADD KEY `IDX_F0C4D2371C729A47` (`geslacht_id`),
  ADD KEY `IDX_F0C4D2371994904A` (`land_id`),
  ADD KEY `IDX_F0C4D237CECBFEB7` (`nationaliteit_id`),
  ADD KEY `idx_vrijwilligers_postcodegebied` (`postcodegebied`),
  ADD KEY `idx_vrijwilligers_geboortedatum` (`geboortedatum`);

--
-- Indexen voor tabel `werkgebieden`
--
ALTER TABLE `werkgebieden`
  ADD PRIMARY KEY (`naam`),
  ADD UNIQUE KEY `naam` (`naam`,`zichtbaar`);

--
-- Indexen voor tabel `woonsituaties`
--
ALTER TABLE `woonsituaties`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `zrm_reports`
--
ALTER TABLE `zrm_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C8EF119C3C427B2F` (`klant_id`),
  ADD KEY `IDX_C8EF119C3D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `zrm_settings`
--
ALTER TABLE `zrm_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `zrm_v2_reports`
--
ALTER TABLE `zrm_v2_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_751519083C427B2F` (`klant_id`);

--
-- Indexen voor tabel `zrm_v2_settings`
--
ALTER TABLE `zrm_v2_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `amoc_landen`
--
ALTER TABLE `amoc_landen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `awbz_hoofdaannemers`
--
ALTER TABLE `awbz_hoofdaannemers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `awbz_indicaties`
--
ALTER TABLE `awbz_indicaties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `awbz_intakes`
--
ALTER TABLE `awbz_intakes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `awbz_intakes_primaireproblematieksgebruikswijzen`
--
ALTER TABLE `awbz_intakes_primaireproblematieksgebruikswijzen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `awbz_intakes_verslavingen`
--
ALTER TABLE `awbz_intakes_verslavingen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `awbz_intakes_verslavingsgebruikswijzen`
--
ALTER TABLE `awbz_intakes_verslavingsgebruikswijzen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `back_on_tracks`
--
ALTER TABLE `back_on_tracks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `bedrijfitems`
--
ALTER TABLE `bedrijfitems`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `bedrijfsectoren`
--
ALTER TABLE `bedrijfsectoren`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `bot_koppelingen`
--
ALTER TABLE `bot_koppelingen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `bot_verslagen`
--
ALTER TABLE `bot_verslagen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `buurtboerderij_afsluitredenen`
--
ALTER TABLE `buurtboerderij_afsluitredenen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `buurtboerderij_vrijwilligers`
--
ALTER TABLE `buurtboerderij_vrijwilligers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `categorieen`
--
ALTER TABLE `categorieen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `clip_afsluitredenen_vrijwilligers`
--
ALTER TABLE `clip_afsluitredenen_vrijwilligers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `clip_behandelaars`
--
ALTER TABLE `clip_behandelaars`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `clip_binnen_via`
--
ALTER TABLE `clip_binnen_via`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `clip_clienten`
--
ALTER TABLE `clip_clienten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `clip_communicatiekanalen`
--
ALTER TABLE `clip_communicatiekanalen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `clip_contactmomenten`
--
ALTER TABLE `clip_contactmomenten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `clip_deelnames`
--
ALTER TABLE `clip_deelnames`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `clip_documenten`
--
ALTER TABLE `clip_documenten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `clip_hulpvragersoorten`
--
ALTER TABLE `clip_hulpvragersoorten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `clip_leeftijdscategorieen`
--
ALTER TABLE `clip_leeftijdscategorieen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `clip_locaties`
--
ALTER TABLE `clip_locaties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `clip_memos`
--
ALTER TABLE `clip_memos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `clip_training`
--
ALTER TABLE `clip_training`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `clip_viacategorieen`
--
ALTER TABLE `clip_viacategorieen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `clip_vraagsoorten`
--
ALTER TABLE `clip_vraagsoorten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `clip_vragen`
--
ALTER TABLE `clip_vragen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `clip_vrijwilligers`
--
ALTER TABLE `clip_vrijwilligers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `contactjournals`
--
ALTER TABLE `contactjournals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `contactsoorts`
--
ALTER TABLE `contactsoorts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `dagbesteding_afsluitingen`
--
ALTER TABLE `dagbesteding_afsluitingen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `dagbesteding_beschikbaarheid`
--
ALTER TABLE `dagbesteding_beschikbaarheid`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `dagbesteding_contactpersonen`
--
ALTER TABLE `dagbesteding_contactpersonen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `dagbesteding_dagdelen`
--
ALTER TABLE `dagbesteding_dagdelen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `dagbesteding_deelnames`
--
ALTER TABLE `dagbesteding_deelnames`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `dagbesteding_deelnemers`
--
ALTER TABLE `dagbesteding_deelnemers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `dagbesteding_documenten`
--
ALTER TABLE `dagbesteding_documenten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `dagbesteding_locaties`
--
ALTER TABLE `dagbesteding_locaties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `dagbesteding_projecten`
--
ALTER TABLE `dagbesteding_projecten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `dagbesteding_rapportages`
--
ALTER TABLE `dagbesteding_rapportages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `dagbesteding_resultaatgebieden`
--
ALTER TABLE `dagbesteding_resultaatgebieden`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `dagbesteding_resultaatgebiedsoorten`
--
ALTER TABLE `dagbesteding_resultaatgebiedsoorten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `dagbesteding_trajectcoaches`
--
ALTER TABLE `dagbesteding_trajectcoaches`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `dagbesteding_trajecten`
--
ALTER TABLE `dagbesteding_trajecten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `dagbesteding_trajectsoorten`
--
ALTER TABLE `dagbesteding_trajectsoorten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `dagbesteding_verslagen`
--
ALTER TABLE `dagbesteding_verslagen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `dagbesteding_werkdoelen`
--
ALTER TABLE `dagbesteding_werkdoelen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `documenten`
--
ALTER TABLE `documenten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `doelstellingen`
--
ALTER TABLE `doelstellingen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `doorverwijzers`
--
ALTER TABLE `doorverwijzers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `eropuit_klanten`
--
ALTER TABLE `eropuit_klanten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `eropuit_uitschrijfredenen`
--
ALTER TABLE `eropuit_uitschrijfredenen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `eropuit_vrijwilligers`
--
ALTER TABLE `eropuit_vrijwilligers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `ext_log_entries`
--
ALTER TABLE `ext_log_entries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `ga_activiteitannuleringsredenen`
--
ALTER TABLE `ga_activiteitannuleringsredenen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `ga_activiteiten`
--
ALTER TABLE `ga_activiteiten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `ga_deelnames`
--
ALTER TABLE `ga_deelnames`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `ga_documenten`
--
ALTER TABLE `ga_documenten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `ga_dossierafsluitredenen`
--
ALTER TABLE `ga_dossierafsluitredenen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `ga_dossiers`
--
ALTER TABLE `ga_dossiers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `ga_groepen`
--
ALTER TABLE `ga_groepen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `ga_intakes`
--
ALTER TABLE `ga_intakes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `ga_lidmaatschappen`
--
ALTER TABLE `ga_lidmaatschappen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `ga_memos`
--
ALTER TABLE `ga_memos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `ga_redenen`
--
ALTER TABLE `ga_redenen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `ga_verslagen`
--
ALTER TABLE `ga_verslagen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `gd27`
--
ALTER TABLE `gd27`
  MODIFY `idd` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `geslachten`
--
ALTER TABLE `geslachten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `groepsactiviteiten`
--
ALTER TABLE `groepsactiviteiten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `groepsactiviteiten_afsluitingen`
--
ALTER TABLE `groepsactiviteiten_afsluitingen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `groepsactiviteiten_groepen`
--
ALTER TABLE `groepsactiviteiten_groepen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `groepsactiviteiten_groepen_klanten`
--
ALTER TABLE `groepsactiviteiten_groepen_klanten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `groepsactiviteiten_groepen_vrijwilligers`
--
ALTER TABLE `groepsactiviteiten_groepen_vrijwilligers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `groepsactiviteiten_intakes`
--
ALTER TABLE `groepsactiviteiten_intakes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `groepsactiviteiten_klanten`
--
ALTER TABLE `groepsactiviteiten_klanten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `groepsactiviteiten_redenen`
--
ALTER TABLE `groepsactiviteiten_redenen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `groepsactiviteiten_verslagen`
--
ALTER TABLE `groepsactiviteiten_verslagen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `groepsactiviteiten_vrijwilligers`
--
ALTER TABLE `groepsactiviteiten_vrijwilligers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hi5_answers`
--
ALTER TABLE `hi5_answers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hi5_answer_types`
--
ALTER TABLE `hi5_answer_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hi5_evaluaties`
--
ALTER TABLE `hi5_evaluaties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hi5_evaluaties_hi5_evaluatie_questions`
--
ALTER TABLE `hi5_evaluaties_hi5_evaluatie_questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hi5_evaluatie_paragraphs`
--
ALTER TABLE `hi5_evaluatie_paragraphs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hi5_evaluatie_questions`
--
ALTER TABLE `hi5_evaluatie_questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hi5_intakes`
--
ALTER TABLE `hi5_intakes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hi5_intakes_answers`
--
ALTER TABLE `hi5_intakes_answers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hi5_intakes_inkomens`
--
ALTER TABLE `hi5_intakes_inkomens`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hi5_intakes_instanties`
--
ALTER TABLE `hi5_intakes_instanties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hi5_intakes_primaireproblematieksgebruikswijzen`
--
ALTER TABLE `hi5_intakes_primaireproblematieksgebruikswijzen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hi5_intakes_verslavingen`
--
ALTER TABLE `hi5_intakes_verslavingen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hi5_intakes_verslavingsgebruikswijzen`
--
ALTER TABLE `hi5_intakes_verslavingsgebruikswijzen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hi5_questions`
--
ALTER TABLE `hi5_questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hoofdaannemers`
--
ALTER TABLE `hoofdaannemers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hs_activiteiten`
--
ALTER TABLE `hs_activiteiten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hs_arbeiders`
--
ALTER TABLE `hs_arbeiders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hs_betalingen`
--
ALTER TABLE `hs_betalingen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hs_declaraties`
--
ALTER TABLE `hs_declaraties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hs_declaratie_categorieen`
--
ALTER TABLE `hs_declaratie_categorieen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hs_documenten`
--
ALTER TABLE `hs_documenten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hs_facturen`
--
ALTER TABLE `hs_facturen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hs_herinneringen`
--
ALTER TABLE `hs_herinneringen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hs_klanten`
--
ALTER TABLE `hs_klanten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hs_klussen`
--
ALTER TABLE `hs_klussen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hs_memos`
--
ALTER TABLE `hs_memos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hs_registraties`
--
ALTER TABLE `hs_registraties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `i18n`
--
ALTER TABLE `i18n`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `infobaliedoelgroepen`
--
ALTER TABLE `infobaliedoelgroepen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `inkomens`
--
ALTER TABLE `inkomens`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `inkomens_awbz_intakes`
--
ALTER TABLE `inkomens_awbz_intakes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `inkomens_intakes`
--
ALTER TABLE `inkomens_intakes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `inloop_afsluiting_redenen`
--
ALTER TABLE `inloop_afsluiting_redenen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `inloop_afsluitredenen_vrijwilligers`
--
ALTER TABLE `inloop_afsluitredenen_vrijwilligers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `inloop_binnen_via`
--
ALTER TABLE `inloop_binnen_via`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `inloop_deelnames`
--
ALTER TABLE `inloop_deelnames`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `inloop_documenten`
--
ALTER TABLE `inloop_documenten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `inloop_dossier_statussen`
--
ALTER TABLE `inloop_dossier_statussen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `inloop_incidenten`
--
ALTER TABLE `inloop_incidenten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `inloop_memos`
--
ALTER TABLE `inloop_memos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `inloop_training`
--
ALTER TABLE `inloop_training`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `inloop_vrijwilligers`
--
ALTER TABLE `inloop_vrijwilligers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `instanties`
--
ALTER TABLE `instanties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `instanties_awbz_intakes`
--
ALTER TABLE `instanties_awbz_intakes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `instanties_intakes`
--
ALTER TABLE `instanties_intakes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `intakes`
--
ALTER TABLE `intakes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `intakes_primaireproblematieksgebruikswijzen`
--
ALTER TABLE `intakes_primaireproblematieksgebruikswijzen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `intakes_verslavingen`
--
ALTER TABLE `intakes_verslavingen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `intakes_verslavingsgebruikswijzen`
--
ALTER TABLE `intakes_verslavingsgebruikswijzen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `inventarisaties`
--
ALTER TABLE `inventarisaties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `inventarisaties_verslagen`
--
ALTER TABLE `inventarisaties_verslagen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `iz_afsluitingen`
--
ALTER TABLE `iz_afsluitingen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `iz_deelnemers`
--
ALTER TABLE `iz_deelnemers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `iz_deelnemers_iz_intervisiegroepen`
--
ALTER TABLE `iz_deelnemers_iz_intervisiegroepen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `iz_deelnemers_iz_projecten`
--
ALTER TABLE `iz_deelnemers_iz_projecten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `iz_documenten`
--
ALTER TABLE `iz_documenten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `iz_doelgroepen`
--
ALTER TABLE `iz_doelgroepen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `iz_doelstellingen`
--
ALTER TABLE `iz_doelstellingen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `iz_eindekoppelingen`
--
ALTER TABLE `iz_eindekoppelingen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `iz_hulpvraagsoorten`
--
ALTER TABLE `iz_hulpvraagsoorten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `iz_intakes`
--
ALTER TABLE `iz_intakes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `iz_intervisiegroepen`
--
ALTER TABLE `iz_intervisiegroepen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `iz_koppelingen`
--
ALTER TABLE `iz_koppelingen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `iz_matching_klanten`
--
ALTER TABLE `iz_matching_klanten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `iz_matching_vrijwilligers`
--
ALTER TABLE `iz_matching_vrijwilligers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `iz_ontstaan_contacten`
--
ALTER TABLE `iz_ontstaan_contacten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `iz_projecten`
--
ALTER TABLE `iz_projecten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `iz_reserveringen`
--
ALTER TABLE `iz_reserveringen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `iz_succesindicatoren`
--
ALTER TABLE `iz_succesindicatoren`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `iz_verslagen`
--
ALTER TABLE `iz_verslagen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `iz_via_personen`
--
ALTER TABLE `iz_via_personen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `iz_vraagaanboden`
--
ALTER TABLE `iz_vraagaanboden`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `klanten`
--
ALTER TABLE `klanten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `klantinventarisaties`
--
ALTER TABLE `klantinventarisaties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `klant_taal`
--
ALTER TABLE `klant_taal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `landen`
--
ALTER TABLE `landen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `legitimaties`
--
ALTER TABLE `legitimaties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `locaties`
--
ALTER TABLE `locaties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `locatie_tijden`
--
ALTER TABLE `locatie_tijden`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `logs_2014`
--
ALTER TABLE `logs_2014`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `logs_2015`
--
ALTER TABLE `logs_2015`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `logs_2016`
--
ALTER TABLE `logs_2016`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `logs_2017`
--
ALTER TABLE `logs_2017`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `medewerkers`
--
ALTER TABLE `medewerkers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `mw_afsluiting_redenen`
--
ALTER TABLE `mw_afsluiting_redenen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `mw_afsluitredenen_vrijwilligers`
--
ALTER TABLE `mw_afsluitredenen_vrijwilligers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `mw_binnen_via`
--
ALTER TABLE `mw_binnen_via`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `mw_deelnames`
--
ALTER TABLE `mw_deelnames`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `mw_documenten`
--
ALTER TABLE `mw_documenten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `mw_dossier_statussen`
--
ALTER TABLE `mw_dossier_statussen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `mw_resultaten`
--
ALTER TABLE `mw_resultaten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `mw_training`
--
ALTER TABLE `mw_training`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `mw_vrijwilligers`
--
ALTER TABLE `mw_vrijwilligers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `nationaliteiten`
--
ALTER TABLE `nationaliteiten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `notities`
--
ALTER TABLE `notities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `oekraine_afsluiting_redenen`
--
ALTER TABLE `oekraine_afsluiting_redenen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `oekraine_afsluitredenen_vrijwilligers`
--
ALTER TABLE `oekraine_afsluitredenen_vrijwilligers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `oekraine_bezoekers`
--
ALTER TABLE `oekraine_bezoekers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `oekraine_documenten`
--
ALTER TABLE `oekraine_documenten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `oekraine_dossier_statussen`
--
ALTER TABLE `oekraine_dossier_statussen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `oekraine_incidenten`
--
ALTER TABLE `oekraine_incidenten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `oekraine_inkomens`
--
ALTER TABLE `oekraine_inkomens`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `oekraine_intakes`
--
ALTER TABLE `oekraine_intakes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `oekraine_locaties`
--
ALTER TABLE `oekraine_locaties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `oekraine_registraties`
--
ALTER TABLE `oekraine_registraties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `oekraine_verslagen`
--
ALTER TABLE `oekraine_verslagen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `oek_deelnames`
--
ALTER TABLE `oek_deelnames`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `oek_deelname_statussen`
--
ALTER TABLE `oek_deelname_statussen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `oek_documenten`
--
ALTER TABLE `oek_documenten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `oek_dossier_statussen`
--
ALTER TABLE `oek_dossier_statussen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `oek_groepen`
--
ALTER TABLE `oek_groepen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `oek_klanten`
--
ALTER TABLE `oek_klanten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `oek_lidmaatschappen`
--
ALTER TABLE `oek_lidmaatschappen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `oek_memos`
--
ALTER TABLE `oek_memos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `oek_trainingen`
--
ALTER TABLE `oek_trainingen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `oek_verwijzingen`
--
ALTER TABLE `oek_verwijzingen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `oek_vrijwilligers`
--
ALTER TABLE `oek_vrijwilligers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `opmerkingen`
--
ALTER TABLE `opmerkingen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `pfo_aard_relaties`
--
ALTER TABLE `pfo_aard_relaties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `pfo_clienten`
--
ALTER TABLE `pfo_clienten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `pfo_clienten_supportgroups`
--
ALTER TABLE `pfo_clienten_supportgroups`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `pfo_clienten_verslagen`
--
ALTER TABLE `pfo_clienten_verslagen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `pfo_documenten`
--
ALTER TABLE `pfo_documenten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `pfo_groepen`
--
ALTER TABLE `pfo_groepen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `pfo_verslagen`
--
ALTER TABLE `pfo_verslagen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `postcodegebieden`
--
ALTER TABLE `postcodegebieden`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `queue_tasks`
--
ALTER TABLE `queue_tasks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `redenen`
--
ALTER TABLE `redenen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `registraties`
--
ALTER TABLE `registraties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `registraties_2010`
--
ALTER TABLE `registraties_2010`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `registraties_2011`
--
ALTER TABLE `registraties_2011`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `registraties_2012`
--
ALTER TABLE `registraties_2012`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `registraties_2013`
--
ALTER TABLE `registraties_2013`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `schorsingen`
--
ALTER TABLE `schorsingen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `schorsingen_redenen`
--
ALTER TABLE `schorsingen_redenen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `scip_beschikbaarheid`
--
ALTER TABLE `scip_beschikbaarheid`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `scip_deelnames`
--
ALTER TABLE `scip_deelnames`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `scip_deelnemers`
--
ALTER TABLE `scip_deelnemers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `scip_documenten`
--
ALTER TABLE `scip_documenten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `scip_labels`
--
ALTER TABLE `scip_labels`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `scip_projecten`
--
ALTER TABLE `scip_projecten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `scip_toegangsrechten`
--
ALTER TABLE `scip_toegangsrechten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `scip_verslagen`
--
ALTER TABLE `scip_verslagen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `scip_werkdoelen`
--
ALTER TABLE `scip_werkdoelen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `talen`
--
ALTER TABLE `talen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `trajecten`
--
ALTER TABLE `trajecten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_aanvullinginkomen`
--
ALTER TABLE `tw_aanvullinginkomen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_afsluitingen`
--
ALTER TABLE `tw_afsluitingen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_afsluitredenen_vrijwilligers`
--
ALTER TABLE `tw_afsluitredenen_vrijwilligers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_alcohol`
--
ALTER TABLE `tw_alcohol`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_binnen_via`
--
ALTER TABLE `tw_binnen_via`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_coordinatoren`
--
ALTER TABLE `tw_coordinatoren`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_dagbesteding`
--
ALTER TABLE `tw_dagbesteding`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_deelnames`
--
ALTER TABLE `tw_deelnames`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_deelnemers`
--
ALTER TABLE `tw_deelnemers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_duurthuisloos`
--
ALTER TABLE `tw_duurthuisloos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_huisdieren`
--
ALTER TABLE `tw_huisdieren`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_huuraanbiedingen`
--
ALTER TABLE `tw_huuraanbiedingen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_huurbudget`
--
ALTER TABLE `tw_huurbudget`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_huurovereenkomsten`
--
ALTER TABLE `tw_huurovereenkomsten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_huurovereenkomst_type`
--
ALTER TABLE `tw_huurovereenkomst_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_huurverzoeken`
--
ALTER TABLE `tw_huurverzoeken`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_inkomen`
--
ALTER TABLE `tw_inkomen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_inschrijvingwoningnet`
--
ALTER TABLE `tw_inschrijvingwoningnet`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_intakes`
--
ALTER TABLE `tw_intakes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_intakestatus`
--
ALTER TABLE `tw_intakestatus`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_kwijtschelding`
--
ALTER TABLE `tw_kwijtschelding`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_locaties`
--
ALTER TABLE `tw_locaties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_moscreening`
--
ALTER TABLE `tw_moscreening`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_pandeigenaar`
--
ALTER TABLE `tw_pandeigenaar`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_pandeigenaartype`
--
ALTER TABLE `tw_pandeigenaartype`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_projecten`
--
ALTER TABLE `tw_projecten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_regio`
--
ALTER TABLE `tw_regio`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_ritme`
--
ALTER TABLE `tw_ritme`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_roken`
--
ALTER TABLE `tw_roken`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_softdrugs`
--
ALTER TABLE `tw_softdrugs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_superdocumenten`
--
ALTER TABLE `tw_superdocumenten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_superverslagen`
--
ALTER TABLE `tw_superverslagen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_training`
--
ALTER TABLE `tw_training`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_traplopen`
--
ALTER TABLE `tw_traplopen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_vormvanovereenkomst`
--
ALTER TABLE `tw_vormvanovereenkomst`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_vrijwilligers`
--
ALTER TABLE `tw_vrijwilligers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_werk`
--
ALTER TABLE `tw_werk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `tw_woningbouwcorporaties`
--
ALTER TABLE `tw_woningbouwcorporaties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `uhk_deelnemers`
--
ALTER TABLE `uhk_deelnemers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `uhk_documenten`
--
ALTER TABLE `uhk_documenten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `uhk_verslagen`
--
ALTER TABLE `uhk_verslagen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `verblijfstatussen`
--
ALTER TABLE `verblijfstatussen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `verslagen`
--
ALTER TABLE `verslagen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `verslaginfos`
--
ALTER TABLE `verslaginfos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `verslavingen`
--
ALTER TABLE `verslavingen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `verslavingsfrequenties`
--
ALTER TABLE `verslavingsfrequenties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `verslavingsgebruikswijzen`
--
ALTER TABLE `verslavingsgebruikswijzen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `verslavingsperiodes`
--
ALTER TABLE `verslavingsperiodes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `villa_afsluitredenen_vrijwilligers`
--
ALTER TABLE `villa_afsluitredenen_vrijwilligers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `villa_binnen_via`
--
ALTER TABLE `villa_binnen_via`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `villa_deelnames`
--
ALTER TABLE `villa_deelnames`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `villa_documenten`
--
ALTER TABLE `villa_documenten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `villa_training`
--
ALTER TABLE `villa_training`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `villa_vrijwilligers`
--
ALTER TABLE `villa_vrijwilligers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `vrijwilligers`
--
ALTER TABLE `vrijwilligers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `woonsituaties`
--
ALTER TABLE `woonsituaties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `zrm_reports`
--
ALTER TABLE `zrm_reports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `zrm_settings`
--
ALTER TABLE `zrm_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `zrm_v2_reports`
--
ALTER TABLE `zrm_v2_reports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `zrm_v2_settings`
--
ALTER TABLE `zrm_v2_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `amoc_landen`
--
ALTER TABLE `amoc_landen`
  ADD CONSTRAINT `FK_2B24A60A1994904A` FOREIGN KEY (`land_id`) REFERENCES `landen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `app_klant_document`
--
ALTER TABLE `app_klant_document`
  ADD CONSTRAINT `FK_7BA5F5B3C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_7BA5F5BC33F7837` FOREIGN KEY (`document_id`) REFERENCES `documenten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `app_vrijwilliger_document`
--
ALTER TABLE `app_vrijwilliger_document`
  ADD CONSTRAINT `FK_D5E9A8C976B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_D5E9A8C9C33F7837` FOREIGN KEY (`document_id`) REFERENCES `documenten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `buurtboerderij_vrijwilligers`
--
ALTER TABLE `buurtboerderij_vrijwilligers`
  ADD CONSTRAINT `FK_57645FD73D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_57645FD776B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `vrijwilligers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_57645FD7CA12F7AE` FOREIGN KEY (`afsluitreden_id`) REFERENCES `buurtboerderij_afsluitredenen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `clip_behandelaars`
--
ALTER TABLE `clip_behandelaars`
  ADD CONSTRAINT `FK_4B016D223D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `clip_clienten`
--
ALTER TABLE `clip_clienten`
  ADD CONSTRAINT `FK_B7F4C67E1C729A47` FOREIGN KEY (`geslacht_id`) REFERENCES `geslachten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_B7F4C67E35A09212` FOREIGN KEY (`behandelaar_id`) REFERENCES `clip_behandelaars` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_B7F4C67E46708ED5` FOREIGN KEY (`werkgebied`) REFERENCES `werkgebieden` (`naam`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_B7F4C67EC5BB5F49` FOREIGN KEY (`viacategorie_id`) REFERENCES `clip_viacategorieen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_B7F4C67EFB02B9C2` FOREIGN KEY (`postcodegebied`) REFERENCES `ggw_gebieden` (`naam`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `clip_client_document`
--
ALTER TABLE `clip_client_document`
  ADD CONSTRAINT `FK_18AEA4C519EB6921` FOREIGN KEY (`client_id`) REFERENCES `clip_clienten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_18AEA4C5C33F7837` FOREIGN KEY (`document_id`) REFERENCES `clip_documenten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `clip_contactmomenten`
--
ALTER TABLE `clip_contactmomenten`
  ADD CONSTRAINT `FK_8C4DFF3D2CE1D7E6` FOREIGN KEY (`vraag_id`) REFERENCES `clip_vragen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_8C4DFF3D35A09212` FOREIGN KEY (`behandelaar_id`) REFERENCES `clip_behandelaars` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `clip_deelnames`
--
ALTER TABLE `clip_deelnames`
  ADD CONSTRAINT `FK_BDA50576459F3233` FOREIGN KEY (`mwTraining_id`) REFERENCES `clip_training` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_BDA50576B280D297` FOREIGN KEY (`clip_vrijwilliger_id`) REFERENCES `clip_vrijwilligers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `clip_documenten`
--
ALTER TABLE `clip_documenten`
  ADD CONSTRAINT `FK_98FCA35A09212` FOREIGN KEY (`behandelaar_id`) REFERENCES `clip_behandelaars` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `clip_memos`
--
ALTER TABLE `clip_memos`
  ADD CONSTRAINT `FK_BB25CF7C3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `clip_vraag_document`
--
ALTER TABLE `clip_vraag_document`
  ADD CONSTRAINT `FK_37F7BFD72CE1D7E6` FOREIGN KEY (`vraag_id`) REFERENCES `clip_vragen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_37F7BFD7C33F7837` FOREIGN KEY (`document_id`) REFERENCES `clip_documenten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `clip_vragen`
--
ALTER TABLE `clip_vragen`
  ADD CONSTRAINT `FK_C28C591717F2E03B` FOREIGN KEY (`hulpvrager_id`) REFERENCES `clip_hulpvragersoorten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_C28C591719EB6921` FOREIGN KEY (`client_id`) REFERENCES `clip_clienten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_C28C59172EC18014` FOREIGN KEY (`leeftijdscategorie_id`) REFERENCES `clip_leeftijdscategorieen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_C28C591735A09212` FOREIGN KEY (`behandelaar_id`) REFERENCES `clip_behandelaars` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_C28C59173DEE50DF` FOREIGN KEY (`soort_id`) REFERENCES `clip_vraagsoorten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_C28C591771CC83CE` FOREIGN KEY (`communicatiekanaal_id`) REFERENCES `clip_communicatiekanalen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `clip_vrijwilligers`
--
ALTER TABLE `clip_vrijwilligers`
  ADD CONSTRAINT `FK_E0E2570B3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_E0E2570B4C676E6B` FOREIGN KEY (`binnen_via_id`) REFERENCES `clip_binnen_via` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_E0E2570B76B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `vrijwilligers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_E0E2570BCA12F7AE` FOREIGN KEY (`afsluitreden_id`) REFERENCES `clip_afsluitredenen_vrijwilligers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_E0E2570BEA9C84FE` FOREIGN KEY (`medewerkerLocatie_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `clip_vrijwilliger_document`
--
ALTER TABLE `clip_vrijwilliger_document`
  ADD CONSTRAINT `FK_74EC4DF876B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `clip_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_74EC4DF8C33F7837` FOREIGN KEY (`document_id`) REFERENCES `clip_documenten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `clip_vrijwilliger_locatie`
--
ALTER TABLE `clip_vrijwilliger_locatie`
  ADD CONSTRAINT `FK_96F192054947630C` FOREIGN KEY (`locatie_id`) REFERENCES `clip_locaties` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_96F1920576B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `clip_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `clip_vrijwilliger_memo`
--
ALTER TABLE `clip_vrijwilliger_memo`
  ADD CONSTRAINT `FK_DDCB9E0D76B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `clip_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_DDCB9E0DB4D32439` FOREIGN KEY (`memo_id`) REFERENCES `clip_memos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `dagbesteding_beschikbaarheid`
--
ALTER TABLE `dagbesteding_beschikbaarheid`
  ADD CONSTRAINT `FK_912C9E7AC18FA9D5` FOREIGN KEY (`deelname_id`) REFERENCES `dagbesteding_deelnames` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `dagbesteding_contactpersonen`
--
ALTER TABLE `dagbesteding_contactpersonen`
  ADD CONSTRAINT `FK_C14C44B85DFA57A1` FOREIGN KEY (`deelnemer_id`) REFERENCES `dagbesteding_deelnemers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `dagbesteding_dagdelen`
--
ALTER TABLE `dagbesteding_dagdelen`
  ADD CONSTRAINT `FK_54F41972166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `dagbesteding_projecten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_54F41972A0CADD4` FOREIGN KEY (`traject_id`) REFERENCES `dagbesteding_trajecten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `dagbesteding_deelnames`
--
ALTER TABLE `dagbesteding_deelnames`
  ADD CONSTRAINT `FK_328AD703166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `dagbesteding_projecten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_328AD7035DFA57A1` FOREIGN KEY (`traject_id`) REFERENCES `dagbesteding_trajecten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `dagbesteding_deelnemers`
--
ALTER TABLE `dagbesteding_deelnemers`
  ADD CONSTRAINT `FK_6EAE83E73C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_6EAE83E73D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_6EAE83E7ECDAD1A9` FOREIGN KEY (`afsluiting_id`) REFERENCES `dagbesteding_afsluitingen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `dagbesteding_deelnemer_document`
--
ALTER TABLE `dagbesteding_deelnemer_document`
  ADD CONSTRAINT `FK_89539E645DFA57A1` FOREIGN KEY (`deelnemer_id`) REFERENCES `dagbesteding_deelnemers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_89539E64C33F7837` FOREIGN KEY (`document_id`) REFERENCES `dagbesteding_documenten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `dagbesteding_deelnemer_verslag`
--
ALTER TABLE `dagbesteding_deelnemer_verslag`
  ADD CONSTRAINT `FK_BA9CAC335DFA57A1` FOREIGN KEY (`deelnemer_id`) REFERENCES `dagbesteding_deelnemers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_BA9CAC33D949475D` FOREIGN KEY (`verslag_id`) REFERENCES `dagbesteding_verslagen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `dagbesteding_documenten`
--
ALTER TABLE `dagbesteding_documenten`
  ADD CONSTRAINT `FK_20E925AB3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `dagbesteding_projecten`
--
ALTER TABLE `dagbesteding_projecten`
  ADD CONSTRAINT `FK_6AD94DA34947630C` FOREIGN KEY (`locatie_id`) REFERENCES `dagbesteding_locaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `dagbesteding_rapportages`
--
ALTER TABLE `dagbesteding_rapportages`
  ADD CONSTRAINT `FK_FBA614843D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_FBA61484A0CADD4` FOREIGN KEY (`traject_id`) REFERENCES `dagbesteding_trajecten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `dagbesteding_rapportage_document`
--
ALTER TABLE `dagbesteding_rapportage_document`
  ADD CONSTRAINT `FK_8ED5B83668A3850` FOREIGN KEY (`rapportage_id`) REFERENCES `dagbesteding_rapportages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_8ED5B83C33F7837` FOREIGN KEY (`document_id`) REFERENCES `dagbesteding_documenten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `dagbesteding_resultaatgebieden`
--
ALTER TABLE `dagbesteding_resultaatgebieden`
  ADD CONSTRAINT `FK_4F7529D33DEE50DF` FOREIGN KEY (`soort_id`) REFERENCES `dagbesteding_resultaatgebiedsoorten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_4F7529D3A0CADD4` FOREIGN KEY (`traject_id`) REFERENCES `dagbesteding_trajecten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `dagbesteding_trajectcoaches`
--
ALTER TABLE `dagbesteding_trajectcoaches`
  ADD CONSTRAINT `FK_EA2465533D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `dagbesteding_trajecten`
--
ALTER TABLE `dagbesteding_trajecten`
  ADD CONSTRAINT `FK_670A67F21BE6904` FOREIGN KEY (`resultaatgebied_id`) REFERENCES `dagbesteding_resultaatgebieden` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_670A67F23DEE50DF` FOREIGN KEY (`soort_id`) REFERENCES `dagbesteding_trajectsoorten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_670A67F25DFA57A1` FOREIGN KEY (`deelnemer_id`) REFERENCES `dagbesteding_deelnemers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_670A67F2AEDCD25A` FOREIGN KEY (`trajectcoach_id`) REFERENCES `dagbesteding_trajectcoaches` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_670A67F2ECDAD1A9` FOREIGN KEY (`afsluiting_id`) REFERENCES `dagbesteding_afsluitingen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `dagbesteding_traject_document`
--
ALTER TABLE `dagbesteding_traject_document`
  ADD CONSTRAINT `FK_5408B1ADA0CADD4` FOREIGN KEY (`traject_id`) REFERENCES `dagbesteding_trajecten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_5408B1ADC33F7837` FOREIGN KEY (`document_id`) REFERENCES `dagbesteding_documenten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `dagbesteding_traject_locatie`
--
ALTER TABLE `dagbesteding_traject_locatie`
  ADD CONSTRAINT `FK_1D8874704947630C` FOREIGN KEY (`locatie_id`) REFERENCES `dagbesteding_locaties` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_1D887470A0CADD4` FOREIGN KEY (`traject_id`) REFERENCES `dagbesteding_trajecten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `dagbesteding_traject_project`
--
ALTER TABLE `dagbesteding_traject_project`
  ADD CONSTRAINT `FK_9DF4F8B0166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `dagbesteding_projecten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_9DF4F8B0A0CADD4` FOREIGN KEY (`traject_id`) REFERENCES `dagbesteding_trajecten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `dagbesteding_traject_verslag`
--
ALTER TABLE `dagbesteding_traject_verslag`
  ADD CONSTRAINT `FK_ECCFAC5CA0CADD4` FOREIGN KEY (`traject_id`) REFERENCES `dagbesteding_trajecten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_ECCFAC5CD949475D` FOREIGN KEY (`verslag_id`) REFERENCES `dagbesteding_verslagen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `dagbesteding_verslagen`
--
ALTER TABLE `dagbesteding_verslagen`
  ADD CONSTRAINT `FK_F41415953D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `dagbesteding_werkdoelen`
--
ALTER TABLE `dagbesteding_werkdoelen`
  ADD CONSTRAINT `FK_56257F583D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_56257F585DFA57A1` FOREIGN KEY (`deelnemer_id`) REFERENCES `dagbesteding_deelnemers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_56257F58A0CADD4` FOREIGN KEY (`traject_id`) REFERENCES `dagbesteding_trajecten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `documenten`
--
ALTER TABLE `documenten`
  ADD CONSTRAINT `FK_8751AD653D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `eropuit_klanten`
--
ALTER TABLE `eropuit_klanten`
  ADD CONSTRAINT `FK_4B38B9823C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_4B38B9825D010236` FOREIGN KEY (`uitschrijfreden_id`) REFERENCES `eropuit_uitschrijfredenen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `eropuit_vrijwilligers`
--
ALTER TABLE `eropuit_vrijwilligers`
  ADD CONSTRAINT `FK_3D566A3E5D010236` FOREIGN KEY (`uitschrijfreden_id`) REFERENCES `eropuit_uitschrijfredenen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_3D566A3E76B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `vrijwilligers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `ga_activiteiten`
--
ALTER TABLE `ga_activiteiten`
  ADD CONSTRAINT `FK_56418A35209ADBBB` FOREIGN KEY (`annuleringsreden_id`) REFERENCES `ga_activiteitannuleringsredenen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_56418A359EB44EC5` FOREIGN KEY (`groep_id`) REFERENCES `ga_groepen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `ga_deelnames`
--
ALTER TABLE `ga_deelnames`
  ADD CONSTRAINT `FK_F577BB9C5A8A0A1` FOREIGN KEY (`activiteit_id`) REFERENCES `ga_activiteiten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_F577BB9C611C0C56` FOREIGN KEY (`dossier_id`) REFERENCES `ga_dossiers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `ga_documenten`
--
ALTER TABLE `ga_documenten`
  ADD CONSTRAINT `FK_409E56123D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `ga_dossiers`
--
ALTER TABLE `ga_dossiers`
  ADD CONSTRAINT `FK_470A2E333C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_470A2E333D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_470A2E3376B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `vrijwilligers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_470A2E33CA12F7AE` FOREIGN KEY (`afsluitreden_id`) REFERENCES `ga_dossierafsluitredenen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `ga_dossier_document`
--
ALTER TABLE `ga_dossier_document`
  ADD CONSTRAINT `FK_63244AA1611C0C56` FOREIGN KEY (`dossier_id`) REFERENCES `ga_dossiers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_63244AA1C33F7837` FOREIGN KEY (`document_id`) REFERENCES `ga_documenten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `ga_gaklantintake_zrm`
--
ALTER TABLE `ga_gaklantintake_zrm`
  ADD CONSTRAINT `FK_D68056715FA93F88` FOREIGN KEY (`gaklantintake_id`) REFERENCES `groepsactiviteiten_intakes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_D6805671C8250F57` FOREIGN KEY (`zrm_id`) REFERENCES `zrm_reports` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `ga_groepen`
--
ALTER TABLE `ga_groepen`
  ADD CONSTRAINT `FK_EEBF811346708ED5` FOREIGN KEY (`werkgebied`) REFERENCES `werkgebieden` (`naam`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `ga_intakes`
--
ALTER TABLE `ga_intakes`
  ADD CONSTRAINT `FK_21B329223D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_21B32922611C0C56` FOREIGN KEY (`dossier_id`) REFERENCES `ga_dossiers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `ga_intake_zrm`
--
ALTER TABLE `ga_intake_zrm`
  ADD CONSTRAINT `FK_4F2ECDF1733DE450` FOREIGN KEY (`intake_id`) REFERENCES `ga_intakes` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_4F2ECDF1C8250F57` FOREIGN KEY (`zrm_id`) REFERENCES `zrm_reports` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `ga_lidmaatschappen`
--
ALTER TABLE `ga_lidmaatschappen`
  ADD CONSTRAINT `FK_D9F3C3F5248D162C` FOREIGN KEY (`groepsactiviteiten_reden_id`) REFERENCES `groepsactiviteiten_redenen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_D9F3C3F5611C0C56` FOREIGN KEY (`dossier_id`) REFERENCES `ga_dossiers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_D9F3C3F59EB44EC5` FOREIGN KEY (`groep_id`) REFERENCES `ga_groepen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `ga_memos`
--
ALTER TABLE `ga_memos`
  ADD CONSTRAINT `FK_692930E83D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `ga_verslagen`
--
ALTER TABLE `ga_verslagen`
  ADD CONSTRAINT `FK_33E9790A3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_33E9790A611C0C56` FOREIGN KEY (`dossier_id`) REFERENCES `ga_dossiers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `groepsactiviteiten`
--
ALTER TABLE `groepsactiviteiten`
  ADD CONSTRAINT `FK_9DB0AE2768AE5B4C` FOREIGN KEY (`groepsactiviteiten_groep_id`) REFERENCES `groepsactiviteiten_groepen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `groepsactiviteiten_groepen_klanten`
--
ALTER TABLE `groepsactiviteiten_groepen_klanten`
  ADD CONSTRAINT `FK_E248EC8D248D162C` FOREIGN KEY (`groepsactiviteiten_reden_id`) REFERENCES `groepsactiviteiten_redenen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_E248EC8D3C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_E248EC8D68AE5B4C` FOREIGN KEY (`groepsactiviteiten_groep_id`) REFERENCES `groepsactiviteiten_groepen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `groepsactiviteiten_groepen_vrijwilligers`
--
ALTER TABLE `groepsactiviteiten_groepen_vrijwilligers`
  ADD CONSTRAINT `FK_81655E23248D162C` FOREIGN KEY (`groepsactiviteiten_reden_id`) REFERENCES `groepsactiviteiten_redenen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_81655E2368AE5B4C` FOREIGN KEY (`groepsactiviteiten_groep_id`) REFERENCES `groepsactiviteiten_groepen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_81655E2376B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `vrijwilligers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `groepsactiviteiten_intakes`
--
ALTER TABLE `groepsactiviteiten_intakes`
  ADD CONSTRAINT `FK_843277B3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_843277B64BCC47A` FOREIGN KEY (`groepsactiviteiten_afsluiting_id`) REFERENCES `groepsactiviteiten_afsluitingen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `groepsactiviteiten_klanten`
--
ALTER TABLE `groepsactiviteiten_klanten`
  ADD CONSTRAINT `FK_560B17693C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_560B17695BF7B988` FOREIGN KEY (`groepsactiviteit_id`) REFERENCES `groepsactiviteiten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `groepsactiviteiten_verslagen`
--
ALTER TABLE `groepsactiviteiten_verslagen`
  ADD CONSTRAINT `FK_BF289BE03D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `groepsactiviteiten_vrijwilligers`
--
ALTER TABLE `groepsactiviteiten_vrijwilligers`
  ADD CONSTRAINT `FK_78A2C7E476B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `vrijwilligers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `hs_betalingen`
--
ALTER TABLE `hs_betalingen`
  ADD CONSTRAINT `FK_EADEA9FFC35D3E` FOREIGN KEY (`factuur_id`) REFERENCES `hs_facturen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `hs_declaraties`
--
ALTER TABLE `hs_declaraties`
  ADD CONSTRAINT `FK_AF23D2921EE52B26` FOREIGN KEY (`declaratieCategorie_id`) REFERENCES `hs_declaratie_categorieen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_AF23D2923D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_AF23D292BA5374AF` FOREIGN KEY (`klus_id`) REFERENCES `hs_klussen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `hs_declaratie_document`
--
ALTER TABLE `hs_declaratie_document`
  ADD CONSTRAINT `FK_9E1A25FE6AE7FC36` FOREIGN KEY (`declaratie_id`) REFERENCES `hs_declaraties` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_9E1A25FEC33F7837` FOREIGN KEY (`document_id`) REFERENCES `hs_documenten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `hs_dienstverleners`
--
ALTER TABLE `hs_dienstverleners`
  ADD CONSTRAINT `FK_4949548D3C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_4949548DBF396750` FOREIGN KEY (`id`) REFERENCES `hs_arbeiders` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `hs_dienstverlener_document`
--
ALTER TABLE `hs_dienstverlener_document`
  ADD CONSTRAINT `FK_DEBCC7F2A1690166` FOREIGN KEY (`dienstverlener_id`) REFERENCES `hs_dienstverleners` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_DEBCC7F2C33F7837` FOREIGN KEY (`document_id`) REFERENCES `hs_documenten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `hs_dienstverlener_memo`
--
ALTER TABLE `hs_dienstverlener_memo`
  ADD CONSTRAINT `FK_F546B7DDA1690166` FOREIGN KEY (`dienstverlener_id`) REFERENCES `hs_dienstverleners` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_F546B7DDB4D32439` FOREIGN KEY (`memo_id`) REFERENCES `hs_memos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `hs_documenten`
--
ALTER TABLE `hs_documenten`
  ADD CONSTRAINT `FK_87CDF0443D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `hs_facturen`
--
ALTER TABLE `hs_facturen`
  ADD CONSTRAINT `FK_31669C163C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `hs_klanten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `hs_factuur_klus`
--
ALTER TABLE `hs_factuur_klus`
  ADD CONSTRAINT `FK_B3DD2838BA5374AF` FOREIGN KEY (`klus_id`) REFERENCES `hs_klussen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_B3DD2838C35D3E` FOREIGN KEY (`factuur_id`) REFERENCES `hs_facturen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `hs_herinneringen`
--
ALTER TABLE `hs_herinneringen`
  ADD CONSTRAINT `FK_D18DFCA3C35D3E` FOREIGN KEY (`factuur_id`) REFERENCES `hs_facturen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `hs_klanten`
--
ALTER TABLE `hs_klanten`
  ADD CONSTRAINT `FK_CC6284A1C729A47` FOREIGN KEY (`geslacht_id`) REFERENCES `geslachten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_CC6284A3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_CC6284A46708ED5` FOREIGN KEY (`werkgebied`) REFERENCES `werkgebieden` (`naam`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_CC6284AFB02B9C2` FOREIGN KEY (`postcodegebied`) REFERENCES `ggw_gebieden` (`naam`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `hs_klant_document`
--
ALTER TABLE `hs_klant_document`
  ADD CONSTRAINT `FK_795E7D0B3C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `hs_klanten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_795E7D0BC33F7837` FOREIGN KEY (`document_id`) REFERENCES `hs_documenten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `hs_klant_memo`
--
ALTER TABLE `hs_klant_memo`
  ADD CONSTRAINT `FK_177077063C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `hs_klanten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_17707706B4D32439` FOREIGN KEY (`memo_id`) REFERENCES `hs_memos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `hs_klussen`
--
ALTER TABLE `hs_klussen`
  ADD CONSTRAINT `FK_3E9A80CF3C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `hs_klanten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_3E9A80CF3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `hs_klus_activiteit`
--
ALTER TABLE `hs_klus_activiteit`
  ADD CONSTRAINT `FK_AE073F885A8A0A1` FOREIGN KEY (`activiteit_id`) REFERENCES `hs_activiteiten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_AE073F88BA5374AF` FOREIGN KEY (`klus_id`) REFERENCES `hs_klussen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `hs_klus_dienstverlener`
--
ALTER TABLE `hs_klus_dienstverlener`
  ADD CONSTRAINT `FK_70F96EFBA1690166` FOREIGN KEY (`dienstverlener_id`) REFERENCES `hs_dienstverleners` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_70F96EFBBA5374AF` FOREIGN KEY (`klus_id`) REFERENCES `hs_klussen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `hs_klus_document`
--
ALTER TABLE `hs_klus_document`
  ADD CONSTRAINT `FK_869EC9C5BA5374AF` FOREIGN KEY (`klus_id`) REFERENCES `hs_klussen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_869EC9C5C33F7837` FOREIGN KEY (`document_id`) REFERENCES `hs_documenten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `hs_klus_memo`
--
ALTER TABLE `hs_klus_memo`
  ADD CONSTRAINT `FK_208D08ECB4D32439` FOREIGN KEY (`memo_id`) REFERENCES `hs_memos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_208D08ECBA5374AF` FOREIGN KEY (`klus_id`) REFERENCES `hs_klussen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `hs_klus_vrijwilliger`
--
ALTER TABLE `hs_klus_vrijwilliger`
  ADD CONSTRAINT `FK_6E1EDAA176B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `hs_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_6E1EDAA1BA5374AF` FOREIGN KEY (`klus_id`) REFERENCES `hs_klussen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `hs_memos`
--
ALTER TABLE `hs_memos`
  ADD CONSTRAINT `FK_4048AFA33D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `hs_registraties`
--
ALTER TABLE `hs_registraties`
  ADD CONSTRAINT `FK_62041BC23D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_62041BC25A8A0A1` FOREIGN KEY (`activiteit_id`) REFERENCES `hs_activiteiten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_62041BC26650623E` FOREIGN KEY (`arbeider_id`) REFERENCES `hs_arbeiders` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_62041BC2BA5374AF` FOREIGN KEY (`klus_id`) REFERENCES `hs_klussen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_62041BC2C35D3E` FOREIGN KEY (`factuur_id`) REFERENCES `hs_facturen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `hs_vrijwilligers`
--
ALTER TABLE `hs_vrijwilligers`
  ADD CONSTRAINT `FK_3FE7029676B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `vrijwilligers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_3FE70296BF396750` FOREIGN KEY (`id`) REFERENCES `hs_arbeiders` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `hs_vrijwilliger_document`
--
ALTER TABLE `hs_vrijwilliger_document`
  ADD CONSTRAINT `FK_EAEC53F376B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `hs_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_EAEC53F3C33F7837` FOREIGN KEY (`document_id`) REFERENCES `hs_documenten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `hs_vrijwilliger_memo`
--
ALTER TABLE `hs_vrijwilliger_memo`
  ADD CONSTRAINT `FK_938D702F76B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `hs_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_938D702FB4D32439` FOREIGN KEY (`memo_id`) REFERENCES `hs_memos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `inkomens_intakes`
--
ALTER TABLE `inkomens_intakes`
  ADD CONSTRAINT `FK_66CE79C0733DE450` FOREIGN KEY (`intake_id`) REFERENCES `intakes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_66CE79C0DE7E5B0` FOREIGN KEY (`inkomen_id`) REFERENCES `inkomens` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `inloop_deelnames`
--
ALTER TABLE `inloop_deelnames`
  ADD CONSTRAINT `FK_CFB194F341D2A6EF` FOREIGN KEY (`inloopTraining_id`) REFERENCES `inloop_training` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_CFB194F3B280D297` FOREIGN KEY (`inloop_vrijwilliger_id`) REFERENCES `inloop_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `inloop_documenten`
--
ALTER TABLE `inloop_documenten`
  ADD CONSTRAINT `FK_9DA9ECF43D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `inloop_dossier_statussen`
--
ALTER TABLE `inloop_dossier_statussen`
  ADD CONSTRAINT `FK_12D2B5701994904A` FOREIGN KEY (`land_id`) REFERENCES `landen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_12D2B5703C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_12D2B5703D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_12D2B570D29703A5` FOREIGN KEY (`reden_id`) REFERENCES `inloop_afsluiting_redenen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `inloop_incidenten`
--
ALTER TABLE `inloop_incidenten`
  ADD CONSTRAINT `FK_F85DD4753C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_F85DD4754947630C` FOREIGN KEY (`locatie_id`) REFERENCES `locaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `inloop_intake_zrm`
--
ALTER TABLE `inloop_intake_zrm`
  ADD CONSTRAINT `FK_92197717733DE450` FOREIGN KEY (`intake_id`) REFERENCES `intakes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_92197717C8250F57` FOREIGN KEY (`zrm_id`) REFERENCES `zrm_reports` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `inloop_memos`
--
ALTER TABLE `inloop_memos`
  ADD CONSTRAINT `FK_9ACAE40D3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `inloop_toegang`
--
ALTER TABLE `inloop_toegang`
  ADD CONSTRAINT `FK_C2038C803C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_C2038C804947630C` FOREIGN KEY (`locatie_id`) REFERENCES `locaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `inloop_vrijwilligers`
--
ALTER TABLE `inloop_vrijwilligers`
  ADD CONSTRAINT `FK_561104803D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_561104804947630C` FOREIGN KEY (`locatie_id`) REFERENCES `locaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_5611048076B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `vrijwilligers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_56110480CA12F7AE` FOREIGN KEY (`afsluitreden_id`) REFERENCES `inloop_afsluitredenen_vrijwilligers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_56110480D8471945` FOREIGN KEY (`binnen_via_id`) REFERENCES `inloop_binnen_via` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_56110480EA9C84FE` FOREIGN KEY (`medewerkerLocatie_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `inloop_vrijwilliger_document`
--
ALTER TABLE `inloop_vrijwilliger_document`
  ADD CONSTRAINT `FK_6401B15D76B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `inloop_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_6401B15DC33F7837` FOREIGN KEY (`document_id`) REFERENCES `inloop_documenten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `inloop_vrijwilliger_locatie`
--
ALTER TABLE `inloop_vrijwilliger_locatie`
  ADD CONSTRAINT `FK_A1776D9F4947630C` FOREIGN KEY (`locatie_id`) REFERENCES `locaties` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_A1776D9F76B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `inloop_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `inloop_vrijwilliger_memo`
--
ALTER TABLE `inloop_vrijwilliger_memo`
  ADD CONSTRAINT `FK_94FA9B1976B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `inloop_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_94FA9B19B4D32439` FOREIGN KEY (`memo_id`) REFERENCES `inloop_memos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `instanties_intakes`
--
ALTER TABLE `instanties_intakes`
  ADD CONSTRAINT `FK_9D7459552A1C57EF` FOREIGN KEY (`instantie_id`) REFERENCES `instanties` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_9D745955733DE450` FOREIGN KEY (`intake_id`) REFERENCES `intakes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `intakes`
--
ALTER TABLE `intakes`
  ADD CONSTRAINT `FK_AB70F5AE1C06E73B` FOREIGN KEY (`verslavingsperiode_id`) REFERENCES `verslavingsperiodes` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_AB70F5AE3C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_AB70F5AE3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_AB70F5AE48D0634A` FOREIGN KEY (`verblijfstatus_id`) REFERENCES `verblijfstatussen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_AB70F5AE4B616C78` FOREIGN KEY (`verslavingsfrequentie_id`) REFERENCES `verslavingsfrequenties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_AB70F5AE694ADD79` FOREIGN KEY (`primaireproblematieksfrequentie_id`) REFERENCES `verslavingsfrequenties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_AB70F5AE6DF0864` FOREIGN KEY (`primaireproblematiek_id`) REFERENCES `verslavingen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_AB70F5AE759AEE50` FOREIGN KEY (`locatie3_id`) REFERENCES `locaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_AB70F5AEC7424D3F` FOREIGN KEY (`woonsituatie_id`) REFERENCES `woonsituaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_AB70F5AEC8250F57` FOREIGN KEY (`zrm_id`) REFERENCES `zrm_reports` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_AB70F5AECD268935` FOREIGN KEY (`locatie2_id`) REFERENCES `locaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_AB70F5AED106567A` FOREIGN KEY (`infobaliedoelgroep_id`) REFERENCES `infobaliedoelgroepen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_AB70F5AEDC542336` FOREIGN KEY (`primaireproblematieksperiode_id`) REFERENCES `verslavingsperiodes` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_AB70F5AEDF9326DB` FOREIGN KEY (`locatie1_id`) REFERENCES `locaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_AB70F5AEEB38826A` FOREIGN KEY (`legitimatie_id`) REFERENCES `legitimaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `intakes_primaireproblematieksgebruikswijzen`
--
ALTER TABLE `intakes_primaireproblematieksgebruikswijzen`
  ADD CONSTRAINT `FK_5BE550D2733DE450` FOREIGN KEY (`intake_id`) REFERENCES `intakes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_5BE550D2DB7A239E` FOREIGN KEY (`primaireproblematieksgebruikswijze_id`) REFERENCES `verslavingsgebruikswijzen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `intakes_verslavingen`
--
ALTER TABLE `intakes_verslavingen`
  ADD CONSTRAINT `FK_AD93CFF310258C8` FOREIGN KEY (`verslaving_id`) REFERENCES `verslavingen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_AD93CFF3733DE450` FOREIGN KEY (`intake_id`) REFERENCES `intakes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `intakes_verslavingsgebruikswijzen`
--
ALTER TABLE `intakes_verslavingsgebruikswijzen`
  ADD CONSTRAINT `FK_A2AFE8FE733DE450` FOREIGN KEY (`intake_id`) REFERENCES `intakes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_A2AFE8FE8BDA2F32` FOREIGN KEY (`verslavingsgebruikswijze_id`) REFERENCES `verslavingsgebruikswijzen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `inventarisaties`
--
ALTER TABLE `inventarisaties`
  ADD CONSTRAINT `FK_1343F8F1727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `inventarisaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `inventarisaties_verslagen`
--
ALTER TABLE `inventarisaties_verslagen`
  ADD CONSTRAINT `FK_45A2DE1430AF6145` FOREIGN KEY (`inventarisatie_id`) REFERENCES `inventarisaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_45A2DE14D8291178` FOREIGN KEY (`doorverwijzer_id`) REFERENCES `doorverwijzers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `iz_deelnemers`
--
ALTER TABLE `iz_deelnemers`
  ADD CONSTRAINT `FK_89B5B51C3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_89B5B51C782093FC` FOREIGN KEY (`contact_ontstaan`) REFERENCES `iz_ontstaan_contacten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_89B5B51CF0A6F57E` FOREIGN KEY (`binnengekomen_via`) REFERENCES `iz_via_personen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_89B5B51CFBE387F6` FOREIGN KEY (`iz_afsluiting_id`) REFERENCES `iz_afsluitingen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `iz_deelnemers_documenten`
--
ALTER TABLE `iz_deelnemers_documenten`
  ADD CONSTRAINT `FK_66AE504F55B482C2` FOREIGN KEY (`izdeelnemer_id`) REFERENCES `iz_deelnemers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_66AE504FC33F7837` FOREIGN KEY (`document_id`) REFERENCES `iz_documenten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `iz_deelnemers_iz_intervisiegroepen`
--
ALTER TABLE `iz_deelnemers_iz_intervisiegroepen`
  ADD CONSTRAINT `FK_3A903EEF495B2A54` FOREIGN KEY (`iz_intervisiegroep_id`) REFERENCES `iz_intervisiegroepen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_3A903EEFD3124B3F` FOREIGN KEY (`iz_deelnemer_id`) REFERENCES `iz_deelnemers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `iz_deelnemers_iz_projecten`
--
ALTER TABLE `iz_deelnemers_iz_projecten`
  ADD CONSTRAINT `FK_65A512DB56CEA1A9` FOREIGN KEY (`iz_project_id`) REFERENCES `iz_projecten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `iz_documenten`
--
ALTER TABLE `iz_documenten`
  ADD CONSTRAINT `FK_C7F213503D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `iz_doelstellingen`
--
ALTER TABLE `iz_doelstellingen`
  ADD CONSTRAINT `FK_D76C6C73166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `iz_projecten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_D76C6C73A13D3FD8` FOREIGN KEY (`stadsdeel`) REFERENCES `werkgebieden` (`naam`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `iz_hulpaanbod_hulpvraagsoort`
--
ALTER TABLE `iz_hulpaanbod_hulpvraagsoort`
  ADD CONSTRAINT `FK_D839A990950213F` FOREIGN KEY (`hulpvraagsoort_id`) REFERENCES `iz_hulpvraagsoorten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_D839A990B42008F3` FOREIGN KEY (`hulpaanbod_id`) REFERENCES `iz_koppelingen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `iz_hulpvraag_succesindicator`
--
ALTER TABLE `iz_hulpvraag_succesindicator`
  ADD CONSTRAINT `FK_BDDCA8FA7B2005C` FOREIGN KEY (`succesindicator_id`) REFERENCES `iz_succesindicatoren` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_BDDCA8FA8450D8C` FOREIGN KEY (`hulpvraag_id`) REFERENCES `iz_koppelingen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `iz_hulpvraag_succesindicatorfinancieel`
--
ALTER TABLE `iz_hulpvraag_succesindicatorfinancieel`
  ADD CONSTRAINT `FK_3A3B526F3FEB2492` FOREIGN KEY (`succesindicatorfinancieel_id`) REFERENCES `iz_succesindicatoren` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_3A3B526FA8450D8C` FOREIGN KEY (`hulpvraag_id`) REFERENCES `iz_koppelingen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `iz_hulpvraag_succesindicatorparticipatie`
--
ALTER TABLE `iz_hulpvraag_succesindicatorparticipatie`
  ADD CONSTRAINT `FK_128F913865A9F272` FOREIGN KEY (`succesindicatorparticipatie_id`) REFERENCES `iz_succesindicatoren` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_128F9138A8450D8C` FOREIGN KEY (`hulpvraag_id`) REFERENCES `iz_koppelingen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `iz_hulpvraag_succesindicatorpersoonlijk`
--
ALTER TABLE `iz_hulpvraag_succesindicatorpersoonlijk`
  ADD CONSTRAINT `FK_BC9D7F44A8450D8C` FOREIGN KEY (`hulpvraag_id`) REFERENCES `iz_koppelingen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_BC9D7F44F9892974` FOREIGN KEY (`succesindicatorpersoonlijk_id`) REFERENCES `iz_succesindicatoren` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `iz_intakes`
--
ALTER TABLE `iz_intakes`
  ADD CONSTRAINT `FK_11EFC53D3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_11EFC53DC8250F57` FOREIGN KEY (`zrm_id`) REFERENCES `zrm_reports` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `iz_intake_zrm`
--
ALTER TABLE `iz_intake_zrm`
  ADD CONSTRAINT `FK_C84288B3733DE450` FOREIGN KEY (`intake_id`) REFERENCES `iz_intakes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_C84288B3C8250F57` FOREIGN KEY (`zrm_id`) REFERENCES `zrm_reports` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `iz_intervisiegroepen`
--
ALTER TABLE `iz_intervisiegroepen`
  ADD CONSTRAINT `FK_86CA227E3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `iz_koppelingen`
--
ALTER TABLE `iz_koppelingen`
  ADD CONSTRAINT `FK_24E5FDC2166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `iz_projecten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_24E5FDC23D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_24E5FDC28B2EFA2C` FOREIGN KEY (`iz_koppeling_id`) REFERENCES `iz_koppelingen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_24E5FDC28B6598D9` FOREIGN KEY (`iz_eindekoppeling_id`) REFERENCES `iz_eindekoppelingen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_24E5FDC2950213F` FOREIGN KEY (`hulpvraagsoort_id`) REFERENCES `iz_hulpvraagsoorten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_24E5FDC2C217B9F` FOREIGN KEY (`iz_vraagaanbod_id`) REFERENCES `iz_vraagaanboden` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_24E5FDC2C9788B0A` FOREIGN KEY (`voorkeurGeslacht_id`) REFERENCES `geslachten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_24E5FDC2D3124B3F` FOREIGN KEY (`iz_deelnemer_id`) REFERENCES `iz_deelnemers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `iz_koppeling_doelgroep`
--
ALTER TABLE `iz_koppeling_doelgroep`
  ADD CONSTRAINT `FK_8E6CE05D5C6E6B2` FOREIGN KEY (`koppeling_id`) REFERENCES `iz_koppelingen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_8E6CE05DE5A2DFCE` FOREIGN KEY (`doelgroep_id`) REFERENCES `iz_doelgroepen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `iz_matchingklant_doelgroep`
--
ALTER TABLE `iz_matchingklant_doelgroep`
  ADD CONSTRAINT `FK_9A957F94CC045EED` FOREIGN KEY (`matchingklant_id`) REFERENCES `iz_matching_klanten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_9A957F94E5A2DFCE` FOREIGN KEY (`doelgroep_id`) REFERENCES `iz_doelgroepen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `iz_matchingvrijwilliger_doelgroep`
--
ALTER TABLE `iz_matchingvrijwilliger_doelgroep`
  ADD CONSTRAINT `FK_AA83F9B42B829AB5` FOREIGN KEY (`matchingvrijwilliger_id`) REFERENCES `iz_matching_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_AA83F9B4E5A2DFCE` FOREIGN KEY (`doelgroep_id`) REFERENCES `iz_doelgroepen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `iz_matchingvrijwilliger_hulpvraagsoort`
--
ALTER TABLE `iz_matchingvrijwilliger_hulpvraagsoort`
  ADD CONSTRAINT `FK_11DF7DC02B829AB5` FOREIGN KEY (`matchingvrijwilliger_id`) REFERENCES `iz_matching_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_11DF7DC0950213F` FOREIGN KEY (`hulpvraagsoort_id`) REFERENCES `iz_hulpvraagsoorten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `iz_matching_klanten`
--
ALTER TABLE `iz_matching_klanten`
  ADD CONSTRAINT `FK_A5321A4355FE26E1` FOREIGN KEY (`iz_klant_id`) REFERENCES `iz_deelnemers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_A5321A43950213F` FOREIGN KEY (`hulpvraagsoort_id`) REFERENCES `iz_hulpvraagsoorten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `iz_matching_vrijwilligers`
--
ALTER TABLE `iz_matching_vrijwilligers`
  ADD CONSTRAINT `FK_1CA45FA7C99F99BF` FOREIGN KEY (`iz_vrijwilliger_id`) REFERENCES `iz_deelnemers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `iz_reserveringen`
--
ALTER TABLE `iz_reserveringen`
  ADD CONSTRAINT `FK_B9D71E143D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_B9D71E14A8450D8C` FOREIGN KEY (`hulpvraag_id`) REFERENCES `iz_koppelingen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_B9D71E14B42008F3` FOREIGN KEY (`hulpaanbod_id`) REFERENCES `iz_koppelingen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `iz_verslagen`
--
ALTER TABLE `iz_verslagen`
  ADD CONSTRAINT `FK_570FE99B3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `klanten`
--
ALTER TABLE `klanten`
  ADD CONSTRAINT `FK_F538C5BC1994904A` FOREIGN KEY (`land_id`) REFERENCES `landen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_F538C5BC1C729A47` FOREIGN KEY (`geslacht_id`) REFERENCES `geslachten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_F538C5BC1D103C3F` FOREIGN KEY (`laste_intake_id`) REFERENCES `intakes` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_F538C5BC21E96F13` FOREIGN KEY (`mwBinnenViaOptieKlant_id`) REFERENCES `mw_binnen_via` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_F538C5BC3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_F538C5BC815E1ED` FOREIGN KEY (`laatste_registratie_id`) REFERENCES `registraties` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_F538C5BC8B2671BD` FOREIGN KEY (`huidigeStatus_id`) REFERENCES `inloop_dossier_statussen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_F538C5BC8F30741E` FOREIGN KEY (`first_intake_id`) REFERENCES `intakes` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_F538C5BC9393F8FE` FOREIGN KEY (`partner_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_F538C5BCC1BEA629` FOREIGN KEY (`merged_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_F538C5BCCC5FC3F9` FOREIGN KEY (`huidigeMwStatus_id`) REFERENCES `mw_dossier_statussen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_F538C5BCCECBFEB7` FOREIGN KEY (`nationaliteit_id`) REFERENCES `nationaliteiten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_F538C5BCEB8E119C` FOREIGN KEY (`maatschappelijkWerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_F538C5BCFB02B9C2` FOREIGN KEY (`postcodegebied`) REFERENCES `ggw_gebieden` (`naam`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `werkgebied` FOREIGN KEY (`werkgebied`) REFERENCES `werkgebieden` (`naam`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `klant_taal`
--
ALTER TABLE `klant_taal`
  ADD CONSTRAINT `FK_84884583C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`),
  ADD CONSTRAINT `FK_8488458A41FDDD` FOREIGN KEY (`taal_id`) REFERENCES `talen` (`id`);

--
-- Beperkingen voor tabel `mw_afsluiting_resultaat`
--
ALTER TABLE `mw_afsluiting_resultaat`
  ADD CONSTRAINT `FK_EBA6C1A2B0A9B358` FOREIGN KEY (`resultaat_id`) REFERENCES `mw_resultaten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_EBA6C1A2ECDAD1A9` FOREIGN KEY (`afsluiting_id`) REFERENCES `mw_dossier_statussen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `mw_deelnames`
--
ALTER TABLE `mw_deelnames`
  ADD CONSTRAINT `FK_59035D7541D2A6EF` FOREIGN KEY (`mwTraining_id`) REFERENCES `inloop_training` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_59035D75B280D297` FOREIGN KEY (`inloop_vrijwilliger_id`) REFERENCES `mw_vrijwilligers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `mw_documenten`
--
ALTER TABLE `mw_documenten`
  ADD CONSTRAINT `FK_99E478283C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_99E478283D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `mw_dossier_statussen`
--
ALTER TABLE `mw_dossier_statussen`
  ADD CONSTRAINT `FK_D74783BB1994904A` FOREIGN KEY (`land_id`) REFERENCES `landen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_D74783BB305E4E53` FOREIGN KEY (`binnenViaOptieKlant_id`) REFERENCES `mw_binnen_via` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_D74783BB3C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_D74783BB3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_D74783BB4947630C` FOREIGN KEY (`locatie_id`) REFERENCES `locaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_D74783BBD29703A5` FOREIGN KEY (`reden_id`) REFERENCES `mw_afsluiting_redenen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `mw_vrijwilligers`
--
ALTER TABLE `mw_vrijwilligers`
  ADD CONSTRAINT `FK_CFC2BAE33D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_CFC2BAE34C676E6B` FOREIGN KEY (`binnen_via_id`) REFERENCES `mw_binnen_via` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_CFC2BAE376B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `vrijwilligers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_CFC2BAE3CA12F7AE` FOREIGN KEY (`afsluitreden_id`) REFERENCES `mw_afsluitredenen_vrijwilligers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `mw_vrijwilliger_document`
--
ALTER TABLE `mw_vrijwilliger_document`
  ADD CONSTRAINT `FK_293091876B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `mw_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_2930918C33F7837` FOREIGN KEY (`document_id`) REFERENCES `mw_documenten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `mw_vrijwilliger_locatie`
--
ALTER TABLE `mw_vrijwilliger_locatie`
  ADD CONSTRAINT `FK_35F4E2454947630C` FOREIGN KEY (`locatie_id`) REFERENCES `locaties` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_35F4E24576B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `mw_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `mw_vrijwilliger_memo`
--
ALTER TABLE `mw_vrijwilliger_memo`
  ADD CONSTRAINT `FK_516FADD276B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `mw_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_516FADD2B4D32439` FOREIGN KEY (`memo_id`) REFERENCES `inloop_memos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `oekklant_oekdossierstatus`
--
ALTER TABLE `oekklant_oekdossierstatus`
  ADD CONSTRAINT `FK_1EF9C0A61833A719` FOREIGN KEY (`oekklant_id`) REFERENCES `oek_klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_1EF9C0A6B689C3C1` FOREIGN KEY (`oekdossierstatus_id`) REFERENCES `oek_dossier_statussen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `oekraine_bezoekers`
--
ALTER TABLE `oekraine_bezoekers`
  ADD CONSTRAINT `FK_7027BCA15C217849` FOREIGN KEY (`appKlant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_7027BCA1733DE450` FOREIGN KEY (`intake_id`) REFERENCES `oekraine_intakes` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_7027BCA1EF862509` FOREIGN KEY (`dossierStatus_id`) REFERENCES `oekraine_dossier_statussen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `oekraine_bezoeker_document`
--
ALTER TABLE `oekraine_bezoeker_document`
  ADD CONSTRAINT `FK_DEE5EC468AEEBAAE` FOREIGN KEY (`bezoeker_id`) REFERENCES `oekraine_bezoekers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_DEE5EC46C33F7837` FOREIGN KEY (`document_id`) REFERENCES `oekraine_documenten` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `oekraine_documenten`
--
ALTER TABLE `oekraine_documenten`
  ADD CONSTRAINT `FK_7DB476213D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `oekraine_dossier_statussen`
--
ALTER TABLE `oekraine_dossier_statussen`
  ADD CONSTRAINT `FK_19DBBEBC1994904A` FOREIGN KEY (`land_id`) REFERENCES `landen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_19DBBEBC3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_19DBBEBC8AEEBAAE` FOREIGN KEY (`bezoeker_id`) REFERENCES `oekraine_bezoekers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_19DBBEBCD29703A5` FOREIGN KEY (`reden_id`) REFERENCES `oekraine_afsluiting_redenen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `oekraine_incidenten`
--
ALTER TABLE `oekraine_incidenten`
  ADD CONSTRAINT `FK_18404EA04947630C` FOREIGN KEY (`locatie_id`) REFERENCES `oekraine_locaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_18404EA08AEEBAAE` FOREIGN KEY (`bezoeker_id`) REFERENCES `oekraine_bezoekers` (`id`);

--
-- Beperkingen voor tabel `oekraine_inkomens_intakes`
--
ALTER TABLE `oekraine_inkomens_intakes`
  ADD CONSTRAINT `FK_55D038FE733DE450` FOREIGN KEY (`intake_id`) REFERENCES `oekraine_intakes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_55D038FEDE7E5B0` FOREIGN KEY (`inkomen_id`) REFERENCES `oekraine_inkomens` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `oekraine_intakes`
--
ALTER TABLE `oekraine_intakes`
  ADD CONSTRAINT `FK_C84A94C33D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_C84A94C348D0634A` FOREIGN KEY (`verblijfstatus_id`) REFERENCES `verblijfstatussen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_C84A94C355E45319` FOREIGN KEY (`intakelocatie_id`) REFERENCES `oekraine_locaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_C84A94C385332A14` FOREIGN KEY (`woonlocatie_id`) REFERENCES `oekraine_locaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_C84A94C38AEEBAAE` FOREIGN KEY (`bezoeker_id`) REFERENCES `oekraine_bezoekers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_C84A94C3EB38826A` FOREIGN KEY (`legitimatie_id`) REFERENCES `legitimaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `oekraine_intakes_instanties`
--
ALTER TABLE `oekraine_intakes_instanties`
  ADD CONSTRAINT `FK_86E955E32A1C57EF` FOREIGN KEY (`instantie_id`) REFERENCES `instanties` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_86E955E3733DE450` FOREIGN KEY (`intake_id`) REFERENCES `oekraine_intakes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `oekraine_registraties`
--
ALTER TABLE `oekraine_registraties`
  ADD CONSTRAINT `FK_7E55584C4947630C` FOREIGN KEY (`locatie_id`) REFERENCES `oekraine_locaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_7E55584C8AEEBAAE` FOREIGN KEY (`bezoeker_id`) REFERENCES `oekraine_bezoekers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `oekraine_registraties_recent`
--
ALTER TABLE `oekraine_registraties_recent`
  ADD CONSTRAINT `FK_8C35B27E4947630C` FOREIGN KEY (`locatie_id`) REFERENCES `oekraine_locaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_8C35B27E5CD9765E` FOREIGN KEY (`registratie_id`) REFERENCES `oekraine_registraties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_8C35B27E8AEEBAAE` FOREIGN KEY (`bezoeker_id`) REFERENCES `oekraine_bezoekers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `oekraine_toegang`
--
ALTER TABLE `oekraine_toegang`
  ADD CONSTRAINT `FK_894DD3E64947630C` FOREIGN KEY (`locatie_id`) REFERENCES `oekraine_locaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_894DD3E68AEEBAAE` FOREIGN KEY (`bezoeker_id`) REFERENCES `oekraine_bezoekers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `oekraine_verslagen`
--
ALTER TABLE `oekraine_verslagen`
  ADD CONSTRAINT `FK_C15C9D6F3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_C15C9D6F4947630C` FOREIGN KEY (`locatie_id`) REFERENCES `oekraine_locaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_C15C9D6F8AEEBAAE` FOREIGN KEY (`bezoeker_id`) REFERENCES `oekraine_bezoekers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_C15C9D6FD3899023` FOREIGN KEY (`contactsoort_id`) REFERENCES `contactsoorts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `oek_deelnames`
--
ALTER TABLE `oek_deelnames`
  ADD CONSTRAINT `FK_A6C1F201120845B9` FOREIGN KEY (`oekTraining_id`) REFERENCES `oek_trainingen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_A6C1F2014DF034FD` FOREIGN KEY (`oekDeelnameStatus_id`) REFERENCES `oek_deelname_statussen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_A6C1F201E145C54F` FOREIGN KEY (`oekKlant_id`) REFERENCES `oek_klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `oek_deelname_statussen`
--
ALTER TABLE `oek_deelname_statussen`
  ADD CONSTRAINT `FK_4CBB9BCD6D7A74BD` FOREIGN KEY (`oekDeelname_id`) REFERENCES `oek_deelnames` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `oek_documenten`
--
ALTER TABLE `oek_documenten`
  ADD CONSTRAINT `FK_CE730FA23D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `oek_dossier_statussen`
--
ALTER TABLE `oek_dossier_statussen`
  ADD CONSTRAINT `FK_D8FAC7653D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_D8FAC765D8B4CBDF` FOREIGN KEY (`verwijzing_id`) REFERENCES `oek_verwijzingen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_D8FAC765E145C54F` FOREIGN KEY (`oekKlant_id`) REFERENCES `oek_klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `oek_klanten`
--
ALTER TABLE `oek_klanten`
  ADD CONSTRAINT `FK_A501F8F723473A1F` FOREIGN KEY (`oekDossierStatus_id`) REFERENCES `oek_dossier_statussen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_A501F8F73C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_A501F8F73D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_A501F8F7B99C329A` FOREIGN KEY (`oekAfsluiting_id`) REFERENCES `oek_dossier_statussen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_A501F8F7C45AE93C` FOREIGN KEY (`oekAanmelding_id`) REFERENCES `oek_dossier_statussen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `oek_lidmaatschappen`
--
ALTER TABLE `oek_lidmaatschappen`
  ADD CONSTRAINT `FK_7B0B7DFF43B3F0A5` FOREIGN KEY (`oekGroep_id`) REFERENCES `oek_groepen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_7B0B7DFFE145C54F` FOREIGN KEY (`oekKlant_id`) REFERENCES `oek_klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `oek_memos`
--
ALTER TABLE `oek_memos`
  ADD CONSTRAINT `FK_8F8DED693D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `oek_trainingen`
--
ALTER TABLE `oek_trainingen`
  ADD CONSTRAINT `FK_B0D582D43B3F0A5` FOREIGN KEY (`oekGroep_id`) REFERENCES `oek_groepen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `oek_vrijwilligers`
--
ALTER TABLE `oek_vrijwilligers`
  ADD CONSTRAINT `FK_2D75CD343D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_2D75CD3476B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `vrijwilligers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `oek_vrijwilliger_document`
--
ALTER TABLE `oek_vrijwilliger_document`
  ADD CONSTRAINT `FK_725F2FCA76B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `oek_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_725F2FCAC33F7837` FOREIGN KEY (`document_id`) REFERENCES `oek_documenten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `oek_vrijwilliger_memo`
--
ALTER TABLE `oek_vrijwilliger_memo`
  ADD CONSTRAINT `FK_5ED2E90C76B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `oek_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_5ED2E90CB4D32439` FOREIGN KEY (`memo_id`) REFERENCES `oek_memos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `opmerkingen`
--
ALTER TABLE `opmerkingen`
  ADD CONSTRAINT `FK_C2C23B293C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_C2C23B29BCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorieen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `pfo_clienten`
--
ALTER TABLE `pfo_clienten`
  ADD CONSTRAINT `FK_3C237EDD1C729A47` FOREIGN KEY (`geslacht_id`) REFERENCES `geslachten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_3C237EDD27025694` FOREIGN KEY (`groep`) REFERENCES `pfo_groepen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_3C237EDD3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_3C237EDD46708ED5` FOREIGN KEY (`werkgebied`) REFERENCES `werkgebieden` (`naam`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_3C237EDDC41BE3` FOREIGN KEY (`aard_relatie`) REFERENCES `pfo_aard_relaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_3C237EDDFB02B9C2` FOREIGN KEY (`postcodegebied`) REFERENCES `ggw_gebieden` (`naam`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `pfo_clienten_documenten`
--
ALTER TABLE `pfo_clienten_documenten`
  ADD CONSTRAINT `FK_A14FB5DE19EB6921` FOREIGN KEY (`client_id`) REFERENCES `pfo_clienten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_A14FB5DEC33F7837` FOREIGN KEY (`document_id`) REFERENCES `pfo_documenten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `pfo_clienten_supportgroups`
--
ALTER TABLE `pfo_clienten_supportgroups`
  ADD CONSTRAINT `FK_39F077D963E315A` FOREIGN KEY (`pfo_client_id`) REFERENCES `pfo_clienten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_73EA8C843926A77` FOREIGN KEY (`pfo_supportgroup_client_id`) REFERENCES `pfo_clienten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `pfo_clienten_verslagen`
--
ALTER TABLE `pfo_clienten_verslagen`
  ADD CONSTRAINT `FK_EC92AD411E813AB1` FOREIGN KEY (`pfo_verslag_id`) REFERENCES `pfo_verslagen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_EC92AD4163E315A` FOREIGN KEY (`pfo_client_id`) REFERENCES `pfo_clienten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `pfo_documenten`
--
ALTER TABLE `pfo_documenten`
  ADD CONSTRAINT `FK_4099D0893D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `pfo_verslagen`
--
ALTER TABLE `pfo_verslagen`
  ADD CONSTRAINT `FK_346FE20A3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `postcodes`
--
ALTER TABLE `postcodes`
  ADD CONSTRAINT `FK_71DDD65DA13D3FD8` FOREIGN KEY (`stadsdeel`) REFERENCES `werkgebieden` (`naam`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_71DDD65DFB02B9C2` FOREIGN KEY (`postcodegebied`) REFERENCES `ggw_gebieden` (`naam`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `registraties`
--
ALTER TABLE `registraties`
  ADD CONSTRAINT `FK_FB4123F43C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_FB4123F44947630C` FOREIGN KEY (`locatie_id`) REFERENCES `locaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `registraties_recent`
--
ALTER TABLE `registraties_recent`
  ADD CONSTRAINT `FK_B1AD39F03C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_B1AD39F04947630C` FOREIGN KEY (`locatie_id`) REFERENCES `locaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_B1AD39F05CD9765E` FOREIGN KEY (`registratie_id`) REFERENCES `registraties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `schorsingen`
--
ALTER TABLE `schorsingen`
  ADD CONSTRAINT `FK_9E658EBF3C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_9E658EBF4947630C` FOREIGN KEY (`locatie_id`) REFERENCES `locaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `schorsingen_redenen`
--
ALTER TABLE `schorsingen_redenen`
  ADD CONSTRAINT `FK_BB99D0FFA52077DE` FOREIGN KEY (`schorsing_id`) REFERENCES `schorsingen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_BB99D0FFD29703A5` FOREIGN KEY (`reden_id`) REFERENCES `redenen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `schorsing_locatie`
--
ALTER TABLE `schorsing_locatie`
  ADD CONSTRAINT `FK_52DA67664947630C` FOREIGN KEY (`locatie_id`) REFERENCES `locaties` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_52DA6766A52077DE` FOREIGN KEY (`schorsing_id`) REFERENCES `schorsingen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `scip_beschikbaarheid`
--
ALTER TABLE `scip_beschikbaarheid`
  ADD CONSTRAINT `FK_8897F00FC18FA9D5` FOREIGN KEY (`deelname_id`) REFERENCES `scip_deelnames` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `scip_deelnames`
--
ALTER TABLE `scip_deelnames`
  ADD CONSTRAINT `FK_FC67EC2F166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `scip_projecten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_FC67EC2F5DFA57A1` FOREIGN KEY (`deelnemer_id`) REFERENCES `scip_deelnemers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `scip_deelnemers`
--
ALTER TABLE `scip_deelnemers`
  ADD CONSTRAINT `FK_5CB8023F3C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_5CB8023F3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `scip_deelnemer_document`
--
ALTER TABLE `scip_deelnemer_document`
  ADD CONSTRAINT `FK_7CA418EB5DFA57A1` FOREIGN KEY (`deelnemer_id`) REFERENCES `scip_deelnemers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_7CA418EBC33F7837` FOREIGN KEY (`document_id`) REFERENCES `scip_documenten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `scip_deelnemer_label`
--
ALTER TABLE `scip_deelnemer_label`
  ADD CONSTRAINT `FK_2AF2CF8933B92F39` FOREIGN KEY (`label_id`) REFERENCES `scip_labels` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_2AF2CF895DFA57A1` FOREIGN KEY (`deelnemer_id`) REFERENCES `scip_deelnemers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `scip_documenten`
--
ALTER TABLE `scip_documenten`
  ADD CONSTRAINT `FK_12FFA4733D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `scip_toegangsrechten`
--
ALTER TABLE `scip_toegangsrechten`
  ADD CONSTRAINT `FK_25CBD12E3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `scip_toegangsrecht_project`
--
ALTER TABLE `scip_toegangsrecht_project`
  ADD CONSTRAINT `FK_DA60099D166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `scip_projecten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_DA60099DAC60ED89` FOREIGN KEY (`toegangsrecht_id`) REFERENCES `scip_toegangsrechten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `scip_verslagen`
--
ALTER TABLE `scip_verslagen`
  ADD CONSTRAINT `FK_3AF92EB93D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_3AF92EB95DFA57A1` FOREIGN KEY (`deelnemer_id`) REFERENCES `scip_deelnemers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `scip_werkdoelen`
--
ALTER TABLE `scip_werkdoelen`
  ADD CONSTRAINT `FK_6433FE803D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_6433FE805DFA57A1` FOREIGN KEY (`deelnemer_id`) REFERENCES `scip_deelnemers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `tw_coordinatoren`
--
ALTER TABLE `tw_coordinatoren`
  ADD CONSTRAINT `FK_62BCCDB53D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `tw_deelnames`
--
ALTER TABLE `tw_deelnames`
  ADD CONSTRAINT `FK_B0B3FDE1459F3233` FOREIGN KEY (`mwTraining_id`) REFERENCES `tw_training` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_B0B3FDE19D1883DD` FOREIGN KEY (`tw_vrijwilliger_id`) REFERENCES `tw_vrijwilligers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_C8B28A18629A95E` FOREIGN KEY (`tw_vrijwilliger_id`) REFERENCES `tw_vrijwilligers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `tw_deelnemers`
--
ALTER TABLE `tw_deelnemers`
  ADD CONSTRAINT `FK_20283999166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `tw_projecten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_202839991AAF70BD` FOREIGN KEY (`werk_id`) REFERENCES `tw_werk` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_202839993D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_20283999C0B11400` FOREIGN KEY (`pandeigenaar_id`) REFERENCES `tw_woningbouwcorporaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_20283999C8250F57` FOREIGN KEY (`zrm_id`) REFERENCES `zrm_reports` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_20283999C8F722F4` FOREIGN KEY (`huurBudget_id`) REFERENCES `tw_huurbudget` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_20283999D93D6B19` FOREIGN KEY (`duurThuisloos_id`) REFERENCES `tw_duurthuisloos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_20283999ECDAD1A9` FOREIGN KEY (`afsluiting_id`) REFERENCES `tw_afsluitingen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_3A1E7F772BB8C0FB` FOREIGN KEY (`ambulantOndersteuner_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_E431725625BA5A47` FOREIGN KEY (`softdrugs_id`) REFERENCES `tw_softdrugs` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_E43172562657F54F` FOREIGN KEY (`intakeStatus_id`) REFERENCES `tw_intakestatus` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_E431725635972C83` FOREIGN KEY (`huisgenoot_id`) REFERENCES `tw_deelnemers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_E4317256452FF74C` FOREIGN KEY (`inschrijvingWoningnet_id`) REFERENCES `tw_inschrijvingwoningnet` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_E431725650B81711` FOREIGN KEY (`aanvullingInkomen_id`) REFERENCES `tw_aanvullinginkomen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_E43172565357D7EE` FOREIGN KEY (`alcohol_id`) REFERENCES `tw_alcohol` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_E43172565721A070` FOREIGN KEY (`pandeigenaar_id`) REFERENCES `tw_pandeigenaar` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_E43172565C217849` FOREIGN KEY (`appKlant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_E431725687B1EE3E` FOREIGN KEY (`roken_id`) REFERENCES `tw_roken` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_E431725693EEEFEC` FOREIGN KEY (`huisdieren_id`) REFERENCES `tw_huisdieren` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_E4317256A90F3026` FOREIGN KEY (`bindingRegio_id`) REFERENCES `tw_regio` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_E4317256B4770027` FOREIGN KEY (`shortlist_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_E4317256BD67529F` FOREIGN KEY (`kwijtschelding_id`) REFERENCES `tw_kwijtschelding` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_E4317256D00EBD14` FOREIGN KEY (`moScreening_id`) REFERENCES `tw_moscreening` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_E4317256D6E2DB5B` FOREIGN KEY (`traplopen_id`) REFERENCES `tw_traplopen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_E4317256DE7E5B0` FOREIGN KEY (`inkomen_id`) REFERENCES `tw_inkomen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_E4317256F190A78A` FOREIGN KEY (`ritme_id`) REFERENCES `tw_ritme` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_E4317256F9E2779E` FOREIGN KEY (`dagbesteding_id`) REFERENCES `tw_dagbesteding` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `tw_deelnemer_document`
--
ALTER TABLE `tw_deelnemer_document`
  ADD CONSTRAINT `FK_9BA61CC55DFA57A1` FOREIGN KEY (`deelnemer_id`) REFERENCES `tw_deelnemers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_9BA61CC5C33F7837` FOREIGN KEY (`document_id`) REFERENCES `tw_superdocumenten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `tw_deelnemer_verslag`
--
ALTER TABLE `tw_deelnemer_verslag`
  ADD CONSTRAINT `FK_F8F75D6A5DFA57A1` FOREIGN KEY (`deelnemer_id`) REFERENCES `tw_deelnemers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_F8F75D6AD949475D` FOREIGN KEY (`verslag_id`) REFERENCES `tw_superverslagen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `tw_huuraanbiedingen`
--
ALTER TABLE `tw_huuraanbiedingen`
  ADD CONSTRAINT `FK_FA204F87166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `tw_projecten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_FA204F873D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_FA204F87522CF24B` FOREIGN KEY (`vormvanovereenkomst_id`) REFERENCES `tw_vormvanovereenkomst` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_FA204F877E18485D` FOREIGN KEY (`verhuurder_id`) REFERENCES `tw_deelnemers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_FA204F87ECDAD1A9` FOREIGN KEY (`afsluiting_id`) REFERENCES `tw_afsluitingen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `tw_huuraanbod_verslag`
--
ALTER TABLE `tw_huuraanbod_verslag`
  ADD CONSTRAINT `FK_9B2DE75B656E2280` FOREIGN KEY (`huuraanbod_id`) REFERENCES `tw_huuraanbiedingen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_9B2DE75BD949475D` FOREIGN KEY (`verslag_id`) REFERENCES `tw_superverslagen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `tw_huurders_tw_projecten`
--
ALTER TABLE `tw_huurders_tw_projecten`
  ADD CONSTRAINT `FK_48E405357776076A` FOREIGN KEY (`tw_huurder_id`) REFERENCES `tw_deelnemers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_48E40535FF532D2C` FOREIGN KEY (`tw_project_id`) REFERENCES `tw_projecten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `tw_huurovereenkomsten`
--
ALTER TABLE `tw_huurovereenkomsten`
  ADD CONSTRAINT `FK_453FF4A63D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_453FF4A645DA3BB7` FOREIGN KEY (`huurverzoek_id`) REFERENCES `tw_huurverzoeken` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_453FF4A6656E2280` FOREIGN KEY (`huuraanbod_id`) REFERENCES `tw_huuraanbiedingen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_453FF4A6ECDAD1A9` FOREIGN KEY (`afsluiting_id`) REFERENCES `tw_afsluitingen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_98F99DF67F442D` FOREIGN KEY (`huurovereenkomstType_id`) REFERENCES `tw_huurovereenkomst_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `tw_huurovereenkomst_document`
--
ALTER TABLE `tw_huurovereenkomst_document`
  ADD CONSTRAINT `FK_7B9A48A7870B85BC` FOREIGN KEY (`huurovereenkomst_id`) REFERENCES `tw_huurovereenkomsten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_7B9A48A7C33F7837` FOREIGN KEY (`document_id`) REFERENCES `tw_superdocumenten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_C5DF83BD870B85BC` FOREIGN KEY (`huurovereenkomst_id`) REFERENCES `tw_huurovereenkomsten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_C5DF83BDC33F7837` FOREIGN KEY (`document_id`) REFERENCES `tw_superdocumenten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `tw_huurovereenkomst_findocument`
--
ALTER TABLE `tw_huurovereenkomst_findocument`
  ADD CONSTRAINT `FK_B9C41948870B85BC` FOREIGN KEY (`huurovereenkomst_id`) REFERENCES `tw_huurovereenkomsten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_B9C41948C33F7837` FOREIGN KEY (`document_id`) REFERENCES `tw_superdocumenten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `tw_huurovereenkomst_finverslag`
--
ALTER TABLE `tw_huurovereenkomst_finverslag`
  ADD CONSTRAINT `FK_98E469DC870B85BC` FOREIGN KEY (`huurovereenkomst_id`) REFERENCES `tw_huurovereenkomsten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_98E469DCD949475D` FOREIGN KEY (`verslag_id`) REFERENCES `tw_superverslagen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `tw_huurovereenkomst_verslag`
--
ALTER TABLE `tw_huurovereenkomst_verslag`
  ADD CONSTRAINT `FK_5F912B12870B85BC` FOREIGN KEY (`huurovereenkomst_id`) REFERENCES `tw_huurovereenkomsten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_5F912B12D949475D` FOREIGN KEY (`verslag_id`) REFERENCES `tw_superverslagen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `tw_huurverzoeken`
--
ALTER TABLE `tw_huurverzoeken`
  ADD CONSTRAINT `FK_588F4E963D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_588F4E969E4835DA` FOREIGN KEY (`klant_id`) REFERENCES `tw_deelnemers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_588F4E96ECDAD1A9` FOREIGN KEY (`afsluiting_id`) REFERENCES `tw_afsluitingen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_B59AA1213C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `tw_deelnemers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `tw_huurverzoeken_tw_projecten`
--
ALTER TABLE `tw_huurverzoeken_tw_projecten`
  ADD CONSTRAINT `FK_CDF6EEBCE7540572` FOREIGN KEY (`tw_huurverzoek_id`) REFERENCES `tw_huurverzoeken` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_CDF6EEBCFF532D2C` FOREIGN KEY (`tw_project_id`) REFERENCES `tw_projecten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `tw_huurverzoek_verslag`
--
ALTER TABLE `tw_huurverzoek_verslag`
  ADD CONSTRAINT `FK_46CB48C145DA3BB7` FOREIGN KEY (`huurverzoek_id`) REFERENCES `tw_huurverzoeken` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_46CB48C1D949475D` FOREIGN KEY (`verslag_id`) REFERENCES `tw_superverslagen` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `tw_intakes`
--
ALTER TABLE `tw_intakes`
  ADD CONSTRAINT `FK_3A1E7F773D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_3A1E7F775DFA57A1` FOREIGN KEY (`deelnemer_id`) REFERENCES `tw_deelnemers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `tw_pandeigenaar`
--
ALTER TABLE `tw_pandeigenaar`
  ADD CONSTRAINT `FK_E43172565721A654` FOREIGN KEY (`pandeigenaartype_id`) REFERENCES `tw_pandeigenaartype` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `tw_superdocumenten`
--
ALTER TABLE `tw_superdocumenten`
  ADD CONSTRAINT `FK_6E6F9FD53D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `tw_superverslagen`
--
ALTER TABLE `tw_superverslagen`
  ADD CONSTRAINT `FK_762D3F773D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `tw_vrijwilligers`
--
ALTER TABLE `tw_vrijwilligers`
  ADD CONSTRAINT `FK_198B65143D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_198B65144C676E6B` FOREIGN KEY (`binnen_via_id`) REFERENCES `tw_binnen_via` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_198B651476B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `vrijwilligers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_198B6514CA12F7AE` FOREIGN KEY (`afsluitreden_id`) REFERENCES `tw_afsluitredenen_vrijwilligers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `tw_vrijwilliger_document`
--
ALTER TABLE `tw_vrijwilliger_document`
  ADD CONSTRAINT `FK_8454B6BA76B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `tw_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_8454B6BAC33F7837` FOREIGN KEY (`document_id`) REFERENCES `tw_superdocumenten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `tw_vrijwilliger_locatie`
--
ALTER TABLE `tw_vrijwilliger_locatie`
  ADD CONSTRAINT `FK_219994954947630C` FOREIGN KEY (`locatie_id`) REFERENCES `tw_locaties` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_2199949576B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `tw_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `tw_vrijwilliger_memo`
--
ALTER TABLE `tw_vrijwilliger_memo`
  ADD CONSTRAINT `FK_8200726C76B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `tw_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_8200726CB4D32439` FOREIGN KEY (`memo_id`) REFERENCES `inloop_memos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `uhk_deelnemers`
--
ALTER TABLE `uhk_deelnemers`
  ADD CONSTRAINT `FK_739D2F673C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_739D2F673D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_739D2F6775E6B4CA` FOREIGN KEY (`aanmelder_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `uhk_deelnemer_document`
--
ALTER TABLE `uhk_deelnemer_document`
  ADD CONSTRAINT `FK_40FD84945DFA57A1` FOREIGN KEY (`deelnemer_id`) REFERENCES `uhk_deelnemers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_40FD8494C33F7837` FOREIGN KEY (`document_id`) REFERENCES `uhk_documenten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `uhk_documenten`
--
ALTER TABLE `uhk_documenten`
  ADD CONSTRAINT `FK_3DDA892B3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `uhk_verslagen`
--
ALTER TABLE `uhk_verslagen`
  ADD CONSTRAINT `FK_70A8F1853D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_70A8F1855DFA57A1` FOREIGN KEY (`deelnemer_id`) REFERENCES `uhk_deelnemers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `verslagen`
--
ALTER TABLE `verslagen`
  ADD CONSTRAINT `FK_2BBABA713C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_2BBABA713D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_2BBABA714947630C` FOREIGN KEY (`locatie_id`) REFERENCES `locaties` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_2BBABA71D3899023` FOREIGN KEY (`contactsoort_id`) REFERENCES `contactsoorts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `verslaginfos`
--
ALTER TABLE `verslaginfos`
  ADD CONSTRAINT `FK_3D2FCA831B81E585` FOREIGN KEY (`casemanager_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_3D2FCA831EC41507` FOREIGN KEY (`trajectbegeleider_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_3D2FCA833C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `villa_deelnames`
--
ALTER TABLE `villa_deelnames`
  ADD CONSTRAINT `FK_BA87C7882BAC9748` FOREIGN KEY (`villa_vrijwilliger_id`) REFERENCES `villa_vrijwilligers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_BA87C788459F3233` FOREIGN KEY (`mwTraining_id`) REFERENCES `villa_training` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `villa_vrijwilligers`
--
ALTER TABLE `villa_vrijwilligers`
  ADD CONSTRAINT `FK_2DA6A7323D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_2DA6A7324C676E6B` FOREIGN KEY (`binnen_via_id`) REFERENCES `villa_binnen_via` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_2DA6A73276B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `vrijwilligers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_2DA6A732CA12F7AE` FOREIGN KEY (`afsluitreden_id`) REFERENCES `villa_afsluitredenen_vrijwilligers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_2DA6A732EA9C84FE` FOREIGN KEY (`medewerkerLocatie_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `villa_vrijwilliger_document`
--
ALTER TABLE `villa_vrijwilliger_document`
  ADD CONSTRAINT `FK_F5C5B6776B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `villa_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_F5C5B67C33F7837` FOREIGN KEY (`document_id`) REFERENCES `villa_documenten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `villa_vrijwilliger_memo`
--
ALTER TABLE `villa_vrijwilliger_memo`
  ADD CONSTRAINT `FK_94EC6EFA76B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `villa_vrijwilligers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_94EC6EFAB4D32439` FOREIGN KEY (`memo_id`) REFERENCES `inloop_memos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `vrijwilligers`
--
ALTER TABLE `vrijwilligers`
  ADD CONSTRAINT `FK_F0C4D2371994904A` FOREIGN KEY (`land_id`) REFERENCES `landen` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_F0C4D2371C729A47` FOREIGN KEY (`geslacht_id`) REFERENCES `geslachten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_F0C4D2373D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_F0C4D23746708ED5` FOREIGN KEY (`werkgebied`) REFERENCES `werkgebieden` (`naam`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_F0C4D237CECBFEB7` FOREIGN KEY (`nationaliteit_id`) REFERENCES `nationaliteiten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_F0C4D237FB02B9C2` FOREIGN KEY (`postcodegebied`) REFERENCES `ggw_gebieden` (`naam`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `zrm_reports`
--
ALTER TABLE `zrm_reports`
  ADD CONSTRAINT `FK_C8EF119C3C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_C8EF119C3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `zrm_v2_reports`
--
ALTER TABLE `zrm_v2_reports`
  ADD CONSTRAINT `FK_751519083C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
