<?php
declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";
class CGestioneChat
{

    /**
     * Metodo responsabile di eseguire le operazioni necessarie a visualizzare correttamente la chat all'interno della
     * home page.
     * Si vanno a recuperare dalla base dati tutti i messaggi che sono stati scritti nelle ultime 24 ore e poi vengono
     * passati come un array json al client.
     * Se i messaggi non vengono recuperati correttamente dalla base dati allora il codice lato client mostrerà un errore
     * nel momento in cui non si vede ricevere una risposta.
     */
    public function visualizzaChat(): void
    {
        $pm = FPersistentManager::getInstance();

        $messaggi = $pm->loadMessaggiUltime24ore();
        if (isset($messaggi)) {
            $messaggiJson = json_encode($messaggi);
            echo $messaggiJson;
        }

    }

    /**
     * Metodo responsabile di eseguire le operazioni necessarie ad aggiornare correttamente la chat.
     * Viene interrogata la view responsabile della gestione delle form per recuperare l'id dell'ultimo messaggio presente
     * nella chat, se il valore è impostato allora viene usato per recuperare i nuovi messaggi dalla base dati.
     * Dalla base dati si ottiene un array di messaggi, che può essere vuoto se non ce ne sono di nuovi, che viene poi
     * reso un json e inviato al client.
     * Se i messaggi non vengono recuperati correttamente dalla base dati o allora il codice lato client mostrerà un errore
     * nel momento in cui non si vede ricevere una risposta.
     */
    public function aggiornaChat(): void
    {

        $view = new VForm();
        $valori = $view->getValori(VForm::FORM_INVIO_MESSAGGIO);

        if (isset($valori)) {
            $messID = $valori['messaggioID'];
            $pm = FPersistentManager::getInstance();
            $nuoviMessaggi = $pm->loadNuoviMessaggi($messID);
            if (isset($nuoviMessaggi)) {
                $messaggiJson = json_encode($nuoviMessaggi);
                echo $messaggiJson;
            }
        }

    }

    /**
     * Metodo che gestisce tutte le operazioni per rimuovere un messaggio dalla chat.
     * Tale operazione richiede che l'utente sia loggato e quindi prima di tutto si verifica che vi sia una sessione
     * attiva con un utente memorizzato. Se così non fosse allora si viene rimandati alla pagina di login.
     * L'operazione può essere eseguita solo da un moderatore o dall'admin, quindi recuperato l'utente dalla sessione si
     * procede a verificare che sia appartenente ad una di queste tipologie.
     * In caso affermativo si procede a rimuovere il messaggio dalla base dati, altrimenti si viene rimandati alla home
     * page in quanto non autorizzati a compiere questa operazione.
     * Se l'eliminazione va buon fine allora si viene rimandati sulla home page e viene visualizzato un messaggio di
     * conferma, altrimenti viene visualizzato un messaggio di conferma.
     * @param int $messID Identificativo del messaggio da rimuovere.
     */
    public function rimuoviMessaggio(int $messID): void
    {
        $session = new USession();
        $user = $session->getValue("user");
        if (isset($user)) {
            $user = unserialize($user);
            $pm = FPersistentManager::getInstance();
            if ($pm->isA(FPersistentManager::ENTITY_MODERATORE, $user->getId()) || $pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getId())) {

                $result = $pm->delete(FPersistentManager::ENTITY_MESSAGGIO, $messID);
                if ($result) {
                    header('Location: /UniChat/');
                } else {
                    header('Location: /UniChat/');
                }

            } else {
                header('Location: /UniChat/');
            }
        } else {
            header('Location: /UniChat/utenti/login');
        }
    }

    /**
     * Metodo che si occupa di gestire tutte le operazioni necessarie per pubblicare un nuovo messaggio nella chat.
     * Un messaggio può essere scritto solo da un utente loggato, quindi prima di procedere si verifica che nella
     * sessione attiva sia presente un utente.
     * Se il controllo ha esito positivo allora si procede a recuperare il testo del messaggio dalla view responsabile
     * di gestire le form, altrimenti se il controllo ha esito negativo si viene rimandati alla pagina di login.
     * Se il testo viene correttamente recuperato dall view allora si procede a creare un messaggio e a memorizzarlo
     * nella base dati.
     */
    public function creaMessaggio(): void
    {
        $session = new USession();
        $user = $session->getValue("user");
        if (isset($user)){
            $user = unserialize($user);
            $view = new VForm();
            $valori = $view->getValori(VForm::FORM_INVIO_MESSAGGIO);
            if (isset($valori)) {
                if (array_key_exists('testo', $valori)) {
                    $testo = $valori['testo'];
                    $messaggio = new EMessaggio(null, $testo, null, $user);
                    $pm = FPersistentManager::getInstance();
                    $result = $pm->store(FPersistentManager::ENTITY_MESSAGGIO, $messaggio);
                }
            }
        } else {
            header('Location: /UniChat/utenti/login');
        }
    }

    /**
     * Metodo che gestisce le operazioni necessarie a recuperare l'identificativo dell'ultimo messaggio presente nella
     * base dati.
     * Se l'operazione va a buon fine allora viene inviato al client l'id in un array json.
     */
    public function ultimoIdMessaggio(): void
    {
        $pm = FPersistentManager::getInstance();
        $result = $pm->lastIdMess();
        if (isset($result)) {
            $messID = array(
                'messID' => $result
            );
            $messIDJson = json_encode($messID);
            echo $messIDJson;
        }
    }
}