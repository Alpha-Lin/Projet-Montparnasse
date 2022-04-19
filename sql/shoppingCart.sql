CREATE TABLE shoppingCart (
    clientID INT NOT NULL,
    productID INT NOT NULL,

    FOREIGN KEY (clientID) REFERENCES users(id),
    FOREIGN KEY (productID) REFERENCES products(id),

    UNIQUE(clientID, productID)
);
