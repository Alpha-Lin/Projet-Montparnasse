CREATE TABLE IF NOT EXISTS `newsletter` (
  `idSub` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `mailSub` CHAR(50) NOT NULL UNIQUE
);