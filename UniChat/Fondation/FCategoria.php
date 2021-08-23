<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

/**
 * Classe Foundation di Categoria. Conosce la tabella "Categorie".
 */


    class FCategoria
    {

        /**
         * Caricamento di tutte le categorie dal DB all'interno di un array.
         * @return array
         */
        public static function loadAll(): array
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $stmt = $pdo->query("SELECT * FROM categorie");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $categorie = array();
            foreach ($rows as $record){
                $cateID = (int)$record["categoriaID"];
                $categorie[] = self::load($cateID);

            }
            return $categorie;
        }

        /**
         * Recupero di un oggetto di tipo ECategoria dal DB.
         * @param int $categoriaID
         * @return ECategoria|null
         */

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

        /**
         * Recupero icona categoria.
         * @param int $iconaID
         * @return array|null
         */

        public static function loadIcona (int $iconaID): ?array
        {

            $pdo=new PDO ("mysql:host=localhost, dbname=testing", "root", "pippo");
            $stmt=$pdo->query("SELECT * FROM categorie AS c, icona AS i WHERE c.iconaID=i.iconaID AND i.iconaID=".$iconaID);
            $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows)==1) {
                $record=$rows[0];
                $icona=array();
                $icona[0]=(int)$record["iconaID"];
                $icona[1]=$record["nome"];
                $icona[2]=$record["dimensione"];
                $icona[3]=$record["tipo"];
                $icona[4]=["immagine"];

                return $icona;
            }
            else {
                return null;
            }
        }

        /**
         * Recupero di una categoria di un Thread dal proprio ID.
         * @param int $threadID
         * @return ECategoria|null
         */

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

        /**
         * Scrittura in DB di un oggetto di tipo Categoria.
         * @param ECategoria $categoria
         * @return bool
         */

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

        /**
         * Assegnazione di un moderatore ad una categoria.
         * @param ECategoria|null $categoria
         * @param EModeratore $mod
         * @return bool
         */
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

        /**
         * >Eliminazione di una categoria da DB.
         * @param int $categoriaID
         * @return bool
         */

        public static function delete(int $categoriaID): bool
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $sql = ("DELETE FROM categorie WHERE categoriaID = " . $categoriaID);
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute();
            return $result;
        }

        /**
         * Recupero dell'ID dell'ultima categoria inserita.
         * @return int
         */

        public static function getLastID(): int
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $stmt = $pdo->query("SELECT MAX(categoriaID) AS id FROM categorie");
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return (int)$row[0]["id"];
        }

        /**
         * Rimozione di un moderatore dalla categoria.
         * @param ECategoria $categoria
         * @return bool
         */

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


    }