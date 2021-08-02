<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class ECategoria
    {

        private int $id;
        private string $nome;
        private string $icona;
        private string $descrizione;

        public function __construct(int $id, string $nome, ?string $icona, string $descrizione)
        {
            $this->id = $id;
            $this->nome = $nome;
            if(isset($icona)){
                $this->icona = $icona;
            } else {
                $this->icona = "predefinito.jpg";
            }
            $this->descrizione = $descrizione;
        }

        /**
         * @return int
         */
        public function getId(): int
        {
            return $this->id;
        }

        /**
         * @return string
         */
        public function getNome(): string
        {
            return $this->nome;
        }

        /**
         * @return string
         */
        public function getIcona(): string
        {
            return $this->icona;
        }

        /**
         * @return string
         */
        public function getDescrizione(): string
        {
            return $this->descrizione;
        }

        /**
         * @param int $id
         */
        public function setId(int $id): void
        {
            $this->id = $id;
        }

        /**
         * @param string $nome
         */
        public function setNome(string $nome): void
        {
            $this->nome = $nome;
        }

        /**
         * @param string $icona
         */
        public function setIcona(string $icona): void
        {
            $this->icona = $icona;
        }

        /**
         * @param string $descrizione
         */
        public function setDescrizione(string $descrizione): void
        {
            $this->descrizione = $descrizione;
        }

    }