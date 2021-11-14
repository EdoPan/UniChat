<?php
declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";

/**
 * Classe che si occupa di gestire le operazioni necessarie a informare l'utente che per il corretto funzionamento
 * dell'applicazione deve abilitare i cookies e l'esecuzione del codice JavaScript.
 */
class CGestioneClient
{
    /**
     * Gestisce le operazioni necessarie per visualizzare la pagina contenente il messaggio che informa l'utente
     * che l'applicazione necessita dell'esecuzione di codice JavaScript per funzionare correttamente.
     */
    public function javascriptDisabilitati(): void
    {
        $vErroreCliente = new VErroreClient();
        $vErroreCliente->setErrore(VErroreClient::MESSAGGIO_JAVASCRIPT_DISABILITATO);
        $vErroreCliente->showErroreClient();
    }

    /**
     * Gestisce le operazioni necessarie per visualizzare la pagina contenente il messaggio che informa l'utente
     * che l'applicazione necessita dei cookies per funzionare correttamente.
     */
    public function cookieDisabilitati(): void
    {
        $vErroreCliente = new VErroreClient();
        $vErroreCliente->setErrore(VErroreClient::MESSAGGIO_COOKIES_DISABILITATI);
        $vErroreCliente->showErroreClient();
    }

}
