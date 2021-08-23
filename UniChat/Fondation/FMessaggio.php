<?php

declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";

/**
 * Classe Foundation di Messaggio. Conosce la tabella messaggi.
 */
    class FMessaggio
    {
        /**
         * Recupero di un Messaggio da DB.
         * @param int $messaggioID
         * @return EMessaggio|null
         */
        public static function load(int $messaggioID): ?EMessaggio
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $stmt = $pdo->query("SELECT * FROM messaggi WHERE messID = " . $messaggioID);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                $record = $rows[0];
                $messID = (int)$record["messID"];
                $autore = $record["autoreMessID"];
                $testo = $record["testo"];
                $data = $record["data"];
                $messaggio = new EMessaggio($messID, $testo, $autore);

                return $messaggio;
            } else {
                return null;
            }
        }


        public static function loadUltimiMessaggi(int $numero): Array

        {

            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $stmt=$pdo->query("SELECT * FROM messaggi ORDER BY messID DESC" );
            $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
            $messaggi=array();
            for ($i=0; $i++; $i< $numero) {
                $record =$rows[$i];
                $messID = (int)$record["messID"];
                $autore = $record["autoreMessID"];
                $testo = $record["testo"];
                $data = $record["data"];
                $messaggio = new EMessaggio($messID, $testo, $autore);
                $messaggi[]=$messaggio;
            }

            return $messaggi;

        }








        /**
         * Rimozione messaggio da DB.
         * @param int $messID
         * @return bool
         */
        public static function delete(int $messID): bool
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $sql = ("DELETE FROM messaggi WHERE messID = " . $messID);
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute();
            return $result;
        }
    }