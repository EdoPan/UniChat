<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

/**
 * ECategoria permette di instanziare oggetti responsabili del mantenimento dei valori delle categoria dei thread.
 */
class ECategoria implements JsonSerializable {

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
            "dimensione" => "",
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
     * @throws ValidationException
     */
    public function __construct(?int $id, string $nome, ?array $icona, string $descrizione)
    {
        if(isset($id)){
            $this->id = $id;
        } else {
            $this->id = 0;
        }

        try {
            $validazione = Validazione::getInstance();
            $validazione->validaStringa($nome);
        } catch (ValidationException $e) {
            throw new ValidationException($e->getMessage(), $e->getCode());
        }

        $nomeMaiuscolo = strtoupper($nome);
        $this->nome = $nomeMaiuscolo;

        if(isset($icona)){
            try {
                $validazione = Validazione::getInstance();
                $validazione->validaImmagine($icona['tipo'], (int)$icona['dimensione']);
            } catch (ValidationException $e) {
                throw new ValidationException($e->getMessage(), $e->getCode());
            }
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
     * @throws ValidationException
     */
    public function setNome(string $nome): void
    {
        try {
            $validazione = Validazione::getInstance();
            $validazione->validaStringa($nome);
        } catch (ValidationException $e) {
            throw new ValidationException($e->getMessage(), $e->getCode());
        }

        $nomeMaiuscolo = strtoupper($nome);
        $this->nome = $nomeMaiuscolo;
    }

    /**
     * Metodo per impostare l'icona della categoria.
     * @param array $icona
     */
    public function setIcona(array $icona): void
    {
        try {
            $validazione = Validazione::getInstance();
            $validazione->validaImmagine($icona['tipo'], (int)$icona['dimensione']);
        } catch (ValidationException $e) {
            throw new ValidationException($e->getMessage(), $e->getCode());
        }
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

    /**
     * Restituisce lo stato di un oggetto ECategoria in formato JSON.
     * @return array Stato dell'oggetto in formato JSON.
     */
    public function jsonSerialize(): array
    {
        $result = array(
            "id" => $this->id,
            "nome" => $this->nome,
            "descrizione" => $this->descrizione
        );
        return $result;
    }
}