<?php
    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

/**
 *
 */
class FUser
{
    /**
     * Instanza della classe FUser, si utilizza per il singleton.
     * @var null
     */
    private static $instance = null;

    /**
     * Costruttore di default.
     */
    private function __construct()
    {
    }

    /**
     * Restituisce l'instanza di FUser. Se già esistente restituisce quella esistente, altrimenti la crea.
     * @return FUser
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
     * Restituisce l'oggeto EUser, memorizzato nel database, avente come id quello passato come paramentro.
     * Qualora la foto profilo dell'utente non fosse presente, vi fossero problemi con la comunicazione con il database,
     * o vi fossero errori di varia natura allora viene restituito null.
     * @param int $userID
     * @return EUser|null
     */
    public function load(int $userID): ?EUser
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

            $stmt = $pdo->query("SELECT * FROM users WHERE userID = " . $userID);
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
                $fotoProfiloID = $record["fotoProfiloID"];
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
     * formato stringa.
     * In caso di errori viene restituito null.
     * @param int $fotoID
     * @return array|null
     */
    private function loadFotoProfilo(int $fotoID): ?array
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

            $stmt = $pdo->query("SELECT * FROM fotoprofilo WHERE fotoID = " . $fotoID);
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
     * Qualora la foto profilo del moderatore non fosse presente, vi fossero problemi con il recupero della categoria
     * gestita dal moderatore, vi fossero problemi con la comunicazione con il database o vi fossero errori di varia
     * natura allora viene restituito null.
     * @param int $moderatoreID
     * @return EModeratore|null
     */
    public function loadModeratore(int $moderatoreID): ?EModeratore
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

            $stmt = $pdo->query("SELECT * FROM users, categorie WHERE moderatore = true AND = moderatoreID AND userID = " . $moderatoreID);
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
                $fotoProfiloID = $record["fotoProfiloID"];
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
     * Qualora la foto profilo dell'admin non fosse presente, vi fossero problemi con la comunicazione con il database
     * o vi fossero errori di varia natura allora viene restituito null.
     * @param int $adminID
     * @return EAdmin|null
     */
    public function loadAdmin(int $adminID): ?EAdmin
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

            $stmt = $pdo->query("SELECT * FROM users WHERE admin = true AND userID = " . $adminID);
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
                $fotoProfiloID = $record["fotoProfiloID"];
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
     * Restituisce un Euser, un EModeratore o un EAdmin, dato l'id che lo identifica nel database.
     * Qualora non vi fosse un utente con quella email o errori di varia natura, viene restituito null.
     * @param string $email
     * @return EUser|null
     */
    public function loadByEmail(string $email): ?EUser
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

            $stmt = $pdo->query("SELECT userID, moderatore, admin FROM users WHERE email = '" . $email . "'");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                $record = $rows[0];

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
     * @param int $categoriaID
     * @return EModeratore|null
     */
    public function loadModeratoreCategoria(int $categoriaID): ?EModeratore
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

