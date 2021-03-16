-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Ago 20, 2020 alle 10:39
-- Versione del server: 10.4.11-MariaDB
-- Versione PHP: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `beachtennis`
--
CREATE DATABASE IF NOT EXISTS `beachtennis` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `beachtennis`;

-- --------------------------------------------------------

--
-- Struttura della tabella `couple_event`
--

CREATE TABLE `couple_event` (
  `coupleID` int(11) NOT NULL,
  `eventID` int(11) NOT NULL,
  `under` int(11) NOT NULL,
  `points` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `couple_round`
--

CREATE TABLE `couple_round` (
  `roundID` int(11) NOT NULL,
  `eventID` int(11) NOT NULL,
  `coupleID` int(11) NOT NULL,
  `under` int(11) NOT NULL,
  `pos` int(11) NOT NULL,
  `numCouples` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `couples`
--

CREATE TABLE `couples` (
  `coupleID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `part1` int(100) NOT NULL,
  `part2` int(100) NOT NULL,
  `under` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `events`
--

CREATE TABLE `events` (
  `eventID` int(11) NOT NULL,
  `eventName` varchar(100) NOT NULL,
  `startDate` varchar(30) DEFAULT NULL,
  `endDate` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `players`
--

CREATE TABLE `players` (
  `playerID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `birthdayDate` date NOT NULL,
  `phoneNumber` varchar(100) DEFAULT NULL,
  `subscribed` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `rounds`
--

CREATE TABLE `rounds` (
  `roundID` int(11) NOT NULL,
  `eventID` int(11) NOT NULL,
  `under` int(11) NOT NULL,
  `numCouples` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `matches`
--

CREATE TABLE `matches` (
  `matchID` int(11) NOT NULL,
  `roundID` int(11) NOT NULL,
  `eventID` int(11) NOT NULL,
  `idCouple1` int(11) NOT NULL,
  `idCouple2` int(11) NOT NULL,
  `date` varchar(100) DEFAULT NULL,
  `field` varchar(100) DEFAULT NULL,
  `points1` int(11) DEFAULT NULL,
  `points2` int(11) DEFAULT NULL,
  `under` int(11) NOT NULL,
  `final` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `winners`
--

CREATE TABLE `winners` (
  `coupleID` int(11) NOT NULL,
  `eventID` int(11) NOT NULL,
  `under` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `couple_event`
--
ALTER TABLE `couple_event`
  ADD PRIMARY KEY (`coupleID`,`eventID`,`under`) USING BTREE,
  ADD KEY `FK_Couple_Event_Event` (`eventID`);

--
-- Indici per le tabelle `couple_round`
--
ALTER TABLE `couple_round`
  ADD PRIMARY KEY (`roundID`,`eventID`,`coupleID`),
  ADD KEY `FK_Couple_Round_Couple` (`coupleID`),
  ADD KEY `FK_Couple_Round_Event` (`eventID`);

--
-- Indici per le tabelle `couples`
--
ALTER TABLE `couples`
  ADD PRIMARY KEY (`coupleID`),
  ADD KEY `FK_Couples_Player1` (`part1`),
  ADD KEY `FK_Couples_Player2` (`part2`);

--
-- Indici per le tabelle `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`eventID`);

--
-- Indici per le tabelle `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`playerID`);

--
-- Indici per le tabelle `rounds`
--
ALTER TABLE `rounds`
  ADD PRIMARY KEY (`roundID`),
  ADD KEY `FK_Event` (`eventID`);

--
-- Indici per le tabelle `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`matchID`),
  ADD KEY `FK_Matches_Round` (`roundID`),
  ADD KEY `FK_Matches_Event` (`eventID`),
  ADD KEY `FK_Matches_Couple1` (`idCouple1`),
  ADD KEY `FK_Matches_Couple2` (`idCouple2`);

--
-- Indici per le tabelle `winners`
--
ALTER TABLE `winners`
  ADD PRIMARY KEY (`coupleID`,`eventID`),
  ADD KEY `FK_Winners_Event` (`eventID`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `couples`
--
ALTER TABLE `couples`
  MODIFY `coupleID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `events`
--
ALTER TABLE `events`
  MODIFY `eventID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `players`
--
ALTER TABLE `players`
  MODIFY `playerID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `rounds`
--
ALTER TABLE `rounds`
  MODIFY `roundID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `matches`
--
ALTER TABLE `matches`
  MODIFY `matchID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `couple_event`
--
ALTER TABLE `couple_event`
  ADD CONSTRAINT `FK_Couple_Event_Couple` FOREIGN KEY (`coupleID`) REFERENCES `couples` (`coupleID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Couple_Event_Event` FOREIGN KEY (`eventID`) REFERENCES `events` (`eventID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `couple_round`
--
ALTER TABLE `couple_round`
  ADD CONSTRAINT `FK_Couple_Round_Couple` FOREIGN KEY (`coupleID`) REFERENCES `couples` (`coupleID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Couple_Round_Event` FOREIGN KEY (`eventID`) REFERENCES `events` (`eventID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Couple_Round_Round` FOREIGN KEY (`roundID`) REFERENCES `rounds` (`roundID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `couples`
--
ALTER TABLE `couples`
  ADD CONSTRAINT `FK_Couples_Player1` FOREIGN KEY (`part1`) REFERENCES `players` (`playerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Couples_Player2` FOREIGN KEY (`part2`) REFERENCES `players` (`playerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `rounds`
--
ALTER TABLE `rounds`
  ADD CONSTRAINT `FK_Event` FOREIGN KEY (`eventID`) REFERENCES `events` (`eventID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `FK_Matches_Couple1` FOREIGN KEY (`idCouple1`) REFERENCES `couples` (`coupleID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Matches_Couple2` FOREIGN KEY (`idCouple2`) REFERENCES `couples` (`coupleID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Matches_Event` FOREIGN KEY (`eventID`) REFERENCES `events` (`eventID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Matches_Round` FOREIGN KEY (`roundID`) REFERENCES `rounds` (`roundID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `winners`
--
ALTER TABLE `winners`
  ADD CONSTRAINT `FK_Winners_Couple` FOREIGN KEY (`coupleID`) REFERENCES `couples` (`coupleID`) ON DELETE NO ACTION,
  ADD CONSTRAINT `FK_Winners_Event` FOREIGN KEY (`eventID`) REFERENCES `events` (`eventID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
