<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Titolo tab del browser -->
    <title>UniChat - Home</title>

    <!-- Custom fonts for this template-->
    <link href="/UniChat/Template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/UniChat/Template/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Inizio controllo JavaScript abilitato -->
    <noscript><meta http-equiv="refresh" content="0;URL=/UniChat/client/javascriptDisabilitati"></noscript>
    <!-- Fine controllo JavaScript abilitato -->

</head>

<body id="page-top" onload="loadChat()">
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

                    {foreach from=$cate item=c}

                        <a class="collapse-item" href="/UniChat/categorie/visualizzaCategoria/{$c->getID()}/1">{$c->getNome()}</a>

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

                    <form method="get" action="/UniChat/threads/ricerca/1"
                          class="d-none d-sm-inline-block form-inline mr-0 ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">

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

                <!-- Messaggio Errore/Conferma -->
                {if $messaggio == true}

                    <div class="alert alert-{$colore} alert-dismissible fade show" role="alert">
                        {$testo}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                {/if}
                <!-- Fine messaggio -->

                <!-- Intestazione della pagina -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Home</h1>
                </div>

                <!-- Contenitore Chat e Thread -->
                <div class="row">

                    <!-- Chat -->
                    <div class="col-lg">
                        <div class="card border-bottom-info mb-2">

                            <!-- Header Box Chat -->
                            <div class="card-header bg-info" >

                                <div class="row justify-content-center" >

                                    <!-- Icona Chat -->
                                    <img src="/UniChat/Immagini/icona_chat.png" style="max-width: 64px; margin-right: 10px">

                                    <!-- Titolo -->
                                    <h4 class="py-0 text-white mt-3"><b>Chat</b></h4>

                                </div>

                            </div>

                            <div class="card-body">

                                <!-- Contenitore scrollable -->
                                <div id="chatbox" style = "width: auto; height: 500px; line-height: 3em; overflow:auto; padding: 5px;">


                                </div>
                                <!-- Fine contenitore scrollable -->

                                <form>
                                    <input type="text" hidden name="ultimoMessaggio" id="ultimoMessaggio"/>
                                </form>

                                {if $log eq true}
                                    <!-- Form invio messaggio -->
                                    <form class="row mb-2">

                                        <!-- Inserimento testo -->
                                        <div class="col-sm-9 mb-3 mb-sm-0">
                                            <input type="text" autocomplete="off" class="form-control" id="usrmsg" placeholder="Messaggio">
                                        </div>

                                        <!-- Pulsante invio -->
                                        <div class="col-sm-3">
                                            <button type="submit" class="btn btn-success btn-icon-split" id="submitmsg">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-arrow-right"></i>
                                                </span>
                                                <span class="text">Invia</span>
                                            </button>
                                        </div>

                                    </form>
                                    <!-- Fine form invio messaggio -->
                                {/if}

                            </div>
                        </div>
                    </div>
                    <!-- Fine Chat -->




                    <!-- Thread più popolari -->
                    <div class="col-lg">

                        <div class="card mb-4 border-bottom-primary">

                            <!-- Header Box Thread Popolari -->
                            <div class="card-header bg-primary" >

                                <div class="row justify-content-center" >

                                    <!-- Icona Thread Popolari -->
                                    <img src="/UniChat/Immagini/post_popolari.png" style="max-width: 64px; margin-right: 10px">

                                    <!-- Titolo -->
                                    <h4 class="py-0 text-white mt-3"><b>Thread Popolari</b></h4>

                                </div>

                            </div>

                            <div class="card-body">

                                <!-- Thread più votati -->
                                <div class="card shadow mb-4">

                                    <!-- Titolo contenitore -->
                                    <a href="#piuvotati" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="piuvotati">
                                        <h6 class="m-0 font-weight-bold text-primary">Thread più votati</h6>
                                    </a>


                                    <div class="collapse show" id="piuvotati" style="">
                                        <div class="card-body">

                                            <!-- Carousel Wrapper -->
                                            <div id="multi-item-piuvotati" class="carousel slide carousel-multi-item" data-ride="carousel">

                                                <!-- Controls -->
                                                <div class="row justify-content-center mb-3">

                                                    <div class="controls-top">
                                                        <a class="btn-floating" href="#multi-item-piuvotati" data-slide="prev"><i class="fas fa-chevron-left"></i></a>
                                                        <a class="btn-floating" href="#multi-item-piuvotati" data-slide="next"><i class="fas fa-chevron-right"></i></a>
                                                    </div>
                                                    <!--/.Controls-->

                                                </div>


                                                <!-- Contenitore slides -->
                                                <div class="carousel-inner" role="listbox">

                                                    {for $i = 0 to $numeroThreadsValutazionePiuAlta}
                                                        {if $i == 0} <div class="carousel-item active"> {else} <div class="carousel-item"> {/if}
                                                                <div class="row ml-1 mb-2 {if $i==0}border-bottom-primary text-primary{elseif $i==1}border-bottom-success text-success{elseif $i==2}border-bottom-danger text-danger{/if}" >
                                                                    <h4>Categoria: <b>{$threadsConValutazionePiuAlta[$i]->getCategoriaThread()->getNome()}</b></h4>
                                                                </div>


                                                                <div class="card mb-2">
                                                                    <div class="card-body">
                                                                        <h4 class="card-title {if $i==0}text-primary{elseif $i==1}text-success{elseif $i==2}text-danger{/if} font-weight-bold">{$threadsConValutazionePiuAlta[$i]->getTitolo()}<sup> {$threadsConValutazionePiuAlta[$i]->getValutazione()->getTotale()} <i class="fas fa-star"></i></sup></h4>
                                                                        <h6 class="font-italic">di <b>{$threadsConValutazionePiuAlta[$i]->getAutoreThread()->getNome()} {$threadsConValutazionePiuAlta[$i]->getAutoreThread()->getCognome()}</b></h6>
                                                                        <p class="card-text">{$threadsConValutazionePiuAlta[$i]->getTesto()}</p>

                                                                        <a class="btn {if $i==0}btn-primary{elseif $i==1}btn-success{elseif $i==2}btn-danger{/if}" href="/UniChat/threads/visualizzaThread/{$threadsConValutazionePiuAlta[$i]->getID()}">Leggi tutto</a>
                                                                    </div>
                                                                </div>


                                                            </div>
                                                    {/for}


                                                </div>
                                                <!-- Fine contenitore slides -->

                                            </div>

                                        </div>
                                    </div>


                                </div>
                                <!-- Fine thread più votati-->



                                <!-- Thread con più risposte -->
                                <div class="card shadow mb-4">

                                    <!-- Titolo contenitore -->
                                    <a href="#piurisposte" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="piurisposte">
                                        <h6 class="m-0 font-weight-bold text-primary">Thread con più risposte</h6>
                                    </a>

                                    <!-- Thread con più risposte -->
                                    <div class="collapse show" id="piurisposte" style="">
                                        <div class="card-body">

                                            <!-- Carousel Wrapper -->
                                            <div id="multi-item-piurisposte" class="carousel slide carousel-multi-item" data-ride="carousel">

                                                <!-- Controls -->
                                                <div class="row justify-content-center mb-3">

                                                    <div class="controls-top">
                                                        <a class="btn-floating" href="#multi-item-piurisposte" data-slide="prev"><i class="fas fa-chevron-left"></i></a>
                                                        <a class="btn-floating" href="#multi-item-piurisposte" data-slide="next"><i class="fas fa-chevron-right"></i></a>
                                                    </div>
                                                    <!--/.Controls-->

                                                </div>

                                                <!-- Inizio contenitore slides -->
                                                <div class="carousel-inner" role="listbox">

                                                    {for $j = 0 to $numeroThreadsPiuDiscussi}
                                                        {if $j == 0} <div class="carousel-item active"> {else} <div class="carousel-item"> {/if}
                                                            <div class="row ml-1 mb-2 {if $j==0}border-bottom-primary text-primary{elseif $j==1}border-bottom-success text-success{elseif $j==2}border-bottom-danger text-danger{/if}">
                                                                <h4>Categoria: <b>{$threadsConPiuRisposte[$j]->getCategoriaThread()->getNome()}</b></h4>
                                                            </div>
                                                            <div class="card mb-2">
                                                                <div class="card-body">
                                                                    <h4 class="card-title {if $j==0}text-primary{elseif $j==1}text-success{elseif $j==2}text-danger{/if} font-weight-bold">{$threadsConPiuRisposte[$j]->getTitolo()}<sup> {$threadsConPiuRisposte[$j]->contaRisposte()} <i class="fas fa-comment"></i></sup></h4>
                                                                    <h6 class="font-italic">di <b>{$threadsConPiuRisposte[$j]->getAutoreThread()->getNome()} {$threadsConPiuRisposte[$j]->getAutoreThread()->getCognome()}</b></h6>
                                                                    <p class="card-text">{$threadsConPiuRisposte[$j]->getTesto()}</p>

                                                                    <a class="btn {if $j==0}btn-primary{elseif $j==1}btn-success{elseif $j==2}btn-danger{/if}" href="/UniChat/threads/visualizzaThread/{$threadsConPiuRisposte[$j]->getID()}">Leggi tutto</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    {/for}
                                                </div>
                                                <!-- Fine contenitore slides -->

                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <!-- Fine Thread con più risposte -->

                            </div>
                        </div>


                    </div>
                    <!-- Fine Thread più popolari -->

                </div>
                <!-- Fine contenitore Chat e Thread -->

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

