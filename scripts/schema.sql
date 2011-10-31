
--
-- Tabellenstruktur für Tabelle `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message` text NOT NULL,
  `priority` int(11) NOT NULL,
  `priorityName` text NOT NULL,
  `info` text NOT NULL,
  `controller` text NOT NULL,
  `action` text NOT NULL,
  `module` text NOT NULL,
  PRIMARY KEY (`id`)
);