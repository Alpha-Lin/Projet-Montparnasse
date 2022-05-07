CREATE TABLE IF NOT EXISTS `subtag` (
  `fk_idSub` int(11) DEFAULT NULL,
  `fk_idTag` int(11) DEFAULT NULL,
  KEY `fk_idSub` (`fk_idSub`),
  KEY `fk_idTag` (`fk_idTag`)
)