<?php

/**
 * Classe di controllo contenente tutti i metodi con operazioni in cui sono coinvolti Threads.
 */
class CGestioneThreads
{


    /**
     * Metodo responsabile della creazione di un thread. Recupera i valori passati dall'utente nelle form e salva
     * il nuovo thread nel DB. Se l'operazione va a buon fine verremo reindirizzati sulla pagina del thread
     * appena creato e visualizzeremo un alert di successo altrimenti verremo reindirizzati sulla pagina di errore
     * dedicata (500 Internal Server Error) e visualizzeremo un alert di errore.
     */
    public function creaThread(): void {

        $session = new USession();
        $user = unserialize($session->getValue('user'));

        $pm = FPersistentManager::getInstance();

        $view = new VForm();
        $view2 = new VError();

        $titolo = $view->getValori(VForm::FORM_CREAZIONE_THREAD)['titolo'];
        $testo = $view->getValori(VForm::FORM_CREAZIONE_THREAD)['testo'];
        $categoriaID = $view->getValori(VForm::FORM_CREAZIONE_THREAD)['categoriaID'];
        $allegati = $view->getValori(VForm::FORM_CREAZIONE_THREAD)['allegati'];

        $categoria = $pm->load(FPersistentManager::ENTITY_CATEGORIA, FPersistentManager::PROPERTY_DEFAULT, $categoriaID);
        $valutazione = new EValutazione(null, null, null, null);

        $thread = new EThread(null, $titolo, $testo,null ,$allegati, $user, $categoria, $valutazione, null);

        if ($pm->store(FPersistentManager::ENTITY_THREAD, $thread) == true) {

            //DEVE CORREGGERE IL METODO NINO -> POI PASSI COME PARAMETRO "conferma"
            header("Location: /UniChat/categoria/$categoriaID/conferma");


        } else {


            $view2->setValoriErrore(VError::CODE_500, VError::TYPE_500);
            $view2->showError();
            header('Location: /UniChat/error/');

        }


    }

    /**
     * Metodo responsabile della pubblicazione di una risposta sotto un thread. Recupera i valori inseriti dall'utente
     * nelle form, crea la risposta e la salva nel DB. Se l'operazione va a buon fine visualizzeremo un alert di successo altrimenti
     * visualizzeremo un alert di errore.
     */
    public function rispondiThread(): void {

        $session = new USession();
        $user = unserialize($session->getValue('user'));

        $pm = FPersistentManager::getInstance();

        $view = new VForm();
        $view1 = new VThread();

        $testo = $view->getValori(VForm::FORM_INVIO_RISPOSTA)['testo'];
        $threadID = $view->getValori(VForm::FORM_INVIO_RISPOSTA)['threadID'];

        $rispID = null;
        $risposta = new ERisposta($rispID, $testo, null, $user);

        if ($pm->storeRispostaThread($risposta, $threadID) == true) {

            header("Location: /UniChat/threads/$threadID/conferma");


        } else {

            header("Location: /UniChat/threads/$threadID/errore");


        }

    }

    /**
     * @param int $valore
     * @param int $threadID
     * Metodo responsabile della valutazione di un thread. In particolare il metodo prenderà come parametro il valore
     * (Positivo -> 1, Negativo-> 2) e l'id del thread che si sta andando a valutare, poi si verificherà se l'utente
     * ha già espresso un giudizio sul thread in passato di modo da aggiornarlo, altrimenti si procederà a valutarlo
     * per la prima volta.
     */
    public function valutaThread(int $valore, int $threadID): void {

        $session = new USession();
        $user = unserialize($session->getValue('user'));

        $pm = FPersistentManager::getInstance();


        $view = new VThread();

        $view->setMessaggiErroreConferma(VThread::NULLA);

        $valutazione = $pm->load(FPersistentManager::ENTITY_VALUTAZIONE, FPersistentManager::PROPERTY_BY_THREAD, $threadID);

        if(isset($valutazione)) {

            $view->setBottoniValutazione(true, $valore);

            $valutazione->valuta($user, $valore);
            $pm->update(FPersistentManager::ENTITY_VALUTAZIONE, $valutazione);

        } else {

            $valutazione->valuta($user, $valore);
            $pm->store(FPersistentManager::ENTITY_VALUTAZIONE, $valutazione);

        }

    }

