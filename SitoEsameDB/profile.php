<?php
    session_start(); // avvio della sessione

    if(isset($_SESSION["Email"])) { // se l'utente si è loggato può visualizzare/eseguire le azioni permesse dopo l'autenticazione

        require_once 'includes/dbh.inc.php';
        require_once 'includes/functions.inc.php';
        include_once 'header.php';
?>

<title> Pagina profilo </title>

<div class="container-fluid">

    <div class="row">

        <div class="col-sm-2 sideBar">
           
            <ul class="nav flex-column" style="margin-top: 10px;">

                <li class="nav-item">
                    <a class="nav-link sideBarText" href="profile.php?viewOrders=true">
                        Visualizza storico ordini
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link sideBarText" href="profile.php?viewDetails=true"> 
                        Visualizza dettagli account
                    </a>
                </li>

            </ul>

        </div>

        <div class="col-sm-10">

            <?php
            
               //if(isset($_SESSION["Email"])) { // se l'utente si è loggato

                if (isset($_GET['viewGif'])) { // codice usato per visualizzare una gif quando l'utente accede alla pagina profilo
                
                    echo
                    '<div class="row justify-content-md-center">
                        <img src="img/animation.gif" class="img-fluid" alt="this slowpoke moves"/>
                    </div>';
                }
                //}
            ?>

            <?php
                
                //if(isset($_SESSION["Email"])) { 

                if (isset($_GET['viewDetails'])) { // se l'utente clicca il link visualizzare i dettagli del proprio account
                
                    $ID = $_SESSION["IDCliente"]; // memorizzo il valore dell'id del cliente all'interno del database

                    $res =  viewDetails($conn, $ID); // richiamo la funzione utilizzata per visualizzare i dettagli del proprio account

                    while($row = mysqli_fetch_assoc($res)) { // ciclo che scorre i risultati della chiamata della funzione precedente
                                    
            ?>       
                        <div class="container rounded bg-white mt-5">
                            
                            <div class="row">
                                
                                <div class="col-md-3 border-right">
                                
                                    <div class="d-flex flex-column align-items-center p-3 py-5">
                                        
                                        <img class="rounded-circle mt-5" src="https://picsum.photos/200" width="90">
                                        
                                        <span class="font-weight-bold"> 
                                            <?php echo $row["Nome"] ." ". $row["Cognome"] ?> 
                                        </span>
                                        
                                        <span class="text-black-50"> 
                                            <?php echo $row["Email"] ?>
                                        </span>

                                    </div>
                                
                                </div>
                                
                                <div class="col-md-9">

                                    <div class="p-3 py-5">

                                        <form action="includes/editdetails.inc.php" method="post"> <!-- Quando l'utente clicca sul tasto salva, invoca la funzione per modificare i dettagli del suo account salvati nel database di sistema -->

                                            <div class="d-flex justify-content-between align-items-center mb-3">

                                                <h2 class="text-right"> 
                                                    Modifica profilo 
                                                    <img src="img/penEraser.gif" alt="penna e gomma" width="80">
                                                </h2>

                                                    <?php // codice PHP utilizzato per gestire gli errori di aggiornamento 
                                                        if (isset($_GET["error"])) {
                                                            
                                                            if ($_GET["error"] == "invalidemail") { // la nuova mail inserita per aggiornare la precedente non è valida
                                                                echo "<small style=\"color: red\"> *E-mal non valida </small>";
                                                            } 
                                                            
                                                            else if ($_GET["error"] == "emailexists") { // la nuova mail inserita per aggiornare la precedente è già stata registrata da un altro utente
                                                                echo "<small style=\"color: red\"> *E-mail già registrata </small>";
                                                            }

                                                            else if ($_GET["error"] == "passdoesntmatch") { // le password non coincidono
                                                                echo "<small style=\"color: red\"> *Le password non coincidono </small>";
                                                            }   
                                                            
                                                            else if ($_GET["error"] === "invalidpassword") {
                                                                echo "<small style=\"color: red\"> *criteri minimi non rispettati </small>";
                                                            }
                                                        }
                                                    ?>
                                            </div>

                                            <!-- Nei value degli input, si mostrano i risultati ottenuti dalla richiesta fatta al database -->

                                            <div class="row mt-2"> 

                                                <div class="col-md-6">

                                                    <input name="name" type="text" class="form-control" placeholder="Nome" value="<?php echo $row["Nome"] ?>" required>
                                                
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    
                                                    <input name="surname" type="text" class="form-control" placeholder="Cognome" value="<?php echo $row["Cognome"] ?>" required>
                                            
                                                </div>

                                            </div>

                                            <div class="row mt-3">
                                            
                                                <div class="col-md-6">
                                                    <input name="email" type="text" class="form-control" placeholder="Email" value="<?php echo $row["Email"] ?>" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <input name="tel" type="tel" class="form-control" pattern="[0-9]{10}" placeholder="Numero di telefono" value="<?php echo $row["Cellulare"] ?>">
                                                </div>
                                            
                                            </div>
                                            
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <input name="address" type="text" class="form-control" placeholder="Indirizzo" value="<?php echo $row["Indirizzo"] ?>" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <input name="city" type="text" class="form-control" placeholder="Città" value="<?php echo $row["Citta"] ?>" required>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                            
                                                <div class="col-md-6">
                                                    <input name="pwd" type="password" class="form-control" placeholder="Password" title="Minimo 8 caratteri con almeno: una minuscola, una maiuscola e un numero.">
                                                </div>
                                                 
                                                <div class="col-md-6">
                                                    <input name="pwdRepeat" type="password" class="form-control" placeholder="Conferma Password">
                                                </div>
                                            </div>

                                            <div class="mt-5 text-right">
                                                <button name="saveDetailsBtn" type="submit" class="btn btn-primary profile-button" > Salva </button>
                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>
                            
                        </div>

            <?php 
                    
                    } 
                }
            //}

            ?>

            <?php
                
                //if(isset($_SESSION["Email"])) {

                if (isset($_GET['viewOrders'])) { // se si preme il link visualizza ordini

                    $ID = $_SESSION["IDCliente"]; // si memorizza il valore dell'id del cliente memorizzato all'interno del database

                    $res =  viewOrders($conn, $ID); // richiamo la funzione utilizzata per visualizzare gli ordini del cliente

                    /**
                     * Gli ordini e le relative caratteristiche vengono mostrati tramite
                     * elementi HTML: tabella.
                     */

                    echo 
                    "<div class=\"table-responsive\">
                        <table class=\"table\" style=\"margin-top: 20px;\"> 
                            <thead class=\"table-dark\">
                                <tr>
                                    <th scope=\"col\"> #Numero ordine </th>
                                    <th scope=\"col\"> Titolo </th>
                                    <th scope=\"col\"> Autore </th>
                                    <th scope=\"col\"> Editore </th>
                                    <th scope=\"col\"> Data ordine </th>
                                    <th scope=\"col\"> Stato </th>
                                </tr>
                            </thead>
                            
                            <tbody>";

                            while($row = mysqli_fetch_assoc($res)) { 

                                echo 
                                "<tr>
                                    <th scope=\"row\">" . $row["IDOrdine"] . "</th>
                                    <td>" . $row["Titolo"] . "</td>
                                    <td>" . $row["Autore"] . "</td>
                                    <td>" . $row["Editore"] . "</td>
                                    <td>" . $row["DataOrdine"] . "</td>";
                                    
                                    if($row["Spedito"] === 0) echo "<td> Non spedito </td>";
                                    else echo "<td> Spedito </td>"; 

                                echo 
                                "</tr>";
                            }
                        
                            echo 
                            "</tbody>
                        </table>
                    </div>";
                }
                //}
            ?>

        </div>

    </div>

</div>

<?php
        include_once 'footer.php';
    }

    else { // se l'utente non è loggato

        header("location: index.php");
        exit();
    }
?>