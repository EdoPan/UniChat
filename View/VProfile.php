<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "utility.php";
require_once "VSmarty.php";

/**
 * Classe View di Profile ossia il profilo di un qualunque utente iscritto a UniChat.
 */
class VProfile
{

    private $smarty;

    public function __construct() {
        $this->smarty = VSmarty::start();
    }

    public function getSmarty(): Smarty
    {
        return $this->smarty;
    }

    /**
     * @param EUser $user
     * Metodo responsabile dell'assegnazione delle variabili richiamate in profile.tpl relative a tutti i valori
     * assunti dall'utente specifico che abbiamo deciso di visualizzare.
     */
    public function setUtente(EUser $user): void {
        $this->smarty->assign('nomeUtente', $user->getNome());
        $this->smarty->assign('cognomeUtente', $user->getCognome());
        $this->smarty->assign('emailUtente', $user->getEmail());
        $this->smarty->assign('corsoUtente', $user->getCorsoStudio());
        $this->smarty->assign('tipo', $user->getFotoProfilo()['tipo']);
        $this->smarty->assign('immagine', $user->getFotoProfilo()['immagine']);
    }

    /**
     * Metodo responsabile della visualizzazione di profile.tpl
     */
    public function showUtente(): void {
        $this->smarty->display('profile.tpl');
    }
}