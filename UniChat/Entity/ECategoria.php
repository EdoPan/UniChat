<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class ECategoria
    {

        private int $id;
        private string $nome;
        private array $icona = array(
            "id"=> 0,
            "nome" => "",
            "tipo" => "",
            "immagine" => ""
        );
        private string $descrizione;

        public function __construct(int $id, string $nome, ?array $icona, string $descrizione)
        {
            $this->id = $id;
            $this->nome = $nome;
            if(isset($icona)){
                $this->icona = $icona;
            } else {
                $this->icona["id"] = 1; //arrray che regge l'immagine predefinito.jpg
            }
            $this->descrizione = $descrizione;
        }


        public function getId(): int
        {
            return $this->id;
        }


        public function getNome(): string
        {
            return $this->nome;
        }


        public function getIcona(): array
        {
            return $this->icona;
        }


        public function getDescrizione(): string
        {
            return $this->descrizione;
        }


        public function setId(int $id): void
        {
            $this->id = $id;
        }


        public function setNome(string $nome): void
        {
            $this->nome = $nome;
        }


        public function setIcona(array $icona): void
        {
            $this->icona = $icona;
        }


        public function setDescrizione(string $descrizione): void
        {
            $this->descrizione = $descrizione;
        }

    }