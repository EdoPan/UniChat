<?php

declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";
require_once "VSmarty.php";


class VPersonalProfile
{

    private Smarty $smarty;

    public function __construct() {

        $this->smarty = VSmarty::start();

    }

    public function setUtente(EUser $user): void {

        $nome = $user->getNome();
        $cognome = $user->getCognome();
        $email = $user->getEmail();
        $corsoStudio = $user->getCorsoStudio();
        $fotoProfiloTipo = $user->getFotoProfilo()['tipo'];
        $fotoProfiloImmagine = $user->getFotoProfilo()['immagine'];

        $this->smarty->assign('nome', $nome);
        $this->smarty->assign('cognome', $cognome);
        $this->smarty->assign('email', $email);
        $this->smarty->assign('corsoStudio', $corsoStudio);
        $this->smarty->assign('tipoFotoProfilo', $fotoProfiloTipo);
        $this->smarty->assign('immagineFotoProfilo', $fotoProfiloImmagine);

    }

    public function setMessaggioConfermaErroreModificaProfilo(?bool $esito): void {
        $this->smarty->assign('conferma', false);
        $this->smarty->assign('errore', false);
        $this->smarty->assign('messaggioConferma', '');
        $this->smarty->assign('messaggioError', '');
        if (isset($esito)) {
            if ($esito) {
                $this->smarty->assign('conferma', true);
                $this->smarty->assign('messaggioConferma', 'Il tuo profilo è stato aggiornato con successo.');
            } else {
                $this->smarty->assign('errore', true);
                $this->smarty->assign('messaggioError', 'Si è verificato un errore, riprovare.');
            }
        }
    }

    public function showPersonalProfile(): void {

        $this->smarty->display('mio-profilo.tpl');

    }
}