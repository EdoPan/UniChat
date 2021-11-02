<?php

declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";
require_once "VSmarty.php";


class VForm
{

    /**
     * @var Smarty
     */
    private Smarty $smarty;

    /**
     * Indica che si vuole interagire con la form responsabile della registrazione di un nuovo utente.
     */
    const FORM_REGISTRAZIONE = 0;

    /**
     * Indica che si vuole interagire con la form responsabile del login di un utente.
     */
    const FORM_LOGIN = 1;

    /**
     * Indica che si vuole interagire con la form responsabile del recupero password di un utente.
     */
    const FORM_RECUPERO_PASSWORD = 2;

    /**
     * Indica che si vuole interagire con la form responsabile della ricerca di threads.
     */
    const FORM_RICERCA = 3;

    /**
     * Indica che si vuole interagire con la form responsabile della creazione di un nuovo thread.
     */
    const FORM_CREAZIONE_THREAD = 4;

    /**
     * Indica che si vuole interagire con la form responsabile della creazione di una nuova categoria.
     */
    const FORM_CREAZIONE_CATEGORIA = 5;

    /**
     * Indica che si vuole interagire con la form responsabile dell'invio di un nuovo messaggio della chat.
     */
    const FORM_INVIO_MESSAGGIO = 6;

    /**
     * Indica che si vuole interagire con la form responsabile dell'invio di una nuova risposta in un thread.
     */
    const FORM_INVIO_RISPOSTA = 7;

    /**
     * Indica che si vuole interagire con la form responsabile dell'aggiornamento del proprio profilo utente.
     */
    const FORM_PROFILO_PERSONALE = 8;

    /**
     * Costruttore, inizializza Smarty.
     */
    public function __construct() {

        $this->smarty = VSmarty::start();

    }

    /**
     * Imposta nella form di creazione di un nuovo thread, le informazioni relative a quale categoria deve essere
     * inserito quel thread.
     * @param ECategoria $categoria Categoria da inserire nella form.
     */
    public function setCategoriaCreazioneThread(ECategoria $categoria): void {
        $categoriaID = $categoria->getId();
        $categoriaNome = $categoria->getNome();

        $this->smarty->assign('categoriaID', $categoriaID);
        $this->smarty->assign('categoriaNome', $categoriaNome);
    }

