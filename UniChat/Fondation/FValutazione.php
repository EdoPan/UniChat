<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

/**
 * Classe Foundation di Valutazione.
 */
class FValutazione
{

    /**
     * Istanza della classe FValutazione, si utilizza per il singleton.
     * @var null
     */
    private static $instance = null;

    /**
     * Costruttore di default.
     */
    private function __construct() {}

    /**
     * Restituisce l'istanza di FValutazione. Se già esistente restituisce quella esistente, altrimenti la crea.
     * @return FValutazione
     */
    public static function getInstance(): FValutazione
    {
        if(self::$instance == null){
            $classe = __CLASS__;
            self::$instance = new $classe;
        }
        return self::$instance;
    }

    /**
     * Restituisce un oggetto EValutazione, memorizzato nel database, dato l'identificativo. Per ottenere un oggetto
     * EValutazione è necessario anche recuperare gli oggetti EUser, ovvero gli utenti, che hanno espresso il proprio
     * giudizio. Se tali oggetti non fossero recuperabili o vi fossero problemi di varia natura allora viene restituito
     * null.
     * @param int $valutazioneID
     * @return EValutazione|null
     */
    public function load(int $valutazioneID): ?EValutazione
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();
            /*
             * Recupero del totale della valutazione.
             */
            $query1 = ("SELECT totale FROM valutazioni WHERE valutazioneID = :valutazioneID");
            $stmt = $pdo->prepare($query1);
            $stmt->execute(array(
                ':valutazioneID' => $valutazioneID
            ));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $totale = (int)$rows[0]["totale"];

            /*
             * Recupero utenti che hanno espresso una valutazione positiva.
             */
            $query2 = ("SELECT userID FROM valutazioni, votipositivi WHERE valutazioni.valutazioneID = votipositivi.valutazioneID AND valutazioni.valutazioneID = :valutazioneID");
            $stmt = $pdo->prepare($query2);
            $stmt->execute(array(
                ":valutazioneID" => $valutazioneID
            ));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $utentiPositivi = array();
            foreach ($rows as $row) {
                $userID = (int)$row["userID"];
                $fUser = FUser::getInstance();
                $user = $fUser->load($userID);
                if (isset($user)) {
                    $utentiPositivi[] = $user;
                } else {
                    return null;
                }
            }

