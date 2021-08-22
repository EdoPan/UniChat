<?php
    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class CMonitoraggio
    {
        public function __construct(){}

        public function rimuoviMessaggio(int $userID, int $messID){
            if(FUser::isModeratore($userID) == true or FUser::isAdmin($userID) == true){
                $result = FMessaggio::delete($messID);
            } else {
                $result = false;
            }
            return $result;
        }

        public function rimuoviThread(int $userID, int $threadID): bool
        {
            if (FUser::isAdmin($userID) == true){
                FValutazione::deleteByThread($threadID);
                $result = FThread::delete($threadID);
            } elseif (FUser::isModeratore($userID) == true){
                $mod = FUser::loadModeratore($userID);
                $cat = FCategoria::loadCategoriaThread($threadID);
                if($mod->getCategoriaGestita()->getNome() == $cat->getNome()){
                    FValutazione::deleteByThread($threadID);
                    $result = FThread::delete($threadID);
                }
            } else {
                $result = false;
            }
            return $result;
        }

        public function rimuoviRisposte(int $userID, int $rispostaID, int $threadID): bool
        {
            if (FUser::isAdmin($userID) == true){
                $result = FRisposta::delete($rispostaID);
            } elseif (FUser::isModeratore($userID) == true){
                $mod = FUser::loadModeratore($userID);
                $cat = FCategoria::loadCategoriaThread($threadID);
                if($mod->getCategoriaGestita()->getNome() == $cat->getNome()){
                    $result = FRisposta::delete($rispostaID);
                }
            } else {
                $result = false;
            }
            return $result;
        }
    }

