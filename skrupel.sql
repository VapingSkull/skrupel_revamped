-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 26. Okt 2024 um 11:21
-- Server-Version: 10.11.6-MariaDB-0+deb12u1
-- PHP-Version: 8.2.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `skrupel`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_anomalien`
--

CREATE TABLE `skrupel_anomalien` (
  `id` int(11) NOT NULL,
  `art` tinyint(4) NOT NULL DEFAULT 0,
  `x_pos` int(11) NOT NULL DEFAULT 0,
  `y_pos` int(11) NOT NULL DEFAULT 0,
  `extra` varchar(255) NOT NULL DEFAULT '',
  `sicht` varchar(10) NOT NULL DEFAULT '',
  `spiel` int(11) NOT NULL DEFAULT 0,
  `sicht_1` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_2` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_3` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_4` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_5` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_6` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_7` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_8` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_9` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_10` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_begegnung`
--

CREATE TABLE `skrupel_begegnung` (
  `id` int(11) NOT NULL,
  `partei_a` tinyint(4) NOT NULL,
  `partei_b` tinyint(4) NOT NULL,
  `spiel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_chat`
--

CREATE TABLE `skrupel_chat` (
  `id` int(11) NOT NULL,
  `spiel` tinyint(4) NOT NULL DEFAULT 0,
  `datum` int(11) NOT NULL,
  `text` mediumtext NOT NULL,
  `an` int(11) NOT NULL DEFAULT 0,
  `von` varchar(50) NOT NULL DEFAULT '',
  `farbe` varchar(7) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_forum_beitrag`
--

