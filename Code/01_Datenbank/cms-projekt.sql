-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 22. Dez 2016 um 12:58
-- Server-Version: 10.1.16-MariaDB
-- PHP-Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `cms-projekt`
--
DROP DATABASE IF EXISTS`cms-projekt`;
CREATE DATABASE IF NOT EXISTS `cms-projekt` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `cms-projekt`;

--
-- User fuer Webanwendung
--
DROP USER IF EXISTS cms@localhost;
CREATE USER IF NOT EXISTS cms@localhost IDENTIFIED BY 'pleasechange';
GRANT SELECT, INSERT, UPDATE, DELETE ON *.* TO 'cms'@'localhost' IDENTIFIED BY 'pleasechange';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `article`
--

CREATE TABLE `article` (
  `id` int(11) NOT NULL,
  `header` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `date` date NOT NULL,
  `page_id` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '0',
  `description` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ban`
--

CREATE TABLE `ban` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reason_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `begin` datetime NOT NULL,
  `end` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ban_reason`
--

CREATE TABLE `ban_reason` (
  `id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `ban_reason`
--

INSERT INTO `ban_reason` (`id`, `reason`) VALUES
(1, 'Benutzung von Schimpfwörtern'),
(2, 'Beleidigung'),
(3, 'Missachtung von Seiten-Regeln'),
(4, 'Spam'),
(5, 'Sonstiges'),
(6, 'Mehrmalige Falscheingabe des Passwortes');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lable`
--

CREATE TABLE `lable` (
  `id` int(11) NOT NULL,
  `lablename` varchar(255) NOT NULL,
  `uri` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lable_article`
--

CREATE TABLE `lable_article` (
  `lable_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lable_user`
--

CREATE TABLE `lable_user` (
  `lable_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `page`
--

CREATE TABLE `page` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `template_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `uri` text NOT NULL,
  `rolename` varchar(255) NOT NULL,
  `guestbookmanagement` tinyint(1) NOT NULL DEFAULT '0',
  `usermanagement` tinyint(1) NOT NULL DEFAULT '0',
  `pagemanagement` tinyint(1) NOT NULL DEFAULT '0',
  `articlemanagement` tinyint(1) NOT NULL DEFAULT '0',
  `guestbookusage` tinyint(1) NOT NULL DEFAULT '1',
  `templateconstruction` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `role`
--

INSERT INTO `role` (`id`, `uri`, `rolename`, `guestbookmanagement`, `usermanagement`, `pagemanagement`, `articlemanagement`, `guestbookusage`, `templateconstruction`) VALUES
(1, 'uri.uri', 'Admin', 1, 1, 1, 1, 1, 1),
(2, 'uri.uri', 'Gast', 0, 0, 0, 0, 1, 0),
(3, 'uri.uri', 'Redakteur', 1, 0, 1, 1, 1, 0),
(4, 'uri.uri', 'Designer', 1, 0, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `searchphrase`
--

CREATE TABLE `searchphrase` (
  `id` int(11) NOT NULL,
  `searchphrase` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `searchphrase_user`
--

CREATE TABLE `searchphrase_user` (
  `user_id` int(11) NOT NULL,
  `searchphrase_id` int(11) NOT NULL,
  `searchdate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `template`
--

CREATE TABLE `template` (
  `id` int(11) NOT NULL,
  `templatename` varchar(255) NOT NULL,
  `filelink` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `registrydate` date NOT NULL,
  `birthdate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `role_id`, `lastname`, `firstname`, `username`, `password`, `email`, `registrydate`, `birthdate`) VALUES
(1, 1, 'Administrator', 'Admin', 'Admin-Admin', 'PLEASECHANGE', 'admin@e-mail.de', '2016-12-22', '2000-12-12'),
(2, 2, 'GastNachname', 'GastVorname', 'Gast-Gast', 'PLEASECHANGE', 'gast@e-mail.de', '2016-12-22', '1998-12-12'),
(3, 3, 'RedakteurNachname', 'RedakteurVorname', 'Redi-Redi', 'PLEASECHANGE', 'Redakteur@e-mail.de', '2016-12-22', '1998-12-25'),
(4, 4, 'DesignerNachname', 'DesignerVorname', 'Designer-Designer', 'PLEASECHANGE', 'Designer@e-mail.de', '2016-12-22', '2000-12-12');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `ban`
--
ALTER TABLE `ban`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `ban_reason`
--
ALTER TABLE `ban_reason`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `lable`
--
ALTER TABLE `lable`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `lable_article`
--
ALTER TABLE `lable_article`
  ADD UNIQUE KEY `tag_id` (`lable_id`,`article_id`),
  ADD KEY `article_id` (`article_id`);

--
-- Indizes für die Tabelle `lable_user`
--
ALTER TABLE `lable_user`
  ADD UNIQUE KEY `tag_id` (`lable_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indizes für die Tabelle `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title` (`title`);

--
-- Indizes für die Tabelle `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rolename` (`rolename`);

--
-- Indizes für die Tabelle `searchphrase`
--
ALTER TABLE `searchphrase`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `searchphrase` (`searchphrase`);

--
-- Indizes für die Tabelle `searchphrase_user`
--
ALTER TABLE `searchphrase_user`
  ADD UNIQUE KEY `user_id` (`user_id`,`searchphrase_id`),
  ADD KEY `searchphrase_id` (`searchphrase_id`);

--
-- Indizes für die Tabelle `template`
--
ALTER TABLE `template`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `templatename` (`templatename`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`,`email`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `ban`
--
ALTER TABLE `ban`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `ban_reason`
--
ALTER TABLE `ban_reason`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT für Tabelle `lable`
--
ALTER TABLE `lable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `page`
--
ALTER TABLE `page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `searchphrase`
--
ALTER TABLE `searchphrase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `template`
--
ALTER TABLE `template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `lable_article`
--
ALTER TABLE `lable_article`
  ADD CONSTRAINT `lable_article_ibfk_1` FOREIGN KEY (`lable_id`) REFERENCES `lable` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lable_article_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `lable_user`
--
ALTER TABLE `lable_user`
  ADD CONSTRAINT `lable_user_ibfk_1` FOREIGN KEY (`lable_id`) REFERENCES `lable` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lable_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `searchphrase_user`
--
ALTER TABLE `searchphrase_user`
  ADD CONSTRAINT `searchphrase_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `searchphrase_user_ibfk_2` FOREIGN KEY (`searchphrase_id`) REFERENCES `searchphrase` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