    /**
     * @param int $threadID
     * Metodo responsabile della visualizzazione di uno specifico thread in base al suo id. Il metodo imposterà tutte
     * le componenti visualizzate nella schermata verificando in oltre se l'utente è abilitato alla visualizzazione di
     * determinati elementi, come ad esempio i cestini di eliminazione (thread/risposta) o la form per l'invio
     * della risposta. Se per qualche motivo il thread non dovesse essere trovato a quel punto l'utente verrà
     * reindirizzato sulla pagina di errore dedicata (404 NOT FOUND).
     */
    public function visualizzaThread(int $threadID): void {

        $session = new USession();
        $user = unserialize($session->getValue('user'));

        $pm = FPersistentManager::getInstance();
        $thread = $pm->load(FPersistentManager::ENTITY_THREAD, FPersistentManager::PROPERTY_DEFAULT, $threadID);

        $view = new VThread();

        $view1 = new VError();

        if (isset($thread)) {

            $valutazione = $pm->load(FPersistentManager::ENTITY_VALUTAZIONE, FPersistentManager::PROPERTY_DEFAULT, $thread->getID());

            $view->setURLNavigazione($thread);
            $view->setThread($thread);

            if(isset($user)) {
                $view->setFormRisposta(true);
                $view->setBottoniValutazione(true, $valutazione->espressoGiudizio($user));
            }

            if(isset($user) && $pm->isA(FPersistentManager::ENTITY_MODERATORE or FPersistentManager::ENTITY_ADMIN, $user->getID()) == true) {
                $view->setBottoniElimina(true);
            }


            /*
             * Condizione per passare i valori alla variabile smarty $messaggio che gestisce la comparsa dell'alert
             * con l'eventuale messaggio di conferma o errore.
             */
            if (func_num_args() == 2) {
                if (func_get_arg(1) == "conferma") {
                    $view->setMessaggiErroreConferma(VHome::SUCCESS);
                } else if (func_get_arg(1) == "errore") {
                    $view->setMessaggiErroreConferma(VHome::ERROR);
                } else {
                    $view->setMessaggiErroreConferma(VHome::NULLA);
                }
            }

            $view->showThread();
            header("Location: /UniChat/threads/$threadID/");

        } else {

            $view1->setValoriErrore(VError::CODE_404, VError::TYPE_404);
            $view1->showError();

        }


    }


    /**
     * @param int $categoriaID
     * @return EThread
     * Metodo responsabile del recupero del thread più discusso in base alla categoria.
     */
    public function threadsPiuDiscussi(int $numeroThreads): ?array {


        $pm = FPersistentManager::getInstance();

        $threads = $pm->loadThreadsPiuDiscussi($numeroThreads);

        return $threads;

    }

    /**
     * @param int $categoriaID
     * @return EThread
     * Metodo responsabile del recupero del thread maggiormente valutato in base alla categoria.
     */
    public function threadsValutazionePiuAlta(int $numeroThreads): ?array {


        $pm = FPersistentManager::getInstance();

        $threads = $pm->loadThreadsValutazionePiuAlta($numeroThreads);

        return $threads;

    }

