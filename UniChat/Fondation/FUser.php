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
     * Construttore di default
     */
    private function __construct()
    {
    }

    /**
     * Restituisce l'instanza di FUser. Se già esistente restituisce quella esistente, altrimenti la crea.
     * @return mixed|null
     */
    public static function getInstance()
    {
        if(self::$instance == null){
            $classe =__CLASS__;
            self::$instance = new $classe;
        }
        return self::$instance;
    }

    /**
     * Restituisce l'oggeto EUser memorizzato nel database con l'id passato come paramentro.
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
     * @param int $userID
     * @return EModeratore|null
     */
    public function loadModeratore(int $userID): ?EModeratore
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

            $stmt = $pdo->query("SELECT * FROM users, categorie WHERE moderatore = true AND = moderatoreID AND userID = " . $userID);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                $record = $rows[0];
                $userID = (int)$record["userID"];
                $nome = $record["nome"];
                $cognome = $record["cognome"];
                $email = $record["email"];
                $password = $record["password"];

                $fotoProfiloID = $record["fotoProfiloID"];
                $fotoProfilo = $this->loadFotoProfilo($fotoProfiloID);
                if (!isset($fotoProfilo)) {
                    return null;
                }

                $corsoStudio = $record["corsoStudio"];

                $categoriaID = (int)$record["categoriaID"];
                $fCategoria = FCategoria::getInstance();
                $categoriaGestita = $fCategoria->load($categoriaID);
                if (!isset($categoriaGestita)) {
                    return null;
                }

                $moderatore = new EModeratore($userID, $nome, $cognome, $email, $password, $fotoProfilo, $corsoStudio, $categoriaGestita);
                return $moderatore;
            } else {
                return null;
            }
        } catch (PDOException $e){
            return null;
        }
    }

    /**
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
     * @param string $email
     * @return EUser|null
     */
    public function loadByEmail(string $email): ?EUser
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

            $stmt = $pdo->query("SELECT userID FROM users WHERE email = '" . $email . "'");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                $record = $rows[0];
                if ((int)$record["moderatore"] == 1) {
                    $user = $this->loadModeratore((int)$record["userID"]);
                } else if ((int)$record["admin"] == 1) {
                    $user = $this->loadAdmin();
                } else {
                    $user = $this->load();
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
            if ($fotoProfiloID == $fotoProfilo["id"]) {

                $sql = ("UPDATE users SET password = :password, corsoStudio = :corsoStudio WHERE userID = :userID");
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute(array(
                    ':password' => $password,
                    ':corsoStudio' => $corsoStudio,
                    ':userID' => $userID
                ));
            } else {
                if ($fotoProfilo["id"] == 1) {
                    $sql = ("UPDATE users SET password = :password, fotoProfiloID = :fotoProfiloID, corsoStudio = :corsoStudio WHERE userID = :userID");
                    $stmt = $pdo->prepare($sql);
                    $result = $stmt->execute(array(
                        ':password' => $password,
                        ':fotoProfiloID' => 1,
                        ':corsoStudio' => $corsoStudio,
                        ':userID' => $userID
                    ));
                } else {
                    $pdo->beginTransaction();
                    $resultDelete = $this->deleteFotoProfilo($pdo, $fotoProfiloID);
                    $newFotoProfiloID = $this->storeFotoProfilo($pdo, $fotoProfilo);
                    if (!$newFotoProfiloID) { //verificare se è davvero necessario o alla fine non ci sarà un crash proseguendo anche con valore null
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
     * @param EUser $u
     * @return bool|null
     */
    public function store(EUser $u): ?bool
    {
        $userID = $u->getId();
        $nome = $u->getNome();
        $cognome = $u->getCognome();
        $email = $u->getEmail();
        $password = $u->getPassword();
        $fotoProfilo = $u->getFotoProfilo();
        $corsoStudio = $u->getCorsoStudio();

        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

            if ($fotoProfilo["id"] != 1) {
                $pdo->beginTransaction();
                $fotoProfiloID = $this->storeFotoProfilo($pdo, $fotoProfilo);
                if (!isset($fotoProfiloID)) {
                    return null;
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
     * @param EUser $user
     * @return bool
     */
    public function delete(EUser $user): bool{
        /*
         * se rimuovo un utente devo:
         * - mettere l'utente di default ai messaggi
         * - mettere l'utente di default ai thread
         * - mettere l'utente di default alle risposte
         * - rimuovere la sua foto profilo se non era quella di default
         */
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

            $userID = $user->getId();
            $fotoProfiloID = $user->getFotoProfilo()["id"];

            $pdo->query("SET autocommit = 0");
            $pdo->query("LOCK TABLES threads WRITE, messaggi WRITE, risposte WRITE");

            $resultDeleteFotoProfilo = true;
            if ($fotoProfiloID != 1) {
                $resultDeleteFotoProfilo = $this->deleteFotoProfilo($pdo, $fotoProfiloID);
            }

            $fThread = FThread::getInstance();
            $resultUpdateThread = $fThread->updateUserID($pdo, $userID); //metodo di FThread che aggiorna tutti gli autoreThreadID sei thread di questo userID con 1

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
                if ($row["moderatore"] == false && $row["admin"] == false) {
                    $users[] = $this->load((int)$row["userID"]);
                } else if ($row["moderatore"] == true && $row["admin"] == false) {
                    $users[] = $this->loadModeratore((int)$row["userID"]);
                }
            }
            return $users;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
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