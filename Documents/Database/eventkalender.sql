-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 15. Dez 2014 um 09:40
-- Server Version: 5.6.11
-- PHP-Version: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `eventkalender`
--
CREATE DATABASE IF NOT EXISTS `eventkalender` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `eventkalender`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `benutzer`
--

CREATE TABLE IF NOT EXISTS `benutzer` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `benutzername` varchar(45) NOT NULL,
  `passwort_p` varchar(64) NOT NULL,
  `erstelldatum` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `benutzer`
--

INSERT INTO `benutzer` (`ID`, `benutzername`, `passwort_p`, `erstelldatum`) VALUES
(1, 'Snatsch', 'a6f22a629342dc2555e57f91da20108af8865293d3610a285ec3c03ee1f6b1a7', '2014-11-11 10:56:27');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `genre`
--

CREATE TABLE IF NOT EXISTS `genre` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Daten für Tabelle `genre`
--

INSERT INTO `genre` (`ID`, `name`) VALUES
(1, 'Open Air Konzert'),
(2, 'Operette'),
(3, 'Drama'),
(4, 'Game Convention'),
(5, 'Konzert'),
(6, 'Soirée');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `link`
--

CREATE TABLE IF NOT EXISTS `link` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `link` varchar(255) NOT NULL,
  `idVeranstaltung` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`,`idVeranstaltung`),
  KEY `fk_Link_Veranstaltung1_idx` (`idVeranstaltung`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `link`
--

INSERT INTO `link` (`ID`, `name`, `link`, `idVeranstaltung`) VALUES
(1, 'Facebook', 'http://facebook.de', 6);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `preisgruppe`
--

CREATE TABLE IF NOT EXISTS `preisgruppe` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `preis` varchar(20) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Daten für Tabelle `preisgruppe`
--

INSERT INTO `preisgruppe` (`ID`, `name`, `preis`) VALUES
(1, 'Adulte Personen', '40'),
(2, 'Kind', '10'),
(3, 'Senioren', '0'),
(4, 'Jugendliche', '30'),
(7, 'Studenten', '35'),
(8, 'Baby''s', '0');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `veranstaltung`
--

CREATE TABLE IF NOT EXISTS `veranstaltung` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `besetzung` varchar(255) DEFAULT NULL,
  `beschreibung` text NOT NULL,
  `dauer` time NOT NULL,
  `bild` varchar(100) DEFAULT NULL,
  `bildbeschreibung` varchar(255) DEFAULT NULL,
  `idGenre` int(10) unsigned NOT NULL,
  `bearbeitungsdatum` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `erstelldatum` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `fk_Veranstaltung_Genre1_idx` (`idGenre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Daten für Tabelle `veranstaltung`
--

