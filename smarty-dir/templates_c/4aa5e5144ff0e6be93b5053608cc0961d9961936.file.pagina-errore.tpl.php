<?php /* Smarty version Smarty-3.1.13, created on 2021-11-10 10:10:16
         compiled from "smarty-dir\templates\pagina-errore.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20572815561869e3f568891-77147816%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4aa5e5144ff0e6be93b5053608cc0961d9961936' => 
    array (
      0 => 'smarty-dir\\templates\\pagina-errore.tpl',
      1 => 1636476867,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20572815561869e3f568891-77147816',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_61869e3f5a3de3_16456441',
  'variables' => 
  array (
    'codiceErrore' => 0,
    'tipoErrore' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_61869e3f5a3de3_16456441')) {function content_61869e3f5a3de3_16456441($_smarty_tpl) {?><!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Titolo tab del browser -->
    <title>UniChat - <?php echo $_smarty_tpl->tpl_vars['codiceErrore']->value;?>
</title>

    <!-- Custom fonts for this template-->
    <link href="/UniChat/Template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/UniChat/Template/css/sb-admin-2.min.css" rel="stylesheet">

    <noscript><meta http-equiv="refresh" content="0;URL=/UniChat/client/javascriptDisabilitati"></noscript>

</head>

<body id="page-top">

<!-- Inizio del Page Wrapper -->
<div id="wrapper" class="mt-5">


    <div class="container-fluid">

        <!-- Messaggio di errore (404 Error) -->
        <div class="text-center">
            <div class="error mx-auto" data-text="<?php echo $_smarty_tpl->tpl_vars['codiceErrore']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['codiceErrore']->value;?>
</div>
            <p class="lead text-gray-800 mb-5"><?php echo $_smarty_tpl->tpl_vars['tipoErrore']->value;?>
</p>
            <a href="/UniChat/home/visualizzaHome">&larr; Torna alla Home</a>
        </div>
    </div>

</div>
<!-- Fine contenuto principale della pagina -->

<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; UniChat 2021</span>
        </div>
    </div>
</footer>
<!-- Fine del Footer -->

</div>
<!-- Fine del Content Wrapper -->

</div>
<!-- Fine del Page Wrapper -->


<!-- Bootstrap core JavaScript-->
<script src="/UniChat/Template/vendor/jquery/jquery.min.js"></script>
<script src="/UniChat/Template/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="/UniChat/Template/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="/UniChat/Template/js/sb-admin-2.min.js"></script>

</body>

</html><?php }} ?>