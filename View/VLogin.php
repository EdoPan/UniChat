<?php
declare(strict_types = 1);
require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "utility.php";
require_once "VSmarty.php";

/**
 * Classe a cui è affidata la gestione della visualizzazione della form di login.
 */
class VLogin
{
    /**
     * Istanza del template engine.
     * @var Smarty
     */
    private $smarty;

    public function __construct() {

        $this->smarty = VSmarty::start();

    }

    /**
     * Impostazione del messaggio di errore nel caso venissero inseriti dati non validi (email e/o password).
     * @param int|null $codiceErrore
     * @param string|null $messaggioErrore
     */

    public function setErroreValidazione(?int $codiceErrore, ?string $messaggioErrore): void
    {
        $this->smarty->assign('erroreEmail', false);
        $this->smarty->assign('errorePassword', false);
        $this->smarty->assign('messaggioErroreEmail', '');
        $this->smarty->assign('messaggioErrorePassword', '');

        if (isset($codiceErrore) && isset($messaggioErrore)) {
            if ($codiceErrore == ValidationException::ERROR_EMAIL_CODE) {
                $this->smarty->assign('erroreEmail', true);
                $this->smarty->assign('messaggioErroreEmail', $messaggioErrore);
            } else if ($codiceErrore == ValidationException::ERROR_PASSWORD_CODE || $codiceErrore == ValidationException::ERROR_PASSWORD_LENGTH_CODE) {
                $this->smarty->assign('errorePassword', true);
                $this->smarty->assign('messaggioErrorePassword', $messaggioErrore);
            }
        }
    }

    /**
     * Impostazione del messaggio di errore nel caso venissero inserite credenziali errate (email e/o password).
     * @param bool $visualizza
     */

    public function setCredenzialiErrate(bool $visualizza): void
    {
        $this->smarty->assign('credenzialiErrate', false);
        $this->smarty->assign('messaggioCredenzialiErrate', "");
        if ($visualizza) {
            $this->smarty->assign('credenzialiErrate', true);
            $this->smarty->assign('messaggioCredenzialiErrate', "Email o password non corrette.");
        }
    }

    /**
     * Impostazione del messaggio di errore nel caso venisse inviata la form senza
     * aver compilato i campi obbligatori.
     * @param bool $visualizza
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
     * Recupero dei dati inseriti nella Form.
     * I dati da recuperare sono:
     * - email (obbligatorio)
     * - password (obbligatorio)
     * @return array|null
     */

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

    /**
     * Display del template della pagina di Login.
     */

    public function showLogin(): void
    {
        $this->smarty->display('login.tpl');
    }
}