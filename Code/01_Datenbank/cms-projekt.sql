-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 05. Jan 2017 um 16:34
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
CREATE DATABASE IF NOT EXISTS `cms-projekt` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
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
  `publicationdate` date NOT NULL,
  `page_id` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '0',
  `description` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `article`
--

INSERT INTO `article` (`id`, `header`, `content`, `publicationdate`, `page_id`, `author`, `type`, `public`, `description`) VALUES
(1, 'Schaufel - eine lange Geschichte', 'Eine Schaufel (ahd. Scuvala; mhd. Schuvel), umgangssprachlich in Nord- und Mitteldeutschland auch Schippe oder Schüppe, im Rheinland auch Schöppe, ist ein Werkzeug zum Aufnehmen und Fortschaffen von Lockermaterialien (beispielsweise Erde, Sand oder Schüttgut). Die Vorläufer der Schaufeln sind die seit dem Neolithikum bekannten Grabstöcke. Diese waren zuerst einfache Stöcke, die aber je nach Einsatzzweck auch ein ausgeschnitztes Blatt oder Schultern aufwiesen. Mit Aufkommen von Metallen wurden zunächst nur die Schnittkanten des Schaufelblattes mit Metall verstärkt, bis schließlich mit zunehmender Verbreitung, vor allem des Eisens, die Schaufelblätter ganz aus Eisen gefertigt wurden. Schaufeln mit eisenverstärktem Holzblatt fanden beim Torfstich noch bis in das 20. Jahrhundert Verwendung.', '2016-12-22', 1, 2, 'Blog', 1, 'Schaufel'),
(2, 'Besen', 'Bei einem Besen oder Feger handelt es sich um einen Gebrauchsgegenstand zum Zusammenkehren von Schmutz und Unrat von Böden. Man unterscheidet zwischen großen und kleinen Besen für den Haushalt und die Straße und maschinell eingesetzten Besen.\r\nEin großer Besen besteht meist aus einem langen Besenstiel, einem Querholz, dem so genannten Riegel, und den Borsten aus Tierhaaren, Pflanzenfasern oder Kunststoff. Der Rutenbesen oder Reisigbesen (regional auch Riedelbesen) ist eine einfachere Besenform, die früher ggf. von einem Besenbinder hergestellt wurde und recht verbreitet war, besitzt keinen Riegel. Stattdessen wird ein Bündel Reisig oder Stroh mit Schnur oder Draht direkt am hölzernen Stiel befestigt. Bei Verwendung von Birkenzweigen ähnelt der Rutenbesen einer großen Birkenrute mit Holzstiel.', '2016-12-22', 4, 9, 'Blog', 1, 'Besen'),
(3, 'Hammer', 'Ein Hammer ist ein händisch oder maschinell angetriebenes Werkzeug, das unter Nutzung seiner beschleunigten Masse (meist) schwere Schläge auf Körper ausübt. Bei von Hand geführten Hämmern wird dieser je nach seiner Masse und genutzter Stiellänge nach dem Heben (Ausholen) aus dem Hand-, Ellbogen- oder Schultergelenk – oder bei beidhändigem Halten aus dem Oberkörper – heraus beschleunigt.\r\nDer Hammer gehört in einer stiellosen Variante als Faustkeil (aus bearbeitetem Stein mit einem nachgewiesenen Alter von 1,75 Millionen Jahren) wahrscheinlich zu den ältesten Werkzeugen der Menschheit.', '2016-12-18', 7, 8, 'Blog', 1, 'Hammer'),
(4, 'Schraubenzieher', 'Ein Hammer ist ein händisch oder maschinell angetriebenes Werkzeug, das unter Nutzung seiner beschleunigten Masse (meist) schwere Schläge auf Körper ausübt. Bei von Hand geführten Hämmern wird dieser je nach seiner Masse und genutzter Stiellänge nach dem Heben (Ausholen) aus dem Hand-, Ellbogen- oder Schultergelenk – oder bei beidhändigem Halten aus dem Oberkörper – heraus beschleunigt.\r\nDer Hammer gehört in einer stiellosen Variante als Faustkeil (aus bearbeitetem Stein mit einem nachgewiesenen Alter von 1,75 Millionen Jahren) wahrscheinlich zu den ältesten Werkzeugen der Menschheit.', '2016-06-13', 7, 9, 'Blog', 1, 'Schraubenzieher'),
(5, 'Brasilien', 'Brasilien (portugiesisch Brasil, gemäß Lautung des brasilianischen Portugiesisch [b?a?ziu?] Audio-Datei / Hörbeispiel Aussprache?/i) ist der flächen- und bevölkerungsmäßig fünftgrößte Staat der Erde. Es ist das größte und mit über 200 Millionen Einwohnern auch das bevölkerungsreichste Land Südamerikas, von dessen Fläche es 47,3 Prozent einnimmt.[7] Brasilien hat mit jedem südamerikanischen Staat außer Chile und Ecuador eine gemeinsame Grenze.\r\nDie ersten Spuren menschlicher Besiedlung durch Paläo-Indianer reichen mehrere tausend Jahre zurück. Nach der Entdeckung Amerikas und der Aufteilung des südamerikanischen Kontinents durch den Vertrag von Tordesillas wurde Brasilien eine portugiesische Kolonie. Diese mehr als drei Jahrhunderte andauernde Kolonialzeit, in der Einwanderer verschiedenster Herkunft (freiwillig oder gezwungenermaßen) nach Brasilien kamen, trug erheblich zur ethnischen Vielfalt des heutigen Staates bei. Nach der im Jahre 1822 erlangten Unabhängigkeit, auf die eine Zeit der konstitutionellen Monarchie folgte, wurde das Land 1889 als Vereinigte Staaten von Brasilien zu einer Republik. Nach der Zeit der Militärdiktatur von 1964 bis 1985 kehrte das Land zur Demokratie mit einem präsidentiellen Regierungssystem zurück.', '2015-08-03', 9, 4, 'Blog', 1, 'Brasilien'),
(6, 'Italien', 'Italien (amtlich Italienische Republik; italienisch Repubblica Italiana, Kurzform Italia) ist eine parlamentarische Republik in Südeuropa; seine Hauptstadt ist Rom. Das italienische Staatsgebiet liegt zum größten Teil auf der vom Mittelmeer umschlossenen Apennin­halbinsel und der Po-Ebene sowie im südlichen Gebirgsteil der Alpen. Der Staat grenzt an Frankreich, die Schweiz, Österreich und Slowenien. Die Kleinstaaten Vatikanstadt und San Marino sind vollständig vom italienischen Staatsgebiet umschlossen. Neben den großen Inseln Sizilien und Sardinien sind mehrere Inselgruppen vorgelagert.\r\nItalien ist Gründungsmitglied der Europäischen Wirtschaftsgemeinschaft (EWG), Vorläuferorganisation der heutigen Europäischen Union und des Europarates sowie der Europäischen Gemeinschaft für Kohle und Stahl (EGKS) und der Europäischen Atomgemeinschaft (EURATOM). Das Land ist Mitglied der Vereinten Nationen (UNO), der Organisation für wirtschaftliche Zusammenarbeit und Entwicklung (OECD), der NATO, der G7 und der G20.', '2015-08-22', 8, 5, 'Blog', 1, 'Italien'),
(7, 'Frankreich', 'Frankreich (amtlich Französische Republik, französisch République française [?e.py.?blik f???.?s?z], Kurzform  Audio-Datei / Hörbeispiel (la) France?/i [f???s]) ist ein demokratischer, interkontinentaler Einheitsstaat in Westeuropa mit Überseeinseln und -gebieten auf mehreren Kontinenten. Metropolitan-Frankreich, d. h. der europäische Teil des Staatsgebietes, erstreckt sich vom Mittelmeer bis zum Ärmelkanal und zur Nordsee sowie vom Rhein bis zum Atlantischen Ozean. Sein Festland wird wegen seiner Landesform als Hexagone (dt: Sechseck) bezeichnet. Frankreich ist flächenmäßig das größte Land der Europäischen Union und verfügt (nach Russland und der Ukraine) über das drittgrößte Staatsgebiet in Europa. Die Métropole Paris ist die Hauptstadt und mit der Île-de-France größter Ballungsraum des Landes, vor Lyon, Marseille, Toulouse und Lille.', '2014-07-16', 4, 6, 'Blog', 0, 'Frankreich'),
(8, 'München', 'Minga ist die Landeshauptstadt des Freistaates Bayern. Sie ist mit rund 1,5 Millionen Einwohnern die einwohnerstärkste Stadt Bayerns und (nach Berlin und Hamburg) die nach Einwohnern drittgrößte Gemeinde Deutschlands sowie die zwölftgrößte der Europäischen Union. Im Ballungsraum München leben mehr als 2,7 Millionen Menschen;[3] die flächengrößere europäische Metropolregion München umfasst rund 5,7 Millionen Einwohner.\r\nDie Landeshauptstadt ist eine kreisfreie Stadt und ein bayerisches Oberzentrum, zudem Verwaltungssitz des die Stadt umgebenden gleichnamigen Landkreises mit dem Landratsamt München als Verwaltung, des Bezirks Oberbayern und des Regierungsbezirks Oberbayern.', '2013-12-19', 3, 5, 'Blog', 0, 'München'),
(9, 'Hallo', 'Hallo ist im Deutschen ein mündlicher oder schriftlicher, nicht förmlicher Gruß, insbesondere unter Bekannten oder Freunden. Als Interjektion wird der Ausdruck auch ähnlich dem veralteten Anruf „Heda!“ gebraucht, um auf sich aufmerksam zu machen: „Hallo, ist da jemand?“ Eine weitere Interjektion – „Aber hallo!“ – hat die Bedeutung einer Bekräftigung (etwa: „Da hast du sowas von Recht!“) oder auch eines Widersprechens (etwa: „Da übersiehst du etwas Wesentliches!“). Seit einigen Jahren vermehrt aufgekommen ist der Gebrauch als Frage „Hallo?“ mit abweichender Betonung, um jemanden zur Besinnung zu rufen.\r\nWesentlich für die jeweilige Bedeutung ist die gewählte Betonung, Mimik und Gestik des Sprechenden.\r\nVon dem Ausruf leitet sich die substantivierte, im Gegensatz zu den anderen Formen auf der zweiten Silbe betonte Form „ein Hallo“ ab, die ein (fröhliches) lärmendes Durcheinander bezeichnet („Er wurde mit großem Hallo empfangen.“).', '1993-12-08', 7, 2, 'Blog', 0, 'Hallo - Grußformel'),
(10, 'Pferd', 'Die Pferde (mittellat. paraveredus abgeleitet von keltisch-spätlat. veredus „Kurierpferd“)[1] (Equus) sind die einzige rezente Gattung der Familie Equidae. Arten anderer Gattungen dieser Familie sind nur als Fossilien erhalten.\r\nZur Gattung Equus gehören die Tiere, die als Pferde, Esel und Zebras bezeichnet werden. Die Abgrenzung der Arten ist bis heute umstritten. Insgesamt werden meist sieben Arten unterschieden, von denen die meisten in ihrem Bestand gefährdet sind. Das Hauspferd und der Hausesel, die domestizierten Formen des Wildpferds respektive des Afrikanischen Esels, spielen als Last- und Reittier eine wichtige Rolle und sind weltweit verbreitet.', '1887-12-08', 6, 3, 'Blog', 0, 'Pferde - Pferdl'),
(11, 'Fertig', '<p>Mein Artikel</p>', '2017-01-05', 10, 1, 'Blog', 1, 'Bloggi');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ban`
--

CREATE TABLE `ban` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reason_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `begindatetime` datetime NOT NULL,
  `enddatetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `ban`
