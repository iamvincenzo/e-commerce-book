<?php
    session_start(); // avvio della sessione --> cioè l'insieme di accessi dello stesso utente a pagine web diverse senza richiedere ogni volta le credenziali dell'utente.

    if(isset($_POST["addToCart"])) { // se è stato premuto il tasto aggiungi al carrello
        
        if(isset($_SESSION["shoppingCart"])) { // se è stata impostata la variabile di sessione: array che contiene gli elementi aggiunti al carrello (cioè si controlla che tale variabile sia stata istanziata)

            $bookArrayID = array_column($_SESSION["shoppingCart"], "bookID"); // ritorna i valori di una colonna dell'array di input (in questo caso i valori della colonna bookID dell'array $_SESSION["shoppingCart"])

            if(!in_array($_GET["IDLibro"], $bookArrayID)) { // se nella colonna $bookArrayID non è contenuto l'id passato con GET

                $count = count($_SESSION["shoppingCart"]); // conteggio del numero di elementi nell'array

                $bookArray = array(
                    'bookID'			=>	$_GET["IDLibro"],
                    'bookTitle'			=>	$_POST["hiddenTitle"],
                    'bookAuthor'		=>	$_POST["hiddenAuthor"],
                    'bookPublisher'		=>	$_POST["hiddenPublisher"],
                    'bookPrice'		    =>	$_POST["hiddenPrice"],
                    'bookQuantity'		=>	$_POST["userQuantity"]
                ); // creazione dell'item 

                $_SESSION["shoppingCart"][$count] = $bookArray; // inserimento dell'item nell'array che rappresenta il carrello
            }

            else { // script eseguito se il prodotto è stato già aggiunto nel carrello
                
                echo '<script> alert("Questo articolo è stato già aggiunto al carrello") </script>';
            }
        }

        else { // se l'array che rappresenta il carrello non è mai stato istanziato prima

            $bookArray = array(
                'bookID'			=>	$_GET["IDLibro"],
                'bookTitle'			=>	$_POST["hiddenTitle"],
                'bookAuthor'		=>	$_POST["hiddenAuthor"],
                'bookPublisher'		=>	$_POST["hiddenPublisher"],
                'bookPrice'		    =>	$_POST["hiddenPrice"],
                'bookQuantity'		=>	$_POST["userQuantity"]
            ); // creazione dell'item da aggiugnere al carrello

            $_SESSION["shoppingCart"][0] = $bookArray; // aggiunta del primo item al carrello
        }
    }

    require_once 'includes/dbh.inc.php';
    require_once 'includes/functions.inc.php';
    include_once 'header.php';
