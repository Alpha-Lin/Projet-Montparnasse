CREATE TABLE stonks_me_sessions ( -- représente les infos d'une session
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL UNIQUE,
    stepLock TINYINT UNSIGNED NOT NULL DEFAULT 0,
    time_start DATETIME,
    time_end DATETIME
);
