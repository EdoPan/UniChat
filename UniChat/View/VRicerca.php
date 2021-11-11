<?php
require_once __DIR__ . "\..\utility.php";

class VRicerca
{

    const NUMERO_THREAD_PER_PAGINA = 6;

    private Smarty $smarty;

    public function __construct() {

        $this->smarty = VSmarty::start();

    }

    public function getSmarty(): Smarty
    {
        return $this->smarty;
    }

    public function getValori(): array {

        $result = array();

        if ($_GET['testoricerca'] != "") {
            $result['testoricerca'] = filter_var($_GET['testoricerca'], FILTER_SANITIZE_SPECIAL_CHARS);
        }
        if ($_GET['categoriaID'] != "") {
            $result['categoriaID'] = (int)filter_var($_GET['categoriaID'], FILTER_SANITIZE_SPECIAL_CHARS);
        }


        return $result;
    }

    /**
     * Imposta la visualizzazione, nella pagina di ricerca, del nome della categoria in cui si stanno cercando i
     * thread. Se null allora vuol dire che si sta cercando in tutte le categorie e quindi non è stato posto un
     * filtro.
     */
    public function setCategoriaRicerca(string $titolo, ?ECategoria $categoria): void {

        $this->smarty->assign('titoloCercato', $titolo);
        if (isset($categoria)) {
            $this->smarty->assign('categoriaNome', $categoria->getNome());
        }

        else {

            $this->smarty->assign('categoriaNome', "Tutte le categorie.");
        }

    }

    /**
     * Imposta la visualizzazione dei threads appartenenti a quella categoria e il
     * collegamento alla pagina di visualizzazione dei singoli thread.
     */
    public function setThreads(array $threads): void {


        $this->smarty->assign('listathread', $threads);
        $this->smarty->assign('posizioneMax', count($threads));
        $this->smarty->assign('maxrighe', ceil(count($threads)/2));

    }

    /**
     * Imposta la visualizzazione della sezione contenente i numeri di pagina per
     * richiamare di volta in volta gli altri threads.
     */
    public function setPaginazione(int $numeroThread, int $categoriaID, string $titolo): void {

        $numeroPagine = ceil($numeroThread/self::NUMERO_THREAD_PER_PAGINA);
        $this->smarty->assign('numeroPagine', $numeroPagine);
        $this->smarty->assign('categoriaIdRicerca', $categoriaID);
        $this->smarty->assign('testoRicerca', $titolo);


    }


    /**
     * Imposta la visualizzazone del bottone relativo alla pagina che si sta visitando
     * nella paginazione dei Thread.
     *
     */


    public function setActivePage(int $numeroPagina): void {


        $this->smarty->assign('activePage', $numeroPagina);


    }

    /**
     * Permette di visualizzare la pagina della ricerca nella sua totalità.
     */
    public function showRicerca(): void {

        $this->smarty->display('risultati-cerca.tpl');

    }


}