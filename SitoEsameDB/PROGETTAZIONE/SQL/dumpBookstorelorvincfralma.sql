-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Feb 20, 2021 alle 23:33
-- Versione del server: 10.1.39-MariaDB
-- Versione PHP: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookstorelorvincfralma`
--
CREATE DATABASE IF NOT EXISTS `bookstorelorvincfralma` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `bookstorelorvincfralma`;

DELIMITER $$
--
-- Procedure
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `Acquisto` (IN `IDProdotto` INT, IN `IDCliente` INT, IN `QntDesired` INT, IN `Tot` FLOAT, OUT `Msg` VARCHAR(255))  ProcAcquisto: BEGIN

	START TRANSACTION;
    
    IF QntDesired < 0 THEN
		ROLLBACK; 
        SET Msg='Errore quantità desiderata negativa.';
		LEAVE ProcAcquisto;
	END IF;
    
    CALL Ordine(IDProdotto, IDCliente, QntDesired, Tot, @EseguitoO);
	
	CASE @EseguitoO
		WHEN -1 THEN SET Msg='Errore nella conferma dell''ordine.';
		ELSE /* Risultato=1 */ BEGIN END;
	END CASE;
	
	IF @EseguitoO < 0 THEN
		ROLLBACK; 
		LEAVE ProcAcquisto;
	END IF;
	
	CALL Decrementa(IDProdotto, QntDesired, @EseguitoD);
	
	CASE @EseguitoD
		WHEN -1 THEN SET Msg='Errore nel decrementare quantità disponibile nel database.';
		ELSE /* Risultato=1 */ BEGIN END;
	END CASE;
	
	IF @EseguitoD < 0 THEN
		ROLLBACK; 
		LEAVE ProcAcquisto;
	ELSE	
		COMMIT;
		SET Msg='Acquisto completato.';
	END IF;
	
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Decrementa` (`IDProdotto` INTEGER, `QntBought` INTEGER, OUT `EseguitoD` INT)  ProcDecr: BEGIN

	DECLARE QntDisp INT;

	SELECT Quantita INTO QntDisp
    FROM libro
    WHERE IDLibro = IDProdotto;
    
    IF QntDisp < QntBought THEN
        SET EseguitoD = -1;
        LEAVE ProcDecr;
    END IF;
    
    IF QntDisp >= QntBought THEN
    	UPDATE libro SET Quantita = Quantita - QntBought WHERE IDLibro = IDProdotto;
        SET EseguitoD = 1;
    ELSE
    	SET EseguitoD = -1;
    END IF;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Ordine` (`IDProdotto` INT, `IDCliente` INT, `QntDesired` INT, `Tot` FLOAT, OUT `EseguitoO` INT)  ProcOrd: BEGIN

	DECLARE QntDisp INT;
    
    SELECT Quantita INTO QntDisp
    FROM libro
    WHERE IDLibro = IDProdotto;
    
    IF QntDisp < QntDesired THEN
        SET EseguitoO = -1;
        LEAVE ProcOrd;
    END IF;
    
    IF QntDisp >= QntDesired THEN
    	INSERT INTO ordine(IDCli, IDL, Quantita, Totale) VALUES(IDCliente, IDProdotto, QntDesired, Tot);
        SET EseguitoO = 1;
    ELSE
    	SET EseguitoO = -1;
    END IF;
    
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `cliente`
--

CREATE TABLE `cliente` (
  `IDCliente` int(11) NOT NULL,
  `Nome` varchar(255) NOT NULL,
  `Cognome` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Indirizzo` varchar(255) NOT NULL,
  `Cellulare` varchar(10) DEFAULT NULL,
  `Citta` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `cliente`
--

INSERT INTO `cliente` (`IDCliente`, `Nome`, `Cognome`, `Email`, `Password`, `Indirizzo`, `Cellulare`, `Citta`) VALUES
(1, 'Vincenzo', 'Fraello', 'vincenzo.fraello@outlook.it', '$2y$10$LYcA1bQq0rjpcK5KZHMBq.6p27aQ47VdZaqt31s/ByBwxv5l7WAIy', 'Via P. G. Cianci, 28', '3332662989', 'Sortino'),
(2, 'Lorenzo', 'Di Palma', 'lorenzo.dipalma@gmail.com', '$2y$10$og5YE.h5JqjZb7CxVWtTle9mAGP45vqxUuGim027KC5LicSySuAeq', 'Via P. G. Cianci, 26', '3928729246', 'Castel San Pietro Terme'),
(3, 'a', 'b', 'a@b.it', '$2y$10$ouSU9wSnqCRUBXMipSHO9.G5tbSo4Jh/zoBVxewi5u1nHsz5g7kmK', 'Via Piemonte, 13', '3332662989', 'Parma');

