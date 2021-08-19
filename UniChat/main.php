<?php

    require_once "utility.php";

/*
    $autenticazione = new CAutenticazione();
    $interazione = new CInterazioneThread();
    $monitor = new CMonitoraggio();
    $ammi = new CAmministrazione();
    $vedi = new CVisualizza();
    $a = $autenticazione->registrazione("Franco", "Neri", "franco.neri@student.univaq.it", "neripass", null, null);
    if($a == true){
        print "OK";
    } else {
        $tags = array(1);
        $r = $vedi->elencaCategorie();
        print_r($r);
    }


$pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
$stmt = $pdo->query("SELECT titoloThread FROM threads WHERE MATCH (titoloThread) AGAINST ('Problema di analisi 2')");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($rows);

ricerca per categoria
ricerca per categoria e tags
ricerca per titolo
ricerca per titolo e categoria/e
ricerca per titolo e tag/s
ricerca per titolo, categoria/e e tag/s

$pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
$sql = ("DELETE FROM valutazioni WHERE valutazioneID = " . 2);
$stmt = $pdo->prepare($sql);
$result = $stmt->execute();
*/
/*
$valutazioneID = 1;
$userID = 2;
$pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
$stmt = $pdo->query("SELECT * FROM votinegativi WHERE userID=" . $userID . " AND valutazioneID=" . $valutazioneID);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (count($rows) == 1) {
    $r = true;
} else {
    $r = false;
}
print($r);
*/
/*
$pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
$sql = ("INSERT INTO risposte(rispostaID, testoRisposta, dataRisposta, autoreRispID, threadRispID)
                    VALUES (:rispostaID, :testoRisposta, :dataRisposta, :autoreRispID, :threadRispID)");
$stmt = $pdo->prepare($sql);
$result = $stmt->execute(array(
    ':rispostaID' =>  1,
    ':testoRisposta' => "Prega molto intensamente",
    ':dataRisposta' => date("Y-m-d H:i:s"),
    ':autoreRispID' => 2,
    ':threadRispID' => 1
));
print($result);
*/
/*
$email = "mario.rossi@student.univaq.it";
$pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
$stmt = $pdo->query("SELECT * FROM users WHERE email = '" . $email . "'");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$record = $rows[0];
$userID = (int)$record["userID"];
$nome = $record["nome"];
$cognome = $record["cognome"];
$email = $record["email"];
$password = $record["password"];
$fotoProfilo = $record["fotoProfilo"];
$corsoStudio = $record["corsoStudio"];
$user = new EUser($userID, $nome, $cognome, $email, $password, $fotoProfilo, $corsoStudio);

print($user->getPassword());
*/
    /*
    $u = new EUser(1, "Mario", "Rossi", "mario.rossi@student.univaq.it", "12345678", null, "Ingegneria");
    $u1 = new EUser(2, "Luigi", "Bianchi", "luigi.bianchi@student.univaq.it", "password", null, "Economia");
    $m = new EMessaggio(1, "Ciao!!", $u);
    $t = new ETag(1, "Matematica");
    $c = new ECategoria(1, "DISIM", "disim.jpg", "Sa Nino cosa scriverci");
    $v = new EValutazione(1);
    $allegati = array("foto.pdf");
    $tags = array($t);
    $r = new ERisposta(1, "Prega tanto", $u1);
    $r1 = new ERisposta(2, "Già lo faccio", $u);
    $risposte = array($r, $r1);
    $t = new EThread(1, "Problema di analisi 2", "Ma come si fa..", $allegati, $u, $c, $tags, $v, $risposte);
    print($t->getRisposte()[0]->getAutoreRisposta()->getNome());
    */