<?php
    include_once 'header.php';
?>

    <title> Accesso </title>

        <div class="container formLogin">
      
            <div class="row">
      
                <div class="col-sm"></div>
      
                <div class="col-sm">
      
                    <form action="includes/login.inc.php" method="post">
      
                        <div class="mb-3">
      
                            <label for="exampleInputEmail1" class="form-label"> Indirizzo e-mail </label>
                            
                            <input type="email" name="email" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp">
                        
                        </div>

                        <div class="mb-3">
                            
                            <label for="exampleInputPassword1" class="form-label" > Password
                               
                                <?php // codice utilizzato per gestisce l'errore di inserimento di password errata
                                    if (isset($_GET["error"])) {
                                        
                                        if ($_GET["error"] == "incorrectpassword") {
                                            echo "<small style=\"color: red\"> *Password non corretta </small>";
                                        }
                                    }
                                ?>

                            </label>

							<!-- codice utilizzato per gestire la visulizzazione o meno della password -->
							<script type="text/javascript" src="includes/passShow.js"></script>
							
                            <div class="input-group" id="show_hide_password">
								<input type="password" name="pwd" class="form-control" id="exampleInputPassword1">
								<div class="input-group-append">
									<span class="input-group-text"><a href=""><i class="bi bi-eye-slash" aria-hidden="true"></i></a></span>
								</div>
							</div>
							
                        </div>

                        <button type="submit" name="submit" class="btn btn-primary"> Accedi </button>  
                        <a href="signup.php" class="btn btn-primary" style="margin-left: 10px;"> Registrati </a>
                        
                        <?php // codice utilizzato per gestisce l'errore di mancata compliazione di tutti i campi del form
                            if (isset($_GET["error"])) {

                                if ($_GET["error"] == "emptyinput") {
                                    echo " <small style=\"color: red;\"> *Compila tutti i campi </small>";
                                } 
                                
                                // codice utilizzato per gestisce l'errore di un utente non presente nel database di sistema
                                else if ($_GET["error"] == "wronglogin") {
                                    echo " <small style=\"color: red;\"> *Utente non esiste </small>";
                                }
                            }

                        ?>

                        
                    </form>
                </div>
                <div class="col-sm"></div>
            </div>
        </div>

        <br>

<?php
    include_once 'footer.php';
?>