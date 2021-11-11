<?php /* Smarty version Smarty-3.1.13, created on 2021-11-10 17:03:22
         compiled from "smarty-dir\templates\login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1150277287618546a07bee18-26084307%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f483346f58475179dc31d7a18be7f4488c5730da' => 
    array (
      0 => 'smarty-dir\\templates\\login.tpl',
      1 => 1636559991,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1150277287618546a07bee18-26084307',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_618546a07e0838_10814252',
  'variables' => 
  array (
    'credenzialiErrate' => 0,
    'messaggioCredenzialiErrate' => 0,
    'erroreDatiObbligatori' => 0,
    'messaggioErroreDatiObbligatori' => 0,
    'erroreEmail' => 0,
    'messaggioErroreEmail' => 0,
    'errorePassword' => 0,
    'messaggioErrorePassword' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_618546a07e0838_10814252')) {function content_618546a07e0838_10814252($_smarty_tpl) {?><!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Titolo tab del browser -->
    <title>UniChat - Login</title>

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
                                    <h1 class="h4 text-gray-900 mb-4">Bentornato!</h1>
                                </div>

                                <!-- Form di Login -->
                                <form class="user" method="post" action="/UniChat/utenti/login">


                                    <!-- Messaggio credenziali errate -->
                                    <?php if ($_smarty_tpl->tpl_vars['credenzialiErrate']->value==true){?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>Errore. </strong><?php echo $_smarty_tpl->tpl_vars['messaggioCredenzialiErrate']->value;?>

                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    <?php }?>
                                    <!-- Fine messaggio errore -->

                                    <!-- Messaggio dati obbligatori mancanti -->
                                    <?php if ($_smarty_tpl->tpl_vars['erroreDatiObbligatori']->value==true){?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>Errore. </strong><?php echo $_smarty_tpl->tpl_vars['messaggioErroreDatiObbligatori']->value;?>

                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    <?php }?>
                                    <!-- Fine messaggio errore -->

                                    <!-- Inserimento email -->

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

                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user"
                                               id="email" name="email" aria-describedby="emailHelp"
                                               placeholder="Email" pattern="[a-z.A-Z0-9]+@student.univaq.it$" title="Sono ammesse solo email del dominio student.univaq.it valide." required>
                                    </div>

                                    <!-- Inserimento password -->
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

                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user"
                                               id="password" name="password" placeholder="Password" title="Sono ammessi solo i caratteri alfanumerici e i simboli speciali: . \ - _ . La password deve essere lunga almeno 8 caratteri." required>
                                    </div>
                                    

                                    <!-- Pulsante Login -->
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Login
                                    </button>
                                </form>
                                <!-- Fine form di Login -->

                                <!-- Divisore -->
                                <hr>

                                <!-- Link a pagina recupero password -->
                                <div class="text-center">
                                    <a class="small" href="/UniChat/utenti/recuperoPassword">Password dimenticata?</a>
                                </div>

                                <!-- Link a pagina registrazione -->
                                <div class="text-center">
                                    <a class="small" href="/UniChat/utenti/registrazione">Crea un Account!</a>
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