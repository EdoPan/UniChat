<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

/**
 * ECategoria permette di instanziare oggetti responsabili del mantenimento dei valori delle categoria dei thread.
 */
class ECategoria {

    /**
     * Identificativo dell'oggetto ECategoria.
     * @var int
     */
    private int $id;

    /**
     * Nome della categoria.
     * @var string
     */
    private string $nome;

    /**
     * Icona della categoria.
     * @var array
     */
    private array $icona = array(
            "id"=> 0,
            "nome" => "",
            "tipo" => "",
            "immagine" => ""
    );

    /**
     * Testo contenente la descrizione della categoria.
     * @var string
     */
    private string $descrizione;

    /**
     * @param int|null $id
     * @param string $nome
     * @param array|null $icona
     * @param string $descrizione
     */
    public function __construct(?int $id, string $nome, ?array $icona, string $descrizione)
    {
        if(isset($id)){
            $this->id = $id;
        } else {
            $this->id = 0;
        }
        $this->nome = $nome;
        if(isset($icona)){
            $this->icona = $icona;
        } else {
            $this->icona["id"] = 1; //arrray che regge l'immagine predefinito.jpg
        }
        $this->descrizione = $descrizione;
    }

    /**
     * Metodo per ottenere l'identificativo della categoria.
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Metodo per ottenere il nome della categoria.
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * Metodo per ottenere l'icona della categoria.
     * @return array
     */
    public function getIcona(): array
    {
        return $this->icona;
    }

    /**
     * Metodo per ottenere la descrizione della categoria.
     * @return string
     */
    public function getDescrizione(): string
    {
        return $this->descrizione;
    }

    /**
     * Metodo per impostare l'identificativo della categoria.
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Metodo per impostare il nome della categoria.
     * @param string $nome
     */
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    /**
     * Metodo per impostare l'icona della categoria.
     * @param array $icona
     */
    public function setIcona(array $icona): void
    {
        $this->icona = $icona;
    }

    /**
     * Metodo per impostare la descrizione della categoria.
     * @param string $descrizione
     */
    public function setDescrizione(string $descrizione): void
    {
        $this->descrizione = $descrizione;
    }
}