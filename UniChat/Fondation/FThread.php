<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

/**
 *
 */
class FThread
    {

    /**
     * Instanza della classe FThread, si utlizza per il singleton.
     * @var null
     */
    private static $instance = null;

    /**
     * Costruttire di default.
     */
    private function __construct()
        {
        }

    /**
     * Restituisce l'instanza di FThread. Se già esistente restituisce quella esistente, altrimenti la crea.
     * @return FThread
     */
    public static function getInstance(): FThread
        {
            if(self::$instance == null){
                $classe = __CLASS__;
                self::$instance = new $classe;
            }
            return self::$instance;
        }

    /**
     * Restituisce l'oggeto EThread, memorizzato nel database, avente come id quello passato come paramentro.
     * Qualora la foto profilo dell'utente non fosse presente, vi fossero problemi con la comunicazione con il database,
     * o vi fossero errori di varia natura allora viene restituito null.
     * @param int $threadID
     * @return EThread|null
     */
    public function load(int $threadID): ?EThread
        {
            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();
                $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

                $stmt = $pdo->query("SELECT * FROM threads WHERE threadID = " . $threadID);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($rows) == 1) {
                    $row = $rows[0];
                    $titolo = $row["titolo"];
                    $testo = $row["testo"];
                    $data = $row["data"];

                    /*
                     * Recupero dell'utente autore del thread.
                     */
                    $autoreThreadID = $row["autoreThreadID"];
                    $fUser = FUser::getInstance();
                    $autoreThread = $fUser->load($autoreThreadID);
                    if (!isset($autoreThread)) {
                        return null;
                    }
                    /*
                     * Recupero della categoria a cui appartiene il thread.
                     */
                    $catThreadID = $row["catThreadID"];
                    $fCategoria = FCategoria::getInstance();
                    $categoriaThread = $fCategoria->load($catThreadID);
                    if(!isset($categoriaThread))

                    /*
                     * Recupero della valutazione associata al thread.
                     */
                    $valutazioneThreadID = $row["valutazioneThreadID"];
                    $fValutazione = FValutazione::getInstance();
                    $valutazioneThread = $fValutazione->load($valutazioneThreadID);

                    /*
                     * recupero degli allegati associati al thread.
                     */
                    $allegatiThread = $this->loadAllegatiByThreadID($threadID);
                    if (!isset($allegatiThread)){
                        return null;
                    }

                    /*
                     * Recupero delle risposte associate al thread.
                     */
                    $fRisposte = FRisposta::getInstance();
                    $riposteThread = $fRisposte->loadRisposteThread($threadID);

                    $thread = new EThread($threadID, $titolo, $testo, $data, $allegatiThread, $autoreThread,
                        $categoriaThread, $valutazioneThread, $riposteThread);
                    return $thread;
                } else {
                    return null;
                }
            } catch (PDOException $e) {
                return null;
            }
        }

    /**
     * Permette di ottenere gli allegati del thread, se presenti, dato l'identificativo del thread a cui sono associati.
     * Gli allegati sono array associativi aventi i campi id, nome, dimensione, tipo e file, in quest'ultimo risiede il
     * file in formato stringa.
     * Viene restituito un array contenente gli array associativi degli allegati, in caso di errori viene restituito
     * null.
     * @param int $threadID
     * @return array|null
     */
    private function loadAllegatiByThreadID(int $threadID): ?array
        {
            try {
                $allegati = array();

                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();
                $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

                $stmt = $pdo->query("SELECT * FROM allegati WHERE threadID = " . $threadID);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    $allegatoID = $row["allegatoID"];
                    $nome = $row["nome"];
                    $dimensione = $row["dimensione"];
                    $tipo = $row["tipo"];
                    $file = $row["file"];
                    $fotoProfilo = array(
                        "id" => $allegatoID,
                        "nome" => $nome,
                        "dimensione" => $dimensione,
                        "tipo" => $tipo,
                        "file" => $file
                    );
                    $allegati[] = $fotoProfilo;
                }
                return $allegati;
            } catch (PDOException $e) {
                return null;
            }
        }

    /**
     * @param int $categoriaID
     * @return EThread|null
     */
    public function loadThreadPiuDiscussoPerCategoria(int $categoriaID): ?EThread
        {
            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();
                $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

                $stmt = $pdo->query("SELECT threadID, COUNT(*) AS numRisposte FROM threads, risposte 
                                    WHERE threadRispID = threadID AND catThreadID = " . $categoriaID .
                                    " GROUP BY threadID ORDER BY numRisposte DESC, data DESC LIMIT 1");
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $threadID = $rows[0]["threadID"];
                $thread = $this->load($threadID);
                return $thread;
            } catch (PDOException $e) {
                return null;
            }
        }

    /**
     * @param int $categoriaID
     * @return Ethread|null
     */
    public function loadThreadMaxValutazionePerCategoria(int $categoriaID): ?Ethread
        {
            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();
                $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

                $stmt = $pdo->query("SELECT threadID FROM threads, valutazioni WHERE valutazioneThreadID = valutazioneID 
                                     AND catThreadID = " . $categoriaID . " ORDER BY totale DESC, data DESC LIMIT 1");
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $threadID = $rows[0]["threadID"];
                $thread = $this->load($threadID);
                return $thread;
            } catch (PDOException $e) {
                return null;
            }
        }

    /**
     * @param PDO $pdo
     * @param int $userID
     * @return bool
     */
    public function updateUserID(PDO $pdo, int $userID): bool
        {
            try {
                $sql = ("UPDATE threads SET autoreThreadID = 1 WHERE autoreThreadID = :userID");
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
     * @param PDO $pdo
     * @param int $categoriaID
     * @return bool
     */
    public function updateCategoriaID(PDO $pdo, int $categoriaID): bool
        {
            try {
                $sql = ("UPDATE threads SET catThreadID = 1 WHERE catThreadID = :categoriaID");
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute(array(
                    ':categoriaID' => $categoriaID
                ));
                return $result;
            } catch (PDOException $e) {
                return false;
            }
        }

    /**
     * @param EThread $thread
     * @return bool
     */
    public function store(EThread $thread): bool
        {
            try {
                $titolo = $thread->getTitolo();
                $testo = $thread->getTesto();
                $data = $thread->getData();
                $userID = $thread->getAutoreThread()->getId();
                $categoriaID = $thread->getCategoriaThread()->getId();

                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();
                $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
                $pdo->beginTransaction();

                $valutazione = $thread->getValutazione();
                $fValutazione = FValutazione::getInstance();
                $valutazioneID = $fValutazione->store($pdo, $valutazione);
                if (!isset($valutazioneID)) {
                    $pdo->rollBack();
                    return false;
                }

                $sql = ("INSERT INTO threads(autoreThreadID, catThreadID, valutazioneThreadID, titolo, testo, data)
                    VALUES (:autoreThreadID, :catThreadID, :valutazioneThreadID, :titolo, :testo, :data)");
                $stmt = $pdo->prepare($sql);
                $resultStoreThread = $stmt->execute(array(
                    ':autoreThreadID' => $userID,
                    ':catThreadID' => $categoriaID,
                    ':valutazioneThreadID' => $valutazioneID,
                    ':titolo' => $titolo,
                    ':testo' => $testo,
                    ':data' => $data
                ));

                $threadID = (int)$pdo->lastInsertId(); //Verificare se restituisce il valore corretto

                $allegati = $thread->getAllegati();
                $i = 0;
                $controllo = true;
                while ($controllo and $i < count($allegati)) {
                    $allegato = $allegati[$i];
                    $controllo = $this->storeAllegato($pdo, $allegato, $threadID);
                    $i++;
                }

                if ($controllo == true && $resultStoreThread == true) {
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
     * @param PDO $pdo
     * @param array $allegato
     * @param int $threadID
     * @return bool
     */
    private function storeAllegato(PDO $pdo, array $allegato, int $threadID): bool
        {
            $nome = $allegato["nome"];
            $dimensione = $allegato["dimensione"];
            $tipo = $allegato["tipo"];
            $file = $allegato["file"];

            try {
                $sql = ("INSERT INTO allegati(threadID, nome, dimensione, tipo, file)
                    VALUES (:threadID, :nome, :dimensione, :tipo, :file)");
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute(array(
                    ':threadID' => $threadID,
                    ':nome' => $nome,
                    ':dimensione' => $dimensione,
                    ':tipo' => $tipo,
                    ':file' => $file
                ));
                return $result;
            } catch (PDOException $e) {
                return false;
            }
        }

    /**
     * @param int $threadID
     * @return bool
     */
    public function delete(int $threadID): bool
        {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

            $stmt = $pdo->query("SELECT valutazioneThreadID FROM threads WHERE threadID = " . $threadID);
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $valutazioneID = (int)$row[0]["valutazioneThreadID"];

            $pdo->beginTransaction();

            $fValutazione = FValutazione::getInstance();
            $resultDeleteValutazione = $fValutazione->delete($pdo, $valutazioneID);

            $sql = ("DELETE FROM threads WHERE threadID = " . $threadID);
            $stmt = $pdo->prepare($sql);
            $resultDeleteThread = $stmt->execute();

            if ($resultDeleteValutazione == true && $resultDeleteThread == true) {
                $pdo->commit();
                return true;
            } else {
                return false;
            }
        }

    /**
     * @param int $categoriaID
     * @param int $rigaPartenza
     * @param int $numeroRighe
     * @return array|null
     */
    public function loadThreadsCategoria(int $categoriaID, int $rigaPartenza, int $numeroRighe): ?array
        {
            $threads = array();

            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();
                $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

                $stmt = $pdo->query("SELECT threadID FROM threads WHERE catThreadID = " . $categoriaID . " ORDER BY data DESC LIMIT " . $rigaPartenza . ", " . $numeroRighe);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    $threadID = $row["threadID"];
                    $threads[] = $this->load($threadID);
                }
                return $threads;
            } catch (PDOException $e) {
                return null;
            }
        }

    /**
     * @param string $titolo
     * @param int $rigaPartenza
     * @param int $numeroRighe
     * @return array|null
     */
    public function ricercaPerTitolo(string $titolo, int $rigaPartenza, int $numeroRighe): ?array //ATTENZIONE SQLi
        {
            $threads = array();

            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();
                $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

                $stmt = $pdo->query("SELECT threadID FROM threads WHERE MATCH (titolo) AGAINST (" . $titolo . ") LIMIT " . $rigaPartenza . ", " . $numeroRighe);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    $threadID = $row["threadID"];
                    $threads[] = $this->load($threadID);
                }
                return $threads;
            } catch (PDOException $e) {
                return null;
            }
        }

    /**
     * @param string $titolo
     * @param array $categorieID
     * @param int $rigaPartenza
     * @param int $numeroRighe
     * @return array|null
     */
    public function ricercaPerTitoloECategorie(string $titolo, array $categorieID, int $rigaPartenza, int $numeroRighe): ?array //ATTENZIONE SQLi
        {
            $threads = array();

            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();
                $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

                $ids = join("','", $categorieID);
                $stmt = $pdo->query("SELECT threadID FROM threads WHERE MATCH (titolo) AGAINST (" . $titolo . ")
                                    AND catThreadID IN ('$ids') LIMIT " . $rigaPartenza . ", " . $numeroRighe);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    $threadID = $row["threadID"];
                    $threads[] = $this->load($threadID);
                }
                return $threads;
            } catch (PDOException $e) {
                return null;
            }
        }
    }