INSERT INTO `veranstaltung` (`ID`, `name`, `besetzung`, `beschreibung`, `dauer`, `bild`, `bildbeschreibung`, `idGenre`, `bearbeitungsdatum`, `erstelldatum`) VALUES
(1, 'Totally no unicorns included!', 'Schauspieler (interessiert niemanden) <br> Mithelfer (interessiert sowas von keinen)', 'Total langweiliges Theaterstück', '02:30:00', 'test.jpg', 'Bla bla bla', 3, '2014-12-15 01:24:36', '2014-11-11 15:27:09'),
(2, 'amazing Gamecon', '... Nerds', 'see name', '23:00:00', NULL, NULL, 4, '2014-11-25 11:23:58', '2014-11-25 11:23:58'),
(3, 'asdfa', 'dasdf', 'dasdfasdf', '12:00:00', NULL, NULL, 1, '2014-12-14 13:40:42', '2014-12-14 13:40:42'),
(4, 'test', 'test', 'test', '12:00:00', NULL, NULL, 1, '2014-12-14 20:03:17', '2014-12-14 20:03:17'),
(5, 'test', 'Test', 'Test', '12:00:00', 'Wondaroo_Icon.png', '', 1, '2014-12-15 00:14:14', '2014-12-14 20:10:32'),
(6, 'tralala', 'tralala', 'tralalala', '12:00:00', NULL, NULL, 1, '2014-12-14 20:11:01', '2014-12-14 20:11:01'),
(7, 'asdfasdf', 'adfasdf', '<s<asdf', '12:00:00', NULL, NULL, 1, '2014-12-14 20:12:32', '2014-12-14 20:12:32'),
(8, 'seriöser Test', 'Dies ist ein seriöser Test', 'much seriousness, much wow', '13:00:00', 'Ball.jpg', 'Test', 1, '2014-12-15 01:18:14', '2014-12-14 20:13:27'),
(10, 'lulu', 'LULU', 'LULULULULU', '12:00:00', NULL, NULL, 5, '2014-12-15 01:26:27', '2014-12-15 01:26:27'),
(11, 'dasdf', 'asdfasdf', 'asdfasdf', '10:00:00', NULL, NULL, 6, '2014-12-15 01:26:47', '2014-12-15 01:26:47'),
(12, 'dasdf', 'asdfasdf', 'asdfasdf', '10:00:00', NULL, NULL, 6, '2014-12-15 01:26:50', '2014-12-15 01:26:50');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `veranstaltung_hat_preisgruppe`
--

CREATE TABLE IF NOT EXISTS `veranstaltung_hat_preisgruppe` (
  `idPreisgruppe` int(10) unsigned NOT NULL,
  `idVeranstaltung` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idPreisgruppe`,`idVeranstaltung`),
  KEY `fk_Veranstaltung_hat_Presigruppen_Preisgruppe_idx` (`idPreisgruppe`),
  KEY `fk_Veranstaltung_hat_Presigruppen_Veranstaltung1_idx` (`idVeranstaltung`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `veranstaltung_hat_preisgruppe`
--

INSERT INTO `veranstaltung_hat_preisgruppe` (`idPreisgruppe`, `idVeranstaltung`) VALUES
(1, 1),
(1, 12),
(2, 1),
(4, 11),
(7, 11),
(7, 12),
(8, 12);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `vorstellung`
--

CREATE TABLE IF NOT EXISTS `vorstellung` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `datum` date NOT NULL,
  `zeit` time NOT NULL,
  `idVeranstaltung` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`,`idVeranstaltung`),
  KEY `fk_Vorstellung_Veranstaltung1_idx` (`idVeranstaltung`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `vorstellung`
--

INSERT INTO `vorstellung` (`ID`, `datum`, `zeit`, `idVeranstaltung`) VALUES
(1, '2014-12-31', '18:00:00', 1),
(3, '2014-12-19', '12:00:00', 1),
(4, '2015-01-07', '12:00:00', 1),
(5, '2015-01-06', '12:00:00', 1);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `link`
--
ALTER TABLE `link`
  ADD CONSTRAINT `fk_Link_Veranstaltung1` FOREIGN KEY (`idVeranstaltung`) REFERENCES `veranstaltung` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `veranstaltung`
--
ALTER TABLE `veranstaltung`
  ADD CONSTRAINT `fk_Veranstaltung_Genre1` FOREIGN KEY (`idGenre`) REFERENCES `genre` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `veranstaltung_hat_preisgruppe`
--
ALTER TABLE `veranstaltung_hat_preisgruppe`
  ADD CONSTRAINT `fk_Veranstaltung_hat_Presigruppen_Preisgruppe` FOREIGN KEY (`idPreisgruppe`) REFERENCES `preisgruppe` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Veranstaltung_hat_Presigruppen_Veranstaltung1` FOREIGN KEY (`idVeranstaltung`) REFERENCES `veranstaltung` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `vorstellung`
--
ALTER TABLE `vorstellung`
  ADD CONSTRAINT `fk_Vorstellung_Veranstaltung1` FOREIGN KEY (`idVeranstaltung`) REFERENCES `veranstaltung` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
