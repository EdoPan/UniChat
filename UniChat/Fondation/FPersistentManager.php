<?php

declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";

/**
 * Classe Foundation Persistent Manager. Ha il compito di gestire la persistenza di tutti gli oggetti entity.
 */
class FPersistentManager
{
    /*
     * Costanti per la gestione della tipologia di classe entity coinvolta.
     */

    /**
     * Costante che indica il voler lavorare con l'entity EUser.
     */
    const ENTITY_USER = 1;

    /**
     * Costante che indica il voler lavorare con l'entity EModeratore.
     */
    const ENTITY_MODERATORE = 2;

    /**
     * Costante che indica il voler lavorare con l'entity EAdmin.
     */
    const ENTITY_ADMIN = 3;

    /**
     * Costante che indica il voler lavorare con l'entity EThread.
     */
    const ENTITY_THREAD = 4;

    /**
     * Costante che indica il voler lavorare con l'entity ERisposta.
     */
    const ENTITY_RISPOSTA = 5;

    /**
     * Costante che indica il voler lavorare con l'entity EValutazione.
     */
    const ENTITY_VALUTAZIONE = 6;

    /**
     * Costante che indica il voler lavorare con l'entity ECategoria.
     */
    const ENTITY_CATEGORIA = 7;

    /**
     * Costante che indica il voler lavorare con l'entity EMessaggio.
     */
    const ENTITY_MESSAGGIO = 8;

    /*
     * Costanti con le quali viene specificato di cosa si necessita per eseguire l'operazione richiesta.
     */

    /**
     * Costante che indica che per l'operazione richiesta è necessario usare una proprietà della classe entity con la
     * quale si vuole lavorare.
     */
    const PROPERTY_DEFAULT = 1;

    /**
     * Costante che indica che per l'operazione richiesta è necessario usare una proprietà della classe entity categoria
     * per poter lavorare con la classe entity specificata dalle costanti entity.
     */
    const PROPERTY_BY_CATEGORIA = 2;

    /**
     * Costante che indica che per l'operazione richiesta è necessario usare una proprietà della classe entity thread
     * per poter lavorare con la classe entity specificata dalle costanti entity.
     */
    const PROPERTY_BY_THREAD = 3;

    /**
     * Costante che indica che si vuole lavorare con il thread che sia il più discusso di una determinata categoria.
     */
    const PROPERTY_PIU_DISCUSSO_CATEGORIA = 4;

    /**
     * Costante che indica che si vuole lavorare con il thread che sia quello con la valutazione più alta all'interno di
     * una data categoria.
     */
    const PROPERTY_VALUTAZIONE_MAGGIORE_CATEGORIA = 5;

    /*
     * Costanti che permettono di specificare il tipo di ricerca che si vuole effettuare.
     */

    /**
     * Costante che indica che si vuole fare un ricerca di threads in base al titolo.
     */
    const SEARCH_TYPE_TITOLO = 1;

    /**
     * Costante che indica che si vuole fare una ricerca di threads in base al titolo e appartenenti a determinate
     * categorie.
     */
    const SEARCH_TYPE_TITOLO_CATEGORIE = 2;

    /**
     * Istanza della classe FPersistentManager, si usa per il singleton.
     * @var null|FPersistentManager
     */
    private static $instance = null;

    /**
     * Costruttore di default.
     */
    private function __construct()
        {
        }

    /**
     * Restituisce l'istanza di FPersistentManager. Se già esistente restituisce quella esistente, altrimenti la crea.
     * @return FPersistentManager
     */
    public static function getInstance(): FPersistentManager
    {
        $classe = __CLASS__;
        if(self::$instance == null) {
            self::$instance = new $classe;
        }
        return self::$instance;
    }

