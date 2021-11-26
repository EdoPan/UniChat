<?php
declare(strict_types = 1);
require_once __DIR__.DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "utility.php";

/**
 * Classe entity di Valutazione.
 */
class EValutazione
{

    /**
     * Identificativo della valutazione del thread.
     * @var int
     */
    private  $id;

    /**
     * Valore della valutazione del thread.
     * @var int
     */
    private  $totale;

    /**
     * Array contenente gli utenti che hanno valutato positivamente il thread.
     * @var array
     */
    private  $utentiPositivi;

    /**
     * Array contenente gli utenti che hanno valutato negativamente il thread.
     * @var array
     */
    private  $utentiNegativi;

    /**
     * Costante con cui si indica il fatto che ci sia stato un problema con l'aggiornamento della valutazione.
     */
    const ERRORE_VOTO = 0;

    /**
     * Costante con cui si indica il fatto che sia stato espresso un voto positivo.
     */
    const VOTO_POSITIVO = 1;

    /**
     * Costante con cui si indica il fatto che il voto positivo, precedentemente espresso, è stato annullato.
     */
    const VOTO_POSITIVO_ANNULlATO = 2;

    /**
     * Costante con cui si indica il fatto che il voto negativo, precedentemente espresso, è stato cambiato in positivo.
     */
    const VOTO_POSITIVO_CAMBIATO = 3;

    /**
     * Costante con cui si indica il fatto che sia stato espresso un voto negativo.
     */
    const VOTO_NEGATIVO = 4;

    /**
     * Costante con cui si indica il fatto che il voto negativo, precedentemente espresso, è stato annullato.
     */
    const VOTO_NEGATIVO_ANNULLATO = 5;

    /**
     * Costante con cui si indica il fatto che il voto positivo, precedentemente espresso, è stato cambiato in negativo.
     */
    const VOTO_NEGATIVO_CAMBIATO = 6;


    /**
     * Se la valutazione è stata appena aggiunta al thread allora non possiede un id, viene passato come null e settato
     * a 0.
     * Quando la valutazione viene appena aggiunta ha valore 0, il totale viene passato come null.
     * Quando la valutazione viene appena aggiunta non ha ancora utenti che hanno espresso il proprio giudizio, viene
     * passato null e settati i due array come vuoti.
     * @param int|null $id Identificativo della valutazione da creare, può non essere impostato.
     * @param int|null $totale Totale della valutazione da creare, può non essere impostato.
     * @param array|null $utentiPositivi Elenco utenti che hanno valutato positivamente, può non essere impostato.
     * @param array|null $utentiNegativi Elenco utenti che hanno valutato negativamente, può non essere impostato.
     */
    public function __construct(?int $id, ?int $totale, ?array $utentiPositivi, ?array $utentiNegativi)
    {
        if(isset($id)) {
            $this->id = $id;
        } else {
            $this->id = 0;
        }
        if(isset($totale)){
            $this->totale = $totale;
        } else {
            $this->totale = 0;
        }
        if(isset($utentiPositivi)){
            $this->utentiPositivi = $utentiPositivi;
        } else {
            $this->utentiPositivi = array();
        }
        if(isset($utentiNegativi)){
            $this->utentiNegativi = $utentiNegativi;
        } else {
            $this->utentiNegativi = array();
        }
    }

    /**
     * Restituisce l'identificativo della valutazione del thread.
     * @return int Identificativo della valutazione.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Restituisce il valore della valutazione.
     * @return int Totale della valutazione.
     */
    public function getTotale(): int
    {
        return $this->totale;
    }

    /**
     * Restituisce l'array contenente gli utenti che hanno espresso un giudizio positivo.
     * @return array Elenco degli utenti che hanno espresso un voto positivo, può essere vuoto.
     */
    public function getUtentiPositivi(): array
    {
        return $this->utentiPositivi;
    }

    /**
     * Restituisce l'array contenente gli utenti che hanno espresso un giudizio negativo.
     * @return array Elenco degli utenti che hanno espresso un voto negativo, può essere vuoto.
     */
    public function getUtentiNegativi(): array
    {
        return $this->utentiNegativi;
    }

    /**
     * Imposta l'identificativo della valutazione.
     * @param int $id Identificativo della valutazione da assegnare.
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Imposta il totale della valutazione.
     * @param int $totale Totale della valutazione da assegnare.
     */
    public function setTotale(int $totale): void
    {
        $this->totale = $totale;
    }

    /**
     * Imposta l'array contenente gli utenti che hanno espresso un giudizio positivo.
     * @param array $utentiPositivi Elenco degli utenti che hanno espresso un giudizio positivo da assegnare.
     */
    public function setUtentiPositivi(array $utentiPositivi): void
    {
        $this->utentiPositivi = $utentiPositivi;
    }

