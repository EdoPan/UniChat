<?php
require_once __DIR__ . "\..\utility.php";
require_once "VSmarty.php";
class VProfile
{
    private Smarty $smarty;

    public function __construct() {
        $this->smarty = VSmarty::start();
    }

    public function setUtente(EUser $user) {
        $this->smarty->assign('nomeUtente', $user->getNome());
        $this->smarty->assign('cognomeUtente', $user->getCognome());
        $this->smarty->assign('emailUtente', $user->getEmail());
        $this->smarty->assign('corsoUtente', $user->getCorsoStudio());
        $this->smarty->assign('tipo', $user->getFotoProfilo()['tipo']);
        $this->smarty->assign('immagine', $user->getFotoProfilo()['immagine']);
    }

    public function showUtente() {
        $this->smarty->display('profile.tpl');
    }
}