    /**
     * Permette di restituire un oggetto del sottosistema Entity.
     * L'oggetto restituito è una istanza della classe entity specificata dall'attributo entityType.
     * L'oggetto viene restituito dato un identificativo che può essere relativo alla classe entity di cui è istanza
     * (PROPERTY_DEFAULT) oppiure relativo ad un'altra classe entity (es. PROPERTY_BY_DEFAULT).
     * Se la combinazione di input non è corretta o durante l'esecuzione dei metodi richiamati vi sono errori, allora
     * viene restituito null.
     * Gli entityType ammessi sono:
     * - ENTITY_USER;
     * - ENTITY_MODERATORE;
     * - ENTITY_ADMIN;
     * - ENTITY_THREAD;
     * - ENTITY_RISPOSTA;
     * - ENTITY_VALUTAZIONE;
     * - ENTITY_CATEGORIA;
     * - ENTITY_MESSAGGIO.
     * @param int $entityType Valore che indica quale entity restituire
     * @param int $property Valore che indica attraverso quale identificativo si ottiene l'entity
     * @param int $id Identificativo dell'entity richiesta o attraverso la quale è possibile ottenere tale entity
     * @return object|null Istanza dell'entity
     * @throws ValidationException Eccezione lanciata in caso di problemi con la validazione dei dati nel momento della
     * creazione di alcune delle istanze entity.
     */
    public function load(int $entityType, int $property, int $id): ?object
    {
        if ($entityType == self::ENTITY_USER && $property == self::PROPERTY_DEFAULT) {
            /*
             * Si vuole ottenere un utente dato il suo identificativo.
             */
            $fUser = FUser::getInstance();
            return $fUser->load($id);

        } else if ($entityType == self::ENTITY_MODERATORE && $property == self::PROPERTY_DEFAULT) {
            /*
             * Si vuole ottenere un moderatore dato il suo identificativo.
             */
            $fUser = FUser::getInstance();
            return $fUser->loadModeratore($id);

        } else if ($entityType == self::ENTITY_ADMIN && $property == self::PROPERTY_DEFAULT) {
            /*
             * Si vuole ottenere un admin dato il suo identificativo.
             */
            $fUser = FUser::getInstance();
            return $fUser->loadAdmin($id);

        } else if ($entityType == self::ENTITY_MODERATORE && $property == self::PROPERTY_BY_CATEGORIA) {
            /*
             * Si vuole ottenere un moderatore dato l'identificativo della categoria che gestisce.
             */
            $fUser = FUser::getInstance();
            return $fUser->loadModeratoreCategoria($id);

        } else if ($entityType == self::ENTITY_THREAD && $property == self::PROPERTY_DEFAULT) {
            /*
             * Si vuole ottenere un thread dato il suo identificativo.
             */
            $fThread = FThread::getInstance();
            return $fThread->load($id);

        } else if ($entityType == self::ENTITY_VALUTAZIONE && $property == self::PROPERTY_DEFAULT) {
            /*
             * Si vuole ottenere una valutazione dato il suo identificativo.
             */
            $fValutazione = FValutazione::getInstance();
            return $fValutazione->load($id);

        } else if ($entityType == self::ENTITY_VALUTAZIONE && $property == self::PROPERTY_BY_THREAD) {
            /*
             * Si vuole ottenere una valutazione dato l'identificativo del thread a cui è associata.
             */
            $fValutazione = FValutazione::getInstance();
            return $fValutazione->loadValutazioneThread($id);

        } else if ($entityType == self::ENTITY_CATEGORIA && $property == self::PROPERTY_DEFAULT) {
            /*
             * Si vuole ottenere una categoria dato il suo identificativo.
             */
            $fCategoria = FCategoria::getInstance();
            return $fCategoria->load($id);

        } else if ($entityType == self::ENTITY_CATEGORIA && $property == self::PROPERTY_BY_THREAD) {
            /*
             * Si vuole ottenere la categoria di un thread di cui viene fornito l'identificativo.
             */
            $fCategoria = FCategoria::getInstance();
            return $fCategoria->loadCategoriaThread($id);

        } else {
            return null;
        }
    }

    /**
     * Restituisce un Euser, un EModeratore o un EAdmin, data l'email fornita in fase di registrazione.
     * Se l'operazione non va a buon fine allora viene restituito null.
     * @param string $email Email dell'utente da recuperare
     * @return EUser|null Istanza recuperata
     */
    public function loadUserByEmail(string $email): ?EUser
    {
        $fUser = FUser::getInstance();
        return $fUser->loadByEmail($email);
    }

    /**
     * Restituisce un allegato, ovvero un array associativo che lo rappresenta, dato l'identificativo dell'allegato
     * stesso.
     * Se l'operazione non va a buon fine allora viene restituito null.
     * @param int $allegatoID Identificativo dell'allegato da recuperare.
     * @return array|null Array associativo rappresentante l'allegato.
     */
    public function loadAllegato(int $allegatoID): ?array
    {
        $fThread = FThread::getInstance();
        return $fThread->loadAllegato($allegatoID);
    }

