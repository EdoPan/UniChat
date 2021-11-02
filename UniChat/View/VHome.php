<?php
require_once __DIR__ . "\..\utility.php";
require_once "VSmarty.php";

/**
 * Classe View di Home.
 */
class VHome
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

    private Smarty $smarty;

    public function __construct() {
        $this->smarty = VSmarty::start();
    }

    /**
     * @param bool $loggato
     *  Metodo responsabile dell'assegnazione della variabile richiamata in home.tpl responsabile della verifica
     * di avvenuta autenticazione per la visualizzazione della form d'invio messaggio nella chat.
     */
    public function  setInterazioneChat(bool $loggato): void {
        $this->smarty->assign('loggato', $loggato);
    }

    /**
     * @param array $threadsConPiuRisposte
     * @param array $treadsConValutazionePiuAlta
     * Metodo responsabile dell'assegnazione delle variabili richiamate in home.tpl responsabile
     * della visualizzazione dei Threads più discussi e maggiormente valutati nel loro box specifico.
     */
    public function setBoxThread(array $threadsConPiuRisposte, array $treadsConValutazionePiuAlta): void {
        $this->smarty->assign('threadsConPiuRisposte', $threadsConPiuRisposte);
        $this->smarty->assign('treadsConValutazionePiuAlta', $treadsConValutazionePiuAlta);
    }

    /**
     * @param bool|null $utenteModeratoreAdminLoggato
     * Metodo responsabile dell'assegnazione della variabile richiamata in home.tpl responsabile della verifica
     * di avvenuta autenticazione da parte di un Moderatore o Admin per la visualizzazione del bottone di eliminazione
     * dei messaggi inviati sulla chat.
     */
    public function setBottoneElimina(?bool $utenteModeratoreAdminLoggato): void {
        $this->smarty->assign('moderatoreAdmin', $utenteModeratoreAdminLoggato);
    }

    /**
     * @param string $tipologiaMessaggio
     * Metodo responsabile dell'assegnazione della variabile richiamata in home.tpl per la visualizzazione di un alert
     * con un messaggio di conferma o errore (questo metodo viene richiamato solo per l'eliminazione
     * dei messaggi della chat).
     */
    public function setMessaggiErroreConferma(string $tipologiaMessaggio): void {
        $this->smarty->assign('messaggio', $tipologiaMessaggio);
    }

    /**
     * Metodo responsabile della visualizzazione di home.tpl
     */
    public function showHome(): void {
        $this->smarty->display('home.tpl');
    }
}