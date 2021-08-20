<?php
    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

class FUser
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if(self::$instance == null){
            $classe =__CLASS__;
            self::$instance = new $classe;
        }
        return self::$instance;
    }

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

    public function loadMod(int $userID): ?EModeratore
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

    public function loadByEmail(string $email): ?object
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
                    $user = $this->loadMod((int)$record["userID"]);
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

                $moderatore = $this->loadMod($userID);

                return $moderatore;
            } else {
                return null;
            }
        } catch (PDOException $e){
            return null;
        }
    }

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

            $fotoProfiloID = $this->storeFotoProfilo($fotoProfilo, $pdo);
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
            return $result;
        } catch (PDOException $e){
            return null;
        }
    }

    private function storeFotoProfilo(array $fotoProfilo, PDO $pdo): ?int{
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

    public static function existsByEmail(string $email): ?bool
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

    /*
    public static function getLastID(): int
    {
        $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
        $stmt = $pdo->query("SELECT MAX(userID) AS id FROM users");
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return (int)$row[0]["id"];
    }
    */

    public static function exists(string $email, string $password): bool
    {
        $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
        $stmt = $pdo->query("SELECT userID FROM users WHERE email = '" . $email . "' AND password = '" . $password . "'");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) == 1) {
            return true;
        } else {
            return false;
        }
    }


    public static function isModeratore(int $userID): bool
    {
        $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
        $stmt = $pdo->query("SELECT * FROM users WHERE moderatore = true AND userID = " . $userID);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(count($rows) == 1){
            return true;
        } else {
            return false;
        }
    }

    public static function isAdmin(int $userID): bool
    {
        $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
        $stmt = $pdo->query("SELECT * FROM users WHERE admin = true AND userID = " . $userID);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(count($rows) == 1){
            return true;
        } else {
            return false;
        }
    }


    public static function updateMod(EModeratore $mod): bool
    {
        $moderatoreID = $mod->getId();
        $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
        $sql = ("UPDATE users SET moderatore = true WHERE userID = :moderatoreID");
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute(array(
            ':moderatoreID' =>  $moderatoreID
        ));
        return $result;
    }


    public static function update(EModeratore $mod): bool
    {
        $moderatoreID = $mod->getId();
        $fotoProfilo = $mod->getFotoProfilo();
        $corsoStudio = $mod->getCorsoStudio();
        $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
        $sql = ("UPDATE users SET fotoProfilo = :fotoProfilo, corsoStudio = :corsoStudio, moderatore = false WHERE userID = :moderatoreID"); //DA correggere, se cambio la foto torno EUSER ANCHE SE DOVREI ESSERE UN EMod
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute(array(
            ':fotoProfilo' => $fotoProfilo,
            ':corsoStudio' => $corsoStudio,
            ':moderatoreID' =>  $moderatoreID
        ));
        return $result;
    }

    public static function updateToUser(EModeratore $mod): bool
    {
        $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
        $sql = ("UPDATE users SET moderatore = false WHERE userID = :moderatoreID");
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute(array(
            ':moderatoreID' =>  $mod->getId()
        ));
        return $result;
    }
}