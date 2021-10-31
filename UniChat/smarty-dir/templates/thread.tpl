<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Titolo tab del browser -->
    <title>UniChat - {$titoloThread} </title>

    <!-- Custom fonts for this template-->
    <link href="/UniChat/Template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/UniChat/Template/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top" onload="recuperaColori()">

<!-- Inizio del Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Logo sito e Sidebar -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/UniChat/Home/showHome">
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
            <div id="collapsePages" class="collapse show" aria-labelledby="headingPages"
                 data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">

                    {foreach $categorie as $categoria}

                        <a class="collapse-item" href="/UniChat/Categorie/visualizzaCategoria/{$categoria->getID()}/1">{$categoria->getNome()}</a>

                    {/foreach}
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

                    <form method="post" action="/UniChat/threads/ricerca"
                          class="d-none d-sm-inline-block form-inline mr-0 ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">

                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 5px 0px 0px 5px">
                                    Filtra
                                </button>
                                <div class="dropdown-menu animated&#45;&#45;fade-in" aria-labelledby="dropdownMenuButton" style="">

                                    <label class="filtro-categorie dropdown-item" id="0-categoria" onclick="seleziona(this)">TUTTE</label>

                                    {foreach $categorie as $categoria}

                                        <label class="filtro-categorie dropdown-item" id="{$categoria->getID()}-categoria" onclick="seleziona(this)">{$categoria->getNome()}</label>

                                    {/foreach}


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

                                {foreach $categorie as $categoria}

                                    <label class="dropdown-item" id="{$categoria->getID()}cat" onclick="seleziona(this)">{$categoria->getNome()}</label>

                                {/foreach}

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
                            <form method="post" action="/UniChat/threads/ricerca" class="form-inline mr-auto w-100 navbar-search">
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

                            {if $loggato}

                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{$nome + " " + $cognome }</span>
                                <img class="img-profile rounded-circle"
                                     src="data:image/jpeg;base64,{$icona}">
                            </a>


                            <!-- Tendina -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                 aria-labelledby="userDropdown">


                                <a class="dropdown-item" href="/UniChat/Utenti/editShowPersonalProfile">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profilo
                                </a>

                                {if $controlpanel}

                                    <a class="dropdown-item" href="/UniChat/Admin/visualizzaPannelloDiControllo">
                                        <i class="fas fa-tools fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Pannello di Controllo
                                    </a>

                                {/if}



                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>

                            {else}

                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">"Entra"</span>
                                {html_image file="../Immagini/icona_autore.png"}
                            </a>

                            <!-- Tendina -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                 aria-labelledby="userDropdown">


                                <a class="dropdown-item" href="/UniChat/Utenti/login">
                                    <i class="fas fa-sign-in  fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Login
                                </a>

                                <a class="dropdown-item" href="/UniChat/Utenti/logout">
                                    <i class="fas fa-sign-out  fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>


                                {/if}


                        </li>
                    </ul>
                </ul>
            </nav>
            <!-- Fine della Topbar -->


            <!-- Inizio del Page Content -->
            <div class="container-fluid">

                <!-- Barra di navigazione -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/UniChat/home/showHome">Home</a></li>
                        <li class="breadcrumb-item"><a href="/UniChat/categorie/visualizzaCategoria/{$idCategoria}/1">{$categoriaThread}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{$titoloT}</li>
                    </ol>
                </nav>

                <div class="card shadow mb-2">

                    <!-- Intestazione della pagina -->
                    <div class="card-header py-3">

                        <div class="row">
                            <div class="col" style="max-width: max-content">

                                <img src="data:{$tipo};base64,{$immagine}" alt="" style="max-width: 80px"> <br>

                                <h6 class="m-0 font-weight-normal text-dark">{$nomeAutoreThread} {$cognomeAutoreThread}</h6>
                            </div>

                            <div class="col-sm">

                                <h2 class="m-0 font-weight-bold text-primary" style="margin-bottom: 5px">{$titoloThread}</h2>

                                <!-- Data pubblicazione Thread -->
                                <div class="py-0">
                                    <span><b>Pubblicato il:</b> {$dataThread}</span>
                                </div>
                            </div>

                            <div class="col">

                                <div class="row">

                                    <div class="col">

                                        <div class="text font-weight-bold text-primary" style="text-align: right; margin-top: 10px; padding-right: 5px;
                      margin-right: -10px; border-right: 2px solid #e3e6f0">
                                            <a>{$valutazioneThread}<i class="fas fa-star"></i></a>
                                        </div>
                                    </div>



                                    {if $loggato2 eq true}
                                    <div class="col" style="max-width: max-content" >
                                        <!-- Pulsanti valutazione -->
                                        <a href="/UniChat/threads/valutaThread/1/{$idThread}">
                                            <button id="bottoneInSu" class="btn btn-icon btn-success" onclick="coloraBottoneInSu()">
                                                <i class="fas fa-thumbs-up"></i>
                                            </button>
                                        </a>

                                        <a href="/UniChat/threads/valutaThread/-1/{$idThread}">
                                            <button id="bottoneInGiu" class="btn btn-icon btn-danger" onclick="coloraBottoneInGiu()">
                                                <i class="fas fa-thumbs-down"></i>
                                            </button>
                                        </a>

                                    </div>
                                    {/if}


                                    {if $moderatoreAdmin eq true}
                                    <div class="col" style="max-width: max-content; margin-left:-15px" >


                                        <!-- Pulsante eliminazione thread -->
                                        <a href="/UniChat/threads/rimuoviThread/{$idThread}" class="btn btn-danger btn-icon-split float-lg-right" onclick="presentaAlert()">
                                          <span class="icon text-white-50">
                                              <i class="fas fa-trash"></i>
                                          </span>
                                        </a>
                                    </div>
                                    {/if}


                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Descrizione -->
                    <div class="card-body">
                        {$testoThread}
                    </div>


                    <!-- Ciclo recupero allegati -->
                    <div class="card-footer">

                        <h5 class="m-0 font-weight-bold text-primary" style="padding-top:10px; margin-bottom: 5px">Scarica Allegati</h5><br>

                        <div class="row" style="background-color: #c0c1c8">

                            {foreach from=$allegatiThread item=allegato}
                            <div>
                                <i class="fa fa-paperclip" style="margin-left: 10px; margin-right: 10px; margin-top: 10px; padding-bottom: 15px" aria-hidden="true"> {$allegato['nome']}</i>
                                <a class="btn btn-danger btn-sm" href="/UniChat/threads/scaricaAllegato/{$allegato['id']}"> Download </a>
                            </div> <br>
                            {/foreach}

                        </div>

                    </div>


                    <!-- Divisore -->
                    <hr class="sidebar-divider">



                    <div class="card-body">

                        <h2 id="inserisci-risposta" class="font-weight-normal">Risposte al Thread</h2>


                        {if $loggato1 eq true}
                        <!-- Form invio messaggio -->
                        <div class="row mb-2 py-3">

                            <!-- Inserimento testo -->
                            <div class="col-sm-9 mb-3 mb-sm-0">
                                <input type="text" class="form-control" id="messaggio" placeholder="Inserisci una risposta...">
                            </div>

                            <!-- Pulsante invio -->
                            <div class="col-sm-3">
                                <a href="/UniChat/threads/rispondiThread" class="btn btn-success btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-arrow-right"></i>
                                    </span>
                                    <span class="text">Invia</span>
                                </a>
                            </div>
                        </div>
                        <!-- Fine form invio messaggio -->
                        {/if}


                        <!-- Ciclo recupero messaggi -->
                        <div class="card-body" style="height: 800px; line-height: 3em;overflow-y:scroll">

                            {foreach from=$risposteThread item=risposta}
                            <!-- Messaggio -->
                            <div class="card shadow mb-2">

                                <!-- Utente -->
                                <div class="card-header mr-0 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">{$risposta->getAutoreRisposta()->getNome()}
                                        {$risposta->getAutoreRisposta()->getCognome()}</h6>

                                    {if $moderatoreAdmin eq true}
                                    <!-- Pulsante eliminazione risposta -->
                                    <a href="/UniChat/threads/rimuoviRisposta/{$risposta->getId()}/{$idThread}" class="btn btn-danger btn-circle btn-sm" onclick="presentaAlert()">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    {/if}


                                </div>

                                <!-- Testo -->
                                <div class="card-body py-2">{$risposta->getTesto()}</div>

                                <!-- Data invio messaggio -->
                                <div class="container my-auto py-1">
                                    <div class="copyright text-center my-auto">
                                        <span>{$risposta->getData()}</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Fine messaggio -->
                            {/foreach}

                        </div>

                    </div>

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
                <a class="btn btn-primary" href="/UniChat/Utenti/logout">Logout</a>
            </div>
        </div>
    </div>
