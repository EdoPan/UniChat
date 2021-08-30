<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";


/**
 * Classe Foundation FRisposta. Conosce la tabella "risposte".
 */

    class FRisposta
    {

        /**
         * Instanza della classe FRisposta, si utilizza per il singleton.
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
         * Restituisce l'instanza di FRisposta. Se già esistente restituisce quella esistente, altrimenti la crea.
         * @return FRisposta
         */
        public static function getInstance(): FRisposta
        {
            if(self::$instance == null){
                $classe =__CLASS__;
                self::$instance = new $classe;
            }
            return self::$instance;
        }

        /**
         * Restituisce l'oggeto ERisposta, memorizzato nel database, avente come id quello passato come paramentro.
         * @param int $rispostaID
         * @return Erisposta|null
         */
        public function load(int $rispostaID): ?ERisposta
        {
            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();

                $sql = ("SELECT * FROM risposte WHERE rispostaID = :rispostaID");
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':rispostaID' => $rispostaID
                ));
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($rows) == 1) {
                    $record = $rows[0];
                    $rispostaID = (int)$record["rispostaID"];


                    /*
                     * Recupero dell'utente autore della risposta.
                     */

                    $autoreRispID = $record["autoreRispID"];
                    $fUser = FUser::getInstance();
                    $autoreRisposta = $fUser->load($autoreRispID);
                    if (!isset($autoreRisposta)) {
                        return null;
                    }

                    $data = $record["data"];
                    $testo = $record["testo"];


                    $risposta = new ERisposta($rispostaID, $testo, $data, $autoreRisposta);

                    return $risposta;

                } else {
                    return null;
                }
            } catch (PDOException $e){
                return null;
            }
        }


        /**
         * Recupero delle risposte di un thread.
         * @param int $threadID ID del Thread di cui recuperare le risposte
         * @return array Array contenente le risposte del thread
         */


        public function loadRisposteThread(int $threadID): ?Array
        {
            try {
                $risposte = array();

                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();

                $sql = ('SELECT * FROM risposte WHERE threadRispID=' . $threadID);

                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':threadID' => $threadID
                ));
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    $rispostaID = $row["rispostaID"];
                    $data = $row["data"];
                    $testo = $row["testo"];

                    /*
                     * Recupero dell'utente autore della risposta.
                     */

                    $autoreRispID = $row["autoreRispID"];
                    $fUser = FUser::getInstance();
                    $autoreRisposta = $fUser->load($autoreRispID);
                    if (!isset($autoreRisposta)) {
                        return null;
                    }


                    $risposta = array(
                        "id" => $rispostaID,
                        "data" => $data,
                        "testo" => $testo,
                        "autore" => $autoreRisposta,
                    );
                    $risposte[] = $risposta;
                }
                return $risposte;

            } catch (PDOException $e) {
                return null;
            }
        }


        /**
         * Recupero di tutte le risposte di un utente.
         * @param int $userID ID dell' utente di cui recuperare le risposte.
         * @return array Array contenente le risposte dell'utente.
         */


        public function loadRisposteByUtente(int $userID): ?Array
        {
            try {
                $risposte=array();

                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();

                $sql=('SELECT * FROM risposte WHERE autoreRispID='.$userID);

                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':userID' => $userID
                ));
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    $rispostaID = $row["rispostaID"];
                    $data = $row["data"];
                    $testo = $row["testo"];

                    /*
                     * Recupero dell'utente autore della risposta.
                     */

                    $fUser = FUser::getInstance();
                    $autoreRisposta = $fUser->load($userID);
                    if (!isset($autoreRisposta)) {
                        return null;
                    }


                    $risposta = array(
                        "id" => $rispostaID,
                        "data" => $data,
                        "testo" => $testo,
                        "autore" => $autoreRisposta,
                    );
                    $risposte[] = $risposta;
                }
                return $risposte;

            } catch (PDOException $e) {
                return null;
            }

        }

        /**
         * Permette di modificare l'autore delle risposte scritte da un utente, la modifica comporta che il nuovo autore
         * risulta essere l'utente di default. Il metodo viene richiamato nel momento in cui l'utente che aveva scritto
         * le risposte viene eliminato dalla base dati.
         * Se l'operazione va a buon fine allora viene restituito true, false altrimenti.
         * @param PDO $pdo
         * @param int $userID
         * @return bool
         */
        public function updateUserID(PDO $pdo, int $userID): bool
        {
            try {
                $sql = ("UPDATE risposte SET autoreRispID = 1 WHERE autoreRispID = :userID");
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
         * Conta numero delle risposte di un thread.
         * @param int $threadID
         * @return int|null
         */

        public function countRisposteThread(int $threadID): ?int
        {

            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();

                $sql = ("SELECT COUNT (*) FROM risposte WHERE threadRispID=".$threadID);

                $stmt = $pdo->prepare($sql);

                $stmt->execute(array(
                    ':threadID' => $threadID
                ));
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $numeroRisposte = $rows[0]["threadID"];

                return $numeroRisposte;

            } catch (PDOException $e) {
                return null;
            }

        }

        /**
         * Scrittura in Db di un oggetto di tipo ERisposta.
         * @param ERisposta $risposta
         * @param int $threadID
         * @return bool
         */

        public static function store(ERisposta $risposta, int $threadID): bool
        {
            $rispostaID = $risposta->getId();
            $user = $risposta->getAutoreRisposta();
            $testo = $risposta->getTesto();
            $dataRisposta = $risposta->getData();

            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();

                $sql = ("INSERT INTO risposte(rispostaID, testoRisposta, dataRisposta, autoreRispID, threadRispID)
                        VALUES (:rispostaID, :testoRisposta, :dataRisposta, :autoreRispID, :threadRispID)");
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute(array(
                    ':rispostaID' => $rispostaID,
                    ':testoRisposta' => $testo,
                    ':dataRisposta' => $dataRisposta,
                    ':autoreRispID' => $user->getId(),
                    ':threadRispID' => $threadID
                ));

                return $result;
            } catch (PDOException $e) {
                return false;
            }
        }

        /**
         * Permette di rimuovere una risposta dal db.
         * Se l'operazione va a buon fine viene restituito true, false altrimenti.
         * @param int $rispostaID
         * @return bool
         */

        public static function delete(int $rispostaID): bool
        {
            try {
                $dbConnection=FConnection::getInstance();
                $pdo=$dbConnection->connect();


                $sql = ("DELETE FROM risposte WHERE rispostaID = " . $rispostaID);
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute();

                return $result;

            } catch (PDOException $e) {
                return false;
            }

        }
    }