<?php
require_once __DIR__ . "\..\utility.php";
require_once "VSmarty.php";
require_once "VPage.php";

class VCategoria 

{

	private Smarty $smarty;
	const NUMERO_THREAD_PER_PAGINA = 6;


    public function __construct() {

        $this->smarty = VSmarty::start();

    }

    /**
	 * Imposta l'id della Categoria per le url della paginazione.
	 */
	public function setIDCategoria(ECategoria $categoria): void {

		$this->smarty->assign('idCategoria', $categoria->getID());


	}



	/**
	 * Imposta la visualizzazione dell'icona della categoria, il suo nome, la sua 
	 * descrizione.
	 */
	public function setIntestazionePagina(ECategoria $categoria): void {

		$this->smarty->assign('iconaCategoria', $categoria->getIcona());
		$this->smarty->assign('nomeCategoria', $categoria->getNome());
		$this->smarty->assign('descrizioneCategoria', $categoria->getDescrizione());

	}

	/**
	 * Imposta la visualizzazione dei threads appartenenti a quella categoria e il 
	 * collegamento alla pagina di visualizzazione dei singoli thread.
	 */
	public function setThreads(array $threads): void {


		$this->smarty->assign('listaThread', $threads);

	}

	/**
	 * Imposta la visualizzazione della sezione contenente i numeri di pagina per 
	 * richiamare di volta in volta gli altri threads.
	 */
	public function setPaginazione(int $numeroThread): void {

		$numeroPagine = ceil($numeroThread/self::NUMERO_THREAD_PER_PAGINA);
       

		$this->smarty->assign('numeroPagine', $numeroPagine);


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
	 * Imposta la visualizzazione del bottone per creare un nuovo thread da pubblicare nella medesima sezione delle
	 * categorie. Il bottone deve essere visualizzato solo se l'utente è loggato (true).
	 * La categoria viene fornita solo se loggato vale true e serve a specificare dove dovrà essere pubblicato
	 * il thread (serve per generare la url autodescrittiva). Se loggato vale false allora categoria vale null.
	 */
	public function setBottoneCreazioneThread(bool $loggato, ?ECategoria $categoria): void {


		if ($loggato) {

			$this->smarty->assign('isLoggato', true);
			$this->smarty->assign('catNuovoThread', $categoria);

		}


	}


	/**
	 * Imposta la visualizzazione di un messaggio di conferma a seguito di una richiesta
     * di rimozione di un thread da parte del moderatore appartenente a quella determinata categoria.
     * A richiesta inviata, si viene reindirizzati sulla pagina della relativa categoria.
	 */
	public function setMessaggioConfermaEliminazioneThread(?bool $esitoOperazione): void {

        $this->smarty->assign('conferma', false);

        if (isset($esitoOperazione)) {
            if ($esitoOperazione) {
                $this->smarty->assign('conferma', true);
                $this->smarty->assign('messaggioConferma', "L'operazione è stata eseguita con successo.");
            }
        }

	}

	/**
	 * Imposta la visualizzazione, nella pagina di ricerca, del nome della categoria in cui si stanno cercando i 
	 * thread. Se null allora vuol dire che si sta cercando in tutte le categorie e quindi non è stato posto un 
	 * filtro.
	 */
	public function setCategoriaRicerca(?ECategoria $categoria): void {

		if (isset($categoria)) {

			$this->smarty->assign('categoria', $categoria);
		}

		else {

			$this->smarty->assign('categoria', "Tutte le categorie.");
		}



	}

	/**
	 * Permette di visualizzare la pagina delle categorie nella sua totalità.
	 */
	 public function showCategoria(): void {



	 	$this->smarty->display('categoria_thread.tpl');


	 }

	 /**
	 * Permette di visualizzare la pagina della ricerca nella sua totalità.
	 */
	 public function showRicerca(): void {


	 	$this->smarty->display('risultati_cerca.tpl');



	 }


}