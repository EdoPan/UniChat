<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

/**
 *
 */
class EUser implements JsonSerializable
{
    /**
     * Identificativo dell'utente.
     * @var int
     */
    private int $id;

    /**
     * Nome dell'utente.
     * @var string
     */
    private string $nome;

    /**
     * Cognome dell'utente.
     * @var string
     */
    private string $cognome;

    /**
     * Email dell'utente.
     * @var string
     */
    private string $email;

    /**
     * Password dell'utente cifrata con funzione di hash.
     * @var string
     */
    private string $password;

    /**
     * Foto profilo dell'utente, viene rappresentata attraverso un array associativo dove vengono riporati, in questo
     * ordine, l'identificativo del file, il nome del file, la dimensione del file, tipo del file e l'immagine posta in
     * formato stringa.
     * @var array
     */
    private array $fotoProfilo = array(
            "id" => 0,
            "nome" => "",
            "dimensione" => "",
            "tipo" => "",
            "immagine" => ""
        );

    /**
     * Corso di studio frequentato dall'utente.
     * @var string
     */
    private string $corsoStudio;

    /**
     * Costruttore di EUser.
     * Se l'utente si è appena registrato allora non ha un id e quindi viene passato come null e impostato a 0.
     * Se l'utente in fase di registrazione non vuole impostare una foto profilo allora viene posto l'identificativo 1
     * che viene utilizzato per impostare la foto di default e gli altri campi dell'array vengono lasciati vuoti.
     * In caso contrario viene posto l'identificativo a 0 e gli altri campi vengono settati.
     * Se l'utente in fase di registrazione non specifica il corso di studio allora viene passato come null e impostato
     * a Sconosciuto.
     * Prima di procedere con la creazione dell'oggetto si esegue una validazione sui dati, in particolare si verifica
     * che il nome ed il cognome dell'utente contengano solo lettere, che l'email sia istituzionale e che la password
     * sia di almeno 8 caratteri e che tra questi non ve ne siano alcuni particolari. Se la validazione non va a buon
     * fine allora viene lanciata una eccezione.
     * @param int|null $id Identificativo dell'utente da creare, può non essere impostato
     * @param string $nome Nome dell'utente da creare
     * @param string $cognome Cognome dell'utente da creare
     * @param string $email Email dell'utente da creare
     * @param string $password Password dell'utente da creare, può essere in chiaro o cifrata
     * @param array|null $fotoProfilo Foto profilo dell'utente da creare, può non essere impostata
     * @param string|null $corsoStudio Corso di studio dell'utente da creare, può non essere impostato
     * @throws ValidationException Eccezione lanciata in caso di problemi con la validazione dei dati
     */
    public function __construct(?int $id, string $nome, string $cognome, string $email, string $password, ?array $fotoProfilo, ?string $corsoStudio)
    {
        if (isset($id)) {
            $this->id = $id;
        } else {
            $this->id = 0;
        }

        try {
            $validazione = Validazione::getInstance();
            $validazione->validaStringa($nome);
            $validazione->validaStringa($cognome);
            $validazione->validaEmail($email);
            if(substr($password, 0, 4) !== "$2y$") {
                $validazione->validaPassword($password);
            }
        } catch (ValidationException $e) {
            throw new ValidationException($e->getMessage(), $e->getCode());
        }

        $nomeMinuscolo = strtolower($nome);
        $nomePrimeMaiuscole = ucwords($nomeMinuscolo);
        $this->nome = $nomePrimeMaiuscole;

        $cognomeMinuscolo = strtolower($cognome);
        $cognomePrimeMaiuscole = ucwords($cognomeMinuscolo);
        $this->cognome = $cognomePrimeMaiuscole;

        $this->email = $email;
        if(substr($password, 0, 4) === "$2y$"){
            $this->password = $password;
        } else {
            $this->password = password_hash($password, PASSWORD_BCRYPT);
        }
        if(isset($fotoProfilo)) {
            $this->fotoProfilo = $fotoProfilo;
        } else {
            $this->fotoProfilo["id"] = 1;
        }

        if(isset($corsoStudio)){
            $corsoStudioMinuscolo = strtolower($corsoStudio);
            $corsoStudioPrimaMaiuscola = ucfirst($corsoStudioMinuscolo);
            $this->corsoStudio = $corsoStudioPrimaMaiuscola;
        } else {
            $this->corsoStudio = "Sconosciuto";
        }
    }

    /**
     * Restituisce l'identificativo dell'utente.
     * @return int Identificativo dell'utente.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Restituisce il nome dell'utente.
     * @return string Nome dell'utente.
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * Restituisce il cognome dell'utente.
     * @return string Cognome dell'utente.
     */
    public function getCognome(): string
    {
        return $this->cognome;
    }

    /**
     * Restituisce l'email dell'utente.
     * @return string Email dell'utente.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Restituisce la password dell'utente.
     * @return string Password dell'utente (cifrata).
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Restituisce la foto profilo dell'utente, all'interno di un array.
     * @return array Foto profilo dell'utente.
     */
    public function getFotoProfilo(): array
    {
        return $this->fotoProfilo;
    }

    /**
     * Restituisce il corso di studio dell'utente.
     * @return string Corso di studio dell'utente.
     */
    public function getCorsoStudio(): string
    {
        return $this->corsoStudio;
    }

