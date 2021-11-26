<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Titolo tab del browser -->
    <title>UniChat - Recupero Password</title>

    <!-- Custom fonts for this template-->
    <link href="/UniChat/Template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/UniChat/Template/css/sb-admin-2.min.css" rel="stylesheet">


    <!-- Controllo JS disattivato.-->
    <noscript><meta http-equiv="refresh" content="0;URL=/UniChat/client/javascriptDisabilitati"></noscript>

</head>

<body class="bg-gradient-primary">

<!-- Contenitore principale -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="p-5">

                                <!-- Titolo -->
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-2">Password dimenticata?</h1>
                                    <p class="mb-4">Inserisci la tua email qui sotto e ti invieremo una password
                                        provvisoria con cui effettuare l'accesso!</p>
                                </div>

                                <!-- Form di recupero account -->
                                <form class="user" method="post" action="/UniChat/utenti/recuperoPassword">

                                    <!-- Visualizzazione messaggio di conferma o errore -->
                                    {if $messaggio == true}
                                        <div class="alert alert-{$colore} alert-dismissible fade show" role="alert">
                                            {$testo}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                    {/if}

                                    <!-- Messaggio credenziali errate -->
                                    {if $credenzialiErrate == true}
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>Errore. </strong>{$messaggioCredenzialiErrate}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    {/if}

                                    <!-- Messaggio errore validazione email -->
                                    {if $erroreEmail == true}
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>Errore. </strong>{$messaggioErroreEmail}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    {/if}
                                    <!-- Fine messaggio errore -->

                                    <!-- Messaggio errore campi obbligatori mancanti -->
                                    {if $erroreDatiObbligatori == true}
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>Errore. </strong>{$messaggioErroreDatiObbligatori}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    {/if}
                                    <!-- Fine messaggio errore -->

                                    <!-- Inserisci email -->
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user"
                                               id="email" name="email" aria-describedby="emailHelp"
                                               placeholder="Inserisci la tua Email..." title="Sono ammesse solo email valide." required>
                                    </div>

                                    <!-- Bottone conferma email inserita -->
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Reimposta Password
                                    </button>
                                </form>
                                <!-- Fine form di recupero account -->


                                <!-- Divisore -->
                                <hr>

                                <!-- Link pagina registrazione -->
                                <div class="text-center">
                                    <a class="small" href="/UniChat/utenti/registrazione">Crea un Account!</a>
                                </div>

                                <!-- Link pagina login -->
                                <div class="text-center">
                                    <a class="small" href="/UniChat/utenti/login">Sei già un utente? Effettua il Login!</a>
                                </div><br>

                                <!-- Link torna alla home  -->
                                <div class="text-center">
                                    <a href="/UniChat" class="btn btn-google" role="button"><i class="fas fa-home pr-2" aria-hidden="true"></i>Torna alla Home</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Fine contenitore principale -->

<!-- Bootstrap core JavaScript-->
<script src="/UniChat/Template/vendor/jquery/jquery.min.js"></script>
<script src="/UniChat/Template/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="/UniChat/Template/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="/UniChat/Template/js/sb-admin-2.min.js"></script>

<!-- Controllo cookie disabilitati -->
<script>
    if (navigator.cookieEnabled === false) {
        window.location.replace('/UniChat/client/cookieDisabilitati');
    }
</script>

</body>

</html>