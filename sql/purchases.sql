CREATE TABLE purchases (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    purchaseDATE DATETIME NOT NULL DEFAULT NOW(),

    buyerID INT NOT NULL,
    productID INT NOT NULL,
    deliveringAddressID INT NOT NULL,
    billingAddressID INT NOT NULL,
    bankCardID INT NOT NULL,
    FOREIGN KEY (buyerID) REFERENCES users(id),
    FOREIGN KEY (productID) REFERENCES products(id),
    FOREIGN KEY (deliveringAddressID) REFERENCES addresses(id),
    FOREIGN KEY (billingAddressID) REFERENCES addresses(id),
    FOREIGN KEY (bankCardID) REFERENCES bankCards(id)
);
