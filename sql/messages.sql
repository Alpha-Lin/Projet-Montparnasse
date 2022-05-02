CREATE TABLE messages (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    content VARCHAR(500) NOT NULL,
    releaseDate DATETIME NOT NULL DEFAULT NOW(),

    authorID INT NOT NULL,
    destID INT NOT NULL,
    FOREIGN KEY (authorID) REFERENCES users(id),
    FOREIGN KEY (destID) REFERENCES users(id)
);
