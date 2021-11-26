<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "utility.php";
require_once "VSmarty.php";

/**
 * Classe che si occupa della visualizzazione della pagina che comunica all'utente che deve abilitare i cookies o
 * il JavaScript per poter usufruire dell'applicazione.
 */
class VErroreClient
{
    /**
     * Costante che indica il messaggio da visualizzare nel momento in cui si disabilita l'esecuzione del codice JavaSript
     * sul proprio browser.
     */
    const MESSAGGIO_JAVASCRIPT_DISABILITATO = "Il sito necessita del codice JavaScript per funzionare correttamente.";

    /**
     * Costante che indica il messaggio da visualizzare nel momento in cui si disabilitano tutti i cookies
     * sul proprio browser.
     */
    const MESSAGGIO_COOKIES_DISABILITATI = "Il sito necessita dei cookies per funzionare correttamente.";

    /**
     * @var Smarty
     */
    private $smarty;

    /**
     * Costruttore. Inizializza Smarty.
     */
    public function __construct() {
        $this->smarty = VSmarty::start();
    }

    /**
     * Imposta il messaggio da far visualizzare.
     * @param string $messaggioErrore Messaggio da visualizzare.
     */
    public function setErrore(string $messaggioErrore): void
    {
        $this->smarty->assign('messaggioErrore', $messaggioErrore);
    }

    /**
     * Visualizza la pagina che avvisa l'utente dei requisiti dell'applicazione.
     */
    public function showErroreClient(): void
    {
        $this->smarty->display('pagina-errore-client.tpl');
    }
}