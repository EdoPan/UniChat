<?php
    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class CAmministrazione
    {
        public function __construct(){}

        public function aggiungiCategoria(int $adminID, string $nomeCategoria, array $icona, string $descrizione): bool
        {
            $admin = FUser::loadAdmin($adminID);
            $categoriaID = null;
            $categoria = $admin->creaCategoria($categoriaID, $nomeCategoria, $icona, $descrizione);
            $result = FCategoria::store($categoria);
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

        public function rimuoviUser(int $adminID, int $userID){
            if(FUser::isAdmin($adminID)){
                $user = FUser::load($userID);
                $result = FUser::delete($user->getID()); //Manca nel Fondation il metodo delete che mediante l'userID fa saltare l'utente dal database
            } else {
                $result = false;
            }
            return $result;
        }
    }