<?php

declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";

require_once __DIR__. "\..\lib\phpmailer\includes\PHPMailer.php";
require_once __DIR__. "\..\lib\phpmailer\includes\SMTP.php";
require_once __DIR__. "\..\lib\phpmailer\includes\Exception.php";

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Classe di controllo contenente tutti i metodi con operazioni in cui è coinvolto l'utente.
 */
class CGestioneUtenti
{

    /**
     * Metodo responsabile dell'invio email verso l'utente che ha richiesto una nuova password.
     * @param $ricevente //email dell'utente che intende recuperare la password
     * @param $nuovapassword //nuova password generata per l'utente
     * @return bool
     * @throws \PHPMailer\PHPMailer\Exception
     */

    private function invioEmail(string $ricevente, string $nuovapassword):bool{

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
        $mail->Password = "";

        //Oggetto dell'email
        $mail->Subject = "Recupero Password UniChat";

        //Imposto il mittente
        $mail->setFrom('critellinino@gmail.com');

        //Abilito HTML
        $mail->isHTML(true);

        //Body dell'email
        $mail->Body = "<h1>Recupero Password UniChat</h1></br><p>Ciao! La tua nuova password è: $nuovapassword </p>";

        //Inserisco il ricevente
        $mail->addAddress($ricevente);

        //Invio l'email
        if ($mail->send()) {
            $inviato=true;
        } else {
            $inviato=false;
        }

        //Chiudo la connessione SMTP
        $mail->smtpClose();

        return $inviato;
}


    /**
     * Metodo responsabile della gestione della form di registrazione di un utente.
     * Se Richiesta GET, ho solo la visualizzazione della form.
     * Se Richiesta POST, recupero gli elementi inseriti dal
     * visitatore (nome, cognome, email, password, foto profilo, corso  di studio) in modo da creare
     * un nuovo oggetto di tipo EUser e procedere al salvataggio in DB.
     * Al termine della registrazione l'utente è indirizzato sulla pagina di Login.
     *
     */

