CREATE TABLE bankCards (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    number NUMERIC(16) NOT NULL,
    expirationDate DATE NOT NULL,
    cvc NUMERIC(4) NOT NULL,
    ownerName VARCHAR(60)
);
