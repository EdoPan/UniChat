<?php

declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";

/**
 * Classe Foundation di Messaggio. Conosce la tabella messaggi.
 */
    class FMessaggio
    {

        /**
         * Instanza della classe FMessaggio, si utilizza per il singleton.
         * @var null
         */
        private static $instance = null;

        /**
         * Construttore di default
         */
        private function __construct()
        {
        }

        /**
         * Restituisce l'instanza di FMessaggio. Se già esistente restituisce quella esistente, altrimenti la crea.
         * @return FMessaggio
         */
        public static function getInstance(): FMessaggio
        {
            if(self::$instance == null){
                $classe =__CLASS__;
                self::$instance = new $classe;
            }
            return self::$instance;
        }


        /**
         * Recupero di un Messaggio da DB.
         * @param int $messaggioID
         * @return EMessaggio|null
         */
        public function load(int $messaggioID): ?EMessaggio
        {
            try {
                $dbConnection=FConnection::getInstance();
                $pdo=$dbConnection->connect();

                $sql=("SELECT * FROM messaggi WHERE messID = " . $messaggioID);
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':messaggioID' => $messaggioID
                ));
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($rows) == 1) {
                    $record = $rows[0];
                    $messID = (int)$record["messID"];


                    /*
                    * Recupero dell'utente autore del messaggio.
                    */
                    $autore = $record["autoreMessID"];
                    $fUser = FUser::getInstance();
                    $autoreMessaggio = $fUser->load($autore);
                    if (!isset($autoreMessaggio)) {
                        return null;
                    }


                    $testo = $record["testo"];
                    $data = $record["data"];


                    $messaggio = new EMessaggio($messID, $testo, $data, $autore);

                    return $messaggio;
                } else {
                    return null;
                }

            }catch (PDOException $e) {
                return false;
            }
        }

        /**
         * Recupero degli ultimi messaggi inseriti.
         * @param int $numero
         * @return array
         */


        public function loadUltimiMessaggi(int $numero): ?Array

        {

            try {
                $dbConnection=FConnection::getInstance();
                $pdo=$dbConnection->connect();
                $stmt=$pdo->query("SELECT * FROM messaggi ORDER BY messID DESC LIMIT ($numero)" );

                $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
                $messaggi=array();

                foreach ($rows as $row) {
                        $messID = (int)$row["messID"];

                        /*
                        * Recupero dell'utente autore del messaggio.
                        */
                        $autore = $row["autoreMessID"];
                        $fUser = FUser::getInstance();
                        $autoreMessaggio = $fUser->load($autore);
                        if (!isset($autoreMessaggio)) {
                            return null;
                        }

                        $testo = $row["testo"];
                        $data = $row["data"];
                        $messaggio = new EMessaggio($messID, $testo, $data, $autoreMessaggio);
                        $messaggi[]=$messaggio;
                }

                return $messaggi;

                }catch(PDOException $e) {
                    return false;
                }
        }

        /**
         * Recupero messaggi di un utente desiderato.
         * @param int $UserID
         * @return array|null
         */


        public static function loadMessaggiByUtente (int $userID): ?Array

        {
            try {
                $dbConnection=FConnection::getInstance();
                $pdo=$dbConnection->connect();
                $stmt=$pdo->query("SELECT * FROM messaggi WHERE autoreMessID=" . $userID );

                $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
                $messutente=array();

                foreach ($rows as $row) {
                    $messID = (int)$row["messID"];

                    /*
                    * Recupero dell'utente autore del messaggio.
                    */
                    $fUser = FUser::getInstance();
                    $autoreMessaggio = $fUser->load($userID);
                    if (!isset($autoreMessaggio)) {
                        return null;
                    }

                    $testo = $row["testo"];
                    $data = $row["data"];
                    $messaggio = new EMessaggio($messID, $testo, $data, $autoreMessaggio);
                    $messutente[]=$messaggio;
                }

                return $messutente;

            }catch(PDOException $e) {
                return null;
            }

        }

        /**
         * Permette di modificare l'autore dei messaggi scritti da un utente, la modifica comporta che il nuovo autore
         * risulta essere l'utente di default. Il metodo viene richiamato nel momento in cui l'utente che aveva scritto
         * i messaggi viene eliminato dalla base dati.
         * Se l'operazione va a buon fine allora viene restituito true, false altrimenti.
         * @param PDO $pdo
         * @param int $userID
         * @return bool
         */
        public function updateUserID(PDO $pdo, int $userID): bool
        {
            try {
                $sql = ("UPDATE messaggi SET autoreMessID = 1 WHERE autoreMessID = :userID");
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute(array(
                    ':userID' => $userID
                ));
                return $result;
            } catch (PDOException $e) {
                return false;
            }
        }



        /**
         * Scrittura in DB di un oggetto di tipo messaggio.
         * @param EMessaggio $messaggio
         * @return bool
         */

        public static function store(EMessaggio $messaggio): bool
        {
            $autoreMessID = $messaggio->getAutoreMessaggio()->getId();
            $testo = $messaggio->getTesto();
            $data = $messaggio->getData();

            try {
                $dbConnection=FConnection::getInstance();
                $pdo=$dbConnection->connect();


                $sql = ("INSERT INTO messaggi(autoreMessID, testo, data)
                    VALUES (:autoreMessID, :testo, :data)");
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute(array(
                    ':autoreMessID' => $autoreMessID,
                    ':testo' => $testo,
                    ':data' => $data
                ));
                return $result;

            } catch (PDOException $e) {
                return false;
            }


        }

        /**
         * Rimozione messaggio da DB.
         * @param int $messID
         * @return bool
         */
        public static function delete(int $messID): bool
        {

            try {
                $dbConnection=FConnection::getInstance();
                $pdo=$dbConnection->connect();

                $sql = ("DELETE FROM messaggi WHERE messID = " . $messID);

                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute();
                return $result;

            } catch(PDOException $e)  {
                return false;
            }

        }
    }