<?php
    include_once 'header.php';
?>

    <title> Accesso </title>

        <div class="container">
            
            <div class="spazio"></div>

            <div class="row">
      
            <div class="col-sm"></div>
      
                <div class="col-sm">

                    <div class="alert alert-dark" role="alert">

                        <form action="loginemp.inc.php" method="post">
        
                            <div class="mb-3">
        
                                <label for="exampleInputEmail1" class="form-label"> Nome utente </label>
                                
                                <input type="text" name="username" class="form-control" id="exampleInputEmail1"
                                    aria-describedby="emailHelp">
                            
                            </div>

                            <div class="mb-3">
                                
                                <label for="exampleInputPassword1" class="form-label" > Password
                                
                                    <?php
                                        if (isset($_GET["error"])) { // codice utilizzato per gestisce l'errore di inserimento di password errata
                                            
                                            if ($_GET["error"] == "incorrectpassword") {
                                                echo "<small style=\"color: red\"> *Password non corretta </small>";
                                            }
                                        }
                                    ?>

                                </label>
                                
								<script type="text/javascript" src="../includes/passShow.js"></script>
								
								<div class="input-group" id="show_hide_password">
								<input type="password" name="pwd" class="form-control" id="exampleInputPassword1">
								<div class="input-group-append">
									<span class="input-group-text"><a href=""><i class="bi bi-eye-slash" aria-hidden="true"></i></a></span>
								</div>
							</div>
                            
                            </div>

                            <button type="submit" name="submit" class="btn btn-dark"> Accedi </button>  
                            
                            
                            <?php  // codice utilizzato per gestisce l'errore di mancata compliazione di tutti i campi del form
                                
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

                </div>

                <div class="col-sm"></div>

            </div>

        </div>

        <br>

    </body>
</html>
