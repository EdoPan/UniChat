<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";


/**
 * La classe EMessaggio ci permette di instanziare messaggi di chat.
 */
class EMessaggio {

    /**
     * Imposta il formato del metodo data.
     * @var string
     */
    private static string $formatoData = "Y-m-d H:i:s";

    /**
     * Identificativo dell'oggetto EMessaggio.
     * @var int
     */
    private int $id;

    /**
     * Testo del messaggio.
     * @var string
     */
    private string $testo;

    /**
     * Data di pubblicazione del messaggio.
     * @var string|false
     */
    private string $data;

    /**
     * Utente che ha pubblicato il messaggio.
     * @var EUser
     */
    private EUser $autoreMessaggio;

    /**
     * @param int|null $id
     * @param string $testo
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
}