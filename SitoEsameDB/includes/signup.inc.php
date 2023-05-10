<?php
    if(isset($_POST["submit"])) { // se viene premuto il tasto Registrami

        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $email = $_POST["email"];
        $address = $_POST["address"];
        $city = $_POST["city"];
        $pwd = $_POST["pwd"];
        $pwdRepeat = $_POST["pwdRepeat"];

        require_once 'dbh.inc.php';
        require_once 'functions.inc.php';

        // gestori degli errori

        if(emptyInputSignup($name, $surname, $email, $address, $city, $pwd, $pwdRepeat) !== false) { // se non si inseriscono dati in tutti i campi del form
            header("location: ../signup.php?error=emptyinput");
            exit();
        }

        if(invalidEmail($email) !== false) { // email non valida
            header("location: ../signup.php?error=invalidemail");
            exit();
        }

        if(emailExists($conn, $email) !== false) { // email già usata da altri user
            header("location: ../signup.php?error=emailexists");
            exit();
        }

        if(invalidPassword($pwd) !== false) {
            header("location: ../signup.php?error=invalidpassword");
            exit();
        }

        if(passwordMatch($pwd, $pwdRepeat) !== false) { // le due password inserite non coincidono
            header("location: ../signup.php?error=passdoesntmatch");
            exit();
        }

        createUser($conn, $name, $surname, $email, $pwd, $address, $city); // permette la creazione del nuovo utente
    }

    else{
        header("location: ../signup.php");
        exit();
    }
 ?>