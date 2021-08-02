<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class EModeratore extends EUser
    {

        private ?ECategoria $categoriaGestita;

        public function __construct(int $_id, string $_nome, string $_cognome, string $_email,string $_password, ?string $_fotoProfilo, ?string $_corsoStudio, ?ECategoria $categoriaGestita)
        {
            parent::__construct($_id, $_nome, $_cognome, $_email, $_password, $_fotoProfilo, $_corsoStudio);
            $this->categoriaGestita = $categoriaGestita;

        }

        /**
         * @return ECategoria
         */
        public function getCategoriaGestita(): ECategoria
        {
            return $this->categoriaGestita;
        }

        /**
         * @param ECategoria $categoriaGestita
         */
        public function setCategoriaGestita(ECategoria $categoriaGestita): void
        {
            $this->categoriaGestita = $categoriaGestita;
        }

        /**
         * @param EThread
         * @return bool
         */
        public function verificaCategoria(EThread $thread): bool
        {
            $categoriaThread = $thread->getCategoriaThread()->getNome();
            if($categoriaThread == $this->categoriaGestita->getNome()){
                return true;
            } else {
                return false;
            }
        }
    }