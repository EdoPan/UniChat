<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

/**
 * Classe Foundation di Categoria.
 */
class FCategoria
{
    /**
     * Istanza della classe FCategoria, si utilizza per il singleton.
     * @var null|FCategoria
     */
    private static $instance = null;

    /**
     * Costruttore di default
     */
    private function __construct() {}

    /**
     * Restituisce l'istanza di FCategoria. Se già esistente restituisce quella esistente, altrimenti la crea.
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
     * Restituisce l'oggetto ECategoria, memorizzato nel database, avente come id quello passato come parametro.
     * Per ottenere un oggetto ECategoria è necessario recuperare prima l'icona (utilizzo il metodo loadIcona).
     * Nel caso non fosse possibile o vi fossero altri errori di varia natura allora viene restituito null.
     * @param int $categoriaID
     * @return ECategoria|null
     * @throws ValidationException
     */
    public function load(int $categoriaID): ?ECategoria
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $sql = ("SELECT * FROM categorie WHERE categoriaID = :categoriaID");
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':categoriaID' => $categoriaID
            ));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                $record = $rows[0];
                $nome = $record["nome"];
                $descrizione = $record["descrizione"];

                /*
                 * Recupero icona della categoria
                 */
                $iconaID = (int)$record["iconaID"];
                $icona = $this->loadIcona($iconaID);
                if (!isset($icona)) {
                    return null;
                }

                $categoria = new ECategoria($categoriaID, $nome, $icona, $descrizione);
                return $categoria;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Restituisce l'array contenente le informazioni riguardante l'icona di una categoria, memorizzata nel database,
     * avente come id quello passato come parametro.
     * L'array restituito, se il recupero avviene con successo, conterrà:
     * - id icona
     * - nome icona
     * - dimensione icona
     * - tipo file icona
     * - immagine
     * In caso contrario il metodo restituisce null.
     * @param int $iconaID
     * @return array|null
     */
    private function loadIcona (int $iconaID): ?array
    {
        try {
            $dbConnection=FConnection::getInstance();
            $pdo=$dbConnection->connect();

            $sql = ("SELECT * FROM icone WHERE iconaID = :iconaID");
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':iconaID' => $iconaID
            ));
            $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows)==1) {
                $record=$rows[0];
                $icona=array(
                    "id"=>$iconaID,
                    "nome"=>$record["nome"],
                    "dimensione"=>$record["dimensione"],
                    "tipo"=>$record["tipo"],
                    "immagine"=>$record["immagine"] );
                return $icona;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Restituisce l'oggetto ECategoria, memorizzato nel database, a partire dal thread avente come id quello passato come parametro.
     * Per il recupero dell'oggetto ECategoria faccio uso del metodo load.
     * Se ciò non fosse possibile o vi fossero altri errori di varia natura allora viene restituito null.
     * @param int $threadID
     * @return ECategoria|null
     * @throws ValidationException
     */
    public function loadCategoriaThread(int $threadID): ?ECategoria
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo=$dbConnection->connect();

            $sql = ("SELECT categoriaID FROM categorie, threads WHERE catThreadID = categoriaID AND threadID = :threadID");
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':threadID' => $threadID
            ));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                $record = $rows[0];
                $categoriaID = (int)$record["categoriaID"];
                $categoria = $this->load($categoriaID);
                return $categoria;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Assegnazione di un moderatore ad una categoria.
     * L'operazione segue diversi passi che consistono nel verificare che il moderatore da assegnare non sia già stato
     * assegnato ad un'altra categoria, in tal caso viene prima rimosso da quest'ultima, e nel verificare che la
     * categoria da moderare non sia già moderata, in tal caso il moderatore ad essa assegnato viene prima rimosso.
     * @param ECategoria $categoria
     * @param EModeratore $user
     * @return bool
     */
    public function update(ECategoria $categoria, EModeratore $user): bool
    {
        $categoriaID = $categoria->getId();
        $moderatoreID = $user->getId();

        if ($moderatoreID == 1 || $moderatoreID == 2) {
            return false;
        }

        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            /*
             * L'assegnazione di un moderatore ad una categoria nella base dati richiede che vengano compiute una serie di
             * operazioni da eseguire una di seguito all'altra, ma che devono apportare modifiche alla base dati
             * solo se sono avvenute tutte con successo.
             * Tali operazioni sono:
             * - verifica che la categoria designata non sia già moderata, in tal caso viene reso user il suo moderatore
             * (metodo updateToUser di FUser);
             * - l'oggetto di tipo user assume il ruolo di moderatore (metodo updateToModeratore di FUser);
             * - rimuovere il moderatore passato, dalla categoria a cui era precedentemente assegnato;
             * - il moderatore viene assegnato ad una categoria (quella passata come parametro).
             * Inoltre, per evitare inconsistenza sui dati causata dall'accesso concorrente alle stesse risorse, le
             * operazioni sopra descritte vengono eseguite in mutua esclusione.
             */

            //$pdo->beginTransaction();
            $pdo->query("SET autocommit = 0");
            $pdo->query("LOCK TABLES categorie WRITE, users WRITE");

            $fUser = FUser::getInstance();

            $resultUpdateToUser = true;

            $query1 = ("SELECT moderatoreID FROM categorie WHERE categoriaID = :categoriaID");
            $stmt1 = $pdo->prepare($query1);
            $stmt1->execute(array(
                ':categoriaID' => $categoriaID
            ));
            $rows1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows1) == 1) {
                $modID = (int)$rows1[0]['moderatoreID'];
                if ($modID != 0) {
                    $resultUpdateToUser = $fUser->updateToUser($pdo, $modID);
                }
            } else {
                $resultUpdateToUser = false;
            }

            $resultUpdateUserToModeratore = $fUser->updateToModeratore($pdo, $moderatoreID);

            $query2 = ("UPDATE categorie SET moderatoreID = NULL WHERE moderatoreID = :moderatoreID");
            $stmt2 = $pdo->prepare($query2);
            $resultResetModeratoreCategoria = $stmt2->execute(array(
                ':moderatoreID' => $moderatoreID
            ));

            $query3 = ("UPDATE categorie SET moderatoreID = :moderatoreID WHERE categoriaID = :categoriaID");
            $stmt3 = $pdo->prepare($query3);
            $resultUpdateModeratoreCategoria = $stmt3->execute(array(
                ':moderatoreID' =>  $moderatoreID,
                ':categoriaID' => $categoriaID
            ));

            if($resultUpdateToUser == true && $resultUpdateUserToModeratore == true && $resultResetModeratoreCategoria == true && $resultUpdateModeratoreCategoria == true) {
                //$pdo->commit();
                $pdo->query("COMMIT");
                $pdo->query("UNLOCK TABLES");
                return true;
            } else {
                //$pdo->rollBack();
                $pdo->query("ROLLBACK");
                $pdo->query("UNLOCK TABLES");
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Scrittura in DB di un oggetto di tipo ECategoria.
     * Se l'operazione va buon fine allora viene restituito true, false altrimenti.
     * @param ECategoria $categoria
     * @return bool
     */
    public function store(ECategoria $categoria): bool
    {
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
                    $pdo->rollBack();
                    return false;
                }

                $sql = ("INSERT INTO categorie(nome, iconaID, descrizione)
                        VALUES (:nome, :iconaID, :descrizione)");
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute(array(
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
                 * memorizzazione della categoria e in particolare viene posto ad 1 il campo iconaID.
                 */
                $sql = ("INSERT INTO categorie(nome, iconaID, descrizione)
                        VALUES (:nome, :iconaID, :descrizione)");
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute(array(
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
     * @param array $icona
     * @return int|null
     */
    private function storeIcona(PDO $pdo, array $icona): ?int{
        $nome = $icona["nome"];
        $dimensione = $icona["dimensione"];
        $tipo = $icona["tipo"];
        $immagine = $icona["immagine"];

        try {
            $query = $pdo->query("INSERT INTO icone(nome, dimensione, tipo, immagine) VALUES ('$nome','$dimensione','$tipo','$immagine')");
            $iconaCategoriaID = (int)$pdo->lastInsertId();
            return $iconaCategoriaID;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Eliminazione di una categoria da DB.
     * Quando una categoria viene eliminata dall'Admin, tutti i thread ad essa associati "passano" ad una categoria di default.
     * Inoltre il moderatore viene rimosso dalla sua carica e torna ad essere un "semplice" User.
     * Restituisce true se l'operazione va a buon fine, false altrimenti.
     * @param int $categoriaID
     * @return bool
     */
    public function delete(int $categoriaID): bool
    {

        if ($categoriaID == 1) {
            return false;
        }

        try {
            $dbConnection = FConnection::getInstance();
            $pdo=$dbConnection->connect();

            $sql = ("SELECT iconaID, moderatoreID FROM categorie WHERE categoriaID=:categoriaID");
            $s=$pdo->prepare($sql);
            $s->execute(array(
                ':categoriaID' => $categoriaID
            ));

            $rows = $s->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                $iconaCategoriaID = (int)$rows[0]["iconaID"];
                $moderatoreID = (int)$rows[0]["moderatoreID"];
            } else {
                return false;
            }

            /*
             * La rimozione della categoria richiede una serie di operazioni che devono essere eseguite una di seguito
             * all'altra e con mutua esclusione sulle tabelle della base dati che ne sono coinvolte. Le operazioni
             * avranno effetto sulla base dati solo se tutte avranno esito positivo.
             * Tali operazioni sono:
             * - rimozione dell'icona (solo se questa non è quella di default);
             * - aggiornamento dei thread appartenenti a tale categoria con l'identificativo della categoria di default;
             * - rimozione dell'utente da ruolo di moderatore;
             * - rimozione della categoria.
             * Inoltre, per evitare inconsistenza sui dati causata dall'accesso concorrente alle stesse risorse, le
             * operazioni sopra descritte vengono eseguite in mutua esclusione.
             */
            $pdo->query("SET autocommit = 0");
            $pdo->query("LOCK TABLES categorie WRITE, threads WRITE, users WRITE, icone WRITE");

            $resultDeleteIconaCategoria=true;
            if($iconaCategoriaID !=1) {
                $resultDeleteIconaCategoria=$this-> deleteIconaCategoria($pdo, $iconaCategoriaID);
            }

            $fThread=FThread::getInstance();
            $resultUpdateCategoriaThread =$fThread->updateCategoriaID($pdo, $categoriaID);

            $fUser = FUser::getInstance();
            $resultUpdateModeratoreToUser = $fUser->updateToUser($pdo, $moderatoreID);

            $query = "DELETE FROM categorie WHERE categoriaID = :categoriaID";
            $stmt =  $pdo->prepare($query);
            $resultDeleteCategoria=$stmt->execute(array(
                ':categoriaID'=>$categoriaID
            ));

            if ($resultDeleteIconaCategoria == true && $resultUpdateCategoriaThread == true && $resultUpdateModeratoreToUser == true && $resultDeleteCategoria == true) {
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
     * Permette di rimuovere l'icona di una categoria dalla base dati.
     * Restituisce true se l'operazione va buon fine, false altrimenti.
     * @param PDO $pdo
     * @param int $iconaID
     * @return bool
     */
    private function deleteIconaCategoria(PDO $pdo, int $iconaID): bool
    {
        try {
            $sql = "DELETE FROM icone WHERE iconaID = :iconaID";
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
     * Rimozione di un moderatore dalla categoria e rimozione dell'utente dal ruolo di moderatore.
     * @param int $categoriaID
     * @param EModeratore $moderatore
     * @return bool
     */
    public function rimuoviModeratore(int $categoriaID, EModeratore $moderatore): bool
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo=$dbConnection->connect();

            //$pdo->beginTransaction();
            $pdo->query("SET autocommit = 0");
            $pdo->query("LOCK TABLES categorie WRITE, users WRITE");

            $fUser = FUser::getInstance();
            $resultUpdateModeratoreToUser = $fUser->updateToUser($pdo, $moderatore->getId());

            $sql = ("UPDATE categorie SET moderatoreID = NULL WHERE categoriaID = :categoriaID");
            $stmt = $pdo->prepare($sql);
            $resultUpdateModeratore = $stmt->execute(array(
                ':categoriaID' => $categoriaID
            ));

            if ($resultUpdateModeratoreToUser== true && $resultUpdateModeratore == true) {
                //$pdo->commit();
                $pdo->query("COMMIT");
                $pdo->query("UNLOCK TABLES");
                return true;
            } else {
                //$pdo->rollBack();
                $pdo->query("ROLLBACK");
                $pdo->query("UNLOCK TABLES");
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Caricamento di un certo numero di categorie dal DB all'interno di un array.
     * @param int $rigaPartenza
     * @param int $numeroRighe
     * @return array
     * @throws ValidationException
     */
    public function loadAll(int $rigaPartenza, int $numeroRighe): ?array
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $stmt = $pdo->query("SELECT categoriaID FROM categorie ORDER BY nome LIMIT " . $rigaPartenza . ", " . $numeroRighe);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $categorie = array();
            foreach ($rows as $record){
                $categoriaID = (int)$record["categoriaID"];
                $categoria = $this->load($categoriaID);
                if (isset($categoria)) {
                    $categorie[] = $categoria;
                } else {
                    return null;
                }
            }
            return $categorie;
        } catch (PDOException $e) {
            return null;
        }
    }


    /**
     * Caricamento di tutte le categorie dal DB all'interno di un array.
     * @param int $rigaPartenza
     * @param int $numeroRighe
     * @return array
     * @throws ValidationException
     */
    public function loadAllSenzaPaginazione(): ?array
    {
        $categorie = array();
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $stmt = $pdo->query("SELECT categoriaID FROM categorie ORDER BY categoriaID");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $categoriaID =(int)$row["categoriaID"];
                $categoria = $this->load($categoriaID);
                if (isset($categoria)) {
                    $categorie[] = $categoria;
                } else {
                    return null;
                }
            }
            return $categorie;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Restituisce il numero di categorie attualmente presenti nella bse dati.
     * In caso di errori viene restituito null.
     * @return int|null Numero di categorie nella base dati.
     */
    public function conta(): ?int
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $stmt = $pdo->query("SELECT count(*) as numeroCategorie FROM categorie");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(count($rows) == 1){
                return (int)$rows[0]['numeroCategorie'];
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return null;
        }
    }
}