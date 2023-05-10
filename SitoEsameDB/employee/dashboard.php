<?php
    session_start(); // avvio della sessione --> cioè l'insieme di accessi dello stesso utente a pagine web diverse senza richiedere ogni volta le credenziali dell'utente.

    if(isset($_SESSION["NomeUtente"])) { // se l'impiegato è loggato

        require_once '../includes/dbh.inc.php';
        require_once '../includes/functions.inc.php';
        include_once 'header.php';

        if(isset($_POST["addQuantity"])) { // se si clicca il bottone Aggiungi

            if(isset($_GET["action"])) { 

                if($_GET["action"] == "add") { // se l'azione è aggiungi
                    
                    updateQuantity($conn, $_GET["IDLibro"], $_SESSION["IDImpiegato"], $_POST["userQuantity"]); // viene richiamata la funzione che aggiorna la quantità disponibile di un prodotto
                }
            }
        }

        if(isset($_POST["sendOrder"])) { // se viene premuto il bottone Spedisci

            if(isset($_GET["action"])) {

                if($_GET["action"] == "send") { // se l'azione è send
                    
                    shipOrder($conn, $_GET["IDOrdine"], $_SESSION["IDImpiegato"]); // viene invocata la funzione che spedisce l'ordine
                }
            }
        }

        
?>

    <title> Dashboard </title>

    <div class="container-fluid">

            <div class="row">

                <div class="col-sm-2 sideBar">

                    <ul class="nav flex-column" style="margin-top: 10px;">

                        <li class="nav-item">
                            <a class="nav-link sideBarText" href="dashboard.php?viewStock=true">
                                Aggiungi nuove copie
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link sideBarText" href="dashboard.php?viewForm=true"> Inserisci un nuovo libro </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link sideBarText" href="dashboard.php?viewOrderToShip=true"> Spedisci </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link sideBarText" href="dashboard.php?viewStats=true"> Statistiche vendite </a>
                        </li>
                    </ul>

                </div>

                <div class="col-sm-10">
                    
                    <?php
                        if (isset($_GET['viewStock'])) { // se viene premuto il link acquista nuove copie 

                            $res =  viewProducts($conn, "../employee/dashboard");

                            echo
                            "<div class=\"table-responsive\">
                                
                                <table class=\"table\" style=\"margin-top: 20px;\"> 
                                    
                                    <thead class=\"table-dark\">
                                        
                                        <tr>
                                            <th scope=\"col\"> #Codice </th>
                                            <th scope=\"col\"> Titolo </th>
                                            <th scope=\"col\"> Autore </th>
                                            <th scope=\"col\"> Editore </th>
                                            <th scope=\"col\"> Anno </th>
                                            <th scope=\"col\"> Formato </th>
                                            <th scope=\"col\"> Lingua </th>
                                            <th scope=\"col\"> Quantità </th>
                                            <th scope=\"col\"> Azione </th>
                                        </tr>
                                    
                                    </thead>
                                    
                                    <tbody>";

                                    while($row = mysqli_fetch_assoc($res)) { 

                                        echo
                                        "<form method=\"post\" action=\"dashboard.php?action=add&IDLibro=" . $row["IDLibro"] . "\">
                                            
                                            <tr>
                                                <th scope=\"row\">" . $row["IDLibro"] . "</th>
                                                <td>" . $row["Titolo"] . "</td>
                                                <td>" . $row["Autore"] . "</td>
                                                <td>" . $row["Editore"] . "</td>
                                                <td>" . $row["AnnoPubblicazione"] . "</td>
                                                <td>" . $row["NomeFormato"] . "</td>
                                                <td>" . $row["NomeLingua"] . "</td>
                                                <td>" . $row["Quantita"] . "</td>
                                                <td> 
                                                    <input placeholder=\"Inserisci quantità\" required min=\"1\" type=\"number\" name=\"userQuantity\" style='width: 180px;'/>
                                                    
                                                    <input style=\"margin-top: 6px;\" type=\"submit\" name=\"addQuantity\" class=\"btn btn-primary\" value=\"Aggiungi\" /> 
                                                </td>
                                            </tr>
                                            
                                        </form>";

                                    }
                                
                                    echo 
                                    "</tbody>
                                </table>
                            </div>";
                        }
                    ?>

                    <?php
                       
                        if (isset($_GET['viewForm'])) { // se viene premuto il link acquista un nuovo libro
                            
                     ?>

                            <div class="container rounded mt-5">
                                        
                                <div class="row justify-content-md-center">
                                                                   
                                    <div class="col-md-9">

                                        <div class="p-3 py-5">

                                            <form action="../includes/buyNewBook.inc.php" method="post" enctype="multipart/form-data">

                                                <div class="d-flex justify-content-between align-items-center mb-3">

                                                    <h2 class="text-right"> 
                                                        Inserisci un nuovo libro 
                                                        <img src="../img/book-loader.gif" alt="caricamento libro" width="50">
                                                    </h2>                                                  

                                                    <?php
                                                        if (isset($_GET["error"])) {
                                                            
                                                            if ($_GET["error"] === "none") { // se acquisto andato a buon fine
                                                                echo "<small style=\"color: green\"> Inserimento andato a buon fine </small>";
                                                            } 
                                                            
                                                            else if ($_GET["error"] === "duplicateentry") { // se acquisto non andato a buon fine
                                                                echo "<small style=\"color: red\"> *Inserimento fallito: libro già esistente </small>";
                                                            }
                                                            
                                                            else if($_GET["error"] === "notallowformat") {
                                                                echo "<small style=\"color: red\"> *Formato immagine non supportato </small>";
                                                            }
                                                        }
                                                    ?>

                                                </div>

                                                <div class="row mt-2">

                                                    <div class="col-md-6">

                                                        <input name="title" type="text" class="form-control" placeholder="Titolo" required>
                                                    
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        
                                                        <input name="author" type="text" class="form-control" placeholder="Autore" required>
                                                
                                                    </div>

                                                </div>

                                                <div class="row mt-3">
                                                
                                                    <div class="col-md-6">
                                                    
                                                        <input name="publisher" type="text" class="form-control" placeholder="Editore" required>
                                                    
                                                    </div>

                                                    <div class="col-md-6"> 
                                                    
                                                        <input name="year" type="number" min=1980 max=2021 class="form-control"  placeholder="Anno pubblicazione">
                                                    
                                                    </div>
                                                
                                                </div>
                                                
                                                <div class="row mt-3">
                                                    
                                                    <div class="col-md-6">
                                                    
                                                        <input name="description" type="textarea" class="form-control" placeholder="Descrizione" required>
                                                    
                                                    </div>

                                                    
                                                    <div class="col-md-6">
                                                                                                  
                                                        <label class="custom-file-upload">
                                                            
                                                            <input name="file" type="file" required/>
                                                        
                                                        </label>

                                                    </div>
                                                
                                                </div>

                                                <div class="row mt-3">
                                                                                                    
                                                    <div class="col-md-6">
                                                        
                                                        <select name="format" class="custom-select" id="inputGroupSelect04" aria-label="Example select with button addon">
                                                            
                                                            <option selected> Scegli il formato del libro </option>
                                                            
                                                            <?php
                                                            
                                                                $res =  viewFormat($conn);
                                                                
                                                                while($row = mysqli_fetch_assoc($res)) { 
                                                                
                                                                    echo'<option value="' . $row["IDFormato"] . '">' . $row["NomeFormato"] . '</option>';
                                                                }
                                                            
                                                            ?>
                                                              
                                                        </select>
                                                    
                                                    </div>

                                                    <div class="col-md-6">
                                                        
                                                        <input name="price" min=1 type="number" step="0.01" class="form-control" placeholder="Prezzo" required>

                                                    </div>
                                                
                                                </div>

                                                <div class="row mt-3">
                                                
                                                    <div class="col-md-6">

                                                        <select name="lang" class="custom-select" id="inputGroupSelect04" aria-label="Example select with button addon">
                                                                                                    
                                                            <option selected> Scegli la lingua del libro </option>
                                                            
                                                            <?php
                                                            
                                                                $res =  viewLanguage($conn);
                                                                
                                                                while($row = mysqli_fetch_assoc($res)) { 
                                                                
                                                                    echo'<option value="' . $row["IDLingua"] . '">' . $row["NomeLingua"] . '</option>';
                                                                }
                                                            
                                                            ?>
                                                            
                                                        </select>

                                                    </div>

                                                    <div class="col-md-6">
                                                
                                                        <input name="qnt" min=1 type="number" class="form-control" placeholder="Quantità" required>
                                                
                                                    </div>
                                                
                                                </div>

                                                <div class="row mt-3">
                                                
                                                    <div class="col-md-6">

                                                        <select name="genre" class="custom-select" id="inputGroupSelect04" aria-label="Example select with button addon">
                                                
                                                            <option selected> Scegli il genere del libro </option>
                                                            
                                                            <?php
                                                            
                                                                $res =  viewGenre($conn);
                                                                
                                                                while($row = mysqli_fetch_assoc($res)) { 
                                                                
                                                                    echo'<option value="' . $row["IDGenere"] . '">' . $row["NomeGenere"] . '</option>';
                                                                }
                                                            
                                                            ?>
                                                            
                                                        </select>
                                                        
                                                    </div>

                                                    <div class="col-md-6">
                                                        
                                                        <input name="numPage" min=1 type="number" class="form-control" placeholder="Numero pagine" required>

                                                    </div>
                                               
                                                </div>

                                                <div class="mt-5 text-right">                                                    
                                               
                                                    <button name="submitBuyBtn" type="submit" class="btn btn-primary profile-button" > Inserisci </button>
                                               
                                                </div>

                                            </form>

                                        </div>

                                    </div>
                               
                                </div>
                                                                
                            </div>
                     <?php       
                        
                        }
                    ?>

                    <?php
                       
                        if (isset($_GET['viewOrderToShip'])) { // se viene premuto il link spedisci 

                            $res =  viewOrdersToShip($conn);

                            echo
                            "<div class=\"table-responsive\">
                                
                                <table class=\"table\" style=\"margin-top: 20px;\"> 
                                    
                                    <thead class=\"table-dark\">
                                        
                                        <tr>
                                            <th scope=\"col\"> #Codice </th>
                                            <th scope=\"col\"> Titolo </th>
                                            <th scope=\"col\"> Autore </th>
                                            <th scope=\"col\"> Editore </th>
                                            <th scope=\"col\"> Anno </th>
                                            <th scope=\"col\"> Formato </th>
                                            <th scope=\"col\"> Lingua </th>
                                            <th scope=\"col\"> Quantità </th>
                                            <th scope=\"col\"> Azione </th>
                                        </tr>
                                    
                                    </thead>
                                    
                                    <tbody>";

                                    while($row = mysqli_fetch_assoc($res)) { 

                                        echo
                                        "<form method=\"post\" action=\"dashboard.php?action=send&IDOrdine=" . $row["IDOrdine"] . "\">
                                            
                                            <tr>
                                                <th scope=\"row\">" . $row["IDOrdine"] . "</th>
                                                <td>" . $row["Titolo"] . "</td>
                                                <td>" . $row["Autore"] . "</td>
                                                <td>" . $row["Editore"] . "</td>
                                                <td>" . $row["AnnoPubblicazione"] . "</td>
                                                <td>" . $row["NomeFormato"] . "</td>
                                                <td>" . $row["NomeLingua"] . "</td>
                                                <td>" . $row["Quantita"] . "</td>
                                                <td>                                              
                                                    <input style=\"margin-top: 6px;\" type=\"submit\" name=\"sendOrder\" class=\"btn btn-primary\" value=\"Spedisci\" /> 
                                                </td>
                                            </tr>
                                            
                                        </form>";

                                    }
                                
                                    echo 
                                    "</tbody>
                                </table>
                            </div>";
                        }
                    ?>

                    <?php
                        
                        if(isset($_GET["viewStats"])) { // se viene premuto il link Statistiche vendite

                            $res = viewStats($conn); // vengono ritornati i risultati delle statistiche per ogni prdotto sulle vendite totali

                            $i = 0;

                            while($row = mysqli_fetch_assoc($res)) { // inserimento delle statistiche in un array
                       
                                $statsArray = array(
                                    'IDL'		        =>	$row["IDL"],
                                    'Title'             =>  $row["Titolo"],
                                    'Percent'		    =>	$row["Percentuale"]
                                );
                
                                $_SESSION["stats"][$i] = $statsArray;

                                $i = $i + 1; // avanzamento dell'indice
                            }
                    ?>                           
                    
                        <div class="container" style="width: 100%; height: 80vh;">
                            <canvas id="myCanvas"></canvas>
                        </div>

                        <script type="text/javascript">

                            let myCanvas = document.getElementById("myCanvas").getContext('2d');

                            let myLabels = [];

                            let myData = [];
                    
                            var jArray = <?php echo json_encode($_SESSION["stats"]); ?>; // conversione dell'array php in JSON
                    
                            for(var i = 0; i < jArray.length; i++) { // inserimento dei dati dell'oggetto JavaScript all'interno di array JavaScript
                    
                                myLabels.push("ID: " + jArray[i].IDL + ", Titolo: " + jArray[i].Title);
                                myData.push(jArray[i].Percent);
                            }

                            let chart = new Chart(myCanvas, { // grafico e relativi dettagli
                                
                                type: 'bar', // line, pie (torta), horizontalBar, bar

                                data: {
                                    
                                    labels: myLabels,

                                    datasets: [{

                                        label: "Percentuale vendite libri",
                                        data: myData,
                                        backgroundColor: '#' + Math.floor(Math.random()*16777215).toString(16),
                                    }]
                                },
                                
                                options: {
                                    
                                    responsive: true,

                                    maintainAspectRatio: false,

                                    title: {

                                        display: true,
                                        text: 'Statistiche sulle vendite dei libri',
                                        fontSize: 25,
                                    },

                                    tooltips: {
                                        enabled: true
                                    },

                                    layout: {

                                        padding: { top: 20 }
                                    },

                                    scales: {
                                        yAxes: [{
                                            ticks: {
                                                suggestedMin: 0,
                                                suggestedMax: 100
                                            }
                                        }],
                                        xAxes: [{
                                            display: false //this will remove all the x-axis grid lines
                                        }]
                                    }
                                },
                            });
                
                        </script>

                        

                    <?php
                            echo'
                            <div class="row justify-content-md-center">
                                <h5> 
                                    libri 
                                </h5> 
                            </div>';

                        }
                    ?>
                          
                </div>

            </div>

        </div>
 
    </body>

</html>

<?php
    }
?>