    /**
     * @param int $threadID
     * Metodo responsabile dell'eliminazione di un thread specifico mediante l'id di quest'ultimo. Verrà verificato
     * se l'utente loggato è un Admin o il Moderatore della categoria dove è stato pubblicato il thread, in tal caso
     * verrà eliminato il thread dal DB e se l'operazione è andata a buon fine visualizzeremo un messaggio di successo.
     */
    public function rimuoviThread(int $threadID): void {

        $session = new USession();
        $user = unserialize($session->getValue('user'));

        $pm = FPersistentManager::getInstance();

        $view = new VCategoria();

        $thread = $pm->load(FPersistentManager::ENTITY_THREAD, FPersistentManager::PROPERTY_DEFAULT, $threadID);
        $categoriaID = $thread->getCategoriaThread()->getID();


        if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getID()) == true){

            if ($pm->delete(FPersistentManager::ENTITY_THREAD, $threadID) == true) {

                //DEVE CORREGGERE IL METODO NINO
                header("Location: /UniChat/categoria/$categoriaID/conferma");


            } else {

                header("Location: /UniChat/threads/$threadID/errore");

            }

        }

        elseif ($pm->isA(FPersistentManager::ENTITY_MODERATORE, $user->getID()) == true) {

            $mod = $pm->load(FPersistentManager::ENTITY_MODERATORE, FPersistentManager::PROPERTY_DEFAULT, $user->getID());
            $cat = $pm->load(FPersistentManager::ENTITY_CATEGORIA, FPersistentManager::PROPERTY_BY_THREAD, $threadID);

            if (isset($mod) && isset($cat) && $mod->getCategoriaGestita()->getNome() == $cat->getNome()) {

                if ($pm->delete(FPersistentManager::ENTITY_THREAD, $threadID) == true) {

                    //DEVE CORREGGERE IL METODO NINO
                    header("Location: /UniChat/threads/$categoriaID/conferma");

                } else {

                    header("Location: /UniChat/threads/$threadID/errore");

                }

            }
        }



    }

    /**
     * @param int $rispostaID
     * @param int $threadID
     * Metodo responsabile dell'eliminazione di una risposta specifica mediante l'id di quest'ultima e l'id del thread.
     * Verrà verificato se l'utente loggato è un Admin o il Moderatore della categoria del thread dove è stato pubblicata
     * la risposta, in tal caso verrà eliminata la risposta dal DB e se l'operazione è andata a buon fine visualizzeremo
     * un messaggio di successo altrimenti verrà visualizzato un messaggio d'errore.
     */
    public function rimuoviRisposta(int $rispostaID, int $threadID): void {

        $session = new USession();
        $user = unserialize($session->getValue('user'));

        $pm = FPersistentManager::getInstance();

        $view = new VThread();


        if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getID()) == true){

            if ($pm->delete(FPersistentManager::ENTITY_RISPOSTA, $rispostaID) == true) {

                header("Location: /UniChat/threads/$threadID/conferma");

            } else {

                header("Location: /UniChat/threads/$threadID/errore");

            }

        }

        elseif ($pm->isA(FPersistentManager::ENTITY_MODERATORE, $user->getID()) == true) {

            $mod = $pm->load(FPersistentManager::ENTITY_MODERATORE, FPersistentManager::PROPERTY_DEFAULT, $user->getID());
            $cat = $pm->load(FPersistentManager::ENTITY_CATEGORIA, FPersistentManager::PROPERTY_BY_THREAD, $threadID);

            if (isset($mod) && isset($cat) && $mod->getCategoriaGestita()->getNome() == $cat->getNome()) {

                if ($pm->delete(FPersistentManager::ENTITY_RISPOSTA, $rispostaID) == true) {

                    header("Location: /UniChat/threads/$threadID/conferma");

                } else {

                    header("Location: /UniChat/threads/$threadID/errore");

                }

            }
        }

    }

    /**
     * @param int $categoriaID
     * @param int $numeroPagina
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

        $testoRicerca = $view->getValori(VForm::FORM_RICERCA)['testoricerca'];
        $categoriaRicerca = $view->getValori(VForm::FORM_RICERCA)['categoriaID'];

        $view1 = new VCategoria();

        /*
         * La riga di partenza deve essere calcolata in base alla pagina selezionata.
         * Se siamo in pagina 1 la riga di partenza sarà 0, se siamo in pagina 2 la riga di partenza sarà 6
         * se siamo in pagina 3 la riga di partenza sarà 12 e così via.
         * rigaDiPartenza = (6 x numeroPaginaSelezionata) - 6
         * Questo perché le pagine partono con indice 1 e le righe con indice 0
         */
        $rigaDiPartenza = 6*($numeroPagina - 1);

        if (isset($categoriaRicerca)) {

            $threads = $pm->ricercaThreads(FPersistentManager::SEARCH_TYPE_TITOLO_CATEGORIE, $testoRicerca, $categoriaRicerca, $rigaDiPartenza, 6);
            $view1->setCategoriaRicerca($categoriaRicerca);
            $view1->setActivePage($numeroPagina);
            $view1->setThreads($threads);

        } else {

            $threads = $pm->ricercaThreads(FPersistentManager::SEARCH_TYPE_TITOLO, $testoRicerca, null, $rigaDiPartenza, 6);
            $view1->setCategoriaRicerca(null);
            $view1->setActivePage($numeroPagina);
            $view1->setThreads($threads);

        }


        $view1->showRicerca();

    }

    /**
     * @param int $allegatoID
     * Metodo responsabile del recupero di un allegato in base al suo id.
     */
    public function scaricaAllegato(int $allegatoID): void {

        $pm = FPersistentManager::getInstance();

        $allegato = $pm->loadAllegato($allegatoID);

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