<?php
    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

/**
 * Classe di controllo contenente tutti i metodi accessibili al solo utente EAdmin.
 */
class CAmministrazione {
    /**
     *
     */
    public function __construct(){}

    /**
     * Metodo responsabile della creazione di una nuova categoria e relativo salvataggio di quest'ultima su DB.
     * @param int $adminID
     * @param string $nomeCategoria
     * @param array|null $icona
     * @param string $descrizione
     * @return bool
     */
    public function aggiungiCategoria(int $adminID, string $nomeCategoria, ?array $icona, string $descrizione): bool
    {
        $pm = FPersistentManager::getInstance();
        $admin = $pm->load(FPersistentManager::ENTITY_ADMIN, FPersistentManager::PROPERTY_DEFAULT, $adminID);
        if(isset($admin)) {
            $categoriaID = null;
            $categoria = $admin->creaCategoria($categoriaID, $nomeCategoria, $icona, $descrizione);
            $result = $pm->store(FPersistentManager::ENTITY_CATEGORIA, $categoria);
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * Metodo responsabile della "trasformazione" da EUser a EModeratore e relativo
     * salvataggio di questa condizione nel DB.
     * @param int $adminID
     * @param int $userID
     * @param int $categoriaID
     * @return bool
     */
    public function aggiungiModeratore(int $adminID, int $userID, int $categoriaID): bool
    {
        $pm = FPersistentManager::getInstance();
        $admin = $pm->load(FPersistentManager::ENTITY_ADMIN, FPersistentManager::PROPERTY_DEFAULT, $adminID);
        $user = $pm->load(FPersistentManager::ENTITY_USER, FPersistentManager::PROPERTY_DEFAULT, $userID);
        $categoria = $pm->load(FPersistentManager::ENTITY_CATEGORIA, FPersistentManager::PROPERTY_DEFAULT, $categoriaID);
        if(isset($admin) and isset($user) and isset($categoria)) {
            $mod = $admin->creaModeratore($user, $categoria);
            if(isset($mod)){
                $result = $pm->updateModeratoreCategoria($categoria, $mod); //FUser::updateMod($mod)
            } else {
                $result = false;
            }
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * Metodo responsabile della rimozione di una categoria e relativa eliminazione di quest'ultima dal DB.
     * @param int $adminID
     * @param int $categoriaID
     * @return bool
     */
    public function rimuoviCategoria(int $adminID, int $categoriaID): bool
    {
        $pm = FPersistentManager::getInstance();
        if($pm->isA(FPersistentManager::ENTITY_ADMIN, $adminID)){
            $result = $pm->delete(FPersistentManager::ENTITY_CATEGORIA, $categoriaID);
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * Metodo responsabile della "trasformazione" da Moderatore a User e relativo
     * salvataggio di questa condizione nel DB.
     * @param int $adminID
     * @param int $moderatoreID
     * @return bool
     */
    public function rimuoviModeratore(int $adminID, int $moderatoreID): bool
    {
        $pm = FPersistentManager::getInstance();
        if($pm->isA(FPersistentManager::ENTITY_ADMIN, $adminID)){
            $mod = $pm->load(FPersistentManager::ENTITY_MODERATORE, FPersistentManager::PROPERTY_DEFAULT, $moderatoreID);
            if(isset($mod)) {
                $categoria = $mod->getCategoriaGestita();
                $result = $pm->rimuoviModeratoreCategoria($categoria->getID(), $mod);
            } else {
                $result = false;
            }
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * Metodo responsabile della rimozione di uno User dalla piattaforma UniChat e relativa
     * eliminazione di quest ultimo dal DB.
     * @param int $adminID
     * @param int $userID
     * @return bool
     */
    public function rimuoviUser(int $adminID, int $userID): bool
    {
        $pm = FPersistentManager::getInstance();
        if($pm->isA(FPersistentManager::ENTITY_ADMIN, $adminID)){
            $result = $pm->delete(FPersistentManager::ENTITY_USER, $userID);
        } else {
            $result = false;
        }
        return $result;
    }
}