-- --------------------------------------------------------

--
-- Struttura della tabella `formato`
--

CREATE TABLE `formato` (
  `IDFormato` int(11) NOT NULL,
  `NomeFormato` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `formato`
--

INSERT INTO `formato` (`IDFormato`, `NomeFormato`) VALUES
(1, 'Tascabile'),
(2, 'Brossura'),
(3, 'Rilegato'),
(4, 'Cartonato'),
(5, 'AudioLibro'),
(6, 'Rilegatura di pregio');

-- --------------------------------------------------------

--
-- Struttura della tabella `genere`
--

CREATE TABLE `genere` (
  `IDGenere` int(11) NOT NULL,
  `NomeGenere` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `genere`
--

INSERT INTO `genere` (`IDGenere`, `NomeGenere`) VALUES
(1, 'Poliziesco'),
(2, 'Fantasy'),
(3, 'Cucina'),
(4, 'Informatica'),
(5, 'Scienze');

-- --------------------------------------------------------

--
-- Struttura della tabella `impiegato`
--

CREATE TABLE `impiegato` (
  `IDImpiegato` int(11) NOT NULL,
  `Nome` varchar(255) NOT NULL,
  `Cognome` varchar(255) NOT NULL,
  `NomeUtente` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `impiegato`
--

INSERT INTO `impiegato` (`IDImpiegato`, `Nome`, `Cognome`, `NomeUtente`, `Password`) VALUES
(1, 'admin', 'admin', 'root', 'root'),
(2, 'a', 'b', 'a@b', '123');

-- --------------------------------------------------------

--
-- Struttura della tabella `libro`
--

CREATE TABLE `libro` (
  `IDLibro` int(11) NOT NULL,
  `Titolo` varchar(255) NOT NULL,
  `Autore` varchar(255) NOT NULL,
  `Editore` varchar(255) NOT NULL,
  `AnnoPubblicazione` year(4) NOT NULL,
  `Descrizione` text NOT NULL,
  `ImmagineCopertina` varchar(255) NOT NULL,
  `CodiceFormato` int(11) NOT NULL,
  `Prezzo` float NOT NULL,
  `Quantita` int(11) NOT NULL,
  `Pagine` int(11) NOT NULL,
  `CodiceLingua` int(11) NOT NULL,
  `CodiceGenere` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `libro`
--

INSERT INTO `libro` (`IDLibro`, `Titolo`, `Autore`, `Editore`, `AnnoPubblicazione`, `Descrizione`, `ImmagineCopertina`, `CodiceFormato`, `Prezzo`, `Quantita`, `Pagine`, `CodiceLingua`, `CodiceGenere`) VALUES
(1, 'Sviluppo in PHP', 'Enrico Zimuel', 'Tecniche Nuove', 2019, 'Questa seconda edizione del libro, aggiornata con le ultime novità del PHP 7.3 e 7.4, è ricca di novità per consentire agli sviluppatori un utilizzo continuo e puntuale del linguaggio PHP, la tecnologia Open Source consolidata utilizzata dal 79% dei siti internet in tutto il mondo per lo sviluppo di applicazioni web e API professionali. Il manuale presenta i principali design pattern utilizzati nella progettazione di applicazioni professionali, per facilitare la manutenzione e il riutilizzo del codice, creando architetture software robuste e flessibili, forte dell’esperienza e dei progetti curati o ai quali ha partecipato l’autore. Inoltre contiene un nuovo capitolo di approfondimento sulla programmazione orientata agli oggetti in PHP e numerosi aggiornamenti sulle novità introdotte dalle versioni 7.3 e 7.4 del PHP. All’indirizzo: www.sviluppareinphp7.it si possono scaricare i codici sorgenti presenti nel libro.', 'img/sviluppoPHPEnricoZimuel.jpg', 2, 32.9, 62, 408, 1, 4),
(2, 'Profumi di Sicilia. Il libro della cucina siciliana', 'Giuseppe Coria', 'Cavallotto', 2019, 'Esistono alcuni libri che, nel tempo diventano dei classici. \"Profumi di Sicilia\" da più parti considerato \"la Bibbia della cucina siciliana\", non è soltanto un ricchissimo testo di cucina che nelle sue 700 pagine racchiude le più autentiche e genuine ricette della tradizione gastronomica siciliana: contiene anche una ricchissima serie di informazioni storiche, folcloristiche, etimologiche e sulle tradizioni che offrono al lettore una chiave privilegiata per indagare, da un originale e piacevole punto di vista, sul carattere della Sicilia e dei siciliani. Proposto in una tiratura speciale limitata e numerata arricchita da un elegante cofanetto e da una stampa d\'arte su carta cotone.', 'img/profumiCucinaGiuseppeCoria.jpg', 3, 119, 100, 670, 1, 3),
(3, 'Profumi di Sicilia. Il libro della cucina siciliana', 'Giuseppe Coria', 'Cavallotto', 2019, 'Esistono alcuni libri che, nel tempo diventano dei classici. \"Profumi di Sicilia\" da più parti considerato \"la Bibbia della cucina siciliana\", non è soltanto un ricchissimo testo di cucina che nelle sue 700 pagine racchiude le più autentiche e genuine ricette della tradizione gastronomica siciliana: contiene anche una ricchissima serie di informazioni storiche, folcloristiche, etimologiche e sulle tradizioni che offrono al lettore una chiave privilegiata per indagare, da un originale e piacevole punto di vista, sul carattere della Sicilia e dei siciliani. Proposto in una tiratura speciale limitata e numerata arricchita da un elegante cofanetto e da una stampa d\'arte su carta cotone.', 'img/profumiCucinaGiuseppeCoria.jpg', 2, 119, 38, 670, 1, 3),
(4, 'Blockchain criptovalute e ICO', 'Alessandro Basile', 'Flaccovio', 2019, 'La blockchain Ã¨ una realtÃ  tecnologica sempre piÃ¹ attuale che costituisce la base per nuovi business e imprese. Il libro fornisce unâ€™accurata introduzione al tema del paradigma tecnologico della blockchain, ripercorrendone la storia ed esplicitandone gli aspetti teorici e pratici, le caratteristiche e anche gli aspetti legali. Spaziando tra la sfera tecnica, la sfera legale e la sfera economica e finanziaria, lâ€™autore illustra come strutturare la catena, â€œminareâ€ le criptovalute e raccogliere capitali grazie alla blockchain e ai token. Inoltre riporta alcuni casi concreti di applicazione pratica della blockchain in diversi settori industriali senza dimenticare di analizzarne le implicazioni legali.', 'img/blockchain.jpg', 2, 22, 9, 201, 1, 4),
(5, 'Hacking finance. La rivoluzione digitale nella finanza tra bitcoin e crowdfunding', 'Francesco De Collibus, Lovercraft-Turing Ralph', 'Agenzia X', 2016, 'La crisi del 2008-09 ha accelerato le dinamiche di trasformazione del sistema finanziario che oggi appare prossimo a una rivoluzione simile a quella accaduta nellâ€™industria musicale, nel giornalismo e nellâ€™editoria. In questi settori le tecnologie digitali hanno portato cambiamenti radicali, modificando i modelli di business, gli attori principali, le modalitÃ  di fruizione dei contenuti e la struttura generale dellâ€™industria. Nella finanza il sistema Ã¨ ben piÃ¹ complesso, trilioni e trilioni di dollari sono accumulati, trasferiti in pochi secondi e impiegati ogni giorno tra molteplici metodi di pagamento, mercati azionari, contratti derivati e investimenti internazionali. Lâ€™innovazione finanziaria, specialmente quella che proviene dal basso, sta minando le fondamenta del sistema: nuove pratiche e tecnologie stanno portando lâ€™economia e la finanza verso una struttura basata su processi decentralizzati. Hacking Finance esplora queste linee di cambiamento e ne identifica limiti e possibilitÃ , con un linguaggio e un approccio hacker, vale a dire â€œsmontare la scatolaâ€, non seguire le istruzioni, ri-arrangiare i pezzi in modo non previsto. Centinaia di progetti indipendenti e startup stanno oggi partecipando a questa trasformazione che, per la prima volta, non Ã¨ guidata dai grandi attori come le banche di investimento, i governi e le banche centrali.', 'img/hackingFinance.jpg', 2, 11.99, 12, 160, 1, 4);

-- --------------------------------------------------------

--
-- Struttura della tabella `lingua`
--

CREATE TABLE `lingua` (
  `IDLingua` int(11) NOT NULL,
  `NomeLingua` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `lingua`
--

INSERT INTO `lingua` (`IDLingua`, `NomeLingua`) VALUES
(1, 'Italiano'),
(2, 'Inglese'),
(3, 'Tedesco'),
(4, 'Francese'),
(5, 'Spagnolo');

-- --------------------------------------------------------

--
-- Struttura della tabella `ordine`
--

CREATE TABLE `ordine` (
  `IDOrdine` int(11) NOT NULL,
  `IDCli` int(11) NOT NULL,
  `IDL` int(11) NOT NULL,
  `Quantita` int(11) NOT NULL,
  `Spedito` tinyint(1) NOT NULL DEFAULT '0',
  `DataOrdine` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Totale` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ordine`
--

INSERT INTO `ordine` (`IDOrdine`, `IDCli`, `IDL`, `Quantita`, `Spedito`, `DataOrdine`, `Totale`) VALUES
(1, 1, 2, 1, 1, '2021-02-18 14:10:00', 119),
(2, 1, 4, 1, 1, '2021-02-18 16:45:27', 22),
(3, 1, 5, 1, 1, '2021-02-18 16:45:27', 11.99),
(4, 2, 4, 1, 1, '2021-02-18 16:46:27', 22),
(5, 2, 1, 1, 1, '2021-02-18 16:46:27', 32.9),
(6, 2, 2, 2, 1, '2021-02-18 16:50:01', 238),
(7, 2, 1, 1, 1, '2021-02-18 16:50:01', 32.9),
(8, 3, 4, 1, 1, '2021-02-18 21:14:29', 22),
(9, 3, 5, 1, 0, '2021-02-18 21:14:29', 11.99),
(10, 1, 4, 1, 0, '2021-02-18 21:17:30', 22),
(11, 2, 1, 2, 0, '2021-02-18 21:18:12', 65.8),
(12, 1, 1, 2, 0, '2021-02-18 22:27:14', 65.8),
(13, 1, 3, 1, 0, '2021-02-18 22:27:14', 119);

-- --------------------------------------------------------

--
-- Struttura della tabella `ricarica`
--

CREATE TABLE `ricarica` (
  `IDRicarica` int(11) NOT NULL,
  `CodLibro` int(11) NOT NULL,
  `CodImpiegato` int(11) NOT NULL,
  `Quantita` int(11) NOT NULL,
  `DataRicarica` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ricarica`
--

INSERT INTO `ricarica` (`IDRicarica`, `CodLibro`, `CodImpiegato`, `Quantita`, `DataRicarica`) VALUES
(1, 4, 1, 13, '2021-02-18 16:15:25'),
(2, 5, 1, 13, '2021-02-18 16:23:50'),
(3, 5, 1, 1, '2021-02-18 16:54:20'),
(4, 1, 1, 1, '2021-02-18 22:05:40');

-- --------------------------------------------------------

--
-- Struttura della tabella `spedizione`
--

CREATE TABLE `spedizione` (
  `IDSpedizione` int(11) NOT NULL,
  `CodOrdine` int(11) NOT NULL,
  `CodImpiegato` int(11) NOT NULL,
  `DataSpedizione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `spedizione`
--

INSERT INTO `spedizione` (`IDSpedizione`, `CodOrdine`, `CodImpiegato`, `DataSpedizione`) VALUES
(1, 1, 1, '2021-02-18 16:44:40'),
(2, 2, 1, '2021-02-18 16:54:26'),
(3, 3, 1, '2021-02-18 16:54:26'),
(4, 4, 1, '2021-02-18 16:54:27'),
(5, 5, 1, '2021-02-18 16:54:28'),
(6, 6, 1, '2021-02-18 16:54:29'),
(7, 7, 1, '2021-02-18 16:54:29'),
(8, 8, 1, '2021-02-18 22:06:11');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`IDCliente`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indici per le tabelle `formato`
--
ALTER TABLE `formato`
  ADD PRIMARY KEY (`IDFormato`);

--
-- Indici per le tabelle `genere`
--
ALTER TABLE `genere`
  ADD PRIMARY KEY (`IDGenere`);

--
-- Indici per le tabelle `impiegato`
--
ALTER TABLE `impiegato`
  ADD PRIMARY KEY (`IDImpiegato`),
  ADD UNIQUE KEY `Email` (`NomeUtente`);

--
-- Indici per le tabelle `libro`
--
ALTER TABLE `libro`
  ADD PRIMARY KEY (`IDLibro`),
  ADD UNIQUE KEY `Titolo` (`Titolo`,`Autore`,`Editore`,`AnnoPubblicazione`,`CodiceFormato`,`CodiceLingua`),
  ADD KEY `CodiceFormato` (`CodiceFormato`),
  ADD KEY `CodiceLingua` (`CodiceLingua`),
  ADD KEY `CodiceGen` (`CodiceGenere`);

--
-- Indici per le tabelle `lingua`
--
ALTER TABLE `lingua`
  ADD PRIMARY KEY (`IDLingua`);

--
-- Indici per le tabelle `ordine`
--
ALTER TABLE `ordine`
  ADD PRIMARY KEY (`IDOrdine`,`IDCli`,`IDL`),
  ADD KEY `IDCli` (`IDCli`),
  ADD KEY `IDL` (`IDL`);

--
-- Indici per le tabelle `ricarica`
--
ALTER TABLE `ricarica`
  ADD PRIMARY KEY (`IDRicarica`,`CodLibro`,`CodImpiegato`),
  ADD KEY `CodImpiegato` (`CodImpiegato`),
  ADD KEY `CodLibro` (`CodLibro`);

--
-- Indici per le tabelle `spedizione`
--
ALTER TABLE `spedizione`
  ADD PRIMARY KEY (`IDSpedizione`,`CodOrdine`,`CodImpiegato`),
  ADD KEY `CodImpiegato` (`CodImpiegato`),
  ADD KEY `CodOrdine` (`CodOrdine`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `cliente`
--
ALTER TABLE `cliente`
  MODIFY `IDCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `formato`
--
ALTER TABLE `formato`
  MODIFY `IDFormato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `genere`
--
ALTER TABLE `genere`
  MODIFY `IDGenere` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `impiegato`
--
ALTER TABLE `impiegato`
  MODIFY `IDImpiegato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `libro`
--
ALTER TABLE `libro`
  MODIFY `IDLibro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `lingua`
--
ALTER TABLE `lingua`
  MODIFY `IDLingua` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `ordine`
--
ALTER TABLE `ordine`
  MODIFY `IDOrdine` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT per la tabella `ricarica`
--
ALTER TABLE `ricarica`
  MODIFY `IDRicarica` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `spedizione`
--
ALTER TABLE `spedizione`
  MODIFY `IDSpedizione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `libro`
--
ALTER TABLE `libro`
  ADD CONSTRAINT `libro_ibfk_1` FOREIGN KEY (`CodiceFormato`) REFERENCES `formato` (`IDFormato`),
  ADD CONSTRAINT `libro_ibfk_2` FOREIGN KEY (`CodiceLingua`) REFERENCES `lingua` (`IDLingua`),
  ADD CONSTRAINT `libro_ibfk_3` FOREIGN KEY (`CodiceGenere`) REFERENCES `genere` (`IDGenere`);

--
-- Limiti per la tabella `ordine`
--
ALTER TABLE `ordine`
  ADD CONSTRAINT `ordine_ibfk_1` FOREIGN KEY (`IDCli`) REFERENCES `cliente` (`IDCliente`),
  ADD CONSTRAINT `ordine_ibfk_2` FOREIGN KEY (`IDL`) REFERENCES `libro` (`IDLibro`);

--
-- Limiti per la tabella `ricarica`
--
ALTER TABLE `ricarica`
  ADD CONSTRAINT `ricarica_ibfk_1` FOREIGN KEY (`CodImpiegato`) REFERENCES `impiegato` (`IDImpiegato`),
  ADD CONSTRAINT `ricarica_ibfk_2` FOREIGN KEY (`CodLibro`) REFERENCES `libro` (`IDLibro`);

--
-- Limiti per la tabella `spedizione`
--
ALTER TABLE `spedizione`
  ADD CONSTRAINT `spedizione_ibfk_1` FOREIGN KEY (`CodImpiegato`) REFERENCES `impiegato` (`IDImpiegato`),
  ADD CONSTRAINT `spedizione_ibfk_2` FOREIGN KEY (`CodOrdine`) REFERENCES `ordine` (`IDOrdine`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
