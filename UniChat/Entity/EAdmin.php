<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

/**
 * EAdmin è il nostro super user, l'utente in grado di poter gestire qualunque cosa sulla piattaforma UniChat.
 */
class EAdmin extends EModeratore implements JsonSerializable {

    /**
     * @param int|null $id
     * @param string $nome
     * @param string $cognome
     * @param string $email
     * @param string $password
     * @param array|null $fotoProfilo
     * @param string|null $corsoStudio
     * @param ECategoria|null $categoriaGestita
     * @throws ValidationException
     */
    public function __construct(?int $id, string $nome, string $cognome, string $email, string $password, ?array $fotoProfilo, ?string $corsoStudio, ?ECategoria $categoriaGestita)
    {
        parent::__construct($id, $nome, $cognome, $email, $password, $fotoProfilo, $corsoStudio, $categoriaGestita);
    }

    /**
     * Metodo responsabile della creazione di una categoria.
     * @param int|null $categoriaID
     * @param string $nomeCategoria
     * @param array|null $icona
     * @param string $descrizione
     * @return ECategoria
     * @throws ValidationException
     */
    public function creaCategoria(?int $categoriaID, string $nomeCategoria, ?array $icona, string $descrizione): ECategoria
    {
        return new ECategoria($categoriaID, $nomeCategoria, $icona, $descrizione);
    }

    /**
     * Metodo responsabile della creazione di un moderatore.
     * @param EUser|null $user
     * @param ECategoria|null $categoria
     * @return EModeratore|null
     * @throws ValidationException
     */
    public function creaModeratore(EUser $user, ECategoria $categoria): EModeratore
    {

        return new EModeratore($user->getId(), $user->getNome(), $user->getCognome(), $user->getEmail(), $user->getPassword(), $user->getFotoProfilo(), $user->getCorsoStudio(), $categoria);

    }

    /**
     * Restituisce lo stato di un oggetto EAdmin in formato JSON.
     * @return array Stato dell'oggetto in formato JSON.
     */
    public function jsonSerialize(): array
    {
        $result = parent::jsonSerialize();
        $result["ruolo"] = "Admin";
        $result["categoriaModerata"] = "Tutte";
        return $result;
    }
}