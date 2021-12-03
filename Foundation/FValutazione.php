<?php
declare(strict_types = 1);
require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "utility.php";

/**
 * Classe Foundation di Valutazione.
 */
class FValutazione
{

    /**
     * Istanza della classe FValutazione, si utilizza per il singleton.
     * @var null|FValutazione
     */
    private static $instance = null;

    /**
     * Costruttore di default.
     */
    private function __construct() {}

    /**
     * Restituisce l'istanza di FValutazione. Se già esistente restituisce quella esistente, altrimenti la crea.
     * @return FValutazione Istanza di FValutazione.
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
     * @param int $valutazioneID Identificativo della valutazione da recuperare dalla base dati.
     * @return EValutazione|null Valutazione recuperata dalla base dati.
     * @throws ValidationException Eccezione lanciata nel caso ci fossero problemi di validazione dei dati.
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
                if ($fUser->isModeratore($userID)) {
                    $user = $fUser->loadModeratore($userID);
                } else if ($fUser->isAdmin($userID)) {
                    $user = $fUser->loadAdmin($userID);
                } else {
                    $user = $fUser->load($userID);
                }
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
                if ($fUser->isModeratore($userID)) {
                    $user = $fUser->loadModeratore($userID);
                } else if ($fUser->isAdmin($userID)) {
                    $user = $fUser->loadAdmin($userID);
                } else {
                    $user = $fUser->load($userID);
                }
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
     * @param int $threadID Identificativo del thread a cui la valutazione da reucperare dalla base dati è associata.
     * @return EValutazione|null Valutazione recuperata dalla base dati.
     * @throws ValidationException Eccezione lanciata nel caso ci fossero problemi con la validazione dei dati.
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
            if (count($rows) == 1) {
                $record = $rows[0];
                $valutazioneID = (int)$record["valutazioneID"];
                $valutazione = $this->load($valutazioneID);
                return $valutazione;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return null;
        }
    }


    /**
     * Aggiorna nella base dati il valore della valutazione e si occupa di aggiornare anche la tabella contenente gli
     * utenti votanti.
     * Se l'operazione va a buon fine viene restituito true, false altrimenti.
     * @param EValutazione $valutazione Valutazione da aggiornare con il nuovo giudizio.
     * @param int $tipologiaVoto Tipo di giudizio espresso.
     * @param EUser $userVotante Utente che ha espresso il giudizio.
     * @return bool Esito dell'operazione.
     */
    public function update(EValutazione $valutazione, int $tipologiaVoto, EUser $userVotante): bool
    {
        $valutazioneID = $valutazione->getId();
        $totale = $valutazione->getTotale();

        $userEmail = $userVotante->getEmail();
        $fUser = FUser::getInstance();
        if (!$fUser->existsByEmail($userEmail)) {
            return false;
        }


        try {
            /*
             * L'aggiornamento della base dati comporta l'esecuzione di una serie di operazioni in successione, solo se
             * tutte vanno a buon fine allora la base dati viene modificata, in caso contrario viene ripristinata.
             * Tali operazioni sono:
             * - se la tipologia di voto è VOTO_POSITIVO, memorizzazione dell'identificativo dell'utente nella tabella
             * contenente i voti positivi
             * - se la tipologia di voto è VOTO_POSITIVO_ANNULLATO, rimozione dell'identificativo dell'utente nella
             * tabella contenente i voti positivi
             * - se la tipologia di voto è VOTO_POSITIVO_CAMBIATO, rimozione dell'identificativo dell'utente nella
             * tabella contenente i voti negativi e memorizzazione in quella contenente i voti positivi
             * - se la tipologia di voto è VOTO_NEGATIVO, memorizzazione dell'identificativo dell'utente nella tabella
             * contenente i voti negativi
             * - se la tipologia di voto è VOTO_NEGATIVO_ANNULLATO, rimozione dell'identificativo dell'utente nella
             * tabella contenente i voti negativi
             * - se la tipologia di voto è VOTO_NEGATIVO_CAMBIATO, rimozione dell'identificativo dell'utente nella
             * tabella contenente i voti positivi e memorizzazione in quella contenente i voti negativi
             * - aggiornamento del totale della valutazione.
             * Inoltre, per evitare inconsistenza sui dati causata dall'accesso concorrente alle stesse risorse, le
             * operazioni sopra descritte vengono eseguite in mutua esclusione.
             */
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $pdo->query("SET autocommit = 0");
            $pdo->query("LOCK TABLES valutazioni WRITE, votipositivi WRITE, votinegativi WRITE");

            $resultStoreVotiPositivi = true;
            $resultDeleteVotiPositivi = true;
            $resultStoreVotiNegativi = true;
            $resultDeleteVotiNegativi = true;

            if ($tipologiaVoto == EValutazione::VOTO_POSITIVO) {
                $resultStoreVotiPositivi = $this->storeVotiPositivi($pdo, $valutazioneID, $userVotante->getId());
            } else if ($tipologiaVoto == EValutazione::VOTO_POSITIVO_ANNULlATO) {
                $resultDeleteVotiPositivi = $this->deleteVotiPositivi($pdo, $valutazioneID, $userVotante->getId());
            } else if ($tipologiaVoto == EValutazione::VOTO_POSITIVO_CAMBIATO) {
                $resultDeleteVotiNegativi = $this->deleteVotiNegativi($pdo, $valutazioneID, $userVotante->getId());
                $resultStoreVotiPositivi = $this->storeVotiPositivi($pdo, $valutazioneID, $userVotante->getId());
            } else if ($tipologiaVoto == EValutazione::VOTO_NEGATIVO) {
                $resultStoreVotiNegativi == $this->storeVotiNegativi($pdo, $valutazioneID, $userVotante->getId());
            } else if ($tipologiaVoto == EValutazione::VOTO_NEGATIVO_ANNULLATO) {
                $resultDeleteVotiNegativi = $this->deleteVotiNegativi($pdo, $valutazioneID, $userVotante->getId());
            } else if ($tipologiaVoto == EValutazione::VOTO_NEGATIVO_CAMBIATO) {
                $resultDeleteVotiPositivi = $this->deleteVotiPositivi($pdo, $valutazioneID, $userVotante->getId());
                $resultStoreVotiNegativi == $this->storeVotiNegativi($pdo, $valutazioneID, $userVotante->getId());
            }

            $sql = ("UPDATE valutazioni SET totale = :totale WHERE valutazioneID = :valutazioneID");
            $stmt = $pdo->prepare($sql);
            $resultUpdateValutazioni = $stmt->execute(array(
                ':totale' => $totale,
                ':valutazioneID' => $valutazioneID
            ));

            if ($resultStoreVotiPositivi == true && $resultDeleteVotiPositivi == true && $resultStoreVotiNegativi == true && $resultDeleteVotiNegativi == true && $resultUpdateValutazioni == true) {
                $pdo->query("COMMIT");
                $pdo->query("UNLOCK TABLES");
                return true;
            } else {
                $pdo->query("ROLLBACK");
                $pdo->query("UNLOCK TABLES");
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Permette di memorizzare nella base dati un oggetto EValutazione.
     * Se l'operazione va a buon fine allora viene restituito true, false altrimenti.
     * @param PDO $pdo Connessione al DBMS e alla base dati.
     * @param EValutazione $valutazione Valutazione da memorizzare nella base dati.
     * @return int Identificativo della valutazione assegnato dalla base dati.
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
     * @param PDO $pdo Connessione al DBMS e alla base dati.
     * @param int $valutazioneID Identificativo della valutazione a cui è stato espresso il giudizio.
     * @param int $userID Identificativo dell'utente che ha espresso il giudizio.
     * @return bool Esito dell'operazione.
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
     * @param PDO $pdo Connessione al DBMS e alla base dati.
     * @param int $valutazioneID Identificativo della valutazione a cui è stato espresso il giudizio.
     * @param int $userID Identificativo dell'utente che ha espresso il giudizio.
     * @return bool Esito dell'operazione.
     */
    private function storeVotiNegativi(PDO $pdo, int $valutazioneID, int $userID): bool
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
     * @param PDO $pdo Connessione al DBMS e alla base dati.
     * @param int $valutazioneID Identificativo della valutazione da rimuovere dalla base dati.
     * @return bool Esito dell'operazione.
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
     * Permette di rimuovere dalla base dati il voto positivo associato ad un utente che ha cambiato la propria valutazione.
     * @param PDO $pdo Connessione al DBMS e alla base dati.
     * @param int $valutazioneID Identificativo della valutazione a cui l'utente ha espresso il proprio giudizio.
     * @param int $userID Identificativo dell'utente che ha espresso giudizio.
     * @return bool Esito dell'operazione.
     */
    private function deleteVotiPositivi(PDO $pdo, int $valutazioneID, int $userID): bool
    {
        try {
            $sql = ("DELETE FROM votipositivi WHERE valutazioneID = :valutazioneID AND userID = :userID");
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
     * Permette di rimuovere dalla base dati il voto negativo associato ad un utente che ha cambiato la propria valutazione
     * @param PDO $pdo Connessione al DBMS e alla base dati.
     * @param int $valutazioneID Identificativo della valutazione a cui l'utente ha espresso il proprio giudizio.
     * @param int $userID Identificativo dell'utente che ha espresso giudizio.
     * @return bool Esito dell'operazione.
     */
    private function deleteVotiNegativi(PDO $pdo, int $valutazioneID, int $userID): bool
    {
        try {
            $sql = ("DELETE FROM votinegativi WHERE valutazioneID = :valutazioneID AND userID = :userID");
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
}