<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "utility.php";
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
     * Costante per non visualizzare il messaggio di errore.
     */
    const NULLA = "";

    /**
     * Costante per il numero massimo di threads da visualizzare nel box thread più polari.
     */
    const NUMERO_MAX_THREADS = 3;

    private $smarty;

    public function __construct() {
        $this->smarty = VSmarty::start();
    }

    public function getSmarty(): Smarty
    {
        return $this->smarty;
    }

    /**
     * @param bool $loggato
     *  Metodo responsabile dell'assegnazione della variabile richiamata in home.tpl responsabile della verifica
     * di avvenuta autenticazione per la visualizzazione della form d'invio messaggio nella chat.
     */
    public function  setInterazioneChat(bool $loggato): void {
        $this->smarty->assign('log', $loggato);
    }

    /**
     * @param array $threadsConPiuRisposte
     * @param array $threadsConValutazionePiuAlta
     * Metodo responsabile dell'assegnazione delle variabili richiamate in home.tpl responsabile
     * della visualizzazione dei Threads più discussi e maggiormente valutati nel loro box specifico.
     */
    public function setBoxThread(array $threadsConPiuRisposte, array $threadsConValutazionePiuAlta): void {
        $this->smarty->assign('threadsConPiuRisposte', $threadsConPiuRisposte);
        $this->smarty->assign('threadsConValutazionePiuAlta', $threadsConValutazionePiuAlta);
        $this->smarty->assign('numeroThreadsValutazionePiuAlta', count($threadsConValutazionePiuAlta)-1);
        $this->smarty->assign('numeroThreadsPiuDiscussi', count($threadsConPiuRisposte)-1);

    }

    /**
     * @param bool|null $utenteModeratoreAdminLoggato
     * Metodo responsabile dell'assegnazione della variabile richiamata in home.tpl responsabile della verifica
     * di avvenuta autenticazione da parte di un Moderatore o Admin per la visualizzazione del bottone di eliminazione
     * dei messaggi inviati sulla chat.
     */
    public function setBottoneElimina(bool $utenteModeratoreAdminLoggato): void {
        $this->smarty->assign('moderatoreAdmin', $utenteModeratoreAdminLoggato);
    }

    /**
     * @param string $tipologiaMessaggio
     * Metodo responsabile dell'assegnazione dei valori e della visualizzazione del tipo di messaggio (Errore/Conferma)
     * e del relativo colore ('danger'/'success').
     */
    public function setMessaggio(bool $messaggio, string $tipologiaMessaggio, ?string $colore): void {
        $this->smarty->assign('testo', $tipologiaMessaggio);
        $this->smarty->assign('messaggio', $messaggio);
        $this->smarty->assign('colore', $colore);
    }


    /**
     * Metodo per il recupero del messaggio inserito nella form dall'utente.
     * @return string|null
     */
    public function getTestoNuovoMessaggio(): ?string
    {
        if ($_POST['text'] != "") {

            return $_POST['text'];

        } else {

            return null;

        }
    }

    /**
     * Metodo per il recupero dell'id dell'ultimo messaggio inviato.
     * @return int|null
     */
    public function getIdUltimoMessaggio(): ?int
    {
        if ($_POST['idMessage'] != "") {

            return (int)$_POST['idMessage'];

        } else {

            return null;

        }
    }

    /**
     * Metodo responsabile della visualizzazione di home.tpl
     */
    public function showHome(): void {
        $this->smarty->display('home.tpl');
    }
}