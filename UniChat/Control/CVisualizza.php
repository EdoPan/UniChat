<?php
    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class CVisualizza
    {
        public function __construct()
        {
        }

        public function elencaTags(): array
        {
            $result = FTag::loadAll();
            return $result;
        }

        public function elencaCategorie(): array
        {
            $result = FCategoria::loadAll();
            return $result;
        }
    }
