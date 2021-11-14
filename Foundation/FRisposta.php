<?php
declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";


/**
 * Classe Foundation FRisposta.
 * Gestisce tutte le operazioni CRUD e presenta anche dei metodi di ausilio per il corretto funzionamento di alcuni
 * casi d'uso in cui risultano coinvolte le Risposte.
 */

class FRisposta
{
     /**
      * Istanza della classe FRisposta, si utilizza per il singleton.
      * @var null|FRisposta
      */
     private static $instance = null;

     /**
      * Costruttore di default.
      */
     private function __construct()
     {
     }

     /**
      * Restituisce l'istanza di FRisposta. Se già esistente restituisce quella esistente, altrimenti la crea.
      * @return FRisposta Istanza di FRisposta.
      */
     public static function getInstance(): FRisposta
     {
         if(self::$instance == null){
             $classe =__CLASS__;
             self::$instance = new $classe;
         }
         return self::$instance;
     }

    /**
     * Restituisce l'oggetto ERisposta, memorizzato nel database, avente come id quello passato come parametro.
     * Per ottenere un oggetto ERisposta è necessario recuperare prima l'autore della risposta (utilizzo il metodo load di FUser).
     * Nel caso non fosse possibile recuperare l'autore, la risposta non fosse presente nella base dati o vi fossero
     * altri errori di varia natura allora viene restituito null.
     * @param int $rispostaID Identificativo della risposta da recuperare dalla base dati.
     * @return Erisposta|null Risposta recuperata dalla base dati.
     * @throws ValidationException Eccezione lanciata nel momento in cui vi sono problemi nella validazione dei dati nel
     * momento in cui viene creata l'istanza di EUser (autore del messaggio).
     */
     public function load(int $rispostaID): ?ERisposta
     {
         try {
             $dbConnection = FConnection::getInstance();
             $pdo = $dbConnection->connect();

             $sql = ("SELECT * FROM risposte WHERE rispostaID = :rispostaID");
             $stmt = $pdo->prepare($sql);
             $stmt->execute(array(
                 ':rispostaID' => $rispostaID
             ));
             $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
             if (count($rows) == 1) {
                 $record = $rows[0];
                 $data = $record["data"];
                 $testo = $record["testo"];

                 /*
                  * Recupero dell'utente autore della risposta.
                  */
                 $autoreRispID = (int)$record["autoreRispID"];
                 $fUser = FUser::getInstance();
                 $autoreRisposta = $fUser->load($autoreRispID);
                 if (!isset($autoreRisposta)) {
                     return null;
                 }

                 $risposta = new ERisposta($rispostaID, $testo, $data, $autoreRisposta);

                 return $risposta;
             } else {
                 return null;
             }
         } catch (PDOException $e){
             return null;
         }
     }

     /**
      * Permette di modificare l'autore delle risposte scritte da un utente, la modifica comporta che il nuovo autore
      * risulta essere l'utente di default. Il metodo viene richiamato nel momento in cui l'utente che aveva scritto
      * le risposte viene eliminato dalla base dati.
      * Se l'operazione va a buon fine allora viene restituito true, false altrimenti.
      * @param PDO $pdo Connessione al DBMS e alla base dati.
      * @param int $userID Identificativo dell'autore dei messaggi da modificare.
      * @return bool Esito dell'operazione.
      */
     public function updateUserID(PDO $pdo, int $userID): bool
     {
         try {
             $sql = ("UPDATE risposte SET autoreRispID = 1 WHERE autoreRispID = :userID");
             $stmt = $pdo->prepare($sql);
             $result = $stmt->execute(array(
                 ':userID' => $userID
             ));
             return $result;
         } catch (PDOException $e) {
             return false;
         }
     }

