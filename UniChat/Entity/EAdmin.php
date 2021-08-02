<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class EAdmin extends EModeratore
    {

        public function __construct(int $_id, string $_nome, string $_cognome, string $_email, string $_password, ?string $_fotoProfilo, ?string $_corsoStudio, ?ECategoria $categoriaGestita)
        {
            parent::__construct($_id, $_nome, $_cognome, $_email, $_password, $_fotoProfilo, $_corsoStudio, $categoriaGestita);
        }

        public function creaCategoria(int $categoriaID, string $nomeCategoria, ?string $icona, string $descrizione): ECategoria
        {
            return new ECategoria($categoriaID, $nomeCategoria, $icona, $descrizione);
        }

        public function creaTag(int $tagID, string $denominazione): Etag
        {
            return new Etag($tagID, $denominazione);
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