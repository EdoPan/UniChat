<?php

declare(strict_types = 1);
require_once __DIR__.DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "utility.php";

/**
 * Classe Foundation di Categoria.
 * Gestisce tutte le operazioni CRUD e presenta anche dei metodi di ausilio per il corretto funzionamento di alcuni
 * casi d'uso in cui risultano coinvolte le Categorie.
 */
class FCategoria
{
    /**
     * Istanza della classe FCategoria, si utilizza per il singleton.
     * @var null|FCategoria
     */
    private static $instance = null;

    /**
     * Costruttore di default.
     */
    private function __construct() {}

    /**
     * Restituisce l'istanza di FCategoria. Se già esistente restituisce quella esistente, altrimenti la crea.
     * @return FCategoria Istanza di FCategoria.
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
     * Nel caso non fosse possibile recuperare l'icona, non vi sia una categoria associata all'identificativo o vi
     * fossero altri errori di varia natura allora viene restituito null.
     * @param int $categoriaID Identificativo della categoria da recuperare.
     * @return ECategoria|null Istanza di ECategoria recuperata dalla base dati.
     * @throws ValidationException Eccezione lanciata nel momento in cui vi sono problemi nella validazione dei dati nel
     * momento in cui viene creata l'istanza di ECategoria.
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
     * Se all'identificativo fornito in ingresso non è associata una icona o vi sono problemi di varia natura, allora
     * viene restituito null.
     * @param int $iconaID Identificativo dell'icona da recuperare dalla base dati.
     * @return array|null Icona recuperata dalla base dati.
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
     * Restituisce la Categoria a cui appartiene un Thread, del quale viene fornito in ingresso il suo identificativo.
     * Se ciò non fosse possibile o vi fossero altri errori di varia natura allora viene restituito null.
     * @param int $threadID Identificativo del thread di cui si vuole recuperare la Categoria.
     * @return ECategoria|null Istanza di ECategoria recuperata dalla base dati.
     * @throws ValidationException Eccezione lanciata nel momento in cui vi sono problemi nella validazione dei dati nel
     * momento in cui viene creata l'istanza di ECategoria.
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
     * Permette di aggiornare la Categoria presente nella base dati.
     * L'unico campo modificabile è quello relativo al moderatore che gestisce la categoria.
     * L'operazione segue diversi passi:
     * - verificare che il moderatore da assegnare non sia già stato assegnato ad un'altra categoria, in tal caso viene
     * prima rimosso da quest'ultima;
     * - verificare che la categoria da moderare non sia già moderata, in tal caso il moderatore ad essa assegnato viene
     * prima rimosso.
     * Se si cerca di rendere moderatore l'utente di default o l'admin, l'operazione viene annullata e viene restituito
     * false.
     * Se tutte le operazioni vengono completate con successo viene restituito true, false altrimenti e anche se ci sono
     * problemi di varia natura.
     * @param ECategoria $categoria Categoria da aggiornare.
     * @param EModeratore $user Moderatore da assegnare alla categoria.
     * @return bool Esito dell'operazione.
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
             * - l'oggetto moderatore fornito in ingresso assume il ruolo di moderatore (metodo updateToModeratore di FUser);
             * - rimuovere il moderatore passato, dalla categoria a cui era precedentemente assegnato;
             * - il moderatore viene assegnato ad una categoria (quella passata come parametro).
             * Inoltre, per evitare inconsistenza sui dati causata dall'accesso concorrente alle stesse risorse, le
             * operazioni sopra descritte vengono eseguite in mutua esclusione.
             */

            $pdo->query("SET autocommit = 0");
            $pdo->query("LOCK TABLES categorie WRITE, users WRITE");

            $fUser = FUser::getInstance();

            /*
             * Rimozione del vecchio moderatore dalla categoria fornita in ingresso.
             */
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

            /*
             * Aggiornamento del nuovo moderatore nella base dati
             */
            $resultUpdateUserToModeratore = $fUser->updateToModeratore($pdo, $moderatoreID);

            /*
             * Rimozione del nuovo moderatore dalla categoria da lui precedentemente gestita.
             */
            $query2 = ("UPDATE categorie SET moderatoreID = NULL WHERE moderatoreID = :moderatoreID");
            $stmt2 = $pdo->prepare($query2);
            $resultResetModeratoreCategoria = $stmt2->execute(array(
                ':moderatoreID' => $moderatoreID
            ));

            /*
             * Assegnazione del nuovo moderatore alla categoria fornita in ingresso.
             */
            $query3 = ("UPDATE categorie SET moderatoreID = :moderatoreID WHERE categoriaID = :categoriaID");
            $stmt3 = $pdo->prepare($query3);
            $resultUpdateModeratoreCategoria = $stmt3->execute(array(
                ':moderatoreID' =>  $moderatoreID,
                ':categoriaID' => $categoriaID
            ));

