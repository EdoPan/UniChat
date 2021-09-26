<?php
declare(strict_types=1);

class ValidationException extends Exception
{
    const ERROR_STRING_MESSAGE = "Il testo inserito presenta caratteri diversi da quelli alfabetici.";

    const ERROR_EMAIL_MESSAGE = "L'email fornita non è quella istituzionale.";

    const ERROR_EMAIL_LENGTH_MESSAGE = "L'email non è valida.";

    const ERROR_PASSWORD_MESSAGE = "La password inserita presenta dei caratteri non ammessi.";

    const ERROR_PASSWORD_LENGTH_MESSAGE = "La password deve essere di almeno 8 caratteri";

    const ERROR_STRING_CODE = 1;

    const ERROR_EMAIL_CODE = 2;

    const ERROR_EMAIL_LENGTH_CODE = 3;

    const ERROR_PASSWORD_CODE = 4;

    const ERROR_PASSWORD_LENGTH_CODE = 5;
}