    /**
     * Permette di eseguire l'aggiornamento delle informazioni nella base dati relative ad una instanza della classe
     * entity.
     * La classe entity a cui appartiene l'istanza viene stabilito dall'attributo entityType.
     * Viene restituito false se viene passato un entityType non corretto o vi sono errori durante l'esecuzione dei
     * metodi richiamati, true se l'operazione va a buon fine.
     * Gli entityType ammessi sono:
     * - ENTITY_USER;
     * - ENTITY_MODERATORE;
     * - ENTITY_ADMIN;
     * @param int $entityType Valore che indica il tipo di entity passata
     * @param object $entity Entity con cui eseguire l'update
     * @return bool L'esito dell'operazione.
     */
    public function update(int $entityType, object $entity): bool
    {
        if($entityType == self::ENTITY_USER || $entityType == self::ENTITY_MODERATORE || $entityType == self::ENTITY_ADMIN) {
            $fUser = FUser::getInstance();
            return $fUser->update($entity);
        } else {
            return false;
        }
    }

    /**
     * Permette di aggiornare la categoria memorizzata nella base dati, assegnando il moderatore che la gestisce.
     * Se l'operazione va a buon fine allora viene restituito true, false altrimenti.
     * @param ECategoria $categoria Categoria a cui si deve assegnare il moderatore
     * @param EModeratore $moderatore Moderatore da assegnare alla categoria scelta
     * @return bool Esito dell'operazione
     */
    public function updateModeratoreCategoria(ECategoria $categoria, EModeratore $moderatore): bool
    {
        $fCategoria = FCategoria::getInstance();
        return $fCategoria->update($categoria, $moderatore);
    }

    /**
     * Permette di esprimere un giudizio ad un thread.
     * @param EValutazione $valutazione Valutazione da aggiornare con il nuovo giudizio
     * @param int $tipologiaValutazione Tipo di giudizio espresso
     * @param EUser $user Utente che ha espresso il giudizio
     * @return bool Esito dell'operazione
     */
    public function updateValutazione(EValutazione $valutazione, int $tipologiaValutazione, EUser $user): bool
    {
        $fValutazione = FValutazione::getInstance();
        return $fValutazione->update($valutazione, $tipologiaValutazione, $user);
    }

    /**
     * Permette di memorizzare nella base dati l'istanza di una classe entity in base all'entityType scelto.
     * Se l'operazione va a buon fine viene restituito true, false altrimenti.
     * Gli entityType ammessi sono:
     * - ENTITY_USER;
     * - ENTITY_THREAD;
     * - ENTITY_CATEGORIA;
     * - ENTITY_MESSAGGIO.
     * @param int $entityType Valore che indica il tuo di entity da memorizzare nella base dati
     * @param object $entity Entity da memorizzare nella base dati
     * @return bool Esito dell'operazione
     */
    public function store(int $entityType, object $entity): bool
    {
        if ($entityType == self::ENTITY_USER) {
            $fUser = FUser::getInstance();
            return $fUser->store($entity);
        } else if ($entityType == self::ENTITY_THREAD) {
            $fThread = FThread::getInstance();
            return $fThread->store($entity);
        } else if ($entityType == self::ENTITY_CATEGORIA) {
            $fCategoria = FCategoria::getInstance();
            return $fCategoria->store($entity);
        } else if ($entityType == self::ENTITY_MESSAGGIO) {
            $fMessaggio = FMessaggio::getInstance();
            return $fMessaggio->store($entity);
        } else {
            return false;
        }
    }

    /**
     * Permette di memorizzare all'interno della base dati una risposta relativa ad un thread.
     * Se l'operazione va a buon fine viene restituito true, false altrimenti.
     * @param ERisposta $risposta Risposta da memorizzare nella base dati
     * @param int $threadID Thread a cui la risposta è associata
     * @return bool Esito dell'operazione
     */
    public function storeRispostaThread(ERisposta $risposta, int $threadID): bool
    {
        $fRisposta = FRisposta::getInstance();
        return $fRisposta->store($risposta, $threadID);
    }

