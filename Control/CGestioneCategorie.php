<?php

declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";

/**
 * Classe di controllo contenente tutti i metodi con operazioni in cui sono coinvolte le categorie dei Threads.
 */
class CGestioneCategorie {

    /**
     * Metodo responsabile del recupero da Db della
     * categoria selezionata e della visualizzazione della propria
     * intestazione (icona, Titolo e descrizione) e dei thread in essa inseriti.
     *
     */

    public function visualizzaCategoria(int $idCategoria, int $numeroPaginaThread):void {

        $session = new USession();
        $user = $session->getValue('user');

        $pm = FPersistentManager::getInstance();
        $vCategoria = new VCategoria();
        $vPage = new vPage($vCategoria->getSmarty());

        try {
            $categoria = $pm->load(FPersistentManager::ENTITY_CATEGORIA, FPersistentManager::PROPERTY_DEFAULT, $idCategoria);
        } catch (ValidationException $e) {
            $categoria = null;
        }

        $numeroRiga = VCategoria::NUMERO_THREAD_PER_PAGINA * ($numeroPaginaThread - 1);
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

            if (isset($user)) {
                $user = unserialize($user);
                $vCategoria->setBottoneCreazioneThread(true, $categoria);
                if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getId())) {
                    $vPage->setMenuUtente($user, true);
                } else {
                    $vPage->setMenuUtente($user, false);
                }
            }

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

        } else {
            $vError = new VError();
            $vError->setValoriErrore(VError::CODE_500, VError::TYPE_500);
            $vError->showError();
        }
    }


    /**
     * Metodo responsabile della stampa del nome, cognome ed email del moderatore
     * di una categoria. (recuperata tramite id da richiesta POST)
     */

    public function visualizzaModeratoreCategoria():void {

        $session=new USession();
        $user=$session->getValue('user');

        $pm=FPersistentManager::getInstance();

        $vAmministazione=new VAmministrazione();
        $idCategoria=$vAmministazione->getValoreIdCategoriaModeratore();

        if(isset($user)) {
            $user=unserialize($user);
            if($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getID())) {

                if(isset($idCategoria)) {

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


            } else {
                header('/UniChat/');
            }
        } else {
            header('/UniChat/Utenti/login');
        }
    }

    public function contaThreadsCategoria():void {

        $session=new USession();
        $user=$session->getValue('user');

        $pm=FPersistentManager::getInstance();
        $vAmministazione=new VAmministrazione();
        $idCategoria=$vAmministazione->getValoreIdCategoriaContaThreads();

        if(isset($user)) {
            $user = unserialize($user);
            if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getID())) {
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
            } else {
                header('/UniChat/');
            }

        } else {
            header('/UniChat/Utenti/login');
        }
    }
}