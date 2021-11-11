<?php /* Smarty version Smarty-3.1.13, created on 2021-11-10 17:03:31
         compiled from "smarty-dir\templates\recupero-password.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15149743026185471d2b71f5-85161802%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b80972bd4bfb04e2acf721582c16c88a7dfcce3d' => 
    array (
      0 => 'smarty-dir\\templates\\recupero-password.tpl',
      1 => 1636559991,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15149743026185471d2b71f5-85161802',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_6185471d2e7360_89124175',
  'variables' => 
  array (
    'messaggio' => 0,
    'colore' => 0,
    'testo' => 0,
    'credenzialiErrate' => 0,
    'messaggioCredenzialiErrate' => 0,
    'erroreEmail' => 0,
    'messaggioErroreEmail' => 0,
    'erroreDatiObbligatori' => 0,
    'messaggioErroreDatiObbligatori' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6185471d2e7360_89124175')) {function content_6185471d2e7360_89124175($_smarty_tpl) {?><!DOCTYPE html>
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

                                    <?php if ($_smarty_tpl->tpl_vars['messaggio']->value==true){?>
                                        <div class="alert alert-<?php echo $_smarty_tpl->tpl_vars['colore']->value;?>
 alert-dismissible fade show" role="alert">
                                            <?php echo $_smarty_tpl->tpl_vars['testo']->value;?>

                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                    <?php }?>

                                    <!-- Messaggio credenziali errate -->
                                    <?php if ($_smarty_tpl->tpl_vars['credenzialiErrate']->value==true){?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>Errore. </strong><?php echo $_smarty_tpl->tpl_vars['messaggioCredenzialiErrate']->value;?>

                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    <?php }?>

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

                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user"
                                               id="email" name="email" aria-describedby="emailHelp"
                                               placeholder="Inserisci la tua Email..." pattern="[a-z.A-Z0-9]+@student.univaq.it$" title="Sono ammesse solo email del dominio student.univaq.it valide." required>
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
                                    <a href="/UniChat/" class="btn btn-google" role="button"><i class="fas fa-home pr-2" aria-hidden="true"></i>Torna alla Home</a>
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

<script>
    if (navigator.cookieEnabled === false) {
        window.location.replace('/UniChat/client/cookieDisabilitati');
    }
</script>

</body>

</html><?php }} ?>