<?php
require_once __DIR__ . "\..\utility.php";
require_once "VSmarty.php";

class VLogin
{
    private Smarty $smarty;

    public function __construct() {

        $this->smarty = VSmarty::start();

    }

    public function setErroreValidazione(?int $codiceErrore, ?string $messaggioErrore): void
    {
        $this->smarty->assign('erroreEmail', false);
        $this->smarty->assign('errorePassword', false);
        $this->smarty->assign('messaggioErroreEmail', '');
        $this->smarty->assign('messaggioErrorePassword', '');

        if (isset($codiceErrore) && isset($messaggioErrore)) {
            if ($codiceErrore == ValidationException::ERROR_EMAIL_CODE || $codiceErrore == ValidationException::ERROR_EMAIL_LENGTH_CODE ) {
                $this->smarty->assign('erroreEmail', true);
                $this->smarty->assign('messaggioErroreEmail', $messaggioErrore);
            } else if ($codiceErrore == ValidationException::ERROR_PASSWORD_CODE || $codiceErrore == ValidationException::ERROR_PASSWORD_LENGTH_CODE) {
                $this->smarty->assign('errorePassword', true);
                $this->smarty->assign('messaggioErrorePassword', $messaggioErrore);
            }
        }
    }

    public function setCredenzialiErrate(bool $visualizza): void
    {
        $this->smarty->assign('credenzialiErrate', false);
        $this->smarty->assign('messaggioCredenzialiErrate', "");
        if ($visualizza) {
            $this->smarty->assign('credenzialiErrate', true);
            $this->smarty->assign('messaggioCredenzialiErrate', "Email o password non corrette.");
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

    public function getValori(): ?array
    {
        $result = array();
        if ($_POST['email'] != "" && $_POST['password'] != "") {
            $result['email'] = filter_var($_POST['email'], FILTER_SANITIZE_SPECIAL_CHARS);
            $result['password'] = filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS);
        } else{
            $result = null;
        }
        return $result;
    }

    public function showLogin(): void
    {
        $this->smarty->display('login.tpl');
    }
}