-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 16. Dez 2016 um 23:11
-- Server-Version: 10.1.16-MariaDB
-- PHP-Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `cms-projekt_fiktive_Testdaten`
--
DROP DATABASE IF EXISTS`cms-projekt_fiktive_Testdaten`;
CREATE DATABASE IF NOT EXISTS `cms-projekt_fiktive_Testdaten` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `cms-projekt_fiktive_Testdaten`;

--
-- User fuer Webanwendung
--
DROP USER IF EXISTS cms@localhost;
CREATE USER IF NOT EXISTS cms@localhost IDENTIFIED BY 'pleasechange';
GRANT SELECT, INSERT, UPDATE, DELETE ON *.* TO 'cms'@'localhost' IDENTIFIED BY 'pleasechange';

-- --------------------------------------------------------

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

--
-- Daten für Tabelle `article`
--

INSERT INTO `article` (`id`, `header`, `content`, `date`, `page_id`, `author`, `type`, `public`, `description`) VALUES
(1, '', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.', '2016-12-04', 1, 1, 'Blog', 1, 'Umwelt'),
(2, '', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.', '2016-12-31', 2, 3, 'Blog', 1, 'Urlaub'),
(3, '', 'Copacabana ist einer der bekanntesten Stadtteile Rio de Janeiros in der Zona Sul, der direkt zwischen dem Atlantik und den mit Favelas bevölkerten Granitfelsen liegt und über einen vier Kilometer langen Sandstrand verfügt. Gemeinsam mit dem benachbarten Leme bildet der Stadtteil außerdem die gleichnamige Verwaltungsregion Copacabana.', '2016-12-14', 3, 2, 'Blog', 1, 'Urlaub');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ban`
--

CREATE TABLE `ban` (
  `id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `begin` datetime NOT NULL,
  `end` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `ban`
--

INSERT INTO `ban` (`id`, `reason`, `description`, `begin`, `end`) VALUES
(1, 'Schimpfwörter', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.', '2016-12-01 12:30:00', '2016-12-31 23:59:00'),
(2, 'ständige Anfragen', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.', '2016-12-04 00:00:00', '3000-12-16 00:00:00'),
(3, 'Falscheingabe des Passwortes', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.', '2016-12-16 00:00:00', '2016-12-17 00:00:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ban_user`
--

CREATE TABLE `ban_user` (
  `ban_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `ban_user`
--

INSERT INTO `ban_user` (`ban_id`, `user_id`) VALUES
(1, 1),
(2, 5),
(3, 5);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lable`
--

CREATE TABLE `lable` (
  `id` int(11) NOT NULL,
  `lablename` varchar(255) NOT NULL,
  `uri` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `lable`
--

INSERT INTO `lable` (`id`, `lablename`, `uri`) VALUES
(1, 'Auto', ''),
(2, 'Urlaub', ''),
(3, 'Meer', ''),
(4, 'Pflanzen', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lable_article`
--

CREATE TABLE `lable_article` (
  `lable_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `lable_article`
--

INSERT INTO `lable_article` (`lable_id`, `article_id`) VALUES
(1, 1),
(2, 2),
(4, 2),
(4, 3);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lable_user`
--

CREATE TABLE `lable_user` (
  `lable_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `lable_user`
--

INSERT INTO `lable_user` (`lable_id`, `user_id`) VALUES
(1, 2),
(3, 2),
(4, 2),
(5, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `page`
--

CREATE TABLE `page` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `template_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `page`
--

INSERT INTO `page` (`id`, `title`, `template_id`) VALUES
(1, 'Urlaub', 3),
(2, 'Andere Länder, andere Sitten', 3),
(3, 'Alpen', 2),
(4, 'Nordpol', 1);

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
(1, '', 'admin', 1, 1, 1, 1, 1, 1),
(2, '', 'Gast', 0, 0, 0, 0, 1, 0),
(3, '', 'Redakteur', 1, 0, 1, 1, 1, 0),
(4, '', 'Designer', 1, 0, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `searchphrase`
--

CREATE TABLE `searchphrase` (
  `id` int(11) NOT NULL,
  `searchphrase` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `searchphrase`
--

INSERT INTO `searchphrase` (`id`, `searchphrase`) VALUES
(2, 'Meer'),
(3, 'Sand'),
(5, 'Stiefel'),
(1, 'Strand'),
(4, 'Stuhl');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `searchphrase_user`
--

CREATE TABLE `searchphrase_user` (
  `user_id` int(11) NOT NULL,
  `searchphrase_id` int(11) NOT NULL,
  `searchdate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `searchphrase_user`
--

INSERT INTO `searchphrase_user` (`user_id`, `searchphrase_id`, `searchdate`) VALUES
(1, 1, '2016-12-19'),
(1, 4, '2015-12-22'),
(1, 5, '2015-12-22'),
(3, 3, '2015-12-22'),
(4, 3, '2015-12-22'),
(4, 4, '2015-12-22');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `template`
--

CREATE TABLE `template` (
  `id` int(11) NOT NULL,
  `templatename` varchar(255) NOT NULL,
  `filelink` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `template`
--

INSERT INTO `template` (`id`, `templatename`, `filelink`) VALUES
(1, 'Blumen im Winter', ''),
(2, 'Berge im Sommer', ''),
(3, 'Brasilien - Copacabana', '');

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
(1, 1, 'Achim', 'Ach', 'Admin', '', 'achmin@ach.de', '2016-12-13', '2000-12-16'),
(2, 2, 'Langstrumpf', 'Pippi', 'Pia', '', 'Pippi@ach.de', '2016-08-16', '1999-08-16'),
(3, 2, 'Ende', 'Michael', 'Michi', '', 'Michl@abc.de', '2016-12-05', '2000-12-05'),
(4, 3, 'Linus', 'Ida', 'Momo', '', 'Momo@ach.de', '2016-12-11', '2016-12-26'),
(5, 4, 'Räubertochter', 'Ronja', 'Ro', '', 'Räuber@Tochter.de', '0000-00-00', '0000-00-00');

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
-- Indizes für die Tabelle `ban_user`
--
ALTER TABLE `ban_user`
  ADD UNIQUE KEY `ban_id` (`ban_id`,`user_id`);

--
-- Indizes für die Tabelle `lable`
--
ALTER TABLE `lable`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `lable_article`
--
ALTER TABLE `lable_article`
  ADD UNIQUE KEY `tag_id` (`lable_id`,`article_id`);

--
-- Indizes für die Tabelle `lable_user`
--
ALTER TABLE `lable_user`
  ADD UNIQUE KEY `tag_id` (`lable_id`,`user_id`);

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
  ADD UNIQUE KEY `user_id` (`user_id`,`searchphrase_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `ban`
--
ALTER TABLE `ban`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `lable`
--
ALTER TABLE `lable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `page`
--
ALTER TABLE `page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `searchphrase`
--
ALTER TABLE `searchphrase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT für Tabelle `template`
--
ALTER TABLE `template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
