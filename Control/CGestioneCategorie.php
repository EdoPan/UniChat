<?php

declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";

/**
 * Classe di controllo a cui sono affidate tutte le operazioni in cui sono coinvolte le categorie dei Threads.
 */
class CGestioneCategorie {

    /**
     * Metodo responsabile del recupero da Db della
     * categoria selezionata e della visualizzazione della propria
     * intestazione (icona, Titolo e descrizione) e dei thread in essa inseriti.
     * Inoltre, se l'utente è loggato, viene visualizzato il bottone per la creazione di
     * un nuovo thread in tale categoria.
     * @param int $idCategoria
     * @param int $numeroPaginaThread
     */

    public function visualizzaCategoria(int $idCategoria, int $numeroPaginaThread):void {

        //Recupero dell'utente dalla sessione
        $session = new USession();
        $user = $session->getValue('user');

        $pm = FPersistentManager::getInstance();
        $vCategoria = new VCategoria();
        $vPage = new vPage($vCategoria->getSmarty());


        //Recupero della categoria da DB
        try {
            $categoria = $pm->load(FPersistentManager::ENTITY_CATEGORIA, FPersistentManager::PROPERTY_DEFAULT, $idCategoria);
        } catch (ValidationException $e) {
            $categoria = null;
        }

        $numeroRiga = VCategoria::NUMERO_THREAD_PER_PAGINA * ($numeroPaginaThread - 1);

        //Recupero dei Threads della categoria in base al numero di pagina (dalla URL) e
        // di quanti visualizzarne sulla pagina
        try {
            $threads = $pm->loadEntities(FPersistentManager::ENTITY_THREAD, FPersistentManager::PROPERTY_BY_CATEGORIA, $idCategoria, $numeroRiga, VCategoria::NUMERO_THREAD_PER_PAGINA);
        } catch (ValidationException $e) {
            $threads = null;
        }

        $numeroThreads = $pm->contaEntities(FPersistentManager::ENTITY_THREAD, FPersistentManager::PROPERTY_BY_CATEGORIA, $idCategoria, null);

        try {
            $categorie = $pm->loadAllCategorie();
        } catch (ValidationException $e) {
            $categorie = null;
        }


        // Se la categoria, la lista dei threads e il numero dei threads non sono null imposto la visualizzazione
        // della pagina.
        // Se la lista di tutte le categorie non è null imposta la visualizzazione del menù left e del
        // bottone Filtra per la ricerca.
        if (isset($categoria) && isset($threads) && isset($numeroThreads) && isset($categorie)) {

            $vCategoria->setActivePage($numeroPaginaThread);
            $vCategoria->setBottoneCreazioneThread(false, null);
            $vCategoria->setIDCategoria($categoria);
            $vCategoria->setIntestazionePagina($categoria);
            $vCategoria->setPaginazione($numeroThreads);
            $vCategoria->setThreads($threads);

            $vPage->setMenuUtente(null, false);
            $vPage->setMenuLeft($categorie);
            $vPage->setBottoneFiltra($categorie);


            // Se l'utente loggato è un amministratore imposto la visualizzazione del Menù utente per
            // l'amministratore (Pannello di Controllo)
            if (isset($user)) {
                $user = unserialize($user);
                $vCategoria->setBottoneCreazioneThread(true, $categoria);
                if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getId())) {
                    $vPage->setMenuUtente($user, true);
                } else {
                    $vPage->setMenuUtente($user, false);
                }
            }


            // Gestione degli avvisi di Successo o Errore recuperando un eventuale terzo paramentro dalla URL
            // Se non è presente allora l'avviso non viene mostrato.
            if (func_num_args() == 3) {
                if (func_get_arg(2) == "conferma") {
                    $vCategoria->setMessaggio(true, VCategoria::SUCCESS, 'success');
                } else if (func_get_arg(2) == "errore") {
                    $vCategoria->setMessaggio(true, VCategoria::ERROR, 'danger');
                } else {
                    $vCategoria->setMessaggio(false, VCategoria::NULLA, null);
                }
            } else {
                $vCategoria->setMessaggio(false, VCategoria::NULLA, null);
            }

