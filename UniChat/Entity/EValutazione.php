<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class EValutazione
    {

        private int $id;
        private int $totale;
        private array $utentiPositivi;
        private array $utentiNegativi;

        public function __construct(int $id, ?int $totale, ?array $utentiPositivi, ?array $utentiNegativi)
        {
            $this->id = $id;
            if(isset($totale)){
                $this->totale = $totale;
            } else {
                $this->totale = 0;
            }
            if(isset($utentiPositivi)){
                $this->utentiPositivi = $utentiPositivi;
            } else {
                $this->utentiPositivi = array();
            }
            if(isset($utentiNegativi)){
                $this->utentiNegativi = $utentiNegativi;
            } else {
                $this->utentiNegativi = array();
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
         * @return int
         */
        public function getTotale(): int
        {
            return $this->totale;
        }

        /**
         * @return array
         */
        public function getUtentiPositivi(): array
        {
            return $this->utentiPositivi;
        }

        /**
         * @return array
         */
        public function getUtentiNegativi(): array
        {
            return $this->utentiNegativi;
        }

        /**
         * @return void
         */
        public function valuta(EUser $user, int $valore): void
        {
            if($valore == 1){
                $this->totale = $this->totale + 1;
                $this->utentiPositivi[] = $user;
            } else if ($valore == -1){
                $this->totale = $this->totale - 1;
                $this->utentiNegativi[] = $user;
            }
        }
    }