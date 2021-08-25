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

                $sql = ("SELECT * FROM threads WHERE threadID = :threadID");
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':threadID' => $threadID
                ));
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
                    if(!isset($categoriaThread)) {
                        return null;
                    }

                    /*
                     * Recupero della valutazione associata al thread.
                     */
                    $valutazioneThreadID = $row["valutazioneThreadID"];
                    $fValutazione = FValutazione::getInstance();
                    $valutazioneThread = $fValutazione->load($valutazioneThreadID);
                    if(!isset($valutazioneThread)) {
                        return null;
                    }

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
                    if(!isset($riposteThread)) {
                        return null;
                    }

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
     * Viene restituito un array, eventualmente vuoto, contenente gli array associativi degli allegati, in caso di
     * errori viene restituito null.
     * @param int $threadID
     * @return array|null
     */
    private function loadAllegatiByThreadID(int $threadID): ?array
        {
            try {
                $allegati = array();

                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();

                $sql = ("SELECT * FROM allegati WHERE threadID = :threadID");
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':threadID' => $threadID
                ));
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
     * Permette di restituire il thread con il maggior numero di risposte, appartenente ad una particolare categoria
     * fornita in ingresso.
     * Se l'operazione va a buon fine viene restituito un EThread, in caso contrario viene restituito null.
     * @param int $categoriaID
     * @return EThread|null
     */
    public function loadThreadPiuDiscussoPerCategoria(int $categoriaID): ?EThread
        {
            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();

                /** @noinspection SqlAggregates */
                $sql = ("SELECT threadID, COUNT(*) AS numRisposte FROM threads, risposte 
                            WHERE threadRispID = threadID AND catThreadID = :categoriaID 
                            GROUP BY threadID ORDER BY numRisposte DESC, data DESC LIMIT 1");
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':categoriaID' => $categoriaID
                ));
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $threadID = $rows[0]["threadID"];
                $thread = $this->load($threadID);
                return $thread;
            } catch (PDOException $e) {
                return null;
            }
        }

    /**
     * Permette di restituire il thread con la valutazione più alta, appartenente ad una particolare categoria
     * fornita in ingresso.
     * Se l'operazione va a buon fine viene restituito un EThread, in caso contrario viene restituito null.
     * @param int $categoriaID
     * @return Ethread|null
     */
    public function loadThreadMaxValutazionePerCategoria(int $categoriaID): ?Ethread
        {
            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();

                $sql = ("SELECT threadID FROM threads, valutazioni WHERE valutazioneThreadID = valutazioneID 
                         AND catThreadID = :categoriaID ORDER BY totale DESC, data DESC LIMIT 1");
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':categoriaID' => $categoriaID
                ));
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $threadID = $rows[0]["threadID"];
                $thread = $this->load($threadID);
                return $thread;
            } catch (PDOException $e) {
                return null;
            }
        }

    /**
     * Permette di modificare l'autore dei threads scritti da un utente, la modifica comporta che il nuovo autore
     * risulta essere l'utente di default. Il metodo viene richiamato nel momento in cui l'utente che aveva scritto i
     * threads viene eliminato dalla base dati.
     * Se l'operazione va a buon fine allora viene restituito true, false altrimenti.
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
     * Permette di modificare la categoria di appartenenza dei threads, la modifica comporta che la nuova categoria
     * risulta essere la categoria di default. Il metodo viene richiamato nel momento in cui la categoria a cui i
     * threads appartenevano, viene rimossa dalla base dati.
     * Se l'operazione va a buon fine allora viene restituito true, false altrimenti.
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

                $titolo = $thread->getTitolo();
                $testo = $thread->getTesto();
                $data = $thread->getData();
                $userID = $thread->getAutoreThread()->getId();
                $categoriaID = $thread->getCategoriaThread()->getId();

            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();

                /*
                 * La memorizzazione di un nuovo thread nella base dati richiede che vengano compiute una serie di
                 * operazioni da eseguire una di seguito all'altra, ma che devono apportare modifiche alla base dati
                 * solo se sono avvenute tutte con successo.
                 * Tali operazioni sono:
                 * - memorizzazione di una nuova valutazione associata al thread;
                 * - memorizzazione del nuovo thread;
                 * - memorizzazione, se presenti, degli allegati associati al thread.
                 */
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
     * Permette di memorizzare un allegato associato ad un thread.
     * Se l'operazione va a buon fine viene restituito true, false altrimenti.
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
                $query = $pdo->query("INSERT INTO allegati(threadID, nome, dimensione, tipo, file)
                                            VALUES ('$threadID', '$nome','$dimensione','$tipo','$file')");
                return true;
            } catch (PDOException $e) {
                return false;
            }
        }

    /**
     * Peremette di rimuovere un thread dalla base dati. Quando un thread viene eliminato allora viene rimossa anche la
     * valutazione, tutti gli allegati e tutte le risposte ad esso associati.
     * Se l'operazione va a buon fine viene restituito true, false altrimenti.
     * @param int $threadID
     * @return bool
     */
    public function delete(int $threadID): bool
        {
            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();

                /*
                 * Recupero identificativo della valutazione associata al thread.
                 */
                $sql = ("SELECT valutazioneThreadID FROM threads WHERE threadID = :threadID");
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':threadID' => $threadID
                ));
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $valutazioneID = (int)$row[0]["valutazioneThreadID"];

                /*
                 * L'eliminazione del thread dalla base dati comporta una serie di operazioni, queste vengono eseguite
                 * una di seguito all'altra e le modifiche sulla base dati devono essere effettuate solo se tutte vanno
                 * a buon fine.
                 * Tali operazioni sono:
                 * - eliminazione della valutazione associata al thread;
                 * - eliminazione del thread.
                 */
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
                    $pdo->rollBack();
                    return false;
                }
            } catch (PDOException $e) {
                return false;
            }
        }

    /**
     * Permette di ottenere un certo numero di EThread di una determinata categoria di appartenenza, specificando da
     * quale riga dell tabella della base dati partire (riga di partenza esclusa) e il numero di righe da visualizzare.
     * Viene restituito un array di threads, eventualmente vuoto, se l'operazione va a buon fine, null altrimenti.
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

                //$stmt = $pdo->query("SELECT threadID FROM threads WHERE catThreadID = " . $categoriaID . " ORDER BY data DESC LIMIT " . $rigaPartenza . ", " . $numeroRighe);
                $sql = ("SELECT threadID FROM threads WHERE catThreadID = :categoriaID ORDER BY data DESC LIMIT " . $rigaPartenza . ", " . $numeroRighe);
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':categoriaID' => $categoriaID
                ));
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    $threadID = $row["threadID"];
                    $thread = $this->load($threadID);
                    if (isset($thread)) {
                        $threads[] = $thread;
                    } else {
                        return null;
                    }
                }
                return $threads;
            } catch (PDOException $e) {
                return null;
            }
        }

    /**
     * Permette di restituire un certo numero di EThread, i quali presentano nel titolo alcune o tutte le parole che
     * vengono passate in ingresso. I thread vengono restituiti ordinati a partire da quello che ha il titolo con il
     * maggior numero di parole uguali e nello stesso ordine di quelle fornite in ingresso.
     * Viene restituito un array di threads, eventualmente vuoto, se l'operazione va a buon fine, null altrimenti.
     * @param string $titolo
     * @param int $rigaPartenza
     * @param int $numeroRighe
     * @return array|null
     */
    public function ricercaPerTitolo(string $titolo, int $rigaPartenza, int $numeroRighe): ?array
        {
            $threads = array();

            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();

                //$stmt = $pdo->query("SELECT threadID FROM threads WHERE MATCH (titolo) AGAINST (" . $titolo . ") LIMIT " . $rigaPartenza . ", " . $numeroRighe);
                $sql = ("SELECT threadID FROM threads WHERE MATCH (titolo) AGAINST (:titolo) LIMIT " . $rigaPartenza . ", " . $numeroRighe);
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':titolo' => $titolo
                ));
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    $threadID = $row["threadID"];
                    $thread = $this->load($threadID);
                    if (isset($thread)) {
                        $threads[] = $thread;
                    } else {
                        return null;
                    }
                }
                return $threads;
            } catch (PDOException $e) {
                return null;
            }
        }

    /**
     * Permette di restituire un certo numero di EThread che appartengono a determinate categorie e che presentano nel
     * titolo alcune o tutte le parole che vengono passate in ingresso. I thread vengono restituiti ordinati a partire
     * da quello che ha il titolo con il maggior numero di parole uguali e nello stesso ordine di quelle fornite in
     * ingresso.
     * Viene restituito un array di threads, eventualmente vuoto, se l'operazione va a buon fine, null altrimenti.
     * @param string $titolo
     * @param array $categorieID
     * @param int $rigaPartenza
     * @param int $numeroRighe
     * @return array|null
     */
    public function ricercaPerTitoloECategorie(string $titolo, array $categorieID, int $rigaPartenza, int $numeroRighe): ?array
        {
            $threads = array();

            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();

                $ids = join("','", $categorieID);
                //$stmt = $pdo->query("SELECT threadID FROM threads WHERE MATCH (titolo) AGAINST (" . $titolo . ")
                //                    AND catThreadID IN ('$ids') LIMIT " . $rigaPartenza . ", " . $numeroRighe);
                $sql = ("SELECT threadID FROM threads WHERE MATCH (titolo) AGAINST (:titolo) AND catThreadID IN ('$ids') LIMIT " . $rigaPartenza . ", " . $numeroRighe);
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':titolo' => $titolo
                ));
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    $threadID = $row["threadID"];
                    $thread = $this->load($threadID);
                    if (isset($thread)) {
                        $threads[] = $thread;
                    } else {
                        return null;
                    }
                }
                return $threads;
            } catch (PDOException $e) {
                return null;
            }
        }
    }