CREATE TABLE association_externe (
    produit_id INT NOT NULL,
    produit_externe_id INT NOT NULL,
    FOREIGN KEY (produit_id) REFERENCES produit(id),
    FOREIGN KEY (produit_externe_id) REFERENCES produit_externe(id)
);
