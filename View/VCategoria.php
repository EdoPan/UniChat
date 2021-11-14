<?php
require_once __DIR__ . "\..\utility.php";
require_once "VSmarty.php";
//require_once "VPage.php";

/**
 * Classe che gestisce la visualizzazione della pagina di Categoria dei Thread.
 */
class VCategoria 

{

    /**
     * Costante on success per setMessaggiErroreConferma.
     */
    const SUCCESS = "L'operazione è andata a buon fine!";

    /**
     * Costante on error per setMessaggiErroreConferma.
     */
    const ERROR = "OPS.. sembra si sia verificato un errore.";

    /**
     * Costante per non visualizzare l'alert.
     */
    const NULLA = "";

    const NUMERO_THREAD_PER_PAGINA = 6;

	private Smarty $smarty;



    public function __construct() {

        $this->smarty = VSmarty::start();

    }

    public function getSmarty(): Smarty
    {
        return $this->smarty;
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

        $this->smarty->assign('iTipo', $categoria->getIcona()['tipo']);
		$this->smarty->assign('iImmagine', $categoria->getIcona()['immagine']);
		$this->smarty->assign('nomeCategoria', $categoria->getNome());
		$this->smarty->assign('descrizioneCategoria', $categoria->getDescrizione());

	}

	/**
	 * Imposta la lista dei threads da visualizzare appartenenti a quella categoria e il
	 * collegamento alla pagina di visualizzazione dei singoli thread.
	 */
	public function setThreads(array $threads): void {


		$this->smarty->assign('listathread', $threads);
        $this->smarty->assign('posizioneMax', count($threads));
        $this->smarty->assign('maxrighe', ceil(count($threads)/2));

	}

	/**
	 * Imposta la paginazione dei threads di una categoria. Sapendo che ogni poagina mostra al massimo
     * un numero pre stabilito di threads, recuperando il numero totale dei threads da visualizzare,
     * calcolo il numero di pagine necessarie.
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
	 * Imposta la visualizzazione del bottone per creare un nuovo thread da pubblicare nella medesima categoria.
     * Il bottone deve essere visualizzato solo se l'utente è loggato (true).
	 * La categoria viene fornita solo se loggato vale true e serve a specificare dove dovrà essere pubblicato
	 * il thread (serve per generare la url autodescrittiva). Se loggato vale false allora categoria vale null.
	 */
	public function setBottoneCreazioneThread(bool $loggato, ?ECategoria $categoria): void {


		if ($loggato) {

			$this->smarty->assign('isLoggato', true);
			$this->smarty->assign('catNuovoThread', $categoria);

		} else {
            $this->smarty->assign('isLoggato', false);
            $this->smarty->assign('catNuovoThread', "");
        }


	}


	/**
	 * Imposta la visualizzazione di un messaggio di conferma a seguito di una richiesta
     * di rimozione di un thread da parte del moderatore appartenente a quella determinata categoria.
     * A richiesta inviata, si viene reindirizzati sulla pagina della relativa categoria.
	 */
	public function setMessaggio(bool $messaggio, string $tipologiaMessaggio, ?string $colore): void {

        $this->smarty->assign('testo', $tipologiaMessaggio);
        $this->smarty->assign('messaggio', $messaggio);
        $this->smarty->assign('colore', $colore);

    }


	/**
	 * Permette di visualizzare la pagina delle categorie nella sua totalità.
	 */
	 public function showCategoria(): void {

	 	$this->smarty->display('categoria_thread.tpl');

	 }

}