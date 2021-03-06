<!DOCTYPE html>
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


                    <!-- Voce di men?? corrispondente ad ogni categoria -->
                    {foreach from=$cate item=c}

                        <a class="collapse-item" href="/UniChat/categorie/visualizzaCategoria/{$c->getID()}/1">{$c->getNome()}</a>

                    {/foreach}
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

                                    {foreach from=$categorie item=categoria}

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

                                {foreach from=$categorie item=categoria}

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

                        <!-- Men?? utente -->
                        <li class="nav-item dropdown no-arrow">


                            {if $loggato}
                            <!-- Se l'utente ?? loggato, mostrare nome cognome e icona dell'img di profilo -->

                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{$nome} {$cognome}</span>
                                <img class="img-profile rounded-circle"
                                     src="data:{$iconaTipo};base64,{$iconaImmagine}">
                            </a>


                            <!-- Tendina -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                 aria-labelledby="userDropdown">

                                <!-- Visualizzazione voce di men?? "Profilo" per accedere alle info personali -->
                                <a class="dropdown-item" href="/UniChat/Utenti/editShowPersonalProfile">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profilo
                                </a>

                                {if $controlpanel}

                                    <!-- Se l'utente ?? l'Admin, visualizzare il Pannello di Controllo -->
                                    <a class="dropdown-item" href="/UniChat/Admin/visualizzaPannelloDiControllo">
                                        <i class="fas fa-tools fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Pannello di Controllo
                                    </a>

                                {/if}


                                <!-- Voce di men?? per effettuare il Logout-->
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>

                            {else}

                            <!-- Vista del top men?? nel caso non l'utente non fosse loggato -->

                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <button class="btn btn-primary">Entra</button>
                            </a>

                            <!-- Tendina -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                 aria-labelledby="userDropdown">

                                <!-- Voce di men?? per effettuare il Login -->
                                <a class="dropdown-item" href="/UniChat/Utenti/login">
                                    <i class="fas fa-sign-in-alt  fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Login
                                </a>

                                <!-- Voce di men?? per effettuare la Registrazione -->
                                <a class="dropdown-item" href="/UniChat/Utenti/registrazione">
                                    <i class="fas fa-user-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Registrazione
                                </a>
                                {/if}

                        </li>
                        <!-- Fine men?? utente -->
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
                        {$titoloCercato}
                        in <b>"{$categoriaNome}"</b></h6>
                    <h7>Risultati:</h7><br><br>


                    {if $numeroPagine>0 }

                        <!--inizio righe thread-->


                        {$j=0} <!--variabile che gestisce l'accesso ai threads -->


                        {for $i=1 to $maxrighe}


                            <div class="row">

                                <!-- 1?? thread della riga -->
                                <div class="col">

                                    <!-- Contenitore Thread  -->
                                    <div class="card shadow mb-2">

                                        <!-- Intestazione Thread -->
                                        <div class="card-header py-3">

                                            <div class="row">

                                                <!-- Nome, valutazione e autore del thread -->
                                                <div class="col mr-4">
                                                    <h5 class="m-0 font-weight-bold text-primary">{$listathread[$j]->getTitolo()}<sup>{$listathread[$j]->getValutazione()->getTotale()} <i class="fas fa-star"></i></sup></h5><br>
                                                    <h6 class="m-0 font-weight-normal text-dark">Pubblicato da: <b><a href="/UniChat/utenti/showProfile/{$listathread[$j]->getAutoreThread()->getID()}">{$listathread[$j]->getAutoreThread()->getNome()} {$listathread[$j]->getAutoreThread()->getCognome()}</a></b></h6>

                                                    <!-- Data di pubblicazione -->
                                                    <div class="py-0">
                                                        <span>il: <b>{$listathread[$j]->getData()}</b></span>
                                                    </div>
                                                </div>

                                                <div class="ml-2 mt-2 mr-2" style="position: absolute; top: 0; right: 0;">

                                                    <!-- Pulsante thread leggi tutto -->
                                                    <a href="\UniChat\Threads\visualizzaThread\{$listathread[$j]->getID()}" class="btn btn-info btn-icon-split">
                                                        <span class="icon text-white-600">
                                                            <i class="fas fa-arrow-right"></i>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Descrizione Thread -->
                                        <div class="card-body">
                                            {$listathread[$j]->getTesto()}
                                        </div>

                                        <!-- Contatore risposte -->
                                        <div class="container my-auto py-3">
                                            <div class="copyright text-center my-auto">
                                                <span>Risposte: {$listathread[$j]->contaRisposte()} </span>
                                                <i class="fa fa-comments text-primary"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fine Thread -->

                                </div>

                                {if $j+1 < $posizioneMax}

                                    <!-- 2?? thread della riga -->
                                    <div class="col">

                                        <!-- Contenitore Thread  -->
                                        <div class="card shadow mb-2">

                                            <!-- Intestazione Thread -->
                                            <div class="card-header py-3">

                                                <div class="row">

                                                    <!-- Titolo, valutazione e autore del thread -->
                                                    <div class="col mr-4">
                                                        <h5 class="m-0 font-weight-bold text-primary">{$listathread[$j+1]->getTitolo()}<sup>{$listathread[$j+1]->getValutazione()->getTotale()} <i class="fas fa-star"></i></sup></h5><br>
                                                        <h6 class="m-0 font-weight-normal text-dark">Pubblicato da: <b><a href="/UniChat/utenti/showProfile/{$listathread[$j+1]->getAutoreThread()->getId()}">{$listathread[$j+1]->getAutoreThread()->getNome()} {$listathread[$j+1]->getAutoreThread()->getCognome()}</a></b></h6>

                                                        <!-- Data di pubblicazione -->
                                                        <div class="py-0">
                                                            <span>il: <b>{$listathread[$j+1]->getData()}</b></span>
                                                        </div>
                                                    </div>

                                                    <div class="ml-2 mt-2 mr-2" style="position: absolute; top: 0; right: 0;">

                                                        <!-- Pulsante thread specifico -->
                                                        <a href="\UniChat\Threads\visualizzaThread\{$listathread[$j+1]->getID()}" class="btn btn-info btn-icon-split">
                                                                            <span class="icon text-white-600">
                                                                                <i class="fas fa-arrow-right"></i>
                                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Descrizione Thread -->
                                            <div class="card-body">
                                                {$listathread[$j+1]->getTesto()}
                                            </div>

                                            <!-- Contatore risposte -->
                                            <div class="container my-auto py-3">
                                                <div class="copyright text-center my-auto">
                                                    <span>Risposte: {$listathread[$j+1]->contaRisposte()} </span>
                                                    <i class="fa fa-comments text-primary"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Fine Thread -->

                                    </div>
                                    <!-- Fine 2?? thread della riga -->

                                {/if}

                            </div>

                            <!-- Contatore per unaa nuova riga -->
                            {$j=$j+2}

                        {/for}

                        <!--fine righe tread-->


                        <!--inizio paginazione-->

                        <div class="pagination mt-3 mb-3" style="justify-content: center">

                            {if $activePage!=1}
                                <!-- Bottone vai a pagina precedente -->
                                <a href="\UniChat\threads\ricerca\{$activePage -1}?categoriaID={$categoriaIdRicerca}&testoricerca={$testoRicerca}">&laquo;</a>

                            {/if}


                            {for $pagThread=1 to $numeroPagine}

                                {if $pagThread==$activePage}

                                    <!-- La pagina che si sta visualizzando viene evidenziata -->
                                    <a href="\UniChat\threads\ricerca\{$pagThread}?categoriaID={$categoriaIdRicerca}&testoricerca={$testoRicerca}" class="active">{$pagThread}</a>

                                {else}

                                    <!-- Le altre pagine presenti -->
                                    <a href="\UniChat\threads\ricerca\{$pagThread}?categoriaID={$categoriaIdRicerca}&testoricerca={$testoRicerca}">{$pagThread}</a>

                                {/if}

                            {/for}

                            {if $activePage!=$numeroPagine}

                                <!-- Bottone vai a pagina successiva -->
                                <a href="\UniChat\threads\ricerca\{$activePage +1}?categoriaID={$categoriaIdRicerca}&testoricerca={$testoRicerca}">&raquo;</a>

                            {/if}

                        </div>

                        <!--fine paginazione-->


                        <!-- Caso: nessun risultato prodotto dalla ricerca -->
                    {else}

                        <h5 class="h5 mb-0 text-gray-800 mt-0">Nessun Thread presente in questa Categoria.</h5>

                    {/if}


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
                    <span aria-hidden="true">??</span>
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
     * dropdown-item active' e procede ad assegnargli il valore 'filtro-categorie dropdown-item', cos?? facendo la
     * categoria precedentemente scelta, viene deselezionata.
     * La funzione prende in ingresso l'elemento HTML su cui si clicca per selezionare la categoria e a questo
     * elemento viene assegnato il valore 'filtro-categorie dropdown-item active' all'attributo class e cos?? facendo
     * risulter?? evidenziato.
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

</html>