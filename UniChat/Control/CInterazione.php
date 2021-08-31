<?php
    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

/**
 * Classe di controllo contenente tutti i metodi di interazione alla piattaforma UniChat.
 */
class CInterazione {
    /**
     *
     */
    public function __construct(){}

    /**
     * Metodo responsabile della creazione di un oggetto Thread e relativo salvataggio su DB.
     * @param int $userID
     * @param string $titolo
     * @param int $categoriaID
     * @param string $testo
     * @param array|null $allegati
     * @return bool
     */
    public function creaThread(int $userID, string $titolo, int $categoriaID, string $testo, ?array $allegati): bool
    {
        $pm = FPersistentManager::getInstance();
        $user = $pm->load(FPersistentManager::ENTITY_USER, FPersistentManager::PROPERTY_DEFAULT, $userID);
        $cat = $pm->load(FPersistentManager::ENTITY_CATEGORIA, FPersistentManager::PROPERTY_DEFAULT, $categoriaID);
        if(isset($user) and isset($cat)){
            $valutazioneID = null;
            $valutazione = new EValutazione($valutazioneID, null, null, null);
            $threadID = null;
            $thread = new EThread($threadID, $titolo, $testo, null, $allegati, $user, $cat, $valutazione, null);
            $result = $pm->store(FPersistentManager::ENTITY_THREAD, $thread);
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * Metodo responsabile della creazione di una risposta ad uno specifico thread e relativo
     * salvataggio di quest'ultima su DB.
     * @param int $userID
     * @param string $testo
     * @param int $threadID
     * @return bool
     */
    public function rispondiThread(int $userID, string $testo, int $threadID): bool
    {
        $pm = FPersistentManager::getInstance();
        $user = $pm->load(FPersistentManager::ENTITY_USER, FPersistentManager::PROPERTY_DEFAULT, $userID);
        if(isset($user)) {
            $rispID = null;
            $risposta = new ERisposta($rispID, $testo, null, $user);
            $result = $pm->storeRispostaThread($risposta, $threadID);
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * Metodo responsabile della valutazione di un thread e relativo salvataggio di quest'ultima su DB.
     * @param int $userID
     * @param int $valore
     * @param int $threadID
     * @return bool
     */
    public function valutaThread(int $userID, int $valore, int $threadID): bool
    {
        $pm = FPersistentManager::getInstance();
        $user = $pm->load(FPersistentManager::ENTITY_USER, FPersistentManager::PROPERTY_DEFAULT, $userID);
        $valutazione = $pm->load(FPersistentManager::ENTITY_VALUTAZIONE, FPersistentManager::PROPERTY_BY_THREAD, $threadID);
        if(isset($user) and isset($valutazione)) {
            $valutazione->valuta($user, $valore);
            $result = $pm->update(FPersistentManager::ENTITY_VALUTAZIONE, $valutazione);
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * Metodo responsabile della creazione di un messaggio sulla chat e relativo salvataggio su DB.
     * @param string $testo
     * @param int $userID
     * @return bool
     */
    public function creaMessaggio(string $testo, int $userID): bool
    {
        $pm = FPersistentManager::getInstance();
        $user = $pm->load(FPersistentManager::ENTITY_USER, FPersistentManager::PROPERTY_DEFAULT, $userID);
        if(isset($user)) {
            $id = null;
            $messaggio = new EMessaggio($id, $testo, null, $user);
            $result = $pm->store(FPersistentManager::ENTITY_MESSAGGIO, $messaggio);
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * Metodo responsabile per la modifica ed il salvataggio delle informazioni dell'utente.
     * @param int $userID
     * @param string $password
     * @param array $fotoProfilo
     * @param string $corsoStudio
     * @return bool
     */
    public function editPersonalProfile(int $userID, //Ancora non sappiamo come recuperare l'id dell'utente dalla sessione attuale!
        string $password, array $fotoProfilo, string $corsoStudio): bool
    {
        $pm = FPersistentManager::getInstance();
        $user = $pm->load(FPersistentManager::ENTITY_USER, FPersistentManager::PROPERTY_DEFAULT, $userID);
        if(isset($user)) {

            $user->setPassword($password);
            $user->setFotoProfilo($fotoProfilo);
            $user->setCorsoStudio($corsoStudio);
            $result = $pm->update(FPersistentManager::ENTITY_USER, $user);
        } else {
            $result = false;
        }
        return $result;
    }
}