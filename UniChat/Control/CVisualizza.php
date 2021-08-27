<?php
    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

/**
 * Classe contenente tutti i metodi relativi alla visualizzazione di contenuti.
 */
class CVisualizza {
    /**
     *
     */
    public function __construct(){}

    /**
     * Metodo responsabile della visualizzazione di 10 categorie.
     * @return array
     */
    public function elencaCategorie(): array
    {
        $pm = FPersistentManager::getInstance();
        $result = $pm->loadEntities(ENTITY_CATEGORIA, PROPERTY_DEFAULT, null, 1, 10); //FCategoria::loadAll(); //min: 1, max: 10
        return $result;
    }

    /**
     * Metodo responsabile della visualizzazione del thread più discusso in una categoria.
     * @param int $categoriaID
     * @return EThread
     */
    public function threadPiùDiscusso(int $categoriaID): EThread
    {
        $pm = FPersistentManager::getInstance();
        $result = $pm->load(ENTITY_THREAD, PROPERTY_PIU_DISCUSSO_CATEGORIA, $categoriaID);
        return $result;

    }

    /**
     * Metodo responsabile della visualizzazione del thread con valutazione più elevata in una categoria.
     * @param int $categoriaID
     * @return EThread
     */
    public function threadValutazionePiùAlta(int $categoriaID): EThread
    {
        $pm = FPersistentManager::getInstance();
        $result = $pm->load(ENTITY_THREAD, PROPERTY_VALUTAZIONE_MAGGIORE_CATEGORIA, $categoriaID);
        return $result;
    }

    /**
     * Metodo responsabile della visualizzazione di 10 users.
     * @return array
     */
    public function elencaUsers(): array
    {
        $pm = FPersistentManager::getInstance();
        $result = $pm->loadEntities(ENTITY_USER, PROPERTY_DEFAULT, null, 1, 10); //min: 1, max: 10
        return $result;
    }

    /**
     * Metodo responsabile della visualizzazione di 10 moderatori.
     * @return array
     */
    public function elencaModeratori(): array
    {
        $pm = FPersistentManager::getInstance();
        $result = $pm->loadEntities(ENTITY_MODERATORE, PROPERTY_DEFAULT, null, 1, 10); //min: 1, max: 10
        return $result;
    }

    /**
     * Metodo di ricerca unico, effettua sia la ricerca filtrata che quella non filtrata.
     * @param $selettore
     * @param string $titoloThread
     * @param array|null $categorieIDs
     * @return array
     */
    public function ricerca(string $titoloThread, ?array $categorieIDs): array
    {
        $pm = FPersistentManager::getInstance();
        if (isset($categorieIDs)) {
            $result = $pm->ricercaThreads(SEARCH_TYPE_TITOLO_CATEGORIE, $titoloThread, $categorieIDs);
        } else {
            $result = $pm->ricercaThreads(SEARCH_TYPE_TITOLO, $titoloThread, null);
        }
        return $result;
    }

    /**
     * Metodo responsabile della visualizzazione di 10 threads.
     * @param int $categoriaID
     * @return array
     */
    public function elencaThreads(int $categoriaID): array
    {
        $pm = FPersistentManager::getInstance();
        $result = $pm->loadEntities(ENTITY_THREAD, PROPERTY_BY_CATEGORIA, $categoriaID, 1, 10); //min: 1, max: 10
        return $result;
    }

    /*
    public function threadPerCategoria(int $threadID, int $categoriaID): EThread
    {

    }
    */

    /**
     * Metodo responsabile della visualizzazione di 10 messaggi della chat.
     * @return array
     */
    public function loadChat(): array
    {
        $pm = FPersistentManager::getInstance();
        $result = $pm->loadEntities(ENTITY_MESSAGGIO, PROPERTY_DEFAULT, null, 1, 10); //min: 1, max: 10
        return $result;
    }

    /**
     * Metodo responsabile della visualizzazione di un profilo utente.
     * @param int $userID
     * @return EUser
     */
    public function showProfile(int $userID): EUser
    {
        $pm = FPersistentManager::getInstance();
        $result = $pm->load(ENTITY_USER, PROPERTY_DEFAULT, $userID);
        return $result;
    }

    /**
     * Metodo responsabile della visualizzazione del profilo personale.
     * @return EUser
     */
    public function showPersonalProfile(int $userID //Ancora non sappiamo come recuperare l'id dell'utente dalla sessione attuale!
    ): EUser
    {
        $pm = FPersistentManager::getInstance();
        $user = $pm->load(ENTITY_USER, PROPERTY_DEFAULT, $userID);
        $user->getNome();
        $user->getCognome();
        $user->getEmail();
        $user->getFotoProfilo();
        $user->getCorsoStudio();
        return $user;
    }
}