CREATE TABLE `skrupel_forum_beitrag` (
  `id` int(11) NOT NULL,
  `forum` tinyint(4) NOT NULL DEFAULT 0,
  `thema` int(11) NOT NULL DEFAULT 0,
  `datum` int(11) NOT NULL,
  `beitrag` mediumtext NOT NULL,
  `verfasser` varchar(30) NOT NULL DEFAULT '',
  `spielerid` tinyint(4) NOT NULL DEFAULT 0,
  `spiel` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_forum_thema`
--

CREATE TABLE `skrupel_forum_thema` (
  `id` int(11) NOT NULL,
  `forum` tinyint(4) NOT NULL DEFAULT 0,
  `icon` tinyint(4) NOT NULL DEFAULT 0,
  `thema` varchar(50) NOT NULL DEFAULT '',
  `beginner` varchar(30) NOT NULL DEFAULT '',
  `antworten` tinyint(4) NOT NULL DEFAULT 0,
  `letzter` int(11) NOT NULL,
  `spiel` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_huellen`
--

CREATE TABLE `skrupel_huellen` (
  `id` int(11) NOT NULL,
  `baid` int(11) NOT NULL DEFAULT 0,
  `klasse` tinyint(4) NOT NULL DEFAULT 0,
  `bild_gross` varchar(255) NOT NULL DEFAULT '',
  `bild_klein` varchar(255) NOT NULL DEFAULT '',
  `crew` int(11) NOT NULL DEFAULT 0,
  `masse` int(11) NOT NULL DEFAULT 0,
  `tank` int(11) NOT NULL DEFAULT 0,
  `fracht` int(11) NOT NULL DEFAULT 0,
  `antriebe` tinyint(4) NOT NULL DEFAULT 0,
  `energetik` tinyint(4) NOT NULL DEFAULT 0,
  `projektile` tinyint(4) NOT NULL DEFAULT 0,
  `hangar` tinyint(4) NOT NULL DEFAULT 0,
  `klasse_name` varchar(25) NOT NULL DEFAULT '',
  `rasse` varchar(255) NOT NULL DEFAULT '',
  `fertigkeiten` varchar(255) NOT NULL DEFAULT '',
  `techlevel` tinyint(4) NOT NULL DEFAULT 0,
  `spiel` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_info`
--

CREATE TABLE `skrupel_info` (
  `version` varchar(15) NOT NULL DEFAULT '',
  `chat` tinyint(4) NOT NULL DEFAULT 0,
  `anleitung` tinyint(4) NOT NULL DEFAULT 0,
  `forum` tinyint(4) NOT NULL DEFAULT 0,
  `forum_url` varchar(255) NOT NULL DEFAULT '',
  `stat_spiele` int(11) NOT NULL DEFAULT 0,
  `extend` varchar(255) NOT NULL DEFAULT '',
  `serial` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `skrupel_info`
--

INSERT INTO `skrupel_info` (`version`, `chat`, `anleitung`, `forum`, `forum_url`, `stat_spiele`, `extend`, `serial`) VALUES
('0.974_nightly', 0, 1, 0, '  http://', 5, '10000000000000000000000000000000000000000000000000', 'SFUR8293854ISFURGGF!');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_kampf`
--

CREATE TABLE `skrupel_kampf` (
  `id` int(11) NOT NULL,
  `schiff_id_1` int(11) NOT NULL DEFAULT 0,
  `schiff_id_2` int(11) NOT NULL DEFAULT 0,
  `name_1` varchar(255) NOT NULL DEFAULT '',
  `name_2` varchar(255) NOT NULL DEFAULT '',
  `rasse_1` varchar(255) NOT NULL DEFAULT '',
  `rasse_2` varchar(255) NOT NULL DEFAULT '',
  `bild_1` varchar(255) NOT NULL DEFAULT '',
  `bild_2` varchar(255) NOT NULL DEFAULT '',
  `datum` int(11) NOT NULL,
  `energetik_1` mediumtext NOT NULL,
  `energetik_2` mediumtext NOT NULL,
  `projektile_1` mediumtext NOT NULL,
  `projektile_2` mediumtext NOT NULL,
  `hangar_1` mediumtext NOT NULL,
  `hangar_2` mediumtext NOT NULL,
  `schild_1` mediumtext NOT NULL,
  `schild_2` mediumtext NOT NULL,
  `schaden_1` mediumtext NOT NULL,
  `schaden_2` mediumtext NOT NULL,
  `art` tinyint(4) NOT NULL DEFAULT 0,
  `spiel` int(11) NOT NULL DEFAULT 0,
  `crew_1` mediumtext NOT NULL,
  `crew_2` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_ki_neuebasen`
--

CREATE TABLE `skrupel_ki_neuebasen` (
  `id` int(11) NOT NULL,
  `planeten_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_ki_objekte`
--

CREATE TABLE `skrupel_ki_objekte` (
  `id` int(11) NOT NULL,
  `objekt_id` int(11) NOT NULL,
  `comp_id` int(11) NOT NULL,
  `extra` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_ki_planeten`
--

CREATE TABLE `skrupel_ki_planeten` (
  `id` int(11) NOT NULL,
  `planeten_id` int(11) NOT NULL,
  `comp_id` int(11) NOT NULL,
  `extra` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_ki_spezialschiffe`
--

CREATE TABLE `skrupel_ki_spezialschiffe` (
  `id` int(11) NOT NULL,
  `schiff_id` int(11) NOT NULL,
  `spezial_mission` varchar(64) DEFAULT NULL,
  `aktiv` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_konplaene`
--

CREATE TABLE `skrupel_konplaene` (
  `id` int(11) NOT NULL,
  `besitzer` int(11) NOT NULL DEFAULT 0,
  `spiel` int(11) NOT NULL DEFAULT 0,
  `rasse` varchar(255) NOT NULL DEFAULT '',
  `klasse` varchar(25) NOT NULL DEFAULT '',
  `klasse_id` int(11) NOT NULL DEFAULT 0,
  `techlevel` tinyint(4) NOT NULL DEFAULT 0,
  `sonstiges` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_languages`
--

CREATE TABLE `skrupel_languages` (
  `lid` int(11) NOT NULL,
  `language` char(2) NOT NULL DEFAULT 'de',
  `page` varchar(120) NOT NULL,
  `phrase` varchar(255) NOT NULL,
  `text` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_nebel`
--

CREATE TABLE `skrupel_nebel` (
  `id` int(11) NOT NULL,
  `spiel` int(11) NOT NULL DEFAULT 0,
  `x_a` int(11) NOT NULL DEFAULT 0,
  `y_a` int(11) NOT NULL DEFAULT 0,
  `sicht` varchar(10) NOT NULL DEFAULT '',
  `x_e` int(11) NOT NULL DEFAULT 0,
  `y_e` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_neuigkeiten`
--

CREATE TABLE `skrupel_neuigkeiten` (
  `id` int(11) NOT NULL,
  `datum` int(11) NOT NULL,
  `art` tinyint(4) NOT NULL DEFAULT 0,
  `icon` varchar(255) NOT NULL DEFAULT '',
  `inhalt` mediumtext NOT NULL,
  `spieler_id` tinyint(4) NOT NULL DEFAULT 0,
  `spiel_id` int(11) NOT NULL DEFAULT 0,
  `sicher` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_ordner`
--

CREATE TABLE `skrupel_ordner` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL DEFAULT '',
  `besitzer` int(11) NOT NULL DEFAULT 0,
  `spiel` int(11) NOT NULL DEFAULT 0,
  `icon` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_planeten`
--

CREATE TABLE `skrupel_planeten` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `x_pos` int(11) NOT NULL DEFAULT 0,
  `y_pos` int(11) NOT NULL DEFAULT 0,
  `besitzer` tinyint(4) NOT NULL DEFAULT 0,
  `klasse` tinyint(4) NOT NULL DEFAULT 0,
  `bild` tinyint(4) NOT NULL DEFAULT 0,
  `temp` tinyint(4) NOT NULL DEFAULT 0,
  `kolonisten` int(11) NOT NULL DEFAULT 0,
  `lemin` int(11) NOT NULL DEFAULT 0,
  `min1` int(11) NOT NULL DEFAULT 0,
  `min2` int(11) NOT NULL DEFAULT 0,
  `min3` int(11) NOT NULL DEFAULT 0,
  `planet_lemin` int(11) NOT NULL DEFAULT 0,
  `planet_min1` int(11) NOT NULL DEFAULT 0,
  `planet_min2` int(11) NOT NULL DEFAULT 0,
  `planet_min3` int(11) NOT NULL DEFAULT 0,
  `konz_lemin` tinyint(4) NOT NULL DEFAULT 0,
  `konz_min1` tinyint(4) NOT NULL DEFAULT 0,
  `konz_min2` tinyint(4) NOT NULL DEFAULT 0,
  `konz_min3` tinyint(4) NOT NULL DEFAULT 0,
  `minen` int(11) NOT NULL DEFAULT 0,
  `vorrat` int(11) NOT NULL DEFAULT 0,
  `cantox` int(11) NOT NULL DEFAULT 0,
  `auto_minen` tinyint(4) NOT NULL DEFAULT 0,
  `fabriken` int(11) NOT NULL DEFAULT 0,
  `auto_fabriken` tinyint(4) NOT NULL DEFAULT 0,
  `auto_vorrat` tinyint(4) NOT NULL DEFAULT 0,
  `abwehr` int(11) NOT NULL DEFAULT 0,
  `auto_abwehr` tinyint(4) NOT NULL DEFAULT 0,
  `sternenbasis` tinyint(4) NOT NULL DEFAULT 0,
  `sternenbasis_name` varchar(20) NOT NULL DEFAULT '',
  `sternenbasis_id` int(11) NOT NULL DEFAULT 0,
  `sternenbasis_rasse` varchar(255) NOT NULL DEFAULT '',
  `kolonisten_new` int(11) NOT NULL DEFAULT 0,
  `kolonisten_spieler` int(11) NOT NULL DEFAULT 0,
  `sternenbasis_defense` tinyint(4) NOT NULL DEFAULT 0,
  `p_defense_gesamt` int(11) NOT NULL DEFAULT 0,
  `logbuch` mediumtext NOT NULL,
  `sicht` varchar(10) NOT NULL DEFAULT '',
  `sicht_beta` varchar(10) NOT NULL DEFAULT '',
  `sicht_temp` varchar(10) NOT NULL DEFAULT '',
  `spiel` int(11) NOT NULL DEFAULT 0,
  `native_id` int(11) NOT NULL DEFAULT 0,
  `native_name` varchar(50) NOT NULL DEFAULT '',
  `native_art` int(11) NOT NULL DEFAULT 0,
  `native_art_name` varchar(50) NOT NULL DEFAULT '',
  `native_abgabe` float NOT NULL DEFAULT 0,
  `native_bild` varchar(255) NOT NULL DEFAULT '',
  `native_text` varchar(255) NOT NULL DEFAULT '',
  `native_fert` varchar(255) NOT NULL DEFAULT '',
  `native_kol` bigint(20) NOT NULL DEFAULT 0,
  `osys_anzahl` tinyint(4) NOT NULL DEFAULT 0,
  `osys_1` tinyint(4) NOT NULL DEFAULT 0,
  `osys_2` tinyint(4) NOT NULL DEFAULT 0,
  `osys_3` tinyint(4) NOT NULL DEFAULT 0,
  `osys_4` tinyint(4) NOT NULL DEFAULT 0,
  `osys_5` tinyint(4) NOT NULL DEFAULT 0,
  `osys_6` tinyint(4) NOT NULL DEFAULT 0,
  `heimatplanet` tinyint(4) NOT NULL DEFAULT 0,
  `leichtebt` int(11) NOT NULL DEFAULT 0,
  `schwerebt` int(11) NOT NULL DEFAULT 0,
  `leichtebt_new` int(11) NOT NULL DEFAULT 0,
  `schwerebt_new` int(11) NOT NULL DEFAULT 0,
  `leichtebt_bau` int(11) NOT NULL DEFAULT 0,
  `schwerebt_bau` int(11) NOT NULL DEFAULT 0,
  `sternenbasis_art` tinyint(4) NOT NULL DEFAULT 0,
  `artefakt` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_1` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_2` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_3` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_4` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_5` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_6` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_7` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_8` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_9` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_10` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_1_beta` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_2_beta` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_3_beta` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_4_beta` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_5_beta` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_6_beta` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_7_beta` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_8_beta` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_9_beta` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_10_beta` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_politik`
--

CREATE TABLE `skrupel_politik` (
  `id` int(11) NOT NULL,
  `partei_a` int(11) NOT NULL DEFAULT 0,
  `partei_b` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `optionen` tinyint(4) NOT NULL DEFAULT 0,
  `spiel` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_politik_anfrage`
--

CREATE TABLE `skrupel_politik_anfrage` (
  `id` int(11) NOT NULL,
  `partei_a` tinyint(4) NOT NULL DEFAULT 0,
  `partei_b` tinyint(4) NOT NULL DEFAULT 0,
  `art` tinyint(4) NOT NULL DEFAULT 0,
  `zeit` tinyint(4) NOT NULL DEFAULT 0,
  `spiel` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_scan`
--

CREATE TABLE `skrupel_scan` (
  `id` int(11) NOT NULL,
  `spiel` int(11) NOT NULL,
  `besitzer` tinyint(4) NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_schiffe`
--

CREATE TABLE `skrupel_schiffe` (
  `id` int(11) NOT NULL,
  `besitzer` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `name` varchar(25) NOT NULL,
  `klasse` varchar(25) NOT NULL,
  `klasseid` int(11) NOT NULL DEFAULT 0,
  `volk` varchar(255) NOT NULL,
  `techlevel` tinyint(4) NOT NULL DEFAULT 0,
  `antrieb` tinyint(4) NOT NULL DEFAULT 0,
  `antrieb_anzahl` tinyint(4) NOT NULL DEFAULT 0,
  `kox` int(11) NOT NULL DEFAULT 0,
  `koy` int(11) NOT NULL DEFAULT 0,
  `flug` tinyint(4) NOT NULL DEFAULT 0,
  `warp` tinyint(4) NOT NULL DEFAULT 0,
  `plasmawarp` tinyint(4) NOT NULL DEFAULT 0,
  `zielx` int(11) NOT NULL DEFAULT 0,
  `ziely` int(11) NOT NULL DEFAULT 0,
  `zielid` int(11) NOT NULL DEFAULT 0,
  `crew` int(11) NOT NULL DEFAULT 0,
  `crewmax` int(11) NOT NULL DEFAULT 0,
  `lemin` int(11) NOT NULL DEFAULT 0,
  `leminmax` int(11) NOT NULL DEFAULT 0,
  `schaden` tinyint(4) NOT NULL DEFAULT 0,
  `mission` tinyint(4) NOT NULL DEFAULT 0,
  `frachtraum` int(11) NOT NULL DEFAULT 0,
  `masse` int(11) NOT NULL DEFAULT 0,
  `masse_gesamt` int(11) NOT NULL DEFAULT 0,
  `fracht_leute` int(11) NOT NULL DEFAULT 0,
  `fracht_cantox` int(11) NOT NULL DEFAULT 0,
  `fracht_vorrat` int(11) NOT NULL DEFAULT 0,
  `fracht_min1` int(11) NOT NULL DEFAULT 0,
  `fracht_min2` int(11) NOT NULL DEFAULT 0,
  `fracht_min3` int(11) NOT NULL DEFAULT 0,
  `bild_gross` varchar(255) NOT NULL DEFAULT '',
  `bild_klein` varchar(255) NOT NULL DEFAULT '',
  `energetik_stufe` tinyint(4) NOT NULL DEFAULT 0,
  `energetik_anzahl` tinyint(4) NOT NULL DEFAULT 0,
  `projektile_stufe` tinyint(4) NOT NULL DEFAULT 0,
  `projektile_anzahl` tinyint(4) NOT NULL DEFAULT 0,
  `hanger_anzahl` tinyint(4) NOT NULL DEFAULT 0,
  `schild` tinyint(4) NOT NULL DEFAULT 0,
  `fertigkeiten` varchar(255) NOT NULL DEFAULT '',
  `spezialmission` tinyint(4) NOT NULL DEFAULT 0,
  `tarnfeld` tinyint(4) NOT NULL DEFAULT 0,
  `scanner` tinyint(4) NOT NULL DEFAULT 0,
  `sprungtorbauid` int(11) NOT NULL DEFAULT 0,
  `logbuch` mediumtext NOT NULL,
  `routing_id` varchar(255) NOT NULL DEFAULT '',
  `routing_koord` varchar(255) NOT NULL DEFAULT '',
  `routing_status` tinyint(4) NOT NULL DEFAULT 0,
  `routing_mins` varchar(255) NOT NULL DEFAULT '',
  `routing_warp` tinyint(4) NOT NULL DEFAULT 0,
  `routing_rohstoff` tinyint(4) NOT NULL DEFAULT 0,
  `routing_tank` int(11) NOT NULL DEFAULT 0,
  `routing_schritt` tinyint(4) NOT NULL DEFAULT 0,
  `sicht` varchar(10) NOT NULL DEFAULT '',
  `spiel` int(11) NOT NULL DEFAULT 0,
  `aggro` tinyint(4) NOT NULL DEFAULT 0,
  `projektile` tinyint(4) NOT NULL DEFAULT 0,
  `projektile_auto` tinyint(4) NOT NULL DEFAULT 0,
  `s_x` int(11) NOT NULL DEFAULT 0,
  `s_y` int(11) NOT NULL DEFAULT 0,
  `erfahrung` tinyint(4) NOT NULL DEFAULT 0,
  `strecke` int(11) NOT NULL DEFAULT 0,
  `traktor_id` int(11) NOT NULL DEFAULT 0,
  `ordner` int(11) NOT NULL DEFAULT 0,
  `extra` varchar(255) NOT NULL DEFAULT '',
  `kox_old` int(11) NOT NULL DEFAULT 0,
  `koy_old` int(11) NOT NULL DEFAULT 0,
  `leichtebt` int(11) NOT NULL DEFAULT 0,
  `schwerebt` int(11) NOT NULL DEFAULT 0,
  `zusatzmodul` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_1` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_2` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_3` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_4` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_5` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_6` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_7` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_8` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_9` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_10` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_1_beta` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_2_beta` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_3_beta` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_4_beta` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_5_beta` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_6_beta` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_7_beta` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_8_beta` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_9_beta` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_10_beta` tinyint(4) NOT NULL DEFAULT 0,
  `temp_verfolgt` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_spiele`
--

CREATE TABLE `skrupel_spiele` (
  `id` int(11) NOT NULL,
  `sid` varchar(20) NOT NULL DEFAULT '',
  `phase` tinyint(4) NOT NULL DEFAULT 0,
  `ziel_id` tinyint(4) NOT NULL DEFAULT 0,
  `ziel_info` varchar(255) NOT NULL DEFAULT '',
  `module` varchar(255) NOT NULL DEFAULT '',
  `spieler_1` int(11) NOT NULL DEFAULT 0,
  `spieler_2` int(11) NOT NULL DEFAULT 0,
  `spieler_3` int(11) NOT NULL DEFAULT 0,
  `spieler_4` int(11) NOT NULL DEFAULT 0,
  `spieler_5` int(11) NOT NULL DEFAULT 0,
  `spieler_6` int(11) NOT NULL DEFAULT 0,
  `spieler_7` int(11) NOT NULL DEFAULT 0,
  `spieler_8` int(11) NOT NULL DEFAULT 0,
  `spieler_9` int(11) NOT NULL DEFAULT 0,
  `spieler_10` int(11) NOT NULL DEFAULT 0,
  `spieleranzahl` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_1_zug` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_2_zug` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_3_zug` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_4_zug` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_5_zug` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_6_zug` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_7_zug` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_8_zug` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_9_zug` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_10_zug` tinyint(4) NOT NULL DEFAULT 0,
  `lasttick` int(11) NOT NULL DEFAULT 0,
  `spieler_1_basen` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_1_planeten` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_1_schiffe` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_2_basen` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_2_planeten` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_2_schiffe` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_3_basen` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_3_planeten` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_3_schiffe` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_4_basen` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_4_planeten` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_4_schiffe` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_5_basen` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_5_planeten` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_5_schiffe` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_6_basen` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_6_planeten` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_6_schiffe` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_7_basen` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_7_planeten` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_7_schiffe` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_8_basen` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_8_planeten` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_8_schiffe` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_9_basen` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_9_planeten` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_9_schiffe` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_10_basen` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_10_planeten` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_10_schiffe` tinyint(4) NOT NULL DEFAULT 0,
  `letztermonat` mediumtext NOT NULL,
  `spieler_1_rasse` varchar(255) NOT NULL DEFAULT '',
  `spieler_2_rasse` varchar(255) NOT NULL DEFAULT '',
  `spieler_3_rasse` varchar(255) NOT NULL DEFAULT '',
  `spieler_4_rasse` varchar(255) NOT NULL DEFAULT '',
  `spieler_5_rasse` varchar(255) NOT NULL DEFAULT '',
  `spieler_6_rasse` varchar(255) NOT NULL DEFAULT '',
  `spieler_7_rasse` varchar(255) NOT NULL DEFAULT '',
  `spieler_8_rasse` varchar(255) NOT NULL DEFAULT '',
  `spieler_9_rasse` varchar(255) NOT NULL DEFAULT '',
  `spieler_10_rasse` varchar(255) NOT NULL DEFAULT '',
  `autozug` tinyint(4) NOT NULL DEFAULT 0,
  `nebel` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_1_platz` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_2_platz` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_3_platz` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_4_platz` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_5_platz` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_6_platz` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_7_platz` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_8_platz` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_9_platz` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_10_platz` tinyint(4) NOT NULL DEFAULT 0,
  `name` varchar(50) NOT NULL DEFAULT '',
  `runde` int(11) NOT NULL DEFAULT 0,
  `plasma_wahr` tinyint(4) NOT NULL DEFAULT 0,
  `plasma_max` tinyint(4) NOT NULL DEFAULT 0,
  `plasma_lang` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_admin` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_1_raus` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_2_raus` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_3_raus` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_4_raus` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_5_raus` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_6_raus` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_7_raus` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_8_raus` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_9_raus` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_10_raus` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_1_ziel` varchar(255) NOT NULL DEFAULT '',
  `spieler_2_ziel` varchar(255) NOT NULL DEFAULT '',
  `spieler_3_ziel` varchar(255) NOT NULL DEFAULT '',
  `spieler_4_ziel` varchar(255) NOT NULL DEFAULT '',
  `spieler_5_ziel` varchar(255) NOT NULL DEFAULT '',
  `spieler_6_ziel` varchar(255) NOT NULL DEFAULT '',
  `spieler_7_ziel` varchar(255) NOT NULL DEFAULT '',
  `spieler_8_ziel` varchar(255) NOT NULL DEFAULT '',
  `spieler_9_ziel` varchar(255) NOT NULL DEFAULT '',
  `spieler_10_ziel` varchar(255) NOT NULL DEFAULT '',
  `piraten_mitte` tinyint(4) NOT NULL DEFAULT 0,
  `piraten_aussen` tinyint(4) NOT NULL DEFAULT 0,
  `piraten_min` tinyint(4) NOT NULL DEFAULT 0,
  `piraten_max` tinyint(4) NOT NULL DEFAULT 0,
  `gewinner` varchar(255) NOT NULL DEFAULT '',
  `siegeranzahl` tinyint(4) NOT NULL DEFAULT 0,
  `spieler_1_rassename` varchar(40) NOT NULL DEFAULT '',
  `spieler_2_rassename` varchar(40) NOT NULL DEFAULT '',
  `spieler_3_rassename` varchar(40) NOT NULL DEFAULT '',
  `spieler_4_rassename` varchar(40) NOT NULL DEFAULT '',
  `spieler_5_rassename` varchar(40) NOT NULL DEFAULT '',
  `spieler_6_rassename` varchar(40) NOT NULL DEFAULT '',
  `spieler_7_rassename` varchar(40) NOT NULL DEFAULT '',
  `spieler_8_rassename` varchar(40) NOT NULL DEFAULT '',
  `spieler_9_rassename` varchar(40) NOT NULL DEFAULT '',
  `spieler_10_rassename` varchar(40) NOT NULL DEFAULT '',
  `oput` int(11) NOT NULL DEFAULT 0,
  `umfang` int(11) NOT NULL DEFAULT 0,
  `aufloesung` int(11) NOT NULL DEFAULT 0,
  `passwort` varchar(20) NOT NULL,
  `rassenerlaubt` varchar(255) NOT NULL,
  `kommentar` varchar(255) NOT NULL,
  `jabber` varchar(255) NOT NULL,
  `spieler_1_hash` char(20) NOT NULL,
  `spieler_2_hash` char(20) NOT NULL,
  `spieler_3_hash` char(20) NOT NULL,
  `spieler_4_hash` char(20) NOT NULL,
  `spieler_5_hash` char(20) NOT NULL,
  `spieler_6_hash` char(20) NOT NULL,
  `spieler_7_hash` char(20) NOT NULL,
  `spieler_8_hash` char(20) NOT NULL,
  `spieler_9_hash` char(20) NOT NULL,
  `spieler_10_hash` char(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_sternenbasen`
--

CREATE TABLE `skrupel_sternenbasen` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL DEFAULT '',
  `x_pos` int(11) NOT NULL DEFAULT 0,
  `y_pos` int(11) NOT NULL DEFAULT 0,
  `rasse` varchar(255) NOT NULL DEFAULT '',
  `planetid` int(11) NOT NULL DEFAULT 0,
  `besitzer` tinyint(4) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `schaden` tinyint(4) NOT NULL DEFAULT 0,
  `t_huelle` tinyint(4) NOT NULL DEFAULT 0,
  `t_antrieb` tinyint(4) NOT NULL DEFAULT 0,
  `t_energie` tinyint(4) NOT NULL DEFAULT 0,
  `t_explosiv` tinyint(4) NOT NULL DEFAULT 0,
  `defense` int(11) NOT NULL DEFAULT 0,
  `jaeger` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_antrieb_1` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_antrieb_2` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_antrieb_3` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_antrieb_4` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_antrieb_5` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_antrieb_6` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_antrieb_7` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_antrieb_8` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_antrieb_9` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_antrieb_10` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_energetik_1` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_energetik_2` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_energetik_3` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_energetik_4` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_energetik_5` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_energetik_6` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_energetik_7` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_energetik_8` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_energetik_9` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_energetik_10` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_projektile_1` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_projektile_2` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_projektile_3` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_projektile_4` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_projektile_5` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_projektile_6` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_projektile_7` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_projektile_8` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_projektile_9` tinyint(4) NOT NULL DEFAULT 0,
  `vorrat_projektile_10` tinyint(4) NOT NULL DEFAULT 0,
  `schiffbau_status` tinyint(4) NOT NULL DEFAULT 0,
  `schiffbau_klasse` tinyint(4) NOT NULL DEFAULT 0,
  `schiffbau_bild_gross` varchar(255) NOT NULL DEFAULT '',
  `schiffbau_bild_klein` varchar(255) NOT NULL DEFAULT '',
  `schiffbau_crew` int(11) NOT NULL DEFAULT 0,
  `schiffbau_masse` int(11) NOT NULL DEFAULT 0,
  `schiffbau_tank` int(11) NOT NULL DEFAULT 0,
  `schiffbau_fracht` int(11) NOT NULL DEFAULT 0,
  `schiffbau_antriebe` tinyint(4) NOT NULL DEFAULT 0,
  `schiffbau_energetik` tinyint(4) NOT NULL DEFAULT 0,
  `schiffbau_projektile` tinyint(4) NOT NULL DEFAULT 0,
  `schiffbau_hangar` tinyint(4) NOT NULL DEFAULT 0,
  `schiffbau_klasse_name` varchar(25) NOT NULL DEFAULT '',
  `schiffbau_rasse` varchar(255) NOT NULL DEFAULT '',
  `schiffbau_fertigkeiten` varchar(255) NOT NULL DEFAULT '',
  `schiffbau_energetik_stufe` tinyint(4) NOT NULL DEFAULT 0,
  `schiffbau_projektile_stufe` tinyint(4) NOT NULL DEFAULT 0,
  `schiffbau_techlevel` tinyint(4) NOT NULL DEFAULT 0,
  `schiffbau_antriebe_stufe` tinyint(4) NOT NULL DEFAULT 0,
  `schiffbau_name` varchar(25) NOT NULL DEFAULT '',
  `logbuch` mediumtext NOT NULL,
  `sicht` varchar(10) NOT NULL DEFAULT '',
  `spiel` int(11) NOT NULL DEFAULT 0,
  `schiffbau_extra` varchar(255) NOT NULL DEFAULT '',
  `art` tinyint(4) NOT NULL DEFAULT 0,
  `schiffbau_zusatz` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_1` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_2` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_3` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_4` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_5` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_6` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_7` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_8` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_9` tinyint(4) NOT NULL DEFAULT 0,
  `sicht_10` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skrupel_user`
--

CREATE TABLE `skrupel_user` (
  `id` int(11) NOT NULL,
  `nick` varchar(30) NOT NULL DEFAULT '',
  `passwort` varchar(64) NOT NULL DEFAULT '',
  `salt` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL DEFAULT '',
  `uid` varchar(20) NOT NULL DEFAULT '',
  `icq` varchar(20) NOT NULL DEFAULT '',
  `jabber` varchar(255) NOT NULL DEFAULT '',
  `homepage` text NOT NULL,
  `optionen` varchar(255) NOT NULL DEFAULT '',
  `chatfarbe` varchar(6) NOT NULL DEFAULT 'ffffff',
  `stat_teilnahme` int(11) NOT NULL DEFAULT 0,
  `stat_sieg` int(11) NOT NULL DEFAULT 0,
  `stat_schlacht` int(11) NOT NULL DEFAULT 0,
  `stat_schlacht_sieg` int(11) NOT NULL DEFAULT 0,
  `stat_kol_erobert` int(11) NOT NULL DEFAULT 0,
  `stat_lichtjahre` bigint(20) NOT NULL DEFAULT 0,
  `stat_monate` int(11) NOT NULL DEFAULT 0,
  `bildpfad` varchar(255) NOT NULL DEFAULT '',
  `avatar` varchar(255) NOT NULL DEFAULT '',
  `sprache` varchar(255) NOT NULL DEFAULT '',
  `portal_layout` char(20) NOT NULL DEFAULT 'classic',
  `portal_bann` varchar(1) NOT NULL DEFAULT '0',
  `portal_activity` char(20) NOT NULL DEFAULT '0',
  `profil_text` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `skrupel_anomalien`
--
ALTER TABLE `skrupel_anomalien`
  ADD PRIMARY KEY (`id`),
  ADD KEY `art` (`art`),
  ADD KEY `spiel` (`spiel`),
  ADD KEY `x_pos` (`x_pos`),
  ADD KEY `y_pos` (`y_pos`);

--
-- Indizes für die Tabelle `skrupel_begegnung`
--
ALTER TABLE `skrupel_begegnung`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spiel` (`spiel`);

--
-- Indizes für die Tabelle `skrupel_chat`
--
ALTER TABLE `skrupel_chat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indizes für die Tabelle `skrupel_forum_beitrag`
--
ALTER TABLE `skrupel_forum_beitrag`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `skrupel_forum_thema`
--
ALTER TABLE `skrupel_forum_thema`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `skrupel_huellen`
--
ALTER TABLE `skrupel_huellen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `klasse` (`klasse`),
  ADD KEY `spiel` (`spiel`),
  ADD KEY `baid` (`baid`);
ALTER TABLE `skrupel_huellen` ADD FULLTEXT KEY `klasse_name` (`klasse_name`);

--
-- Indizes für die Tabelle `skrupel_kampf`
--
ALTER TABLE `skrupel_kampf`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `schiff_id_1` (`schiff_id_1`),
  ADD KEY `schiff_id_2` (`schiff_id_2`),
  ADD KEY `spiel` (`spiel`);

--
-- Indizes für die Tabelle `skrupel_ki_neuebasen`
--
ALTER TABLE `skrupel_ki_neuebasen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `planeten_id` (`planeten_id`);

--
-- Indizes für die Tabelle `skrupel_ki_objekte`
--
ALTER TABLE `skrupel_ki_objekte`
  ADD PRIMARY KEY (`id`),
  ADD KEY `objekt_id` (`objekt_id`);

--
-- Indizes für die Tabelle `skrupel_ki_planeten`
--
ALTER TABLE `skrupel_ki_planeten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `planeten_id` (`planeten_id`);

--
-- Indizes für die Tabelle `skrupel_ki_spezialschiffe`
--
ALTER TABLE `skrupel_ki_spezialschiffe`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schiff_id` (`schiff_id`);

--
-- Indizes für die Tabelle `skrupel_konplaene`
--
ALTER TABLE `skrupel_konplaene`
  ADD PRIMARY KEY (`id`),
  ADD KEY `besitzer` (`besitzer`),
  ADD KEY `klasse_id` (`klasse_id`),
  ADD KEY `spiel` (`spiel`);
ALTER TABLE `skrupel_konplaene` ADD FULLTEXT KEY `rasse` (`rasse`);
ALTER TABLE `skrupel_konplaene` ADD FULLTEXT KEY `klasse` (`klasse`);

--
-- Indizes für die Tabelle `skrupel_languages`
--
ALTER TABLE `skrupel_languages`
  ADD PRIMARY KEY (`lid`),
  ADD KEY `language` (`language`);
ALTER TABLE `skrupel_languages` ADD FULLTEXT KEY `page` (`page`);
ALTER TABLE `skrupel_languages` ADD FULLTEXT KEY `phrase` (`phrase`);

--
-- Indizes für die Tabelle `skrupel_nebel`
--
ALTER TABLE `skrupel_nebel`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `skrupel_neuigkeiten`
--
ALTER TABLE `skrupel_neuigkeiten`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `sicher` (`sicher`),
  ADD KEY `spiel_id` (`spiel_id`),
  ADD KEY `art` (`art`);

--
-- Indizes für die Tabelle `skrupel_ordner`
--
ALTER TABLE `skrupel_ordner`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `skrupel_planeten`
--
ALTER TABLE `skrupel_planeten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spiel` (`spiel`),
  ADD KEY `besitzer` (`besitzer`),
  ADD KEY `x_pos` (`x_pos`),
  ADD KEY `y_pos` (`y_pos`),
  ADD KEY `native_id` (`native_id`),
  ADD KEY `native_kol` (`native_kol`),
  ADD KEY `sternenbasis_art` (`sternenbasis_art`);

--
-- Indizes für die Tabelle `skrupel_politik`
--
ALTER TABLE `skrupel_politik`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spiel` (`spiel`);

--
-- Indizes für die Tabelle `skrupel_politik_anfrage`
--
ALTER TABLE `skrupel_politik_anfrage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spiel` (`spiel`);

--
-- Indizes für die Tabelle `skrupel_scan`
--
ALTER TABLE `skrupel_scan`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `skrupel_schiffe`
--
ALTER TABLE `skrupel_schiffe`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_1` (`id`) USING BTREE,
  ADD KEY `spezialmission` (`spezialmission`),
  ADD KEY `spiel` (`spiel`),
  ADD KEY `flug` (`flug`),
  ADD KEY `zielid` (`zielid`),
  ADD KEY `temp_verfolgt` (`temp_verfolgt`),
  ADD KEY `besitzer` (`besitzer`),
  ADD KEY `ordner` (`ordner`),
  ADD KEY `status` (`status`),
  ADD KEY `kox` (`kox`),
  ADD KEY `koy` (`koy`),
  ADD KEY `klasseid` (`klasseid`),
  ADD KEY `zusatzmodul` (`zusatzmodul`),
  ADD KEY `routing_status` (`routing_status`);
ALTER TABLE `skrupel_schiffe` ADD FULLTEXT KEY `volk` (`volk`);

--
-- Indizes für die Tabelle `skrupel_spiele`
--
ALTER TABLE `skrupel_spiele`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sid` (`sid`),
  ADD KEY `phase` (`phase`),
  ADD KEY `ziel_id` (`ziel_id`),
  ADD KEY `lasttick` (`lasttick`),
  ADD KEY `sid_spieler_index` (`sid`,`spieler_1`,`spieler_2`,`spieler_3`,`spieler_4`,`spieler_5`,`spieler_6`,`spieler_7`,`spieler_8`,`spieler_9`,`spieler_10`),
  ADD KEY `id_spieler_index` (`id`,`spieler_1`,`spieler_2`,`spieler_3`,`spieler_4`,`spieler_5`,`spieler_6`,`spieler_7`,`spieler_8`,`spieler_9`,`spieler_10`);
ALTER TABLE `skrupel_spiele` ADD FULLTEXT KEY `name` (`name`);

--
-- Indizes für die Tabelle `skrupel_sternenbasen`
--
ALTER TABLE `skrupel_sternenbasen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `besitzer` (`besitzer`),
  ADD KEY `spiel` (`spiel`),
  ADD KEY `status` (`status`),
  ADD KEY `planetid` (`planetid`);
ALTER TABLE `skrupel_sternenbasen` ADD FULLTEXT KEY `rasse` (`rasse`);

--
-- Indizes für die Tabelle `skrupel_user`
--
ALTER TABLE `skrupel_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);
ALTER TABLE `skrupel_user` ADD FULLTEXT KEY `nick` (`nick`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `skrupel_anomalien`
--
ALTER TABLE `skrupel_anomalien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `skrupel_begegnung`
--
ALTER TABLE `skrupel_begegnung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `skrupel_chat`
--
ALTER TABLE `skrupel_chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `skrupel_forum_beitrag`
--
ALTER TABLE `skrupel_forum_beitrag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `skrupel_forum_thema`
--
ALTER TABLE `skrupel_forum_thema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `skrupel_huellen`
--
ALTER TABLE `skrupel_huellen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `skrupel_kampf`
--
ALTER TABLE `skrupel_kampf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `skrupel_konplaene`
--
ALTER TABLE `skrupel_konplaene`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `skrupel_languages`
--
ALTER TABLE `skrupel_languages`
  MODIFY `lid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `skrupel_nebel`
--
ALTER TABLE `skrupel_nebel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `skrupel_neuigkeiten`
--
ALTER TABLE `skrupel_neuigkeiten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `skrupel_ordner`
--
ALTER TABLE `skrupel_ordner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `skrupel_planeten`
--
ALTER TABLE `skrupel_planeten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `skrupel_politik`
--
ALTER TABLE `skrupel_politik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `skrupel_politik_anfrage`
--
ALTER TABLE `skrupel_politik_anfrage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `skrupel_scan`
--
ALTER TABLE `skrupel_scan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `skrupel_schiffe`
--
ALTER TABLE `skrupel_schiffe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `skrupel_spiele`
--
ALTER TABLE `skrupel_spiele`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `skrupel_sternenbasen`
--
ALTER TABLE `skrupel_sternenbasen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `skrupel_user`
--
ALTER TABLE `skrupel_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `skrupel_ki_neuebasen`
--
ALTER TABLE `skrupel_ki_neuebasen`
  ADD CONSTRAINT `skrupel_ki_neuebasen_ibfk_1` FOREIGN KEY (`planeten_id`) REFERENCES `skrupel_planeten` (`id`);

--
-- Constraints der Tabelle `skrupel_ki_objekte`
--
ALTER TABLE `skrupel_ki_objekte`
  ADD CONSTRAINT `skrupel_ki_objekte_ibfk_1` FOREIGN KEY (`objekt_id`) REFERENCES `skrupel_anomalien` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
