<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Titolo tab del browser -->
    <title>UniChat - Pannello di Controllo</title>

    <!-- Custom fonts -->
    <link href="/UniChat/Template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

    <!-- Custom styles -->
    <link href="/UniChat/Template/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="/UniChat/Template/css/pannello_di_controllo.css" rel="stylesheet">

    <!-- Inizio controllo JavaScript abilitato -->
    <noscript><meta http-equiv="refresh" content="0;URL=/UniChat/client/javascriptDisabilitati"></noscript>
    <!-- Fine controllo JavaScript abilitato -->

</head>

<!-- Al caricamento della pagina viene eseguito del codice JavaScript per la corretta visualizzazione degli elementi
    paginati -->
<body id="page-top" onload="loadElementiPaginati(1, 'utenti');loadElementiPaginati(1, 'categorie');">

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

                    <!-- Elenco categorie -->
                    {foreach from=$cate item=c}

                        <a class="collapse-item" href="/UniChat/categorie/visualizzaCategoria/{$c->getID()}/1">{$c->getNome()}</a>

                    {/foreach}
                    <!-- Fine elenco categorie -->
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

                                    <!-- Elenco categorie filtro ricerca -->
                                    {foreach from=$categorie item=categoria}

                                        <label class="filtro-categorie dropdown-item" id="{$categoria->getID()}-categoria" onclick="seleziona(this)">{$categoria->getNome()}</label>

                                    {/foreach}
                                    <!-- Fine elenco categorie filtro ricerca -->


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

                                <!-- Elenco categorie filtro ricerca -->
                                {foreach from=$categorie item=categoria}

                                    <label class="dropdown-item" id="{$categoria->getID()}cat" onclick="seleziona(this)">{$categoria->getNome()}</label>

                                {/foreach}
                                <!-- Fine elenco categorie filtro ricerca -->

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

                            {if $loggato}
                            <!-- Menu utente loggato -->
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{$nome} {$cognome}</span>
                                <img class="img-profile rounded-circle"
                                     src="data:{$iconaTipo};base64,{$iconaImmagine}">
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
                            <!-- Menu utente non loggato -->
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <button class="btn btn-primary">Entra</button>
                            </a>

                            <!-- Tendina -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                 aria-labelledby="userDropdown">


                                <a class="dropdown-item" href="/UniChat/Utenti/login">
                                    <i class="fas fa-sign-in-alt  fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Login
                                </a>

                                <a class="dropdown-item" href="/UniChat/utenti/registrazione">
                                    <i class="fas fa-user-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Registrazione
                                </a>


                                {/if}


                        </li>
                    </ul>
                </ul>
            </nav>
            <!-- Fine della Topbar -->

            <!-- Inizio del Page Content -->
            <div class="container-fluid">

                <!-- Intestazione della pagina -->
                <!-- Avviso operazione non possibile -->
                {if $avviso == true}
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <b>Attenzione. </b>{$messaggioAvviso}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                {/if}
                <!-- Fine avviso -->
                <!-- Messaggi conferma errore -->
                {if $conferma == true}
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {$messaggioConferma}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                {/if}
                {if $errore == true}
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {$messaggioErrore}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                {/if}
                <!-- Fine messaggi conferma errore -->

                <h1 class="h3 mb-0 text-gray-800">Pannello di Controllo</h1>
                <p class="p-0 mb-4 text-gray-800">Area riservata al solo utente admin, ogni operazione eseguita sar?? irreversibile!</p>

                <!--Inizio contenitore tabella utenti -->
                <div id="tabella-utenti" class="card shadow mb-4">

                    <!-- Titolo contenitore -->
                    <a href="#utenti" class="d-block card-header py-3 collapsed" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="utenti">
                        <h6 class="m-0 font-weight-bold text-primary">Utenti</h6>
                    </a>

                    <!-- Contenitore utenti -->
                    <div class="collapse" id="utenti" style="">
                        <div class="card-body">

                            <!-- riga utenti-->
                            <div class="row">
                                <div class="col" id="utente-0">
                                    <!-- Utente -->

                                    <!-- Fine Utente -->
                                </div>
                                <div class="col" id="utente-1">
                                    <!-- Utente -->

                                    <!-- Fine Utente -->
                                </div>
                            </div>

                            <!-- riga utenti-->
                            <div class="row">
                                <div class="col" id="utente-2">
                                    <!-- Utente -->

                                    <!-- Fine Utente -->
                                </div>
                                <div class="col" id="utente-3">
                                    <!-- Utente -->

                                    <!-- Fine Utente -->
                                </div>
                            </div>

                            <!-- riga utenti-->
                            <div class="row">
                                <div class="col" id="utente-4">
                                    <!-- Utente -->

                                    <!-- Fine Utente -->
                                </div>
                                <div class="col" id="utente-5">
                                    <!-- Utente -->

                                    <!-- Fine Utente -->
                                </div>
                            </div>

                            <br>

                            <!-- Paginazione utenti -->
                            {if $numeroPagineUtenti > 0}
                            <div class="pagination" style="justify-content: center">

                                <!-- Permette di tornare indietro di una pagina -->
                                <a href="#tabella-utenti" onclick="indietro(1, 'utenti')">&laquo;</a>

                                <!-- Creazione dei "bottoni" di selezione delle pagine.
                                     Quando un bottone viene cliccato allora viene richiamata una funzione JavaScript che richiede al server
                                     i prossimi utenti da visualizzare e una seconda funzione che permette di evidenziare il nuovo numero di
                                     pagina selezionato. -->

                                {for $pagUtenti=1 to $numeroPagineUtenti}
                                    {if $pagUtenti == 1}

                                        <!-- Creazione del primo "bottone" che ha la peculiarit?? di essere attivo, ovvero evidenziato -->
                                        <a href="#tabella-utenti" id="pagina-utenti-{$pagUtenti}" class="navigazione-utenti active" onclick="loadElementiPaginati({$pagUtenti}, 'utenti');cambiaClass(this, 'utenti');">{$pagUtenti}</a>
                                    {else}
                                        <!-- Creazione dei successivi "bottoni", questi non sono evidenziati -->
                                        <a href="#tabella-utenti" id="pagina-utenti-{$pagUtenti}" class="navigazione-utenti" onclick="loadElementiPaginati({$pagUtenti}, 'utenti');cambiaClass(this, 'utenti');">{$pagUtenti}</a>
                                    {/if}
                                {/for}

                                <!-- Permette di andare avanti di una pagina -->
                                <a href="#tabella-utenti" onclick="avanti({$numeroPagineUtenti}, 'utenti')">&raquo;</a>
                            </div>
                            {/if}
                            <!-- Fine paginazione utenti -->

                        </div>
                    </div>
                </div>
                <!-- Fine contenitore utenti -->



                <!--Contenitore categorie -->
                <div id="tabella-categorie" class="card shadow mb-4">

                    <!-- Contenitore titolo -->
                    <a href="#categorie" class="d-block card-header py-3 collapsed" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="categorie">
                        <h6 class="m-0 font-weight-bold text-primary">Categorie</h6>
                    </a>

                    <!-- Categorie -->
                    <div class="collapse" id="categorie" style="">
                        <div class="card-body">

                            <a href="/UniChat/admin/aggiungiCategoria" class="btn btn-danger ml-3 mr-3 float-lg-right" role="button" aria-pressed="true">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                Inserisci nuova Categoria
                            </a>


                            <br><br>

                            <!-- Categoria -->
                            <div class="col" id="categoria-0">

                            </div>
                            <!-- Fine Categoria -->

                            <!-- Categoria -->

                            <div class="col" id="categoria-1">

                            </div>
                            <!-- Fine Categoria -->

                            <!-- Categoria -->
                            <div class="col" id="categoria-2">

                            </div>
                            <!-- Fine Categoria -->

                            <!-- Categoria -->
                            <div class="col" id="categoria-3">

                            </div>
                            <!-- Fine Categoria -->

                            <!-- Categoria -->
                            <div class="col" id="categoria-4">

                            </div>
                            <!-- Fine Categoria -->

                            <!-- Categoria -->
                            <div class="col" id="categoria-5">

                            </div>
                            <!-- Fine Categoria -->

                            <br>

                            <!-- Paginazione categorie-->
                            {if $numeroPagineCategorie > 0}
                            <div class="pagination" style="justify-content: center">

                                <!-- Permette di tornare indietro di una pagina -->
                                <a href="#tabella-categorie" onclick="indietro(1, 'categorie')">&laquo;</a>

                                <!-- Creazione dei "bottoni" di selezione delle pagine.
                                     Quando un bottone viene cliccato allora viene richiamata una funzione JavaScript che richiede al server
                                     i prossimi utenti da visualizzare e una seconda funzione che permette di evidenziare il nuovo numero di
                                     pagina selezionato. -->
                                {for $pagCategorie=1 to $numeroPagineCategorie}
                                    {if $pagCategorie == 1}

                                        <!-- Creazione del primo "bottone" che ha la peculiarit?? di essere attivo, ovvero evidenziato-->
                                        <a href="#tabella-categorie" id="pagina-categorie-{$pagCategorie}" class="navigazione-categorie active" onclick="loadElementiPaginati({$pagCategorie}, 'categorie');cambiaClass(this, 'categorie');">{$pagCategorie}</a>
                                    {else}
                                        <!-- Creazione dei successivi "bottoni", questi non sono evidenziati -->
                                        <a href="#tabella-categorie" id="pagina-categorie-{$pagCategorie}" class="navigazione-categorie" onclick="loadElementiPaginati({$pagCategorie}, 'categorie');cambiaClass(this, 'categorie');">{$pagCategorie}</a>
                                    {/if}
                                {/for}

                                <!-- Permette di andare avanti di una pagina -->
                                <a href="#tabella-categorie" onclick="avanti({$numeroPagineCategorie}, 'categorie')">&raquo;</a>
                            </div>
                            {/if}
                            <!-- Fine paginazione categorie -->

                        </div>
                    </div>
                </div>
                <!-- Fine contenitore categorie -->

            </div>
            <!-- Inizio del Page Content -->

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
<!-- Fine del Page Wrapper -->

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
                <a class="btn btn-primary" href="/UniChat/utenti/logout">Logout</a>
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

