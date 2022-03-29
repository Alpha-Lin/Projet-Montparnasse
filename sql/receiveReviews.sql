CREATE TABLE receiveReviews (
    reviewID INT NOT NULL,
    clientID INT NOT NULL,
    sellerID INT NOT NULL,

    FOREIGN KEY (reviewID) REFERENCES messages(id),
    FOREIGN KEY (clientID) REFERENCES users(id),
    FOREIGN KEY (sellerID) REFERENCES users(id)
);
