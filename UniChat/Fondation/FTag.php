<?php
    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class FTag
    {
        public static function load(int $tagID): ?ETag
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $stmt = $pdo->query("SELECT * FROM tag WHERE tagID = " . $tagID);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                $record = $rows[0];
                $tID = (int)$record["tagID"];
                $denominazione = $record["denominazione"];
                $tag = new ETag($tID, $denominazione);
                return $tag;
            } else {
                return null;
            }
        }

        public static function getLastID(): int
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $stmt = $pdo->query("SELECT MAX(tagID) AS id FROM tag");
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return (int)$row[0]["id"];
        }

        public static function store(Etag $tag): bool
        {
            $tagID = $tag->getId();
            $denominazione = $tag->getDenominazione();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $sql = ("INSERT INTO tag(tagID, denominazione) VALUES (:tagID, :denominazione)");
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(array(
                ':tagID' =>  $tagID,
                ':denominazione' => $denominazione
            ));
            return $result;
        }

        public static function loadAll(): array
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $stmt = $pdo->query("SELECT * FROM tag");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $tags = array();
            foreach ($rows as $record) {
                $tID = (int)$record["tagID"];
                $denominazione = $record["denominazione"];
                $tag = new ETag($tID, $denominazione);
                $tags[] = $tag;
            }
            return $tags;
        }
    }