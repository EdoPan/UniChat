<?php

/**
 * Classe responsabile della Gestione della pagina iniziale.
 */
class CGestioneHome
{


    /**
     * Metodo responsabile della visualizzazione della Home in base al tipo di utente connesso.
     */
    public function visualizzaHome(): void {

        $pm = FPersistentManager::getInstance();
        $view = new VHome();
        $session = new USession();
        $utente = unserialize($session->getValue('user'));

        $view->setMessaggiErroreConferma(VHome::NULLA);


        /*
         * Condizione per utente Guest.
         * Visualizzeremo il box contenete i thread più discussi e maggiormente valutati e la chat senza la possibilità
         * d'interazione con gli altri utenti.
         */
        if(!isset($utente)){

            $view->setBoxThread($pm->loadThreadsPiuDiscussi(6), $pm->loadThreadsValutazionePiuAlta(6));
            $view->setInterazioneChat(false);
            $view->showHome();

        }

        /*
         * Condizione per utente User.
         * Visualizzeremo il box contenete i thread più discussi e maggiormente valutati e la chat con la possibilità
         * d'interazione con gli altri utenti.
         */
        elseif ($pm->isA(FPersistentManager::ENTITY_MODERATORE or FPersistentManager::ENTITY_ADMIN, $utente->getID()) == false) {

            $view->setBoxThread($pm->loadThreadsPiuDiscussi(6), $pm->loadThreadsValutazionePiuAlta(6));
            $view->setInterazioneChat(true);
            $view->showHome();

        }

        /*
         * Condizione per utente Moderatore/Admin.
         * Visualizzeremo il box contenete i thread più discussi e maggiormente valutati, la chat con la possibilità
         * d'interazione con gli altri utenti, e i cestini per l'eliminazione dei messaggi.
         */
        elseif ($pm->isA(FPersistentManager::ENTITY_MODERATORE or FPersistentManager::ENTITY_ADMIN, $utente->getID()) == true) {

            $view->setBoxThread($pm->loadThreadsPiuDiscussi(6), $pm->loadThreadsValutazionePiuAlta(6));
            $view->setInterazioneChat(true);
            $view->setBottoneElimina(true);
            $view->showHome();

        }

        header('Location: /UniChat/home/');

    }
}