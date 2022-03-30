CREATE TABLE Notes (
    rating FLOAT(2, 1),
    voterID INT NOT NULL,
    rankedID INT NOT NULL,
    FOREIGN KEY (voterID) REFERENCES users(id),
    FOREIGN KEY (rankedID) REFERENCES users(id)
);
