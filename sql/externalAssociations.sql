CREATE TABLE externalAssociations (
    productID INT NOT NULL,
    externalProductID INT NOT NULL,
    FOREIGN KEY (productID) REFERENCES products(id),
    FOREIGN KEY (externalProductID) REFERENCES externalProducts(id)
);
