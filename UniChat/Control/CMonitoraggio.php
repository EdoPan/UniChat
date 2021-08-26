<?php
    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

/**
 * Classe di controllo contenente tutti i metodi relativi al monitoraggio della piattaforma UniChat.
 */
class CMonitoraggio {
    /**
     *
     */
    public function __construct(){}

    /**
     * Metodo responsabile della rimozione di un messaggio dalla chat libera.
     * @param int $userID
     * @param int $messID
     * @return bool
     */
    public function rimuoviMessaggio(int $userID, int $messID): bool
    {
        $pm = FPersistentManager::getInstance();
        if($pm->isA(ENTITY_MODERATORE, $userID) == true or $pm->isA(ENTITY_ADMIN, $userID) == true){
            $result = $pm->delete(ENTITY_MESSAGGIO, PROPERTY_DEFAULT, $messID);
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * Metodo responsabile della rimozione di un thread.
     * @param int $userID
     * @param int $threadID
     * @return bool
     */
    public function rimuoviThread(int $userID, int $threadID): bool
    {
        $pm = FPersistentManager::getInstance();
        if ($pm->isA(ENTITY_ADMIN, $userID) == true){
            $pm->delete(ENTITY_VALUTAZIONE, PROPERTY_BY_THREAD, $threadID); //FValutazione::deleteByThread($threadID);
            $result = $pm->delete(ENTITY_THREAD, PROPERTY_DEFAULT, $threadID); //FThread::delete($threadID);
        } elseif ($pm->isA(ENTITY_MODERATORE, $userID) == true){
            $mod = $pm->load(ENTITY_MODERATORE, PROPERTY_DEFAULT, $userID); //FUser::loadModeratore($userID);
            $cat = $pm->load(ENTITY_CATEGORIA, PROPERTY_DEFAULT, $threadID); //FCategoria::loadCategoriaThread($threadID);
            if($mod->getCategoriaGestita()->getNome() == $cat->getNome()){
                $pm->delete(ENTITY_VALUTAZIONE, PROPERTY_BY_THREAD, $threadID); //FValutazione::deleteByThread($threadID);
                $result = $pm->delete(ENTITY_THREAD, PROPERTY_DEFAULT, $threadID); //FThread::delete($threadID);
            }
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * Metodo responsabile della rimozione di una risposta di un thread.
     * @param int $userID
     * @param int $rispostaID
     * @param int $threadID
     * @return bool
     */
    public function rimuoviRisposte(int $userID, int $rispostaID, int $threadID): bool
    {
        $pm = FPersistentManager::getInstance();
        if ($pm->isA(ENTITY_ADMIN, $userID) == true){
            $result = $pm->delete(ENTITY_RISPOSTA, PROPERTY_DEFAULT, $rispostaID); //FRisposta::delete($rispostaID);
        } elseif ($pm->isA(ENTITY_MODERATORE, $userID) == true){
            $mod = $pm->load(ENTITY_MODERATORE, PROPERTY_DEFAULT, $userID); //FUser::loadModeratore($userID);
            $cat = $pm->load(ENTITY_CATEGORIA, PROPERTY_DEFAULT, $threadID); //FCategoria::loadCategoriaThread($threadID);
            if($mod->getCategoriaGestita()->getNome() == $cat->getNome()){
                $result = $pm->delete(ENTITY_RISPOSTA, PROPERTY_DEFAULT, $rispostaID); //FRisposta::delete($rispostaID);
            }
        } else {
            $result = false;
        }
        return $result;
    }
}