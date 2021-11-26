<?php
declare(strict_types = 1);
require_once __DIR__.DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "utility.php";

/**
 * Classe responsabile dell'esecuzione dei casi d'uso in cui è necessaria l'interazione con un Admin.
 */
class CGestioneAdmin
{

    /**
     * Gestisce tutte le operazioni necessarie alla rimozione di un utente, notare che tale operazione non comporta
     * l'eliminazione dei contenuti da esso prodotti: vengono assegnati ad un utente di default.
     * Se l'utente era un moderatore, allora viene anche rimosso dalla categoria che gestiva.
     * Tale metodo può essere eseguito solo da un utente loggato e in particolare solo se quell'utente è l'admin.
     * Quindi la prima operazione da fare è verificare che nella sessione vi sia la variabile utente, in caso contrario
     * si viene rimandati alla pagina di login.
     * Se la variabile è presente nella sessione allora si procede a verificare che sia un admin, in caso contrario si
     * viene rimandati alla home page in quanto non si è autorizzati a procedere oltre.
     * Se l'utente è un admin allora si procede ad eliminare l'utente dalla base dati.
     * Se l'operazione va a buon fine allora si viene rimandati alla pagina del pannello di controllo e viene
     * visualizzato un messaggio di operazione riuscita, altrimenti si viene comunque rimandati alla pagina del pannello
     * di controllo, ma viene visualizzato un messaggio di operazione fallita.
     * @param int $userID Identificativo dell'utente da eliminare.
     */
    public function rimuoviUtente(int $userID): void
    {
        $session = new USession();
        $user = $session->getValue('user');
        if (isset($user)) {
            $user = unserialize($user);
            $pm = FPersistentManager::getInstance();
            if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getId())) {
                $result = $pm->delete(FPersistentManager::ENTITY_USER, $userID);
                if ($result) {
                    header('Location: /UniChat/admin/visualizzaPannelloDiControllo/conferma');
                } else {
                    header('Location: /UniChat/admin/visualizzaPannelloDiControllo/errore');
                }
            } else {
                /*
                 * Se l'utente presente nella variabile di sessione non è un admin allora non deve avere accesso a tale
                 * operazione e viene quindi riportato sulla home page.
                 */
                header('Location: /UniChat');
            }
        } else {
            /*
             * Se la variabile di sessione non è presente allora vuol dire che non si risulta loggati, si viene quindi
             * rimandati nella pagina di login per effettuare l'accesso.
             */
            header('Location: /UniChat/utenti/login');
        }
    }

    /**
     * Metodo incaricato di eseguire tutte le operazioni necessarie per rendere un utente un moderatore di una categoria.
     * L'esecuzione di tale metodo può essere richiesta solo dall'Admin, quindi prima di procedere viene recuperato
     * l'utente associato alla variabilie di sessione.
     * Se l'utente è stato caricato nella variabile di sessione allora viene eseguito un
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
                    /*
                     * Recupero della categoria a cui si deve assegnare un moderatore.
                     */
                    $categoria = $pm->load(FPersistentManager::ENTITY_CATEGORIA, FPersistentManager::PROPERTY_DEFAULT, $categoriaID);
                } catch (ValidationException $e) {
                    $categoria = null;
                }
                try {
                    /*
                     * Recupero dell'utente da rendere moderatore.
                     */
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
                    /*
                     * Se non si riesce a recuperare l'utente o la categoria dalla base dati, oppure queste presentano
                     * problemi con la validazione dei dati allora vuol dire che ci sono dei problemi con la comunicazione
                     * con la base dati o che i dati presenti in essa sono stati cambiati manualmente e presentano errori.
                     */
                    $view = new VError();
                    $view->setValoriErrore(VError::CODE_500, VError::TYPE_500 );
                    $view->showError();
                }
            } else {
                /*
                 * Se l'utente presente nella variabile di sessione non è un admin allora non deve avere accesso a tale
                 * operazione e viene quindi riportato sulla home page.
                 */
                header('Location: /UniChat');
            }
        } else {
            /*
             * Se la variabile di sessione non è presente allora vuol dire che non si risulta loggati, si viene quindi
             * rimandati nella pagina di login per effettuare l'accesso.
             */
            header('Location: /UniChat/utenti/login');
        }
    }

    /**
     * Metodo che si occupa di gestire tutte le operazioni necessarie a rimuovere un moderatore dal suo ruolo e renderlo
     * pertanto un normale utente.
     * L'esecuzione di tale metodo può essere richiesta solo dall'Admin, quindi prima di procedere viene recuperato
     * l'utente presente nella variabile di sessione.
     * Se la variabile di sessione esiste allora viene eseguito un controllo per assicurarsi chr l'utente sia
     * effettivamente un admin, altrimenti si viene rimandati alla schermata di login.
     * Se l'utente è un admin allora si controlla che l'utente di cui si è ottenuto l'identificativo in ingresso sia un
     * moderatore, altrimenti si viene rimandati alla home page in quanto non autorizzati a compiere tale operazione.
     * Se il controllo è positivo allora si recupera dalla base dati il moderatore da rendere semplice utente, altrimenti
     * viene ricaricata la pagina del pannello di controllo con un messaggio di avviso che comunica che l'utente non è
     * un moderatore.
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
                if ($pm->isA(FPersistentManager::ENTITY_MODERATORE, $moderatoreID)) {
                    try {
                        /*
                         * Recupero dell'utente moderatore.
                         */
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
                        /*
                         * Se non si riesce a recuperare il moderatore oppure questo presenta problemi con la validazione
                         * dei dati allora vuol dire che ci sono dei problemi con la comunicazione con la base dati o
                         * che i dati presenti in essa sono cambiati manualmente e presentano errori.
                         */
                        $view = new VError();
                        $view->setValoriErrore(VError::CODE_500, VError::TYPE_500);
                        $view->showError();
                    }
                } else {
                    header('Location: /UniChat/admin/visualizzaPannelloDiControllo/avviso');
                }
            } else {
                /*
                 * Se l'utente presente nella variabile di sessione non è un admin allora non deve avere accesso a tale
                 * operazione e viene quindi riportato sulla home page.
                 */
                header('Location: /UniChat');
            }
        } else {
            /*
             * Se la variabile di sessione non è presente allora vuol dire che non si risulta loggati, si viene quindi
             * rimandati nella pagina di login per effettuare l'accesso.
             */
            header('Location: /UniChat/utenti/Login');
        }
    }

    /**
     * Gestisce tutte le operazioni necessarie alla creazione di una nuova categoria.
     * Tale metodo può essere eseguito solo da un utente loggato e in particolare solo se quell'utente è l'admin.
     * Quindi la prima operazione da fare è verificare che nella sessione sia presente la variabile utente, in caso
     * contrario si viene rimandati alla pagina di login.
     * Se l'utente è presente nella sessione allora si procede a verificare che sia un admin, in caso contrario si
     * viene rimandati alla home page in quanto non si è autorizzati a procedere oltre.
     * Se l'utente è un admin allora si verifica che la richiesta del client sia una HTTP GET, in tal caso viene
     * chiesto alla view responsabile, di mostrare la pagina contente la form di creazione di una nuova categoria.
     * Se la richiesta del client, invece, è una HTTP POST allora si chiede alla view responsabile, di recuperare i
     * dati immessi dall'utente nella form.
     * In questo ultimo caso, se l'utente non ha fornito tutti i dati obbligatori, allora si ottiene un messaggio di
     * errore e viene chiesto di fornire nuovamente i dati.
     * Se durante la creazione della categoria viene sollevata una eccezione dovuto ad un esito negativo della validazione
     * dei dati, questa viene gestita mostrando nuovamente la pagina contenente la form e un messaggio di errore.
     * Se la categoria viene creata correttamente allora viene memorizzata nella base dati e se l'operazione va a buon
     * fine allora si viene rimandati alla pagina del pannello di controllo e viene visualizzato un messaggio di operazione
     * riuscita, altrimenti si viene comunque rimandati alla pagina del pannello di controllo, ma viene visualizzato
     * un messaggio di operazione fallita.
     */
    public function aggiungiCategoria(): void
    {
        $session = new USession();
        $user = $session->getValue('user');
        if (isset($user)) {
            $user = unserialize($user);
            $pm = FPersistentManager::getInstance();
            if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getId())) {

                try {
                    /*
                     * Recupero delle categorie.
                     */
                    $categorie = $pm->loadAllCategorie();
                } catch (ValidationException $e) {
                    $categorie = null;
                }

                if (isset($categorie)) {
                    $vCreaCategoria = new VCreazioneCategoria();

                    $vPage = new VPage($vCreaCategoria->getSmarty());
                    $vPage->setBottoneFiltra($categorie);
                    $vPage->setMenuLeft($categorie);
                    $vPage->setMenuUtente($user, true);

                    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                        /*
                         * Visualizza la pagina contenente la form per immettere i dati necessari a creare una nuova
                         * categoria.
                         */
                        $vCreaCategoria->setErroreValidazione(null, null);
                        $vCreaCategoria->setCampiObbligatoriMancanti(false);
                        $vCreaCategoria->showCreaCategoria();


                    } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                        /*
                         * Recupera e gestisce i dati forniti dall'utente allo scopo di creare una nuova categoria.
                         */
                        $valori = $vCreaCategoria->getValori();

                        if (isset($valori)) {
                            $nome = $valori['nome'];
                            $descrizione = $valori['descrizione'];
                            if (array_key_exists('nomeIcona', $valori)) {
                                $icona = array();
                                $icona['id'] = 0;
                                $icona['nome'] = $valori['nomeIcona'];
                                $icona['dimensione'] = $valori['dimensioneIcona'];
                                $icona['tipo'] = $valori['tipoIcona'];
                                $icona['immagine'] = $valori['immagineIcona'];
                            } else {
                                $icona = null;
                            }

                            try {
                                $categoria = $user->creaCategoria(null, $nome, $icona, $descrizione);
                                $result = $pm->store(FPersistentManager::ENTITY_CATEGORIA, $categoria);

                                if ($result) {
                                    header('Location: /UniChat/admin/visualizzaPannelloDiControllo/conferma');
                                } else {
                                    header('Location: /UniChat/admin/visualizzaPannelloDiControllo/errore');
                                }
                            } catch (ValidationException $e) {

                                /*
                                 * Se l'utente ha fornito in ingresso dati che non rispettano il formato richiesto allora
                                 * viene riproposta la pagina di creazione di una nuova categoria con un messaggio
                                 * di errore.
                                 */
                                $vCreaCategoria->setErroreValidazione($e->getCode(), $e->getMessage());
                                $vCreaCategoria->setCampiObbligatoriMancanti(false);
                                $vCreaCategoria->showCreaCategoria();
                            }
                        } else {
                            /*
                             * Se non sono presenti valori allora vuol dire che l'utente non ha fornito tutti i dati
                             * obbligatori.
                             * In tal caso viene riproposta la pagina di creazione di una nuova categoria con un messaggio
                             * di errore.
                             */
                            $vCreaCategoria->setCampiObbligatoriMancanti(true);
                            $vCreaCategoria->setErroreValidazione(null, null);
                            $vCreaCategoria->showCreaCategoria();

                        }
                    }
                } else {
                    /*
                     * Se non si riescono a recuperare le categorie oppure queste presentano problemi con la validazione
                     * dei dati allora vuol dire che ci sono dei problemi con la comunicazione con la base dati o
                     * che i dati presenti in essa sono stati cambiati manualmente e presentano errori.
                     */
                    $view = new VError();
                    $view->setValoriErrore(VError::CODE_500, VError::TYPE_500);
                    $view->showError();
                }
            } else {
                /*
                 * Se l'utente presente nella variabile di sessione non è un admin allora non deve avere accesso a tale
                 * operazione e viene quindi riportato sulla home page.
                 */
                header('Location: /UniChat');
            }
        } else {
            /*
             * Se la variabile di sessione non è presente allora vuol dire che non si risulta loggati, si viene quindi
             * rimandati nella pagina di login per effettuare l'accesso.
             */
            header('Location: /UniChat/utenti/login');
        }
    }

    /**
     * Gestisce tutte le operazioni necessarie alla rimozione di una categoria, notare che tale operazione non comporta
     * l'eliminazione dei thread in essa presenti; i threads vengono spostati nella categoria di default.
     * Tale metodo può essere eseguito solo da un utente loggato e in particolare solo se quell'utente è l'admin.
     * Quindi la prima operazione da fare è verificare che nella sessione sia presente la variabile utente, in caso contrario
     * si viene rimandati alla pagina di login.
     * Se l'utente è presente nella sessione allora si procede a verificare che sia un admin, in caso contrario si
     * viene rimandati alla home page in quanto non si è autorizzati a procedere oltre.
     * Se l'utente è un admin allora si procede ad eliminare la categoria dalla base dati.
     * Se l'operazione va a buon fine allora si viene rimandati alla pagina del pannello di controllo e viene
     * visualizzato un messaggio di operazione riuscita, altrimenti si viene comunque rimandati alla pagina del pannello
     * di controllo, ma viene visualizzato un messaggio di operazione fallita.
     * @param int $categoriaID Identificativo della categoria da eliminare.
     */
    public function rimuoviCategoria(int $categoriaID): void
    {
        $session = new USession();
        $user = $session->getValue('user');
        if (isset($user)) {
            $user = unserialize($user);
            $pm = FPersistentManager::getInstance();
            if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getId())) {
                $result = $pm->delete(FPersistentManager::ENTITY_CATEGORIA, $categoriaID);
                if ($result) {
                    header('Location: /UniChat/admin/visualizzaPannelloDiControllo/conferma');
                } else {
                    header('Location: /UniChat/admin/visualizzaPannelloDiControllo/errore');
                }
            } else {
                /*
                 * Se l'utente presente nella variabile di sessione non è un admin allora non deve avere accesso a tale
                 * operazione e viene quindi riportato sulla home page.
                 */
                header('Location: /UniChat');
            }
        } else {
            /*
             * Se la variabile di sessione non è presente allora vuol dire che non si risulta loggati, si viene quindi
             * rimandati nella pagina di login per effettuare l'accesso.
             */
            header('Location: /UniChat/utenti/login');
        }
    }

    /**
     * Metodo responsabile di gestire tutte le operazioni necessarie a recuperare un elenco di utenti presenti nella
     * base dati, per visualizzarli nel pannello di controllo.
     * Il numero di utenti da recuperare è stabilito dalla view responsabile di visualizzare il pannello di controllo e
     * sempre da quest'ultima si recupera il numero di pagina necessario per sapere da quale riga interrogare la base
     * dati.
     * L'elenco di user può essere richiesto solo dall'Admin, quindi prima di procedere viene recuperato
     * l'utente dalla variabile di sessione.
     * Se l'utente è stato caricato in sessione allora viene eseguito un controllo per assicurarsi sia effettivamente un
     * admin, altrimenti si viene rimandati alla schermata di login. Se il controllo è positivo allora si recupera dalla
     * view il numero di pagina, questo viene poi usato per recuperare un array di utenti dalla base dati, altrimenti si
     * viene rimandati alla home page in quanto non autorizzati a compiere tale operazione.
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
                /*
                 * Se l'utente presente nella variabile di sessione non è un admin allora non deve avere accesso a tale
                 * operazione e viene quindi riportato sulla home page.
                 */
                header('Location: /UniChat');
            }
        } else {
            /*
             * Se la variabile di sessione non è presente allora vuol dire che non si risulta loggati, si viene quindi
             * rimandati nella pagina di login per effettuare l'accesso.
             */
            header('Location: /UniChat/utenti/login');
        }
    }

    /**
     * Metodo responsabile di gestire tutte le operazioni necessarie a recuperare un elenco di categorie presenti nella
     * base dati, per visualizzarle nel pannello di controllo.
     * Il numero di categorie da recuperare è stabilito dalla view responsabile di visualizzare il pannello di controllo e
     * sempre da quest'ultima si recupera il numero di pagina necessario per sapere da quale riga interrogare la base
     * dati.
     * L'elenco di categorie può essere richiesto solo dall'Admin, quindi prima di procedere viene recuperato
     * l'utente dalla variabile di sessione.
     * Se l'utente è stato caricato in sessione allora viene eseguito un controllo per assicurarsi sia effettivamente un
     * admin, altrimenti si viene rimandati alla schermata di login.
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
                /*
                 * Se l'utente presente nella variabile di sessione non è un admin allora non deve avere accesso a tale
                 * operazione e viene quindi riportato sulla home page.
                 */
                header('Location: /UniChat');
            }
        } else {
            /*
             * Se la variabile di sessione non è presente allora vuol dire che non si risulta loggati, si viene quindi
             * rimandati nella pagina di login per effettuare l'accesso.
             */
            header('Location: /UniChat/utenti/login');
        }
    }

    /**
     * Metodo responsabile di gestire tutte le operazioni necessarie a visualizzare correttamente il pannello di controllo.
     * L'esecuzione di tale metodo può essere richiesta solo dall'Admin, quindi prima di procedere viene recuperato
     * l'utente dalla variabile di sessione.
     * Se l'utente è stato caricato in sessione allora viene eseguito un controllo per assicurarsi sia effettivamente un
     * admin, altrimenti si viene rimandati alla schermata di login.
     * Se il controllo è positivo allora si recuperano dalla base dati il numero di utenti, il numero di categorie e tutte
     * le categorie, altrimenti si viene rimandati alla home page in quanto non si è autorizzati.
     * Se tutti i dati vengono recuperati correttamente dalla base dati allora si procede con il settare, tramite le view
     * apposite, il top della pagina, il side della pagina, la barra per la paginazione degli utenti, la barra per la
     * paginazione delle categorie ed i bottoni per rendere un utente moderatore.
     * In caso contrario allora viene visualizzato l'errore HTTP 500.
     * Il metodo può ricevere in ingresso un parametro e se questo vale 'conferma' allora viene anche settato un
     * messaggio di conferma, se vale 'errore' viene settato un messaggio di errore, se vale 'avviso' viene settato un
     * messaggio di avviso, altrimenti non viene settato alcun messaggio.
     */
    public function visualizzaPannelloDiControllo(): void
    {
        $session = new USession();
        $user = $session->getValue("user");

        if (isset($user)) {
            $user = unserialize($user);
            $pm = FPersistentManager::getInstance();

            if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getId())) {
                $numeroUtenti = $pm->contaEntities(FPersistentManager::ENTITY_USER, FPersistentManager::PROPERTY_DEFAULT, null, null);
                $numeroCategorie = $pm->contaEntities(FPersistentManager::ENTITY_CATEGORIA, FPersistentManager::PROPERTY_DEFAULT, null, null);
                try {
                    $categorie = $pm->loadAllCategorie();
                } catch (ValidationException $e) {
                    $categorie = null;
                }

                if (isset($numeroUtenti) && isset($numeroCategorie) && isset($categorie)) {



                    $vAmministrazione = new VAmministrazione();
                    $vAmministrazione->setPaginazioneUtenti($numeroUtenti);
                    $vAmministrazione->setPaginazioneCategorie($numeroCategorie);
                    $vAmministrazione->setBottoneAggiungiRimuoviModeratore($categorie);


                    $vPage = new VPage($vAmministrazione->getSmarty());
                    $vPage->setMenuUtente($user, true);
                    $vPage->setMenuLeft($categorie);
                    $vPage->setBottoneFiltra($categorie);


                    if (func_num_args() == 1) {
                        /*
                         * Gestisce se visualizza o meno messaggi di avviso, di errore o di conferma.
                         */
                        if (func_get_arg(0) == "conferma") {
                            $vAmministrazione->setMessaggiConfermaErroreOperazioni(true);
                            $vAmministrazione->setAvviso(false);
                        } else if (func_get_arg(0) == "errore") {
                            $vAmministrazione->setMessaggiConfermaErroreOperazioni(false);
                            $vAmministrazione->setAvviso(false);
                        } else if(func_get_arg(0) == "avviso") {
                            $vAmministrazione->setMessaggiConfermaErroreOperazioni(null);
                            $vAmministrazione->setAvviso(true);
                        } else {
                            $vAmministrazione->setMessaggiConfermaErroreOperazioni(null);
                            $vAmministrazione->setAvviso(false);
                        }
                    } else {
                        $vAmministrazione->setMessaggiConfermaErroreOperazioni(null);
                        $vAmministrazione->setAvviso(false);
                    }

                    $vAmministrazione->showPannelloDiControllo();

                } else {
                    /*
                     * Se non si riescono a recuperare i dati oppure questi presentano problemi con la validazione
                     * dei dati allora vuol dire che ci sono dei problemi con la comunicazione con la base dati o
                     * che i dati presenti in essa sono stati cambiati manualmente e presentano errori.
                     */
                    $view = new VError();
                    $view->setValoriErrore(VError::CODE_500, VError::TYPE_500 );
                    $view->showError();
                }
            } else {
                /*
                 * Se l'utente presente nella variabile di sessione non è un admin allora non deve avere accesso a tale
                 * operazione e viene quindi riportato sulla home page.
                 */
                header('Location: /UniChat');
            }
        } else {
            /*
             * Se la variabile di sessione non è presente allora vuol dire che non si risulta loggati, si viene quindi
             * rimandati nella pagina di login per effettuare l'accesso.
             */
            header('Location: /UniChat/utenti/login');
        }
    }
}