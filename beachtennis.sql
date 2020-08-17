-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Ago 15, 2020 alle 17:49
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
-- Struttura della tabella `coppia_evento`
--

CREATE TABLE `coppia_evento` (
  `codCoppia` int(11) NOT NULL,
  `codEvento` int(11) NOT NULL,
  `under` int(11) NOT NULL,
  `punt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `coppia_girone`
--

CREATE TABLE `coppia_girone` (
  `codGirone` int(11) NOT NULL,
  `codEvento` int(11) NOT NULL,
  `codCoppia` int(11) NOT NULL,
  `under` int(11) NOT NULL,
  `pos` int(11) NOT NULL,
  `numCoppie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `coppie`
--

CREATE TABLE `coppie` (
  `codCoppia` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `part1` int(100) NOT NULL,
  `part2` int(100) NOT NULL,
  `under` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `eventi`
--

CREATE TABLE `eventi` (
  `codEvento` int(11) NOT NULL,
  `nomeEvento` varchar(100) NOT NULL,
  `dataInizio` varchar(30) DEFAULT NULL,
  `dataFine` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `giocatori`
--

CREATE TABLE `giocatori` (
  `codGiocatore` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `annoNascita` int(11) NOT NULL,
  `numeroTelefono` varchar(100) NOT NULL,
  `iscritto` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `gironi`
--

CREATE TABLE `gironi` (
  `codGirone` int(11) NOT NULL,
  `codEvento` int(11) NOT NULL,
  `under` int(11) NOT NULL,
  `numCoppie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `partite`
--

CREATE TABLE `partite` (
  `codPartita` int(11) NOT NULL,
  `codGirone` int(11) NOT NULL,
  `codEvento` int(11) NOT NULL,
  `codCoppia1` int(11) NOT NULL,
  `codCoppia2` int(11) NOT NULL,
  `data` varchar(100) DEFAULT NULL,
  `campo` varchar(100) DEFAULT NULL,
  `punt1` int(11) DEFAULT NULL,
  `punt2` int(11) DEFAULT NULL,
  `under` int(11) NOT NULL,
  `finale` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `vincitori`
--

CREATE TABLE `vincitori` (
  `codCoppia` int(11) NOT NULL,
  `codEvento` int(11) NOT NULL,
  `under` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `coppia_evento`
--
ALTER TABLE `coppia_evento`
  ADD PRIMARY KEY (`codCoppia`,`codEvento`),
  ADD KEY `FK_Coppia_Evento_Evento` (`codEvento`);

--
-- Indici per le tabelle `coppia_girone`
--
ALTER TABLE `coppia_girone`
  ADD PRIMARY KEY (`codGirone`,`codEvento`,`codCoppia`),
  ADD KEY `FK_Coppia_Girone_Coppia` (`codCoppia`),
  ADD KEY `FK_Coppia_Girone_Evento` (`codEvento`);

--
-- Indici per le tabelle `coppie`
--
ALTER TABLE `coppie`
  ADD PRIMARY KEY (`codCoppia`),
  ADD KEY `FK_Coppie_Giocatore1` (`part1`),
  ADD KEY `FK_Coppie_Giocatore2` (`part2`);

--
-- Indici per le tabelle `eventi`
--
ALTER TABLE `eventi`
  ADD PRIMARY KEY (`codEvento`);

--
-- Indici per le tabelle `giocatori`
--
ALTER TABLE `giocatori`
  ADD PRIMARY KEY (`codGiocatore`);

--
-- Indici per le tabelle `gironi`
--
ALTER TABLE `gironi`
  ADD PRIMARY KEY (`codGirone`),
  ADD KEY `FK_Evento` (`codEvento`);

--
-- Indici per le tabelle `partite`
--
ALTER TABLE `partite`
  ADD PRIMARY KEY (`codPartita`),
  ADD KEY `FK_Partite_Girone` (`codGirone`),
  ADD KEY `FK_Partite_Evento` (`codEvento`),
  ADD KEY `FK_Partite_Coppia1` (`codCoppia1`),
  ADD KEY `FK_Partite_Coppia2` (`codCoppia2`);

--
-- Indici per le tabelle `vincitori`
--
ALTER TABLE `vincitori`
  ADD PRIMARY KEY (`codCoppia`,`codEvento`),
  ADD KEY `FK_Vincitori_Evento` (`codEvento`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `coppie`
--
ALTER TABLE `coppie`
  MODIFY `codCoppia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `eventi`
--
ALTER TABLE `eventi`
  MODIFY `codEvento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `giocatori`
--
ALTER TABLE `giocatori`
  MODIFY `codGiocatore` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `gironi`
--
ALTER TABLE `gironi`
  MODIFY `codGirone` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `partite`
--
ALTER TABLE `partite`
  MODIFY `codPartita` int(11) NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `coppia_evento`
--
ALTER TABLE `coppia_evento`
  ADD CONSTRAINT `FK_Coppia_Evento_Coppia` FOREIGN KEY (`codCoppia`) REFERENCES `coppie` (`codCoppia`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Coppia_Evento_Evento` FOREIGN KEY (`codEvento`) REFERENCES `eventi` (`codEvento`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `coppia_girone`
--
ALTER TABLE `coppia_girone`
  ADD CONSTRAINT `FK_Coppia_Girone_Coppia` FOREIGN KEY (`codCoppia`) REFERENCES `coppie` (`codCoppia`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Coppia_Girone_Evento` FOREIGN KEY (`codEvento`) REFERENCES `eventi` (`codEvento`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Coppia_Girone_Girone` FOREIGN KEY (`codGirone`) REFERENCES `gironi` (`codGirone`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `coppie`
--
ALTER TABLE `coppie`
  ADD CONSTRAINT `FK_Coppie_Giocatore1` FOREIGN KEY (`part1`) REFERENCES `giocatori` (`codGiocatore`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Coppie_Giocatore2` FOREIGN KEY (`part2`) REFERENCES `giocatori` (`codGiocatore`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `gironi`
--
ALTER TABLE `gironi`
  ADD CONSTRAINT `FK_Evento` FOREIGN KEY (`codEvento`) REFERENCES `eventi` (`codEvento`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `partite`
--
ALTER TABLE `partite`
  ADD CONSTRAINT `FK_Partite_Coppia1` FOREIGN KEY (`codCoppia1`) REFERENCES `coppie` (`codCoppia`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Partite_Coppia2` FOREIGN KEY (`codCoppia2`) REFERENCES `coppie` (`codCoppia`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Partite_Evento` FOREIGN KEY (`codEvento`) REFERENCES `eventi` (`codEvento`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Partite_Girone` FOREIGN KEY (`codGirone`) REFERENCES `gironi` (`codGirone`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `vincitori`
--
ALTER TABLE `vincitori`
  ADD CONSTRAINT `FK_Vincitori_Coppia` FOREIGN KEY (`codCoppia`) REFERENCES `coppie` (`codCoppia`) ON DELETE NO ACTION,
  ADD CONSTRAINT `FK_Vincitori_Evento` FOREIGN KEY (`codEvento`) REFERENCES `eventi` (`codEvento`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
