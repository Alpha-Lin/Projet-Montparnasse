CREATE TABLE receiveReviews (
    reviewID INT NOT NULL,
    sellerID INT NOT NULL,

    FOREIGN KEY (reviewID) REFERENCES messages(id),
    FOREIGN KEY (sellerID) REFERENCES users(id)
);
