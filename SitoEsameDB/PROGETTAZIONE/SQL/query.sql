--query utilizzata per controllare che l'email sia già stata registrata nel database --> emailExists
SELECT * 
FROM Cliente 
WHERE Email = $email;

--query utilizzata per registrare un utente nel database di sistema --> createUser
INSERT INTO Cliente(Nome, Cognome, Email, Password, Indirizzo, Citta) VALUES($name, $surname, $email, $hashedPwd, $address, $city);

--query utilizzata per effettuare il login dell'utente --> loginUser
SELECT * 
FROM Cliente 
WHERE Email = $email;

--query utilizzata per visualizzare lo storico ordini di un utente --> viewOrders        
SELECT O.IDOrdine, O.Spedito, O.DataOrdine, O.Totale, O.Quantita, Titolo, Autore, Editore, AnnoPubblicazione, 
       Descrizione, NomeFormato, Prezzo, Pagine, NomeLingua, NomeGenere
FROM Ordine AS O, Libro, Formato, Genere, Lingua 
WHERE IDCli = $ID
AND IDL = IDLibro
AND CodiceGenere = IDGenere
AND CodiceFormato = IDFormato
AND CodiceLingua = IDLingua
ORDER BY O.IDOrdine;; 

--query utilizzata per visualizzare i prodotti disponibili --> viewProducts
SELECT IDLibro, Titolo, Autore, Editore, AnnoPubblicazione, Descrizione, NomeFormato, Prezzo, Pagine, NomeLingua, 
       NomeGenere, ImmagineCopertina, Quantita
FROM Libro, Formato, Genere, Lingua
WHERE CodiceGenere = IDGenere
AND CodiceFormato = IDFormato
AND CodiceLingua = IDLingua
ORDER BY IDLibro; 

--query utilizzata per cercare un prodotto (libro) all'interno del sistema --> searchBook
SELECT Titolo, Autore, Editore, AnnoPubblicazione, NomeGenere, ImmagineCopertina, Descrizione, Prezzo, NomeFormato, 
       Quantita, IDLibro
FROM Libro, Genere, Formato 
WHERE CodiceGenere = IDGenere 
AND CodiceFormato = IDFormato
AND CONCAT_WS(Titolo, Autore, Editore, AnnoPubblicazione, NomeGenere) REGEXP  $searchField;

--query usata per comprare un prodotto --> makeOrder --> vedi anche acquistoProcedureTransazioni.sql
CALL Acquisto(?, ?, ?, ?, @msg);

--query usata per controllare l'esito dell'acquisto prodotto --> makeOrder --> vedi anche acquistoProcedureTransazioni.sql
SELECT @msg;

--query usata per visualizzare i dettagli dell'account dell'utente --> viewDetails
SELECT *
FROM Cliente 
WHERE IDCliente = ?;

--query usata per controllare che non esista già una email uguale di un altro utente --> emailDetailsExists
SELECT * 
FROM Cliente 
WHERE Email = $email
AND IDCliente <> $id;

--quer usata per modificare i dettagli dell'account di un utente (compresa la password) --> editUserDetails
UPDATE Cliente 
SET Nome=$name, Cognome=$surname, Email=$email, Password=$hashedPwd, Indirizzo=$address, Citta=$city, Cellulare=$tel 
WHERE IDCliente=$id;

--quer usata per modificare i dettagli dell'account di un utente (senza la password) --> editUserDetails
UPDATE Cliente 
SET Nome=$name, Cognome=$surname, Email=$email, Indirizzo=$address, Citta=$city, Cellulare=$tel 
WHERE IDCliente=$id;

--query usata per il login dell'impiegato --> loginEmp 
SELECT * 
FROM Impiegato 
WHERE NomeUtente = $username;

--query usata per aggiornare la quantità disponibile di un prodotto --> updateQuantity
UPDATE Libro 
SET Quantita = Quantita + $qnt
WHERE IDLibro=$idl;

--query usata per tracciare l'operazione di aggiornamento quantità prodotto --> updateQuantity
INSERT INTO Ricarica(CodLibro, CodImpiegato, Quantita) VALUES($idl, $ide, $qnt); 

--query usata per ottenere i formati dei libri --> viewFormat
SELECT *
FROM Formato;

--query usata per ottenere le lingue dei libri --> viewLanguage
SELECT *
FROM Lingua;

--query usata per ottenere i generi dei libri --> viewGenre
SELECT *
FROM Genere;
       
--query usata per inserire un libro --> buyNewBook
INSERT INTO Libro(Titolo, Autore, Editore, AnnoPubblicazione, Descrizione, 
       ImmagineCopertina, CodiceFormato, Prezzo, Quantita, Pagine, CodiceLingua, CodiceGenere) 
              VALUES($title, $author, $publisher, $year, $description, $img, $format, $price, $qnt, 
                     $numPage, $lang, $genre);

--query usata per estrarre l'id dell'ultimo libro inserito per poter tracciare l'operazione --> buyNewBook
SELECT IDLibro 
FROM Libro
WHERE Titolo = $title
AND Autore = $author
AND Editore = $publisher
AND AnnoPubblicazione = $year
AND CodiceFormato = $format
AND CodiceLingua = $lang 
AND CodiceGenere = $genre;

--query usata per inserire/tracciare le operazioni di acquisto - ricarica dell'impiegato --> buyNewBook      
INSERT INTO Ricarica(CodLibro, CodImpiegato, Quantita) VALUES($idL, $ide, $qnt);

--query usata per visualizzare gli ordini da spedire --> viewOrdersToShip
SELECT O.IDOrdine, O.Spedito, O.DataOrdine, O.Totale, O.Quantita, Titolo, Autore, Editore, AnnoPubblicazione, Descrizione, NomeFormato, Prezzo, Pagine, NomeLingua, NomeGenere
FROM Ordine AS O, Libro, Formato, Genere, Lingua 
WHERE IDL = IDLibro
AND CodiceGenere = IDGenere
AND CodiceFormato = IDFormato
AND CodiceLingua = IDLingua
AND O.Spedito = 0
ORDER BY O.IDOrdine;

--query usata per calcolare le statistiche delle vendite --> viewStats
SELECT IDL, Titolo, count(*)/(SELECT count(*) FROM ordine) AS Percentuale
FROM ordine, libro
WHERE IDL = IDLibro
GROUP BY IDL, Titolo;

--query usata per spedire l'ordine --> shipOrder
UPDATE Ordine 
SET Spedito = 1
WHERE IDOrdine=$idO;

--query usata per inserire il tracciamento dell'operazione di spedizione dell'impiegato --> shipOrder
INSERT INTO spedizione(CodOrdine, CodImpiegato) VALUES($idO, $idImp);
