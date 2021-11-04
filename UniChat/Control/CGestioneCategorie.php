<?php

declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";

/**
 * Classe di controllo contenente tutti i metodi con operazioni in cui sono coinvolte le categorie dei Threads.
 */
class CGestioneCategorie {

    /**
     * Metodo responsabile del recupero da Db delle
     * categorie inserite dall'Admin e della loro
     * visualizzazione (solo titolo) per il Left Menu.
     *
     */

    /*
    public function elencaCategorie(): ?array {

        $pm = FPersistentManager::getInstance();
        try {
            $categorie=$pm->loadAllCategorie();
        } catch(ValidationException $e) {
            $categorie=null;
        }
        return $categorie;
    } */


    /**
     * Metodo responsabile del recupero da Db della
     * categoria selezionata e della visualizzazione della propria
     * intestazione (icona, Titolo e descrizione) e dei thread in essa inseriti.
     *
     */

    public function visualizzaCategoria(int $idCategoria, int $numeroPaginaThread):void {


        $session = new USession();
        $user = $session->getValue('user');
        $pm=FPersistentManager::getInstance();


        // Impostazione di VPage
        $admin = false;
        $loggato=false;

        $vPage=new VPage();
        $categorie=self::elencaCategorie();

        if (isset($user)) {
            $loggato=true;
            $user=unserialize($user);
            if (isset($categorie)) {

                if($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getID())) {
                    $admin=true;
                }

                $vPage->setMenuLeft($categorie);
                $vPage->setBottoneFiltra($categorie);
                $vPage->setMenuUtente($user, $admin);

            }
        }

        //Fine imp VPage


        try {

            $categoria=$pm->load(FPersistentManager::ENTITY_CATEGORIA, FPersistentManager::PROPERTY_DEFAULT, $idCategoria);

            $cThread=new CGestioneThreads();
            $threads=$cThread->elencaThreads($idCategoria, $numeroPaginaThread);
            $numeroThread=$pm->contaEntities(FPersistentManager::ENTITY_THREAD, FPersistentManager::PROPERTY_BY_CATEGORIA, $idCategoria);

        } catch(ValidationException $e) {
            $categoria=null;
            $threads=null;
            $numeroThread=null;
        }

        $view=new VCategoria();

        if(isset($categoria) && isset($threads) && isset($numeroThread)) {

            $view->setIDCategoria($categoria);
            $view->setIntestazionePagina($categoria);
            $view->setActivePage($numeroPaginaThread);
            $view->setThreads($threads);
            $view->setPaginazione($numeroThread);

            if ($loggato) {
                $view->setBottoneCreazioneThread(true, $categoria);
            }


            if (func_num_args() == 1) {
                if (func_get_arg(0) == "conferma") {
                    $view->setMessaggioConfermaEliminazioneThread(true);
                } else {
                    $view->setMessaggioConfermaEliminazioneThread(null);
                }
            } else {
                $view->setMessaggioConfermaEliminazioneThread(null);
            }

            $view->showCategoria();

        } else {
            $view = new VError();
            $view->setValoriErrore(VError::CODE_500, VError::TYPE_500 );
            $view->showError();
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

                        $numeroThread = $pm->contaEntities(FPersistentManager::ENTITY_THREAD, FPersistentManager::PROPERTY_BY_CATEGORIA, $idCategoria);
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