            /*
             * Recupero utenti che hanno espresso una valutazione negativa.
             */
            $query3 = ("SELECT userID FROM valutazioni, votinegativi WHERE valutazioni.valutazioneID = votinegativi.valutazioneID AND valutazioni.valutazioneID = :valutazioneID");
            $stmt = $pdo->prepare($query3);
            $stmt->execute(array(
                ":valutazioneID" => $valutazioneID
            ));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $utentiNegativi = array();
            foreach ($rows as $row) {
                $userID = (int)$row["userID"];
                $fUser = FUser::getInstance();
                $user = $fUser->load($userID);
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

    /**
     * Permette di recuperare la valutazione associata ad un thread a partire dall'identificativo di quest'ultimo.
     * Se l'operazione va a buon fine viene restituito un oggetto EValutazione, null altrimenti.
     * @param int $threadID
     * @return EValutazione|null
     */
    public function loadValutazioneThread(int $threadID): ?EValutazione
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();
            $sql = ("SELECT valutazioneID FROM valutazioni, threads WHERE valutazioneThreadID = valutazioneID AND threadID = :threadID");
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':threadID' => $threadID
            ));

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $record = $rows[0];
            $valutazioneID = (int)$record["valutazioneID"];
            $valutazione = $this->load($valutazioneID);
            return $valutazione;
        } catch (PDOException $e) {
            return null;
        }
    }


    /**
     * Aggiorna nella base dati il valore della valutazione e si occupa di aggiornare anche la tabella contenente gli
     * utenti votanti.
     * Se l'operazioen va a buon fine viene restituito true, false altrimenti.
     * @param EValutazione $valutazione
     * @return bool
     */
    public function update(EValutazione $valutazione): bool
    {
        $valutazioneID = $valutazione->getId();
        $totale = $valutazione->getTotale();
        $utentiPositivi = $valutazione->getUtentiPositivi();
        $utentiNegativi = $valutazione->getUtentiNegativi();

        try {
            /*
             * L'aggiornamento della base dati comporta l'esecuzione di una serie di operazioni in successione, solo se
             * tutte vanno a buon fine allora la base dati viene modificata, in caso contrario viene ripristinata.
             * Tali operazioni sono:
             * - se è stato espresso un giudizio positivo, memorizzazione nella tabella contenente i voti positivi
             * - se è stato espresso un giudizio negativo, memorizzazione nella tabella contenente i voti negativo
             * - aggiornamento del totale della valutazione.
             */
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

    /**
     * Permette di memorizzare nella base dati un oggetto EValutazione.
     * Se l'operazione va a buon fine allora viene restituito true, false altrimenti.
     * @param PDO $pdo
     * @param EValutazione $valutazione
     * @return int
     */
    public function store(PDO $pdo, EValutazione $valutazione): ?int
    {
        $totale = $valutazione->getTotale();

        try {
            $sql = ("INSERT INTO valutazioni(totale) VALUES (:totale)");
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':totale' => $totale
            ));
            $result = (int)$pdo->lastInsertId();
            return $result;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Permette di memorizzare nella base dati l'identificativo dell'utente che ha espresso un giudizio positivo per una
     * determinata valutazione.
     * Se l'operazione va a buon fine allora viene restituito true, false altrimenti.
     * @param PDO $pdo
     * @param int $valutazioneID
     * @param int $userID
     * @return bool
     */
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

    /**
     * Permette di memorizzare nella base dati l'identificativo dell'utente che ha espresso un giudizio negativo per una
     * determinata valutazione.
     * Se l'operazione va a buon fine allora viene restituito true, false altrimenti.
     * @param PDO $pdo
     * @param int $valutazioneID
     * @param $userID
     * @return bool
     */
    private function storeVotiNegativi(PDO $pdo, int $valutazioneID, $userID): bool
    {
        try {
            $sql = ("INSERT INTO votinegativi(valutazioneID, userID) VALUES (:valutazioneID, :userID)");
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

    /**
     * Permette di rimuovere una valutazione dalla base dati. Quando viene eliminata una valutazione allora vengono
     * rimossi anche tutti gli utenti legati a quella valutazione dall'elenco dei voti positivi e negativi.
     * Se l'operazione va buon fine viene restituito true, false altrimenti.
     * @param PDO $pdo
     * @param int $valutazioneID
     * @return bool
     */
    public function delete(PDO $pdo, int $valutazioneID): bool
    {
        try {
            $sql = ("DELETE FROM valutazioni WHERE valutazioneID = :valutazioneID");
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(array(
                ':valutazioneID' => $valutazioneID
            ));
            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Verifica che un utente abbia espresso un giudizio positivo, per una determinata valutazione, dato il suo
     * identificativo.
     * Se l'utente è presente nella tabella relativa ai voti positivi viene restituito true, false se non vi è presente
     * o se vi fossero degli errori.
     * @param PDO $pdo
     * @param int $valutazioneID
     * @param int $userID
     * @return bool
     */
    private function existsInVotiPositivi(PDO $pdo, int $valutazioneID, int $userID): bool
    {
        try {
            $sql = ("SELECT * FROM votipositivi WHERE userID = :userID AND valutazioneID = :valutazioneID");
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':userID' => $userID,
                ':valutazioneID' => $valutazioneID
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
     * Verifica che un utente abbia espresso un giudizio negativo, per una determinata valutazione, dato il suo
     * identificativo.
     * Se l'utente è presente nella tabella relativa ai voti negativi viene restituito true, false se non vi è presente
     * o se vi fossero degli errori.
     * @param PDO $pdo
     * @param int $valutazioneID
     * @param int $userID
     * @return bool
     */
    private function existsInVotiNegativi(PDO $pdo, int $valutazioneID, int $userID): bool
    {
        try {
            $sql = ("SELECT * FROM votinegativi WHERE userID = :userID AND valutazioneID = :valutazioneID");
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':userID' => $userID,
                ':valutazioneID' => $valutazioneID
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
}