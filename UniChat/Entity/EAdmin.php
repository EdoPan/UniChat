<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class EAdmin extends EModeratore
    {

        public function __construct(?int $id, string $nome, string $cognome, string $email, string $password, array $fotoProfilo, ?string $corsoStudio, ?ECategoria $categoriaGestita)
        {
            parent::__construct($id, $nome, $cognome, $email, $password, $fotoProfilo, $corsoStudio, $categoriaGestita);
        }

        public function creaCategoria(int $categoriaID, string $nomeCategoria, ?string $icona, string $descrizione): ECategoria
        {
            return new ECategoria($categoriaID, $nomeCategoria, $icona, $descrizione);
        }


        public function creaModeratore(?EUser $user, ?ECategoria $categoria): ?EModeratore
        {
            if(isset($user) and isset($categoria)){
                return new EModeratore($user->getId(), $user->getNome(), $user->getCognome(), $user->getEmail(), $user->getPassword(), $user->getFotoProfilo(), $user->getCorsoStudio(), $categoria);
            } else {
                return null;
            }
        }
    }