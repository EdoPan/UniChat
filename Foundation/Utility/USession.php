<?php

/**
 * Classe di utility responsabile della gestione delle sessioni. Attraverso di essa è possibile creare o rimuovere una
 * sessione e aggiungere o rimuovere dati da essa.
 */
class USession
{

    /**
     * Nel momento in cui si crea una istanza allora viene anche attivata o recuperata la sessione.
     */
    public function __construct() {
        session_start();
    }

    /**
     * Restituisce il valore della variabile di sessione avente come nome la chiave fornita in ingresso.
     * Se la chiave non esiste allora viene restituito null.
     * Prestare attenzione, il valore restituito è in formato stringa e quindi è necessario eseguire opportunamente un
     * casting o un serialize prima di poter usare il valore ottenuto.
     * @param string $key Nome della variabile di sessione da recuperare.
     * @return string|null Valore restituito
     */
    public function getValue(string $key): ?string
    {

        if (isset($_SESSION[$key])) {
            return $result = $_SESSION[$key];
        } else {
            return null;
        }

    }

    /**
     * Aggiunge una variabile di sessione con nome uguale alla chiave e valore uguale al valore forniti in ingresso.
     * Prestare attenzione, si accettano solo valori di tipo stringa e quindi è necessario eseguire opportunamente un
     * casting o un serialize prima di fornire il valore in ingresso.
     * @param string $key Nome da assegnare alla variabile di sessione.
     * @param string $value Valore da salvare nella variabile di sessione.
     */
    public function setValue(string $key, string $value): void
    {

        $_SESSION[$key] = $value;

    }

    /**
     * Rimuove la variabile di sessione avente come nome la chiave fornita in ingresso.
     * Se la chiave è inesistente allora viene restituito false, altrimenti true in quanto l'operazione è andata a buon
     * fine.
     * @param string $key Nome della variabile di sessione da rimuovere.
     * @return bool Esito operazione.
     */
    public function removeValue(string $key): bool
    {

        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
            return true;
        } else {
            return false;
        }

    }

    /**
     * Aggiorna una variabile di sessione, se presente, con il nuovo valore fornito in ingresso.
     * E' necessario anche fornire la chiave per sapere quale variabile aggiornare.
     * Se la variabile non esiste allora viene restituito false, altrimenti true in quanto l'operazione è andata a buon
     * fine.
     * @param string $key Nome della variabile di sessione da aggiornare.
     * @param string $value Nuovo valore da dare alla variabile di sessione.
     * @return bool Esito operazione.
     */
    public function updateValue(string $key, string $value): bool
    {

       if (isset($_SESSION[$key])) {
           $_SESSION[$key] = $value;
           return true;
       } else {
           return false;
       }

    }

    /**
     * Elimina in modo completo la sessione:
     * - aggiorna il cookie di sessione e vi assegna un valore nullo, così facendo non è più possibile accedere al file
     * di sessione;
     * - rimuove tutte le variabili di sessione;
     * - rimuove tutti i dati associati alla sessione corrente.
     */
    public function deleteSession(): void {

        setcookie('PHPSESSID', '');
        session_unset();
        session_destroy();

    }
}