<!-- Page level plugins -->
<script src="/UniChat/Template/vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script src="/UniChat/Template/js/demo/chart-area-demo.js"></script>
<script src="/UniChat/Template/js/demo/chart-pie-demo.js"></script>

<!-- Script per verificare se i cookie sono abilitati -->
<script type="text/javascript">
    if (navigator.cookieEnabled === false) {
        window.location.replace('/UniChat/client/cookieDisabilitati');
    }
</script>

<!-- Libreria jquery -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">
    // jQuery Document
    $(document).ready(function () {
        /*
         * Al click sul bottone Invia viene eseguita la seguente funzione.
         * Viene recuperato il contenuto del campo input contenente il testo del nuovo messaggio e mandato con il metodo
         * HTTP POST alla URL riportata (/chat/creaMessaggio).
         */
        $("#submitmsg").click(function () {
            var clientmsg = document.getElementById('usrmsg').value;
            $.post("/UniChat/chat/creaMessaggio", { text: clientmsg });
            document.getElementById('usrmsg').value = "";
            return false;
        });

        /**
         * La funzione permette di aggiornare la chat riportando gli ultimi messaggi pubblicati e gestisce l'auto-scrolling.
         * Si utlizza ajax per eseguire una richiesta al server e poi elaborarne la risposta.
         * Ajax contatta il server con l'url autodescrittiva /chat/aggiornaChat utilizzando il metodo POST.
         * Al server viene fornito l'id dell'ultimo messaggio presente nella chat e il server fornisce un array JSON
         * contenente i messaggi presenti nel database aventi identificativo successivo a quello fornito.
         * La risposta del server viene ripresa e formattata per poi essere mostrata a schermo, inoltre viene aggiornato
         * il campo input contenente l'id dell'ulitmo messaggio.
         */
        function loadLastMessages() {
            var oldscrollHeight = $("#chatbox")[0].scrollHeight; //Scroll height before the request

            var idMessage = document.getElementById('ultimoMessaggio').value;
            $.ajax({
                url: "/UniChat/chat/aggiornaChat",       //  richiesta al server
                cache: false,
                type: "POST",                                       //  metodo utilizzato per la richiesta
                data: "idMessage=" + idMessage,                     //  parametro passato al server in formato chiave-valore
                dataType: "json",                                   //  formato della risposta fornita dal server
                success: function (result) {                        //  se la richiesta ajax va a buon fine allora...
                    if (result.length > 0) {                 //  se l'id del primo elemento restituito dal server è maggiore di quello salvato nella div allora aggiorna la chat,
                        //  altrimenti non fa nulla.
                        var lastId = 0;
                        result.forEach(function (item) {
                            document.getElementById("chatbox").innerHTML += '<!-- Messaggio -->' +
                                '<div class="card shadow mb-2">' +
                                '<!-- Utente -->' +
                                '<div class="card-header mr-0 d-flex flex-row align-items-center justify-content-between">' +
                                '<h6 class="m-0 font-weight-bold text-secondary"><a href="/UniChat/utenti/showProfile/'+item.idAutore+'">'+item.nomeAutore+' '+item.cognomeAutore+'</a></h6>' +
                                '{if $moderatoreAdmin==true} <a href="/UniChat/chat/rimuoviMessaggio/'+item.idMessaggio+'" class="btn btn-danger btn-circle btn-sm" onclick="presentaAlert()"><i class="fas fa-trash"></i></a>{/if}' +
                                '</div>' +
                                '<!-- Testo -->' +
                                '<div class="card-body py-2">'+item.testo+'</div>' +
                                '<!-- Data invio messaggio -->' +
                                '<div class="container my-auto py-1">' +
                                '<div class="copyright text-center my-auto">' +
                                '<span>'+item.data+'</span>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '<!-- Fine messaggio -->';
                            lastId = item.idMessaggio;
                        });
                        document.getElementById('ultimoMessaggio').value = lastId   //  aggiornamento campo input contenente l'id dell'ulitmo messaggio.

                        //Auto-scroll

                        var newscrollHeight = $("#chatbox")[0].scrollHeight; //Scroll height after the request
                        if(newscrollHeight > oldscrollHeight){
                            $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
                        }

                    }
                },
                error: function (result) {                          //  se la richiesta ajax non va a buon fine allora...
                    document.getElementById("chatbox").innerHTML = '<!-- Messaggio errore -->' +
                        '<div class="alert alert-danger" role="alert">' +
                        '<b>ERRORE! </b>Aggiornamento della chat fallito.' +
                        '</div>' +
                        '<!-- Fine messaggio errore -->';
                }
            });
        }

        /*
         * La funzione per l'aggiornamento della pagina viene richiamato ogni 2.5 secondi.
         */
        setInterval (loadLastMessages, 2500);
    });

    /**
     * Funzione per effettuare il primo caricamento della chat.
     * La funzione viene richiamata al caricamento della pagina o ad ogni suo refresh. Il funzionamento è molto analogo
     * a quello di LoadLastMessages().
     */
    function loadChat() {
        $.ajax({
            url: "/UniChat/chat/visualizzaChat",
            cache: false,
            type: "POST",
            dataType: "json",
            success: function (result) {
                var show = '';
                var lastId = 0;
                document.getElementById("chatbox").innerHTML = show;
                result.forEach(function (item){
                    show = show +
                        '<!-- Messaggio -->' +
                        '<div class="card shadow mb-2">' +
                        '<!-- Utente -->' +
                        '<div class="card-header mr-0 d-flex flex-row align-items-center justify-content-between">' +
                        '<h6 class="m-0 font-weight-bold text-secondary"><a href="/UniChat/utenti/showProfile/'+item.idAutore+'">'+item.nomeAutore+' '+item.cognomeAutore+'</a></h6>' +
                        '{if $moderatoreAdmin==true} <a href="/UniChat/chat/rimuoviMessaggio/'+item.idMessaggio+'" class="btn btn-danger btn-circle btn-sm" onclick="presentaAlert()"><i class="fas fa-trash"></i></a>{/if}' +
                        '</div>' +
                        '<!-- Testo -->' +
                        '<div class="card-body py-2">'+item.testo+'</div>' +
                        '<!-- Data invio messaggio -->' +
                        '<div class="container my-auto py-1">' +
                        '<div class="copyright text-center my-auto">' +
                        '<span>'+item.data+'</span>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<!-- Fine messaggio -->';
                    lastId = item.idMessaggio;
                });
                document.getElementById("chatbox").innerHTML = show;
                if (lastId !== 0) {
                    $("#ultimoMessaggio").val(lastId);
                } else {
                    ultimoMessId();
                }
            },
            error: function (result) {
                document.getElementById("chatbox").innerHTML = '<!-- Messaggio errore -->' +
                    '<div class="alert alert-danger" role="alert">' +
                    '<b>ERRORE! </b>Caricamento della chat fallito.' +
                    '</div>' +
                    '<!-- Fine messaggio errore -->';
            }
        });
    }

    /**
     * Permette di recuperare l'id associato all'ultimo messaggio pesente nella base dati e risulta utile quando la
     * chat risulta essere vuota in quanto non ci sono messaggi nelle ultime 24 ore.
     * Nell'apposito input viene riportato l'ultimo id ottenuto dal server, altrimenti si ottiene un messaggio di errore.
     * La funzione contatta il server all'url /chat/ultimoMessaggio con una HTTP POST.
     */
    function ultimoMessId() {
        $.ajax({
            url: "/UniChat/chat/ultimoIdMessaggio",
            cache: false,
            type: "POST",
            dataType: "json",
            success: function (result) {
                $("#ultimoMessaggio").val(result.messID);
            },
            error: function (){
                document.getElementById("chatbox").innerHTML = '<!-- Messaggio errore -->' +
                    '<div class="alert alert-danger" role="alert">' +
                    '<b>ERRORE! </b>Caricamento della chat fallito.' +
                    '</div>' +
                    '<!-- Fine messaggio errore -->';
            }
        });

    }

</script>


<script type="text/javascript">

    /**
     * Funzione che permette di gestire la selezione della categoria con cui filtrare la ricerca.
     * La funzione ricerca l'elemento del menu di selezione che abbia l'attributo class con valore 'filtro-categorie
     * dropdown-item active' e procede ad assegnargli il valore 'filtro-categorie dropdown-item', così facendo la
     * categoria precedentemente scelta, viene deselezionata.
     * La funzione prende in ingresso l'elemento HTML su cui si clicca per selezionare la categoria e all'attributo class
     * di questo elemento viene assegnato il valore 'filtro-categorie dropdown-item active' e così facendo
     * risulterà evidenziato.
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