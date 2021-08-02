<?php
    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class CAutenticazione
    {
        public function __construct()
        {
        }

        public function registrazione(string $nome, string $cognome, string $email, string $password, ?string $fotoProfilo, ?string $corsoStudio): bool
        {
            if(FUser::existsByEmail($email) == false){
                $userID = FUser::getLastID() + 1;
                $u = new EUser($userID, $nome, $cognome,$email, $password, $fotoProfilo, $corsoStudio);
                $result = FUser::store($u);
            } else {
                $result = false;
            }
            return $result;
        }

        public function login(string $email, string $password): bool
        {
            $result = FUser::exists($email, $password);
            return $result;
        }

        public function recuperoPassword(string $email): string
        {
            $user = FUser::loadByEmail($email);
            if(isset($user)){
                $password = $user->getPassword();
                return $password;
            } else {
                return "";
            }

        }
    }