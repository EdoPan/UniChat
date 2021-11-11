<?php
require_once __DIR__ . "\..\utility.php";
require_once "VSmarty.php";

class VCreazioneCategoria
{

    private Smarty $smarty;

    public function __construct() {

        $this->smarty = VSmarty::start();

    }

    public function getSmarty(): Smarty
    {
        return $this->smarty;
    }

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
     * I dati da recuperare dalla form sono:
     * - nome della categoria (obbligatorio);
     * - descrizione della categoria (obbligatorio);
     * - icona.
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

    public function showCreaCategoria(): void {

        $this->smarty->display('inserisci-categoria.tpl');


    }

}