</div>








<!-- Script per la visualizzazione messaggio conferma/errore -->
<script>
    function presentaAlert() {
        alert({$messaggio});
    }
</script>

<!-- Script per il recupero dei colori dei bottoni valutazione in base al voto precedentemente inserito -->
<script>
    function recuperaColori() {
        {if $giudizio == 1}

        document.getElementById('bottoneInSu').classList.add('btn-primary');
        document.getElementById('bottoneInSu').classList.remove('btn-success');

        {elseif $giudizio == -1}

        document.getElementById('bottoneInGiu').classList.add('btn-primary');
        document.getElementById('bottoneInGiu').classList.remove('btn-danger');

        {/if}
    }
</script>


<!-- Script per la colorazione dei pulsanti di valutazione -->
<script>
    function coloraBottoneInSu() {

        if(document.getElementById('bottoneInSu').classList.contains('btn-success') && document.getElementById('bottoneInGiu').classList.contains('btn-danger')){
            document.getElementById('bottoneInSu').classList.add('btn-primary');
            document.getElementById('bottoneInSu').classList.remove('btn-success');
        }

        else if (document.getElementById('bottoneInSu').classList.contains('btn-primary') && document.getElementById('bottoneInGiu').classList.contains('btn-danger')) {
            document.getElementById('bottoneInSu').classList.remove('btn-primary');
            document.getElementById('bottoneInSu').classList.add('btn-success');
        }

    }
    function coloraBottoneInGiu() {

        if (document.getElementById('bottoneInSu').classList.contains('btn-success') && document.getElementById('bottoneInGiu').classList.contains('btn-danger')) {
            document.getElementById('bottoneInGiu').classList.add('btn-primary');
            document.getElementById('bottoneInGiu').classList.remove('btn-danger');
        }

        else if (document.getElementById('bottoneInSu').classList.contains('btn-success') && document.getElementById('bottoneInGiu').classList.contains('btn-danger')) {
            document.getElementById('bottoneInGiu').classList.remove('btn-primary');
            document.getElementById('bottoneInGiu').classList.add('btn-danger');
        }

    }
</script>






<!-- Bootstrap core JavaScript-->
<script src="/UniChat/Template/vendor/jquery/jquery.min.js"></script>
<script src="/UniChat/Template/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="/UniChat/Template/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="/UniChat/Template/js/sb-admin-2.min.js"></script>

</body>

</html>