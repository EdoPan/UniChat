<?php
require_once __DIR__. "\..\lib\phpmailer\includes\PHPMailer.php";
require_once __DIR__. "\..\lib\phpmailer\includes\SMTP.php";
require_once __DIR__. "\..\lib\phpmailer\includes\Exception.php";

use PHPMailer\PHPMailer\PHPMailer;

//Creo un'istanza di PHPMailer
$mail = new PHPMailer();

//Imposto il mailer per usare SMTP
$mail->isSMTP();

//Definisco host SMTP
$mail->Host = "smtp.gmail.com";

//Abilito autenticazione SMTP
$mail->SMTPAuth = true;

//Imposto il tipo di cifratura SMTP (SSL/TLS)
$mail->SMTPSecure = "tls";

//Porta SMTP
$mail->Port = "587";

//Imposto l'username Gmail
$mail->Username = "critellinino@gmail.com";

//Imposto la password dell'account Gmail
$mail->Password = "nino070499";

//Oggetto dell'email
$mail->Subject = "Recupero Password UniChat";

//Imposto il mittente
$mail->setFrom('critellinino@gmail.com');

//Abilito HTML
$mail->isHTML(true);

//Allegati
//$mail->addAttachment();

//Body dell'email
$mail->Body = "<h1>Recupero Password UniChat</h1></br><p>Ciao! La tua nuova password è: $nuovapassword </p>";

//Inserisco il ricevente
$mail->addAddress($ricevente);

//Invio l'email
if ($mail->send()) {
    echo "Email Inviata!";
} else {
    echo "Errore";
	}

//Chiudo la connessione SMTP
$mail->smtpClose();