            $vCategoria->showCategoria();


            // Pagina di errore nel caso non fosse riuscito il recupero da DB di Thread o Categorie.
        } else {
            $vError = new VError();
            $vError->setValoriErrore(VError::CODE_500, VError::TYPE_500);
            $vError->showError();
        }
    }


    /**
     * Metodo responsabile della stampa del nome, cognome ed email del moderatore
     * di una categoria. Risultati visualizzati nella gestione degli utenti
     * del pannello di controllo.
     */
    public function visualizzaModeratoreCategoria():void {

        //Recupero dell'utente dalla sessione
        $session=new USession();
        $user=$session->getValue('user');

        $pm=FPersistentManager::getInstance();

        //Recupero id della Categoria tramite view (richiesta post).
        $vAmministazione=new VAmministrazione();
        $idCategoria=$vAmministazione->getValoreIdCategoriaModeratore();

        if(isset($user)) {
            $user=unserialize($user);
            if($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getID())) {

                if(isset($idCategoria)) {

                    //Se esiste la categoria, recupero il moderatore dal db e stampo le info necessarie.

                    try {
                        $moderatore=$pm->load(FPersistentManager::ENTITY_MODERATORE, FPersistentManager::PROPERTY_BY_CATEGORIA , $idCategoria);
                        if (isset($moderatore)) {
                            echo json_encode($moderatore);
                        } else {
                            echo json_encode(array
                            ('nome' => 'Non',
                                'cognome' => 'Assegnato' ,
                                'email' => '-'
                            ));
                        }
                    } catch(ValidationException $e) {
                        $moderatore=null;
                    }

                } else {
                    $view = new VError();
                    $view->setValoriErrore(VError::CODE_500, VError::TYPE_500 );
                    $view->showError();
                }


                //Se l'utente non è l'amministratore si viene reindirizzati in home page.
            } else {
                header('/UniChat/');
            }

            //Se l'utente non è loggato si viene reindirizzati verso la pagina di login.
        } else {
            header('/UniChat/Utenti/login');
        }
    }

    /**
     * Metodo che stampa il numero di Thread per ogni categoria.
     * Il risultato viene mostrato nell'area della gestione categorie del pannello di controllo.
     */

    public function contaThreadsCategoria():void {

        //Recupero dell'utente dalla sessione
        $session=new USession();
        $user=$session->getValue('user');

        $pm=FPersistentManager::getInstance();
        //Recupero id della Categoria tramite view (richiesta post).
        $vAmministazione=new VAmministrazione();
        $idCategoria=$vAmministazione->getValoreIdCategoriaContaThreads();

        if(isset($user)) {
            $user = unserialize($user);
            if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getID())) {

                //Se esiste la categoria, recupero il relativo numero dei thread e stampo le info necessarie.
                if (isset($idCategoria)) {
                    try {
                        $numeroThread = $pm->contaEntities(FPersistentManager::ENTITY_THREAD, FPersistentManager::PROPERTY_BY_CATEGORIA, $idCategoria, null);
                        if (isset($numeroThread)) {
                            echo json_encode(array('numeroThreads' => $numeroThread));
                        }
                    } catch (ValidationException $e) {
                        $numeroThread = null;
                    }
                } else {
                    $view = new VError();
                    $view->setValoriErrore(VError::CODE_500, VError::TYPE_500 );
                    $view->showError();
                }

                //Se l'utente non è l'amministratore si viene reindirizzati in home page.
            } else {
                header('/UniChat/');
            }

            //Se l'utente non è loggato si viene reindirizzati verso la pagina di login.
        } else {
            header('/UniChat/Utenti/login');
        }
    }
}