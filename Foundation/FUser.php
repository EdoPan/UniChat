<?php
declare(strict_types = 1);
require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "utility.php";

/**
 * Classe Foundation di User.
 */
class FUser
{
    /**
     * Istanza della classe FUser, si utilizza per il singleton.
     * @var null|FUser
     */
    private static $instance = null;

    /**
     * Costruttore di default.
     */
    private function __construct()
    {
    }

    /**
     * Restituisce l'istanza di FUser. Se già esistente restituisce quella esistente, altrimenti la crea.
     * @return FUser Istanza di FUser.
     */
    public static function getInstance(): FUser
    {
        if(self::$instance == null){
            $classe = __CLASS__;
            self::$instance = new $classe;
        }
        return self::$instance;
    }

    /**
     * Restituisce l'oggetto EUser, memorizzato nel database, avente come id quello passato come parametro.
     * Qualora la foto profilo dell'utente non fosse presente, vi fossero problemi con la comunicazione con il database,
     * o vi fossero errori di varia natura allora viene restituito null.
     * @param int $userID Identificativo dell'utente da recuperare dalla base dati.
     * @return EUser|null Utente recuperato dalla base dati.
     * @throws ValidationException Eccezione lanciata nel caso vi fossero problemi con la validazione dei dati.
     */
    public function load(int $userID): ?EUser
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $sql = ("SELECT * FROM users WHERE userID = :userID");
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':userID' => $userID
            ));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                $record = $rows[0];
                $userID = (int)$record["userID"];
                $nome = $record["nome"];
                $cognome = $record["cognome"];
                $email = $record["email"];
                $password = $record["password"];

                /*
                 * Recupero foto profilo dell'utente.
                 */
                $fotoProfiloID = (int)$record["fotoProfiloID"];
                $fotoProfilo = $this->loadFotoProfilo($fotoProfiloID);
                if (!isset($fotoProfilo)) {
                    return null;
                }

                $corsoStudio = $record["corsoStudio"];
                $user = new EUser($userID, $nome, $cognome, $email, $password, $fotoProfilo, $corsoStudio);
                return $user;
            } else {
                return null;
            }
        } catch (PDOException $e){
            return null;
        }
    }

    /**
     * Permette di ottenere la foto profilo dell'utente dato l'id che la identifica. La foto profilo è restituita in un
     * array associativo avente i campi id, nome, dimensione, tipo e immagine, in quest'ultimo risiede il file in
     * formato stringa e codificato in BASE64.
     * In caso di errori viene restituito null.
     * @param int $fotoID Identificativo della foto profilo da recuperare dalla base dati.
     * @return array|null Foto profilo.
     */
    private function loadFotoProfilo(int $fotoID): ?array
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $sql = ("SELECT * FROM fotoprofilo WHERE fotoID = :fotoID");
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                'fotoID' => $fotoID
            ));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                $record = $rows[0];
                $fotoProfilo = array(
                    "id" => $record["fotoID"],
                    "nome" => $record["nome"],
                    "dimensione" => $record["dimensione"],
                    "tipo" => $record["tipo"],
                    "immagine" => $record["immagine"]
                );
                return $fotoProfilo;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Restituisce l'oggetto EModeratore, memorizzato nel database, dato l'id che lo identifica.
     * Qualora il moderatore o la sua la foto profilo non fossero presenti, vi fossero problemi con il recupero della categoria
     * gestita dal moderatore, vi fossero problemi con la comunicazione con il database o vi fossero errori di varia
     * natura allora viene restituito null.
     * @param int $moderatoreID Identificativo del moderatore da recuperare dalla base dati.
     * @return EModeratore|null Moderatore recuperato dalla base dati.
     * @throws ValidationException Eccezione lanciata nel caso in cui ci fossero problemi con la validazione dei dati.
     */
    public function loadModeratore(int $moderatoreID): ?EModeratore
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $sql = ("SELECT users.*, categorie.categoriaID FROM users, categorie WHERE moderatore = true AND userID = moderatoreID AND userID = :userID");
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':userID' => $moderatoreID
            ));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                $record = $rows[0];
                $moderatoreID = (int)$record["userID"];
                $nome = $record["nome"];
                $cognome = $record["cognome"];
                $email = $record["email"];
                $password = $record["password"];

                /*
                 * Recupero foto profilo del moderatore.
                 */
                $fotoProfiloID = (int)$record["fotoProfiloID"];
                $fotoProfilo = $this->loadFotoProfilo($fotoProfiloID);
                if (!isset($fotoProfilo)) {
                    return null;
                }

                $corsoStudio = $record["corsoStudio"];

                /*
                 * Recupero categoria gestita dal moderatore.
                 */
                $categoriaID = (int)$record["categoriaID"];
                $fCategoria = FCategoria::getInstance();
                $categoriaGestita = $fCategoria->load($categoriaID);
                if (!isset($categoriaGestita)) {
                    return null;
                }

                $moderatore = new EModeratore($moderatoreID, $nome, $cognome, $email, $password, $fotoProfilo, $corsoStudio, $categoriaGestita);
                return $moderatore;
            } else {
                return null;
            }
        } catch (PDOException $e){
            return null;
        }
    }

    /**
     * Restituisce l'oggetto EAdmin, memorizzato nel database, dato l'id che lo identifica.
     * Qualora l'admin o la sua foto profilo non fossero presenti, vi fossero problemi con la comunicazione con il database
     * o vi fossero errori di varia natura allora viene restituito null.
     * @param int $adminID Identificativo dell'admin da recuperare dalla base dati.
     * @return EAdmin|null Admin recuperato dalla base dati.
     * @throws ValidationException  Eccezione lanciata nel caso in cui ci fossero problemi con la validazione dei dati.
     */
    public function loadAdmin(int $adminID): ?EAdmin
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $sql = ("SELECT * FROM users WHERE admin = true AND userID = :userID");
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':userID' => $adminID
            ));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                $record = $rows[0];
                $userID = (int)$record["userID"];
                $nome = $record["nome"];
                $cognome = $record["cognome"];
                $email = $record["email"];
                $password = $record["password"];

                /*
                 * Recupero foto profilo.
                 */
                $fotoProfiloID = (int)$record["fotoProfiloID"];
                $fotoProfilo = $this->loadFotoProfilo($fotoProfiloID);
                if (!isset($fotoProfilo)) {
                    return null;
                }

                $corsoStudio = $record["corsoStudio"];
                $admin = new EAdmin($userID, $nome, $cognome, $email, $password, $fotoProfilo, $corsoStudio, null);
                return $admin;
            } else {
                return null;
            }
        } catch (PDOException $e){
            return null;
        }
    }

    /**
     * Restituisce un Euser, un EModeratore o un EAdmin, dato l'email fornita in fase di registrazione.
     * Qualora non vi fosse un utente con quella email o errori di varia natura, viene restituito null.
     * @param string $email Email dell'utente da recuperare dalla base dati.
     * @return EUser|null Utente recuperato dalla base dati.
     * @throws ValidationException Eccezione lanciata nel caso ci fossero problemi con la validazione dei dati.
     */
    public function loadByEmail(string $email): ?EUser
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $sql = ("SELECT userID, moderatore, admin FROM users WHERE email = :email");
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':email' => $email
            ));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                $record = $rows[0];
                if ((int)$record['userID'] == 1) {
                    return null;
                } else {
                    /*
                     * Verifica della tipologia di utente e scelta del metodo di load corrispondente.
                     */
                    if ((int)$record["moderatore"] == 1) {
                        $user = $this->loadModeratore((int)$record["userID"]);
                    } else if ((int)$record["admin"] == 1) {
                        $user = $this->loadAdmin((int)$record['userID']);
                    } else {
                        $user = $this->load((int)$record['userID']);
                    }

                    return $user;
                }
            } else {
                return null;
            }
        } catch (PDOException $e){
            return null;
        }
    }

    /**
     * Permette di ottenere il moderatore che gestisce una categoria a partire dall'identificativo di quest'ultima.
     * In caso di errori viene restituito null.
     * @param int $categoriaID Identificativo della categoria di cui si vuole ottenere il moderatore.
     * @return EModeratore|null Moderatore recuperato dalla base dati.
     * @throws ValidationException Eccezione lanciata nel caso ci fossero problemi con la validazione dei dati.
     */
    public function loadModeratoreCategoria(int $categoriaID): ?EModeratore
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $sql = ("SELECT userID FROM users, categorie WHERE userID = moderatoreID AND categoriaID = :categoriaID");
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':categoriaID' => $categoriaID
            ));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                $record = $rows[0];
                $userID = (int)$record["userID"];
                $moderatore = $this->loadModeratore($userID);
                return $moderatore;
            } else {
                return null;
            }
        } catch (PDOException $e){
            return null;
        }
    }

    
    /**
     * Aggiorna nel database alcuni valori degli attributi dell'utente, si tratta degli unici modificabili dall'utente
     * stesso (quindi password, corso di studio e foto profilo).
     * Se l'operazione va a buon fine allora viene restituito true, in caso contrario false.
     * @param EUser $user Utente di cui si devono aggiornare alcuni valori nella base dati.
     * @return bool Esito dell'operazione.
     */
    public function update(EUser $user): bool
    {
        $userID = $user->getId();
        $userEmail = $user->getEmail();

        if(!$this->existsByEmail($userEmail)) {
            return false;
        }

        if ($userID == 1) {
            return false;
        }


        $password = $user->getPassword();
        $fotoProfilo = $user->getFotoProfilo();
        $corsoStudio = $user->getCorsoStudio();

        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $query = ("SELECT fotoProfiloID FROM users WHERE userID = :userID");
            $s = $pdo->prepare($query);
            $s->execute(array(
                ':userID' => $userID
            ));
            $rows = $s->fetchAll(PDO::FETCH_ASSOC);
            $fotoProfiloID = (int)$rows[0]["fotoProfiloID"];

            /*
             * Gestione delle operazioni di update in base al tipo di modifica che viene apportata alla foto profilo da
             * parte dell'utente.
             */
            if ($fotoProfiloID == $fotoProfilo["id"]) {

                /*
                 * La foto profilo non è stata cambiata, allora non ne viene eseguito l'update e si aggiornano solo gli
                 * altri campi.
                 */
                $sql = ("UPDATE users SET password = :password, corsoStudio = :corsoStudio WHERE userID = :userID");
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute(array(
                    ':password' => $password,
                    ':corsoStudio' => $corsoStudio,
                    ':userID' => $userID
                ));

            } else {

                /*
                 * La foto profilo è stata cambiata e quindi si deve includere nell'update.
                 * Si devono eseguire una serie di operazioni consecutive, ma le modifiche sulla base dati devono avere
                 * effetto solo se tutte vanno a buon fine.
                 * Tali operazioni sono:
                 * - eliminazione della foto profilo precedente (se questa non era quella di default);
                 * - memorizzazione della nuova foto profilo;
                 * - operazione di update.
                 * Inoltre, per evitare inconsistenza sui dati causata dall'accesso concorrente alle stesse risorse, le
                 * operazioni sopra descritte vengono eseguite in mutua esclusione.
                 */

                $pdo->query("SET autocommit = 0");
                $pdo->query("LOCK TABLES fotoprofilo WRITE, users WRITE");

                if ($fotoProfiloID == 1) {
                    $resultDelete = true;
                } else {
                    $resultDelete = $this->deleteFotoProfilo($pdo, $fotoProfiloID);
                }
                $newFotoProfiloID = $this->storeFotoProfilo($pdo, $fotoProfilo);
                if (!$newFotoProfiloID) {
                    $pdo->rollBack();
                    return false;
                }
                $sql = ("UPDATE users SET password = :password, fotoProfiloID = :fotoProfiloID, corsoStudio = :corsoStudio WHERE userID = :userID");
                $stmt = $pdo->prepare($sql);
                $resultUpdate = $stmt->execute(array(
                    ':password' => $password,
                    ':fotoProfiloID' => $newFotoProfiloID,
                    ':corsoStudio' => $corsoStudio,
                    ':userID' => $userID
                ));
                if ($resultDelete == true && $resultUpdate == true) {
                    $pdo->query("COMMIT");
                    $pdo->query("UNLOCK TABLES");
                    $result = true;
                } else {
                    $pdo->query("ROLLBACK");
                    $pdo->query("UNLOCK TABLES");
                    $result = false;
                }
            }
            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Permette di assegnare ad un utente il ruolo di moderatore di una categoria.
     * Viene restituito true se l'operazione va buon fine, false altrimenti.
     * @param PDO $pdo Connessione al DBMS e al database.
     * @param int $userID Identificativo dell'utente da rendere moderatore.
     * @return bool Esito dell'operazione.
     */
    public function updateToModeratore(PDO $pdo, int $userID): bool  //usato da FCategoria->update
    {
        try {
            $sql = ("UPDATE users SET moderatore = true WHERE userID = :moderatoreID");
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(array(
                ':moderatoreID' => $userID
            ));
            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Permette di rimuovere un utente dal ruolo di moderatore di una categoria.
     * Viene restituito true se l'operazione va a buon fine, false altrimenti.
     * @param PDO $pdo Connessione al database.
     * @param int $moderatoreID Identificativo del moderatore da rendere utente semplice.
     * @return bool Esito dell'operazione.
     */
    public function updateToUser(PDO $pdo, int $moderatoreID): bool  //usato da FCategoria->rimuoviModeratore
    {
        try {
            $sql = ("UPDATE users SET moderatore = false WHERE userID = :moderatoreID");
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(array(
                ':moderatoreID' => $moderatoreID
            ));
            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Permette di memorizzare nella base dati l'oggetto EUser.
     * Viene restituito true se l'operazione va a buon fine, false altrimenti.
     * @param EUser $user Utente da memorizzare nella base dati.
     * @return bool Esito dell'operazione.
     */
    public function store(EUser $user): bool
    {
        $nome = $user->getNome();
        $cognome = $user->getCognome();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $fotoProfilo = $user->getFotoProfilo();
        $corsoStudio = $user->getCorsoStudio();

        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            /*
             * Gestione delle operazioni di store in base alla foto profilo scelta dall'utente.
             */


            if ($fotoProfilo["id"] != 1) {

                /*
                 * L'utente ha caricato un foto profilo diversa da quella di default. Vengono eseguite delle operazioni
                 * in maniera consecutiva, ma solo se queste avvengono tutte correttamente allora viene modificata la
                 * base dati.
                 * Tali operazioni sono:
                 * - memorizzazione della nuova foto profilo;
                 * - memorizzazione del nuovo utente.
                 */
                $pdo->beginTransaction();

                /*
                 * Memorizzazione della foto profilo.
                 */
                $fotoProfiloID = $this->storeFotoProfilo($pdo, $fotoProfilo);
                if (!isset($fotoProfiloID)) {
                    $pdo->rollBack();
                    return false;
                }

                /*
                 * Memorizzazione dell'utente.
                 */
                $sql = ("INSERT INTO users(nome, cognome, email, password, fotoProfiloID, corsoStudio, moderatore, admin)
                    VALUES (:nome, :cognome, :email, :password, :fotoProfiloID, :corsoStudio, false, false)");
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute(array(
                    ':nome' => $nome,
                    ':cognome' => $cognome,
                    ':email' => $email,
                    ':password' => $password,
                    ':fotoProfiloID' => $fotoProfiloID,
                    ':corsoStudio' => $corsoStudio
                ));
                if($result){
                    $pdo->commit();
                } else {
                    $pdo->rollBack();
                }
            } else {

                /*
                 * L'utente ha impostato la foto di profilo di default. Deve essere seguita la sola operazione di
                 * memorizzazione dell'utente e in particolare viene posto ad 1 il campo fotoProfiloID.
                 */
                $sql = ("INSERT INTO users(nome, cognome, email, password, fotoProfiloID, corsoStudio, moderatore, admin)
                    VALUES (:nome, :cognome, :email, :password, :fotoProfiloID, :corsoStudio, false, false)");
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute(array(
                    ':nome' => $nome,
                    ':cognome' => $cognome,
                    ':email' => $email,
                    ':password' => $password,
                    ':fotoProfiloID' => 1,
                    ':corsoStudio' => $corsoStudio
                ));
            }
            return $result;
        } catch (PDOException $e){
            return false;
        }
    }

    /**
     * Permette di memorizzare nella base dati una nuova foto profilo.
     * Se l'operazione va a buon fine viene restituito l'id assegnato alla foto profilo nella base dati, altrimenti
     * viene restituito null.
     * @param PDO $pdo Connessione al DBMS e alla base dati.
     * @param array $fotoProfilo Foto profilo da memorizzare nella base dati.
     * @return int|null Identificativo assegnato dalla base dati alla foto profilo.
     */
    private function storeFotoProfilo(PDO $pdo, array $fotoProfilo): ?int{
        $nome = $fotoProfilo["nome"];
        $dimensione = $fotoProfilo["dimensione"];
        $tipo = $fotoProfilo["tipo"];
        $immagine = $fotoProfilo["immagine"];

        try {
            $query = $pdo->query("INSERT INTO fotoprofilo(nome, dimensione, tipo, immagine) VALUES ('$nome','$dimensione','$tipo','$immagine')");
            $fotoProfiloId = (int)$pdo->lastInsertId();
            return $fotoProfiloId;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Permette di rimuovere un utente dalla base dati. Quando un utente viene eliminato allora tutti i suoi thread,
     * messaggi e risposte vengono assegnate ad un utente di default. Per quanto riguarda il giudizio espresso per i
     * thread, il valore non viene decrementato ma l'utente viene rimosso dall'elenco degli utenti che ha espresso il
     * giudizio. Se l'utente era un moderatore allora questo viene rimosso da tale ruolo.
     * Restituisce true se l'operazione va buon fine, false altrimenti.
     * @param int $userID Identificativo dell'utente da rimuovere dalla base dati.
     * @return bool Esito dell'operazione.
     */
    public function delete(int $userID): bool{

        if ($userID == 1 || $userID == 2) {
            return false;
        }

        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            /*
             * Recupero identificativo della foto profilo dell'utente.
             */
            $query = ("SELECT fotoProfiloID FROM users WHERE userID = :userID");
            $s = $pdo->prepare($query);
            $s->execute(array(
                ':userID' => $userID
            ));
            $rows = $s->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                $fotoProfiloID = (int)$rows[0]["fotoProfiloID"];
            } else {
                return false;
            }

            /*
             * La rimozione dell'utente richiede una serie di operazioni che devono essere eseguite una di seguito
             * all'altra. Le operazioni avranno effetto sulla base dati solo se tutte avranno esito positivo.
             * Tali operazioni sono:
             * - rimozione della foto profilo (solo se questa non è quella di default);
             * - aggiornamento dei thread scritti dall'utente con l'identificativo dell'utente di default;
             * - aggiornamento dei messaggi scritti dall'utente con l'identificativo dell'utente di default;
             * - aggiornamento delle risposte scritte dall'utente con l'identificativo dell'utente di default;
             * - rimozione dell'utente.
             * Inoltre, per evitare inconsistenza sui dati causata dall'accesso concorrente alle stesse risorse, le
             * operazioni sopra descritte vengono eseguite in mutua esclusione.
             */
            $pdo->query("SET autocommit = 0");
            $pdo->query("LOCK TABLES threads WRITE, messaggi WRITE, risposte WRITE, users WRITE, fotoprofilo WRITE");

            /*
             * Rimozione della foto profilo.
             */
            $resultDeleteFotoProfilo = true;
            if ($fotoProfiloID != 1) {
                $resultDeleteFotoProfilo = $this->deleteFotoProfilo($pdo, $fotoProfiloID);
            }

            /*
             * Aggiornamento dei threads.
             */
            $fThread = FThread::getInstance();
            $resultUpdateThread = $fThread->updateUserID($pdo, $userID); //metodo di FThread che aggiorna tutti gli autoreThreadID dei thread di questo userID con 1

            /*
             * Aggiornamento dei messaggi.
             */
            $fMessaggio = FMessaggio::getInstance();
            $resultUpdateMessaggi = $fMessaggio->updateUserID($pdo, $userID);

            /*
             * Aggiornamento delle risposte.
             */
            $fRisposta = FRisposta::getInstance();
            $resultUpdateRisposte = $fRisposta->updateUserID($pdo, $userID);

            /*
             * Eliminazione dell'utente.
             */
            $sql = ("DELETE FROM users WHERE userID = :userID");
            $stmt = $pdo->prepare($sql);
            $resultDeleteUser = $stmt->execute(array(
                ':userID' => $userID
            ));

            if ($resultDeleteFotoProfilo == true && $resultUpdateThread == true && $resultUpdateMessaggi == true && $resultUpdateRisposte == true && $resultDeleteUser == true) {
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
     * Permette di rimuovere la foto profilo di un utente dalla base dati.
     * Restituisce true se l'operazione va buon fine, false altrimenti.
     * @param PDO $pdo Connessione al DBMS e alla base dati.
     * @param int $fotoID Identificativo della foto profilo da rimuovere.
     * @return bool Esito dell'operazione.
     */
    private function deleteFotoProfilo(PDO $pdo, int $fotoID): bool
    {
        try {
            $sql = "DELETE FROM fotoprofilo WHERE fotoID = :fotoID";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(array(
                ':fotoID' => $fotoID
            ));
            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Verifica che un utente sia presente nella base dati fornendo la sua email.
     * Restituisce true se l'utente è presente, false se l'utente non è presente o null se vi sono stati errori.
     * @param string $email Email dell'utente di cui si deve verificare l'esistenza nella base dati.
     * @return bool|null Esito della richiesta.
     */
    public function existsByEmail(string $email): ?bool
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $sql = ("SELECT userID FROM users WHERE email = :email");
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':email' => $email
            ));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                if ((int)$rows[0]['userID'] == 1) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        } catch (PDOException $e){
            return null;
        }
    }

    /**
     * Verifica che l'utente sia un moderatore.
     * Restituisce true se l'utente è un moderatore, false se non lo è o se l'utente non esiste e null se vi sono stati errori.
     * @param int $userID Identificativo dell'utente di cui si deve verificare il ruolo di moderatore.
     * @return bool|null Esito della richiesta.
     */
    public function isModeratore(int $userID): ?bool
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $sql = ("SELECT * FROM users WHERE moderatore = true AND userID = :userID");
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':userID' => $userID
            ));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Verifica che l'utente sia un admin.
     * Restituisce true se l'utente è un admin, false se non lo è o se l'utente non esiste e null se vi sono stati errori.
     * @param int $userID Identificativo dell'utente di cui si deve verificare il ruolo di admin.
     * @return bool|null Esito della richiesta.
     */
    public function isAdmin(int $userID): ?bool
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $sql = ("SELECT * FROM users WHERE admin = true AND userID = :userID");
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':userID' => $userID
            ));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Permette di ottenere un certo numero di EUser, EModeratori e EAdmin specificando da quale riga dell tabella della
     * base dati partire (riga di partenza esclusa) e il numero di righe da visualizzare.
     * Viene restituito un array di utenti (in senso generale), eventualmente vuoto, se l'operazione va a buon fine,
     * null altrimenti.
     * @param int $rigaPartenza Valore che indica da quale record iniziare il recupero.
     * @param int $numeroRighe Valore che indica quanti record recuperare.
     * @return array|null Elenco contenente gli utenti recuperati.
     * @throws ValidationException Eccezione lanciata nel caso ci fossero problemi di validazione dei dati.
     */
    public function loadAll(int $rigaPartenza, int $numeroRighe): ?array
    {
        $users = array();

        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $stmt = $pdo->query("SELECT userID, moderatore, admin FROM users ORDER BY cognome LIMIT " . $rigaPartenza . ", " . $numeroRighe);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {

                /*
                 * Verifica della tipologia di utente da caricare e poi aggiungere all'array da restituire.
                 */
                if ((int)$row["moderatore"] == 1) {
                    $user= $this->loadModeratore((int)$row["userID"]);
                } else if ((int)$row["admin"] == 1) {
                    $user = $this->loadAdmin((int)$row['userID']);
                } else {
                    $user = $this->load((int)$row['userID']);
                }

                if(isset($user)){
                    $users[] = $user;
                } else {
                    return null;
                }
            }
            return $users;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Conta il numero di utenti registrati nella base dati.
     * Se l'operazione non va a buon fine allora viene restituito null.
     * @return int|null Numero di utenti registrati nella base dati.
     */
    public function conta(): ?int
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $stmt = $pdo->query("SELECT count(*) as numeroUtenti FROM users");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(count($rows) == 1){
                return (int)$rows[0]['numeroUtenti'];
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return null;
        }
    }
}