CREATE TABLE products (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    lastPrice FLOAT unsigned NOT NULL DEFAULT 0, -- doit être recalculé à chaque fois
    marketPosition INT NOT NULL CHECK(marketPosition BETWEEN 1 AND 100),
    description VARCHAR(300),
    releaseDate DATETIME NOT NULL DEFAULT NOW(),
    conditionP ENUM('Neuf', 'Comme neuf', 'Très bon état', 'Bon état', 'État correct', 'Mauvais état', 'Hors service', 'Autre') NOT NULL,
    category ENUM('Promo', 'Musique', 'Multimédia', 'Santé', 'Électroménager', 'Décoration', 'Bricolage', 'Animaux', 'Autre') NOT NULL, 
    saleStatus BOOLEAN NOT NULL DEFAULT 0,
    premium BOOLEAN NOT NULL DEFAULT 0,

    sellerID INT NOT NULL,
    FOREIGN KEY (sellerID) REFERENCES users(id)
);
