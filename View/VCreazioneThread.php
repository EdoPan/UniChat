<?php
require_once __DIR__ . "\..\utility.php";
require_once "VSmarty.php";

/**
 * Classe a cui è affidata la gestione della visualizzazione della form di creazione thread.
 */
class VCreazioneThread
{

    private Smarty $smarty;

    public function __construct() {

        $this->smarty = VSmarty::start();

    }

    public function getSmarty(): Smarty
    {
        return $this->smarty;
    }

    /**
     * Impostazione dell'errore da visualizzare in caso di invio della form senza aver inserito
     * dati nei campi obbligatori.
     * @param bool $campiMancanti
     */

    public function setCampiObbligatoriMancanti(bool $campiMancanti): void
    {
        if ($campiMancanti) {
            $this->smarty->assign('erroreDatiObbligatori', true);
            $this->smarty->assign('messggioErroreDatiObbligatori', "Dati obbligatori mancanti.");
        } else {
            $this->smarty->assign('erroreDatiObbligatori', false);
            $this->smarty->assign('messggioErroreDatiObbligatori', "");
        }
    }

    /**
     * Impostazione dell'id e del nome della Categoria necessari all'invio e alla
     * visualizzazione della form di creazione thread.
     * @param ECategoria $categoria
     */

    public function setCategoriaCreazioneThread(ECategoria $categoria): void {
        $categoriaID = $categoria->getId();
        $categoriaNome = $categoria->getNome();

        $this->smarty->assign('categoriaID', $categoriaID);
        $this->smarty->assign('categoriaNome', $categoriaNome);
    }

    /**
     * Impostazione del messaggio di errore da visualizzare in caso di caricamento di allegati
     * non validi.
     * @param int|null $codiceErrore
     * @param string|null $messaggioErrore
     */

    public function setErroreValidazione(?int $codiceErrore, ?string $messaggioErrore): void {

        $this->smarty->assign('erroreAllegato', false);
        $this->smarty->assign('messaggioErroreAllegato', '');

        if ($codiceErrore == ValidationException::ERROR_ATTACHMENT_MIME_TYPE_CODE || $codiceErrore == ValidationException::ERROR_ATTACHMENT_SIZE_CODE) {
            $this->smarty->assign('erroreAllegato', true);
            $this->smarty->assign('messaggioErroreAllegato', $messaggioErrore);
        }

    }

    /**
     * Recupero dei valori inseriti nella form di creazione thread.
     * I dati da recuperare sono
     * - titolo del thread (campo obbligatorio)
     * - descrizione del thread (campo obbligatorio)
     * - allegati (non obbligatorio)
     * @return array|null
     */

    public function getValori(): ?array {

        $result = array();

        if ($_POST['titolo'] != "" && $_POST['testo'] != "") {

            $result['titolo'] = filter_var($_POST['titolo'], FILTER_SANITIZE_SPECIAL_CHARS);
            $result['testo'] = filter_var($_POST['testo'], FILTER_SANITIZE_SPECIAL_CHARS);
            if($_FILES['allegati']['name'][0] != "") {
                $allegati = array();
                $numeroAllegati = count($_FILES['allegati']['name']);
                for ($i = 0; $i < $numeroAllegati; $i++) {
                    $allegato = array();
                    $allegato['nome'] = $_FILES['allegati']['name'][$i];
                    $allegato['dimensione'] = $_FILES['allegati']['size'][$i];
                    $allegato['tipo'] = $_FILES['allegati']['type'][$i];
                    $allegato['file'] = base64_encode(file_get_contents($_FILES['allegati']['tmp_name'][$i]));
                    $allegati[] = $allegato;
                }
                $result['allegati'] = $allegati;
            }

        } else {

            $result = null;
        }

            return $result;

    }


    /**
     * Impostazione della visualizzazione del template della pagina di Creazione di un nuovo Thread.
     */
    public function showCreaThread(): void {

        $this->smarty->display('pagina-creazione-thread.tpl');


    }

}