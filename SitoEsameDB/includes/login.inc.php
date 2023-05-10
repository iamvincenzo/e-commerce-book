<?php
    if(isset($_POST["submit"])) { // se viene premuto il tasto Accedi

        $email = $_POST["email"];
        $pwd = $_POST["pwd"];

        require_once 'dbh.inc.php';
        require_once 'functions.inc.php';

        if(emptyInputLogin($email, $pwd) !== false) { // se l'utente non compila tutti i campi durante la fase di accesso
            header("location: ../login.php?error=emptyinput");
            exit();
        }

        loginUser($conn, $email, $pwd); // richiama la funzione di accesso al sistema da parte dell'utente
    }

    else {
        header("location: ../login.php");
        exit();
    }
?>