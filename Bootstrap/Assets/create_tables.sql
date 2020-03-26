-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 10. Okt 2019 um 07:46
-- Server-Version: 5.7.27-0ubuntu0.18.04.1
-- PHP-Version: 7.2.19-0ubuntu0.18.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Datenbank: `shopware`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp_order_export`
--

CREATE TABLE `cp_order_export` (
  `id` bigint(11) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastOrderId` bigint(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp_order_status`
--

CREATE TABLE `cp_order_status` (
  `id` int(11) NOT NULL,
  `orderid` int(11) NOT NULL,
  `orderNumber` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(8) NOT NULL DEFAULT '0',
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comment` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `cp_order_export`
--
ALTER TABLE `cp_order_export`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indizes für die Tabelle `cp_order_status`
--
ALTER TABLE `cp_order_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orderid` (`orderid`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `cp_order_export`
--
ALTER TABLE `cp_order_export`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `cp_order_status`
--
ALTER TABLE `cp_order_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