    /**
     * Imposta o meno, i messaggi di errore relativi alla validazione dei dati forniti all'interno della form.
     * Il metodo prende in ingresso il codice ed il messaggio di errore fornito al lancio dell'eccezione relativa alla
     * validazione dei dati.
     * Se non è necessario settare un messaggio di errore allora in ingresso deve essere fornito null per entrambi i
     * parametri.
     * @param int|null $codiceErrore Codice errore fornito dall'eccezione.
     * @param string|null $messaggioErrore Messaggio errore fornito dall'eccezione.
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
                $this->smarty->assign('messaggioDenominazione', $messaggioErrore);
            } else if ($codiceErrore == ValidationException::ERROR_EMAIL_CODE || $codiceErrore == ValidationException::ERROR_EMAIL_LENGTH_CODE ) {
                $this->smarty->assign('erroreEmail', true);
                $this->smarty->assign('messaggioErroreEmail', $messaggioErrore);
            } else if ($codiceErrore == ValidationException::ERROR_PASSWORD_CODE || $codiceErrore == ValidationException::ERROR_PASSWORD_LENGTH_CODE) {
                $this->smarty->assign('errorePassword', true);
                $this->smarty->assign('messaggioErrorePassword', $messaggioErrore);
            } else if ($codiceErrore == ValidationException::ERROR_IMAGE_MIME_TYPE_CODE || $codiceErrore == ValidationException::ERROR_IMAGE_SIZE_CODE) {
                $this->smarty->assign('erroreImmagine', true);
                $this->smarty->assign('messaggioImmagine', $messaggioErrore);
            }
        }
    }

    /**
     * Permette di recuperare i dati, all'interno di un array associativo, immessi nelle form presenti nelle varie
     * pagine del sito.
     * In base alla tipologia di form fornita in ingresso allora si vanno a recuperare i possibili dati immessi
     * dall'utente.
     * Se tra i dati recuperati non sono presenti quelli obbligatori allora viene restituito null.
     * Le form per cui si recuperano i dati sono:
     *  - FORM DI REGISTRAZIONE;
     *  - FORM DI LOGIN;
     *  - FORM DI RECUPERO PASSWORD;
     *  - FORM DI RICERCA;
     *  - FORM DI CREAZIONE THREAD;
     *  - FORM DI CREAZIONE CATEGORIA;
     *  - FORM DI INVIO MESSAGGIO;
     *  - FORM DI INVIO RISPOSTA;
     *  - FORM DI MODIFICA PROFILO PERSONALE.
     * Tutti i dati recuperati vengono prima sanificati per evitare la presenza di codice interpretabile dal browser.
     * @param int $tipologiaForm Valore che indica da quale form recuperare i dati.
     * @return array|null Dati recuperati.
     */
    public function getValori(int $tipologiaForm): ?array {

        $result = array();
        if ($tipologiaForm == self::FORM_REGISTRAZIONE) {

            /**
             * I dati da recuperare dalla form di registrazione sono:
             * - nome (obbligatorio);
             * - cognome (obbligatorio);
             * - email (obbligatoria);
             * - password (obbligatoria);
             * - corso di studio;
             * - foto profilo.
             */
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
                    $result['immagineFotoProfilo'] = $_FILES['fotoProfilo']['tmp_name'];
                }
            }
        }
        if ($tipologiaForm == self::FORM_LOGIN) {

            /**
             * I dati da recuperare dalla form sono:
             * - email (obbligatoria);
             * - password (obbligatoria).
             */
            if ($_POST['email'] != "" && $_POST['password'] != "") {
                $result['email'] = filter_var($_POST['email'], FILTER_SANITIZE_SPECIAL_CHARS);
                $result['password'] = filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS);
            } else{
                $result = null;
            }
        }
        if ($tipologiaForm == self::FORM_RECUPERO_PASSWORD) {

            /**
             * I dati da recuperare dalla form sono:
             * - email (obbligatoria).
             */
            if ($_POST['email'] != "") {
                $result['email'] = filter_var($_POST['email'], FILTER_SANITIZE_SPECIAL_CHARS);
            }  else {
                $result = null;
            }
        } else if ($tipologiaForm == self::FORM_RICERCA) {

            /**
             * I dati da recuperare dalla form di ricerca sono:
             * - testo della ricerca (obbligatorio);
             * - id della categoria in cui cercare.
             */
            if ($_POST['testoricerca'] != "") {
                $result['testoRicerca'] = filter_var($_POST['testoricerca'], FILTER_SANITIZE_SPECIAL_CHARS);
                if ($_POST['categoriaID'] != "") {
                    $result['categoriaID'] = filter_var($_POST['categoriaID'], FILTER_SANITIZE_SPECIAL_CHARS);
                }
            } else {
                $result = null;
            }
        } else if ($tipologiaForm == self::FORM_CREAZIONE_THREAD) {

            /**
             * I dati da recuperare dalla form sono:
             * - titolo del thread (obbligatorio);
             * - testo del thread (obbligatorio);
             * - categoria del thread (obbligatoria);
             * - allegati.
             */
            if ($_POST['titolo'] != "" && $_POST['testo'] != "" && $_POST['categoriathread'] != "") {
                $result['titolo'] = filter_var($_POST['titolo'], FILTER_SANITIZE_SPECIAL_CHARS);
                $result['testo'] = filter_var($_POST['testo'], FILTER_SANITIZE_SPECIAL_CHARS);
                $result['categoriaID'] = filter_var($_POST['categoriathread'], FILTER_SANITIZE_SPECIAL_CHARS);
                if($_FILES['allegati']['name'] != "") {
                    $numeroAllegati = count($_FILES['allegati']['name']);
                    for ($i = 0; $i < $numeroAllegati; $i++) {
                        $indice = 'allegato' . $i;
                        $result[$indice."Nome"] = $_FILES['allegati']['name'][$i];
                        $result[$indice."Dimensione"] = $_FILES['allegati']['size'][$i];
                        $result[$indice."Tipo"] = $_FILES['allegati']['type'][$i];
                        $result[$indice."File"] = base64_encode(file_get_contents($_FILES['allegati']['tmp_name'][$i]));
                    }
                }
            } else {
                $result = null;
            }
        } else if ($tipologiaForm == self::FORM_CREAZIONE_CATEGORIA) {

            /**
             * I dati da recuperare dalla form sono:
             * - nome della categoria (obbligatorio);
             * - descrizione della categoria (obbligatorio);
             * - icona.
             */
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
        } else if ($tipologiaForm == self::FORM_INVIO_MESSAGGIO) {

            /**
             * I dati da recuperare dalla form sono:
             * - testo del messaggio;
             * - id dell'ultimo messaggio;
             */
            if ($_POST['testo'] != "") {
                $result['testo'] = filter_var($_POST['testo'], FILTER_SANITIZE_SPECIAL_CHARS);
            } else if ($_POST['idMessage'] != "") {
                $result['messaggioID'] = filter_var($_POST['idMessage'], FILTER_SANITIZE_SPECIAL_CHARS);
            } else {
                $result = null;
            }
        } else if ($tipologiaForm == self::FORM_INVIO_RISPOSTA) {

            /**
             * I dati da recuperare dalla form sono:
             * - testo della risposta (obbligatorio);
             * - id del thread della risposta (obbligatorio).
             */
            if ($_POST['testo'] != "" && $_POST['threadID'] != "") {
                $result['testo'] = filter_var($_POST['testo'], FILTER_SANITIZE_SPECIAL_CHARS);
                $result['threadID'] = filter_var($_POST['threadID'], FILTER_SANITIZE_SPECIAL_CHARS);
            } else {
                $result = null;
            }
        } else if ($tipologiaForm == self::FORM_PROFILO_PERSONALE) {

            /**
             * I dati da recuperare dalla form sono:
             * - nuova foto profilo;
             * - nuova password;
             * - nuovo corso di studio;
             */
            if ($_POST['nuovaFotoProfilo']['name'] != "") {
                $result['nomeNuovaFotoProfilo'] = $_POST['nuovaFotoProfilo']['name'];
                $result['dimensioneNuovaFotoProfilo']  = $_FILES['NuovaFotoProfilo']['size'];
                $result['tipoNuovaFotoProfilo']  = $_FILES['NuovaFotoProfilo']['type'];
                $result['immagineNuovaFotoProfilo']  = base64_encode(file_get_contents($_FILES['NuovaFotoProfilo']['tmp_name']));
            }
            if ($_POST['nuovaPassword'] != "") {
                $result['nuovaPassword'] = filter_var($_POST['nuovaPassword'], FILTER_SANITIZE_SPECIAL_CHARS);
            }
            if ($_POST['nuovoCorsoStudio'] != "") {
                $result['nuovoCorsoStudio'] = filter_var($_POST['nuovoCorsoStudio'], FILTER_SANITIZE_SPECIAL_CHARS);
            }
        } else {
            $result = null;
        }

        return $result;

    }

    /**
     * Visualizza la pagina relativa ad una form.
     * Le form che si possono visualizzare sono quelle relative a:
     *  - FORM DI REGISTRAZIONE;
     *  - FORM DI LOGIN;
     *  - FORM DI RECUPERO PASSWORD;
     *  - FORM DI CREAZIONE THREAD;
     *  - FORM DI CREAZIONE CATEGORIA;
     * @param int $tipologiaForm Valore che indica da quale form visualizzare.
     */
    public function showForm(int $tipologiaForm): void {

        if ($tipologiaForm == self::FORM_REGISTRAZIONE) {
            $this->smarty->display('registrazione.tpl');
        } else if ($tipologiaForm == self::FORM_LOGIN) {
            $this->smarty->display('login.tpl');
        } else if ($tipologiaForm == self::FORM_RECUPERO_PASSWORD) {
            $this->smarty->display('recupero-password.tpl');
        } else if ($tipologiaForm == self::FORM_CREAZIONE_THREAD) {
            $this->smarty->display('pagina-creazione-thread.tpl');
        } else if ($tipologiaForm == self::FORM_CREAZIONE_CATEGORIA) {
            $this->smarty->display('inserisci-categoria.tpl');
        }

    }

}