    /**
     * Permette di elminare dalla base dati le informazioni relative ad una istanza di una classe entity, stabilita in
     * base all'entityType scelto.
     * Se l'operazione va buon fine allora viene restituito true, false altrimenti.
     * @param int $entityType Valore che indica il tipo di entity da eliminare dalla base dati
     * @param int $id Identificativo dell'entity da eliminare
     * @return bool Esito dell'operazione
     */
    public function delete(int $entityType, int $id): bool
    {
        if ($entityType == self::ENTITY_USER || $entityType == self::ENTITY_MODERATORE) {
            $fUser = FUser::getInstance();
            return $fUser->delete($id);
        } else if ($entityType == self::ENTITY_THREAD) {
            $fThread = FThread::getInstance();
            return $fThread->delete($id);
        } else if ($entityType == self::ENTITY_RISPOSTA) {
            $fRisposta = FRisposta::getInstance();
            return $fRisposta->delete($id);
        } else if ($entityType == self::ENTITY_CATEGORIA) {
            $fCategoria = FCategoria::getInstance();
            return $fCategoria->delete($id);
        } else if ($entityType == self::ENTITY_MESSAGGIO) {
            $fMessaggio = FMessaggio::getInstance();
            return $fMessaggio->delete($id);
        } else {
            return false;
        }
    }

    /**
     * Permette di ottenere un certo numero di istanze di una determinata classe entity specificando da quale riga della
     * tabella della base dati partire (riga di partenza esclusa) e il numero di righe da visualizzare.
     * Gli oggetti restituiti sono istanze della classe entity specificata dall'attributo entityType.
     * In base alla property passata può essere necessario specificare l'identificativo attraverso cui eseguire un
     * filtro su cosa restituire (PROPERTY_BY_CATEGORIA) oppure se non è neccessarcio specificare nulla si passa null
     * (PROPERTY_DEFAULT).
     * Se la combinazione di input risulta essere errata o vi sono errori durante l'esecuzione dei metodi chiamati
     * allora viene restituito null.
     * Gli entityType ammessi sono:
     * - ENTITY_USER;
     * - ENTITY_MODERATORE;
     * - ENTITY_THREAD;
     * - ENTITY_CATEGORIA;
     * - ENTITY_MESSAGGIO.
     * @param int $entityType Valore che indica la tipologia di entity da recuperare dalla base dati.
     * @param int $property Valore che indica se è necessario l'identificativo e quale entity è riferito, per effettuare
     * l'operazione
     * @param int|null $id Identificativo dell'entity che permette di effettuare il recupero
     * @param int $rigaPartenza Valore che indica da quale record iniziare il recupero
     * @param int $numeroRighe Valore che indica quanti record recuperare
     * @return array|null Elenco contenente il risultato dell'operazione
     * @throws ValidationException Eccezione lanciata in caso di problemi con la validazione dei dati nel momento della
     * creazione di alcune delle istanze entity.
     */
    public function loadEntities(int $entityType, int $property, ?int $id, int $rigaPartenza, int $numeroRighe): ?array
    {
        if ($entityType == self::ENTITY_USER && $property == self::PROPERTY_DEFAULT && !isset($id)) {
            $fUser = FUser::getInstance();
            return $fUser->loadAll($rigaPartenza, $numeroRighe);
        } else if ($entityType == self::ENTITY_MODERATORE && $property == self::PROPERTY_DEFAULT && !isset($id)) {
            $fUser = FUser::getInstance();
            return $fUser->loadAllModeratori($rigaPartenza, $numeroRighe);
        } else if ($entityType == self::ENTITY_THREAD && $property == self::PROPERTY_BY_CATEGORIA && isset($id)) {
            $fThread = FThread::getInstance();
            return $fThread->loadThreadsCategoria($id, $rigaPartenza, $numeroRighe);
        } else if ($entityType == self::ENTITY_CATEGORIA && $property == self::PROPERTY_DEFAULT && !isset($id)) {
            $fCategoria = FCategoria::getInstance();
            return $fCategoria->loadAll($rigaPartenza, $numeroRighe);
        } else if ($entityType == self::ENTITY_MESSAGGIO && $property == self::PROPERTY_DEFAULT && !isset($id)) {
            $fMessaggio = FMessaggio::getInstance();
            return $fMessaggio->loadAll($rigaPartenza, $numeroRighe);
        } else {
            return null;
        }
    }

