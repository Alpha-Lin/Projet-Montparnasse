CREATE TABLE askAnswer (
    productID INT NOT NULL,
    questionID INT NOT NULL,
    answerID INT,

    FOREIGN KEY (productID) REFERENCES products(id),
    FOREIGN KEY (questionID) REFERENCES messages(id),
    FOREIGN KEY (answerID) REFERENCES messages(id)
);
