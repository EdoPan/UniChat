<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";


    class EMessaggio
    {

        private static string $formatoData = "Y-m-d H:i:s";

        private int $id;
        private string $testo;
        private string $data;
        private EUser $autoreMessaggio;

        public function __construct(int $id, string $testo, EUser $autoreMessaggio)
        {
            $this->id = $id;
            $this->testo = $testo;
            $this->data = date(self::$formatoData);
            $this->autoreMessaggio = $autoreMessaggio;
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
        public function getAutoreMessaggio(): EUser
        {
            return $this->autoreMessaggio;
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
         * @param EUser $autoreMessaggio
         */
        public function setAutoreMessaggio(EUser $autoreMessaggio): void
        {
            $this->autoreMessaggio = $autoreMessaggio;
        }



    }