?>

        <title> Home </title>
        
            <div class="container">

                <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">

                    <ol class="carousel-indicators">

                        <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>

                        <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>

                    </ol>

                    <div class="carousel-inner">

                        <div class="carousel-item active">

                            <img src="img/libroCarousel1.jpg" class="d-block w-100" alt="Immagine di un libro">

                            <div class="carousel-caption d-none d-md-block">

                                <h5> I nostri prodotti sono i migliori </h5>

                                <p> Registrati e tieniti sempre aggiornato sulle novità pù interessanti </p>

                            </div>

                        </div>

                        <div class="carousel-item">

                            <img src="img/libroCarousel2.jpg" class="d-block w-100" alt="Immagine di un libro">

                            <div class="carousel-caption d-none d-md-block">

                                <h5> I nostri prodotti sono i migliori </h5>

                                <p> Bisogna sempre essereprudenti con i libri e con ciò che contengono, perché le parole hanno il potere di cambiarci </p>

                            </div>

                        </div>

                    </div>

                    <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">

                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>

                        <span class="sr-only"> Precedente </span>

                    </a>

                    <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">

                        <span class="carousel-control-next-icon" aria-hidden="true"></span>

                        <span class="sr-only"> Successiva </span>

                    </a>

                </div>

            </div>

            <br>

    <?php // codice PHP usato per la visualizzazione, nella home, dei prodotti disponibili. 
        
        $res = viewProducts($conn, "../index"); // richiama funzione per ottenere i prodotti da visualizzare nella home

        echo 
            "<div class=\"container\">
                <div class=\"row row-cols-1 row-cols-md-3 g-4 mb-3\">";

                    while($row = mysqli_fetch_assoc($res)) { // ciclo per scorrere i risultati della funzione precedente
                                
                        /**
                         * I prodotti e le relative caratteristiche vengono mostrati tramite
                         * elementi HTML: card.
                         * 
                         * Nelle card sono anche contenuti i bottoni per poter aggiungere al carrelo un determinato prodotto
                         * con la relativa quantità.
                         */

                        echo 
                        "<div class=\"col\" style=\"margin-bottom: 20px;\">
                            
                            <div class=\"card h-100\">
                                
                                <form method=\"post\" action=\"index.php?action=add&IDLibro=" . $row["IDLibro"] . "\">"; // quando si preme il tasto aggiungi al carrello, viene attivato il codie PHP che si occupa di aggiungere al carrello l'item passando l'ID del libro
                                    
                                    echo 
                                    "<img src=\"" . $row["ImmagineCopertina"] . "\" class=\"card-img-top\" alt=\"immagine temporanea\">
                    
                                    <div class=\"card-body\">
                                        
                                        <h2 class=\"card-title\">" . $row["Titolo"] . "</h2>
                                        <p class=\"card-text descrCard\">Autore: " . $row["Autore"] . "</p>
                                        <p class=\"card-text descrCard\">Editore: " . $row["Editore"] . "</p>
                                        <p class=\"card-text descrCard\">Formato: " . $row["NomeFormato"] . "</p>
                                        <p class=\"card-text descrCard\">Genere: " . $row["NomeGenere"] . "</p>
                                        <p class=\"card-text descrCard\">Anno: " . $row["AnnoPubblicazione"] . "</p>
                                        <p class=\"card-text\">Quantita: " . $row["Quantita"] . "</p>
                                        <p class=\"card-text\">Prezzo: €" . number_format($row["Prezzo"], 2) . "</p>
                                       

                                        <script src='./src/bootstrap-input-spinner.js'></script>

                                        <script>
                                            $(\"input[type='number']\").inputSpinner()
                                        </script>

                                        <input type=\"hidden\" name=\"hiddenTitle\" value=\"" . $row["Titolo"] . "\"/>
                                        <input type=\"hidden\" name=\"hiddenAuthor\" value=\"" . $row["Autore"] . "\"/>
                                        <input type=\"hidden\" name=\"hiddenPublisher\" value=\"" . $row["Editore"] . "\"/>
                                        <input type=\"hidden\" name=\"hiddenPrice\" value=\"" . $row["Prezzo"] . "\"/>
                                        <input type=\"hidden\" name=\"hiddenQuantity\" value=\"" . $row["Quantita"] . "\"/>";
                                                         

                                    if(isset($_SESSION["Email"])) { // se l'utente è loggato
                                        
                                        if($row["Quantita"] > 0) { // se il prodotto è disponibile allora lo si può aggiungere al carrello ma la massima quantità selezionabile è quella effettivamente disponibile

                                            echo 
                                            "<input placeholder=\"Inserisci quantità\" required min=\"1\" max=\"" . $row["Quantita"] . "\" type=\"number\" name=\"userQuantity\" style='width: 180px;'/>
                                            <br> <br>
                                            <input type=\"submit\" name=\"addToCart\" class=\"btn btn-primary\" value=\"Aggiungi al carrello\" />";
                                        }   
                                        
                                        else { // se il prodotto non è disponibile allora si disabilitano i tasti per poter aggiugnere il prodotto al carrello
                                            
                                            echo 
                                            "<input placeholder=\"Inserisci quantità\" required min=\"1\" max=\"" . $row["Quantita"] . "\" type=\"number\" name=\"userQuantity\" style='width: 180px;' disabled/>
                                            <br> <br>
                                            <input type=\"submit\" name=\"addToCart\"  class=\"btn btn-primary\" value=\"Aggiungi al carrello\" disabled/>";
                                        }
                                    }
                                    
                                    else { // se l'utente non è loggato
                                        
                                        if($row["Quantita"] > 0) { // se il prodotto è disponibile ma non si è loggati, per poter aggiugnere al carrello un prodotto occore effettuare l'accesso

                                            echo 
                                            "<input placeholder=\"Inserisci quantità\" required min=\"1\" max=\"" . $row["Quantita"] . "\" type=\"number\" name=\"userQuantity\" style='width: 180px;' onclick=\"window.location=\"login.php\"\"/>
                                            <br> <br>
                                            <a href=\"login.php\" class=\"btn btn-primary\" style=\"margin-left: 10px;\"> Aggiungi al carrello </a>";
                                        }

                                        else { // se il prodotto non è disponibile allora si disabilitano i tasti per poter aggiugnere il prodotto al carrello

                                            echo 
                                            "<input placeholder=\"Inserisci quantità\" required min=\"1\" max=\"" . $row["Quantita"] . "\" type=\"number\" name=\"userQuantity\" style='width: 180px;' disabled/>
                                            <br> <br>
                                            <a href=\"login.php\" class=\"disabled btn btn-primary\" style=\"margin-left: 10px;\"> Aggiungi al carrello </a>";  
                                        }
                                    }
                                    
                                    echo 
                                    "</div>
                                
                                </form>

                            </div>

                        </div>";                        
                    }
        
                echo 
                "</div>

            </div>";
    ?>

    <div class="testi container">

        <div class="row">

            <div class="col-sm" style="text-align: justify;  text-justify: inter-word;">

                <h2 class="titoloParagrafi"> Presentazione </h2>

                <p>
                    Lorvincfralma permette di effettuare ricerche all'interno del proprio database utilizzando diversi parametri, permettendo così all'utente di affinare la ricerca e di vedere visualizzati solamente quei titoli che soddisfano i criteri di ricerca da lui imposti. E' possibile effettuare ricerche sia su titoli in catalogo che fuori catalogo, permettendo così un servizio di ricerca bibliografica molto utile per tutti. 
                </p>

            </div>

            <div class="col-sm" style="text-align: justify;  text-justify: inter-word;">

                <h2 class="titoloParagrafi"> I nostri obiettivi </h2>

                <p>
                    Lorvincfralma nasce per fornire al mondo universitario ed a tutti gli internauti, una scelta vastissima di libri e riviste specializzate e vuole differenziarsi per l’accurato servizio di ricerca dei titoli più difficili da reperire sul mercato. Offre, a questo proposito, il servizio di ORDINE PERSONALIZZATO che prevede la ricerca sul mercato di quanto non è stato trovato all’interno del nostro catalogo.
                </p>

            </div>

            <div class="col-sm" style="text-align: justify;  text-justify: inter-word;">

                <h2 class="titoloParagrafi">  18app & Carta Docente </h2>

                <p>               
                    Lorvincfralma è partner ufficiale dell'iniziativa Carta del Docente e 18app Bonus Cultura. È possibile acquistare, utilizzando Bonus Cultura e 18app, solo prodotti venduti e spediti da IBS: libri e audiolibri, libri in inglese, eBook, eBook in inglese, CD musicali (solo per i nati nel 1999). Nel sito Libri puoi trovare tutte le novità, libri universitari e professionali, libri per ragazzi, saggi e manuali.
                </p>

            </div>
            
        </div>

    </div>

    <br> <br>

<?php
    include_once 'footer.php';
?>
    