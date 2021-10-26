<?php
require_once __DIR__ . "\..\utility.php";
require_once "VSmarty.php";
class VHome
{
    private Smarty $smarty;

    public function __construct() {
        $this->smarty = VSmarty::start();
    }

    public function  setInterazioneChat(bool $loggato) {
        $this->smarty->assign('loggato', $loggato);
    }

    public function setBoxThread(array $threadsConPiuRisposte, array $treadsConValutazionePiuAlta) {
        $this->smarty->assign('threadsConPiuRisposte', $threadsConPiuRisposte);
        $this->smarty->assign('treadsConValutazionePiuAlta', $treadsConValutazionePiuAlta);
    }

    public function setBottoneElimina(?bool $utenteModeratoreAdminLoggato) {
        $this->smarty->assign('moderatoreAdmin', $utenteModeratoreAdminLoggato);
    }

    public function setMessaggiErroreConferma(string $tipologiaMessaggio) {
        $this->smarty->assign('messaggio', $tipologiaMessaggio);
    }

    public function showHome() {
        $this->smarty->display('home.tpl');
    }
}