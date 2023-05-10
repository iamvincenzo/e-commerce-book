<?php
    echo
    '<!doctype html>
    <html lang="it">

        <head>
		
			<link rel="shortcut icon" href="img/logo.png"/>

            <meta charset="utf-8">

            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

            <link rel="stylesheet" href="css/style.css">

            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
                integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

            <link rel="preconnect" href="https://fonts.gstatic.com">
			
			<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.0.min.js"></script>

            <link href="https://fonts.googleapis.com/css2?family=Yanone+Kaffeesatz:wght@300&display=swap" rel="stylesheet">

            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

            <style>
            
                body {
                    font-family: "Yanone Kaffeesatz", sans-serif;
                    font-size: 25px;
                }
            
            </style>

        </head>

        <body>

            <div class="strisciaColorata"></div>

            <nav class="navbar navbar-expand-lg navbar-dark bg-primary">

                <a class="navbar-brand" href="index.php"> LORVINCFRALMA </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarScroll"
                    aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">

                    <span class="navbar-toggler-icon"></span>

                </button>

                <div class="collapse navbar-collapse" id="navbarScroll">

                    <ul class="navbar-nav mr-auto my-2 my-lg-0 navbar-nav-scroll">

                        <li class="nav-item active">

                            <a class="nav-link" href="index.php"> Home <span class="sr-only">(current)</span></a>

                        </li>

                        <li class="nav-item dropdown">';
                            
                            if (isset($_SESSION["Email"])) { // se l'utente è loggato (variabile globale settata) allora stampa il suo nome
                                
                                echo 
                                "<a class=\"nav-link dropdown-toggle\" href=\"#\" id=\"navbarScrollingDropdown\" role=\"button\" data-toggle=\"dropdown\" aria-expanded=\"false\"> 
                                    Ciao " . $_SESSION["Nome"] . 
                                "</a>";
                            }
                            
                            else { // altirmenti se l'utente non è loggato stampa stringa generica
                                
                                echo 
                                "<a class=\"nav-link dropdown-toggle\" href=\"#\" id=\"navbarScrollingDropdown\" role=\"button\" data-toggle=\"dropdown\" aria-expanded=\"false\"> 
                                    Utente 
                                </a>";
                            }

                                echo '<ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">';
                                        
                                    if (isset($_SESSION["Email"])) { // se l'utente è loggato si personalizza il menu dropdown

                                        echo "<li> <a href=\"profile.php?viewGif=true\" class=\"dropdown-item\"> Profilo utente </a> </li>";
                                        echo '<li> <hr class="dropdown-divider"> </li>';
                                        echo "<li> <a href=\"includes/logout.inc.php\" class=\"dropdown-item\"> Esci </a> </li>";
                                    } 
                                    
                                    else { // altrimenti nel menu dropdown si inseriscono i link alle pagine di accesso/registrazione

                                        echo "<li> <a class=\"dropdown-item\" href=\"login.php\"> Accesso </a> </li>";
                                        echo "<li> <a class=\"dropdown-item\" href=\"signup.php\"> Registrazione </a> </li>";                                    
                                    }

                                echo '</ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="chisiamo.php" tabindex="-1" aria-disabled="true"> Chi siamo </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#contatti"> Contattaci </a>
                        </li>';

                        if (isset($_SESSION["Email"])) { // se l'utente è loggato può visualizzare il carrello degli acquisti
                            
                            echo
                            '<li class="nav-item">
                                <a class="nav-link bi bi-cart" href="cart.php"></a>
                            </li>';
                        } 
                    
                    echo
                    '</ul>

                    <form action="search.php" method="post" class="d-flex">

                        <input required name="searchfield" class="form-control mr-2" type="search" placeholder="Cerca"
                            aria-label="Search">

                        <button name="searchbtn" class="btn btn-outline-light" type="submit"> Cerca </button>

                    </form>

                </div>

            </nav>';