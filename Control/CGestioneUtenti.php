<?php

declare(strict_types = 1);
require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "utility.php";

require_once __DIR__. DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "phpmailer" . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "PHPMailer.php";
require_once __DIR__. DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "phpmailer" . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "SMTP.php";
require_once __DIR__. DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "phpmailer" . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "Exception.php";

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
        $mail->Username = "unichatappweb@gmail.com";

        //Imposto la password dell'account Gmail
        $mail->Password = "";

        //Oggetto dell'email
        $mail->Subject = "Recupero Password UniChat";

        //Imposto il mittente
        $mail->setFrom("unichatappweb@gmail.com");

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

        /*
        $mail = new PHPMailer(true);
        $mail->isHTML(true);
        $mail->From='unichat@altervista.org';
        $mail->FromName='UniChat';
        $mail->addAddress($ricevente);
        $mail->Subject='Recupero password';
        $mail->Body = "<h1>Recupero Password UniChat</h1></br><p>Ciao! La tua nuova password è: $nuovapassword </p>";
        if ($mail->send()) {
            return true;
        } else {
            return false;
        }
        */
}


    /**
     * Metodo responsabile della gestione della form di registrazione di un utente.
     * Se Richiesta GET, ho solo la visualizzazione della form.
     * Se Richiesta POST, recupero gli elementi inseriti dal
     * visitatore (nome, cognome, email, password, foto profilo, corso  di studio) in modo da creare
     * un nuovo oggetto di tipo EUser e procedere al salvataggio in DB.
     * Al termine della registrazione l'utente è indirizzato sulla pagina di Login.
     */

    public function registrazione():void {

        //Recupero dell'utente dalla sessione
        $session = new USession();
        $user = $session->getValue('user');

        $pm = FPersistentManager::getInstance();

        $vRegistrazione = new VRegistrazione();


        //se si è loggati si viene reindirizzati verso il proprio profilo

        if (isset($user)) {

            header("Location: /UniChat/Utenti/editShowPersonalProfile");

        } else {

            // Se si effettua una richiesta di tipo GET si visualizza la form di Registrazione.

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                $vRegistrazione->setErroreValidazione(null, null);
                $vRegistrazione->setOperazioneFallita(false);
                $vRegistrazione->setCampiObbligatoriMancanti(false);
                $vRegistrazione->showRegistrazione();

                // Se la richiesta è di tipo POST si recuperano i valori inseriti dall'utente.

            } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $valori = $vRegistrazione->getValori();
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
                        $fotoProfilo['id'] = 0;
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

                            /*
                             * Se l'operazione non va a buon fine allora la form viene riproposta all'utente con un
                             * messaggio di operazione fallita.
                             */
                            $vRegistrazione->setErroreValidazione(null, null);
                            $vRegistrazione->setOperazioneFallita(true);
                            $vRegistrazione->setCampiObbligatoriMancanti(false);
                            $vRegistrazione->showRegistrazione();
                        }

                    } catch (ValidationException $e) {

                        $vRegistrazione->setErroreValidazione($e->getCode(), $e->getMessage());
                        $vRegistrazione->setOperazioneFallita(false);
                        $vRegistrazione->setCampiObbligatoriMancanti(false);
                        $vRegistrazione->showRegistrazione();

                    }

                } else {

                    /*
                     * Se l'utente non fornisce tutti i dati obbligatori, allora la form di registrazione viene riproposta
                     * con un messaggio di errore.
                     */
                    $vRegistrazione->setErroreValidazione(null, null);
                    $vRegistrazione->setOperazioneFallita(false);
                    $vRegistrazione->setCampiObbligatoriMancanti(true);
                    $vRegistrazione->showRegistrazione();

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
     * all'utente con la nuova password. Al termine, l'utente verrà
     * reindirizzato verso la pagina di login.
     */

    public function recuperoPassword():void {

        //Recupero dell'utente dalla sessione
        $session=new USession();
        $user=$session->getValue('user');


        $pm = FPersistentManager::getInstance();

        $vRecuperaPassword = new VRecuperaPassword();

        //Se l'utente non è già loggato: (se lo fosse non potrebbe accedere a tale pagina)
        if (! isset($user)) {

            //Se la Richiesta è di tipo GET visualizzo la FORM (senza alcun avviso)
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                $vRecuperaPassword->setMessaggio(false, VRecuperaPassword::NULLA, null);
                $vRecuperaPassword->setCredenzialiErrate(false);
                $vRecuperaPassword->setCampiObbligatoriMancanti(false);
                $vRecuperaPassword->setErroreValidazione(null, null);
                $vRecuperaPassword->showRecuperaPassword();

                //Se la richiesta è di tipo post recupero i dati inseriti e invio email con la nuova password
            } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $valori = $vRecuperaPassword->getValori();
                if (isset($valori)) {
                    $email = $valori['email'];

                    try {
                        //Verifico che l'email inserita corrisponda ad un utente iscritto
                        if ($pm->existsUserByEmail($email) == true) {

                            //Recupero utente dal DB tramite email
                            $utente = $pm->loadUserByEmail($email);

                            if (isset($utente)) {

                                //Genero una nuova password
                                $nuovaPassword=$utente->generaPassword();

                                //Salvo le modifiche in DB
                                if($pm->update(FPersistentManager::ENTITY_USER, $utente)) {

                                    //Invio email con la nuova password
                                    $esito=self::invioEmail($email, $nuovaPassword);

                                    if ($esito) {

                                        $vRecuperaPassword->setMessaggio(true, VRecuperaPassword::SUCCESS, 'success');

                                    } else {

                                        $vRecuperaPassword->setMessaggio(true, VRecuperaPassword::ERROR, 'danger');
                                    }

                                    $vRecuperaPassword->setCredenzialiErrate(false);
                                    $vRecuperaPassword->setCampiObbligatoriMancanti(false);
                                    $vRecuperaPassword->setErroreValidazione(null, null);
                                    $vRecuperaPassword->showRecuperaPassword();


                                    //Caso in cui il salvataggio in DB non è andato a buon fine: viene mostrato messaggio di
                                    // errore.
                                } else {
                                    $vRecuperaPassword->setErroreValidazione(null, null);
                                    $vRecuperaPassword->setMessaggio(true,VRecuperaPassword::ERROR, 'danger');
                                    $vRecuperaPassword->setCredenzialiErrate(false);
                                    $vRecuperaPassword->setCampiObbligatoriMancanti(false);
                                    $vRecuperaPassword->showRecuperaPassword();
                                }

                                //Caso in cui non è stato possibile caricare l'utente da DB: messaggio di errore
                            } else {
                                $vRecuperaPassword->setErroreValidazione(null, null);
                                $vRecuperaPassword->setMessaggio(true,VRecuperaPassword::ERROR, 'danger');
                                $vRecuperaPassword->setCredenzialiErrate(false);
                                $vRecuperaPassword->setCampiObbligatoriMancanti(false);
                                $vRecuperaPassword->showRecuperaPassword();
                            }

                            //Caso in cui è stata inserita un'email non corrispondente a nessun utente iscritto.
                        } else {
                            $vRecuperaPassword->setCredenzialiErrate(true);
                            $vRecuperaPassword->setMessaggio(false,VRecuperaPassword::NULLA, null);
                            $vRecuperaPassword->setCampiObbligatoriMancanti(false);
                            $vRecuperaPassword->setErroreValidazione(null, null);
                            $vRecuperaPassword->showRecuperaPassword();
                        }


                    } catch (ValidationException $e) {

                        $vRecuperaPassword->setErroreValidazione($e->getCode(), $e->getMessage());
                        $vRecuperaPassword->setMessaggio(false,VRecuperaPassword::NULLA, null);
                        $vRecuperaPassword->setCredenzialiErrate(false);
                        $vRecuperaPassword->setCampiObbligatoriMancanti(true);
                        $vRecuperaPassword->showRecuperaPassword();

                    }
                    //Caso in cui non è stata inserita l'email (campo vuoto).
                } else {
                    $vRecuperaPassword->setErroreValidazione(null, null);
                    $vRecuperaPassword->setMessaggio(false, VRecuperaPassword::NULLA, null);
                    $vRecuperaPassword->setCredenzialiErrate(false);
                    $vRecuperaPassword->setCampiObbligatoriMancanti(true);
                    $vRecuperaPassword->showRecuperaPassword();

                }
            }
            //Se già loggato, l'utente viene reindirizzato verso la home
        } else {
            header('Location: /UniChat');
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

        //Recupero dell'utente dalla sessione
        $session = new USession();
        $user = $session->getValue('user');

        $pm = FPersistentManager::getInstance();

        $vLogin = new VLogin();
        $viewError = new VError();


        //se si è già loggati si viene reindirizzati verso il proprio profilo

        if (isset($user)) {
            header("Location: /UniChat/Utenti/editShowPersonalProfile");
        } else {

            /*
             * Se non si è loggati allora si può aver contattato questo metodo per visualizzare la form da compilare
             * o per voler elaborare i dati sottomessi alla form di login.
             */

            //Se richiesta GET visualizzo la form
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                /*
                 * Visualizza la form di login.
                 */
                $vLogin->setErroreValidazione(null, null);
                $vLogin->setCampiObbligatoriMancanti(false);
                $vLogin->setCredenzialiErrate(false);
                $vLogin->showLogin();

                //Se richiesta POST recupero i dati inseriti, verifico che le credenziali siano corrette e
                // creo una nuova variabile di sessione per l'utente.
            } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

                /*
                 * Recupera i dati dalla form di login.
                 */
                $valori = $vLogin->getValori();

                if (isset($valori)) {

                    /*
                     * Se i campi obbligatori sono stati compilati dall'utente, allora si procede a valutarli.
                     */
                    $email = $vLogin->getValori()['email'];
                    $password = $vLogin->getValori()['password'];

                    try {
                        $utenteEsistente = $pm->existsUserByEmail($email);
                        if (isset($utenteEsistente)) {
                            if ($utenteEsistente == true) {

                                $userLoggato = $pm->loadUserByEmail($email);

                                if (isset($userLoggato)) {

                                    //verifica password
                                    if ($userLoggato->verificaPassword($password)) {

                                        //variabile sessione
                                        $userLoggato = serialize($userLoggato);
                                        $session = new USession();
                                        $session->setValue('user', $userLoggato);

                                        header("Location: /UniChat");
                                    } else {

                                        /*
                                         * L'utente ha inserito una password errata e quindi gli viene
                                         * riproposta la form con un messaggio di errore di credenziali errate.
                                         */
                                        $vLogin->setErroreValidazione(null, null);
                                        $vLogin->setCampiObbligatoriMancanti(false);
                                        $vLogin->setCredenzialiErrate(true);
                                        $vLogin->showLogin();
                                    }

                                } else {

                                    /*
                                     * Caso in cui non sia stato possibile caricare l'utente da DB. Viene visualizzata
                                     * una pagina di errore (500)
                                     */
                                    $viewError->setValoriErrore(VError::CODE_500, VError::TYPE_500);
                                    $viewError->showError();
                                }

                            } else {

                                /*
                                 * L'utente ha fornito un email non presente nella base dati e quindi gli viene
                                 * riproposta la form con un messaggio di errore di credenziali errate.
                                 */
                                $vLogin->setErroreValidazione(null, null);
                                $vLogin->setCampiObbligatoriMancanti(false);
                                $vLogin->setCredenzialiErrate(true);
                                $vLogin->showLogin();

                            }
                        } else {

                            /*
                             * Se si è ottenuto un null allora vuol dire che il database è offline.
                             */
                            $viewError->setValoriErrore(VError::CODE_500, VError::TYPE_500);
                            $viewError->showError();
                        }

                    } catch (ValidationException $e) {

                        /*
                         * L'utente he compilato i campi con dati non ammessi e quindi gli viene riproposta la form con
                         * un messaggio di errore.
                         */
                        $vLogin->setErroreValidazione($e->getCode(), $e->getMessage());
                        $vLogin->setCampiObbligatoriMancanti(false);
                        $vLogin->setCredenzialiErrate(false);
                        $vLogin->showLogin();

                    }
                } else {

                    /*
                     * L'utente non ha compilato tutti i campi obbligatori e quindi gli viene riproposta la form con un
                     * messaggio di errore di campi obbligatori mancanti.
                     */
                    $vLogin->setErroreValidazione(null, null);
                    $vLogin->setCampiObbligatoriMancanti(true);
                    $vLogin->setCredenzialiErrate(false);
                    $vLogin->showLogin();
                }

            }
        }
    }


    /**
     * Metodo responsabile della chiusura di una sessione di un utente
     * registrato. Si fa scadere la sessione e si reindirizza l'utente verso la homepage.
     */

    public function logout():void {

        //Recupero dell'utente dalla sessione
        $session = new USession();
        $user = $session->getValue('user');

        //Se la sessione esiste, la elimino.
        if(isset($user)) {
            $session->deleteSession();
        }

        header('Location: /UniChat');

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

        //Recupero dell'utente dalla sessione
        $session = new USession();
        $user = $session->getValue('user');

        $pm = FPersistentManager::getInstance();

        $vProfilo = new VPersonalProfile();
        $vPage = new VPage($vProfilo->getSmarty());
        $viewError = new VError();

        //se non si è già loggati si viene reindirizzati verso la pagina di login
        if (! isset($user)) {

            header("Location: /UniChat/Utenti/login");
        }

        else {

            $user=unserialize($user);

            //Se la richiesta è di tipo GET visualizzo la FORM
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                //Gestione degli avvisi recuperando i parametri dalla URL.

                if (func_num_args() == 1) {
                    if (func_get_arg(0) == "conferma") {
                        $vProfilo->setMessaggioConfermaErroreModificaProfilo(true);
                        $vProfilo->setAvvisoCampiVuoti(false);
                    } else if (func_get_arg(0) == "errore") {
                        $vProfilo->setMessaggioConfermaErroreModificaProfilo(false);
                        $vProfilo->setAvvisoCampiVuoti(false);
                    } else if (func_get_arg(0) == "avviso") {

                        $vProfilo->setMessaggioConfermaErroreModificaProfilo(null);
                        $vProfilo->setAvvisoCampiVuoti(true);
                    } else {
                        $vProfilo->setMessaggioConfermaErroreModificaProfilo(null);
                        $vProfilo->setAvvisoCampiVuoti(false);
                    }
                } else {
                    $vProfilo->setMessaggioConfermaErroreModificaProfilo(null);
                    $vProfilo->setAvvisoCampiVuoti(false);
                }

                try {
                    $categorie = $pm->loadAllCategorie();
                } catch (ValidationException $e) {
                    $categorie = null;
                }
                //Impostazione dei menù e del filtro ricerca tramite VPage.
                if (isset($categorie)) {
                    $vPage->setMenuUtente($user, false);
                    $vPage->setMenuLeft($categorie);
                    $vPage->setBottoneFiltra($categorie);
                    if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getId())) {
                        $vPage->setMenuUtente($user, true);
                    }
                    $vProfilo->setUtente($user);
                    $vProfilo->setErroreValidazione(null, null);
                    $vProfilo->showPersonalProfile();
                } else {
                    $viewError->setValoriErrore(VError::CODE_500, VError::TYPE_500);
                    $viewError->showError();
                }


                //Se richiesta è di tipo Post, recupero i dati inseriti e procedo al salvataggio in DB.
            } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $valori = $vProfilo->getValori();

                //I dati modificabili sono: Foto Profilo, corso di studio e password. Nessuno di questi è un
                // campo obbligatorio.

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
                        $nuovaFotoProfilo['id'] = 0;
                        $nuovaFotoProfilo['nome'] = $valori['nomeNuovaFotoProfilo'];
                        $nuovaFotoProfilo['dimensione'] = $valori['dimensioneNuovaFotoProfilo'];
                        $nuovaFotoProfilo['tipo'] = $valori['tipoNuovaFotoProfilo'];
                        $nuovaFotoProfilo['immagine'] = $valori['immagineNuovaFotoProfilo'];
                    } else {
                        $nuovaFotoProfilo = null;
                    }


                    try {

                        if (isset($password)) {
                            $user->setPassword($password);
                        }

                        if(isset($nuovaFotoProfilo)) {
                            $user->setFotoProfilo($nuovaFotoProfilo);
                        }

                        if(isset($corsoStudio)) {
                            $user->setCorsoStudio($corsoStudio);
                        }

                        //Aggiorno l'utente in DB con le eventuali nuove informazioni inserite
                        $updUtente=$pm->update(FPersistentManager::ENTITY_USER, $user);

                        if ($updUtente) {

                            //Aggiorno la variabile di sessione dell'utente
                            $user=serialize($user);
                            $session->updateValue('user', $user);

                            //Mostro avviso di conferma modifiche
                            header("Location: /UniChat/utenti/editShowPersonalProfile/conferma");

                        } else {
                            //Se il salvataggio in DB non è avvenuto con successo mostro il messaggio di errore.
                            header("Location: /UniChat/utenti/editShowPersonalProfile/errore");

                        }


                    } catch (ValidationException $e) {

                        //Nel caso in cui siano inseriti dati non validi, mostro messaggio di errore.

                        $vProfilo->setMessaggioConfermaErroreModificaProfilo(null);
                        try {
                            $categorie = $pm->loadAllCategorie();
                        } catch (ValidationException $e1) {
                            $categorie = null;
                        }
                        if (isset($categorie)) {
                            $vPage->setMenuUtente($user, false);
                            $vPage->setMenuLeft($categorie);
                            $vPage->setBottoneFiltra($categorie);
                            if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getId())) {
                                $vPage->setMenuUtente($user, true);
                            }
                            $vProfilo->setUtente($user);
                            $vProfilo->setErroreValidazione($e->getCode(), $e->getMessage());
                            $vProfilo->setAvvisoCampiVuoti(false);
                            $vProfilo->showPersonalProfile();
                        } else {
                            $viewError->setValoriErrore(VError::CODE_500, VError::TYPE_500);
                            $viewError->showError();
                        }

                    }

                    // Nel caso in cui non siano stati inseriti dati nella form viene visualizzato il relativo
                    // avviso.
                } else {
                    header("Location: /UniChat/utenti/editShowPersonalProfile/avviso");
                }
            }
        }
    }


    /**
     * Metodo responsabile della visualizzazione delle info personali
     * di un utente iscritto su UniChat.
     * Recupero id utente dall'url.
     */

    public function showProfile($idUtente): void {

        //Recupero utente dalla sessione
        $session = new USession();
        $vProfile = new VProfile();

        $vPage = new VPage($vProfile->getSmarty());
        $pm = FPersistentManager::getInstance();

        //Recupero dell'utente di cui visualizzarne il profilo da DB tramite il suo id (parametro della URL)
        try {
            $utente = $pm->load(FPersistentManager::ENTITY_USER, FPersistentManager::PROPERTY_DEFAULT, $idUtente);
        } catch (ValidationException $e) {
            $utente = null;
        }

        try {
            $categorie = $pm->loadAllCategorie();
        } catch (ValidationException $e) {
            $categorie = null;
        }

        //Impostazione dei menù e della pagina.
        if (isset($utente) && isset($categorie)) {
            $vProfile->setUtente($utente);

            $vPage->setBottoneFiltra($categorie);
            $vPage->setMenuLeft($categorie);
            $vPage->setMenuUtente(null, null);

            $utenteSessione = $session->getValue('user');
            if (isset($utenteSessione)) {
                $utenteSessione = unserialize($utenteSessione);
                if ($pm->isA(FPersistentManager::ENTITY_ADMIN, $utenteSessione->getId())) {
                    $vPage->setMenuUtente($utenteSessione, true);
                } else {
                    $vPage->setMenuUtente($utenteSessione, false);

                }
            }

            $vProfile->showUtente();
        } else {
            $vError = new VError();
            $vError->setValoriErrore(VError::CODE_500, VError::TYPE_500);
            $vError->showError();
        }

    }

}