<?php

class USession
{

    /**
     * Nel momento in cui si crea una istanza allora viene anche attivata o recuperata la sessione.
     */
    public function __construct() {
        session_start();
    }

    /**
     * Restituisce il valore presente nella sessione e associato alla chiave fornita in ingresso.
     * Se la chiave non esiste allora viene restituito null.
     * Prestare attenzione, il valore restituito è in formato stringa e quindi è necessario eseguire opportunamente un
     * casting o un serialize prima di poter usare il valore ottenuto.
     * @param string $key Chiave associata al valore da recuperare.
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
     * Aggiunge un valore alla sessione e vi attribuisce la chiave fornita in ingresso.
     * Prestare attenzione, si accettano solo valori di tipo stringa e quindi è necessario eseguire opportunamente un
     * casting o un serialize prima di fornire il valore in ingresso.
     * @param string $key Chiave da assegnare al valore.
     * @param string $value Valore da salvare nella sessione.
     */
    public function setValue(string $key, string $value): void
    {

        $_SESSION[$key] = $value;

    }

    /**
     * Rimuove dalla sessione, il valore a cui è associata la chiave fornita in ingresso.
     * Se la chiave è inesistente allora viene restituito false, altrimenti true.
     * @param string $key Chiave associata al valore da rimuovere.
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
     * Aggiorna il valore, se presente nella sessione, con il nuovo fornito in ingresso.
     * E' necessario anche fornire la chiave per sapere quale valore aggiornare.
     * Se la chiave fornita non esiste allora viene restituito false, altrimenti true.
     * @param string $key Chiave associata al valore da aggiornare.
     * @param string $value Nuovo valore.
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
     * Elimina in modo completo la sessione.
     */
    public function deleteSession(): void {

        setcookie('PHPSESSID', '');
        session_unset();
        session_destroy();

    }
}