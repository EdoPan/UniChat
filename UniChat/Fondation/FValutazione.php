<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class FValutazione
    {
        public static function getLastID(): int
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $stmt = $pdo->query("SELECT MAX(valutazioneID) AS id FROM valutazioni");
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return (int)$row[0]["id"];
        }

        public static function store(EValutazione $v): bool
        {
            $valutazioneID = $v->getId();
            $totale = $v->getTotale();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $sql = ("INSERT INTO valutazioni(valutazioneID, totale) VALUES (:valutazioneID, :totale)");
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(array(
                ':valutazioneID' =>  $valutazioneID,
                ':totale' => $totale
            ));
            return $result;
        }

        public static function loadValutazioneThread(int $threadID): EValutazione
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $stmt = $pdo->query("SELECT * FROM valutazioni, threads WHERE valutazioneThreadId = valutazioneID AND threadID = " . $threadID);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                $record = $rows[0];
                $valutazioneID = (int)$record["valutazioneID"];
                $totale = (int)$record["totale"];
            }
            $stmt = $pdo->query("SELECT * FROM valutazioni, votipositivi WHERE votipositivi.valutazioneID = valutazioni.valutazioneID AND valutazioneID = " . $valutazioneID);
            $utentiPositivi = array();
            if($stmt != false) {
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    $utentiPositivi[] = FUser::load((int)$row["userID"]);
                }
            }
            $stmt = $pdo->query("SELECT * FROM valutazioni, votinegativi WHERE votinegativi.valutazioneID = valutazioni.valutazioneID AND valutazioneID = " . $valutazioneID);
            $utentiNegativi = array();
            if(($stmt) != false) {
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    $utentiNegativi[] = FUser::load((int)$row["userID"]);
                }
            }
            $valutazione = new EValutazione($valutazioneID, $totale, $utentiPositivi, $utentiNegativi);
            return $valutazione;
        }

        public static function update(EValutazione $valutazione): bool
        {
            $valutazioneID = $valutazione->getId();
            $totale = $valutazione->getTotale();
            $utentiPositivi = $valutazione->getUtentiPositivi();
            $utentiNegativi = $valutazione->getUtentiNegativi();
            if(count($utentiPositivi) > 0){
                $userPositivoID = $utentiPositivi[count($utentiPositivi) - 1]->getId();
                if(self::existsInVotiPositivi($valutazioneID, $userPositivoID) == false){
                    self::storeVotiPositivi($valutazioneID, $userPositivoID);
                }

            }
            if(count($utentiNegativi) > 0){
                $userNegativoID = $utentiNegativi[count($utentiNegativi) - 1]->getId();
                if (self::existsInVotiNegativi($valutazioneID, $userNegativoID) == false){
                    self::storeVotiNegativi($valutazioneID, $userNegativoID);
                }
            }
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $sql = ("UPDATE valutazioni SET totale = :totale WHERE valutazioneID = :valutazioneID");
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(array(
                ':totale' =>  $totale,
                ':valutazioneID' => $valutazioneID
            ));
            return $result;
        }

        private static function existsInVotiPositivi(int $valutazioneID, int $userID): bool
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $stmt = $pdo->query("SELECT * FROM votipositivi WHERE userID=" . $userID . " AND valutazioneID=" . $valutazioneID);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                return true;
            } else {
                return false;
            }
        }

        private static function existsInVotiNegativi(int $valutazioneID, int $userID): bool
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $stmt = $pdo->query("SELECT * FROM votinegativi WHERE userID=" . $userID . " AND valutazioneID=" . $valutazioneID);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                return true;
            } else {
                return false;
            }
        }

        private static function storeVotiPositivi(int $valutazioneID, int $userID): bool
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $sql = ("INSERT INTO votipositivi(valutazioneID, userID) VALUES (:valutazioneID, :userID)");
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(array(
                ':valutazioneID' =>  $valutazioneID,
                ':userID' => $userID
            ));
            return $result;
        }

        private static function storeVotiNegativi(int $valutazioneID, $userID)
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $sql = ("INSERT INTO votinegativi(valutazioneID, userID) VALUES (:valutazioneID, :userID)");
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(array(
                ':valutazioneID' =>  $valutazioneID,
                ':userID' => $userID
            ));
            return $result;
        }

        public static function deleteByThread(int $threadID): bool
        {
            $valutazioneID = self::getValutazioneIDByThread($threadID);
            if(isset($valutazioneID)){
                $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
                $sql = ("DELETE FROM valutazioni WHERE valutazioneID = " . $valutazioneID);
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute();
                return $result;
            } else {
                return false;
            }

        }

        private static function getValutazioneIDByThread(int $threadID): ?int
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $stmt = $pdo->query("SELECT valutazioneID AS id FROM valutazioni, threads WHERE valutazioneThreadID = valutazioneID AND threadID = " . $threadID);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(count($rows) == 1){
                return (int)$rows[0]["id"];
            } else {
                return null;
            }
        }
    }