-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: database
-- Gegenereerd op: 23 okt 2018 om 07:44
-- Serverversie: 5.6.39
-- PHP-versie: 5.6.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `ecd`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `amoc_landen`
--

CREATE TABLE `amoc_landen` (
  `id` int(11) NOT NULL,
  `land_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `user_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `awbz_hoofdaannemers`
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
-- Tabelstructuur voor tabel `awbz_indicaties`
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
-- Tabelstructuur voor tabel `awbz_intakes`
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
-- Tabelstructuur voor tabel `awbz_intakes_primaireproblematieksgebruikswijzen`
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
-- Tabelstructuur voor tabel `awbz_intakes_verslavingen`
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
-- Tabelstructuur voor tabel `awbz_intakes_verslavingsgebruikswijzen`
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
-- Tabelstructuur voor tabel `backup_iz_deelnemers`
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
-- Tabelstructuur voor tabel `back_on_tracks`
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
-- Tabelstructuur voor tabel `bedrijfitems`
--

CREATE TABLE `bedrijfitems` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `bedrijfsector_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `bedrijfsectoren`
--

CREATE TABLE `bedrijfsectoren` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `bot_koppelingen`
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
-- Tabelstructuur voor tabel `bot_verslagen`
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
-- Tabelstructuur voor tabel `categorieen`
--

CREATE TABLE `categorieen` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_behandelaars`
--

CREATE TABLE `clip_behandelaars` (
  `id` int(11) NOT NULL,
  `medewerker_id` int(11) DEFAULT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_clienten`
--

CREATE TABLE `clip_clienten` (
  `id` int(11) NOT NULL,
  `viacategorie_id` int(11) DEFAULT NULL,
  `behandelaar_id` int(11) NOT NULL,
  `aanmelddatum` date NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `geslacht_id` int(11) DEFAULT NULL,
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
  `regipro_client_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_client_document`
--

CREATE TABLE `clip_client_document` (
  `client_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_communicatiekanalen`
--

CREATE TABLE `clip_communicatiekanalen` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_contactmomenten`
--

CREATE TABLE `clip_contactmomenten` (
  `id` int(11) NOT NULL,
  `vraag_id` int(11) NOT NULL,
  `behandelaar_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `opmerking` longtext,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_documenten`
--

CREATE TABLE `clip_documenten` (
  `id` int(11) NOT NULL,
  `behandelaar_id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_hulpvragersoorten`
--

CREATE TABLE `clip_hulpvragersoorten` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_leeftijdscategorieen`
--

CREATE TABLE `clip_leeftijdscategorieen` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_viacategorieen`
--

CREATE TABLE `clip_viacategorieen` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_vraagsoorten`
--

CREATE TABLE `clip_vraagsoorten` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_vraag_document`
--

CREATE TABLE `clip_vraag_document` (
  `vraag_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `clip_vragen`
--

CREATE TABLE `clip_vragen` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `soort_id` int(11) NOT NULL,
  `hulpvrager_id` int(11) DEFAULT NULL,
  `communicatiekanaal_id` int(11) DEFAULT NULL,
  `leeftijdscategorie_id` int(11) DEFAULT NULL,
  `behandelaar_id` int(11) NOT NULL,
  `omschrijving` varchar(255) NOT NULL,
  `startdatum` date NOT NULL,
  `afsluitdatum` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contactjournals`
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
-- Tabelstructuur voor tabel `contactsoorts`
--

CREATE TABLE `contactsoorts` (
  `id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_afsluitingen`
--

CREATE TABLE `dagbesteding_afsluitingen` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `discr` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_contactpersonen`
--

CREATE TABLE `dagbesteding_contactpersonen` (
  `id` int(11) NOT NULL,
  `deelnemer_id` int(11) DEFAULT NULL,
  `soort` varchar(255) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `telefoon` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `opmerking` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_dagdelen`
--

CREATE TABLE `dagbesteding_dagdelen` (
  `id` int(11) NOT NULL,
  `traject_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `dagdeel` varchar(255) NOT NULL,
  `aanwezigheid` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_deelnemers`
--

CREATE TABLE `dagbesteding_deelnemers` (
  `id` int(11) NOT NULL,
  `afsluiting_id` int(11) DEFAULT NULL,
  `klant_id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `risDossiernummer` varchar(255) DEFAULT NULL,
  `aanmelddatum` date NOT NULL,
  `afsluitdatum` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_deelnemer_document`
--

CREATE TABLE `dagbesteding_deelnemer_document` (
  `deelnemer_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_deelnemer_verslag`
--

CREATE TABLE `dagbesteding_deelnemer_verslag` (
  `deelnemer_id` int(11) NOT NULL,
  `verslag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_documenten`
--

CREATE TABLE `dagbesteding_documenten` (
  `id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_locaties`
--

CREATE TABLE `dagbesteding_locaties` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_projecten`
--

CREATE TABLE `dagbesteding_projecten` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_rapportages`
--

CREATE TABLE `dagbesteding_rapportages` (
  `id` int(11) NOT NULL,
  `traject_id` int(11) DEFAULT NULL,
  `medewerker_id` int(11) DEFAULT NULL,
  `datum` date NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_rapportage_document`
--

CREATE TABLE `dagbesteding_rapportage_document` (
  `rapportage_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_resultaatgebieden`
--

CREATE TABLE `dagbesteding_resultaatgebieden` (
  `id` int(11) NOT NULL,
  `traject_id` int(11) DEFAULT NULL,
  `soort_id` int(11) DEFAULT NULL,
  `startdatum` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_resultaatgebiedsoorten`
--

CREATE TABLE `dagbesteding_resultaatgebiedsoorten` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_trajectbegeleiders`
--

CREATE TABLE `dagbesteding_trajectbegeleiders` (
  `id` int(11) NOT NULL,
  `medewerker_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_trajecten`
--

CREATE TABLE `dagbesteding_trajecten` (
  `id` int(11) NOT NULL,
  `deelnemer_id` int(11) NOT NULL,
  `soort_id` int(11) NOT NULL,
  `resultaatgebied_id` int(11) DEFAULT NULL,
  `begeleider_id` int(11) NOT NULL,
  `afsluiting_id` int(11) DEFAULT NULL,
  `startdatum` date NOT NULL,
  `einddatum` date NOT NULL,
  `afsluitdatum` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_trajectsoorten`
--

CREATE TABLE `dagbesteding_trajectsoorten` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_traject_document`
--

CREATE TABLE `dagbesteding_traject_document` (
  `traject_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_traject_locatie`
--

CREATE TABLE `dagbesteding_traject_locatie` (
  `traject_id` int(11) NOT NULL,
  `locatie_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_traject_project`
--

CREATE TABLE `dagbesteding_traject_project` (
  `traject_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_traject_verslag`
--

CREATE TABLE `dagbesteding_traject_verslag` (
  `traject_id` int(11) NOT NULL,
  `verslag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dagbesteding_verslagen`
--

CREATE TABLE `dagbesteding_verslagen` (
  `id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `datum` datetime NOT NULL,
  `opmerking` longtext,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `doorverwijzers`
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
-- Stand-in structuur voor view `eropuit_klanten`
-- (Zie onder voor de actuele view)
--
CREATE TABLE `eropuit_klanten` (
`id` int(11)
,`klant_id` int(11)
,`inschrijfdatum` date
,`uitschrijfdatum` date
,`uitschrijfreden_id` int(11)
,`communicatie_email` tinyint(1)
,`communicatie_post` tinyint(1)
,`communicatie_telefoon` tinyint(1)
,`created` datetime
,`modified` datetime
);

-- --------------------------------------------------------

--
-- Stand-in structuur voor view `eropuit_vrijwilligers`
-- (Zie onder voor de actuele view)
--
CREATE TABLE `eropuit_vrijwilligers` (
`id` int(11)
,`vrijwilliger_id` int(11)
,`inschrijfdatum` date
,`uitschrijfdatum` date
,`uitschrijfreden_id` int(11)
,`communicatie_email` tinyint(1)
,`communicatie_post` tinyint(1)
,`communicatie_telefoon` tinyint(1)
,`created` datetime
,`modified` datetime
);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ext_log_entries`
--

CREATE TABLE `ext_log_entries` (
  `id` int(11) NOT NULL,
  `action` varchar(8) NOT NULL,
  `logged_at` datetime NOT NULL,
  `object_id` varchar(64) DEFAULT NULL,
  `object_class` varchar(255) NOT NULL,
  `version` int(11) NOT NULL,
  `data` longtext COMMENT '(DC2Type:array)',
  `username` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ga_documenten`
--

CREATE TABLE `ga_documenten` (
  `id` int(11) NOT NULL,
  `vrijwilliger_id` int(11) DEFAULT NULL,
  `medewerker_id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ga_gaklantintake_zrm`
--

CREATE TABLE `ga_gaklantintake_zrm` (
  `gaklantintake_id` int(11) NOT NULL,
  `zrm_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `gd27`
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
-- Tabelstructuur voor tabel `geslachten`
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
-- Tabelstructuur voor tabel `ggw_gebieden`
--

CREATE TABLE `ggw_gebieden` (
  `naam` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `groepsactiviteiten`
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
-- Tabelstructuur voor tabel `groepsactiviteiten_afsluitingen`
--

CREATE TABLE `groepsactiviteiten_afsluitingen` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `groepsactiviteiten_groepen`
--

CREATE TABLE `groepsactiviteiten_groepen` (
  `id` int(11) NOT NULL,
  `naam` varchar(100) NOT NULL,
  `startdatum` date DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `werkgebied` varchar(20) DEFAULT NULL,
  `activiteiten_registreren` tinyint(1) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `groepsactiviteiten_groepen_klanten`
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
-- Tabelstructuur voor tabel `groepsactiviteiten_groepen_vrijwilligers`
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
-- Tabelstructuur voor tabel `groepsactiviteiten_intakes`
--

CREATE TABLE `groepsactiviteiten_intakes` (
  `id` int(11) NOT NULL,
  `model` varchar(255) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `medewerker_id` int(11) DEFAULT NULL,
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
-- Tabelstructuur voor tabel `groepsactiviteiten_klanten`
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
-- Tabelstructuur voor tabel `groepsactiviteiten_redenen`
--

CREATE TABLE `groepsactiviteiten_redenen` (
  `id` int(11) NOT NULL,
  `naam` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `groepsactiviteiten_verslagen`
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
-- Tabelstructuur voor tabel `groepsactiviteiten_vrijwilligers`
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
-- Tabelstructuur voor tabel `hi5_answers`
--

CREATE TABLE `hi5_answers` (
  `id` int(11) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `hi5_question_id` int(11) NOT NULL,
  `hi5_answer_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hi5_answer_types`
--

CREATE TABLE `hi5_answer_types` (
  `id` int(11) NOT NULL,
  `answer_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hi5_evaluaties`
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
-- Tabelstructuur voor tabel `hi5_evaluaties_hi5_evaluatie_questions`
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
-- Tabelstructuur voor tabel `hi5_evaluatie_paragraphs`
--

CREATE TABLE `hi5_evaluatie_paragraphs` (
  `id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hi5_evaluatie_questions`
--

CREATE TABLE `hi5_evaluatie_questions` (
  `id` int(11) NOT NULL,
  `hi5_evaluatie_paragraph_id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hi5_intakes`
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
-- Tabelstructuur voor tabel `hi5_intakes_answers`
--

CREATE TABLE `hi5_intakes_answers` (
  `id` int(11) NOT NULL,
  `hi5_intake_id` int(11) NOT NULL,
  `hi5_answer_id` int(11) NOT NULL,
  `hi5_answer_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hi5_intakes_inkomens`
--

CREATE TABLE `hi5_intakes_inkomens` (
  `id` int(11) NOT NULL,
  `hi5_intake_id` int(11) NOT NULL,
  `inkomen_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hi5_intakes_instanties`
--

CREATE TABLE `hi5_intakes_instanties` (
  `id` int(11) NOT NULL,
  `hi5_intake_id` int(11) NOT NULL,
  `instantie_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hi5_intakes_primaireproblematieksgebruikswijzen`
--

CREATE TABLE `hi5_intakes_primaireproblematieksgebruikswijzen` (
  `id` int(11) NOT NULL,
  `hi5_intake_id` int(11) NOT NULL,
  `primaireproblematieksgebruikswijze_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hi5_intakes_verslavingen`
--

CREATE TABLE `hi5_intakes_verslavingen` (
  `id` int(11) NOT NULL,
  `hi5_intake_id` int(11) NOT NULL,
  `verslaving_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hi5_intakes_verslavingsgebruikswijzen`
--

CREATE TABLE `hi5_intakes_verslavingsgebruikswijzen` (
  `id` int(11) NOT NULL,
  `hi5_intake_id` int(11) NOT NULL,
  `verslavingsgebruikswijze_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hi5_questions`
--

CREATE TABLE `hi5_questions` (
  `id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hoofdaannemers`
--

CREATE TABLE `hoofdaannemers` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_activiteiten`
--

CREATE TABLE `hs_activiteiten` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_arbeiders`
--

CREATE TABLE `hs_arbeiders` (
  `id` int(11) NOT NULL,
  `inschrijving` date NOT NULL,
  `uitschrijving` date DEFAULT NULL,
  `rijbewijs` tinyint(1) DEFAULT NULL,
  `dtype` varchar(255) NOT NULL,
  `actief` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_betalingen`
--

CREATE TABLE `hs_betalingen` (
  `id` int(11) NOT NULL,
  `factuur_id` int(11) NOT NULL,
  `referentie` varchar(255) DEFAULT NULL,
  `datum` date NOT NULL,
  `info` longtext,
  `bedrag` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_declaraties`
--

CREATE TABLE `hs_declaraties` (
  `id` int(11) NOT NULL,
  `klus_id` int(11) DEFAULT NULL,
  `factuur_id` int(11) DEFAULT NULL,
  `medewerker_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `info` longtext NOT NULL,
  `bedrag` double NOT NULL,
  `declaratieCategorie_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_declaratie_categorieen`
--

CREATE TABLE `hs_declaratie_categorieen` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_declaratie_document`
--

CREATE TABLE `hs_declaratie_document` (
  `declaratie_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_dienstverleners`
--

CREATE TABLE `hs_dienstverleners` (
  `id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `hulpverlener_naam` varchar(255) DEFAULT NULL,
  `hulpverlener_organisatie` varchar(255) DEFAULT NULL,
  `hulpverlener_telefoon` varchar(255) DEFAULT NULL,
  `hulpverlener_email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_dienstverlener_document`
--

CREATE TABLE `hs_dienstverlener_document` (
  `dienstverlener_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_dienstverlener_memo`
--

CREATE TABLE `hs_dienstverlener_memo` (
  `dienstverlener_id` int(11) NOT NULL,
  `memo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_documenten`
--

CREATE TABLE `hs_documenten` (
  `id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_facturen`
--

CREATE TABLE `hs_facturen` (
  `id` int(11) NOT NULL,
  `klant_id` int(11) DEFAULT NULL,
  `nummer` varchar(255) NOT NULL,
  `datum` date NOT NULL,
  `betreft` varchar(255) NOT NULL,
  `bedrag` decimal(10,2) NOT NULL,
  `locked` tinyint(1) NOT NULL,
  `class` varchar(255) NOT NULL,
  `opmerking` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_factuur_klus`
--

CREATE TABLE `hs_factuur_klus` (
  `factuur_id` int(11) NOT NULL,
  `klus_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_herinneringen`
--

CREATE TABLE `hs_herinneringen` (
  `id` int(11) NOT NULL,
  `factuur_id` int(11) DEFAULT NULL,
  `datum` date NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_klanten`
--

CREATE TABLE `hs_klanten` (
  `id` int(11) NOT NULL,
  `geslacht_id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `erp_id` int(11) DEFAULT NULL,
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
  `afwijkendFactuuradres` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_klant_document`
--

CREATE TABLE `hs_klant_document` (
  `klant_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_klant_memo`
--

CREATE TABLE `hs_klant_memo` (
  `klant_id` int(11) NOT NULL,
  `memo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_klussen`
--

CREATE TABLE `hs_klussen` (
  `id` int(11) NOT NULL,
  `klant_id` int(11) DEFAULT NULL,
  `medewerker_id` int(11) NOT NULL,
  `startdatum` date NOT NULL,
  `einddatum` date DEFAULT NULL,
  `onHold` tinyint(1) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_klus_activiteit`
--

CREATE TABLE `hs_klus_activiteit` (
  `klus_id` int(11) NOT NULL,
  `activiteit_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_klus_dienstverlener`
--

CREATE TABLE `hs_klus_dienstverlener` (
  `klus_id` int(11) NOT NULL,
  `dienstverlener_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_klus_document`
--

CREATE TABLE `hs_klus_document` (
  `klus_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_klus_memo`
--

CREATE TABLE `hs_klus_memo` (
  `klus_id` int(11) NOT NULL,
  `memo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_klus_vrijwilliger`
--

CREATE TABLE `hs_klus_vrijwilliger` (
  `klus_id` int(11) NOT NULL,
  `vrijwilliger_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_memos`
--

CREATE TABLE `hs_memos` (
  `id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `datum` datetime NOT NULL,
  `memo` longtext NOT NULL,
  `intake` tinyint(1) NOT NULL,
  `onderwerp` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_registraties`
--

CREATE TABLE `hs_registraties` (
  `id` int(11) NOT NULL,
  `klus_id` int(11) DEFAULT NULL,
  `factuur_id` int(11) DEFAULT NULL,
  `arbeider_id` int(11) NOT NULL,
  `activiteit_id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `start` time NOT NULL,
  `eind` time NOT NULL,
  `reiskosten` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_vrijwilligers`
--

CREATE TABLE `hs_vrijwilligers` (
  `id` int(11) NOT NULL,
  `vrijwilliger_id` int(11) NOT NULL,
  `hulpverlener_naam` varchar(255) DEFAULT NULL,
  `hulpverlener_organisatie` varchar(255) DEFAULT NULL,
  `hulpverlener_telefoon` varchar(255) DEFAULT NULL,
  `hulpverlener_email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_vrijwilliger_document`
--

CREATE TABLE `hs_vrijwilliger_document` (
  `vrijwilliger_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hs_vrijwilliger_memo`
--

CREATE TABLE `hs_vrijwilliger_memo` (
  `vrijwilliger_id` int(11) NOT NULL,
  `memo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `i18n`
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
-- Tabelstructuur voor tabel `infobaliedoelgroepen`
--

CREATE TABLE `infobaliedoelgroepen` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inkomens`
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
-- Tabelstructuur voor tabel `inkomens_awbz_intakes`
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
-- Tabelstructuur voor tabel `inkomens_intakes`
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
-- Tabelstructuur voor tabel `inloop_afsluiting_redenen`
--

CREATE TABLE `inloop_afsluiting_redenen` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `actief` tinyint(1) NOT NULL,
  `land` tinyint(1) NOT NULL,
  `gewicht` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inloop_binnen_via`
--

CREATE TABLE `inloop_binnen_via` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inloop_documenten`
--

CREATE TABLE `inloop_documenten` (
  `id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inloop_dossier_statussen`
--

CREATE TABLE `inloop_dossier_statussen` (
  `id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `medewerker_id` int(11) DEFAULT NULL,
  `reden_id` int(11) DEFAULT NULL,
  `land_id` int(11) DEFAULT NULL,
  `datum` date NOT NULL,
  `toelichting` longtext,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `class` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inloop_intake_zrm`
--

CREATE TABLE `inloop_intake_zrm` (
  `intake_id` int(11) NOT NULL,
  `zrm_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inloop_memos`
--

CREATE TABLE `inloop_memos` (
  `id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `datum` datetime NOT NULL,
  `onderwerp` varchar(255) NOT NULL,
  `memo` longtext NOT NULL,
  `intake` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inloop_vrijwilligers`
--

CREATE TABLE `inloop_vrijwilligers` (
  `id` int(11) NOT NULL,
  `vrijwilliger_id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `aanmelddatum` date NOT NULL,
  `binnen_via_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inloop_vrijwilliger_document`
--

CREATE TABLE `inloop_vrijwilliger_document` (
  `vrijwilliger_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inloop_vrijwilliger_locatie`
--

CREATE TABLE `inloop_vrijwilliger_locatie` (
  `vrijwilliger_id` int(11) NOT NULL,
  `locatie_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `inloop_vrijwilliger_memo`
--

CREATE TABLE `inloop_vrijwilliger_memo` (
  `vrijwilliger_id` int(11) NOT NULL,
  `memo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `instanties`
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
-- Tabelstructuur voor tabel `instanties_awbz_intakes`
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
-- Tabelstructuur voor tabel `instanties_intakes`
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
-- Tabelstructuur voor tabel `intakes`
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
  `toegang_inloophuis` tinyint(1) DEFAULT NULL,
  `amoc_toegang_tot` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `intakes_primaireproblematieksgebruikswijzen`
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
-- Tabelstructuur voor tabel `intakes_verslavingen`
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
-- Tabelstructuur voor tabel `intakes_verslavingsgebruikswijzen`
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
-- Tabelstructuur voor tabel `inventarisaties`
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
-- Tabelstructuur voor tabel `inventarisaties_verslagen`
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
-- Tabelstructuur voor tabel `iz_afsluitingen`
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
-- Tabelstructuur voor tabel `iz_deelnemers`
--

CREATE TABLE `iz_deelnemers` (
  `id` int(11) NOT NULL,
  `model` varchar(50) NOT NULL,
  `foreign_key` int(11) DEFAULT NULL,
  `datum_aanmelding` date DEFAULT NULL,
  `binnengekomen_via` int(11) DEFAULT NULL,
  `organisatie` varchar(100) DEFAULT NULL,
  `naam_aanmelder` varchar(100) DEFAULT NULL,
  `email_aanmelder` varchar(100) DEFAULT NULL,
  `telefoon_aanmelder` varchar(100) DEFAULT NULL,
  `notitie` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `datumafsluiting` date DEFAULT NULL,
  `iz_afsluiting_id` int(11) DEFAULT NULL,
  `contact_ontstaan` int(11) DEFAULT NULL,
  `deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_deelnemers_documenten`
--

CREATE TABLE `iz_deelnemers_documenten` (
  `izdeelnemer_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_deelnemers_iz_intervisiegroepen`
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
-- Tabelstructuur voor tabel `iz_deelnemers_iz_projecten`
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
-- Tabelstructuur voor tabel `iz_documenten`
--

CREATE TABLE `iz_documenten` (
  `id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_doelgroepen`
--

CREATE TABLE `iz_doelgroepen` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_doelstellingen`
--

CREATE TABLE `iz_doelstellingen` (
  `id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `jaar` int(11) NOT NULL,
  `aantal` int(11) NOT NULL,
  `stadsdeel` varchar(255) DEFAULT NULL,
  `categorie` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_eindekoppelingen`
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
-- Tabelstructuur voor tabel `iz_hulpaanbod_hulpvraagsoort`
--

CREATE TABLE `iz_hulpaanbod_hulpvraagsoort` (
  `hulpaanbod_id` int(11) NOT NULL,
  `hulpvraagsoort_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_hulpvraagsoorten`
--

CREATE TABLE `iz_hulpvraagsoorten` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `toelichting` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_hulpvraag_succesindicatorfinancieel`
--

CREATE TABLE `iz_hulpvraag_succesindicatorfinancieel` (
  `hulpvraag_id` int(11) NOT NULL,
  `succesindicatorfinancieel_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_hulpvraag_succesindicatorparticipatie`
--

CREATE TABLE `iz_hulpvraag_succesindicatorparticipatie` (
  `hulpvraag_id` int(11) NOT NULL,
  `succesindicatorparticipatie_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_hulpvraag_succesindicatorpersoonlijk`
--

CREATE TABLE `iz_hulpvraag_succesindicatorpersoonlijk` (
  `hulpvraag_id` int(11) NOT NULL,
  `succesindicatorpersoonlijk_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_intakes`
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
-- Tabelstructuur voor tabel `iz_intake_zrm`
--

CREATE TABLE `iz_intake_zrm` (
  `intake_id` int(11) NOT NULL,
  `zrm_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_intervisiegroepen`
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
-- Tabelstructuur voor tabel `iz_koppelingen`
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
  `iz_koppeling_id` int(11) DEFAULT NULL,
  `discr` varchar(255) NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `info` longtext,
  `dagdeel` varchar(255) DEFAULT NULL,
  `spreekt_nederlands` tinyint(1) DEFAULT '1',
  `hulpvraagsoort_id` int(11) DEFAULT NULL,
  `voorkeur_voor_nederlands` tinyint(1) DEFAULT NULL,
  `voorkeurGeslacht_id` int(11) DEFAULT NULL,
  `coachend` tinyint(1) DEFAULT NULL,
  `expat` tinyint(1) DEFAULT NULL,
  `tussenevaluatiedatum` datetime DEFAULT NULL,
  `eindevaluatiedatum` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_koppeling_doelgroep`
--

CREATE TABLE `iz_koppeling_doelgroep` (
  `koppeling_id` int(11) NOT NULL,
  `doelgroep_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_matchingklant_doelgroep`
--

CREATE TABLE `iz_matchingklant_doelgroep` (
  `matchingklant_id` int(11) NOT NULL,
  `doelgroep_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_matchingvrijwilliger_doelgroep`
--

CREATE TABLE `iz_matchingvrijwilliger_doelgroep` (
  `matchingvrijwilliger_id` int(11) NOT NULL,
  `doelgroep_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_matchingvrijwilliger_hulpvraagsoort`
--

CREATE TABLE `iz_matchingvrijwilliger_hulpvraagsoort` (
  `matchingvrijwilliger_id` int(11) NOT NULL,
  `hulpvraagsoort_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_matching_klanten`
--

CREATE TABLE `iz_matching_klanten` (
  `id` int(11) NOT NULL,
  `iz_klant_id` int(11) DEFAULT NULL,
  `hulpvraagsoort_id` int(11) DEFAULT NULL,
  `info` varchar(255) NOT NULL,
  `spreekt_nederlands` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_matching_vrijwilligers`
--

CREATE TABLE `iz_matching_vrijwilligers` (
  `id` int(11) NOT NULL,
  `iz_vrijwilliger_id` int(11) DEFAULT NULL,
  `info` varchar(255) NOT NULL,
  `voorkeur_voor_nederlands` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_ontstaan_contacten`
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
-- Tabelstructuur voor tabel `iz_projecten`
--

CREATE TABLE `iz_projecten` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `startdatum` date DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `heeft_koppelingen` tinyint(1) DEFAULT NULL,
  `prestatie_strategy` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_reserveringen`
--

CREATE TABLE `iz_reserveringen` (
  `id` int(11) NOT NULL,
  `hulpvraag_id` int(11) NOT NULL,
  `hulpaanbod_id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `startdatum` date NOT NULL,
  `einddatum` date NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_succesindicatoren`
--

CREATE TABLE `iz_succesindicatoren` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `discr` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `iz_verslagen`
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
-- Tabelstructuur voor tabel `iz_via_personen`
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
-- Tabelstructuur voor tabel `iz_vraagaanboden`
--

CREATE TABLE `iz_vraagaanboden` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `klanten`
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
  `BSN` varchar(255) DEFAULT NULL,
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
  `postcodegebied` varchar(50) DEFAULT NULL,
  `huidigeStatus_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `klantinventarisaties`
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
-- Tabelstructuur voor tabel `landen`
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
-- Tabelstructuur voor tabel `legitimaties`
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
-- Tabelstructuur voor tabel `locaties`
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
  `maatschappelijkwerk` tinyint(1) DEFAULT '0',
  `tbc_check` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `locatie_tijden`
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
-- Tabelstructuur voor tabel `logs`
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
-- Tabelstructuur voor tabel `medewerkers`
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
  `uidnumber` varchar(255) NOT NULL,
  `active` int(4) NOT NULL DEFAULT '1',
  `groups` longtext COMMENT '(DC2Type:json_array)',
  `ldap_groups` longtext COMMENT '(DC2Type:json_array)',
  `roles` longtext NOT NULL COMMENT '(DC2Type:json_array)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
('20181002093231');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mw_documenten`
--

CREATE TABLE `mw_documenten` (
  `id` int(11) NOT NULL,
  `klant_id` int(11) DEFAULT NULL,
  `medewerker_id` int(11) DEFAULT NULL,
  `naam` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `nationaliteiten`
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
-- Tabelstructuur voor tabel `notities`
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
-- Tabelstructuur voor tabel `odp_afsluitingen`
--

CREATE TABLE `odp_afsluitingen` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `discr` varchar(255) NOT NULL,
  `tonen` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `odp_coordinatoren`
--

CREATE TABLE `odp_coordinatoren` (
  `id` int(11) NOT NULL,
  `medewerker_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `odp_deelnemers`
--

CREATE TABLE `odp_deelnemers` (
  `id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `woningbouwcorporatie_id` int(11) DEFAULT NULL,
  `aanmelddatum` date NOT NULL,
  `afsluitdatum` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `model` varchar(255) NOT NULL,
  `afsluiting_id` int(11) DEFAULT NULL,
  `woningbouwcorporatie_toelichting` varchar(255) DEFAULT NULL,
  `rekeningnummer` varchar(255) DEFAULT NULL,
  `wpi` tinyint(1) NOT NULL DEFAULT '0',
  `klantmanager` varchar(255) DEFAULT NULL,
  `automatischeIncasso` tinyint(1) DEFAULT NULL,
  `ksgw` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `odp_deelnemer_document`
--

CREATE TABLE `odp_deelnemer_document` (
  `deelnemer_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `odp_deelnemer_verslag`
--

CREATE TABLE `odp_deelnemer_verslag` (
  `deelnemer_id` int(11) NOT NULL,
  `verslag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `odp_documenten`
--

CREATE TABLE `odp_documenten` (
  `id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `datum` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `odp_huuraanbiedingen`
--

CREATE TABLE `odp_huuraanbiedingen` (
  `id` int(11) NOT NULL,
  `verhuurder_id` int(11) DEFAULT NULL,
  `medewerker_id` int(11) NOT NULL,
  `startdatum` date NOT NULL,
  `afsluitdatum` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `afsluiting_id` int(11) DEFAULT NULL,
  `datumToestemmingAangevraagd` date DEFAULT NULL,
  `datumToestemmingToegekend` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `odp_huuraanbod_verslag`
--

CREATE TABLE `odp_huuraanbod_verslag` (
  `huuraanbod_id` int(11) NOT NULL,
  `verslag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `odp_huurovereenkomsten`
--

CREATE TABLE `odp_huurovereenkomsten` (
  `id` int(11) NOT NULL,
  `huuraanbod_id` int(11) DEFAULT NULL,
  `huurverzoek_id` int(11) DEFAULT NULL,
  `medewerker_id` int(11) NOT NULL,
  `startdatum` date NOT NULL,
  `opzegdatum` date DEFAULT NULL,
  `einddatum` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `afsluiting_id` int(11) DEFAULT NULL,
  `afsluitdatum` date DEFAULT NULL,
  `vorm` varchar(50) DEFAULT NULL,
  `opzegbrief_verstuurd` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `odp_huurovereenkomst_document`
--

CREATE TABLE `odp_huurovereenkomst_document` (
  `huurovereenkomst_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `odp_huurovereenkomst_verslag`
--

CREATE TABLE `odp_huurovereenkomst_verslag` (
  `huurovereenkomst_id` int(11) NOT NULL,
  `verslag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `odp_huurverzoeken`
--

CREATE TABLE `odp_huurverzoeken` (
  `id` int(11) NOT NULL,
  `huurder_id` int(11) DEFAULT NULL,
  `medewerker_id` int(11) NOT NULL,
  `startdatum` date NOT NULL,
  `afsluitdatum` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `afsluiting_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `odp_huurverzoek_verslag`
--

CREATE TABLE `odp_huurverzoek_verslag` (
  `huurverzoek_id` int(11) NOT NULL,
  `verslag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `odp_intakes`
--

CREATE TABLE `odp_intakes` (
  `id` int(11) NOT NULL,
  `deelnemer_id` int(11) DEFAULT NULL,
  `medewerker_id` int(11) NOT NULL,
  `intake_datum` date NOT NULL,
  `gezin_met_kinderen` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `odp_verslagen`
--

CREATE TABLE `odp_verslagen` (
  `id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `datum` datetime NOT NULL,
  `opmerking` longtext,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `odp_woningbouwcorporaties`
--

CREATE TABLE `odp_woningbouwcorporaties` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oekklant_oekdossierstatus`
--

CREATE TABLE `oekklant_oekdossierstatus` (
  `oekklant_id` int(11) NOT NULL,
  `oekdossierstatus_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_deelnames`
--

CREATE TABLE `oek_deelnames` (
  `id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `oekTraining_id` int(11) NOT NULL,
  `oekKlant_id` int(11) NOT NULL,
  `oekDeelnameStatus_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_deelname_statussen`
--

CREATE TABLE `oek_deelname_statussen` (
  `id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `status` varchar(255) NOT NULL,
  `oekDeelname_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_documenten`
--

CREATE TABLE `oek_documenten` (
  `id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_dossier_statussen`
--

CREATE TABLE `oek_dossier_statussen` (
  `id` int(11) NOT NULL,
  `verwijzing_id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `oekKlant_id` int(11) NOT NULL,
  `class` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_groepen`
--

CREATE TABLE `oek_groepen` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `aantal_bijeenkomsten` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_klanten`
--

CREATE TABLE `oek_klanten` (
  `id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `voedselbankklant` tinyint(1) NOT NULL,
  `opmerking` longtext,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `oekDossierStatus_id` int(11) DEFAULT NULL,
  `oekAanmelding_id` int(11) DEFAULT NULL,
  `oekAfsluiting_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_lidmaatschappen`
--

CREATE TABLE `oek_lidmaatschappen` (
  `id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `oekGroep_id` int(11) NOT NULL,
  `oekKlant_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_memos`
--

CREATE TABLE `oek_memos` (
  `id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `datum` datetime NOT NULL,
  `onderwerp` varchar(255) NOT NULL,
  `memo` longtext NOT NULL,
  `intake` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_trainingen`
--

CREATE TABLE `oek_trainingen` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `startdatum` date NOT NULL,
  `starttijd` time NOT NULL,
  `einddatum` date DEFAULT NULL,
  `locatie` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `oekGroep_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_verwijzingen`
--

CREATE TABLE `oek_verwijzingen` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `actief` tinyint(1) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `class` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_vrijwilligers`
--

CREATE TABLE `oek_vrijwilligers` (
  `id` int(11) NOT NULL,
  `vrijwilliger_id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_vrijwilliger_document`
--

CREATE TABLE `oek_vrijwilliger_document` (
  `vrijwilliger_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `oek_vrijwilliger_memo`
--

CREATE TABLE `oek_vrijwilliger_memo` (
  `vrijwilliger_id` int(11) NOT NULL,
  `memo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `opmerkingen`
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
-- Tabelstructuur voor tabel `pfo_aard_relaties`
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
-- Tabelstructuur voor tabel `pfo_clienten`
--

CREATE TABLE `pfo_clienten` (
  `id` int(11) NOT NULL,
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
-- Tabelstructuur voor tabel `pfo_clienten_documenten`
--

CREATE TABLE `pfo_clienten_documenten` (
  `document_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pfo_clienten_supportgroups`
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
-- Tabelstructuur voor tabel `pfo_clienten_verslagen`
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
-- Tabelstructuur voor tabel `pfo_documenten`
--

CREATE TABLE `pfo_documenten` (
  `id` int(11) NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pfo_groepen`
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
-- Tabelstructuur voor tabel `pfo_verslagen`
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
-- Tabelstructuur voor tabel `postcodegebieden`
--

CREATE TABLE `postcodegebieden` (
  `postcodegebied` varchar(255) NOT NULL,
  `id` int(11) NOT NULL,
  `van` int(11) NOT NULL,
  `tot` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `postcodes`
--

CREATE TABLE `postcodes` (
  `postcode` varchar(255) NOT NULL,
  `stadsdeel` varchar(255) NOT NULL,
  `postcodegebied` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `queue_tasks`
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
-- Tabelstructuur voor tabel `redenen`
--

CREATE TABLE `redenen` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `registraties`
--

CREATE TABLE `registraties` (
  `id` int(11) NOT NULL,
  `locatie_id` int(11) DEFAULT NULL,
  `klant_id` int(11) DEFAULT NULL,
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
  `closed` tinyint(1) DEFAULT NULL,
  `binnen_date` date DEFAULT NULL,
  `veegploeg` tinyint(1) NOT NULL,
  `klant_id_before_constraint` int(11) DEFAULT NULL,
  `locatie_id_before_constraint` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stand-in structuur voor view `registraties_recent`
-- (Zie onder voor de actuele view)
--
CREATE TABLE `registraties_recent` (
`klant_id` int(11)
,`locatie_id` int(11)
,`max_buiten` datetime
);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `schorsingen`
--

CREATE TABLE `schorsingen` (
  `id` int(11) NOT NULL,
  `datum_van` date NOT NULL,
  `datum_tot` date NOT NULL,
  `locatie_id` int(11) DEFAULT NULL,
  `klant_id` int(11) NOT NULL,
  `remark` longtext,
  `gezien` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `overig_reden` varchar(255) DEFAULT NULL,
  `aangifte` tinyint(1) NOT NULL DEFAULT '0',
  `nazorg` tinyint(1) NOT NULL DEFAULT '0',
  `aggressie_tegen_medewerker` int(4) DEFAULT NULL,
  `aggressie_doelwit` varchar(255) DEFAULT NULL,
  `agressie` tinyint(1) NOT NULL DEFAULT '0',
  `aggressie_tegen_medewerker2` int(4) DEFAULT NULL,
  `aggressie_doelwit2` varchar(255) DEFAULT NULL,
  `aggressie_tegen_medewerker3` int(4) DEFAULT NULL,
  `aggressie_doelwit3` varchar(255) DEFAULT NULL,
  `aggressie_tegen_medewerker4` int(4) DEFAULT NULL,
  `aggressie_doelwit4` varchar(255) DEFAULT NULL,
  `locatiehoofd` varchar(100) DEFAULT NULL,
  `bijzonderheden` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `schorsingen_redenen`
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
-- Tabelstructuur voor tabel `schorsing_locatie`
--

CREATE TABLE `schorsing_locatie` (
  `schorsing_id` int(11) NOT NULL,
  `locatie_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `stadsdelen`
--

CREATE TABLE `stadsdelen` (
  `postcode` varchar(255) NOT NULL,
  `stadsdeel` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tmp_avgduration`
--

CREATE TABLE `tmp_avgduration` (
  `label` varchar(64) DEFAULT NULL,
  `range_start` int(11) DEFAULT NULL,
  `range_end` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tmp_err`
--

CREATE TABLE `tmp_err` (
  `koppeling` int(11) NOT NULL DEFAULT '0',
  `deelnemer` int(11) NOT NULL DEFAULT '0',
  `vrijwilliger` int(11) DEFAULT '0',
  `klant` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tmp_izid`
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
-- Tabelstructuur voor tabel `tmp_open_days`
--

CREATE TABLE `tmp_open_days` (
  `locatie_id` int(11) NOT NULL,
  `open_day` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tmp_registraties`
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
-- Tabelstructuur voor tabel `tmp_rm`
--

CREATE TABLE `tmp_rm` (
  `id` int(11) NOT NULL DEFAULT '0'
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
  `klant_id` int(11) NOT NULL,
  `locaties` longblob,
  `binnen` longblob
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tmp_visitors`
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
-- Tabelstructuur voor tabel `tmp_visits`
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
-- Tabelstructuur voor tabel `trajecten`
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
-- Tabelstructuur voor tabel `verblijfstatussen`
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
-- Tabelstructuur voor tabel `verslagen`
--

CREATE TABLE `verslagen` (
  `id` int(11) NOT NULL,
  `klant_id` int(11) DEFAULT NULL,
  `medewerker_id` int(11) DEFAULT NULL,
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
-- Tabelstructuur voor tabel `verslaginfos`
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
-- Tabelstructuur voor tabel `verslavingen`
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
-- Tabelstructuur voor tabel `verslavingsfrequenties`
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
-- Tabelstructuur voor tabel `verslavingsgebruikswijzen`
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
-- Tabelstructuur voor tabel `verslavingsperiodes`
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
-- Tabelstructuur voor tabel `vrijwilligers`
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
  `BSN` varchar(255) DEFAULT NULL,
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
  `postcodegebied` varchar(50) DEFAULT NULL,
  `vog_aangevraagd` tinyint(1) NOT NULL DEFAULT '0',
  `vog_aanwezig` tinyint(1) NOT NULL DEFAULT '0',
  `overeenkomst_aanwezig` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `werkgebieden`
--

CREATE TABLE `werkgebieden` (
  `naam` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `woonsituaties`
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
-- Tabelstructuur voor tabel `zrm_reports`
--

CREATE TABLE `zrm_reports` (
  `id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `model` varchar(50) DEFAULT NULL,
  `foreign_key` int(11) DEFAULT NULL,
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
  `modified` datetime DEFAULT NULL,
  `discr` varchar(5) NOT NULL,
  `financien` int(11) DEFAULT NULL,
  `werk_opleiding` int(11) DEFAULT NULL,
  `tijdsbesteding` int(11) DEFAULT NULL,
  `huiselijke_relaties` int(11) DEFAULT NULL,
  `lichamelijke_gezondheid` int(11) DEFAULT NULL,
  `middelengebruik` int(11) DEFAULT NULL,
  `basale_adl` int(11) DEFAULT NULL,
  `instrumentele_adl` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `zrm_settings`
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

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `zrm_v2_reports`
--

CREATE TABLE `zrm_v2_reports` (
  `id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `model` varchar(255) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `request_module` varchar(255) NOT NULL,
  `financien` int(11) DEFAULT NULL,
  `werk_opleiding` int(11) DEFAULT NULL,
  `tijdsbesteding` int(11) DEFAULT NULL,
  `huisvesting` int(11) DEFAULT NULL,
  `huiselijke_relaties` int(11) DEFAULT NULL,
  `geestelijke_gezondheid` int(11) DEFAULT NULL,
  `lichamelijke_gezondheid` int(11) DEFAULT NULL,
  `middelengebruik` int(11) DEFAULT NULL,
  `basale_adl` int(11) DEFAULT NULL,
  `instrumentele_adl` int(11) DEFAULT NULL,
  `sociaal_netwerk` int(11) DEFAULT NULL,
  `maatschappelijke_participatie` int(11) DEFAULT NULL,
  `justitie` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `zrm_v2_settings`
--

CREATE TABLE `zrm_v2_settings` (
  `id` int(11) NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structuur voor de view `eropuit_klanten`
--
DROP TABLE IF EXISTS `eropuit_klanten`;

CREATE ALGORITHM=UNDEFINED DEFINER=`ecd`@`%` SQL SECURITY DEFINER VIEW `eropuit_klanten`  AS  select `lidmaatschap`.`id` AS `id`,`lidmaatschap`.`klant_id` AS `klant_id`,`lidmaatschap`.`startdatum` AS `inschrijfdatum`,`lidmaatschap`.`einddatum` AS `uitschrijfdatum`,`lidmaatschap`.`groepsactiviteiten_reden_id` AS `uitschrijfreden_id`,`lidmaatschap`.`communicatie_email` AS `communicatie_email`,`lidmaatschap`.`communicatie_post` AS `communicatie_post`,`lidmaatschap`.`communicatie_telefoon` AS `communicatie_telefoon`,`lidmaatschap`.`created` AS `created`,`lidmaatschap`.`modified` AS `modified` from (`groepsactiviteiten_groepen_klanten` `lidmaatschap` join `klanten` on(((`klanten`.`id` = `lidmaatschap`.`klant_id`) and (`klanten`.`disabled` = 0)))) where ((`lidmaatschap`.`groepsactiviteiten_groep_id` = 19) and `lidmaatschap`.`id` in (select max(`groepsactiviteiten_groepen_klanten`.`id`) from `groepsactiviteiten_groepen_klanten` where (`groepsactiviteiten_groepen_klanten`.`groepsactiviteiten_groep_id` = 19) group by `groepsactiviteiten_groepen_klanten`.`klant_id`)) ;

-- --------------------------------------------------------

--
-- Structuur voor de view `eropuit_vrijwilligers`
--
DROP TABLE IF EXISTS `eropuit_vrijwilligers`;

CREATE ALGORITHM=UNDEFINED DEFINER=`ecd`@`%` SQL SECURITY DEFINER VIEW `eropuit_vrijwilligers`  AS  select `lidmaatschap`.`id` AS `id`,`lidmaatschap`.`vrijwilliger_id` AS `vrijwilliger_id`,`lidmaatschap`.`startdatum` AS `inschrijfdatum`,`lidmaatschap`.`einddatum` AS `uitschrijfdatum`,`lidmaatschap`.`groepsactiviteiten_reden_id` AS `uitschrijfreden_id`,`lidmaatschap`.`communicatie_email` AS `communicatie_email`,`lidmaatschap`.`communicatie_post` AS `communicatie_post`,`lidmaatschap`.`communicatie_telefoon` AS `communicatie_telefoon`,`lidmaatschap`.`created` AS `created`,`lidmaatschap`.`modified` AS `modified` from (`groepsactiviteiten_groepen_vrijwilligers` `lidmaatschap` join `vrijwilligers` on(((`vrijwilligers`.`id` = `lidmaatschap`.`vrijwilliger_id`) and (`vrijwilligers`.`disabled` = 0)))) where ((`lidmaatschap`.`groepsactiviteiten_groep_id` = 19) and `lidmaatschap`.`id` in (select max(`groepsactiviteiten_groepen_vrijwilligers`.`id`) from `groepsactiviteiten_groepen_vrijwilligers` where (`groepsactiviteiten_groepen_vrijwilligers`.`groepsactiviteiten_groep_id` = 19) group by `groepsactiviteiten_groepen_vrijwilligers`.`vrijwilliger_id`)) ;

-- --------------------------------------------------------

--
-- Structuur voor de view `registraties_recent`
--
DROP TABLE IF EXISTS `registraties_recent`;

CREATE ALGORITHM=UNDEFINED DEFINER=`ecd`@`%` SQL SECURITY DEFINER VIEW `registraties_recent`  AS  select `registraties`.`klant_id` AS `klant_id`,`registraties`.`locatie_id` AS `locatie_id`,max(`registraties`.`buiten`) AS `max_buiten` from `registraties` where ((`registraties`.`closed` = 1) and (`registraties`.`binnen` > (now() + interval -(15) day))) group by `registraties`.`klant_id`,`registraties`.`locatie_id` ;

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
-- Indexen voor tabel `categorieen`
--
ALTER TABLE `categorieen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `clip_behandelaars`
--
ALTER TABLE `clip_behandelaars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_4B016D223D707F64` (`medewerker_id`);

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
  ADD PRIMARY KEY (`id`);

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
-- Indexen voor tabel `dagbesteding_trajectbegeleiders`
--
ALTER TABLE `dagbesteding_trajectbegeleiders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_EA2465533D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `dagbesteding_trajecten`
--
ALTER TABLE `dagbesteding_trajecten`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_670A67F21BE6904` (`resultaatgebied_id`),
  ADD KEY `IDX_670A67F25DFA57A1` (`deelnemer_id`),
  ADD KEY `IDX_670A67F23DEE50DF` (`soort_id`),
  ADD KEY `IDX_670A67F239F86A1D` (`begeleider_id`),
  ADD KEY `IDX_670A67F2ECDAD1A9` (`afsluiting_id`);

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
-- Indexen voor tabel `doorverwijzers`
--
ALTER TABLE `doorverwijzers`
  ADD PRIMARY KEY (`id`);

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
-- Indexen voor tabel `ga_documenten`
--
ALTER TABLE `ga_documenten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_409E561276B43BDC` (`vrijwilliger_id`),
  ADD KEY `IDX_409E56123D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `ga_gaklantintake_zrm`
--
ALTER TABLE `ga_gaklantintake_zrm`
  ADD PRIMARY KEY (`gaklantintake_id`,`zrm_id`),
  ADD UNIQUE KEY `UNIQ_D6805671C8250F57` (`zrm_id`),
  ADD KEY `IDX_D68056715FA93F88` (`gaklantintake_id`);

--
-- Indexen voor tabel `gd27`
--
ALTER TABLE `gd27`
  ADD PRIMARY KEY (`idd`);

--
-- Indexen voor tabel `geslachten`
--
ALTER TABLE `geslachten`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `inloop_afsluiting_redenen`
--
ALTER TABLE `inloop_afsluiting_redenen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `inloop_binnen_via`
--
ALTER TABLE `inloop_binnen_via`
  ADD PRIMARY KEY (`id`);

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
-- Indexen voor tabel `inloop_vrijwilligers`
--
ALTER TABLE `inloop_vrijwilligers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_5611048076B43BDC` (`vrijwilliger_id`),
  ADD KEY `IDX_561104803D707F64` (`medewerker_id`),
  ADD KEY `IDX_56110480D8471945` (`binnen_via_id`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `intakes`
--
ALTER TABLE `intakes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_intakes_woonsituatie_id` (`woonsituatie_id`),
  ADD KEY `IDX_AB70F5AE3D707F64` (`medewerker_id`),
  ADD KEY `IDX_AB70F5AE3C427B2F` (`klant_id`),
  ADD KEY `IDX_AB70F5AE48D0634A` (`verblijfstatus_id`);

--
-- Indexen voor tabel `intakes_primaireproblematieksgebruikswijzen`
--
ALTER TABLE `intakes_primaireproblematieksgebruikswijzen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `intakes_verslavingen`
--
ALTER TABLE `intakes_verslavingen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `intakes_verslavingsgebruikswijzen`
--
ALTER TABLE `intakes_verslavingsgebruikswijzen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `inventarisaties`
--
ALTER TABLE `inventarisaties`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `inventarisaties_verslagen`
--
ALTER TABLE `inventarisaties_verslagen`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `idx_iz_deelnemers_persoon` (`model`,`foreign_key`),
  ADD KEY `IDX_89B5B51CFBE387F6` (`iz_afsluiting_id`),
  ADD KEY `IDX_89B5B51C782093FC` (`contact_ontstaan`),
  ADD KEY `IDX_89B5B51CF0A6F57E` (`binnengekomen_via`),
  ADD KEY `IDX_89B5B51C7E366551` (`foreign_key`);

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
  ADD KEY `iz_deelnemers_iz_projecten_id_deelnemr` (`iz_deelnemer_id`),
  ADD KEY `iz_deelnemers_iz_projecten_iz_project_id` (`iz_project_id`),
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
  ADD UNIQUE KEY `UNIQ_86CE34D4FC4DB938` (`naam`);

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
  ADD KEY `IDX_24E5FDC2C217B9F` (`iz_vraagaanbod_id`);

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
  ADD UNIQUE KEY `UNIQ_178943F7FC4DB9384AD26064` (`naam`,`discr`);

--
-- Indexen voor tabel `iz_verslagen`
--
ALTER TABLE `iz_verslagen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idz_iz_verslag_iz_koppeling_id` (`iz_koppeling_id`),
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
  ADD KEY `idx_klanten_geboortedatum` (`geboortedatum`),
  ADD KEY `idx_klanten_first_intake_date` (`first_intake_date`),
  ADD KEY `idx_klanten_werkgebied` (`werkgebied`),
  ADD KEY `IDX_F538C5BC1C729A47` (`geslacht_id`),
  ADD KEY `IDX_F538C5BCCECBFEB7` (`nationaliteit_id`),
  ADD KEY `FK_F538C5BC1D103C3F` (`laste_intake_id`),
  ADD KEY `IDX_F538C5BC3D707F64` (`medewerker_id`),
  ADD KEY `IDX_F538C5BC1994904A` (`land_id`),
  ADD KEY `FK_F538C5BC8B2671BD` (`huidigeStatus_id`),
  ADD KEY `idx_klanten_postcodegebied` (`postcodegebied`);

--
-- Indexen voor tabel `klantinventarisaties`
--
ALTER TABLE `klantinventarisaties`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`);

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
-- Indexen voor tabel `mw_documenten`
--
ALTER TABLE `mw_documenten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_99E478283C427B2F` (`klant_id`),
  ADD KEY `IDX_99E478283D707F64` (`medewerker_id`);

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
-- Indexen voor tabel `odp_afsluitingen`
--
ALTER TABLE `odp_afsluitingen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `odp_coordinatoren`
--
ALTER TABLE `odp_coordinatoren`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_62BCCDB53D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `odp_deelnemers`
--
ALTER TABLE `odp_deelnemers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_202839993D707F64` (`medewerker_id`),
  ADD KEY `IDX_20283999ECDAD1A9` (`afsluiting_id`),
  ADD KEY `IDX_20283999C0B11400` (`woningbouwcorporatie_id`),
  ADD KEY `IDX_202839993C427B2F` (`klant_id`);

--
-- Indexen voor tabel `odp_deelnemer_document`
--
ALTER TABLE `odp_deelnemer_document`
  ADD PRIMARY KEY (`deelnemer_id`,`document_id`),
  ADD KEY `IDX_9BA61CC55DFA57A1` (`deelnemer_id`),
  ADD KEY `IDX_9BA61CC5C33F7837` (`document_id`);

--
-- Indexen voor tabel `odp_deelnemer_verslag`
--
ALTER TABLE `odp_deelnemer_verslag`
  ADD PRIMARY KEY (`deelnemer_id`,`verslag_id`),
  ADD KEY `IDX_F8F75D6A5DFA57A1` (`deelnemer_id`),
  ADD KEY `IDX_F8F75D6AD949475D` (`verslag_id`);

--
-- Indexen voor tabel `odp_documenten`
--
ALTER TABLE `odp_documenten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6E6F9FD53D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `odp_huuraanbiedingen`
--
ALTER TABLE `odp_huuraanbiedingen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_FA204F877E18485D` (`verhuurder_id`),
  ADD KEY `IDX_FA204F873D707F64` (`medewerker_id`),
  ADD KEY `IDX_FA204F87ECDAD1A9` (`afsluiting_id`);

--
-- Indexen voor tabel `odp_huuraanbod_verslag`
--
ALTER TABLE `odp_huuraanbod_verslag`
  ADD PRIMARY KEY (`huuraanbod_id`,`verslag_id`),
  ADD KEY `IDX_9B2DE75B656E2280` (`huuraanbod_id`),
  ADD KEY `IDX_9B2DE75BD949475D` (`verslag_id`);

--
-- Indexen voor tabel `odp_huurovereenkomsten`
--
ALTER TABLE `odp_huurovereenkomsten`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_453FF4A6656E2280` (`huuraanbod_id`),
  ADD UNIQUE KEY `UNIQ_453FF4A645DA3BB7` (`huurverzoek_id`),
  ADD KEY `IDX_453FF4A63D707F64` (`medewerker_id`),
  ADD KEY `IDX_453FF4A6ECDAD1A9` (`afsluiting_id`);

--
-- Indexen voor tabel `odp_huurovereenkomst_document`
--
ALTER TABLE `odp_huurovereenkomst_document`
  ADD PRIMARY KEY (`huurovereenkomst_id`,`document_id`),
  ADD KEY `IDX_7B9A48A7870B85BC` (`huurovereenkomst_id`),
  ADD KEY `IDX_7B9A48A7C33F7837` (`document_id`);

--
-- Indexen voor tabel `odp_huurovereenkomst_verslag`
--
ALTER TABLE `odp_huurovereenkomst_verslag`
  ADD PRIMARY KEY (`huurovereenkomst_id`,`verslag_id`),
  ADD KEY `IDX_114A2160870B85BC` (`huurovereenkomst_id`),
  ADD KEY `IDX_114A2160D949475D` (`verslag_id`);

--
-- Indexen voor tabel `odp_huurverzoeken`
--
ALTER TABLE `odp_huurverzoeken`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_588F4E969E4835DA` (`huurder_id`),
  ADD KEY `IDX_588F4E963D707F64` (`medewerker_id`),
  ADD KEY `IDX_588F4E96ECDAD1A9` (`afsluiting_id`);

--
-- Indexen voor tabel `odp_huurverzoek_verslag`
--
ALTER TABLE `odp_huurverzoek_verslag`
  ADD PRIMARY KEY (`huurverzoek_id`,`verslag_id`),
  ADD KEY `IDX_46CB48C145DA3BB7` (`huurverzoek_id`),
  ADD KEY `IDX_46CB48C1D949475D` (`verslag_id`);

--
-- Indexen voor tabel `odp_intakes`
--
ALTER TABLE `odp_intakes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_3A1E7F775DFA57A1` (`deelnemer_id`),
  ADD KEY `IDX_3A1E7F773D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `odp_verslagen`
--
ALTER TABLE `odp_verslagen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_762D3F773D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `odp_woningbouwcorporaties`
--
ALTER TABLE `odp_woningbouwcorporaties`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `oekklant_oekdossierstatus`
--
ALTER TABLE `oekklant_oekdossierstatus`
  ADD PRIMARY KEY (`oekklant_id`,`oekdossierstatus_id`),
  ADD KEY `IDX_1EF9C0A61833A719` (`oekklant_id`),
  ADD KEY `IDX_1EF9C0A6B689C3C1` (`oekdossierstatus_id`);

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
  ADD KEY `idx_opmerkingen_klant_id` (`klant_id`);

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
  ADD KEY `IDX_3C237EDD3D707F64` (`medewerker_id`);

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
  ADD KEY `IDX_FB4123F43C427B2F` (`klant_id`),
  ADD KEY `IDX_FB4123F44947630C` (`locatie_id`);

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
-- Indexen voor tabel `stadsdelen`
--
ALTER TABLE `stadsdelen`
  ADD PRIMARY KEY (`postcode`);

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
-- Indexen voor tabel `tmp_visits`
--
ALTER TABLE `tmp_visits`
  ADD KEY `idx_tmp_visits_locatie_id` (`locatie_id`),
  ADD KEY `idx_tmp_visits_klant_id` (`klant_id`),
  ADD KEY `idx_tmp_visits_date` (`date`),
  ADD KEY `idx_tmp_visits_duration` (`duration`),
  ADD KEY `idx_tmp_visits_gender` (`gender`);

--
-- Indexen voor tabel `trajecten`
--
ALTER TABLE `trajecten`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_klant_id` (`klant_id`);

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
  ADD KEY `IDX_2BBABA713D707F64` (`medewerker_id`);

--
-- Indexen voor tabel `verslaginfos`
--
ALTER TABLE `verslaginfos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_klant` (`klant_id`);

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
-- Indexen voor tabel `vrijwilligers`
--
ALTER TABLE `vrijwilligers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_klanten_geboortedatum` (`geboortedatum`),
  ADD KEY `idx_vrijwilligers_werkgebied` (`werkgebied`),
  ADD KEY `IDX_F0C4D2373D707F64` (`medewerker_id`),
  ADD KEY `IDX_F0C4D2371C729A47` (`geslacht_id`),
  ADD KEY `IDX_F0C4D2371994904A` (`land_id`),
  ADD KEY `IDX_F0C4D237CECBFEB7` (`nationaliteit_id`),
  ADD KEY `idx_vrijwilligers_postcodegebied` (`postcodegebied`);

--
-- Indexen voor tabel `werkgebieden`
--
ALTER TABLE `werkgebieden`
  ADD PRIMARY KEY (`naam`);

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
  ADD KEY `IDX_C8EF119C3C427B2F` (`klant_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `awbz_hoofdaannemers`
--
ALTER TABLE `awbz_hoofdaannemers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `awbz_indicaties`
--
ALTER TABLE `awbz_indicaties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `awbz_intakes`
--
ALTER TABLE `awbz_intakes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `awbz_intakes_primaireproblematieksgebruikswijzen`
--
ALTER TABLE `awbz_intakes_primaireproblematieksgebruikswijzen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `awbz_intakes_verslavingen`
--
ALTER TABLE `awbz_intakes_verslavingen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `awbz_intakes_verslavingsgebruikswijzen`
--
ALTER TABLE `awbz_intakes_verslavingsgebruikswijzen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `back_on_tracks`
--
ALTER TABLE `back_on_tracks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `bedrijfitems`
--
ALTER TABLE `bedrijfitems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `bedrijfsectoren`
--
ALTER TABLE `bedrijfsectoren`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `bot_koppelingen`
--
ALTER TABLE `bot_koppelingen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `bot_verslagen`
--
ALTER TABLE `bot_verslagen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `categorieen`
--
ALTER TABLE `categorieen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `clip_behandelaars`
--
ALTER TABLE `clip_behandelaars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `clip_clienten`
--
ALTER TABLE `clip_clienten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `clip_communicatiekanalen`
--
ALTER TABLE `clip_communicatiekanalen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `clip_contactmomenten`
--
ALTER TABLE `clip_contactmomenten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `clip_documenten`
--
ALTER TABLE `clip_documenten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `clip_hulpvragersoorten`
--
ALTER TABLE `clip_hulpvragersoorten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `clip_leeftijdscategorieen`
--
ALTER TABLE `clip_leeftijdscategorieen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `clip_viacategorieen`
--
ALTER TABLE `clip_viacategorieen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `clip_vraagsoorten`
--
ALTER TABLE `clip_vraagsoorten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `clip_vragen`
--
ALTER TABLE `clip_vragen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `contactjournals`
--
ALTER TABLE `contactjournals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `contactsoorts`
--
ALTER TABLE `contactsoorts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `dagbesteding_afsluitingen`
--
ALTER TABLE `dagbesteding_afsluitingen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `dagbesteding_contactpersonen`
--
ALTER TABLE `dagbesteding_contactpersonen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `dagbesteding_dagdelen`
--
ALTER TABLE `dagbesteding_dagdelen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `dagbesteding_deelnemers`
--
ALTER TABLE `dagbesteding_deelnemers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `dagbesteding_documenten`
--
ALTER TABLE `dagbesteding_documenten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `dagbesteding_locaties`
--
ALTER TABLE `dagbesteding_locaties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `dagbesteding_projecten`
--
ALTER TABLE `dagbesteding_projecten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `dagbesteding_rapportages`
--
ALTER TABLE `dagbesteding_rapportages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `dagbesteding_resultaatgebieden`
--
ALTER TABLE `dagbesteding_resultaatgebieden`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `dagbesteding_resultaatgebiedsoorten`
--
ALTER TABLE `dagbesteding_resultaatgebiedsoorten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `dagbesteding_trajectbegeleiders`
--
ALTER TABLE `dagbesteding_trajectbegeleiders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `dagbesteding_trajecten`
--
ALTER TABLE `dagbesteding_trajecten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `dagbesteding_trajectsoorten`
--
ALTER TABLE `dagbesteding_trajectsoorten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `dagbesteding_verslagen`
--
ALTER TABLE `dagbesteding_verslagen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `doorverwijzers`
--
ALTER TABLE `doorverwijzers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `ext_log_entries`
--
ALTER TABLE `ext_log_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `ga_documenten`
--
ALTER TABLE `ga_documenten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `gd27`
--
ALTER TABLE `gd27`
  MODIFY `idd` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `geslachten`
--
ALTER TABLE `geslachten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `groepsactiviteiten`
--
ALTER TABLE `groepsactiviteiten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `groepsactiviteiten_afsluitingen`
--
ALTER TABLE `groepsactiviteiten_afsluitingen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `groepsactiviteiten_groepen`
--
ALTER TABLE `groepsactiviteiten_groepen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `groepsactiviteiten_groepen_klanten`
--
ALTER TABLE `groepsactiviteiten_groepen_klanten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `groepsactiviteiten_groepen_vrijwilligers`
--
ALTER TABLE `groepsactiviteiten_groepen_vrijwilligers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `groepsactiviteiten_intakes`
--
ALTER TABLE `groepsactiviteiten_intakes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `groepsactiviteiten_klanten`
--
ALTER TABLE `groepsactiviteiten_klanten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `groepsactiviteiten_redenen`
--
ALTER TABLE `groepsactiviteiten_redenen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `groepsactiviteiten_verslagen`
--
ALTER TABLE `groepsactiviteiten_verslagen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `groepsactiviteiten_vrijwilligers`
--
ALTER TABLE `groepsactiviteiten_vrijwilligers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hi5_answers`
--
ALTER TABLE `hi5_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hi5_answer_types`
--
ALTER TABLE `hi5_answer_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hi5_evaluaties`
--
ALTER TABLE `hi5_evaluaties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hi5_evaluaties_hi5_evaluatie_questions`
--
ALTER TABLE `hi5_evaluaties_hi5_evaluatie_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hi5_evaluatie_paragraphs`
--
ALTER TABLE `hi5_evaluatie_paragraphs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hi5_evaluatie_questions`
--
ALTER TABLE `hi5_evaluatie_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hi5_intakes`
--
ALTER TABLE `hi5_intakes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hi5_intakes_answers`
--
ALTER TABLE `hi5_intakes_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hi5_intakes_inkomens`
--
ALTER TABLE `hi5_intakes_inkomens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hi5_intakes_instanties`
--
ALTER TABLE `hi5_intakes_instanties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hi5_intakes_primaireproblematieksgebruikswijzen`
--
ALTER TABLE `hi5_intakes_primaireproblematieksgebruikswijzen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hi5_intakes_verslavingen`
--
ALTER TABLE `hi5_intakes_verslavingen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hi5_intakes_verslavingsgebruikswijzen`
--
ALTER TABLE `hi5_intakes_verslavingsgebruikswijzen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hi5_questions`
--
ALTER TABLE `hi5_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hoofdaannemers`
--
ALTER TABLE `hoofdaannemers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hs_activiteiten`
--
ALTER TABLE `hs_activiteiten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hs_arbeiders`
--
ALTER TABLE `hs_arbeiders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hs_betalingen`
--
ALTER TABLE `hs_betalingen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hs_declaraties`
--
ALTER TABLE `hs_declaraties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hs_declaratie_categorieen`
--
ALTER TABLE `hs_declaratie_categorieen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hs_documenten`
--
ALTER TABLE `hs_documenten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hs_facturen`
--
ALTER TABLE `hs_facturen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hs_herinneringen`
--
ALTER TABLE `hs_herinneringen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hs_klanten`
--
ALTER TABLE `hs_klanten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hs_klussen`
--
ALTER TABLE `hs_klussen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hs_memos`
--
ALTER TABLE `hs_memos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `hs_registraties`
--
ALTER TABLE `hs_registraties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `i18n`
--
ALTER TABLE `i18n`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `infobaliedoelgroepen`
--
ALTER TABLE `infobaliedoelgroepen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `inkomens`
--
ALTER TABLE `inkomens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `inkomens_awbz_intakes`
--
ALTER TABLE `inkomens_awbz_intakes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `inkomens_intakes`
--
ALTER TABLE `inkomens_intakes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `inloop_afsluiting_redenen`
--
ALTER TABLE `inloop_afsluiting_redenen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `inloop_binnen_via`
--
ALTER TABLE `inloop_binnen_via`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `inloop_documenten`
--
ALTER TABLE `inloop_documenten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `inloop_dossier_statussen`
--
ALTER TABLE `inloop_dossier_statussen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `inloop_memos`
--
ALTER TABLE `inloop_memos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `inloop_vrijwilligers`
--
ALTER TABLE `inloop_vrijwilligers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `instanties`
--
ALTER TABLE `instanties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `instanties_awbz_intakes`
--
ALTER TABLE `instanties_awbz_intakes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `instanties_intakes`
--
ALTER TABLE `instanties_intakes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `intakes`
--
ALTER TABLE `intakes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `intakes_primaireproblematieksgebruikswijzen`
--
ALTER TABLE `intakes_primaireproblematieksgebruikswijzen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `intakes_verslavingen`
--
ALTER TABLE `intakes_verslavingen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `intakes_verslavingsgebruikswijzen`
--
ALTER TABLE `intakes_verslavingsgebruikswijzen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `inventarisaties`
--
ALTER TABLE `inventarisaties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `inventarisaties_verslagen`
--
ALTER TABLE `inventarisaties_verslagen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `iz_afsluitingen`
--
ALTER TABLE `iz_afsluitingen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `iz_deelnemers`
--
ALTER TABLE `iz_deelnemers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `iz_deelnemers_iz_intervisiegroepen`
--
ALTER TABLE `iz_deelnemers_iz_intervisiegroepen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `iz_deelnemers_iz_projecten`
--
ALTER TABLE `iz_deelnemers_iz_projecten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `iz_documenten`
--
ALTER TABLE `iz_documenten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `iz_doelgroepen`
--
ALTER TABLE `iz_doelgroepen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `iz_doelstellingen`
--
ALTER TABLE `iz_doelstellingen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `iz_eindekoppelingen`
--
ALTER TABLE `iz_eindekoppelingen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `iz_hulpvraagsoorten`
--
ALTER TABLE `iz_hulpvraagsoorten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `iz_intakes`
--
ALTER TABLE `iz_intakes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `iz_intervisiegroepen`
--
ALTER TABLE `iz_intervisiegroepen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `iz_koppelingen`
--
ALTER TABLE `iz_koppelingen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `iz_matching_klanten`
--
ALTER TABLE `iz_matching_klanten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `iz_matching_vrijwilligers`
--
ALTER TABLE `iz_matching_vrijwilligers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `iz_ontstaan_contacten`
--
ALTER TABLE `iz_ontstaan_contacten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `iz_projecten`
--
ALTER TABLE `iz_projecten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `iz_reserveringen`
--
ALTER TABLE `iz_reserveringen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `iz_succesindicatoren`
--
ALTER TABLE `iz_succesindicatoren`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `iz_verslagen`
--
ALTER TABLE `iz_verslagen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `iz_via_personen`
--
ALTER TABLE `iz_via_personen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `iz_vraagaanboden`
--
ALTER TABLE `iz_vraagaanboden`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `klanten`
--
ALTER TABLE `klanten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `klantinventarisaties`
--
ALTER TABLE `klantinventarisaties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `landen`
--
ALTER TABLE `landen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `legitimaties`
--
ALTER TABLE `legitimaties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `locaties`
--
ALTER TABLE `locaties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `locatie_tijden`
--
ALTER TABLE `locatie_tijden`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `medewerkers`
--
ALTER TABLE `medewerkers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `mw_documenten`
--
ALTER TABLE `mw_documenten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `nationaliteiten`
--
ALTER TABLE `nationaliteiten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `notities`
--
ALTER TABLE `notities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `odp_afsluitingen`
--
ALTER TABLE `odp_afsluitingen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `odp_coordinatoren`
--
ALTER TABLE `odp_coordinatoren`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `odp_deelnemers`
--
ALTER TABLE `odp_deelnemers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `odp_documenten`
--
ALTER TABLE `odp_documenten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `odp_huuraanbiedingen`
--
ALTER TABLE `odp_huuraanbiedingen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `odp_huurovereenkomsten`
--
ALTER TABLE `odp_huurovereenkomsten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `odp_huurverzoeken`
--
ALTER TABLE `odp_huurverzoeken`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `odp_intakes`
--
ALTER TABLE `odp_intakes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `odp_verslagen`
--
ALTER TABLE `odp_verslagen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `odp_woningbouwcorporaties`
--
ALTER TABLE `odp_woningbouwcorporaties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `oek_deelnames`
--
ALTER TABLE `oek_deelnames`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `oek_deelname_statussen`
--
ALTER TABLE `oek_deelname_statussen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `oek_documenten`
--
ALTER TABLE `oek_documenten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `oek_dossier_statussen`
--
ALTER TABLE `oek_dossier_statussen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `oek_groepen`
--
ALTER TABLE `oek_groepen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `oek_klanten`
--
ALTER TABLE `oek_klanten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `oek_lidmaatschappen`
--
ALTER TABLE `oek_lidmaatschappen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `oek_memos`
--
ALTER TABLE `oek_memos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `oek_trainingen`
--
ALTER TABLE `oek_trainingen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `oek_verwijzingen`
--
ALTER TABLE `oek_verwijzingen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `oek_vrijwilligers`
--
ALTER TABLE `oek_vrijwilligers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `opmerkingen`
--
ALTER TABLE `opmerkingen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `pfo_aard_relaties`
--
ALTER TABLE `pfo_aard_relaties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `pfo_clienten`
--
ALTER TABLE `pfo_clienten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `pfo_clienten_supportgroups`
--
ALTER TABLE `pfo_clienten_supportgroups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `pfo_clienten_verslagen`
--
ALTER TABLE `pfo_clienten_verslagen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `pfo_documenten`
--
ALTER TABLE `pfo_documenten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `pfo_groepen`
--
ALTER TABLE `pfo_groepen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `pfo_verslagen`
--
ALTER TABLE `pfo_verslagen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `postcodegebieden`
--
ALTER TABLE `postcodegebieden`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `queue_tasks`
--
ALTER TABLE `queue_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `redenen`
--
ALTER TABLE `redenen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `registraties`
--
ALTER TABLE `registraties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `schorsingen`
--
ALTER TABLE `schorsingen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `schorsingen_redenen`
--
ALTER TABLE `schorsingen_redenen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `trajecten`
--
ALTER TABLE `trajecten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `verblijfstatussen`
--
ALTER TABLE `verblijfstatussen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `verslagen`
--
ALTER TABLE `verslagen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `verslaginfos`
--
ALTER TABLE `verslaginfos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `verslavingen`
--
ALTER TABLE `verslavingen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `verslavingsfrequenties`
--
ALTER TABLE `verslavingsfrequenties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `verslavingsgebruikswijzen`
--
ALTER TABLE `verslavingsgebruikswijzen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `verslavingsperiodes`
--
ALTER TABLE `verslavingsperiodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `vrijwilligers`
--
ALTER TABLE `vrijwilligers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `woonsituaties`
--
ALTER TABLE `woonsituaties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `zrm_reports`
--
ALTER TABLE `zrm_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `zrm_settings`
--
ALTER TABLE `zrm_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `zrm_v2_reports`
--
ALTER TABLE `zrm_v2_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `zrm_v2_settings`
--
ALTER TABLE `zrm_v2_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `amoc_landen`
--
ALTER TABLE `amoc_landen`
  ADD CONSTRAINT `FK_2B24A60A1994904A` FOREIGN KEY (`land_id`) REFERENCES `landen` (`id`);

--
-- Beperkingen voor tabel `clip_behandelaars`
--
ALTER TABLE `clip_behandelaars`
  ADD CONSTRAINT `FK_4B016D223D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `clip_clienten`
--
ALTER TABLE `clip_clienten`
  ADD CONSTRAINT `FK_B7F4C67E1C729A47` FOREIGN KEY (`geslacht_id`) REFERENCES `geslachten` (`id`),
  ADD CONSTRAINT `FK_B7F4C67E35A09212` FOREIGN KEY (`behandelaar_id`) REFERENCES `clip_behandelaars` (`id`),
  ADD CONSTRAINT `FK_B7F4C67E46708ED5` FOREIGN KEY (`werkgebied`) REFERENCES `werkgebieden` (`naam`),
  ADD CONSTRAINT `FK_B7F4C67EC5BB5F49` FOREIGN KEY (`viacategorie_id`) REFERENCES `clip_viacategorieen` (`id`),
  ADD CONSTRAINT `FK_B7F4C67EFB02B9C2` FOREIGN KEY (`postcodegebied`) REFERENCES `ggw_gebieden` (`naam`);

--
-- Beperkingen voor tabel `clip_client_document`
--
ALTER TABLE `clip_client_document`
  ADD CONSTRAINT `FK_18AEA4C519EB6921` FOREIGN KEY (`client_id`) REFERENCES `clip_clienten` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_18AEA4C5C33F7837` FOREIGN KEY (`document_id`) REFERENCES `clip_documenten` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `clip_contactmomenten`
--
ALTER TABLE `clip_contactmomenten`
  ADD CONSTRAINT `FK_8C4DFF3D2CE1D7E6` FOREIGN KEY (`vraag_id`) REFERENCES `clip_vragen` (`id`),
  ADD CONSTRAINT `FK_8C4DFF3D35A09212` FOREIGN KEY (`behandelaar_id`) REFERENCES `clip_behandelaars` (`id`);

--
-- Beperkingen voor tabel `clip_documenten`
--
ALTER TABLE `clip_documenten`
  ADD CONSTRAINT `FK_98FCA35A09212` FOREIGN KEY (`behandelaar_id`) REFERENCES `clip_behandelaars` (`id`);

--
-- Beperkingen voor tabel `clip_vraag_document`
--
ALTER TABLE `clip_vraag_document`
  ADD CONSTRAINT `FK_37F7BFD72CE1D7E6` FOREIGN KEY (`vraag_id`) REFERENCES `clip_vragen` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_37F7BFD7C33F7837` FOREIGN KEY (`document_id`) REFERENCES `clip_documenten` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `clip_vragen`
--
ALTER TABLE `clip_vragen`
  ADD CONSTRAINT `FK_C28C591717F2E03B` FOREIGN KEY (`hulpvrager_id`) REFERENCES `clip_hulpvragersoorten` (`id`),
  ADD CONSTRAINT `FK_C28C591719EB6921` FOREIGN KEY (`client_id`) REFERENCES `clip_clienten` (`id`),
  ADD CONSTRAINT `FK_C28C59172EC18014` FOREIGN KEY (`leeftijdscategorie_id`) REFERENCES `clip_leeftijdscategorieen` (`id`),
  ADD CONSTRAINT `FK_C28C591735A09212` FOREIGN KEY (`behandelaar_id`) REFERENCES `clip_behandelaars` (`id`),
  ADD CONSTRAINT `FK_C28C59173DEE50DF` FOREIGN KEY (`soort_id`) REFERENCES `clip_vraagsoorten` (`id`),
  ADD CONSTRAINT `FK_C28C591771CC83CE` FOREIGN KEY (`communicatiekanaal_id`) REFERENCES `clip_communicatiekanalen` (`id`);

--
-- Beperkingen voor tabel `dagbesteding_contactpersonen`
--
ALTER TABLE `dagbesteding_contactpersonen`
  ADD CONSTRAINT `FK_C14C44B85DFA57A1` FOREIGN KEY (`deelnemer_id`) REFERENCES `dagbesteding_deelnemers` (`id`);

--
-- Beperkingen voor tabel `dagbesteding_dagdelen`
--
ALTER TABLE `dagbesteding_dagdelen`
  ADD CONSTRAINT `FK_54F41972166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `dagbesteding_projecten` (`id`),
  ADD CONSTRAINT `FK_54F41972A0CADD4` FOREIGN KEY (`traject_id`) REFERENCES `dagbesteding_trajecten` (`id`);

--
-- Beperkingen voor tabel `dagbesteding_deelnemers`
--
ALTER TABLE `dagbesteding_deelnemers`
  ADD CONSTRAINT `FK_6EAE83E73C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`),
  ADD CONSTRAINT `FK_6EAE83E73D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`),
  ADD CONSTRAINT `FK_6EAE83E7ECDAD1A9` FOREIGN KEY (`afsluiting_id`) REFERENCES `dagbesteding_afsluitingen` (`id`);

--
-- Beperkingen voor tabel `dagbesteding_deelnemer_document`
--
ALTER TABLE `dagbesteding_deelnemer_document`
  ADD CONSTRAINT `FK_89539E645DFA57A1` FOREIGN KEY (`deelnemer_id`) REFERENCES `dagbesteding_deelnemers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_89539E64C33F7837` FOREIGN KEY (`document_id`) REFERENCES `dagbesteding_documenten` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `dagbesteding_deelnemer_verslag`
--
ALTER TABLE `dagbesteding_deelnemer_verslag`
  ADD CONSTRAINT `FK_BA9CAC335DFA57A1` FOREIGN KEY (`deelnemer_id`) REFERENCES `dagbesteding_deelnemers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_BA9CAC33D949475D` FOREIGN KEY (`verslag_id`) REFERENCES `dagbesteding_verslagen` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `dagbesteding_documenten`
--
ALTER TABLE `dagbesteding_documenten`
  ADD CONSTRAINT `FK_20E925AB3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `dagbesteding_rapportages`
--
ALTER TABLE `dagbesteding_rapportages`
  ADD CONSTRAINT `FK_FBA614843D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`),
  ADD CONSTRAINT `FK_FBA61484A0CADD4` FOREIGN KEY (`traject_id`) REFERENCES `dagbesteding_trajecten` (`id`);

--
-- Beperkingen voor tabel `dagbesteding_rapportage_document`
--
ALTER TABLE `dagbesteding_rapportage_document`
  ADD CONSTRAINT `FK_8ED5B83668A3850` FOREIGN KEY (`rapportage_id`) REFERENCES `dagbesteding_rapportages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_8ED5B83C33F7837` FOREIGN KEY (`document_id`) REFERENCES `dagbesteding_documenten` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `dagbesteding_resultaatgebieden`
--
ALTER TABLE `dagbesteding_resultaatgebieden`
  ADD CONSTRAINT `FK_4F7529D33DEE50DF` FOREIGN KEY (`soort_id`) REFERENCES `dagbesteding_resultaatgebiedsoorten` (`id`),
  ADD CONSTRAINT `FK_4F7529D3A0CADD4` FOREIGN KEY (`traject_id`) REFERENCES `dagbesteding_trajecten` (`id`);

--
-- Beperkingen voor tabel `dagbesteding_trajectbegeleiders`
--
ALTER TABLE `dagbesteding_trajectbegeleiders`
  ADD CONSTRAINT `FK_EA2465533D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `dagbesteding_trajecten`
--
ALTER TABLE `dagbesteding_trajecten`
  ADD CONSTRAINT `FK_670A67F21BE6904` FOREIGN KEY (`resultaatgebied_id`) REFERENCES `dagbesteding_resultaatgebieden` (`id`),
  ADD CONSTRAINT `FK_670A67F239F86A1D` FOREIGN KEY (`begeleider_id`) REFERENCES `dagbesteding_trajectbegeleiders` (`id`),
  ADD CONSTRAINT `FK_670A67F23DEE50DF` FOREIGN KEY (`soort_id`) REFERENCES `dagbesteding_trajectsoorten` (`id`),
  ADD CONSTRAINT `FK_670A67F25DFA57A1` FOREIGN KEY (`deelnemer_id`) REFERENCES `dagbesteding_deelnemers` (`id`),
  ADD CONSTRAINT `FK_670A67F2ECDAD1A9` FOREIGN KEY (`afsluiting_id`) REFERENCES `dagbesteding_afsluitingen` (`id`);

--
-- Beperkingen voor tabel `dagbesteding_traject_document`
--
ALTER TABLE `dagbesteding_traject_document`
  ADD CONSTRAINT `FK_5408B1ADA0CADD4` FOREIGN KEY (`traject_id`) REFERENCES `dagbesteding_trajecten` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_5408B1ADC33F7837` FOREIGN KEY (`document_id`) REFERENCES `dagbesteding_documenten` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `dagbesteding_traject_locatie`
--
ALTER TABLE `dagbesteding_traject_locatie`
  ADD CONSTRAINT `FK_1D8874704947630C` FOREIGN KEY (`locatie_id`) REFERENCES `dagbesteding_locaties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_1D887470A0CADD4` FOREIGN KEY (`traject_id`) REFERENCES `dagbesteding_trajecten` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `dagbesteding_traject_project`
--
ALTER TABLE `dagbesteding_traject_project`
  ADD CONSTRAINT `FK_9DF4F8B0166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `dagbesteding_projecten` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_9DF4F8B0A0CADD4` FOREIGN KEY (`traject_id`) REFERENCES `dagbesteding_trajecten` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `dagbesteding_traject_verslag`
--
ALTER TABLE `dagbesteding_traject_verslag`
  ADD CONSTRAINT `FK_ECCFAC5CA0CADD4` FOREIGN KEY (`traject_id`) REFERENCES `dagbesteding_trajecten` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_ECCFAC5CD949475D` FOREIGN KEY (`verslag_id`) REFERENCES `dagbesteding_verslagen` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `dagbesteding_verslagen`
--
ALTER TABLE `dagbesteding_verslagen`
  ADD CONSTRAINT `FK_F41415953D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `ga_documenten`
--
ALTER TABLE `ga_documenten`
  ADD CONSTRAINT `FK_409E56123D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`),
  ADD CONSTRAINT `FK_409E561276B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `vrijwilligers` (`id`);

--
-- Beperkingen voor tabel `ga_gaklantintake_zrm`
--
ALTER TABLE `ga_gaklantintake_zrm`
  ADD CONSTRAINT `FK_D68056715FA93F88` FOREIGN KEY (`gaklantintake_id`) REFERENCES `groepsactiviteiten_intakes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_D6805671C8250F57` FOREIGN KEY (`zrm_id`) REFERENCES `zrm_reports` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `groepsactiviteiten`
--
ALTER TABLE `groepsactiviteiten`
  ADD CONSTRAINT `FK_9DB0AE2768AE5B4C` FOREIGN KEY (`groepsactiviteiten_groep_id`) REFERENCES `groepsactiviteiten_groepen` (`id`);

--
-- Beperkingen voor tabel `groepsactiviteiten_groepen_klanten`
--
ALTER TABLE `groepsactiviteiten_groepen_klanten`
  ADD CONSTRAINT `FK_E248EC8D248D162C` FOREIGN KEY (`groepsactiviteiten_reden_id`) REFERENCES `groepsactiviteiten_redenen` (`id`),
  ADD CONSTRAINT `FK_E248EC8D3C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`),
  ADD CONSTRAINT `FK_E248EC8D68AE5B4C` FOREIGN KEY (`groepsactiviteiten_groep_id`) REFERENCES `groepsactiviteiten_groepen` (`id`);

--
-- Beperkingen voor tabel `groepsactiviteiten_groepen_vrijwilligers`
--
ALTER TABLE `groepsactiviteiten_groepen_vrijwilligers`
  ADD CONSTRAINT `FK_81655E23248D162C` FOREIGN KEY (`groepsactiviteiten_reden_id`) REFERENCES `groepsactiviteiten_redenen` (`id`),
  ADD CONSTRAINT `FK_81655E2368AE5B4C` FOREIGN KEY (`groepsactiviteiten_groep_id`) REFERENCES `groepsactiviteiten_groepen` (`id`),
  ADD CONSTRAINT `FK_81655E2376B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `vrijwilligers` (`id`);

--
-- Beperkingen voor tabel `groepsactiviteiten_intakes`
--
ALTER TABLE `groepsactiviteiten_intakes`
  ADD CONSTRAINT `FK_843277B3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`),
  ADD CONSTRAINT `FK_843277B64BCC47A` FOREIGN KEY (`groepsactiviteiten_afsluiting_id`) REFERENCES `groepsactiviteiten_afsluitingen` (`id`);

--
-- Beperkingen voor tabel `groepsactiviteiten_klanten`
--
ALTER TABLE `groepsactiviteiten_klanten`
  ADD CONSTRAINT `FK_560B17693C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`),
  ADD CONSTRAINT `FK_560B17695BF7B988` FOREIGN KEY (`groepsactiviteit_id`) REFERENCES `groepsactiviteiten` (`id`);

--
-- Beperkingen voor tabel `groepsactiviteiten_verslagen`
--
ALTER TABLE `groepsactiviteiten_verslagen`
  ADD CONSTRAINT `FK_BF289BE03D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `groepsactiviteiten_vrijwilligers`
--
ALTER TABLE `groepsactiviteiten_vrijwilligers`
  ADD CONSTRAINT `FK_78A2C7E476B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `vrijwilligers` (`id`);

--
-- Beperkingen voor tabel `hs_betalingen`
--
ALTER TABLE `hs_betalingen`
  ADD CONSTRAINT `FK_EADEA9FFC35D3E` FOREIGN KEY (`factuur_id`) REFERENCES `hs_facturen` (`id`);

--
-- Beperkingen voor tabel `hs_declaraties`
--
ALTER TABLE `hs_declaraties`
  ADD CONSTRAINT `FK_AF23D2921EE52B26` FOREIGN KEY (`declaratieCategorie_id`) REFERENCES `hs_declaratie_categorieen` (`id`),
  ADD CONSTRAINT `FK_AF23D2923D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`),
  ADD CONSTRAINT `FK_AF23D292BA5374AF` FOREIGN KEY (`klus_id`) REFERENCES `hs_klussen` (`id`),
  ADD CONSTRAINT `FK_AF23D292C35D3E` FOREIGN KEY (`factuur_id`) REFERENCES `hs_facturen` (`id`);

--
-- Beperkingen voor tabel `hs_declaratie_document`
--
ALTER TABLE `hs_declaratie_document`
  ADD CONSTRAINT `FK_9E1A25FE6AE7FC36` FOREIGN KEY (`declaratie_id`) REFERENCES `hs_declaraties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_9E1A25FEC33F7837` FOREIGN KEY (`document_id`) REFERENCES `hs_documenten` (`id`);

--
-- Beperkingen voor tabel `hs_dienstverleners`
--
ALTER TABLE `hs_dienstverleners`
  ADD CONSTRAINT `FK_4949548D3C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`),
  ADD CONSTRAINT `FK_4949548DBF396750` FOREIGN KEY (`id`) REFERENCES `hs_arbeiders` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `hs_dienstverlener_document`
--
ALTER TABLE `hs_dienstverlener_document`
  ADD CONSTRAINT `FK_DEBCC7F2A1690166` FOREIGN KEY (`dienstverlener_id`) REFERENCES `hs_dienstverleners` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_DEBCC7F2C33F7837` FOREIGN KEY (`document_id`) REFERENCES `hs_documenten` (`id`);

--
-- Beperkingen voor tabel `hs_dienstverlener_memo`
--
ALTER TABLE `hs_dienstverlener_memo`
  ADD CONSTRAINT `FK_F546B7DDA1690166` FOREIGN KEY (`dienstverlener_id`) REFERENCES `hs_dienstverleners` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_F546B7DDB4D32439` FOREIGN KEY (`memo_id`) REFERENCES `hs_memos` (`id`);

--
-- Beperkingen voor tabel `hs_documenten`
--
ALTER TABLE `hs_documenten`
  ADD CONSTRAINT `FK_87CDF0443D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `hs_facturen`
--
ALTER TABLE `hs_facturen`
  ADD CONSTRAINT `FK_31669C163C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `hs_klanten` (`id`);

--
-- Beperkingen voor tabel `hs_factuur_klus`
--
ALTER TABLE `hs_factuur_klus`
  ADD CONSTRAINT `FK_B3DD2838BA5374AF` FOREIGN KEY (`klus_id`) REFERENCES `hs_klussen` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_B3DD2838C35D3E` FOREIGN KEY (`factuur_id`) REFERENCES `hs_facturen` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `hs_herinneringen`
--
ALTER TABLE `hs_herinneringen`
  ADD CONSTRAINT `FK_D18DFCA3C35D3E` FOREIGN KEY (`factuur_id`) REFERENCES `hs_facturen` (`id`);

--
-- Beperkingen voor tabel `hs_klanten`
--
ALTER TABLE `hs_klanten`
  ADD CONSTRAINT `FK_CC6284A1C729A47` FOREIGN KEY (`geslacht_id`) REFERENCES `geslachten` (`id`),
  ADD CONSTRAINT `FK_CC6284A3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`),
  ADD CONSTRAINT `FK_CC6284A46708ED5` FOREIGN KEY (`werkgebied`) REFERENCES `werkgebieden` (`naam`),
  ADD CONSTRAINT `FK_CC6284AFB02B9C2` FOREIGN KEY (`postcodegebied`) REFERENCES `ggw_gebieden` (`naam`);

--
-- Beperkingen voor tabel `hs_klant_document`
--
ALTER TABLE `hs_klant_document`
  ADD CONSTRAINT `FK_795E7D0B3C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `hs_klanten` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_795E7D0BC33F7837` FOREIGN KEY (`document_id`) REFERENCES `hs_documenten` (`id`);

--
-- Beperkingen voor tabel `hs_klant_memo`
--
ALTER TABLE `hs_klant_memo`
  ADD CONSTRAINT `FK_177077063C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `hs_klanten` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_17707706B4D32439` FOREIGN KEY (`memo_id`) REFERENCES `hs_memos` (`id`);

--
-- Beperkingen voor tabel `hs_klussen`
--
ALTER TABLE `hs_klussen`
  ADD CONSTRAINT `FK_3E9A80CF3C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `hs_klanten` (`id`),
  ADD CONSTRAINT `FK_3E9A80CF3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `hs_klus_activiteit`
--
ALTER TABLE `hs_klus_activiteit`
  ADD CONSTRAINT `FK_AE073F885A8A0A1` FOREIGN KEY (`activiteit_id`) REFERENCES `hs_activiteiten` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_AE073F88BA5374AF` FOREIGN KEY (`klus_id`) REFERENCES `hs_klussen` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `hs_klus_dienstverlener`
--
ALTER TABLE `hs_klus_dienstverlener`
  ADD CONSTRAINT `FK_70F96EFBA1690166` FOREIGN KEY (`dienstverlener_id`) REFERENCES `hs_dienstverleners` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_70F96EFBBA5374AF` FOREIGN KEY (`klus_id`) REFERENCES `hs_klussen` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `hs_klus_document`
--
ALTER TABLE `hs_klus_document`
  ADD CONSTRAINT `FK_869EC9C5BA5374AF` FOREIGN KEY (`klus_id`) REFERENCES `hs_klussen` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_869EC9C5C33F7837` FOREIGN KEY (`document_id`) REFERENCES `hs_documenten` (`id`);

--
-- Beperkingen voor tabel `hs_klus_memo`
--
ALTER TABLE `hs_klus_memo`
  ADD CONSTRAINT `FK_208D08ECB4D32439` FOREIGN KEY (`memo_id`) REFERENCES `hs_memos` (`id`),
  ADD CONSTRAINT `FK_208D08ECBA5374AF` FOREIGN KEY (`klus_id`) REFERENCES `hs_klussen` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `hs_klus_vrijwilliger`
--
ALTER TABLE `hs_klus_vrijwilliger`
  ADD CONSTRAINT `FK_6E1EDAA176B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `hs_vrijwilligers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_6E1EDAA1BA5374AF` FOREIGN KEY (`klus_id`) REFERENCES `hs_klussen` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `hs_memos`
--
ALTER TABLE `hs_memos`
  ADD CONSTRAINT `FK_4048AFA33D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `hs_registraties`
--
ALTER TABLE `hs_registraties`
  ADD CONSTRAINT `FK_62041BC23D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`),
  ADD CONSTRAINT `FK_62041BC25A8A0A1` FOREIGN KEY (`activiteit_id`) REFERENCES `hs_activiteiten` (`id`),
  ADD CONSTRAINT `FK_62041BC26650623E` FOREIGN KEY (`arbeider_id`) REFERENCES `hs_arbeiders` (`id`),
  ADD CONSTRAINT `FK_62041BC2BA5374AF` FOREIGN KEY (`klus_id`) REFERENCES `hs_klussen` (`id`),
  ADD CONSTRAINT `FK_62041BC2C35D3E` FOREIGN KEY (`factuur_id`) REFERENCES `hs_facturen` (`id`);

--
-- Beperkingen voor tabel `hs_vrijwilligers`
--
ALTER TABLE `hs_vrijwilligers`
  ADD CONSTRAINT `FK_3FE7029676B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `vrijwilligers` (`id`),
  ADD CONSTRAINT `FK_3FE70296BF396750` FOREIGN KEY (`id`) REFERENCES `hs_arbeiders` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `hs_vrijwilliger_document`
--
ALTER TABLE `hs_vrijwilliger_document`
  ADD CONSTRAINT `FK_EAEC53F376B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `hs_vrijwilligers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_EAEC53F3C33F7837` FOREIGN KEY (`document_id`) REFERENCES `hs_documenten` (`id`);

--
-- Beperkingen voor tabel `hs_vrijwilliger_memo`
--
ALTER TABLE `hs_vrijwilliger_memo`
  ADD CONSTRAINT `FK_938D702F76B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `hs_vrijwilligers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_938D702FB4D32439` FOREIGN KEY (`memo_id`) REFERENCES `hs_memos` (`id`);

--
-- Beperkingen voor tabel `inloop_documenten`
--
ALTER TABLE `inloop_documenten`
  ADD CONSTRAINT `FK_9DA9ECF43D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `inloop_dossier_statussen`
--
ALTER TABLE `inloop_dossier_statussen`
  ADD CONSTRAINT `FK_12D2B5701994904A` FOREIGN KEY (`land_id`) REFERENCES `landen` (`id`),
  ADD CONSTRAINT `FK_12D2B5703C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`),
  ADD CONSTRAINT `FK_12D2B5703D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`),
  ADD CONSTRAINT `FK_12D2B570D29703A5` FOREIGN KEY (`reden_id`) REFERENCES `inloop_afsluiting_redenen` (`id`);

--
-- Beperkingen voor tabel `inloop_intake_zrm`
--
ALTER TABLE `inloop_intake_zrm`
  ADD CONSTRAINT `FK_92197717733DE450` FOREIGN KEY (`intake_id`) REFERENCES `intakes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_92197717C8250F57` FOREIGN KEY (`zrm_id`) REFERENCES `zrm_reports` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `inloop_memos`
--
ALTER TABLE `inloop_memos`
  ADD CONSTRAINT `FK_9ACAE40D3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `inloop_vrijwilligers`
--
ALTER TABLE `inloop_vrijwilligers`
  ADD CONSTRAINT `FK_561104803D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`),
  ADD CONSTRAINT `FK_5611048076B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `vrijwilligers` (`id`),
  ADD CONSTRAINT `FK_56110480D8471945` FOREIGN KEY (`binnen_via_id`) REFERENCES `inloop_binnen_via` (`id`);

--
-- Beperkingen voor tabel `inloop_vrijwilliger_document`
--
ALTER TABLE `inloop_vrijwilliger_document`
  ADD CONSTRAINT `FK_6401B15D76B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `inloop_vrijwilligers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_6401B15DC33F7837` FOREIGN KEY (`document_id`) REFERENCES `inloop_documenten` (`id`);

--
-- Beperkingen voor tabel `inloop_vrijwilliger_locatie`
--
ALTER TABLE `inloop_vrijwilliger_locatie`
  ADD CONSTRAINT `FK_A1776D9F4947630C` FOREIGN KEY (`locatie_id`) REFERENCES `locaties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_A1776D9F76B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `inloop_vrijwilligers` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `inloop_vrijwilliger_memo`
--
ALTER TABLE `inloop_vrijwilliger_memo`
  ADD CONSTRAINT `FK_94FA9B1976B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `inloop_vrijwilligers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_94FA9B19B4D32439` FOREIGN KEY (`memo_id`) REFERENCES `inloop_memos` (`id`);

--
-- Beperkingen voor tabel `intakes`
--
ALTER TABLE `intakes`
  ADD CONSTRAINT `FK_AB70F5AE48D0634A` FOREIGN KEY (`verblijfstatus_id`) REFERENCES `verblijfstatussen` (`id`);

--
-- Beperkingen voor tabel `iz_deelnemers`
--
ALTER TABLE `iz_deelnemers`
  ADD CONSTRAINT `FK_89B5B51C782093FC` FOREIGN KEY (`contact_ontstaan`) REFERENCES `iz_ontstaan_contacten` (`id`),
  ADD CONSTRAINT `FK_89B5B51CF0A6F57E` FOREIGN KEY (`binnengekomen_via`) REFERENCES `iz_via_personen` (`id`),
  ADD CONSTRAINT `FK_89B5B51CFBE387F6` FOREIGN KEY (`iz_afsluiting_id`) REFERENCES `iz_afsluitingen` (`id`);

--
-- Beperkingen voor tabel `iz_deelnemers_documenten`
--
ALTER TABLE `iz_deelnemers_documenten`
  ADD CONSTRAINT `FK_66AE504F55B482C2` FOREIGN KEY (`izdeelnemer_id`) REFERENCES `iz_deelnemers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_66AE504FC33F7837` FOREIGN KEY (`document_id`) REFERENCES `iz_documenten` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `iz_deelnemers_iz_intervisiegroepen`
--
ALTER TABLE `iz_deelnemers_iz_intervisiegroepen`
  ADD CONSTRAINT `FK_3A903EEF495B2A54` FOREIGN KEY (`iz_intervisiegroep_id`) REFERENCES `iz_intervisiegroepen` (`id`),
  ADD CONSTRAINT `FK_3A903EEFD3124B3F` FOREIGN KEY (`iz_deelnemer_id`) REFERENCES `iz_deelnemers` (`id`);

--
-- Beperkingen voor tabel `iz_deelnemers_iz_projecten`
--
ALTER TABLE `iz_deelnemers_iz_projecten`
  ADD CONSTRAINT `FK_65A512DB56CEA1A9` FOREIGN KEY (`iz_project_id`) REFERENCES `iz_projecten` (`id`);

--
-- Beperkingen voor tabel `iz_documenten`
--
ALTER TABLE `iz_documenten`
  ADD CONSTRAINT `FK_C7F213503D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `iz_doelstellingen`
--
ALTER TABLE `iz_doelstellingen`
  ADD CONSTRAINT `FK_D76C6C73166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `iz_projecten` (`id`),
  ADD CONSTRAINT `FK_D76C6C73A13D3FD8` FOREIGN KEY (`stadsdeel`) REFERENCES `werkgebieden` (`naam`);

--
-- Beperkingen voor tabel `iz_hulpaanbod_hulpvraagsoort`
--
ALTER TABLE `iz_hulpaanbod_hulpvraagsoort`
  ADD CONSTRAINT `FK_D839A990950213F` FOREIGN KEY (`hulpvraagsoort_id`) REFERENCES `iz_hulpvraagsoorten` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_D839A990B42008F3` FOREIGN KEY (`hulpaanbod_id`) REFERENCES `iz_koppelingen` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `iz_hulpvraag_succesindicatorfinancieel`
--
ALTER TABLE `iz_hulpvraag_succesindicatorfinancieel`
  ADD CONSTRAINT `FK_3A3B526F3FEB2492` FOREIGN KEY (`succesindicatorfinancieel_id`) REFERENCES `iz_succesindicatoren` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_3A3B526FA8450D8C` FOREIGN KEY (`hulpvraag_id`) REFERENCES `iz_koppelingen` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `iz_hulpvraag_succesindicatorparticipatie`
--
ALTER TABLE `iz_hulpvraag_succesindicatorparticipatie`
  ADD CONSTRAINT `FK_128F913865A9F272` FOREIGN KEY (`succesindicatorparticipatie_id`) REFERENCES `iz_succesindicatoren` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_128F9138A8450D8C` FOREIGN KEY (`hulpvraag_id`) REFERENCES `iz_koppelingen` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `iz_hulpvraag_succesindicatorpersoonlijk`
--
ALTER TABLE `iz_hulpvraag_succesindicatorpersoonlijk`
  ADD CONSTRAINT `FK_BC9D7F44A8450D8C` FOREIGN KEY (`hulpvraag_id`) REFERENCES `iz_koppelingen` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_BC9D7F44F9892974` FOREIGN KEY (`succesindicatorpersoonlijk_id`) REFERENCES `iz_succesindicatoren` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `iz_intakes`
--
ALTER TABLE `iz_intakes`
  ADD CONSTRAINT `FK_11EFC53D3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `iz_intake_zrm`
--
ALTER TABLE `iz_intake_zrm`
  ADD CONSTRAINT `FK_C84288B3733DE450` FOREIGN KEY (`intake_id`) REFERENCES `iz_intakes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_C84288B3C8250F57` FOREIGN KEY (`zrm_id`) REFERENCES `zrm_reports` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `iz_intervisiegroepen`
--
ALTER TABLE `iz_intervisiegroepen`
  ADD CONSTRAINT `FK_86CA227E3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `iz_koppelingen`
--
ALTER TABLE `iz_koppelingen`
  ADD CONSTRAINT `FK_24E5FDC2166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `iz_projecten` (`id`),
  ADD CONSTRAINT `FK_24E5FDC23D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`),
  ADD CONSTRAINT `FK_24E5FDC28B6598D9` FOREIGN KEY (`iz_eindekoppeling_id`) REFERENCES `iz_eindekoppelingen` (`id`),
  ADD CONSTRAINT `FK_24E5FDC2950213F` FOREIGN KEY (`hulpvraagsoort_id`) REFERENCES `iz_hulpvraagsoorten` (`id`),
  ADD CONSTRAINT `FK_24E5FDC2C9788B0A` FOREIGN KEY (`voorkeurGeslacht_id`) REFERENCES `geslachten` (`id`),
  ADD CONSTRAINT `FK_24E5FDC2D3124B3F` FOREIGN KEY (`iz_deelnemer_id`) REFERENCES `iz_deelnemers` (`id`);

--
-- Beperkingen voor tabel `iz_koppeling_doelgroep`
--
ALTER TABLE `iz_koppeling_doelgroep`
  ADD CONSTRAINT `FK_8E6CE05D5C6E6B2` FOREIGN KEY (`koppeling_id`) REFERENCES `iz_koppelingen` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_8E6CE05DE5A2DFCE` FOREIGN KEY (`doelgroep_id`) REFERENCES `iz_doelgroepen` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `iz_matchingklant_doelgroep`
--
ALTER TABLE `iz_matchingklant_doelgroep`
  ADD CONSTRAINT `FK_9A957F94CC045EED` FOREIGN KEY (`matchingklant_id`) REFERENCES `iz_matching_klanten` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_9A957F94E5A2DFCE` FOREIGN KEY (`doelgroep_id`) REFERENCES `iz_doelgroepen` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `iz_matchingvrijwilliger_doelgroep`
--
ALTER TABLE `iz_matchingvrijwilliger_doelgroep`
  ADD CONSTRAINT `FK_AA83F9B42B829AB5` FOREIGN KEY (`matchingvrijwilliger_id`) REFERENCES `iz_matching_vrijwilligers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_AA83F9B4E5A2DFCE` FOREIGN KEY (`doelgroep_id`) REFERENCES `iz_doelgroepen` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `iz_matchingvrijwilliger_hulpvraagsoort`
--
ALTER TABLE `iz_matchingvrijwilliger_hulpvraagsoort`
  ADD CONSTRAINT `FK_11DF7DC02B829AB5` FOREIGN KEY (`matchingvrijwilliger_id`) REFERENCES `iz_matching_vrijwilligers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_11DF7DC0950213F` FOREIGN KEY (`hulpvraagsoort_id`) REFERENCES `iz_hulpvraagsoorten` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `iz_matching_klanten`
--
ALTER TABLE `iz_matching_klanten`
  ADD CONSTRAINT `FK_A5321A4355FE26E1` FOREIGN KEY (`iz_klant_id`) REFERENCES `iz_deelnemers` (`id`),
  ADD CONSTRAINT `FK_A5321A43950213F` FOREIGN KEY (`hulpvraagsoort_id`) REFERENCES `iz_hulpvraagsoorten` (`id`);

--
-- Beperkingen voor tabel `iz_matching_vrijwilligers`
--
ALTER TABLE `iz_matching_vrijwilligers`
  ADD CONSTRAINT `FK_1CA45FA7C99F99BF` FOREIGN KEY (`iz_vrijwilliger_id`) REFERENCES `iz_deelnemers` (`id`);

--
-- Beperkingen voor tabel `iz_reserveringen`
--
ALTER TABLE `iz_reserveringen`
  ADD CONSTRAINT `FK_B9D71E143D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`),
  ADD CONSTRAINT `FK_B9D71E14A8450D8C` FOREIGN KEY (`hulpvraag_id`) REFERENCES `iz_koppelingen` (`id`),
  ADD CONSTRAINT `FK_B9D71E14B42008F3` FOREIGN KEY (`hulpaanbod_id`) REFERENCES `iz_koppelingen` (`id`);

--
-- Beperkingen voor tabel `iz_verslagen`
--
ALTER TABLE `iz_verslagen`
  ADD CONSTRAINT `FK_570FE99B3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `klanten`
--
ALTER TABLE `klanten`
  ADD CONSTRAINT `FK_F538C5BC1C729A47` FOREIGN KEY (`geslacht_id`) REFERENCES `geslachten` (`id`),
  ADD CONSTRAINT `FK_F538C5BC1D103C3F` FOREIGN KEY (`laste_intake_id`) REFERENCES `intakes` (`id`),
  ADD CONSTRAINT `FK_F538C5BC46708ED5` FOREIGN KEY (`werkgebied`) REFERENCES `werkgebieden` (`naam`),
  ADD CONSTRAINT `FK_F538C5BC8B2671BD` FOREIGN KEY (`huidigeStatus_id`) REFERENCES `inloop_dossier_statussen` (`id`),
  ADD CONSTRAINT `FK_F538C5BCCECBFEB7` FOREIGN KEY (`nationaliteit_id`) REFERENCES `nationaliteiten` (`id`),
  ADD CONSTRAINT `FK_F538C5BCFB02B9C2` FOREIGN KEY (`postcodegebied`) REFERENCES `ggw_gebieden` (`naam`);

--
-- Beperkingen voor tabel `mw_documenten`
--
ALTER TABLE `mw_documenten`
  ADD CONSTRAINT `FK_99E478283C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`),
  ADD CONSTRAINT `FK_99E478283D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `odp_coordinatoren`
--
ALTER TABLE `odp_coordinatoren`
  ADD CONSTRAINT `FK_62BCCDB53D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `odp_deelnemers`
--
ALTER TABLE `odp_deelnemers`
  ADD CONSTRAINT `FK_202839993C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`),
  ADD CONSTRAINT `FK_202839993D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`),
  ADD CONSTRAINT `FK_20283999C0B11400` FOREIGN KEY (`woningbouwcorporatie_id`) REFERENCES `odp_woningbouwcorporaties` (`id`),
  ADD CONSTRAINT `FK_20283999ECDAD1A9` FOREIGN KEY (`afsluiting_id`) REFERENCES `odp_afsluitingen` (`id`);

--
-- Beperkingen voor tabel `odp_deelnemer_document`
--
ALTER TABLE `odp_deelnemer_document`
  ADD CONSTRAINT `FK_9BA61CC55DFA57A1` FOREIGN KEY (`deelnemer_id`) REFERENCES `odp_deelnemers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_9BA61CC5C33F7837` FOREIGN KEY (`document_id`) REFERENCES `odp_documenten` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `odp_deelnemer_verslag`
--
ALTER TABLE `odp_deelnemer_verslag`
  ADD CONSTRAINT `FK_F8F75D6A5DFA57A1` FOREIGN KEY (`deelnemer_id`) REFERENCES `odp_deelnemers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_F8F75D6AD949475D` FOREIGN KEY (`verslag_id`) REFERENCES `odp_verslagen` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `odp_documenten`
--
ALTER TABLE `odp_documenten`
  ADD CONSTRAINT `FK_6E6F9FD53D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `odp_huuraanbiedingen`
--
ALTER TABLE `odp_huuraanbiedingen`
  ADD CONSTRAINT `FK_FA204F873D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`),
  ADD CONSTRAINT `FK_FA204F877E18485D` FOREIGN KEY (`verhuurder_id`) REFERENCES `odp_deelnemers` (`id`),
  ADD CONSTRAINT `FK_FA204F87ECDAD1A9` FOREIGN KEY (`afsluiting_id`) REFERENCES `odp_afsluitingen` (`id`);

--
-- Beperkingen voor tabel `odp_huuraanbod_verslag`
--
ALTER TABLE `odp_huuraanbod_verslag`
  ADD CONSTRAINT `FK_9B2DE75B656E2280` FOREIGN KEY (`huuraanbod_id`) REFERENCES `odp_huuraanbiedingen` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_9B2DE75BD949475D` FOREIGN KEY (`verslag_id`) REFERENCES `odp_verslagen` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `odp_huurovereenkomsten`
--
ALTER TABLE `odp_huurovereenkomsten`
  ADD CONSTRAINT `FK_453FF4A63D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`),
  ADD CONSTRAINT `FK_453FF4A645DA3BB7` FOREIGN KEY (`huurverzoek_id`) REFERENCES `odp_huurverzoeken` (`id`),
  ADD CONSTRAINT `FK_453FF4A6656E2280` FOREIGN KEY (`huuraanbod_id`) REFERENCES `odp_huuraanbiedingen` (`id`),
  ADD CONSTRAINT `FK_453FF4A6ECDAD1A9` FOREIGN KEY (`afsluiting_id`) REFERENCES `odp_afsluitingen` (`id`);

--
-- Beperkingen voor tabel `odp_huurovereenkomst_document`
--
ALTER TABLE `odp_huurovereenkomst_document`
  ADD CONSTRAINT `FK_7B9A48A7870B85BC` FOREIGN KEY (`huurovereenkomst_id`) REFERENCES `odp_huurovereenkomsten` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_7B9A48A7C33F7837` FOREIGN KEY (`document_id`) REFERENCES `odp_documenten` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `odp_huurovereenkomst_verslag`
--
ALTER TABLE `odp_huurovereenkomst_verslag`
  ADD CONSTRAINT `FK_114A2160870B85BC` FOREIGN KEY (`huurovereenkomst_id`) REFERENCES `odp_huurovereenkomsten` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_114A2160D949475D` FOREIGN KEY (`verslag_id`) REFERENCES `odp_verslagen` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `odp_huurverzoeken`
--
ALTER TABLE `odp_huurverzoeken`
  ADD CONSTRAINT `FK_588F4E963D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`),
  ADD CONSTRAINT `FK_588F4E969E4835DA` FOREIGN KEY (`huurder_id`) REFERENCES `odp_deelnemers` (`id`),
  ADD CONSTRAINT `FK_588F4E96ECDAD1A9` FOREIGN KEY (`afsluiting_id`) REFERENCES `odp_afsluitingen` (`id`);

--
-- Beperkingen voor tabel `odp_huurverzoek_verslag`
--
ALTER TABLE `odp_huurverzoek_verslag`
  ADD CONSTRAINT `FK_46CB48C145DA3BB7` FOREIGN KEY (`huurverzoek_id`) REFERENCES `odp_huurverzoeken` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_46CB48C1D949475D` FOREIGN KEY (`verslag_id`) REFERENCES `odp_verslagen` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `odp_intakes`
--
ALTER TABLE `odp_intakes`
  ADD CONSTRAINT `FK_3A1E7F773D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`),
  ADD CONSTRAINT `FK_3A1E7F775DFA57A1` FOREIGN KEY (`deelnemer_id`) REFERENCES `odp_deelnemers` (`id`);

--
-- Beperkingen voor tabel `odp_verslagen`
--
ALTER TABLE `odp_verslagen`
  ADD CONSTRAINT `FK_762D3F773D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `oekklant_oekdossierstatus`
--
ALTER TABLE `oekklant_oekdossierstatus`
  ADD CONSTRAINT `FK_1EF9C0A61833A719` FOREIGN KEY (`oekklant_id`) REFERENCES `oek_klanten` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_1EF9C0A6B689C3C1` FOREIGN KEY (`oekdossierstatus_id`) REFERENCES `oek_dossier_statussen` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `oek_deelnames`
--
ALTER TABLE `oek_deelnames`
  ADD CONSTRAINT `FK_A6C1F201120845B9` FOREIGN KEY (`oekTraining_id`) REFERENCES `oek_trainingen` (`id`),
  ADD CONSTRAINT `FK_A6C1F2014DF034FD` FOREIGN KEY (`oekDeelnameStatus_id`) REFERENCES `oek_deelname_statussen` (`id`),
  ADD CONSTRAINT `FK_A6C1F201E145C54F` FOREIGN KEY (`oekKlant_id`) REFERENCES `oek_klanten` (`id`);

--
-- Beperkingen voor tabel `oek_deelname_statussen`
--
ALTER TABLE `oek_deelname_statussen`
  ADD CONSTRAINT `FK_4CBB9BCD6D7A74BD` FOREIGN KEY (`oekDeelname_id`) REFERENCES `oek_deelnames` (`id`);

--
-- Beperkingen voor tabel `oek_documenten`
--
ALTER TABLE `oek_documenten`
  ADD CONSTRAINT `FK_CE730FA23D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `oek_dossier_statussen`
--
ALTER TABLE `oek_dossier_statussen`
  ADD CONSTRAINT `FK_D8FAC7653D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`),
  ADD CONSTRAINT `FK_D8FAC765D8B4CBDF` FOREIGN KEY (`verwijzing_id`) REFERENCES `oek_verwijzingen` (`id`),
  ADD CONSTRAINT `FK_D8FAC765E145C54F` FOREIGN KEY (`oekKlant_id`) REFERENCES `oek_klanten` (`id`);

--
-- Beperkingen voor tabel `oek_klanten`
--
ALTER TABLE `oek_klanten`
  ADD CONSTRAINT `FK_A501F8F723473A1F` FOREIGN KEY (`oekDossierStatus_id`) REFERENCES `oek_dossier_statussen` (`id`),
  ADD CONSTRAINT `FK_A501F8F73C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`),
  ADD CONSTRAINT `FK_A501F8F73D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`),
  ADD CONSTRAINT `FK_A501F8F7B99C329A` FOREIGN KEY (`oekAfsluiting_id`) REFERENCES `oek_dossier_statussen` (`id`),
  ADD CONSTRAINT `FK_A501F8F7C45AE93C` FOREIGN KEY (`oekAanmelding_id`) REFERENCES `oek_dossier_statussen` (`id`);

--
-- Beperkingen voor tabel `oek_lidmaatschappen`
--
ALTER TABLE `oek_lidmaatschappen`
  ADD CONSTRAINT `FK_7B0B7DFF43B3F0A5` FOREIGN KEY (`oekGroep_id`) REFERENCES `oek_groepen` (`id`),
  ADD CONSTRAINT `FK_7B0B7DFFE145C54F` FOREIGN KEY (`oekKlant_id`) REFERENCES `oek_klanten` (`id`);

--
-- Beperkingen voor tabel `oek_memos`
--
ALTER TABLE `oek_memos`
  ADD CONSTRAINT `FK_8F8DED693D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `oek_trainingen`
--
ALTER TABLE `oek_trainingen`
  ADD CONSTRAINT `FK_B0D582D43B3F0A5` FOREIGN KEY (`oekGroep_id`) REFERENCES `oek_groepen` (`id`);

--
-- Beperkingen voor tabel `oek_vrijwilligers`
--
ALTER TABLE `oek_vrijwilligers`
  ADD CONSTRAINT `FK_2D75CD343D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`),
  ADD CONSTRAINT `FK_2D75CD3476B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `vrijwilligers` (`id`);

--
-- Beperkingen voor tabel `oek_vrijwilliger_document`
--
ALTER TABLE `oek_vrijwilliger_document`
  ADD CONSTRAINT `FK_725F2FCA76B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `oek_vrijwilligers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_725F2FCAC33F7837` FOREIGN KEY (`document_id`) REFERENCES `oek_documenten` (`id`);

--
-- Beperkingen voor tabel `oek_vrijwilliger_memo`
--
ALTER TABLE `oek_vrijwilliger_memo`
  ADD CONSTRAINT `FK_5ED2E90C76B43BDC` FOREIGN KEY (`vrijwilliger_id`) REFERENCES `oek_vrijwilligers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_5ED2E90CB4D32439` FOREIGN KEY (`memo_id`) REFERENCES `oek_memos` (`id`);

--
-- Beperkingen voor tabel `pfo_clienten`
--
ALTER TABLE `pfo_clienten`
  ADD CONSTRAINT `FK_3C237EDD1C729A47` FOREIGN KEY (`geslacht_id`) REFERENCES `geslachten` (`id`),
  ADD CONSTRAINT `FK_3C237EDD27025694` FOREIGN KEY (`groep`) REFERENCES `pfo_groepen` (`id`),
  ADD CONSTRAINT `FK_3C237EDD3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`),
  ADD CONSTRAINT `FK_3C237EDDC41BE3` FOREIGN KEY (`aard_relatie`) REFERENCES `pfo_aard_relaties` (`id`);

--
-- Beperkingen voor tabel `pfo_clienten_documenten`
--
ALTER TABLE `pfo_clienten_documenten`
  ADD CONSTRAINT `FK_A14FB5DE19EB6921` FOREIGN KEY (`client_id`) REFERENCES `pfo_clienten` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_A14FB5DEC33F7837` FOREIGN KEY (`document_id`) REFERENCES `pfo_documenten` (`id`);

--
-- Beperkingen voor tabel `pfo_clienten_supportgroups`
--
ALTER TABLE `pfo_clienten_supportgroups`
  ADD CONSTRAINT `FK_39F077D963E315A` FOREIGN KEY (`pfo_client_id`) REFERENCES `pfo_clienten` (`id`),
  ADD CONSTRAINT `FK_73EA8C843926A77` FOREIGN KEY (`pfo_supportgroup_client_id`) REFERENCES `pfo_clienten` (`id`);

--
-- Beperkingen voor tabel `pfo_clienten_verslagen`
--
ALTER TABLE `pfo_clienten_verslagen`
  ADD CONSTRAINT `FK_EC92AD411E813AB1` FOREIGN KEY (`pfo_verslag_id`) REFERENCES `pfo_verslagen` (`id`),
  ADD CONSTRAINT `FK_EC92AD4163E315A` FOREIGN KEY (`pfo_client_id`) REFERENCES `pfo_clienten` (`id`);

--
-- Beperkingen voor tabel `pfo_documenten`
--
ALTER TABLE `pfo_documenten`
  ADD CONSTRAINT `FK_4099D0893D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `pfo_verslagen`
--
ALTER TABLE `pfo_verslagen`
  ADD CONSTRAINT `FK_346FE20A3D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `postcodes`
--
ALTER TABLE `postcodes`
  ADD CONSTRAINT `FK_71DDD65DA13D3FD8` FOREIGN KEY (`stadsdeel`) REFERENCES `werkgebieden` (`naam`),
  ADD CONSTRAINT `FK_71DDD65DFB02B9C2` FOREIGN KEY (`postcodegebied`) REFERENCES `ggw_gebieden` (`naam`);

--
-- Beperkingen voor tabel `registraties`
--
ALTER TABLE `registraties`
  ADD CONSTRAINT `FK_FB4123F43C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`),
  ADD CONSTRAINT `FK_FB4123F44947630C` FOREIGN KEY (`locatie_id`) REFERENCES `locaties` (`id`);

--
-- Beperkingen voor tabel `schorsingen`
--
ALTER TABLE `schorsingen`
  ADD CONSTRAINT `FK_9E658EBF3C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`),
  ADD CONSTRAINT `FK_9E658EBF4947630C` FOREIGN KEY (`locatie_id`) REFERENCES `locaties` (`id`);

--
-- Beperkingen voor tabel `schorsingen_redenen`
--
ALTER TABLE `schorsingen_redenen`
  ADD CONSTRAINT `FK_BB99D0FFA52077DE` FOREIGN KEY (`schorsing_id`) REFERENCES `schorsingen` (`id`),
  ADD CONSTRAINT `FK_BB99D0FFD29703A5` FOREIGN KEY (`reden_id`) REFERENCES `redenen` (`id`);

--
-- Beperkingen voor tabel `schorsing_locatie`
--
ALTER TABLE `schorsing_locatie`
  ADD CONSTRAINT `FK_52DA67664947630C` FOREIGN KEY (`locatie_id`) REFERENCES `locaties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_52DA6766A52077DE` FOREIGN KEY (`schorsing_id`) REFERENCES `schorsingen` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `verslagen`
--
ALTER TABLE `verslagen`
  ADD CONSTRAINT `FK_2BBABA713C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`),
  ADD CONSTRAINT `FK_2BBABA713D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`);

--
-- Beperkingen voor tabel `vrijwilligers`
--
ALTER TABLE `vrijwilligers`
  ADD CONSTRAINT `FK_F0C4D2371994904A` FOREIGN KEY (`land_id`) REFERENCES `landen` (`id`),
  ADD CONSTRAINT `FK_F0C4D2371C729A47` FOREIGN KEY (`geslacht_id`) REFERENCES `geslachten` (`id`),
  ADD CONSTRAINT `FK_F0C4D2373D707F64` FOREIGN KEY (`medewerker_id`) REFERENCES `medewerkers` (`id`),
  ADD CONSTRAINT `FK_F0C4D23746708ED5` FOREIGN KEY (`werkgebied`) REFERENCES `werkgebieden` (`naam`),
  ADD CONSTRAINT `FK_F0C4D237CECBFEB7` FOREIGN KEY (`nationaliteit_id`) REFERENCES `nationaliteiten` (`id`),
  ADD CONSTRAINT `FK_F0C4D237FB02B9C2` FOREIGN KEY (`postcodegebied`) REFERENCES `ggw_gebieden` (`naam`);

--
-- Beperkingen voor tabel `zrm_reports`
--
ALTER TABLE `zrm_reports`
  ADD CONSTRAINT `FK_C8EF119C3C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`);

--
-- Beperkingen voor tabel `zrm_v2_reports`
--
ALTER TABLE `zrm_v2_reports`
  ADD CONSTRAINT `FK_751519083C427B2F` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`);
