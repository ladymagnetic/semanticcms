-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 14. Dez 2016 um 13:29
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

--
-- Daten für Tabelle `article`
--

INSERT INTO `article` (`id`, `header`, `content`, `date`, `page_id`, `author`, `type`, `public`, `description`) VALUES
(1, '', 'Plätzchenrezepte', '2016-12-11', 0, 1, 'Gästebucheintrag', 1, ''),
(2, '', 'Plätzchenrezepte', '2016-12-16', 0, 2, '', 1, ''),
(4, '', 'Fußball in Bayern', '2016-05-16', 0, 3, 'Blog', 1, ''),
(5, '', 'Was sagt ein Hai, nachdem es einen Surfer gefressen hat? - "Nett serviert, so mit Frühstücksbrettchen"', '2016-12-02', 0, 1, '', 1, ''),
(6, '', 'Ich werde es nie kapieren, wie man beim Biathlon Zweiter werden kann. Man hat doch ein Gewehr.', '2015-04-20', 0, 1, '', 1, ''),
(7, '', 'Ich konnte es nicht fassen. Mein Nachbar hat tatsächlich noch um 3 Uhr Nachts bei uns geklingelt. Mir wäre fast die Bohrmaschine runtergefallen.', '2014-03-16', 0, 3, 'Gästebucheintrag', 1, 'Witze'),
(8, '', 'Tipp, wenn sich jemand mit dir streiten will: Einfach ein paar Kekse essen. Die schmecken lecker und man hört nichts mehr.', '2012-10-15', 0, 5, '', 0, 'Witze'),
(9, '', 'Schönheitschirurg zur Schwester: "Halt ma die Fresse.".', '2014-11-29', 0, 4, 'Blog', 0, 'Witze');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ban`
--

CREATE TABLE `ban` (
  `id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `begin` datetime NOT NULL,
  `end` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `ban`
--

INSERT INTO `ban` (`id`, `reason`, `begin`, `end`) VALUES
(1, 'Verwendung von Schimpfwörtern', '2016-12-14 09:43:16', '2017-01-12 13:13:47'),
(2, 'lästig', '2016-12-13 23:59:59', '2016-12-14 00:00:00'),
(3, 'zu viele Anfragen an Admin', '2016-12-13 23:59:59', '2017-12-31 23:59:59'),
(4, 'Bääähm.', '2016-10-10 14:44:36', '2017-11-15 16:18:00');

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
(2, 7),
(2, 8),
(2, 9),
(4, 5);

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
(1, 'Schule', 'abc.de.com'),
(2, 'Pferd', 'Pferd.de.com'),
(3, 'Kirsche', 'Kirsche.de.com'),
(4, 'Apfel', 'Apfel.de.com'),
(5, 'Pferd', 'Pferd.de.com'),
(6, 'Huhn', 'Huhn.de.com'),
(7, 'Brasilien', 'Brasilien.de.com');

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
(1, 5),
(1, 6),
(2, 3),
(4, 6),
(5, 3),
(6, 1);

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

--
-- Daten für Tabelle `page`
--

INSERT INTO `page` (`id`, `title`, `template_id`) VALUES
(1, 'Witze im Alltag', 1),
(2, 'Reisen in Brasilien', 2),
(3, 'Reisen rund um die Welt', 2);

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
(1, 'www.abc.de', 'Gast', 0, 1, 0, 0, 1, 0),
(3, 'cms.de/oth/projektarbeit', 'Designer', 0, 0, 0, 1, 1, 0);

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
(5, 'Berg'),
(2, 'Schneemann'),
(4, 'Schranktürangelrahmenleiste'),
(3, 'Tischbeinschrankbaumhausbaumstammwurzelerdesand');

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

--
-- Daten für Tabelle `template`
--

INSERT INTO `template` (`id`, `templatename`, `filelink`) VALUES
(1, 'Blumenwiese', 'DokDoksfile.pdf'),
(2, 'Bäume', 'DokDoksBaum.pdf'),
(3, 'Katzenbaum', 'DokDoksKatzen.pdf');

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

INSERT INTO `user` (`id`, `role_id`, `lastname`, `firstname`, `username`, `password`, `email`, `registrydate`, `birthsate`) VALUES
(1, 1, 'Huber', 'Sepp', 'Seppl', 'ABC', 'Abc@def.de', '2016-12-11', '2014-01-14'),
(2, 3, 'Valentinus', 'Valentin', 'Valentin', '', 'Anni@Anni.de', '1916-07-24', '2016-02-10'),
(3, 1, 'Müller', 'Max', 'Maxl', '', 'Max@schorsch.de', '2016-12-12', '2000-12-22'),
(4, 3, 'Meier', 'Moritz', 'Mo', '', 'Mo@schorsch.de', '2000-12-18', '2016-12-14'),
(5, 3, 'Anna-Maria', 'Anne', 'Anni', '', 'Anni@Anni.de', '1916-07-24', '2016-02-10'),
(9, 3, 'Winter', 'Frühling', 'Sommer', '', 'Mo@schorsch.de', '2000-12-18', '2016-12-14');

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `begin` (`begin`,`end`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT für Tabelle `ban`
--
ALTER TABLE `ban`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `lable`
--
ALTER TABLE `lable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT für Tabelle `page`
--
ALTER TABLE `page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
