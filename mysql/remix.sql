CREATE TABLE IF NOT EXISTS tiedotukset(
    id INT UNIQUE AUTO_INCREMENT,
    tiedotus TEXT NOT NULL,
    kirjoitusaika TIMESTAMP NOT NULL,
    vanhenemisaika TIMESTAMP NOT NULL,
    eivanhenemisaikaa INT,
    joukkueetID INT,
    PRIMARY KEY(id),
    FOREIGN KEY(joukkueetID) REFERENCES joukkueet
);
CREATE TABLE IF NOT EXISTS joukkueet(
    id INT UNIQUE AUTO_INCREMENT,
    nimi VARCHAR(30) NOT NULL,
    kapteeni INT,
    kerho INT,
    salasana VARCHAR(50) NOT NULL,
    keskustelualueetID INT NOT NULL,
    jarjestysnumero INT,
    kausi VARCHAR(10),
    PRIMARY KEY(id),
    FOREIGN KEY(kapteeni) REFERENCES pelaajat
);
CREATE TABLE IF NOT EXISTS yhteyshenkilot(
    tiedot VARCHAR(200),
    rooli VARCHAR(30),
    joukkueetID INT NOT NULL,
    tunnuksetID INT NOT NULL,
    PRIMARY KEY(joukkueetID,tunnuksetID),
    FOREIGN KEY(joukkueetID) REFERENCES joukkueet,
    FOREIGN KEY(tunnuksetID) REFERENCES tunnukset
);
CREATE TABLE IF NOT EXISTS pelaajat(
    rooli VARCHAR(30),
    joukkueetID INT NOT NULL,
    tunnuksetID INT NOT NULL,
    pelinumero INT,
    kuva VARCHAR(50),
    PRIMARY KEY(joukkueetID,tunnuksetID),
    FOREIGN KEY(joukkueetID) REFERENCES joukkueet,
    FOREIGN KEY(tunnuksetID) REFERENCES tunnukset
);
CREATE TABLE IF NOT EXISTS pelaajakorttilisatieto(
    nimi TEXT NOT NULL,
    lisatieto TEXT NOT NULL,
    jarjestysnumero INT,
    joukkueetID INT NOT NULL,
    tunnuksetID INT NOT NULL,
    FOREIGN KEY(joukkueetID) REFERENCES joukkueet,
    FOREIGN KEY(tunnuksetID) REFERENCES tunnukset
);
CREATE TABLE IF NOT EXISTS tunnukset(
    id INT UNIQUE AUTO_INCREMENT,
    login VARCHAR(20),
    salasana VARCHAR(100) NOT NULL,
    email VARCHAR(50) NOT NULL,
    etunimi VARCHAR(50) NOT NULL,
    sukunimi VARCHAR(50) NOT NULL,
    syntymavuosi VARCHAR(4) NOT NULL,
    isadmin VARCHAR(20) NOT NULL,
	rpaivamaara TIMESTAMP NOT NULL,
    enabled INT NOT NULL,
    estetty INT NOT NULL,
    nakyvattiedotID INT,
    PRIMARY KEY(id),
    FOREIGN KEY(nakyvattiedotID) REFERENCES nakyvattiedot
);
CREATE TABLE IF NOT EXISTS kirjautumistiedot(
    ip VARCHAR(30) NOT NULL,
    aika TIMESTAMP NOT NULL,
    tunnuksetID INT NOT NULL,
    FOREIGN KEY(tunnuksetID) REFERENCES tunnukset
);
CREATE TABLE IF NOT EXISTS nakyvattiedot(
    id INT UNIQUE AUTO_INCREMENT,
    email INT,
    etunimi INT,
    sukunimi INT,
    syntymavuosi INT,
    PRIMARY KEY(id)
);
CREATE TABLE IF NOT EXISTS tokenit(
    token VARCHAR(100) NOT NULL,
    tunnuksetID INT,
    vieraskirjaID INT,
    FOREIGN KEY(tunnuksetID) REFERENCES tunnukset,
    FOREIGN KEY(vieraskirjaID) REFERENCES vieraskirja
);
CREATE TABLE IF NOT EXISTS vieraskirja(
    id INT UNIQUE AUTO_INCREMENT,
    kirjoittaja VARCHAR(20) NOT NULL,
    email VARCHAR(50),
    aika TIMESTAMP NOT NULL,
    seura VARCHAR(30),
    joukkue VARCHAR(30),
    viesti TEXT NOT NULL,
    ip VARCHAR(30) NOT NULL,
    poistettu INT,
    enabled INT,
    tunnuksetID INT,
    PRIMARY KEY(id),
    FOREIGN KEY(tunnuksetID) REFERENCES tunnukset
);
CREATE TABLE IF NOT EXISTS tilastoryhmat(
    id INT UNIQUE AUTO_INCREMENT,
    nimi VARCHAR(50) NOT NULL,
    oletus INT,
    kokonaistilastoon INT,
    joukkueetID INT NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(joukkueetID) REFERENCES joukkueet
);
CREATE TABLE IF NOT EXISTS tilastot(
    O INT NOT NULL,
    RLM INT NOT NULL,
    RLY INT NOT NULL,
    RM INT NOT NULL,
    S INT NOT NULL,
    M INT NOT NULL,
    plusmiinus INT NOT NULL,
    tunnuksetID INT NOT NULL,
    tilastoryhmatID INT NOT NULL,
    PRIMARY KEY(tunnuksetID, tilastoryhmatID),
    FOREIGN KEY(tunnuksetID) REFERENCES tunnukset,
    FOREIGN KEY(tilastoryhmatID) REFERENCES tilastoryhmat
);
CREATE TABLE IF NOT EXISTS peliryhmat(
    id INT UNIQUE AUTO_INCREMENT,
    nimi VARCHAR(50) NOT NULL,
    oletus INT,
    joukkueetID INT NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(joukkueetID) REFERENCES joukkueet
);
CREATE TABLE IF NOT EXISTS pelit(
    id INT UNIQUE AUTO_INCREMENT,
    vastustaja VARCHAR(50) NOT NULL,
    aika TIMESTAMP NOT NULL,
    koti INT NOT NULL,
    kotimaalit INT,
    vierasmaalit INT,
    pelattu INT NOT NULL,
    pelipaikka TEXT,
    kotiturnaus INT,
    peliryhmatID INT NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(peliryhmatID) REFERENCES peliryhmat
);
CREATE TABLE IF NOT EXISTS sarjataulukkoryhmat(
    id INT UNIQUE AUTO_INCREMENT,
    nimi VARCHAR(50) NOT NULL,
    oletus INT,
    joukkueetID INT NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(joukkueetID) REFERENCES joukkueet
);
CREATE TABLE IF NOT EXISTS sarjataulukot(
    id INT UNIQUE AUTO_INCREMENT,
    joukkue VARCHAR(50) NOT NULL,
    O INT NOT NULL,
    V INT NOT NULL,
    T INT NOT NULL,
    H INT NOT NULL,
    TM INT NOT NULL,
    PM INT NOT NULL,
    P INT NOT NULL,
    jarjestysnumero INT NOT NULL,
    sarjataulukkoryhmatID INT NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(sarjataulukkoryhmatID) REFERENCES sarjataulukkoryhmat
);
CREATE TABLE IF NOT EXISTS uutiset(
    id INT UNIQUE AUTO_INCREMENT,
    uutinen TEXT NOT NULL,
    kirjoitusaika TIMESTAMP NOT NULL,
	kirjoittaja VARCHAR(50),
    otsikko TEXT NOT NULL,
    kuvaus TEXT NOT NULL,
    kuva VARCHAR(100),
    asettele VARCHAR(10),
	tunnuksetID int,
    PRIMARY KEY(id),
	FOREIGN KEY(tunnuksetID) REFERENCES tunnukset
);
CREATE TABLE IF NOT EXISTS johtokunta(
    id INT UNIQUE AUTO_INCREMENT,
    etunimi VARCHAR(30) NOT NULL,
    sukunimi VARCHAR(30) NOT NULL,
    arvo VARCHAR(100),
    sahkoposti VARCHAR(50),
    puhelinnumero VARCHAR(20),
    jarjestysnumero INT,
    PRIMARY KEY(id)
);
CREATE TABLE IF NOT EXISTS oikeudet(
    keskustelualueetID INT,
    joukkueetID INT,
    tunnuksetID INT NOT NULL,
    FOREIGN KEY(keskustelualueetID) REFERENCES keskustelualueet,
    FOREIGN KEY(joukkueetID) REFERENCES joukkueet,
    FOREIGN KEY(tunnuksetID) REFERENCES tunnukset
);