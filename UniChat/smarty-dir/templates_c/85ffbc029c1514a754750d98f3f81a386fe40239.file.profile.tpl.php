<?php /* Smarty version Smarty-3.1.13, created on 2021-11-09 17:58:47
         compiled from "smarty-dir\templates\profile.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18693435576186999acde8b0-74464491%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '85ffbc029c1514a754750d98f3f81a386fe40239' => 
    array (
      0 => 'smarty-dir\\templates\\profile.tpl',
      1 => 1636476866,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18693435576186999acde8b0-74464491',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_6186999ad2ae52_19108528',
  'variables' => 
  array (
    'nomeUtente' => 0,
    'cognomeUtente' => 0,
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
    'tipo' => 0,
    'immagine' => 0,
    'emailUtente' => 0,
    'corsoUtente' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6186999ad2ae52_19108528')) {function content_6186999ad2ae52_19108528($_smarty_tpl) {?><!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Titolo tab del browser -->
    <title>UniChat - <?php echo $_smarty_tpl->tpl_vars['nomeUtente']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['cognomeUtente']->value;?>
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
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">

        <!-- Logo sito e Sidebar -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/UniChat/Home/visualizzaHome">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-laugh-wink"></i>
            </div>
            <div class="sidebar-brand-text mx-3">UniChat <sup>1.0</sup></div>
        </a>

        <!-- Divisore -->
        <hr class="sidebar-divider my-0">


        <!-- Menu laterale categorie -->
        <li class="nav-item active">
            <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
               aria-controls="collapsePages">
                <i class="fas fa-fw fa-folder"></i>
                <span>Categorie</span>
            </a>
            <div id="collapsePages" class="collapse" aria-labelledby="headingPages"
                 data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">

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

                    <form method="get" action="/UniChat/threads/ricerca/1"
                          class="d-none d-sm-inline-block form-inline mr-0 ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">

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

                </div>


                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">


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
                        </div>


                    </li>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">


                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Informazioni profilo loggato e relativa tendina -->
                        <li class="nav-item dropdown no-arrow">

                            <?php if ($_smarty_tpl->tpl_vars['loggato']->value){?>

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


                                <a class="dropdown-item" href="/UniChat/Utenti/editShowPersonalProfile">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profilo
                                </a>

                                <?php if ($_smarty_tpl->tpl_vars['controlpanel']->value){?>

                                    <a class="dropdown-item" href="/UniChat/Admin/visualizzaPannelloDiControllo">
                                        <i class="fas fa-tools fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Pannello di Controllo
                                    </a>

                                <?php }?>



                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>

                            <?php }else{ ?>

                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <button class="btn btn-primary">Entra</button>
                            </a>

                            <!-- Tendina -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                 aria-labelledby="userDropdown">


                                <a class="dropdown-item" href="/UniChat/utenti/login">
                                    <i class="fas fa-sign-in-alt  fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Login
                                </a>

                                <a class="dropdown-item" href="/UniChat/utenti/registrazione">
                                    <i class="fas fa-user-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Registrazione
                                </a>


                                <?php }?>


                        </li>
                    </ul>
                </ul>
            </nav>
            <!-- Fine della Topbar -->


            <!-- Inizio del Page Content -->
            <div class="container-fluid">

                <!-- Intestazione della pagina -->
                <h1 class="h3 mb-4 text-gray-800">Profilo utente</h1>


                <div class="row">

                    <!-- Contenitore inserimento foto -->
                    <div class="col-xl-4">
                        <div class="card mb-4 mb-xl-0">

                            <!-- Titolo Contenitore -->
                            <div class="card-header">Immagine di Profilo</div>
                            <div class="card-body text-center">

                                <!-- Immagine profilo -->
                                <img class="img-account-profile mb-2" src="data:<?php echo $_smarty_tpl->tpl_vars['tipo']->value;?>
;base64,<?php echo $_smarty_tpl->tpl_vars['immagine']->value;?>
"
                                     alt="" style="max-width: 100%">

                            </div>
                        </div>
                    </div>

                    <!-- Contenitore informazioni utente -->
                    <div class="col-xl-8">
                        <div class="card mb-4">

                            <!-- Titolo contenitore -->
                            <div class="card-header">Dettagli account</div>
                            <div class="card-body">

                                <!-- Informazioni utente -->
                                <form>

                                    <h5 class="text-primary mb-1">Nome:</h5>
                                    <h6 class="text-dark mb-4"><?php echo $_smarty_tpl->tpl_vars['nomeUtente']->value;?>
</h6>

                                    <h5 class="text-primary mb-1">Cognome:</h5>
                                    <h6 class="text-dark mb-4"><?php echo $_smarty_tpl->tpl_vars['cognomeUtente']->value;?>
</h6>

                                    <h5 class="text-primary mb-1">Email:</h5>
                                    <h6 class="text-dark mb-4"><?php echo $_smarty_tpl->tpl_vars['emailUtente']->value;?>
</h6>

                                    <h5 class="text-primary mb-1">Corso di Studio:</h5>
                                    <h6 class="text-dark mb-4"><?php echo $_smarty_tpl->tpl_vars['corsoUtente']->value;?>
</h6>

                                </form>
                                <!-- Fine informazioni utente -->

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fine contenitore informazioni utente -->


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
                <a class="btn btn-primary" href="/UniChat/Utenti/logout">Logout</a>
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