<?php
declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";
require_once "VSmarty.php";

class VAmministrazione
{
    private Smarty $smarty;
    const NUMERO_UTENTI_PER_PAGINA = 6;
    const NUMERO_CATEGORIE_PER_PAGINA = 6;

    public function __construct() {
        $this->smarty = VSmarty::start();
    }

    public function setPaginazioneUtenti(int $numeroUtenti): void {

        $numeroPagine = ceil($numeroUtenti/self::NUMERO_UTENTI_PER_PAGINA);
        $this->smarty->assign('numeroPagineUtenti', $numeroPagine);

    }

    public function setPaginazioneCategorie(int $numeroCategorie): void {

        $numeroPagine = ceil($numeroCategorie/self::NUMERO_CATEGORIE_PER_PAGINA);
        $this->smarty->assign('numeroPagineCategorie', $numeroPagine);

    }

    public function setBottoneAggiungiRimuoviModeratore(array $categorie): void {

        $this->smarty->assign('elencoCategorie', $categorie);

    }

    public function setMessaggiConfermaErroreOperazioni(?bool $esitoOperazione): void {

        $this->smarty->assign('conferma', false);
        $this->smarty->assign('errore', false);
        $this->smarty->assign('messaggioConferma', '');
        $this->smarty->assign('messaggioErrore', '');
        if (isset($esitoOperazione)) {
            if ($esitoOperazione) {
                $this->smarty->assign('conferma', true);
                $this->smarty->assign('messaggioConferma', "L'operazione è stata eseguita con successo.");
            } else {
                $this->smarty->assign('errore', true);
                $this->smarty->assign('messaggioError', 'Si è verificato un errore, riprovare.');
            }
        }

    }

    public function getValorePaginaUtenti(): ?int {
        if (isset($_POST['pagina'])) {
           return (int)$_POST['pagina'];
        } else {
            return null;
        }
    }

    public function getValorePaginaCategorie(): ?int {
        if (isset($_POST['pagina'])) {
            return (int)$_POST['pagina'];
        } else {
            return null;
        }
    }

    public function getValoreIdCategoriaModeratore(): ?int {
        if (isset($_POST['categoriaID'])) {
            return (int)$_POST['categoriaID'];
        } else {
            return null;
        }
    }

    public function getValoreIdCategoriaContaThreads(): ?int {
        if (isset($_POST['categoriaID'])) {
            return (int)$_POST['categoriaID'];
        } else {
            return null;
        }
    }

    public function showPannelloDiControllo(): void  {

        $this->smarty->display('pannello-di-controllo.tpl');

    }
}