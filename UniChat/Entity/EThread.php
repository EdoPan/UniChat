<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

/**
 *
 */
class EThread
    {


    /**
     * Constante che riporta il formato di data ed ora utlizzato per tenere traccia di quando è stato creato il thread.
     */
    const FORMATO_DATA = "Y-m-d H:i:s";

    /**
     * Identificativo del thread.
     * @var int
     */
    private int $id;

    /**
     * Titolo del thread.
     * @var string
     */
    private string $titolo;

    /**
     * Testo del thread.
     * @var string
     */
    private string $testo;

    /**
     * Data e ora di creazione del thread.
     * @var string|false
     */
    private string $data;

    /**
     * Allegati aggiuntivi del thread. Si tratta di un array contente array associativi che rappresentano il file
     * allegato al thread. Ogni array associativo presenta i seguenti campi: l'identificativo del file, il nome del
     * file, la dimensione del file, il tipo del file e il file stesso rappresentato in formato stringa.
     * @var array
     */
    private array $allegati;

    /**
     * Autore del thread.
     * @var EUser
     */
    private EUser $autoreThread;

    /**
     * Categoria di appartenenza del thread.
     * @var ECategoria
     */
    private ECategoria $categoriaThread;

    /**
     * Valutazione del thread da parte di altri utenti.
     * @var EValutazione
     */
    private EValutazione $valutazione;

    /**
     * Risposte al thread. Si tratta un array di ERisposta.
     * @var array
     */
    private array $risposte;

    /**
     * Costruttore di EThread
     * Se il thread è stato appena creato allora non ha un id e quindi viene passato come null e impostato a 0.
     * Se il thread è appena stato creato allora non ha la data e l'ora di creazione, quindi viene passato come null e
     * vengono impostate la data e l'ora.
     * Se l'autore non ha aggiunto allegati allora viene passato null e impostato un array vuoto.
     * Se il thread non ha ancora delle risposte allora viene passato null e impostato un array vuoto.
     * @param int|null $id
     * @param string $titolo
     * @param string $testo
     * @param string|null $data
     * @param array|null $allegati
     * @param EUser $autoreThread
     * @param ECategoria $categoriaThread
     * @param EValutazione $valutazione
     * @param array|null $risposte
     */
    public function __construct(?int $id, string $titolo, string $testo, ?string $data, ?array $allegati, EUser $autoreThread,
                                ECategoria $categoriaThread, EValutazione $valutazione, ?array $risposte)
    {
        if(isset($id)) {
            $this->id = $id;
        } else {
            $this->id = 0;
        }
        $this->titolo = $titolo;
        $this->testo = $testo;
        if(isset($data)){
            $this->data = $data;
        } else {
            $this->data = date(self::FORMATO_DATA);
        }
        if(isset($allegati)){
            $this->allegati = $allegati;
        } else {
            $this->allegati = array();
        }
        $this->autoreThread = $autoreThread;
        $this->categoriaThread = $categoriaThread;
        $this->valutazione = clone $valutazione;
        if(isset($risposte)){
            $this->risposte = $risposte;
        } else {
            $this->risposte = array();
        }
    }

    /**
     * Restituisce l'identificativo del thread.
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Restituisce il titolo del thread.
     * @return string
     */
    public function getTitolo(): string
    {
        return $this->titolo;
    }

    /**
     * Restituisce il testo del thread.
     * @return string
     */
    public function getTesto(): string
    {
        return $this->testo;
    }

    /**
     * Restituisce la data del thread.
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * Restituisce un array contenente array associativi che rapprensentano i file allegati.
     * @return array
     */
    public function getAllegati(): array
    {
        return $this->allegati;
    }

    /**
     * Restituisce l'autore del thread.
     * @return EUser
     */
    public function getAutoreThread(): EUser
    {
        return $this->autoreThread;
    }

    /**
     * Restituisce la categoria di appartenenza del thread.
     * @return ECategoria
     */
    public function getCategoriaThread(): ECategoria
    {
        return $this->categoriaThread;
    }

    /**
     * Restituisce la valutazione del thread.
     * @return EValutazione
     */
    public function getValutazione(): EValutazione
    {
        return clone $this->valutazione;
    }

    /**
     * Restituisce un array contenente le risposte al thread.
     * @return array
     */
    public function getRisposte(): array
    {
        return $this->risposte;
    }

    /**
     * Imposta l'identificativo del thread.
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Imposta il titolo del thread.
     * @param string $titolo
     */
    public function setTitolo(string $titolo): void
    {
        $this->titolo = $titolo;
    }

    /**
     * Imposta il testo del thread.
     * @param string $testo
     */
    public function setTesto(string $testo): void
    {
        $this->testo = $testo;
    }

    /**
     * Imposta la data del thread alla data e l'ora attuale.
     */
    public function setData(): void
    {
        $this->data = date(self::FORMATO_DATA);
    }

    /**
     * Imposta gli allegati del thread.
     * @param array $allegati
     */
    public function setAllegati(array $allegati): void
    {
        $this->allegati = $allegati;
    }

    /**
     * Imposta l'autore del thread.
     * @param EUser $autoreThread
     */
    public function setAutoreThread(EUser $autoreThread): void
    {
        $this->autoreThread = $autoreThread;
    }

    /**
     * Imposta la categoria di appartenenza del thread.
     * @param ECategoria $categoriaThread
     */
    public function setCategoriaThread(ECategoria $categoriaThread): void
    {
        $this->categoriaThread = $categoriaThread;
    }

    /**
     * Imposta la valutazione del thread.
     * @param EValutazione $valutazione
     */
    public function setValutazione(EValutazione $valutazione): void
    {
        $this->valutazione = clone $valutazione;
    }

    /**
     * Imposta le risposte del thread.
     * @param array $risposte
     */
    public function setRisposte(array $risposte): void
    {
        $this->risposte = $risposte;
    }
    }