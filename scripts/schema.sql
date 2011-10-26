
--
-- Tabellenstruktur für Tabelle `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `message` text NOT NULL,
  `errno` int(11) NOT NULL,
  `file` text NOT NULL,
  `line` int(11) NOT NULL,
  `context` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);
