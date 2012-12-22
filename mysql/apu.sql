CREATE TABLE IF NOT EXISTS sponsorit(
    id INT UNIQUE AUTO_INCREMENT,
    kuva VARCHAR(50) NOT NULL,
    joukkueetID INT NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(joukkueetID) REFERENCES joukkueet
);
CREATE TABLE IF NOT EXISTS kuvakategoriat(
    id INT UNIQUE AUTO_INCREMENT,
    nimi VARCHAR(100) NOT NULL,
    jarjestysnumero INT,
    joukkueetID INT NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(joukkueetID) REFERENCES joukkueet
);
CREATE TABLE IF NOT EXISTS kuvat(
    id INT UNIQUE AUTO_INCREMENT,
    kuva VARCHAR(100) NOT NULL,
    kuvateksti TEXT,
    kuvakategoriatID INT NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(kuvakategoriatID) REFERENCES kuvakategoriat
);