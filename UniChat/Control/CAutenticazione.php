<?php
    declare(strict_types = 1);
    require_once __DIR__ . "\..\utility.php";

/**
 * Classe di controllo contenente tutti i metodi relativi alle operazioni di autenticazione alla piattaforma UniChat.
 */
class CAutenticazione {
    /**
     *
     */
    public function __construct(){}

    /**
     * Metodo responsabile della registrazione di un utente alla piattaforma UniChat.
     * @param string $nome
     * @param string $cognome
     * @param string $email
     * @param string $password
     * @param array $fotoProfilo
     * @param string|null $corsoStudio
     * @return bool
     */
    public function registrazione(string $nome, string $cognome, string $email, string $password, array $fotoProfilo, ?string $corsoStudio): bool
    {
        $pm = FPersistentManager::getInstance();
        if($pm->existsUserByEmail($email) == false){
            $userID = null;
            $u = new EUser($userID, $nome, $cognome, $email, $password, $fotoProfilo, $corsoStudio);
            $result = $pm->store(ENTITY_USER, $u);
        } else {
            $result = false;
        }
        mail($u->getEmail(), 'Iscrizione UniChat', 'Congratulazioni' .$u->getNome() .$u->getCognome() .'la registrazione su UniChat è andata a buon fine!');
        return $result;
    }

    /**
     * Metodo responsabile del login alla piattaforma UniChat.
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function login(string $email, string $password): bool
    {
        $pm = FPersistentManager::getInstance();
        $user = $pm->loadUserByEmail($email);
        $result = $user->verificaPassword($password);
        return $result;
    }

    /**
     * Metodo responsabile del recupero della password dell'utente mediante l'email.
     * @param string $email
     */
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