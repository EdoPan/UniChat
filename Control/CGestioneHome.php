<?php
declare(strict_types = 1);
require_once __DIR__.DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "utility.php";

/**
 * Classe responsabile della Gestione della pagina iniziale.
 */
class CGestioneHome
{

    /**
     * Metodo responsabile della visualizzazione della pagina iniziale del sito.
     */
    public function visualizzaHome(): void
    {
        $session = new USession();
        $user = $session->getValue('user');
        $pm = FPersistentManager::getInstance();
        $vHome = new VHome();
        $vPage = new VPage($vHome->getSmarty());

        try {

            $threadsPiuDiscussi = $pm->loadThreadsPiuDiscussi(VHome::NUMERO_MAX_THREADS);
            $threadsValutazionePiuAlta = $pm->loadThreadsValutazionePiuAlta(VHome::NUMERO_MAX_THREADS);
            $categorie = $pm->loadAllCategorie();

        } catch (ValidationException $e) {

            $threadsPiuDiscussi = null;
            $threadsValutazionePiuAlta = null;
            $categorie = null;

        }

        if (isset($threadsPiuDiscussi) && isset($threadsValutazionePiuAlta) && isset($categorie)) {

            $vHome->setBoxThread($threadsPiuDiscussi, $threadsValutazionePiuAlta);
            $vPage->setBottoneFiltra($categorie);
            $vPage->setMenuLeft($categorie);
            $vPage->setMenuUtente(null, false);
            $vHome->setBottoneElimina(false);
            $vHome->setInterazioneChat(false);


            if (isset($user)) {

                $user = unserialize($user);

                if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getId()) || $pm->isA(FPersistentManager::ENTITY_MODERATORE, $user->getId())) {

                    $vHome->setBottoneElimina(true);

                    if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getId())) {

                        $vPage->setMenuUtente($user, true);

                    } else {

                        $vPage->setMenuUtente($user, false);
                    }

                } else {

                    $vHome->setBottoneElimina(false);
                    $vPage->setMenuUtente($user, false);

                }

                $vHome->setInterazioneChat(true);
            }

            if (func_num_args() == 1) {
                if (func_get_arg(0) == "conferma") {
                    $vHome->setMessaggio(true, VHome::SUCCESS, 'success');
                } else if (func_get_arg(0) == "errore") {
                    $vHome->setMessaggio(true, VHome::ERROR, 'danger');
                } else {
                    $vHome->setMessaggio(false, VHome::NULLA, null);
                }
            } else {
                $vHome->setMessaggio(false, VHome::NULLA, null);
            }

            $vHome->showHome();

        } else {

            $vError = new VError();
            $vError->setValoriErrore(VError::CODE_500, VError::TYPE_500);
            $vError->showError();

        }
    }

}