--

INSERT INTO `ban` (`id`, `user_id`, `reason_id`, `description`, `begindatetime`, `enddatetime`) VALUES
(1, 3, 5, 'Lästig', '2016-12-22 21:48:50', '2016-12-22 21:48:50'),
(2, 8, 6, 'Lästig', '2016-12-11 21:48:50', '2016-12-31 21:48:50'),
(3, 8, 5, 'Sonstiges', '2016-08-16 21:48:50', '2016-12-16 21:48:50'),
(4, 8, 5, 'Sonstiges', '2016-12-11 21:48:50', '2016-12-31 21:48:50'),
(5, 3, 2, 'mehrmaliges Missachtung von Seiten-Regeln', '2016-12-11 21:48:50', '2016-12-31 21:48:50'),
(6, 4, 6, 'Mehrmalige Falscheingabe des Passwortes - schon wieder', '2016-12-22 21:48:50', '2016-12-22 21:48:50'),
(7, 13, 4, 'Spam', '2014-02-12 21:48:50', '2017-11-17 21:48:50'),
(8, 14, 6, 'Mehrmalige Falscheingabe des Passwortes - schon wieder', '2016-08-16 21:48:50', '2018-12-14 21:48:50'),
(9, 8, 4, 'Spam', '2016-12-11 21:48:50', '2016-12-31 21:48:50'),
(10, 11, 2, 'Beleidigung der anderen Gäste', '2016-12-11 21:48:50', '3000-12-31 21:48:50');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ban_reason`
--

CREATE TABLE `ban_reason` (
  `id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `lable`
