<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class ERisposta
    {

        //Attributi

        /**
         * Atttributo statico per formato data della risposta.
         * @var string
         */

        private static string $formatoData = "Y-m-d H:i:s";

        /**
         * Numero identificativo della risposta.
         * @var int
         */

        private int $id;

        /**
         * Testo della risposta.
         * @var string
         */

        private string $testo;

        /**
         * Data della risposta.
         * @var string|false
         */
        private string $data;

        /**
         * Autore della risposta.
         * @var EUser
         */
        private ?EUser $autoreRisposta;

        //Costruttore

        public function __construct(?int $id, string $testo, ?string $data, EUser $autoreRisposta)
        {
            if (isset($id)) {
                $this->id = $id;
            } else {
                $this->id = 0;
            }
            $this->testo = $testo;

            if ($data!=null) {
                $this->data=$data;
            } else {
                $this->data = date(self::$formatoData);
            }

            $this->autoreRisposta = $autoreRisposta;

        }

        /**
         * Metodo che restituisce l'id della risposta.
         * @return int
         */
        public function getId(): int
        {
            return $this->id;
        }

        /**
         * Metodo che restituisce il testo della risposta.
         * @return string
         */
        public function getTesto(): string
        {
            return $this->testo;
        }

        /**
         * Metodo che restituisce la data della risposta.
         * @return false|string
         */
        public function getData()
        {
            return $this->data;
        }

        /**
         * Metodo che restituisce l'utente autore della risposta.
         * @return EUser
         */
        public function getAutoreRisposta(): EUser
        {
            return $this->autoreRisposta;
        }

        /**
         * Metodo che imposta l'id della risposta.
         * @param int $id
         */
        public function setId(int $id): void
        {
            $this->id = $id;
        }

        /**
         * Metodo che imposta il testo della risposta.
         * @param string $testo
         */
        public function setTesto(string $testo): void
        {
            $this->testo = $testo;
        }

        /**
         * Metodo che imposta la data della risposta.
         */
        public function setData(): void
        {
            $this->data = date(self::$formatoData);
        }

        /**
         * Metodo che imposta l'autore della risposta.
         * @param EUser $autoreRisposta
         */
        public function setAutoreRisposta(EUser $autoreRisposta): void
        {
            $this->autoreRisposta = $autoreRisposta;
        }

    }