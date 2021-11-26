<?php
declare(strict_types = 1);

/**
 * Classe utilizzata per gestire la connessione alla base dati.
 */
class FConnection
{
    /**
     * Indirizzo IP o nome dell'host in cui risiede il DBMS.
     * @var string
     */
    private static $host = "localhost";

    /**
     * Nome dell'utente con cui effettuare la connessione al DBMS.
     * @var string
     */
    private static $user = "root";

    /**
     * Password dell'utente con cui effettuare la connessione al DBMS.
     * @var string
     */
    private static $password = "";  

    /**
     * Nome del database che si vuole utilizzare.
     * @var string
     */
    private static $database = "unichat";

    /**
     * Istanza della classe FConnection, si utilizza per il singleton.
     * @var null|FConnection
     */
    private static $instance = null;

    /**
     * Costruttore di default.
     */
    private function __construct(){}


    /**
     * Restituisce l'istanza di FConnection. Se già esistente restituisce quella esistente, altrimenti la crea.
     * @return FConnection Istanza di FConnection.
     */
    public static function getInstance(): FConnection
    {
        $classe = __CLASS__;
        if(self::$instance == null){
            self::$instance = new $classe;
        }
        return self::$instance;
    }

    /**
     * Restituisce la connessione con il DBMS dopo aver impostato la gestione degli errori mediante eccezioni.
     * @return PDO Connessione al DBMS e alla base dati.
     */
    public function connect(): PDO
    {
        $pdo = new PDO ("mysql:host=" . self::$host . ";dbname=" . self::$database, self::$user, self::$password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}