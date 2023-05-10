<?php

echo
    '<!doctype html>
    <html lang="it">

        <head>

	        <link rel="shortcut icon" href="../img/logo.png" />

            <meta charset="utf-8">

            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

            <link rel="stylesheet" href="../css/style.css">

            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
                integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

            <link rel="preconnect" href="https://fonts.gstatic.com">
			
			<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.0.min.js"></script>

            <link href="https://fonts.googleapis.com/css2?family=Yanone+Kaffeesatz:wght@300&display=swap" rel="stylesheet">

            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

            <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>

            <style>
            
                body {
                    font-family: "Yanone Kaffeesatz", sans-serif;
                    font-size: 25px;
                }
            
            </style>

        </head>

        <body style="background-color: #e9e9e9;">

            <nav class="navbar-expand-lg navbar navbar-dark bg-dark">

                <a class="navbar-brand" href="index.php"> LORVINCFRALMA </a>';

                if(isset($_SESSION["NomeUtente"])) { // se l'impiegato è loggato può effettuare il logout

                    echo "<a href=\"logoutemp.inc.php\" class=\"navbar-brand\"> Esci </a>";
                }

            echo
            '</nav>';

            