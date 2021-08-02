<?php

    declare(strict_types = 1);

    class EUser
    {

        private int $id;
        private string $nome;
        private string $cognome;
        private string $email;
        private string $password;
        private string $fotoProfilo;
        private string $corsoStudio;

        public function __construct(int $id, string $nome, string $cognome, string $email, string $password, ?string $fotoProfilo, ?string $corsoStudio)
        {
            $this->id = $id;
            $this->nome = $nome;
            $this->cognome = $cognome;
            $this->email = $email;
            $this->password = $password;
            if(isset($fotoProfilo)) {
                $this->fotoProfilo = $fotoProfilo;
            } else {
                $this->fotoProfilo = "predefinito.jpg";
            }
            if(isset($corsoStudio)){
                $this->corsoStudio = $corsoStudio;
            } else {
                $this->corsoStudio = "sconosciuto";
            }
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
        public function getCognome(): string
        {
            return $this->cognome;
        }

        /**
         * @return string
         */
        public function getEmail(): string
        {
            return $this->email;
        }

        /**
         * @return string
         */
        public function getPassword(): string
        {
            return $this->password;
        }

        /**
         * @return string
         */
        public function getFotoProfilo(): string
        {
            return $this->fotoProfilo;
        }

        /**
         * @return string
         */
        public function getCorsoStudio(): string
        {
            return $this->corsoStudio;
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
         * @param string $cognome
         */
        public function setCognome(string $cognome): void
        {
            $this->cognome = $cognome;
        }

        /**
         * @param string $email
         */
        public function setEmail(string $email): void
        {
            $this->email = $email;
        }

        /**
         * @param string $password
         */
        public function setPassword(string $password): void
        {
            $this->password = $password;
        }

        /**
         * @param string $fotoProfilo
         */
        public function setFotoProfilo(string $fotoProfilo): void
        {
            $this->fotoProfilo = $fotoProfilo;
        }

        /**
         * @param string $corsoStudio
         */
        public function setCorsoStudio(string $corsoStudio): void
        {
            $this->corsoStudio = $corsoStudio;
        }

    }