<?php

    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class FPersistentManager
    {

        private EUser $u;
        private EUser $u1;
        private EMessaggio $m;
        private ETag $tag;
        private ECategoria $cat;
        private EValutazione $v;
        private ERisposta $r;
        private ERisposta  $r1;
        private EThread $t;

        public function __construct()
        {
            $this->u = new EUser(1, "Mario", "Rossi", "mario.rossi@student.univaq.it", "12345678", null, "Ingegneria");
            $this->u1 = new EUser(2, "Luigi", "Bianchi", "luigi.bianchi@student.univaq.it", "password", null, "Economia");
            $this->m = new EMessaggio(1, "Ciao!!", $this->u);
            $this->tag = new ETag(1, "Matematica");
            $this->cat = new ECategoria(1, "DISIM", "disim.jpg", "Sa Nino cosa scriverci");
            $this->v = new EValutazione(1);
            $allegati = array("foto.pdf");
            $tags = array($this->tag);
            $this->r = new ERisposta(1, "Prega tanto", $this->u1);
            $this->r1 = new ERisposta(2, "Già lo faccio", $this->u);
            $risposte = array($this->r, $this->r1);
            $this->t = new EThread(1, "Problema di analisi 2", "Ma come si fa..", $allegati, $this->u, $this->cat, $tags, $this->v, $risposte);
        }




    }