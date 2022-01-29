CREATE TABLE produit_externe ( -- représente les infos d'un produit
    plateforme ENUM('AMAZON', 'CDISCOUNT', 'EBAY', 'LEBONCOIN', 'ALIEXPRESS', 'MATERIEL.NET', 'LDLC', 'TOPACHAT') NOT NULL,
    id VARCHAR(151) NOT NULL,
    marche ENUM('FR', 'US', 'UK', 'CA', 'IN'),
    prix SMALLINT,
    PRIMARY KEY(plateforme, id)
);
-- devise ?
