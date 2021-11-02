<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>UniChat - Registrazione</title>

    <!-- Custom fonts for this template-->
    <link href="/UniChat/Template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/UniChat/Template/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

<!-- Contenitore principale -->
<div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-7">
                    <div class="p-5">

                        <!-- Titolo -->
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Crea un Account!</h1>
                        </div>

                        <!-- Form registrazione -->
                        <form class="user">

                            <!-- Messaggio errore validazione nome o cognome -->
                            {if $erroreDenominazione == true}
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Errore. </strong>{$messaggioErroreDenominazione}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            {/if}
                            <!-- Fine messaggio errore -->


                            <div class="form-group row">

                                <!-- Inserimento nome -->
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="text" class="form-control form-control-user" id="nome"
                                           name="nome" placeholder="Nome" pattern="^[a-z A-z]+" title="Sono ammessi solo caratteri alfabetici." required>
                                </div>

                                <!-- Inserimento cognome -->
                                <div class="col-sm-6">
                                    <input type="text" class="form-control form-control-user" id="cognome"
                                           name="cognome" placeholder="Cognome" pattern="^[a-z A-z]+" title="Sono ammessi solo caratteri alfabetici." required>
                                </div>
                            </div>

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


                            <!-- Inserimento email -->
                            <div class="form-group">
                                <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Email" pattern="[a-z.A-Z0-9]+@student.univaq.it$" title="Sono ammesse solo email del dominio student.univaq.it valide." required>
                            </div>


                            <!-- Messaggio errore validazione password -->
                            {if $errorePassword == true}
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Errore. </strong>{$messaggioErrorePassword}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            {/if}
                            <!-- Fine messaggio errore -->

                            <div class="form-group row">

                                <!-- Inserimento password -->
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" class="form-control form-control-user"
                                           id="password" name="password" placeholder="Password" pattern="^[a-zA-z0-9@.\-_]{8,}$" title="Sono ammessi solo i caratteri alfanumerici e i simboli speciali: . \ - _ . La password deve essere lunga almeno 8 caratteri." required>
                                </div>

                                <!-- Conferma password -->
                                <div class="col-sm-6">
                                    <input type="password" class="form-control form-control-user"
                                           id="ripetiPassword" onblur="verificaPassword()" placeholder="Ripeti la Password" required>
                                </div>
                            </div>

                            <!-- Inserimento Corso di Studio -->
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="corso"
                                       name="corsoStudio" placeholder="Corso di Studio">
                            </div>

                            <!-- Messaggio errore validazione foto profilo -->
                            {if $erroreImmagine == true}
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Errore. </strong>{$messaggioErroreImmagine}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            {/if}
                            <!-- Fine messaggio di errore -->

                            <!-- Pulsante caricamento foto profilo -->
                            <label for="fotoProfilo">Carica foto profilo:</label>
                            <input type="file" id="fotoProfilo" name="fotoProfilo" class="btn btn-google btn-user btn-block">

                            <!-- Pulsante registrazione account -->
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Regista Account
                            </button>
                        </form>
                        <!-- Fine form registrazione -->

                        <!-- Divisore -->
                        <hr>

                        <!-- Link pagina recupero password -->
                        <div class="text-center">
                            <a class="small" href="/UniChat/utenti/recuperoPassword">Password dimenticata?</a>
                        </div>

                        <!-- Link pagina registrazione -->
                        <div class="text-center">
                            <a class="small" href="/UniChat/utenti/login">Sei già un utente? Effettua il Login!</a>
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

<script type="text/javascript">
    function verificaPassword() {
        var password;
        var ripetiPassword;
        password = document.getElementById("password").value;
        ripetiPassword = document.getElementById("ripetiPassword").value;
        if (password !== ripetiPassword) {
            alert("Le due password non coincidono");
        }
    }
</script>

</body>

</html>