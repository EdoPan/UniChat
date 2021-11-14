<?php
require_once __DIR__ . "\..\utility.php";
require_once "VSmarty.php";

/**
 * Classe per la gestione dell'input/output della pagina di creazione di una nuova categoria.
 */
class VCreazioneCategoria
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
     * @return Smarty
     */
    public function getSmarty(): Smarty
    {
        return $this->smarty;
    }

    /**
     * Imposta o meno la visualizzazione di un messaggio di errore che comunica all'utente che al momento della
     * compilazione della form di creazione di una nuova categoria, non ha inserito alcuni o tutti i dati obbligatori.
     * @param bool $campiMancanti Valore che indica se visualizzare o meno il messaggio di errore.
     */
    public function setCampiObbligatoriMancanti(bool $campiMancanti): void
    {
        if ($campiMancanti) {
            $this->smarty->assign('erroreDatiObbligatori', true);
            $this->smarty->assign('messaggioErroreDatiObbligatori', "Dati obbligatori mancanti.");
        } else {
            $this->smarty->assign('erroreDatiObbligatori', false);
            $this->smarty->assign('messaggioErroreDatiObbligatori', "");
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
        $this->smarty->assign('erroreImmagine', false);
        $this->smarty->assign('messaggioErroreDenominazione', '');
        $this->smarty->assign('messaggioErroreImmagine', '');

             if (isset($codiceErrore) && isset($messaggioErrore)) {
                 if($codiceErrore == ValidationException::ERROR_STRING_CODE) {
                     $this->smarty->assign('erroreDenominazione', true);
                     $this->smarty->assign('messaggioDenominazione', $messaggioErrore);
                 } else if ($codiceErrore == ValidationException::ERROR_IMAGE_MIME_TYPE_CODE || $codiceErrore == ValidationException::ERROR_IMAGE_SIZE_CODE) {
                     $this->smarty->assign('erroreImmagine', true);
                     $this->smarty->assign('messaggioImmagine', $messaggioErrore);
                 }
             }

    }


    /**
     * Restituisce i valori forniti dall'utente al momento della compilazione della form per la creazione di una nuova
     * categoria.
     * I dati vengono forniti tramite una richiesta ti tipo POST.
     * I dati vengono restituiti in un array associativo contente i seguenti campi:
     * - nome (obbligatorio);
     * - descrizione (obbligatorio);
     * - nomeIcona;
     * - dimensioneIcona;
     * - tipoIcona;
     * - immagineIcona;
     * Se l'utente sottomette la form senza aver compilato tutti i campi obbligatori allora viene restituito null.
     * @return array|null Dati forniti dall'utente.
     */
    public function getValori(): ?array {
        if ($_POST['nome'] != "" && $_POST['descrizione'] != "") {
            $result['nome'] = filter_var($_POST['nome'], FILTER_SANITIZE_SPECIAL_CHARS);
            $result['descrizione'] = filter_var($_POST['descrizione'], FILTER_SANITIZE_SPECIAL_CHARS);
            if ($_FILES['icona']['name'] != "") {
                $result['nomeIcona']  = $_FILES['icona']['name'];
                $result['dimensioneIcona']  = $_FILES['icona']['size'];
                $result['tipoIcona']  = $_FILES['icona']['type'];
                $result['immagineIcona']  = base64_encode(file_get_contents($_FILES['icona']['tmp_name']));
            }
        } else {
            $result = null;
        }

        return $result;

    }

    /**
     * Visualizza la pagina relativa alla creazione di una nuova categoria.
     */
    public function showCreaCategoria(): void {

        $this->smarty->display('inserisci-categoria.tpl');


    }

}