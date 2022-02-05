
CREATE TABLE IF NOT EXISTS `image` (
  `img_name` varchar(256) NOT NULL,
  `img_data` longblob NOT NULL,
  PRIMARY KEY (`img_name`)
) 