<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class FThread
    {
        public static function getLastID(): int
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $stmt = $pdo->query("SELECT MAX(threadID) AS id FROM threads");
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return (int)$row[0]["id"];
        }

        public static function store(EThread $t): bool
        {
            $threadID = $t->getId();
            $titolo = $t->getTitolo();
            $testo = $t->getTesto();
            $data = $t->getData();
            $allegati = $t->getAllegati();
            $user = $t->getAutoreThread();
            $categoria = $t->getCategoriaThread();
            $tags = $t->getTags();
            $valutazione = $t->getValutazione();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $sql = ("INSERT INTO threads(threadID, titoloThread, testoThread, dataThread, autoreThreadID, catThreadID, valutazioneThreadID)
                    VALUES (:threadID, :titoloThread, :testoThread, :dataThread, :autoreThreadID, :catThreadID, :valutazioneThreadID)");
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(array(
                ':threadID' =>  $threadID,
                ':titoloThread' => $titolo,
                ':testoThread' => $testo,
                ':dataThread' => $data,
                ':autoreThreadID' => $user->getId(),
                ':catThreadID' => $categoria->getId(),
                ':valutazioneThreadID' => $valutazione->getId()
            ));
            foreach($allegati as $allegato){
                $allegatoID = self::getLastIDAllegato();
                self::storeAllegato($allegatoID, $allegato, $threadID);
            }
            foreach($tags as $tag){
                self::storeTagsToThread($tag->getId(), $threadID);
            }

            return $result;
        }

        private static function getLastIDAllegato(): int
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $stmt = $pdo->query("SELECT MAX(allegatoID) AS id FROM allegati");
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return (int)$row[0]["id"];
        }

        private static function storeAllegato(int $allegatoID, string $path, int $threadID): bool
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $sql = ("INSERT INTO alleagti(allegatoID, path, threadID)
                    VALUES (:allegatoID, :path, :threadID)");
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(array(
                ':allegatoID' => $allegatoID,
                ':path' => $path,
                ':threadID' =>  $threadID
            ));
            return $result;
        }

        private static function storeTagsToThread(int $tagID, int $threadID): bool
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $sql = ("INSERT INTO tagstothread(tagID, threadID)
                    VALUES (:tagID, :threadID)");
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(array(
                ':tagID' => $tagID,
                ':threadID' =>  $threadID
            ));
            return $result;
        }

        public static function delete(int $threadID): bool
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $sql = ("DELETE FROM threads WHERE threadID = " . $threadID);
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute();
            return $result;
        }

        public static function updateByCategoriaID($categotiaID): bool
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $sql = ("UPDATE threads SET catThreadID = 1 WHERE catThreadID = :categoriaID");
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(array(
                ':categoriaID' => $categotiaID
            ));
            return $result;
        }
    }