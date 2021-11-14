<?php
require_once __DIR__ . "\..\utility.php";
require_once "VSmarty.php";

/**
 * Classe per la gestione dell'input/output della pagina di registrazione di un nuovo utente.
 */
class VRegistrazione
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
     * Restituisce i valori forniti dall'utente al momento della compilazione della form per la registrazione.
     * I dati vengono forniti tramite una richiesta ti tipo POST.
     * I dati vengono restituiti in un array associativo contente i seguenti campi):
     * - nome (obbligatorio);
     * - cognome (obbligatorio);
     * - email (obbligatorio);
     * - password (obbligatorio);
     * - corsoStudio;
     * - nomeFotoProfilo;
     * - dimensioneFotoProfilo;
     * - tipoFotoProfilo;
     * - immagineFotoProfilo;
     * Se l'utente sottomette la form senza aver compilato tutti i campi obbligatori allora viene restituito null.
     * @return array|null Dati forniti dall'utente.
     */
    public function getValori(): ?array
    {
        $result = array();
        if ($_POST['nome'] != "" && $_POST['cognome'] != "" && $_POST['email'] != "" && $_POST['password'] != "") {
            $result['nome'] = filter_var($_POST['nome'], FILTER_SANITIZE_SPECIAL_CHARS);
            $result['cognome'] = filter_var($_POST['cognome'], FILTER_SANITIZE_SPECIAL_CHARS);
            $result['email'] = filter_var($_POST['email'], FILTER_SANITIZE_SPECIAL_CHARS);
            $result['password'] = filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS);
            if ($_POST['corsoStudio'] != "") {
                $result['corsoStudio'] = filter_var($_POST['corsoStudio'], FILTER_SANITIZE_SPECIAL_CHARS);
            }
            if ($_FILES['fotoProfilo']['name'] != "") {
                $result['nomeFotoProfilo'] = $_FILES['fotoProfilo']['name'];
                $result['dimensioneFotoProfilo'] = $_FILES['fotoProfilo']['size'];
                $result['tipoFotoProfilo'] = $_FILES['fotoProfilo']['type'];
                $result['immagineFotoProfilo'] = base64_encode(file_get_contents($_FILES['fotoProfilo']['tmp_name']));
            }
        } else {
            $result = null;
        }
        return $result;
    }

    /**
     * Imposta o meno la visualizzazione di un messaggio di errore che comunica che la registrazione non è riuscita.
     * @param bool $visualizza Valore che indica se visualizzare o meno il messaggio di errore.
     */
    public function setOperazioneFallita(bool $visualizza): void
    {
        $this->smarty->assign('erroreOperazione', false);
        $this->smarty->assign('messaggioErroreOperazione', "");
        if ($visualizza) {
            $this->smarty->assign('erroreOperazione', true);
            $this->smarty->assign('messaggioErroreOperazione', "Registrazione non riuscita.");
        }

    }

    /**
     * Imposta o meno la visualizzazione di un messaggio di errore che comunica che l'utente ha sottomesso la form
     * senza compilare tutti i campi obbligatori.
     * @param bool $visualizza Valore che indica se visualizzare o meno il messaggio di errore.
     */
    public function setCampiObbligatoriMancanti(bool $visualizza): void
    {
        $this->smarty->assign('erroreDatiObbligatori', false);
        $this->smarty->assign('messaggioErroreDatiObbligatori', "");
        if ($visualizza) {
            $this->smarty->assign('erroreDatiObbligatori', true);
            $this->smarty->assign('messaggioErroreDatiObbligatori', "Dati obbligatori mancanti.");
        }
    }

    /**
     * Imposta o meno la visualizzazione del messaggio di errore relativo alla presenza di dati che, in seguito ad un
     * controllo di validazione, risultano essere non corretti nel formato.
     * Il metodo richiede di fornire in ingresso il code ed il message forniti dall'eccezione ValidationException o valori
     * null se non si vuole visualizzare l'errore.
     * @param int|null $codiceErrore Codice errore fornito dall'eccezione ValidationException.
     * @param string|null $messaggioErrore Messaggio errore fornito dall'eccezione ValidationException.
     */
    public function setErroreValidazione(?int $codiceErrore, ?string $messaggioErrore): void {
        $this->smarty->assign('erroreDenominazione', false);
        $this->smarty->assign('erroreEmail', false);
        $this->smarty->assign('errorePassword', false);
        $this->smarty->assign('erroreImmagine', false);
        $this->smarty->assign('messaggioErroreDenominazione', '');
        $this->smarty->assign('messaggioErroreEmail', '');
        $this->smarty->assign('messaggioErrorePassword', '');
        $this->smarty->assign('messaggioErroreImmagine', '');

        if (isset($codiceErrore) && isset($messaggioErrore)) {
            if($codiceErrore == ValidationException::ERROR_STRING_CODE) {
                $this->smarty->assign('erroreDenominazione', true);
                $this->smarty->assign('messaggioErroreDenominazione', $messaggioErrore);
            } else if ($codiceErrore == ValidationException::ERROR_EMAIL_CODE || $codiceErrore == ValidationException::ERROR_EMAIL_LENGTH_CODE ) {
                $this->smarty->assign('erroreEmail', true);
                $this->smarty->assign('messaggioErroreEmail', $messaggioErrore);
            } else if ($codiceErrore == ValidationException::ERROR_PASSWORD_CODE || $codiceErrore == ValidationException::ERROR_PASSWORD_LENGTH_CODE) {
                $this->smarty->assign('errorePassword', true);
                $this->smarty->assign('messaggioErrorePassword', $messaggioErrore);
            } else if ($codiceErrore == ValidationException::ERROR_IMAGE_MIME_TYPE_CODE || $codiceErrore == ValidationException::ERROR_IMAGE_SIZE_CODE) {
                $this->smarty->assign('erroreImmagine', true);
                $this->smarty->assign('messaggioErroreImmagine', $messaggioErrore);
            }
        }
    }

    /**
     * Visualizza la pagina relativa alla registrazione di un nuovo utente.
     */
    public function showRegistrazione(): void
    {
        $this->smarty->display('registrazione.tpl');
    }
}