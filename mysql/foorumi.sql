CREATE TABLE IF NOT EXISTS keskustelualueryhmat(
    id INT UNIQUE AUTO_INCREMENT,
    otsikko VARCHAR(50) NOT NULL,
    PRIMARY KEY(id)
);
CREATE TABLE IF NOT EXISTS keskustelualueet(
    id INT UNIQUE AUTO_INCREMENT,
    nimi VARCHAR(50) NOT NULL,
    kuvaus VARCHAR(50) NOT NULL,
    keskustelualueryhmatID INT NOT NULL,
    julkinen INT,
    PRIMARY KEY(id),
    FOREIGN KEY(keskustelualueryhmatID) REFERENCES keskustelualueryhmat
);
CREATE TABLE IF NOT EXISTS keskustelualuekeskustelut(
    keskustelualueetID INT NOT NULL,
    keskustelutID INT NOT NULL,
    FOREIGN KEY(keskustelualueetID) REFERENCES keskustelualueet,
    FOREIGN KEY(keskustelutID) REFERENCES keskustelut
);
CREATE TABLE IF NOT EXISTS keskustelut(
    id INT UNIQUE AUTO_INCREMENT,
    otsikko VARCHAR(50) NOT NULL,
    aloitusaika TIMESTAMP NOT NULL,
    aloittaja INT NOT NULL,
    nakyypelaajille INT NOT NULL,
    tapahtumatID INT,
    PRIMARY KEY(id),
    FOREIGN KEY(aloittaja) REFERENCES tunnukset,
    FOREIGN KEY(tapahtumatID) REFERENCES tapahtumat
);
CREATE TABLE IF NOT EXISTS viestit(
    id INT UNIQUE AUTO_INCREMENT,
    otsikko VARCHAR(50),
    viesti TEXT NOT NULL,
    lahetysaika TIMESTAMP NOT NULL,
    keskustelutID INT NOT NULL,
    tunnuksetID INT NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(keskustelutID) REFERENCES keskustelut,
    FOREIGN KEY(tunnuksetID) REFERENCES tunnukset
);
CREATE TABLE IF NOT EXISTS keskustelualueoikeudet(
    keskustelualueetID INT NOT NULL,
    tunnuksetID INT NOT NULL,
    FOREIGN KEY(keskustelualueetID) REFERENCES keskustelualueet,
    FOREIGN KEY(tunnuksetID) REFERENCES tunnukset
);
CREATE TABLE IF NOT EXISTS tapahtumat(
    id INT UNIQUE AUTO_INCREMENT,
    nimi VARCHAR(200) NOT NULL,
    tapahtuma VARCHAR(20) NOT NULL,
    alkamisaika TIMESTAMP NOT NULL,
    loppumisaika TIMESTAMP,
    kuvaus TEXT,
    ilmotakaraja TIMESTAMP,
    ilmomaxmaara INT,
    lisatieto TEXT,
    paikka VARCHAR(50),
    PRIMARY KEY(id)
);
CREATE TABLE IF NOT EXISTS paikallaolo(
    lasna INT NOT NULL,
    viesti TEXT,
    tunnuksetID INT NOT NULL,
    tapahtumatID INT NOT NULL,
    FOREIGN KEY(tunnuksetID) REFERENCES tunnukset,
    FOREIGN KEY(tapahtumatID) REFERENCES tapahtumat
);