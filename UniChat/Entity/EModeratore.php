<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class EModeratore extends EUser implements JsonSerializable
    {
        // Attributi

        /**
         * Oggetto di tipo ECategoria. Indica la categoria gestita dal moderatore.
         * @var ?ECategoria
         */
        private ?ECategoria $categoriaGestita;


        //Costruttore
        public function __construct(int $_id, string $_nome, string $_cognome, string $_email,string $_password, ?array $_fotoProfilo, ?string $_corsoStudio, ?ECategoria $categoriaGestita)
        {
            parent::__construct($_id, $_nome, $_cognome, $_email, $_password, $_fotoProfilo, $_corsoStudio);
            $this->categoriaGestita = $categoriaGestita;

        }

        //Metodi

        /**
         * Metodo che restituisce la categoria associata al moderatore.
         * @return ECategoria
         */
        public function getCategoriaGestita(): ECategoria
        {
            return $this->categoriaGestita;
        }

        /**
         * Metodo che imposta la categoria associata al moderatore.
         * @param ECategoria $categoriaGestita
         */
        public function setCategoriaGestita(ECategoria $categoriaGestita): void
        {
            $this->categoriaGestita = $categoriaGestita;
        }

        /**
         * Verifica se un thread appartiene alla categoria gestita da tale moderatore.
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

        /**
         * Restituisce lo stato di un oggetto EModeratore in formato JSON.
         * @return array Stato dell'oggetto in formato JSON.
         */
        public function jsonSerialize(): array
        {
            $result = parent::jsonSerialize();
            $result["ruolo"] = "Moderatore";
            if (isset($this->categoriaGestita)) {
                $result["categoriaModerata"] = $this->getCategoriaGestita()->getNome();
            }
            return $result;
        }


    }