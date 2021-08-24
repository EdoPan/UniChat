<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

/**
 * EAdmin è il nostro super user, l'utente in grado di poter gestire qualunque cosa sulla piattaforma UniChat.
 */
class EAdmin extends EModeratore
    {

    /**
     * @param int|null $id
     * @param string $nome
     * @param string $cognome
     * @param string $email
     * @param string $password
     * @param array $fotoProfilo
     * @param string|null $corsoStudio
     * @param ECategoria|null $categoriaGestita
     */
    public function __construct(?int $id, string $nome, string $cognome, string $email, string $password, array $fotoProfilo, ?string $corsoStudio, ?ECategoria $categoriaGestita)
        {
            parent::__construct($id, $nome, $cognome, $email, $password, $fotoProfilo, $corsoStudio, $categoriaGestita);
        }

    /**
     * Metodo responsabile della creazione di una categoria.
     * @param int $categoriaID
     * @param string $nomeCategoria
     * @param string|null $icona
     * @param string $descrizione
     * @return ECategoria
     */
    public function creaCategoria(int $categoriaID, string $nomeCategoria, ?string $icona, string $descrizione): ECategoria
        {
            return new ECategoria($categoriaID, $nomeCategoria, $icona, $descrizione);
        }


    /**
     * Metodo responsabile della creazione di un moderatore.
     * @param EUser|null $user
     * @param ECategoria|null $categoria
     * @return EModeratore|null
     */
    public function creaModeratore(?EUser $user, ?ECategoria $categoria): ?EModeratore
        {

            //Controllo per verificare se l'utente e la categoria da moderare esistono.
            if(isset($user) and isset($categoria)){
                return new EModeratore($user->getId(), $user->getNome(), $user->getCognome(), $user->getEmail(), $user->getPassword(), $user->getFotoProfilo(), $user->getCorsoStudio(), $categoria);
            } else {
                return null;
            }
        }
    }