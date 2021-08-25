<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class FValutazione
    {
        private static $instance = null;

        private function __construct() {}

        public static function getInstance(): FValutazione
        {
            $classe = __CLASS__;
            if (self::$instance == null) {
                self::$instance == new $classe;
            }
            return self::$instance;
        }

        public function load(int $valutazioneID): ?EValutazione
        {
            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();

                $query1 = ("SELECT totale FROM valutazioni WHERE valutazioneID = :valutazioneID");
                $stmt = $pdo->prepare($query1);
                $stmt->execute(array(
                    ':valutazioneID' => $valutazioneID
                ));

                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $totale = $rows[0]["totale"];

                $query2 = ("SELECT * FROM valutazioni, votipositivi WHERE valutazioni.valutazioneID = votipositivi.valutazioneID AND valutazioni.valutazioneID = :valutazioneID");
                $stmt = $pdo->prepare($query2);
                $stmt->execute(array(
                    ":valutazioneID" => $valutazioneID
                ));
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $utentiPositivi = array();
                foreach ($rows as $row) {
                    $userID = $row["userID"];
                    $fThread = FThread::getInstance();
                    $user = $fThread->load($userID);
                    if (isset($user)) {
                        $utentiPositivi[] = $user;
                    } else {
                        return null;
                    }
                }

                $query3 = ("SELECT * FROM valutazioni, votinegativi WHERE valutazioni.valutazioneID = votinegativi.valutazioneID AND valutazioni.valutazioneID = :valutazioneID");
                $stmt = $pdo->prepare($query3);
                $stmt->execute(array(
                    ":valutazioneID" => $valutazioneID
                ));
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $utentiNegativi = array();
                foreach ($rows as $row) {
                    $userID = $row["userID"];
                    $fThread = FThread::getInstance();
                    $user = $fThread->load($userID);
                    if (isset($user)) {
                        $utentiNegativi[] = $user;
                    } else {
                        return null;
                    }
                }

                $valutazione = new EValutazione($valutazioneID, $totale, $utentiPositivi, $utentiNegativi);
                return $valutazione;
            } catch (PDOException $e) {
                return null;
            }
        }

        public function update(EValutazione $valutazione): bool
        {
            $valutazioneID = $valutazione->getId();
            $totale = $valutazione->getTotale();
            $utentiPositivi = $valutazione->getUtentiPositivi();
            $utentiNegativi = $valutazione->getUtentiNegativi();

            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();
                $pdo->beginTransaction();

                $resultStoreVotiPositivi = true;
                $resultStoreVotiNegativi = true;

                if (count($utentiPositivi) > 0) {
                    $userPositivoID = $utentiPositivi[count($utentiPositivi) - 1]->getId();
                    if ($this->existsInVotiPositivi($pdo, $valutazioneID, $userPositivoID) == false) {
                        $resultStoreVotiPositivi = $this->storeVotiPositivi($pdo, $valutazioneID, $userPositivoID);
                    }
                }

                if (count($utentiNegativi) > 0) {
                    $userNegativoID = $utentiNegativi[count($utentiNegativi) - 1]->getId();
                    if ($this->existsInVotiNegativi($pdo, $valutazioneID, $userNegativoID) == false) {
                        $resultStoreVotiNegativi = $this->storeVotiNegativi($pdo, $valutazioneID, $userNegativoID);
                    }
                }

                $sql = ("UPDATE valutazioni SET totale = :totale WHERE valutazioneID = :valutazioneID");
                $stmt = $pdo->prepare($sql);
                $resultUpdateValutazioni = $stmt->execute(array(
                    ':totale' => $totale,
                    ':valutazioneID' => $valutazioneID
                ));

                if ($resultStoreVotiPositivi == true && $resultStoreVotiNegativi == true && $resultUpdateValutazioni == true) {
                    $pdo->commit();
                    return true;
                } else {
                    $pdo->rollBack();
                    return false;
                }
            } catch (PDOException $e) {
                return false;
            }
        }

        public function store(EValutazione $valutazione): bool
        {
            $valutazioneID = $valutazione->getId();
            $totale = $valutazione->getTotale();

            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();

                $sql = ("INSERT INTO valutazioni(valutazioneID, totale) VALUES (:valutazioneID, :totale)");
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute(array(
                    ':valutazioneID' => $valutazioneID,
                    ':totale' => $totale
                ));
                return $result;
            } catch (PDOException $e) {
                return false;
            }
        }

        private function storeVotiPositivi(PDO $pdo, int $valutazioneID, int $userID): bool
        {
            try {
                $sql = ("INSERT INTO votipositivi(valutazioneID, userID) VALUES (:valutazioneID, :userID)");
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute(array(
                    ':valutazioneID' => $valutazioneID,
                    ':userID' => $userID
                ));
                return $result;
            } catch (PDOException $e) {
                return false;
            }
        }

        private function storeVotiNegativi(PDO $pdo, int $valutazioneID, $userID)
        {
            $sql = ("INSERT INTO votinegativi(valutazioneID, userID) VALUES (:valutazioneID, :userID)");
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(array(
                ':valutazioneID' =>  $valutazioneID,
                ':userID' => $userID
            ));
            return $result;
        }
/*
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
*/

        private function existsInVotiPositivi(PDO $pdo, int $valutazioneID, int $userID): bool
        {
            $stmt = $pdo->query("SELECT * FROM votipositivi WHERE userID=" . $userID . " AND valutazioneID=" . $valutazioneID);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                return true;
            } else {
                return false;
            }
        }

        private function existsInVotiNegativi(PDO $pdo, int $valutazioneID, int $userID): bool
        {
            $stmt = $pdo->query("SELECT * FROM votinegativi WHERE userID=" . $userID . " AND valutazioneID=" . $valutazioneID);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                return true;
            } else {
                return false;
            }
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