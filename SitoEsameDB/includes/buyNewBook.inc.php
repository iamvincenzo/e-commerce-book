<?php
    session_start(); // avvio della sessione

    if(isset($_SESSION["NomeUtente"])) { // se l'impiegato è loggato
        
        $fileName = basename($_FILES["file"]["name"]);
        $targetDir = "../img/";
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        require_once '../includes/dbh.inc.php';
        require_once '../includes/functions.inc.php';

        if(isset($_POST["submitBuyBtn"])) { // se premuto il tasto Inserisci

            $allowTypes = array('jpg','png','jpeg');

            if(in_array($fileType, $allowTypes)) {

                if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {

                    buyNewBook($conn, $_POST["title"], $_POST["author"], $_POST["publisher"], 
                        $_POST["year"], $_POST["description"], $fileName, $_POST["format"], 
                            $_POST["price"], $_POST["qnt"],$_POST["numPage"], $_POST["lang"], 
                                $_POST["genre"], $_SESSION["IDImpiegato"]); // viene invocata la funzione per l'inserimento di un nuovo prodotto all'interno del database di sistema

                    header("location: ../employee/dashboard.php?viewForm=true&&error=none"); // se l''acquisto è andato a buon fine
                
                }
            }

            else {
                header("location: ../employee/dashboard.php?viewForm=true&&error=notallowformat");
            }
        }
    
        else {
            
            header("location: ../employee/dashboard.php");
            exit();
        }
    }
?>