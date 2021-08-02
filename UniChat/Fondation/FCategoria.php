<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class FCategoria
    {
        public static function load(int $categoriaID): ?ECategoria
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $stmt = $pdo->query("SELECT * FROM categorie WHERE categoriaID = " . $categoriaID);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                $record = $rows[0];
                $cateID = (int)$record["categoriaID"];
                $nome = $record["nomeCategoria"];
                $icona = $record["icona"];
                $descrizione = $record["descrizione"];
                $categoria = new ECategoria($cateID, $nome, $icona, $descrizione);
                return $categoria;
            } else {
                return null;
            }
        }

        public static function loadCategoriaThread(int $threadID): ?ECategoria
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $stmt = $pdo->query("SELECT * FROM categorie, threads WHERE catThreadID = categoriaID AND threadID = " . $threadID);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                $record = $rows[0];
                $cateID = (int)$record["categoriaID"];
                $nome = $record["nomeCategoria"];
                $icona = $record["icona"];
                $descrizione = $record["descrizione"];
                $categoria = new ECategoria($cateID, $nome, $icona, $descrizione);
                return $categoria;
            } else {
                return null;
            }
        }

        public static function getLastID(): int
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $stmt = $pdo->query("SELECT MAX(categoriaID) AS id FROM categorie");
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return (int)$row[0]["id"];
        }

        public static function store(ECategoria $categoria): bool
        {
            $categoriaID = $categoria->getId();
            $nomeCategoria = $categoria->getNome();
            $icona = $categoria->getIcona();
            $descrizione = $categoria->getDescrizione();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $sql = ("INSERT INTO categorie(categoriaID, nomeCategoria, icona, descrizione)
                    VALUES (:categoriaID, :nomeCategoria, :icona, :descrizione)");
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(array(
                ':categoriaID' =>  $categoriaID,
                ':nomeCategoria' => $nomeCategoria,
                ':icona' => $icona,
                ':descrizione' => $descrizione
            ));
            return $result;
        }

        public static function update(?ECategoria $categoria, EModeratore $mod): bool
        {
            $categoriaID = $categoria->getId();
            $moderatoreID = $mod->getId();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $sql = ("UPDATE categorie SET moderatoreID = :moderatoreID WHERE categoriaID = :categoriaID");
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(array(
                ':moderatoreID' =>  $moderatoreID,
                ':categoriaID' => $categoriaID
            ));
            return $result;

        }

        public static function delete(int $categoriaID): bool
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $sql = ("DELETE FROM categorie WHERE categoriaID = " . $categoriaID);
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute();
            return $result;
        }

        public static function updateNoModer(ECategoria $categoria): bool
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $sql = ("UPDATE categorie SET moderatoreID = NULL WHERE categoriaID = :categoriaID");
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(array(
                ':categoriaID' => $categoria->getId()
            ));
            return $result;
        }

        public static function loadAll(): array
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $stmt = $pdo->query("SELECT * FROM categorie");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $categorie = array();
            foreach ($rows as $record){
                $cateID = (int)$record["categoriaID"];
                $categorie[] = self::load($cateID);
                /*
                $cateID = (int)$record["categoriaID"];
                $nome = $record["nomeCategoria"];
                $icona = $record["icona"];
                $descrizione = $record["descrizione"];
                $categoria = new ECategoria($cateID, $nome, $icona, $descrizione);
                $categorie[] = $categoria;
                */
            }
            return $categorie;
        }
    }