<?php
    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

/**
 * Classe di controllo contenente tutti i metodi accessibili al solo utente EAdmin.
 */
class CAmministrazione
    {
    /**
     *
     */
    public function __construct(){}


    /**
     * Metodo responsabile della creazione di una nuova categoria e relativo salvataggio di quest'ultima su DB.
     * @param int $adminID
     * @param string $nomeCategoria
     * @param array $icona
     * @param string $descrizione
     * @return bool
     */
    public function aggiungiCategoria(int $adminID, string $nomeCategoria, array $icona, string $descrizione): bool
        {
            $pm = FPersistentManager::getInstance();
            $admin = $pm->load(ENTITY_ADMIN, PROPERTY_DEFAULT, $adminID);
            $categoriaID = null;
            $categoria = $admin->creaCategoria($categoriaID, $nomeCategoria, $icona, $descrizione);
            $result = $pm->store(ENTITY_CATEGORIA, $categoria);
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
            $admin = $pm->load(ENTITY_ADMIN, PROPERTY_DEFAULT, $adminID);
            $user = $pm->load(ENTITY_USER, PROPERTY_DEFAULT, $userID);
            $categoria = $pm->load(ENTITY_CATEGORIA, PROPERTY_DEFAULT, $categoriaID);
            $mod = $admin->creaModeratore($user, $categoria);
            if(isset($mod)){
                $result = $pm->updateModeratoreCategoria($categoria, $mod); //FUser::updateMod($mod)
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
            if($pm->isA(ENTITY_ADMIN, $adminID)){
                $mod = $pm->load(ENTITY_MODERATORE, PROPERTY_BY_CATEGORIA, $categoriaID); //Da discutere con Antonio
                $pm->update(ENTITY_USER,PROPERTY_DEFAULT, $mod);
                $pm->update(ENTITY_THREAD, PROPERTY_BY_CATEGORIA, $categoriaID);
                $result = $pm->delete(ENTITY_CATEGORIA, PROPERTY_DEFAULT, $categoriaID);
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
     * @return false
     */
    public function rimuoviModeratore(int $adminID, int $moderatoreID){
            $pm = FPersistentManager::getInstance();
            if($pm->isA(ENTITY_ADMIN, $adminID)){
                $mod = $pm->loadMod(ENTITY_MODERATORE, PROPERTY_DEFAULT, $moderatoreID);
                $categoria = $mod->getCategoriaGestita();
                $pm->rimoviModeratoreCategoria($categoria);
                $result = $pm->update(ENTITY_USER, PROPERTY_DEFAULT, $mod); //Era updateToUser
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
     * @return false
     */
    public function rimuoviUser(int $adminID, int $userID){
            $pm = FPersistentManager::getInstance();
            if($pm->isA(ENTITY_ADMIN, $adminID)){
                $user = $pm->load(ENTITY_USER, PROPERTY_DEFAULT, $userID);
                $result = $pm->delete(ENTITY_USER, PROPERTY_DEFAULT, $user->getID()); //Manca nel Fondation il metodo delete che mediante l'userID fa saltare l'utente dal database
            } else {
                $result = false;
            }
            return $result;
        }
    }