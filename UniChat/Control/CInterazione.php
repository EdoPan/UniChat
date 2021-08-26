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
        $user = $pm->load(ENTITY_USER, PROPERTY_DEFAULT, $userID);
        $cat = $pm->load(ENTITY_CATEGORIA, PROPERTY_DEFAULT, $categoriaID);
        $valutazioneID = null;
        $valutazione = new EValutazione($valutazioneID, null, null, null);
        $pm->store(ENTITY_VALUTAZIONE, $valutazione);
        $threadID = null;
        $thread = new EThread($threadID, $titolo, $testo, $allegati, $user, $cat, $valutazione, null);
        $result = $pm->store(ENTITY_THREAD, $thread);
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
        $user = $pm->load(ENTITY_USER, PROPERTY_DEFAULT, $userID);
        $rispID = null;
        $risposta = new ERisposta($rispID, $testo, $user);
        $result = $pm->storeRispostaThread($risposta, $threadID);
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
        $user = $pm->load(ENTITY_USER, PROPERT_DEFAULT, $userID);
        $valutazione = $pm->load(ENTITY_VALUTAZONE, PROPERTY_BY_THREAD, $threadID);
        $valutazione->valuta($user, $valore);
        $result = $pm->update(ENTITY_VALUTAZIONE, PROPERTY_DEFAULT, $valutazione);
        return $result;
    }

    /**
     * Metodo responsabile della creazione di un messaggio sulla chat e relativo salvataggio su DB.
     * @param string $testo
     * @param string $data
     * @param int $userID
     * @return bool
     */
    public function creaMessaggio(string $testo, string $data, int $userID): bool
    {
        $pm = FPersistentManager::getInstance();
        $user = $pm->load(ENTITY_USER, PROPERTY_DEFAULT, $userID);
        $id = null;
        $messaggio = new EMessaggio($id, $testo, $data, $user);
        $result = $pm->store(ENTITY_MESSAGGIO, $messaggio);
        return $result;
    }

    /**
     * Metodo responsabile per la modifica ed il salvataggio delle informazioni dell'utente.
     * @param int $userID
     * @param string $nome
     * @param string $cognome
     * @param array $fotoProfilo
     * @param string $corsoStudio
     * @return bool
     */
    public function editPersonalProfile(int $userID, //Ancora non sappiamo come recuperare l'id dell'utente dalla sessione attuale!
        string $nome, string $cognome, array $fotoProfilo, string $corsoStudio): bool
    {
        $pm = FPersistentManager::getInstance();
        $user = $pm->load(ENTITY_USER, PROPERTY_DEFAULT, $userID);
        $user->setNome($nome);
        $user->setCognome($cognome);
        $user->setFotoProfilo($fotoProfilo);
        $user->setCorsoStudio($corsoStudio);
        $result = $pm->update(ENTITY_USER, PROPERTY_DEFAULT, $user);
        return $result;
    }
}