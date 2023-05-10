<?php
    session_start(); // avvio della sessione

    if(isset($_SESSION["Email"])) { // se l'utente è logggato

        include_once 'header.php';
?>

<title> Check-out </title>

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">

<div class="container">

    <div class="row mb-4"> </div>

    <div class="row">

        <div class="col-lg-6 mx-auto">

            <div class="card ">

                <div class="card-header">

                    <div class="bg-white shadow-sm pt-4 pl-2 pr-2 pb-2">
                        <ul role="tablist" class="nav bg-light nav-pills rounded nav-fill mb-3">
                            <li class="nav-item"> <a data-toggle="pill" href="#credit-card" class="nav-link active "> <i
                                        class="fas fa-credit-card mr-2"></i> Inserisci carta di credito </a> </li>
                        </ul>
                    </div>

                    <div class="tab-content">
                        <div id="credit-card" class="tab-pane fade show active pt-3">

                            <form method="post" action="includes/checkout.inc.php" role="form">

                                <div class="form-group"> <label for="username">
                                        <h6> Titolare carta </h6>
                                    </label>
                                    <input type="text" name="username" placeholder="Nome proprietario carta" required class="form-control" pattern="^[a-zA-Z ]*$" title="Inserisci del testo">
                                </div>

                                <div class="form-group">

                                    <label for="cardNumber">
                                        <h6> Numero della carta </h6>
                                    </label>

                                    <div class="input-group">  <!--pattern="^.{16,16}$"-->
                                        <input type="text"  name="cardNumber" placeholder="Inserisci un numero di carta valido" class="form-control" pattern="[0-9]{16}" required title="Inserisci 16 cifre">
                                        <div class="input-group-append">
                                            <span class="input-group-text text-muted">
                                                <i class="fab fa-cc-visa mx-1"></i>
                                                <i class="fab fa-cc-mastercard mx-1"></i>
                                                <i class="fab fa-cc-amex mx-1"></i> </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-sm-8">

                                        <div class="form-group">
                                            <label><span class="hidden-xs">
                                                    <h6> Data di scadenza </h6>
                                                </span></label>
                                            <div class="input-group">
                                                <input type="number" placeholder="MM" name="" class="form-control" min=1 max=30 required>
                                                    <input type="number" placeholder="YY" name="" class="form-control" min=21 required>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-sm-4">

                                        <div class="form-group mb-4">
                                            <label data-toggle="tooltip"
                                                title="Inserisci le tre cifre del CV presenti nel retro della carta">
                                                <h6> CVV <i class="fa fa-question-circle d-inline"> </i></h6>
                                            </label>
                                            <input type="text" required class="form-control" pattern="[0-9]{3}" title="Inserisci 3 cifre">
                                        </div>

                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button name="makeOrderBtn" type="submit" class="subscribe btn btn-primary btn-block shadow-sm"> Conferma l'ordine </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<br>

<?php
        include_once 'footer.php';
    }
?>
