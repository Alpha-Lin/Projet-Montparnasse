CREATE TABLE stonks-me_groups ( -- représente les infos d'un groupe
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR NOT NULL,
    step TINYINT UNSIGNED NOT NULL DEFAULT 0;
    time_start DATETIME,
    time_end DATETIME
);
-- devise ?
