CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    pseudo VARCHAR(20) NOT NULL UNIQUE,
    lastName VARCHAR(30) NOT NULL,
    firstName VARCHAR(30) NOT NULL,
    country VARCHAR(43) NOT NULL,
    email VARCHAR(319) NOT NULL UNIQUE,
    phone NUMERIC(10) NOT NULL UNIQUE,
    password CHAR(97) NOT NULL,
    registerDate DATETIME NOT NULL DEFAULT NOW(),
    reputation FLOAT(2,1) UNSIGNED NOT NULL DEFAULT 0.0 CHECK(reputation <= 5.0),
    description VARCHAR(300),
    sales INT UNSIGNED NOT NULL DEFAULT 0,
    purchases INT UNSIGNED NOT NULL DEFAULT 0,
    picture mediumblob,
    `rank` ENUM("Bronze", "Fer", "Argent", "Or", "Diamant", "Platine") NOT NULL DEFAULT "Bronze",
    score INT NOT NULL DEFAULT 0,
    premium BOOLEAN NOT NULL DEFAULT 0,
    isAdmin BOOLEAN NOT NULL DEFAULT 0,
    idGroupe INT NOT NULL,

    FOREIGN KEY (idGroupe) REFERENCES stonks_me_groups(id)
);
