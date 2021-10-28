<?php

require_once __DIR__ . "\..\utility.php";
require_once "VSmarty.php";

class VPage {
	
	private Smarty $smarty;


    public function __construct() {

        $this->smarty = VSmarty::start();

    }



    /**
     * Imposta il menu utente. Se l'utente viene passato allora vuol dire che deve essere 
     * visualizzato il suo nome, cognome e foto profilo, la voce 'Profilo' e 'Logout',
     * inoltre se l'utente in questione è un admin allora deve esser visualizzata anche la
     * voce 'Pannello di controllo'.
     * Se l'utente non viene passato allora viene visualizzato 'Entra' e le voci 
     * 'Registrati' e 'Login' .
     * 
     * 
     */


    public function setMenuUtente(?EUser $user, ?bool $verificaAdmin): void {

        if (isset($user)) {

            $this->smarty->assign('loggato', true);

            if($verificaAdmin) {

                $this->smarty->assign('controlpanel', true);

            }

            $this->smarty->assign('icona', $user->getFotoProfilo());
            $this->smarty->assign('nome', $user->getNome());
            $this->smarty->assign('cognome', $user->getCognome());

        }
        

    }



    /**
     * Imposta le voci del menu delle categorie, attraverso il quale si raggiunge la pagina
     * contenente tutti i threads della categoria selezionata.
     */ 
    public function setMenuLeft(array $eCategorie): void{

        $this->smarty->assign('categorie', $eCategorie);

    

    }


    /**
     * Imposta le voci del bottone che permette di filtrare una ricerca per titolo
     * limitandola ad una categoria specifica o a tutte.
     */

    public function setBottoneFiltra(array $eCategorie): void {

        $this->smarty->assign('categorie', $eCategorie);


    }


}