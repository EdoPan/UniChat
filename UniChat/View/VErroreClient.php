<?php
require_once __DIR__ . "\..\utility.php";
require_once "VSmarty.php";

class VErroreClient
{
    const MESSAGGIO_JAVASCRIPT_DISABILITATO = "Il sito necessita del codice JavaScript per funzionare correttamente.";

    const MESSAGGIO_COOKIES_DISABILITATI = "Il sito necessita dei cookies per funzionare correttamente.";

    private Smarty $smarty;

    public function __construct() {
        $this->smarty = VSmarty::start();
    }

    public function setErrore(string $messaggioErrore): void
    {
        $this->smarty->assign('messaggioErrore', $messaggioErrore);
    }

    public function showErroreClient(): void
    {
        $this->smarty->display('pagina-errore-client.tpl');
    }
}