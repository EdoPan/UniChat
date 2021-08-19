<?php
    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

    class CAutenticazione
    {
        public function __construct()
        {
        }

        public function registrazione(string $nome, string $cognome, string $email, string $password, array $fotoProfilo, ?string $corsoStudio): bool
        {
            if(FUser::existsByEmail($email) == false){
                $userID = null;
                $u = new EUser($userID, $nome, $cognome, $email, $password, $fotoProfilo, $corsoStudio);
                $result = FUser::store($u);
            } else {
                $result = false;
            }
            return $result;
            mail($u->getEmail(), 'Iscrizione UniChat', 'Congratulazioni' .$u->getNome() .$u->getCognome() .'la registrazione su UniChat è andata a buon fine!');
        }

        public function login(string $email, string $password): bool
        {
            $user = FUser::loadByEmail($email);
            $result = $user->verificaPassword($password);
            return $result;
        }

        public function recuperoPassword(string $email): void
        {
            $user = FUser::loadByEmail($email);
            if(isset($user)){
                $password = rand(6) ; //Qui bisognerà chiamare il metodo che sta sviluppando Antonio
                mail($email, 'Recupero password', "La tua password temporanea è: ".$password); //https://www.html.it/pag/68800/inviare-email-con-php/
            } else {
            }

        }
    }