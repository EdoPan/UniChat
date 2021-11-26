<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "utility.php";
require_once "VSmarty.php";

/**
 * Classe View di Thread.
 */
class VThread
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

    private $smarty;

    public function __construct() {
        $this->smarty = VSmarty::start();
    }

    public function getSmarty(): Smarty
    {
        return $this->smarty;
    }

    /**
     * @param EThread $thread
     * Metodo responsabile dell'assegnazione delle variabili richiamate in thread.tpl per la gestione della barra
     * contenente l'URL del thread specifico /Home/NomeCategoriaThread/NomeThreadSpecifico.
     */
    public function setURLNavigazione(EThread $thread): void {
        $this->smarty->assign('categoriaThread', $thread->getCategoriaThread()->getNome());
        $this->smarty->assign('idCategoria', $thread->getCategoriaThread()->getId());
        $this->smarty->assign('titoloT', $thread->getTitolo());
    }

    /**
     * @param EThread $thread
     * Metodo responsabile dell'assegnazione delle variabili richiamate in thread.tpl relative a tutti i valori
     * assunti dal thread specifico che abbiamo deciso di visualizzare (Titolo, Testo, Allegati ecc.).
     */
    public function setThread(EThread $thread): void {
        $this->smarty->assign('titoloThread', $thread->getTitolo());
        $this->smarty->assign('testoThread', $thread->getTesto());
        $this->smarty->assign('dataThread', $thread->getData());
        $this->smarty->assign('allegatiThread', $thread->getAllegati());
        $this->smarty->assign('idAutoreThread', $thread->getAutoreThread()->getId());
        $this->smarty->assign('nomeAutoreThread', $thread->getAutoreThread()->getNome());
        $this->smarty->assign('cognomeAutoreThread', $thread->getAutoreThread()->getCognome());
        $this->smarty->assign('tipo', $thread->getAutoreThread()->getFotoProfilo()['tipo']);
        $this->smarty->assign('immagine', $thread->getAutoreThread()->getFotoProfilo()['immagine']);
        $this->smarty->assign('valutazioneThread', $thread->getValutazione()->getTotale());
        $this->smarty->assign('risposteThread', $thread->getRisposte());
        $this->smarty->assign('idThread', $thread->getId());
    }

    /**
     * @param bool $loggato
     * Metodo responsabile dell'assegnazione della variabile richiamata in thread.tpl responsabile della verifica
     * di avvenuta autenticazione per la visualizzazione della form d'invio risposta al thread.
     */
    public function setFormRisposta(bool $loggato): void {
        $this->smarty->assign('loggato1', $loggato);
    }

    /**
     * @param bool $moderatoreAdminLoggato
     * Metodo responsabile dell'assegnazione della variabile richiamata in thread.tpl responsabile della verifica
     * di avvenuta autenticazione da parte di un Moderatore o Admin per la visualizzazione del bottone di eliminazione
     * del Thread e dei bottoni per l'eliminazione delle risposte.
     */
    public function setBottoniElimina(bool $moderatoreAdminLoggato): void {
        $this->smarty->assign('moderatoreAdmin', $moderatoreAdminLoggato);
    }

    /**
     * @param bool $loggato
     * @param int|null $giudizio
     * Metodo responsabile dell'assegnazione delle variabili richiamate in thread.tpl responsabili dell'assegnazione
     * della valutazione del Thread. In particolare serviranno per gestire la colorazione dei pulsanti in base
     * a se il Thread è stato valutato in precedenza oppure lo si sta valutando al momento (script JS dedicato nel .tpl).
     */
    public function setBottoniValutazione(bool $loggato, ?int $giudizio): void {
        $this->smarty->assign('loggato2', $loggato);
        $this->smarty->assign('giudizio', $giudizio);
    }

    /**
     * @param bool $messaggio
     * @param string $tipologiaMessaggio
     * @param string|null $colore
     * Metodo responsabile dell'assegnazione dei valori e della visualizzazione del tipo di messaggio (Errore/Conferma)
     * e del relativo colore ('danger'/'success').
     */
    public function setMessaggio(bool $messaggio, string $tipologiaMessaggio, ?string $colore): void {
        $this->smarty->assign('testo', $tipologiaMessaggio);
        $this->smarty->assign('messaggio', $messaggio);
        $this->smarty->assign('colore', $colore);
    }

    /**
     * Metodo responsabile della visualizzazione di thread.tpl
     */
    public function showThread(): void {
        $this->smarty->display('thread.tpl');
    }
}