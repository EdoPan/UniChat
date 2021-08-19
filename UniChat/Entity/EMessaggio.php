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

        public function __construct(?int $id, string $testo, EUser $autoreMessaggio)
        {
            if(isset($id)){
                $this->id = $id;
            } else {
                $this->id = 0;
            }
            $this->testo = $testo;
            $this->data = date(self::$formatoData);
            $this->autoreMessaggio = $autoreMessaggio;
        }


        public function getId(): int
        {
            return $this->id;
        }


        public function getTesto(): string
        {
            return $this->testo;
        }


        public function getData()
        {
            return $this->data;
        }


        public function getAutoreMessaggio(): EUser
        {
            return $this->autoreMessaggio;
        }


        public function setId(int $id): void
        {
            $this->id = $id;
        }


        public function setTesto(string $testo): void
        {
            $this->testo = $testo;
        }


        public function setData(): void
        {
            $this->data = date(self::$formatoData);
        }


        public function setAutoreMessaggio(EUser $autoreMessaggio): void
        {
            $this->autoreMessaggio = $autoreMessaggio;
        }


    }