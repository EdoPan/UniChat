<?php
require_once __DIR__ . "\..\utility.php";

/**
 * Classe View per le risposte dei Thread.
 */
class VRisposta
{

    /**
     * Metodo per il recupero dei valori passati dall'utente nelle POST.
     * @return array|null
     */
    public function getValori(): ?array {

        $result = array();

        if ($_POST['testo'] != "" && $_POST['threadID'] != "") {

            $result['testo'] = filter_var($_POST['testo'], FILTER_SANITIZE_SPECIAL_CHARS);
            $result['threadID'] = filter_var($_POST['threadID'], FILTER_SANITIZE_SPECIAL_CHARS);

        } else {

            $result = null;

        }

        return $result;
    }

}