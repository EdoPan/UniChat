<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class FRisposta
    {
        public static function getLastID(): int
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $stmt = $pdo->query("SELECT MAX(rispostaID) AS id FROM risposte");
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return (int)$row[0]["id"];
        }

        public static function store(ERisposta $risposta, int $threadID): bool
        {
            $rispostaID = $risposta->getId();
            $user = $risposta->getAutoreRisposta();
            $testo = $risposta->getTesto();
            $dataRisposta = $risposta->getData();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $sql = ("INSERT INTO risposte(rispostaID, testoRisposta, dataRisposta, autoreRispID, threadRispID)
                    VALUES (:rispostaID, :testoRisposta, :dataRisposta, :autoreRispID, :threadRispID)");
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(array(
                ':rispostaID' =>  $rispostaID,
                ':testoRisposta' => $testo,
                ':dataRisposta' => $dataRisposta,
                ':autoreRispID' => $user->getId(),
                ':threadRispID' => $threadID
            ));
            return $result;
        }

        public static function delete(int $rispostaID): bool
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $sql = ("DELETE FROM risposte WHERE rispostaID = " . $rispostaID);
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute();
            return $result;
        }
    }