    /**
     * Permette di ottenere un array contenente tutte le categorie presenti nella base dati.
     * In caso di errore viene restituito null.
     * @return array|null Categorie presenti nella base dati
     * @throws ValidationException Eccezione lanciata in caso di problemi con la validazione dei dati nel momento della
     * creazione delle istanze ECategoria.
     */
    public function loadAllCategorie(): ?array
    {
        $fCategoria = FCategoria::getInstance();
        return $fCategoria->loadAllSenzaPaginazione();
    }

    /**
     * Restituisce un elenco di threads, i quali sono quelli con il maggior numero di risposte. Quanti threads
     * recuperare viene stabilito in base al valore del parametro che viene richiesto in ingresso.
     * Se l'operazione non va a buon fine allora viene restituito null.
     * @param int $numeroThreads Valore che indica il numero di threads da recuperare
     * @return array|null Elenco di threads recuperati
     */
    public function loadThreadsPiuDiscussi(int $numeroThreads): ?array
    {
        $fThread = FThread::getInstance();
        return $fThread->loadThreadsPiuRisposte($numeroThreads);
    }

    /**
     * Restituisce un elenco di threads, i quali sono quelli con la valutazione maggiore. Quanti threads recuperare
     * viene stabilito in base al valore del parametro che viene richiesto in ingresso.
     * Se l'operazione non va a buon fine allora viene restituito null.
     * @param int $numeroThreads Valore che indica il numero di threads da recuperare
     * @return array|null Elenco di threads recuperati
     */
    public function loadThreadsValutazionePiuAlta(int $numeroThreads): ?array
    {
        $fThread = FThread::getInstance();
        return $fThread->loadThreadsValutazioneMaggiore($numeroThreads);
    }

    /**
     * Restituisce un elenco di messaggi, i quali sono stati pubblicati nelle ultime 24 ore.
     * Se l'operazione non va a buon fine allora viene restituito null.
     * @return array|null Elenco contenente i messaggi pubblicati nelle ultime 24 ore
     */
    public function loadMessaggiUltime24ore(): ?array
    {
        $fMessaggio = FMessaggio::getInstance();
        return $fMessaggio->loadMessaggiUltime24ore();
    }

    /**
     * Permette di ottenere l'elenco dei nuovi messaggi, ovvero quelli pubblicati successivamente al messaggio di cui
     * viene fornito l'identificativo.
     * In caso di errori viene restituito null.
     * @param int $ultimoMessaggioId Identificativo del messaggio da cui iniziare a recuperare i nuovi messaggi.
     * @return array|null Elenco dei messaggi pubblicati dopo un determinato messaggio.
     */
    public function loadNuoviMessaggi(int $ultimoMessaggioId): ?array
    {
        $fMessaggio = FMessaggio::getInstance();
        return $fMessaggio->loadNuoviMessaggi($ultimoMessaggioId);
    }

    /**
     * Verifica che un utente sia presente nella base dati fornendo la sua email.
     * Restituisce true se l'utente è presente, false se l'utente non è presente o null se vi sono stati errori.
     * @param string $email Email dell'utente di cui si deve verificare la presenza nella base dati
     * @return null|bool Esito dell'operazione
     */
    public function existsUserByEmail(string $email): ?bool
    {
        $fUser = FUser::getInstance();
        return $fUser->existsByEmail($email);
    }

    /**
     * Verifica che l'utente sia un moderatore o un admin, in base all'entityType fornito in ingresso.
     * Restituisce true se l'utente è un moderatore o un admin, false se non lo è o null se vi sono stati errori.
     * @param int $entityType Valore che indica il ruolo da verificare
     * @param int $id Identificativo dell'entity di cui si deve verificare il ruolo
     * @return null|bool Esito dell'operazione
     */
    public function isA(int $entityType, int $id): ?bool
    {
        if ($entityType == self::ENTITY_MODERATORE) {
            $fUser = FUser::getInstance();
            return $fUser->isModeratore($id);
        } else if ($entityType == self::ENTITY_ADMIN) {
            $fUser = FUser::getInstance();
            return $fUser->isAdmin($id);
        } else {
            return false;
        }
    }

