CREATE TABLE pictures(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fileName varchar(64) NOT NULL,
    
    productID INT NOT NULL,
    FOREIGN KEY (productID) REFERENCES products(id)
);
