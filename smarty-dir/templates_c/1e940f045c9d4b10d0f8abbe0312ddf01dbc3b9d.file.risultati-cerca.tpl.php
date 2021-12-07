<?php /* Smarty version Smarty-3.1.13, created on 2021-11-26 13:22:57
         compiled from "smarty-dir\templates\risultati-cerca.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1447419577618902f1dd8b36-84132183%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1e940f045c9d4b10d0f8abbe0312ddf01dbc3b9d' => 
    array (
      0 => 'smarty-dir\\templates\\risultati-cerca.tpl',
      1 => 1637929261,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1447419577618902f1dd8b36-84132183',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_618902f1e65046_03218418',
  'variables' => 
  array (
    'cate' => 0,
    'c' => 0,
    'categorie' => 0,
    'categoria' => 0,
    'loggato' => 0,
    'nome' => 0,
    'cognome' => 0,
    'iconaTipo' => 0,
    'iconaImmagine' => 0,
    'controlpanel' => 0,
    'titoloCercato' => 0,
    'categoriaNome' => 0,
    'numeroPagine' => 0,
    'maxrighe' => 0,
    'j' => 0,
    'listathread' => 0,
    'posizioneMax' => 0,
    'activePage' => 0,
    'categoriaIdRicerca' => 0,
    'testoRicerca' => 0,
    'pagThread' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_618902f1e65046_03218418')) {function content_618902f1e65046_03218418($_smarty_tpl) {?><!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Titolo tab del browser -->
    <title>UniChat - Risultato ricerca</title>

    <!-- Custom fonts for this template-->
    <link href="/UniChat/Template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/UniChat/Template/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="/UniChat/Template/css/pannello_di_controllo.css" rel="stylesheet">


    <!-- Controllo JS disabilitato -->
    <noscript><meta http-equiv="refresh" content="0;URL=/UniChat/client/javascriptDisabilitati"></noscript>

</head>

<body id="page-top">

<!-- Inizio del Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">

        <!-- Logo sito e Sidebar -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/UniChat">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-laugh-wink"></i>
            </div>
            <div class="sidebar-brand-text mx-3">UniChat <sup>1.0</sup></div>
        </a>

        <!-- Divisore -->
        <hr class="sidebar-divider my-0">


        <!-- Inizio Menu laterale categorie -->
        <li class="nav-item active">
            <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
               aria-controls="collapsePages">
                <i class="fas fa-fw fa-folder"></i>
                <span>Categorie</span>
            </a>
            <div id="collapsePages" class="collapse" aria-labelledby="headingPages"
                 data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">


                    <!-- Voce di menù corrispondente ad ogni categoria -->
                    <?php  $_smarty_tpl->tpl_vars['c'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['c']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['cate']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['c']->key => $_smarty_tpl->tpl_vars['c']->value){
$_smarty_tpl->tpl_vars['c']->_loop = true;
?>

                        <a class="collapse-item" href="/UniChat/categorie/visualizzaCategoria/<?php echo $_smarty_tpl->tpl_vars['c']->value->getID();?>
/1"><?php echo $_smarty_tpl->tpl_vars['c']->value->getNome();?>
</a>

                    <?php } ?>
                </div>
            </div>
        </li>
        <!-- Fine Menu laterale categorie -->


        <!-- Divisore -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Bottone per restringere la Sidebar -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
    <!-- Fine della Sidebar -->

    <!-- Inizio del Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Inizio del Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>



                <!-- Topbar Search -->
                <div class="col justify-content-center" style="display: grid">

                    <!-- Inizio Form Ricerca -->
                    <form method="get" action="/UniChat/threads/ricerca/1"
                          class="d-none d-sm-inline-block form-inline mr-0 ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <!-- Bottone Filtra ricerca -->
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 5px 0px 0px 5px">
                                    Filtra
                                </button>
                                <div class="dropdown-menu animated&#45;&#45;fade-in" aria-labelledby="dropdownMenuButton" style="">

                                    <label class="filtro-categorie dropdown-item" id="0-categoria" onclick="seleziona(this)">TUTTE</label>

                                    <?php  $_smarty_tpl->tpl_vars['categoria'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['categoria']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['categorie']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['categoria']->key => $_smarty_tpl->tpl_vars['categoria']->value){
$_smarty_tpl->tpl_vars['categoria']->_loop = true;
?>

                                        <label class="filtro-categorie dropdown-item" id="<?php echo $_smarty_tpl->tpl_vars['categoria']->value->getID();?>
-categoria" onclick="seleziona(this)"><?php echo $_smarty_tpl->tpl_vars['categoria']->value->getNome();?>
</label>

                                    <?php } ?>


                                </div>
                            </div>

                            <input type="text" name="categoriaID" id="categoria-id" hidden>

                            <input type="search" name="testoricerca" id="testo-ricerca"
                                   class="form-control bg-light border-0 small" placeholder="Cerca tra i thread..."
                                   aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <!-- Fine Form Ricerca -->
                </div>


                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">


                    <!-- Filtra ricerca mod. XS -->
                    <div class="dropdown">

                        <div class="nav-item dropdown no-arrow d-sm-none mt-3">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="ricerca" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Filtra
                            </button>
                            <div class="dropdown-menu animated&#45;&#45 fade-in" aria-labelledby="ricerca" style="">

                                <label class="dropdown-item" id="0cat" onclick="seleziona(this)">TUTTE</label>

                                <?php  $_smarty_tpl->tpl_vars['categoria'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['categoria']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['categorie']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['categoria']->key => $_smarty_tpl->tpl_vars['categoria']->value){
$_smarty_tpl->tpl_vars['categoria']->_loop = true;
?>

                                    <label class="dropdown-item" id="<?php echo $_smarty_tpl->tpl_vars['categoria']->value->getID();?>
cat" onclick="seleziona(this)"><?php echo $_smarty_tpl->tpl_vars['categoria']->value->getNome();?>
</label>

                                <?php } ?>

                            </div>
                        </div>
                    </div>


                    <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                    <li class="nav-item dropdown no-arrow d-sm-none">
                        <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-search fa-fw"></i>
                        </a>
                        <!-- Dropdown - Cerca -->
                        <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                             aria-labelledby="searchDropdown">
                            <!-- Inizio Form Ricerca XS -->
                            <form method="get" action="/UniChat/threads/ricerca/1" class="form-inline mr-auto w-100 navbar-search">
                                <div class="input-group">

                                    <input type="text" name="categoriaID" id="categoria-id2" hidden>

                                    <input type="search" name="testoricerca" class="form-control bg-light border-0 small"
                                           placeholder="Cerca tra i thread..." aria-label="Search"
                                           aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search fa-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <!-- Fine Form Ricerca XS -->
                        </div>

                    </li>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">


                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Menù utente -->
                        <li class="nav-item dropdown no-arrow">


                            <?php if ($_smarty_tpl->tpl_vars['loggato']->value){?>
                            <!-- Se l'utente è loggato, mostrare nome cognome e icona dell'img di profilo -->

                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_smarty_tpl->tpl_vars['nome']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['cognome']->value;?>
</span>
                                <img class="img-profile rounded-circle"
                                     src="data:<?php echo $_smarty_tpl->tpl_vars['iconaTipo']->value;?>
;base64,<?php echo $_smarty_tpl->tpl_vars['iconaImmagine']->value;?>
">
                            </a>


                            <!-- Tendina -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                 aria-labelledby="userDropdown">

                                <!-- Visualizzazione voce di menù "Profilo" per accedere alle info personali -->
                                <a class="dropdown-item" href="/UniChat/Utenti/editShowPersonalProfile">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profilo
                                </a>

                                <?php if ($_smarty_tpl->tpl_vars['controlpanel']->value){?>

                                    <!-- Se l'utente è l'Admin, visualizzare il Pannello di Controllo -->
                                    <a class="dropdown-item" href="/UniChat/Admin/visualizzaPannelloDiControllo">
                                        <i class="fas fa-tools fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Pannello di Controllo
                                    </a>

                                <?php }?>


                                <!-- Voce di menù per effettuare il Logout-->
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>

                            <?php }else{ ?>

                            <!-- Vista del top menù nel caso non l'utente non fosse loggato -->

                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <button class="btn btn-primary">Entra</button>
                            </a>

                            <!-- Tendina -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                 aria-labelledby="userDropdown">

                                <!-- Voce di menù per effettuare il Login -->
                                <a class="dropdown-item" href="/UniChat/Utenti/login">
                                    <i class="fas fa-sign-in-alt  fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Login
                                </a>

                                <!-- Voce di menù per effettuare la Registrazione -->
                                <a class="dropdown-item" href="/UniChat/Utenti/registrazione">
                                    <i class="fas fa-user-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Registrazione
                                </a>
                                <?php }?>

                        </li>
                        <!-- Fine menù utente -->
                    </ul>
                </ul>
            </nav>
            <!-- Fine della Topbar -->

            <!-- Inizio del Page Content -->
            <div class="container-fluid">

                <!-- Intestazione della pagina -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Risultato Ricerca</h1>
                </div>

                <!-- Dati della ricerca -->
                <div class="container-fluid">
                    <h6>Hai cercato:
                        <?php echo $_smarty_tpl->tpl_vars['titoloCercato']->value;?>

                        in <b>"<?php echo $_smarty_tpl->tpl_vars['categoriaNome']->value;?>
"</b></h6>
                    <h7>Risultati:</h7><br><br>


                    <?php if ($_smarty_tpl->tpl_vars['numeroPagine']->value>0){?>

                        <!--inizio righe thread-->


                        <?php $_smarty_tpl->tpl_vars['j'] = new Smarty_variable(0, null, 0);?> <!--variabile che gestisce l'accesso ai threads -->


                        <?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int)ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? $_smarty_tpl->tpl_vars['maxrighe']->value+1 - (1) : 1-($_smarty_tpl->tpl_vars['maxrighe']->value)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0){
for ($_smarty_tpl->tpl_vars['i']->value = 1, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++){
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration == 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration == $_smarty_tpl->tpl_vars['i']->total;?>


                            <div class="row">

                                <!-- 1° thread della riga -->
                                <div class="col">

                                    <!-- Contenitore Thread  -->
                                    <div class="card shadow mb-2">

                                        <!-- Intestazione Thread -->
                                        <div class="card-header py-3">

                                            <div class="row">

                                                <!-- Nome, valutazione e autore del thread -->
                                                <div class="col mr-4">
                                                    <h5 class="m-0 font-weight-bold text-primary"><?php echo $_smarty_tpl->tpl_vars['listathread']->value[$_smarty_tpl->tpl_vars['j']->value]->getTitolo();?>
<sup><?php echo $_smarty_tpl->tpl_vars['listathread']->value[$_smarty_tpl->tpl_vars['j']->value]->getValutazione()->getTotale();?>
 <i class="fas fa-star"></i></sup></h5><br>
                                                    <h6 class="m-0 font-weight-normal text-dark">Pubblicato da: <b><a href="/UniChat/utenti/showProfile/<?php echo $_smarty_tpl->tpl_vars['listathread']->value[$_smarty_tpl->tpl_vars['j']->value]->getAutoreThread()->getID();?>
"><?php echo $_smarty_tpl->tpl_vars['listathread']->value[$_smarty_tpl->tpl_vars['j']->value]->getAutoreThread()->getNome();?>
 <?php echo $_smarty_tpl->tpl_vars['listathread']->value[$_smarty_tpl->tpl_vars['j']->value]->getAutoreThread()->getCognome();?>
</a></b></h6>

                                                    <!-- Data di pubblicazione -->
                                                    <div class="py-0">
                                                        <span>il: <b><?php echo $_smarty_tpl->tpl_vars['listathread']->value[$_smarty_tpl->tpl_vars['j']->value]->getData();?>
</b></span>
                                                    </div>
                                                </div>

                                                <div class="ml-2 mt-2 mr-2" style="position: absolute; top: 0; right: 0;">

                                                    <!-- Pulsante thread leggi tutto -->
                                                    <a href="\UniChat\Threads\visualizzaThread\<?php echo $_smarty_tpl->tpl_vars['listathread']->value[$_smarty_tpl->tpl_vars['j']->value]->getID();?>
" class="btn btn-info btn-icon-split">
                                                        <span class="icon text-white-600">
                                                            <i class="fas fa-arrow-right"></i>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Descrizione Thread -->
                                        <div class="card-body">
                                            <?php echo $_smarty_tpl->tpl_vars['listathread']->value[$_smarty_tpl->tpl_vars['j']->value]->getTesto();?>

                                        </div>

                                        <!-- Contatore risposte -->
                                        <div class="container my-auto py-3">
                                            <div class="copyright text-center my-auto">
                                                <span>Risposte: <?php echo $_smarty_tpl->tpl_vars['listathread']->value[$_smarty_tpl->tpl_vars['j']->value]->contaRisposte();?>
 </span>
                                                <i class="fa fa-comments text-primary"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fine Thread -->

                                </div>

                                <?php if ($_smarty_tpl->tpl_vars['j']->value+1<$_smarty_tpl->tpl_vars['posizioneMax']->value){?>

                                    <!-- 2° thread della riga -->
                                    <div class="col">

                                        <!-- Contenitore Thread  -->
                                        <div class="card shadow mb-2">

                                            <!-- Intestazione Thread -->
                                            <div class="card-header py-3">

                                                <div class="row">

                                                    <!-- Titolo, valutazione e autore del thread -->
                                                    <div class="col mr-4">
                                                        <h5 class="m-0 font-weight-bold text-primary"><?php echo $_smarty_tpl->tpl_vars['listathread']->value[$_smarty_tpl->tpl_vars['j']->value+1]->getTitolo();?>
<sup><?php echo $_smarty_tpl->tpl_vars['listathread']->value[$_smarty_tpl->tpl_vars['j']->value+1]->getValutazione()->getTotale();?>
 <i class="fas fa-star"></i></sup></h5><br>
                                                        <h6 class="m-0 font-weight-normal text-dark">Pubblicato da: <b><a href="/UniChat/utenti/showProfile/<?php echo $_smarty_tpl->tpl_vars['listathread']->value[$_smarty_tpl->tpl_vars['j']->value+1]->getAutoreThread()->getId();?>
"><?php echo $_smarty_tpl->tpl_vars['listathread']->value[$_smarty_tpl->tpl_vars['j']->value+1]->getAutoreThread()->getNome();?>
 <?php echo $_smarty_tpl->tpl_vars['listathread']->value[$_smarty_tpl->tpl_vars['j']->value+1]->getAutoreThread()->getCognome();?>
</a></b></h6>

                                                        <!-- Data di pubblicazione -->
                                                        <div class="py-0">
                                                            <span>il: <b><?php echo $_smarty_tpl->tpl_vars['listathread']->value[$_smarty_tpl->tpl_vars['j']->value+1]->getData();?>
</b></span>
                                                        </div>
                                                    </div>

                                                    <div class="ml-2 mt-2 mr-2" style="position: absolute; top: 0; right: 0;">

                                                        <!-- Pulsante thread specifico -->
                                                        <a href="\UniChat\Threads\visualizzaThread\<?php echo $_smarty_tpl->tpl_vars['listathread']->value[$_smarty_tpl->tpl_vars['j']->value+1]->getID();?>
" class="btn btn-info btn-icon-split">
                                                                            <span class="icon text-white-600">
                                                                                <i class="fas fa-arrow-right"></i>
                                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Descrizione Thread -->
                                            <div class="card-body">
                                                <?php echo $_smarty_tpl->tpl_vars['listathread']->value[$_smarty_tpl->tpl_vars['j']->value+1]->getTesto();?>

                                            </div>

                                            <!-- Contatore risposte -->
                                            <div class="container my-auto py-3">
                                                <div class="copyright text-center my-auto">
                                                    <span>Risposte: <?php echo $_smarty_tpl->tpl_vars['listathread']->value[$_smarty_tpl->tpl_vars['j']->value+1]->contaRisposte();?>
 </span>
                                                    <i class="fa fa-comments text-primary"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Fine Thread -->

                                    </div>
                                    <!-- Fine 2° thread della riga -->

                                <?php }?>

                            </div>

                            <!-- Contatore per unaa nuova riga -->
                            <?php $_smarty_tpl->tpl_vars['j'] = new Smarty_variable($_smarty_tpl->tpl_vars['j']->value+2, null, 0);?>

                        <?php }} ?>

                        <!--fine righe tread-->


                        <!--inizio paginazione-->

                        <div class="pagination mt-3 mb-3" style="justify-content: center">

                            <?php if ($_smarty_tpl->tpl_vars['activePage']->value!=1){?>
                                <!-- Bottone vai a pagina precedente -->
                                <a href="\UniChat\threads\ricerca\<?php echo $_smarty_tpl->tpl_vars['activePage']->value-1;?>
?categoriaID=<?php echo $_smarty_tpl->tpl_vars['categoriaIdRicerca']->value;?>
&testoricerca=<?php echo $_smarty_tpl->tpl_vars['testoRicerca']->value;?>
">&laquo;</a>

                            <?php }?>


                            <?php $_smarty_tpl->tpl_vars['pagThread'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['pagThread']->step = 1;$_smarty_tpl->tpl_vars['pagThread']->total = (int)ceil(($_smarty_tpl->tpl_vars['pagThread']->step > 0 ? $_smarty_tpl->tpl_vars['numeroPagine']->value+1 - (1) : 1-($_smarty_tpl->tpl_vars['numeroPagine']->value)+1)/abs($_smarty_tpl->tpl_vars['pagThread']->step));
if ($_smarty_tpl->tpl_vars['pagThread']->total > 0){
for ($_smarty_tpl->tpl_vars['pagThread']->value = 1, $_smarty_tpl->tpl_vars['pagThread']->iteration = 1;$_smarty_tpl->tpl_vars['pagThread']->iteration <= $_smarty_tpl->tpl_vars['pagThread']->total;$_smarty_tpl->tpl_vars['pagThread']->value += $_smarty_tpl->tpl_vars['pagThread']->step, $_smarty_tpl->tpl_vars['pagThread']->iteration++){
$_smarty_tpl->tpl_vars['pagThread']->first = $_smarty_tpl->tpl_vars['pagThread']->iteration == 1;$_smarty_tpl->tpl_vars['pagThread']->last = $_smarty_tpl->tpl_vars['pagThread']->iteration == $_smarty_tpl->tpl_vars['pagThread']->total;?>

                                <?php if ($_smarty_tpl->tpl_vars['pagThread']->value==$_smarty_tpl->tpl_vars['activePage']->value){?>

                                    <!-- La pagina che si sta visualizzando viene evidenziata -->
                                    <a href="\UniChat\threads\ricerca\<?php echo $_smarty_tpl->tpl_vars['pagThread']->value;?>
?categoriaID=<?php echo $_smarty_tpl->tpl_vars['categoriaIdRicerca']->value;?>
&testoricerca=<?php echo $_smarty_tpl->tpl_vars['testoRicerca']->value;?>
" class="active"><?php echo $_smarty_tpl->tpl_vars['pagThread']->value;?>
</a>

                                <?php }else{ ?>

                                    <!-- Le altre pagine presenti -->
                                    <a href="\UniChat\threads\ricerca\<?php echo $_smarty_tpl->tpl_vars['pagThread']->value;?>
?categoriaID=<?php echo $_smarty_tpl->tpl_vars['categoriaIdRicerca']->value;?>
&testoricerca=<?php echo $_smarty_tpl->tpl_vars['testoRicerca']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['pagThread']->value;?>
</a>

                                <?php }?>

                            <?php }} ?>

                            <?php if ($_smarty_tpl->tpl_vars['activePage']->value!=$_smarty_tpl->tpl_vars['numeroPagine']->value){?>

                                <!-- Bottone vai a pagina successiva -->
                                <a href="\UniChat\threads\ricerca\<?php echo $_smarty_tpl->tpl_vars['activePage']->value+1;?>
?categoriaID=<?php echo $_smarty_tpl->tpl_vars['categoriaIdRicerca']->value;?>
&testoricerca=<?php echo $_smarty_tpl->tpl_vars['testoRicerca']->value;?>
">&raquo;</a>

                            <?php }?>

                        </div>

                        <!--fine paginazione-->


                        <!-- Caso: nessun risultato prodotto dalla ricerca -->
                    <?php }else{ ?>

                        <h5 class="h5 mb-0 text-gray-800 mt-0">Nessun Thread presente in questa Categoria.</h5>

                    <?php }?>


                </div>

            </div>
            <!-- Fine del Page Content -->

        </div>
        <!-- Fine del Main Content -->

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
<!-- Fine Page Wrapper -->

<!-- Bottone Scroll to Top -->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sei sicuro di voler andare?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Seleziona "Logout" qui sotto se sei pronto a terminare la sessione attuale.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="\UniChat\Utenti\logout">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="/UniChat/Template/vendor/jquery/jquery.min.js"></script>
<script src="/UniChat/Template/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="/UniChat/Template/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="/UniChat/Template/js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="/UniChat/Template/vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script src="/UniChat/Template/js/demo/chart-area-demo.js"></script>
<script src="/UniChat/Template/js/demo/chart-pie-demo.js"></script>

<!-- Controllo cookie disabilitati -->
<script type="text/javascript">
    if (navigator.cookieEnabled === false) {
        window.location.replace('/UniChat/client/cookieDisabilitati');
    }
</script>

<script type="text/javascript">

    /**
     * Funzione che permette di gestire la selezione della categoria con cui filtrare la ricerca.
     * La funzione ricerca l'elemento del menu di selezione che abbia l'attributo class con valore 'filtro-categorie
     * dropdown-item active' e procede ad assegnargli il valore 'filtro-categorie dropdown-item', così facendo la
     * categoria precedentemente scelta, viene deselezionata.
     * La funzione prende in ingresso l'elemento HTML su cui si clicca per selezionare la categoria e a questo
     * elemento viene assegnato il valore 'filtro-categorie dropdown-item active' all'attributo class e così facendo
     * risulterà evidenziato.
     * Il nome della categoria scelta viene mostrato sul bottone per la selezione.
     * L'id della categoria scelta viene posto in un input text con attributo hidden e poi usato dalla form per
     * passare la richiesta la server.
     * @param elemento HTML rappresentante la categoria scelta per la ricerca.
     */
    function seleziona(elemento) {
        var categoriaID = parseInt(elemento.id);
        var elementiSelezione = document.getElementsByClassName('filtro-categorie dropdown-item active');
        if (elementiSelezione.length === 1){
            elementiSelezione[0].className = 'filtro-categorie dropdown-item'
        }

        document.getElementById('dropdownMenuButton').innerHTML = elemento.innerHTML;
        document.getElementById('ricerca').innerHTML = elemento.innerHTML;
        document.getElementById('categoria-id').value = categoriaID;
        document.getElementById('categoria-id2').value = categoriaID;
        elemento.className = 'filtro-categorie dropdown-item active';

    }
</script>


</body>

</html><?php }} ?>