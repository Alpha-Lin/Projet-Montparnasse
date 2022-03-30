CREATE TABLE externalProducts ( -- représente les infos d'un produit
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    platform ENUM('AMAZON', 'CDISCOUNT', 'EBAY', 'LEBONCOIN', 'ALIEXPRESS', 'MATERIEL.NET', 'LDLC', 'TOPACHAT') NOT NULL,
    productID VARCHAR(151) NOT NULL,
    market ENUM('FR', 'US', 'UK', 'CA', 'IN'),
    price FLOAT unsigned NOT NULL,
    lastRefresh DATETIME NOT NULL DEFAULT NOW()
);
-- devise ?
