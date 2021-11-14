<?php
declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";
/**
 * Classe di controllo contenente tutti i metodi con operazioni in cui sono coinvolti Threads.
 */
class CGestioneThreads
{


    /**
     * @throws ValidationException
     * Metodo responsabile della creazione di un thread. Recupera i valori passati dall'utente nelle form e salva
     * il nuovo thread nel DB. Se l'operazione va a buon fine verremo reindirizzati sulla pagina della categoria dove
     * è stato appena pubblicato il Thread e visualizzeremo un messaggio di conferma. Se qualcosa dovesse andare storto
     * invece visualizzeremo un messaggio d'errore.
     */
    public function creaThread(int $categoriaID): void {

        $pm = FPersistentManager::getInstance();

        $session = new USession();


        $utente = $session->getValue('user');

        if (isset($utente)) {

            $user = unserialize($utente);

            $vCreazioneThread = new VCreazioneThread();

            $vPage = new VPage($vCreazioneThread->getSmarty());
            $vPage->setBottoneFiltra($pm->loadAllCategorie());
            $vPage->setMenuLeft($pm->loadAllCategorie());
            $vPage->setMenuUtente($user, false);

            if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getId()) || $pm->isA(FPersistentManager::ENTITY_MODERATORE, $user->getId())) {
                if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getId())) {
                    $vPage->setMenuUtente($user, true);
                } else {
                    $vPage->setMenuUtente($user, false);
                }
            }


            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                $categoria = $pm->load(FPersistentManager::ENTITY_CATEGORIA,FPersistentManager::PROPERTY_DEFAULT, $categoriaID);


                $vCreazioneThread->setCategoriaCreazioneThread($categoria);
                $vCreazioneThread->setErroreValidazione(null, null);
                $vCreazioneThread->showCreaThread();

            } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $categoria = $pm->load(FPersistentManager::ENTITY_CATEGORIA, FPersistentManager::PROPERTY_DEFAULT, $categoriaID);
                $valutazione = new EValutazione(null, null, null, null);

                $valori = $vCreazioneThread->getValori();

                if (isset($valori)) {

                    $titolo = $valori['titolo'];
                    $testo = $valori['testo'];

                    if (array_key_exists('allegati', $valori)) {

                        $allegati = $valori['allegati'];

                    } else {

                        $allegati = null;

                    }

                    try {

                        $thread = new EThread(null, $titolo, $testo, null, $allegati, $user, $categoria, $valutazione, null);

                        if ($pm->store(FPersistentManager::ENTITY_THREAD, $thread) == true) {

                            header("Location: /UniChat/categorie/visualizzaCategoria/$categoriaID/1/conferma");

                        } else {

                            header("Location: /UniChat/categorie/visualizzaCategoria/$categoriaID/1/errore");

                        }

                    } catch (ValidationException $e) {

                        $vCreazioneThread->setErroreValidazione($e->getCode(), $e->getMessage());
                        $vCreazioneThread->setCategoriaCreazioneThread($categoria);
                        $vCreazioneThread->setCampiObbligatoriMancanti(false);
                        $vCreazioneThread->showCreaThread();

                    }


                } else {

                    header("Location: /UniChat/categorie/visualizzaCategoria/$categoriaID/1/errore");

                }

            }

        }

    }

    /**
     * Metodo responsabile della pubblicazione di una risposta sotto un thread. Recupera i valori inseriti dall'utente
     * nelle form, crea la risposta e la salva nel DB. Se l'operazione va a buon fine visualizzeremo un messaggio di conferma
     * altrimenti visualizzeremo un messaggio di errore.
     */
    public function rispondiThread(): void {

        $pm = FPersistentManager::getInstance();
        $vRisposta = new VRisposta();

        $session = new USession();
        $utente = $session->getValue('user');

        if (isset($utente)) {
            $user = unserialize($utente);


            $valori = $vRisposta->getValori();

            if (isset($valori)) {
                $testo = $valori['testo'];
                $threadID = (int) $valori['threadID'];

                $rispID = null;
                $risposta = new ERisposta($rispID, $testo, null, $user);

                if ($pm->storeRispostaThread($risposta, $threadID) == true) {

                    header("Location: /UniChat/threads/visualizzaThread/$threadID/conferma");


                } else {

                    header("Location: /UniChat/threads/visualizzaThread/$threadID/errore");


                }

            } else {

                $vThread = new VThread();
                $vThread->setMessaggio(true, VThread::ERROR, 'danger');
                $vThread->showThread();

            }

        }

    }

    /**
     * @param int $valore
     * @param int $threadID
     * @throws ValidationException
     * Metodo responsabile della valutazione di un thread. In particolare il metodo prenderà come parametro il valore
     * (Positivo -> 1, Negativo-> 2) e l'id del thread che si sta andando a valutare, poi si verificherà se l'utente
     * ha già espresso un giudizio sul thread in passato di modo da aggiornarlo, altrimenti si procederà a valutarlo
     * per la prima volta.
     */
    public function valutaThread(int $valore, int $threadID): void {

        $pm = FPersistentManager::getInstance();


        $session = new USession();
        $utente = $session->getValue('user');

        if (isset($utente)) {

            $user = unserialize($utente);

            $valutazione = $pm->load(FPersistentManager::ENTITY_VALUTAZIONE, FPersistentManager::PROPERTY_BY_THREAD, $threadID);
            $giudizio = $valutazione->valuta($user, $valore);

            $pm->updateValutazione($valutazione, $giudizio , $user);


            header("Location: /UniChat/threads/visualizzaThread/$threadID");


        } else {

            header('Location: /UniChat/home/visualizzaHome');

        }

    }

    /**
     * @param int $threadID
     * @throws ValidationException
     * Metodo responsabile della visualizzazione di uno specifico thread in base al suo id. Il metodo imposterà tutte
     * le componenti visualizzate nella schermata verificando in oltre se l'utente è abilitato alla visualizzazione di
     * determinati elementi, come ad esempio i cestini di eliminazione (thread/risposta) o la form per l'invio
     * della risposta. Se per qualche motivo il thread non dovesse essere trovato a quel punto l'utente sarà
     * reindirizzato sulla pagina di errore dedicata (404 NOT FOUND).
     */
    public function visualizzaThread(int $threadID): void {

        $pm = FPersistentManager::getInstance();
        $thread = $pm->load(FPersistentManager::ENTITY_THREAD, FPersistentManager::PROPERTY_DEFAULT, $threadID);

        $categorie = $pm->loadAllCategorie();

        $vThread = new VThread();

        $vPage = new VPage($vThread->getSmarty());

        $session = new USession();
        $utente = $session->getValue('user');

        //Verifica se le categorie sono state recuperate.
        if (!isset($categorie)) {

            $vError = new VError();
            $vError->setValoriErrore(VError::CODE_500, VError::TYPE_500);
            $vError->showError();

        } else {

            //Verifica se il thread è stato caricato.
            if (isset($thread)) {

                //Condizione per utente loggato.
                if (isset($utente)) {

                    $user = unserialize($utente);

                    $valutazione = $pm->load(FPersistentManager::ENTITY_VALUTAZIONE, FPersistentManager::PROPERTY_BY_THREAD, $thread->getID());

                    $vThread->setBottoniElimina(false);
                    $vThread->setFormRisposta(true);
                    $vThread->setBottoniValutazione(true, $valutazione->espressoGiudizio($user));
                    $vThread->setURLNavigazione($thread);
                    $vThread->setThread($thread);

                    $vPage->setMenuLeft($categorie);
                    $vPage->setBottoneFiltra($categorie);
                    $vPage->setMenuUtente($user, false);



                    /*
                     * Condizione per utente Admin (Visualizza bottone elimina thread/risposta)
                     */
                    if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getId()) == true) {

                        $vPage->setMenuUtente($user, true);
                        $vThread->setBottoniElimina(true);
                    }

                    /*
                     * Condizione per utente Moderatore (Visualizza bottone elimina thread/risposta)
                     */
                    if ($pm->isA(FPersistentManager::ENTITY_MODERATORE, $user->getID()) == true) {

                        $mod = $pm->load(FPersistentManager::ENTITY_MODERATORE, FPersistentManager::PROPERTY_DEFAULT, $user->getID());
                        $cat = $pm->load(FPersistentManager::ENTITY_CATEGORIA, FPersistentManager::PROPERTY_BY_THREAD, $threadID);

                        if (isset($mod) && isset($cat) && $mod->getCategoriaGestita()->getNome() == $cat->getNome()) {

                            $vThread->setBottoniElimina(true);

                        }

                    }

                    $vThread->setFormRisposta(true);
                    $vThread->setBottoniValutazione(true, null);
                    $vThread->setBottoniValutazione(true, $valutazione->espressoGiudizio($user));


                //Condizione per utente non loggato.
                } else {

                    $vThread->setBottoniElimina(false);
                    $vThread->setFormRisposta(false);
                    $vThread->setBottoniValutazione(false, null);
                    $vThread->setURLNavigazione($thread);
                    $vPage->setBottoneFiltra($categorie);
                    $vPage->setMenuUtente(null, false);
                    $vThread->setThread($thread);
                    $vPage->setMenuLeft($categorie);

                }

                /*
                 * Condizione per passare i valori alla variabile smarty $messaggio che gestisce la comparsa del
                 * messaggio di conferma o errore.
                 */
                if (func_num_args() == 2) {
                    if (func_get_arg(1) == "conferma") {
                        $vThread->setMessaggio(true, VThread::SUCCESS, 'success');
                    } else if (func_get_arg(1) == "errore") {
                        $vThread->setMessaggio(true, VThread::ERROR, 'danger');
                    } else {
                        $vThread->setMessaggio(false, VThread::NULLA, null);
                    }
                } else {

                    $vThread->setMessaggio(false, VCategoria::NULLA, null);

                }

                $vThread->showThread();

            //Condizione nel caso in cui non dovesse essere recuperato il thread.
            } else {

                $vError = new VError();
                $vError->setValoriErrore(VError::CODE_404, VError::TYPE_404);
                $vError->showError();

            }

        }


    }


    /**
     * @param int $threadID
     * @throws ValidationException
     * Metodo responsabile dell'eliminazione di un thread specifico mediante l'id di quest'ultimo. Verrà verificato
     * se l'utente loggato è un Admin o il Moderatore della categoria dove è stato pubblicato il thread, in tal caso
     * verrà eliminato il thread dal DB e se l'operazione è andata a buon fine visualizzeremo un messaggio di successo.
     */
    public function rimuoviThread(int $threadID): void {

        $session = new USession();
        $utente = $session->getValue('user');

        $pm = FPersistentManager::getInstance();

        $thread = $pm->load(FPersistentManager::ENTITY_THREAD, FPersistentManager::PROPERTY_DEFAULT, $threadID);
        $categoriaID = $thread->getCategoriaThread()->getID();

        if (isset($utente)) {

            $user = unserialize($utente);

            if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getID()) == true){

                if ($pm->delete(FPersistentManager::ENTITY_THREAD, $threadID) == true) {

                    header("Location: /UniChat/categorie/visualizzaCategoria/$categoriaID/1/conferma");


                } else {

                    header("Location: /UniChat/threads/visualizzaThread/$threadID/errore");

                }

            }

            elseif ($pm->isA(FPersistentManager::ENTITY_MODERATORE, $user->getID()) == true) {

                $mod = $pm->load(FPersistentManager::ENTITY_MODERATORE, FPersistentManager::PROPERTY_DEFAULT, $user->getID());
                $cat = $pm->load(FPersistentManager::ENTITY_CATEGORIA, FPersistentManager::PROPERTY_BY_THREAD, $threadID);

                if (isset($mod) && isset($cat) && $mod->getCategoriaGestita()->getNome() == $cat->getNome()) {

                    if ($pm->delete(FPersistentManager::ENTITY_THREAD, $threadID) == true) {

                        header("Location: /UniChat/categoria/visualizzaCategoria/$categoriaID/1/conferma");

                    } else {

                        header("Location: /UniChat/threads/visualizzaThread/$threadID/errore");

                    }

                } else {

                    header("Location: /UniChat/threads/visualizzaThread/$threadID");

                }

            } else {

                header("Location: /UniChat/threads/visualizzaThread/$threadID/errore");

            }

        }

    }

    /**
     * @param int $rispostaID
     * @param int $threadID
     * @throws ValidationException
     * Metodo responsabile dell'eliminazione di una risposta specifica mediante l'id di quest'ultima e l'id del thread.
     * Verrà verificato se l'utente loggato è un Admin o il Moderatore della categoria del thread dove è stato pubblicata
     * la risposta, in tal caso verrà eliminata la risposta dal DB e se l'operazione è andata a buon fine visualizzeremo
     * un messaggio di successo altrimenti verrà visualizzato un messaggio d'errore.
     */
    public function rimuoviRisposta(int $rispostaID, int $threadID): void {

        $pm = FPersistentManager::getInstance();

        $session = new USession();
        $utente = $session->getValue('user');

        if (isset($utente)) {

            $user = unserialize($utente);

            if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getID()) == true){

                if ($pm->delete(FPersistentManager::ENTITY_RISPOSTA, $rispostaID) == true) {

                    header("Location: /UniChat/threads/visualizzaThread/$threadID/conferma");

                } else {

                    header("Location: /UniChat/threads/visualizzaThread/$threadID/errore");

                }

            }

            elseif ($pm->isA(FPersistentManager::ENTITY_MODERATORE, $user->getID()) == true) {

                $mod = $pm->load(FPersistentManager::ENTITY_MODERATORE, FPersistentManager::PROPERTY_DEFAULT, $user->getID());
                $cat = $pm->load(FPersistentManager::ENTITY_CATEGORIA, FPersistentManager::PROPERTY_BY_THREAD, $threadID);

                if (isset($mod) && isset($cat) && $mod->getCategoriaGestita()->getNome() == $cat->getNome()) {

                    if ($pm->delete(FPersistentManager::ENTITY_RISPOSTA, $rispostaID) == true) {

                        header("Location: /UniChat/threads/visualizzaThread/$threadID/conferma");

                    } else {

                        header("Location: /UniChat/threads/visualizzaThread/$threadID/errore");

                    }

                } else {

                    header("Location: /UniChat/threads/visualizzaThread/$threadID");

                }

            } else {

                header("Location: /UniChat/threads/visualizzaThread/$threadID/errore");

            }

        }


    }


    /**
     * @param int $numeroPagina
     * Metodo responsabile di gestire tutte le operazioni necessarie per effettuare una ricerca di threads e per
     * visualizzarli.
     * I possibili scenari gestiti sono:
     * - l'utente effettua una ricerca basata solo sul titolo, in questo caso la ricerca viene effettuata in tutte le
     * categorie;
     * - l'utente effettua una ricerca basata sul titolo e specifica una categoria, in tal caso la ricerca viene
     * effettuata solo nella categoria scelta;
     * - l'utente effettua una ricerca specificando solo la categoria, in tal caso si viene rimandati alla pagina
     * della categoria scelta.
     * - l'utente effettua una ricerca senza specificare titolo e categoria, in tal caso vengono visualizzati tutti i
     * threads presenti nella base dati.
     * Il numero di threads recuperati dipende dalla view responsabile della loro visualizzazione nella pagina di ricerca.
     * Il numero di pagina fornito in ingresso stabilisce quali threads visualizzare tra quelli che rispettano i criteri
     * di ricerca.
     * Se ci sono problemi con il recupero dei dati allora si visualizza una pagina di errore con il codice HTTP 500.
     */
    public function ricerca(int $numeroPagina): void {

        $session = new USession();
        $vRicerca = new VRicerca();
        $vPage = new VPage($vRicerca->getSmarty());
        $pm = FPersistentManager::getInstance();

        try {
            $categorie = $pm->loadAllCategorie();
        } catch (ValidationException $e) {
            $categorie = null;
        }


        if (isset($categorie)) {
            $vPage->setBottoneFiltra($categorie);
            $vPage->setMenuLeft($categorie);
            $vPage->setMenuUtente(null, null);

            $user = $session->getValue('user');
            if (isset($user)) {
                $user = unserialize($user);
                if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getId())) {
                    $vPage->setMenuUtente($user, true);
                } else {
                    $vPage->setMenuUtente($user, false);
                }
            }

            $valori = $vRicerca->getValori();
            $rigaDiPartenza = VRicerca::NUMERO_THREAD_PER_PAGINA*($numeroPagina - 1);

            try {
                if (array_key_exists('testoricerca', $valori)) {
                    $titoloCercato = $valori['testoricerca'];
                    if (array_key_exists('categoriaID', $valori) && $valori['categoriaID'] != 0) {
                        $categoriaID = $valori['categoriaID'];
                        $categoria = $pm->load(FPersistentManager::ENTITY_CATEGORIA, FPersistentManager::PROPERTY_DEFAULT, $categoriaID);

                    } else {
                        $categoriaID = null;
                        $categoria = null;
                    }


                    if (isset($categoriaID)) {

                        /*
                         * Ricerca in base al titolo e rispetto ad una categoria.
                         */
                        $threads = $pm->ricercaThreads(FPersistentManager::SEARCH_TYPE_TITOLO_CATEGORIA, $titoloCercato, $categoriaID, $rigaDiPartenza, VRicerca::NUMERO_THREAD_PER_PAGINA);
                        $numeroThreads = $pm->contaEntities(FPersistentManager::ENTITY_THREAD, FPersistentManager::PROPERTY_BY_SEARCH, $categoriaID, $titoloCercato);
                    } else {

                        /*
                         * Ricerca in base al titolo e rispetto a tutte le categorie.
                         */
                        $threads = $pm->ricercaThreads(FPersistentManager::SEARCH_TYPE_TITOLO, $titoloCercato, null, $rigaDiPartenza, VRicerca::NUMERO_THREAD_PER_PAGINA);
                        $numeroThreads = $pm->contaEntities(FPersistentManager::ENTITY_THREAD, FPersistentManager::PROPERTY_BY_SEARCH, null, $titoloCercato);

                    }

                    if (isset($threads) && isset($numeroThreads) && !(isset($categoriaID) && !isset($categoria))) {

                        if (!(isset($categoriaID))) {
                            $categoriaID = 0;
                        }

                        $vRicerca->setPaginazione($numeroThreads, $categoriaID, $titoloCercato);
                        $vRicerca->setActivePage($numeroPagina);
                        $vRicerca->setCategoriaRicerca($titoloCercato, $categoria);
                        $vRicerca->setThreads($threads);
                        $vRicerca->showRicerca();

                    } else {

                        /*
                         * Se non si riescono a recuperare i threads e il numero di threads ottenuti e se in particolare
                         * risulta che l'utente ha impostato una categoria di ricerca, ma la categoria non viene recuperata
                         * allora vuol dire che la base dati non è raggiungibile, quindi viene mostrata la pagina di errore.
                         */
                        $vError = new VError();
                        $vError->setValoriErrore(VError::CODE_500, VError::TYPE_500);
                        $vError->showError();
                    }

                } else {

                    if (array_key_exists('categoriaID', $valori) && $valori['categoriaID'] != 0) {

                        /*
                         * Se l'utente effettua una ricerca senza testo, ma selezionando una categoria allora si viene
                         * rimandati alla pagina della categoria selezionata.
                         */
                        $categoriaID = $valori['categoriaID'];
                        header("Location: /UniChat/categorie/visualizzaCategoria/$categoriaID/1");

                    } else {

                        /*
                         * Se l'utente sottomette al server una ricerca senza testo e senza specificare una categoria
                         * allora vengono visualizzati tutti i thread.
                         */
                        $threads = $pm->loadEntities(FPersistentManager::ENTITY_THREAD, FPersistentManager::PROPERTY_DEFAULT, null, $rigaDiPartenza, VRicerca::NUMERO_THREAD_PER_PAGINA);
                        $numeroThreads = $pm->contaEntities(FPersistentManager::ENTITY_THREAD, FPersistentManager::PROPERTY_DEFAULT, null, null);
                        if (isset($threads) && isset($numeroThreads)) {
                            $vRicerca->setPaginazione($numeroThreads, 0, "");
                            $vRicerca->setActivePage($numeroPagina);
                            $vRicerca->setCategoriaRicerca("", null);
                            $vRicerca->setThreads($threads);
                            $vRicerca->showRicerca();
                        } else {

                            /*
                             * Se non si riescono a recuperare i dati allora vuol dire che la base dati non è raggiungibile,
                             * quindi viene mostrata la pagina di errore.
                             */
                            $vError = new VError();
                            $vError->setValoriErrore(VError::CODE_500, VError::TYPE_500);
                            $vError->showError();
                        }
                    }
                }
            } catch (ValidationException $e) {

                /*
                 * Se si hanno problemi con la validazione dei dati presenti nella base dati vuol dire che i dati in
                 * essa presenti sono stati cambiati manualmente e presentano degli errori.
                 * L'utente riceverà una pagina di errore.
                 */
                $vError = new VError();
                $vError->setValoriErrore(VError::CODE_500, VError::TYPE_500);
                $vError->showError();
            }

        } else {

            /*
             * Se non si riescono a recuperare le categorie allora vuol dire che la base dati non è raggiungibile,
             * quindi viene mostrata la pagina di errore.
             */
            $vError = new VError();
            $vError->setValoriErrore(VError::CODE_500, VError::TYPE_500);
            $vError->showError();
        }
    }

    /**
     * @param int $allegatoID
     * Metodo responsabile del recupero di un allegato in base al suo id.
     */
    public function scaricaAllegato(int $allegatoID): void {

        $pm = FPersistentManager::getInstance();

        $allegato = $pm->loadAllegato($allegatoID);

        if (isset($allegato)) {

            $tipo = $allegato['tipo'];
            $nome = $allegato['nome'];
            $dimensione = $allegato['dimensione'];
            $file = base64_decode($allegato['file']);

            header('Content-Transfer-Encoding: binary');
            header("Content-Type: $tipo");
            header("Content-disposition: attachment; filename = $nome");
            header("Content-length: $dimensione");

            echo $file;

        }

    }

}