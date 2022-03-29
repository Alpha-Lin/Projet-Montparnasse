CREATE TABLE askAnswer (
    questionID INT NOT NULL,
    answerID INT NOT NULL,
    clientID INT NOT NULL,
    sellerID INT NOT NULL,

    FOREIGN KEY (questionID) REFERENCES messages(id),
    FOREIGN KEY (answerID) REFERENCES messages(id),
    FOREIGN KEY (clientID) REFERENCES users(id),
    FOREIGN KEY (sellerID) REFERENCES users(id)

);
