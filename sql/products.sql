CREATE TABLE products (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    lastPrice SMALLINT unsigned NOT NULL, -- doit être recalculé à chaque fois
    description VARCHAR(300),
    releaseDate DATETIME NOT NULL DEFAULT NOW(),
    condition ENUM('Neuf', 'Comme neuf', 'Très bon état', 'Bon état', 'État correct', 'Mauvais état', 'Hors service', 'Autre') NOT NULL, 
    saleStatus BOOLEAN NOT NULL DEFAULT 0,
    premium BOOLEAN NOT NULL DEFAULT 0, -- modif + tard

    sellerID INT NOT NULL,
    FOREIGN KEY (sellerID) REFERENCES users(id)
);
