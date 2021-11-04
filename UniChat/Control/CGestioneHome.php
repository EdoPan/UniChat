<?php

/**
 * Classe responsabile della Gestione della pagina iniziale.
 */
class CGestioneHome
{


    /**
     * Metodo responsabile della visualizzazione della Home in base al tipo di utente connesso.
     */
    public function visualizzaHome(): void
    {

        $pm = FPersistentManager::getInstance();
        $view = new VHome();
        $session = new USession();
        $cthread = new CGestioneThreads();

        $utente = $session->getValue('user');

        if (isset($utente)) {

            $user = unserialize($utente);

            /*
             * Condizione per utente User.
             * Visualizzeremo il box contenete i thread più discussi e maggiormente valutati e la chat con la possibilità
             * d'interazione con gli altri utenti.
             */
            if ($pm->isA(FPersistentManager::ENTITY_MODERATORE, $user->getID()) == false or $pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getID()) == false) {

                $view->setBottoneElimina(false);

            } else {

                /*
                 * Condizione per utente Moderatore/Admin.
                 * Visualizzeremo il box contenete i thread più discussi e maggiormente valutati, la chat con la possibilità
                 * d'interazione con gli altri utenti, e i cestini per l'eliminazione dei messaggi.
                 */
                $view->setBottoneElimina(true);

            }

            $view->setInterazioneChat(true);


        } else {

            /*
             * Condizione per utente Guest.
             * Visualizzeremo il box contenete i thread più discussi e maggiormente valutati e la chat senza la possibilità
             * d'interazione con gli altri utenti.
             */
            $view->setBottoneElimina(false);
            $view->setInterazioneChat(false);

        }

        $view->setBoxThread($cthread->threadsPiuDiscussi(6), $cthread->threadsValutazionePiuAlta(6));
        $view->showHome();


        /*
         * Condizione per passare i valori alla variabile smarty $messaggio che gestisce la comparsa dell'alert
         * con l'eventuale messaggio di conferma o errore.
         */
        if (func_num_args() == 1) {
            if (func_get_arg(0) == "conferma") {
                $view->setMessaggiErroreConferma(VHome::SUCCESS);
            } else if (func_get_arg(0) == "errore") {
                $view->setMessaggiErroreConferma(VHome::ERROR);
            } else {
                $view->setMessaggiErroreConferma(VHome::NULLA);
            }

        }


    }

}