CREATE TABLE IF NOT EXISTS `subtag` (
    `fk_idSub` int(11) DEFAULT NULL,
    `fk_idTag` int(11) DEFAULT NULL,
    FOREIGN KEY (fk_idSub) REFERENCES newsletter(idSub),
    FOREIGN KEY (fk_idTag) REFERENCES tags(idTags)
)