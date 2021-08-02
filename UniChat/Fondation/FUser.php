<?php
    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

class FUser
{

    public static function existsByEmail(string $email): bool
    {
        $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
        $stmt = $pdo->query("SELECT userID FROM users WHERE email = '" . $email . "'");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) == 1) {
            return true;
        } else {
            return false;
        }
    }

    public static function getLastID(): int
    {
        $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
        $stmt = $pdo->query("SELECT MAX(userID) AS id FROM users");
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return (int)$row[0]["id"];
    }

    public static function store(EUser $u): bool
    {
        $userID = $u->getId();
        $nome = $u->getNome();
        $cognome = $u->getCognome();
        $email = $u->getEmail();
        $password = $u->getPassword();
        $fotoProfilo = $u->getFotoProfilo();
        $corsoStudio = $u->getCorsoStudio();
        $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
        $sql = ("INSERT INTO users(userID, nome, cognome, email, password, fotoProfilo, corsoStudio, moderatore, admin)
                    VALUES (:userID, :nome, :cognome, :email, :password, :fotoProfilo, :corsoStudio, false, false)");
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute(array(
            ':userID' =>  $userID,
            ':nome' => $nome,
            ':cognome' => $cognome,
            ':email' => $email,
            ':password' => $password,
            ':fotoProfilo' => $fotoProfilo,
            ':corsoStudio' => $corsoStudio
        ));
        return $result;
    }

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

    public static function load(int $userID): ?EUser
    {
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
            $fotoProfilo = $record["fotoProfilo"];
            $corsoStudio = $record["corsoStudio"];
            $user = new EUser($userID, $nome, $cognome, $email, $password, $fotoProfilo, $corsoStudio);
            return $user;
        } else {
            return null;
        }
    }

    public static function loadMod(int $userID): ?EModeratore
    {
        $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
        $stmt = $pdo->query("SELECT * FROM users, categorie WHERE userID = moderatoreID AND userID = " . $userID);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) == 1) {
            $record = $rows[0];
            $userID = (int)$record["userID"];
            $nome = $record["nome"];
            $cognome = $record["cognome"];
            $email = $record["email"];
            $password = $record["password"];
            $fotoProfilo = $record["fotoProfilo"];
            $corsoStudio = $record["corsoStudio"];
            $categoriaID = (int)$record["categoriaID"];
            $nomeCat = $record["nomeCategoria"];
            $icona = $record["icona"];
            $descrizione = $record["descrizione"];
            $categoriaGestita = new ECategoria($categoriaID, $nomeCat, $icona, $descrizione);
            $moderatore = new EModeratore($userID, $nome, $cognome, $email, $password, $fotoProfilo, $corsoStudio, $categoriaGestita);
            return $moderatore;
        } else {
            return null;
        }
    }

    public static function loadByEmail(string $email): ?EUser
    {
        $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
        $stmt = $pdo->query("SELECT * FROM users WHERE email = '" . $email . "'");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) == 1) {
            $record = $rows[0];
            if((int)$record["moderatore"] == 1){
                $user = self::loadMod((int)$record["userID"]);
            } else {
                $user = self::load((int)$record["userID"]);
            }
            return $user;
        } else {
            return null;
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

    public static function loadAdmin(int $adminID): ?EAdmin
    {
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
            $fotoProfilo = $record["fotoProfilo"];
            $corsoStudio = $record["corsoStudio"];
            $admin = new EAdmin($userID, $nome, $cognome, $email, $password, $fotoProfilo, $corsoStudio, null);
            return $admin;
        } else {
            return null;
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

    public static function loadModeratoreCategoria(int $categoriaID): ?EModeratore
    {
        $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
        $stmt = $pdo->query("SELECT * FROM users, categorie WHERE userID = moderatoreID AND categoriaID = " . $categoriaID);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) == 1) {
            $record = $rows[0];
            $userID = (int)$record["userID"];
            $nome = $record["nome"];
            $cognome = $record["cognome"];
            $email = $record["email"];
            $password = $record["password"];
            $fotoProfilo = $record["fotoProfilo"];
            $corsoStudio = $record["corsoStudio"];
            $categoriaID = (int)$record["categoriaID"];
            $nomeCat = $record["nomeCategoria"];
            $icona = $record["icona"];
            $descrizione = $record["descrizione"];
            $categoriaGestita = new ECategoria($categoriaID, $nomeCat, $icona, $descrizione);
            $moderatore = new EModeratore($userID, $nome, $cognome, $email, $password, $fotoProfilo, $corsoStudio, $categoriaGestita);
            return $moderatore;
        } else {
            return null;
        }
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