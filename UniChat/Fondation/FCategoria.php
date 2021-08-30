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
        public static function getInstance(): FCategoria
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
        public function loadAll(): ?array
        {

            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();

                $stmt = $pdo->query("SELECT * FROM categorie");
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $categorie = array();
                foreach ($rows as $record){
                    $cateID = (int)$record["categoriaID"];
                    $categorie[] = self::load($cateID);

                }
                return $categorie;
            } catch (PDOException $e) {
                return null;
            }

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



                /*
                * Gestione delle operazioni di store in base alla icona della categoria.
                */


                if ($icona["id"] != 1) {

                    /*
                     * L'admin ha caricato un'icona diversa da quella di default. Vengono eseguite delle operazioni
                     * in maniera consecutiva, ma solo se queste avvengono tutte correttamente allora viene modificata la
                     * base dati.
                     * Tali operazioni sono:
                     * - memorizzazione della nuova icona;
                     * - memorizzazione della nuova categoria.
                     */

                    $pdo->beginTransaction();
                    $iconaCategoriaID = $this->storeIcona($pdo, $icona);
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
         * Permette di rimuovere l'icona di una categoria dalla base dati.
         * Restituisce true se l'operazione va buon fine, false altrimenti.
         * @param PDO $pdo
         * @param int $iconaID
         * @return bool
         */
        private function deleteIconaCategoria(PDO $pdo, int $iconaID): bool
        {
            try {
                $sql = "DELETE FROM icona WHERE iconaID = :iconaID";
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute(array(
                    ':iconaID' => $iconaID
                ));
                return $result;
            } catch (PDOException $e) {
                return false;
            }
        }

        /**
         * Eliminazione di una categoria da DB.
         * Quando una categoria viene eliminata dall'Admin, tutti i thread ad essa associati "passano" ad una categoria di default.
         * Inoltre il moderatore viene rimosso dalla sua carica e torna ad essere un "semplice" User.
         * Restituisce true se l'operazione va a buon fine, false altrimenti.
         * @param int $categoriaID
         * @param EModeratore $moderatore
         * @return bool
         */

        public function delete(int $categoriaID, EModeratore $moderatore): bool
        {

            try {
                $dbConnection = FConnection::getInstance();
                $pdo=$dbConnection->connect();

                $query = ("SELECT iconaID FROM categorie WHERE categoriaID=:categoriaID");
                $s=$pdo->prepare($query);
                $s->execute(array(
                    ':categoriaID' => $categoriaID
                ));

                $rows = $s->fetchAll(PDO::FETCH_ASSOC);
                $iconaCategoriaID=(int)$rows[0]["iconaID"];

             /*
             * La rimozione della categoria richiede una serie di operazioni che devono essere eseguite una di seguito
             * all'altra e con mutua esclusione sulle tabelle della base dati che ne sono coinvolte. Le operazioni
             * avranno effetto sulla base dati solo se tutte avranno esito positivo.
             * Tali operazioni sono:
             * - rimozione dell'icona (solo se questa non è quella di default);
             * - aggiornamento dei thread appartenenti a tale categoria con l'identificativo della categoria di default;
             * - rimozione dell'utente da ruolo di moderatore;
             * - rimozione della categoria.
             */

                $pdo->query("SET autocommit = 0");
                $pdo->query("LOCK TABLES categorie WRITE, threads WRITE, users WRITE");

                $resultDeleteIconaCategoria=true;
                if($iconaCategoriaID !=1) {

                    $resultDeleteIconaCategoria=$this-> deleteIconaCategoria($pdo, $iconaCategoriaID);
                }

                $fThread=FThread::getInstance();

                //updateCategoriaID() -> metodo di FThread che aggiorna tutti i catThreadID dei thread di questa categoriaID con 1
                $resultUpdateCatThread =$fThread->updateCategoriaID($pdo, $categoriaID);


                $resultUpdModeratore=self::rimuoviModeratore($categoriaID, $moderatore);


                $sql = "DELETE FROM categorie WHERE categoriaID = $categoriaID";

                $stmt =  $pdo->prepare($sql);

                $resultDeleteCategoria=$stmt->execute(array(
                    ':$categoriaID'=>$categoriaID
                ));

                if ($resultDeleteIconaCategoria == true && $resultUpdateCatThread == true && $resultUpdModeratore == true && $resultDeleteCategoria == true) {
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
         * Rimozione di un moderatore dalla categoria e rimozione dell'utente dal ruolo di moderatore.
         * @param ECategoria $categoria
         * @param EModeratore $moderatore
         * @return bool
         */

        public function rimuoviModeratore(int $categoriaID, EModeratore $moderatore): bool
        {
            try {

                $dbConnection = FConnection::getInstance();
                $pdo=$dbConnection->connect();

                $sql = ("UPDATE categorie SET moderatoreID = NULL WHERE categoriaID = $categoriaID");

                $fUser=FUser::getInstance();

                $resultUpdtoUser=$fUser->updateToUser($pdo, $moderatore);

                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute(array(
                    ':categoriaID' => $categoriaID
                ));

                if ($resultUpdtoUser== true && $result == true) {

                    return true;
                } else {

                    return false;
                }


            } catch (PDOException $e) {
                return false;
            }



        }


    }