            $stmt = $pdo->query("SELECT userID FROM users, categorie WHERE userID = moderatoreID AND categoriaID = " . $categoriaID);
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
     * stesso.
     * Se l'operazione va a buon fine allora viene restituito true, in caso contrario false.
     * @param EUser $user
     * @return bool
     */
    public function update(EUser $user): bool
    {
        $userID = $user->getId();
        $password = $user->getPassword();
        $fotoProfilo = $user->getFotoProfilo();
        $corsoStudio = $user->getCorsoStudio();

        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

            $query = $pdo->query("SELECT fotoProfiloID FROM users WHERE userID = " . $userID);
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);
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
                 * La foto profilo è stata cambiata e quindi si deve includere nell'update, si deve capire se è stata
                 * impostata quella di default o una nuova immagine.
                 */
                if ($fotoProfilo["id"] == 1) {

                    /*
                     * La foto profilo scelta è quella di default, si aggiornano tutti i campi modificabili dall'utente
                     * e in particolare si imposta l'identificativo della foto profilo ad 1 (chiave primaria della foto
                     * di default).
                     * Inoltre, la foto profilo precedente usata dall'utente viene rimossa dalla base dati.
                     * Queste operazioni devono essere eseguite una di seguito all'altra e devono apportare modifiche
                     * alla base dati solo se avvengono tutte con successo.
                     * Tali operazioni sono:
                     * - eliminazione della foto profilo precedente;
                     * - operazione di update.
                     */
                    $pdo->beginTransaction();
                    $resultDelete = $this->deleteFotoProfilo($pdo, $fotoProfiloID);
                    $sql = ("UPDATE users SET password = :password, fotoProfiloID = :fotoProfiloID, corsoStudio = :corsoStudio WHERE userID = :userID");
                    $stmt = $pdo->prepare($sql);
                    $resultUpdate = $stmt->execute(array(
                        ':password' => $password,
                        ':fotoProfiloID' => 1,
                        ':corsoStudio' => $corsoStudio,
                        ':userID' => $userID
                    ));
                    if($resultDelete == true && $resultUpdate == true){
                        $pdo->commit();
                        $result = true;
                    } else {
                        $pdo->rollBack();
                        $result = false;
                    }
                } else {
                    /*
                     * La foto profilo scelta è una nuova, si devono eseguire una serie di operazioni consecutive, ma le
                     * modfiche sulla base dati devono avere effetto solo se tutte vanno a buon fine.
                     * Tali operazioni sono:
                     * - eliminazione della foto profilo precedente;
                     * - memorizzazione della nuova foto profilo;
                     * - operazione di update.
                     */
                    $pdo->beginTransaction();
                    $resultDelete = $this->deleteFotoProfilo($pdo, $fotoProfiloID);
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
                        $pdo->commit();
                        $result = true;
                    } else {
                        $pdo->rollBack();
                        $result = false;
                    }
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
     * @param PDO $pdo
     * @param EModeratore $moderatore
     * @return bool
     */
    public function updateToModeratore(PDO $pdo, EModeratore $moderatore): bool  //usato da FCategoria->update
    {
        $moderatoreID = $moderatore->getId();
        try {
            $sql = ("UPDATE users SET moderatore = true WHERE userID = :moderatoreID");
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
     * Permette di rimuovere un utente dal ruolo di moderatore di una categoria.
     * Viene restituito true se l'operazione va a buon fine, false altrimenti.
     * @param PDO $pdo
     * @param EModeratore $moderatore
     * @return bool
     */
    public function updateToUser(PDO $pdo, EModeratore $moderatore): bool  //usato da FCategoria->rimuoviModeratore
    {
        $moderatoreID = $moderatore->getId();
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
     * @param EUser $user
     * @return bool
     */
    public function store(EUser $user): bool
    {
        $userID = $user->getId();
        $nome = $user->getNome();
        $cognome = $user->getCognome();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $fotoProfilo = $user->getFotoProfilo();
        $corsoStudio = $user->getCorsoStudio();

        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

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
                $fotoProfiloID = $this->storeFotoProfilo($pdo, $fotoProfilo);
                if (!isset($fotoProfiloID)) {
                    $pdo->rollBack();
                    return false;
                }

                $sql = ("INSERT INTO users(userID, nome, cognome, email, password, fotoProfiloID, corsoStudio, moderatore, admin)
                    VALUES (:userID, :nome, :cognome, :email, :password, :fotoProfiloID, :corsoStudio, false, false)");
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute(array(
                    ':userID' => $userID,
                    ':nome' => $nome,
                    ':cognome' => $cognome,
                    ':email' => $email,
                    ':password' => $password,
                    ':fotoProfiloID' => $fotoProfilo,
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
                $sql = ("INSERT INTO users(userID, nome, cognome, email, password, fotoProfiloID, corsoStudio, moderatore, admin)
                    VALUES (:userID, :nome, :cognome, :email, :password, :fotoProfiloID, :corsoStudio, false, false)");
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute(array(
                    ':userID' => $userID,
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
     * @param PDO $pdo
     * @param array $fotoProfilo
     * @return int|null
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
     * messaggi e risposte vengono assegnate ad un utente di default. Per quanto rigaurda il giudizio espresso per i
     * thread, il valore non viene decrementato ma l'utente viene rimosso dall'elenco degli utenti che ha espresso il
     * giudizio. Se l'utente era un moderatore allora questo viene rimosso da tale ruolo.
     * Restituisce true se l'operazione va buon fine, false altrimenti.
     * @param int $userID
     * @return bool
     */
    public function delete(int $userID): bool{
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

            $stmt = $pdo->query("SELECT fotoProfiloID FROM users WHERE userID = " . $userID);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $fotoProfiloID = (int)$rows[0]["fotoProfiloID"];

            /*
             * La rimozione dell'utente richiede una serie di operazioni che devono essere eseguite una di seguito
             * all'altra e con mutua esclusione sulle tabelle della base dati che ne sono coinvolte. Le operazioni
             * avranno effetto sulla base dati solo se tutte avranno esito positivo.
             * Tali operazioni sono:
             * - rimozione della foto profilo (solo se questa non è quella di default);
             * - aggiornamento dei thread scritti dall'utente con l'identificativo dell'utente di default;
             * - aggiornamento dei messaggi scritti dall'utente con l'identificativo dell'utente di default;
             * - aggiornamento delle risposte scritte dall'utente con l'identificativo dell'utente di default;
             * - rimozione dell'utente.
             */
            $pdo->query("SET autocommit = 0");
            $pdo->query("LOCK TABLES threads WRITE, messaggi WRITE, risposte WRITE");

            $resultDeleteFotoProfilo = true;
            if ($fotoProfiloID != 1) {
                $resultDeleteFotoProfilo = $this->deleteFotoProfilo($pdo, $fotoProfiloID);
            }

            $fThread = FThread::getInstance();
            $resultUpdateThread = $fThread->updateUserID($pdo, $userID); //metodo di FThread che aggiorna tutti gli autoreThreadID dei thread di questo userID con 1

            $fMessaggio = FMessaggio::getInstance();
            $resultUpdateMessaggi = $fMessaggio->updateUserID($pdo, $userID);

            $fRisposta = FRisposta::getInstance();
            $resultUpdateRisposte = $fRisposta->updateUserID($pdo, $userID);

            $sql = "DELETE FROM users WHERE userID = :userID";
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
     * @param PDO $pdo
     * @param int $fotoID
     * @return bool
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
     * @param string $email
     * @return bool|null
     */
    public function existsByEmail(string $email): ?bool
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $stmt = $pdo->query("SELECT userID FROM users WHERE email = '" . $email . "'");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e){
            return null;
        }
    }

    /**
     * Verifica che l'utente sia un moderatore.
     * Restituisce true se l'utente è un moderatore, false se non lo è o null se vi sono stati errori.
     * @param int $userID
     * @return bool|null
     */
    public function isModeratore(int $userID): ?bool
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

            $stmt = $pdo->query("SELECT * FROM users WHERE moderatore = true AND userID = " . $userID);
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
     * Restituisce true se l'utente è un admin, false se non lo è o null se vi sono stati errori.
     * @param int $userID
     * @return bool|null
     */
    public function isAdmin(int $userID): ?bool
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

            $stmt = $pdo->query("SELECT * FROM users WHERE admin = true AND userID = " . $userID);
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
     * @param int $rigaPartenza
     * @param int $numeroRighe
     * @return array|null
     */
    public function loadAll(int $rigaPartenza, int $numeroRighe): ?array
    {
        $users = array();

        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

            $stmt = $pdo->query("SELECT userID, moderatore, admin FROM users ORDER BY cognome LIMIT " . $rigaPartenza . ", " . $numeroRighe);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {

                /*
                 * Verifica della tipologia di utente da caricare e poi aggiungere all'array da restituire.
                 */
                if ((int)$row["moderatore"] == 1) {
                    $users[] = $this->loadModeratore((int)$row["userID"]);
                } else if ((int)$row["admin"] == 1) {
                    $users[] = $this->loadAdmin((int)$row['userID']);
                } else {
                    $users[] = $this->load((int)$row['userID']);
                }
            }
            return $users;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Permette di ottenere un certo numero di EModeratori specificando da quale riga dell tabella della base dati
     * partire (riga di partenza esclusa) e il numero di righe da visualizzare.
     * Viene restituito un array di moderatori, eventualmente vuoto, se l'operazione va a buon fine, null altrimenti.
     * @param int $rigaPartenza
     * @param int $numeroRighe
     * @return array|null
     */
    public function loadAllModeratori(int $rigaPartenza, int $numeroRighe): ?array
    {
        $moderatori = array();

        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

            $stmt = $pdo->query("SELECT userID FROM users WHERE moderatore = true ORDER BY cognome LIMIT " . $rigaPartenza . ", " . $numeroRighe);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $moderatori[] = $this->loadModeratore((int)$row["userID"]);
            }
            return $moderatori;
        } catch (PDOException $e) {
            return null;
        }
    }
}