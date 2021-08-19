<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

/**
 *
 */
class EValutazione
    {

    /**
     * Identificativo della valutazione del thread.
     * @var int
     */
    private int $id;

    /**
     * Valore della valutazione del thread.
     * @var int
     */
    private int $totale;

    /**
     * Array contenente gli utenti che hanno valutato positivamente il thread.
     * @var array
     */
    private array $utentiPositivi;

    /**
     * Array contenente gli utenti che hanno valutato negativamente il thread.
     * @var array
     */
    private array $utentiNegativi;

    /**
     * Se la valutazione è stata appena aggiunta al thread allora non possiede un id, viene passato come null e settato
     * a 0.
     * Quando la valutazione viene appena aggiunta ha valore 0, il totale viene passato come null.
     * Quando la valutazione viene appena aggiunta non ha ancora utenti che hanno espresso il proprio giudizio, viene
     * passato null e settati i due array come vuoti.
     * @param int|null $id
     * @param int|null $totale
     * @param array|null $utentiPositivi
     * @param array|null $utentiNegativi
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
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Restituisce il valore della valutazione.
     * @return int
     */
    public function getTotale(): int
    {
        return $this->totale;
    }

    /**
     * Restituisce l'array contenente gli utenti che hanno espresso un giudzio positivo.
     * @return array
     */
    public function getUtentiPositivi(): array
    {
        return $this->utentiPositivi;
    }

    /**
     * Restituisce l'array contenente gli utenti che hanno espresso un giudizio negativo.
     * @return array
     */
    public function getUtentiNegativi(): array
    {
        return $this->utentiNegativi;
    }

    /**
     * Imposta l'identificativo della valutazione.
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Imposta il totale della valutazione.
     * @param int $totale
     */
    public function setTotale(int $totale): void
    {
        $this->totale = $totale;
    }

    /**
     * Imposta l'array contenente gli utenti che hanno espresso un giudizio positivo.
     * @param array $utentiPositivi
     */
    public function setUtentiPositivi(array $utentiPositivi): void
    {
        $this->utentiPositivi = $utentiPositivi;
    }

    /**
     * Imposta l'array contenente gli utenti che hanno espresso un giudizio negativo.
     * @param array $utentiNegativi
     */
    public function setUtentiNegativi(array $utentiNegativi): void
    {
        $this->utentiNegativi = $utentiNegativi;
    }


    /**
     * @param EUser $user
     * @param int $valore
     */
    public function valuta(EUser $user, int $valore): void
    {
        if($valore == 1){
            $this->totale = $this->totale + 1;
            $this->utentiPositivi[] = $user;
        } else if ($valore == -1){
            $this->totale = $this->totale - 1;
            $this->utentiNegativi[] = $user;
        }
    }
    }