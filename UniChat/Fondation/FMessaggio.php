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

        /**
         * Recupero degli ultimi messaggi inseriti.
         * @param int $numero
         * @return array
         */


        public static function loadUltimiMessaggi(int $numero): ?Array

        {

            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $stmt=$pdo->query("SELECT * FROM messaggi ORDER BY messID DESC" );
            $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
            $messaggi=array();
            if (count($rows)!=0) {

                for ($i=0; $i< $numero; $i++) {
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

            else {
                return "Non ci sono messaggi inseriti.";
            }

        }

        /**
         * Recupero messaggi di un utente desiderato.
         * @param int $UserID
         * @return array|null
         */


        public static function loadMessaggiByUtente (int $UserID): ?Array

        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $stmt=$pdo->query("SELECT * FROM messaggi, users WHERE autoreMessID=userID AND userID=" . $UsersID );
            $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
            $messutente=array();
            if (count($rows)!=0) {
                foreach($rows as $messaggio ) {
                    $messID = (int)$messaggio["messID"];
                    $autore = $messaggio["autoreMessID"];
                    $testo = $messaggio["testo"];
                    $data = $messaggio["data"];
                    $mess = new EMessaggio($messID, $testo, $autore);
                    $messutente[]=$mess;
                }

                return $messutente;
            }

            else {
               return "L'utente selezionato non ha inserito messaggi.";
            }

        }

        /**
         * Scrittura in DB di un oggetto di tipo messaggio.
         * @param EMessaggio $messaggio
         * @return bool
         */

        public static function store(EMessaggio $messaggio): bool
        {
            $messaggioID = $messaggio->getId()
            $messaggioAutore = $messaggio->getAutoreMessaggio();
            $messaggioTesto = $messaggio->getTesto();
            $messaggioData = $messaggio->getData();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $sql = ("INSERT INTO messaggi(messaggioID, messaggioAutore, messaggioTesto, messaggioData)
                    VALUES (:messaggioID, :messaggioAutore, :messaggioTesto, :messaggioData)");
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(array(
                ':messaggioID' =>  $messaggioID,
                ':messaggioAutore' => $messaggioTesto,
                ':messaggioTesto' => $messaggioTesto,
                ':messaggioData' => $messaggioData
            ));
            return $result;
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