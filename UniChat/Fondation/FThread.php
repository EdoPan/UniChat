<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class FThread
    {

        private static $instance = null;

        private function __construct()
        {
        }

        public static function getInstance(): FThread
        {
            if(self::$instance == null){
                $classe = __CLASS__;
                self::$instance = new $classe;
            }
            return self::$instance;
        }

        public function load(int $threadID): ?EThread
        {
            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();
                $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

                $stmt = $pdo->query("SELECT * FROM threads WHERE threadID = " . $threadID);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($rows) == 1) {
                    $row = $rows[0];
                    $titolo = $row["titolo"];
                    $testo = $row["testo"];
                    $data = $row["data"];

                    $autoreThreadID = $row["autoreThreadID"];
                    $fUser = FUser::getInstance();
                    $autoreThread = $fUser->load($autoreThreadID);

                    $catThreadID = $row["catThreadID"];
                    $fCategoria = FCategoria::getInstance();
                    $categoriaThread = $fCategoria->load($catThreadID);

                    $valutazioneThreadID = $row["valutazioneThreadID"];
                    $fValutazione = FValutazione::getInstance();
                    $valutazioneThread = $fValutazione->load($valutazioneThreadID);

                    $allegatiThread = $this->loadAllegatiByThreadID($threadID);
                    if (!isset($allegatiThread)){
                        return null;
                    }

                    $fRisposte = FRisposta::getInstance();
                    $riposteThread = $fRisposte->loadRisposteThread($threadID);

                    $thread = new EThread($threadID, $titolo, $testo, $data, $allegatiThread, $autoreThread,
                        $categoriaThread, $valutazioneThread, $riposteThread);
                    return $thread;
                } else {
                    return null;
                }
            } catch (PDOException $e) {
                return null;
            }
        }

        private function loadAllegatiByThreadID(int $threadID): ?array
        {
            try {
                $allegati = array();

                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();
                $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

                $stmt = $pdo->query("SELECT * FROM allegati WHERE threadID = " . $threadID);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    $allegatoID = $row["allegatoID"];
                    $nome = $row["nome"];
                    $dimensione = $row["dimensione"];
                    $tipo = $row["tipo"];
                    $file = $row["file"];
                    $fotoProfilo = array(
                        "id" => $allegatoID,
                        "nome" => $nome,
                        "dimensione" => $dimensione,
                        "tipo" => $tipo,
                        "file" => $file
                    );
                    $allegati[] = $fotoProfilo;
                }
                return $allegati;
            } catch (PDOException $e) {
                return null;
            }
        }

        public function loadThreadPiuDiscussoPerCategoria(int $categoriaID): ?EThread
        {
            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();
                $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

                $stmt = $pdo->query("SELECT threadID, COUNT(*) AS numRisposte FROM threads, risposte 
                                    WHERE threadRispID = threadID AND catThreadID = " . $categoriaID .
                                    " GROUP BY threadID ORDER BY numRisposte DESC, data DESC LIMIT 1");
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $threadID = $rows[0]["threadID"];
                $thread = $this->load($threadID);
                return $thread;
            } catch (PDOException $e) {
                return null;
            }
        }

        public function loadThreadMaxValutazionePerCategoria(int $categoriaID): ?Ethread
        {
            try {
                $dbConnection = FConnection::getInstance();
                $pdo = $dbConnection->connect();
                $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");

                $stmt = $pdo->query("SELECT threadID FROM threads, valutazioni WHERE valutazioneThreadID = valutazioneID 
                                     AND catThreadID = " . $categoriaID . " ORDER BY totale DESC, data DESC LIMIT 1");
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $threadID = $rows[0]["threadID"];
                $thread = $this->load($threadID);
                return $thread;
            } catch (PDOException $e) {
                return null;
            }
        }

        public static function getLastID(): int
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $stmt = $pdo->query("SELECT MAX(threadID) AS id FROM threads");
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return (int)$row[0]["id"];
        }

        public static function store(EThread $t): bool
        {
            $threadID = $t->getId();
            $titolo = $t->getTitolo();
            $testo = $t->getTesto();
            $data = $t->getData();
            $allegati = $t->getAllegati();
            $user = $t->getAutoreThread();
            $categoria = $t->getCategoriaThread();
            $tags = $t->getTags();
            $valutazione = $t->getValutazione();
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $sql = ("INSERT INTO threads(threadID, titoloThread, testoThread, dataThread, autoreThreadID, catThreadID, valutazioneThreadID)
                    VALUES (:threadID, :titoloThread, :testoThread, :dataThread, :autoreThreadID, :catThreadID, :valutazioneThreadID)");
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(array(
                ':threadID' =>  $threadID,
                ':titoloThread' => $titolo,
                ':testoThread' => $testo,
                ':dataThread' => $data,
                ':autoreThreadID' => $user->getId(),
                ':catThreadID' => $categoria->getId(),
                ':valutazioneThreadID' => $valutazione->getId()
            ));
            foreach($allegati as $allegato){
                $allegatoID = self::getLastIDAllegato();
                self::storeAllegato($allegatoID, $allegato, $threadID);
            }
            foreach($tags as $tag){
                self::storeTagsToThread($tag->getId(), $threadID);
            }

            return $result;
        }

        private static function getLastIDAllegato(): int
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $stmt = $pdo->query("SELECT MAX(allegatoID) AS id FROM allegati");
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return (int)$row[0]["id"];
        }

        private static function storeAllegato(int $allegatoID, string $path, int $threadID): bool
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $sql = ("INSERT INTO alleagti(allegatoID, path, threadID)
                    VALUES (:allegatoID, :path, :threadID)");
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(array(
                ':allegatoID' => $allegatoID,
                ':path' => $path,
                ':threadID' =>  $threadID
            ));
            return $result;
        }

        private static function storeTagsToThread(int $tagID, int $threadID): bool
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $sql = ("INSERT INTO tagstothread(tagID, threadID)
                    VALUES (:tagID, :threadID)");
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(array(
                ':tagID' => $tagID,
                ':threadID' =>  $threadID
            ));
            return $result;
        }

        public static function delete(int $threadID): bool
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $sql = ("DELETE FROM threads WHERE threadID = " . $threadID);
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute();
            return $result;
        }

        public static function updateByCategoriaID($categotiaID): bool
        {
            $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
            $sql = ("UPDATE threads SET catThreadID = 1 WHERE catThreadID = :categoriaID");
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(array(
                ':categoriaID' => $categotiaID
            ));
            return $result;
        }
    }