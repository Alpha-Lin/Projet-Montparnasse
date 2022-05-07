
CREATE TABLE IF NOT EXISTS `tags` (
  `idTag` int(11) NOT NULL AUTO_INCREMENT,
  `nameTage` varchar(50) NOT NULL,
  PRIMARY KEY (`idTag`),
  UNIQUE KEY `name` (`nameTag`)
)