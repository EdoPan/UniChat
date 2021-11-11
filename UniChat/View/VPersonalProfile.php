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

    public function getSmarty(): Smarty
    {
        return $this->smarty;
    }

    public function getValori(): array
    {
        $result = array();
        if ($_FILES['nuovaFotoProfilo']['name'] != "") {
            $result['nomeNuovaFotoProfilo'] = $_FILES['nuovaFotoProfilo']['name'];
            $result['dimensioneNuovaFotoProfilo']  = $_FILES['nuovaFotoProfilo']['size'];
            $result['tipoNuovaFotoProfilo']  = $_FILES['nuovaFotoProfilo']['type'];
            $result['immagineNuovaFotoProfilo']  = base64_encode(file_get_contents($_FILES['nuovaFotoProfilo']['tmp_name']));
        }
        if ($_POST['nuovaPassword'] != "") {
            $result['nuovaPassword'] = filter_var($_POST['nuovaPassword'], FILTER_SANITIZE_SPECIAL_CHARS);
        }
        if ($_POST['nuovoCorsoStudio'] != "") {
            $result['nuovoCorsoStudio'] = filter_var($_POST['nuovoCorsoStudio'], FILTER_SANITIZE_SPECIAL_CHARS);
        }
        return $result;
    }

    /**
     * Imposta tutti i valori relativi al proprio profilo, in modo da poterlo visualizzare nella apposita pagina.
     * Il metodo richiede in ingresso l'utente di cui si vogliono ottenere i dati.
     * @param EUser $user Utente di cui si deve visualizzare il profilo
     */
    public function setUtente(EUser $user): void
    {

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

    public function setErroreValidazione(?int $codiceErrore, ?string $messaggioErrore): void
    {
        $this->smarty->assign('errorePassword', false);
        $this->smarty->assign('erroreImmagine', false);
        $this->smarty->assign('messaggioErrorePassword', '');
        $this->smarty->assign('messaggioErroreImmagine', '');
        if (isset($codiceErrore) && isset($messaggioErrore)) {
            if ($codiceErrore == ValidationException::ERROR_PASSWORD_CODE || $codiceErrore == ValidationException::ERROR_PASSWORD_LENGTH_CODE) {
                $this->smarty->assign('errorePassword', true);
                $this->smarty->assign('messaggioErrorePassword', $messaggioErrore);
            } else if ($codiceErrore == ValidationException::ERROR_IMAGE_MIME_TYPE_CODE || $codiceErrore == ValidationException::ERROR_IMAGE_SIZE_CODE) {
                $this->smarty->assign('erroreImmagine', true);
                $this->smarty->assign('messaggioErroreImmagine', $messaggioErrore);
            }
        }
    }

    public function setAvvisoCampiVuoti(bool $visualizza): void
    {
        $this->smarty->assign('avvisoCampiVuoti', false);
        $this->smarty->assign('messaggioAvvisoCampiVuoti', '');
        if ($visualizza) {
            $this->smarty->assign('avvisoCampiVuoti', true);
            $this->smarty->assign('messaggioAvvisoCampiVuoti', 'Compilare almeno un campo.');
        }
    }

    /**
     * Imposta o meno un messaggio di conferma o di errore di avvenuta modifica del profilo.
     * Se in ingresso viene fornito true allora viene settato il messaggio di operazione riuscita, se viene fornito
     * false allora viene settato il messaggio di errore e se invece viene fornito null allora vuol dire che non è
     * stata compiuta nessuna operazione e quindi non è necessario settare un messaggio.
     * @param bool|null $esito Esito operazione di modifica profilo.
     */
    public function setMessaggioConfermaErroreModificaProfilo(?bool $esito): void
    {
        $this->smarty->assign('conferma', false);
        $this->smarty->assign('errore', false);
        $this->smarty->assign('messaggioConferma', '');
        $this->smarty->assign('messaggioErrore', '');
        if (isset($esito)) {
            if ($esito) {
                $this->smarty->assign('conferma', true);
                $this->smarty->assign('messaggioConferma', "L'operazione è andata a buon fine!");
            } else {
                $this->smarty->assign('errore', true);
                $this->smarty->assign('messaggioErrore', 'OPS.. sembra si sia verificato un errore.');
            }
        }
    }

    /**
     * Visualizza la pagina relativa al proprio profilo.
     */
    public function showPersonalProfile(): void
    {

        $this->smarty->display('mio-profilo.tpl');

    }
}