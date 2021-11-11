<?php /* Smarty version Smarty-3.1.13, created on 2021-11-10 17:03:26
         compiled from "smarty-dir\templates\registrazione.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2079493896618931b5499851-06040689%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1bf7231a39719df1d1be9b24b706ca96fd3ba77c' => 
    array (
      0 => 'smarty-dir\\templates\\registrazione.tpl',
      1 => 1636559992,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2079493896618931b5499851-06040689',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_618931b54ddde1_01633459',
  'variables' => 
  array (
    'erroreOperazione' => 0,
    'messaggioErroreOperazione' => 0,
    'erroreDatiObbligatori' => 0,
    'messaggioErroreDatiObbligatori' => 0,
    'erroreDenominazione' => 0,
    'messaggioErroreDenominazione' => 0,
    'erroreEmail' => 0,
    'messaggioErroreEmail' => 0,
    'errorePassword' => 0,
    'messaggioErrorePassword' => 0,
    'erroreImmagine' => 0,
    'messaggioErroreImmagine' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_618931b54ddde1_01633459')) {function content_618931b54ddde1_01633459($_smarty_tpl) {?><!DOCTYPE html>
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

    <noscript><meta http-equiv="refresh" content="0;URL=/UniChat/client/javascriptDisabilitati"></noscript>

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
                        <form class="user" enctype="multipart/form-data" action="/UniChat/utenti/registrazione" method="post">

                            <!-- Messaggio operazione fallita -->
                            <?php if ($_smarty_tpl->tpl_vars['erroreOperazione']->value==true){?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Errore. </strong><?php echo $_smarty_tpl->tpl_vars['messaggioErroreOperazione']->value;?>

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php }?>
                            <!-- Fine messaggio errore -->

                            <!-- Messaggio errore campi obbligatori mancanti -->
                            <?php if ($_smarty_tpl->tpl_vars['erroreDatiObbligatori']->value==true){?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Errore. </strong><?php echo $_smarty_tpl->tpl_vars['messaggioErroreDatiObbligatori']->value;?>

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php }?>
                            <!-- Fine messaggio errore -->

                            <!-- Messaggio errore validazione nome o cognome -->
                            <?php if ($_smarty_tpl->tpl_vars['erroreDenominazione']->value==true){?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Errore. </strong><?php echo $_smarty_tpl->tpl_vars['messaggioErroreDenominazione']->value;?>

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php }?>
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
                            <?php if ($_smarty_tpl->tpl_vars['erroreEmail']->value==true){?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Errore. </strong><?php echo $_smarty_tpl->tpl_vars['messaggioErroreEmail']->value;?>

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php }?>
                            <!-- Fine messaggio errore -->


                            <!-- Inserimento email -->
                            <div class="form-group">
                                <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Email" pattern="[a-z.A-Z0-9]+@student.univaq.it$" title="Sono ammesse solo email del dominio student.univaq.it valide." required>
                            </div>


                            <!-- Messaggio errore validazione password -->
                            <?php if ($_smarty_tpl->tpl_vars['errorePassword']->value==true){?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Errore. </strong><?php echo $_smarty_tpl->tpl_vars['messaggioErrorePassword']->value;?>

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php }?>
                            <!-- Fine messaggio errore -->

                            <!-- Messaggio avviso password non uguali -->
                            <div id="avvisoPasswordNonUguali"></div>
                            <!-- Fine messaggio -->

                            <div class="form-group row">

                                <!-- Inserimento password -->
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" class="form-control form-control-user"
                                           id="password" name="password" placeholder="Password" title="Sono ammessi solo i caratteri alfanumerici e i simboli speciali: . \ - _ . La password deve essere lunga almeno 8 caratteri." required>
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
                            <?php if ($_smarty_tpl->tpl_vars['erroreImmagine']->value==true){?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Errore. </strong><?php echo $_smarty_tpl->tpl_vars['messaggioErroreImmagine']->value;?>

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php }?>
                            <!-- Fine messaggio di errore -->

                            <!-- Pulsante caricamento foto profilo -->


                            <input type="file" id="fotoProfilo" name="fotoProfilo" hidden="hidden" />
                            <button class="btn btn-secondary btn-user btn-block" type="button" id="custom-button"><i class="fas fa-upload pr-2" aria-hidden="true"></i>Carica Foto Profilo</button>
                            <span style="display: block; position: relative; text-align: center"; id="custom-text">Nessuna foto caricata</span><br><br>


                            <!--<label for="fotoProfilo" style="display: block; position: relative; border-radius: 25px; background: linear-gradient(40deg, #ff2525, #e33535); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: normal; cursor: pointer; transition: transform .2s ease-out;">
                                Carica foto profilo
                            </label>

                            <label for="fotoProfilo" style="display: block; position: relative; width: 100%; height: 45px; border-radius: 25px; background: linear-gradient(40deg, #ff2525, #e33535); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: normal; cursor: pointer; transition: transform .2s ease-out;">
                                 Carica foto profilo
                             </label>
                             <input class="btn btn-google" type="file" id="fotoProfilo" name="fotoProfilo">  -->
                              <!-- Pulsante registrazione account -->
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Registra Account
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

                        <br>

                        <!-- Link torna alla home  -->
                        <div class="text-center">
                            <a href="/UniChat/" class="btn btn-google" role="button"><i class="fas fa-home pr-2" aria-hidden="true"></i>Torna alla Home</a>
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

<script>
    if (navigator.cookieEnabled === false) {
        window.location.replace('/UniChat/client/cookieDisabilitati');
    }
</script>

<script type="text/javascript">
    function verificaPassword() {
        var password;
        var ripetiPassword;
        password = document.getElementById("password").value;
        ripetiPassword = document.getElementById("ripetiPassword").value;
        if (password !== ripetiPassword) {
            document.getElementById("avvisoPasswordNonUguali").innerHTML = '<div class="alert alert-warning alert-dismissible fade show" role="alert">' +
                '<b>Attenzione. </b>Le due password non sono uguali.' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span>' +
                '</button>' +
                '</div>';
        } else {
            document.getElementById("avvisoPasswordNonUguali").innerHTML = '';
        }
    }
</script>

<script type="text/javascript">
        const realFileBtn = document.getElementById("fotoProfilo");
        const customBtn = document.getElementById("custom-button");
        const customTxt = document.getElementById("custom-text");

        customBtn.addEventListener("click", function() {
            realFileBtn.click();
        });

        realFileBtn.addEventListener("change", function() {
            if (realFileBtn.value) {
                customTxt.innerHTML = realFileBtn.value.match(
                    /[\/\\]([\w\d\s\.\-\(\)]+)$/
                )[1];
            } else {
                customTxt.innerHTML = "Nessuna foto caricata.";
            }
        });
</script>


</body>

</html><?php }} ?>