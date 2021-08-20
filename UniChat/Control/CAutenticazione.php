<?php
    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class CAutenticazione
    {
        public function __construct(){}


        public function registrazione(string $nome, string $cognome, string $email, string $password, array $fotoProfilo, ?string $corsoStudio): bool
        {
            $pm = FPersistentManager::getInstance();
            if($pm->existsUser($email, $password) == false){
                $userID = null;
                $u = new EUser($userID, $nome, $cognome, $email, $password, $fotoProfilo, $corsoStudio);
                $result = $pm->store(ENTITY_USER, $u);
            } else {
                $result = false;
            }
            mail($u->getEmail(), 'Iscrizione UniChat', 'Congratulazioni' .$u->getNome() .$u->getCognome() .'la registrazione su UniChat è andata a buon fine!');
            return $result;
        }


        public function login(string $email, string $password): bool
        {
            $pm = FPersistentManager::getInstance();
            $user = $pm->loadUserByEmail($email);
            $result = $user->verificaPassword($password);
            return $result;
        }


        public function recuperoPassword(string $email): void
        {
            $pm = FPersistentManager::getInstance();
            $user = $pm->loadUserByEmail($email);
            if(isset($user)){
                $password = $user->generaPassword();
                $pm->update(ENTITY_USER,PROPERTY_DEFAULT, $user);
                mail($email, 'Recupero password', "La tua password temporanea è: ".$password); //https://www.html.it/pag/68800/inviare-email-con-php/
            } else {
            }
        }
    }