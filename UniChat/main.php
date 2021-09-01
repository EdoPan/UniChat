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
/* CAPITOLO CHIUSO TRANNE PER LE EMAIL
$cAutenticazione = new CAutenticazione();
$result = $cAutenticazione->login("email.finta@email.com", "7R4SPPDbxTD4JL4kNom2xK6sfudDpF");
if ($result) {
    print ("TUTTO OK!");
} else {
    print("OPSS!");
}
*/

$cAmministrazione = new CAmministrazione();
$cAutenticazione = new CAutenticazione();
$cInterazione = new CInterazione();
$cMonitoraggio = new CMonitoraggio();
$cVisualizza = new CVisualizza();
//$result = $cAutenticazione->registrazione("Marta", "Verdi", "marta.verdi1@email.com", "passqord5", null, "Matematica");
//$result = $cAutenticazione->registrazione("Franco", "Bollo", "test1@localhost", "email", null, "Fisica");

//$result = $cAmministrazione->aggiungiCategoria(3, "DISIM", null, "Qui troverai i threads del DISIM.");
//$result = $cAmministrazione->aggiungiModeratore(3, 6, 6);
//$result = $cAmministrazione->rimuoviCategoria(3, 5);
//$result = $cAmministrazione->rimuoviModeratore(3, 12);
//$result = $cAmministrazione->rimuoviUser(3, 12);
//$result = $cInterazione->creaThread(3, "Questo è un thread.", 6, "Un testo di prova per un thread di prova.", null);
//$result = $cInterazione->creaThread(6, "Questo è un thread.", 5, "Un testo di prova per un thread di prova.", null);
//$result = $cInterazione->creaThread(3, "Problema di Cauchy", 6, "Un testo di prova per un thread di prova.", null);
//$result = $cInterazione->rispondiThread(11, "Questa è un risposta di prova per un thread di prova, un'altra volta.", 14);
//$result = $cInterazione->rispondiThread(6, "Tutto ok", 14);
//$result = $cInterazione->rispondiThread(13, "Sarebbe.", 14);
//$fValutazione = FValutazione::getInstance();
//$fUser = FUser::getInstance();
//$result = $cInterazione->valutaThread(11, -1, 15);
//$result = true;
//$result = $cInterazione->creaMessaggio("Ci voleva", 6);
//$marta = $fUser->load(13);
//$fotoProfilo = $marta->getFotoProfilo();
//$result = $cInterazione->editPersonalProfile(13, "nuovaPass", $fotoProfilo, "Economia");
//$result = $cAutenticazione->login("marta.verdi1@email.com", "nuovaPass");

//$result = $cMonitoraggio->rimuoviMessaggio(6, 2);
//$result = $cMonitoraggio->rimuoviThread(6, 6);
//$result = $cMonitoraggio->rimuoviRisposte(6, 9, 14);
//$result = $cVisualizza->elencaCategorie();
//$result = $cVisualizza->threadPiuDiscusso(6);
//$threads = $cVisualizza->elencaThreads(1);

//$cAutenticazione->recuperoPassword("test1@localhost");

//$users = $cVisualizza->elencaModeratori();
$user = $cVisualizza->visualizzaCategoria(6);
print($user->getNome());
//mail("test1@localhost", "Prova", "Email di prova.");
//$result = $cAutenticazione->login("test1@localhost", ".rq_BM9-4Lp.4.^49n.y^.Q_.p1.C.");
/*
if ($result) {
    print ("TUTTO OK!");
} else {
    print("OPSS!");
}
*/
