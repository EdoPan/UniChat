<?php
require_once __DIR__ . "\..\utility.php";
require_once "VSmarty.php";

class VRecuperaPassword
{

    /**
     * Costante on success per setMessaggio.
     */
    const SUCCESS = "L'operazione è andata a buon fine!";

    /**
     * Costante on error per setMessaggio.
     */
    const ERROR = "OPS.. sembra si sia verificato un errore.";

    /**
     * Costante per non visualizzare l'alert.
     */
    const NULLA = "";

    private Smarty $smarty;

    public function __construct() {
        $this->smarty = VSmarty::start();
    }

    /**
     * I dati da recuperare dalla form sono:
     * - email (obbligatoria).
     */

    public function getValori(): ?array {

        if ($_POST['email'] != "") {
            $result['email'] = filter_var($_POST['email'], FILTER_SANITIZE_SPECIAL_CHARS);
        } else {
            $result = null;
        }

        return $result;
    }

    public function setErroreValidazione(?int $codiceErrore, ?string $messaggioErrore) {

        $this->smarty->assign('erroreEmail', false);

        if(isset($codiceErrore) && isset($messaggioErrore)) {
            if ($codiceErrore == ValidationException::ERROR_EMAIL_CODE ||
                    $codiceErrore == ValidationException::ERROR_EMAIL_LENGTH_CODE) {
                $this->smarty->assign('erroreEmail', true);
                $this->smarty->assign('messaggioErroreEmail', $messaggioErrore);
            }
        }

    }

    public function setCampiObbligatoriMancanti(bool $visualizza) {
        $this->smarty->assign('erroreDatiObbligatori', false);
        $this->smarty->assign('messaggioErroreDatiObbligatori', "");
        if ($visualizza) {
            $this->smarty->assign('erroreDatiObbligatori', true);
            $this->smarty->assign('messaggioErroreDatiObbligatori', "Inserisci Email.");
        }
    }

    public function setCredenzialiErrate(bool $visualizza): void
    {
        $this->smarty->assign('credenzialiErrate', false);
        $this->smarty->assign('messaggioCredenzialiErrate', "");
        if ($visualizza) {
            $this->smarty->assign('credenzialiErrate', true);
            $this->smarty->assign('messaggioCredenzialiErrate', "Nessun utente corrisponde all'email inserita. Riprova.");
        }
    }

    /**
     * Imposta la visualizzazione di un messaggio di conferma a seguito di una richiesta
     * di recupero password.
     * A richiesta inviata, viene inviata un'email con la nuova password e
     * viene mostrato un avviso di conferma operazione.
     */
    public function setMessaggio(bool $messaggio, string $tipologiaMessaggio, ?string $colore): void {

        $this->smarty->assign('testo', $tipologiaMessaggio);
        $this->smarty->assign('messaggio', $messaggio);
        $this->smarty->assign('colore', $colore);

    }

    public function showRecuperaPassword () {

        $this->smarty->display('recupero-password.tpl');
    }

}