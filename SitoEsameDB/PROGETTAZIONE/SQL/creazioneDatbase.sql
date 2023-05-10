--Creazione della tabella cliente
CREATE TABLE Cliente (
  IDCliente int NOT NULL AUTO_INCREMENT,
  Nome varchar(255) NOT NULL,
  Cognome varchar(255) NOT NULL,
  Email varchar(255) NOT NULL,
  Password varchar(255) NOT NULL,
  Indirizzo varchar(255) NOT NULL,
  Cellulare varchar(10) DEFAULT NULL,
  Citta varchar(255) NOT NULL,
  PRIMARY KEY (IDCliente),
  UNIQUE (Email)
);

--Creazione della tabella impiegato
CREATE TABLE Impiegato (
  IDImpiegato int(11) NOT NULL AUTO_INCREMENT,
  Nome varchar(255) NOT NULL,
  Cognome varchar(255) NOT NULL,
  NomeUtente varchar(255) NOT NULL,
  Password varchar(255) NOT NULL,
  PRIMARY KEY (IDImpiegato),
  UNIQUE (NomeUtente)
);

--Creazione della tabella genere letterario
CREATE TABLE Genere (
  IDGenere int NOT NULL AUTO_INCREMENT,
  NomeGenere varchar(255) NOT NULL,
  PRIMARY KEY (IDGenere)
);

--Creazione della tabella formato libro
CREATE TABLE Formato (
  IDFormato int NOT NULL AUTO_INCREMENT,
  NomeFormato varchar(255) NOT NULL,
  PRIMARY KEY (IDFormato)
);

--Creazione della tabella lingua libro
CREATE TABLE Lingua (
  IDLingua int NOT NULL AUTO_INCREMENT,
  NomeLingua varchar(255) NOT NULL,
  PRIMARY KEY (IDLingua)
);

--Creazione della tabella libro
CREATE TABLE Libro (
  IDLibro int NOT NULL AUTO_INCREMENT,
  Titolo varchar(255) NOT NULL,
  Autore varchar(255) NOT NULL,
  Editore varchar(255) NOT NULL,
  AnnoPubblicazione year NOT NULL,
  Descrizione text NOT NULL,
  ImmagineCopertina varchar(255) NOT NULL,
  CodiceFormato int NOT NULL,
  Prezzo float NOT NULL,
  Quantita int NOT NULL,
  Pagine int NOT NULL,
  CodiceLingua int NOT NULL,
  CodiceGenere int NOT NULL,
  PRIMARY KEY (IDLibro),
  FOREIGN KEY (CodiceFormato) REFERENCES Formato (IDFormato),
  FOREIGN KEY (CodiceLingua) REFERENCES Lingua (IDLingua),
  FOREIGN KEY (CodiceGenere) REFERENCES Genere (IDGenere),
  UNIQUE (Titolo, Autore, Editore, AnnoPubblicazione, CodiceFormato, CodiceLingua)
);

--Creazione della tabella ordine
CREATE TABLE Ordine (
  IDOrdine int NOT NULL AUTO_INCREMENT,
  IDCli int NOT NULL,
  IDL int NOT NULL,
  Quantita int NOT NULL,
  Spedito tinyint(1) NOT NULL DEFAULT '0',
  DataOrdine timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  Totale float NOT NULL,
  PRIMARY KEY (IDOrdine, IDCli, IDL),
  FOREIGN KEY (IDCli) REFERENCES Cliente (IDCliente),
  FOREIGN KEY (IDL) REFERENCES Libro (IDLibro)
);

--Creazione della tabella ricarica
CREATE TABLE Ricarica (
  IDRicarica int(11) NOT NULL AUTO_INCREMENT,
  CodLibro int(11) NOT NULL,
  CodImpiegato int(11) NOT NULL,
  Quantita int(11) NOT NULL,
  DataRicarica timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (IDRicarica, CodLibro, CodImpiegato)
);

--Creazione della tabella spedizione
CREATE TABLE Spedizione (
  IDSpedizione int(11) NOT NULL AUTO_INCREMENT,
  CodOrdine int(11) NOT NULL,
  CodImpiegato int(11) NOT NULL,
  DataSpedizione timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (IDSpedizione, CodOrdine, CodImpiegato)
);