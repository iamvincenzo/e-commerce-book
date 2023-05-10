<?php
    session_start(); // avvio della sessione --> cioè l'insieme di accessi dello stesso utente a pagine web diverse senza richiedere ogni volta le credenziali dell'utente.

    if(isset($_SESSION["Email"])) { // se l'utente è loggato

        include_once 'header.php';

        if(isset($_GET["action"])) { 

            if($_GET["action"] == "delete")	{ // se l'utente clicca l'icona elimina
                
                foreach($_SESSION["shoppingCart"] as $keys => $values) { // ciclo che scorre gli item nel carrello
    
                    if($values["bookID"] == $_GET["IDLibro"]) { // se il valore trovato coicide
                        
                        unset($_SESSION["shoppingCart"][$keys]); // distrugge la variabile all'interno di "shoppinfCart" che ha il $keys corrispondente (rimuove elemento dal carrello)
                    }
                }
            }
        }
?>
		
        <title> Carrello </title>
		
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>


		<br>

		<div class="container">
					
        <div class="alert alert-secondary" role="alert">
            <h2></h2>
            <h2> Riepilogo ordine </h2>
        </div>
			
            <br>
			<div class="table-responsive">
				
                <table class="table">

                    <thead class="table-dark">
                        <tr>
                            <th scope="col"> Titolo </th>
                            <th scope="col"> Autore </th>
                            <th scope="col"> Editore </th>
                            <th scope="col"> Quantità </th>
                            <th scope="col"> Prezzo unitario </th>
                            <th scope="col"> Totale </th>
                            <th width="col"> Azione </th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        
                            if(!empty($_SESSION["shoppingCart"])) { // se il carrello non è vuoto
                                
                                $total = 0;

                                foreach($_SESSION["shoppingCart"] as $keys => $values) { // ciclo che scorre gli item nel carrello
                        ?>

                                    <tr>
                                        <td><?php echo $values["bookTitle"]; ?></td>
                                        <td><?php echo $values["bookAuthor"]; ?></td>
                                        <td><?php echo $values["bookPublisher"]; ?></td>
                                        <td><?php echo $values["bookQuantity"]; ?></td>
                                        <td>€ <?php echo number_format($values["bookPrice"], 2); ?></td>
                                        <td>€ <?php echo number_format($values["bookQuantity"] * $values["bookPrice"], 2);?></td>
                                        
                                        <td>
                                            <a href="cart.php?action=delete&IDLibro=<?php echo $values["bookID"]; ?>"> <!-- Quando l'utente clicca sull'icona del cestino, invoca il codice php in cima al documento per rimuovere un item dal carrello -->
                                                <span class="text-danger"> 
                                                    <i class="bi bi-trash"></i>
                                                </span>
                                            </a>
                                        </td>

                                    </tr>
                            
                            <?php
                                    $total = $total + ($values["bookQuantity"] * $values["bookPrice"]); // calcolo del costo totale di tutti i prodotti presenti nel carrello
                                }
                            ?>

                            <tr>

                                <td colspan="4" align="right"> Totale </td>
                                <td align="right">€ <?php echo number_format($total, 2); ?></td>
                                
                                
                                <td align="right"> <a class="bi bi-cart-check-fill btn btn-primary" href="checkout.php"> Check Out</a> </td> <!-- Quando l'utente clicca sul tasto checkout, si viene rediretti nella pagina di checkout -->
                                
                            </tr>

                        <?php
                        
                            }
                        ?>

                    </tbody>
                    
				</table>

			</div>

		</div>
        
	</div>

	<br> <br>

<?php
        include_once 'footer.php';
    }
?>
