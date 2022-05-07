CREATE TABLE IF NOT EXISTS `subscribers` (
  `idSub` int(11) NOT NULL AUTO_INCREMENT,
  `mailSub` char(50) NOT NULL,
  PRIMARY KEY (`idSub`),
  UNIQUE KEY `userMail` (`mailSub`)
)
