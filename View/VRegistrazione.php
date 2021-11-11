<?php
require_once __DIR__ . "\..\utility.php";
require_once "VSmarty.php";

class VRegistrazione
{
    private Smarty $smarty;

    public function __construct() {
        $this->smarty = VSmarty::start();
    }

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

    public function setOperazioneFallita(bool $visualizza): void
    {
        $this->smarty->assign('erroreOperazione', false);
        $this->smarty->assign('messaggioErroreOperazione', "");
        if ($visualizza) {
            $this->smarty->assign('erroreOperazione', true);
            $this->smarty->assign('messaggioErroreOperazione', "Registrazione non riuscita.");
        }

    }

    public function setCampiObbligatoriMancanti(bool $visualizza): void
    {
        $this->smarty->assign('erroreDatiObbligatori', false);
        $this->smarty->assign('messaggioErroreDatiObbligatori', "");
        if ($visualizza) {
            $this->smarty->assign('erroreDatiObbligatori', true);
            $this->smarty->assign('messaggioErroreDatiObbligatori', "Dati obbligatori mancanti.");
        }
    }

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

    public function showRegistrazione(): void
    {
        $this->smarty->display('registrazione.tpl');
    }
}