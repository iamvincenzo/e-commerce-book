<?php
    session_start(); // avvio della sessione

    if(isset($_SESSION["Email"])) { // se l'utente è loggato

        if(isset($_SESSION["shoppingCart"])) { // se il carrello è stato istanziato

            if(isset($_POST["makeOrderBtn"])) { // se viene premuto il bottone Conferma l'ordine
        
                require_once 'dbh.inc.php';
                require_once 'functions.inc.php';

                echo 
                '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
                integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

                 <link rel="preconnect" href="https://fonts.gstatic.com">

                <link href="https://fonts.googleapis.com/css2?family=Yanone+Kaffeesatz:wght@300&display=swap" rel="stylesheet">';
                
            if(!empty($_SESSION["shoppingCart"])) { // se il carrello non è vuoto   
                    
                    $i = 0;

                    foreach($_SESSION["shoppingCart"] as $keys => $values) { // ciclo che scorre gli elementi contenuti nel carrello
   
                        $res = makeOrder($conn,  $values["bookID"], $_SESSION["IDCliente"], 
                            $values["bookQuantity"], $values["bookQuantity"] * $values["bookPrice"]); // si richiama la funzione per ordinare un prodotto (ciclicamente)

                        $rowcount = mysqli_num_rows($res); // numero di righe del risultato

                        if($rowcount > 0) { // se il numero di righe è positivo

                            while($row = mysqli_fetch_assoc($res)) { // ciclo che inserisce all'interno di un array il prodotto von il relativo esito dell'acquisto
                       
                                $orderResult = array(
                                    'bookTitle'			=>	$values["bookTitle"],
                                    'bookAuthor'		=>	$values["bookAuthor"],
                                    'bookPublisher'		=>	$values["bookPublisher"],
                                    'result'            =>  $row["@msg"]
                                );
                
                                $_SESSION["orderResult"][$i] = $orderResult;
                            }
                        }

                        $i = $i + 1; // avanzamento dell'indice dell'array
                        
                        unset($_SESSION["shoppingCart"][$keys]); // rimuove l'elemento appena acquistato dal carrello
                    }            

                    // vengono mostrati gli esisti degli acquisti
                    
                    echo 
                    '<div class="alert alert-primary" role="alert">
                        <h1> Esito ordine </h1>
                    </div>';

                    echo '<div class="alert alert-info" role="alert">';

                    foreach($_SESSION["orderResult"] as $keys => $values) {
                        
                        echo '<p> Il tuo ordine che comprende il prodotto: ' . $values["bookTitle"] . ' ' . $values["bookAuthor"] . ' ' . $values["bookPublisher"] . ' ha avuto esito: ' . $values["result"] . '</p>';
                        
                        unset($_SESSION["orderResult"][$keys]);
                    }

                    echo '<p> <a href="../index.php"> Torna alla home </a> </p> </div>';
                }
            }
        }
    }

    else {

        header("location: ../index.php");
        exit();
    }
 ?>