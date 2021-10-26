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
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

<!-- Inizio del Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Logo sito e Sidebar -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="home.html">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-laugh-wink"></i>
            </div>
            <div class="sidebar-brand-text mx-3">UniChat <sup>1.0</sup></div>
        </a>

        <!-- Divisore -->
        <hr class="sidebar-divider my-0">


        <!-- Menu laterale categorie -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
               aria-expanded="true" aria-controls="collapsePages">
                <i class="fas fa-fw fa-folder"></i>
                <span>Categorie</span>
            </a>
            <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="disim.html">DISIM</a>
                    <a class="collapse-item" href="diiie.html">DIIIE</a>
                    <a class="collapse-item" href="diceaa.html">DICEAA</a>
                    <a class="collapse-item" href="dsfc.html">DSFC</a>
                    <a class="collapse-item" href="mesva.html">MESVA</a>
                    <a class="collapse-item" href="discab.html">DISCAB</a>
                    <a class="collapse-item" href="dsu.html">DSU</a>
                    <a class="collapse-item" href="mista.html">ALTRO</a>
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

                    <form method="get" action="home.html"
                          class="d-none d-sm-inline-block form-inline mr-0 ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">

                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 5px 0px 0px 5px">
                                    Filtra
                                </button>
                                <div class="dropdown-menu animated&#45;&#45;fade-in" aria-labelledby="dropdownMenuButton" style="">
                                    <label class="filtro-categorie dropdown-item" id="0-categoria" onclick="seleziona(this)">TUTTE</label>
                                    <label class="filtro-categorie dropdown-item" id="2-categoria" onclick="seleziona(this)">DISIM</label>
                                    <label class="filtro-categorie dropdown-item" id="3-categoria" onclick="seleziona(this)">DIIIE</label>
                                    <label class="filtro-categorie dropdown-item" id="4-categoria" onclick="seleziona(this)">DICEAA</label>
                                    <label class="filtro-categorie dropdown-item" id="5-categoria" onclick="seleziona(this)">DSFC</label>
                                    <label class="filtro-categorie dropdown-item" id="6-categoria" onclick="seleziona(this)">MESVA</label>
                                    <label class="filtro-categorie dropdown-item" id="7-categoria" onclick="seleziona(this)">DISCAB</label>
                                    <label class="filtro-categorie dropdown-item" id="8-categoria" onclick="seleziona(this)">DSU</label>
                                    <label class="filtro-categorie dropdown-item" id="1-categoria" onclick="seleziona(this)">Mista</label>
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
                                <label class="dropdown-item" id="2cat" onclick="seleziona(this)">DISIM</label>
                                <label class="dropdown-item" id="3cat" onclick="seleziona(this)">DIIIE</label>
                                <label class="dropdown-item" id="4cat" onclick="seleziona(this)">DICEAA</label>
                                <label class="dropdown-item" id="5cat" onclick="seleziona(this)">DSFC</label>
                                <label class="dropdown-item" id="6cat" onclick="seleziona(this)">MESVA</label>
                                <label class="dropdown-item" id="7cat" onclick="seleziona(this)">DISCAB</label>
                                <label class="dropdown-item" id="8cat" onclick="seleziona(this)">DSU</label>
                                <label class="dropdown-item" id="1cat" onclick="seleziona(this)">Mista</label>
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
                            <form method="get" action="home.html" class="form-inline mr-auto w-100 navbar-search">
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
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
                                <img class="img-profile rounded-circle"
                                     src="../img/undraw_profile.svg">
                            </a>

                            <!-- Tendina -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                 aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="mio_profilo.html">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profilo
                                </a>

                                <a class="dropdown-item" href="pannello-di-controllo.html">
                                    <i class="fas fa-tools fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Pannello di Controllo
                                </a>

                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </ul>
            </nav>
            <!-- Fine della Topbar -->

















            <!-- Inizio del Page Content -->
            <div class="container-fluid">

                <!-- Intestazione della pagina -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Home</h1>
                </div>

                <!-- Contenitore Chat e Thread -->
                <div class="row">

                    <!-- Chat -->
                    <div class="col-lg">
                        <div class="card border-bottom-info mb-2">
                            <div class="card-header bg-info" >

                                <!-- Titolo e icona contenitore -->

                                <div class="row justify-content-center" >

                                    <img src="/Immagini/icona_chat.png" style="max-width: 64px; margin-right: 10px">
                                    <h4 class="py-0 text-white mt-3"><b>Chat</b></h4>

                                </div>



                            </div>

                            <div class="card-body">

                                <!-- Contenitore scrollable -->
                                <div style = "width: auto; height: 500px; line-height: 3em; overflow-y:scroll; padding: 5px;">



                                    <!-- Messaggio -->
                                    <div class="card shadow mb-2">

                                        <!-- Utente -->
                                        <div class="card-header mr-0 d-flex flex-row align-items-center justify-content-between">
                                            <h6 class="m-0 font-weight-bold text-secondary">Franco Bollo</h6>


                                            {if $moderatoreAdmin eq true}
                                            <!-- Bottone elimina messaggio -->
                                            <a href="#" class="btn btn-danger btn-circle btn-sm" onclick="presentaAlert()">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {/if}

                                        </div>

                                        <!-- Testo -->
                                        <div class="card-body py-2">Messaggio ricevuto</div>

                                        <!-- Data invio messaggio -->
                                        <div class="container my-auto py-1">
                                            <div class="copyright text-center my-auto">
                                                <span>01/03/2020 17:23</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fine messaggio -->


                                </div>
                                <!-- Fine contenitore scrollable -->

                                {if $loggato eq true}
                                <!-- Form invio messaggio -->
                                <div class="row mb-2">

                                    <!-- Inserimento testo -->
                                    <div class="col-sm-9 mb-3 mb-sm-0">
                                        <input type="text" class="form-control" id="messaggio" placeholder="Messaggio">
                                    </div>

                                    <!-- Pulsante invio -->
                                    <div class="col-sm-3">
                                        <a href="/UniChat/chat/creaMessaggio" class="btn btn-success btn-icon-split">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-arrow-right"></i>
                                            </span>
                                            <span class="text">Invia</span>
                                        </a>
                                    </div>
                                </div>
                                <!-- Fine form invio messaggio -->
                                {/if}

                            </div>
                        </div>
                    </div>
                    <!-- Fine Chat -->




                    <!-- Thread più popolari -->
                    <div class="col-lg">

                        <div class="card mb-4 border-bottom-primary">
                            <div class="card-header bg-primary" >

                                <!-- Titolo e icona contenitore -->

                                <div class="row justify-content-center" >

                                    <img src="/Immagini/post_popolari.png" style="max-width: 64px; margin-right: 10px">
                                    <h4 class="py-0 text-white mt-3"><b>Thread Popolari</b></h4>

                                </div>

                            </div>

                            <div class="card-body">

                                <!-- Thread più votati -->

                                <div class="card shadow mb-4">

                                    <!-- Titolo contenitore -->
                                    <a href="#piuvotati" class="d-block card-header py-3 collapsed" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="piuvotati">
                                        <h6 class="m-0 font-weight-bold text-primary">Thread più votati</h6>
                                    </a>


                                    <div class="collapse show" id="piuvotati" style="">
                                        <div class="card-body">

                                            <!--Carousel Wrapper-->
                                            <div id="multi-item-piuvotati" class="carousel slide carousel-multi-item" data-ride="carousel">

                                                <!--Controls-->
                                                <div class="row justify-content-center mb-3">

                                                    <div class="controls-top">
                                                        <a class="btn-floating" href="#multi-item-piuvotati" data-slide="prev"><i class="fas fa-chevron-left"></i></a>
                                                        <a class="btn-floating" href="#multi-item-piuvotati" data-slide="next"><i class="fas fa-chevron-right"></i></a>
                                                    </div>
                                                    <!--/.Controls-->
                                                </div>

                                                <!--Indicators-->
                                                <ol class="carousel-indicators">
                                                    <li data-target="#multi-item-piuvotati" data-slide-to="0" class="active"></li>
                                                    <li data-target="#multi-item-piuvotati" data-slide-to="1"></li>
                                                    <li data-target="#multi-item-piuvotati" data-slide-to="2"></li>

                                                </ol>
                                                <!--/.Indicators-->

                                                <!-- Contenitore slides -->
                                                <div class="carousel-inner" role="listbox">

                                                    {foreach from=$treadsConValutazionePiuAlta item=thread}
                                                    <!-- Inizio prima slide -->
                                                    <div class="carousel-item active">

                                                        <div class="row ml-1 mb-2 border-bottom-primary text-primary" >
                                                            <h4>Categoria: <b>{$thread->getCategoria()}</b></h4>
                                                        </div>


                                                        <div class="card mb-2">
                                                            <div class="card-body">
                                                                <h4 class="card-title text-primary font-weight-bold">{$thread->getTitolo()}<sup> {$thread->getValutazione()} <i class="fas fa-star"></i></sup></h4>
                                                                <h6 class="font-italic">di <b>{$thread->getAutoreThread()->getNome()} {$thread->getAutoreThread()->getCognome()}</b></h6>
                                                                <p class="card-text">{$thread->getTesto()}</p>

                                                                <a class="btn btn-primary">Leggi tutto</a>
                                                            </div>
                                                        </div>


                                                    </div>
                                                    <!-- Fine prima slide -->
                                                    {/foreach}


                                                </div>
                                                <!-- Fine contenitore slides -->

                                            </div>

                                        </div>
                                    </div>


                                </div>

                                <!-- fine thread più votati-->

                                <!-- Thread con più risposte -->

                                <div class="card shadow mb-4">

                                    <!-- Titolo contenitore -->
                                    <a href="#piurisposte" class="d-block card-header py-3 collapsed" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="piurisposte">
                                        <h6 class="m-0 font-weight-bold text-primary">Thread con più risposte</h6>
                                    </a>

                                    <!-- Thread con più risposte -->
                                    <div class="collapse show" id="piurisposte" style="">
                                        <div class="card-body">

                                            <!--Carousel Wrapper-->
                                            <div id="multi-item-piurisposte" class="carousel slide carousel-multi-item" data-ride="carousel">

                                                <!--Controls-->
                                                <div class="row justify-content-center mb-3">

                                                    <div class="controls-top">
                                                        <a class="btn-floating" href="#multi-item-piurisposte" data-slide="prev"><i class="fas fa-chevron-left"></i></a>
                                                        <a class="btn-floating" href="#multi-item-piurisposte" data-slide="next"><i class="fas fa-chevron-right"></i></a>
                                                    </div>
                                                    <!--/.Controls-->
                                                </div>

                                                <!--Indicators-->
                                                <ol class="carousel-indicators">
                                                    <li data-target="#multi-item-piurisposte" data-slide-to="0" class="active"></li>
                                                    <li data-target="#multi-item-piurisposte" data-slide-to="1"></li>
                                                    <li data-target="#multi-item-piurisposte" data-slide-to="2"></li>

                                                </ol>
                                                <!--/.Indicators-->

                                                <!-- Inizio contenitore slides -->
                                                <div class="carousel-inner" role="listbox">

                                                    {foreach from=$threadsConPiuRisposte item=thread}
                                                    <!-- Inizio prima slide -->
                                                    <div class="carousel-item active">

                                                        <div class="row ml-1 mb-2 border-bottom-primary text-primary" >
                                                            <h4>Categoria: <b>{$thread->getCategoria()}</b></h4>
                                                        </div>


                                                        <div class="card mb-2">
                                                            <div class="card-body">
                                                                <h4 class="card-title text-primary font-weight-bold">{$thread->getTitolo()}<sup> {$thread->getValutazione()} <i class="fas fa-comment"></i></sup></h4>
                                                                <h6 class="font-italic">di <b>{$thread->getAutoreThread()->getNome()} {$thread->getAutoreThread()->getCognome()}</b></h6>
                                                                <p class="card-text">{$thread->getTesto()}</p>

                                                                <a class="btn btn-primary">Leggi tutto</a>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <!-- Fine prima slide -->
                                                    {/foreach}


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
                <a class="btn btn-primary" href="login.html">Logout</a>
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




<!-- Bootstrap core JavaScript-->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="../vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script src="../js/demo/chart-area-demo.js"></script>
<script src="../js/demo/chart-pie-demo.js"></script>

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

</html>