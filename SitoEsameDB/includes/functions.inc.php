<?php
    
                                        // REGISTRAZIONE //

    /**
     * Funzione di servizio utilizzata per controllare che i valori immessi nei campi
     * del form di registrazione al database di sistema non siano vuoti.
     */

    function emptyInputSignup($name, $surname, $email, $address, $city, $pwd, $pwdRepeat) {

        if(empty($name) || empty($surname) || empty($email) || empty($address) || empty($city) 
            || empty($pwd) || empty($pwdRepeat)) {

            return true;
        }

        else {
            return false;
        }
    }


    /**
     * Funzione di servizio utilizzata per controllare la validità dell'e-mail immessa
     * nell'apposito campo.
     */

    function invalidEmail($email) {

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }

        else{
            return false;
        }
    }


    /**
     * Funzione di servizio utilizzata per controllare che l'email utilizzata in fase di 
     * registrazione non sia già stata usata da un altro utente.
     */

    function emailExists($conn, $email) {

        // protezione da SQL injection
       
        $email = stripslashes($email);

        $email = mysqli_real_escape_string($conn, $email);
       
        // fine protezione da SQL injection

        $sql = "SELECT * 
                FROM Cliente 
                WHERE Email = ?;";

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../signup.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "s", $email);

        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($resultData)) {
            return $row; // qualcuno ha già usato l'e-mail inserita
        }

        else {
            return false; // nessuno ha usato tale e-mail
        }

        mysqli_stmt_close($stmt);
    }


    /**
     * Funzione di servizo utilizzata per controllare che le password inserite in fase di
     * registrazione coincidano.
     */

    function passwordMatch($pwd, $pwdRepeat) {

        if($pwd !== $pwdRepeat) {
            return true;
        }

        else {
            return false;
        }
    }


    /**
     * DA IMPLEMENTARE ???
     */

    function invalidPassword($pwd) { // lungehzza caratteri, caratteri speciali, ecc.ecc.
       
        $uppercase = preg_match('@[A-Z]@', $pwd); 
        $lowercase = preg_match('@[a-z]@', $pwd);
        $number    = preg_match('@[0-9]@', $pwd);

        if(!$uppercase || !$lowercase || !$number || strlen($pwd) < 8) {
            return true;
        }
        
        else {
            return false;
        }
    }


    /**
     * Funzione di servizio utilizzata per inserire all'interno del database di sistema
     * i dati inseriti dall'utente in fase di registrazione
     */

    function createUser($conn, $name, $surname, $email, $pwd, $address, $city) {

        // protezione da SQL injection

        $name = stripslashes($name);
        $surname = stripslashes($surname);
        $email = stripslashes($email);
        $pwd = stripslashes($pwd);
        $address = stripslashes($address);
        $city = stripslashes($city);

        $name = mysqli_real_escape_string($conn, $name);
        $surname = mysqli_real_escape_string($conn, $surname);
        $email = mysqli_real_escape_string($conn, $email);
        $pwd = mysqli_real_escape_string($conn, $pwd);
        $address = mysqli_real_escape_string($conn, $address);
        $city = mysqli_real_escape_string($conn, $city);

        // fine protezione da SQL injection

        $sql = "INSERT INTO Cliente(Nome, Cognome, Email, Password, Indirizzo, Citta) 
                VALUES(?, ?, ?, ?, ?, ?);";

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)) { 
            header("location: ../signup.php?error=stmtfailed");
            exit();
        }        

        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

        mysqli_stmt_bind_param($stmt, "ssssss", $name, $surname, $email, $hashedPwd, $address, $city);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        header("location: ../login.php?error=none");
        exit();
    }

                                        // ACCESSO Cliente//

    /**
     * Funzione di servizio utilizzata per controllare che i valori immessi nei campi
     * del form di accesso sistema non siano vuoti.
     */

    function emptyInputLogin($email, $pwd) {

        if(empty($email) || empty($pwd)) {
            return true;
        }

        else {
            return false;
        }
    }


    /**
     * Funzione di servizio utilizzata per permettere l'accesso al sistema all'utente.
     */

    function loginUser($conn, $email, $pwd) {

        // protezione da SQL injection

        $email = stripslashes($email);
        $pwd = stripslashes($pwd);
        
        $email = mysqli_real_escape_string($conn, $email);
        $pwd = mysqli_real_escape_string($conn, $pwd);

        // fine protezione da SQL injection

        $sql = "SELECT * 
                FROM Cliente 
                WHERE Email = ?;";

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../login.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "s", $email);

        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($resultData)) { // si ottiene una sola riga come risultato

            $pwdHashed = $row["Password"];

            $checkPwd = password_verify($pwd, $pwdHashed);

            if($checkPwd === false) {
                header("location: ../login.php?error=incorrectpassword");
                exit();
            }

            else if($checkPwd === true) { // se l'utente si è registrato e la password è corretta allora 

                session_start(); // si avvia una sessione

                // impostazione delle variabili di sessione (variabili globali)

                $_SESSION["IDCliente"] = $row["IDCliente"]; 
                $_SESSION["Nome"] = $row["Nome"];
                $_SESSION["Email"] = $row["Email"];

                header("location: ../index.php"); // reindirizzamento ad un'altra pagina
                exit();
            }
        }

        else { // non è stato trovato alcun utente con l'e-mail indicata

            header("location: ../login.php?error=wronglogin");
            exit();
        }

        mysqli_stmt_close($stmt);
    }

                                    // PAGINA PROFILO Cliente //

    /**
     * Funzione di servizio utilizzata per poter visualizzare gli ordini effettuati da un cliente.
     */

    function viewOrders($conn, $ID) {
        
        $sql = "SELECT O.IDOrdine, O.Spedito, O.DataOrdine, O.Totale, O.Quantita, Titolo, Autore, 
                       Editore, AnnoPubblicazione, Descrizione, NomeFormato, Prezzo, Pagine, NomeLingua, NomeGenere
                FROM Ordine AS O, Libro, Formato, Genere, Lingua
                WHERE IDCli = ? 
                AND IDL = IDLibro
                AND CodiceGenere = IDGenere
                AND CodiceFormato = IDFormato
                AND CodiceLingua = IDLingua
                ORDER BY O.IDOrdine;"; 

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../login.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "s", $ID);

        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);
            
        mysqli_stmt_close($stmt);

        return $resultData;
    }


    /**
     * Funzione di servizio utilizzata per poter visualizzare nella pagina profilo del cliente
     * i dettagli che (il cliente) ha inserito in fase di registrazione.
     */

    function viewDetails($conn, $ID) {

        $sql = "SELECT *
                FROM Cliente 
                WHERE IDCliente = ?;"; 

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../login.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "s", $ID);

        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);
            
        mysqli_stmt_close($stmt);

        return $resultData;
    }


    /**
     * Funzione di servizio utilizzata per controllare che l'email utilizzata in fase di 
     * aggiornamento dei dettagli dell'utente non sia già stata usata da un altro utente
     * (ad esclusione di se stesso).
     */
     
    function emailDetailsExists($conn, $email, $id) {

        // protezione da SQL injection
       
        $email = stripslashes($email);

        $email = mysqli_real_escape_string($conn, $email);
       
        // fine protezione da SQL injection

        $sql = "SELECT * 
                FROM Cliente 
                WHERE Email = ?
                AND IDCliente <> ?;";

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../profile.php?viewDetails=true&&error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "ss", $email, $id);

        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($resultData)) {
            return $row;
        }

        else {
            $result = false;

            return $result;
        }

        mysqli_stmt_close($stmt);
    }


    /**
     * Funzione di servizio utilizzata per poter modificare i dettagli dell'account del cliente
     * memorizzati nel database di sistema.
     */

    function editUserDetails($conn, $name, $surname, $email, $pwd, $address, $city, $tel, $id) {

         // protezione da SQL injection

         $name = stripslashes($name);
         $surname = stripslashes($surname);
         $email = stripslashes($email);
         $pwd = stripslashes($pwd);
         $address = stripslashes($address);
         $city = stripslashes($city);
         $tel = stripslashes($tel);
 
         $name = mysqli_real_escape_string($conn, $name);
         $surname = mysqli_real_escape_string($conn, $surname);
         $email = mysqli_real_escape_string($conn, $email);
         $pwd = mysqli_real_escape_string($conn, $pwd);
         $address = mysqli_real_escape_string($conn, $address);
         $city = mysqli_real_escape_string($conn, $city);
         $tel = mysqli_real_escape_string($conn, $tel);
 
         // fine protezione da SQL injection
        
         if(!empty($pwd)){ // istruzioni eseguite se si vuole modificare anche la password
            
            $sql = "UPDATE Cliente 
                    SET Nome=?, Cognome=?, Email=?, Password=?, Indirizzo=?, Citta=?, Cellulare=? 
                    WHERE IDCliente=?;";
 
            $stmt = mysqli_stmt_init($conn);
    
            if(!mysqli_stmt_prepare($stmt, $sql)) { 
                header("location: ../profile.php?viewDetails=true&&error=stmtfailed");
                exit();
            }        
    
            $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
    
            mysqli_stmt_bind_param($stmt, "ssssssss", $name, $surname, $email, $hashedPwd, $address, $city, $tel, $id);
    
         }

         else { // istruzioni eseguite se si vogliono modificare gli altri campi ma non la password
            
            $sql = "UPDATE Cliente 
                    SET Nome=?, Cognome=?, Email=?, Indirizzo=?, Citta=?, Cellulare=? 
                    WHERE IDCliente=?;";
 
            $stmt = mysqli_stmt_init($conn);
    
            if(!mysqli_stmt_prepare($stmt, $sql)) { 
                header("location: ../profile.php?viewDetails=true&&error=stmtfailed");
                exit();
            }        
    
            mysqli_stmt_bind_param($stmt, "sssssss", $name, $surname, $email, $address, $city, $tel, $id);
         }
        
         mysqli_stmt_execute($stmt);
 
         mysqli_stmt_close($stmt);
 
         header("location: ../profile.php?viewDetails=true&&error=none");
         exit();
    }

                            // HOME DEL SITO - DASHBOARD IMPIEGATO //

    /**
     * Funzione di servizio utilizzata per poter visualizzare i prodotti, con le relative informazioni,
     * che sono memorizzati nel database di sistema.
     */

    function viewProducts($conn, $file) {

        $sql = "SELECT IDLibro, Titolo, Autore, Editore, AnnoPubblicazione, Descrizione, 
                       NomeFormato, Prezzo, Pagine, NomeLingua, NomeGenere, ImmagineCopertina, Quantita
                FROM Libro, Formato, Genere, Lingua
                WHERE CodiceGenere = IDGenere
                AND CodiceFormato = IDFormato
                AND CodiceLingua = IDLingua
                ORDER BY IDLibro;"; 

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)) { 
            header("location: " . $file . ".php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);
            
        mysqli_stmt_close($stmt);

        return $resultData;
    }


                                        // RICERCA //

    /**
     * Funzione di servizio utilizzata per controllare che i valori immessi
     * all'interno del campo di ricerca di prodotti non sia vuoto.
     */

    function emptyInputSearch($searchField) {

        if(empty($searchField)) {
           return true;
        }

        else {
            return false;
        }
    }


    /**
     * Funzione di servizio utilizzata per ritornare i risultati che soddisfano i criteri di ricerca.
     */

    function searchBook($conn, $searchField) {

        // protezione da SQL injection

        $searchField = stripslashes($searchField);
        
        $searchField = mysqli_real_escape_string($conn, $searchField);

        // fine protezione da SQL injection

        /* sostituzione degli spazi con i caratteri pipe poichè il formato della REGEX di ricerca 
           deve essere il seguente: Io|Sono|Un|Esempio */

        $searchField = preg_replace('/\s+/', '|', $searchField); 

        $sql = "SELECT Titolo, Autore, Editore, AnnoPubblicazione, NomeGenere, ImmagineCopertina, 
                       Descrizione, Prezzo, NomeFormato, Quantita, IDLibro
                FROM Libro, Genere, Formato 
                WHERE CodiceGenere = IDGenere 
                AND CodiceFormato = IDFormato
                AND CONCAT_WS('|', Titolo, Autore, Editore, AnnoPubblicazione, NomeGenere) REGEXP ?;";

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../index.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "s", $searchField);

        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        mysqli_stmt_close($stmt);

        return $resultData;
    }

                                        // ACQUISTO Cliente//

    /**
     * Funzione di servizio utilizzata dal cliente per poter effettuare l'ordine/acquisto
     * di un prodotto.
     */

    function makeOrder($conn,  $bookID, $IDCliente, $bookQuantity, $Tot) {

        // protezione da SQL injection

        $bookID = stripslashes($bookID);
        $IDCliente = stripslashes($IDCliente);
        $bookQuantity = stripslashes($bookQuantity);
        $Tot = stripslashes($Tot);

        $bookID = mysqli_real_escape_string($conn, $bookID);
        $IDCliente = mysqli_real_escape_string($conn, $IDCliente);
        $bookQuantity = mysqli_real_escape_string($conn, $bookQuantity);
        $Tot = mysqli_real_escape_string($conn, $Tot);
       
        // fine protezione da SQL injection

        $sql1 = "CALL Acquisto(?, ?, ?, ?, @msg);"; // chiamata della routine di tipo Procedure per effettuare l'acquisto

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql1)) {
            header("location: ../checkout.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "ssss", $bookID, $IDCliente, $bookQuantity, $Tot);

        mysqli_stmt_execute($stmt);

        $sql2 = "SELECT @msg;"; // esito dell'acquisto

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql2)) {
            header("location: ../checkout.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        mysqli_stmt_close($stmt);

        return $resultData;
    }


                                        // ACCESSO Impiegato //
    
    /**
     * Funzione di servizio utilizzata per permettere l'accesso al sistema all'utente.
     */

    function loginEmp($conn, $username, $pwd) {

        // protezione da SQL injection

        $username = stripslashes($username);
        $pwd = stripslashes($pwd);
        
        $username = mysqli_real_escape_string($conn, $username);
        $pwd = mysqli_real_escape_string($conn, $pwd);

        // fine protezione da SQL injection

        $sql = "SELECT * 
                FROM Impiegato 
                WHERE NomeUtente = ?;";

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../employee/index.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "s", $username);

        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($resultData)) {

            $pwdDB = $row["Password"];

            if($pwd !== $pwdDB) {
                header("location: ../employee/index.php?error=incorrectpassword");
                exit();
            }

            else if($pwd === $pwdDB) { // se il nome utente è valido e la password è corretta allora 

                session_start(); // si avvia una sessione

                // impostazione delle variabili di sessione (variabili globali)

                $_SESSION["NomeUtente"] =  $row["NomeUtente"];
                $_SESSION["IDImpiegato"] =  $row["IDImpiegato"];

                header("location: ../employee/dashboard.php?viewStats=true");
                exit();
            }
        }

        else {

            header("location: ../employee/index.php?error=wronglogin");
            exit();
        }

        mysqli_stmt_close($stmt);
    }


                                    // DASHBOARD IMPIEGATO //

    /**
     * Funzione di servizio utilizzata per poter aggiornare la quantità
     * disponibile di un determinato prodotto.
     */

    function updateQuantity($conn, $idl, $ide, $qnt) {

        // protezione da SQL injection

        $idl = stripslashes($idl);
        $qnt = stripslashes($qnt);

        $idl = mysqli_real_escape_string($conn, $idl);
        $qnt = mysqli_real_escape_string($conn, $qnt);

        // fine protezione da SQL injection
       
        $sql = "UPDATE Libro 
                SET Quantita = Quantita + ?
                WHERE IDLibro=?;";

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../employee/dashboard.php?viewStock=true&&error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "ss", $qnt, $idl);

        mysqli_stmt_execute($stmt);

        $sql1 = "INSERT INTO Ricarica(CodLibro, CodImpiegato, Quantita) VALUES(?, ?, ?);"; // si tiene traccia di chi effettua l'operazione

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql1)) {
            header("location: ../employee/dashboard.php?viewStock=true&&error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "sss", $idl, $ide, $qnt);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        header("location: ../employee/dashboard.php?viewStock=true&&error=none");
        exit();
    }


    /**
     * Funzione di servizio utilizzata per poter visualizzare i formati
     * disponibili per i nuovi prodotti da inserire.
     */

    function viewFormat($conn) {

        $sql = "SELECT *
                FROM Formato;";

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../employee/dashboard.php?viewForm=true&&error=stmtfailed");
            exit();
        }

        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        mysqli_stmt_close($stmt);

        return $resultData;
    }


    /**
     * Funzione di servizio utilizzata per poter visualizzare le lingue
     * disponibili per i nuovi prodotti da inserire.
     */

    function viewLanguage($conn) {

        $sql = "SELECT *
                FROM Lingua;";

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../employee/dashboard.php?viewForm=true&&error=stmtfailed");
            exit();
        }

        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        mysqli_stmt_close($stmt);

        return $resultData;
    }


    /**
     * Funzione di servizio utilizzata per poter visualizzare i generi letterari
     * disponibili per i nuovi prodotti da inserire.
     */

    function viewGenre($conn) {

        $sql = "SELECT *
                FROM Genere;";

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../employee/dashboard.php?viewForm=true&&error=stmtfailed");
            exit();
        }

        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        mysqli_stmt_close($stmt);

        return $resultData;
    }


    /**
     * Funzione di servizio utilizzata per inserire un nuovo prodotto all'interno
     * del database di sistema.
     */

    function buyNewBook($conn, $title, $author, $publisher, $year, $description, $file, $format, $price, $qnt, $numPage, $lang, $genre, $ide) {

        // protezione da SQL injection

        $title = stripslashes($title);
        $author = stripslashes($author);
        $publisher = stripslashes($publisher);
        $year = stripslashes($year);
        $description = stripslashes($description);
        $file = stripslashes($file);
        $format = stripslashes($format);
        $price = stripslashes($price);
        $lang = stripslashes($lang);
        $qnt = stripslashes($qnt);
        $genre = stripslashes($genre);
        $numPage = stripslashes($numPage);

        $title = mysqli_real_escape_string($conn, $title);
        $author = mysqli_real_escape_string($conn, $author);
        $publisher = mysqli_real_escape_string($conn, $publisher);
        $year = mysqli_real_escape_string($conn, $year);
        $description = mysqli_real_escape_string($conn, $description);
        $file = mysqli_real_escape_string($conn, $file);
        $format = mysqli_real_escape_string($conn, $format);
        $price = mysqli_real_escape_string($conn, $price);
        $lang = mysqli_real_escape_string($conn, $lang);
        $qnt = mysqli_real_escape_string($conn, $qnt);
        $genre = mysqli_real_escape_string($conn, $genre);
        $numPage = mysqli_real_escape_string($conn, $numPage);
       
        // fine protezione da SQL injection

        $sql = "INSERT INTO Libro(Titolo, Autore, Editore, AnnoPubblicazione, Descrizione, ImmagineCopertina, CodiceFormato, Prezzo, Quantita, Pagine, CodiceLingua, CodiceGenere) 
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";

        $img = 'img/'.$file;

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../employee/dashboard.php?error=stmtfailed1");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "ssssssssssss", $title, $author, $publisher, $year, $description, $img, $format, $price, $qnt, $numPage, $lang, $genre);

        mysqli_stmt_execute($stmt);

        if (mysqli_errno($conn) == 1062) { // controllo che non sia stato generato un codice di errore di duplicate entry
            
            mysqli_stmt_close($stmt);
            header("location: ../employee/dashboard.php?viewForm=true&&error=duplicateentry");
            exit();
        }

        else {

            // query utilizzata per ottenere l'ID del libro appena inserito nel database

            $sql1 = "SELECT IDLibro
                    FROM Libro
                    WHERE Titolo = ?
                    AND Autore = ?
                    AND Editore = ?
                    AND AnnoPubblicazione = ?
                    AND CodiceFormato = ?
                    AND CodiceLingua = ?
                    AND CodiceGenere = ? ;"; 

            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sql1)) {
                header("location: ../employee/dashboard.php?viewForm=true&&error=stmtfailed2");
                exit();
            }

            mysqli_stmt_bind_param($stmt, "sssssss", $title, $author, $publisher, $year, $format, $lang, $genre);

            mysqli_stmt_execute($stmt);

            $resultData = mysqli_stmt_get_result($stmt);

            while($row = mysqli_fetch_assoc($resultData)) {

                $idL = $row["IDLibro"];
            }

            $sql2 = "INSERT INTO Ricarica(CodLibro, CodImpiegato, Quantita) VALUES(?, ?, ?);"; // si tiene traccia di chi esegue le operazioni

            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sql2)) {
                header("location: ../employee/dashboard.php?viewForm=true&&error=stmtfailed3");
                exit();
            }

            mysqli_stmt_bind_param($stmt, "sss", $idL, $ide, $qnt);

            mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);
        }
    }


    /**
     * Funzione di servizio utilizzata per poter visualizzare nella dashboard
     * dell'impiegato gli ordini che devono ancora essere spediti.
     */

    function viewOrdersToShip($conn) {

        $sql = "SELECT O.IDOrdine, O.Spedito, O.DataOrdine, O.Totale, O.Quantita, Titolo, Autore, Editore, 
                       AnnoPubblicazione, Descrizione, NomeFormato, Prezzo, Pagine, NomeLingua, NomeGenere
                FROM Ordine AS O, Libro, Formato, Genere, Lingua 
                WHERE IDL = IDLibro
                AND CodiceGenere = IDGenere
                AND CodiceFormato = IDFormato
                AND CodiceLingua = IDLingua
                AND O.Spedito = 0
                ORDER BY O.IDOrdine;"; 

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../employee/dashboard.php?viewOrderToShip=true&&error=stmtfailed");
            exit();
        }

        mysqli_stmt_execute($stmt);

        $resultData =  mysqli_stmt_get_result($stmt);
            
        mysqli_stmt_close($stmt);

        return $resultData;
    }


    /**
     * Funzione di servizio utilizzta per poter ottenere i dati statistici 
     * sulle vendite per ogni prodotto (in relazione alle vendite totali).
     */

    function viewStats($conn) {

        $sql = "SELECT IDL, Titolo, (count(*)/(SELECT count(*) FROM ordine))*100 AS Percentuale
                FROM ordine, libro
                WHERE IDL = IDLibro
                GROUP BY IDL, Titolo;";

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../employee/dashboard.php?viewStats=true&&error=stmtfailed");
            exit();
        }

        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);
            
        mysqli_stmt_close($stmt);

        return $resultData;
    }


    /**
     * Funzione di servizio utilizzata per poter spedire gli ordini.
     */

    function shipOrder($conn, $idO, $idImp) {

        // protezione da SQL injection

        $idO = stripslashes($idO);

        $idO = mysqli_real_escape_string($conn, $idO);

        // fine protezione da SQL injection

        $sql = "UPDATE Ordine 
                SET Spedito = 1
                WHERE IDOrdine=?;";

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../employee/dashboard.php?viewOrderToShip=true&&error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "s", $idO);

        mysqli_stmt_execute($stmt);

        $sql1 = "INSERT INTO spedizione(CodOrdine, CodImpiegato) VALUES(?, ?);"; // si tiene traccia di chi esegue le operazioni

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql1)) {
            header("location: ../employee/dashboard.php?viewOrderToShip=true&&error=stmtfailed2");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "ss", $idO, $idImp);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        header("location: ../employee/dashboard.php?viewOrderToShip=true&&error=none");
        exit();
    }
?>