            if($resultUpdateToUser == true && $resultUpdateUserToModeratore == true && $resultResetModeratoreCategoria == true && $resultUpdateModeratoreCategoria == true) {
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
     * Permette di memorizzare una Categoria nella base dati.
     * Se l'operazione va buon fine allora viene restituito true, false altrimenti.
     * @param ECategoria $categoria Categoria da memorizzare nella base dati.
     * @return bool Esito dell'operazione.
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
                    ':iconaID' => $iconaCategoriaID,
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
     * Permette di memorizzare una nuova icona, ovvero l'array associativo che la rappresenta, della categoria nella
     * base dati.
     * Se l'operazione va a buon fine viene restituito l'id assegnato all'icona nella base dati, altrimenti
     * viene restituito null.
     * @param PDO $pdo Connessione alla DBMS e al database.
     * @param array $icona Icona della categoria da memorizzare.
     * @return int|null Identificativo assegnato all'icona.
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
     * Permette di eliminare una Categoria dalla base dati.
     * Quando una categoria viene eliminata dall'Admin, tutti i thread ad essa associati vengnono assegnati ad una
     * categoria di default.
     * Inoltre il moderatore che gestiva la categoria viene rimosso dalla sua carica.
     * Restituisce true se l'operazione va a buon fine, false altrimenti.
     * Viene restituito false anche se si tenta di rimuovere la categoria di default o se l'identificativo della
     * categoria non esiste.
     * @param int $categoriaID Identificativo della categoria da rimuovere.
     * @return bool Esito dell'operazione.
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
             * all'altra. Le operazioni
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

            /*
             * Rimozione dell'icona.
             */
            $resultDeleteIconaCategoria=true;
            if($iconaCategoriaID !=1) {
                $resultDeleteIconaCategoria=$this-> deleteIconaCategoria($pdo, $iconaCategoriaID);
            }

            /*
             * Aggiornamento dei thread, i quali vengono assegnati alla categoria di default.
             */
            $fThread=FThread::getInstance();
            $resultUpdateCategoriaThread =$fThread->updateCategoriaID($pdo, $categoriaID);

            /*
             * Aggiornamento del moderatore ad utente normale.
             */
            $fUser = FUser::getInstance();
            $resultUpdateModeratoreToUser = $fUser->updateToUser($pdo, $moderatoreID);

            /*
             * Rimozione della categoria.
             */
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
     * @param PDO $pdo Connessione al DBMS e al database.
     * @param int $iconaID Identificativo dell'icona da rimuovere dalla base dati
     * @return bool Esito dell'operazione.
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
     * Aggiorna la Categoria memorizzata nella base dati, in particolare rimuove il moderatore che la gestisce.
     * Inoltre viene aggiornato il Moderatore che viene reso un utente normale.
     * Se l'operazione va a buon fine allora viene restituito true, false altrimenti.
     * @param int $categoriaID Identificativo della categoria da aggiornare.
     * @param EModeratore $moderatore Moderatore da rimuovere dal suo ruolo.
     * @return bool Esito dell'operazione.
     */
    public function rimuoviModeratore(int $categoriaID, EModeratore $moderatore): bool
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo=$dbConnection->connect();

            /*
             * La rimozione del moderatore dalla categoria richiede una serie di operazioni che devono essere eseguite
             * una di seguito all'altra. Le operazioni avranno effetto sulla base dati solo se tutte avranno esito positivo.
             * Tali operazioni sono:
             * - aggiornamento del moderatore;
             * - aggiornamento della categoria;
             * Inoltre, per evitare inconsistenza sui dati causata dall'accesso concorrente alle stesse risorse, le
             * operazioni sopra descritte vengono eseguite in mutua esclusione.
             */
            $pdo->query("SET autocommit = 0");
            $pdo->query("LOCK TABLES categorie WRITE, users WRITE");

            /*
             * Aggiornamento del moderatore
             */
            $fUser = FUser::getInstance();
            $resultUpdateModeratoreToUser = $fUser->updateToUser($pdo, $moderatore->getId());

            /*
             * Aggiornamento della Categoria.
             */
            $sql = ("UPDATE categorie SET moderatoreID = NULL WHERE categoriaID = :categoriaID");
            $stmt = $pdo->prepare($sql);
            $resultUpdateModeratore = $stmt->execute(array(
                ':categoriaID' => $categoriaID
            ));

            if ($resultUpdateModeratoreToUser== true && $resultUpdateModeratore == true) {
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
     * Permette di ottenere un certo numero di Categorie specificando da quale riga dell tabella della base dati partire
     * (riga di partenza esclusa) e il numero di righe da visualizzare.
     * Viene restituito un array di categorie, eventualmente vuoto, se l'operazione va a buon fine,
     * null altrimenti.
     * @param int $rigaPartenza Valore che indica da quale record iniziare il recupero.
     * @param int $numeroRighe Valore che indica quanti record recuperare.
     * @return array|null Elenco contenente le categorie recuperate.
     * @throws ValidationException Eccezione lanciata nel caso ci fossero problemi di validazione dei dati al momento
     * della creazione delle istanze di ECategoria.
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
     * Permette di recuperare tutte le Categorie presenti nella base dati.
     * Se ci sono dei problemi allora viene restituito null.
     * @return array Elenco contenente le Categorie recuperate.
     * @throws ValidationException Eccezione lanciata nel caso ci fossero problemi di validazione dei dati al momento
     * della creazione delle istanze di ECategoria.
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
     * Restituisce il numero di categorie attualmente presenti nella base dati.
     * In caso di errori viene restituito null.
     * @return int|null Numero di categorie memorizzate nella base dati.
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