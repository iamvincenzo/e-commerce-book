
<?php
    session_start(); // la sessione deve essere avviata per poterla distruggere
    session_unset(); // dealloca(libera) tutte le variabili di sessione (variabili globali $_SESSION["..."])
    session_destroy(); // distrugge la sessione

    header("location: index.php"); // reindirizzamento
    exit();
 ?>