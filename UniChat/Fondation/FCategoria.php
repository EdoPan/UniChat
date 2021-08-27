<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

/**
 * Classe Foundation di Categoria. Conosce la tabella "Categorie".
 */


    class FCategoria
    {

        /**
         * Instanza della classe FCategoria, si utilizza per il singleton.
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
         * Restituisce l'instanza di FCategoria. Se già esistente restituisce quella esistente, altrimenti la crea.
         * @return FCategoria
         */
        public static function getInstance(): FUser
        {
            if(self::$instance == null){
                $classe =__CLASS__;
                self::$instance = new $classe;
            }
            return self::$instance;
        }

        /**
         * Caricamento di tutte le categorie dal DB all'interno di un array.
         * @return array
         */
        public function loadAll(): array
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
         * Recupero icona categoria.
         * @param int $iconaID
         * @return array|null
         */

        private function loadIcona (int $iconaID): ?array
        {

            try {
                $dbconnection=FConnection::getInstance();
                $pdo=$dbconnection->connect;

                $pdo=new PDO ("mysql:host=localhost, dbname=testing", "root", "pippo");
                $stmt=$pdo->query("SELECT * FROM icona WHERE iconaID=".$iconaID);
                $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($rows)==1) {
                    $record=$rows[0];
                    $icona=array(
                        "id"=>(int)$record["iconaID"],
                        "nome"=>$record["nome"],
                        "dimensione"=>$record["dimensione"],
                        "tipo"=>$record["tipo"],
                        "immagine"=>$record["immagine"] );

                    return $icona;
                }
                else {
                    return null;
                }
            } catch (PDOException $e) {
                return null;
            }


        }

        /**
         * Recupero di un oggetto di tipo ECategoria dal DB.
         * @param int $categoriaID
         * @return ECategoria|null
         */

        public function load(int $categoriaID): ?ECategoria
        {
            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();
                $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

                $stmt = $pdo->query("SELECT * FROM categorie WHERE categoriaID = " . $categoriaID);

                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($rows) == 1) {
                    $record = $rows[0];
                    $cateID = (int)$record["categoriaID"];
                    $nome = $record["nomeCategoria"];

                    /*
                    * Recupero icona della categoria
                    */
                    $idIcona = $record["iconaID"];
                    $icona = $this->loadIcona($idIcona);
                    if (!isset($icona)) {
                        return null;
                    }

                    $descrizione = $record["descrizione"];
                    $categoria = new ECategoria($cateID, $nome, $icona, $descrizione);
                    return $categoria;
                } else {
                    return null;
                }
            } catch (PDOException $e) {
                return null;

            }

        }

        /**
         * Recupero di una categoria di un Thread dal proprio ID.
         * @param int $threadID
         * @return ECategoria|null
         */

        public function loadCategoriaThread(int $threadID): ?ECategoria
        {
            try {
                $dbConnection = FConnection::getInstance();
                $pdo=$dbConnection->connect();
                $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
                $stmt = $pdo->query("SELECT * FROM categorie, threads WHERE catThreadID = categoriaID AND threadID = " . $threadID);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($rows) == 1) {
                    $record = $rows[0];
                    $cateID = (int)$record["categoriaID"];
                    $nome = $record["nomeCategoria"];


                    /*
                    * Recupero icona della categoria
                    */
                    $idIcona = $record["iconaID"];
                    $icona = $this->loadIcona($idIcona);
                    if (!isset($icona)) {
                        return null;
                    }


                    $descrizione = $record["descrizione"];
                    $categoria = new ECategoria($cateID, $nome, $icona, $descrizione);
                    return $categoria;
                } else {
                    return null;
                }
            } catch (PDOException $e) {
                return null;
            }

        }

        /**
         * Scrittura in DB di un oggetto di tipo Categoria.
         * @param ECategoria $categoria
         * @return bool
         */

        public function store(ECategoria $categoria): bool
        {
            $categoriaID = $categoria->getId();
            $nome = $categoria->getNome();
            $icona = $categoria->getIcona();
            $descrizione = $categoria->getDescrizione();


            try {

                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();
                $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");


                /*
                * Gestione delle operazioni di store in base alla icona della categoria.
                */


                if ($iconaCat["id"] != 1) {

                    /*
                     * L'admin ha caricato un'icona diversa da quella di default. Vengono eseguite delle operazioni
                     * in maniera consecutiva, ma solo se queste avvengono tutte correttamente allora viene modificata la
                     * base dati.
                     * Tali operazioni sono:
                     * - memorizzazione della nuova icona;
                     * - memorizzazione della nuova categoria.
                     */

                    $pdo->beginTransaction();
                    $iconaCategoriaID = $this->storeIcona($pdo, $iconaCat);
                    if (!isset($iconaCategoriaID)) {
                        return false;
                    }

                    $sql = ("INSERT INTO categorie(categoriaID, nome, iconaID, descrizione)
                    VALUES (:categoriaID, :nome, :iconaID, :descrizione)");
                    $stmt = $pdo->prepare($sql);
                    $result = $stmt->execute(array(
                        ':categoriaID' =>  $categoriaID,
                        ':nome' => $nome,
                        ':icona' => $icona,
                        ':descrizione' => $descrizione
                    ));

                    if($result){
                        $pdo->commit();
                    } else {
                        $pdo->rollBack();
                    }
                } else {

                    /*
                     * L'admin ha impostato l'icona di default. Deve essere seguita la sola operazione di
                     * memorizzazione della categoria e in particolare viene posto ad 1 il campo iconaCategoriaID.
                     */
                    $sql = ("INSERT INTO categoria(categoriaID, nome, iconaID, descrizione)
                    VALUES (:categoriaID, :nome, :iconaID, :descrione)");
                    $stmt = $pdo->prepare($sql);
                    $result = $stmt->execute(array(
                        ':categoriaID' =>  $categoriaID,
                        ':nome' => $nome,
                        ':iconaID' => 1,
                        ':descrizione' => $descrizione
                    ));
                }
                return $result;
            } catch (PDOException $e){
                return false;
            }

        }


         /**
          * Permette di memorizzare nella base dati una nuova icona della categoria.
          * Se l'operazione va a buon fine viene restituito l'id assegnato all'icona nella base dati, altrimenti
          * viene restituito null.
          * @param PDO $pdo
          * @param array $iconaCat
          * @return int|null
          */
     private function storeIcona(PDO $pdo, array $iconaCat): ?int{
            $nome = $iconaCat["nome"];
            $dimensione = $iconaCat["dimensione"];
            $tipo = $iconaCat["tipo"];
            $immagine = $iconaCat["immagine"];

            try {
                $query = $pdo->query("INSERT INTO icona(nome, dimensione, tipo, immagine) VALUES ('$nome','$dimensione','$tipo','$immagine')");
                $iconaCategoriaID = (int)$pdo->lastInsertId();
                return $iconaCategoriaID;
            } catch (PDOException $e) {
                return null;
            }
        }

        /**
         * Assegnazione di un moderatore ad una categoria.
         * @param ECategoria|null $categoria
         * @param EModeratore $mod
         * @return bool
         */
        public function update(?ECategoria $categoria, EModeratore $mod): bool
        {
            $categoriaID = $categoria->getId();
            $moderatoreID = $mod->getId();

            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();

                $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
                $sql = ("UPDATE categorie SET moderatoreID = :moderatoreID WHERE categoriaID = :categoriaID");

                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute(array(
                    ':moderatoreID' =>  $moderatoreID,
                    ':categoriaID' => $categoriaID
                ));
                return $result;

            } catch (PDOException $e) {
                return false;
            }

        }

        /**
         * >Eliminazione di una categoria da DB.
         * @param int $categoriaID
         * @return bool
         */

        public function delete(int $categoriaID): bool
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

        public function getLastID(): int
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

        public function rimuoviModeratore(ECategoria $categoria): bool
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