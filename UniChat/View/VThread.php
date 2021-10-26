<?php
require_once __DIR__ . "\..\utility.php";
require_once "VSmarty.php";
class VThread
{
    private Smarty $smarty;

    public function __construct() {
        $this->smarty = VSmarty::start();
    }

    public function setURLNavigazione(EThread $thread) {
        $this->smarty->assign('categoriaThread', $thread->getCategoriaThread());
        $this->smarty->assign('idCategoria', $thread->getCategoriaThread()->getId());
        $this->smarty->assign('titoloT', $thread->getTitolo());
    }

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

    //Il parametro $thread dovrebbe essere inutilizzato in quanto recupero l'id del thread dalla URL
    public function setFormRisposta(bool $loggato, ?EThread $thread) {
        $this->smarty->assign('loggato1', $loggato);
    }

    public function setBottoniElimina(bool $moderatoreAdminLoggato) {
        $this->smarty->assign('moderatoreAdmin', $moderatoreAdminLoggato);
    }

    public function setBottoniValutazione(bool $loggato, ?int $giudizio) {
        $this->smarty->assign('loggato2', $loggato);
        $this->smarty->assign('giudizio', $giudizio);
    }

    public function setMessaggiErroreConferma(string $tipologiaMessaggio) {
        $this->smarty->assign('messaggio', $tipologiaMessaggio);
    }

    public function showThread() {
        $this->smarty->display('thread.tpl');
    }
}