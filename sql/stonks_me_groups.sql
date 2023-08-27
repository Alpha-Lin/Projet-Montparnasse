CREATE TABLE stonks_me_groups ( -- représente les infos d'un groupe
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL UNIQUE,
    step TINYINT UNSIGNED NOT NULL DEFAULT 0,
    time_start DATETIME,
    time_end DATETIME,
    session INT NOT NULL,

    FOREIGN KEY (session) REFERENCES stonks_me_sessions(id),
);
