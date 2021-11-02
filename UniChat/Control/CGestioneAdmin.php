<?php
declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";
class CGestioneAdmin
{

    /**
     * Metodo incaricato di eseguire tutte le operazioni necessarie per rendere un utente un moderatore di una categoria.
     * L'esecuzione di tale metodo può essere richiesta solo dall'Admin, quindi prima di procedere viene recuperato
     * l'utente associato alla sessione.
     * Se l'utente è stato caricato in sessione allora viene eseguito un
     * controllo per assicurarsi sia effettivamente un admin, altrimenti si viene rimandati alla schermata di login.
     * Se il controllo è positivo allora si recuperano dalla base dati l'utente da rendere moderatore e la categoria a
     * cui dovrà essere assegnato, altrimenti si viene rimandati alla home page in quanto non autorizzati a compiere
     * tale operazione.
     * Se l'utente da rendere moderatore e la categoria vengono correttamente recuperati dalla base dati allora l'utente
     * viene reso moderatore e la base dati viene aggiornata, altrimenti viene visualizzato l'errore HTTP 500.
     * Se la base dati viene aggiornata correttamente allora viene caricato il pannello di controllo con un messaggio di
     * conferma, altrimenti di errore.
     * @param int $userID Identificativo dell'utente da rendere moderatore.
     * @param int $categoriaID Identificativo della categoria a cui assegnare il moderatore.
     */
    public function aggiungiModeratore(int $userID, int $categoriaID): void
    {
        $session = new USession();
        $user = $session->getValue("user");

        if (isset($user)) {

            $user = unserialize($user);
            $pm = FPersistentManager::getInstance();

            if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getId())) {

                try {
                    $categoria = $pm->load(FPersistentManager::ENTITY_CATEGORIA, FPersistentManager::PROPERTY_DEFAULT, $categoriaID);
                } catch (ValidationException $e) {
                    $categoria = null;
                }
                try {
                    $nuovoModeratore = $pm->load(FPersistentManager::ENTITY_USER, FPersistentManager::PROPERTY_DEFAULT, $userID);
                } catch (ValidationException $e) {
                    $nuovoModeratore = null;
                }

                if (isset($categoria) && isset($nuovoModeratore)) {
                    $moderatore = $user->creaModeratore($nuovoModeratore, $categoria);
                    $result = $pm->updateModeratoreCategoria($categoria, $moderatore);
                    if ($result) {
                        header('Location: /UniChat/admin/visualizzaPannelloDiControllo/conferma');
                    } else {
                        header('Location: /UniChat/admin/visualizzaPannelloDiControllo/errore');
                    }
                } else {
                    $view = new VError();
                    $view->setValoriErrore(VError::CODE_500, VError::TYPE_500 );
                    $view->showError();
                }
            } else {
                header('Location: /UniChat/');
            }
        } else {
            header('Location: /UniChat/utenti/login');
        }
    }

    /**
     * Metodo che si occupa di gestire tutte le operazioni necessarie a rimuovere un moderatore dal suo ruolo e renderlo
     * pertanto un normale utente.
     * L'esecuzione di tale metodo può essere richiesta solo dall'Admin, quindi prima di procedere viene recuperato
     * l'utente associato alla sessione.
     * Se l'utente è stato caricato in sessione allora viene eseguito un
     * controllo per assicurarsi sia effettivamente un admin, altrimenti si viene rimandati alla schermata di login.
     * Se il controllo è positivo allora si recupera dalla base dati il moderatore da rendere semplice utente, altrimenti
     * si viene rimandati alla home page in quanto non autorizzati a compiere tale operazione.
     * Se il moderatore viene correttamente recuperato dalla base dati allora viene rimosso dal suo ruolo e la basi dati
     * viene aggiornata, altrimenti viene visualizzato l'errore HTTP 500.
     * Se la base dati viene aggiornata correttamente allora viene caricato il pannello di controllo con un messaggio di
     * conferma, altrimenti di errore.
     * @param int $moderatoreID Identificativo del moderatore da rimuovere dal ruolo di moderatore.
     */
    public function rimuoviModeratore(int $moderatoreID): void
    {
        $session = new USession();
        $user = $session->getValue("user");
        if (isset($user)) {
            $user = unserialize($user);
            $pm = FPersistentManager::getInstance();
            if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getId())) {
                try {
                    $moderatore = $pm->load(FPersistentManager::ENTITY_MODERATORE, FPersistentManager::PROPERTY_DEFAULT, $moderatoreID);
                } catch (ValidationException $e) {
                    $moderatore = null;
                }
                if (isset($moderatore)) {
                    $categoriaGestita = $moderatore->getCategoriaGestita();
                    $result = $pm->rimuoviModeratoreCategoria($categoriaGestita->getId(), $moderatore);
                    if ($result) {
                        header('Location: /UniChat/admin/visualizzaPannelloDiControllo/conferma');
                    } else {
                        header('Location: /UniChat/admin/visualizzaPannelloDiControllo/errore');
                    }
                } else {
                    $view = new VError();
                    $view->setValoriErrore(VError::CODE_500, VError::TYPE_500 );
                    $view->showError();
                }
            } else {
                header('Location: /UniChat/');
            }
        } else {
            header('Location: /UniChat/utenti/Login');
        }
    }

    /**
     * Metodo responsabile di gestire tutte le operazioni necessarie a recuperare un elenco di utenti presenti nella
     * base dati, per visualizzarli nel pannello di controllo.
     * Il numero di utenti da recuperare è stabilito dalla view responsabile di visualizzare il pannello di controllo e
     * sempre da quest'ultima si recupera il numero di pagina necessario per sapere da quale riga interrogare la base
     * dati.
     * L'elenco di user può essere richiesto solo dall'Admin, quindi prima di procedere viene recuperato
     * l'utente associato alla sessione.
     * Se l'utente è stato caricato in sessione allora viene eseguito un
     * controllo per assicurarsi sia effettivamente un admin, altrimenti si viene rimandati alla schermata di login.
     * Se il controllo è positivo allora si recupera dalla view il numero di pagina, questo viene poi usato per
     * recuperare un array di utenti dalla base dati, altrimenti si viene rimandati alla home page in quanto non
     * autorizzati a compiere tale operazione.
     * Se l'operazione va a buon fine allora viene visualizzato un elenco di utenti, altrimenti viene lasciato al codice
     * presente lato client la responsabilità di mostrare un errore.
     */
    public function elencaUsers(): void
    {
        $session = new USession();
        $user = $session->getValue("user");
        if (isset($user)) {
            $user = unserialize($user);
            $pm = FPersistentManager::getInstance();
            if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getId())) {
                $vAmministrazione = new VAmministrazione();
                $numeroPaginaUtenti = $vAmministrazione->getValorePaginaUtenti();
                if (isset($numeroPaginaUtenti)) {
                    $numeroRigaUtenti = VAmministrazione::NUMERO_UTENTI_PER_PAGINA * ($numeroPaginaUtenti - 1);
                    try {
                        $utenti = $pm->loadEntities(FPersistentManager::ENTITY_USER, FPersistentManager::PROPERTY_DEFAULT, null, $numeroRigaUtenti, VAmministrazione::NUMERO_UTENTI_PER_PAGINA);
                    } catch (ValidationException $e) {
                        $utenti = null;
                    }
                    if (isset($utenti)) {
                        $utentiJson = json_encode($utenti);
                        echo $utentiJson;
                    }
                }
            } else {
                header('Location: /UniChat/');
            }
        } else {
            header('Location: /UniChat/utenti/login');
        }
    }

    /**
     * Metodo responsabile di gestire tutte le operazioni necessarie a recuperare un elenco di categorie presenti nella
     * base dati, per visualizzarli nel pannello di controllo.
     * Il numero di categorie da recuperare è stabilito dalla view responsabile di visualizzare il pannello di controllo e
     * sempre da quest'ultima si recupera il numero di pagina necessario per sapere da quale riga interrogare la base
     * dati.
     * L'elenco di categorie può essere richiesto solo dall'Admin, quindi prima di procedere viene recuperato
     * l'utente associato alla sessione.
     * Se l'utente è stato caricato in sessione allora viene eseguito un
     * controllo per assicurarsi sia effettivamente un admin, altrimenti si viene rimandati alla schermata di login.
     * Se il controllo è positivo allora si recupera dalla view il numero di pagina, questo viene poi usato per
     * recuperare un array di categorie dalla base dati, altrimenti si viene rimandati alla home page in quanto non
     * autorizzati a compiere tale operazione.
     * Se l'operazione va a buon fine allora viene visualizzato un elenco di categorie, altrimenti viene lasciato al codice
     * presente lato client la responsabilità di mostrare un errore.
     */
    public function elencaCategoriePaginate(): void
    {
        $session = new USession();
        $user = $session->getValue("user");
        if (isset($user)) {
            $user = unserialize($user);
            $pm = FPersistentManager::getInstance();
            if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getId())) {
                $vAmministrazione = new VAmministrazione();
                $numeroPaginaCategorie = $vAmministrazione->getValorePaginaCategorie();
                if (isset($numeroPaginaCategorie)) {
                    $numeroRigaCategorie = VAmministrazione::NUMERO_CATEGORIE_PER_PAGINA * ($numeroPaginaCategorie - 1);
                    try {
                        $categorie = $pm->loadEntities(FPersistentManager::ENTITY_CATEGORIA, FPersistentManager::PROPERTY_DEFAULT, null, $numeroRigaCategorie, VAmministrazione::NUMERO_CATEGORIE_PER_PAGINA);
                    } catch (ValidationException $e) {
                        $categorie = null;
                    }
                    if (isset($categorie)) {
                        $categorieJson = json_encode($categorie);
                        echo $categorieJson;
                    }
                }
            } else {
                header('/UniChat/');
            }
        } else {
            header('/UniChat/utenti/login');
        }
    }

    /**
     * Metodo responsabile di gestire tutte le operazioni necessarie a visualizzare correttamente il pannello di controllo.
     * L'esecuzione di tale metodo può essere rischiesta solo dall'Admin, quindi prima di procedere viene recuperato
     * l'utente associato alla sessione.
     * Se l'utente è stato caricato in sessione allora viene eseguito un controllo per assicurarsi sia effettivamente un
     * admin, altrimenti si viene rimandati alla schermata di login.
     * Se il controllo è positivo allora si recuperano dalla base dati il numero di utenti, il numero di categorie e tutte
     * le categorie, altrimenti si viene rimandati alla home page in quanto non si è autorizzati.
     * Se tutti i dati vengono recuperati correttamente dalla base dati allora si procede con il settare, tramite le view
     * apposite, il top della pagina, il side della pagina, la barra per la paginazione degli utenti, la barra per la
     * paginazione delle categorie ed i bottoni per rendere un utente moderatore.
     * In caso contrario allora viene visualizzato l'errore HTTP 500.
     * Il metodo può ricevere in ingresso un parametro e se questo vale 'conferma' allora viene anche settato un
     * messaggio di conferma, se vale 'errore' viene settato un messaggio di errore, altrimenti non viene settato alcun
     * errore.
     */
    public function visualizzaPannelloDiControllo(): void
    {
        $session = new USession();
        $user = $session->getValue("user");

        if (isset($user)) {
            $user = unserialize($user);
            $pm = FPersistentManager::getInstance();

            if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getId())) {
                $numeroUtenti = $pm->contaEntities(FPersistentManager::ENTITY_USER, FPersistentManager::PROPERTY_DEFAULT, null);
                $numeroCategorie = $pm->contaEntities(FPersistentManager::ENTITY_CATEGORIA, FPersistentManager::PROPERTY_DEFAULT, null);
                try {
                    $categorie = $pm->loadAllCategorie();
                } catch (ValidationException $e) {
                    $categorie = null;
                }

                if (isset($numeroUtenti) && isset($numeroCategorie) && isset($categorie)) {
                    $vPage = new VPage();
                    $vPage->setMenuUtente($user, true);
                    $vPage->setMenuLeft($categorie);
                    $vPage->setBottoneFiltra($categorie);

                    $vAmministrazione = new VAmministrazione();
                    $vAmministrazione->setPaginazioneUtenti($numeroUtenti);
                    $vAmministrazione->setPaginazioneCategorie($numeroCategorie);
                    $vAmministrazione->setBottoneAggiungiRimuoviModeratore($categorie);

                    if (func_num_args() == 1) {
                        if (func_get_arg(0) == "conferma") {
                            $vAmministrazione->setMessaggiConfermaErroreOperazioni(true);
                        } else if (func_get_arg(0) == "errore") {
                            $vAmministrazione->setMessaggiConfermaErroreOperazioni(false);
                        } else {
                            $vAmministrazione->setMessaggiConfermaErroreOperazioni(null);
                        }
                    } else {
                        $vAmministrazione->setMessaggiConfermaErroreOperazioni(null);
                    }

                    $vAmministrazione->showPannelloDiControllo();
                } else {
                    $view = new VError();
                    $view->setValoriErrore(VError::CODE_500, VError::TYPE_500 );
                    $view->showError();
                }
            } else {
                header('Location: /UniChat/');
            }
        } else {
            header('Location: /UniChat/utenti/login');
        }
    }
}