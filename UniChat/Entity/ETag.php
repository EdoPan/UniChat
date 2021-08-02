<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class ETag
    {

        private int $id;
        private string $denominazione;

        public function __construct(int $id, string $denominazione)
        {
            $this->id = $id;
            $this->denominazione = $denominazione;
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
        public function getDenominazione(): string
        {
            return $this->denominazione;
        }

        /**
         * @param string $denominazione
         */
        public function setDenominazione(string $denominazione): void
        {
            $this->denominazione = $denominazione;
        }



    }