    /**
     * Imposta l'array contenente gli utenti che hanno espresso un giudizio negativo.
     * @param array $utentiNegativi Elenco degli utenti che hanno espresso un giudizio negativo da assegnare.
     */
    public function setUtentiNegativi(array $utentiNegativi): void
    {
        $this->utentiNegativi = $utentiNegativi;
    }


    /**
     * Permette di aggiornare il totale della valutazione e l'elenco degli utenti che hanno espresso un giudizio.
     * Sono previste le seguenti situazioni:
     * - l'utente esprime un giudizio positivo;
     * - l'utente esprime nuovamente un giudizio positivo e così facendo annulla il giudizio precedentemente espresso;
     * - l'utente aveva espresso un giudizio negativo ma ha cambiato idea ed esprime un nuovo giudizio, questa volta
     * positivo;
     * - l'utente esprime un giudizio negativo;
     * - l'utente esprime nuovamente un giudizio negativo e così facendo annulla il giudizio precedentemente espresso;
     * - l'utente aveva espresso un giudizio positivo ma ha cambiato idea ed esprime un nuovo giudizio, questa volta
     * negativo.
     * Viene restituito un valore rappresentante la situazione che si è verificata.
     * @param EUser $user Utente che esprime il giudizio.
     * @param int $valore Valore che indica se il giudizio è positivo o negativo.
     * @return int Valore che indica la tipologia di situazione che si è verificata.
     */
    public function valuta(EUser $user, int $valore): int
    {
        $indicePositivi = array_search($user, $this->utentiPositivi);
        $indiceNegativi = array_search($user, $this->utentiNegativi);
        if($valore >= 0){
            /*
             * L'utente ha votato positivamente.
             */
            if ($indicePositivi === false && $indiceNegativi === false) {

                /*
                 * L'utente è la prima volta che esprime un giudizio.
                 */
                $this->totale = $this->totale + 1;
                $this->utentiPositivi[] = $user;
                return self::VOTO_POSITIVO;
            } else if ($indicePositivi !== false && $indiceNegativi === false) {

                /*
                 * L'utente aveva espresso un giudizio positivo.
                 */
                $this->totale = $this->totale - 1;
                unset($this->utentiPositivi[$indicePositivi]);
                $this->utentiPositivi = array_values($this->utentiPositivi);
                return self::VOTO_POSITIVO_ANNULlATO;
            } else if ($indicePositivi === false && $indiceNegativi !== false) {

                /*
                 * L'utente aveva espresso un giudizio negativo.
                 */
                $this->totale = $this->totale + 2;
                unset($this->utentiNegativi[$indiceNegativi]);
                $this->utentiNegativi = array_values($this->utentiNegativi);
                $this->utentiPositivi[] = $user;
                return self::VOTO_POSITIVO_CAMBIATO;
            } else {
                return self::ERRORE_VOTO;
            }
        } else if ($valore < 0){

            /*
             * L'utente ha votato negativamente.
             */
            if ($indicePositivi === false && $indiceNegativi === false) {

                /*
                * L'utente è la prima volta che esprime un giudizio.
                */
                $this->totale = $this->totale - 1;
                $this->utentiNegativi[] = $user;
                return self::VOTO_NEGATIVO;
            } else if ($indicePositivi === false && $indiceNegativi !== false) {
                /*
                 * L'utente aveva espresso un giudizio negativo.
                 */
                $this->totale = $this->totale + 1;
                unset($this->utentiNegativi[$indiceNegativi]);
                $this->utentiNegativi = array_values($this->utentiNegativi);
                return self::VOTO_NEGATIVO_ANNULLATO;
            } else if ($indicePositivi !== false && $indiceNegativi === false) {
                /*
                 * L'utente aveva espresso un giudizio positivo.
                 */
                $this->totale = $this->totale - 2;
                unset($this->utentiPositivi[$indicePositivi]);
                $this->utentiPositivi = array_values($this->utentiPositivi);
                $this->utentiNegativi[] = $user;
                return self::VOTO_NEGATIVO_CAMBIATO;
            } else {
                return self::ERRORE_VOTO;
            }
        } else {
            return self::ERRORE_VOTO;
        }
    }

    /**
     * Verifica che l'utente fornito in ingresso abbia espresso un giudizio.
     * Se l'utente ha votato positivamente allora viene restituito VOTO_POSITIVO.
     * Se l'utente ha votato negativamente allora viene restituito VOTO_NEGATIVO.
     * Se l'utente non ha votato allora viene restituito null.
     * @param $user
     * @return int|null Esito del controllo.
     */
    public function espressoGiudizio($user): ?int
    {
        if (array_search($user, $this->utentiPositivi) !== false) {
            return self::VOTO_POSITIVO;
        } else if (array_search($user, $this->utentiNegativi) !== false) {
            return self::VOTO_NEGATIVO;
        } else {
            return null;
        }
    }
}