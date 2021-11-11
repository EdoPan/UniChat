<?php /* Smarty version Smarty-3.1.13, created on 2021-11-11 11:33:28
         compiled from "smarty-dir\templates\pagina-errore-client.tpl" */ ?>
<?php /*%%SmartyHeaderCode:185797566618aa2b5218452-64876137%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9c653f97da9db5f92dc8bf67b999e8dc81b343fe' => 
    array (
      0 => 'smarty-dir\\templates\\pagina-errore-client.tpl',
      1 => 1636626733,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '185797566618aa2b5218452-64876137',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_618aa2b52661c6_60412864',
  'variables' => 
  array (
    'messaggioErrore' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_618aa2b52661c6_60412864')) {function content_618aa2b52661c6_60412864($_smarty_tpl) {?><!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Titolo tab del browser -->
    <title>UniChat - Errore</title>

    <!-- Custom fonts for this template-->
    <link href="/UniChat/Template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/UniChat/Template/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

<!-- Inizio del Page Wrapper -->
<div id="wrapper" class="mt-5">


    <div class="container-fluid">

        <!-- Messaggio di errore (404 Error) -->
        <div class="text-center">
            <p class="lead text-gray-800 mb-5"><?php echo $_smarty_tpl->tpl_vars['messaggioErrore']->value;?>
</p>
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
<!-- Fine del Page Wrapper -->

</body>

</html><?php }} ?>