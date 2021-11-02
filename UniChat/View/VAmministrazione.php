<?php
declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";
require_once "VSmarty.php";

class VAmministrazione
{
    /**
     * @var Smarty
     */
    private Smarty $smarty;

    /**
     * Costante che indica il numero di utenti da visualizzare, volta per volta, nella pagina del pannello di controllo.
     */
    const NUMERO_UTENTI_PER_PAGINA = 6;

    /**
     * Costante che indica il numero di categorie da visualizzare, volta per volta, nella pagina del pannello di controllo.
     */
    const NUMERO_CATEGORIE_PER_PAGINA = 6;

    /**
     * Costruttore, inizializza Smarty.
     */
    public function __construct() {
        $this->smarty = VSmarty::start();
    }

    /**
     * Imposta il numero di pagine necessarie da visualizzare nella barra di paginazione degli utenti del pannello di
     * controllo. Per calcolare tale numero è necessario fornire in ingresso il numero di utenti attualmente registrati
     * e viene sfruttata la costante indicante il numero di utenti da voler visualizzare in ciascuna pagina.
     * @param int $numeroUtenti Numero di utenti registrati.
     */
    public function setPaginazioneUtenti(int $numeroUtenti): void {

        $numeroPagine = ceil($numeroUtenti/self::NUMERO_UTENTI_PER_PAGINA);
        $this->smarty->assign('numeroPagineUtenti', $numeroPagine);

    }

    /**
     * Imposta il numero di pagine necessarie da visualizzare nella barra di paginazione delle categorie del pannello di
     * controllo. Per calcolare tale numero è necessario fornire in ingresso il numero di categorie attualmente registrate
     * e viene sfruttata la costante indicante il numero di categorie da voler visualizzare in ciascuna pagina.
     * @param int $numeroCategorie Numero di categorie registrate.
     */
    public function setPaginazioneCategorie(int $numeroCategorie): void {

        $numeroPagine = ceil($numeroCategorie/self::NUMERO_CATEGORIE_PER_PAGINA);
        $this->smarty->assign('numeroPagineCategorie', $numeroPagine);

    }

    /**
     * Imposta i bottoni per rendere un utente, moderatore di una categoria.
     * Si tratta si un bottone contenente tutte le categorie registrate.
     * @param array $categorie Categorie registrate.
     */
    public function setBottoneAggiungiRimuoviModeratore(array $categorie): void {

        $this->smarty->assign('elencoCategorie', $categorie);

    }

    /**
     * Imposta o meno il messaggio di conferma o di errore relativo ad una operazione compiuta dall'admin nel pannello
     * di controllo.
     * Se in ingresso viene fornito true allora viene settato il messaggio di operazione riuscita, se viene fornito
     * false allora viene settato il messaggio di errore e se invece viene fornito null allora vuol dire che non è
     * stata compiuta nessuna operazione e quindi non è necessario settare un messaggio.
     * @param bool|null $esitoOperazione Esito operazione compiuta dall'admin.
     */
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
                $this->smarty->assign('messaggioErrore', 'Si è verificato un errore, riprovare.');
            }
        }

    }

    /**
     * Restituisce il numero di pagina della sezione utenti che è stato appena cliccato nel pannello di controllo.
     * Tale valore viene recuperato da una richiesta di tipo POST.
     * Se nella richiesta non c'è un valore valido allora viene restituito null.
     * @return int|null Numero di pagina della sezione utenti che è stato appena cliccato.
     */
    public function getValorePaginaUtenti(): ?int {
        if ($_POST['pagina'] != "") {
           return (int)$_POST['pagina'];
        } else {
            return null;
        }
    }

    /**
     * Restituisce il numero di pagina della sezione categorie, che è stato appena cliccato nel pannello di controllo.
     * Tale valore viene recuperato da una richiesta di tipo POST.
     * Se nella richiesta non c'è un valore valido allora viene restituito null.
     * @return int|null Numero di pagina della sezione categorie che è stato appena cliccato.
     */
    public function getValorePaginaCategorie(): ?int {
        if ($_POST['pagina'] != "") {
            return (int)$_POST['pagina'];
        } else {
            return null;
        }
    }

    /**
     * Restituisce il valore dell'identificativo della categoria di cui si vuole conoscere il moderatore, in modo tale
     * da poterlo settare nella sezione categorie del pannello di controllo.
     * Tale valore viene recuperato da una richiesta di tipo POST.
     * Se nella richiesta non c'è un valore valido allora viene restituito null.
     * @return int|null Identificativo della categoria di cui si vuole conoscere il moderatore.
     */
    public function getValoreIdCategoriaModeratore(): ?int {
        if ($_POST['categoriaID'] != "") {
            return (int)$_POST['categoriaID'];
        } else {
            return null;
        }
    }

    /**
     * Restituisce il valore dell'identificativo della categoria di cui si vuole conoscere il numero di threads, in modo
     * tale da poterlo settare nella sezione categorie del pannello di controllo.
     * Tale valore viene recuperato da una richiesta di tipo POST.
     * Se nella richiesta non c'è un valore valido allora viene restituito null.
     * @return int|null Identificativo della categoria di cui si vuole conoscere il numero di thread.
     */
    public function getValoreIdCategoriaContaThreads(): ?int {
        if ($_POST['categoriaID'] != "") {
            return (int)$_POST['categoriaID'];
        } else {
            return null;
        }
    }

    /**
     * Visualizza la pagina relativa al pannello di controllo.
     */
    public function showPannelloDiControllo(): void  {

        $this->smarty->display('pannello-di-controllo.tpl');

    }
}