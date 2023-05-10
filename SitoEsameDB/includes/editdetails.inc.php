<?php
    session_start(); // avvio della sessione

    if(isset($_SESSION["Email"])) { // se l'utente è loggato

        if(isset($_POST["saveDetailsBtn"])) { // se viene cliccato il bottone Salva 

            $name = $_POST["name"];
            $surname = $_POST["surname"];
            $email = $_POST["email"];
            $address = $_POST["address"];
            $city = $_POST["city"];
            $tel = $_POST["tel"];
            $pwd = $_POST["pwd"];
            $pwdRepeat = $_POST["pwdRepeat"];

            $id = $_SESSION["IDCliente"];
    
            require_once 'dbh.inc.php';
            require_once 'functions.inc.php';
    
            // gestori degli errori
    
            if(invalidEmail($email) !== false) { // se email inserita non è valida
                header("location: ../profile.php?viewDetails=true&&error=invalidemail");
                exit();
            }
    
            if(emailDetailsExists($conn, $email, $id) !== false) { // email già usata da altri user
                header("location: ../profile.php?viewDetails=true&&error=emailexists");
                exit();
            }
    
            if((!empty($pwd) || !empty($pwdRepeat)) && (passwordMatch($pwd, $pwdRepeat) !== false)) { // se i campi password non sono vuoti e i due valori non coincidono
                header("location: ../profile.php?viewDetails=true&&error=passdoesntmatch");
                exit();
            }

            if(!empty($pwd)) {

                if(invalidPassword($pwd) !== false) { // se i criteri minimi non vengono rispettati c'è un errore
                    header("location: ../profile.php?viewDetails=true&&error=invalidpassword");
                    exit();
                }
            }
            
            editUserDetails($conn, $name, $surname, $email, $pwd, $address, $city, $tel, $id); // viene richiamata la funzione per modificare i dettagli dell'account
        }
    
        else {
            
            header("location: ../profile.php?viewDetails=true");
            exit();
        }
    }
 ?>