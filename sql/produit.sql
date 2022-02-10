CREATE TABLE produit (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prix SMALLINT unsigned NOT NULL,
    description_produit VARCHAR(300),
    date_publication DATETIME NOT NULL DEFAULT NOW(),
    last_refesh DATETIME NOT NULL DEFAULT NOW(),
    vendeur_id INT,
    etat ENUM('Neuf', 'Comme neuf', 'Très bonne état', 'Bonne état', 'état correct', 'Mauvais état', 'Hors service', 'Autre') NOT NULL, 
    premium BOOLEAN NOT NULL DEFAULT 0, -- modif + tard
    FOREIGN KEY (vendeur_id) REFERENCES utilisateur(id) 
);