<!-- Script per la verifica dei cookie abilitati -->
<script type="text/javascript">
    if (navigator.cookieEnabled === false) {
        window.location.replace('/UniChat/client/cookieDisabilitati');
    }
</script>

<script type="text/javascript">

    /**
     * La funzione permette di richiedere al server un elenco di utenti o di categorie e poi li dispone sulla pagina.
     * Quali utenti/categorie visualizzare viene stabilito dal numero di pagina che viene passato come parametro.
     * La funzione contatta il server all'URL: /utenti/elencaUsers oppure /categorie/elencaCategorie
     * mediante una richiesta HTTP POST.
     * Il corpo della richiesta contiene il numero di pagina.
     * Se la richiesta va a buon fine allora viene ripulita la sezione utenti/categorie della pagina e vengono poi
     * disposti i nuovi utenti o le nuove categorie forniti dal server, altrimenti viene mostrato un alert di errore.
     * @param pagina Numero di pagina da fornire al server per indicare quali utenti o categorie fornire.
     * @param tipologia Permette di capire se si devono recuperare utenti (vale "utenti") o categorie (vale "categorie").
     */
    function loadElementiPaginati(pagina, tipologia) {
        if (tipologia === "utenti") {
            $.ajax({
                url: "/UniChat/admin/elencaUsers",                 //  richiesta al server
                cache: false,
                type: "POST",                                       //  metodo utilizzato per la richiesta
                data: "pagina=" + pagina,                           //  parametro passato al server in formato chiave-valore
                dataType: "json",                                   //  formato della risposta fornita dal server
                success: function (result) {                        //  se la richiesta ajax va a buon fine allora...
                    var numUtenti = result.length;
                    var posizione = 0;
                    document.getElementById("utente-0").innerHTML = "";         // reset elementi della sezione utenti
                    document.getElementById("utente-1").innerHTML = "";         // ...
                    document.getElementById("utente-2").innerHTML = "";         // ...
                    document.getElementById("utente-3").innerHTML = "";         // ...
                    document.getElementById("utente-4").innerHTML = "";         // ...
                    document.getElementById("utente-5").innerHTML = "";         // ...
                    if (numUtenti !== 0) {                                       // se il server non risponde con un array vuoto allora..
                        while (posizione < numUtenti) {                             // visualizzazione degli utenti forniti dal server
                            var id = "utente-" + posizione;
                            document.getElementById(id).innerHTML = '<!-- Utente --> ' +
                                '<div> ' +
                                '<div class="card border-left-primary shadow mb-2"> ' +
                                '<div class="card-body"> ' +
                                '<div class="utente"> ' +
                                '<div class="row"> ' +
                                '<div class="col"> ' +
                                '<label class="mr-4 m-0"><b>Nome: </b>' + result[posizione].nome + '</label> ' +
                                '<br> ' +
                                '<div class="mr-4 m-0"><b>Email:<br> </b>' + result[posizione].email + '</div> ' +

                                '<label class="mr-4 m-0"><b>Ruolo: </b>' + result[posizione].ruolo + '</label> ' +
                                '</div> ' +
                                '<div class="col"> ' +
                                '<label class="mr-4 m-0"><b>Cognome: </b>' + result[posizione].cognome + '</label> ' +
                                '<br> ' +
                                '<label class="mr-4 m-0"><b>Corso di studio: </b>' + result[posizione].corsoStudio + '</label> ' +
                                '<br> ' +
                                '<label class="mr-4 m-0" id="categoriaModerata-' + posizione + '"><b>Categoria Moderata:</b> - </label> ' +
                                '</div> ' +
                                '</div> ' +
                                '<div class="row"> ' +
                                '<div class="col"> ' +
                                '<br> ' +
                                '<div class="row" style="justify-content: center">' +
                                '<!-- Pulsante a tendina selezione categoria moderata --> ' +
                                '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuCategoria2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                                'Assegna Categoria ' +
                                '</button>' +
                                '<!-- Tendina --> ' +
                                '<div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton" style=""> ' +
                                '<a class="dropdown-item" href="/UniChat/admin/rimuoviModeratore/'+result[posizione].id+'">RIMUOVI MODERATORE</a>'+
                                '{section name=indice loop=$elencoCategorie} <a class="dropdown-item" href="/UniChat/admin/aggiungiModeratore/'+result[posizione].id+'/{$elencoCategorie[indice]->getId()}">{$elencoCategorie[indice]->getNome()}</a>{/section}' +
                                '</div> ' +
                                '<a href="/UniChat/admin/rimuoviUtente/'+ result[posizione].id +'" class="btn btn-danger btn-icon-split" style="margin-left:5px"> ' +
                                '<span class="icon text-white-50"> ' +
                                '<i class="fas fa-trash text-white"></i> ' +
                                '</span> ' +
                                '</a> ' +
                                '</div>' +
                                '</div> ' +
                                '</div>' +
                                '</div> ' +
                                '</div> ' +
                                '</div>' +
                                '</div>' +
                                '<!-- Fine Utente -->';
                            /*
                             * Se l'utente caricato riveste il ruolo di admin o di moderatore, allora viene visualizzato il nome della categoria che gestiscono.
                             * Se l'utente non riveste uno di questi ruoli allora non gestisce nessuna categoria e quindi viene messo il valore "-".
                             */
                            if (result[posizione].ruolo == 'Admin' || result[posizione].ruolo == 'Moderatore') {
                                var identificativo = "categoriaModerata-" + posizione;
                                document.getElementById(identificativo).innerHTML = '<b>Categoria Moderata: </b>' + result[posizione].categoriaModerata;
                            } else {
                                var identificativo = "categoriaModerata-" + posizione;
                                document.getElementById(identificativo).innerHTML = '<b>Categoria Moderata: </b> -';
                            }

                            posizione++;
                        }
                    } else { // se il server fornisce un array vuoto allora viene visualizzato un messaggio che indica la mancanza di utenti iscritti.
                        document.getElementById("utente-0").innerHTML = "Al momento non vi sono utenti.";
                    }

                },
                error: function (result) {                          //  se la richiesta ajax non va a buon fine allora...
                    document.getElementById("utente-0").innerHTML = '<div class="alert alert-danger" role="alert">' +
                        '<b>ERRORE! </b>Caricamento degli utenti fallito.' +
                        '</div>';
                }
            });
        } else if (tipologia === "categorie") {
            $.ajax({
                url: "/UniChat/admin/elencaCategoriePaginate",                 //  richiesta al server
                cache: false,
                type: "POST",                                       //  metodo utilizzato per la richiesta
                data: "pagina=" + pagina,                           //  parametro passato al server in formato chiave-valore
                dataType: "json",                                   //  formato della risposta fornita dal server
                success: function (result) {                        //  se la richiesta ajax va a buon fine allora...
                    var numCategorie = result.length;
                    var posizione = 0;
                    document.getElementById("categoria-0").innerHTML = "";         // reset elementi della sezione categorie
                    document.getElementById("categoria-1").innerHTML = "";         // ...
                    document.getElementById("categoria-2").innerHTML = "";         // ...
                    document.getElementById("categoria-3").innerHTML = "";         // ...
                    document.getElementById("categoria-4").innerHTML = "";         // ...
                    document.getElementById("categoria-5").innerHTML = "";         // ...
                    if(numCategorie !== 0) {                                       // se il server non risponde con un array vuoto allora..
                        while (posizione < numCategorie) {                             // visualizzazione delle categorie fornite dal server
                            var id = "categoria-" + posizione;
                            document.getElementById(id).innerHTML = '<div>' +
                                '<div class="card border-left-secondary shadow mb-2 card-header-actions">' +
                                '<div class="card-header text-danger font-weight-bold">'+result[posizione].nome+'<div>' +
                                '<a class="btn btn-danger btn-icon mr-2" href="/UniChat/admin/rimuoviCategoria/'+result[posizione].id+'" tyle="color: #fff">' +
                                '<i class="fa fa-trash" aria-hidden="true"></i>' +
                                '</a>' +
                                '</div>' +
                                '</div>' +
                                '<div class="card-body">' +
                                '<div class="utente">' +
                                '<div class="row">' +
                                '<div class="col">' +
                                '<div id="moderatore-'+id+'"><b>Moderatore: </b>- [-]</div>' +
                                '<div id="numero-threads-'+id+'"><b>N?? Thread: </b>-</div>' +
                                '<div><b>Descrizione: </b>'+result[posizione].descrizione+'</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';

                            /*
                             * Recupero delle informazioni sul moderatore della categoria recuperata, se presente.
                             */
                            visualizzaNomeEmailModeratore(parseInt(result[posizione].id), posizione);

                            /*
                             * Recupera il numero di thread presenti nella categoria recuperata.
                             */
                            contaThreads(parseInt(result[posizione].id), posizione);
                            posizione++;
                        }
                    } else { // se il server fornisce un array vuoto allora viene visualizzato un messaggio che indica la mancanza di utenti iscritti.
                        document.getElementById("categoria-0").innerHTML = "Al momento non vi sono categorie.";
                    }

                },
                error: function (result) {                          //  se la richiesta ajax non va a buon fine allora...
                    document.getElementById("categoria-0").innerHTML = '<div class="alert alert-danger" role="alert">' +
                        '<b>ERRORE! </b>Caricamento delle categorie fallito.' +
                        '</div>';
                }
            });
        }
    }

    /**
     * Recupera dal server le informazioni sul moderatore di una categoria di cui viene fornito in ingresso l'identificativo.
     * La funzione contatta il server all'URL: /categorie/visualizzaModeratoreCategoria ed esegue una richiesta HTTP POST.
     * @param categoriaID Identificativo della categoria di cui si vuole conoscere il moderatore.
     * @param posizione Indica la posizione nella sezione delle categorie, in cui si devono disporre le informazioni recuperate.
     */
    function visualizzaNomeEmailModeratore(categoriaID, posizione) {
        $.ajax({
            url: "/UniChat/categorie/visualizzaModeratoreCategoria",                 //  richiesta al server
            cache: false,
            type: "POST",                                                           //  metodo utilizzato per la richiesta
            data: "categoriaID=" + categoriaID,                                     //  parametro passatto al server in formato chiave-valore
            dataType: "json",                                                       //  formato della risposta fornita dal server
            success: function (result) {                                            //  se la richiesta ajax va a buon fine allora...
                var id = "moderatore-categoria-" + posizione;
                document.getElementById(id).innerHTML = '<b>Moderatore: </b>' + result.nome + ' ' + result.cognome + ' [' + result.email + ']';
            },
            error: function (result) {                          //  se la richiesta ajax non va a buon fine allora...
                alert("ERRORE! Caricamento dati moderatore categoria fallito.");
            }
        })
    }

    /**
     * Recupera dal server il numero di threads presenti in una categoria di cui viene fornito in ingresso l'identificativo.
     * La funzione contatta il server all'URL: /categorie/contaThreadsCategoria ed esegue una richiesta HTTP POST.
     * @param categoriaID Identificativo della categoria di cui si vuole conoscere il numero di threads.
     * @param posizione Indica la posizione nella sezione delle categorie, in cui si devono disporre le informazioni recuperate.
     */
    function contaThreads(categoriaID, posizione) {
        $.ajax({
            url: "/UniChat/categorie/contaThreadsCategoria",                //  richiesta al server
            cache: false,
            type: "POST",                                                   //  metodo utilizzato per la richiesta
            data: "categoriaID=" + categoriaID,                             //  parametro passatto al server in formato chiave-valore
            dataType: "json",                                               //  formato della risposta fornita dal server
            success: function (result) {                                    //  se la richiesta ajax va a buon fine allora...
                var id = "numero-threads-categoria-" + posizione;
                document.getElementById(id).innerHTML = '<b>N?? Thread: </b>' + result.numeroThreads;
            },
            error: function (result) {                                      //  se la richiesta ajax non va a buon fine allora...
                alert("ERRORE! Caricamento numero threads fallito.");
            }
        });
    }

    /**
     * Funzione con cui ?? possibile cambiare il contenuto dell'attributo class dell'elemento HTML fornito in ingresso.
     * Questa funzione si usa per i "bottoni" della sezione navigazione e permette di modificare l'attributo class
     * affinch?? ci sia "navigazione-utenti active" o "navigazione-categorie active" (dipende dal valore assunto da
     * tipologia) sul "bottone" recante il numero di pagina, cos?? facendo risulter?? evidenziato. Prima di eseguire
     * questa operazione, viene resettato l'attributo class dell'elemento che rappresenta la pagina corrente in modo
     * tale che il suo valore sia "navigazione-utenti" o "navigazione-categorie", cos?? facendo non risulter?? pi??
     * evidenziato.
     * @param elemento Del DOM di cui si deve cambiare l'attributo class.
     * @param tipologia Indica se si deve cambiare class della navigazione utenti (vale "utenti") o di quella delle
     * categorie (vale "categorie").
     */
    function cambiaClass(elemento, tipologia){
        if (tipologia === 'utenti') {
            var elementoPaginaCorrente = document.getElementsByClassName("navigazione-utenti active");
            elementoPaginaCorrente[0].className = "navigazione-utenti";
            elemento.className = "navigazione-utenti active";
        } else if (tipologia === 'categorie') {
            var elementoPaginaCorrente = document.getElementsByClassName("navigazione-categorie active");
            elementoPaginaCorrente[0].className = "navigazione-categorie";
            elemento.className ="navigazione-categorie active";
        }
    }

    /**
     *  Funzione che permette di visualizzare gli utenti o le categorie della pagina successiva rispetto a quella attuale.
     *  La funzione verifica il contenuto dell'elemento HTML avente come valore dell'attributo class la stringa
     *  "navigazione-utenti active" o "navigazione-categorie active" (in base alla tipologia fornita in ingresso),
     *  questo ?? il numero della pagina corrente. Tale numero viene poi incrementato e si procede
     *  ad assegnare "navigazione active" all'attributo class dell'elemento avente come id il valore della pagina
     *  successiva (pagina corrente + 1), ad assegnare "navigazione" all'attributo class dell'elemento HTML della
     *  pagina corrente ed infine viene chiamata la funzione per il caricamento dei nuovi utenti.
     *  La funzione richiede due parametri, uno ?? il numero dell'ultima pagina visualizzabile e viene utilizzato per
     *  evitare che si vada avanti pi?? del dovuto. L'altro ?? la tipologia ed indica se ci si sta riferendo alla
     *  navigazione degli utenti o delle categorie.
     * @param ultimaPagina Numero di pagina dell'ultima pagina.
     * @param tipologia Indica se si deve cambiare la pagina della navigazione utenti (vale "utenti") o di quella delle
     * categorie (vale "categorie").
     */
    function avanti(ultimaPagina, tipologia) {
        if (tipologia === 'utenti') {
            var paginaCorrente = document.getElementsByClassName("navigazione-utenti active")[0];
            var paginaSuccessiva = parseInt(paginaCorrente.innerHTML) + 1;
            if (paginaSuccessiva <= parseInt(ultimaPagina)) {
                document.getElementById('pagina-utenti-' + paginaSuccessiva).className = "navigazione-utenti active";
                paginaCorrente.className = "navigazione-utenti";
                loadElementiPaginati(paginaSuccessiva, 'utenti');
            }
        } else if (tipologia === 'categorie') {
            var paginaCorrente = document.getElementsByClassName("navigazione-categorie active")[0];
            var paginaSuccessiva = parseInt(paginaCorrente.innerHTML) + 1;
            if (paginaSuccessiva <= parseInt(ultimaPagina)){
                document.getElementById('pagina-categorie-'+paginaSuccessiva).className = "navigazione-categorie active";
                paginaCorrente.className="navigazione-categorie";
                loadElementiPaginati(paginaSuccessiva, 'categorie');
            }
        }

    }

    /**
     *  Funzione che permette di visualizzare gli utenti o le categorie della pagina precedente rispetto a quella attuale.
     *  La funzione verifica il contenuto dell'elemento HTML avente come valore dell'attributo class la stringa
     *  "navigazione-utenti active" o "navigazione-categorie active" (in base alla tipologia fornita in ingresso),
     *  questo ?? il numero della pagina corrente. Tale numero viene poi decrementato e si procede
     *  ad assegnare "navigazione active" all'attributo class dell'elemento avente come id il valore della pagina
     *  precedente (pagina corrente - 1), ad assegnare "navigazione" all'attributo class dell'elemento HTML della
     *  pagina corrente ed infine viene chiamata la funzione per il caricamento dei nuovi utenti.
     *  La funzione richiede due parametri, il primo ?? il numero della prima pagina visualizzabile e viene utilizzato per
     *  evitare che si vada indietro pi?? del dovuto. Il secondo ?? la tipologia ed indica se ci si sta riferendo alla
     *  navigazione degli utenti o delle categorie.
     * @param primaPagina Numero di pagina della prima pagina.
     * @param tipologia Indica se si deve cambiare la pagina della navigazione utenti (vale "utenti") o di quella delle
     * categorie (vale "categorie").
     */
    function indietro(primaPagina, tipologia) {
        if (tipologia === 'utenti') {
            var paginaCorrente = document.getElementsByClassName("navigazione-utenti active")[0];
            var paginaPrecedente = parseInt(paginaCorrente.innerHTML) - 1;
            if (paginaPrecedente >= parseInt(primaPagina)) {
                document.getElementById('pagina-utenti-' + paginaPrecedente).className = "navigazione-utenti active";
                paginaCorrente.className = "navigazione-utenti";
                loadElementiPaginati(paginaPrecedente, 'utenti');
            }
        } else if (tipologia === 'categorie') {
            var paginaCorrente = document.getElementsByClassName("navigazione-categorie active")[0];
            var paginaPrecedente = parseInt(paginaCorrente.innerHTML) - 1;
            if (paginaPrecedente >= parseInt(primaPagina)){
                document.getElementById('pagina-categorie-'+paginaPrecedente).className = "navigazione-categorie active";
                paginaCorrente.className="navigazione-categorie";
                loadElementiPaginati(paginaPrecedente, 'categorie');
            }
        }
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
     * @param elemento Elemento del DOM rappresentante la categoria scelta per la ricerca.
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