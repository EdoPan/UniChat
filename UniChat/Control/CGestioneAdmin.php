<?php
declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";
class CGestioneAdmin
{
    public function aggiungiModeratore(int $userID, int $categoriaID): void
    {
        $session = new USession();
        $user = $session->getValue("user");

        if (isset($user)) {

            $user = unserialize($user);
            $pm = FPersistentManager::getInstance();

            if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getId())) {

                try {
                    $categoria = $pm->load(FPersistentManager::ENTITY_CATEGORIA, FPersistentManager::PROPERTY_DEFAULT, $categoriaID);
                } catch (ValidationException $e) {
                    $categoria = null;
                }
                try {
                    $nuovoModeratore = $pm->load(FPersistentManager::ENTITY_USER, FPersistentManager::PROPERTY_DEFAULT, $userID);
                } catch (ValidationException $e) {
                    $nuovoModeratore = null;
                }

                if (isset($categoria) && isset($nuovoModeratore)) {
                    $result = $pm->updateModeratoreCategoria($categoria, $nuovoModeratore);
                    if ($result) {
                        header('Location: /UniChat/admin/visualizzaPannelloDiControllo/conferma');
                    } else {
                        header('Location: /UniChat/admin/visualizzaPannelloDiControllo/errore');
                    }
                } else {
                    header('');
                }
            } else {
                header('Location: /UniChat/home/visualizzaHome');
            }
        } else {
            header('Location: /UniChat/utenti/login');
        }
    }

    public function rimuoviModeratore(int $moderatoreID): void
    {
        $session = new USession();
        $user = $session->getValue("user");
        if (isset($user)) {
            $user = unserialize($user);
            $pm = FPersistentManager::getInstance();
            if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getId())) {
                try {
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
                    header('');
                }
            } else {
                header('Location: /UniChat/home/visualizzaHome');
            }
        } else {
            header('Location: /UniChat/utenti/Login');
        }
    }

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
                header('Location: /UniChat/home/visualizzaHome');
            }
        } else {
            header('Location: /UniChat/utenti/login');
        }
    }

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
                header('/UniChat/home/visualizzaHome');
            }
        } else {
            header('/UniChat/utenti/login');
        }
    }

    public function visualizzaPannelloDiControllo(): void
    {
        $session = new USession();
        $user = $session->getValue("user");

        if (isset($user)) {
            $user = unserialize($user);
            $pm = FPersistentManager::getInstance();

            if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getId())) {
                $numeroUtenti = $pm->contaEntities(FPersistentManager::ENTITY_USER, FPersistentManager::PROPERTY_DEFAULT, null);
                $numeroCategorie = $pm->contaEntities(FPersistentManager::ENTITY_CATEGORIA, FPersistentManager::PROPERTY_DEFAULT, null);
                try {
                    $categorie = $pm->loadAllCategorie();
                } catch (ValidationException $e) {
                    $categorie = null;
                }

                if (isset($numeroUtenti) && isset($numeroCategorie) && isset($categorie)) {
                    $vPage = new VPage();
                    $vPage->setMenuUtente($user, true);
                    $vPage->setMenuLeft($categorie);
                    $vPage->setBottoneFiltra($categorie);

                    $vAmministrazione = new VAmministrazione();
                    $vAmministrazione->setPaginazioneUtenti($numeroUtenti);
                    $vAmministrazione->setPaginazioneCategorie($numeroCategorie);
                    $vAmministrazione->setBottoneAggiungiRimuoviModeratore($categorie);

                    if (func_num_args() == 1) {
                        if (func_get_arg(0) == "conferma") {
                            $vAmministrazione->setMessaggiConfermaErroreOperazioni(true);
                        } else if (func_get_arg(0) == "errore") {
                            $vAmministrazione->setMessaggiConfermaErroreOperazioni(false);
                        } else {
                            $vAmministrazione->setMessaggiConfermaErroreOperazioni(null);
                        }
                    } else {
                        $vAmministrazione->setMessaggiConfermaErroreOperazioni(null);
                    }

                    $vAmministrazione->showPannelloDiControllo();
                } else {
                    header('');
                }
            } else {
                header('Location: /UniChat/home/visualizzaHome');
            }
        } else {
            header('Location: /UniChat/utenti/login');
        }
    }
}