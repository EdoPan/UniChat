<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class CInterazioneThread
    {
        public function __construct()
        {
        }

        public function creaThread(int $userID, string $titolo, int $categoriaID, array $tagIDs, string $testo, ?array $allegati): bool
        {
            $user = FUser::load($userID);
            $cat = FCategoria::load($categoriaID);
            $tags = array();
            foreach ($tagIDs as $id){
                $tag = FTag::load($id);
                $tags[] = $tag;
            }
            $valutazioneID = FValutazione::getLastID() + 1;
            $valutazione = new EValutazione($valutazioneID, null, null, null);
            FValutazione::store($valutazione);
            $threadID = FThread::getLastID() + 1;
            $thread = new EThread($threadID, $titolo, $testo, $allegati, $user, $cat, $tags, $valutazione, null);
            $result = FThread::store($thread);
            return $result;
        }

        public function rispondi(int $userID, string $testo, int $threadID): bool
        {
            $user = FUser::load($userID);
            $rispID = FRisposta::getLastID() + 1;
            $risposta = new ERisposta($rispID, $testo, $user);
            $result = FRisposta::store($risposta, $threadID);
            return $result;
        }

        public function valuta(int $userID, int $valore, int $threadID): bool
        {
            $user = FUser::load($userID);
            $valutazione = FValutazione::loadValutazioneThread($threadID);
            $valutazione->valuta($user, $valore);
            $result = FValutazione::update($valutazione);
            return $result;
        }
    }