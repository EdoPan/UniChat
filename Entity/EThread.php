<?php
declare(strict_types = 1);
require_once __DIR__.DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "utility.php";

/**
 * Classe entity di Thread.
 */
class EThread
{

    /**
     * Identificativo del thread.
     * @var int
     */
    private  $id;

    /**
     * Titolo del thread.
     * @var string
     */
    private  $titolo;

    /**
     * Testo del thread.
     * @var string
     */
    private  $testo;

    /**
     * Data e ora di creazione del thread.
     * @var string|false
     */
    private  $data;

    /**
     * Allegati aggiuntivi del thread. Si tratta di un array contente array associativi che rappresentano il file
     * allegato al thread. Ogni array associativo presenta i seguenti campi: l'identificativo del file, il nome del
     * file, la dimensione del file, il tipo del file e il file stesso rappresentato in formato stringa e codificato in
     * BASE64.
     * @var array
     */
    private $allegati;

    /**
     * Constante che riporta il formato di data ed ora utilizzato per tenere traccia di quando è stato creato il thread.
     */
    private static   $formatoData = "Y-m-d H:i:s";

    /**
     * Autore del thread.
     * @var EUser
     */
    private  $autoreThread;

    /**
     * Categoria di appartenenza del thread.
     * @var ECategoria
     */
    private  $categoriaThread;

    /**
     * Valutazione del thread da parte di altri utenti.
     * @var EValutazione
     */
    private  $valutazione;


    /**
     * Risposte al thread. Si tratta un array di ERisposta.
     * @var array
     */
    private  $risposte;

    /**
     * Costruttore di EThread
     * Se il thread è stato appena creato allora non ha un id e quindi viene passato come null e impostato a 0.
     * Se il thread è appena stato creato allora non ha la data e l'ora di creazione, quindi viene passato come null e
     * vengono impostate la data e l'ora attuali.
     * Se l'autore non ha aggiunto allegati allora viene passato null e impostato un array vuoto.
     * Se il thread non ha ancora delle risposte allora viene passato null e impostato un array vuoto.
     * Prima di procedere con la creazione dell'oggetto si esegue una validazione sui dati, in particolare si verifica
     * che ciascun allegato abbia un formato adatto e non abbia dimensioni troppo elevati. Se la validazione non va a buon
     * fine allora viene lanciata una eccezione.
     * @param int|null $id Identificativo del thread da creare, può non essere impostato.
     * @param string $titolo Titolo del thread da creare.
     * @param string $testo Testo del thread da creare.
     * @param string|null $data Data di creazione del thread da creare, può non essere impostato
     * @param array|null $allegati Allegati del thread da creare, possono non essere presenti.
     * @param EUser $autoreThread Autore del thread da creare.
     * @param ECategoria $categoriaThread Categoria di appartenenza del thread da creare.
     * @param EValutazione $valutazione Valutazione associata al thread da creare.
     * @param array|null $risposte Risposte associate al thread da creare, possono non essere presenti.
     * @throws ValidationException Eccezione lanciata in caso di problemi con la validazione dei dati.
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
            $this->data = date(self::$formatoData);
        }
        if(isset($allegati)){

            $validazione = Validazione::getInstance();
            foreach ($allegati as $allegato) {
                try {
                    $validazione->validaAllegato($allegato['tipo'], (int)$allegato['dimensione']);
                } catch (ValidationException $e) {
                    throw new ValidationException($e->getMessage(), $e->getCode());
                }
            }
            $this->allegati = $allegati;

        } else {
            $this->allegati = array();
        }
        $this->autoreThread = $autoreThread;
        $this->categoriaThread = $categoriaThread;
        $this->valutazione = $valutazione;
        if(isset($risposte)){
            $this->risposte = $risposte;
        } else {
            $this->risposte = array();
        }
    }

    /**
     * Restituisce l'identificativo del thread.
     * @return int Identificativo del thread.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Restituisce il titolo del thread.
     * @return string Titolo del thread.
     */
    public function getTitolo(): string
    {
        return $this->titolo;
    }

    /**
     * Restituisce il testo del thread.
     * @return string Testo del thread.
     */
    public function getTesto(): string
    {
        return $this->testo;
    }

    /**
     * Restituisce la data del thread.
     * @return string Data di creazione del thread.
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * Restituisce un array contenente array associativi che rappresentano i file allegati.
     * @return array Allegati del thread.
     */
    public function getAllegati(): array
    {
        return $this->allegati;
    }

    /**
     * Restituisce l'autore del thread.
     * @return EUser Autore del thread.
     */
    public function getAutoreThread(): EUser
    {
        return $this->autoreThread;
    }

    /**
     * Restituisce la categoria di appartenenza del thread.
     * @return ECategoria Categoria del thread.
     */
    public function getCategoriaThread(): ECategoria
    {
        return $this->categoriaThread;
    }

    /**
     * Restituisce la valutazione del thread.
     * @return EValutazione Valutazione del thread.
     */
    public function getValutazione(): EValutazione
    {
        return $this->valutazione;
    }

    /**
     * Restituisce un array contenente le risposte al thread.
     * @return array Risposte del thread.
     */
    public function getRisposte(): array
    {
        return $this->risposte;
    }

    /**
     * Imposta l'identificativo del thread.
     * @param int $id Identificativo thread da assegnare.
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Imposta il titolo del thread.
     * @param string $titolo Titolo thread da assegnare.
     */
    public function setTitolo(string $titolo): void
    {
        $this->titolo = $titolo;
    }

    /**
     * Imposta il testo del thread.
     * @param string $testo Testo thread da assegnare.
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
        $this->data = date(self::$formatoData);
    }

    /**
     * Imposta gli allegati del thread.
     * @param array $allegati Allegati thread da assegnare.
     * @throws ValidationException Eccezione lanciata in caso di problemi con la validazione dei dati
     */
    public function setAllegati(array $allegati): void
    {

        $validazione = Validazione::getInstance();
        foreach ($allegati as $allegato) {
            try {
                $validazione->validaAllegato($allegato['tipo'], $allegato['dimensione']);
            } catch (ValidationException $e) {
                throw new ValidationException($e->getMessage(), $e->getCode());
            }
        }

        $this->allegati = $allegati;
    }

    /**
     * Imposta l'autore del thread.
     * @param EUser $autoreThread Autore thread da assegnare.
     */
    public function setAutoreThread(EUser $autoreThread): void
    {
        $this->autoreThread = $autoreThread;
    }

    /**
     * Imposta la categoria di appartenenza del thread.
     * @param ECategoria $categoriaThread Categoria thread da assegnare.
     */
    public function setCategoriaThread(ECategoria $categoriaThread): void
    {
        $this->categoriaThread = $categoriaThread;
    }

    /**
     * Imposta la valutazione del thread.
     * @param EValutazione $valutazione Valutazione thread da assegnare.
     */
    public function setValutazione(EValutazione $valutazione): void
    {
        $this->valutazione = $valutazione;
    }

    /**
     * Imposta le risposte del thread.
     * @param array $risposte Risposte thread da assegnare.
     */
    public function setRisposte(array $risposte): void
    {
        $this->risposte = $risposte;
    }


    /**
     * Restituisce il numero di risposte presenti nel thread.
     * @return int Numero di risposte presenti nel thread.
     */
    public function contaRisposte(): int
    {
        $numRisposte = count($this->risposte);
        return $numRisposte;
    }
}