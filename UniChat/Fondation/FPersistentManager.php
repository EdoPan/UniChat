<?php

declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";

/**
 *
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
     * @var null
     */
    private static $instance = null;

    /**
     * Costruttore di default.
     */
    private function __construct()
        {
        }

    /**
     * Restituisce l'instanza di FThread. Se già esistente restituisce quella esistente, altrimenti la crea.
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
     * @param int $entityType
     * @param int $property
     * @param int $id
     * @return object|null
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

        } else if ($entityType == self::ENTITY_THREAD && $property == self::PROPERTY_PIU_DISCUSSO_CATEGORIA) {
            /*
             * Si vuole ottenere il thread con il maggior numero di risposte tra quelli appartenenti
             * ad una determinata categoria di cui si conosce l'identificativo.
             */
            $fThread = FThread::getInstance();
            return $fThread->loadThreadPiuDiscussoPerCategoria($id);

        } else if ($entityType == self::ENTITY_THREAD && $property == self::PROPERTY_VALUTAZIONE_MAGGIORE_CATEGORIA) {
            /*
             * Si vuole ottenere il thread con la valutazione più alta tra quelli appartenenti ad una determinata
             * categoria di cui si conosce l'identificativo.
             */
            $fThread = FThread::getInstance();
            return $fThread->loadThreadMaxValutazionePerCategoria($id);

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
     * Restituisce un Euser, un EModeratore o un EAdmin, dato l'email fornita in fase di registrazione.
     * Se l'operazione non va a buon fine allora viene restituito null.
     * @param string $email
     * @return EUser|null
     */
    public function loadUserByEmail(string $email): ?EUser
    {
        $fUser = FUser::getInstance();
        return $fUser->loadByEmail($email);
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
     * - ENTITY_VALUTAZIONE.
     * @param int $entityType
     * @param object $entity
     * @return bool
     */
    public function update(int $entityType, object $entity): bool                           //PROPERTY NON DOVREBBE SERVIRE
    {
        if($entityType == self::ENTITY_USER || $entityType == self::ENTITY_MODERATORE || $entityType == self::ENTITY_ADMIN) {
            $fUser = FUser::getInstance();
            return $fUser->update($entity);
        } else if ($entityType == self::ENTITY_VALUTAZIONE) {
            $fValutazione = FValutazione::getInstance();
            return $fValutazione->update($entity);
        } else {
            return false;
        }
    }

    /**
     * Permette di aggiornare la categoria memorizzata nella base dati, assegnando il moderatore che la gestisce.
     * Se l'operazione va a buon fine allora viene restituito true, false altrimenti.
     * @param ECategoria $categoria
     * @param EModeratore $moderatore
     * @return bool
     */
    public function updateModeratoreCategoria(ECategoria $categoria, EModeratore $moderatore): bool
    {
        $fCategoria = FCategoria::getInstance();
        return $fCategoria->update($categoria, $moderatore);
    }

    /**
     * Permette di memorizzare nella base dati l'istanza di una classe entity in base all'entityType scelto.
     * Se l'operazione va a buon fine viene restituito true, false altrimenti.
     * Gli entityType ammessi sono:
     * - ENTITY_USER;
     * - ENTITY_THREAD;
     * - ENTITY_CATEGORIA;
     * - ENTITY_MESSAGGIO.
     * @param int $entityType
     * @param object $entity
     * @return bool
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
     * @param ERisposta $risposta
     * @param int $threadID
     * @return bool
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
     * @param int $entityType
     * @param int $id
     * @return bool
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
     * filtro su cosa resituire (PROPERTY_BY_CATEGORIA) oppure se non è neccessarcio specificare nulla si passa null
     * (PROPERTY_DEFAULT).
     * Se la combinazione di input risulta essere errata o vi sono errori durante l'esecuzione dei metodi chiamati
     * allora viene restituito null.
     * Gli entityType ammessi sono:
     * - ENTITY_USER;
     * - ENTITY_MODERATORE;
     * - ENTITY_THREAD;
     * - ENTITY_MESSAGGIO.
     * @param int $entityType
     * @param int $property
     * @param int|null $id
     * @param int $rigaPartenza
     * @param int $numeroRighe
     * @return array|null
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
        } else if ($entityType == self::ENTITY_MESSAGGIO && $property == self::PROPERTY_DEFAULT && !isset($id)) {
            $fMessaggio = FMessaggio::getInstance();
            return $fMessaggio->loadAll($rigaPartenza, $numeroRighe);
        } else {
            return null;
        }
    }

    /**
     * Permette di ottenere un array di ECategorie memorizzate nella base dati.
     * Se vi sono errori durante l'esecuzione del metodo richiamato allora viene restituito null.
     * @return array|null
     */
    public function loadAllCategorie(): ?array
    {
        $fCategoria = FCategoria::getInstance();
        return $fCategoria->loadAll();
    }

    /**
     * Verifica che un utente sia presente nella base dati fornendo la sua email.
     * Restituisce true se l'utente è presente, false se l'utente non è presente o null se vi sono stati errori.
     * @param string $email
     * @return null|bool
     */
    public function existsUserByEmail(string $email): ?bool
    {
        $fUser = FUser::getInstance();
        return $fUser->existsByEmail($email);
    }

    /**
     * Verifica che l'utente sia un moderatore o un admin, in base all'entityType fornito in ingresso.
     * Restituisce true se l'utente è un moderatore o un admin, false se non lo è o null se vi sono stati errori.
     * @param int $entityType
     * @param int $id
     * @return null|bool
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
     * @param int $searchType
     * @param string $titolo
     * @param array|null $ids
     * @param int $rigaPartenza
     * @param int $numeroRighe
     * @return array|null
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
     * @param int $categoriaID
     * @param EModeratore $moderatore
     * @return bool
     */
    public function rimuoviModeratoreCategoria(int $categoriaID, EModeratore $moderatore): bool
    {
        $fCategoria = FCategoria::getInstance();
        return $fCategoria->rimuoviModeratore($categoriaID, $moderatore);
    }
}