    public function registrazione():void {

        $session = new USession();
        $user = $session->getValue('user');

        $pm = FPersistentManager::getInstance();

        $viewForm = new VForm();
        $viewError = new VError();


        //se si è già loggati si viene reindirizzati verso il proprio profilo

        if (isset($user)) {

            header("Location: /UniChat/Utenti/editShowPersonalProfile");

        } else {

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                $viewForm->setErroreValidazione(null, null);
                $viewForm->showForm(VForm::FORM_REGISTRAZIONE);

            } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $valori = $viewForm->getValori(VForm::FORM_REGISTRAZIONE);
                if (isset($valori)) {
                    $nome = $valori['nome'];
                    $cognome = $valori['cognome'];
                    $email = $valori['email'];
                    $password = $valori['password'];

                    //gestione campi non obbligatori

                    if(array_key_exists('corsoStudio', $valori)) {
                        $corsoStudio = $valori['corsoStudio'];
                    } else {
                        $corsoStudio=null;
                    }

                    if (array_key_exists('nomeFotoProfilo', $valori)) {
                        $fotoProfilo = array();
                        $fotoProfilo['nome'] = $valori['nomeFotoProfilo'];
                        $fotoProfilo['dimensione'] = $valori['dimensioneFotoProfilo'];
                        $fotoProfilo['tipo'] = $valori['tipoFotoProfilo'];
                        $fotoProfilo['immagine'] = $valori['immagineFotoProfilo'];
                    } else {
                        $fotoProfilo = null;
                    }

                    try {

                        $nuovoUser = new EUser(null, $nome, $cognome, $email, $password, $fotoProfilo, $corsoStudio);

                        if ($pm->store(FPersistentManager::ENTITY_USER, $nuovoUser) == true) {

                            header("Location: /UniChat/Utenti/login");
                        }

                        else {
                            // rimandare su registrazione con Operazione non Riuscita
                        }

                    } catch (ValidationException $e) {

                        $viewForm->setErroreValidazione($e->getCode(), $e->getMessage());
                        $viewForm->showForm(VForm::FORM_REGISTRAZIONE);

                    }

                } else {
                    $viewError->setValoriErrore(VError::CODE_500, VError::TYPE_500);
                    $viewError->showError();
                }

            }

        }
    }


    /**
     * Metodo responsabile del recupero della password inserita in
     * fase di registrazione.
     * Se Richiesta GET, visualizzo la form di recupero password
     * Se Richiesta POST, dall'email inserita nella form, recupero
     * l'oggetto EUser relativo, genero una nuova password, la
     * imposto, salvando il nuovo oggetto in DB e invio una email
     * all'utente di conferma. Al termine, l'utente verrà
     * reindirizzato verso la pagina di login.
     */

    public function recuperoPassword():void {

        $session=new USession();
        $user=$session->getValue('user');


        $pm = FPersistentManager::getInstance();

        $viewForm = new VForm();
        $viewError = new VError();

        if (! isset($user)) {

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                $viewForm->setErroreValidazione(null, null);
                $viewForm->showForm(VForm::FORM_RECUPERO_PASSWORD);

            } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $valori = $viewForm->getValori(VForm::FORM_RECUPERO_PASSWORD);
                if (isset($valori)) {
                    $email = $valori['email'];
                }

                else {

                    //gestire in caso di campo vuoto
                }

                try {

                    if ($pm->existsUserByEmail($email) == true) {

                        $utente = $pm->loadUserByEmail($email);

                        if (isset($utente)) {

                            $nuovaPassword=$utente->generaPassword();

                            if($pm->update(FPersistentManager::ENTITY_USER, $utente)) {

                                $esito=self::invioEmail($email, $nuovaPassword);

                                if ($esito) {

                                    header('/Unichat/utenti/login/');

                                } else {

                                    $viewForm->setErroreValidazione(null, null);
                                    $viewForm->showForm(VForm::FORM_RECUPERO_PASSWORD);
                                }


                            } else {
                                $viewError->setValoriErrore(VError::CODE_500, VError::TYPE_500);
                                $viewError->showError();
                            }

                        } else {
                            $viewError->setValoriErrore(VError::CODE_500, VError::TYPE_500);
                            $viewError->showError();
                        }

                    } else {
                        $viewError->setValoriErrore(VError::CODE_500, VError::TYPE_500);
                        $viewError->showError();
                    }


                } catch (ValidationException $e) {

                    $viewForm->setErroreValidazione($e->getCode(), $e->getMessage());
                    $viewForm->showForm(VForm::FORM_RECUPERO_PASSWORD);

                }

            }

        } else {
            header('/UniChat/');
        }

    }


    /**
     * Metodo responsabile dell'accesso a UniChat in modalità utente
     * registrato.
     * Se Richiesta GET, visualizzo la form di login
     * Se Richiesta POST, recupero l'utente nel db, verifico la
     * password, creo sessione per l'utente e infine reindirizzo verso
     * la home da loggato (vista diversa se user o moderatore/admin).
     */

    public function login():void {

        $session = new USession();
        $user = $session->getValue('user');

        $pm = FPersistentManager::getInstance();

        $viewForm = new VForm();
        $viewError = new VError();


        //se si è già loggati si viene reindirizzati verso il proprio profilo

        if (isset($user)) {
            header("Location: /UniChat/Utenti/editShowPersonalProfile");
        } else {

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                $viewForm->setErroreValidazione(null, null);
                $viewForm->showForm(VForm::FORM_LOGIN);

            } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

                //verificare isset valori email e password

                $email = $viewForm->getValori(VForm::FORM_LOGIN)['email'];
                $password = $viewForm->getValori(VForm::FORM_LOGIN)['password'];

                try {

                    if ($pm->existsUserByEmail($email) == true) {

                        $userLoggato = $pm->loadUserByEmail($email);

                        if (isset($userLoggato)) {

                            //verifica password
                            if($userLoggato->getPassword()==$password) {

                                //avvio sessione

                                    $userLoggato = serialize($userLoggato);
                                    $session = new USession();
                                    $session->setValue('user', $userLoggato);

                                    header("Location: /UniChat/");
                            }

                        } else {
                            //rimandare a login che segnala credenziali errate
                        }

                    } else {

                        //rimandare a login che segnala credenziali errate [DA CAMBIARE]
                        $viewError->setValoriErrore(VError::CODE_500, VError::TYPE_500);
                        $viewError->showError();
                    }

                } catch (ValidationException $e) {

                    $viewForm->setErroreValidazione($e->getCode(), $e->getMessage());
                    $viewForm->showForm(VForm::FORM_LOGIN);

                }

            }
        }
    }


    /**
     * Metodo responsabile della chiusura di una sessione di un utente
     * registrato. Si fa scadere la sessione e si reindirizza l'utente verso la homepage.
     */

    public function logout():void {

        $session = new USession();
        $user = $session->getValue('user');

        if(isset($user)) {
            $session->deleteSession();
        }

        header('Location: /UniChat/');

    }

    /**
     * Metodo responsabile della visualizzazione e della modifica
     * delle proprie informazioni personali (password/foto profilo/
     * corso studi)
     * Se Richiesta GET, visualizzo le informazioni personali
     * Se Richiesta POST, visualizzo e ho possibilità di modificare le
     * informazioni. Se applico modifiche, salvo in db e visualizzo le info del
     * profilo aggiornate con relativo messaggio di conferma.
     */

    public function editShowPersonalProfile(): void {


        $session = new USession();
        $user = $session->getValue('user');

        $pm = FPersistentManager::getInstance();

        $viewForm = new VForm();
        $viewError = new VError();


        //se non si è già loggati si viene reindirizzati verso la pagina di login

        if (! isset($user)) {

            header("Location: /UniChat/Utenti/login");
        }

        else {

            $user=unserialize($user);

            $vProfilo=new VPersonalProfile();

            if (func_num_args() == 1) {
                if (func_get_arg(0) == "conferma") {
                    $vProfilo->setMessaggioConfermaErroreModificaProfilo(true);
                } else if (func_get_arg(0) == "errore") {
                    $vProfilo->setMessaggioConfermaErroreModificaProfilo(false);
                } else {
                    $vProfilo->setMessaggioConfermaErroreModificaProfilo(null);
                }
            } else {
                $vProfilo->setMessaggioConfermaErroreModificaProfilo(null);
            }

            $cImpostaPagina=new CImpostaPagina();

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {


                if($cImpostaPagina->impostaModuli($user)) {

                    $vProfilo->setUtente($user);
                    $vProfilo->showPersonalProfile();

                }else {
                    $viewError->setValoriErrore(VError::CODE_500, VError::TYPE_500);
                    $viewError->showError();
                }



            } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {


                $valori = $viewForm->getValori(VForm::FORM_PROFILO_PERSONALE);

                if (count($valori)!=0) {

                    if(array_key_exists('nuovaPassword', $valori)) {
                        $password = $valori['nuovaPassword'];
                    } else {
                        $password=null;
                    }


                    if(array_key_exists('nuovoCorsoStudio', $valori)) {
                        $corsoStudio = $valori['nuovoCorsoStudio'];
                    } else {
                        $corsoStudio=null;
                    }


                    if (array_key_exists('nomeNuovaFotoProfilo', $valori)) {
                        $nuovaFotoProfilo = array();
                        $nuovaFotoProfilo['nome'] = $valori['nomeNuovaFotoProfilo'];
                        $nuovaFotoProfilo['dimensione'] = $valori['dimensioneNuovaFotoProfilo'];
                        $nuovaFotoProfilo['tipo'] = $valori['tipoNuovaFotoProfilo'];
                        $nuovaFotoProfilo['immagine'] = $valori['immagineNuovaFotoProfilo'];
                    } else {
                        $nuovaFotoProfilo = null;
                    }


                    try {

                        //$utente=$pm->load(FPersistentManager::ENTITY_USER, FPersistentManager::PROPERTY_DEFAULT, $user->getID());

                        if (isset($password)) {
                            $user->setPassword($password);
                        }

                        if(isset($nuovaFotoProfilo)) {
                            $user->setFotoProfilo($nuovaFotoProfilo);
                        }

                        if(isset($corsoStudio)) {
                            $user->setCorsoStudio($corsoStudio);
                        }

                        $updUtente=$pm->update(FPersistentManager::ENTITY_USER, $user);

                        if ($updUtente) {

                            $user=serialize($user);

                            $session->updateValue('user', $user);

                            $vProfilo->setMessaggioConfermaErroreModificaProfilo(true);


                        } else {

                            $vProfilo->setMessaggioConfermaErroreModificaProfilo(false);
                        }

                        $impostaModuli=$cImpostaPagina->impostaModuli($user);

                        if($impostaModuli) {

                            $vProfilo->setUtente($user);
                            $vProfilo->showPersonalProfile();

                        }else {
                            $viewError->setValoriErrore(VError::CODE_500, VError::TYPE_500);
                            $viewError->showError();
                        }


                    } catch (ValidationException $e) {



                        $viewForm->setErroreValidazione($e->getCode(), $e->getMessage());

                        $impostaModuli=$cImpostaPagina->impostaModuli($user);

                        if($impostaModuli) {
                            $vProfilo->setUtente($user);
                            $vProfilo->showPersonalProfile();

                        }else {
                            $viewError->setValoriErrore(VError::CODE_500, VError::TYPE_500);
                            $viewError->showError();
                        }
                    }


                } else {

                    //vedere come gestire campi vuoti
                    $viewError->setValoriErrore(VError::CODE_500, VError::TYPE_500);
                    $viewError->showError();
                }

            }
        }

    }


    /**
     * Metodo responsabile della visualizzazione delle info personali
     * di un altro utente iscritto su UniChat
     * Recupero id utente dall'url.
     */

    public function showProfile($idUtente): void {

        $session = new USession();
        $user = $session->getValue('user');

        if (isset($user)) {
            $user=unserialize($user);
        }


        $pm = FPersistentManager::getInstance();
        $viewError=new VError();
        $cImpostaPagina=new CImpostaPagina();

        try {
            $utente=$pm->load(FPersistentManager::ENTITY_USER, FPersistentManager::PROPERTY_DEFAULT, $idUtente);
        } catch (ValidationException $e) {

            $utente=null;

        }

        $vProfilo=new VProfile();
        if (isset($utente)) {

            $impostaModuli=$cImpostaPagina->impostaModuli($user);

            if($impostaModuli) {

                $vProfilo->setUtente($utente);
                $vProfilo->showUtente();

            }else {
                $viewError->setValoriErrore(VError::CODE_500, VError::TYPE_500);
                $viewError->showError();
            }

        }
        else {
            $viewError->setValoriErrore(VError::CODE_404, VError::TYPE_404);
            $viewError->showError();
        }

    }


}