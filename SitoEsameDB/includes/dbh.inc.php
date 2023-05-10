<?php
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "bookstorelorvincfralma";

    $conn = mysqli_connect($host, $dbUsername, $dbPassword, $dbName) or die 
        ("Impossibile connettersi al server: " . mysqli_connect_error());
 ?>