    /**
     * Imposta l'identificativo dell'utente.
     * @param int $id Identificativo utente da assegnare
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Imposta il nome dell'utente, dopo averlo validato.
     * @param string $nome Nome utente da assegnare.
     * @throws ValidationException Eccezione lanciata in caso di problemi con la validazione.
     */
    public function setNome(string $nome): void
    {
        try {
            $validazione = Validazione::getInstance();
            $validazione->validaStringa($nome);
        } catch (ValidationException $e) {
            throw new ValidationException($e->getMessage(), $e->getCode());
        }

        $nomeMinuscolo = strtolower($nome);
        $nomePrimeMaiuscole = ucwords($nomeMinuscolo);
        $this->nome = $nomePrimeMaiuscole;
    }

    /**
     * Imposta il cognome dell'utente, dopo averlo validato.
     * @param string $cognome Cognome utente da assegnare.
     * @throws ValidationException Eccezione lanciata in caso di problemi con la validazione.
     */
    public function setCognome(string $cognome): void
    {
        try {
            $validazione = Validazione::getInstance();
            $validazione->validaStringa($cognome);
        } catch (ValidationException $e) {
            throw new ValidationException($e->getMessage(), $e->getCode());
        }

        $cognomeMinuscolo = strtolower($cognome);
        $cognomePrimaMaiuscola = ucwords($cognomeMinuscolo);
        $this->cognome = $cognomePrimaMaiuscola;
    }

    /**
     * Imposta l'email dell'utente, dopo averla validata.
     * @param string $email Email utente da assegnare.
     * @throws ValidationException Eccezione lanciata in caso di problemi con la validazione.
     */
    public function setEmail(string $email): void
    {
        try{
            $validazione = Validazione::getInstance();
            $validazione->validaEmail($email);
        } catch (ValidationException $e) {
            throw new ValidationException($e->getMessage(), $e->getCode());
        }

        $this->email = $email;
    }

    /**
     * Imposta la password dell'utente.
     * Se la password è in chiaro allora viene prima validata e cifrata.
     * @param string $password Password utente da assegnare.
     * @throws ValidationException Eccezione lanciata in caso di problemi con la validazione.
     */
    public function setPassword(string $password): void
    {
        if(substr($password, 0, 4) === "$2y$"){
            $this->password = $password;
        } else {
            try {
                $validazione = Validazione::getInstance();
                $validazione->validaPassword($password);
            } catch (ValidationException $e) {
                throw new ValidationException($e->getMessage(), $e->getCode());
            }

            $this->password = password_hash($password, PASSWORD_BCRYPT);
        }
    }

    /**
     * Imposta la foto profilo dell'utente.
     * @param array $fotoProfilo
     */
    public function setFotoProfilo(array $fotoProfilo): void
    {
        $this->fotoProfilo = $fotoProfilo;
    }

    /**
     * Imposta il corso di studio dell'utente.
     * @param string $corsoStudio
     */
    public function setCorsoStudio(string $corsoStudio): void
    {
        $corsoStudioMinuscolo = strtolower($corsoStudio);
        $corsoStudioPrimaMaiuscola = ucfirst($corsoStudioMinuscolo);
        $this->corsoStudio = $corsoStudioPrimaMaiuscola;
    }

    /**
     * Imposta la foto profilo dell'utente con quella di default.
     */
    public function resetFotoProfilo(): void
    {
        $this->fotoProfilo["id"] = 1;
        $this->fotoProfilo["nome"] = "";
        $this->fotoProfilo["dimensione"] = "";
        $this->fotoProfilo["tipo"] = "";
        $this->fotoProfilo["immagine"] = "";
    }

    /**
     * Imposta il corso di studio dell'utente a Sconosciuto.
     */
    public function resetCorsoStudio(): void
    {
        $this->corsoStudio = "Sconosciuto";
    }


    /**
     * Verifica che la password fornita dall'utente sia quella corretta.
     * @param string $password
     * @return bool
     */
    public function verificaPassword(string $password): bool
    {
            return password_verify($password, $this->password);
    }


    /**
     * Genera una nuova password per l'utente, ne esegue l'hash e la memorizza. Restituisce la nuova password in chiaro.
     * @return string
     */
    public function generaPassword(): string
    {
        $lunghezza = 30;
        $newPassword = "";
        for($i=0; $i<$lunghezza; $i++){
            $scelta = rand(1, 3);
            switch($scelta){
                case 1:
                    $newPassword = $newPassword . chr(rand(48, 57));
                    break;
                case 2:
                    $newPassword = $newPassword .chr(rand(65, 90));
                    break;
                case 3:
                    $newPassword = $newPassword . chr(rand(97, 122));
                    break;
            }
        }
        $this->setPassword($newPassword);
        return $newPassword;
    }

    /**
     * Restituisce lo stato di un oggetto EUser in formato JSON.
     * @return array Stato dell'oggetto in formato JSON.
     */
    public function jsonSerialize(): array
    {
        $result = array(
            "id" => $this->id,
            "nome" => $this->nome,
            "cognome" => $this->cognome,
            "email" => $this->email,
            "corsoStudio" => $this->corsoStudio,
            "ruolo" => "User"
        );
        return $result;
    }
}