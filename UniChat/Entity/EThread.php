<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class EThread
    {

        private static string $formatoData = "Y-m-d H:i:s";

        private int $id;
        private string $titolo;
        private string $testo;
        private string $data;
        private array $allegati;
        private EUser $autoreThread;
        private ECategoria $categoriaThread;
        private array $tags;
        private EValutazione $valutazione;
        private array $risposte;

        public function __construct(int $id, string $titolo, string $testo, ?array $allegati, EUser $autoreThread, ECategoria $categoriaThread, array $tags,
                                    EValutazione $valutazione, ?array $risposte)
        {

            $this->id = $id;
            $this->titolo = $titolo;
            $this->testo = $testo;
            $this->data = date(self::$formatoData);
            if(isset($allegati)){
                $this->allegati = $allegati;
            } else {
                $this->allegati = array();
            }
            $this->autoreThread = $autoreThread;
            $this->categoriaThread = $categoriaThread;
            $this->tags = $tags;
            $this->valutazione = clone $valutazione;
            if(isset($risposte)){
                $this->risposte = $risposte;
            } else {
                $this->risposte = array();
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
        public function getTitolo(): string
        {
            return $this->titolo;
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
         * @return array
         */
        public function getAllegati(): array
        {
            return $this->allegati;
        }

        /**
         * @return EUser
         */
        public function getAutoreThread(): EUser
        {
            return $this->autoreThread;
        }

        /**
         * @return ECategoria
         */
        public function getCategoriaThread(): ECategoria
        {
            return $this->categoriaThread;
        }

        /**
         * @return array
         */
        public function getTags(): array
        {
            return $this->tags;
        }

        /**
         * @return EValutazione
         */
        public function getValutazione(): EValutazione
        {
            return clone $this->valutazione;
        }

        /**
         * @return array
         */
        public function getRisposte(): array
        {
            return $this->risposte;
        }

        /**
         * @param int $id
         */
        public function setId(int $id): void
        {
            $this->id = $id;
        }

        /**
         * @param string $titolo
         */
        public function setTitolo(string $titolo): void
        {
            $this->titolo = $titolo;
        }

        /**
         * @param string $testo
         */
        public function setTesto(string $testo): void
        {
            $this->testo = $testo;
        }

        /**
         * @return void
         */
        public function setData(): void
        {
            $this->data = date(self::$formatoData);
        }

        /**
         * @param array $allegati
         */
        public function setAllegati(array $allegati): void
        {
            $this->allegati = $allegati;
        }

        /**
         * @param EUser $autoreThread
         */
        public function setAutoreThread(EUser $autoreThread): void
        {
            $this->autoreThread = $autoreThread;
        }

        /**
         * @param ECategoria $categoriaThread
         */
        public function setCategoriaThread(ECategoria $categoriaThread): void
        {
            $this->categoriaThread = $categoriaThread;
        }

        /**
         * @param array $tags
         */
        public function setTags(array $tags): void
        {
            $this->tags = $tags;
        }

        /**
         * @param EValutazione $valutazione
         */
        public function setValutazione(EValutazione $valutazione): void
        {
            $this->valutazione = clone $valutazione;
        }

        /**
         * @param array $risposte
         */
        public function setRisposte(array $risposte): void
        {
            $this->risposte = $risposte;
        }

        /**
         * @return int
         */
        public function countRisposte(): int
        {
            return count($this->risposte);
        }
    }