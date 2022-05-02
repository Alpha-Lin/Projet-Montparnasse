CREATE TABLE notes (
    rating FLOAT(2, 1) NOT NULL,
    comment VARCHAR(1500),
    releaseDate DATETIME NOT NULL DEFAULT NOW(),

    purchaseID INT NOT NULL,
    FOREIGN KEY (purchaseID) REFERENCES purchases(id)
);
