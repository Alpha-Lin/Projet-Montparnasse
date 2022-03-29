CREATE TABLE Notes (
    rating FLOAT(0, 5),
    voterID INT NOT NULL,
    rankedID INT NOT NULL,
    FOREIGN KEY (voterID) REFERENCES users(id),
    FOREIGN KEY (rankedID) REFERENCES users(id)
);