--

INSERT INTO `lable` (`id`, `lablename`, `uri`) VALUES
(1, 'Italien', 'Italien.uri'),
(2, 'Spanien', 'Spanien.uri'),
(3, 'Portugal', 'Portugal.uri'),
(4, 'Frankreich', 'Frankreich.uri'),
(5, 'Belgien', 'Belgien.uri'),
(6, 'Luxemburg', 'Luxemburg.uri'),
(7, 'Deutschland', 'Deutschland.uri'),
(8, 'Dänemark', 'Dänemark.uri'),
(9, 'Andorra', 'Andorra.uri'),
(10, 'Polen', 'Polen.uri'),
(11, 'Schweden', 'Schweden.uri'),
(12, 'Brasilien', 'Brasilien.uri'),
(13, 'Bolivien', 'Bolivien.uri'),
(14, 'Chile', 'Chile.uri'),
(15, 'Costa Rica', 'CostaRica.uri');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lable_article`
--

CREATE TABLE `lable_article` (
  `lable_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `lable_article`
--

INSERT INTO `lable_article` (`lable_id`, `article_id`) VALUES
(1, 9),
(4, 7),
(4, 8),
(5, 9),
(7, 8),
(8, 7),
(8, 8),
(12, 3),
(14, 3),
(15, 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lable_user`
--

CREATE TABLE `lable_user` (
  `lable_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `lable_user`
--

INSERT INTO `lable_user` (`lable_id`, `user_id`) VALUES
(1, 5),
(2, 9),
(2, 11),
(4, 8),
(6, 13),
(7, 1),
(7, 8),
(12, 5),
(14, 14),
(15, 2),
(15, 5);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `logtable`
--

CREATE TABLE `logtable` (
  `id` int(11) NOT NULL,
  `logdate` date NOT NULL,
  `username` varchar(255) NOT NULL,
  `rolename` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `page`
--

CREATE TABLE `page` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `relativeposition` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `website_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `page`
--

INSERT INTO `page` (`id`, `title`, `relativeposition`, `template_id`, `website_id`) VALUES
(1, 'Reisen', 5, 1, 0),
(2, 'Fußball', 7, 2, 0),
(3, 'Witze', 8, 3, 0),
(4, 'Möbel', 9, 4, 0),
(5, 'Haus', 20, 6, 0),
(6, 'Autos', 30, 3, 0),
(7, 'Katzen', 17, 5, 0),
(8, 'Pferde', 13, 5, 0),
(9, 'Zoo', 2, 8, 0),
(10, 'Bäume', 12, 8, 0),
(11, 'Pflanzen', 18, 10, 0),
(12, 'Neue Seite1', 31, 1, 0),
(13, 'Neue Seite2', 32, 1, 0);

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
  `templateconstruction` tinyint(1) NOT NULL DEFAULT '0',
  `databasemanagement` tinyint(1) NOT NULL DEFAULT '0',
  `backendlogin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `role`
--

INSERT INTO `role` (`id`, `uri`, `rolename`, `guestbookmanagement`, `usermanagement`, `pagemanagement`, `articlemanagement`, `guestbookusage`, `templateconstruction`, `databasemanagement`, `backendlogin`) VALUES
(1, 'uri.uri', 'Admin', 1, 1, 1, 1, 1, 1, 1, 1),
(2, 'uri.uri', 'Gast', 0, 0, 0, 0, 1, 0, 0, 0),
(3, 'uri.uri', 'Redakteur', 1, 0, 1, 1, 1, 0, 0, 0),
(4, 'uri.uri', 'Designer', 1, 0, 1, 1, 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `searchphrase`
--

CREATE TABLE `searchphrase` (
  `id` int(11) NOT NULL,
  `searchphrase` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `searchphrase`
--

INSERT INTO `searchphrase` (`id`, `searchphrase`) VALUES
(1, 'Blumen im Garten'),
(5, 'Eis in Spanien'),
(6, 'Fahrrad auf der Straße'),
(11, 'Fasching'),
(8, 'Flasche Wasser'),
(7, 'Gurken und Tomaten'),
(10, 'Ostern'),
(2, 'Pferde auf der Wiese'),
(3, 'Sonne auf der Wiese'),
(9, 'Strand'),
(4, 'Strand in Brasilien');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `searchphrase_user`
--

CREATE TABLE `searchphrase_user` (
  `user_id` int(11) NOT NULL,
  `searchphrase_id` int(11) NOT NULL,
  `searchdate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `searchphrase_user`
--

INSERT INTO `searchphrase_user` (`user_id`, `searchphrase_id`, `searchdate`) VALUES
(1, 1, '2016-12-19'),
(1, 2, '2016-12-22'),
(1, 5, '2000-03-09'),
(2, 3, '1990-08-15'),
(3, 2, '1998-10-22'),
(4, 10, '1999-01-05'),
(5, 6, '1992-01-05'),
(5, 7, '2016-12-22'),
(9, 7, '2016-12-22'),
(14, 10, '2000-01-05');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `template`
--

CREATE TABLE `template` (
  `id` int(11) NOT NULL,
  `templatename` varchar(255) NOT NULL,
  `filelink` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `template`
--

INSERT INTO `template` (`id`, `templatename`, `filelink`) VALUES
(1, 'Theresa1', 'TheresaOrdner1'),
(2, 'Theresa2', 'TheresaOrdner2'),
(3, 'Theresa3', 'TheresaOrdner3'),
(4, 'Theresa4', 'TheresaOrdner4'),
(5, 'Theresa5', 'TheresaOrdner5'),
(6, 'Theresa6', 'TheresaOrdner6'),
(7, 'Theresa7', 'TheresaOrdner7'),
(8, 'Theresa8', 'TheresaOrdner8'),
(9, 'Theresa9', 'TheresaOrdner9'),
(10, 'Theresa10', 'TheresaOrdner10');

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
  `password` varchar(512) NOT NULL,
  `email` varchar(255) NOT NULL,
  `registrydate` date NOT NULL,
  `birthdate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `role_id`, `lastname`, `firstname`, `username`, `password`, `email`, `registrydate`, `birthdate`) VALUES
(1, 1, 'Administrator', 'Admin', 'Admin-Admin', 'PLEASECHANGE', 'admin@e-mail.de', '2016-12-22', '2000-12-12'),
(2, 2, 'GastNachname', 'GastVorname', 'Gast-Gast', 'PLEASECHANGE', 'gast@e-mail.de', '2016-12-22', '1998-12-12'),
(3, 3, 'RedakteurNachname', 'RedakteurVorname', 'Redi-Redi', 'PLEASECHANGE', 'Redakteur@e-mail.de', '2016-12-22', '1998-12-25'),
(4, 4, 'DesignerNachname', 'DesignerVorname', 'Designer-Designer', 'PLEASECHANGE', 'Designer@e-mail.de', '2016-12-22', '2000-12-12'),
(5, 4, 'SDesigner', 'Theresa', 'Resi', 'ABC', 'ABC@FGHI.de', '2016-12-22', '2016-04-11'),
(6, 4, 'ODesigner', 'Cornelia', 'Conny', 'abcdef', 'abderfgi@jgkl.de', '2016-12-22', '2016-09-12'),
(7, 3, 'GRedakteur', 'Tamara', 'Tami', '345', 'abdfa@wqer.asdf', '2016-12-22', '2016-06-22'),
(8, 3, 'DRedakteur', 'Mirjam', 'M', '78876', '23@werwerwer.wie', '2016-12-22', '2015-01-14'),
(9, 4, 'KDesigner', 'Jonas', 'J', 'tzu', 'iuuoi@wewe.com', '2016-12-22', '2013-08-12'),
(10, 3, 'SRedakteur', 'Dimitrij', 'D', 'wertwert', 'wowowow@hohoh.de', '2016-12-22', '2016-12-11'),
(11, 4, 'Mustermann', 'Maximilian', 'Maxi', 'Max', 'Max.Mustermann@web.de', '2016-12-22', '2016-06-08'),
(12, 3, 'MRedakteur', 'Moritz', 'MM', 'Muster', 'Moritz@Muster.com', '2016-12-22', '2016-02-16'),
(13, 4, 'HeinrichDesigner', 'Hannah', 'Hanni', 'Hanni', 'Hanna.Heinrich@asdbdfag.de', '2016-12-22', '2015-08-19'),
(14, 3, 'HeinrichRedakteur', 'Nanni', 'N', 'Nanni', 'Nanni.Heinrich@web.asdfaer', '2016-12-22', '2016-12-12'),
(15, 3, 'Müller', 'Milch', 'Milch', 'Milch', 'Milch.Heinrich@web.asdfaer', '2016-12-22', '2016-12-12');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `website`
--

CREATE TABLE `website` (
  `id` int(11) NOT NULL,
  `headertitle` varchar(255) NOT NULL,
  `contact` text,
  `imprint` text,
  `privacyinformation` text,
  `gtc` text,
  `login` int(1) NOT NULL DEFAULT '0',
  `guestbook` tinyint(1) DEFAULT '0',
  `template_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
-- Indizes für die Tabelle `logtable`
--
ALTER TABLE `logtable`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title` (`title`),
  ADD KEY `website_id` (`website_id`);

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
-- Indizes für die Tabelle `website`
--
ALTER TABLE `website`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `headertitle` (`headertitle`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT für Tabelle `ban`
--
ALTER TABLE `ban`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT für Tabelle `ban_reason`
--
ALTER TABLE `ban_reason`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT für Tabelle `lable`
--
ALTER TABLE `lable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT für Tabelle `logtable`
--
ALTER TABLE `logtable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `page`
--
ALTER TABLE `page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT für Tabelle `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `searchphrase`
--
ALTER TABLE `searchphrase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT für Tabelle `template`
--
ALTER TABLE `template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT für Tabelle `website`
--
ALTER TABLE `website`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
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
