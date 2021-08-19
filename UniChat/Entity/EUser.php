<?php

    declare(strict_types = 1);

/**
 *
 */
class EUser
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
     * @param int|null $id
     * @param string $nome
     * @param string $cognome
     * @param string $email
     * @param string $password
     * @param array|null $fotoProfilo
     * @param string|null $corsoStudio
     */
    public function __construct(?int $id, string $nome, string $cognome, string $email, string $password, ?array $fotoProfilo, ?string $corsoStudio)
    {
        if (isset($id)) {
            $this->id = $id;
        } else {
            $this->id = 0;
        }
        $this->nome = $nome;
        $this->cognome = $cognome;
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
            $this->corsoStudio = $corsoStudio;
        } else {
            $this->corsoStudio = "Sconosciuto";
        }
    }

    /**
     * Restituisce l'identificativo dell'utente.
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Restituisce il nome dell'utente.
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * Restituisce il cognome dell'utente.
     * @return string
     */
    public function getCognome(): string
    {
        return $this->cognome;
    }

    /**
     * Restituisce l'email dell'utente.
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Restituisce la password dell'utente.
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Restituisce la foto profilo dell'utente, all'interno di un array.
     * @return array
     */
    public function getFotoProfilo(): array
    {
        return $this->fotoProfilo;
    }

    /**
     * Restituisce il corso di studio dell'utente.
     * @return string
     */
    public function getCorsoStudio(): string
    {
        return $this->corsoStudio;
    }

    /**
     * Imposta l'identificativo dell'utente.
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Imposta il nome dell'utente.
     * @param string $nome
     */
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    /**
     * Imposta il cognome dell'utente.
     * @param string $cognome
     */
    public function setCognome(string $cognome): void
    {
        $this->cognome = $cognome;
    }

    /**
     * Imposta l'email dell'utente.
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Imposta la password dell'utente.
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        if(substr($password, 0, 4) === "$2y$"){
            $this->password = $password;
        } else {
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
        $this->corsoStudio = $corsoStudio;
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
            $scelta = rand(1, 5);
            switch($scelta){
                case 1:
                    $newPassword = $newPassword . chr(rand(45, 46));
                    break;
                case 2:
                    $newPassword = $newPassword . chr(rand(48, 57));
                    break;
                case 3:
                    $newPassword = $newPassword .chr(rand(65, 90));
                    break;
                case 4:
                    $newPassword = $newPassword . chr(rand(97, 122));
                    break;
                case 5:
                    $newPassword = $newPassword . chr(rand(94, 95));
            }
        }
        $this->setPassword($newPassword);
        return $newPassword;
    }
    }