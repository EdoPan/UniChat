<?php

    declare(strict_types = 1);
    require_once __DIR__.DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "utility.php";


/**
 * La classe EMessaggio ci permette d'instanziare messaggi di chat.
 */
class EMessaggio implements JsonSerializable {


    /**
     * Identificativo dell'oggetto EMessaggio.
     * @var int
     */
    private  $id;

    /**
     * Testo del messaggio.
     * @var string
     */
    private  $testo;

    /**
     * Data di pubblicazione del messaggio.
     * @var string|false
     */
    private  $data;

    /**
     * Imposta il formato del metodo data.
     * @var string
     */
    private static  $formatoData = "Y-m-d H:i:s";

    /**
     * Utente che ha pubblicato il messaggio.
     * @var EUser
     */
    private  $autoreMessaggio;

    /**
     * @param int|null $id
     * @param string $testo
     * @param string|null $data
     * @param EUser $autoreMessaggio
     */
    public function __construct(?int $id, string $testo, ?string $data, EUser $autoreMessaggio)
    {
        if(isset($id)){
            $this->id = $id;
        } else {
            $this->id = 0;
        }
        $this->testo = $testo;
        if ($data!=null) {
            $this->data=$data;
        } else {
            $this->data = date(self::$formatoData);
        }
        $this->autoreMessaggio = $autoreMessaggio;
    }

    /**
     * Metodo per ottenere l'identificativo dell'oggetto EMessaggio.
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Metodo per ottenere il testo del messaggio.
     * @return string
     */
    public function getTesto(): string
    {
        return $this->testo;
    }

    /**
     * Metodo per ottenere la data del messaggio.
     * @return false|string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Metodo per ottenere l'utente che ha pubblicato il messaggio.
     * @return EUser
     */
    public function getAutoreMessaggio(): EUser
    {
        return $this->autoreMessaggio;
    }

    /**
     * Metodo per impostare l'identificativo dell'oggetto EMessaggio.
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Metodo per impostare il testo del messaggio.
     * @param string $testo
     */
    public function setTesto(string $testo): void
    {
        $this->testo = $testo;
    }

    /**
     * Metodo per impostare la data del messaggio.
     */
    public function setData(): void
    {
        $this->data = date(self::$formatoData);
    }

    /**
     * Metodo per impostare l'utente che ha pubblicato il messaggio.
     * @param EUser $autoreMessaggio
     */
    public function setAutoreMessaggio(EUser $autoreMessaggio): void
    {
        $this->autoreMessaggio = $autoreMessaggio;
    }

    /**
     * Restituisce lo stato di un oggetto EMessaggio in formato JSON.
     * @return array Stato dell'oggetto in formato JSON.
     */
    public function jsonSerialize(): array
    {
        $result = array(
            'idMessaggio' => $this->id,
            'testo' => $this->testo,
            'data' => $this->data,
            'idAutore' => $this->autoreMessaggio->getId(),
            'nomeAutore' => $this->autoreMessaggio->getNome(),
            'cognomeAutore' => $this->autoreMessaggio->getCognome()
        );
        return $result;
    }
}