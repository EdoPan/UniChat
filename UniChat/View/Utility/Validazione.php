<?php
declare(strict_types=1);

class Validazione
{

    private string $STRING_PATTERN = '/^[a-z A-z]+$/';

    private string $EMAIL_PATTERN = '/[a-z.A-Z0-9]+@student.univaq.it$/';

    private string $PASSWORD_PATTERN = '/^[a-zA-z0-9@.\-_]{8,}$/';

    private static $instance = null;

    private function __construct() {}

    public static function getInstance() {
        if (self::$instance == null) {
            $classe = __CLASS__;
            self::$instance = new $classe;
        }
        return self::$instance;
    }

    /**
     * @throws ValidationException
     */
    public function validaStringa(string $stringa): void
    {
        if (!preg_match($this->STRING_PATTERN, $stringa)) {
            throw new ValidationException(ValidationException::ERROR_STRING_MESSAGE, ValidationException::ERROR_STRING_CODE);
        }
    }

    /**
     * @throws ValidationException
     */
    public function validaEmail(string $email): void
    {
        if (!preg_match($this->EMAIL_PATTERN, $email)) {
            if (strlen($email) <= 18) {
                throw new ValidationException(ValidationException::ERROR_EMAIL_LENGTH_MESSAGE, ValidationException::ERROR_EMAIL_LENGTH_CODE);
            } else {
                throw new ValidationException(ValidationException::ERROR_EMAIL_MESSAGE, ValidationException::ERROR_EMAIL_CODE);
            }
        }
    }

    /**
     * @throws ValidationException
     */
    public function validaPassword(string $password): void
    {
        if (!preg_match($this->PASSWORD_PATTERN, $password)) {
            if (strlen($password) < 8) {
                throw new ValidationException(ValidationException::ERROR_PASSWORD_LENGTH_MESSAGE, ValidationException::ERROR_PASSWORD_LENGTH_CODE);
            } else {
                throw new ValidationException(ValidationException::ERROR_PASSWORD_MESSAGE, ValidationException::ERROR_PASSWORD_CODE);
            }
        }
    }
}