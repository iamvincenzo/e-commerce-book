<?php
    include_once 'header.php';
?>

    <title> Registrazione </title>

    <div class="formSignup container">
        
        <div class="row">
		
			<script type="text/javascript" src="includes/passShow.js"></script>
        
            <div class="col-sm"></div>
        
            <div class="col-sm">
        
                <form action="includes/signup.inc.php" method="post">
        
                    <div class="mb-3">
        
                        <label for="exampleInputEmail1" class="form-label"> Nome </label>
        
                        <input type="text" name='name' class="form-control" id="exampleInputEmail1"
                            aria-describedby="emailHelp">
        
                    </div>

                    <div class="mb-3">
        
                        <label for="exampleInputEmail1" class="form-label"> Cognome </label>
        
                        <input type="text" name='surname' class="form-control" id="exampleInputEmail1"
                            aria-describedby="emailHelp">
        
                    </div>

                    <div class="mb-3">
        
                        <label for="exampleInputEmail1" class="form-label"> Indirizzo e-mail 
                        
                        <?php // codice utilizzato per gestisce l'errore di inserimento di email errata/già registrata
                            if (isset($_GET["error"])) {
                                
                                if ($_GET["error"] === "invalidemail") {
                                    echo "<small style=\"color: red\"> *E-mal non valida </small>";
                                } 
                                
                                else if ($_GET["error"] === "emailexists") {
                                    echo "<small style=\"color: red\"> *E-mail già registrata </small>";
                                }
                            }
                        ?>
                        
                        </label>
        
                        <input type="email" name='email' class="form-control" id="exampleInputEmail1"
                            aria-describedby="emailHelp">
        
                    </div>

                    <div class="mb-3">
        
                        <label for="exampleInputEmail1" class="form-label" > Città </label>
        
                        <input type="text" name='city' class="form-control" id="exampleInputEmail1"
                            aria-describedby="emailHelp" >
                    </div>

                    <div class="mb-3">
        
                        <label for="exampleInputEmail1" class="form-label" > Indirizzo </label>
        
                        <input type="text" name='address' class="form-control" id="exampleInputEmail1"
                            aria-describedby="emailHelp" >
                    </div>

                    <div class="mb-3">
        
                        <label for="exampleInputPassword1" class="form-label"> Password 

                        <label data-toggle="tooltip"
                            title="Minimo 8 caratteri con almeno: una minuscola, una maiuscola e un numero.">
                            <h6> <i class="bi bi-question-diamond"> </i></h6>
                        </label> 
                        
                            <?php
                                if (isset($_GET["error"])) {

                                    if($_GET["error"] === "invalidpassword") {
                                        echo "<small style=\"color: red\"> *criteri minimi non rispettati </small>";
                                    }
                                }
                            ?>
                            
                        </label>

          						
						<div class="input-group" id="show_hide_password">
							<input type="password" name="pwd" class="form-control" id="exampleInputPassword1">
							<div class="input-group-append">
								<span class="input-group-text"><a href=""><i class="bi bi-eye-slash" aria-hidden="true"></i></a></span>
							</div>
						</div>
						
                    </div>

                    <div class="mb-3">
        
                        <label for="exampleInputPassword1" class="form-label"> Ripetere Password 
                        
                        <?php // codice utilizzato per gestisce l'errore di inserimento di password non coincidente
                            if (isset($_GET["error"])) {

                                if ($_GET["error"] === "passdoesntmatch") {
                                    echo "<small style=\"color: red\"> *Le password non coincidono </small>";
                                }
                            }
                        ?>

                        </label>
						
						<div class="input-group" id="show_hide_password1">
							<input type="password" name="pwdRepeat" class="form-control" id="exampleInputPassword1">
							<div class="input-group-append">
								<span class="input-group-text"><a href=""><i class="bi bi-eye-slash" aria-hidden="true"></i></a></span>
							</div>
						</div>
						
                            
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary"> Registrami </button>

                    <?php // codice utilizzato per gestisce l'errore di mancata compliazione di tutti i campi del form
                        if (isset($_GET["error"])) {

                            if ($_GET["error"] == "emptyinput") {
                                echo "<small style=\"color: red;\"> *Compila tutti i campi </small>";
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