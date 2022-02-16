CREATE TABLE produit_externe ( -- représente les infos d'un produit
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    plateforme ENUM('AMAZON', 'CDISCOUNT', 'EBAY', 'LEBONCOIN', 'ALIEXPRESS', 'MATERIEL.NET', 'LDLC', 'TOPACHAT') NOT NULL,
    produit_id VARCHAR(151) NOT NULL,
    marche ENUM('FR', 'US', 'UK', 'CA', 'IN'),
    prix SMALLINT NOT NULL
);
-- devise ?