     /**
      * Permette di memorizzare una Risposta di un thread nella base dati.
      * Se l'operazione va buon fine allora viene restituito true, false altrimenti.
      * @param ERisposta $risposta Risposta da memorizzare nella base dati.
      * @param int $threadID Identificativo del thread a cui la risposta è associata.
      * @return bool Esito dell'operazione.
      */
     public function store(ERisposta $risposta, int $threadID): bool
     {
         $userID = $risposta->getAutoreRisposta()->getId();
         $testo = $risposta->getTesto();
         $dataRisposta = $risposta->getData();
         try {
             $dbConnection = FConnection::getInstance();
             $pdo = $dbConnection->connect();

             $sql = ("INSERT INTO risposte(testo, data, autoreRispID, threadRispID)
                   VALUES (:testoRisposta, :dataRisposta, :autoreRispID, :threadRispID)");
             $stmt = $pdo->prepare($sql);
             $result = $stmt->execute(array(
                 ':testoRisposta' => $testo,
                 ':dataRisposta' => $dataRisposta,
                 ':autoreRispID' => $userID,
                 ':threadRispID' => $threadID
             ));
             return $result;
         } catch (PDOException $e) {
             return false;
         }
     }

     /**
      * Permette di eliminare un Messaggio dalla base dati.
      * Se l'operazione va a buon fine allora viene restituito true, false altrimenti.
      * @param int $rispostaID Identificativo della risposta da eliminare.
      * @return bool Esito dell'operazione.
      */
     public function delete(int $rispostaID): bool
     {
         try {
             $dbConnection=FConnection::getInstance();
             $pdo=$dbConnection->connect();

             if ($this->exists($rispostaID)) {
                 /*
                 * Per evitare inconsistenza sui dati, causato dall'accesso concorrente, si procede ad eseguire
                 * l'operazione in mutua esclusione.
                 */
                 $pdo->query("SET autocommit = 0");
                 $pdo->query("LOCK TABLES risposte WRITE");

                 $sql = ("DELETE FROM risposte WHERE rispostaID = :rispostaID");
                 $stmt = $pdo->prepare($sql);
                 $result = $stmt->execute(array(
                     ':rispostaID' => $rispostaID
                 ));

                 $pdo->query("COMMIT");
                 $pdo->query("UNLOCK TABLES");

                 return $result;
             } else {
                 return false;
             }
         } catch (PDOException $e) {
             return false;
         }
     }

    /**
     * Verifica la presenza nella base dati di una risposta di cui viene fornito l'identificativo.
     * Se la risposta è presente allora viene restituito true, altrimenti false.
     * Viene restituito false anche se ci sono problemi.
     * @param int $rispostaID Identificativo della risposta di cui si deve verificare l'esistenza nella base dati.
     * @return bool Esito della ricerca.
     */
    private function exists(int $rispostaID): bool
    {
        try {
            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $sql = ("SELECT rispostaID FROM risposte WHERE rispostaID = :rispostaID");
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':rispostaID' => $rispostaID
            ));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 1) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Recupero dalla base dati di tutte le risposte associate ad un thread, del quale viene fornito l'identificativo.
     * Se ci sono problemi allora viene restituito null.
     * @param int $threadID Identificativo del thread di cui si vogliono recuperare le Risposte.
     * @return array Elenco di risposte recuperate dalla base dati.
     * @throws ValidationException Eccezione lanciata nel momento in cui vi sono problemi nella validazione dei dati nel
     * momento in cui viene creata l'istanza di EUser (autore del messaggio).
     */
    public function loadRisposteThread(int $threadID): ?array
    {
        try {
            $risposte = array();

            $dbConnection = FConnection::getInstance();
            $pdo = $dbConnection->connect();

            $sql = ('SELECT rispostaID FROM risposte WHERE threadRispID = :threadID');
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':threadID' => $threadID
            ));

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $rispostaID = (int)$row["rispostaID"];
                $risposta = $this->load($rispostaID);
                if (isset($risposta)) {
                    $risposte[] = $risposta;
                } else {
                    return null;
                }
            }
            return $risposte;
        } catch (PDOException $e) {
            return null;
        }
    }
}