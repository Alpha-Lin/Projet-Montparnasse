CREATE TABLE utilisateur (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    pseudo VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(319) NOT NULL UNIQUE,
    mot_de_passe CHAR(97) NOT NULL,
    date_inscription DATETIME NOT NULL DEFAULT NOW(),
    reputation DECIMAL(2,1) UNSIGNED NOT NULL DEFAULT 0.0 CHECK(reputation <= 5.0),
    nb_vente INT UNSIGNED NOT NULL DEFAULT 0,
    description_perso VARCHAR(300)
);

-- commentaires --> autre table
