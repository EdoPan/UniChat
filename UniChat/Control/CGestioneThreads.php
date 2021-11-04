<?php

/**
 * Classe di controllo contenente tutti i metodi con operazioni in cui sono coinvolti Threads.
 */
class CGestioneThreads
{


    /**
     * @throws ValidationException
     * Metodo responsabile della creazione di un thread. Recupera i valori passati dall'utente nelle form e salva
     * il nuovo thread nel DB. Se l'operazione va a buon fine verremo reindirizzati sulla pagina del thread
     * appena creato e visualizzeremo un alert di successo altrimenti verremo reindirizzati sulla pagina di errore
     * dedicata (500 Internal Server Error) e visualizzeremo un alert di errore.
     */
    public function creaThread(int $categoriaID): void {

        $pm = FPersistentManager::getInstance();
        $view = new VForm();
        $view2 = new VError();

        $session = new USession();

        $utente = $session->getValue('user');

        if (isset($utente)) {

            $user = unserialize($utente);

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                $categoria = $pm->load(FPersistentManager::ENTITY_CATEGORIA,FPersistentManager::PROPERTY_DEFAULT, $categoriaID);
                $view->setCategoriaCreazioneThread($categoria);
                $view->showForm(VForm::FORM_CREAZIONE_THREAD);

            } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

                //Potrebbero essere ritornati dei valori null

                $categoria = $pm->load(FPersistentManager::ENTITY_CATEGORIA, FPersistentManager::PROPERTY_DEFAULT, $categoriaID);
                $valutazione = new EValutazione(null, null, null, null);

                $valori = $view->getValori(VForm::FORM_CREAZIONE_THREAD);

                if (isset($valori)) {

                    $titolo = $valori['titolo'];
                    $testo = $valori['testo'];

                    if (array_key_exists('allegati', $valori)) {
                        $allegati = array();
                        $allegati['tipo'] = $valori['tipo'];
                        $allegati['nome'] = $valori['nome'];
                        $allegati['dimensione'] = $valori['dimensione'];
                        $allegati['file'] = $valori['file'];
                    } else {
                        $allegati = null;
                    }

                    try {

                        $thread = new EThread(null, $titolo, $testo, null, $allegati, $user, $categoria, $valutazione, null);

                        if ($pm->store(FPersistentManager::ENTITY_THREAD, $thread) == true) {

                            header("Location: /UniChat/categoria/visualizzaCategoria/$categoriaID/conferma");


                        } else {

                            //Errore visualizzato nel caso non si riuscisse a salvare il thread
                            $view2->setValoriErrore(VError::CODE_500, VError::TYPE_500);
                            $view2->showError();

                        }

                    } catch (ValidationException $e) {

                        $view->setErroreValidazione($e->getCode(), $e->getMessage());
                        $view->showForm(VForm::FORM_CREAZIONE_THREAD);

                    }


                } else {

                    //Errore visualizzato nel caso di POST con campi vuoti
                    $view2->setValoriErrore(VError::CODE_400, VError::TYPE_400);
                    $view2->showError();

                }


            } else {

                $view2->setValoriErrore(VError::CODE_404,VError::TYPE_404);
                $view2->showError();

            }

        }


    }

    /**
     * Metodo responsabile della pubblicazione di una risposta sotto un thread. Recupera i valori inseriti dall'utente
     * nelle form, crea la risposta e la salva nel DB. Se l'operazione va a buon fine visualizzeremo un alert di successo altrimenti
     * visualizzeremo un alert di errore.
     */
    public function rispondiThread(): void {

        $pm = FPersistentManager::getInstance();
        $view = new VForm();

        $session = new USession();
        $utente = $session->getValue('user');

        if (isset($utente)) {
            $user = unserialize($utente);


            $valori = $view->getValori(VForm::FORM_INVIO_RISPOSTA);

            if (isset($valori)) {
                $testo = $valori['testo'];
                $threadID = $valori['threadID'];

                $rispID = null;
                $risposta = new ERisposta($rispID, $testo, null, $user);

                if ($pm->storeRispostaThread($risposta, $threadID) == true) {

                    header("Location: /UniChat/threads/visualizzaThread/$threadID/conferma");


                } else {

                    header("Location: /UniChat/threads/visualizzaThread/$threadID/errore");


                }

            } else {

                $view1 = new VThread();
                $view1->setMessaggiErroreConferma(VThread::ERROR);
                $view1->showThread();

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

        $view = new VThread();

        $session = new USession();
        $utente = $session->getValue('user');

        $view->setMessaggiErroreConferma(VThread::NULLA);

        $valutazione = $pm->load(FPersistentManager::ENTITY_VALUTAZIONE, FPersistentManager::PROPERTY_BY_THREAD, $threadID);

        if (isset($utente)) {

            $user = unserialize($utente);

            $view->setBottoniValutazione(true, $valore);

            $valutazione->valuta($user, $valore);
            $pm->update(FPersistentManager::ENTITY_VALUTAZIONE, $valutazione);

        }

    }

    /**
     * @param int $threadID
     * @throws ValidationException
     * Metodo responsabile della visualizzazione di uno specifico thread in base al suo id. Il metodo imposterà tutte
     * le componenti visualizzate nella schermata verificando in oltre se l'utente è abilitato alla visualizzazione di
     * determinati elementi, come ad esempio i cestini di eliminazione (thread/risposta) o la form per l'invio
     * della risposta. Se per qualche motivo il thread non dovesse essere trovato a quel punto l'utente verrà
     * reindirizzato sulla pagina di errore dedicata (404 NOT FOUND).
     */
    public function visualizzaThread(int $threadID): void {

        $pm = FPersistentManager::getInstance();
        $thread = $pm->load(FPersistentManager::ENTITY_THREAD, FPersistentManager::PROPERTY_DEFAULT, $threadID);

        $view = new VThread();
        $view1 = new VError();

        $session = new USession();
        $utente = $session->getValue('user');

        if (isset($utente)) {

            $user = unserialize($utente);

            if (isset($thread)) {

                $valutazione = $pm->load(FPersistentManager::ENTITY_VALUTAZIONE, FPersistentManager::PROPERTY_DEFAULT, $thread->getID());

                $view->setURLNavigazione($thread);
                $view->setThread($thread);
                $view->setFormRisposta(true);
                $view->setBottoniElimina(false);
                $view->setBottoniValutazione(true, $valutazione->espressoGiudizio($user));

                if($pm->isA(FPersistentManager::ENTITY_MODERATORE, $user->getID()) == true or $pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getID()) == true) {
                    $view->setBottoniElimina(true);
                }

                /*
                 * Condizione per passare i valori alla variabile smarty $messaggio che gestisce la comparsa dell'alert
                 * con l'eventuale messaggio di conferma o errore.
                 */
                if (func_num_args() == 2) {
                    if (func_get_arg(1) == "conferma") {
                        $view->setMessaggiErroreConferma(VThread::SUCCESS);
                    } else if (func_get_arg(1) == "errore") {
                        $view->setMessaggiErroreConferma(VThread::ERROR);
                    } else {
                        $view->setMessaggiErroreConferma(VThread::NULLA);
                    }
                }

                $view->showThread();


            } else {

                $view1->setValoriErrore(VError::CODE_404, VError::TYPE_404);
                $view1->showError();

            }

        }

    }


    /**
     * Metodo responsabile del recupero del thread più discusso in base alla categoria.
     */
    public function threadsPiuDiscussi(int $numeroThreads): ?array {


        $pm = FPersistentManager::getInstance();

        $threads = $pm->loadThreadsPiuDiscussi($numeroThreads);

        return $threads;

    }

    /**
     * Metodo responsabile del recupero del thread maggiormente valutato in base alla categoria.
     */
    public function threadsValutazionePiuAlta(int $numeroThreads): ?array {


        $pm = FPersistentManager::getInstance();

        $threads = $pm->loadThreadsValutazionePiuAlta($numeroThreads);

        return $threads;

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

                    header("Location: /UniChat/categoria/visualizzaCategoria/$categoriaID/conferma");


                } else {

                    header("Location: /UniChat/threads/visualizzaThread/$threadID/errore");

                }

            }

            elseif ($pm->isA(FPersistentManager::ENTITY_MODERATORE, $user->getID()) == true) {

                $mod = $pm->load(FPersistentManager::ENTITY_MODERATORE, FPersistentManager::PROPERTY_DEFAULT, $user->getID());
                $cat = $pm->load(FPersistentManager::ENTITY_CATEGORIA, FPersistentManager::PROPERTY_BY_THREAD, $threadID);

                if (isset($mod) && isset($cat) && $mod->getCategoriaGestita()->getNome() == $cat->getNome()) {

                    if ($pm->delete(FPersistentManager::ENTITY_THREAD, $threadID) == true) {

                        header("Location: /UniChat/categoria/visualizzaCategoria/$categoriaID/conferma");

                    } else {

                        header("Location: /UniChat/threads/visualizzaThread/$threadID/errore");

                    }

                }

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

                }
            }

        }


    }

    /**
     * @param int $categoriaID
     * @param int $numeroPagina
     * @throws ValidationException
     * @return array|null
     * Metodo responsabile del recupero di un array di 6 thread (valore prescelto per la paginazione) in base alla pagina
     * selezionata dall'utente in una determinata categoria.
     */
    public function elencaThreads(int $categoriaID, int $numeroPagina): ?array {

        $pm = FPersistentManager::getInstance();

        /*
         * La riga di partenza deve essere calcolata in base alla pagina selezionata.
         * Se siamo in pagina 1 la riga di partenza sarà 0, se siamo in pagina 2 la riga di partenza sarà 6
         * se siamo in pagina 3 la riga di partenza sarà 12 e così via.
         * rigaDiPartenza = (6 x numeroPaginaSelezionata) - 6
         * Questo perché le pagine partono con indice 1 e le righe con indice 0
         */
        $rigaDiPartenza = 6*($numeroPagina - 1);

        $threads = $pm->loadEntities(FPersistentManager::ENTITY_THREAD, FPersistentManager::PROPERTY_BY_CATEGORIA, $categoriaID, $rigaDiPartenza, 6);
        return $threads;

    }

    /**
     * @param int $numeroPagina
     * Metodo per la ricerca di threads nel sito. Vengono recuperati i valori inseriti nelle form
     * dall'utente, successivamente vengono recuperati tutti i thread che hanno un match con le parole inserite
     * dall'utente e con l'eventuale categoria di filtraggio selezionata. In seguito l'utente verrà reindirizzato
     * alla schermata di ricerca dedicata.
     *
     * N.B.
     * Il metodo prende in ingresso il numero della pagina in quanto verrà richiamato
     * a ogni passaggio da una pagina all'altra poiché pagina dopo pagina dovranno essere caricati
     * i successivi (o precedenti) 6 thread.
     */
    public function ricerca(int $numeroPagina): void {

        $pm = FPersistentManager::getInstance();

        $view = new VForm();

        $view1 = new VCategoria();

        /*
         * La riga di partenza deve essere calcolata in base alla pagina selezionata.
         * Se siamo in pagina 1 la riga di partenza sarà 0, se siamo in pagina 2 la riga di partenza sarà 6
         * se siamo in pagina 3 la riga di partenza sarà 12 e così via.
         * rigaDiPartenza = (6 x numeroPaginaSelezionata) - 6
         * Questo perché le pagine partono con indice 1 e le righe con indice 0
         */
        $rigaDiPartenza = 6*($numeroPagina - 1);

        $testoRicerca = $view->getValori(VForm::FORM_RICERCA)['testoricerca'];
        if (isset($testoRicerca)) {

            $categoriaRicerca = $view->getValori(VForm::FORM_RICERCA)['testoricerca'];
            $categoriaRicerca = array($categoriaRicerca);


            if (isset($categoriaRicerca) && $categoriaRicerca != 0) {

                $threads = $pm->ricercaThreads(FPersistentManager::SEARCH_TYPE_TITOLO_CATEGORIE, $testoRicerca, $categoriaRicerca, $rigaDiPartenza, 6);
                $view1->setCategoriaRicerca($categoriaRicerca);


            } else {

                $threads = $pm->ricercaThreads(FPersistentManager::SEARCH_TYPE_TITOLO, $testoRicerca, null, $rigaDiPartenza, 6);
                $view1->setCategoriaRicerca(null);

            }

            $view1->setThreads($threads);
            $view1->setActivePage($numeroPagina);
            $view1->showRicerca();

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