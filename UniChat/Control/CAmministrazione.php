<?php
    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class CAmministrazione
    {
        public function __construct(){}

        public function aggiungiCategoria(int $adminID, string $nomeCategoria, ?string $icona, string $descrizione): bool
        {
            $admin = FUser::loadAdmin($adminID);
            $categoriaID = FCategoria::getLastID() + 1;
            $categoria = $admin->creaCategoria($categoriaID, $nomeCategoria, $icona, $descrizione);
            $result = FCategoria::store($categoria);
            return $result;
        }

        public function aggiungiTag(int $adminID, string $denominazione):bool
        {
            $a = FUser::loadAdmin($adminID);
            $tagID = FTag::getLastID() + 1;
            $tag = $a->creaTag($tagID, $denominazione);
            $result = FTag::store($tag);
            return $result;
        }

        public function aggiungiModeratore(int $adminID, int $userID, int $categoriaID): bool
        {
            $admin = FUser::loadAdmin($adminID);
            $user = FUser::load($userID);
            $categoria = FCategoria::load($categoriaID);
            $mod = $admin->creaModeratore($user, $categoria);
            if(isset($mod)){
                FCategoria::update($categoria, $mod);
                $result = FUser::updateMod($mod);
            } else {
                $result = false;
            }
            return $result;
        }

        public function rimuoviCategoria(int $adminID, int $categoriaID): bool
        {
            if(FUser::isAdmin($adminID)){
                $mod = FUser::loadModeratoreCategoria($categoriaID);
                FUser::update($mod);
                FThread::updateByCategoriaID($categoriaID);
                $result = FCategoria::delete($categoriaID);
            } else {
                $result = false;
            }
            return $result;
        }

        public function rimuoviModeratore(int $adminID, int $moderatoreID){
            if(FUser::isAdmin($adminID)){
                $mod = FUser::loadMod($moderatoreID);
                $categoria = $mod->getCategoriaGestita();
                FCategoria::updateNoModer($categoria);
                $result = FUser::updateToUser($mod);
            } else {
                $result = false;
            }
            return $result;
        }
    }