    /**
     * Permette di restituire un certo numero di EThread, i quali presentano nel titolo alcune o tutte le parole che
     * vengono passate in ingresso. I thread vengono restituiti ordinati a partire da quello che ha il titolo con il
     * maggior numero di parole uguali e nello stesso ordine di quelle fornite in ingresso.
     * Se ha che searchType è SEARCH_TYPE_TITOLO allora ci si limita a questo, altrimenti (SEARCH_TYPE_TITOLO_CATEGORIE)
     * deve essere fornito un array di identificativi delle categorie affinché sia effettuata la ricerca solo in quelle
     * categorie.
     * Viene restituito un array di istanze di Ethread, eventualmente vuoto, ma se si utilizza una combinazione errata
     * di input o ci sono errori durante l'esecuzione dei metodi richiamati, allora viene restituito null.
     * @param int $searchType Valore che indica il tipo di ricerca
     * @param string $titolo Titolo del thread da cercare nella base dati
     * @param array|null $ids Elenco di categorie a cui i threads trovati devono appartenere
     * @param int $rigaPartenza Valore che indica da quale record iniziare il recupero
     * @param int $numeroRighe Valore che indica quanti record recuperare
     * @return array|null Elenco contenente il risultato della ricerca
     */
    public function ricercaThreads(int $searchType, string $titolo, ?array $ids, int $rigaPartenza, int $numeroRighe): ?array
    {
        if ($searchType == self::SEARCH_TYPE_TITOLO && !isset($ids)) {
            $fThread = FThread::getInstance();
            return $fThread->ricercaPerTitolo($titolo, $rigaPartenza, $numeroRighe);
        } else if ($searchType == self::SEARCH_TYPE_TITOLO_CATEGORIE && isset($ids)) {
            $fThread = FThread::getInstance();
            return $fThread->ricercaPerTitoloECategorie($titolo, $ids, $rigaPartenza, $numeroRighe);
        } else {
            return null;
        }
    }

    /**
     * Permette di rimuovere l'assegnazione del ruolo di moderatore di una categoria ad un utente.
     * Se l'operazione va buon fine allora viene resituito true, false altrimenti.
     * @param int $categoriaID Identificativo della categoria da cui si deve rimuovere il moderatore
     * @param EModeratore $moderatore Moderatore da rimuovere dal suo ruolo
     * @return bool Esito dell'operazione
     */
    public function rimuoviModeratoreCategoria(int $categoriaID, EModeratore $moderatore): bool
    {
        $fCategoria = FCategoria::getInstance();
        return $fCategoria->rimuoviModeratore($categoriaID, $moderatore);
    }

    /**
     * Permette di ottenere il numero di entities memorizzati all'interno della base dati.
     * Quali entities contare viene specificato dall'attributo entityType.
     * In base alla property passata può essere necessario specificare l'identificativo attraverso cui eseguire un
     * filtro su cosa restituire (PROPERTY_BY_CATEGORIA) oppure se non è neccessarcio specificare nulla si passa null
     * (PROPERTY_DEFAULT).
     * Se la combinazione di input risulta essere errata o vi sono errori durante l'esecuzione dei metodi chiamati
     * allora viene restituito null.
     * Gli entityType ammessi sono:
     * - ENTITY_USER;
     * - ENTITY_THREAD;
     * - ENTITY_CATEGORIA.
     * @param int $entityType Valore che indica la tipologia di entity da contare
     * @param int $property Valore che indica attraverso quale identificativo, se necessario, si ottiene il conteggio
     * @param int|null $id
     * @return int|null Risultato del conteggio
     */
    public function contaEntities(int $entityType, int $property, ?int $id): ?int
    {
        if ($entityType == self::ENTITY_USER && $property == self::PROPERTY_DEFAULT && !isset($id)) {
            $fUser = FUser::getInstance();
            return $fUser->conta();
        } else if ($entityType == self::ENTITY_CATEGORIA && $property == self::PROPERTY_DEFAULT && !isset($id)) {
            $fCategoria = FCategoria::getInstance();
            return $fCategoria->conta();
        } else if ($entityType == self::ENTITY_THREAD && $property == self::PROPERTY_BY_CATEGORIA && isset($id)) {
            $fThread = FThread::getInstance();
            return $fThread->contaThreadsCategoria($id);
        }else {
            return null;
        }
    }
}