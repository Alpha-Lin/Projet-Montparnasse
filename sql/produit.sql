CREATE TABLE produit (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    dernier_prix SMALLINT unsigned NOT NULL, -- doit être recalculé à chaque fois
    description_produit VARCHAR(300),
    date_publication DATETIME NOT NULL DEFAULT NOW(),
    vendeur_id INT NOT NULL,
    etat ENUM('Neuf', 'Comme neuf', 'Très bon état', 'Bon état', 'État correct', 'Mauvais état', 'Hors service', 'Autre') NOT NULL, 
    premium BOOLEAN NOT NULL DEFAULT 0, -- modif + tard
    FOREIGN KEY (vendeur_id) REFERENCES utilisateur(id)
);
