<?php
    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class CInterazione
    {
        public function __construct(){}


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


        public function rispondiThread(int $userID, string $testo, int $threadID): bool
        {
            $pm = FPersistentManager::getInstance();
            $user = $pm->load(ENTITY_USER, PROPERTY_DEFAULT, $userID);
            $rispID = null;
            $risposta = new ERisposta($rispID, $testo, $user);
            $result = $pm->storeRispostaThread($risposta, $threadID);
            return $result;
        }


        public function valutaThread(int $userID, int $valore, int $threadID): bool
        {
            $pm = FPersistentManager::getInstance();
            $user = $pm->load(ENTITY_USER, PROPERT_DEFAULT, $userID);
            $valutazione = $pm->load(ENTITY_VALUTAZONE, PROPERTY_DEFAULT, $threadID);
            $valutazione->valuta($user, $valore);
            $result = $pm->update(ENTITY_VALUTAZIONE, PROPERTY_DEFAULT, $valutazione);
            return $result;
        }


        public function creaMessaggio(string $testo, string $data, int $userID): bool
        {
            $pm = FPersistentManager::getInstance();
            $user = $pm->load(ENTITY_USER, PROPERTY_DEFAULT, $userID);
            $id = null;
            $messaggio = new EMessaggio($id, $testo, $data, $user);
            $result = $pm->store(ENTITY_MESSAGGIO, $messaggio);
            return $result;
        }
    }