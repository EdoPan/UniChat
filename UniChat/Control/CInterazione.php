<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class CInterazione
    {
        public function __construct()
        {
        }

        public function creaThread(int $userID, string $titolo, int $categoriaID, string $testo, ?array $allegati): bool
        {
            $user = FUser::load($userID);
            $cat = FCategoria::load($categoriaID);
            $valutazioneID = null;
            $valutazione = new EValutazione($valutazioneID, null, null, null);
            FValutazione::store($valutazione);
            $threadID = null;
            $thread = new EThread($threadID, $titolo, $testo, $allegati, $user, $cat, $valutazione, null);
            $result = FThread::store($thread);
            return $result;
        }

        public function rispondiThread(int $userID, string $testo, int $threadID): bool
        {
            $user = FUser::load($userID);
            $rispID = null;
            $risposta = new ERisposta($rispID, $testo, $user);
            $result = FRisposta::store($risposta, $threadID);
            return $result;
        }

        public function valutaThread(int $userID, int $valore, int $threadID): bool
        {
            $user = FUser::load($userID);
            $valutazione = FValutazione::loadValutazioneThread($threadID);
            $valutazione->valuta($user, $valore);
            $result = FValutazione::update($valutazione);
            return $result;
        }

        public function creaMessaggio(string $testo, string $data, int $userID): bool
        {
            $user = FUser::load($userID);
            $id = null;
            $messaggio = new EMessaggio($id, $testo, $data, $user);
            $result = FMessaggio::store($messaggio);
            return $result;
        }

    }