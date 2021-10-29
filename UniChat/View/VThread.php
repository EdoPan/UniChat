<?php
require_once __DIR__ . "\..\utility.php";
require_once "VSmarty.php";

/**
 * Classe View di Thread.
 */
class VThread
{

    private Smarty $smarty;

    public function __construct() {
        $this->smarty = VSmarty::start();
    }

    /**
     * @param EThread $thread
     * Metodo responsabile dell'assegnazione delle variabili richiamate in thread.tpl per la gestione della barra
     * contenente l'URL del thread specifico /Home/NomeCategoriaThread/NomeThreadSpecifico.
     */
    public function setURLNavigazione(EThread $thread) {
        $this->smarty->assign('categoriaThread', $thread->getCategoriaThread());
        $this->smarty->assign('idCategoria', $thread->getCategoriaThread()->getId());
        $this->smarty->assign('titoloT', $thread->getTitolo());
    }

    /**
     * @param EThread $thread
     * Metodo responsabile dell'assegnazione delle variabili richiamate in thread.tpl relative a tutti i valori
     * assunti dal thread specifico che abbiamo deciso di visualizzare (Titolo, Testo, Allegati ecc.).
     */
    public function setThread(EThread $thread) {
        $this->smarty->assign('titoloThread', $thread->getTitolo());
        $this->smarty->assign('testoThread', $thread->getTesto());
        $this->smarty->assign('dataThread', $thread->getData());
        $this->smarty->assign('allegatiThread', $thread->getAllegati());
        $this->smarty->assign('nomeAutoreThread', $thread->getAutoreThread()->getNome());
        $this->smarty->assign('cognomeAutoreThread', $thread->getAutoreThread()->getCognome());
        $this->smarty->assign('tipo', $thread->getAutoreThread()->getFotoProfilo()['tipo']);
        $this->smarty->assign('immagine', $thread->getAutoreThread()->getFotoProfilo()['immagine']);
        $this->smarty->assign('valutazioneThread', $thread->getValutazione());
        $this->smarty->assign('risposteThread', $thread->getRisposte());
        $this->smarty->assign('idThread', $thread->getId());
    }

    /**
     * @param bool $loggato
     * Metodo responsabile dell'assegnazione della variabile richiamata in thread.tpl responsabile della verifica
     * di avvenuta autenticazione per la visualizzazione della form d'invio risposta al thread.
     */
    public function setFormRisposta(bool $loggato) {
        $this->smarty->assign('loggato1', $loggato);
    }

    /**
     * @param bool $moderatoreAdminLoggato
     * Metodo responsabile dell'assegnazione della variabile richiamata in thread.tpl responsabile della verifica
     * di avvenuta autenticazione da parte di un Moderatore o Admin per la visualizzazione del bottone di eliminazione
     * del Thread e dei bottoni per l'eliminazione delle risposte.
     */
    public function setBottoniElimina(bool $moderatoreAdminLoggato) {
        $this->smarty->assign('moderatoreAdmin', $moderatoreAdminLoggato);
    }

    /**
     * @param bool $loggato
     * @param int|null $giudizio
     * Metodo responsabile dell'assegnazione delle variabili richiamate in thread.tpl responsabili dell'assegnazione
     * della valutazione del Thread. In particolare serviranno per gestire la colorazione dei pulsanti in base
     * a se il Thread è stato valutato in precedenza oppure lo si sta valutando al momento (script JS dedicato nel .tpl).
     */
    public function setBottoniValutazione(bool $loggato, ?int $giudizio) {
        $this->smarty->assign('loggato2', $loggato);
        $this->smarty->assign('giudizio', $giudizio);
    }

    /**
     * @param string $tipologiaMessaggio
     * Metodo responsabile dell'assegnazione della variabile richiamata in thread.tpl per la visualizzazione di un alert
     * con un messaggio di conferma o errore (questo metodo viene richiamato solo per l'eliminazione del thread
     * o di una risposta).
     */
    public function setMessaggiErroreConferma(string $tipologiaMessaggio) {
        $this->smarty->assign('messaggio', $tipologiaMessaggio);
    }

    /**
     * Metodo responsabile della visualizzazione di thread.tpl
     */
    public function showThread() {
        $this->smarty->display('thread.tpl');
    }
}