<?php

    /**
     * 
     * Guarda descrizione index.php sezione visualizzazione prodotti nelle card.
     * 
     */

    session_start();

    if(isset($_POST["searchbtn"])) {

        $searchField = $_POST["searchfield"];
    
        require_once 'includes/dbh.inc.php';
        require_once 'includes/functions.inc.php';
        include_once 'header.php';

        echo '<title> Ricerca </title>';

        $res = searchBook($conn, $searchField); 

            echo 
            "<div class=\"container\" style=\"margin-top: 20px;\">
                
                <div class=\"row row-cols-1 row-cols-md-3 g-4 mb-3\">";

                    $rowcount = mysqli_num_rows($res); 

                    if($rowcount > 0) { // controllo che la ricerca abbia prodotto dei risultati
                                            
                        while($row = mysqli_fetch_assoc($res)) { 
                                                 
                            echo 
                            "<div class=\"col\" style=\"margin-bottom: 20px;\">
                                
                                <div class=\"card h-100\">
                                    
                                    <form method=\"post\" action=\"index.php?action=add&IDLibro=" . $row["IDLibro"] . "\">
                                     
                                        <img src=\"" . $row["ImmagineCopertina"] . "\" class=\"card-img-top\" alt=\"immagine temporanea\">
                        
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
                                                             
    
                                        if(isset($_SESSION["Email"])) {
                                            
                                            if($row["Quantita"] > 0) {
                                                echo "<input placeholder=\"Inserisci quantità\" required min=\"1\" max=\"" . $row["Quantita"] . "\" type=\"number\" name=\"userQuantity\" style='width: 180px;'/>
                                                <br> <br>
                                                <input type=\"submit\" name=\"addToCart\" class=\"btn btn-primary\" value=\"Aggiungi al carrello\" />";
                                            }   
                                            
                                            else {
                                                echo "<input placeholder=\"Inserisci quantità\" required min=\"1\" max=\"" . $row["Quantita"] . "\" type=\"number\" name=\"userQuantity\" style='width: 180px;' disabled/>
                                                <br> <br>
                                                <input type=\"submit\" name=\"addToCart\"  class=\"btn btn-primary\" value=\"Aggiungi al carrello\" disabled/>";
                                            }
                                        }
                                        
                                        else {
                                            
                                            if($row["Quantita"] > 0) {
                                                echo "<input placeholder=\"Inserisci quantità\" required min=\"1\" max=\"" . $row["Quantita"] . "\" type=\"number\" name=\"userQuantity\" style='width: 180px;' onclick=\"window.location=\"login.php\"\"/>
                                                <br> <br>
                                                <a href=\"login.php\" class=\"btn btn-primary\" style=\"margin-left: 10px;\"> Aggiungi al carrello </a>";
                                            }
    
                                            else {
                                                echo "<input placeholder=\"Inserisci quantità\" required min=\"1\" max=\"" . $row["Quantita"] . "\" type=\"number\" name=\"userQuantity\" style='width: 180px;' disabled/>
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
                    }
                    
                    else  { // se non sono stati trovati dei prodotti che soddisfano i criteri di ricerca
                        
                        echo 
                        '<div class="alert alert-warning" role="alert" style="height: 60px;">
                            Siamo spiacenti, nessun risultato. 
                        </div>
                        
                        <div class="row justify-content-md-center">
                            <img src="img/searchNotFound.gif" class="img-fluid" alt="Immagine di risultato di ricerca non trovato"/>
                        </div>';
            echo 
                "</div>
            </div>
                    
            <br><br>";
                                        
                    }

        include_once 'footer.php';
    }

    else {
        header("location: index.php");
        exit();
    }
?>