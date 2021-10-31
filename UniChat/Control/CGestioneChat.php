<?php
declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";
class CGestioneChat
{

    public function visualizzaChat(): void
    {
        $pm = FPersistentManager::getInstance();

        $messaggi = $pm->loadMessaggiUltime24ore();
        if (isset($messaggi)) {
            $messaggiJson = json_encode($messaggi);
            echo $messaggiJson;
        }

    }

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