<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

/**
 * Classe Foundation di Thread.
 */
class FThread
    {

    /**
     * Istanza della classe FThread, si utlizza per il singleton.
     * @var null|FThread
     */
    private static $instance = null;

    /**
     * Costruttore di default.
     */
    private function __construct()
        {
        }

    /**
     * Restituisce l'istanza di FThread. Se già esistente restituisce quella esistente, altrimenti la crea.
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
     * Restituisce l'oggetto EThread, memorizzato nel database, avente come id quello passato come parametro.
     * Per ottenere un oggetto EThread è necessario recuperare diversi oggetti dalla base dati, nel caso non fosse
     * possibile o vi fossero altri errori di varia natura allora viene restituito null.
     * Gli oggetti da recuperare sono:
     * - EUser;
     * - ECategoria;
     * - EValutazione;
     * - ERisposta.
     * @param int $threadID Identificativo del thread da recuperare dalla base dati
     * @return EThread|null Thread richiesto
     * @throws ValidationException Eccezione lanciata nel caso vi fossero problemi con la validazione dei dati nel
     * della creazione dell'oggetto EThread recuperato dalla base dati
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
                    $autoreThreadID = (int)$row["autoreThreadID"];
                    $fUser = FUser::getInstance();
                    $autoreThread = $fUser->load($autoreThreadID);
                    if (!isset($autoreThread)) {
                        return null;
                    }

                    /*
                     * Recupero della categoria a cui appartiene il thread.
                     */
                    $catThreadID = (int)$row["catThreadID"];
                    $fCategoria = FCategoria::getInstance();
                    $categoriaThread = $fCategoria->load($catThreadID);
                    if(!isset($categoriaThread)) {
                        return null;
                    }

                    /*
                     * Recupero della valutazione associata al thread.
                     */
                    $valutazioneThreadID = (int)$row["valutazioneThreadID"];
                    $fValutazione = FValutazione::getInstance();
                    $valutazioneThread = $fValutazione->load($valutazioneThreadID);
                    if(!isset($valutazioneThread)) {
                        return null;
                    }

                    /*
                     * Recupero degli allegati associati al thread.
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
     * @param int $threadID Identificativo del thread di cui si devono recuperare gli allegati dalla base dati
     * @return array|null Elenco contenente gli allegati del thread (se presenti)
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
     * Permette di restituire l'elenco di threads con il maggior numero di risposte.
     * Il numero di threads viene stabilito dal parametro richiesto in ingresso.
     * Se l'operazione non va a buon fine allora viene restituito null.
     * @param int $numeroThreads Valore che indica il numero di threads da recuperare dalla base dati
     * @return array|null Elenco contenente i thread recuperati
     * @throws ValidationException Eccezione lanciata nel caso vi fossero problemi con la validazione dei dati nel
     * della creazione dell'oggetto EThread recuperato dalla base dati
     */
    public function loadThreadsPiuRisposte(int $numeroThreads): ?array
    {
        $threads = array();
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $stmt = $pdo->query("SELECT threadID, COUNT(*) AS numRisposte FROM threads, risposte 
                            WHERE threadRispID = threadID GROUP BY threadID ORDER BY numRisposte DESC, threadID DESC LIMIT " . $numeroThreads);

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $threadID = (int)$row["threadID"];
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
     * Permette di restituire l'elenco di threads con la valutazione più alta.
     * Il numero di threads viene stabilito dal parametro richiesto in ingresso.
     * Se l'operazione non va a buon fine allora viene restituito null.
     * @param int $numeroThreads Valore che indica il numero di threads da recuperare dalla base dati
     * @return array|null Elenco contenente i thread recuperati
     * @throws ValidationException Eccezione lanciata nel caso vi fossero problemi con la validazione dei dati nel
     * della creazione dell'oggetto EThread recuperato dalla base dati
     */
    public function loadThreadsValutazioneMaggiore(int $numeroThreads): ?array
    {
        $threads = array();
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $stmt = $pdo->query("SELECT threadID FROM threads, valutazioni WHERE valutazioneThreadID = valutazioneID 
                         ORDER BY totale DESC, data DESC LIMIT " . $numeroThreads);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $threadID = (int)$row["threadID"];
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
     * Permette di modificare l'autore dei threads scritti da un utente, la modifica comporta che il nuovo autore
     * risulta essere l'utente di default. Il metodo viene richiamato nel momento in cui l'utente che aveva scritto i
     * threads viene eliminato dalla base dati.
     * Se l'operazione va a buon fine allora viene restituito true, false altrimenti.
     * @param PDO $pdo Connessione con il database
     * @param int $userID Identificativo che rappresenta l'autore dei thread da aggiornare
     * @return bool Esito dell'operazione
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
     * @param PDO $pdo Connessione con il database
     * @param int $categoriaID Identificativo che rappresenta la categoria di appartenenza dei threads da aggiornare
     * @return bool Esito dell'operazione
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
     * Permette di memorizzare nella base dati un oggetto EThread.
     * Se l'operazione va buon fine allora viene restituito true, false altrimenti.
     * @param EThread $thread Thread da memorizzare nella base dati
     * @return bool Esito dell'operazione
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
     * @param PDO $pdo Connessione al database
     * @param array $allegato Allegato da memorizzzare nella base dati
     * @param int $threadID Identificativo del thread a cui è associato l'allegato da memorizzare
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
     * Permette di rimuovere un thread dalla base dati. Quando un thread viene eliminato allora viene rimossa anche la
     * valutazione, tutti gli allegati e tutte le risposte ad esso associati.
     * Se l'operazione va a buon fine viene restituito true, false altrimenti.
     * @param int $threadID Identificativo del thread da rimuovere dalla base dati
     * @return bool Esito dell'operazione
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
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($rows) == 1) {
                    $valutazioneID = (int)$rows[0]["valutazioneThreadID"];
                } else {
                    return false;
                }
                /*
                 * L'eliminazione del thread dalla base dati comporta una serie di operazioni, queste vengono eseguite
                 * una di seguito all'altra e le modifiche sulla base dati devono essere effettuate solo se tutte vanno
                 * a buon fine.
                 * Tali operazioni sono:
                 * - eliminazione della valutazione associata al thread;
                 * - eliminazione del thread.
                 * Inoltre, per evitare inconsistenza sui dati causata dall'accesso concorrente alle stesse risorse, le
                 * operazioni sopra descritte vengono eseguite in mutua esclusione.
                 */
                //$pdo->beginTransaction();
                $pdo->query("SET autocommit = 0");
                $pdo->query("LOCK TABLES threads WRITE, valutazioni WRITE");

                $fValutazione = FValutazione::getInstance();
                $resultDeleteValutazione = $fValutazione->delete($pdo, $valutazioneID);

                $sql = ("DELETE FROM threads WHERE threadID = " . $threadID);
                $stmt = $pdo->prepare($sql);
                $resultDeleteThread = $stmt->execute();

                if ($resultDeleteValutazione == true && $resultDeleteThread == true) {
                    //$pdo->commit();
                    $pdo->query("COMMIT");
                    $pdo->query("UNLOCK TABLES");
                    return true;
                } else {
                    //$pdo->rollBack();
                    $pdo->query("COMMIT");
                    $pdo->query("UNLOCK TABLES");
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
     * @param int $categoriaID Identificativo della categoria a cui devono appartenere i threads da recuperare dalla base dati
     * @param int $rigaPartenza Valore che indica da quale record iniziare il recupero
     * @param int $numeroRighe Valore che indica quanti record recuperare
     * @return array|null Elenco contenente i threads recuperati
     * @throws ValidationException Eccezione lanciata nel caso in cui ci fossero problemi con la validazione dei dati
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
                    $threadID = (int)$row["threadID"];
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
     * @param string $titolo Titolo che deve avere il thread da cercare nella base dati
     * @param int $rigaPartenza Valore che indica da quale record iniziare il recupero
     * @param int $numeroRighe Valore che indica quanti record recuperare
     * @return array|null Elenco contenente i threads cercati
     * @throws ValidationException Eccezione lanciata nel caso in cui ci fossero problemi con la validazione dei dati
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
                    $threadID = (int)$row["threadID"];
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
     * @param string $titolo Titolo che deve avere il thread da cercare nella base dati
     * @param array $categorieID Elenco degli identificativi delle categorie a cui i thread recuperati devono appartenere
     * @param int $rigaPartenza Valore che indica da quale record iniziare il recupero
     * @param int $numeroRighe Valore che indica quanti record recuperare
     * @return array|null Elenco contenente i threads cercati
     * @throws ValidationException Eccezione lanciata nel caso in cui ci fossero problemi con la validazione dei dati
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
                    $threadID = (int)$row["threadID"];
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
     * Conta il numero di threads appartenenti ad una categoria, della quale viene fornito in ingresso il suo
     * identificativo.
     * Se l'operazione non va a buon fine allora viene restituito null.
     * @param int $categoriaID Identificativo della categoria a cui devono appartenere i thread da contare
     * @return int|null Numero di threads appartenenti alla categoria fornita in ingresso
     */
    public function contaThreadsCategoria(int $categoriaID): ?int
        {
            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();

                $sql = ("SELECT count(*) AS numeroThreads FROM threads WHERE catThreadID = :categoriaID");
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    'categoriaID' => $categoriaID
                ));
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($rows) == 1) {
                    return (int)$rows[0]['numeroThreads'];
                } else {
                    return null;
                }
            } catch (PDOException $e) {
                return null;
            }
        }
    }