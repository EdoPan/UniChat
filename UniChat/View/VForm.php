<?php

declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";
require_once "VSmarty.php";


class VForm
{

    private Smarty $smarty;

    const FORM_REGISTRAZIONE = 0;
    const FORM_LOGIN = 1;
    const FORM_RECUPERO_PASSWORD = 2;
    const FORM_RICERCA = 3;
    const FORM_CREAZIONE_THREAD = 4;
    const FORM_CREAZIONE_CATEGORIA = 5;
    const FORM_INVIO_MESSAGGIO = 6;
    const FORM_INVIO_RISPOSTA = 7;
    const FORM_PROFILO_PERSONALE = 8;

    public function __construct() {

        $this->smarty = VSmarty::start();

    }

    public function setCategoriaCreazioneThread(ECategoria $categoria): void {
        $categoriaID = $categoria->getId();
        $categoriaNome = $categoria->getNome();

        $this->smarty->assign('categoriaID', $categoriaID);
        $this->smarty->assign('categoriaNome', $categoriaNome);
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

    public function getValori(int $tipologiaForm): ?array {

        $result = array();
        if ($tipologiaForm == self::FORM_REGISTRAZIONE) {
            if (isset($_POST['nome']) && isset($_POST['cognome']) && isset($_POST['email']) && $_POST['password']) {
                $result['nome'] = filter_var($_POST['nome'], FILTER_SANITIZE_SPECIAL_CHARS);
                $result['cognome'] = filter_var($_POST['cognome'], FILTER_SANITIZE_SPECIAL_CHARS);
                $result['email'] = filter_var($_POST['email'], FILTER_SANITIZE_SPECIAL_CHARS);
                $result['password'] = filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS);
                if (isset($_POST['corsoStudio'])) {
                    $result['corsoStudio'] = filter_var($_POST['corsoStudio'], FILTER_SANITIZE_SPECIAL_CHARS);
                }
                if (isset($_FILES['fotoProfilo'])) {
                    $result['nomeFotoProfilo'] = $_FILES['fotoProfilo']['name'];
                    $result['dimensioneFotoProfilo'] = $_FILES['fotoProfilo']['size'];
                    $result['tipoFotoProfilo'] = $_FILES['fotoProfilo']['type'];
                    $result['immagineFotoProfilo'] = $_FILES['fotoProfilo']['tmp_name'];
                }
            }
        }
        if ($tipologiaForm == self::FORM_LOGIN) {
            if (isset($_POST['email']) && isset($_POST['password'])) {
                $result['email'] = filter_var($_POST['email'], FILTER_SANITIZE_SPECIAL_CHARS);
                $result['password'] = filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS);
            } else{
                $result = null;
            }
        }
        if ($tipologiaForm == self::FORM_RECUPERO_PASSWORD) {
          if (isset($_POST['email'])) {
             $result['email'] = filter_var($_POST['email'], FILTER_SANITIZE_SPECIAL_CHARS);
          }  else {
              $result = null;
          }
        } else if ($tipologiaForm == self::FORM_RICERCA) {
            if (isset($_POST['testoricerca'])) {
                $result['testoRicerca'] = filter_var($_POST['testoricerca'], FILTER_SANITIZE_SPECIAL_CHARS);
                if (isset($_POST['categoriaID'])) {
                    $result['categoriaID'] = filter_var($_POST['categoriaID'], FILTER_SANITIZE_SPECIAL_CHARS);
                }
            } else {
                $result = null;
            }
        } else if ($tipologiaForm == self::FORM_CREAZIONE_THREAD) {
            if (isset($_POST['titolo']) && isset($_POST['testo']) && isset($_POST['categoriathread'])) {
                $result['titolo'] = filter_var($_POST['titolo'], FILTER_SANITIZE_SPECIAL_CHARS);
                $result['testo'] = filter_var($_POST['testo'], FILTER_SANITIZE_SPECIAL_CHARS);
                $result['categoriaID'] = filter_var($_POST['categoriathread'], FILTER_SANITIZE_SPECIAL_CHARS);
                if(isset($_FILES['allegati'])) {
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
        }else if ($tipologiaForm == self::FORM_CREAZIONE_CATEGORIA) {
            if (isset($_POST['nome']) && isset($_POST['descrizione'])) {
                $result['nome'] = filter_var($_POST['nome'], FILTER_SANITIZE_SPECIAL_CHARS);
                $result['descrizione'] = filter_var($_POST['descrizione'], FILTER_SANITIZE_SPECIAL_CHARS);
                if (isset($_FILES['icona'])) {
                    $result['nomeIcona']  = $_FILES['icona']['name'];
                    $result['dimensioneIcona']  = $_FILES['icona']['size'];
                    $result['tipoIcona']  = $_FILES['icona']['type'];
                    $result['immagineIcona']  = base64_encode(file_get_contents($_FILES['icona']['tmp_name']));
                }
            } else {
                $result = null;
            }
        } else if ($tipologiaForm == self::FORM_INVIO_MESSAGGIO) {
            if (isset($_POST['testo'])) {
                $result['testo'] = filter_var($_POST['testo'], FILTER_SANITIZE_SPECIAL_CHARS);
            } else {
                $result = null;
            }
        } else if ($tipologiaForm == self::FORM_INVIO_RISPOSTA) {
            if (isset($_POST['testo']) && isset($_POST['threadID'])) {
                $result['testo'] = filter_var($_POST['testo'], FILTER_SANITIZE_SPECIAL_CHARS);
                $result['threadID'] = filter_var($_POST['threadID'], FILTER_SANITIZE_SPECIAL_CHARS);
            } else {
                $result = null;
            }
        } else if ($tipologiaForm == self::FORM_PROFILO_PERSONALE) {
            if (isset($_POST['nuovaFotoProfilo'])) {
                $result['nomeNuovaFotoProfilo'] = $_POST['nuovaFotoProfilo']['name'];
                $result['dimensioneNuovaFotoProfilo']  = $_FILES['NuovaFotoProfilo']['size'];
                $result['tipoNuovaFotoProfilo']  = $_FILES['NuovaFotoProfilo']['type'];
                $result['immagineNuovaFotoProfilo']  = base64_encode(file_get_contents($_FILES['NuovaFotoProfilo']['tmp_name']));
            }
            if (isset($_POST['nuovaPassword'])) {
                $result['nuovaPassword'] = filter_var($_POST['nuovaPassword'], FILTER_SANITIZE_SPECIAL_CHARS);
            }
            if (isset($_POST['nuovoCorsoStudio'])) {
                $result['nuovoCorsoStudio'] = filter_var($_POST['nuovoCorsoStudio'], FILTER_SANITIZE_SPECIAL_CHARS);
            }
        } else {
            $result = null;
        }

        return $result;

    }

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