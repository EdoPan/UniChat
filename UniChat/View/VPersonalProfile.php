<?php

declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";
require_once "VSmarty.php";


class VPersonalProfile
{

    /**
     * @var Smarty
     */
    private Smarty $smarty;

    /**
     * Costruttore, inizializza Smarty.
     */
    public function __construct() {

        $this->smarty = VSmarty::start();

    }

    /**
     * Imposta tutti i valori relativi al proprio profilo, in modo da poterlo visualizzare nella apposita pagina.
     * Il metodo richiede in ingresso l'utente di cui si vogliono ottenere i dati.
     * @param EUser $user Utente di cui si deve visualizzare il profilo
     */
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

    /**
     * Imposta o meno un messaggio di conferma o di errore di avvenuta modifica del profilo.
     * Se in ingresso viene fornito true allora viene settato il messaggio di operazione riuscita, se viene fornito
     * false allora viene settato il messaggio di errore e se invece viene fornito null allora vuol dire che non è
     * stata compiuta nessuna operazione e quindi non è necessario settare un messaggio.
     * @param bool|null $esito Esito operazione di modifica profilo.
     */
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

    /**
     * Visualizza la pagina relativa al proprio profilo.
     */
    public function showPersonalProfile(): void {

        $this->smarty->display('mio-profilo.tpl');

    }
}