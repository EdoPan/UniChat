DROP DATABASE IF EXISTS UniChat;
CREATE DATABASE UniChat;
USE UniChat;



CREATE TABLE fotoprofilo(
    fotoID int NOT NULL auto_increment,
    nome varchar(40) NOT NULL,
    dimensione varchar(20) NOT NULL,
    tipo varchar(20) NOT NULL,
    immagine longblob NOT NULL,
    PRIMARY KEY (fotoID)
);

CREATE TABLE users(
    userID int NOT NULL auto_increment,
    nome varchar(30) NOT NULL,
    cognome varchar(30) NOT NULL,
    email varchar(60) NOT NULL,
    password char(60) NOT NULL,
    fotoProfiloID int,
    corsoStudio varchar(75) DEFAULT 'Sconosciuto',
    moderatore boolean NOT NULL DEFAULT false,
    admin boolean NOT NULL DEFAULT false,
    PRIMARY KEY (userID),
    UNIQUE (email),
    INDEX (email),
    FOREIGN KEY (fotoProfiloID) REFERENCES fotoprofilo(fotoID) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE messaggi(
    messID int NOT NULL auto_increment,
    autoreMessID int,
    testo varchar(300) NOT NULL,
    data datetime NOT NULL,
    PRIMARY KEY (messID),
    FOREIGN KEY (autoreMessID) REFERENCES users(userID) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE icone(
    iconaID int NOT NULL auto_increment,
    nome varchar(40) NOT NULL,
    dimensione varchar(20) NOT NULL,
    tipo varchar(20) NOT NULL,
    immagine longblob NOT NULL,
    PRIMARY KEY (iconaID)
);

CREATE TABLE categorie(
    categoriaID int NOT NULL auto_increment,
    moderatoreID int DEFAULT NULL,
    nome varchar(25) NOT NULL,
    iconaID int,
    descrizione varchar(75) NOT NULL DEFAULT '',
    PRIMARY KEY (categoriaID),
    UNIQUE (nome),
    UNIQUE (moderatoreID),
    FOREIGN KEY (moderatoreID) REFERENCES users(userID) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (iconaID) REFERENCES icone(iconaID) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE valutazioni(
    valutazioneID int NOT NULL auto_increment,
    totale int NOT NULL DEFAULT 0,
    PRIMARY KEY (valutazioneID)
);

CREATE TABLE votipositivi(
    valutazioneID int NOT NULL,
    userID int NOT NULL,
    PRIMARY KEY (valutazioneID, userID),
    FOREIGN KEY (valutazioneID) REFERENCES valutazioni(valutazioneID) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE votinegativi(
    valutazioneID int NOT NULL,
    userID int NOT NULL,
    PRIMARY KEY (valutazioneID, userID),
    FOREIGN KEY (valutazioneID) REFERENCES valutazioni(valutazioneID) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE threads(
    threadID int NOT NULL auto_increment,
    autoreThreadID int,
    catThreadID int,
    valutazioneThreadID int,
    titolo varchar(200) NOT NULL,
    testo varchar(600) NOT NULL,
    data datetime NOT NULL,
    PRIMARY KEY (threadID),
    FOREIGN KEY (autoreThreadID) REFERENCES users(userID) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (catThreadID) REFERENCES categorie(categoriaID) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (valutazioneThreadID) REFERENCES valutazioni(valutazioneID) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE allegati(
    allegatoID int NOT NULL auto_increment,
    threadID int NOT NULL,
    nome varchar(40) NOT NULL,
    dimensione varchar(20) NOT NULL,
    tipo varchar(20) NOT NULL,
    file longblob NOT NULL,
    PRIMARY KEY (allegatoID),
    FOREIGN KEY (threadID) REFERENCES threads(threadID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE risposte(
    rispostaID int NOT NULL auto_increment,
    autoreRispID int,
    threadRispID int NOT NULL,
    testo varchar(600) NOT NULL,
    data datetime NOT NULL,
    PRIMARY KEY (rispostaID),
    FOREIGN KEY (autoreRispID) REFERENCES users(userID) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (threadRispID) REFERENCES threads(threadID) ON DELETE CASCADE ON UPDATE CASCADE
);

ALTER TABLE threads
ADD FULLTEXT (titolo);


