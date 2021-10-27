<?php

declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";

/**
 * Classe Foundation di Messaggio.
 */
class FMessaggio
{

    /**
     * Istanza della classe FMessaggio, si utilizza per il singleton.
     * @var null|FMessaggio
     */
    private static $instance = null;

    /**
     * Costruttore di default
     */
    private function __construct()
    {
    }

    /**
     * Restituisce l'istanza di FMessaggio. Se già esistente restituisce quella esistente, altrimenti la crea.
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
     * Restituisce l'oggetto EMessaggio, memorizzato nel database, avente come id quello passato come parametro.
     * Per ottenere un oggetto EMessaggio è necessario recuperare prima l'autore (utilizzo il metodo load di FUser).
     * Nel caso non fosse possibile o vi fossero altri errori di varia natura allora viene restituito null.
     * @param int $messaggioID
     * @return EMessaggio|null
     */
    public function load(int $messaggioID): ?EMessaggio
    {
        try {
            $dbConnection=FConnection::getInstance();
            $pdo=$dbConnection->connect();

            $sql=("SELECT * FROM messaggi WHERE messID = :messaggioID");
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':messaggioID' => $messaggioID
            ));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                $record = $rows[0];
                $testo = $record["testo"];
                $data = $record["data"];

                /*
                 * Recupero dell'utente autore del messaggio.
                 */
                $autoreID = (int)$record["autoreMessID"];
                $fUser = FUser::getInstance();
                $autoreMessaggio = $fUser->load($autoreID);
                if (!isset($autoreMessaggio)) {
                    return null;
                }

                $messaggio = new EMessaggio($messaggioID, $testo, $data, $autoreMessaggio);
                return $messaggio;
            } else {
                return null;
            }
        }catch (PDOException $e) {
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
     * Scrittura in DB di un oggetto di tipo EMessaggio.
     * @param EMessaggio $messaggio
     * @return bool
     */
    public function store(EMessaggio $messaggio): bool
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
    public function delete(int $messID): bool
    {
        try {
            $dbConnection=FConnection::getInstance();
            $pdo=$dbConnection->connect();

            if ($this->exists($messID)) {
                /*
                 * Per evitare inconsistenza sui dati, causato dall'accesso concorrente, si procede ad eseguire
                 * l'operazione in mutua esclusione.
                 */
                $pdo->query("SET autocommit = 0");
                $pdo->query("LOCK TABLES messaggi WRITE");

                $sql = ("DELETE FROM messaggi WHERE messID = :messID");
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute(array(
                    ':messID' => $messID
                ));

                $pdo->query("COMMIT");
                $pdo->query("UNLOCK TABLES");

                return $result;
            } else {
                return false;
            }
        } catch(PDOException $e)  {
            return false;
        }
    }

    private function exists(int $messID): bool
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $sql = ("SELECT messID FROM messaggi WHERE messID = :messID");
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':messID' => $messID
            ));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Restituisce un array contenente un numero di messaggi pari a $numeroRighe a partire da un determinato messaggio ($rigaPartenza).
     * Se il recupero fallisce il metodo restituisce null.
     * @param int $rigaPartenza
     * @param int $numeroRighe
     * @return array|null
     */
    public function loadAll(int $rigaPartenza, int $numeroRighe): ?array
    {
        $messaggi = array();

        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $stmt = $pdo->query("SELECT messID FROM messaggi ORDER BY messID LIMIT " . $rigaPartenza . ", " . $numeroRighe);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                $messID = (int)$row['messID'];
                $messaggio = self::load($messID);
                if (isset($messaggio)) {
                    $messaggi[] = $messaggio;
                } else {
                    return null;
                }
            }
            return $messaggi;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Permette di ottenere l'elenco dei messaggi che sono stati pubblicati nelle ultime 24 ore.
     * In caso di errori viene restituito null.
     * @return array|null Elenco dei messaggi pubblicati nelle ultime 24 ore.
     */
    public function loadMessaggiUltime24ore(): ?array
    {
        $messaggi = array();

        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $stmt = $pdo->query("SELECT messID FROM messaggi WHERE data > DATE_SUB(CURDATE(), INTERVAL 1 DAY)");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                $messID = (int)$row['messID'];
                $messaggio = self::load($messID);
                if (isset($messaggio)) {
                    $messaggi[] = $messaggio;
                } else {
                    return null;
                }
            }
            return $messaggi;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Permette di ottenere l'elenco dei nuovi messaggi, ovvero quelli pubblicati successivamente al messaggio di cui
     * viene fornito l'identificativo.
     * In caso di errori viene restituito null.
     * @param int $ultimoMessaggioId Identificativo del messaggio da cui iniziare a recuperare i nuovi messaggi.
     * @return array|null Elenco dei messaggi pubblicati dopo un determinato messaggio.
     */
    public function loadNuoviMessaggi(int $ultimoMessaggioId): ?array
    {
        $messaggi = array();

        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $sql = ('SELECT messID FROM messaggi WHERE messID > :id ORDER BY messID DESC');
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ":id" => $ultimoMessaggioId
            ));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                $messID = (int)$row['messID'];
                $messaggio = self::load($messID);
                if (isset($messaggio)) {
                    $messaggi[] = $messaggio;
                } else {
                    return null;
                }
            }
            return $messaggi;
        } catch (PDOException $e) {
            return null;
        }
    }
}