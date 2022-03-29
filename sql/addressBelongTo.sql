CREATE TABLE addressBelongTo (
    addressID INT NOT NULL,
    userID INT NOT NULL,

    FOREIGN KEY (addressID) REFERENCES addresses(id),
    FOREIGN KEY (userID) REFERENCES users(id)
);
