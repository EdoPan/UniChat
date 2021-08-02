<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class ERisposta
    {

        private static string $formatoData = "Y-m-d H:i:s";

        private int $id;
        private string $testo;
        private string $data;
        private EUser $autoreRisposta;

        public function __construct(int $id, string $testo, EUser $autoreRisposta)
        {

            $this->id = $id;
            $this->testo = $testo;
            $this->data = date(self::$formatoData);
            $this->autoreRisposta = $autoreRisposta;

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
        public function getTesto(): string
        {
            return $this->testo;
        }

        /**
         * @return false|string
         */
        public function getData()
        {
            return $this->data;
        }

        /**
         * @return EUser
         */
        public function getAutoreRisposta(): EUser
        {
            return $this->autoreRisposta;
        }

        /**
         * @param int $id
         */
        public function setId(int $id): void
        {
            $this->id = $id;
        }

        /**
         * @param string $testo
         */
        public function setTesto(string $testo): void
        {
            $this->testo = $testo;
        }

        /**
         *
         */
        public function setData(): void
        {
            $this->data = date(self::$formatoData);
        }

        /**
         * @param EUser $autoreRisposta
         */
        public function setAutoreRisposta(EUser $autoreRisposta): void
        {
            $this->autoreRisposta = $autoreRisposta;
        }

    }